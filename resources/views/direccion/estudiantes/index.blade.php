@extends('layouts.direccion')
@section('title', 'Estudiantes')
@section('role', 'Dirección')
@section('module-title', 'Listado de estudiantes')
@section('dashboard-url', route('direccion.dashboard'))
@section('content')
@include('direccion.estudiantes.partials.estilos')
<div class="container-fluid py-3 atlas-estudiantes">
    <div class="ae-page-heading">
        <div class="ae-page-heading-copy">
            <h2 class="h4 mb-1">Listado de estudiantes</h2>
            <p class="text-muted mb-0">Consulta de estudiantes registrados en ATLAS.</p>
        </div>
        <a href="{{ route('direccion.inscripciones.create') }}" class="btn btn-primary"><i class="bi bi-person-plus"></i> Nueva inscripción</a>
    </div>

    <div class="card shadow-sm mb-3 ae-filter-card">
        <div class="card-body">
            <form method="GET" action="{{ route('direccion.estudiantes.index') }}" class="ae-filter-form">
                <div class="ae-filter-field ae-filter-search">
                    <label class="form-label" for="buscar">Buscar estudiante</label>
                    <input id="buscar" name="buscar" class="form-control" maxlength="120" value="{{ $filtros['buscar'] ?? '' }}" placeholder="Nombre, código histórico o carné">
                </div>
                <div class="ae-filter-field">
                    <label class="form-label" for="estado">Estado</label>
                    <select class="form-select" name="estado" id="estado">
                        <option value="">Todos</option>
                        <option value="ACTIVO" @selected(($filtros['estado'] ?? '') === 'ACTIVO')>Activo</option>
                        <option value="INACTIVO" @selected(($filtros['estado'] ?? '') === 'INACTIVO')>Inactivo</option>
                    </select>
                </div>
                <div class="ae-filter-field">
                    <label class="form-label" for="ciclo_escolar_id">Ciclo escolar</label>
                    <select class="form-select" name="ciclo_escolar_id" id="ciclo_escolar_id">
                        <option value="">Todos</option>
                        @foreach($ciclos as $ciclo)
                            <option value="{{ $ciclo->id }}" @selected((string)($filtros['ciclo_escolar_id'] ?? '') === (string)$ciclo->id)>{{ $ciclo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ae-filter-actions">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Buscar</button>
                    <a class="btn btn-outline-secondary" href="{{ route('direccion.estudiantes.index') }}">Limpiar</a>
                </div>
            </form>
        </div>
    </div>
    <div class="card shadow-sm"><div class="card-header bg-white ae-students-table-heading"><strong>Estudiantes</strong><span class="text-muted ae-record-count">{{ $estudiantes->total() }} registros</span></div>
        <div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead class="table-light"><tr><th>Estudiante</th><th>Código histórico</th><th>Carné</th><th>Estado</th><th>Inscripciones</th><th>Acción</th></tr></thead><tbody>
        @forelse($estudiantes as $estudiante)<tr><td><strong>{{ $estudiante->nombre_completo }}</strong></td><td>{{ $estudiante->codigo ?: '—' }}</td><td>{{ $estudiante->numero_carnet ?: 'Pendiente' }}</td><td><span class="badge {{ $estudiante->estado === 'ACTIVO' ? 'bg-success' : 'bg-secondary' }}">{{ $estudiante->estado }}</span></td><td>{{ $estudiante->registros_inscripcion_count }}</td><td><a class="btn btn-sm btn-outline-primary" href="{{ route('direccion.estudiantes.expedientes.show', $estudiante) }}">Ver expediente</a></td></tr>
        @empty<tr><td colspan="6" class="text-center text-muted py-4">No se encontraron estudiantes.</td></tr>@endforelse
        </tbody></table></div><div class="card-footer bg-white">{{ $estudiantes->links() }}</div>
    </div>
</div>
@endsection
