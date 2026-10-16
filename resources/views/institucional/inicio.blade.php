@extends('layouts.institucional')

@section('title', 'Inicio')
@section('description', 'Conoce el Colegio Mixto El Jardín, San Raymundo: nuestra comunidad educativa, actividades y formación académica.')

@section('content')
@php
    $slides = [
        ['image' => 'images/institucional/carrusel/colegio-01.jpg', 'eyebrow' => 'COLEGIO MIXTO EL JARDÍN · SAN RAYMUNDO', 'title' => 'Un espacio para aprender y crecer', 'description' => 'Conoce nuestra comunidad educativa y descubre un entorno donde cada etapa de aprendizaje importa.', 'action' => 'Conoce el colegio', 'target' => '#nosotros'],
        ['image' => 'images/institucional/carrusel/colegio-02.jpg', 'eyebrow' => 'NUESTRA COMUNIDAD', 'title' => 'Experiencias que dejan huella', 'description' => 'Las actividades escolares y la participación estudiantil forman parte de nuestra vida institucional.', 'action' => 'Vida estudiantil', 'target' => '#vida-estudiantil'],
        ['image' => 'images/institucional/carrusel/colegio-03.jpg', 'eyebrow' => 'FORMACIÓN Y FUTURO', 'title' => 'Descubre nuestra propuesta educativa', 'description' => 'Infórmate sobre nuestra institución y los programas académicos que ofrece.', 'action' => 'Oferta educativa', 'target' => '#oferta-academica'],
    ];
@endphp

<section class="school-hero" aria-label="Presentación del colegio">
    <div id="schoolHeroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="6500" data-bs-pause="hover">
        <div class="carousel-indicators school-hero-indicators">
            @foreach($slides as $index => $slide)
                <button type="button" data-bs-target="#schoolHeroCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" @if($index === 0) aria-current="true" @endif aria-label="Ver presentación {{ $index + 1 }}"></button>
            @endforeach
        </div>
        <div class="carousel-inner">
            @foreach($slides as $index => $slide)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <div class="school-hero-slide" style="--slide-image: url('{{ asset($slide['image']) }}')">
                        <div class="container school-hero-container">
                            <div class="school-hero-copy">
                                <span class="school-eyebrow school-eyebrow-light"><span class="school-eyebrow-line"></span>{{ $slide['eyebrow'] }}</span>
                                <h1>{{ $slide['title'] }}</h1>
                                <p>{{ $slide['description'] }}</p>
                                <div class="school-hero-actions">
                                    <a href="{{ $slide['target'] }}" class="school-btn school-btn-red">{{ $slide['action'] }} <i class="bi bi-arrow-up-right" aria-hidden="true"></i></a>
                                    <a href="#contacto" class="school-btn school-btn-outline-light">Solicitar información</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <button class="carousel-control-prev school-hero-control" type="button" data-bs-target="#schoolHeroCarousel" data-bs-slide="prev"><span class="school-hero-arrow"><i class="bi bi-chevron-left" aria-hidden="true"></i></span><span class="visually-hidden">Anterior</span></button>
        <button class="carousel-control-next school-hero-control" type="button" data-bs-target="#schoolHeroCarousel" data-bs-slide="next"><span class="school-hero-arrow"><i class="bi bi-chevron-right" aria-hidden="true"></i></span><span class="visually-hidden">Siguiente</span></button>
    </div>
</section>

<section class="school-intro-band" aria-label="Valores de nuestra comunidad">
    <div class="container"><div class="row g-0">
        <div class="col-md-4"><div class="school-intro-feature"><i class="bi bi-book-half" aria-hidden="true"></i><div><strong>Aprendizaje</strong><span>Conocimiento para el futuro</span></div></div></div>
        <div class="col-md-4"><div class="school-intro-feature"><i class="bi bi-people" aria-hidden="true"></i><div><strong>Comunidad</strong><span>Participación y convivencia</span></div></div></div>
        <div class="col-md-4"><div class="school-intro-feature"><i class="bi bi-stars" aria-hidden="true"></i><div><strong>Desarrollo integral</strong><span>Experiencias dentro y fuera del aula</span></div></div></div>
    </div></div>
</section>

<section id="nosotros" class="school-section school-welcome">
    <div class="container"><div class="row align-items-center g-5">
        <div class="col-lg-6">
            <div class="school-image-composition">
                <div class="school-image-main school-image-fallback" data-image-fallback><img src="{{ asset('images/institucional/presentacion/colegio.jpg') }}" alt="Instalaciones del Colegio Mixto El Jardín" loading="lazy"><div class="school-image-placeholder"><i class="bi bi-buildings" aria-hidden="true"></i><span>Conoce nuestra institución</span></div></div>
                <div class="school-image-caption"><i class="bi bi-mortarboard-fill" aria-hidden="true"></i><span>Colegio Mixto<br><strong>El Jardín</strong></span></div>
            </div>
        </div>
        <div class="col-lg-6">
            <span class="school-eyebrow">BIENVENIDOS A NUESTRA INSTITUCIÓN</span>
            <h2 class="school-heading">Un colegio, muchas oportunidades para <em>crecer.</em></h2>
            <p class="school-lead">El Colegio Mixto El Jardín, ubicado en San Raymundo, abre sus puertas a estudiantes y familias que desean conocer su propuesta educativa y formar parte de su comunidad.</p>
            <p class="school-body">Creemos en el valor de la educación, la convivencia y las experiencias que contribuyen al desarrollo de cada estudiante. En este sitio podrás encontrar información del establecimiento, sus actividades y los medios para comunicarte con nosotros.</p>
            <a href="#contacto" class="school-text-link">Conoce más sobre el colegio <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
        </div>
    </div></div>
</section>

<section class="school-section school-why" aria-labelledby="why-title">
    <div class="container">
        <div class="school-section-head text-center"><span class="school-eyebrow">NUESTRA COMUNIDAD EDUCATIVA</span><h2 id="why-title" class="school-heading">Mucho más que un salón de clases</h2><p>La formación escolar también se construye mediante experiencias, participación y relaciones que fortalecen a la comunidad.</p></div>
        <div class="row g-4">
            <div class="col-md-4"><article class="school-value-card"><span class="school-value-number">01</span><div class="school-value-icon"><i class="bi bi-journal-bookmark-fill" aria-hidden="true"></i></div><h3>Aprendizaje con propósito</h3><p>La educación como base para adquirir conocimientos, desarrollar habilidades y abrir nuevas oportunidades.</p></article></div>
            <div class="col-md-4"><article class="school-value-card"><span class="school-value-number">02</span><div class="school-value-icon"><i class="bi bi-people-fill" aria-hidden="true"></i></div><h3>Participación estudiantil</h3><p>Actividades y espacios de convivencia que enriquecen la experiencia de nuestra comunidad educativa.</p></article></div>
            <div class="col-md-4"><article class="school-value-card"><span class="school-value-number">03</span><div class="school-value-icon"><i class="bi bi-heart-fill" aria-hidden="true"></i></div><h3>Formación integral</h3><p>Una visión de la educación que reconoce la importancia del crecimiento académico y personal.</p></article></div>
        </div>
    </div>
</section>

<section id="vida-estudiantil" class="school-section school-life">
    <div class="container">
        <div class="school-life-header"><div><span class="school-eyebrow">VIDA ESTUDIANTIL</span><h2 class="school-heading">Momentos que forman parte de nuestra historia</h2></div><p>Un espacio para compartir actividades, celebraciones, logros y acontecimientos de nuestra comunidad escolar.</p></div>
        <div class="school-gallery-grid">
            @foreach([
                ['file' => 'actividad-01.jpg', 'title' => 'Actividades escolares', 'icon' => 'bi-calendar-event'],
                ['file' => 'actividad-02.jpg', 'title' => 'Participación estudiantil', 'icon' => 'bi-people'],
                ['file' => 'actividad-03.jpg', 'title' => 'Experiencias educativas', 'icon' => 'bi-award'],
            ] as $photo)
                <article class="school-gallery-item school-image-fallback" data-image-fallback>
                    <img src="{{ asset('images/institucional/galeria/' . $photo['file']) }}" alt="{{ $photo['title'] }} del Colegio Mixto El Jardín" loading="lazy">
                    <div class="school-image-placeholder"><i class="bi {{ $photo['icon'] }}" aria-hidden="true"></i><span>{{ $photo['title'] }}</span></div>
                    <div class="school-gallery-caption"><h3>{{ $photo['title'] }}</h3><i class="bi bi-arrow-up-right" aria-hidden="true"></i></div>
                </article>
            @endforeach
        </div>
        <p class="school-gallery-note"><i class="bi bi-shield-check" aria-hidden="true"></i> Las fotografías de actividades se incorporarán únicamente cuando cuenten con la autorización institucional correspondiente.</p>
    </div>
</section>

<section id="oferta-academica" class="school-section school-academic">
    <div class="container"><div class="row align-items-center g-5">
        <div class="col-lg-6"><span class="school-eyebrow">OFERTA EDUCATIVA</span><h2 class="school-heading">Tu próximo paso comienza con una buena decisión</h2><p class="school-lead">Conoce las oportunidades de formación del Colegio Mixto El Jardín.</p><p class="school-body">Estamos preparando la información de los niveles y programas académicos que ofrece el establecimiento, junto con los requisitos y procedimientos de inscripción.</p><a href="#contacto" class="school-btn school-btn-red">Solicitar información <i class="bi bi-arrow-right" aria-hidden="true"></i></a></div>
        <div class="col-lg-6"><div class="school-academic-panel"><div class="school-academic-panel-top"><i class="bi bi-mortarboard" aria-hidden="true"></i><span>INFORMACIÓN ACADÉMICA</span></div><div class="school-academic-panel-row"><i class="bi bi-book" aria-hidden="true"></i><div><strong>Niveles y programas</strong><span>Información institucional en preparación</span></div></div><div class="school-academic-panel-row"><i class="bi bi-card-checklist" aria-hidden="true"></i><div><strong>Inscripciones y requisitos</strong><span>Consulta los canales de atención del colegio</span></div></div><div class="school-academic-panel-row"><i class="bi bi-chat-square-text" aria-hidden="true"></i><div><strong>Orientación para familias</strong><span>Solicita información sobre el establecimiento</span></div></div></div></div>
    </div></div>
</section>

<section id="portal-atlas" class="school-atlas">
    <div class="container"><div class="row align-items-center g-5">
        <div class="col-lg-7"><span class="school-atlas-eyebrow">TRANSFORMACIÓN DIGITAL EDUCATIVA</span><div class="school-atlas-brand"><img src="{{ asset('images/atlas/logo-atlas.png') }}" alt="" class="school-atlas-logo" onerror="this.style.display='none'"><h2>ATLAS</h2></div><h3>Sistema Integral de Control Escolar</h3><p>Una plataforma tecnológica diseñada para acompañar la gestión académica y administrativa del Colegio Mixto El Jardín.</p><div class="school-atlas-tags"><span><i class="bi bi-diagram-3" aria-hidden="true"></i> Conecta</span><span><i class="bi bi-grid" aria-hidden="true"></i> Gestiona</span><span><i class="bi bi-bar-chart" aria-hidden="true"></i> Analiza</span></div></div>
        <div class="col-lg-5"><div class="school-atlas-access"><div class="school-atlas-access-icon"><i class="bi bi-laptop" aria-hidden="true"></i></div><h4>Tu colegio, conectado</h4><p>El portal de acceso a ATLAS estará disponible cuando finalice su implementación.</p><span class="school-atlas-status"><i class="bi bi-clock-history" aria-hidden="true"></i> Plataforma en desarrollo</span></div></div>
    </div></div>
</section>

<section id="contacto" class="school-section school-contact"><div class="container"><div class="school-contact-panel"><div><span class="school-eyebrow">¿QUIERES CONOCERNOS?</span><h2>Tu próximo paso puede comenzar aquí.</h2><p>Comunícate con el Colegio Mixto El Jardín para conocer más sobre nuestra institución y sus procesos de inscripción.</p></div><div class="school-contact-action"><span><i class="bi bi-geo-alt" aria-hidden="true"></i> San Raymundo, Guatemala</span><span class="school-contact-pending">Los medios oficiales de contacto se publicarán próximamente.</span></div></div></div></section>
@endsection
