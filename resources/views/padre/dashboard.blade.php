
@extends('layouts.padre')

@section('title', 'Dashboard de Padre de Familia')
@section('module-title', 'Panel de Familia')

@section('content')

<div class="atlas-dashboard">

    <section class="atlas-dashboard-hero">
        <div class="atlas-dashboard-hero-content">
            <span class="atlas-dashboard-eyebrow">
                <i class="bi bi-house-heart"></i>
                Portal de padres de familia
            </span>

            <h2>Bienvenido al portal familiar</h2>

            <p>
                Consulte el progreso académico, la asistencia,
                el comportamiento y la información financiera
                de sus hijos desde un solo lugar.
            </p>
        </div>

        <div class="atlas-dashboard-hero-icon">
            <i class="bi bi-people"></i>
        </div>
    </section>

    <section class="atlas-dashboard-stats">

        <article class="atlas-dashboard-stat">
            <div>
                <span class="atlas-dashboard-stat-label">
                    Hijos vinculados
                </span>
                <strong class="atlas-dashboard-stat-value">—</strong>
                <span class="atlas-dashboard-stat-note">
                    Estudiantes asociados a su cuenta
                </span>
            </div>

            <div class="atlas-dashboard-stat-icon">
                <i class="bi bi-people"></i>
            </div>
        </article>

        <article class="atlas-dashboard-stat">
            <div>
                <span class="atlas-dashboard-stat-label">
                    Asistencia de hoy
                </span>
                <strong class="atlas-dashboard-stat-value">—</strong>
                <span class="atlas-dashboard-stat-note">
                    Información de sus hijos
                </span>
            </div>

            <div class="atlas-dashboard-stat-icon green">
                <i class="bi bi-person-check"></i>
            </div>
        </article>

        <article class="atlas-dashboard-stat">
            <div>
                <span class="atlas-dashboard-stat-label">
                    Comunicados
                </span>
                <strong class="atlas-dashboard-stat-value">—</strong>
                <span class="atlas-dashboard-stat-note">
                    Avisos institucionales disponibles
                </span>
            </div>

            <div class="atlas-dashboard-stat-icon navy">
                <i class="bi bi-megaphone"></i>
            </div>
        </article>

        <article class="atlas-dashboard-stat">
            <div>
                <span class="atlas-dashboard-stat-label">
                    Próximas actividades
                </span>
                <strong class="atlas-dashboard-stat-value">—</strong>
                <span class="atlas-dashboard-stat-note">
                    Agenda académica
                </span>
            </div>

            <div class="atlas-dashboard-stat-icon amber">
                <i class="bi bi-calendar-event"></i>
            </div>
        </article>

    </section>

    <div class="atlas-dashboard-columns">

        <section class="atlas-dashboard-panel">
            <header class="atlas-dashboard-panel-header">
                <div>
                    <h3 class="atlas-dashboard-panel-title">
                        Mis hijos
                    </h3>
                    <p class="atlas-dashboard-panel-subtitle">
                        Estudiantes vinculados a su cuenta familiar.
                    </p>
                </div>
            </header>

            <div class="atlas-dashboard-panel-body">
                <div class="atlas-dashboard-empty">
                    <div class="atlas-dashboard-empty-icon">
                        <i class="bi bi-person-hearts"></i>
                    </div>
                    <h4>Información pendiente de vinculación</h4>
                    <p>
                        Aquí aparecerán los estudiantes asociados
                        a su cuenta, con acceso individual a
                        sus datos académicos y administrativos.
                    </p>
                </div>
            </div>
        </section>

        <section class="atlas-dashboard-panel">
            <header class="atlas-dashboard-panel-header">
                <div>
                    <h3 class="atlas-dashboard-panel-title">
                        Consultas y servicios
                    </h3>
                    <p class="atlas-dashboard-panel-subtitle">
                        Información escolar de sus hijos.
                    </p>
                </div>
            </header>

            <div class="atlas-dashboard-panel-body">
                <div class="atlas-dashboard-actions">

                    <a href="#" class="atlas-dashboard-action">
                        <span class="atlas-dashboard-action-icon">
                            <i class="bi bi-journal-check"></i>
                        </span>
                        <span class="atlas-dashboard-action-text">
                            <strong>Calificaciones</strong>
                            <span>Consultar resultados académicos</span>
                        </span>
                    </a>

                    <a href="#" class="atlas-dashboard-action">
                        <span class="atlas-dashboard-action-icon">
                            <i class="bi bi-clipboard-check"></i>
                        </span>
                        <span class="atlas-dashboard-action-text">
                            <strong>Comportamiento</strong>
                            <span>Seguimiento estudiantil</span>
                        </span>
                    </a>

                    <a href="#" class="atlas-dashboard-action">
                        <span class="atlas-dashboard-action-icon">
                            <i class="bi bi-clock-history"></i>
                        </span>
                        <span class="atlas-dashboard-action-text">
                            <strong>Asistencia</strong>
                            <span>Entradas y salidas registradas</span>
                        </span>
                    </a>

                    <a href="#" class="atlas-dashboard-action">
                        <span class="atlas-dashboard-action-icon">
                            <i class="bi bi-receipt"></i>
                        </span>
                        <span class="atlas-dashboard-action-text">
                            <strong>Estado de cuenta</strong>
                            <span>Consultar saldos y pagos</span>
                        </span>
                    </a>

                    <a href="#" class="atlas-dashboard-action">
                        <span class="atlas-dashboard-action-icon">
                            <i class="bi bi-envelope-paper"></i>
                        </span>
                        <span class="atlas-dashboard-action-text">
                            <strong>Notificar ausencia</strong>
                            <span>Enviar aviso al colegio</span>
                        </span>
                    </a>

                    <a href="#" class="atlas-dashboard-action">
                        <span class="atlas-dashboard-action-icon">
                            <i class="bi bi-alarm"></i>
                        </span>
                        <span class="atlas-dashboard-action-text">
                            <strong>Notificar retraso</strong>
                            <span>Informar llegada tardía</span>
                        </span>
                    </a>

                </div>
            </div>
        </section>

    </div>

    <div class="atlas-dashboard-columns equal">

        <section class="atlas-dashboard-panel">
            <header class="atlas-dashboard-panel-header">
                <div>
                    <h3 class="atlas-dashboard-panel-title">
                        Registro de asistencia
                    </h3>
                    <p class="atlas-dashboard-panel-subtitle">
                        Últimos movimientos de sus hijos.
                    </p>
                </div>
            </header>

            <div class="atlas-dashboard-panel-body">
                <div class="atlas-dashboard-empty">
                    <div class="atlas-dashboard-empty-icon">
                        <i class="bi bi-qr-code-scan"></i>
                    </div>
                    <h4>Sin registros disponibles</h4>
                    <p>
                        Se mostrarán las fechas y horas exactas
                        de ingreso y salida de sus hijos.
                    </p>
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
                        Próximas actividades del colegio.
                    </p>
                </div>

                <span class="atlas-dashboard-tag">
                    Solo lectura
                </span>
            </header>

            <div class="atlas-dashboard-panel-body">
                <div class="atlas-dashboard-empty">
                    <div class="atlas-dashboard-empty-icon">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <h4>Sin actividades disponibles</h4>
                    <p>
                        Consulte aquí los eventos, reuniones
                        y actividades académicas que publique el colegio.
                    </p>
                </div>
            </div>
        </section>

    </div>

    <section class="atlas-dashboard-panel">
        <header class="atlas-dashboard-panel-header">
            <div>
                <h3 class="atlas-dashboard-panel-title">
                    Comunicados institucionales
                </h3>
                <p class="atlas-dashboard-panel-subtitle">
                    Información publicada para padres de familia.
                </p>
            </div>
        </header>

        <div class="atlas-dashboard-panel-body">
            <div class="atlas-dashboard-empty">
                <div class="atlas-dashboard-empty-icon">
                    <i class="bi bi-megaphone"></i>
                </div>
                <h4>Sin comunicados disponibles</h4>
                <p>
                    Las publicaciones dirigidas a padres de familia
                    aparecerán en esta sección.
                </p>
            </div>
        </div>
    </section>

    <div class="atlas-dashboard-notice">
        <i class="bi bi-shield-check"></i>
        <span>
            La información académica, de comportamiento, asistencia
            y estados de cuenta se limitará a los estudiantes
            vinculados a su cuenta. No se permitirá modificar
            calificaciones, registros de asistencia ni datos financieros.
        </span>
    </div>

</div>

@endsection
