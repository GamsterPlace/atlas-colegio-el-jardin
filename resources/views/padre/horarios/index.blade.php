@extends('layouts.padre')
@section('module-title', 'Horario de clases')
@section('content')
<style>
.atlas-horario-consulta{width:100%;border-collapse:collapse;min-width:750px}.atlas-horario-consulta th,.atlas-horario-consulta td{border:1px solid #dce4ef;padding:12px;vertical-align:top}.atlas-horario-consulta th{background:#112d55;color:#fff;text-align:center}.atlas-horario-consulta td:first-child{background:#f0f4fa;font-weight:700;white-space:nowrap}.atlas-horario-materia{font-weight:700;color:#12365f}.atlas-horario-secundario{font-size:12px;color:#526780;margin-top:4px}.atlas-horario-celda{margin-bottom:8px;padding:8px;border-radius:6px;background:#f5f8fc}@media print{.atlas-horarios-acciones,aside,nav{display:none!important}.atlas-horario-consulta{min-width:0;font-size:11px}.atlas-horario-consulta th,.atlas-horario-consulta td{padding:6px}}
</style>
<div class="atlas-page-heading"><h2>Horario de clases</h2><p>Consulta el horario oficial de cada uno de tus hijos inscritos.</p></div>
<div class="atlas-card atlas-horarios-acciones" style="margin-bottom:18px"><div class="atlas-card-body">
<form method="GET" action="{{ route('padre-familia.horarios.index') }}" style="display:flex;gap:12px;align-items:end;flex-wrap:wrap"><div class="atlas-form-group"><label class="atlas-label" for="ciclo_id">Ciclo escolar</label><select class="atlas-select" name="ciclo_id" id="ciclo_id">@foreach($ciclos as $ciclo)<option value="{{ $ciclo->id }}" @selected($cicloId === (int)$ciclo->id)>{{ $ciclo->nombre }}</option>@endforeach</select></div><div class="atlas-form-group"><label class="atlas-label" for="estudiante_id">Estudiante</label><select class="atlas-select" name="estudiante_id" id="estudiante_id">@foreach($hijos as $estudiante)<option value="{{ $estudiante->estudiante_id }}" @selected($hijo && $hijo->estudiante_id === $estudiante->estudiante_id)>{{ $estudiante->nombres }} {{ $estudiante->apellidos }}</option>@endforeach</select></div><button class="atlas-btn atlas-btn-primary" type="submit">Consultar</button><button class="atlas-btn atlas-btn-outline" type="button" onclick="window.print()">Imprimir / PDF</button></form>
</div></div>
<div class="atlas-card"><div class="atlas-card-body"><h3>Colegio Mixto El Jardín — Horario oficial</h3>
@if(!$hijo)<p>No se encontraron estudiantes inscritos asociados a tu cuenta para este ciclo.</p>
@else
<p><strong>Estudiante:</strong> {{ $hijo->nombres }} {{ $hijo->apellidos }} · <strong>Grado:</strong> {{ $hijo->grado }} · <strong>Sección:</strong> {{ $hijo->seccion }}</p>
@if($registros->isEmpty())<p>La sección todavía no tiene un horario aprobado y publicado.</p>
@else
@php $dias=[1=>'Lunes',2=>'Martes',3=>'Miércoles',4=>'Jueves',5=>'Viernes',6=>'Sábado',7=>'Domingo']; $franjas=$registros->map(fn($r)=>$r->hora_inicio.'|'.$r->hora_fin)->unique()->sort()->values(); @endphp
<div style="overflow-x:auto"><table class="atlas-horario-consulta"><thead><tr><th>Hora</th>@foreach($dias as $dia)<th>{{ $dia }}</th>@endforeach</tr></thead><tbody>
@foreach($franjas as $franja)
@php [$inicio,$fin]=explode('|',$franja); @endphp
<tr><td>{{ substr($inicio,0,5) }} – {{ substr($fin,0,5) }}</td>
@foreach($dias as $numero=>$dia)<td>
@foreach($registros as $fila)
@if((int)$fila->dia_semana === $numero && $fila->hora_inicio === $inicio && $fila->hora_fin === $fin)
<div class="atlas-horario-celda"><div class="atlas-horario-materia">{{ $fila->materia }}</div>@if($fila->aula)<div class="atlas-horario-secundario">Aula: {{ $fila->aula }}</div>@endif</div>
@endif
@endforeach
</td>@endforeach
</tr>@endforeach
</tbody></table></div>
@endif
@endif
</div></div>
@endsection
