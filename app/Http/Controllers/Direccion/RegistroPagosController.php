<?php

namespace App\Http\Controllers\Direccion;

use App\Http\Controllers\Controller;
use App\Models\CicloEscolar;
use App\Models\CuentaPorCobrar;
use App\Models\HojaTalonario;
use App\Models\Pago;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RegistroPagosController extends Controller
{
    public function index(Request $request): View
    {
        $request->validate([
            'codigo' => ['nullable', 'string', 'max:100'],
            'buscar' => ['nullable', 'string', 'max:120'],
            'ciclo_escolar_id' => ['nullable', 'integer', 'exists:ciclos_escolares,id'],
            'estado' => ['nullable', 'in:VALIDO,ANULADO,TODOS'],
        ]);

        $codigo = trim((string) $request->input('codigo', ''));
        $buscar = trim((string) $request->input('buscar', ''));
        $estado = $request->input('estado', 'TODOS');
        $ciclos = CicloEscolar::query()->orderByDesc('fecha_inicio')->get();
        $cicloId = $request->filled('ciclo_escolar_id') ? (int) $request->input('ciclo_escolar_id') : null;

        $hoja = $codigo !== '' ? HojaTalonario::query()
            ->with(['cuenta_por_cobrar.estudiante', 'cuenta_por_cobrar.concepto_cobro', 'autorizacion_detalle.autorizacion'])
            ->where('codigo', $codigo)->first() : null;

        $resumenHoja = null;
        if ($hoja && $hoja->cuenta_por_cobrar) {
            $cuenta = $hoja->cuenta_por_cobrar;
            $pagos = Pago::query()->where('cuenta_por_cobrar_id', $cuenta->id)->where('estado', 'VALIDO');
            $principalPagado = (float) (clone $pagos)->sum('monto_aplicado_principal');
            $moraPagada = (float) (clone $pagos)->sum('monto_aplicado_mora');
            $moraGenerada = (float) DB::table('recargos_mora')->where('cuenta_por_cobrar_id', $cuenta->id)
                ->where('estado', 'VIGENTE')->sum('monto');
            $resumenHoja = [
                'principal' => max(0, round((float) $cuenta->monto_exigible - $principalPagado, 2)),
                'mora' => max(0, round($moraGenerada - $moraPagada, 2)),
                'ya_pagada' => (clone $pagos)->where('hoja_talonario_id', $hoja->id)->exists(),
            ];
        }

        $historial = Pago::query()
            ->with(['estudiante', 'hoja_talonario', 'cuenta_por_cobrar.concepto_cobro', 'cuenta_por_cobrar.ciclo_escolar'])
            ->when($cicloId !== null, fn ($q) => $q->whereHas('cuenta_por_cobrar', fn ($c) => $c->where('ciclo_escolar_id', $cicloId)))
            ->when($estado !== 'TODOS', fn ($q) => $q->where('estado', $estado))
            ->when($buscar !== '', function ($q) use ($buscar) {
                $q->where(function ($s) use ($buscar) {
                    $s->where('referencia_bancaria', 'like', "%{$buscar}%")
                        ->orWhereHas('estudiante', fn ($e) => $e->buscar($buscar))
                        ->orWhereHas('hoja_talonario', fn ($h) => $h->where('codigo', 'like', "%{$buscar}%"));
                });
            })
            ->orderByDesc('fecha_registro')->orderByDesc('id')->paginate(20)->withQueryString();

        return view('direccion.finanzas.pagos.index', compact('codigo', 'buscar', 'estado', 'ciclos', 'cicloId', 'hoja', 'resumenHoja', 'historial'));
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'codigo' => ['required', 'string', 'max:100'],
            'fecha_deposito' => ['required', 'date', 'before_or_equal:today'],
            'banco' => ['required', 'string', 'max:120'],
            'referencia_bancaria' => ['required', 'string', 'max:120'],
            'observaciones' => ['nullable', 'string', 'max:2000'],
        ]);
        $datos['codigo'] = trim($datos['codigo']);
        $datos['referencia_bancaria'] = trim($datos['referencia_bancaria']);

        DB::transaction(function () use ($datos) {
            $hoja = HojaTalonario::query()->where('codigo', $datos['codigo'])->lockForUpdate()->first();
            if (!$hoja) {
                throw ValidationException::withMessages(['codigo' => 'No existe una hoja de talonario con ese código.']);
            }
            $cuenta = CuentaPorCobrar::query()->whereKey($hoja->cuenta_por_cobrar_id)->lockForUpdate()->firstOrFail();
            if ($hoja->estado !== 'VIGENTE' || $cuenta->estado_administrativo !== 'VIGENTE') {
                throw ValidationException::withMessages(['codigo' => 'La hoja o la obligación no está vigente.']);
            }
            if (Pago::query()->where('hoja_talonario_id', $hoja->id)->where('estado', 'VALIDO')->exists()) {
                throw ValidationException::withMessages(['codigo' => 'Esta boleta ya tiene un pago válido registrado.']);
            }
            if ($hoja->tipo === 'EXTRAORDINARIA') {
                $detalle = $hoja->autorizacion_detalle()->with('autorizacion')->first();
                if (!$detalle || !$detalle->autorizacion || $detalle->autorizacion->cuenta_por_cobrar_id !== $cuenta->id ||
                    !in_array($detalle->autorizacion->estado, ['AUTORIZADA', 'COMPLETADA'], true) ||
                    abs((float) $detalle->monto - (float) $hoja->monto_impreso) > 0.009) {
                    throw ValidationException::withMessages(['codigo' => 'La boleta extraordinaria no tiene una autorización válida.']);
                }
            } elseif ($hoja->tipo === 'ORDINARIA' || $hoja->tipo === 'SUSTITUTIVA') {
                if (DB::table('hojas_talonario')->where('cuenta_por_cobrar_id', $cuenta->id)
                    ->where('tipo', 'EXTRAORDINARIA')->where('estado', 'VIGENTE')->exists()) {
                    throw ValidationException::withMessages(['codigo' => 'Esta obligación dispone de hojas extraordinarias; no puede pagarse con una boleta ordinaria.']);
                }
            } else {
                throw ValidationException::withMessages(['codigo' => 'Tipo de boleta no reconocido.']);
            }

            $montoCentavos = (int) round((float) $hoja->monto_impreso * 100);
            $principalPagado = (int) round((float) Pago::query()->where('cuenta_por_cobrar_id', $cuenta->id)
                ->where('estado', 'VALIDO')->sum('monto_aplicado_principal') * 100);
            $moraPagada = (int) round((float) Pago::query()->where('cuenta_por_cobrar_id', $cuenta->id)
                ->where('estado', 'VALIDO')->sum('monto_aplicado_mora') * 100);
            $principalPendiente = max(0, (int) round((float) $cuenta->monto_exigible * 100) - $principalPagado);
            $moraPendiente = max(0, (int) round((float) DB::table('recargos_mora')->where('cuenta_por_cobrar_id', $cuenta->id)
                ->where('estado', 'VIGENTE')->sum('monto') * 100) - $moraPagada);

            if ($montoCentavos <= 0 || $montoCentavos > $principalPendiente + $moraPendiente) {
                throw ValidationException::withMessages(['codigo' => 'El monto impreso supera el saldo pendiente o es inválido.']);
            }
            if ($hoja->tipo !== 'EXTRAORDINARIA' && $montoCentavos !== $principalPendiente + $moraPendiente) {
                throw ValidationException::withMessages(['codigo' => 'El importe de la boleta no coincide con el saldo actual. Debe revisarse antes de registrar el pago.']);
            }
            $aplicadoPrincipal = min($montoCentavos, $principalPendiente);
            $aplicadoMora = $montoCentavos - $aplicadoPrincipal;
            if ($aplicadoMora > $moraPendiente) {
                throw ValidationException::withMessages(['codigo' => 'El pago supera el saldo de mora disponible.']);
            }
            Pago::query()->create([
                'hoja_talonario_id' => $hoja->id,
                'estudiante_id' => $cuenta->estudiante_id,
                'cuenta_por_cobrar_id' => $cuenta->id,
                'fecha_deposito' => $datos['fecha_deposito'],
                'fecha_registro' => now('America/Guatemala'),
                'monto' => number_format($montoCentavos / 100, 2, '.', ''),
                'monto_aplicado_principal' => number_format($aplicadoPrincipal / 100, 2, '.', ''),
                'monto_aplicado_mora' => number_format($aplicadoMora / 100, 2, '.', ''),
                'banco' => $datos['banco'],
                'referencia_bancaria' => $datos['referencia_bancaria'],
                'registrado_por' => Auth::id(),
                'estado' => 'VALIDO',
                'observaciones' => $datos['observaciones'] ?? null,
            ]);
        }, 3);

        return redirect()->route('direccion.finanzas.pagos.index')->with('success', 'Pago registrado correctamente.');
    }

    public function anular(Request $request, Pago $pago): RedirectResponse
    {
        $datos = $request->validate(['motivo' => ['required', 'string', 'min:10', 'max:2000']]);
        DB::transaction(function () use ($pago, $datos) {
            $cuenta = CuentaPorCobrar::query()->whereKey($pago->cuenta_por_cobrar_id)->lockForUpdate()->firstOrFail();
            $pago = Pago::query()->whereKey($pago->id)->lockForUpdate()->firstOrFail();
            if ($pago->estado !== 'VALIDO') {
                throw ValidationException::withMessages(['motivo' => 'El pago ya fue anulado.']);
            }
            $pago->estado = 'ANULADO';
            $pago->anulado_por = Auth::id();
            $pago->anulado_at = now('America/Guatemala');
            $pago->motivo_anulacion = $datos['motivo'];
            $pago->save();
        }, 3);
        return back()->with('success', 'Pago anulado. La operación permanece en el historial.');
    }
}
