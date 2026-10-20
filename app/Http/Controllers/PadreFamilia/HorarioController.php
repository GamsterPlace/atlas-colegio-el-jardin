<?php

namespace App\Http\Controllers\PadreFamilia;

use App\Http\Controllers\Controller;
use App\Models\CicloEscolar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

final class HorarioController extends Controller
{
    public function index(Request $request): View
    {
        $personaId = (int) $request->user()->persona_id;
        $responsable = DB::table('responsables')
            ->where('persona_id', $personaId)
            ->where('activo', 1)
            ->first();

        $ciclos = CicloEscolar::query()->orderByDesc('fecha_inicio')->get();
        $cicloId = (int) $request->query('ciclo_id', $ciclos->first()?->id ?? 0);
        abort_unless($cicloId === 0 || $ciclos->contains('id', $cicloId), 404);

        $hijos = collect();
        if ($responsable && $cicloId) {
            $hijos = DB::table('estudiante_responsables as er')
                ->join('estudiantes as e', 'e.id', '=', 'er.estudiante_id')
                ->join('inscripciones as i', 'i.estudiante_id', '=', 'e.id')
                ->join('secciones as s', 's.id', '=', 'i.seccion_id')
                ->join('grados as g', 'g.id', '=', 's.grado_id')
                ->where('er.responsable_id', $responsable->id)
                ->where('i.ciclo_escolar_id', $cicloId)
                ->where('i.estado', 'COMPLETADA')
                ->whereDate('er.fecha_inicio', '<=', DB::raw('COALESCE(i.fecha_inicio, CURRENT_DATE)'))
                ->where(function ($query) {
                    $query->whereNull('er.fecha_fin')->orWhereColumn('er.fecha_fin', '>=', 'i.fecha_inicio');
                })
                ->where(function ($query) {
                    $query->whereNull('i.fecha_fin')->orWhereColumn('i.fecha_fin', '>=', 'i.fecha_inicio');
                })
                ->select('e.id as estudiante_id', 'e.nombres', 'e.apellidos', 's.id as seccion_id', 's.nombre as seccion', 'g.nombre as grado')
                ->distinct()->orderBy('e.apellidos')->orderBy('e.nombres')->get();
        }

        $estudianteId = (int) $request->query('estudiante_id', $hijos->first()?->estudiante_id ?? 0);
        $hijo = $hijos->firstWhere('estudiante_id', $estudianteId);
        abort_if($estudianteId && !$hijo, 404);
        $registros = collect();

        if ($hijo) {
            $registros = DB::table('horario_detalles as hd')
                ->join('horarios as h', 'h.id', '=', 'hd.horario_id')
                ->join('bloques_horarios as b', 'b.id', '=', 'hd.bloque_horario_id')
                ->join('cargas_academicas as ca', 'ca.id', '=', 'hd.carga_academica_id')
                ->join('pensum_cursos as pc', 'pc.id', '=', 'ca.pensum_curso_id')
                ->join('cursos as c', 'c.id', '=', 'pc.curso_id')
                ->where('h.seccion_id', $hijo->seccion_id)
                ->where('h.estado', 'APROBADO')
                ->whereColumn('ca.seccion_id', 'h.seccion_id')
                ->select('hd.id', 'hd.dia_semana', 'b.hora_inicio', 'b.hora_fin', 'c.nombre as materia', 'hd.aula')
                ->orderBy('hd.dia_semana')->orderBy('b.hora_inicio')->get();
        }

        return view('padre.horarios.index', compact('ciclos', 'cicloId', 'hijos', 'hijo', 'registros'));
    }
}
