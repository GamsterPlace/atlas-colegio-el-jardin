
@extends('layouts.atlas')

@section('title', 'Docente')
@section('role', 'Docente')
@section('module-title', 'Panel Docente')
@section('dashboard-url', url('/docente'))

@section('sidebar-menu')

    <div class="atlas-nav-section">
        <span class="atlas-nav-heading">Principal</span>

        <a href="{{ url('/docente') }}"
           class="atlas-nav-link {{ request()->is('docente') ? 'active' : '' }}"
           @if(request()->is('docente')) aria-current="page" @endif>
            <i class="bi bi-grid-1x2"></i>
            <span>Dashboard</span>
        </a>
    </div>

    <div class="atlas-nav-section">
        <span class="atlas-nav-heading">Gestión académica</span>

        <button type="button" class="atlas-nav-button"
                data-atlas-submenu="docente-notas"
                aria-controls="docente-notas"
                aria-expanded="false">
            <i class="bi bi-journal-check"></i>
            <span>Calificaciones</span>
            <i class="bi bi-chevron-down atlas-nav-chevron"></i>
        </button>

        <div class="atlas-nav-submenu" id="docente-notas" hidden>
            <a href="#" class="atlas-nav-sublink">Registrar calificaciones</a>
            <a href="#" class="atlas-nav-sublink">Consultar calificaciones</a>
            <a href="#" class="atlas-nav-sublink">Cuadros de notas</a>
        </div>

        <button type="button" class="atlas-nav-button"
                data-atlas-submenu="docente-asistencia"
                aria-controls="docente-asistencia"
                aria-expanded="false">
            <i class="bi bi-person-check"></i>
            <span>Asistencia</span>
            <i class="bi bi-chevron-down atlas-nav-chevron"></i>
        </button>

        <div class="atlas-nav-submenu" id="docente-asistencia" hidden>
            <a href="#" class="atlas-nav-sublink">Registrar asistencia</a>
            <a href="#" class="atlas-nav-sublink">Escaneo QR y código</a>
            <a href="#" class="atlas-nav-sublink">Entradas y salidas</a>
            <a href="#" class="atlas-nav-sublink">Historial de asistencia</a>
        </div>

        <a href="#" class="atlas-nav-link">
            <i class="bi bi-calendar-week"></i>
            <span>Mi horario de clases</span>
        </a>

        <a href="#" class="atlas-nav-link">
            <i class="bi bi-calendar-event"></i>
            <span>Agenda académica</span>
        </a>
    </div>

    <div class="atlas-nav-section">
        <span class="atlas-nav-heading">Seguimiento estudiantil</span>

        <button type="button" class="atlas-nav-button"
                data-atlas-submenu="docente-estudiantes"
                aria-controls="docente-estudiantes"
                aria-expanded="false">
            <i class="bi bi-people"></i>
            <span>Mis estudiantes</span>
            <i class="bi bi-chevron-down atlas-nav-chevron"></i>
        </button>

        <div class="atlas-nav-submenu" id="docente-estudiantes" hidden>
            <a href="#" class="atlas-nav-sublink">Listado de estudiantes</a>
            <a href="#" class="atlas-nav-sublink">Seguimiento académico</a>
            <a href="#" class="atlas-nav-sublink">Control de comportamiento</a>
        </div>

        <button type="button" class="atlas-nav-button"
                data-atlas-submenu="docente-avisos"
                aria-controls="docente-avisos"
                aria-expanded="false">
            <i class="bi bi-envelope"></i>
            <span>Avisos de padres</span>
            <i class="bi bi-chevron-down atlas-nav-chevron"></i>
        </button>

        <div class="atlas-nav-submenu" id="docente-avisos" hidden>
            <a href="#" class="atlas-nav-sublink">Avisos de inasistencia</a>
            <a href="#" class="atlas-nav-sublink">Avisos de retraso</a>
            <a href="#" class="atlas-nav-sublink">Historial de avisos</a>
        </div>
    </div>

    <div class="atlas-nav-section">
        <span class="atlas-nav-heading">Grado guía</span>

        <button type="button" class="atlas-nav-button"
                data-atlas-submenu="docente-guia"
                aria-controls="docente-guia"
                aria-expanded="false">
            <i class="bi bi-person-badge"></i>
            <span>Mi grado guía</span>
            <i class="bi bi-chevron-down atlas-nav-chevron"></i>
        </button>

        <div class="atlas-nav-submenu" id="docente-guia" hidden>
            <a href="#" class="atlas-nav-sublink">Estudiantes del grado</a>
            <a href="#" class="atlas-nav-sublink">Seguimiento del grado</a>
            <a href="#" class="atlas-nav-sublink">Reportería del grado</a>
        </div>
    </div>

    <div class="atlas-nav-section">
        <span class="atlas-nav-heading">Información</span>

        <button type="button" class="atlas-nav-button"
                data-atlas-submenu="docente-reportes"
                aria-controls="docente-reportes"
                aria-expanded="false">
            <i class="bi bi-bar-chart-line"></i>
            <span>Reportes</span>
            <i class="bi bi-chevron-down atlas-nav-chevron"></i>
        </button>

        <div class="atlas-nav-submenu" id="docente-reportes" hidden>
            <a href="#" class="atlas-nav-sublink">Reportes académicos</a>
            <a href="#" class="atlas-nav-sublink">Reportes de asistencia</a>
            <a href="#" class="atlas-nav-sublink">Reportes del grado guía</a>
        </div>

        <a href="#" class="atlas-nav-link">
            <i class="bi bi-megaphone"></i>
            <span>Comunicados</span>
        </a>
    </div>

@endsection

@include('layouts.partials.atlas-submenus')
