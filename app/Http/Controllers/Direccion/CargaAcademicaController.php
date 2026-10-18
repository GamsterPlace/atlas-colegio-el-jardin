<?php

namespace App\Http\Controllers\Direccion;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CargaAcademicaController extends Controller
{
    public function index(Request $request): View
    {
        $ciclos = DB::table('ciclos_escolares')
            ->orderByRaw("FIELD(estado, 'ACTIVO', 'PLANIFICACION', 'CERRADO')")
            ->orderByDesc('fecha_inicio')->get();

        $cicloId = (int) $request->query('ciclo_id', $ciclos->first()?->id ?? 0);
        if ($cicloId && !$ciclos->contains('id', $cicloId)) {
            abort(404);
        }

        $secciones = DB::table('secciones as s')
            ->join('grados as g', 'g.id', '=', 's.grado_id')
            ->join('niveles as n', 'n.id', '=', 'g.nivel_id')
            ->join('jornadas as j', 'j.id', '=', 's.jornada_id')
            ->where('s.ciclo_escolar_id', $cicloId)
            ->select('s.id', 's.grado_id', 's.nombre', 's.activo', 'g.nombre as grado',
                'n.nombre as nivel', 'j.nombre as jornada')
            ->orderBy('n.orden')->orderBy('g.orden')->orderBy('s.nombre')->get();

        $seccionId = (int) $request->query('seccion_id', $secciones->first()?->id ?? 0);
        if ($seccionId && !$secciones->contains('id', $seccionId)) {
            abort(404);
        }

        $seccion = $secciones->firstWhere('id', $seccionId);
        $materias = collect();
        $cargas = collect();

        if ($seccion) {
            $materias = DB::table('pensum_cursos as pc')
                ->join('pensum_estudios as p', 'p.id', '=', 'pc.pensum_id')
                ->join('cursos as c', 'c.id', '=', 'pc.curso_id')
                ->join('grados as g', 'g.id', '=', 'pc.grado_id')
                ->where('pc.grado_id', $seccion->grado_id)
                ->where('p.nivel_id', '=', DB::raw('g.nivel_id'))
                ->where('p.activo', true)
                ->where('c.activo', true)
                ->where(function ($q) use ($ciclos, $cicloId) {
                    $ciclo = $ciclos->firstWhere('id', $cicloId);
                    if ($ciclo) {
                        $q->where(function ($sub) use ($ciclo) {
                            $sub->whereNull('p.vigente_desde')
                                ->orWhereDate('p.vigente_desde', '<=', $ciclo->fecha_fin);
                        })->where(function ($sub) use ($ciclo) {
                            $sub->whereNull('p.vigente_hasta')
                                ->orWhereDate('p.vigente_hasta', '>=', $ciclo->fecha_inicio);
                        });
                    }
                })
                ->select('pc.id', 'pc.horas_semanales', 'c.nombre as curso',
                    'p.nombre as pensum', 'p.version')
                ->orderBy('p.nombre')->orderBy('pc.orden')->orderBy('c.nombre')->get();

            $cargas = DB::table('cargas_academicas')
                ->where('seccion_id', $seccionId)->get()->keyBy('pensum_curso_id');
        }

        return view('direccion.horarios.cargas.index', compact(
            'ciclos', 'cicloId', 'secciones', 'seccionId', 'seccion', 'materias', 'cargas'
        ));
    }

    public function guardar(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'seccion_id' => ['required', 'integer', 'exists:secciones,id'],
            'pensum_curso_id' => ['required', 'integer', 'exists:pensum_cursos,id'],
            'activo' => ['required', 'boolean'],
        ]);

        $seccion = DB::table('secciones as s')
            ->join('grados as g', 'g.id', '=', 's.grado_id')
            ->join('ciclos_escolares as ce', 'ce.id', '=', 's.ciclo_escolar_id')
            ->select('s.id', 's.ciclo_escolar_id', 's.grado_id',
                'g.nivel_id', 'ce.estado', 'ce.fecha_inicio', 'ce.fecha_fin')
            ->where('s.id', $datos['seccion_id'])->first();

        if (!$seccion || $seccion->estado === 'CERRADO') {
            throw ValidationException::withMessages([
                'seccion_id' => 'No se puede modificar una sección de un ciclo cerrado.',
            ]);
        }

        $materia = DB::table('pensum_cursos as pc')
            ->join('pensum_estudios as p', 'p.id', '=', 'pc.pensum_id')
            ->join('cursos as c', 'c.id', '=', 'pc.curso_id')
            ->where('pc.id', $datos['pensum_curso_id'])
            ->where('pc.grado_id', $seccion->grado_id)
            ->where('p.nivel_id', $seccion->nivel_id)
            ->where('p.activo', true)->where('c.activo', true)
            ->where(function ($q) use ($seccion) {
                $q->whereNull('p.vigente_desde')
                    ->orWhereDate('p.vigente_desde', '<=', $seccion->fecha_fin);
            })->where(function ($q) use ($seccion) {
                $q->whereNull('p.vigente_hasta')
                    ->orWhereDate('p.vigente_hasta', '>=', $seccion->fecha_inicio);
            })->first();

        if (!$materia) {
            throw ValidationException::withMessages([
                'pensum_curso_id' => 'La materia no corresponde al grado, nivel o ciclo de la sección.',
            ]);
        }

        DB::transaction(function () use ($datos) {
            $existente = DB::table('cargas_academicas')
                ->where('seccion_id', $datos['seccion_id'])
                ->where('pensum_curso_id', $datos['pensum_curso_id'])
                ->lockForUpdate()->first();

            if ($existente) {
                if (!$datos['activo'] && (
                    DB::table('asignaciones_docentes')->where('carga_academica_id', $existente->id)
                        ->where('activo', true)->exists()
                    || DB::table('horario_detalles')->where('carga_academica_id', $existente->id)->exists()
                    || DB::table('actividades_evaluacion')->where('carga_academica_id', $existente->id)->exists()
                    || DB::table('notas_periodo')->where('carga_academica_id', $existente->id)->exists()
                )) {
                    throw ValidationException::withMessages([
                        'pensum_curso_id' => 'Esta carga tiene asignaciones, horarios o evaluaciones relacionadas. No puede desactivarse.',
                    ]);
                }
                DB::table('cargas_academicas')->where('id', $existente->id)
                    ->update(['activo' => (bool) $datos['activo'], 'updated_at' => now()]);
            } else {
                if (!$datos['activo']) {
                    throw ValidationException::withMessages([
                        'activo' => 'No existe una carga académica que pueda desactivarse.',
                    ]);
                }
                DB::table('cargas_academicas')->insert([
                    'seccion_id' => $datos['seccion_id'],
                    'pensum_curso_id' => $datos['pensum_curso_id'],
                    'activo' => true, 'created_at' => now(), 'updated_at' => now(),
                ]);
            }
        });

        return redirect()->route('direccion.horarios.cargas.index', [
            'ciclo_id' => $seccion->ciclo_escolar_id,
            'seccion_id' => $seccion->id,
        ])->with('success', 'La carga académica se actualizó correctamente.');
    }
}
