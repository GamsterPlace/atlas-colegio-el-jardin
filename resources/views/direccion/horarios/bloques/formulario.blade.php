@extends('layouts.direccion')
@section('module-title', $bloque ? 'Editar bloque horario' : 'Nuevo bloque horario')
@section('content')
<div class="atlas-page-heading"><h2>{{ $bloque ? 'Editar bloque horario' : 'Nuevo bloque horario' }}</h2><p>Los bloques deben respetar la jornada y no superponerse.</p></div>
@if($errors->any())<div class="atlas-alert atlas-alert-danger">Revisa los campos señalados.</div>@endif
<div class="atlas-card" style="max-width:850px"><div class="atlas-card-body">
<form method="POST" action="{{ $bloque ? route('direccion.horarios.bloques.update',$bloque->id) : route('direccion.horarios.bloques.store') }}">@csrf @if($bloque) @method('PUT') @endif
<div class="atlas-grid atlas-grid-2">
<div class="atlas-form-group"><label class="atlas-label" for="jornada_id">Jornada *</label><select class="atlas-select" name="jornada_id" id="jornada_id" required><option value="">Seleccione</option>@foreach($jornadas as $jornada)<option value="{{ $jornada->id }}" @selected(old('jornada_id',$jornadaId)==$jornada->id)>{{ $jornada->nombre }}</option>@endforeach</select>@error('jornada_id')<small style="color:#b3263e">{{ $message }}</small>@enderror</div>
<div class="atlas-form-group"><label class="atlas-label" for="tipo">Tipo *</label><select class="atlas-select" name="tipo" id="tipo" required>@foreach(['CLASE'=>'Clase','RECESO'=>'Receso','OTRO'=>'Otro'] as $key=>$label)<option value="{{ $key }}" @selected(old('tipo',$bloque->tipo ?? 'CLASE')===$key)>{{ $label }}</option>@endforeach</select>@error('tipo')<small style="color:#b3263e">{{ $message }}</small>@enderror</div>
<div class="atlas-form-group"><label class="atlas-label" for="nombre">Nombre *</label><input class="atlas-input" id="nombre" name="nombre" maxlength="80" required value="{{ old('nombre',$bloque->nombre ?? '') }}">@error('nombre')<small style="color:#b3263e">{{ $message }}</small>@enderror</div>
<div class="atlas-form-group"><label class="atlas-label" for="orden">Orden *</label><input class="atlas-input" type="number" min="1" max="65535" id="orden" name="orden" required value="{{ old('orden',$ordenSugerido) }}"><span class="atlas-form-help">Número único por jornada.</span>@error('orden')<small style="color:#b3263e">{{ $message }}</small>@enderror</div>
<div class="atlas-form-group"><label class="atlas-label" for="hora_inicio">Inicio *</label><input class="atlas-input" type="time" id="hora_inicio" name="hora_inicio" required value="{{ old('hora_inicio',$bloque ? substr($bloque->hora_inicio,0,5) : '') }}">@error('hora_inicio')<small style="color:#b3263e">{{ $message }}</small>@enderror</div>
<div class="atlas-form-group"><label class="atlas-label" for="hora_fin">Fin *</label><input class="atlas-input" type="time" id="hora_fin" name="hora_fin" required value="{{ old('hora_fin',$bloque ? substr($bloque->hora_fin,0,5) : '') }}">@error('hora_fin')<small style="color:#b3263e">{{ $message }}</small>@enderror</div>
</div><div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:20px"><button type="submit" class="atlas-btn atlas-btn-primary">{{ $bloque ? 'Guardar cambios' : 'Registrar bloque' }}</button><a class="atlas-btn atlas-btn-outline" href="{{ route('direccion.horarios.bloques.index',['jornada_id'=>$jornadaId]) }}">Regresar</a></div>
</form></div></div>
@endsection
