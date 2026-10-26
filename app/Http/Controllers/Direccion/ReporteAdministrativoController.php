<?php

namespace App\Http\Controllers\Direccion;

use App\Http\Controllers\Controller;
use App\Models\AsistenciaPersonal;
use App\Models\BitacoraAuditoria;
use App\Models\CicloEscolar;
use App\Models\CuentaPorCobrar;
use App\Models\Inscripcion;
use App\Models\MovimientoInventario;
use App\Models\Pago;
use App\Models\Personal;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReporteAdministrativoController extends Controller
{
    public function index(Request $request): View
    {
        $filtros = $request->validate([
            'pestana' => ['nullable', Rule::in(['inscripciones', 'finanzas', 'personal', 'inventario', 'auditoria'])],
            'ciclo_escolar_id' => ['nullable', 'integer', 'exists:ciclos_escolares,id'],
            'desde' => ['nullable', 'date'],
            'hasta' => ['nullable', 'date', 'after_or_equal:desde'],
            'buscar' => ['nullable', 'string', 'max:100'],
        ]);

        $pestana = $filtros['pestana'] ?? 'inscripciones';
        $ciclos = CicloEscolar::query()->orderByDesc('fecha_inicio')->get();
        $cicloId = isset($filtros['ciclo_escolar_id'])
            ? (int) $filtros['ciclo_escolar_id']
            : (CicloEscolar::vigente()?->id ?? $ciclos->first()?->id);

        $desde = $filtros['desde'] ?? null;
        $hasta = $filtros['hasta'] ?? null;
        $buscar = trim($filtros['buscar'] ?? '');
        $registros = null;
        $indicadores = [];

        switch ($pestana) {
            case 'inscripciones':
                $consulta = Inscripcion::query()
                    ->with(['estudiante', 'seccion.grado.nivel'])
                    ->when($cicloId, fn ($q) => $q->where('ciclo_escolar_id', $cicloId))
                    ->when($desde, fn ($q) => $q->whereDate('fecha_inscripcion', '>=', $desde))
                    ->when($hasta, fn ($q) => $q->whereDate('fecha_inscripcion', '<=', $hasta))
                    ->when($buscar !== '', fn ($q) => $q->buscar($buscar));
                $indicadores = [
                    'Total de inscripciones' => (clone $consulta)->count(),
                    'Completadas' => (clone $consulta)->where('estado', 'COMPLETADA')->count(),
                ];
                $registros = $consulta->orderByDesc('fecha_inscripcion')->orderByDesc('id')
                    ->paginate(25)->withQueryString();
                break;

            case 'finanzas':
                $consulta = CuentaPorCobrar::query()
                    ->with(['estudiante', 'concepto_cobro'])
                    ->when($cicloId, fn ($q) => $q->where('ciclo_escolar_id', $cicloId))
                    ->when($desde, fn ($q) => $q->whereDate('fecha_emision', '>=', $desde))
                    ->when($hasta, fn ($q) => $q->whereDate('fecha_emision', '<=', $hasta))
                    ->when($buscar !== '', fn ($q) => $q->whereHas('estudiante', fn ($e) => $e->buscar($buscar)));
                $indicadores = [
                    'Cuentas registradas' => (clone $consulta)->count(),
                    'Monto exigible registrado' => (float) (clone $consulta)
                        ->whereNull('anulada_at')->sum('monto_exigible'),
                ];
                $registros = $consulta->orderByDesc('fecha_emision')->orderByDesc('id')
                    ->paginate(25)->withQueryString();
                break;

            case 'personal':
                $consulta = AsistenciaPersonal::query()
                    ->with('personal.persona')
                    ->when($desde, fn ($q) => $q->whereDate('fecha', '>=', $desde))
                    ->when($hasta, fn ($q) => $q->whereDate('fecha', '<=', $hasta))
                    ->when($buscar !== '', fn ($q) => $q->whereHas('personal', fn ($p) => $p->where('codigo_empleado', 'like', "%{$buscar}%")));
                $indicadores = [
                    'Registros de asistencia' => (clone $consulta)->count(),
                    'Personal registrado' => Personal::query()->count(),
                ];
                $registros = $consulta->orderByDesc('fecha')->orderByDesc('id')
                    ->paginate(25)->withQueryString();
                break;

            case 'inventario':
                $consulta = MovimientoInventario::query()
                    ->with('producto_uniforme')
                    ->when($desde, fn ($q) => $q->whereDate('fecha_movimiento', '>=', $desde))
                    ->when($hasta, fn ($q) => $q->whereDate('fecha_movimiento', '<=', $hasta))
                    ->when($buscar !== '', fn ($q) => $q->whereHas('producto_uniforme', fn ($p) => $p->where('nombre', 'like', "%{$buscar}%")));
                $indicadores = ['Movimientos registrados' => (clone $consulta)->count()];
                $registros = $consulta->orderByDesc('fecha_movimiento')->orderByDesc('id')
                    ->paginate(25)->withQueryString();
                break;

            case 'auditoria':
                $consulta = BitacoraAuditoria::query()
                    ->with('usuario')
                    ->when($desde, fn ($q) => $q->whereDate('fecha_evento', '>=', $desde))
                    ->when($hasta, fn ($q) => $q->whereDate('fecha_evento', '<=', $hasta))
                    ->when($buscar !== '', fn ($q) => $q->where(function ($w) use ($buscar) {
                        $w->where('accion', 'like', "%{$buscar}%")
                            ->orWhere('entidad', 'like', "%{$buscar}%");
                    }));
                $indicadores = ['Eventos de auditoría' => (clone $consulta)->count()];
                $registros = $consulta->orderByDesc('fecha_evento')->orderByDesc('id')
                    ->paginate(25)->withQueryString();
                break;
        }

        return view('direccion.reportes.administrativos.index', compact(
            'filtros', 'pestana', 'ciclos', 'cicloId', 'registros', 'indicadores'
        ));
    }
}
