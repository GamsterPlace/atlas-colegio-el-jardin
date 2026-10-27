
@extends('layouts.atlas')

@section('title', 'Administración')
@section('role', 'Administrador')
@section('module-title', 'Panel de Administración')
@section('dashboard-url', route('administrador.dashboard'))

@section('sidebar-menu')

    <div class="atlas-nav-section">
        <span class="atlas-nav-heading">Principal</span>

        <a href="{{ route('administrador.dashboard') }}"
           class="atlas-nav-link {{ request()->routeIs('administrador.dashboard') ? 'active' : '' }}"
           @if(request()->routeIs('administrador.dashboard')) aria-current="page" @endif>
            <i class="bi bi-grid-1x2"></i>
            <span>Dashboard</span>
        </a>
    </div>

    <div class="atlas-nav-section">
        <span class="atlas-nav-heading">Administración del sistema</span>

        <button type="button"
                class="atlas-nav-button {{ request()->routeIs('administrador.usuarios.*') ? 'active' : '' }}"
                data-atlas-submenu="admin-usuarios"
                aria-controls="admin-usuarios"
                aria-expanded="false">
            <i class="bi bi-people"></i>
            <span>Usuarios</span>
            <i class="bi bi-chevron-down atlas-nav-chevron"></i>
        </button>

        <div class="atlas-nav-submenu" id="admin-usuarios" hidden>
            <a href="{{ route('administrador.usuarios.index') }}"
               class="atlas-nav-sublink {{ request()->routeIs('administrador.usuarios.index', 'administrador.usuarios.show') ? 'active' : '' }}"
               @if(request()->routeIs('administrador.usuarios.index', 'administrador.usuarios.show')) aria-current="page" @endif>
                Listado de usuarios
            </a>

            <a href="{{ route('administrador.usuarios.create') }}"
               class="atlas-nav-sublink {{ request()->routeIs('administrador.usuarios.create') ? 'active' : '' }}"
               @if(request()->routeIs('administrador.usuarios.create')) aria-current="page" @endif>
                Crear usuario
            </a>

            <a href="#" class="atlas-nav-sublink">Gestión de accesos</a>
        </div>

        <button type="button" class="atlas-nav-button"
                data-atlas-submenu="admin-roles"
                aria-controls="admin-roles"
                aria-expanded="false">
            <i class="bi bi-person-gear"></i>
            <span>Roles y permisos</span>
            <i class="bi bi-chevron-down atlas-nav-chevron"></i>
        </button>

        <div class="atlas-nav-submenu" id="admin-roles" hidden>
            <a href="#" class="atlas-nav-sublink">Administrar roles</a>
            <a href="#" class="atlas-nav-sublink">Asignar permisos</a>
            <a href="#" class="atlas-nav-sublink">Consultar permisos</a>
        </div>

        <button type="button" class="atlas-nav-button"
                data-atlas-submenu="admin-seguridad"
                aria-controls="admin-seguridad"
                aria-expanded="false">
            <i class="bi bi-shield-lock"></i>
            <span>Seguridad</span>
            <i class="bi bi-chevron-down atlas-nav-chevron"></i>
        </button>

        <div class="atlas-nav-submenu" id="admin-seguridad" hidden>
            <a href="#" class="atlas-nav-sublink">Control de seguridad</a>
            <a href="#" class="atlas-nav-sublink">Restablecer contraseñas</a>
            <a href="#" class="atlas-nav-sublink">Registro de accesos</a>
        </div>
    </div>

    <div class="atlas-nav-section">
        <span class="atlas-nav-heading">Control institucional</span>

        <button type="button" class="atlas-nav-button"
                data-atlas-submenu="admin-finanzas"
                aria-controls="admin-finanzas"
                aria-expanded="false">
            <i class="bi bi-wallet2"></i>
            <span>Finanzas</span>
            <i class="bi bi-chevron-down atlas-nav-chevron"></i>
        </button>

        <div class="atlas-nav-submenu" id="admin-finanzas" hidden>
            <a href="#" class="atlas-nav-sublink">Panel financiero</a>
            <a href="#" class="atlas-nav-sublink">Registro de pagos</a>
            <a href="#" class="atlas-nav-sublink">Estados de cuenta</a>
            <a href="#" class="atlas-nav-sublink">Reportes financieros</a>
        </div>

        <button type="button" class="atlas-nav-button"
                data-atlas-submenu="admin-asistencia"
                aria-controls="admin-asistencia"
                aria-expanded="false">
            <i class="bi bi-qr-code-scan"></i>
            <span>Asistencia</span>
            <i class="bi bi-chevron-down atlas-nav-chevron"></i>
        </button>

        <div class="atlas-nav-submenu" id="admin-asistencia" hidden>
            <a href="#" class="atlas-nav-sublink">Registro de asistencia</a>
            <a href="#" class="atlas-nav-sublink">Escaneo QR y código</a>
            <a href="#" class="atlas-nav-sublink">Entradas y salidas</a>
            <a href="#" class="atlas-nav-sublink">Historial de asistencia</a>
        </div>
    </div>

    <div class="atlas-nav-section">
        <span class="atlas-nav-heading">Supervisión del sistema</span>

        <button type="button" class="atlas-nav-button"
                data-atlas-submenu="admin-auditoria"
                aria-controls="admin-auditoria"
                aria-expanded="false">
            <i class="bi bi-journal-text"></i>
            <span>Auditoría</span>
            <i class="bi bi-chevron-down atlas-nav-chevron"></i>
        </button>

        <div class="atlas-nav-submenu" id="admin-auditoria" hidden>
            <a href="#" class="atlas-nav-sublink">Actividad del sistema</a>
            <a href="#" class="atlas-nav-sublink">Historial de cambios</a>
            <a href="#" class="atlas-nav-sublink">Registros de auditoría</a>
        </div>
    </div>

@endsection

@include('layouts.partials.atlas-submenus')
