
@extends('layouts.institucional')

@section('title', 'Oferta Académica')

@section('description', 'Conoce la propuesta educativa del Colegio Mixto El Jardín de San Raymundo, sus niveles académicos, formación integral y proceso de admisión.')

@push('styles')
    @vite('resources/css/oferta-academica.css')
@endpush

@section('content')

@php
    $niveles = [
        [
            'numero' => '01',
            'icono' => 'bi-backpack2-fill',
            'titulo' => 'Nivel Preprimario',
            'subtitulo' => 'Primeros pasos en el aprendizaje',
            'descripcion' => 'Una etapa orientada al descubrimiento, la convivencia y el desarrollo de habilidades fundamentales mediante experiencias educativas adecuadas a la edad.',
            'caracteristicas' => [
                'Desarrollo de habilidades iniciales',
                'Estimulación de la creatividad',
                'Aprendizaje mediante actividades',
                'Convivencia y formación en valores'
            ],
            'etiqueta' => 'Información de demostración'
        ],
        [
            'numero' => '02',
            'icono' => 'bi-book-half',
            'titulo' => 'Nivel Primario',
            'subtitulo' => 'Construyendo conocimientos',
            'descripcion' => 'Una formación enfocada en fortalecer los conocimientos fundamentales, las habilidades de comunicación, el razonamiento y la autonomía de los estudiantes.',
            'caracteristicas' => [
                'Comunicación y lenguaje',
                'Pensamiento lógico y matemático',
                'Ciencias y conocimiento del entorno',
                'Desarrollo personal y social'
            ],
            'etiqueta' => 'Información de demostración'
        ],
        [
            'numero' => '03',
            'icono' => 'bi-mortarboard-fill',
            'titulo' => 'Ciclo Básico',
            'subtitulo' => 'Preparación para nuevos desafíos',
            'descripcion' => 'Una etapa de consolidación académica que busca desarrollar el pensamiento crítico, la responsabilidad y las competencias necesarias para continuar la formación.',
            'caracteristicas' => [
                'Fortalecimiento académico',
                'Pensamiento crítico',
                'Trabajo colaborativo',
                'Orientación y desarrollo personal'
            ],
            'etiqueta' => 'Información de demostración'
        ],
        [
            'numero' => '04',
            'icono' => 'bi-award-fill',
            'titulo' => 'Nivel Diversificado',
            'subtitulo' => 'Proyectando el futuro',
            'descripcion' => 'Una propuesta de formación orientada a preparar a los estudiantes para continuar estudios superiores y desarrollar competencias aplicables a su futuro.',
            'caracteristicas' => [
                'Preparación académica',
                'Desarrollo de competencias',
                'Orientación vocacional',
                'Proyección profesional'
            ],
            'etiqueta' => 'Información de demostración'
        ]
    ];

    $competencias = [
        [
            'icono' => 'bi-lightbulb-fill',
            'titulo' => 'Pensamiento crítico',
            'descripcion' => 'Analizar situaciones, formular preguntas y desarrollar soluciones.'
        ],
        [
            'icono' => 'bi-chat-dots-fill',
            'titulo' => 'Comunicación',
            'descripcion' => 'Expresar ideas con claridad y escuchar respetuosamente.'
        ],
        [
            'icono' => 'bi-people-fill',
            'titulo' => 'Trabajo en equipo',
            'descripcion' => 'Colaborar con otras personas para alcanzar objetivos comunes.'
        ],
        [
            'icono' => 'bi-heart-fill',
            'titulo' => 'Formación en valores',
            'descripcion' => 'Fortalecer el respeto, la honestidad y la responsabilidad.'
        ],
        [
            'icono' => 'bi-pc-display-horizontal',
            'titulo' => 'Competencias digitales',
            'descripcion' => 'Promover el uso responsable de herramientas tecnológicas.'
        ],
        [
            'icono' => 'bi-stars',
            'titulo' => 'Creatividad',
            'descripcion' => 'Explorar ideas y desarrollar nuevas formas de aprender.'
        ]
    ];

    $metodologia = [
        [
            'numero' => '01',
            'titulo' => 'Explorar',
            'descripcion' => 'Despertar la curiosidad y relacionar los nuevos aprendizajes con experiencias previas.'
        ],
        [
            'numero' => '02',
            'titulo' => 'Aprender',
            'descripcion' => 'Desarrollar conocimientos y habilidades mediante actividades educativas.'
        ],
        [
            'numero' => '03',
            'titulo' => 'Aplicar',
            'descripcion' => 'Poner en práctica lo aprendido mediante ejercicios, proyectos y experiencias.'
        ],
        [
            'numero' => '04',
            'titulo' => 'Crecer',
            'descripcion' => 'Reconocer los avances y fortalecer las oportunidades de mejora.'
        ]
    ];

    $admisiones = [
        [
            'numero' => '01',
            'icono' => 'bi-chat-left-text',
            'titulo' => 'Solicitar información',
            'descripcion' => 'Comunicarse con la institución para conocer las opciones educativas disponibles.'
        ],
        [
            'numero' => '02',
            'icono' => 'bi-journal-text',
            'titulo' => 'Conocer los requisitos',
            'descripcion' => 'Consultar la documentación y las condiciones de inscripción correspondientes.'
        ],
        [
            'numero' => '03',
            'icono' => 'bi-file-earmark-check',
            'titulo' => 'Realizar la inscripción',
            'descripcion' => 'Completar el proceso administrativo indicado por la institución.'
        ],
        [
            'numero' => '04',
            'icono' => 'bi-mortarboard',
            'titulo' => 'Iniciar la experiencia',
            'descripcion' => 'Integrarse a las actividades académicas del ciclo escolar.'
        ]
    ];
@endphp

{{-- ENCABEZADO --}}

<section class="oferta-hero">
    <div class="container">

        <div class="oferta-hero-content">

            <span class="oferta-eyebrow">
                FORMACIÓN Y OPORTUNIDADES
            </span>

            <h1>Nuestra Oferta Académica</h1>

            <p>
                Descubre una propuesta educativa orientada
                al aprendizaje, el desarrollo de habilidades
                y la formación integral de los estudiantes.
            </p>

            <nav aria-label="Ruta de navegación">
                <ol class="oferta-breadcrumb">
                    <li>
                        <a href="{{ route('institucional.inicio') }}">
                            Inicio
                        </a>
                    </li>
                    <li aria-current="page">
                        Oferta Académica
                    </li>
                </ol>
            </nav>

        </div>

    </div>
</section>

{{-- PRESENTACIÓN --}}

<section class="oferta-introduccion section-padding">
    <div class="container">

        <div class="row align-items-center g-5">

            <div class="col-lg-6">

                <span class="section-eyebrow">
                    NUESTRA PROPUESTA EDUCATIVA
                </span>

                <h2 class="section-heading">
                    Aprender hoy para construir
                    las oportunidades del mañana
                </h2>

                <p class="section-description">
                    La educación es un proceso de crecimiento
                    que permite descubrir capacidades,
                    adquirir conocimientos y desarrollar
                    habilidades para la vida.
                </p>

                <p class="section-description">
                    En el Colegio Mixto El Jardín buscamos
                    presentar una experiencia educativa
                    que integre la formación académica,
                    los valores y el desarrollo personal.
                </p>

                <div class="oferta-intro-highlight">

                    <i class="bi bi-mortarboard-fill"></i>

                    <div>
                        <strong>Formación integral</strong>
                        <span>
                            Conocimientos, habilidades y valores
                            para cada etapa del aprendizaje.
                        </span>
                    </div>

                </div>

            </div>

            <div class="col-lg-6">

                <div class="oferta-intro-visual">

                    <div class="oferta-photo-placeholder">

                        <i class="bi bi-building"></i>

                        <strong>
                            Nuestra comunidad educativa
                        </strong>

                        <span>
                            Espacio reservado para fotografía
                            institucional autorizada.
                        </span>

                    </div>

                    <div class="oferta-intro-badge">
                        <i class="bi bi-book-fill"></i>
                        <span>Educación para el futuro</span>
                    </div>

                </div>

            </div>

        </div>

    </div>
</section>

{{-- NIVELES EDUCATIVOS --}}

<section class="oferta-niveles section-padding" id="niveles-educativos">
    <div class="container">

        <div class="section-center">

            <span class="section-eyebrow">
                OPCIONES DE FORMACIÓN
            </span>

            <h2 class="section-heading">
                Niveles educativos
            </h2>

            <p class="section-description">
                Conoce las etapas educativas contempladas
                en esta propuesta de diseño.
                La oferta oficial será confirmada por la institución.
            </p>

        </div>

        <div class="row g-4">

            @foreach($niveles as $nivel)

                <div class="col-lg-6">

                    <article class="oferta-nivel-card">

                        <div class="oferta-nivel-header">

                            <div class="oferta-nivel-icon">
                                <i class="bi {{ $nivel['icono'] }}"></i>
                            </div>

                            <span class="oferta-nivel-number">
                                {{ $nivel['numero'] }}
                            </span>

                        </div>

                        <div class="oferta-nivel-content">

                            <span class="oferta-nivel-subtitle">
                                {{ $nivel['subtitulo'] }}
                            </span>

                            <h3>{{ $nivel['titulo'] }}</h3>

                            <p>{{ $nivel['descripcion'] }}</p>

                            <div class="oferta-nivel-divider"></div>

                            <h4>Áreas de desarrollo</h4>

                            <ul class="oferta-nivel-list">

                                @foreach($nivel['caracteristicas'] as $caracteristica)

                                    <li>
                                        <i class="bi bi-check-circle-fill"></i>
                                        <span>{{ $caracteristica }}</span>
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                        <div class="oferta-nivel-footer">

                            <span>
                                <i class="bi bi-info-circle"></i>
                                {{ $nivel['etiqueta'] }}
                            </span>

                            <a href="{{ route('institucional.contacto') }}">
                                Más información
                                <i class="bi bi-arrow-right"></i>
                            </a>

                        </div>

                    </article>

                </div>

            @endforeach

        </div>

        <p class="oferta-disclaimer">
            <i class="bi bi-exclamation-circle"></i>
            Los niveles educativos mostrados son únicamente
            demostrativos y no constituyen una confirmación
            de la oferta académica autorizada del colegio.
        </p>

    </div>
</section>

{{-- FORMACIÓN INTEGRAL --}}

<section class="oferta-formacion section-padding">
    <div class="container">

        <div class="section-center">

            <span class="section-eyebrow">
                MÁS ALLÁ DEL AULA
            </span>

            <h2 class="section-heading">
                Formación integral
            </h2>

            <p class="section-description">
                Una educación completa busca fortalecer
                tanto los conocimientos académicos
                como las habilidades personales y sociales.
            </p>

        </div>

        <div class="row g-4">

            @foreach($competencias as $competencia)

                <div class="col-lg-4 col-md-6">

                    <article class="oferta-competencia-card">

                        <div class="oferta-competencia-icon">
                            <i class="bi {{ $competencia['icono'] }}"></i>
                        </div>

                        <h3>{{ $competencia['titulo'] }}</h3>

                        <p>{{ $competencia['descripcion'] }}</p>

                    </article>

                </div>

            @endforeach

        </div>

    </div>
</section>

{{-- METODOLOGÍA --}}

<section class="oferta-metodologia section-padding">
    <div class="container">

        <div class="row align-items-center g-5">

            <div class="col-lg-5">

                <span class="section-eyebrow">
                    EXPERIENCIA DE APRENDIZAJE
                </span>

                <h2 class="section-heading">
                    Una metodología centrada
                    en el desarrollo del estudiante
                </h2>

                <p class="section-description">
                    Como propuesta de diseño, presentamos
                    cuatro momentos que pueden representar
                    el proceso de enseñanza y aprendizaje.
                </p>

                <p class="section-description">
                    La metodología definitiva se adaptará
                    al modelo pedagógico y a las prácticas
                    oficiales del Colegio Mixto El Jardín.
                </p>

            </div>

            <div class="col-lg-7">

                <div class="oferta-metodologia-list">

                    @foreach($metodologia as $paso)

                        <article class="oferta-metodologia-item">

                            <span class="oferta-metodologia-number">
                                {{ $paso['numero'] }}
                            </span>

                            <div>
                                <h3>{{ $paso['titulo'] }}</h3>
                                <p>{{ $paso['descripcion'] }}</p>
                            </div>

                        </article>

                    @endforeach

                </div>

            </div>

        </div>

    </div>
</section>

{{-- EXPERIENCIA ACADÉMICA --}}

<section class="oferta-experiencia section-padding">
    <div class="container">

        <div class="section-center">

            <span class="section-eyebrow">
                APRENDIZAJE EN IMÁGENES
            </span>

            <h2 class="section-heading">
                Nuestra experiencia académica
            </h2>

            <p class="section-description">
                Espacios destinados a mostrar las instalaciones,
                actividades y experiencias educativas del colegio.
            </p>

        </div>

        <div class="row g-4">

            <div class="col-lg-7">

                <div class="oferta-gallery-item oferta-gallery-featured">

                    <i class="bi bi-camera-fill"></i>

                    <strong>Actividades académicas</strong>

                    <span>
                        Fotografía oficial pendiente
                    </span>

                </div>

            </div>

            <div class="col-lg-5">

                <div class="oferta-gallery-item">

                    <i class="bi bi-easel-fill"></i>

                    <strong>Espacios de aprendizaje</strong>

                    <span>
                        Fotografía oficial pendiente
                    </span>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="oferta-gallery-item">

                    <i class="bi bi-journal-bookmark-fill"></i>

                    <strong>Proyectos educativos</strong>

                    <span>
                        Fotografía oficial pendiente
                    </span>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="oferta-gallery-item">

                    <i class="bi bi-people-fill"></i>

                    <strong>Trabajo colaborativo</strong>

                    <span>
                        Fotografía oficial pendiente
                    </span>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="oferta-gallery-item">

                    <i class="bi bi-award-fill"></i>

                    <strong>Logros académicos</strong>

                    <span>
                        Fotografía oficial pendiente
                    </span>

                </div>

            </div>

        </div>

    </div>
</section>

{{-- ADMISIONES --}}

<section class="oferta-admisiones section-padding">
    <div class="container">

        <div class="section-center">

            <span class="section-eyebrow">
                INFORMACIÓN PARA FAMILIAS
            </span>

            <h2 class="section-heading">
                Proceso de admisión e inscripción
            </h2>

            <p class="section-description">
                Conoce una guía orientativa del proceso
                de ingreso. Los requisitos y procedimientos
                oficiales serán incorporados posteriormente.
            </p>

        </div>

        <div class="row g-4">

            @foreach($admisiones as $admision)

                <div class="col-lg-3 col-md-6">

                    <article class="oferta-admision-card">

                        <span class="oferta-admision-number">
                            {{ $admision['numero'] }}
                        </span>

                        <div class="oferta-admision-icon">
                            <i class="bi {{ $admision['icono'] }}"></i>
                        </div>

                        <h3>{{ $admision['titulo'] }}</h3>

                        <p>{{ $admision['descripcion'] }}</p>

                    </article>

                </div>

            @endforeach

        </div>

        <div class="oferta-admisiones-note">

            <i class="bi bi-info-circle-fill"></i>

            <p>
                La información presentada es demostrativa.
                Las fechas, cuotas, requisitos y condiciones
                de inscripción deberán confirmarse directamente
                con la administración del colegio.
            </p>

        </div>

    </div>
</section>

{{-- LLAMADO A LA ACCIÓN --}}

<section class="oferta-cierre">
    <div class="container">

        <div class="oferta-cierre-content">

            <span>
                TU FUTURO COMIENZA CON LA EDUCACIÓN
            </span>

            <h2>
                ¿Deseas conocer más sobre
                nuestra propuesta educativa?
            </h2>

            <p>
                Comunícate con la institución para
                obtener información sobre sus
                programas académicos y el proceso
                de inscripción.
            </p>

            <a href="{{ route('institucional.contacto') }}"
               class="btn-institutional">

                Solicitar información
                <i class="bi bi-arrow-right"></i>

            </a>

        </div>

    </div>
</section>

@endsection
