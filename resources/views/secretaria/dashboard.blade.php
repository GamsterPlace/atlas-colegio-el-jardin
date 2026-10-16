
@extends('layouts.secretaria')

@section('title', 'Dashboard de Secretaría')
@section('module-title', 'Panel de Secretaría')

@section('content')

<div class="atlas-dashboard">

    <section class="atlas-dashboard-hero">
        <div class="atlas-dashboard-hero-content">
            <span class="atlas-dashboard-eyebrow">
                <i class="bi bi-building"></i>
                Gestión administrativa escolar
            </span>

            <h2>Panel de Secretaría</h2>

            <p>
                Administración de inscripciones, expedientes, pagos,
                asistencia y comunicación institucional.
            </p>
        </div>

        <div class="atlas-dashboard-hero-icon">
            <i class="bi bi-folder2-open"></i>
        </div>
    </section>

    <section class="atlas-dashboard-stats">

        <article class="atlas-dashboard-stat">
            <div>
                <span class="atlas-dashboard-stat-label">
                    Inscripciones registradas
                </span>
                <strong class="atlas-dashboard-stat-value">—</strong>
                <span class="atlas-dashboard-stat-note">
                    Ciclo escolar vigente
                </span>
            </div>

            <div class="atlas-dashboard-stat-icon">
                <i class="bi bi-person-plus"></i>
            </div>
        </article>

        <article class="atlas-dashboard-stat">
            <div>
                <span class="atlas-dashboard-stat-label">
                    Expedientes pendientes
                </span>
                <strong class="atlas-dashboard-stat-value">—</strong>
                <span class="atlas-dashboard-stat-note">
                    Registros por completar
                </span>
            </div>

            <div class="atlas-dashboard-stat-icon amber">
                <i class="bi bi-folder-symlink"></i>
            </div>
        </article>

        <article class="atlas-dashboard-stat">
            <div>
                <span class="atlas-dashboard-stat-label">
                    Pagos registrados hoy
                </span>
                <strong class="atlas-dashboard-stat-value">—</strong>
                <span class="atlas-dashboard-stat-note">
                    Movimientos del día
                </span>
            </div>

            <div class="atlas-dashboard-stat-icon green">
                <i class="bi bi-receipt"></i>
            </div>
        </article>

        <article class="atlas-dashboard-stat">
            <div>
                <span class="atlas-dashboard-stat-label">
                    Actividades académicas
                </span>
                <strong class="atlas-dashboard-stat-value">—</strong>
                <span class="atlas-dashboard-stat-note">
                    Próximos eventos programados
                </span>
            </div>

            <div class="atlas-dashboard-stat-icon navy">
                <i class="bi bi-calendar-event"></i>
            </div>
        </article>

    </section>

    <div class="atlas-dashboard-columns">

        <section class="atlas-dashboard-panel">
            <header class="atlas-dashboard-panel-header">
                <div>
                    <h3 class="atlas-dashboard-panel-title">
                        Operaciones frecuentes
                    </h3>
                    <p class="atlas-dashboard-panel-subtitle">
                        Herramientas de atención y gestión escolar.
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
                            <strong>Nueva inscripción</strong>
                            <span>Registrar estudiante</span>
                        </span>
                        <i class="bi bi-arrow-up-right atlas-dashboard-action-arrow"></i>
                    </a>

                    <a href="#" class="atlas-dashboard-action">
                        <span class="atlas-dashboard-action-icon">
                            <i class="bi bi-folder2-open"></i>
                        </span>
                        <span class="atlas-dashboard-action-text">
                            <strong>Expedientes</strong>
                            <span>Consultar y actualizar</span>
                        </span>
                        <i class="bi bi-arrow-up-right atlas-dashboard-action-arrow"></i>
                    </a>

                    <a href="#" class="atlas-dashboard-action">
                        <span class="atlas-dashboard-action-icon">
                            <i class="bi bi-cash-coin"></i>
                        </span>
                        <span class="atlas-dashboard-action-text">
                            <strong>Registrar pago</strong>
                            <span>Movimientos financieros</span>
                        </span>
                        <i class="bi bi-arrow-up-right atlas-dashboard-action-arrow"></i>
                    </a>

                    <a href="#" class="atlas-dashboard-action">
                        <span class="atlas-dashboard-action-icon">
                            <i class="bi bi-qr-code-scan"></i>
                        </span>
                        <span class="atlas-dashboard-action-text">
                            <strong>Asistencia</strong>
                            <span>Registrar entradas y salidas</span>
                        </span>
                        <i class="bi bi-arrow-up-right atlas-dashboard-action-arrow"></i>
                    </a>

                    <a href="#" class="atlas-dashboard-action">
                        <span class="atlas-dashboard-action-icon">
                            <i class="bi bi-calendar-plus"></i>
                        </span>
                        <span class="atlas-dashboard-action-text">
                            <strong>Agenda académica</strong>
                            <span>Crear y administrar eventos</span>
                        </span>
                        <i class="bi bi-arrow-up-right atlas-dashboard-action-arrow"></i>
                    </a>

                    <a href="#" class="atlas-dashboard-action">
                        <span class="atlas-dashboard-action-icon">
                            <i class="bi bi-megaphone"></i>
                        </span>
                        <span class="atlas-dashboard-action-text">
                            <strong>Comunicados</strong>
                            <span>Información institucional</span>
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
                        Agenda académica
                    </h3>
                    <p class="atlas-dashboard-panel-subtitle">
                        Próximas actividades institucionales.
                    </p>
                </div>

                <span class="atlas-dashboard-tag">
                    Sin datos
                </span>
            </header>

            <div class="atlas-dashboard-panel-body">
                <div class="atlas-dashboard-empty">
                    <div class="atlas-dashboard-empty-icon">
                        <i class="bi bi-calendar3"></i>
                    </div>
                    <h4>Sin actividades disponibles</h4>
                    <p>
                        Los eventos aparecerán cuando se habilite
                        la gestión de la agenda académica.
                    </p>
                </div>
            </div>
        </section>

    </div>

    <div class="atlas-dashboard-columns equal">

        <section class="atlas-dashboard-panel">
            <header class="atlas-dashboard-panel-header">
                <div>
                    <h3 class="atlas-dashboard-panel-title">
                        Control de asistencia
                    </h3>
                    <p class="atlas-dashboard-panel-subtitle">
                        Resumen de movimientos del día.
                    </p>
                </div>
            </header>

            <div class="atlas-dashboard-panel-body">
                <div class="atlas-dashboard-list">

                    <div class="atlas-dashboard-list-item">
                        <div>
                            <span class="atlas-dashboard-list-label">
                                Entradas registradas
                            </span>
                            <span class="atlas-dashboard-list-note">
                                Ingresos estudiantiles
                            </span>
                        </div>
                        <strong class="atlas-dashboard-list-value">—</strong>
                    </div>

                    <div class="atlas-dashboard-list-item">
                        <div>
                            <span class="atlas-dashboard-list-label">
                                Salidas registradas
                            </span>
                            <span class="atlas-dashboard-list-note">
                                Egresos estudiantiles
                            </span>
                        </div>
                        <strong class="atlas-dashboard-list-value">—</strong>
                    </div>

                    <div class="atlas-dashboard-list-item">
                        <div>
                            <span class="atlas-dashboard-list-label">
                                Avisos de inasistencia
                            </span>
                            <span class="atlas-dashboard-list-note">
                                Notificaciones de padres
                            </span>
                        </div>
                        <strong class="atlas-dashboard-list-value">—</strong>
                    </div>

                </div>
            </div>
        </section>

        <section class="atlas-dashboard-panel">
            <header class="atlas-dashboard-panel-header">
                <div>
                    <h3 class="atlas-dashboard-panel-title">
                        Actividad administrativa reciente
                    </h3>
                    <p class="atlas-dashboard-panel-subtitle">
                        Inscripciones, pagos y expedientes.
                    </p>
                </div>
            </header>

            <div class="atlas-dashboard-panel-body">
                <div class="atlas-dashboard-empty">
                    <div class="atlas-dashboard-empty-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h4>Sin movimientos disponibles</h4>
                    <p>
                        La actividad se mostrará cuando los módulos
                        administrativos estén conectados.
                    </p>
                </div>
            </div>
        </section>

    </div>

</div>

@endsection
