<?php

namespace App\Http\Controllers\Direccion;

use App\Http\Controllers\Controller;
use App\Models\CicloEscolar;
use App\Models\Estudiante;
use App\Models\Inscripcion;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EstudianteController extends Controller
{
    public function index(Request $request): View
    {
        $filtros = $request->validate([
            'buscar' => ['nullable', 'string', 'max:120'],
            'estado' => ['nullable', Rule::in(['ACTIVO', 'INACTIVO'])],
            'ciclo_escolar_id' => ['nullable', 'integer', 'exists:ciclos_escolares,id'],
        ]);

        $estudiantes = Estudiante::query()
            ->buscar($filtros['buscar'] ?? null)
            ->when($filtros['estado'] ?? null, fn ($q, $estado) => $q->where('estado', $estado))
            ->when($filtros['ciclo_escolar_id'] ?? null, fn ($q, $id) =>
                $q->whereHas('registrosInscripcion', fn ($ins) => $ins->delCiclo((int) $id))
            )
            ->withCount('registrosInscripcion')
            ->orderBy('apellidos')->orderBy('nombres')->paginate(15)->withQueryString();

        $ciclos = CicloEscolar::query()->orderByDesc('fecha_inicio')->get();

        return view('direccion.estudiantes.index', compact('estudiantes', 'ciclos', 'filtros'));
    }

    public function expedientes(Request $request): View
    {
        $filtros = $request->validate(['buscar' => ['nullable', 'string', 'max:120']]);
        $estudiantes = Estudiante::query()
            ->buscar($filtros['buscar'] ?? null)
            ->orderBy('apellidos')->orderBy('nombres')->paginate(15)->withQueryString();

        return view('direccion.estudiantes.expedientes.index', compact('estudiantes', 'filtros'));
    }

    public function expediente(Estudiante $estudiante): View
    {
        $estudiante->load([
            'registrosInformacionMedicaEstudiante',
            'registrosInformacionComplementariaEstudiante',
            'registrosEstudianteResponsable.responsable.persona',
            'registrosContactoEmergencia',
            'registrosPersonaAutorizadaRetiro',
            'registrosDocumentoEstudiante',
            'registrosInscripcion.ciclo_escolar',
            'registrosInscripcion.seccion.grado',
            'registrosInscripcion.seccion.jornada',
        ]);

        return view('direccion.estudiantes.expedientes.show', compact('estudiante'));
    }

    public function control(Request $request): View
    {
        $filtros = $request->validate([
            'ciclo_escolar_id' => ['nullable', 'integer', 'exists:ciclos_escolares,id'],
        ]);
        $ciclos = CicloEscolar::query()->orderByDesc('fecha_inicio')->get();
        $cicloId = $filtros['ciclo_escolar_id']
            ?? $ciclos->firstWhere('estado', 'ACTIVO')?->id
            ?? $ciclos->first()?->id;

        $inscripciones = $cicloId
            ? Inscripcion::query()->delCiclo((int) $cicloId)->completadas()
                ->with(['estudiante', 'seccion.grado', 'seccion.jornada'])
                ->get()
            : collect();

        $grupos = $inscripciones->groupBy('seccion_id')->map(function ($registros) {
            $seccion = $registros->first()->seccion;
            return [
                'grado' => $seccion?->grado?->nombre ?? 'Sin grado',
                'seccion' => $seccion?->nombre ?? 'Sin sección',
                'jornada' => $seccion?->jornada?->nombre ?? 'Sin jornada',
                'total' => $registros->count(),
                'estudiantes' => $registros->pluck('estudiante')->filter()->sortBy('apellidos'),
            ];
        })->sortBy(fn ($grupo) => $grupo['grado'].' '.$grupo['seccion']);

        return view('direccion.estudiantes.control.index', compact('ciclos', 'cicloId', 'inscripciones', 'grupos'));
    }
}
