<?php

namespace App\Http\Controllers\Direccion;

use App\Http\Controllers\Controller;
use App\Models\CuentaPorCobrar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class BeneficioFinancieroController extends Controller
{
    public function index(Request $request): View
    {
        $request->validate(['buscar' => ['nullable', 'string', 'max:100']]);
        $buscar = trim((string) $request->query('buscar', ''));

        $obligaciones = CuentaPorCobrar::query()
            ->with(['estudiante', 'concepto_cobro', 'ciclo_escolar'])
            ->where('estado_administrativo', 'VIGENTE')
            ->when($buscar !== '', function ($query) use ($buscar) {
                $query->whereHas('estudiante', function ($estudiante) use ($buscar) {
                    $estudiante->where(function ($q) use ($buscar) {
                        $q->where('nombres', 'like', "%{$buscar}%")
                            ->orWhere('apellidos', 'like', "%{$buscar}%")
                            ->orWhere('numero_carnet', 'like', "%{$buscar}%");
                    });
                });
            })
            ->orderByDesc('fecha_vencimiento')
            ->paginate(15)
            ->withQueryString();

        $beneficios = DB::table('beneficios_financieros_individuales as b')
            ->join('cuentas_por_cobrar as c', 'c.id', '=', 'b.cuenta_por_cobrar_id')
            ->join('estudiantes as e', 'e.id', '=', 'c.estudiante_id')
            ->select('b.*', 'e.nombres', 'e.apellidos', 'c.periodo_referencia')
            ->orderByDesc('b.id')->limit(20)->get();

        return view('direccion.finanzas.beneficios.index', compact('obligaciones', 'beneficios', 'buscar'));
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'cuenta_por_cobrar_id' => ['required', 'integer', 'exists:cuentas_por_cobrar,id'],
            'tipo' => ['required', 'in:DESCUENTO_FIJO,DESCUENTO_PORCENTAJE,TARIFA_ESPECIAL'],
            'valor' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'motivo' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        DB::transaction(function () use ($datos) {
            $cuenta = CuentaPorCobrar::query()->lockForUpdate()->findOrFail($datos['cuenta_por_cobrar_id']);

            if ($cuenta->estado_administrativo !== 'VIGENTE') {
                throw ValidationException::withMessages(['cuenta_por_cobrar_id' => 'La obligación no está vigente.']);
            }
            if (DB::table('beneficios_financieros_individuales')->where('cuenta_por_cobrar_id', $cuenta->id)->exists()) {
                throw ValidationException::withMessages(['cuenta_por_cobrar_id' => 'Esta obligación ya tiene un beneficio autorizado.']);
            }
            if (DB::table('pagos')->where('cuenta_por_cobrar_id', $cuenta->id)->exists()) {
                throw ValidationException::withMessages(['cuenta_por_cobrar_id' => 'No se puede modificar una obligación que ya registra pagos, incluso anulados.']);
            }
            if (DB::table('hojas_talonario')->where('cuenta_por_cobrar_id', $cuenta->id)->exists()) {
                throw ValidationException::withMessages(['cuenta_por_cobrar_id' => 'Esta obligación tiene una hoja de talonario emitida. Se requiere un procedimiento de sustitución antes de modificar el importe.']);
            }
            if (DB::table('recargos_mora')->where('cuenta_por_cobrar_id', $cuenta->id)->exists()) {
                throw ValidationException::withMessages(['cuenta_por_cobrar_id' => 'Esta obligación ya tiene historial de mora; requiere revisión individual.']);
            }

            $anterior = round((float) $cuenta->monto_exigible, 2);
            $valor = round((float) $datos['valor'], 2);
            if ($datos['tipo'] === 'DESCUENTO_PORCENTAJE' && $valor > 100) {
                throw ValidationException::withMessages(['valor' => 'El porcentaje no puede superar el 100%.']);
            }
            if ($datos['tipo'] === 'TARIFA_ESPECIAL') {
                $nuevo = $valor;
            } elseif ($datos['tipo'] === 'DESCUENTO_PORCENTAJE') {
                $nuevo = round($anterior * (1 - $valor / 100), 2);
            } else {
                $nuevo = round($anterior - $valor, 2);
            }
            if ($nuevo < 0 || $nuevo >= $anterior) {
                throw ValidationException::withMessages(['valor' => 'El beneficio debe reducir el importe sin generar un monto negativo.']);
            }

            DB::table('beneficios_financieros_individuales')->insert([
                'cuenta_por_cobrar_id' => $cuenta->id,
                'tipo' => $datos['tipo'],
                'valor' => $valor,
                'monto_anterior' => $anterior,
                'monto_nuevo' => $nuevo,
                'motivo' => $datos['motivo'],
                'autorizado_por' => auth::id(),
                'autorizado_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $cuenta->monto_descuento = round((float) $cuenta->monto_descuento + ($anterior - $nuevo), 2);
            $cuenta->monto_exigible = $nuevo;
            $cuenta->save();
        }, 3);

        return redirect()->route('direccion.finanzas.beneficios.index')
            ->with('success', 'Beneficio individual autorizado y aplicado a una sola obligación.');
    }
}
