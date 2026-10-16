
@extends('layouts.institucional')

@section('title', 'Vida Estudiantil')

@section('description', 'Descubre las actividades, recursos y experiencias que forman parte de la vida estudiantil del Colegio Mixto El Jardín.')

@push('styles')
    @vite('resources/css/vida-estudiantil.css')
@endpush

@php
    /*
    |--------------------------------------------------------------------------
    | VIDA ESTUDIANTIL - CONTENIDO DEMOSTRATIVO
    |--------------------------------------------------------------------------
    | Esta página presenta experiencias, actividades y recursos.
    |
    | No incluye galerías generales de instalaciones, porque estas
    | ya se encuentran en la página Nosotros.
    |
    | Todas las actividades y recursos deberán confirmarse con
    | Dirección antes de publicar el contenido definitivo.
    |
    | Las fotografías deben contar con autorización de difusión.
    |--------------------------------------------------------------------------
    */

    $imagenPrincipal = null;

    $momentos = [
        [
            'numero' => '01',
            'titulo' => 'Comenzamos nuestra jornada',
            'descripcion' => 'Un vistazo a los primeros momentos del día y al inicio de las actividades escolares.',
            'icono' => 'bi-sunrise',
            'imagen' => null,
        ],
        [
            'numero' => '02',
            'titulo' => 'Compartimos en clase',
            'descripcion' => 'Momentos de participación y desarrollo de actividades dentro del salón.',
            'icono' => 'bi-pencil-square',
            'imagen' => null,
        ],
        [
            'numero' => '03',
            'titulo' => 'Disfrutamos el recreo',
            'descripcion' => 'Espacios de descanso, recreación y convivencia durante la jornada.',
            'icono' => 'bi-emoji-smile',
            'imagen' => null,
        ],
        [
            'numero' => '04',
            'titulo' => 'Participamos en actividades',
            'descripcion' => 'Experiencias complementarias que forman parte de la programación escolar.',
            'icono' => 'bi-stars',
            'imagen' => null,
        ],
    ];

    $recursos = [
        [
            'titulo' => 'Herramientas tecnológicas',
            'descripcion' => 'Espacio para presentar los dispositivos y equipos que los estudiantes utilizan durante sus actividades.',
            'icono' => 'bi-pc-display',
            'imagen' => null,
        ],
        [
            'titulo' => 'Materiales de trabajo',
            'descripcion' => 'Materiales, herramientas y recursos utilizados para desarrollar actividades escolares.',
            'icono' => 'bi-journal-bookmark',
            'imagen' => null,
        ],
        [
            'titulo' => 'Recursos para actividades prácticas',
            'descripcion' => 'Equipamiento y materiales disponibles para experiencias de aprendizaje práctico.',
            'icono' => 'bi-tools',
            'imagen' => null,
        ],
    ];

    $experiencias = [
        [
            'titulo' => 'Deporte y recreación',
            'descripcion' => 'Actividades que brindan oportunidades para compartir, participar y disfrutar de experiencias recreativas.',
            'icono' => 'bi-trophy',
            'imagen' => null,
            'etiqueta' => 'RECREACIÓN',
        ],
        [
            'titulo' => 'Arte y cultura',
            'descripcion' => 'Momentos dedicados a la expresión artística y a las actividades culturales de la comunidad educativa.',
            'icono' => 'bi-palette',
            'imagen' => null,
            'etiqueta' => 'CULTURA',
        ],
        [
            'titulo' => 'Convivencias',
            'descripcion' => 'Experiencias compartidas que forman parte de las actividades de integración estudiantil.',
            'icono' => 'bi-people',
            'imagen' => null,
            'etiqueta' => 'CONVIVENCIA',
        ],
        [
            'titulo' => 'Proyectos y presentaciones',
            'descripcion' => 'Actividades en las que los estudiantes pueden presentar trabajos y compartir sus experiencias.',
            'icono' => 'bi-easel',
            'imagen' => null,
            'etiqueta' => 'PARTICIPACIÓN',
        ],
    ];

    $momentosEspeciales = [
        [
            'titulo' => 'Compartiendo experiencias',
            'icono' => 'bi-camera',
            'imagen' => null,
            'clase' => 'principal',
        ],
        [
            'titulo' => 'Participación estudiantil',
            'icono' => 'bi-people',
            'imagen' => null,
            'clase' => '',
        ],
        [
            'titulo' => 'Momentos de recreación',
            'icono' => 'bi-emoji-smile',
            'imagen' => null,
            'clase' => '',
        ],
    ];
@endphp

@section('content')

{{-- ==========================================================
     1. ENCABEZADO
========================================================== --}}

<section class="vida-hero">

    <div class="container">

        <div class="vida-hero-content">

            <span class="vida-eyebrow">
                EXPERIENCIAS QUE COMPARTIMOS
            </span>

            <h1>
                Vida Estudiantil
            </h1>

            <p>
                Descubre cómo transcurre nuestra jornada,
                las actividades que compartimos
                y los momentos que forman parte
                de la experiencia escolar.
            </p>

            <nav aria-label="Ruta de navegación">

                <ol class="vida-breadcrumb">

                    <li>
                        <a href="{{ route('institucional.inicio') }}">
                            Inicio
                        </a>
                    </li>

                    <li aria-current="page">
                        Vida Estudiantil
                    </li>

                </ol>

            </nav>

        </div>

    </div>

</section>

{{-- ==========================================================
     2. PRESENTACIÓN DE LA VIDA ESTUDIANTIL
========================================================== --}}

<section class="vida-presentacion section-padding">

    <div class="container">

        <div class="row align-items-center g-5">

            <div class="col-lg-5">

                <div class="vida-presentacion-content">

                    <span class="section-eyebrow">
                        NUESTRO DÍA A DÍA
                    </span>

                    <h2 class="section-heading">
                        Cada día está lleno
                        de experiencias
                    </h2>

                    <p>
                        La vida escolar está formada por
                        pequeños y grandes momentos:
                        las actividades en clase,
                        los espacios de recreación,
                        la participación en proyectos
                        y las experiencias compartidas
                        con los compañeros.
                    </p>

                    <p>
                        En esta sección podrás conocer
                        esas experiencias a través de
                        fotografías y descripciones
                        de las actividades que se desarrollan
                        en nuestra institución.
                    </p>

                    <a
                        href="#jornada"
                        class="vida-explorar-btn">

                        Descubre nuestro día a día

                        <i class="bi bi-arrow-down-right"></i>

                    </a>

                </div>

            </div>

            <div class="col-lg-7">

                <div class="vida-presentacion-visual">

                    @if($imagenPrincipal)

                        <img
                            class="vida-presentacion-img"
                            src="{{ asset($imagenPrincipal) }}"
                            alt="Actividad de la vida estudiantil"
                            loading="lazy">

                    @else

                        <div class="vida-photo-placeholder vida-photo-main">

                            <i class="bi bi-camera"></i>

                            <strong>
                                Nuestra vida estudiantil
                            </strong>

                            <span>
                                Espacio para una fotografía real
                                de una actividad estudiantil.
                            </span>

                        </div>

                    @endif

                    <div class="vida-visual-label">

                        <i class="bi bi-heart"></i>

                        <div>

                            <strong>
                                Momentos que compartimos
                            </strong>

                            <span>
                                Colegio Mixto El Jardín
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

{{-- ==========================================================
     3. UN DÍA EN EL COLEGIO
========================================================== --}}

<section
    class="vida-jornada section-padding"
    id="jornada">

    <div class="container">

        <div class="section-center">

            <span class="section-eyebrow">
                ASÍ TRANSCURRE NUESTRA JORNADA
            </span>

            <h2 class="section-heading">
                Un día en nuestro colegio
            </h2>

            <p class="section-description">
                Diferentes momentos que pueden formar
                parte de una jornada escolar.
            </p>

        </div>

        <div class="vida-jornada-grid">

            @foreach($momentos as $momento)

                <article class="vida-jornada-card">

                    <div class="vida-jornada-imagen">

                        @if($momento['imagen'])

                            <img
                                src="{{ asset($momento['imagen']) }}"
                                alt="{{ $momento['titulo'] }}"
                                loading="lazy">

                        @else

                            <div class="vida-photo-placeholder">

                                <i class="bi {{ $momento['icono'] }}"></i>

                                <span>
                                    Fotografía de la actividad
                                    pendiente
                                </span>

                            </div>

                        @endif

                        <span class="vida-jornada-numero">
                            {{ $momento['numero'] }}
                        </span>

                    </div>

                    <div class="vida-jornada-detalle">

                        <h3>
                            {{ $momento['titulo'] }}
                        </h3>

                        <p>
                            {{ $momento['descripcion'] }}
                        </p>

                    </div>

                </article>

            @endforeach

        </div>

        <p class="vida-demo-note">

            <i class="bi bi-info-circle"></i>

            La secuencia es demostrativa y será
            adaptada a la jornada real del colegio.

        </p>

    </div>

</section>

{{-- ==========================================================
     4. RECURSOS UTILIZADOS POR LOS ESTUDIANTES
========================================================== --}}

<section class="vida-recursos section-padding">

    <div class="container">

        <div class="vida-section-header">

            <div>

                <span class="section-eyebrow">
                    HERRAMIENTAS Y RECURSOS
                </span>

                <h2 class="section-heading">
                    Recursos para nuestras actividades
                </h2>

                <p class="section-description">
                    Conoce los materiales y herramientas
                    que acompañan las actividades
                    de los estudiantes.
                </p>

            </div>

        </div>

        <div class="row g-4">

            @foreach($recursos as $recurso)

                <div class="col-md-4">

                    <article class="vida-recurso-card h-100">

                        <div class="vida-recurso-imagen">

                            @if($recurso['imagen'])

                                <img
                                    src="{{ asset($recurso['imagen']) }}"
                                    alt="{{ $recurso['titulo'] }}"
                                    loading="lazy">

                            @else

                                <div class="vida-photo-placeholder">

                                    <i class="bi {{ $recurso['icono'] }}"></i>

                                    <span>
                                        Fotografía pendiente
                                    </span>

                                </div>

                            @endif

                        </div>

                        <div class="vida-recurso-content">

                            <div class="vida-recurso-icon">

                                <i class="bi {{ $recurso['icono'] }}"></i>

                            </div>

                            <h3>
                                {{ $recurso['titulo'] }}
                            </h3>

                            <p>
                                {{ $recurso['descripcion'] }}
                            </p>

                        </div>

                    </article>

                </div>

            @endforeach

        </div>

        <p class="vida-demo-note">

            <i class="bi bi-info-circle"></i>

            Los recursos son categorías demostrativas.
            Se mostrarán únicamente aquellos que
            Dirección confirme como disponibles.

        </p>

    </div>

</section>

{{-- ==========================================================
     5. ACTIVIDADES Y EXPERIENCIAS
========================================================== --}}

<section class="vida-actividades section-padding">

    <div class="container">

        <div class="section-center">

            <span class="section-eyebrow">
                MÁS ALLÁ DE LAS CLASES
            </span>

            <h2 class="section-heading">
                Actividades y experiencias
            </h2>

            <p class="section-description">
                Momentos de participación, recreación
                y convivencia que enriquecen
                la experiencia escolar.
            </p>

        </div>

        <div class="row g-4">

            @foreach($experiencias as $experiencia)

                <div class="col-md-6 col-xl-3">

                    <article class="vida-actividad-card h-100">

                        <div class="vida-actividad-image">

                            @if($experiencia['imagen'])

                                <img
                                    src="{{ asset($experiencia['imagen']) }}"
                                    alt="{{ $experiencia['titulo'] }}"
                                    loading="lazy">

                            @else

                                <div class="vida-photo-placeholder">

                                    <i class="bi {{ $experiencia['icono'] }}"></i>

                                    <span>
                                        Fotografía pendiente
                                    </span>

                                </div>

                            @endif

                        </div>

                        <div class="vida-actividad-content">

                            <span class="vida-actividad-etiqueta">
                                {{ $experiencia['etiqueta'] }}
                            </span>

                            <h3>
                                {{ $experiencia['titulo'] }}
                            </h3>

                            <p>
                                {{ $experiencia['descripcion'] }}
                            </p>

                        </div>

                    </article>

                </div>

            @endforeach

        </div>

        <p class="vida-demo-note">

            <i class="bi bi-info-circle"></i>

            Las actividades son ejemplos de contenido
            y deberán confirmarse con Dirección.

        </p>

    </div>

</section>

{{-- ==========================================================
     6. MOMENTOS QUE NOS REPRESENTAN
========================================================== --}}

<section class="vida-momentos section-padding">

    <div class="container">

        <div class="section-center">

            <span class="section-eyebrow">
                EXPERIENCIAS COMPARTIDAS
            </span>

            <h2 class="section-heading">
                Momentos que nos representan
            </h2>

            <p class="section-description">
                Fotografías de actividades y experiencias
                que reflejan la vida cotidiana
                de nuestra comunidad estudiantil.
            </p>

        </div>

        <div class="vida-momentos-grid">

            @foreach($momentosEspeciales as $momento)

                <div class="vida-momento-item {{ $momento['clase'] }}">

                    @if($momento['imagen'])

                        <img
                            src="{{ asset($momento['imagen']) }}"
                            alt="{{ $momento['titulo'] }}"
                            loading="lazy">

                    @else

                        <div class="vida-photo-placeholder">

                            <i class="bi {{ $momento['icono'] }}"></i>

                            <span>
                                Fotografía de experiencia
                                estudiantil pendiente
                            </span>

                        </div>

                    @endif

                    <div class="vida-momento-caption">

                        <h3>
                            {{ $momento['titulo'] }}
                        </h3>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</section>

{{-- ==========================================================
     7. CIERRE
========================================================== --}}

<section class="vida-cierre">

    <div class="container">

        <div class="vida-cierre-content">

            <span>
                COLEGIO MIXTO EL JARDÍN
            </span>

            <h2>
                Descubre más sobre nuestra
                vida estudiantil
            </h2>

            <p>
                Si deseas obtener información adicional
                sobre las actividades y la experiencia
                educativa del colegio,
                puedes comunicarte con nosotros.
            </p>

            <a
                href="{{ route('institucional.contacto') }}"
                class="btn-institutional">

                Contáctanos

                <i class="bi bi-arrow-right"></i>

            </a>

        </div>

    </div>

</section>

@endsection
