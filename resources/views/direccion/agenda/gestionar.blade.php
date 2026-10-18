@extends('layouts.direccion')
@section('title','Gestionar actividades')
@section('role','Dirección')
@section('module-title','Agenda académica')
@section('dashboard-url',route('direccion.dashboard'))
@section('content')
@vite('resources/css/direccion/agenda.css')
<div class="agenda-enlaces-complementos"><a href="{{ route('direccion.agenda.calendario-escolar.index') }}"><i class="bi bi-calendar-check"></i> Días lectivos y feriados</a><a href="{{ route('direccion.agenda.recordatorios.index') }}"><i class="bi bi-bell"></i> Recordatorios</a></div>
<div class="container-fluid py-3 atlas-agenda"><div class="d-flex flex-wrap justify-content-between align-items-center mb-3"><div><h3>Gestionar actividades</h3><p class="text-muted">Administración de eventos académicos.</p></div><div class="d-flex gap-2"><a class="btn btn-outline-primary" href="{{ route('direccion.agenda.index') }}">Calendario</a><a class="btn btn-primary" href="{{ route('direccion.agenda.create') }}">Nueva actividad</a></div></div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
<form class="card card-body atlas-filtros border-0 shadow-sm mb-3" method="GET"><div class="row g-2"><div class="col-md-5"><label for="ag-ciclo">Ciclo escolar</label><select id="ag-ciclo" name="ciclo_escolar_id" class="form-select">@foreach($ciclos as $c)<option value="{{ $c->id }}" @selected($cicloId==$c->id)>{{ $c->nombre }}</option>@endforeach</select></div><div class="col-md-4"><label for="ag-estado">Estado</label><select id="ag-estado" class="form-select" name="estado"><option value="">Todos los estados</option>@foreach(['PROGRAMADO','REALIZADO','CANCELADO'] as $e)<option value="{{ $e }}" @selected(request('estado')===$e)>{{ ucfirst(strtolower($e)) }}</option>@endforeach</select></div><div class="col-md-3"><button class="btn btn-primary w-100">Filtrar</button></div></div></form>
<div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead><tr><th>Actividad</th><th>Fecha</th><th>Visibilidad</th><th>Estado</th><th>Acciones</th></tr></thead><tbody>@forelse($eventos as $e)<tr><td><strong>{{ $e->titulo }}</strong><div class="small text-muted">{{ $e->ubicacion ?: 'Sin ubicación' }}</div></td><td>{{ $e->inicio?->format('d/m/Y H:i') }}</td><td><span class="atlas-tag">{{ ucfirst(strtolower($e->visibilidad)) }}</span></td><td><span class="atlas-state atlas-state--{{ strtolower($e->estado) }}">{{ ucfirst(strtolower($e->estado)) }}</span></td><td><div class="atlas-actions">@if($e->estado==='PROGRAMADO')<a class="btn btn-sm btn-outline-primary" href="{{ route('direccion.agenda.edit',$e) }}">Editar</a><button type="button" class="btn btn-sm btn-outline-success" data-id="{{ $e->id }}" data-estado="REALIZADO" data-titulo="{{ $e->titulo }}" onclick="abrirCambio(this)">Realizada</button><button type="button" class="btn btn-sm btn-outline-danger" data-id="{{ $e->id }}" data-estado="CANCELADO" data-titulo="{{ $e->titulo }}" onclick="abrirCambio(this)">Cancelar</button>@endif</div></td></tr>@empty<tr><td colspan="5" class="text-center text-muted py-4">No hay actividades para estos filtros.</td></tr>@endforelse</tbody></table></div><div class="card-footer bg-white">{{ $eventos->links() }}</div></div></div>
<div class="atlas-modal" id="modalEstadoAgenda" hidden role="dialog" aria-modal="true" aria-labelledby="tituloEstadoAgenda">
 <div class="atlas-modal__panel">
  <form id="formEstadoAgenda" method="POST">
   @csrf @method('PATCH')
   <div class="atlas-modal__header"><h4 id="tituloEstadoAgenda">Confirmar cambio de estado</h4><button type="button" class="atlas-modal__close" data-cerrar-agenda aria-label="Cerrar">&times;</button></div>
   <div class="atlas-modal__body"><p id="textoEstadoAgenda"></p><input type="hidden" name="estado" id="nuevoEstadoAgenda"></div>
   <div class="atlas-modal__footer"><button type="button" class="btn btn-outline-secondary" data-cerrar-agenda>Volver</button><button class="btn btn-primary" type="submit">Confirmar</button></div>
  </form>
 </div>
</div>
<script>
(function(){
 const modal=document.getElementById('modalEstadoAgenda');
 const cerrar=()=>{modal.hidden=true;document.body.classList.remove('atlas-modal-abierto');};
 window.abrirCambio=function(b){
  const estado=b.dataset.estado;
  document.getElementById('formEstadoAgenda').action=@json(url('/direccion/agenda'))+'/'+encodeURIComponent(b.dataset.id)+'/estado';
  document.getElementById('nuevoEstadoAgenda').value=estado;
  document.getElementById('textoEstadoAgenda').textContent='¿Desea marcar «'+b.dataset.titulo+'» como '+(estado==='CANCELADO'?'cancelada':'realizada')+'?';
  modal.hidden=false;document.body.classList.add('atlas-modal-abierto');
  modal.querySelector('[data-cerrar-agenda]').focus();
 };
 modal.querySelectorAll('[data-cerrar-agenda]').forEach(b=>b.addEventListener('click',cerrar));
 modal.addEventListener('click',e=>{if(e.target===modal)cerrar();});
 document.addEventListener('keydown',e=>{if(e.key==='Escape'&&!modal.hidden)cerrar();});
})();
</script>
@endsection
