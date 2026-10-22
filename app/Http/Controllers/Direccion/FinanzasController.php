<?php

namespace App\Http\Controllers\Direccion;

use App\Http\Controllers\Controller;
use App\Models\CicloEscolar;
use App\Models\Estudiante;
use App\Models\Pago;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanzasController extends Controller
{
    private function obligaciones(?int $cicloId = null)
    {
        $abonos = DB::table('pagos')
            ->where('estado', 'VALIDO')
            ->selectRaw('cuenta_por_cobrar_id, SUM(monto_aplicado_principal) AS principal_pagado, SUM(monto_aplicado_mora) AS mora_pagada')
            ->groupBy('cuenta_por_cobrar_id');

        $recargos = DB::table('recargos_mora')
            ->where('estado', 'VIGENTE')
            ->selectRaw('cuenta_por_cobrar_id, SUM(monto) AS mora_generada')
            ->groupBy('cuenta_por_cobrar_id');

        return DB::table('cuentas_por_cobrar as c')
            ->leftJoinSub($abonos, 'a', fn ($join) => $join->on('a.cuenta_por_cobrar_id', '=', 'c.id'))
            ->leftJoinSub($recargos, 'r', fn ($join) => $join->on('r.cuenta_por_cobrar_id', '=', 'c.id'))
            ->where('c.estado_administrativo', 'VIGENTE')
            ->when($cicloId !== null, fn ($q) => $q->where('c.ciclo_escolar_id', $cicloId))
            ->selectRaw('c.id, c.estudiante_id, c.ciclo_escolar_id, c.concepto_cobro_id, c.periodo_referencia, c.descripcion, c.fecha_emision, c.fecha_vencimiento, c.monto_exigible,
                COALESCE(a.principal_pagado,0) AS principal_pagado,
                COALESCE(a.mora_pagada,0) AS mora_pagada,
                COALESCE(r.mora_generada,0) AS mora_generada');
    }

    private function seleccionCiclo(Request $request): array
    {
        $request->validate(['ciclo_escolar_id' => ['nullable', 'integer', 'exists:ciclos_escolares,id']]);
        $ciclos = CicloEscolar::query()->orderByDesc('fecha_inicio')->get();
        $cicloId = $request->filled('ciclo_escolar_id')
            ? (int) $request->input('ciclo_escolar_id')
            : (CicloEscolar::vigente()?->id ?? $ciclos->first()?->id);

        return [$ciclos, $cicloId];
    }

    public function index(Request $request): View
    {
        [$ciclos, $cicloId] = $this->seleccionCiclo($request);
        $hoy = now('America/Guatemala')->toDateString();
        $inicioMes = now('America/Guatemala')->startOfMonth()->toDateString();
        $finMes = now('America/Guatemala')->endOfMonth()->toDateString();

        $obligaciones = $cicloId ? $this->obligaciones($cicloId)->get() : collect();
        $saldos = $obligaciones->map(function ($c) {
            $c->saldo_principal = max(0, round((float)$c->monto_exigible - (float)$c->principal_pagado, 2));
            $c->saldo_mora = max(0, round((float)$c->mora_generada - (float)$c->mora_pagada, 2));
            return $c;
        });

        $pendientes = $saldos->filter(fn ($c) => $c->saldo_principal + $c->saldo_mora > 0.009);
        $vencidas = $saldos->filter(fn ($c) => $c->saldo_principal > 0.009 && $c->fecha_vencimiento < $hoy);
        $ids = $saldos->pluck('id');

        $pagos = Pago::query()->where('estado', 'VALIDO')
            ->whereIn('cuenta_por_cobrar_id', $ids);

        $movimientos = (clone $pagos)
            ->with(['estudiante:id,nombres,apellidos,numero_carnet', 'cuenta_por_cobrar.concepto_cobro:id,nombre'])
            ->orderByDesc('fecha_registro')->orderByDesc('id')->limit(10)->get();

        return view('direccion.finanzas.index', [
            'ciclos' => $ciclos,
            'cicloId' => $cicloId,
            'estudiantesConCuenta' => $saldos->pluck('estudiante_id')->unique()->count(),
            'estudiantesDeudores' => $pendientes->pluck('estudiante_id')->unique()->count(),
            'obligacionesVigentes' => $saldos->count(),
            'obligacionesPendientes' => $pendientes->count(),
            'principal' => $saldos->sum('monto_exigible'),
            'principalPagado' => $saldos->sum('principal_pagado'),
            'saldoPrincipal' => $saldos->sum('saldo_principal'),
            'mora' => $saldos->sum('mora_generada'),
            'moraPagada' => $saldos->sum('mora_pagada'),
            'saldoMora' => $saldos->sum('saldo_mora'),
            'estudiantesVencidos' => $vencidas->pluck('estudiante_id')->unique()->count(),
            'obligacionesVencidas' => $vencidas->count(),
            'saldoVencido' => $vencidas->sum('saldo_principal'),
            'recaudacionMes' => (clone $pagos)->whereBetween('fecha_deposito', [$inicioMes, $finMes])->sum('monto'),
            'movimientos' => $movimientos,
            'mes' => now('America/Guatemala')->translatedFormat('F Y'),
        ]);
    }

    public function estadosCuenta(Request $request): View
    {
        $request->validate(['buscar' => ['nullable', 'string', 'max:100']]);
        $buscar = trim((string)$request->input('buscar', ''));

        $resumen = DB::query()->fromSub($this->obligaciones(), 'o')
            ->selectRaw('o.estudiante_id, SUM(o.monto_exigible) AS cargos, SUM(o.principal_pagado) AS abonos_principal, SUM(o.mora_generada) AS recargos, SUM(o.mora_pagada) AS abonos_mora')
            ->groupBy('o.estudiante_id');

        $estudiantes = Estudiante::query()
            ->joinSub($resumen, 'f', fn ($join) => $join->on('f.estudiante_id', '=', 'estudiantes.id'))
            ->when($buscar !== '', fn ($q) => $q->buscar($buscar))
            ->selectRaw('estudiantes.id, estudiantes.codigo, estudiantes.numero_carnet, estudiantes.nombres, estudiantes.apellidos, f.cargos, f.abonos_principal, f.recargos, f.abonos_mora')
            ->orderBy('estudiantes.apellidos')->orderBy('estudiantes.nombres')
            ->paginate(20)->withQueryString();

        return view('direccion.finanzas.estados-cuenta.index', compact('estudiantes', 'buscar'));
    }

    public function estadoCuenta(Request $request, Estudiante $estudiante): View
    {
        $request->validate(['ciclo_escolar_id' => ['nullable', 'integer', 'exists:ciclos_escolares,id']]);
        $cicloId = $request->filled('ciclo_escolar_id') ? (int)$request->input('ciclo_escolar_id') : null;
        $ciclos = CicloEscolar::query()->orderByDesc('fecha_inicio')->get();

        $obligaciones = $this->obligaciones($cicloId)
            ->where('c.estudiante_id', $estudiante->id)
            ->leftJoin('conceptos_cobro as concepto', 'concepto.id', '=', 'c.concepto_cobro_id')
            ->leftJoin('ciclos_escolares as ciclo', 'ciclo.id', '=', 'c.ciclo_escolar_id')
            ->addSelect('concepto.nombre as concepto_nombre', 'ciclo.nombre as ciclo_nombre')
            ->orderBy('c.fecha_vencimiento')->orderBy('c.id')->get();

        $obligaciones->each(function ($o) {
            $o->saldo_principal = max(0, round((float)$o->monto_exigible - (float)$o->principal_pagado, 2));
            $o->saldo_mora = max(0, round((float)$o->mora_generada - (float)$o->mora_pagada, 2));
        });

        $pagos = Pago::query()->where('estado', 'VALIDO')->where('estudiante_id', $estudiante->id)
            ->when($cicloId !== null, fn ($q) => $q->whereHas('cuenta_por_cobrar', fn ($c) => $c->where('ciclo_escolar_id', $cicloId)))
            ->with('cuenta_por_cobrar.concepto_cobro:id,nombre')
            ->orderByDesc('fecha_deposito')->orderByDesc('id')->paginate(20)->withQueryString();

        return view('direccion.finanzas.estados-cuenta.show', compact('estudiante', 'ciclos', 'cicloId', 'obligaciones', 'pagos'));
    }
}
