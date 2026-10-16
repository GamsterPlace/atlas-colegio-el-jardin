@extends('layouts.direccion')
@section('title','Cuadros de notas')
@section('role','Dirección')
@section('module-title','Notas · Cuadros')
@section('dashboard-url',route('direccion.dashboard'))
@section('content')
@include('direccion.notas.partials.estilos')
<div class="container-fluid py-3 atlas-notas"><div class="ai-header"><div><h2>Cuadros de notas</h2><p class="ai-muted">Seleccione una materia y un período para consultar el cuadro oficial de resultados.</p></div><button class="ai-btn primary" type="button" onclick="window.print()">Imprimir cuadro</button></div>
@include('direccion.notas.partials.filtros')
@if($materia && $periodo)<div class="ai-card"><div class="ai-card-head"><div><strong>{{ $materia->pensum_curso?->curso?->nombre ?? 'Materia' }}</strong><div class="ai-muted">{{ $materia->seccion?->grado?->nombre }} / {{ $materia->seccion?->nombre }} · {{ $periodo->nombre }}</div></div><span class="ai-tag {{ $cierre ? '' : 'off' }}">{{ $cierre ? 'Período cerrado' : 'Sin cierre registrado' }}</span></div><div class="ai-table-wrap"><table class="ai-table"><thead><tr><th>No.</th><th>Estudiante</th><th>Carné</th><th>Nota</th><th>Estado</th></tr></thead><tbody>@forelse($inscripciones as $i)<tr><td>{{ $loop->iteration }}</td><td>{{ $i->estudiante?->apellidos }} {{ $i->estudiante?->nombres }}</td><td>{{ $i->estudiante?->numero_carnet ?? '—' }}</td><td><strong>{{ $notas->get($i->id)?->nota ?? '—' }}</strong></td><td>{{ $notas->get($i->id)?->estado ?? 'Pendiente' }}</td></tr>@empty<tr><td colspan="5" class="ai-empty">No hay estudiantes inscritos en esta sección.</td></tr>@endforelse</tbody></table></div><div class="ai-footer">{{ $inscripciones->count() }} estudiantes · {{ $notas->count() }} notas registradas</div></div>
@else<div class="ai-card"><div class="ai-card-body ai-muted">Seleccione una materia y un período para mostrar el cuadro de notas.</div></div>@endif</div>
@push('styles')<style>@media print{.atlas-sidebar,.atlas-topbar,.ai-header button,.atlas-notas form{display:none!important}.atlas-notas{padding:0!important}.ai-card{box-shadow:none!important;border:1px solid #ddd!important}}</style>@endpush
@endsection
