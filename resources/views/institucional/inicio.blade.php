@extends('layouts.institucional')

@section('title', 'Inicio')
@section('description', 'Conoce el Colegio Mixto El Jardín, San Raymundo: nuestra comunidad educativa, actividades y formación académica.')


@push('styles')
<style>
/* Mejoras exclusivas de la página de inicio; la barra superior no se modifica. */
/* Escudo destacado, integrado en el primer slide y sin tarjetas de identidad. */
.school-team { background: linear-gradient(180deg,#f4f8fd 0%,#fff 100%); }
.school-team-intro { max-width: 780px; margin: 0 auto 35px; text-align: center; }
.school-team-intro p { color: #5d7085; line-height: 1.8; margin-top: 14px; }
.school-team-photo { margin: 0; overflow: hidden; background: #fff; border: 1px solid #dfe8f2; border-radius: 24px; box-shadow: 0 22px 55px rgba(20,45,77,.12); }
.school-team-photo img { display: block; width: 100%; height: auto; object-fit: contain; }
.school-team-photo figcaption { display: flex; align-items: center; gap: 12px; padding: 21px 28px; background: #fff; color: #183451; }
.school-team-photo figcaption i { display: grid; place-items: center; width: 42px; height: 42px; border-radius: 12px; background: #e9f1fb; color: #174d85; font-size: 20px; flex-shrink: 0; }
.school-team-photo figcaption strong { display: block; font-size: 16px; }
.school-team-photo figcaption span { display: block; font-size: 13px; margin-top: 3px; color: #667b90; }
@media(max-width: 767px) {
 .school-team-photo figcaption { padding: 16px; }
}

.school-brand-ribbon {position:relative;background:linear-gradient(110deg,#fff 0%,#f5f8fc 100%);border-bottom:1px solid #e3eaf2}
.school-brand-ribbon-inner {display:flex;align-items:center;justify-content:center;gap:32px;padding:24px 18px}
.school-brand-ribbon img {width:clamp(110px,11vw,150px);height:auto;object-fit:contain;flex-shrink:0}
.school-brand-ribbon span {font-size:11px;font-weight:800;letter-spacing:.17em;color:#b52242}
.school-brand-ribbon h2 {font-size:clamp(24px,3vw,37px);font-weight:800;color:#102e54;margin:7px 0}
.school-brand-ribbon p {color:#60748b;margin:0}
.school-visit-button {display:inline-flex;align-items:center;justify-content:center;gap:10px;background:#b92343;color:white!important;padding:14px 23px;border:0;border-radius:10px;font-weight:800;cursor:pointer;text-decoration:none;box-shadow:0 9px 24px rgba(185,35,67,.18)}
.school-visit-button:hover {background:#981b37}
.school-visit-modal .modal-content {border:0;border-radius:20px;overflow:hidden}
.school-visit-modal .modal-header {background:#102e54;color:#fff;padding:22px 25px}
.school-visit-modal .modal-body {padding:25px}
.school-visit-modal .form-label {font-weight:700;font-size:13px;color:#234365}
.school-visit-modal .form-control,.school-visit-modal .form-select {border-radius:9px;padding:11px;border-color:#d7e2ee}
@media(max-width:600px){.school-brand-ribbon-inner{gap:16px;text-align:left}.school-brand-ribbon img{width:100px}.school-brand-ribbon h2{font-size:22px}}
</style>
@endpush

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
                    <div class="school-hero-slide " style="--slide-image: url('{{ asset($slide['image']) }}')">
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
<section class="school-brand-ribbon" aria-label="Identidad institucional">
 <div class="container school-brand-ribbon-inner">
  <img src="{{ asset('images/institucional/logo-colegio.png') }}" alt="Escudo del Colegio Mixto El Jardín" width="150" height="150">
  <div><span>IDENTIDAD INSTITUCIONAL</span><h2>Colegio Mixto El Jardín</h2><p>San Raymundo, Guatemala · Educación, comunidad y oportunidades para crecer.</p></div>
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

<section id="equipo-docente" class="school-section school-team" aria-labelledby="school-team-title">
    <div class="container">
        <div class="school-team-intro">
            <span class="school-eyebrow">NUESTRO EQUIPO DOCENTE</span>
            <h2 id="school-team-title" class="school-heading">Personas que acompañan cada etapa del aprendizaje</h2>
            <p>Conoce al equipo docente que forma parte de la comunidad educativa del Colegio Mixto El Jardín. Su labor es esencial en la formación y el acompañamiento de nuestros estudiantes.</p>
        </div>
        <figure class="school-team-photo">
            <img src="{{ asset('images/institucional/personal/docentes-jardin.jpeg') }}" alt="Fotografía del equipo docente del Colegio Mixto El Jardín" loading="lazy" decoding="async">
            <figcaption><i class="bi bi-people-fill" aria-hidden="true"></i><div><strong>Nuestro personal docente</strong><span>Colegio Mixto El Jardín · San Raymundo, Guatemala</span></div></figcaption>
        </figure>
    </div>
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

<section id="contacto" class="school-section school-contact"><div class="container"><div class="school-contact-panel"><div><span class="school-eyebrow">¿QUIERES CONOCERNOS?</span><h2>Tu próximo paso puede comenzar aquí.</h2><p>Comunícate con el Colegio Mixto El Jardín para conocer más sobre nuestra institución y sus procesos de inscripción.</p></div><div class="school-contact-action"><span><i class="bi bi-geo-alt" aria-hidden="true"></i> San Raymundo, Guatemala</span><button type="button" class="school-visit-button" data-bs-toggle="modal" data-bs-target="#modalAgendaVisita"><i class="bi bi-calendar-plus"></i> Agendar cita</button></div></div></div></section>

<div class="modal fade school-visit-modal" id="modalAgendaVisita" tabindex="-1" aria-labelledby="modalAgendaVisitaTitulo" aria-hidden="true">
 <div class="modal-dialog modal-dialog-centered modal-lg"><div class="modal-content">
  <div class="modal-header"><div><h2 class="modal-title fs-5" id="modalAgendaVisitaTitulo">Agendar una visita</h2><small>Colegio Mixto El Jardín · Citas de una hora</small></div><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button></div>
  <form action="{{ route('visitas.publicas.store') }}" method="POST" id="formAgendaVisita">@csrf
   <div class="modal-body"><div class="row g-3">
    <div class="col-md-6"><label for="visitaNombre" class="form-label">Nombre completo *</label><input id="visitaNombre" name="nombre_visitante" class="form-control" maxlength="160" required value="{{ old('nombre_visitante') }}"></div>
    <div class="col-md-6"><label for="visitaTelefono" class="form-label">Teléfono *</label><input id="visitaTelefono" name="telefono" class="form-control" maxlength="30" required value="{{ old('telefono') }}"></div>
    <div class="col-md-6"><label for="visitaCorreo" class="form-label">Correo (opcional)</label><input id="visitaCorreo" type="email" name="correo" class="form-control" maxlength="190" value="{{ old('correo') }}"></div>
    <div class="col-md-6"><label for="visitaMotivo" class="form-label">Motivo *</label><select id="visitaMotivo" name="motivo" class="form-select" required><option value="">Seleccionar...</option><option value="Información de inscripción">Información de inscripción</option><option value="Visita al colegio">Visita al colegio</option><option value="Consulta institucional">Consulta institucional</option><option value="Otro">Otro</option></select></div>
    <div class="col-md-6"><label for="visitaFecha" class="form-label">Fecha preferida *</label><input id="visitaFecha" type="date" name="fecha" class="form-control" min="{{ now()->addDay()->toDateString() }}" required></div>
    <div class="col-md-6"><label for="visitaHora" class="form-label">Horario disponible *</label><select id="visitaHora" name="hora_inicio" class="form-select" required disabled><option value="">Primero seleccione una fecha</option></select></div>
    <div class="col-12"><label for="visitaNotas" class="form-label">Observaciones (opcional)</label><textarea id="visitaNotas" name="observaciones" class="form-control" rows="2" maxlength="1000"></textarea></div>
    <div class="col-12"><div id="visitaDisponibilidad" class="small text-secondary" role="status">Las solicitudes se registran como pendientes y reservan el horario seleccionado hasta que el colegio las gestione.</div></div>
   </div></div>
   <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="school-visit-button">Solicitar visita</button></div>
  </form>
 </div></div>
</div>
@if(session('visita_ok'))<div class="container my-3"><div class="alert alert-success" role="alert">{{ session('visita_ok') }}</div></div>@endif
@if($errors->any())<div class="container my-3"><div class="alert alert-danger" role="alert">{{ $errors->first() }}</div></div>@endif
<script>
document.addEventListener('DOMContentLoaded',()=>{
 const fecha=document.getElementById('visitaFecha'), hora=document.getElementById('visitaHora'), aviso=document.getElementById('visitaDisponibilidad');
 if(!fecha||!hora)return;
 fecha.addEventListener('change',async()=>{
  hora.disabled=true;hora.innerHTML='<option value="">Consultando...</option>';
  if(!fecha.value)return;
  try{const url=new URL(@json(route('visitas.publicas.disponibilidad')),window.location.origin);url.searchParams.set('fecha',fecha.value);
   const response=await fetch(url,{headers:{Accept:'application/json'}});if(!response.ok)throw Error();
   const data=await response.json();hora.innerHTML='<option value="">Seleccionar horario</option>';
   for(const slot of data.horarios){const option=document.createElement('option');option.value=slot.inicio;option.textContent=slot.etiqueta;hora.append(option)}
   hora.disabled=data.horarios.length===0;aviso.textContent=data.horarios.length?'Seleccione uno de los horarios disponibles.':'No hay horarios disponibles para esta fecha.';
  }catch(e){hora.innerHTML='<option value="">No fue posible consultar horarios</option>';aviso.textContent='Intente nuevamente.'}
 });
 @if($errors->any())new bootstrap.Modal(document.getElementById('modalAgendaVisita')).show();@endif
});
</script>

@endsection
