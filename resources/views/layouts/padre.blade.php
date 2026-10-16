
@extends('layouts.atlas')

@section('title', 'Padre de Familia')
@section('role', 'Padre de Familia')
@section('module-title', 'Panel de Familia')
@section('dashboard-url', url('/padre'))

@section('sidebar-menu')

    <div class="atlas-nav-section">
        <span class="atlas-nav-heading">Principal</span>

        <a href="{{ url('/padre') }}"
           class="atlas-nav-link {{ request()->is('padre') ? 'active' : '' }}"
           @if(request()->is('padre')) aria-current="page" @endif>
            <i class="bi bi-grid-1x2"></i>
            <span>Dashboard</span>
        </a>
    </div>

    <div class="atlas-nav-section">
        <span class="atlas-nav-heading">Información de mis hijos</span>

        <a href="#" class="atlas-nav-link">
            <i class="bi bi-people"></i>
            <span>Mis hijos</span>
        </a>

        <button type="button" class="atlas-nav-button"
                data-atlas-submenu="padre-notas"
                aria-controls="padre-notas"
                aria-expanded="false">
            <i class="bi bi-journal-check"></i>
            <span>Calificaciones</span>
            <i class="bi bi-chevron-down atlas-nav-chevron"></i>
        </button>

        <div class="atlas-nav-submenu" id="padre-notas" hidden>
            <a href="#" class="atlas-nav-sublink">Consultar calificaciones</a>
            <a href="#" class="atlas-nav-sublink">Historial académico</a>
        </div>

        <button type="button" class="atlas-nav-button"
                data-atlas-submenu="padre-comportamiento"
                aria-controls="padre-comportamiento"
                aria-expanded="false">
            <i class="bi bi-clipboard-check"></i>
            <span>Comportamiento</span>
            <i class="bi bi-chevron-down atlas-nav-chevron"></i>
        </button>

        <div class="atlas-nav-submenu" id="padre-comportamiento" hidden>
            <a href="#" class="atlas-nav-sublink">Consultar comportamiento</a>
            <a href="#" class="atlas-nav-sublink">Historial de observaciones</a>
        </div>

        <button type="button" class="atlas-nav-button"
                data-atlas-submenu="padre-asistencia"
                aria-controls="padre-asistencia"
                aria-expanded="false">
            <i class="bi bi-clock-history"></i>
            <span>Asistencia</span>
            <i class="bi bi-chevron-down atlas-nav-chevron"></i>
        </button>

        <div class="atlas-nav-submenu" id="padre-asistencia" hidden>
            <a href="#" class="atlas-nav-sublink">Asistencia diaria</a>
            <a href="#" class="atlas-nav-sublink">Entradas y salidas</a>
            <a href="#" class="atlas-nav-sublink">Historial de asistencia</a>
        </div>
    </div>

    <div class="atlas-nav-section">
        <span class="atlas-nav-heading">Información financiera</span>

        <a href="#" class="atlas-nav-link">
            <i class="bi bi-receipt"></i>
            <span>Estados de cuenta</span>
        </a>
    </div>

    <div class="atlas-nav-section">
        <span class="atlas-nav-heading">Comunicación escolar</span>

        <button type="button" class="atlas-nav-button"
                data-atlas-submenu="padre-avisos"
                aria-controls="padre-avisos"
                aria-expanded="false">
            <i class="bi bi-envelope-paper"></i>
            <span>Avisos al colegio</span>
            <i class="bi bi-chevron-down atlas-nav-chevron"></i>
        </button>

        <div class="atlas-nav-submenu" id="padre-avisos" hidden>
            <a href="#" class="atlas-nav-sublink">Notificar inasistencia</a>
            <a href="#" class="atlas-nav-sublink">Notificar retraso</a>
            <a href="#" class="atlas-nav-sublink">Historial de avisos</a>
        </div>

        <a href="#" class="atlas-nav-link">
            <i class="bi bi-megaphone"></i>
            <span>Comunicados</span>
        </a>

        <a href="#" class="atlas-nav-link">
            <i class="bi bi-calendar-event"></i>
            <span>Agenda académica</span>
        </a>
    </div>

@endsection

@include('layouts.partials.atlas-submenus')
