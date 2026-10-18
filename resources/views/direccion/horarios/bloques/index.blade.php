@extends('layouts.direccion')
@section('module-title', 'Bloques horarios')
@section('content')
<div class="atlas-page-heading"><h2>Bloques horarios</h2><p>Configuración de períodos, recreos y otros bloques por jornada.</p></div>
@if(session('success'))<div class="atlas-alert atlas-alert-success">{{ session('success') }}</div>@endif
<div class="atlas-card"><div class="atlas-card-body">
<div style="display:flex;justify-content:space-between;gap:15px;flex-wrap:wrap;align-items:end;margin-bottom:20px">
<form method="GET" action="{{ route('direccion.horarios.bloques.index') }}"><label class="atlas-label" for="jornada_id">Jornada</label><select class="atlas-select" id="jornada_id" name="jornada_id" onchange="this.form.submit()">@foreach($jornadas as $jornada)<option value="{{ $jornada->id }}" @selected($jornadaId == $jornada->id)>{{ $jornada->nombre }}{{ $jornada->activo ? '' : ' (Inactiva)' }}</option>@endforeach</select><noscript><button type="submit">Consultar</button></noscript></form>
<a class="atlas-btn atlas-btn-primary" href="{{ route('direccion.horarios.bloques.create', ['jornada_id' => $jornadaId]) }}"><i class="bi bi-plus-lg"></i> Nuevo bloque</a></div>
<div class="atlas-table-responsive"><table class="atlas-table"><thead><tr><th>Orden</th><th>Nombre</th><th>Tipo</th><th>Inicio</th><th>Fin</th><th>Duración</th><th>Acción</th></tr></thead><tbody>
@forelse($bloques as $bloque)<tr><td>{{ $bloque->orden }}</td><td class="atlas-table-primary">{{ $bloque->nombre }}</td><td>{{ $bloque->tipo }}</td><td>{{ substr($bloque->hora_inicio,0,5) }}</td><td>{{ substr($bloque->hora_fin,0,5) }}</td><td>{{ \Carbon\Carbon::parse($bloque->hora_inicio)->diffInMinutes(\Carbon\Carbon::parse($bloque->hora_fin)) }} min</td><td><a class="atlas-btn atlas-btn-outline atlas-btn-sm" href="{{ route('direccion.horarios.bloques.edit', $bloque->id) }}">Editar</a></td></tr>
@empty<tr><td colspan="7" style="text-align:center;padding:35px">No hay bloques configurados para esta jornada.</td></tr>@endforelse
</tbody></table></div></div></div>
@endsection
