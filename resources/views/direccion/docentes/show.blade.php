@extends('layouts.direccion')
@section('title', 'Expediente docente')
@section('role', 'Dirección')
@section('module-title', 'Expediente docente')
@section('dashboard-url', route('direccion.dashboard'))
@section('content')
@include('direccion.docentes.partials.estilos')
@php($persona = $docente->personal?->persona)
<div class="container-fluid py-3 atlas-docentes"><div class="ad-heading"><div><h2>Expediente del docente</h2><p class="ad-subtitle">{{ trim(($persona?->nombres ?? '').' '.($persona?->apellidos ?? '')) }}</p></div><div style="display:flex;gap:9px"><a class="ad-btn" href="{{ route('direccion.docentes.index') }}">Volver</a><a class="ad-btn primary" href="{{ route('direccion.docentes.control.edit', $docente) }}">Editar datos</a><a class="ad-btn" href="{{ route('direccion.docentes.ficha-acceso', $docente) }}" target="_blank" rel="noopener">Imprimir ficha de acceso</a></div></div>
@if(session('success'))<div class="ad-alert">{{ session('success') }}</div>@endif
<div class="ad-grid two"><div class="ad-card"><div class="ad-card-head"><strong>Información personal</strong></div><div class="ad-card-body"><dl class="ad-data"><dt>Nombre</dt><dd>{{ trim(($persona?->nombres ?? '').' '.($persona?->apellidos ?? '')) }}</dd><dt>CUI</dt><dd>{{ $persona?->cui ?: '—' }}</dd><dt>Teléfono</dt><dd>{{ $persona?->telefono ?: '—' }}</dd><dt>Correo</dt><dd>{{ $persona?->correo ?: '—' }}</dd><dt>Dirección</dt><dd>{{ $persona?->direccion ?: '—' }}</dd></dl></div></div><div class="ad-card"><div class="ad-card-head"><strong>Información laboral</strong></div><div class="ad-card-body"><dl class="ad-data"><dt>Código</dt><dd>{{ $docente->personal?->codigo_empleado ?? '—' }}</dd><dt>Cargo</dt><dd>{{ $docente->personal?->cargo ?? '—' }}</dd><dt>Ingreso</dt><dd>{{ $docente->personal?->fecha_ingreso?->format('d/m/Y') ?? '—' }}</dd><dt>Estado</dt><dd><span class="ad-tag {{ $docente->activo ? '' : 'off' }}">{{ $docente->activo ? 'Activo' : 'Inactivo' }}</span></dd></dl></div></div></div>
<div class="ad-card"><div class="ad-card-head"><strong>Historial de asignaciones académicas</strong><span class="ad-count">{{ $docente->registrosAsignacionDocente->count() }} registros</span></div><div class="ad-table-wrap"><table><thead><tr><th>Materia</th><th>Grado / sección</th><th>Inicio</th><th>Fin</th><th>Estado</th></tr></thead><tbody>@forelse($docente->registrosAsignacionDocente as $asignacion)<tr><td>{{ $asignacion->carga_academica?->pensum_curso?->curso?->nombre ?? '—' }}</td><td>{{ $asignacion->carga_academica?->seccion?->grado?->nombre ?? '—' }} / {{ $asignacion->carga_academica?->seccion?->nombre ?? '—' }}</td><td>{{ $asignacion->fecha_inicio?->format('d/m/Y') }}</td><td>{{ $asignacion->fecha_fin?->format('d/m/Y') ?? '—' }}</td><td><span class="ad-tag {{ $asignacion->activo ? '' : 'off' }}">{{ $asignacion->activo ? 'Vigente' : 'Finalizada' }}</span></td></tr>@empty<tr><td colspan="5" class="ad-empty">Sin asignaciones académicas.</td></tr>@endforelse</tbody></table></div></div>
<div class="ad-card"><div class="ad-card-head"><strong>Historial de docente guía</strong></div><div class="ad-table-wrap"><table><thead><tr><th>Grado</th><th>Sección</th><th>Inicio</th><th>Fin</th></tr></thead><tbody>@forelse($docente->registrosDocenteGuia as $guia)<tr><td>{{ $guia->seccion?->grado?->nombre ?? '—' }}</td><td>{{ $guia->seccion?->nombre ?? '—' }}</td><td>{{ $guia->fecha_inicio?->format('d/m/Y') ?? '—' }}</td><td>{{ $guia->fecha_fin?->format('d/m/Y') ?? 'Vigente' }}</td></tr>@empty<tr><td colspan="4" class="ad-empty">Sin registros como docente guía.</td></tr>@endforelse</tbody></table></div></div>
</div>
@if(session('documento_docente_id') == $docente->id)
<script>
document.addEventListener('DOMContentLoaded', function () {
    window.open(@json(route('direccion.docentes.ficha-acceso', $docente)), '_blank', 'noopener');
});
</script>
@endif
@endsection
