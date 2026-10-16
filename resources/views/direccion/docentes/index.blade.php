@extends('layouts.direccion')
@section('title', 'Docentes')
@section('role', 'Dirección')
@section('module-title', 'Listado de docentes')
@section('dashboard-url', route('direccion.dashboard'))
@section('content')
@include('direccion.docentes.partials.estilos')
<div class="container-fluid py-3 atlas-docentes">
<div class="ad-heading"><div><h2>Listado de docentes</h2><p class="ad-subtitle">Consulta de personal docente registrado en ATLAS.</p></div><a class="ad-btn primary" href="{{ route('direccion.docentes.control.create') }}"><i class="bi bi-person-plus"></i> Registrar docente</a></div>
<div class="ad-card"><div class="ad-card-body"><form method="GET" action="{{ route('direccion.docentes.index') }}"><div class="ad-grid"><div class="ad-field"><label for="buscar">Buscar docente</label><input id="buscar" name="buscar" maxlength="120" value="{{ $buscar }}" placeholder="Nombre, CUI o código de empleado"></div><div class="ad-field"><label for="estado">Estado</label><select id="estado" name="estado"><option value="">Todos</option><option value="1" @selected($estado === '1')>Activo</option><option value="0" @selected($estado === '0')>Inactivo</option></select></div><div class="ad-field"><label>&nbsp;</label><div style="display:flex;gap:9px;flex-wrap:wrap"><button class="ad-btn primary" type="submit">Buscar</button><a class="ad-btn" href="{{ route('direccion.docentes.index') }}">Limpiar</a></div></div></div></form></div></div>
<div class="ad-card"><div class="ad-card-head"><strong>Docentes</strong><span class="ad-count">{{ $docentes->total() }} registros</span></div><div class="ad-table-wrap"><table><thead><tr><th>Docente</th><th>Código empleado</th><th>Cargo</th><th>Estado</th><th>Asignaciones activas</th><th>Acciones</th></tr></thead><tbody>@forelse($docentes as $docente)<tr><td><strong>{{ trim(($docente->personal?->persona?->nombres ?? '').' '.($docente->personal?->persona?->apellidos ?? '')) ?: 'Sin nombre' }}</strong></td><td>{{ $docente->personal?->codigo_empleado ?? '—' }}</td><td>{{ $docente->personal?->cargo ?? '—' }}</td><td><span class="ad-tag {{ $docente->activo ? '' : 'off' }}">{{ $docente->activo ? 'Activo' : 'Inactivo' }}</span></td><td>{{ $docente->asignaciones_activas_count }}</td><td><a class="ad-btn" href="{{ route('direccion.docentes.show', $docente) }}">Ver expediente</a></td></tr>@empty<tr><td class="ad-empty" colspan="6">No se encontraron docentes.</td></tr>@endforelse</tbody></table></div><div class="ad-footer">{{ $docentes->links() }}</div></div>
</div>
@endsection
