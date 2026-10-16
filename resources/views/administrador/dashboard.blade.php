
@extends('layouts.administrador')

@section('title', 'Dashboard de Administración')
@section('module-title', 'Panel de Administración')

@section('content')

<div class="atlas-dashboard">

    <section class="atlas-dashboard-hero">
        <div class="atlas-dashboard-hero-content">
            <span class="atlas-dashboard-eyebrow">
                <i class="bi bi-shield-check"></i>
                Administración del sistema
            </span>

            <h2>Panel de Administración</h2>

            <p>
                Control de usuarios, permisos, seguridad y
                supervisión operativa de la plataforma ATLAS.
            </p>
        </div>

        <div class="atlas-dashboard-hero-icon">
            <i class="bi bi-gear-wide-connected"></i>
        </div>
    </section>

    <section class="atlas-dashboard-stats">

        <article class="atlas-dashboard-stat">
            <div>
                <span class="atlas-dashboard-stat-label">
                    Usuarios registrados
                </span>
                <strong class="atlas-dashboard-stat-value">—</strong>
                <span class="atlas-dashboard-stat-note">
                    Total de cuentas del sistema
                </span>
            </div>

            <div class="atlas-dashboard-stat-icon">
                <i class="bi bi-people"></i>
            </div>
        </article>

        <article class="atlas-dashboard-stat">
            <div>
                <span class="atlas-dashboard-stat-label">
                    Usuarios activos
                </span>
                <strong class="atlas-dashboard-stat-value">—</strong>
                <span class="atlas-dashboard-stat-note">
                    Cuentas habilitadas
                </span>
            </div>

            <div class="atlas-dashboard-stat-icon green">
                <i class="bi bi-person-check"></i>
            </div>
        </article>

        <article class="atlas-dashboard-stat">
            <div>
                <span class="atlas-dashboard-stat-label">
                    Roles configurados
                </span>
                <strong class="atlas-dashboard-stat-value">—</strong>
                <span class="atlas-dashboard-stat-note">
                    Roles registrados en la plataforma
                </span>
            </div>

            <div class="atlas-dashboard-stat-icon navy">
                <i class="bi bi-person-badge"></i>
            </div>
        </article>

        <article class="atlas-dashboard-stat">
            <div>
                <span class="atlas-dashboard-stat-label">
                    Eventos de seguridad
                </span>
                <strong class="atlas-dashboard-stat-value">—</strong>
                <span class="atlas-dashboard-stat-note">
                    Eventos del período actual
                </span>
            </div>

            <div class="atlas-dashboard-stat-icon red">
                <i class="bi bi-shield-exclamation"></i>
            </div>
        </article>

    </section>

    <div class="atlas-dashboard-columns">

        <section class="atlas-dashboard-panel">
            <header class="atlas-dashboard-panel-header">
                <div>
                    <h3 class="atlas-dashboard-panel-title">
                        Gestión rápida
                    </h3>
                    <p class="atlas-dashboard-panel-subtitle">
                        Acceso a las funciones administrativas principales.
                    </p>
                </div>
            </header>

            <div class="atlas-dashboard-panel-body">
                <div class="atlas-dashboard-actions">

                    <a href="#" class="atlas-dashboard-action">
                        <span class="atlas-dashboard-action-icon">
                            <i class="bi bi-person-plus"></i>
                        </span>
                        <span class="atlas-dashboard-action-text">
                            <strong>Usuarios</strong>
                            <span>Crear y administrar cuentas</span>
                        </span>
                        <i class="bi bi-arrow-up-right atlas-dashboard-action-arrow"></i>
                    </a>

                    <a href="#" class="atlas-dashboard-action">
                        <span class="atlas-dashboard-action-icon">
                            <i class="bi bi-person-gear"></i>
                        </span>
                        <span class="atlas-dashboard-action-text">
                            <strong>Roles y permisos</strong>
                            <span>Configurar autorizaciones</span>
                        </span>
                        <i class="bi bi-arrow-up-right atlas-dashboard-action-arrow"></i>
                    </a>

                    <a href="#" class="atlas-dashboard-action">
                        <span class="atlas-dashboard-action-icon">
                            <i class="bi bi-key"></i>
                        </span>
                        <span class="atlas-dashboard-action-text">
                            <strong>Contraseñas</strong>
                            <span>Restablecer credenciales</span>
                        </span>
                        <i class="bi bi-arrow-up-right atlas-dashboard-action-arrow"></i>
                    </a>

                    <a href="#" class="atlas-dashboard-action">
                        <span class="atlas-dashboard-action-icon">
                            <i class="bi bi-journal-text"></i>
                        </span>
                        <span class="atlas-dashboard-action-text">
                            <strong>Auditoría</strong>
                            <span>Consultar actividad del sistema</span>
                        </span>
                        <i class="bi bi-arrow-up-right atlas-dashboard-action-arrow"></i>
                    </a>

                </div>
            </div>
        </section>

        <section class="atlas-dashboard-panel">
            <header class="atlas-dashboard-panel-header">
                <div>
                    <h3 class="atlas-dashboard-panel-title">
                        Estado de seguridad
                    </h3>
                    <p class="atlas-dashboard-panel-subtitle">
                        Supervisión de accesos y actividad.
                    </p>
                </div>

                <span class="atlas-dashboard-tag">
                    Sin datos
                </span>
            </header>

            <div class="atlas-dashboard-panel-body">
                <div class="atlas-dashboard-list">

                    <div class="atlas-dashboard-list-item">
                        <div>
                            <span class="atlas-dashboard-list-label">
                                Accesos registrados hoy
                            </span>
                            <span class="atlas-dashboard-list-note">
                                Inicios de sesión
                            </span>
                        </div>
                        <strong class="atlas-dashboard-list-value">—</strong>
                    </div>

                    <div class="atlas-dashboard-list-item">
                        <div>
                            <span class="atlas-dashboard-list-label">
                                Intentos fallidos
                            </span>
                            <span class="atlas-dashboard-list-note">
                                Autenticaciones rechazadas
                            </span>
                        </div>
                        <strong class="atlas-dashboard-list-value">—</strong>
                    </div>

                    <div class="atlas-dashboard-list-item">
                        <div>
                            <span class="atlas-dashboard-list-label">
                                Cambios de permisos
                            </span>
                            <span class="atlas-dashboard-list-note">
                                Modificaciones registradas
                            </span>
                        </div>
                        <strong class="atlas-dashboard-list-value">—</strong>
                    </div>

                </div>
            </div>
        </section>

    </div>

    <div class="atlas-dashboard-columns equal">

        <section class="atlas-dashboard-panel">
            <header class="atlas-dashboard-panel-header">
                <div>
                    <h3 class="atlas-dashboard-panel-title">
                        Actividad reciente del sistema
                    </h3>
                    <p class="atlas-dashboard-panel-subtitle">
                        Acciones registradas en la bitácora.
                    </p>
                </div>
            </header>

            <div class="atlas-dashboard-panel-body">
                <div class="atlas-dashboard-empty">
                    <div class="atlas-dashboard-empty-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h4>Sin actividad disponible</h4>
                    <p>
                        Los eventos aparecerán cuando esté habilitado
                        el registro de auditoría.
                    </p>
                </div>
            </div>
        </section>

        <section class="atlas-dashboard-panel">
            <header class="atlas-dashboard-panel-header">
                <div>
                    <h3 class="atlas-dashboard-panel-title">
                        Control institucional
                    </h3>
                    <p class="atlas-dashboard-panel-subtitle">
                        Supervisión administrativa complementaria.
                    </p>
                </div>
            </header>

            <div class="atlas-dashboard-panel-body">
                <div class="atlas-dashboard-actions">

                    <a href="#" class="atlas-dashboard-action">
                        <span class="atlas-dashboard-action-icon">
                            <i class="bi bi-wallet2"></i>
                        </span>
                        <span class="atlas-dashboard-action-text">
                            <strong>Finanzas</strong>
                            <span>Control financiero</span>
                        </span>
                    </a>

                    <a href="#" class="atlas-dashboard-action">
                        <span class="atlas-dashboard-action-icon">
                            <i class="bi bi-qr-code-scan"></i>
                        </span>
                        <span class="atlas-dashboard-action-text">
                            <strong>Asistencia</strong>
                            <span>Entradas y salidas</span>
                        </span>
                    </a>

                </div>

                <div class="atlas-dashboard-notice" style="margin-top:18px;">
                    <i class="bi bi-info-circle"></i>
                    <span>
                        Los indicadores se conectarán con los registros
                        reales cuando implementemos los módulos.
                    </span>
                </div>
            </div>
        </section>

    </div>

</div>

@endsection
