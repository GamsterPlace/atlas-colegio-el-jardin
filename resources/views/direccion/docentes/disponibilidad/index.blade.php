@extends('layouts.direccion')
@section('title', 'Disponibilidad docente')
@section('role', 'Dirección')
@section('module-title', 'Disponibilidad docente')
@section('dashboard-url', route('direccion.dashboard'))
@section('content')
@include('direccion.docentes.partials.estilos')
<div class="container-fluid py-3 atlas-docentes">
    <div class="ad-heading">
        <div>
            <h2>Disponibilidad horaria</h2>
            <p class="ad-subtitle">{{ trim(($docente->personal?->persona?->nombres ?? '').' '.($docente->personal?->persona?->apellidos ?? '')) }} · {{ $docente->personal?->codigo_empleado }}</p>
        </div>
        <a class="ad-btn" href="{{ route('direccion.docentes.show', $docente) }}"><i class="bi bi-arrow-left"></i> Volver al expediente</a>
    </div>

    @if(session('success')) <div class="ad-alert">{{ session('success') }}</div> @endif
    @if($errors->any())
        <div class="ad-card"><div class="ad-card-body" role="alert" style="color:#a52a35">
            <strong>No fue posible guardar la información.</strong>
            <ul style="margin:10px 0 0 18px">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div></div>
    @endif

    <div class="ad-card"><div class="ad-card-body">
        <form method="GET" action="{{ route('direccion.docentes.disponibilidad.index', $docente) }}">
            <div class="ad-grid two">
                <div class="ad-field">
                    <label for="ciclo_id">Ciclo escolar</label>
                    <select name="ciclo_id" id="ciclo_id" onchange="this.form.submit()">
                        @foreach($ciclos as $item)
                            <option value="{{ $item->id }}" @selected($ciclo?->id === $item->id)>{{ $item->nombre }} — {{ $item->estado }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ad-field"><label>&nbsp;</label><button type="submit" class="ad-btn primary">Consultar disponibilidad</button></div>
            </div>
        </form>
    </div></div>

    @if(!$ciclo)
        <div class="ad-card"><div class="ad-card-body">Primero registre un ciclo escolar para configurar la disponibilidad.</div></div>
    @else
        @php($editable = $ciclo->estado !== 'CERRADO' && $docente->activo && $docente->personal?->activo)
        @if($editable)
        <div class="ad-card">
            <div class="ad-card-head"><strong>Agregar franja horaria</strong></div>
            <div class="ad-card-body">
                <form method="POST" action="{{ route('direccion.docentes.disponibilidad.store', $docente) }}">
                    @csrf
                    <input type="hidden" name="ciclo_escolar_id" value="{{ $ciclo->id }}">
                    <div class="ad-grid four">
                        <div class="ad-field"><label for="dia_semana">Día</label>
                            <select name="dia_semana" id="dia_semana" required>
                                @foreach($dias as $numero => $nombre)<option value="{{ $numero }}" @selected(old('dia_semana', 1) == $numero)>{{ $nombre }}</option>@endforeach
                            </select>
                        </div>
                        <div class="ad-field"><label for="hora_inicio">Hora de inicio</label><input type="time" id="hora_inicio" name="hora_inicio" value="{{ old('hora_inicio','07:00') }}" required></div>
                        <div class="ad-field"><label for="hora_fin">Hora de finalización</label><input type="time" id="hora_fin" name="hora_fin" value="{{ old('hora_fin','13:30') }}" required></div>
                        <div class="ad-field"><label for="disponible">Condición</label>
                            <select id="disponible" name="disponible">
                                <option value="1" @selected(old('disponible','1') == '1')>Disponible</option>
                                <option value="0" @selected(old('disponible') === '0')>No disponible</option>
                            </select>
                        </div>
                    </div>
                    <div class="ad-actions"><button class="ad-btn primary" type="submit"><i class="bi bi-plus-lg"></i> Registrar franja</button></div>
                </form>
            </div>
        </div>
        @endif

        <div class="ad-card">
            <div class="ad-card-head"><strong>Franjas registradas</strong><span class="ad-count">{{ $franjas->count() }} registros</span></div>
            <div class="ad-card-body">
                <p class="ad-subtitle" style="margin-bottom:16px">Cada franja corresponde a un día y a un rango horario específico. No se permiten solapamientos, incluso entre condiciones diferentes.</p>
                <div class="ad-table-wrap"><table>
                    <thead><tr><th>Día</th><th>Desde</th><th>Hasta</th><th>Condición</th><th>Acciones</th></tr></thead>
                    <tbody>
                    @forelse($franjas as $franja)
                        <tr>
                            <td colspan="5">
                                <form id="editar-franja-{{ $franja->id }}" method="POST" action="{{ route('direccion.docentes.disponibilidad.update', [$docente, $franja]) }}">@csrf @method('PUT')</form>
                                <form id="eliminar-franja-{{ $franja->id }}" method="POST" action="{{ route('direccion.docentes.disponibilidad.destroy', [$docente, $franja]) }}" data-confirm-title="Eliminar franja horaria" data-confirm-message="¿Desea eliminar esta franja de disponibilidad? La acción no se puede deshacer.">@csrf @method('DELETE')</form>
                                <div style="display:grid;grid-template-columns:minmax(120px,1.1fr) minmax(100px,.8fr) minmax(100px,.8fr) minmax(140px,1.1fr) auto;gap:12px;align-items:center;min-width:650px">
                                    <select name="dia_semana" form="editar-franja-{{ $franja->id }}" @disabled(!$editable)>
                                        @foreach($dias as $numero => $nombre)<option value="{{ $numero }}" @selected($franja->dia_semana == $numero)>{{ $nombre }}</option>@endforeach
                                    </select>
                                    <input type="time" name="hora_inicio" value="{{ substr($franja->hora_inicio,0,5) }}" form="editar-franja-{{ $franja->id }}" @disabled(!$editable) required>
                                    <input type="time" name="hora_fin" value="{{ substr($franja->hora_fin,0,5) }}" form="editar-franja-{{ $franja->id }}" @disabled(!$editable) required>
                                    <select name="disponible" form="editar-franja-{{ $franja->id }}" @disabled(!$editable)>
                                        <option value="1" @selected($franja->disponible)>Disponible</option>
                                        <option value="0" @selected(!$franja->disponible)>No disponible</option>
                                    </select>
                                    @if($editable)
                                        <div style="display:flex;gap:8px">
                                            <button class="ad-btn primary" type="submit" form="editar-franja-{{ $franja->id }}">Guardar</button>
                                            <button class="ad-btn danger" type="submit" form="eliminar-franja-{{ $franja->id }}">Eliminar</button>
                                        </div>
                                    @else
                                        <span class="ad-tag off">Solo lectura</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="ad-empty">No hay franjas registradas para este ciclo escolar.</td></tr>
                    @endforelse
                    </tbody>
                </table></div>
            </div>
        </div>
    @endif
</div>
@include('direccion.docentes.partials.modal-confirmacion')
@endsection
