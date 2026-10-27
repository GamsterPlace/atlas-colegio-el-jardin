@extends('layouts.administrador')
@section('title', 'Editar usuario')
@section('module-title', 'Administración de usuarios')
@section('content')
<style>
.atlas-edit { max-width: 850px; margin: 25px auto 50px; padding: 0 18px; color:#183451; }
.atlas-edit .panel { background:white; padding:25px; border:1px solid #dce5f0; border-radius:14px; margin:15px 0; }
.atlas-edit h1 { color:#163b70; font-size:26px; }
.atlas-edit label { display:block; margin:15px 0 6px; font-weight:700; }
.atlas-edit .field { width:100%; border:1px solid #cbd9e8; border-radius:8px; padding:11px; font:inherit; }
.atlas-edit .roles { display:grid; grid-template-columns:1fr 1fr; gap:10px; margin:12px 0; }
.atlas-edit .role { border:1px solid #dce5f0; border-radius:9px; padding:12px; }
.atlas-edit button { background:#195c9d; color:white; border:0; border-radius:8px; padding:12px 17px; font-weight:700; cursor:pointer; }
.atlas-edit .danger { background:#a4362d; }
.atlas-edit .error { color:#a4362d; }
@media(max-width:600px){.atlas-edit .roles{grid-template-columns:1fr}}
</style>
<div class="atlas-edit">
<h1>Editar usuario</h1>
<p>{{ $usuario->persona?->nombres }} {{ $usuario->persona?->apellidos }} · <a href="{{ route('administrador.usuarios.show', $usuario) }}">Volver al detalle</a></p>
@if($errors->any())<div class="panel error"><strong>Corrija los errores:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<div class="panel">
<form method="POST" action="{{ route('administrador.usuarios.update', $usuario) }}">
@csrf @method('PUT')
<label for="nombre_usuario">Nombre de usuario (editable)</label>
<input class="field" id="nombre_usuario" name="nombre_usuario" value="{{ old('nombre_usuario', $usuario->nombre_usuario) }}" required minlength="3" maxlength="100" pattern="[A-Za-z0-9._-]+">
<label for="email">Correo de la cuenta (opcional)</label>
<input class="field" type="email" id="email" name="email" value="{{ old('email', $usuario->email) }}" maxlength="150">
<label>Roles institucionales</label>
<div class="roles">@foreach($roles as $rol)<label class="role"><input type="checkbox" name="roles[]" value="{{ $rol->id }}" @checked(in_array((string)$rol->id, array_map('strval', old('roles', $usuario->roles->pluck('id')->all())), true))> {{ $rol->nombre }}</label>@endforeach</div>
<label for="activo">Estado de la cuenta</label>
<select class="field" name="activo" id="activo"><option value="1" @selected((string)old('activo', (int)$usuario->activo)==='1')>Activa</option><option value="0" @selected((string)old('activo', (int)$usuario->activo)==='0')>Inactiva</option></select>
<p><button type="submit">Guardar cambios</button></p>
</form></div>
<div class="panel"><h2>Restablecer contraseña</h2><p>Genera una contraseña temporal nueva y habilita una ficha PDF de entrega única durante 10 minutos. La contraseña anterior dejará de funcionar.</p>
<form method="POST" action="{{ route('administrador.usuarios.reset-password', $usuario) }}" onsubmit="return confirm('¿Restablecer la contraseña de este usuario?');">
@csrf <label><input type="checkbox" name="confirmar" value="1" required> Confirmo el restablecimiento de contraseña</label>
<p><button class="danger" type="submit">Generar contraseña temporal</button></p></form></div>
</div>
@endsection
