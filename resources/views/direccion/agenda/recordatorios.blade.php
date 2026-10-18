@extends('layouts.direccion')
@section('title','Recordatorios de agenda')
@section('role','Dirección')
@section('module-title','Agenda académica · Recordatorios')
@section('dashboard-url',route('direccion.dashboard'))
@section('content')
@vite('resources/css/direccion/agenda.css')
<div class="container-fluid py-3 atlas-agenda agenda-complementos"><div class="agenda-complementos-header"><div><h2>Recordatorios programados</h2><p>Seguimiento de recordatorios asociados a los responsables de actividades.</p></div><a class="btn btn-outline-primary" href="{{ route('direccion.agenda.gestionar') }}">Gestionar actividades</a></div>
<div class="agenda-panel-complemento"><form method="GET" class="agenda-fila-filtros"><label>Ciclo escolar<select name="ciclo_escolar_id" class="form-select" onchange="this.form.submit()">@foreach($ciclos as $c)<option value="{{ $c->id }}" @selected($cicloId==$c->id)>{{ $c->nombre }}</option>@endforeach</select></label></form></div>
<div class="agenda-panel-complemento"><div class="table-responsive"><table class="table"><thead><tr><th>Actividad</th><th>Responsable</th><th>Fecha programada</th><th>Estado de envío</th></tr></thead><tbody>@forelse($recordatorios as $r)<tr><td>{{ $r->evento_agenda?->titulo ?? '—' }}</td><td>{{ $r->usuario?->nombre_usuario ?? '—' }}</td><td>{{ $r->recordar_at?->format('d/m/Y H:i') }}</td><td><span class="atlas-tag">{{ $r->enviado_at ? 'Enviado' : 'Pendiente de entrega' }}</span></td></tr>@empty<tr><td colspan="4">No hay recordatorios programados.</td></tr>@endforelse</tbody></table></div>{{ $recordatorios->links() }}<p class="agenda-aviso">Los recordatorios pendientes todavía no se envían automáticamente. No se marcarán como enviados hasta configurar y ejecutar un mecanismo real de entrega.</p></div></div>
@endsection
