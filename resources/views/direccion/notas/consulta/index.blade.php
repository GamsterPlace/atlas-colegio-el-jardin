@extends('layouts.direccion')
@section('title','Consulta de calificaciones')
@section('role','Dirección')
@section('module-title','Notas · Consulta')
@section('dashboard-url',route('direccion.dashboard'))
@section('content')
@include('direccion.notas.partials.estilos')
@include('direccion.notas.consulta.boleta-estilos')
<div class="container-fluid py-3 atlas-notas">
<div class="ai-header"><div><h2>Consulta de calificaciones</h2><p class="ai-muted">Seleccione ciclo, grado, sección y estudiante para visualizar e imprimir su boleta.</p></div></div>
<div class="ai-card"><div class="ai-card-head"><strong>Selección académica</strong></div><div class="ai-card-body">
<form method="GET" action="{{ route('direccion.notas.index') }}" id="filtrosBoleta" class="ai-filtros-boleta">
<div class="ai-field"><label for="ciclo">Ciclo escolar</label><select id="ciclo" name="ciclo_escolar_id" onchange="this.form.submit()">@foreach($ciclos as $ciclo)<option value="{{ $ciclo->id }}" @selected($cicloId==$ciclo->id)>{{ $ciclo->nombre }}</option>@endforeach</select></div>
<div class="ai-field"><label for="grado">Grado</label><select id="grado" name="grado_id" onchange="this.form.submit()"><option value="">Seleccione grado</option>@foreach($grados as $grado)<option value="{{ $grado->id }}" @selected($gradoId==$grado->id)>{{ $grado->nombre }}</option>@endforeach</select></div>
<div class="ai-field"><label for="seccion">Sección</label><select id="seccion" name="seccion_id" onchange="this.form.submit()"><option value="">Seleccione sección</option>@foreach($secciones as $seccion)<option value="{{ $seccion->id }}" @selected($seccionId==$seccion->id)>{{ $seccion->nombre }}</option>@endforeach</select></div>
</form></div></div>
@if($seccionId)
<div class="ai-card"><div class="ai-card-head"><strong>Estudiantes de la sección</strong><span class="ai-count">{{ $inscripciones->count() }} estudiantes</span></div>
<div class="ai-table-wrap"><table class="ai-table"><thead><tr><th>No.</th><th>Estudiante</th><th>Acción</th></tr></thead><tbody>
@forelse($inscripciones as $i)<tr><td>{{ $loop->iteration }}</td><td>{{ $i->estudiante?->apellidos }}, {{ $i->estudiante?->nombres }}</td><td><a class="ai-btn {{ $inscripcionId===$i->id ? 'primary' : '' }}" href="{{ route('direccion.notas.index',['ciclo_escolar_id'=>$cicloId,'grado_id'=>$gradoId,'seccion_id'=>$seccionId,'inscripcion_id'=>$i->id]) }}"><i class="bi bi-eye"></i> Ver boleta</a></td></tr>@empty<tr><td colspan="3" class="ai-empty">No hay estudiantes inscritos en esta sección.</td></tr>@endforelse
</tbody></table></div></div>
@endif
@if($boleta)
<div class="ai-card"><div class="ai-card-head"><strong>Vista previa de boleta</strong><a class="ai-btn primary" href="{{ route('direccion.notas.boleta.pdf',$boleta['inscripcion']->id) }}" target="_blank" rel="noopener"><i class="bi bi-printer"></i> Imprimir boleta PDF</a></div>
<div class="ai-preview-boleta">@include('direccion.notas.consulta.boleta',$boleta)</div></div>
@endif
</div>
<style>.ai-filtros-boleta{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:15px}.ai-preview-boleta{max-width:850px;margin:20px auto;border:1px solid #dce4ee;box-shadow:0 6px 20px #142b4410}.ai-preview-boleta .boleta-documento{padding:22px}@media(max-width:700px){.ai-filtros-boleta{grid-template-columns:1fr}.ai-preview-boleta{overflow-x:auto}.ai-preview-boleta .boleta-documento{min-width:650px}}</style>
@endsection
