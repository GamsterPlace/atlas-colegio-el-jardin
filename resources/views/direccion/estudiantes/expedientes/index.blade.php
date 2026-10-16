@extends('layouts.direccion')
@section('title', 'Expedientes estudiantiles')
@section('role', 'Dirección')
@section('module-title', 'Expedientes')
@section('dashboard-url', route('direccion.dashboard'))
@section('content')
@include('direccion.estudiantes.partials.estilos')
<div class="container-fluid py-3 atlas-estudiantes">
<div class="ae-page-heading"><div class="ae-page-heading-copy"><h2 class="h4 mb-1">Expedientes estudiantiles</h2><p class="text-muted mb-0">Consulta integral de información y trayectoria de cada estudiante.</p></div></div><div class="card shadow-sm ae-filter-card"><div class="card-body">
<form method="GET" action="{{ route('direccion.estudiantes.expedientes.index') }}" class="ae-filter-form ae-filter-form--compact">
<div class="ae-filter-field"><label class="form-label" for="buscar">Buscar estudiante</label><input class="form-control" id="buscar" name="buscar" maxlength="120" placeholder="Nombre, código histórico o carné" value="{{ $filtros['buscar'] ?? '' }}"></div>
<div class="ae-filter-actions"><button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Buscar</button><a href="{{ route('direccion.estudiantes.expedientes.index') }}" class="btn btn-outline-secondary">Limpiar</a></div>
</form></div></div>
<div class="card shadow-sm"><div class="card-header ae-students-table-heading"><strong>Expedientes</strong><span class="text-muted ae-record-count">{{ $estudiantes->total() }} registros</span></div>
<div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead class="table-light"><tr><th>Estudiante</th><th>Código histórico</th><th>Carné</th><th>Acción</th></tr></thead><tbody>
@forelse($estudiantes as $estudiante)
<tr><td><strong>{{ $estudiante->nombre_completo }}</strong></td><td>{{ $estudiante->codigo ?: '—' }}</td><td>{{ $estudiante->numero_carnet ?: 'Pendiente' }}</td><td><a class="btn btn-sm btn-outline-primary" href="{{ route('direccion.estudiantes.expedientes.show', $estudiante) }}"><i class="bi bi-folder2-open"></i> Consultar</a></td></tr>
@empty<tr><td colspan="4" class="ae-empty">No se encontraron expedientes.</td></tr>@endforelse
</tbody></table></div><div class="card-footer">{{ $estudiantes->links() }}</div></div>
</div>
@endsection
