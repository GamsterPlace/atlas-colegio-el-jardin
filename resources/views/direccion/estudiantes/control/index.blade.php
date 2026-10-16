@extends('layouts.direccion')
@section('title', 'Control estudiantil')
@section('role', 'Dirección')
@section('module-title', 'Control estudiantil')
@section('dashboard-url', route('direccion.dashboard'))
@section('content')
@include('direccion.estudiantes.partials.estilos')
<div class="container-fluid py-3 atlas-estudiantes">
<div class="ae-page-heading"><div class="ae-page-heading-copy"><h2 class="h4 mb-1">Control estudiantil</h2><p class="text-muted mb-0">Distribución de inscripciones completadas por grado, sección y jornada.</p></div></div><div class="card shadow-sm ae-filter-card"><div class="card-body"><form method="GET" action="{{ route('direccion.estudiantes.control.index') }}" class="ae-filter-form ae-filter-form--compact">
<div class="ae-filter-field"><label class="form-label" for="ciclo_escolar_id">Ciclo escolar</label><select class="form-select" id="ciclo_escolar_id" name="ciclo_escolar_id">@foreach($ciclos as $ciclo)<option value="{{ $ciclo->id }}" @selected((string)$cicloId === (string)$ciclo->id)>{{ $ciclo->nombre }}</option>@endforeach</select></div>
<div class="ae-filter-actions"><button class="btn btn-primary" type="submit"><i class="bi bi-funnel"></i> Consultar</button></div>
</form></div></div>
<div class="ae-summary-grid"><div class="card shadow-sm"><div class="card-body"><div class="ae-stat-label">Inscripciones completadas en el ciclo seleccionado</div><div class="ae-stat-value">{{ $inscripciones->count() }}</div></div></div><div class="card shadow-sm"><div class="card-body"><div class="ae-stat-label">Grupos con inscripciones</div><div class="ae-stat-value">{{ $grupos->count() }}</div></div></div></div>
<div class="card shadow-sm"><div class="card-header ae-students-table-heading"><strong>Distribución por grado y sección</strong><span class="text-muted ae-record-count">{{ $grupos->count() }} grupos</span></div>
<div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead class="table-light"><tr><th>Grado</th><th>Sección</th><th>Jornada</th><th>Estudiantes</th></tr></thead><tbody>
@forelse($grupos as $grupo)<tr><td><strong>{{ $grupo['grado'] }}</strong></td><td>{{ $grupo['seccion'] }}</td><td>{{ $grupo['jornada'] }}</td><td><span class="ae-number-pill">{{ $grupo['total'] }}</span></td></tr>
@empty<tr><td colspan="4" class="ae-empty">No hay inscripciones completadas para este ciclo.</td></tr>@endforelse
</tbody></table></div></div>
</div>
@endsection
