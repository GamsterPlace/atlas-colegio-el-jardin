<?php

namespace App\Http\Controllers\Direccion;

use App\Http\Controllers\Controller;
use App\Models\CicloEscolar;
use App\Models\Grado;
use App\Models\HistorialSeccion;
use App\Models\Inscripcion;
use App\Models\RetiroEstudiante;
use App\Models\Seccion;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class ControlInscripcionController extends Controller
{
    public function index(Request $request): View
    {
        $filtros = $request->validate([
            'buscar' => ['nullable', 'string', 'max:120'],
            'ciclo_escolar_id' => ['nullable', 'integer', 'exists:ciclos_escolares,id'],
            'grado_id' => ['nullable', 'integer', 'exists:grados,id'],
            'seccion_id' => ['nullable', 'integer', 'exists:secciones,id'],
            'estado' => ['nullable', 'string', 'max:40'],
        ]);

        $ciclos = CicloEscolar::query()->orderByDesc('fecha_inicio')->get();
        $cicloId = (int) ($filtros['ciclo_escolar_id'] ?? $ciclos->firstWhere('estado', 'ACTIVO')?->id ?? $ciclos->first()?->id ?? 0);
        $base = Inscripcion::query()->when($cicloId, fn ($q) => $q->delCiclo($cicloId));
        $resumen = [
            'total' => (clone $base)->count(),
            'completadas' => (clone $base)->where('estado', 'COMPLETADA')->count(),
            'vigentes' => (clone $base)->vigentes()->count(),
            'retiros' => RetiroEstudiante::query()->whereHas('inscripcion', fn ($q) => $q->when($cicloId, fn ($c) => $c->delCiclo($cicloId)))->count(),
        ];
        $estados = (clone $base)->distinct()->orderBy('estado')->pluck('estado')->filter();
        $grados = Grado::query()->orderBy('nombre')->get();
        $secciones = Seccion::query()->when($cicloId, fn ($q) => $q->where('ciclo_escolar_id', $cicloId))
            ->with('grado')->orderBy('nombre')->get();

        $inscripciones = (clone $base)->buscar($filtros['buscar'] ?? null)
            ->when($filtros['grado_id'] ?? null, fn ($q, $id) => $q->whereHas('seccion', fn ($s) => $s->where('grado_id', $id)))
            ->when($filtros['seccion_id'] ?? null, fn ($q, $id) => $q->where('seccion_id', $id))
            ->when($filtros['estado'] ?? null, fn ($q, $estado) => $q->where('estado', $estado))
            ->with(['estudiante', 'seccion.grado', 'seccion.jornada', 'ciclo_escolar'])
            ->withCount(['registrosRetiroEstudiante', 'registrosHistorialSeccion'])
            ->orderByDesc('fecha_inscripcion')->orderByDesc('id')->paginate(15)->withQueryString();

        return view('direccion.inscripciones.control.index', compact('inscripciones', 'filtros', 'ciclos', 'cicloId', 'grados', 'secciones', 'estados', 'resumen'));
    }

    public function show(Inscripcion $inscripcion): View
    {
        $inscripcion->load([
            'estudiante', 'ciclo_escolar', 'seccion.grado', 'seccion.jornada',
            'registrosHistorialSeccion.seccion_anterior.grado',
            'registrosHistorialSeccion.seccion_nueva.grado',
            'registrosRetiroEstudiante',
        ]);
        $historialInscripciones = Inscripcion::query()->where('estudiante_id', $inscripcion->estudiante_id)
            ->with(['ciclo_escolar', 'seccion.grado'])->orderByDesc('fecha_inscripcion')->get();
        $seccionesDestino = Seccion::query()->where('ciclo_escolar_id', $inscripcion->ciclo_escolar_id)
            ->where('grado_id', $inscripcion->seccion?->grado_id)
            ->where('id', '!=', $inscripcion->seccion_id)
            ->where('activo', true)->with('grado')->orderBy('nombre')->get();

        return view('direccion.inscripciones.control.show', compact('inscripcion', 'historialInscripciones', 'seccionesDestino'));
    }

    public function trasladar(Request $request, Inscripcion $inscripcion): RedirectResponse
    {
        $datos = $request->validate([
            'seccion_nueva_id' => ['required', 'integer', 'exists:secciones,id'],
            'fecha_traslado' => ['required', 'date', 'before_or_equal:today'],
            'motivo' => ['required', 'string', 'min:8', 'max:1000'],
        ]);

        DB::transaction(function () use ($inscripcion, $datos) {
            $actual = Inscripcion::query()->lockForUpdate()->findOrFail($inscripcion->id);
            if ($actual->estado !== 'COMPLETADA' || $actual->fecha_fin || $actual->registrosRetiroEstudiante()->exists()) {
                throw ValidationException::withMessages(['seccion_nueva_id' => 'Solo se pueden trasladar inscripciones activas sin retiro.']);
            }
            $destino = Seccion::query()->whereKey($datos['seccion_nueva_id'])->where('activo', true)
                ->where('ciclo_escolar_id', $actual->ciclo_escolar_id)
                ->where('grado_id', $actual->seccion?->grado_id)->first();
            if (!$destino || $destino->id === $actual->seccion_id) {
                throw ValidationException::withMessages(['seccion_nueva_id' => 'La sección debe ser distinta y pertenecer al mismo grado y ciclo escolar.']);
            }
            if ($actual->fecha_inicio && $datos['fecha_traslado'] < $actual->fecha_inicio->toDateString()) {
                throw ValidationException::withMessages(['fecha_traslado' => 'La fecha no puede ser anterior al inicio de la inscripción.']);
            }
            $ocupacion = Inscripcion::query()->where('seccion_id', $destino->id)->where('estado', 'COMPLETADA')
                ->whereNull('fecha_fin')->count();
            if ($destino->cupo !== null && $ocupacion >= $destino->cupo) {
                throw ValidationException::withMessages(['seccion_nueva_id' => 'La sección seleccionada no tiene cupos disponibles.']);
            }
            HistorialSeccion::query()->create([
                'inscripcion_id' => $actual->id,
                'seccion_anterior_id' => $actual->seccion_id,
                'seccion_nueva_id' => $destino->id,
                'fecha_traslado' => $datos['fecha_traslado'],
                'motivo' => $datos['motivo'],
                'registrado_por' => auth::id(),
            ]);
            $actual->update(['seccion_id' => $destino->id]);
        });
        return back()->with('success', 'El traslado de sección se registró correctamente.');
    }

    public function retirar(Request $request, Inscripcion $inscripcion): RedirectResponse
    {
        $datos = $request->validate([
            'fecha_retiro' => ['required', 'date', 'before_or_equal:today'],
            'motivo' => ['required', 'string', 'min:8', 'max:1000'],
        ]);
        DB::transaction(function () use ($inscripcion, $datos) {
            $actual = Inscripcion::query()->lockForUpdate()->findOrFail($inscripcion->id);
            if ($actual->estado !== 'COMPLETADA' || $actual->fecha_fin || $actual->registrosRetiroEstudiante()->exists()) {
                throw ValidationException::withMessages(['fecha_retiro' => 'La inscripción ya no está activa o tiene un retiro registrado.']);
            }
            if ($actual->fecha_inicio && $datos['fecha_retiro'] < $actual->fecha_inicio->toDateString()) {
                throw ValidationException::withMessages(['fecha_retiro' => 'La fecha no puede ser anterior al inicio de la inscripción.']);
            }
            RetiroEstudiante::query()->create([
                'inscripcion_id' => $actual->id,
                'fecha_retiro' => $datos['fecha_retiro'],
                'motivo' => $datos['motivo'],
                'registrado_por' => auth::id(),
            ]);
            // Se conserva el estado COMPLETADA y se cierra su vigencia con fecha_fin.
            // Así no se supone un valor RETIRADA que podría no existir en el ENUM de la BD.
            $actual->update(['fecha_fin' => $datos['fecha_retiro']]);
        });
        return back()->with('success', 'El retiro se registró y la inscripción quedó finalizada.');
    }
}
