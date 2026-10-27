@extends('layouts.administrador')

@section('title', 'Crear usuario')
@section('module-title', 'Crear usuario')

@section('content')
<style>
    .atlas-user-create { max-width: 1120px; margin: 0 auto; padding: 22px 20px 45px; color: #173554; }
    .atlas-user-create * { box-sizing: border-box; }
    .atlas-user-create h1 { font-size: 27px; margin: 0 0 8px; font-weight: 800; color: #153354; }
    .atlas-user-create .intro { color: #64788d; margin: 0 0 22px; line-height: 1.6; }
    .atlas-user-create .panel { background: #fff; border: 1px solid #dce5f0; border-radius: 15px; margin-bottom: 20px; overflow: hidden; box-shadow: 0 5px 22px rgba(21,51,84,.045); }
    .atlas-user-create .panel-head { padding: 17px 21px; border-bottom: 1px solid #e7edf4; background: #f8fbff; }
    .atlas-user-create .panel-head h2 { margin: 0; font-size: 17px; font-weight: 800; color: #1c4169; }
    .atlas-user-create .panel-body { padding: 21px; }
    .atlas-user-create .grid { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 16px; }
    .atlas-user-create .full { grid-column: 1 / -1; }
    .atlas-user-create label.field-label { display: block; font-weight: 700; font-size: 13px; margin-bottom: 7px; color: #25486b; }
    .atlas-user-create .field { width: 100%; border: 1px solid #cbd9e8; border-radius: 9px; padding: 11px 12px; background: #fff; color: #1c3650; font: inherit; min-height: 43px; }
    .atlas-user-create .field:focus { outline: 2px solid #a8c9f0; border-color: #3678ba; }
    .atlas-user-create .help { font-size: 12px; color: #718397; margin-top: 6px; }
    .atlas-user-create .error { color: #b42318; font-size: 12px; margin-top: 5px; }
    .atlas-user-create .alert { padding: 14px 17px; border-radius: 11px; margin-bottom: 17px; line-height: 1.6; }
    .atlas-user-create .alert-success { background: #effaf3; border: 1px solid #b8e4c8; color: #19653a; }
    .atlas-user-create .alert-warning { background: #fff8e8; border: 1px solid #efdbab; color: #795515; }
    .atlas-user-create .alert-danger { background: #fff0ee; border: 1px solid #f0b9b1; color: #9c2e23; }
    .atlas-user-create .alert-info { background: #eef5fd; border: 1px solid #bfd6f0; color: #215383; }
    .atlas-user-create .btn { display: inline-flex; justify-content: center; align-items: center; gap: 8px; border: 0; border-radius: 9px; padding: 11px 17px; text-decoration: none; cursor: pointer; font: inherit; font-weight: 750; }
    .atlas-user-create .btn-primary { background: #195c9d; color: white; }
    .atlas-user-create .btn-secondary { background: #e8f0f9; color: #204d7a; }
    .atlas-user-create .actions { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 21px; }
    .atlas-user-create .lookup { display: flex; gap: 10px; align-items: end; flex-wrap: wrap; }
    .atlas-user-create .lookup > div { flex: 1; min-width: 220px; }
    .atlas-user-create .roles { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 10px; }
    .atlas-user-create .role { display: flex; gap: 10px; align-items: center; border: 1px solid #dce6f0; border-radius: 10px; padding: 12px; cursor: pointer; }
    .atlas-user-create .role input { width: 17px; height: 17px; accent-color: #195c9d; }
    .atlas-user-create .credentials { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 12px; }
    .atlas-user-create .credential-value { background: #fff; border: 1px solid #b8d9c4; border-radius: 8px; padding: 10px 12px; font-family: monospace; font-size: 15px; overflow-wrap: anywhere; user-select: all; }
    @media(max-width: 700px) { .atlas-user-create { padding: 15px 10px 35px; } .atlas-user-create .grid,.atlas-user-create .roles,.atlas-user-create .credentials { grid-template-columns: 1fr; } .atlas-user-create .panel-body { padding: 15px; } }
</style>

<div class="atlas-user-create">
    <h1><i class="bi bi-person-plus"></i> Crear usuario</h1>
    <p class="intro">Registre una cuenta institucional y asigne uno o varios roles. ATLAS genera automáticamente el usuario y la contraseña temporal. Las cuentas nuevas deberán cambiar su contraseña en el primer acceso.</p>

    @if(session('success'))
        <div class="alert alert-success" role="status">{{ session('success') }}</div>
    @endif

    @if(session('credenciales_iniciales'))
        @php($credenciales = session('credenciales_iniciales'))
        <section class="alert alert-success" aria-label="Credenciales iniciales">
            <strong><i class="bi bi-key"></i> Credenciales iniciales del usuario</strong>
            <p style="margin: 7px 0;">Entréguelas por un medio seguro. La contraseña solo se muestra después de registrar la cuenta; no se podrá consultar nuevamente.</p>
            <div class="credentials">
                <div><strong>Nombre de usuario</strong><div class="credential-value">{{ $credenciales['usuario'] ?? '' }}</div></div>
                <div><strong>Contraseña temporal</strong><div class="credential-value">{{ $credenciales['password'] ?? '' }}</div></div>
            </div>
            <p style="margin: 10px 0 0;"><strong>Importante:</strong> no comparta estas credenciales por canales públicos.</p>
        </section>
    @endif

    @if($errors->any())
        <div class="alert alert-danger" role="alert">
            <strong>Revise los datos ingresados:</strong>
            <ul style="margin: 7px 0 0; padding-left: 20px;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <section class="panel">
        <div class="panel-head"><h2><i class="bi bi-search"></i> 1. Buscar persona por CUI</h2></div>
        <div class="panel-body">
            <form method="GET" action="{{ route('administrador.usuarios.create') }}" class="lookup">
                <div>
                    <label for="buscar_cui" class="field-label">Código Único de Identificación (CUI)</label>
                    <input id="buscar_cui" class="field" name="cui" type="text" inputmode="numeric" pattern="[0-9]{13}" minlength="13" maxlength="13" value="{{ request('cui', '') }}" placeholder="13 dígitos" required>
                </div>
                <button class="btn btn-secondary" type="submit"><i class="bi bi-search"></i> Buscar persona</button>
                <a class="btn btn-secondary" href="{{ route('administrador.usuarios.create') }}">Limpiar</a>
            </form>
        </div>
    </section>

    @if($usuarioExistente)
        <div class="alert alert-warning" role="status">
            <strong>Esta persona ya tiene una cuenta en ATLAS.</strong>
            Usuario registrado: <strong>{{ $usuarioExistente->nombre_usuario }}</strong>. <a href="{{ route('administrador.usuarios.edit', $usuarioExistente) }}">Editar cuenta</a>.
            Para modificar sus roles o accesos, abra la ficha del usuario desde el listado. No se creará una cuenta duplicada.
        </div>
    @elseif($personaExistente)
        <div class="alert alert-info" role="status">
            <strong>Persona encontrada:</strong> {{ $personaExistente->nombres }} {{ $personaExistente->apellidos }}.
            Se utilizará su registro existente sin modificar sus datos personales.
        </div>
    @elseif(request()->filled('cui') && !$errors->has('cui'))
        <div class="alert alert-info" role="status">No se encontró una persona con ese CUI. Puede registrar sus datos a continuación.</div>
    @endif

    @if(!$usuarioExistente)
        <form method="POST" action="{{ route('administrador.usuarios.store') }}" autocomplete="off">
            @csrf
            <section class="panel">
                <div class="panel-head"><h2><i class="bi bi-person-vcard"></i> 2. Datos personales</h2></div>
                <div class="panel-body">
                    <div class="grid">
                        <div>
                            <label for="cui" class="field-label">CUI *</label>
                            <input class="field" id="cui" name="cui" type="text" inputmode="numeric" pattern="[0-9]{13}" minlength="13" maxlength="13" value="{{ old('cui', $personaExistente?->cui ?? request('cui', '')) }}" required @if($personaExistente) readonly @endif>
                            @error('cui')<div class="error">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label for="fecha_nacimiento" class="field-label">Fecha de nacimiento</label>
                            <input class="field" id="fecha_nacimiento" name="fecha_nacimiento" type="date" max="{{ now()->toDateString() }}" value="{{ old('fecha_nacimiento', $personaExistente?->fecha_nacimiento?->format('Y-m-d')) }}" @if($personaExistente) readonly @endif>
                        </div>
                        <div>
                            <label for="nombres" class="field-label">Nombres *</label>
                            <input class="field" id="nombres" name="nombres" maxlength="150" value="{{ old('nombres', $personaExistente?->nombres) }}" required @if($personaExistente) readonly @endif>
                        </div>
                        <div>
                            <label for="apellidos" class="field-label">Apellidos *</label>
                            <input class="field" id="apellidos" name="apellidos" maxlength="150" value="{{ old('apellidos', $personaExistente?->apellidos) }}" required @if($personaExistente) readonly @endif>
                        </div>
                        <div>
                            <label for="sexo" class="field-label">Sexo</label>
                            <select class="field" id="sexo" name="sexo" @if($personaExistente) disabled @endif>
                                <option value="">Seleccione</option>
                                <option value="M" @selected(old('sexo', $personaExistente?->sexo) === 'M')>Masculino</option>
                                <option value="F" @selected(old('sexo', $personaExistente?->sexo) === 'F')>Femenino</option>
                            </select>
                        </div>
                        <div>
                            <label for="telefono" class="field-label">Teléfono</label>
                            <input class="field" id="telefono" name="telefono" maxlength="30" value="{{ old('telefono', $personaExistente?->telefono) }}" @if($personaExistente) readonly @endif>
                        </div>
                        <div>
                            <label for="correo" class="field-label">Correo personal</label>
                            <input class="field" id="correo" name="correo" type="email" maxlength="150" value="{{ old('correo', $personaExistente?->correo) }}" @if($personaExistente) readonly @endif>
                        </div>
                        <div>
                            <label for="direccion" class="field-label">Dirección</label>
                            <input class="field" id="direccion" name="direccion" maxlength="255" value="{{ old('direccion', $personaExistente?->direccion) }}" @if($personaExistente) readonly @endif>
                        </div>
                    </div>
                    @if($personaExistente)
                        <p class="help">Los datos personales existentes se muestran como referencia. Si necesita corregirlos, hágalo desde el módulo correspondiente.</p>
                    @endif
                </div>
            </section>

            <section class="panel">
                <div class="panel-head"><h2><i class="bi bi-shield-lock"></i> 3. Cuenta de acceso</h2></div>
                <div class="panel-body">
                    <div class="grid">
                        <div>
                            <label for="email" class="field-label">Correo de la cuenta (opcional)</label>
                            <input class="field" id="email" name="email" type="email" maxlength="150" value="{{ old('email') }}" placeholder="correo@ejemplo.com">
                            @error('email')<div class="error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <p class="help">ATLAS generará automáticamente el nombre de usuario con el formato nombre.apellido y cuatro dígitos.</p>
                    <p class="help"><i class="bi bi-key"></i> ATLAS generará automáticamente una contraseña temporal segura. No es necesario escribir una contraseña.</p>
                </div>
            </section>

            <section class="panel">
                <div class="panel-head"><h2><i class="bi bi-person-gear"></i> 4. Asignación de roles</h2></div>
                <div class="panel-body">
                    <p class="intro" style="margin-bottom: 15px;">Seleccione uno o varios roles. El usuario podrá cambiar de panel sin cerrar sesión cuando tenga más de uno.</p>
                    <div class="roles">
                        @forelse($roles as $rol)
                            <label class="role">
                                <input type="checkbox" name="roles[]" value="{{ $rol->id }}" @checked(in_array((string) $rol->id, array_map('strval', old('roles', [])), true))>
                                <span><strong>{{ $rol->nombre }}</strong><br><small>{{ $rol->codigo }}</small></span>
                            </label>
                        @empty
                            <div class="alert alert-warning full">No hay roles institucionales activos. Revise la configuración antes de crear usuarios.</div>
                        @endforelse
                    </div>
                    @error('roles')<div class="error">{{ $message }}</div>@enderror
                    @error('roles.*')<div class="error">{{ $message }}</div>@enderror
                    <div class="actions">
                        <button class="btn btn-primary" type="submit" @disabled($roles->isEmpty())><i class="bi bi-person-plus"></i> Registrar usuario</button>
                        <a class="btn btn-secondary" href="{{ route('administrador.dashboard') }}">Cancelar</a>
                    </div>
                </div>
            </section>
        </form>
    @endif
</div>
@endsection
