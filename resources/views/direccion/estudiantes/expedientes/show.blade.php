@extends('layouts.direccion')
@section('title', 'Expediente estudiantil')
@section('role', 'Dirección')
@section('module-title', 'Expediente estudiantil')
@section('dashboard-url', route('direccion.dashboard'))
@section('content')
@include('direccion.estudiantes.partials.estilos')
<div class="container-fluid py-3 atlas-estudiantes">
<div class="ae-page-heading"><div class="ae-page-heading-copy"><h2 class="h4 mb-1">{{ $estudiante->nombre_completo }}</h2><p class="text-muted mb-0">Expediente institucional · {{ $estudiante->numero_carnet ?: ($estudiante->codigo ?: "Sin carné") }}</p></div><a href="{{ route('direccion.estudiantes.expedientes.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Volver a expedientes</a></div>@php($c = $estudiante->registrosInformacionComplementariaEstudiante)
@php($m = $estudiante->registrosInformacionMedicaEstudiante)
<div class="ae-detail-grid">
<section class="card"><div class="card-header"><strong><i class="bi bi-person-vcard"></i> Información personal</strong></div><div class="card-body"><dl class="ae-data-list">
<dt>Código histórico</dt><dd>{{ $estudiante->codigo ?: '—' }}</dd><dt>Carné</dt><dd>{{ $estudiante->numero_carnet ?: '—' }}</dd><dt>CUI</dt><dd>{{ $estudiante->cui ?: '—' }}</dd><dt>Fecha de nacimiento</dt><dd>{{ $estudiante->fecha_nacimiento?->format('d/m/Y') ?: '—' }}</dd><dt>Lugar de nacimiento</dt><dd>{{ $estudiante->lugar_nacimiento ?: '—' }}</dd><dt>Sexo</dt><dd>{{ $estudiante->sexo ?: '—' }}</dd><dt>Dirección</dt><dd>{{ $estudiante->direccion ?: '—' }}</dd><dt>Estado</dt><dd><span class="badge {{ $estudiante->estado === 'ACTIVO' ? 'bg-success' : 'bg-secondary' }}">{{ $estudiante->estado }}</span></dd>
</dl></div></section>
<section class="card"><div class="card-header"><strong><i class="bi bi-card-list"></i> Información complementaria</strong></div><div class="card-body"><dl class="ae-data-list">
<dt>Religión</dt><dd>{{ $c?->religion ?: '—' }}</dd><dt>Teléfono de casa</dt><dd>{{ $c?->telefono_casa ?: '—' }}</dd><dt>Transporte</dt><dd>{{ $c?->transporte ?: '—' }}</dd><dt>Número de hermanos</dt><dd>{{ $c?->numero_hermanos ?? '—' }}</dd><dt>Hermanos mayores</dt><dd>{{ $c?->hermanos_mayores ?? '—' }}</dd><dt>Hermanos menores</dt><dd>{{ $c?->hermanos_menores ?? '—' }}</dd><dt>Hermanos inscritos</dt><dd>{{ $c?->hermanos_inscritos ?: '—' }}</dd>
</dl></div></section>
<section class="card"><div class="card-header"><strong><i class="bi bi-heart-pulse"></i> Información médica</strong><span class="ae-restricted">Acceso restringido</span></div><div class="card-body"><dl class="ae-data-list">
<dt>Tipo de sangre</dt><dd>{{ $m?->tipo_sangre ?: '—' }}</dd><dt>Alergias</dt><dd>{{ $m?->alergias ?: '—' }}</dd><dt>Condiciones relevantes</dt><dd>{{ $m?->condiciones_relevantes ?: '—' }}</dd><dt>Medicamentos</dt><dd>{{ $m?->medicamentos ?: '—' }}</dd><dt>Indicaciones de emergencia</dt><dd>{{ $m?->indicaciones_emergencia ?: '—' }}</dd><dt>Seguro médico</dt><dd>{{ $m?->seguro_medico ?: '—' }}</dd>
</dl></div></section>
<section class="card"><div class="card-header"><strong><i class="bi bi-people"></i> Responsables</strong></div><div class="card-body ae-responsables">
@forelse($estudiante->registrosEstudianteResponsable as $vinculo)
<div class="ae-responsable"><strong>{{ trim(($vinculo->responsable?->persona?->nombres ?? '').' '.($vinculo->responsable?->persona?->apellidos ?? '')) ?: 'Sin nombre' }}</strong><div class="ae-meta">{{ $vinculo->parentesco }} @if($vinculo->es_responsable_financiero) · Responsable financiero @endif @if($vinculo->es_contacto_principal) · Contacto principal @endif</div><div class="ae-meta"><i class="bi bi-telephone"></i> {{ $vinculo->responsable?->persona?->telefono ?: 'Sin teléfono' }}</div></div>
@empty<p class="text-muted">No hay responsables vinculados.</p>@endforelse
</div></section>
<section class="card ae-detail-full"><div class="card-header ae-students-table-heading"><strong><i class="bi bi-clock-history"></i> Historial de inscripciones</strong><span class="ae-record-count">{{ $estudiante->registrosInscripcion->count() }} registros</span></div><div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead class="table-light"><tr><th>Ciclo escolar</th><th>Grado</th><th>Sección</th><th>Estado</th><th>Fecha</th></tr></thead><tbody>
@forelse($estudiante->registrosInscripcion->sortByDesc('fecha_inscripcion') as $ins)
<tr><td>{{ $ins->ciclo_escolar?->nombre ?: '—' }}</td><td>{{ $ins->seccion?->grado?->nombre ?: '—' }}</td><td>{{ $ins->seccion?->nombre ?: '—' }}</td><td>{{ $ins->estado }}</td><td>{{ $ins->fecha_inscripcion?->format('d/m/Y') ?: '—' }}</td></tr>
@empty<tr><td colspan="5" class="ae-empty">Sin inscripciones registradas.</td></tr>@endforelse
</tbody></table></div></section>
</div>
</div>
@endsection
