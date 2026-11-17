@extends('layouts.institucional')

@section('title', 'Preguntas frecuentes')
@section('description', 'Respuestas a preguntas frecuentes sobre el Colegio Mixto El Jardín, San Raymundo.')

@push('styles')
<style>
.faq-hero{background:linear-gradient(120deg,#142b4c,#24568b);padding:74px 0;color:white}
.faq-hero h1{font-weight:800;font-size:clamp(2rem,4vw,3rem)}
.faq-hero p{max-width:700px;color:#e1eaf7}
.faq-section{padding:65px 0 85px;background:#f5f8fc}
.faq-group{margin-bottom:36px}
.faq-group h2{font-size:1.2rem;color:#193b65;font-weight:800;margin-bottom:18px}
.faq-accordion .accordion-item{border:1px solid #dbe5ef;border-radius:12px!important;overflow:hidden;margin-bottom:10px}
.faq-accordion .accordion-button{font-weight:700;color:#1b3554;box-shadow:none!important;padding:20px}
.faq-accordion .accordion-button:not(.collapsed){background:#eaf1fa;color:#123f75}
.faq-accordion .accordion-body{color:#42576f;line-height:1.75;white-space:pre-line}
.faq-end{background:white;border:1px solid #dce5ef;border-radius:16px;padding:28px}
</style>
@endpush

@section('content')
<section class="faq-hero">
    <div class="container">
        <span class="text-uppercase fw-bold small">Información para familias</span>
        <h1 class="mt-3">Preguntas frecuentes</h1>
        <p class="mb-0">Encuentra respuestas a las consultas más comunes sobre nuestra institución y sus servicios educativos.</p>
    </div>
</section>

<section class="faq-section">
    <div class="container" style="max-width:940px">
        @forelse($categorias as $categoria => $items)
            <div class="faq-group">
                <h2><i class="bi bi-bookmark-check me-2" aria-hidden="true"></i>{{ $categoria }}</h2>
                <div class="accordion faq-accordion" id="faqGrupo{{ $loop->index }}">
                    @foreach($items as $item)
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="faqTitulo{{ $item->id }}">
                                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faqRespuesta{{ $item->id }}"
                                    aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                    aria-controls="faqRespuesta{{ $item->id }}">
                                    {{ $item->pregunta }}
                                </button>
                            </h3>
                            <div id="faqRespuesta{{ $item->id }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                data-bs-parent="#faqGrupo{{ $loop->parent->index }}"
                                aria-labelledby="faqTitulo{{ $item->id }}">
                                <div class="accordion-body">{{ $item->respuesta }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="alert alert-light border">Próximamente publicaremos las respuestas a las preguntas más comunes.</div>
        @endforelse

        <div class="faq-end d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h2 class="h5 fw-bold mb-1">¿Necesitas más información?</h2>
                <p class="text-secondary mb-0">Puedes consultar nuestros medios de atención y solicitar una visita.</p>
            </div>
            <a href="{{ route('institucional.contacto') }}" class="btn btn-danger px-4">Contacto <i class="bi bi-arrow-right ms-1"></i></a>
        </div>
    </div>
</section>
@endsection
