@extends('layouts.direccion')
@section('module-title', 'Restricciones académicas')
@section('content')
<div class="atlas-page-heading">
    <h2>Restricciones académicas</h2>
    <p>Reglas que utilizará el generador de horarios para distribuir clases y evitar conflictos.</p>
</div>

@if(session('success'))
    <div class="atlas-alert atlas-alert-success">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="atlas-alert" style="border:1px solid #dc3545;color:#a52834;padding:12px;margin-bottom:16px">
        <strong>Revisa los datos:</strong>
        <ul style="margin:8px 0 0 20px">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<div class="atlas-card">
    <div class="atlas-card-body">
        <form method="GET" action="{{ route('direccion.horarios.restricciones.index') }}">
            <label class="atlas-label" for="ciclo_escolar_id_filtro">Ciclo escolar</label>
            <select class="atlas-select" id="ciclo_escolar_id_filtro" name="ciclo_escolar_id" onchange="this.form.submit()">
                @foreach($ciclos as $item)
                    <option value="{{ $item->id }}" @selected($ciclo && $ciclo->id == $item->id)>{{ $item->nombre }} ({{ $item->estado }})</option>
                @endforeach
            </select>
            <noscript><button class="atlas-btn atlas-btn-primary" type="submit">Consultar</button></noscript>
        </form>
    </div>
</div>

@if($ciclo)
    @if($ciclo->estado !== 'CERRADO')
    <div class="atlas-card" style="margin-top:18px">
        <div class="atlas-card-body">
            <h3 style="margin-top:0">Nueva restricción</h3>
            <form method="POST" action="{{ route('direccion.horarios.restricciones.store') }}">
                @csrf
                <input type="hidden" name="ciclo_escolar_id" value="{{ $ciclo->id }}">
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px">
                    <div>
                        <label class="atlas-label" for="tipo">Tipo de regla</label>
                        <select class="atlas-select" name="tipo" id="tipo" required>
                            @foreach($tipos as $clave => $etiqueta)
                                <option value="{{ $clave }}" @selected(old('tipo') === $clave)>{{ $etiqueta }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="atlas-label" for="docente_id">Docente</label>
                        <select class="atlas-select" name="docente_id" id="docente_id">
                            <option value="">Sin docente específico</option>
                            @foreach($docentes as $docente)
                                @php($persona = $docente->personal?->persona)
                                <option value="{{ $docente->id }}" @selected(old('docente_id') == $docente->id)>
                                    {{ trim(($persona?->nombres ?? '') . ' ' . ($persona?->apellidos ?? '')) ?: 'Nombre no registrado' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="atlas-label" for="seccion_id">Sección (solo bloqueos)</label>
                        <select class="atlas-select" name="seccion_id" id="seccion_id">
                            <option value="">Sin sección específica</option>
                            @foreach($secciones as $seccion)
                                <option value="{{ $seccion->id }}" @selected(old('seccion_id') == $seccion->id)>Sección {{ $seccion->nombre }} — ID {{ $seccion->id }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="atlas-label" for="maximo">Máximo de clases (reglas de límite)</label>
                        <input class="atlas-input" type="number" name="maximo" id="maximo" min="1" max="20" value="{{ old('maximo', 3) }}">
                    </div>
                    <div>
                        <label class="atlas-label" for="dia_semana">Día (solo bloqueos)</label>
                        <select class="atlas-select" name="dia_semana" id="dia_semana">
                            <option value="">Seleccionar día</option>
                            @foreach([1=>'Lunes',2=>'Martes',3=>'Miércoles',4=>'Jueves',5=>'Viernes',6=>'Sábado',7=>'Domingo'] as $numero=>$dia)
                                <option value="{{ $numero }}" @selected(old('dia_semana') == $numero)>{{ $dia }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="atlas-label" for="bloque_horario_id">Período (solo bloqueos)</label>
                        <select class="atlas-select" name="bloque_horario_id" id="bloque_horario_id">
                            <option value="">Seleccionar período</option>
                            @foreach($bloques as $bloque)
                                <option value="{{ $bloque->id }}" @selected(old('bloque_horario_id') == $bloque->id)>{{ $bloque->nombre }} — {{ substr($bloque->hora_inicio, 0, 5) }} a {{ substr($bloque->hora_fin, 0, 5) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="atlas-label" for="prioridad">Prioridad (1 a 100)</label>
                        <input class="atlas-input" type="number" name="prioridad" id="prioridad" min="1" max="100" value="{{ old('prioridad', 1) }}" required>
                    </div>
                    <div>
                        <label class="atlas-label" for="obligatoria">Cumplimiento</label>
                        <select class="atlas-select" name="obligatoria" id="obligatoria">
                            <option value="1" @selected(old('obligatoria','1') == '1')>Obligatoria</option>
                            <option value="0" @selected(old('obligatoria') == '0')>Preferencia</option>
                        </select>
                    </div>
                </div>
                <p style="font-size:13px;opacity:.8;margin:14px 0">Para los límites selecciona docente y máximo. Para bloquear un período selecciona día, bloque y al menos una sección o docente. Las reglas se aplicarán cuando se integre el generador.</p>
                <button type="submit" class="atlas-btn atlas-btn-primary"><i class="bi bi-plus-lg"></i> Guardar restricción</button>
            </form>
        </div>
    </div>
    @else
        <div class="atlas-alert" style="margin-top:18px">Este ciclo está cerrado. Las restricciones se muestran en modo consulta.</div>
    @endif

    <div class="atlas-card" style="margin-top:18px">
        <div class="atlas-card-body">
            <h3 style="margin-top:0">Restricciones registradas</h3>
            <div class="atlas-table-responsive">
                <table class="atlas-table">
                    <thead><tr><th>Tipo</th><th>Ámbito</th><th>Parámetros</th><th>Prioridad</th><th>Cumplimiento</th><th>Acciones</th></tr></thead>
                    <tbody>
                    @forelse($restricciones as $regla)
                        <tr>
                            <td class="atlas-table-primary">{{ $tipos[$regla->tipo] ?? $regla->tipo }}</td>
                            <td>
                                @if($regla->docente_id){{ trim(($regla->docente?->personal?->persona?->nombres ?? '') . ' ' . ($regla->docente?->personal?->persona?->apellidos ?? '')) ?: 'Nombre no registrado' }}@endif
                                @if($regla->seccion_id) · Sección {{ $regla->seccion?->nombre ?? $regla->seccion_id }}@endif
                            </td>
                            <td>
                                @if(isset($regla->parametros['maximo']))
                                    Máximo: {{ $regla->parametros['maximo'] }}
                                @else
                                    Día: {{ [1=>'Lun',2=>'Mar',3=>'Mié',4=>'Jue',5=>'Vie',6=>'Sáb',7=>'Dom'][$regla->parametros['dia_semana'] ?? 0] ?? '—' }};
                                    bloque #{{ $regla->parametros['bloque_horario_id'] ?? '—' }}
                                @endif
                            </td>
                            <td>{{ $regla->prioridad }}</td>
                            <td>{{ $regla->obligatoria ? 'Obligatoria' : 'Preferencia' }}</td>
                            <td>
                                @if($ciclo->estado !== 'CERRADO')
                                <button type="button" class="atlas-btn atlas-btn-outline atlas-btn-sm" onclick="document.getElementById('editar-{{ $regla->id }}').hidden = !document.getElementById('editar-{{ $regla->id }}').hidden">Editar</button>
                                <button type="button" class="atlas-btn atlas-btn-outline atlas-btn-sm" onclick="document.getElementById('eliminar-{{ $regla->id }}').hidden = false">Eliminar</button>
                                @else — @endif
                            </td>
                        </tr>
                        @if($ciclo->estado !== 'CERRADO')
                        <tr id="editar-{{ $regla->id }}" hidden>
                            <td colspan="6">
                                <form method="POST" action="{{ route('direccion.horarios.restricciones.update', $regla) }}">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="ciclo_escolar_id" value="{{ $ciclo->id }}">
                                    <input type="hidden" name="tipo" value="{{ $regla->tipo }}">
                                    <input type="hidden" name="docente_id" value="{{ $regla->docente_id }}">
                                    <input type="hidden" name="seccion_id" value="{{ $regla->seccion_id }}">
                                    <input type="hidden" name="dia_semana" value="{{ $regla->parametros['dia_semana'] ?? '' }}">
                                    <input type="hidden" name="bloque_horario_id" value="{{ $regla->parametros['bloque_horario_id'] ?? '' }}">
                                    <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:end">
                                        @if(isset($regla->parametros['maximo']))
                                            <div><label class="atlas-label">Máximo</label><input class="atlas-input" name="maximo" type="number" min="1" max="20" value="{{ $regla->parametros['maximo'] }}" required></div>
                                        @endif
                                        <div><label class="atlas-label">Prioridad</label><input class="atlas-input" name="prioridad" type="number" min="1" max="100" value="{{ $regla->prioridad }}" required></div>
                                        <div><label class="atlas-label">Cumplimiento</label><select class="atlas-select" name="obligatoria"><option value="1" @selected($regla->obligatoria)>Obligatoria</option><option value="0" @selected(!$regla->obligatoria)>Preferencia</option></select></div>
                                        <button class="atlas-btn atlas-btn-primary" type="submit">Guardar cambios</button>
                                        <button class="atlas-btn atlas-btn-outline" type="button" onclick="document.getElementById('editar-{{ $regla->id }}').hidden = true">Cancelar</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        <tr id="eliminar-{{ $regla->id }}" hidden>
                            <td colspan="6">
                                <form method="POST" action="{{ route('direccion.horarios.restricciones.destroy', $regla) }}">
                                    @csrf @method('DELETE')
                                    <strong>¿Eliminar esta restricción?</strong>
                                    <button type="submit" class="atlas-btn atlas-btn-primary atlas-btn-sm">Sí, eliminar</button>
                                    <button type="button" class="atlas-btn atlas-btn-outline atlas-btn-sm" onclick="document.getElementById('eliminar-{{ $regla->id }}').hidden = true">Cancelar</button>
                                </form>
                            </td>
                        </tr>
                        @endif
                    @empty
                        <tr><td colspan="6" style="text-align:center;padding:35px">No hay restricciones configuradas para este ciclo.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@else
    <div class="atlas-card" style="margin-top:18px"><div class="atlas-card-body">No hay ciclos escolares registrados.</div></div>
@endif
@endsection
