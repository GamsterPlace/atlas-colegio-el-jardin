
@extends('layouts.atlas')

@section('title', 'Dirección')
@section('role', 'Dirección')
@section('module-title', 'Panel de Dirección')
@section('dashboard-url', route('direccion.dashboard'))

@php
    $menuDireccion = [
        [
            'titulo' => 'Gestión institucional',
            'modulos' => [
                [
                    'id' => 'direccion-estudiantes',
                    'titulo' => 'Estudiantes',
                    'icono' => 'bi-people',
                    'opciones' => [
                        [
                            'titulo' => 'Listado de estudiantes',
                            'ruta' => 'direccion.estudiantes.index',
                        ],
                        [
                            'titulo' => 'Expedientes',
                            'ruta' => 'direccion.estudiantes.expedientes.index',
                        ],
                        [
                            'titulo' => 'Control estudiantil',
                            'ruta' => 'direccion.estudiantes.control.index',
                        ],
                    ],
                ],
                [
                    'id' => 'direccion-docentes',
                    'titulo' => 'Docentes',
                    'icono' => 'bi-person-workspace',
                    'opciones' => [
                        [
                            'titulo' => 'Listado de docentes',
                            'ruta' => 'direccion.docentes.index',
                        ],
                        [
                            'titulo' => 'Control de docentes',
                            'ruta' => 'direccion.docentes.control.index',
                        ],
                        [
                            'titulo' => 'Asignaciones académicas',
                            'ruta' => 'direccion.docentes.asignaciones.index',
                        ],
                    ],
                ],
                [
                    'id' => 'direccion-inscripciones',
                    'titulo' => 'Inscripciones',
                    'icono' => 'bi-person-plus',
                    'opciones' => [
                        [
                            'titulo' => 'Inscripciones',
                            'ruta' => 'direccion.inscripciones.index',
                        ],
                        [
                            'titulo' => 'Control de inscripciones',
                            'ruta' => 'direccion.inscripciones.control.index',
                        ],
                    ],
                ],
            ],
        ],
        [
            'titulo' => 'Gestión académica',
            'modulos' => [
                [
                    'id' => 'direccion-notas',
                    'titulo' => 'Notas',
                    'icono' => 'bi-journal-check',
                    'opciones' => [
                        [
                            'titulo' => 'Consulta de calificaciones',
                            'ruta' => 'direccion.notas.index',
                        ],
                        [
                            'titulo' => 'Cuadros de notas',
                            'ruta' => 'direccion.notas.cuadros.index',
                        ],
                        [
                            'titulo' => 'Control académico',
                            'ruta' => 'direccion.notas.control.index',
                        ],
                    ],
                ],
                [
                    'id' => 'direccion-agenda',
                    'titulo' => 'Agenda académica',
                    'icono' => 'bi-calendar-event',
                    'opciones' => [
                        [
                            'titulo' => 'Calendario académico',
                            'ruta' => 'direccion.agenda.index',
                        ],
                        [
                            'titulo' => 'Crear actividad',
                            'ruta' => 'direccion.agenda.create',
                        ],
                        [
                            'titulo' => 'Gestionar actividades',
                            'ruta' => 'direccion.agenda.gestionar',
                        ],
                    ],
                ],

                [
                    'id' => 'direccion-horarios',
                    'titulo' => 'Horarios académicos',
                    'icono' => 'bi-calendar-week',
                    'opciones' => [
                        [
                            'titulo' => 'Consultar horarios',
                            'ruta' => 'direccion.horarios.index',
                        ],
                        [
                            'titulo' => 'Configurar bloques horarios',
                            'ruta' => 'direccion.horarios.bloques.index',
                        ],
                        [
                            'titulo' => 'Configurar restricciones',
                            'ruta' => 'direccion.horarios.restricciones.index',
                        ],
                        [
                            'titulo' => 'Distribución de cursos',//carga academica
                            'ruta' => 'direccion.horarios.cargas.index',
                        ],
                    ],
                ],

            ],
        ],
        [
            'titulo' => 'Control institucional',
            'modulos' => [
                [
                    'id' => 'direccion-asistencia',
                    'titulo' => 'Asistencia estudiantil',
                    'icono' => 'bi-clipboard2-check',
                    'opciones' => [
                        [
                            'titulo' => 'Registro de asistencia',
                            'ruta' => 'direccion.asistencia.index',
                        ],
                        [
                            'titulo' => 'Historial de asistencia',
                            'ruta' => 'direccion.asistencia.historial.index',
                        ],
                        [
                            'titulo' => 'Justificaciones',
                            'ruta' => 'direccion.asistencia.justificaciones.index',
                        ],
                    ],
                ],
                [
                    'id' => 'direccion-finanzas',
                    'titulo' => 'Finanzas',
                    'icono' => 'bi-wallet2',
                    'opciones' => [
                        [
                            'titulo' => 'Panel financiero',
                            'ruta' => 'direccion.finanzas.index',
                        ],
                        [
                            'titulo' => 'Configuración financiera',
                            'ruta' => 'direccion.finanzas.configuracion.index',
                        ],
                        [
                            'titulo' => 'Cuentas por cobrar',
                            'ruta' => 'direccion.finanzas.cuentas.index',
                        ],
                        [
                            'titulo' => 'Estados de cuenta estudiantiles',
                            'ruta' => 'direccion.finanzas.estados-cuenta.index',
                        ],
                        [
                            'titulo' => 'Registro de pagos',
                            'ruta' => 'direccion.finanzas.pagos.index',
                        ],
                        [
                            'titulo' => 'Reportes financieros',
                            'ruta' => 'direccion.finanzas.reportes.index',
                        ],
                    ],
                ],
                [
                    'id' => 'direccion-inventario',
                    'titulo' => 'Inventario',
                    'icono' => 'bi-box-seam',
                    'opciones' => [
                        [
                            'titulo' => 'Uniformes',
                            'ruta' => 'direccion.inventario.uniformes.index',
                        ],
                        [
                            'titulo' => 'Movimientos de inventario',
                            'ruta' => 'direccion.inventario.uniformes.movimientos.index',
                        ],
                    ],
                ],
            ],
        ],
        [
            'titulo' => 'Comunicación',
            'modulos' => [
                [
                    'id' => 'direccion-comunicados',
                    'titulo' => 'Comunicados',
                    'icono' => 'bi-megaphone',
                    'opciones' => [
                        [
                            'titulo' => 'Ver comunicados',
                            'ruta' => 'direccion.comunicados.index',
                        ],
                        [
                            'titulo' => 'Crear comunicado',
                            'ruta' => 'direccion.comunicados.create',
                        ],
                    ],
                ],
                [
                    'id' => 'direccion-publicaciones',
                    'titulo' => 'Publicaciones del sitio',
                    'icono' => 'bi-globe2',
                    'opciones' => [
                        [
                            'titulo' => 'Ver publicaciones',
                            'ruta' => 'direccion.publicaciones.index',
                        ],
                        [
                            'titulo' => 'Crear publicación',
                            'ruta' => 'direccion.publicaciones.create',
                        ],
                    ],
                ],
            ],
        ],
        [
            'titulo' => 'Información institucional',
            'modulos' => [
                [
                    'id' => 'direccion-reportes',
                    'titulo' => 'Reportes',
                    'icono' => 'bi-bar-chart-line',
                    'opciones' => [
                        [
                            'titulo' => 'Reportes académicos',
                            'ruta' => 'direccion.reportes.academicos.index',
                        ],
                        [
                            'titulo' => 'Reportes administrativos',
                            'ruta' => 'direccion.reportes.administrativos.index',
                        ],
                        [
                            'titulo' => 'Reportes de asistencia',
                            'ruta' => 'direccion.reportes.asistencia.index',
                        ],
                    ],
                ],
            ],
        ],
    ];
@endphp

@section('sidebar-menu')

    <div class="atlas-nav-section">
        <span class="atlas-nav-heading">Principal</span>

        <a
            href="{{ route('direccion.dashboard') }}"
            class="atlas-nav-link {{ request()->routeIs('direccion.dashboard') ? 'active' : '' }}"
            @if(request()->routeIs('direccion.dashboard'))
                aria-current="page"
            @endif
        >
            <i class="bi bi-grid-1x2"></i>
            <span>Dashboard</span>
        </a>
    </div>

    @foreach($menuDireccion as $seccion)

        <div class="atlas-nav-section">
            <span class="atlas-nav-heading">
                {{ $seccion['titulo'] }}
            </span>

            @foreach($seccion['modulos'] as $modulo)

                @php
                    $moduloActivo = false;

                    foreach($modulo['opciones'] as $opcion) {
                        if(
                            request()->routeIs($opcion['ruta']) ||
                            request()->routeIs($opcion['ruta'] . '.*')
                        ) {
                            $moduloActivo = true;
                            break;
                        }
                    }
                @endphp

                <button
                    type="button"
                    class="atlas-nav-button {{ $moduloActivo ? 'active' : '' }}"
                    data-atlas-submenu="{{ $modulo['id'] }}"
                    aria-controls="{{ $modulo['id'] }}"
                    aria-expanded="{{ $moduloActivo ? 'true' : 'false' }}"
                >
                    <i class="bi {{ $modulo['icono'] }}"></i>

                    <span>
                        {{ $modulo['titulo'] }}
                    </span>

                    <i class="bi bi-chevron-down atlas-nav-chevron"></i>
                </button>

                <div
                    class="atlas-nav-submenu"
                    id="{{ $modulo['id'] }}"
                    @if(!$moduloActivo) hidden @endif
                >

                    @foreach($modulo['opciones'] as $opcion)

                        @php
                            $rutaDisponible = \Illuminate\Support\Facades\Route::has(
                                $opcion['ruta']
                            );

                            $opcionActiva =
                                request()->routeIs($opcion['ruta']) ||
                                request()->routeIs($opcion['ruta'] . '.*');
                        @endphp

                        @if($rutaDisponible)

                            <a
                                href="{{ route($opcion['ruta']) }}"
                                class="atlas-nav-sublink {{ $opcionActiva ? 'active' : '' }}"
                                @if($opcionActiva)
                                    aria-current="page"
                                @endif
                            >
                                {{ $opcion['titulo'] }}
                            </a>

                        @else

                            <a
                                class="atlas-nav-sublink"
                                aria-disabled="true"
                                tabindex="-1"
                                title="Módulo pendiente de implementación"
                            >
                                {{ $opcion['titulo'] }}
                            </a>

                        @endif

                    @endforeach

                </div>

            @endforeach

        </div>

    @endforeach

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const submenuButtons = document.querySelectorAll(
        '[data-atlas-submenu]'
    );

    function closeSubmenu(button) {
        const submenu = document.getElementById(
            button.dataset.atlasSubmenu
        );

        button.setAttribute('aria-expanded', 'false');
        button.classList.remove('active');

        if (submenu) {
            submenu.hidden = true;
        }
    }

    function openSubmenu(button) {
        const submenu = document.getElementById(
            button.dataset.atlasSubmenu
        );

        if (!submenu) {
            return;
        }

        button.setAttribute('aria-expanded', 'true');
        button.classList.add('active');

        submenu.hidden = false;
    }

    submenuButtons.forEach(function (button) {
        const submenu = document.getElementById(
            button.dataset.atlasSubmenu
        );

        if (!submenu) {
            return;
        }

        const activeLink = submenu.querySelector(
            '.atlas-nav-sublink.active, .atlas-nav-sublink[aria-current="page"]'
        );

        if (activeLink) {
            openSubmenu(button);
        }

        button.addEventListener('click', function () {
            const isExpanded = button.getAttribute(
                'aria-expanded'
            ) === 'true';

            submenuButtons.forEach(function (otherButton) {
                if (otherButton !== button) {
                    closeSubmenu(otherButton);
                }
            });

            if (isExpanded) {
                closeSubmenu(button);
            } else {
                openSubmenu(button);
            }
        });
    });
});
</script>
@endpush
