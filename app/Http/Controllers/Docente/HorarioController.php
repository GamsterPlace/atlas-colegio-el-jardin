<?php

namespace App\Http\Controllers\Docente;

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
        $docente = DB::table('docentes as d')
            ->join('personal as p', 'p.id', '=', 'd.personal_id')
            ->where('p.persona_id', $personaId)
            ->where('d.activo', 1)
            ->where('p.activo', 1)
            ->select('d.id')
            ->first();

        $ciclos = CicloEscolar::query()->orderByDesc('fecha_inicio')->get();
        $cicloId = (int) $request->query('ciclo_id', $ciclos->first()?->id ?? 0);
        abort_unless($cicloId === 0 || $ciclos->contains('id', $cicloId), 404);

        $registros = collect();
        if ($docente && $cicloId) {
            $registros = DB::table('horario_detalles as hd')
                ->join('horarios as h', 'h.id', '=', 'hd.horario_id')
                ->join('secciones as s', 's.id', '=', 'h.seccion_id')
                ->join('grados as g', 'g.id', '=', 's.grado_id')
                ->join('bloques_horarios as b', 'b.id', '=', 'hd.bloque_horario_id')
                ->join('cargas_academicas as ca', 'ca.id', '=', 'hd.carga_academica_id')
                ->join('pensum_cursos as pc', 'pc.id', '=', 'ca.pensum_curso_id')
                ->join('cursos as c', 'c.id', '=', 'pc.curso_id')
                ->where('hd.docente_id', $docente->id)
                ->where('h.estado', 'APROBADO')
                ->where('s.ciclo_escolar_id', $cicloId)
                ->whereColumn('ca.seccion_id', 's.id')
                ->select('hd.id', 'hd.dia_semana', 'b.hora_inicio', 'b.hora_fin', 'c.nombre as materia', 's.nombre as seccion', 'g.nombre as grado', 'hd.aula')
                ->orderBy('hd.dia_semana')->orderBy('b.hora_inicio')->get();
        }

        return view('docente.horarios.index', compact('ciclos', 'cicloId', 'registros', 'docente'));
    }
}
