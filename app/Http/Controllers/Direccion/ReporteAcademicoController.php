<?php

namespace App\Http\Controllers\Direccion;

use App\Http\Controllers\Controller;
use App\Models\CicloEscolar;
use App\Models\Inscripcion;
use App\Models\Seccion;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReporteAcademicoController extends Controller
{
    public function index(Request $request): View
    {
        $filtros = $request->validate([
            'ciclo_escolar_id' => ['nullable', 'integer', 'exists:ciclos_escolares,id'],
            'seccion_id' => ['nullable', 'integer', 'exists:secciones,id'],
            'buscar' => ['nullable', 'string', 'max:100'],
            'estado' => ['nullable', Rule::in(['COMPLETADA', 'RETIRADA', 'ANULADA'])],
        ]);

        $ciclos = CicloEscolar::query()
            ->orderByDesc('fecha_inicio')
            ->get();

        $cicloActual = CicloEscolar::vigente();
        $cicloId = isset($filtros['ciclo_escolar_id'])
            ? (int) $filtros['ciclo_escolar_id']
            : ($cicloActual?->id ?? $ciclos->first()?->id);

        $secciones = Seccion::query()
            ->with('grado.nivel')
            ->when($cicloId, fn ($q) => $q->where('ciclo_escolar_id', $cicloId))
            ->orderBy('grado_id')
            ->orderBy('nombre')
            ->get();

        $seccionId = isset($filtros['seccion_id'])
            ? (int) $filtros['seccion_id']
            : null;

        if ($seccionId && !$secciones->contains('id', $seccionId)) {
            abort(422, 'La sección seleccionada no corresponde al ciclo escolar.');
        }

        $consulta = Inscripcion::query()
            ->with(['estudiante', 'seccion.grado.nivel', 'ciclo_escolar'])
            ->when($cicloId, fn ($q) => $q->where('ciclo_escolar_id', $cicloId))
            ->when($seccionId, fn ($q) => $q->where('seccion_id', $seccionId))
            ->when($filtros['estado'] ?? null, fn ($q, $estado) => $q->where('estado', $estado))
            ->when($filtros['buscar'] ?? null, fn ($q, $buscar) => $q->buscar($buscar));

        $totalInscripciones = (clone $consulta)->count();
        $totalEstudiantes = (clone $consulta)->distinct()->count('estudiante_id');
        $totalCompletadas = (clone $consulta)->where('estado', 'COMPLETADA')->count();

        $inscripciones = $consulta
            ->orderByDesc('fecha_inscripcion')
            ->orderByDesc('id')
            ->paginate(25)
            ->withQueryString();

        return view('direccion.reportes.academicos.index', compact(
            'filtros',
            'ciclos',
            'cicloId',
            'secciones',
            'inscripciones',
            'totalInscripciones',
            'totalEstudiantes',
            'totalCompletadas'
        ));
    }
}
