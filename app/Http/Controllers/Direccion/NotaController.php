<?php

namespace App\Http\Controllers\Direccion;

use App\Http\Controllers\Controller;
use App\Models\ActividadEvaluacion;
use App\Models\Calificacion;
use App\Models\CargaAcademica;
use App\Models\CicloEscolar;
use App\Models\CierreAcademico;
use App\Models\Inscripcion;
use App\Models\NotaFinal;
use App\Models\NotaPeriodo;
use App\Models\PeriodoAcademico;
use App\Models\Seccion;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NotaController extends Controller
{
    private function filtros(Request $request): array
    {
        return $request->validate([
            'ciclo_escolar_id' => ['nullable', 'integer', 'exists:ciclos_escolares,id'],
            'seccion_id' => ['nullable', 'integer', 'exists:secciones,id'],
            'carga_academica_id' => ['nullable', 'integer', 'exists:cargas_academicas,id'],
            'periodo_academico_id' => ['nullable', 'integer', 'exists:periodos_academicos,id'],
            'buscar' => ['nullable', 'string', 'max:120'],
        ]);
    }

    private function contexto(Request $request): array
    {
        $filtros = $this->filtros($request);
        $ciclos = CicloEscolar::query()->orderByDesc('fecha_inicio')->get();
        $cicloId = (int) ($filtros['ciclo_escolar_id'] ?? $ciclos->firstWhere('estado', 'ACTIVO')?->id ?? $ciclos->first()?->id ?? 0);
        $secciones = Seccion::query()->where('ciclo_escolar_id', $cicloId)->with('grado')->orderBy('nombre')->get();
        $seccionId = (int) ($filtros['seccion_id'] ?? 0);
        $materias = CargaAcademica::query()->whereHas('seccion', fn ($q) => $q->where('ciclo_escolar_id', $cicloId))
            ->when($seccionId, fn ($q) => $q->where('seccion_id', $seccionId))
            ->with(['seccion.grado', 'pensum_curso.curso'])->get();
        $materiaId = (int) ($filtros['carga_academica_id'] ?? 0);
        $periodos = PeriodoAcademico::query()->where('ciclo_escolar_id', $cicloId)->orderBy('numero')->get();
        $periodoId = (int) ($filtros['periodo_academico_id'] ?? 0);
        // Los filtros dependientes se verifican para evitar consultas cruzadas entre ciclos y secciones.
        if ($seccionId && !$secciones->contains('id', $seccionId)) $seccionId = 0;
        if ($materiaId && !$materias->contains('id', $materiaId)) $materiaId = 0;
        if ($periodoId && !$periodos->contains('id', $periodoId)) $periodoId = 0;
        return compact('filtros', 'ciclos', 'cicloId', 'secciones', 'seccionId', 'materias', 'materiaId', 'periodos', 'periodoId');
    }

    private function datosBoleta(Inscripcion $inscripcion): array
    {
        $inscripcion->loadMissing(['estudiante', 'seccion.grado', 'ciclo_escolar']);
        $cargas = CargaAcademica::query()->where('seccion_id', $inscripcion->seccion_id)
            ->with('pensum_curso.curso')->get()->sortBy(fn ($c) => $c->pensum_curso?->orden ?? 999)->values();
        $periodos = PeriodoAcademico::query()->where('ciclo_escolar_id', $inscripcion->ciclo_escolar_id)
            ->orderBy('numero')->get()->keyBy('numero');
        $notas = NotaPeriodo::query()->where('inscripcion_id', $inscripcion->id)
            ->whereIn('carga_academica_id', $cargas->pluck('id'))
            ->whereIn('periodo_academico_id', $periodos->pluck('id'))
            ->get()->keyBy(fn ($n) => $n->carga_academica_id.'-'.$n->periodo_academico_id);
        $filas = []; $sumas = [1=>[],2=>[],3=>[],4=>[]]; $finales = [];
        foreach ($cargas as $carga) {
            $bimestres = []; $presentes = [];
            for ($numero=1; $numero<=4; $numero++) {
                $periodo = $periodos->get($numero);
                $nota = $periodo ? $notas->get($carga->id.'-'.$periodo->id)?->nota : null;
                $bimestres[$numero] = $nota === null ? null : (float) $nota;
                if ($nota !== null) { $presentes[] = (float) $nota; $sumas[$numero][] = (float) $nota; }
            }
            $promedio = count($presentes) ? round(array_sum($presentes)/count($presentes),2) : null;
            if ($promedio !== null) $finales[] = $promedio;
            $filas[] = ['materia'=>$carga->pensum_curso?->curso?->nombre ?? 'Materia sin nombre',
                'bimestres'=>$bimestres, 'total'=>count($presentes) ? array_sum($presentes) : null,
                'promedio'=>$promedio, 'completa'=>count($presentes)===4];
        }
        $promedios = [];
        foreach ($sumas as $numero=>$valores) $promedios[$numero] = count($valores) ? round(array_sum($valores)/count($valores),2) : null;
        $general = count($finales) ? round(array_sum($finales)/count($finales),2) : null;
        $guia = \App\Models\DocenteGuia::query()->where('seccion_id',$inscripcion->seccion_id)
            ->whereDate('fecha_inicio','<=',now()->toDateString())
            ->where(fn ($q)=>$q->whereNull('fecha_fin')->orWhereDate('fecha_fin','>=',now()->toDateString()))
            ->with('docente.personal.persona')->latest('fecha_inicio')->first();
        $persona = $guia?->docente?->personal?->persona;
        $nombreGuia = $persona ? trim($persona->nombres.' '.$persona->apellidos) : null;
        return compact('inscripcion','filas','promedios','general','nombreGuia');
    }

    public function consulta(Request $request): View
    {
        $request->validate([
            'ciclo_escolar_id'=>['nullable','integer','exists:ciclos_escolares,id'],
            'grado_id'=>['nullable','integer','exists:grados,id'],
            'seccion_id'=>['nullable','integer','exists:secciones,id'],
            'inscripcion_id'=>['nullable','integer','exists:inscripciones,id'],
        ]);
        $ciclos = CicloEscolar::query()->orderByDesc('fecha_inicio')->get();
        $cicloId = (int) ($request->input('ciclo_escolar_id') ?: $ciclos->firstWhere('estado','ACTIVO')?->id ?: $ciclos->first()?->id ?: 0);
        $seccionesCiclo = Seccion::query()->where('ciclo_escolar_id',$cicloId)->with('grado')->get();
        $grados = $seccionesCiclo->pluck('grado')->filter()->unique('id')->sortBy('nombre')->values();
        $gradoId = (int) $request->input('grado_id',0);
        if (!$grados->contains('id',$gradoId)) $gradoId = 0;
        $secciones = $seccionesCiclo->when($gradoId,fn ($items)=>$items->where('grado_id',$gradoId))->sortBy('nombre')->values();
        $seccionId = (int) $request->input('seccion_id',0);
        if (!$secciones->contains('id',$seccionId)) $seccionId = 0;
        $inscripciones = collect(); $boleta = null; $inscripcionId = 0;
        if ($seccionId) {
            $inscripciones = Inscripcion::query()->where('ciclo_escolar_id',$cicloId)
                ->where('seccion_id',$seccionId)->where('estado','COMPLETADA')
                ->with('estudiante')->get()->sortBy(fn ($i)=>mb_strtolower(($i->estudiante?->apellidos ?? '').' '.($i->estudiante?->nombres ?? '')))->values();
            $inscripcionId = (int) $request->input('inscripcion_id',0);
            $seleccionada = $inscripciones->firstWhere('id',$inscripcionId);
            if ($seleccionada) $boleta = $this->datosBoleta($seleccionada);
        }
        return view('direccion.notas.consulta.index',compact('ciclos','cicloId','grados','gradoId','secciones','seccionId','inscripciones','inscripcionId','boleta'));
    }

    public function boletaPdf(Inscripcion $inscripcion)
    {
        abort_unless($inscripcion->estado === 'COMPLETADA',404);
        $boleta = $this->datosBoleta($inscripcion);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('direccion.notas.consulta.pdf',$boleta)
            ->setPaper('letter','portrait');
        return $pdf->stream('boleta-calificaciones-'.$inscripcion->id.'.pdf');
    }

    public function cuadros(Request $request): View
    {
        $c = $this->contexto($request);
        $inscripciones = collect(); $notas = collect(); $materia = null; $periodo = null; $cierre = null;
        if ($c['materiaId'] && $c['periodoId']) {
            $materia = $c['materias']->firstWhere('id', $c['materiaId']);
            $periodo = $c['periodos']->firstWhere('id', $c['periodoId']);
            $inscripciones = Inscripcion::query()->where('ciclo_escolar_id', $c['cicloId'])
                ->where('seccion_id', $materia->seccion_id)->where('estado', 'COMPLETADA')
                ->with('estudiante')->get()->sortBy(fn ($i) => mb_strtolower(($i->estudiante?->apellidos ?? '').' '.($i->estudiante?->nombres ?? '')))->values();
            $notas = NotaPeriodo::query()->where('carga_academica_id', $materia->id)
                ->where('periodo_academico_id', $periodo->id)->whereIn('inscripcion_id', $inscripciones->pluck('id'))
                ->get()->keyBy('inscripcion_id');
            $cierre = CierreAcademico::query()->where('carga_academica_id', $materia->id)
                ->where('periodo_academico_id', $periodo->id)->first();
        }
        return view('direccion.notas.cuadros.index', $c + compact('inscripciones', 'notas', 'materia', 'periodo', 'cierre'));
    }

    public function control(Request $request): View
    {
        $c = $this->contexto($request);
        $actividades = ActividadEvaluacion::query()->whereHas('carga_academica.seccion', fn ($q) => $q->where('ciclo_escolar_id', $c['cicloId']))
            ->when($c['seccionId'], fn ($q) => $q->whereHas('carga_academica', fn ($m) => $m->where('seccion_id', $c['seccionId'])))
            ->when($c['materiaId'], fn ($q) => $q->where('carga_academica_id', $c['materiaId']))
            ->when($c['periodoId'], fn ($q) => $q->where('periodo_academico_id', $c['periodoId']))
            ->with(['carga_academica.seccion.grado', 'carga_academica.pensum_curso.curso', 'periodo_academico'])
            ->withCount('registrosCalificacion')->orderByDesc('fecha')->paginate(20)->withQueryString();
        $cierres = CierreAcademico::query()->whereHas('carga_academica.seccion', fn ($q) => $q->where('ciclo_escolar_id', $c['cicloId']))
            ->when($c['seccionId'], fn ($q) => $q->whereHas('carga_academica', fn ($m) => $m->where('seccion_id', $c['seccionId'])))
            ->when($c['materiaId'], fn ($q) => $q->where('carga_academica_id', $c['materiaId']))
            ->when($c['periodoId'], fn ($q) => $q->where('periodo_academico_id', $c['periodoId']))
            ->with(['carga_academica.pensum_curso.curso', 'periodo_academico', 'cerrado_por'])
            ->orderByDesc('cerrado_at')->limit(30)->get();
        return view('direccion.notas.control.index', $c + compact('actividades', 'cierres'));
    }
}
