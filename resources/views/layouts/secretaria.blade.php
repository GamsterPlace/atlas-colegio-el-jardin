
@extends('layouts.atlas')

@section('title', 'Secretaría')
@section('role', 'Secretaría')
@section('module-title', 'Panel de Secretaría')
@section('dashboard-url', url('/secretaria'))

@section('sidebar-menu')

    <div class="atlas-nav-section">
        <span class="atlas-nav-heading">Principal</span>

        <a href="{{ url('/secretaria') }}"
           class="atlas-nav-link {{ request()->is('secretaria') ? 'active' : '' }}"
           @if(request()->is('secretaria')) aria-current="page" @endif>
            <i class="bi bi-grid-1x2"></i>
            <span>Dashboard</span>
        </a>
    </div>

    <div class="atlas-nav-section">
        <span class="atlas-nav-heading">Gestión estudiantil</span>

        <button type="button" class="atlas-nav-button"
                data-atlas-submenu="secretaria-inscripciones"
                aria-controls="secretaria-inscripciones"
                aria-expanded="false">
            <i class="bi bi-person-plus"></i>
            <span>Inscripciones</span>
            <i class="bi bi-chevron-down atlas-nav-chevron"></i>
        </button>

        <div class="atlas-nav-submenu" id="secretaria-inscripciones" hidden>
            <a href="#" class="atlas-nav-sublink">Nueva inscripción</a>
            <a href="#" class="atlas-nav-sublink">Listado de inscripciones</a>
            <a href="#" class="atlas-nav-sublink">Control de inscripciones</a>
        </div>

        <button type="button" class="atlas-nav-button"
                data-atlas-submenu="secretaria-expedientes"
                aria-controls="secretaria-expedientes"
                aria-expanded="false">
            <i class="bi bi-folder2-open"></i>
            <span>Expedientes</span>
            <i class="bi bi-chevron-down atlas-nav-chevron"></i>
        </button>

        <div class="atlas-nav-submenu" id="secretaria-expedientes" hidden>
            <a href="#" class="atlas-nav-sublink">Expedientes estudiantiles</a>
            <a href="#" class="atlas-nav-sublink">Actualizar expedientes</a>
            <a href="#" class="atlas-nav-sublink">Consulta de estudiantes</a>
        </div>
    </div>

    <div class="atlas-nav-section">
        <span class="atlas-nav-heading">Control administrativo</span>

        <button type="button" class="atlas-nav-button"
                data-atlas-submenu="secretaria-pagos"
                aria-controls="secretaria-pagos"
                aria-expanded="false">
            <i class="bi bi-cash-coin"></i>
            <span>Pagos y finanzas</span>
            <i class="bi bi-chevron-down atlas-nav-chevron"></i>
        </button>

        <div class="atlas-nav-submenu" id="secretaria-pagos" hidden>
            <a href="#" class="atlas-nav-sublink">Registrar pago</a>
            <a href="#" class="atlas-nav-sublink">Historial de pagos</a>
            <a href="#" class="atlas-nav-sublink">Estados de cuenta</a>
            <a href="#" class="atlas-nav-sublink">Control financiero</a>
        </div>

        <button type="button" class="atlas-nav-button"
                data-atlas-submenu="secretaria-asistencia"
                aria-controls="secretaria-asistencia"
                aria-expanded="false">
            <i class="bi bi-qr-code-scan"></i>
            <span>Asistencia</span>
            <i class="bi bi-chevron-down atlas-nav-chevron"></i>
        </button>

        <div class="atlas-nav-submenu" id="secretaria-asistencia" hidden>
            <a href="#" class="atlas-nav-sublink">Registrar asistencia</a>
            <a href="#" class="atlas-nav-sublink">Escaneo QR y código</a>
            <a href="#" class="atlas-nav-sublink">Entradas y salidas</a>
            <a href="#" class="atlas-nav-sublink">Historial de asistencia</a>
        </div>
    </div>

    <div class="atlas-nav-section">
        <span class="atlas-nav-heading">Gestión académica</span>

        <button type="button" class="atlas-nav-button"
                data-atlas-submenu="secretaria-agenda"
                aria-controls="secretaria-agenda"
                aria-expanded="false">
            <i class="bi bi-calendar-event"></i>
            <span>Agenda académica</span>
            <i class="bi bi-chevron-down atlas-nav-chevron"></i>
        </button>

        <div class="atlas-nav-submenu" id="secretaria-agenda" hidden>
            <a href="#" class="atlas-nav-sublink">Calendario académico</a>
            <a href="#" class="atlas-nav-sublink">Crear actividad</a>
            <a href="#" class="atlas-nav-sublink">Gestionar actividades</a>
        </div>
    </div>

    <div class="atlas-nav-section">
        <span class="atlas-nav-heading">Comunicación</span>

        <button type="button" class="atlas-nav-button"
                data-atlas-submenu="secretaria-comunicados"
                aria-controls="secretaria-comunicados"
                aria-expanded="false">
            <i class="bi bi-megaphone"></i>
            <span>Comunicados</span>
            <i class="bi bi-chevron-down atlas-nav-chevron"></i>
        </button>

        <div class="atlas-nav-submenu" id="secretaria-comunicados" hidden>
            <a href="#" class="atlas-nav-sublink">Ver comunicados</a>
            <a href="#" class="atlas-nav-sublink">Crear comunicado</a>
            <a href="#" class="atlas-nav-sublink">Historial de comunicados</a>
        </div>

        <button type="button" class="atlas-nav-button"
                data-atlas-submenu="secretaria-publicaciones"
                aria-controls="secretaria-publicaciones"
                aria-expanded="false">
            <i class="bi bi-globe2"></i>
            <span>Publicaciones del sitio</span>
            <i class="bi bi-chevron-down atlas-nav-chevron"></i>
        </button>

        <div class="atlas-nav-submenu" id="secretaria-publicaciones" hidden>
            <a href="#" class="atlas-nav-sublink">Ver publicaciones</a>
            <a href="#" class="atlas-nav-sublink">Crear publicación</a>
            <a href="#" class="atlas-nav-sublink">Administrar publicaciones</a>
        </div>
    </div>

    <div class="atlas-nav-section">
        <span class="atlas-nav-heading">Información</span>

        <button type="button" class="atlas-nav-button"
                data-atlas-submenu="secretaria-reportes"
                aria-controls="secretaria-reportes"
                aria-expanded="false">
            <i class="bi bi-bar-chart-line"></i>
            <span>Reportes</span>
            <i class="bi bi-chevron-down atlas-nav-chevron"></i>
        </button>

        <div class="atlas-nav-submenu" id="secretaria-reportes" hidden>
            <a href="#" class="atlas-nav-sublink">Reportes de inscripciones</a>
            <a href="#" class="atlas-nav-sublink">Reportes de pagos</a>
            <a href="#" class="atlas-nav-sublink">Reportes de asistencia</a>
        </div>
    </div>

@endsection

@include('layouts.partials.atlas-submenus')
