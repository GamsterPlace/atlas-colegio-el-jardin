<?php

namespace App\Http\Controllers\Direccion;

use App\Http\Controllers\Controller;
use App\Models\CicloEscolar;
use App\Models\CuentaPorCobrar;
use App\Models\ConceptoCobro;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\RedirectResponse;

class CuentasPorCobrarController extends Controller
{
    private function consultaBase()
    {
        $pagos = DB::table('pagos')->where('estado', 'VALIDO')
            ->selectRaw('cuenta_por_cobrar_id, SUM(monto_aplicado_principal) as pagado_principal, SUM(monto_aplicado_mora) as pagado_mora')
            ->groupBy('cuenta_por_cobrar_id');
        $moras = DB::table('recargos_mora')->where('estado', 'VIGENTE')
            ->selectRaw('cuenta_por_cobrar_id, SUM(monto) as mora_generada')
            ->groupBy('cuenta_por_cobrar_id');

        return DB::table('cuentas_por_cobrar as c')
            ->join('estudiantes as e', 'e.id', '=', 'c.estudiante_id')
            ->join('conceptos_cobro as concepto', 'concepto.id', '=', 'c.concepto_cobro_id')
            ->join('ciclos_escolares as ciclo', 'ciclo.id', '=', 'c.ciclo_escolar_id')
            ->leftJoinSub($pagos, 'p', fn ($j) => $j->on('p.cuenta_por_cobrar_id', '=', 'c.id'))
            ->leftJoinSub($moras, 'm', fn ($j) => $j->on('m.cuenta_por_cobrar_id', '=', 'c.id'))
            ->whereNull('e.deleted_at')
            ->selectRaw('c.id, c.estudiante_id, c.ciclo_escolar_id, c.concepto_cobro_id, c.periodo_referencia, c.descripcion, c.fecha_vencimiento, c.fecha_emision, c.monto_base, c.monto_descuento, c.monto_exigible, c.estado_administrativo, c.motivo_anulacion, e.codigo, e.numero_carnet, e.nombres, e.apellidos, concepto.nombre as concepto, ciclo.nombre as ciclo, COALESCE(p.pagado_principal, 0) as pagado_principal, COALESCE(p.pagado_mora, 0) as pagado_mora, COALESCE(m.mora_generada, 0) as mora_generada');
    }

    private function completar($fila): object
    {
        $fila->saldo_principal = max(0, round((float) $fila->monto_exigible - (float) $fila->pagado_principal, 2));
        $fila->saldo_mora = max(0, round((float) $fila->mora_generada - (float) $fila->pagado_mora, 2));
        $fila->saldo_total = $fila->saldo_principal + $fila->saldo_mora;
        $fila->estado_calculado = $fila->estado_administrativo === 'ANULADA' ? 'ANULADA' :
            ($fila->saldo_total < 0.01 ? 'PAGADA' :
                ($fila->saldo_principal > 0.009 && $fila->fecha_vencimiento < now('America/Guatemala')->toDateString() ? 'VENCIDA' : 'PENDIENTE'));
        return $fila;
    }

    public function index(Request $request): View
    {
        $filtros = $request->validate([
            'ciclo_escolar_id' => ['nullable', 'integer', 'exists:ciclos_escolares,id'],
            'concepto_cobro_id' => ['nullable', 'integer', 'exists:conceptos_cobro,id'],
            'buscar' => ['nullable', 'string', 'max:120'],
            'estado' => ['nullable', 'in:PENDIENTE,VENCIDA,PAGADA,ANULADA,TODAS'],
        ]);
        $ciclos = CicloEscolar::query()->orderByDesc('fecha_inicio')->get();
        $conceptos = ConceptoCobro::query()->orderBy('nombre')->get();
        $cicloId = $request->filled('ciclo_escolar_id') ? (int) $request->input('ciclo_escolar_id') :
            (CicloEscolar::vigente()?->id ?? $ciclos->first()?->id);
        $estado = $request->input('estado', 'PENDIENTE');
        $buscar = trim((string) $request->input('buscar', ''));

        $consulta = $this->consultaBase()
            ->when($cicloId !== null, fn ($q) => $q->where('c.ciclo_escolar_id', $cicloId))
            ->when($request->filled('concepto_cobro_id'), fn ($q) => $q->where('c.concepto_cobro_id', $request->input('concepto_cobro_id')))
            ->when($buscar !== '', function ($q) use ($buscar) {
                $q->where(function ($sub) use ($buscar) {
                    $sub->where('e.codigo', 'like', "%{$buscar}%")
                        ->orWhere('e.numero_carnet', 'like', "%{$buscar}%")
                        ->orWhere('e.nombres', 'like', "%{$buscar}%")
                        ->orWhere('e.apellidos', 'like', "%{$buscar}%")
                        ->orWhereRaw("CONCAT(e.nombres, ' ', e.apellidos) LIKE ?", ["%{$buscar}%"]);
                });
            });
        $filas = $consulta->orderBy('c.fecha_vencimiento')->orderBy('e.apellidos')->get()
            ->map(fn ($fila) => $this->completar($fila));
        if ($estado !== 'TODAS') {
            $filas = $filas->filter(fn ($fila) => $fila->estado_calculado === $estado);
        }
        $total = $filas->count();
        $pagina = max(1, (int) $request->input('page', 1));
        $porPagina = 20;
        $obligaciones = new \Illuminate\Pagination\LengthAwarePaginator(
            $filas->forPage($pagina, $porPagina)->values(), $total, $porPagina, $pagina,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        $resumen = ['obligaciones' => $total, 'estudiantes' => $filas->pluck('estudiante_id')->unique()->count(),
            'principal' => $filas->sum('saldo_principal'), 'mora' => $filas->sum('saldo_mora')];
        return view('direccion.finanzas.cuentas.index', compact('ciclos', 'conceptos', 'cicloId', 'estado', 'buscar', 'obligaciones', 'resumen'));
    }

    public function show(CuentaPorCobrar $cuenta): View
    {
        $detalle = $this->consultaBase()->where('c.id', $cuenta->id)->first();
        abort_unless($detalle, 404);
        $detalle = $this->completar($detalle);
        $pagos = DB::table('pagos')->where('cuenta_por_cobrar_id', $cuenta->id)
            ->orderByDesc('fecha_registro')->get();
        $hojas = DB::table('hojas_talonario')->where('cuenta_por_cobrar_id', $cuenta->id)
            ->orderByDesc('id')->get();
        return view('direccion.finanzas.cuentas.show', compact('detalle', 'pagos', 'hojas'));
    }

    public function anular(Request $request, CuentaPorCobrar $cuenta): RedirectResponse
    {
        $datos = $request->validate(['motivo' => ['required', 'string', 'min:10', 'max:2000']]);
        DB::transaction(function () use ($cuenta, $datos) {
            $cuenta = CuentaPorCobrar::query()->lockForUpdate()->findOrFail($cuenta->id);
            if ($cuenta->estado_administrativo !== 'VIGENTE') {
                throw ValidationException::withMessages(['motivo' => 'La obligación ya está anulada.']);
            }
            if (DB::table('pagos')->where('cuenta_por_cobrar_id', $cuenta->id)->where('estado', 'VALIDO')->exists()) {
                throw ValidationException::withMessages(['motivo' => 'No se puede anular una obligación con pagos válidos.']);
            }
            if (DB::table('hojas_talonario')->where('cuenta_por_cobrar_id', $cuenta->id)->where('estado', 'VIGENTE')->exists()) {
                throw ValidationException::withMessages(['motivo' => 'Primero debe gestionarse la anulación de las hojas bancarias vigentes.']);
            }
            if (DB::table('recargos_mora')->where('cuenta_por_cobrar_id', $cuenta->id)->where('estado', 'VIGENTE')->exists()) {
                throw ValidationException::withMessages(['motivo' => 'La obligación tiene recargos de mora vigentes que deben resolverse antes.']);
            }
            $cuenta->estado_administrativo = 'ANULADA';
            $cuenta->motivo_anulacion = $datos['motivo'];
            $cuenta->anulada_por = Auth::id();
            $cuenta->anulada_at = now('America/Guatemala');
            $cuenta->save();
        });
        return redirect()->route('direccion.finanzas.cuentas.show', $cuenta->id)->with('success', 'Obligación anulada correctamente.');
    }
}
