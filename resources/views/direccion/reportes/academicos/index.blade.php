@extends('layouts.direccion')

@section('title', 'Reportes académicos')
@section('module-title', 'Reportes académicos')

@push('styles')
<style>
.atlas-ra{max-width:1450px;margin:0 auto;padding:22px;color:#1c3553}
.atlas-ra *{box-sizing:border-box}
.atlas-ra__heading{display:flex;justify-content:space-between;align-items:flex-start;gap:16px;flex-wrap:wrap;margin-bottom:20px}
.atlas-ra__heading h1{font-size:26px;line-height:1.25;margin:0 0 7px;color:#183454}
.atlas-ra__heading p{font-size:14px;line-height:1.6;color:#667b90;margin:0}
.atlas-ra__tabs{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:20px;border-bottom:1px solid #dce6f0;padding-bottom:12px}
.atlas-ra__tab{display:inline-flex;gap:8px;align-items:center;text-decoration:none;font-weight:700;font-size:13px;padding:10px 13px;border:1px solid #dce6f0;border-radius:9px;background:#fff;color:#245078}
.atlas-ra__tab[aria-current="page"]{background:#174f91;color:#fff;border-color:#174f91}
.atlas-ra__tab[aria-disabled="true"]{opacity:.55;cursor:not-allowed}
.atlas-ra__panel{background:#fff;border:1px solid #dce6f0;border-radius:14px;padding:22px;margin-bottom:20px;box-shadow:0 5px 20px #17365a09}
.atlas-ra__panel h2{font-size:17px;margin:0 0 6px;color:#183454}
.atlas-ra__description{font-size:13px;color:#667b90;margin:0 0 17px}
.atlas-ra__filters{display:grid;grid-template-columns:repeat(auto-fit,minmax(185px,1fr));gap:14px;align-items:end}
.atlas-ra__field label{display:block;font-size:12px;font-weight:700;margin-bottom:6px}
.atlas-ra__field input,.atlas-ra__field select{width:100%;min-height:41px;border:1px solid #cdd9e6;border-radius:8px;padding:10px;background:#fff;color:#183454;font:inherit;font-size:13px}
.atlas-ra__actions{display:flex;gap:9px;flex-wrap:wrap;margin-top:17px}
.atlas-ra__button{display:inline-flex;align-items:center;justify-content:center;gap:8px;border:1px solid #15549c;border-radius:9px;padding:10px 15px;text-decoration:none;background:#15549c;color:#fff;cursor:pointer;font-weight:700;font-size:13px}
.atlas-ra__button--light{background:#edf4fb;border-color:#d4e3f2;color:#174879}
.atlas-ra__counters{display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:12px;margin-bottom:20px}
.atlas-ra__counter{background:#fff;border:1px solid #dce6f0;border-radius:12px;padding:17px 19px}
.atlas-ra__counter strong{display:block;font-size:25px;color:#183454;font-variant-numeric:tabular-nums}
.atlas-ra__counter span{font-size:12px;color:#6b7d90}
.atlas-ra__table-wrap{overflow-x:auto}
.atlas-ra__table{width:100%;border-collapse:collapse;font-size:13px}
.atlas-ra__table th{background:#f0f5fa;color:#334e6a;text-align:left;white-space:nowrap}
.atlas-ra__table td,.atlas-ra__table th{padding:12px;border-bottom:1px solid #e7edf4;vertical-align:middle}
.atlas-ra__table tbody tr:hover{background:#f8fbff}
.atlas-ra__tag{display:inline-block;padding:5px 9px;border-radius:7px;background:#e9f2fc;color:#22528b;font-size:11px;font-weight:800;white-space:nowrap}
.atlas-ra__tag--green{background:#e5f6eb;color:#17643b}
.atlas-ra__tag--amber{background:#fff3d7;color:#8a5b0b}
.atlas-ra__tag--red{background:#fde9eb;color:#962b39}
.atlas-ra__muted{color:#667b90;font-size:12px}
.atlas-ra__pagination{margin-top:18px;overflow-x:auto}
@media(max-width:600px){.atlas-ra{padding:12px}.atlas-ra__panel{padding:14px}.atlas-ra__heading h1{font-size:23px}}
</style>
@endpush

@section('content')
<div class="atlas-ra">
    <header class="atlas-ra__heading">
        <div>
            <h1><i class="bi bi-mortarboard" aria-hidden="true"></i> Reportes académicos</h1>
            <p>Consulta institucional de estudiantes y resultados académicos por ciclo escolar.</p>
        </div>
    </header>

    @php
        $pestanas = [
            ['titulo' => 'Estudiantes', 'icono' => 'bi-people', 'ruta' => 'direccion.reportes.academicos.index'],
            ['titulo' => 'Calificaciones', 'icono' => 'bi-journal-check', 'ruta' => 'direccion.reportes.academicos.calificaciones.index'],
            ['titulo' => 'Rendimiento', 'icono' => 'bi-graph-up', 'ruta' => 'direccion.reportes.academicos.rendimiento.index'],
            ['titulo' => 'Asistencia', 'icono' => 'bi-clipboard2-check', 'ruta' => 'direccion.reportes.asistencia.index'],
            ['titulo' => 'Docentes', 'icono' => 'bi-person-workspace', 'ruta' => 'direccion.reportes.academicos.docentes.index'],
            ['titulo' => 'Horarios', 'icono' => 'bi-calendar-week', 'ruta' => 'direccion.reportes.academicos.horarios.index'],
            ['titulo' => 'Historial académico', 'icono' => 'bi-clock-history', 'ruta' => 'direccion.reportes.academicos.historial.index'],
        ];
    @endphp

    <nav class="atlas-ra__tabs" aria-label="Categorías de reportes académicos">
        @foreach($pestanas as $pestana)
            @if(\Illuminate\Support\Facades\Route::has($pestana['ruta']))
                <a href="{{ route($pestana['ruta']) }}"
                   class="atlas-ra__tab"
                   @if(request()->routeIs($pestana['ruta']) || request()->routeIs($pestana['ruta'].'.*')) aria-current="page" @endif>
                    <i class="bi {{ $pestana['icono'] }}" aria-hidden="true"></i> {{ $pestana['titulo'] }}
                </a>
            @else
                <span class="atlas-ra__tab" aria-disabled="true" title="Reporte pendiente de implementación">
                    <i class="bi {{ $pestana['icono'] }}" aria-hidden="true"></i> {{ $pestana['titulo'] }}
                </span>
            @endif
        @endforeach
    </nav>

    <section class="atlas-ra__panel" aria-labelledby="atlas-ra-filtros-titulo">
        <h2 id="atlas-ra-filtros-titulo">Nómina y control de estudiantes</h2>
        <p class="atlas-ra__description">Filtra las inscripciones registradas. Los indicadores corresponden a los filtros aplicados.</p>
        <form method="GET" action="{{ route('direccion.reportes.academicos.index') }}">
            <div class="atlas-ra__filters">
                <div class="atlas-ra__field">
                    <label for="ra-ciclo">Ciclo escolar</label>
                    <select id="ra-ciclo" name="ciclo_escolar_id">
                        @foreach($ciclos as $ciclo)
                            <option value="{{ $ciclo->id }}" @selected((string)($filtros['ciclo_escolar_id'] ?? $cicloId) === (string)$ciclo->id)>{{ $ciclo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="atlas-ra__field">
                    <label for="ra-seccion">Grado y sección</label>
                    <select id="ra-seccion" name="seccion_id">
                        <option value="">Todas las secciones</option>
                        @foreach($secciones as $seccion)
                            <option value="{{ $seccion->id }}" @selected((string)($filtros['seccion_id'] ?? '') === (string)$seccion->id)>
                                {{ $seccion->grado?->nivel?->nombre ? $seccion->grado->nivel->nombre.' · ' : '' }}{{ $seccion->grado?->nombre ?? 'Grado no disponible' }} — {{ $seccion->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="atlas-ra__field">
                    <label for="ra-estado">Estado de inscripción</label>
                    <select id="ra-estado" name="estado">
                        <option value="">Todos los estados</option>
                        @foreach(['COMPLETADA' => 'Completada', 'RETIRADA' => 'Retirada', 'ANULADA' => 'Anulada'] as $valor => $etiqueta)
                            <option value="{{ $valor }}" @selected(($filtros['estado'] ?? '') === $valor)>{{ $etiqueta }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="atlas-ra__field">
                    <label for="ra-buscar">Estudiante o carné</label>
                    <input id="ra-buscar" type="search" name="buscar" maxlength="100" value="{{ $filtros['buscar'] ?? '' }}" placeholder="Nombre, apellido o carné">
                </div>
            </div>
            <div class="atlas-ra__actions">
                <button class="atlas-ra__button" type="submit"><i class="bi bi-funnel" aria-hidden="true"></i> Aplicar filtros</button>
                <a class="atlas-ra__button atlas-ra__button--light" href="{{ route('direccion.reportes.academicos.index') }}"><i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i> Limpiar</a>
            </div>
        </form>
    </section>

    <div class="atlas-ra__counters" aria-label="Resumen de inscripciones">
        <div class="atlas-ra__counter"><strong>{{ number_format($totalInscripciones) }}</strong><span>Inscripciones encontradas</span></div>
        <div class="atlas-ra__counter"><strong>{{ number_format($totalEstudiantes) }}</strong><span>Estudiantes diferentes</span></div>
        <div class="atlas-ra__counter"><strong>{{ number_format($totalCompletadas) }}</strong><span>Inscripciones completadas</span></div>
    </div>

    <section class="atlas-ra__panel" aria-labelledby="atlas-ra-resultados-titulo">
        <h2 id="atlas-ra-resultados-titulo">Resultados</h2>
        <p class="atlas-ra__description">{{ number_format($inscripciones->total()) }} registro(s) encontrado(s). Se muestran 25 por página.</p>
        <div class="atlas-ra__table-wrap">
            <table class="atlas-ra__table">
                <thead><tr><th scope="col">Estudiante</th><th scope="col">Carné</th><th scope="col">Código histórico</th><th scope="col">Nivel / grado / sección</th><th scope="col">Fecha de inscripción</th><th scope="col">Estado</th></tr></thead>
                <tbody>
                    @forelse($inscripciones as $inscripcion)
                        @php
                            $estudiante = $inscripcion->estudiante;
                            $seccion = $inscripcion->seccion;
                            $estado = (string) $inscripcion->estado;
                            $etiquetaEstado = match($estado) {
                                'COMPLETADA' => 'atlas-ra__tag--green',
                                'RETIRADA', 'ANULADA' => 'atlas-ra__tag--red',
                                default => 'atlas-ra__tag--amber',
                            };
                        @endphp
                        <tr>
                            <td><strong>{{ $estudiante?->nombre_completo ?: '—' }}</strong></td>
                            <td>{{ $estudiante?->numero_carnet ?: '—' }}</td>
                            <td>{{ $estudiante?->codigo ?: '—' }}</td>
                            <td>
                                {{ $seccion?->grado?->nivel?->nombre ?? '—' }}<br>
                                <span class="atlas-ra__muted">{{ $seccion?->grado?->nombre ?? '—' }} / {{ $seccion?->nombre ?? '—' }}</span>
                            </td>
                            <td>{{ $inscripcion->fecha_inscripcion?->format('d/m/Y') ?? '—' }}</td>
                            <td><span class="atlas-ra__tag {{ $etiquetaEstado }}">{{ ucfirst(mb_strtolower($estado)) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" style="text-align:center;padding:32px;color:#667b90">No se encontraron inscripciones con los filtros seleccionados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($inscripciones->hasPages())
            <div class="atlas-ra__pagination">{{ $inscripciones->links() }}</div>
        @endif
    </section>
</div>
@endsection
