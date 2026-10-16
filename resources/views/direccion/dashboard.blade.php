
@extends('layouts.direccion')

@section('title', 'Panel de Dirección')
@section('module-title', 'Panel de Dirección')

@php
    $indicadores = $indicadores ?? [];
    $asistencia = $asistencia ?? [];
    $finanzas = $finanzas ?? [];
    $proximasActividades = $proximasActividades ?? collect();
    $actividadReciente = $actividadReciente ?? collect();
    $ciclosEscolares = $ciclosEscolares ?? collect();
    $esCicloActual = $esCicloActual ?? false;
    $fechaReferenciaAsistencia = $fechaReferenciaAsistencia ?? null;

    $totalEstudiantes = $indicadores['estudiantes'] ?? null;
    $totalDocentes = $indicadores['docentes'] ?? null;
    $porcentajeAsistencia = $indicadores['asistencia_hoy'] ?? null;
    $totalPagos = $indicadores['pagos'] ?? null;

    $presentesHoy = $asistencia['presentes'] ?? null;
    $ausentesHoy = $asistencia['ausentes'] ?? null;
    $tardesHoy = $asistencia['tardes'] ?? null;
    $justificadosHoy = $asistencia['justificados'] ?? null;
    $sinRegistroHoy = $asistencia['sin_registro'] ?? null;
    $registrosHoy = $asistencia['registros_hoy'] ?? null;

    $pagosRegistrados = $finanzas['pagos_registrados'] ?? null;
    $saldosPendientes = $finanzas['saldos_pendientes'] ?? null;
    $totalRecaudado = $finanzas['total_recaudado'] ?? null;

    $formatoNumero = fn ($valor) => $valor === null
        ? '—'
        : number_format((float) $valor, 0, '.', ',');

    $formatoMoneda = fn ($valor) => $valor === null
        ? '—'
        : 'Q ' . number_format((float) $valor, 2, '.', ',');

    $formatoPorcentaje = fn ($valor) => $valor === null
        ? '—'
        : number_format((float) $valor, 1, '.', ',') . '%';

    $estadisticas = [
        [
            'titulo' => 'Estudiantes inscritos',
            'valor' => $formatoNumero($totalEstudiantes),
            'descripcion' => 'Matrícula del ciclo escolar',
            'icono' => 'bi-people',
            'color' => '',
        ],
        [
            'titulo' => 'Docentes activos',
            'valor' => $formatoNumero($totalDocentes),
            'descripcion' => 'Personal docente registrado',
            'icono' => 'bi-person-workspace',
            'color' => 'green',
        ],
        [
            'titulo' => 'Asistencia de hoy',
            'valor' => $formatoPorcentaje($porcentajeAsistencia),
            'descripcion' => $esCicloActual ? 'Asistencia estudiantil efectiva de hoy' : 'Último día con asistencia del ciclo',
            'icono' => 'bi-person-check',
            'color' => 'amber',
        ],
        [
            'titulo' => 'Pagos registrados',
            'valor' => $formatoNumero($totalPagos),
            'descripcion' => 'Registros del ciclo seleccionado',
            'icono' => 'bi-wallet2',
            'color' => 'navy',
        ],
    ];

    $accesosRapidos = [
        [
            'titulo' => 'Estudiantes',
            'descripcion' => 'Control estudiantil',
            'icono' => 'bi-people',
            'ruta' => 'direccion.estudiantes.index',
        ],
        [
            'titulo' => 'Docentes',
            'descripcion' => 'Gestión docente',
            'icono' => 'bi-person-workspace',
            'ruta' => 'direccion.docentes.index',
        ],
        [
            'titulo' => 'Asistencia',
            'descripcion' => 'Asistencia estudiantil',
            'icono' => 'bi-clipboard2-check',
            'ruta' => 'direccion.asistencia.index',
        ],
        [
            'titulo' => 'Finanzas',
            'descripcion' => 'Control financiero',
            'icono' => 'bi-wallet2',
            'ruta' => 'direccion.finanzas.index',
        ],
        [
            'titulo' => 'Horarios',
            'descripcion' => 'Generación automática',
            'icono' => 'bi-calendar-week',
            'ruta' => 'direccion.horarios.index',
        ],
        [
            'titulo' => 'Agenda',
            'descripcion' => 'Actividades académicas',
            'icono' => 'bi-calendar-event',
            'ruta' => 'direccion.agenda.index',
        ],
    ];

    $estadosAsistencia = [
        [
            'titulo' => 'Presentes',
            'valor' => $presentesHoy,
            'descripcion' => 'Asistencia puntual',
            'icono' => 'bi-person-check',
            'color' => 'green',
        ],
        [
            'titulo' => 'Ausentes',
            'valor' => $ausentesHoy,
            'descripcion' => 'Ausencias registradas',
            'icono' => 'bi-person-x',
            'color' => 'red',
        ],
        [
            'titulo' => 'Llegadas tarde',
            'valor' => $tardesHoy,
            'descripcion' => 'Asistencia con tardanza',
            'icono' => 'bi-clock-history',
            'color' => 'amber',
        ],
        [
            'titulo' => 'Justificados',
            'valor' => $justificadosHoy,
            'descripcion' => 'Ausencias justificadas',
            'icono' => 'bi-file-earmark-check',
            'color' => 'navy',
        ],
    ];
@endphp

@section('content')

<div class="atlas-dashboard">

    <div class="atlas-dashboard-hero">
        <div class="atlas-dashboard-hero-content">

            <span class="atlas-dashboard-eyebrow">
                <i class="bi bi-building-check"></i>
                Gestión institucional
            </span>

            <h2>Panel de Dirección</h2>

            <p>
                Supervisión académica, administrativa y operativa
                del Colegio Mixto El Jardín.
            </p>

            <form method="GET" action="{{ url()->current() }}" style="margin-top: 18px; display: flex; flex-wrap: wrap; align-items: end; gap: 12px;">
                <div style="min-width: 230px; max-width: 360px; flex: 1;">
                    <label for="ciclo_escolar_id" style="display: block; margin-bottom: 6px; font-size: 13px; font-weight: 600;">Ciclo escolar</label>
                    <select id="ciclo_escolar_id" name="ciclo_escolar_id" class="atlas-input" onchange="this.form.submit()" style="width: 100%; padding: 10px 12px; border-radius: 9px; color: #1e293b; background: #fff;">
                        @if(!$cicloEscolar)
                            <option value="">Sin ciclo actual disponible</option>
                        @endif
                        @foreach($ciclosEscolares as $cicloOpcion)
                            @php
                                $anioOpcion = preg_match('/(?:19|20)\d{2}/', (string) $cicloOpcion->nombre, $anioCoincidencia)
                                    ? (int) $anioCoincidencia[0]
                                    : \Illuminate\Support\Carbon::parse($cicloOpcion->fecha_inicio)->year;
                                $tipoOpcion = $anioOpcion > now()->year ? 'Futuro' : ($anioOpcion < now()->year ? 'Histórico' : 'Actual');
                            @endphp
                            <option value="{{ $cicloOpcion->id }}" @selected($cicloEscolar && $cicloEscolar->id === $cicloOpcion->id)>
                                {{ $cicloOpcion->nombre }} — {{ $tipoOpcion }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if($cicloEscolar)
                    <span class="atlas-dashboard-tag" style="margin-bottom: 5px;">
                        <i class="bi bi-calendar-check"></i>
                        {{ $cicloEscolar->nombre }}
                    </span>
                @endif
            </form>

        </div>

        <div class="atlas-dashboard-hero-icon">
            <i class="bi bi-building"></i>
        </div>
    </div>

    <div class="atlas-dashboard-stats">

        @foreach($estadisticas as $estadistica)

            <div class="atlas-dashboard-stat">

                <div>
                    <span class="atlas-dashboard-stat-label">
                        {{ $estadistica['titulo'] }}
                    </span>

                    <strong class="atlas-dashboard-stat-value">
                        {{ $estadistica['valor'] }}
                    </strong>

                    <span class="atlas-dashboard-stat-note">
                        {{ $estadistica['descripcion'] }}
                    </span>
                </div>

                <div class="atlas-dashboard-stat-icon {{ $estadistica['color'] }}">
                    <i class="bi {{ $estadistica['icono'] }}"></i>
                </div>

            </div>

        @endforeach

    </div>

    <div class="atlas-dashboard-columns">

        <section class="atlas-dashboard-panel">

            <div class="atlas-dashboard-panel-header">
                <div>
                    <h3 class="atlas-dashboard-panel-title">
                        <i class="bi bi-lightning-charge"></i>
                        Accesos rápidos
                    </h3>

                    <p class="atlas-dashboard-panel-subtitle">
                        Herramientas principales de gestión institucional.
                    </p>
                </div>
            </div>

            <div class="atlas-dashboard-panel-body">

                <div class="atlas-dashboard-actions">

                    @foreach($accesosRapidos as $acceso)

                        @php
                            $rutaDisponible = \Illuminate\Support\Facades\Route::has(
                                $acceso['ruta']
                            );
                        @endphp

                        @if($rutaDisponible)
                            <a
                                href="{{ route($acceso['ruta']) }}"
                                class="atlas-dashboard-action"
                            >
                        @else
                            <div
                                class="atlas-dashboard-action"
                                aria-disabled="true"
                                title="Módulo pendiente de implementación"
                            >
                        @endif

                            <div class="atlas-dashboard-action-icon">
                                <i class="bi {{ $acceso['icono'] }}"></i>
                            </div>

                            <div class="atlas-dashboard-action-text">
                                <strong>{{ $acceso['titulo'] }}</strong>

                                <span>
                                    {{ $rutaDisponible
                                        ? $acceso['descripcion']
                                        : 'Próximamente' }}
                                </span>
                            </div>

                            <i class="bi {{ $rutaDisponible ? 'bi-arrow-up-right' : 'bi-lock' }} atlas-dashboard-action-arrow"></i>

                        @if($rutaDisponible)
                            </a>
                        @else
                            </div>
                        @endif

                    @endforeach

                </div>

            </div>

        </section>

        <section class="atlas-dashboard-panel">

            <div class="atlas-dashboard-panel-header">

                <div>
                    <h3 class="atlas-dashboard-panel-title">
                        <i class="bi bi-calendar3"></i>
                        Agenda académica
                    </h3>

                    <p class="atlas-dashboard-panel-subtitle">
                        Próximas actividades y eventos institucionales.
                    </p>
                </div>

                @if(\Illuminate\Support\Facades\Route::has('direccion.agenda.index'))
                    <a
                        href="{{ route('direccion.agenda.index') }}"
                        class="atlas-dashboard-tag"
                    >
                        Ver agenda
                    </a>
                @endif

            </div>

            <div class="atlas-dashboard-panel-body">

                @forelse($proximasActividades as $actividad)

                    <div class="atlas-dashboard-list-item">

                        <div>
                            <strong class="atlas-dashboard-list-label">
                                {{ data_get($actividad, 'titulo', 'Actividad académica') }}
                            </strong>

                            <span class="atlas-dashboard-list-note">
                                @if(data_get($actividad, 'fecha'))
                                    {{ \Illuminate\Support\Carbon::parse(data_get($actividad, 'fecha'))->locale('es')->translatedFormat('d \d\e F \d\e Y') }}
                                @else
                                    Fecha por confirmar
                                @endif
                            </span>

                            @if(data_get($actividad, 'descripcion'))
                                <p class="atlas-dashboard-panel-subtitle">
                                    {{ data_get($actividad, 'descripcion') }}
                                </p>
                            @endif
                        </div>

                        <div class="atlas-dashboard-action-icon">
                            <i class="bi bi-calendar-event"></i>
                        </div>

                    </div>

                @empty

                    <div class="atlas-dashboard-empty">

                        <div class="atlas-dashboard-empty-icon">
                            <i class="bi bi-calendar-event"></i>
                        </div>

                        <h4>Sin actividades disponibles</h4>

                        <p>
                            No hay actividades académicas para mostrar
                            en este momento.
                        </p>

                    </div>

                @endforelse

            </div>

        </section>

    </div>

    <div class="atlas-dashboard-columns equal">

        <section class="atlas-dashboard-panel">

            <div class="atlas-dashboard-panel-header">

                <div>
                    <h3 class="atlas-dashboard-panel-title">
                        <i class="bi bi-clipboard2-check"></i>
                        Control de asistencia estudiantil
                    </h3>

                    <p class="atlas-dashboard-panel-subtitle">
                        {{ $esCicloActual ? 'Resumen de los registros de asistencia del día.' : 'Última fecha de asistencia registrada en el ciclo seleccionado.' }}
                    </p>
                </div>

                <span class="atlas-dashboard-tag">
                    <i class="bi bi-calendar-check"></i>
                    {{ $fechaReferenciaAsistencia ? \Illuminate\Support\Carbon::parse($fechaReferenciaAsistencia)->format('d/m/Y') : 'Sin registros' }}
                </span>

            </div>

            <div class="atlas-dashboard-panel-body">

                <div class="atlas-grid atlas-grid-2">

                    @foreach($estadosAsistencia as $estado)

                        <div class="atlas-stat-card">

                            <div class="atlas-stat-info">

                                <span class="atlas-stat-label">
                                    {{ $estado['titulo'] }}
                                </span>

                                <strong class="atlas-stat-value">
                                    {{ $formatoNumero($estado['valor']) }}
                                </strong>

                                <span class="atlas-stat-description">
                                    {{ $estado['descripcion'] }}
                                </span>

                            </div>

                            <div class="atlas-stat-icon {{ $estado['color'] }}">
                                <i class="bi {{ $estado['icono'] }}"></i>
                            </div>

                        </div>

                    @endforeach

                </div>

                <div class="atlas-table-responsive" style="margin-top: 24px;">

                    <table class="atlas-table">
                        <tbody>

                            <tr>
                                <td class="atlas-table-primary">
                                    Estudiantes inscritos
                                </td>
                                <td style="text-align: right;">
                                    {{ $formatoNumero($totalEstudiantes) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="atlas-table-primary">
                                    Asistencias registradas
                                </td>
                                <td style="text-align: right;">
                                    {{ $formatoNumero($registrosHoy) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="atlas-table-primary">
                                    Sin registro de asistencia
                                </td>
                                <td style="text-align: right;">
                                    {{ $formatoNumero($sinRegistroHoy) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="atlas-table-primary">
                                    Porcentaje de asistencia
                                </td>
                                <td style="text-align: right;">
                                    {{ $formatoPorcentaje($porcentajeAsistencia) }}
                                </td>
                            </tr>

                        </tbody>
                    </table>

                </div>

                <div class="atlas-dashboard-notice" style="margin-top: 24px;">

                    <i class="bi bi-info-circle"></i>

                    <div>
                        La asistencia efectiva incluye a los estudiantes
                        registrados como presentes y con llegada tarde.
                        Las ausencias justificadas y los estudiantes sin
                        registro se presentan por separado.
                    </div>

                </div>

            </div>

        </section>

        <section class="atlas-dashboard-panel">

            <div class="atlas-dashboard-panel-header">
                <div>
                    <h3 class="atlas-dashboard-panel-title">
                        <i class="bi bi-graph-up-arrow"></i>
                        Resumen financiero
                    </h3>

                    <p class="atlas-dashboard-panel-subtitle">
                        Información financiera exclusiva del ciclo {{ $cicloEscolar?->nombre ?? "no disponible" }}.
                    </p>
                </div>
            </div>

            <div class="atlas-dashboard-panel-body">

                <div class="atlas-table-responsive">

                    <table class="atlas-table">
                        <thead>
                            <tr>
                                <th>Indicador</th>
                                <th style="text-align: right;">Valor</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td class="atlas-table-primary">
                                    Pagos registrados
                                </td>
                                <td style="text-align: right;">
                                    {{ $formatoNumero($pagosRegistrados) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="atlas-table-primary">
                                    Saldos pendientes
                                </td>
                                <td style="text-align: right;">
                                    {{ $formatoMoneda($saldosPendientes) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="atlas-table-primary">
                                    Total recaudado
                                </td>
                                <td style="text-align: right;">
                                    {{ $formatoMoneda($totalRecaudado) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>

                <div class="atlas-dashboard-notice" style="margin-top: 24px;">

                    <i class="bi bi-shield-lock"></i>

                    <div>
                        La información financiera está restringida
                        a los usuarios que tengan autorización
                        para consultar estos datos.
                    </div>

                </div>

            </div>

        </section>

    </div>

    <section class="atlas-dashboard-panel">

        <div class="atlas-dashboard-panel-header">
            <div>
                <h3 class="atlas-dashboard-panel-title">
                    <i class="bi bi-clock-history"></i>
                    Actividad institucional reciente
                </h3>

                <p class="atlas-dashboard-panel-subtitle">
                    Movimientos y acontecimientos registrados en ATLAS.
                </p>
            </div>
        </div>

        <div class="atlas-dashboard-panel-body">

            @forelse($actividadReciente as $actividad)

                <div class="atlas-dashboard-list-item">

                    <div>
                        <strong class="atlas-dashboard-list-label">
                            {{ data_get($actividad, 'titulo', 'Actividad institucional') }}
                        </strong>

                        @if(data_get($actividad, 'descripcion'))
                            <p class="atlas-dashboard-panel-subtitle">
                                {{ data_get($actividad, 'descripcion') }}
                            </p>
                        @endif

                        @if(data_get($actividad, 'fecha'))
                            <span class="atlas-dashboard-list-note">
                                {{ \Illuminate\Support\Carbon::parse(data_get($actividad, 'fecha'))->locale('es')->translatedFormat('d/m/Y H:i') }}
                            </span>
                        @endif
                    </div>

                    <div class="atlas-dashboard-action-icon">
                        <i class="bi bi-activity"></i>
                    </div>

                </div>

            @empty

                <div class="atlas-dashboard-empty">

                    <div class="atlas-dashboard-empty-icon">
                        <i class="bi bi-activity"></i>
                    </div>

                    <h4>No hay actividad para mostrar</h4>

                    <p>
                        Los movimientos institucionales aparecerán aquí
                        cuando los módulos correspondientes estén
                        conectados al dashboard.
                    </p>

                </div>

            @endforelse

        </div>

    </section>

</div>

@endsection
