@extends('layouts.direccion')

@section('title', 'Reportes administrativos')
@section('module-title', 'Reportes administrativos')

@push('styles')
<style>
.atlas-rad{max-width:1450px;margin:0 auto;padding:22px;color:#1c3553}
.atlas-rad *{box-sizing:border-box}
.atlas-rad__heading{margin-bottom:20px}
.atlas-rad__heading h1{font-size:26px;margin:0 0 7px;color:#183454}
.atlas-rad__heading p{font-size:14px;line-height:1.6;color:#667b90;margin:0}
.atlas-rad__tabs{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:20px;border-bottom:1px solid #dce6f0;padding-bottom:12px}
.atlas-rad__tab{display:inline-flex;gap:8px;align-items:center;text-decoration:none;font-weight:700;font-size:13px;padding:10px 13px;border:1px solid #dce6f0;border-radius:9px;background:#fff;color:#245078}
.atlas-rad__tab[aria-current="page"]{background:#174f91;color:#fff;border-color:#174f91}
.atlas-rad__panel{background:#fff;border:1px solid #dce6f0;border-radius:14px;padding:22px;margin-bottom:20px;box-shadow:0 5px 20px #17365a09}
.atlas-rad__panel h2{font-size:17px;margin:0 0 7px;color:#183454}
.atlas-rad__description{font-size:13px;color:#667b90;margin:0 0 17px}
.atlas-rad__filters{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:14px;align-items:end}
.atlas-rad__field label{display:block;font-size:12px;font-weight:700;margin-bottom:6px}
.atlas-rad__field input,.atlas-rad__field select{width:100%;min-height:41px;border:1px solid #cdd9e6;border-radius:8px;padding:10px;background:#fff;color:#183454;font:inherit;font-size:13px}
.atlas-rad__actions{display:flex;gap:9px;flex-wrap:wrap;margin-top:17px}
.atlas-rad__button{display:inline-flex;align-items:center;justify-content:center;gap:8px;border:1px solid #15549c;border-radius:9px;padding:10px 15px;text-decoration:none;background:#15549c;color:#fff;cursor:pointer;font-weight:700;font-size:13px}
.atlas-rad__button--light{background:#edf4fb;border-color:#d4e3f2;color:#174879}
.atlas-rad__counters{display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:12px;margin-bottom:20px}
.atlas-rad__counter{background:#fff;border:1px solid #dce6f0;border-radius:12px;padding:17px 19px}
.atlas-rad__counter strong{display:block;font-size:23px;color:#183454;font-variant-numeric:tabular-nums}
.atlas-rad__counter span{font-size:12px;color:#6b7d90}
.atlas-rad__table-wrap{overflow-x:auto}
.atlas-rad__table{width:100%;border-collapse:collapse;font-size:13px}
.atlas-rad__table th{background:#f0f5fa;color:#334e6a;text-align:left;white-space:nowrap}
.atlas-rad__table td,.atlas-rad__table th{padding:12px;border-bottom:1px solid #e7edf4;vertical-align:top}
.atlas-rad__table tbody tr:hover{background:#f8fbff}
.atlas-rad__tag{display:inline-block;padding:5px 9px;border-radius:7px;background:#e9f2fc;color:#22528b;font-size:11px;font-weight:800}
.atlas-rad__muted{color:#667b90;font-size:12px}
.atlas-rad__pagination{margin-top:18px;overflow-x:auto}
.atlas-rad__notice{padding:12px 15px;background:#f1f6fc;border:1px solid #dce6f0;border-radius:9px;color:#435d77;font-size:12px;margin-bottom:15px}
@media(max-width:600px){.atlas-rad{padding:12px}.atlas-rad__panel{padding:14px}.atlas-rad__heading h1{font-size:23px}}
</style>
@endpush

@section('content')
@php
    $pestanas = [
        'inscripciones' => ['titulo' => 'Inscripciones', 'icono' => 'bi-person-plus'],
        'finanzas' => ['titulo' => 'Finanzas', 'icono' => 'bi-wallet2'],
        'personal' => ['titulo' => 'Personal', 'icono' => 'bi-people'],
        'inventario' => ['titulo' => 'Inventario', 'icono' => 'bi-box-seam'],
        'auditoria' => ['titulo' => 'Auditoría', 'icono' => 'bi-shield-check'],
    ];
    $tituloActual = $pestanas[$pestana]['titulo'] ?? 'Inscripciones';
@endphp
<div class="atlas-rad">
    <header class="atlas-rad__heading">
        <h1><i class="bi bi-bar-chart-line" aria-hidden="true"></i> Reportes administrativos</h1>
        <p>Consulta institucional de inscripciones, finanzas, asistencia del personal, inventario y auditoría.</p>
    </header>

    <nav class="atlas-rad__tabs" aria-label="Categorías de reportes administrativos">
        @foreach($pestanas as $clave => $datos)
            <a class="atlas-rad__tab" href="{{ route('direccion.reportes.administrativos.index', array_filter(['pestana' => $clave, 'ciclo_escolar_id' => $filtros['ciclo_escolar_id'] ?? null, 'desde' => $filtros['desde'] ?? null, 'hasta' => $filtros['hasta'] ?? null], fn ($v) => $v !== null && $v !== '')) }}" @if($pestana === $clave) aria-current="page" @endif>
                <i class="bi {{ $datos['icono'] }}" aria-hidden="true"></i> {{ $datos['titulo'] }}
            </a>
        @endforeach
    </nav>

    <section class="atlas-rad__panel" aria-labelledby="rad-filtros">
        <h2 id="rad-filtros">Filtros — {{ $tituloActual }}</h2>
        <p class="atlas-rad__description">Los resultados y los indicadores se calculan según los filtros aplicados a la pestaña actual.</p>
        <form method="GET" action="{{ route('direccion.reportes.administrativos.index') }}">
            <input type="hidden" name="pestana" value="{{ $pestana }}">
            <div class="atlas-rad__filters">
                @if(in_array($pestana, ['inscripciones', 'finanzas'], true))
                    <div class="atlas-rad__field">
                        <label for="rad-ciclo">Ciclo escolar</label>
                        <select id="rad-ciclo" name="ciclo_escolar_id">
                            @foreach($ciclos as $ciclo)
                                <option value="{{ $ciclo->id }}" @selected((string)($filtros['ciclo_escolar_id'] ?? $cicloId) === (string)$ciclo->id)>{{ $ciclo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="atlas-rad__field">
                    <label for="rad-desde">Desde</label>
                    <input id="rad-desde" type="date" name="desde" value="{{ $filtros['desde'] ?? '' }}">
                </div>
                <div class="atlas-rad__field">
                    <label for="rad-hasta">Hasta</label>
                    <input id="rad-hasta" type="date" name="hasta" value="{{ $filtros['hasta'] ?? '' }}">
                </div>
                <div class="atlas-rad__field">
                    <label for="rad-buscar">Buscar</label>
                    <input id="rad-buscar" type="search" name="buscar" maxlength="100" value="{{ $filtros['buscar'] ?? '' }}" placeholder="{{ match($pestana) { 'personal' => 'Código de empleado', 'inventario' => 'Nombre del producto', 'auditoria' => 'Acción o entidad', default => 'Estudiante o carné' } }}">
                </div>
            </div>
            <div class="atlas-rad__actions">
                <button class="atlas-rad__button" type="submit"><i class="bi bi-funnel" aria-hidden="true"></i> Aplicar filtros</button>
                <a class="atlas-rad__button atlas-rad__button--light" href="{{ route('direccion.reportes.administrativos.index', ['pestana' => $pestana]) }}">Limpiar filtros</a>
            </div>
        </form>
    </section>

    <div class="atlas-rad__counters" aria-label="Indicadores del reporte">
        @foreach($indicadores as $nombre => $valor)
            <div class="atlas-rad__counter">
                <strong>{{ $nombre === 'Monto exigible registrado' ? 'Q '.number_format((float)$valor, 2, '.', ',') : number_format((float)$valor, 0, '.', ',') }}</strong>
                <span>{{ $nombre }}</span>
            </div>
        @endforeach
    </div>

    <section class="atlas-rad__panel" aria-labelledby="rad-resultados">
        <h2 id="rad-resultados">Detalle de {{ strtolower($tituloActual) }}</h2>
        @if($pestana === 'finanzas')
            <div class="atlas-rad__notice">El monto exigible registrado no representa el saldo pendiente. El cálculo de morosidad y saldos requiere considerar pagos válidos, recargos y anulaciones.</div>
        @elseif($pestana === 'personal' || $pestana === 'inventario' || $pestana === 'auditoria')
            <div class="atlas-rad__notice">Este reporte utiliza un rango de fechas y no se limita por ciclo escolar, ya que los registros consultados no tienen relación directa con un ciclo.</div>
        @endif
        <div class="atlas-rad__table-wrap">
            <table class="atlas-rad__table">
                <thead><tr>
                    @switch($pestana)
                        @case('inscripciones')
                            <th>Fecha</th><th>Estudiante</th><th>Carné</th><th>Grado / sección</th><th>Estado</th>
                            @break
                        @case('finanzas')
                            <th>Emisión</th><th>Estudiante</th><th>Concepto</th><th>Vencimiento</th><th>Monto exigible</th><th>Estado administrativo</th>
                            @break
                        @case('personal')
                            <th>Fecha</th><th>Empleado</th><th>Código</th><th>Entrada</th><th>Salida</th><th>Estado</th>
                            @break
                        @case('inventario')
                            <th>Fecha</th><th>Producto</th><th>Tipo</th><th>Cantidad</th><th>Motivo</th><th>Referencia</th>
                            @break
                        @case('auditoria')
                            <th>Fecha y hora</th><th>Usuario</th><th>Acción</th><th>Entidad</th><th>Registro</th><th>Motivo</th>
                            @break
                    @endswitch
                </tr></thead>
                <tbody>
                    @forelse($registros as $registro)
                        <tr>
                            @switch($pestana)
                                @case('inscripciones')
                                    <td>{{ $registro->fecha_inscripcion?->format('d/m/Y') ?? '—' }}</td>
                                    <td>{{ $registro->estudiante?->nombre_completo ?? '—' }}</td>
                                    <td>{{ $registro->estudiante?->numero_carnet ?? '—' }}</td>
                                    <td>{{ $registro->seccion?->grado?->nombre ?? '—' }} / {{ $registro->seccion?->nombre ?? '—' }}</td>
                                    <td><span class="atlas-rad__tag">{{ $registro->estado ?? '—' }}</span></td>
                                    @break
                                @case('finanzas')
                                    <td>{{ $registro->fecha_emision?->format('d/m/Y') ?? '—' }}</td>
                                    <td>{{ $registro->estudiante?->nombre_completo ?? '—' }}</td>
                                    <td>{{ $registro->concepto_cobro?->nombre ?? $registro->descripcion ?? '—' }}</td>
                                    <td>{{ $registro->fecha_vencimiento?->format('d/m/Y') ?? '—' }}</td>
                                    <td>Q {{ number_format((float)$registro->monto_exigible, 2, '.', ',') }}</td>
                                    <td><span class="atlas-rad__tag">{{ $registro->estado_administrativo ?? '—' }}</span></td>
                                    @break
                                @case('personal')
                                    <td>{{ $registro->fecha?->format('d/m/Y') ?? '—' }}</td>
                                    <td>{{ trim(($registro->personal?->persona?->nombres ?? '').' '.($registro->personal?->persona?->apellidos ?? '')) ?: '—' }}</td>
                                    <td>{{ $registro->personal?->codigo_empleado ?? '—' }}</td>
                                    <td>{{ $registro->hora_entrada ?? '—' }}</td>
                                    <td>{{ $registro->hora_salida ?? '—' }}</td>
                                    <td><span class="atlas-rad__tag">{{ $registro->estado ?? '—' }}</span></td>
                                    @break
                                @case('inventario')
                                    <td>{{ $registro->fecha_movimiento?->format('d/m/Y H:i') ?? '—' }}</td>
                                    <td>{{ $registro->producto_uniforme?->nombre ?? '—' }}</td>
                                    <td><span class="atlas-rad__tag">{{ $registro->tipo ?? '—' }}</span></td>
                                    <td>{{ number_format((float)$registro->cantidad, 0, '.', ',') }}</td>
                                    <td>{{ $registro->motivo ?? '—' }}</td>
                                    <td>{{ $registro->referencia ?? '—' }}</td>
                                    @break
                                @case('auditoria')
                                    <td>{{ $registro->fecha_evento?->format('d/m/Y H:i') ?? '—' }}</td>
                                    <td>{{ $registro->usuario?->nombre_usuario ?? ('ID '.($registro->usuario_id ?? '—')) }}</td>
                                    <td>{{ $registro->accion ?? '—' }}</td>
                                    <td>{{ $registro->entidad ?? '—' }}</td>
                                    <td>{{ $registro->entidad_id ?? '—' }}</td>
                                    <td>{{ $registro->motivo ?? '—' }}</td>
                                    @break
                            @endswitch
                        </tr>
                    @empty
                        <tr><td colspan="6" style="text-align:center;padding:30px">No se encontraron registros para los filtros seleccionados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($registros)
            <div class="atlas-rad__pagination">{{ $registros->links() }}</div>
        @endif
    </section>
</div>
@endsection
