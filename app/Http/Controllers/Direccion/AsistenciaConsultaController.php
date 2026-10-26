<?php

namespace App\Http\Controllers\Direccion;

use App\Http\Controllers\Controller;
use App\Models\AsistenciaEstudiante;
use App\Models\CicloEscolar;
use App\Models\JustificacionAsistencia;
use App\Models\Seccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class AsistenciaConsultaController extends Controller
{
    private function filtros(Request $request): array
    {
        return $request->validate([
            'desde' => ['nullable','date'],
            'hasta' => ['nullable','date','after_or_equal:desde'],
            'ciclo_escolar_id' => ['nullable','integer','exists:ciclos_escolares,id'],
            'seccion_id' => ['nullable','integer','exists:secciones,id'],
            'estado' => ['nullable',Rule::in(['PRESENTE','AUSENTE','TARDE','JUSTIFICADA'])],
            'buscar' => ['nullable','string','max:100'],
        ]);
    }

    private function opciones(array $filtros): array
    {
        $ciclos = CicloEscolar::query()->orderByDesc('fecha_inicio')->get();
        $cicloId = $filtros['ciclo_escolar_id'] ?? $ciclos->first()?->id;
        $secciones = Seccion::query()->with('grado.nivel')
            ->when($cicloId, fn($q) => $q->where('ciclo_escolar_id', $cicloId))
            ->orderBy('grado_id')->orderBy('nombre')->get();
        return compact('ciclos','cicloId','secciones');
    }

    private function base(array $filtros)
    {
        $cicloId = $filtros['ciclo_escolar_id'] ?? CicloEscolar::query()
            ->orderByDesc('fecha_inicio')->value('id');

        return AsistenciaEstudiante::query()
            ->with(['inscripcion.estudiante','inscripcion.seccion.grado.nivel','registrado_por',
                'registrosJustificacionAsistencia'])
            ->when($cicloId, fn($q) => $q->whereHas('inscripcion',
                fn($i) => $i->where('ciclo_escolar_id', $cicloId)))
            ->when($filtros['seccion_id'] ?? null, fn($q,$v) => $q->whereHas('inscripcion',
                fn($i) => $i->where('seccion_id', $v)))
            ->when($filtros['desde'] ?? null, fn($q,$v) => $q->whereDate('fecha','>=',$v))
            ->when($filtros['hasta'] ?? null, fn($q,$v) => $q->whereDate('fecha','<=',$v))
            ->when($filtros['estado'] ?? null, fn($q,$v) => $q->where('estado',$v))
            ->when($filtros['buscar'] ?? null, fn($q,$v) => $q->whereHas('inscripcion.estudiante',
                fn($e) => $e->buscar($v)));
    }

    public function historial(Request $request)
    {
        $filtros = $this->filtros($request);
        $filtros['desde'] ??= now('America/Guatemala')->toDateString();
        $filtros['hasta'] ??= now('America/Guatemala')->toDateString();
        $opciones = $this->opciones($filtros);
        $registros = $this->base($filtros)->orderByDesc('fecha')->orderByDesc('hora_entrada')
            ->paginate(25)->withQueryString();
        $totales = (clone $this->base($filtros))->selectRaw('estado, COUNT(*) AS total')
            ->groupBy('estado')->pluck('total','estado');
        return view('direccion.asistencia.historial',array_merge(compact('filtros','registros','totales'),$opciones));
    }

    public function justificaciones(Request $request)
    {
        $filtros = $this->filtros($request);
        $opciones = $this->opciones($filtros);
        $estadoJustificacion = $request->validate([
            'estado_justificacion' => ['nullable',Rule::in(['PENDIENTE','APROBADA','RECHAZADA'])],
        ])['estado_justificacion'] ?? null;

        $base = $this->base($filtros)->select('asistencia_estudiantes.id');
        $justificaciones = JustificacionAsistencia::query()
            ->with(['asistencia_estudiante.inscripcion.estudiante',
                'asistencia_estudiante.inscripcion.seccion.grado','registrado_por','aprobado_por'])
            ->whereIn('asistencia_estudiante_id',$base)
            ->when($estadoJustificacion,fn($q,$v)=>$q->where('estado',$v))
            ->orderByDesc('id')->paginate(25)->withQueryString();
        return view('direccion.asistencia.justificaciones',array_merge(
            compact('filtros','justificaciones','estadoJustificacion'),$opciones));
    }

    public function resolver(Request $request, JustificacionAsistencia $justificacion)
    {
        $data = $request->validate([
            'decision' => ['required',Rule::in(['APROBADA','RECHAZADA'])],
        ]);
        DB::transaction(function () use ($justificacion,$data) {
            $actual = JustificacionAsistencia::query()->lockForUpdate()
                ->findOrFail($justificacion->id);
            if ($actual->estado !== 'PENDIENTE') {
                throw ValidationException::withMessages(['decision'=>'La justificación ya fue resuelta.']);
            }
            $asistencia = AsistenciaEstudiante::query()->lockForUpdate()
                ->findOrFail($actual->asistencia_estudiante_id);
            $actual->estado = $data['decision'];
            $actual->aprobado_por = auth::id();
            $actual->save();
            if ($data['decision'] === 'APROBADA') {
                $asistencia->estado = 'JUSTIFICADA';
            } else {
                $asistencia->estado = $asistencia->entrada_tarde ? 'TARDE' : 'AUSENTE';
            }
            $asistencia->save();
        });
        return back()->with('success','Justificación resuelta correctamente.');
    }

    public function reporte(Request $request)
    {
        $filtros = $this->filtros($request);
        $filtros['desde'] ??= now('America/Guatemala')->startOfMonth()->toDateString();
        $filtros['hasta'] ??= now('America/Guatemala')->toDateString();
        $opciones = $this->opciones($filtros);
        $registros = $this->base($filtros)->orderBy('fecha')->orderBy('inscripcion_id')
            ->paginate(40)->withQueryString();
        $totales = (clone $this->base($filtros))->selectRaw('estado, COUNT(*) AS total')
            ->groupBy('estado')->pluck('total','estado');
        return view('direccion.asistencia.reporte',array_merge(compact('filtros','registros','totales'),$opciones));
    }

    public function reportePdf(Request $request)
    {
        $filtros = $this->filtros($request);
        $filtros['desde'] ??= now('America/Guatemala')->startOfMonth()->toDateString();
        $filtros['hasta'] ??= now('America/Guatemala')->toDateString();
        $registros = $this->base($filtros)->orderBy('fecha')->orderBy('inscripcion_id')->limit(3000)->get();
        return Pdf::loadView('direccion.asistencia.pdf',compact('registros','filtros'))
            ->setPaper('letter','landscape')->stream('reporte-asistencia.pdf');
    }
}
