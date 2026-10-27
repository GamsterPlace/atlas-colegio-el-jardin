@extends('layouts.administrador')

@section('title', 'Detalle de usuario')
@section('module-title', 'Detalle de usuario')

@section('content')
<style>
    .atlas-user-show { max-width: 1100px; margin: 0 auto; padding: 24px 20px 44px; color: #173554; }
    .atlas-user-show * { box-sizing: border-box; }
    .atlas-user-show .heading { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 14px; margin-bottom: 22px; }
    .atlas-user-show h1 { margin: 0 0 7px; font-size: 27px; font-weight: 800; color: #153354; }
    .atlas-user-show .muted { color: #687e93; font-size: 13px; line-height: 1.6; margin: 0; }
    .atlas-user-show .btn { display: inline-flex; align-items: center; justify-content: center; gap: 7px; padding: 11px 16px; min-height: 42px; border-radius: 9px; background: #e8f0f9; color: #204d7a; font: inherit; font-weight: 750; text-decoration: none; }
    .atlas-user-show .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
    .atlas-user-show .panel { background: #fff; border: 1px solid #dce5f0; border-radius: 14px; overflow: hidden; box-shadow: 0 5px 22px rgba(21,51,84,.045); }
    .atlas-user-show .full { grid-column: 1 / -1; }
    .atlas-user-show .panel-head { padding: 17px 20px; background: #f8fbff; border-bottom: 1px solid #e7edf4; font-size: 16px; font-weight: 800; }
    .atlas-user-show .panel-body { padding: 20px; }
    .atlas-user-show dl { margin: 0; display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 20px 18px; }
    .atlas-user-show dt { font-size: 12px; font-weight: 800; color: #6a8096; margin-bottom: 5px; }
    .atlas-user-show dd { margin: 0; font-size: 14px; color: #1b3c5d; font-weight: 650; overflow-wrap: anywhere; }
    .atlas-user-show .pill { display: inline-block; padding: 6px 11px; border-radius: 999px; font-size: 12px; font-weight: 800; background: #edf3fa; color: #29567f; margin: 2px 5px 2px 0; }
    .atlas-user-show .pill.active { background: #e7f7ed; color: #17683a; }
    .atlas-user-show .pill.inactive { background: #fff0ed; color: #a6382c; }
    .atlas-user-show .pill.warning { background: #fff6e4; color: #855c15; }
    .atlas-user-show .note { margin: 16px 0 0; padding: 13px 15px; background: #f1f6fc; border: 1px solid #d4e3f3; border-radius: 9px; color: #365a7b; font-size: 13px; line-height: 1.6; }
    .atlas-user-show .roles { display: flex; flex-wrap: wrap; gap: 6px; }
    @media(max-width: 700px) { .atlas-user-show { padding: 15px 10px 35px; } .atlas-user-show .grid, .atlas-user-show dl { grid-template-columns: 1fr; } .atlas-user-show .panel-body { padding: 15px; } }
</style>

<div class="atlas-user-show">
    <div class="heading">
        <div>
            <h1><i class="bi bi-person-vcard"></i> Detalle de usuario</h1>
            <p class="muted">Consulta de información personal, cuenta institucional y roles autorizados.</p>
        </div>
        <a class="btn" href="{{ route('administrador.usuarios.index') }}"><i class="bi bi-arrow-left"></i> Volver al listado</a>
    </div>

    @if(session('success'))<p style="padding:12px;background:#eaf7ee;color:#175b36;border-radius:9px">{{ session('success') }}</p>@endif
    @if(\Illuminate\Support\Facades\Cache::has('atlas:admin:credenciales:'.auth()->id().':'.$usuario->id))
        <p style="padding:12px;background:#fff8e7;border-radius:9px"><strong>Ficha disponible por tiempo limitado:</strong>
        <a href="{{ route('administrador.usuarios.credenciales', $usuario) }}" target="_blank" rel="noopener">Abrir ficha PDF con contraseña temporal (una sola vez)</a></p>
    @endif
    <p><a href="{{ route('administrador.usuarios.edit', $usuario) }}" style="font-weight:800;color:#195c9d">Editar usuario, roles y estado</a></p>
    <div class="grid">
        <section class="panel full">
            <div class="panel-head"><i class="bi bi-person"></i> Información personal</div>
            <div class="panel-body">
                <dl>
                    <div><dt>Nombres</dt><dd>{{ $usuario->persona?->nombres ?: 'No registrado' }}</dd></div>
                    <div><dt>Apellidos</dt><dd>{{ $usuario->persona?->apellidos ?: 'No registrado' }}</dd></div>
                    <div><dt>CUI</dt><dd>{{ $usuario->persona?->cui ?: 'No registrado' }}</dd></div>
                    <div><dt>Fecha de nacimiento</dt><dd>{{ $usuario->persona?->fecha_nacimiento?->format('d/m/Y') ?? 'No registrada' }}</dd></div>
                    <div><dt>Sexo</dt><dd>{{ match($usuario->persona?->sexo) { 'M' => 'Masculino', 'F' => 'Femenino', default => 'No registrado' } }}</dd></div>
                    <div><dt>Teléfono</dt><dd>{{ $usuario->persona?->telefono ?: 'No registrado' }}</dd></div>
                    <div><dt>Correo personal</dt><dd>{{ $usuario->persona?->correo ?: 'No registrado' }}</dd></div>
                    <div><dt>Dirección</dt><dd>{{ $usuario->persona?->direccion ?: 'No registrada' }}</dd></div>
                </dl>
            </div>
        </section>

        @if(session('success'))<p style="padding:12px;background:#eaf7ee;color:#175b36;border-radius:9px">{{ session('success') }}</p>@endif
    @if(\Illuminate\Support\Facades\Cache::has('atlas:admin:credenciales:'.auth()->id().':'.$usuario->id))
        <p style="padding:12px;background:#fff8e7;border-radius:9px"><strong>Ficha disponible por tiempo limitado:</strong>
        <a href="{{ route('administrador.usuarios.credenciales', $usuario) }}" target="_blank" rel="noopener">Abrir ficha PDF con contraseña temporal (una sola vez)</a></p>
    @endif
    <p><a href="{{ route('administrador.usuarios.edit', $usuario) }}" style="font-weight:800;color:#195c9d">Editar usuario, roles y estado</a></p>
    <section class="panel">
            <div class="panel-head"><i class="bi bi-shield-lock"></i> Cuenta de acceso</div>
            <div class="panel-body">
                <dl>
                    <div><dt>Nombre de usuario</dt><dd>{{ $usuario->nombre_usuario }}</dd></div>
                    <div><dt>Correo de la cuenta</dt><dd>{{ $usuario->email ?: 'No registrado' }}</dd></div>
                    <div><dt>Estado de la cuenta</dt><dd><span class="pill {{ $usuario->activo ? 'active' : 'inactive' }}">{{ $usuario->activo ? 'Activa' : 'Inactiva' }}</span></dd></div>
                    <div><dt>Último acceso</dt><dd>{{ $usuario->ultimo_acceso_at?->format('d/m/Y H:i') ?? 'Sin accesos registrados' }}</dd></div>
                    <div><dt>Cambio obligatorio de contraseña</dt><dd><span class="pill {{ $usuario->debe_cambiar_password ? 'warning' : 'active' }}">{{ $usuario->debe_cambiar_password ? 'Pendiente' : 'No pendiente' }}</span></dd></div>
                </dl>
                <p class="note"><i class="bi bi-info-circle"></i> Por seguridad, ATLAS no muestra ni recupera las contraseñas de los usuarios.</p>
            </div>
        </section>

        <section class="panel">
            <div class="panel-head"><i class="bi bi-person-gear"></i> Roles institucionales</div>
            <div class="panel-body">
                <p class="muted" style="margin-bottom: 14px;">Roles asignados a esta cuenta:</p>
                <div class="roles">
                    @forelse($usuario->roles as $rol)
                        <span class="pill">{{ $rol->nombre }}{{ $rol->activo ? '' : ' (inactivo)' }}</span>
                    @empty
                        <p class="muted">Este usuario no tiene roles asignados.</p>
                    @endforelse
                </div>
                <p class="note"><i class="bi bi-shield-check"></i> Los roles determinan los paneles institucionales a los que puede acceder el usuario. La gestión de accesos se implementará por separado.</p>
            </div>
        </section>
    </div>
</div>
@endsection
