<?php

namespace App\Http\Controllers\Direccion;

use App\Http\Controllers\Controller;
use App\Models\CicloEscolar;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportesFinancierosController extends Controller
{
    private function filtros(Request $request): array
    {
        $request->validate([
            'ciclo_escolar_id' => ['nullable', 'integer', 'exists:ciclos_escolares,id'],
            'desde' => ['nullable', 'date'],
            'hasta' => ['nullable', 'date', 'after_or_equal:desde'],
        ]);
        $ciclos = CicloEscolar::query()->orderByDesc('fecha_inicio')->get();
        $cicloId = $request->filled('ciclo_escolar_id')
            ? (int) $request->input('ciclo_escolar_id')
            : (CicloEscolar::vigente()?->id ?? $ciclos->first()?->id);
        return [$ciclos, $cicloId, $request->input('desde'), $request->input('hasta')];
    }

    private function datos(int $cicloId, ?string $desde, ?string $hasta): array
    {
        // Las fechas filtran depósitos; la cartera corresponde al saldo actual del ciclo.
        $pagos = DB::table('pagos as p')
            ->join('cuentas_por_cobrar as c', 'c.id', '=', 'p.cuenta_por_cobrar_id')
            ->where('c.ciclo_escolar_id', $cicloId)
            ->where('p.estado', 'VALIDO')
            ->when($desde, fn ($q) => $q->whereDate('p.fecha_deposito', '>=', $desde))
            ->when($hasta, fn ($q) => $q->whereDate('p.fecha_deposito', '<=', $hasta));

        $recaudacion = (clone $pagos)->selectRaw('COUNT(*) as operaciones, COALESCE(SUM(p.monto),0) as total, COALESCE(SUM(p.monto_aplicado_principal),0) as principal, COALESCE(SUM(p.monto_aplicado_mora),0) as mora')->first();
        $porConcepto = (clone $pagos)
            ->join('conceptos_cobro as cc', 'cc.id', '=', 'c.concepto_cobro_id')
            ->selectRaw('cc.id, cc.nombre, COUNT(*) as operaciones, SUM(p.monto) as total')
            ->groupBy('cc.id', 'cc.nombre')->orderBy('cc.nombre')->get();

        $abonos = DB::table('pagos')->where('estado', 'VALIDO')
            ->selectRaw('cuenta_por_cobrar_id, SUM(monto_aplicado_principal) as principal, SUM(monto_aplicado_mora) as mora')
            ->groupBy('cuenta_por_cobrar_id');
        $recargos = DB::table('recargos_mora')->where('estado', 'VIGENTE')
            ->selectRaw('cuenta_por_cobrar_id, SUM(monto) as monto')
            ->groupBy('cuenta_por_cobrar_id');
        $cartera = DB::table('cuentas_por_cobrar as c')
            ->leftJoinSub($abonos, 'a', fn ($j) => $j->on('a.cuenta_por_cobrar_id', '=', 'c.id'))
            ->leftJoinSub($recargos, 'r', fn ($j) => $j->on('r.cuenta_por_cobrar_id', '=', 'c.id'))
            ->where('c.ciclo_escolar_id', $cicloId)->where('c.estado_administrativo', 'VIGENTE')
            ->selectRaw('c.id, c.estudiante_id, c.fecha_vencimiento, c.monto_exigible, COALESCE(a.principal,0) as principal_pagado, COALESCE(a.mora,0) as mora_pagada, COALESCE(r.monto,0) as mora_generada')
            ->get();
        $hoy = now('America/Guatemala')->toDateString();
        $principalPendiente = 0; $moraPendiente = 0; $vencido = 0; $deudores = []; $obligacionesPendientes = 0;
        foreach ($cartera as $c) {
            $principal = max(0, round((float) $c->monto_exigible - (float) $c->principal_pagado, 2));
            $mora = max(0, round((float) $c->mora_generada - (float) $c->mora_pagada, 2));
            $principalPendiente += $principal; $moraPendiente += $mora;
            if ($principal + $mora > 0.009) {
                $obligacionesPendientes++;
                $deudores[$c->estudiante_id] = true;
            }
            if ($principal > 0.009 && $c->fecha_vencimiento < $hoy) $vencido += $principal;
        }
        return compact('recaudacion', 'porConcepto', 'principalPendiente', 'moraPendiente', 'vencido', 'deudores', 'obligacionesPendientes');
    }

    public function index(Request $request): View
    {
        [$ciclos, $cicloId, $desde, $hasta] = $this->filtros($request);
        $datos = $cicloId ? $this->datos($cicloId, $desde, $hasta) : null;
        return view('direccion.finanzas.reportes.index', compact('ciclos', 'cicloId', 'desde', 'hasta', 'datos'));
    }

    public function exportar(Request $request): StreamedResponse
    {
        [, $cicloId, $desde, $hasta] = $this->filtros($request);
        abort_unless($cicloId, 404, 'No hay ciclo escolar para exportar.');
        $datos = $this->datos($cicloId, $desde, $hasta);
        return response()->streamDownload(function () use ($datos, $cicloId, $desde, $hasta) {
            $f = fopen('php://output', 'w');
            fwrite($f, "\xEF\xBB\xBF");
            fputcsv($f, ['Reporte financiero ATLAS', 'Ciclo ID', $cicloId]);
            fputcsv($f, ['Depósitos desde', $desde ?: 'Sin límite', 'hasta', $hasta ?: 'Sin límite']);
            fputcsv($f, ['Recaudación válida', number_format((float) $datos['recaudacion']->total, 2, '.', '')]);
            fputcsv($f, ['Principal pendiente actual', number_format($datos['principalPendiente'], 2, '.', '')]);
            fputcsv($f, ['Mora pendiente actual', number_format($datos['moraPendiente'], 2, '.', '')]);
            fputcsv($f, ['Principal vencido actual', number_format($datos['vencido'], 2, '.', '')]);
            fputcsv($f, []);
            fputcsv($f, ['Concepto', 'Operaciones válidas', 'Recaudación']);
            foreach ($datos['porConcepto'] as $fila) {
                fputcsv($f, [$fila->nombre, $fila->operaciones, number_format((float) $fila->total, 2, '.', '')]);
            }
            fclose($f);
        }, 'ATLAS_reporte_financiero_'.now('America/Guatemala')->format('Ymd_His').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
