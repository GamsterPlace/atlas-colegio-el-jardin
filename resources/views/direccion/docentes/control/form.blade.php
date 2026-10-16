@extends('layouts.direccion')
@section('title', $docente ? 'Editar docente' : 'Registrar docente')
@section('role', 'Dirección')
@section('module-title', 'Control de docentes')
@section('dashboard-url', route('direccion.dashboard'))
@section('content')
@include('direccion.docentes.partials.estilos')
@php($persona = $docente?->personal?->persona)
@php($personal = $docente?->personal)
<div class="container-fluid py-3 atlas-docentes"><div class="ad-heading"><div><h2>{{ $docente ? 'Editar docente' : 'Registrar docente' }}</h2><p class="ad-subtitle">Información personal y laboral.</p></div><a class="ad-btn" href="{{ route('direccion.docentes.control.index') }}">Volver al control</a></div>
@if($errors->any())<div class="ad-alert error"><strong>Revisa los campos:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form method="POST" action="{{ $docente ? route('direccion.docentes.control.update', $docente) : route('direccion.docentes.control.store') }}">@csrf @if($docente) @method('PUT') @endif
<div class="ad-card"><div class="ad-card-head"><strong>Información personal</strong></div><div class="ad-card-body"><div class="ad-grid">
@foreach(['nombres'=>'Nombres *','apellidos'=>'Apellidos *','cui'=>'CUI','telefono'=>'Teléfono','correo'=>'Correo electrónico','direccion'=>'Dirección'] as $campo=>$etiqueta)<div class="ad-field"><label for="{{ $campo }}">{{ $etiqueta }}</label><input id="{{ $campo }}" name="{{ $campo }}" value="{{ old($campo, $persona?->$campo) }}" @if(in_array($campo,['nombres','apellidos'])) required @endif @if($campo==='correo') type="email" @endif></div>@endforeach
<div class="ad-field"><label for="fecha_nacimiento">Fecha de nacimiento</label><input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $persona?->fecha_nacimiento?->format('Y-m-d')) }}"></div><div class="ad-field"><label for="sexo">Sexo</label><select name="sexo" id="sexo"><option value="">Seleccionar</option><option value="M" @selected(old('sexo',$persona?->sexo)==='M')>Masculino</option><option value="F" @selected(old('sexo',$persona?->sexo)==='F')>Femenino</option></select></div>
</div></div></div>
<div class="ad-card"><div class="ad-card-head"><strong>Información laboral</strong></div><div class="ad-card-body"><div class="ad-grid"><div class="ad-field"><label for="codigo_empleado">Código de empleado *</label><input id="codigo_empleado" name="codigo_empleado" required value="{{ old('codigo_empleado',$personal?->codigo_empleado) }}"></div><div class="ad-field"><label for="fecha_ingreso">Fecha de ingreso *</label><input type="date" id="fecha_ingreso" name="fecha_ingreso" required value="{{ old('fecha_ingreso',$personal?->fecha_ingreso?->format('Y-m-d')) }}"></div><div class="ad-field"><label for="cargo">Cargo *</label><input id="cargo" name="cargo" required value="{{ old('cargo',$personal?->cargo ?? 'Docente') }}"></div></div><div class="ad-actions"><a class="ad-btn" href="{{ route('direccion.docentes.control.index') }}">Cancelar</a><button class="ad-btn primary" type="submit">{{ $docente ? 'Guardar cambios' : 'Registrar docente' }}</button></div></div></div>
</form></div>
@endsection
