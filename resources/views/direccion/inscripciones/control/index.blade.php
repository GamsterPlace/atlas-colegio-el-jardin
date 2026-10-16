@extends('layouts.direccion')
@section('title', 'Control de inscripciones')
@section('role', 'Dirección')
@section('module-title', 'Control de inscripciones')
@section('dashboard-url', route('direccion.dashboard'))
@section('content')
@include('direccion.inscripciones.control.partials.estilos')
<div class="container-fluid py-3 atlas-inscripciones">
    <div class="ai-header"><div><h2>Control de inscripciones</h2><p class="ai-muted">Consulta, seguimiento, retiros y traslados de estudiantes.</p></div><a class="ai-btn primary" href="{{ route('direccion.inscripciones.index') }}">Ir a inscripciones</a></div>
    @if(session('success'))<div class="ai-notice">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="ai-notice error">{{ $errors->first() }}</div>@endif
    <div class="ai-stats"><div class="ai-stat">Inscripciones del ciclo<strong>{{ $resumen['total'] }}</strong></div><div class="ai-stat">Completadas<strong>{{ $resumen['completadas'] }}</strong></div><div class="ai-stat">Vigentes hoy<strong>{{ $resumen['vigentes'] }}</strong></div><div class="ai-stat">Retiros registrados<strong>{{ $resumen['retiros'] }}</strong></div></div>
    <div class="ai-card"><div class="ai-card-head"><strong>Filtros de consulta</strong></div><div class="ai-card-body"><form method="GET" action="{{ route('direccion.inscripciones.control.index') }}"><div class="ai-filters">
        <div class="ai-field"><label for="buscar">Estudiante, código o carné</label><input id="buscar" name="buscar" value="{{ $filtros['buscar'] ?? '' }}" placeholder="Buscar estudiante"></div>
        <div class="ai-field"><label for="ciclo">Ciclo escolar</label><select id="ciclo" name="ciclo_escolar_id"><option value="">Todos los ciclos</option>@foreach($ciclos as $ciclo)<option value="{{ $ciclo->id }}" @selected((string)$cicloId === (string)$ciclo->id)>{{ $ciclo->nombre }}</option>@endforeach</select></div>
        <div class="ai-field"><label for="grado">Grado</label><select id="grado" name="grado_id"><option value="">Todos</option>@foreach($grados as $grado)<option value="{{ $grado->id }}" @selected((string)($filtros['grado_id'] ?? '') === (string)$grado->id)>{{ $grado->nombre }}</option>@endforeach</select></div>
        <div class="ai-field"><label for="seccion">Sección</label><select id="seccion" name="seccion_id"><option value="">Todas</option>@foreach($secciones as $seccion)<option value="{{ $seccion->id }}" @selected((string)($filtros['seccion_id'] ?? '') === (string)$seccion->id)>{{ $seccion->grado?->nombre }} — {{ $seccion->nombre }}</option>@endforeach</select></div>
        <div class="ai-field"><label for="estado">Estado</label><select id="estado" name="estado"><option value="">Todos</option>@foreach($estados as $estado)<option value="{{ $estado }}" @selected(($filtros['estado'] ?? '') === $estado)>{{ $estado }}</option>@endforeach</select></div>
    </div><div class="ai-actions"><button class="ai-btn primary" type="submit">Filtrar</button><a class="ai-btn" href="{{ route('direccion.inscripciones.control.index') }}">Limpiar</a></div></form></div></div>
    <div class="ai-card"><div class="ai-card-head"><strong>Inscripciones registradas</strong><span class="ai-count">{{ $inscripciones->total() }} registros</span></div><div class="ai-table-wrap"><table class="ai-table"><thead><tr><th>Estudiante</th><th>Ciclo</th><th>Grado / sección</th><th>Fecha</th><th>Estado</th><th>Movimientos</th><th>Acciones</th></tr></thead><tbody>
    @forelse($inscripciones as $inscripcion)<tr><td><strong>{{ $inscripcion->estudiante?->nombre_completo ?? trim(($inscripcion->estudiante?->nombres ?? '').' '.($inscripcion->estudiante?->apellidos ?? '')) }}</strong><div class="ai-muted">{{ $inscripcion->estudiante?->codigo ?? '—' }}</div></td><td>{{ $inscripcion->ciclo_escolar?->nombre ?? '—' }}</td><td>{{ $inscripcion->seccion?->grado?->nombre ?? '—' }} / {{ $inscripcion->seccion?->nombre ?? '—' }}</td><td>{{ $inscripcion->fecha_inscripcion?->format('d/m/Y') ?? '—' }}</td><td><span class="ai-tag {{ $inscripcion->fecha_fin || $inscripcion->registros_retiro_estudiante_count ? 'off' : '' }}">{{ $inscripcion->registros_retiro_estudiante_count ? 'Retirado' : $inscripcion->estado }}</span></td><td>{{ $inscripcion->registros_historial_seccion_count }} traslados</td><td><a class="ai-btn" href="{{ route('direccion.inscripciones.control.show', $inscripcion) }}">Ver control</a></td></tr>
    @empty<tr><td class="ai-empty" colspan="7">No hay inscripciones con los filtros seleccionados.</td></tr>@endforelse
    </tbody></table></div><div class="ai-footer">{{ $inscripciones->links() }}</div></div>
</div>
@endsection
