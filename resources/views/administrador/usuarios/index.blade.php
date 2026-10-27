@extends('layouts.administrador')

@section('title', 'Listado de usuarios')
@section('module-title', 'Usuarios del sistema')

@section('content')
<style>
    .atlas-users { max-width: 1220px; margin: 0 auto; padding: 24px 20px 44px; color: #173554; }
    .atlas-users * { box-sizing: border-box; }
    .atlas-users .heading { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 22px; }
    .atlas-users h1 { font-size: 27px; line-height: 1.25; margin: 0 0 7px; color: #153354; font-weight: 800; }
    .atlas-users .muted { color: #687e93; font-size: 13px; line-height: 1.6; margin: 0; }
    .atlas-users .panel { background: #fff; border: 1px solid #dce5f0; border-radius: 14px; overflow: hidden; margin-bottom: 18px; box-shadow: 0 5px 22px rgba(21,51,84,.045); }
    .atlas-users .panel-head { padding: 17px 20px; background: #f8fbff; border-bottom: 1px solid #e7edf4; font-size: 16px; font-weight: 800; }
    .atlas-users .panel-body { padding: 20px; }
    .atlas-users .filters { display: grid; grid-template-columns: minmax(200px,2fr) minmax(140px,1fr) minmax(140px,1fr) auto; gap: 12px; align-items: end; }
    .atlas-users label { display: block; margin-bottom: 6px; color: #25486b; font-size: 13px; font-weight: 700; }
    .atlas-users .field { display: block; width: 100%; min-height: 42px; padding: 10px 11px; border: 1px solid #cbd9e8; border-radius: 9px; background: #fff; color: #173554; font: inherit; }
    .atlas-users .field:focus { outline: 2px solid #a8c9f0; border-color: #3678ba; }
    .atlas-users .buttons { display: flex; gap: 8px; flex-wrap: wrap; }
    .atlas-users .btn { display: inline-flex; align-items: center; justify-content: center; gap: 7px; min-height: 42px; padding: 10px 15px; border: 0; border-radius: 9px; font: inherit; font-weight: 750; text-decoration: none; cursor: pointer; white-space: nowrap; }
    .atlas-users .btn-primary { background: #195c9d; color: #fff; }
    .atlas-users .btn-secondary { background: #e8f0f9; color: #204d7a; }
    .atlas-users .table-wrap { overflow-x: auto; }
    .atlas-users table { width: 100%; border-collapse: collapse; text-align: left; }
    .atlas-users th { background: #f8fbff; color: #3d5c7a; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: .035em; padding: 13px 15px; border-bottom: 1px solid #e2eaf3; white-space: nowrap; }
    .atlas-users td { padding: 14px 15px; border-bottom: 1px solid #edf1f6; font-size: 13px; vertical-align: middle; }
    .atlas-users tbody tr:last-child td { border-bottom: 0; }
    .atlas-users tbody tr:hover { background: #fafcff; }
    .atlas-users .person-name { font-weight: 800; color: #183b61; }
    .atlas-users .sub { font-size: 12px; color: #728499; margin-top: 3px; }
    .atlas-users .pill { display: inline-block; padding: 5px 9px; border-radius: 999px; font-size: 11px; font-weight: 800; margin: 2px 4px 2px 0; background: #edf3fa; color: #29567f; }
    .atlas-users .pill.active { background: #e7f7ed; color: #17683a; }
    .atlas-users .pill.inactive { background: #fff0ed; color: #a6382c; }
    .atlas-users .pill.warning { background: #fff6e4; color: #855c15; }
    .atlas-users .view-link { font-weight: 800; color: #195c9d; text-decoration: none; white-space: nowrap; }
    .atlas-users .view-link:hover { text-decoration: underline; }
    .atlas-users .empty { text-align: center; padding: 40px 20px; color: #64788d; }
    .atlas-users .foot { padding: 14px 20px; border-top: 1px solid #e7edf4; }
    .atlas-users .alert { padding: 12px 15px; margin-bottom: 15px; border: 1px solid #efb9b1; border-radius: 10px; background: #fff0ee; color: #9c2e23; }
    @media(max-width: 900px) { .atlas-users .filters { grid-template-columns: 1fr 1fr; } }
    @media(max-width: 600px) { .atlas-users { padding: 15px 10px 35px; } .atlas-users .filters { grid-template-columns: 1fr; } .atlas-users .heading .btn { width: 100%; } .atlas-users .panel-body { padding: 15px; } }
</style>

<div class="atlas-users">
    <div class="heading">
        <div>
            <h1><i class="bi bi-people"></i> Usuarios del sistema</h1>
            <p class="muted">Consulte las cuentas institucionales, sus roles y su estado de acceso.</p>
        </div>
        <a href="{{ route('administrador.usuarios.create') }}" class="btn btn-primary"><i class="bi bi-person-plus"></i> Crear usuario</a>
    </div>

    @if($errors->any())
        <div class="alert" role="alert">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
    @endif

    <section class="panel">
        <div class="panel-head"><i class="bi bi-funnel"></i> Buscar y filtrar</div>
        <div class="panel-body">
            <form method="GET" action="{{ route('administrador.usuarios.index') }}" class="filters">
                <div>
                    <label for="buscar">Nombre, CUI o usuario</label>
                    <input id="buscar" name="buscar" class="field" type="search" maxlength="120" placeholder="Escriba un dato para buscar" value="{{ request('buscar', '') }}">
                </div>
                <div>
                    <label for="rol">Rol</label>
                    <select id="rol" name="rol" class="field">
                        <option value="">Todos los roles</option>
                        @foreach($roles as $rol)
                            <option value="{{ $rol->id }}" @selected((string) request('rol', '') === (string) $rol->id)>{{ $rol->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="estado">Estado</label>
                    <select id="estado" name="estado" class="field">
                        <option value="">Todos</option>
                        <option value="activo" @selected(request('estado') === 'activo')>Activos</option>
                        <option value="inactivo" @selected(request('estado') === 'inactivo')>Inactivos</option>
                    </select>
                </div>
                <div class="buttons">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Buscar</button>
                    <a href="{{ route('administrador.usuarios.index') }}" class="btn btn-secondary">Limpiar</a>
                </div>
            </form>
        </div>
    </section>

    <section class="panel">
        <div class="panel-head"><i class="bi bi-list-ul"></i> Listado de cuentas</div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Persona</th>
                        <th>Nombre de usuario</th>
                        <th>Roles</th>
                        <th>Estado</th>
                        <th>Último acceso</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $usuario)
                        <tr>
                            <td>
                                <div class="person-name">{{ $usuario->persona?->nombres }} {{ $usuario->persona?->apellidos }}</div>
                                <div class="sub">CUI: {{ $usuario->persona?->cui ?: 'No registrado' }}</div>
                            </td>
                            <td>
                                <strong>{{ $usuario->nombre_usuario }}</strong>
                                @if($usuario->email)<div class="sub">{{ $usuario->email }}</div>@endif
                            </td>
                            <td>
                                @forelse($usuario->roles as $rol)
                                    <span class="pill">{{ $rol->nombre }}</span>
                                @empty
                                    <span class="sub">Sin roles asignados</span>
                                @endforelse
                            </td>
                            <td>
                                <span class="pill {{ $usuario->activo ? 'active' : 'inactive' }}">{{ $usuario->activo ? 'Activo' : 'Inactivo' }}</span>
                                @if($usuario->debe_cambiar_password)
                                    <span class="pill warning">Cambio de contraseña pendiente</span>
                                @endif
                            </td>
                            <td>{{ $usuario->ultimo_acceso_at?->format('d/m/Y H:i') ?? 'Sin accesos registrados' }}</td>
                            <td><a class="view-link" href="{{ route('administrador.usuarios.show', $usuario) }}"><i class="bi bi-eye"></i> Ver usuario</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty"><i class="bi bi-inbox"></i> No se encontraron usuarios con los filtros seleccionados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($usuarios->hasPages())
            <div class="foot">{{ $usuarios->links() }}</div>
        @endif
    </section>
</div>
@endsection
