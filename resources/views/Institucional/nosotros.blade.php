
@extends('layouts.institucional')

@section('title', 'Nosotros')

@section('description', 'Conoce al Colegio Mixto El Jardín de San Raymundo, nuestra identidad institucional, misión, visión, valores, compromisos y comunidad educativa.')

@push('styles')
    @vite('resources/css/nosotros.css')
@endpush

@section('content')

<section class="nosotros-hero">
    <div class="container">
        <div class="nosotros-hero-content">

            <span class="nosotros-eyebrow">
                NUESTRA INSTITUCIÓN
            </span>

            <h1>Conoce al Colegio Mixto El Jardín</h1>

            <p>
                Un espacio dedicado a la educación, el desarrollo
                integral y la construcción de oportunidades para
                nuestra comunidad estudiantil.
            </p>

            <nav aria-label="Ruta de navegación">
                <ol class="nosotros-breadcrumb">
                    <li>
                        <a href="{{ route('institucional.inicio') }}">
                            Inicio
                        </a>
                    </li>
                    <li aria-current="page">Nosotros</li>
                </ol>
            </nav>

        </div>
    </div>
</section>

<section class="nosotros-presentacion section-padding">
    <div class="container">

        <div class="row align-items-center g-5">

            <div class="col-lg-6">

                <span class="section-eyebrow">
                    QUIÉNES SOMOS
                </span>

                <h2 class="section-heading">
                    Una comunidad educativa comprometida
                    con el futuro
                </h2>

                <p class="section-description">
                    El Colegio Mixto El Jardín es una institución
                    educativa ubicada en San Raymundo, Guatemala,
                    que forma parte de una comunidad comprometida
                    con el aprendizaje y el desarrollo de sus estudiantes.
                </p>

                <p class="section-description">
                    Nuestro espacio institucional busca promover
                    la formación académica, la convivencia respetuosa
                    y la participación de las familias en el
                    proceso educativo.
                </p>

                <div class="nosotros-presentacion-destacado">
                    <i class="bi bi-mortarboard-fill"></i>

                    <div>
                        <strong>Educación y formación integral</strong>
                        <span>
                            Aprendizaje, convivencia y desarrollo
                            de nuestra comunidad educativa.
                        </span>
                    </div>
                </div>

            </div>

            <div class="col-lg-6">

                <div class="nosotros-presentacion-visual">

                    <div class="nosotros-image-placeholder">
                        <i class="bi bi-building"></i>
                        <span>Fotografía principal de la institución</span>
                        <small>
                            Espacio reservado para una imagen oficial
                            del Colegio Mixto El Jardín.
                        </small>
                    </div>

                    <div class="nosotros-visual-badge">
                        <i class="bi bi-book-half"></i>
                        <span>Identidad institucional</span>
                    </div>

                </div>

            </div>

        </div>

    </div>
</section>

<section class="nosotros-mision-vision section-padding">
    <div class="container">

        <div class="section-center">

            <span class="section-eyebrow">
                NUESTRA IDENTIDAD
            </span>

            <h2 class="section-heading">
                Misión y visión
            </h2>

            <p class="section-description">
                Los principios que orientan nuestro trabajo
                educativo y nuestras aspiraciones institucionales.
            </p>

        </div>

        <div class="row g-4">

            <div class="col-lg-6">

                <article class="nosotros-identity-card nosotros-mision-card">

                    <div class="nosotros-identity-icon">
                        <i class="bi bi-bullseye"></i>
                    </div>

                    <h3>Nuestra misión</h3>

                    <p>
                        Brindar una educación integral que contribuya
                        al desarrollo académico, personal y social
                        de los estudiantes, promoviendo valores,
                        conocimientos y habilidades que les permitan
                        participar responsablemente en su comunidad.
                    </p>

                    <span class="nosotros-pending-label">
                        Texto propuesto, pendiente de aprobación institucional
                    </span>

                </article>

            </div>

            <div class="col-lg-6">

                <article class="nosotros-identity-card nosotros-vision-card">

                    <div class="nosotros-identity-icon">
                        <i class="bi bi-eye"></i>
                    </div>

                    <h3>Nuestra visión</h3>

                    <p>
                        Ser una institución educativa reconocida
                        por su compromiso con la formación integral,
                        la mejora continua y el desarrollo de
                        estudiantes preparados para afrontar
                        los desafíos de su entorno.
                    </p>

                    <span class="nosotros-pending-label">
                        Texto propuesto, pendiente de aprobación institucional
                    </span>

                </article>

            </div>

        </div>

    </div>
</section>

<section class="nosotros-valores section-padding">
    <div class="container">

        <div class="section-center">

            <span class="section-eyebrow">
                PRINCIPIOS QUE NOS INSPIRAN
            </span>

            <h2 class="section-heading">
                Valores institucionales
            </h2>

            <p class="section-description">
                Valores propuestos para representar la convivencia,
                el aprendizaje y la formación de nuestra comunidad.
            </p>

        </div>

        @php
            $valores = [
                [
                    'icono' => 'bi-heart-fill',
                    'titulo' => 'Respeto',
                    'descripcion' => 'Promover relaciones basadas en la consideración, la dignidad y el reconocimiento de los demás.'
                ],
                [
                    'icono' => 'bi-shield-check',
                    'titulo' => 'Responsabilidad',
                    'descripcion' => 'Fomentar el cumplimiento de compromisos y la toma consciente de decisiones.'
                ],
                [
                    'icono' => 'bi-people-fill',
                    'titulo' => 'Solidaridad',
                    'descripcion' => 'Fortalecer la colaboración, la empatía y el apoyo entre los integrantes de la comunidad.'
                ],
                [
                    'icono' => 'bi-award-fill',
                    'titulo' => 'Excelencia',
                    'descripcion' => 'Motivar el esfuerzo, la superación personal y la mejora continua.'
                ],
                [
                    'icono' => 'bi-chat-heart-fill',
                    'titulo' => 'Honestidad',
                    'descripcion' => 'Promover la sinceridad, la integridad y la transparencia en las acciones.'
                ],
                [
                    'icono' => 'bi-lightbulb-fill',
                    'titulo' => 'Creatividad',
                    'descripcion' => 'Estimular nuevas ideas, la curiosidad y la búsqueda de soluciones.'
                ]
            ];
        @endphp

        <div class="row g-4">

            @foreach($valores as $valor)

                <div class="col-lg-4 col-md-6">

                    <article class="nosotros-valor-card">

                        <div class="nosotros-valor-icon">
                            <i class="bi {{ $valor['icono'] }}"></i>
                        </div>

                        <h3>{{ $valor['titulo'] }}</h3>

                        <p>{{ $valor['descripcion'] }}</p>

                    </article>

                </div>

            @endforeach

        </div>

    </div>
</section>

<section class="nosotros-compromisos section-padding">
    <div class="container">

        <div class="row align-items-center g-5">

            <div class="col-lg-5">

                <span class="section-eyebrow">
                    NUESTRO COMPROMISO
                </span>

                <h2 class="section-heading">
                    Comprometidos con una educación
                    que transforma
                </h2>

                <p class="section-description">
                    La formación de los estudiantes requiere
                    del trabajo conjunto entre docentes,
                    familias y comunidad educativa.
                </p>

                <p class="section-description">
                    Estos compromisos representan los aspectos
                    que buscamos fortalecer mediante
                    nuestro proyecto educativo.
                </p>

            </div>

            <div class="col-lg-7">

                <div class="nosotros-compromisos-lista">

                    <article class="nosotros-compromiso-item">
                        <span class="nosotros-compromiso-number">01</span>

                        <div>
                            <h3>Formación académica</h3>
                            <p>
                                Favorecer experiencias de aprendizaje
                                que desarrollen conocimientos y habilidades.
                            </p>
                        </div>
                    </article>

                    <article class="nosotros-compromiso-item">
                        <span class="nosotros-compromiso-number">02</span>

                        <div>
                            <h3>Desarrollo integral</h3>
                            <p>
                                Impulsar el crecimiento intelectual,
                                emocional, social y personal
                                de los estudiantes.
                            </p>
                        </div>
                    </article>

                    <article class="nosotros-compromiso-item">
                        <span class="nosotros-compromiso-number">03</span>

                        <div>
                            <h3>Convivencia y valores</h3>
                            <p>
                                Promover un ambiente de respeto,
                                responsabilidad, colaboración
                                y sana convivencia.
                            </p>
                        </div>
                    </article>

                    <article class="nosotros-compromiso-item">
                        <span class="nosotros-compromiso-number">04</span>

                        <div>
                            <h3>Participación de las familias</h3>
                            <p>
                                Fortalecer la comunicación y el
                                acompañamiento entre la institución
                                y los hogares.
                            </p>
                        </div>
                    </article>

                    <article class="nosotros-compromiso-item">
                        <span class="nosotros-compromiso-number">05</span>

                        <div>
                            <h3>Innovación y mejora continua</h3>
                            <p>
                                Incorporar herramientas y prácticas
                                que contribuyan a mejorar
                                los procesos educativos.
                            </p>
                        </div>
                    </article>

                </div>

            </div>

        </div>

    </div>
</section>

<section class="nosotros-directora section-padding">
    <div class="container">

        <div class="nosotros-directora-card">

            <div class="row align-items-center g-5">

                <div class="col-lg-4">

                    <div class="nosotros-directora-photo">

                        <div class="nosotros-directora-photo-circle">
                            <i class="bi bi-person"></i>
                            <span>Fotografía de la directora</span>
                        </div>

                    </div>

                </div>

                <div class="col-lg-8">

                    <div class="nosotros-directora-content">

                        <span class="nosotros-directora-eyebrow">
                            MENSAJE DE DIRECCIÓN
                        </span>

                        <h2>
                            Un mensaje para nuestra
                            comunidad educativa
                        </h2>

                        <i class="bi bi-quote nosotros-quote-icon"></i>

                        <div class="nosotros-directora-message">

                            <p>
                                La educación representa una oportunidad
                                para descubrir capacidades, fortalecer
                                valores y construir un futuro lleno
                                de posibilidades.
                            </p>

                            <p>
                                Cada estudiante forma parte esencial
                                de nuestra comunidad. El acompañamiento
                                de las familias y el compromiso de
                                los docentes son fundamentales para
                                favorecer su aprendizaje y desarrollo.
                            </p>

                            <p>
                                Continuar creciendo como institución
                                requiere dedicación, trabajo en equipo
                                y el deseo permanente de ofrecer
                                mejores oportunidades educativas.
                            </p>

                        </div>

                        <div class="nosotros-directora-firma">

                            <strong>
                                Licenciada Eneida Raquel Reyes Rojas
                            </strong>

                            <span>
                                Directora
                            </span>

                            <small>
                                Mensaje propuesto, pendiente de
                                revisión y autorización de Dirección.
                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</section>

<section class="nosotros-galeria section-padding">
    <div class="container">

        <div class="section-center">

            <span class="section-eyebrow">
                NUESTRAS INSTALACIONES
            </span>

            <h2 class="section-heading">
                Conoce nuestros espacios
            </h2>

            <p class="section-description">
                Un recorrido visual por los espacios
                que forman parte de nuestra institución.
            </p>

        </div>

        <div class="row g-4">

            <div class="col-lg-8">

                <div class="nosotros-gallery-item nosotros-gallery-large">
                    <i class="bi bi-buildings"></i>
                    <strong>Vista general de la institución</strong>
                    <span>Fotografía institucional pendiente</span>
                </div>

            </div>

            <div class="col-lg-4">

                <div class="nosotros-gallery-item">
                    <i class="bi bi-door-open"></i>
                    <strong>Instalaciones</strong>
                    <span>Fotografía pendiente</span>
                </div>

            </div>

            <div class="col-lg-4">

                <div class="nosotros-gallery-item">
                    <i class="bi bi-book"></i>
                    <strong>Espacios de aprendizaje</strong>
                    <span>Fotografía pendiente</span>
                </div>

            </div>

            <div class="col-lg-4">

                <div class="nosotros-gallery-item">
                    <i class="bi bi-tree"></i>
                    <strong>Áreas de convivencia</strong>
                    <span>Fotografía pendiente</span>
                </div>

            </div>

            <div class="col-lg-4">

                <div class="nosotros-gallery-item">
                    <i class="bi bi-camera"></i>
                    <strong>Vida institucional</strong>
                    <span>Fotografía pendiente</span>
                </div>

            </div>

        </div>

        <p class="nosotros-gallery-note">
            <i class="bi bi-info-circle"></i>
            Las fotografías oficiales se incorporarán cuando
            sean proporcionadas y autorizadas por la institución.
        </p>

    </div>
</section>

<section class="nosotros-video section-padding">
    <div class="container">

        <div class="row align-items-center g-5">

            <div class="col-lg-5">

                <span class="section-eyebrow">
                    DESCUBRE NUESTRA INSTITUCIÓN
                </span>

                <h2 class="section-heading">
                    Colegio Mixto El Jardín
                    en imágenes
                </h2>

                <p class="section-description">
                    Conoce más sobre nuestra institución,
                    sus espacios y la comunidad educativa
                    mediante una presentación audiovisual.
                </p>

                <div class="nosotros-video-info">
                    <i class="bi bi-play-circle-fill"></i>

                    <div>
                        <strong>Video institucional</strong>
                        <span>
                            Próximamente disponible
                        </span>
                    </div>
                </div>

            </div>

            <div class="col-lg-7">

                <div class="nosotros-video-placeholder">

                    <div class="nosotros-video-play">
                        <i class="bi bi-play-fill"></i>
                    </div>

                    <h3>Video promocional del colegio</h3>

                    <p>
                        Espacio reservado para el video
                        institucional oficial.
                    </p>

                    <span>
                        FORMATO PANORÁMICO 16:9
                    </span>

                </div>

            </div>

        </div>

    </div>
</section>

<section class="nosotros-cierre">
    <div class="container">

        <div class="nosotros-cierre-content">

            <span>FORMA PARTE DE NUESTRA COMUNIDAD</span>

            <h2>
                Conoce más sobre nuestra
                propuesta educativa
            </h2>

            <p>
                Explora la oferta académica y descubre
                las oportunidades de formación del
                Colegio Mixto El Jardín.
            </p>

            <a href="{{ route('institucional.oferta-academica') }}"
               class="btn-institutional">

                Explorar oferta académica
                <i class="bi bi-arrow-right"></i>

            </a>

        </div>
        <br>
    </div>
    <br>
</section>

@endsection
