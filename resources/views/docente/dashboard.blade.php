
@extends('layouts.docente')

@section('title', 'Dashboard Docente')
@section('module-title', 'Panel Docente')

@section('content')

<div class="atlas-dashboard">

    <section class="atlas-dashboard-hero">
        <div class="atlas-dashboard-hero-content">
            <span class="atlas-dashboard-eyebrow">
                <i class="bi bi-mortarboard"></i>
                Gestión académica
            </span>

            <h2>Mi panel docente</h2>

            <p>
                Organización de clases, seguimiento estudiantil,
                calificaciones, asistencia y actividades académicas.
            </p>
        </div>

        <div class="atlas-dashboard-hero-icon">
            <i class="bi bi-person-workspace"></i>
        </div>
    </section>

    <section class="atlas-dashboard-stats">

        <article class="atlas-dashboard-stat">
            <div>
                <span class="atlas-dashboard-stat-label">
                    Estudiantes asignados
                </span>
                <strong class="atlas-dashboard-stat-value">—</strong>
                <span class="atlas-dashboard-stat-note">
                    Según asignaciones docentes
                </span>
            </div>

            <div class="atlas-dashboard-stat-icon">
                <i class="bi bi-people"></i>
            </div>
        </article>

        <article class="atlas-dashboard-stat">
            <div>
                <span class="atlas-dashboard-stat-label">
                    Cursos asignados
                </span>
                <strong class="atlas-dashboard-stat-value">—</strong>
                <span class="atlas-dashboard-stat-note">
                    Materias a cargo
                </span>
            </div>

            <div class="atlas-dashboard-stat-icon navy">
                <i class="bi bi-journal-bookmark"></i>
            </div>
        </article>

        <article class="atlas-dashboard-stat">
            <div>
                <span class="atlas-dashboard-stat-label">
                    Clases de hoy
                </span>
                <strong class="atlas-dashboard-stat-value">—</strong>
                <span class="atlas-dashboard-stat-note">
                    Según horario académico
                </span>
            </div>

            <div class="atlas-dashboard-stat-icon green">
                <i class="bi bi-calendar-week"></i>
            </div>
        </article>

        <article class="atlas-dashboard-stat">
            <div>
                <span class="atlas-dashboard-stat-label">
                    Avisos de padres
                </span>
                <strong class="atlas-dashboard-stat-value">—</strong>
                <span class="atlas-dashboard-stat-note">
                    Notificaciones por revisar
                </span>
            </div>

            <div class="atlas-dashboard-stat-icon amber">
                <i class="bi bi-envelope"></i>
            </div>
        </article>

    </section>

    <div class="atlas-dashboard-columns">

        <section class="atlas-dashboard-panel">
            <header class="atlas-dashboard-panel-header">
                <div>
                    <h3 class="atlas-dashboard-panel-title">
                        Herramientas docentes
                    </h3>
                    <p class="atlas-dashboard-panel-subtitle">
                        Acceso a las funciones académicas habituales.
                    </p>
                </div>
            </header>

            <div class="atlas-dashboard-panel-body">
                <div class="atlas-dashboard-actions">

                    <a href="#" class="atlas-dashboard-action">
                        <span class="atlas-dashboard-action-icon">
                            <i class="bi bi-pencil-square"></i>
                        </span>
                        <span class="atlas-dashboard-action-text">
                            <strong>Calificaciones</strong>
                            <span>Registrar y consultar notas</span>
                        </span>
                        <i class="bi bi-arrow-up-right atlas-dashboard-action-arrow"></i>
                    </a>

                    <a href="#" class="atlas-dashboard-action">
                        <span class="atlas-dashboard-action-icon">
                            <i class="bi bi-person-check"></i>
                        </span>
                        <span class="atlas-dashboard-action-text">
                            <strong>Asistencia</strong>
                            <span>Control de estudiantes</span>
                        </span>
                        <i class="bi bi-arrow-up-right atlas-dashboard-action-arrow"></i>
                    </a>

                    <a href="#" class="atlas-dashboard-action">
                        <span class="atlas-dashboard-action-icon">
                            <i class="bi bi-calendar-week"></i>
                        </span>
                        <span class="atlas-dashboard-action-text">
                            <strong>Mi horario</strong>
                            <span>Consultar clases asignadas</span>
                        </span>
                        <i class="bi bi-arrow-up-right atlas-dashboard-action-arrow"></i>
                    </a>

                    <a href="#" class="atlas-dashboard-action">
                        <span class="atlas-dashboard-action-icon">
                            <i class="bi bi-people"></i>
                        </span>
                        <span class="atlas-dashboard-action-text">
                            <strong>Mis estudiantes</strong>
                            <span>Seguimiento académico</span>
                        </span>
                        <i class="bi bi-arrow-up-right atlas-dashboard-action-arrow"></i>
                    </a>

                    <a href="#" class="atlas-dashboard-action">
                        <span class="atlas-dashboard-action-icon">
                            <i class="bi bi-person-badge"></i>
                        </span>
                        <span class="atlas-dashboard-action-text">
                            <strong>Grado guía</strong>
                            <span>Supervisión y reportería</span>
                        </span>
                        <i class="bi bi-arrow-up-right atlas-dashboard-action-arrow"></i>
                    </a>

                    <a href="#" class="atlas-dashboard-action">
                        <span class="atlas-dashboard-action-icon">
                            <i class="bi bi-bar-chart-line"></i>
                        </span>
                        <span class="atlas-dashboard-action-text">
                            <strong>Reportes</strong>
                            <span>Información académica</span>
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
                        Mi horario de hoy
                    </h3>
                    <p class="atlas-dashboard-panel-subtitle">
                        Clases correspondientes a la jornada.
                    </p>
                </div>

                <span class="atlas-dashboard-tag">
                    Pendiente
                </span>
            </header>

            <div class="atlas-dashboard-panel-body">
                <div class="atlas-dashboard-empty">
                    <div class="atlas-dashboard-empty-icon">
                        <i class="bi bi-clock"></i>
                    </div>
                    <h4>Horario no disponible</h4>
                    <p>
                        Las clases aparecerán cuando se registren
                        las asignaciones y horarios docentes.
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
                        Agenda académica
                    </h3>
                    <p class="atlas-dashboard-panel-subtitle">
                        Consulta de actividades institucionales.
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
                        Los eventos registrados por Dirección
                        y Secretaría aparecerán en este espacio.
                    </p>
                </div>
            </div>
        </section>

        <section class="atlas-dashboard-panel">
            <header class="atlas-dashboard-panel-header">
                <div>
                    <h3 class="atlas-dashboard-panel-title">
                        Avisos de padres de familia
                    </h3>
                    <p class="atlas-dashboard-panel-subtitle">
                        Notificaciones de ausencias y retrasos.
                    </p>
                </div>
            </header>

            <div class="atlas-dashboard-panel-body">
                <div class="atlas-dashboard-empty">
                    <div class="atlas-dashboard-empty-icon">
                        <i class="bi bi-envelope-paper"></i>
                    </div>
                    <h4>Sin avisos disponibles</h4>
                    <p>
                        Las notificaciones de los padres se mostrarán
                        cuando se habilite el módulo de comunicación.
                    </p>
                </div>
            </div>
        </section>

    </div>

    <div class="atlas-dashboard-notice">
        <i class="bi bi-info-circle"></i>
        <span>
            El acceso a estudiantes, cursos, calificaciones y reportes
            dependerá de las asignaciones del docente. La agenda académica
            estará disponible únicamente para consulta.
        </span>
    </div>

</div>

@endsection
