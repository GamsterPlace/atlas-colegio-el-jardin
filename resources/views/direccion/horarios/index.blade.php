@extends('layouts.direccion')
@section('module-title', 'Horarios académicos')
@section('content')
<style>
.atlas-horarios-grid{width:100%;border-collapse:collapse;min-width:760px;font-size:13px}.atlas-horarios-grid th,.atlas-horarios-grid td{border:1px solid #dce4ef;padding:10px;vertical-align:top}.atlas-horarios-grid thead th{background:#112d55;color:white;text-align:center}.atlas-horarios-grid .hora{white-space:nowrap;background:#f0f4fa;color:#243b59;font-weight:700}.atlas-horarios-grid .materia{font-weight:700;color:#12365f}.atlas-horarios-grid .docente{color:#64748b;font-size:12px;margin-top:5px}.atlas-horarios-toolbar{display:flex;gap:10px;flex-wrap:wrap;align-items:end}.atlas-horarios-periodo{background:#f5f8fc;padding:8px;border-radius:7px;margin-bottom:5px}.atlas-horarios-editar{display:flex;gap:8px;flex-wrap:wrap;align-items:end;margin:12px 0}.atlas-horarios-editar select{max-width:180px}.atlas-horarios-print-head{display:none}@media print{aside,nav,header,.atlas-page-heading,.atlas-horarios-no-print,.atlas-horarios-editar,.atlas-horarios-toolbar,.atlas-btn,#atlas-modal-horarios{display:none!important}.atlas-card{box-shadow:none!important;border:0!important;break-inside:avoid}.atlas-horarios-print-head{display:block!important}.atlas-horarios-grid{min-width:0;font-size:10px}.atlas-horarios-grid th,.atlas-horarios-grid td{padding:5px}details:not([open]){display:none!important}details>summary{display:none!important}}
</style>
<div class="atlas-page-heading"><h2>Horarios académicos</h2><p>Generación automática, revisión semanal, edición de borradores y aprobación institucional.</p></div>

@if(session('success'))<div class="atlas-alert atlas-alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())<div class="atlas-alert atlas-alert-danger"><strong>No se completó la operación.</strong><ul>
@foreach($errors->all() as $error)<li>{{ $error }}</li>
@endforeach</ul></div>
@endif
<div class="atlas-card atlas-horarios-no-print" style="margin-bottom:18px"><div class="atlas-card-body">
<form action="{{ route('direccion.horarios.index') }}" method="GET" class="atlas-horarios-toolbar"><div class="atlas-form-group"><label class="atlas-label" for="ciclo_id">Ciclo escolar</label><select class="atlas-select" name="ciclo_id" id="ciclo_id">
@foreach($ciclos as $ciclo)<option value="{{ $ciclo->id }}" @selected((int)$cicloId===(int)$ciclo->id)>{{ $ciclo->nombre }} — {{ $ciclo->estado }}</option>
@endforeach</select></div><button class="atlas-btn atlas-btn-outline" type="submit">Consultar</button></form>
<div class="atlas-horarios-toolbar" style="margin-top:15px"><form method="POST" action="{{ route('direccion.horarios.generar') }}">
@csrf<input type="hidden" name="ciclo_id" value="{{ $cicloId }}"><button class="atlas-btn atlas-btn-primary" type="submit" @disabled(!$cicloId)>Generar propuesta</button></form>
@php
    $propuesta = session('atlas_horarios_propuesta');
    $propuestaHorarios = is_array($propuesta) ? ($propuesta['horarios'] ?? []) : [];
@endphp

@if(is_array($propuesta) && (int)($propuesta['ciclo_id']??0)===(int)$cicloId)
<form method="POST" action="{{ route('direccion.horarios.guardar') }}" id="formGuardarHorarios">
@csrf<input type="hidden" name="ciclo_id" value="{{ $cicloId }}"><button class="atlas-btn atlas-btn-outline" type="button" onclick="atlasConfirmar('formGuardarHorarios','Se regenerarán y guardarán nuevas versiones como borradores. ¿Continuar?')">Guardar borradores</button></form>

@endif</div></div></div>
@php
    $dias = [1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado', 7 => 'Domingo'];
@endphp

@if(is_array($propuesta) && (int)($propuesta['ciclo_id']??0)===(int)$cicloId)
<div class="atlas-card" style="margin-bottom:18px"><div class="atlas-card-body"><h3>Vista previa — no guardada</h3><p>La propuesta todavía no es oficial. Al guardar se vuelve a generar con los datos vigentes.</p>

@foreach($propuestaHorarios as $seccionId => $detalles)
@php
    $seccionActual = $secciones->firstWhere('id', (int) $seccionId);
@endphp
@php
$jornadaBloques = $bloques->filter(function ($b) use ($seccionActual) {
    return (int) $b->jornada_id === (int) ($seccionActual?->jornada_id ?? 0) && $b->tipo === 'CLASE';
})->sortBy('hora_inicio');
@endphp
<h4 style="margin:18px 0 10px">Sección {{ $seccionActual?->nombre ?? $seccionId }}</h4>
<div style="overflow-x:auto"><table class="atlas-horarios-grid"><thead><tr><th>Período</th>
@foreach($dias as $diaNombre)<th>{{ $diaNombre }}</th>
@endforeach</tr></thead><tbody>

@foreach($jornadaBloques as $bloque)<tr><td class="hora">{{ substr($bloque->hora_inicio,0,5) }} – {{ substr($bloque->hora_fin,0,5) }}</td>
@foreach($dias as $dia=>$diaNombre)<td>
@foreach($detalles as $detalle)
@if((int)$detalle['dia_semana']===$dia && (int)$detalle['bloque_horario_id']===(int)$bloque->id)<div class="materia">{{ $cargas->get($detalle['carga_academica_id'])?->nombre ?? 'Materia no encontrada' }}</div><div class="docente">Docente #{{ $detalle['docente_id'] }}</div>
@endif
@endforeach</td>
@endforeach</tr>
@endforeach
</tbody></table></div>

@endforeach</div></div>

@endif

@forelse($secciones as $seccion)
@php
    $versiones = $horarios->get($seccion->id, collect());
@endphp
@php
$jornadaBloques = $bloques->filter(function ($b) use ($seccion) {
    return (int) $b->jornada_id === (int) $seccion->jornada_id && $b->tipo === 'CLASE';
})->sortBy('hora_inicio');
@endphp
<div class="atlas-card" style="margin-bottom:18px"><div class="atlas-card-body"><div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap"><h3>Sección {{ $seccion->nombre }} — Horarios</h3><span>{{ $versiones->count() }} versión(es)</span></div>
@php
    $oficial = $versiones->first(fn ($item) => $item->estado === 'APROBADO');
@endphp
@if($oficial)
<p style="margin:10px 0;color:#17603b;font-weight:700">Horario oficial publicado: versión {{ $oficial->version }}. Disponible para consulta e impresión desde Dirección.</p>
@else
<p style="margin:10px 0;color:#7b4d0b">Esta sección todavía no tiene un horario oficial aprobado.</p>
@endif

@forelse($versiones as $horario)
<details style="margin:12px 0;padding:12px;border:1px solid #d9e2ef;border-radius:8px"
@if($loop->first) open
@endif><summary style="cursor:pointer"><strong>Versión {{ $horario->version }}</strong> · {{ $horario->estado === 'APROBADO' ? 'OFICIAL PUBLICADO' : $horario->estado }} · {{ $horario->origen }}</summary>
<div class="atlas-horarios-no-print atlas-horarios-toolbar" style="margin:12px 0"><button class="atlas-btn atlas-btn-outline" type="button" onclick="atlasImprimirHorario(this)">Imprimir / Guardar PDF</button>

@if($horario->estado==='BORRADOR')<form method="POST" action="{{ route('direccion.horarios.aprobar',$horario->id) }}" id="aprobar-{{ $horario->id }}">
@csrf<button class="atlas-btn atlas-btn-primary" type="button" onclick="atlasConfirmar('aprobar-{{ $horario->id }}','¿Aprobar esta versión? Reemplazará el horario oficial de esta sección.')">Aprobar versión</button></form>
@endif</div>
<div class="atlas-horarios-print-head"><h2>Colegio Mixto El Jardín</h2><p>Horario académico — Sección {{ $seccion->nombre }} — Versión {{ $horario->version }} — {{ $horario->estado === 'APROBADO' ? 'OFICIAL' : 'NO OFICIAL' }}</p></div>
<div style="overflow-x:auto"><table class="atlas-horarios-grid"><thead><tr><th>Período</th>
@foreach($dias as $diaNombre)<th>{{ $diaNombre }}</th>
@endforeach</tr></thead><tbody>

@foreach($jornadaBloques as $bloque)<tr><td class="hora">{{ substr($bloque->hora_inicio,0,5) }} – {{ substr($bloque->hora_fin,0,5) }}</td>
@foreach($dias as $dia=>$diaNombre)<td>
@foreach($horario->registrosHorarioDetalle as $detalle)
@if((int)$detalle->dia_semana===$dia && (int)$detalle->bloque_horario_id===(int)$bloque->id)<div class="materia">{{ $cargas->get($detalle->carga_academica_id)?->nombre ?? 'Materia no encontrada' }}</div><div class="docente">Docente #{{ $detalle->docente_id }}</div>
@endif
@endforeach</td>
@endforeach</tr>
@endforeach
</tbody></table></div>

@if($horario->estado==='BORRADOR')
<details class="atlas-horarios-no-print" style="margin-top:16px"><summary style="cursor:pointer;font-weight:600">Editar períodos del borrador</summary><p>Selecciona el día y el bloque de destino. ATLAS validará el horario completo antes de aplicar el cambio.</p>

@foreach($horario->registrosHorarioDetalle->sortBy('dia_semana') as $detalle)
<form class="atlas-horarios-editar" method="POST" action="{{ route('direccion.horarios.detalles.actualizar',[$horario->id,$detalle->id]) }}">
@csrf
@method('PATCH')<div style="min-width:180px"><strong>{{ $cargas->get($detalle->carga_academica_id)?->nombre ?? 'Materia' }}</strong><div class="docente">Docente #{{ $detalle->docente_id }}</div></div><div><label class="atlas-label">Día</label><select class="atlas-select" name="dia_semana">
@foreach($dias as $dia=>$nombre)<option value="{{ $dia }}" @selected((int)$detalle->dia_semana===$dia)>{{ $nombre }}</option>
@endforeach</select></div><div><label class="atlas-label">Período</label><select class="atlas-select" name="bloque_horario_id">
@foreach($jornadaBloques as $bloque)<option value="{{ $bloque->id }}" @selected((int)$detalle->bloque_horario_id===(int)$bloque->id)>{{ substr($bloque->hora_inicio,0,5) }} – {{ substr($bloque->hora_fin,0,5) }}</option>
@endforeach</select></div><button class="atlas-btn atlas-btn-outline" type="submit">Guardar cambio</button></form>

@endforeach</details>

@endif
</details>

@empty<p>No hay horarios guardados para esta sección.</p>
@endforelse
</div></div>

@empty<div class="atlas-card"><div class="atlas-card-body">No hay secciones registradas para el ciclo seleccionado.</div></div>
@endforelse
<div id="atlas-modal-horarios" role="dialog" aria-modal="true" aria-labelledby="atlas-modal-titulo" style="display:none;position:fixed;inset:0;background:rgba(10,22,45,.55);z-index:9999;align-items:center;justify-content:center;padding:20px"><div style="max-width:440px;width:100%;background:#fff;border-radius:12px;padding:24px;box-shadow:0 16px 50px #0003"><h3 id="atlas-modal-titulo">Confirmar operación</h3><p id="atlas-modal-mensaje"></p><div style="display:flex;justify-content:end;gap:10px"><button class="atlas-btn atlas-btn-outline" type="button" onclick="atlasCerrarModal()">Cancelar</button><button class="atlas-btn atlas-btn-primary" type="button" id="atlas-modal-aceptar">Continuar</button></div></div></div>
<script>
function atlasConfirmar(id,mensaje){const modal=document.getElementById('atlas-modal-horarios');document.getElementById('atlas-modal-mensaje').textContent=mensaje;document.getElementById('atlas-modal-aceptar').onclick=function(){document.getElementById(id).submit()};modal.style.display='flex'}
function atlasCerrarModal(){document.getElementById('atlas-modal-horarios').style.display='none'}
function atlasImprimirHorario(boton){const detalles=boton.closest('details');document.querySelectorAll('.atlas-horarios-impresion').forEach(x=>x.classList.remove('atlas-horarios-impresion'));detalles.classList.add('atlas-horarios-impresion');window.print();detalles.classList.remove('atlas-horarios-impresion')}
</script>
<style>@media print{.atlas-card:has(details):not(:has(.atlas-horarios-impresion)){display:none!important}.atlas-horarios-impresion{display:block!important}.atlas-horarios-impresion>summary{display:none!important}}</style>
@endsection
