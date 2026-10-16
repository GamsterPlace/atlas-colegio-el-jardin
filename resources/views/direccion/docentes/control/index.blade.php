@extends('layouts.direccion')
@section('title', 'Control de docentes')
@section('role', 'Dirección')
@section('module-title', 'Control de docentes')
@section('dashboard-url', route('direccion.dashboard'))
@section('content')
@include('direccion.docentes.partials.estilos')
<div class="container-fluid py-3 atlas-docentes">
<div class="ad-heading"><div><h2>Control de docentes</h2><p class="ad-subtitle">Altas, bajas y seguimiento del personal docente.</p></div><a class="ad-btn primary" href="{{ route('direccion.docentes.control.create') }}"><i class="bi bi-person-plus"></i> Registrar docente</a></div>
@if(session('success'))<div class="ad-alert">{{ session('success') }}</div>@endif
@if($errors->any())<div class="ad-alert error">{{ $errors->first() }}</div>@endif
<div class="ad-stats"><div class="ad-stat">Total de docentes<strong>{{ $activos + $inactivos }}</strong></div><div class="ad-stat">Activos<strong>{{ $activos }}</strong></div><div class="ad-stat">Inactivos<strong>{{ $inactivos }}</strong></div></div>
<div class="ad-card"><div class="ad-card-head"><strong>Control del personal docente</strong><span class="ad-count">{{ $docentes->total() }} registros</span></div><div class="ad-table-wrap"><table><thead><tr><th>Docente</th><th>Código</th><th>Estado</th><th>Materias activas</th><th>Acciones</th></tr></thead><tbody>@forelse($docentes as $docente)<tr><td><strong>{{ trim(($docente->personal?->persona?->nombres ?? '').' '.($docente->personal?->persona?->apellidos ?? '')) }}</strong></td><td>{{ $docente->personal?->codigo_empleado ?? '—' }}</td><td><span class="ad-tag {{ $docente->activo ? '' : 'off' }}">{{ $docente->activo ? 'Activo' : 'Inactivo' }}</span></td><td>{{ $docente->asignaciones_activas_count }}</td><td><div style="display:flex;gap:8px;flex-wrap:wrap"><a class="ad-btn" href="{{ route('direccion.docentes.control.edit', $docente) }}">Editar</a><a class="ad-btn" href="{{ route('direccion.docentes.show', $docente) }}">Expediente</a>@if($docente->activo)<form method="POST" action="{{ route('direccion.docentes.control.baja', $docente) }}" data-confirm-title="Dar de baja al docente" data-confirm-message="Se desactivará al docente y finalizarán sus asignaciones de materias y de grado guía.">@csrf @method('PATCH')<input type="hidden" name="confirmacion" value="BAJA"><button class="ad-btn danger" type="submit">Dar de baja</button></form>@else<form method="POST" action="{{ route('direccion.docentes.control.reactivar', $docente) }}" data-confirm-title="Reactivar docente" data-confirm-message="El docente se reactivará sin recuperar las asignaciones anteriores.">@csrf @method('PATCH')<button class="ad-btn" type="submit">Reactivar</button></form>@endif</div></td></tr>@empty<tr><td class="ad-empty" colspan="5">No hay docentes registrados.</td></tr>@endforelse</tbody></table></div><div class="ad-footer">{{ $docentes->links() }}</div></div>
</div>
@include('direccion.docentes.partials.modal-confirmacion')
@endsection
