@extends('layouts.institucional')

@section('title', 'Noticias')
@section('description', 'Noticias, actividades y eventos del Colegio Mixto El Jardín.')

@push('styles')
    @vite('resources/css/noticias.css')
@endpush

@section('content')
@php
    $categorias = ['Todas', 'Institucional', 'Actividades', 'Eventos', 'Proyectos', 'Logros'];
    $logoColegio = asset('images/institucional/logo-colegio.png');
@endphp

<section class="noticias-hero">
    <div class="container">
        <div class="noticias-hero-content">
            <span class="noticias-eyebrow">NUESTRA COMUNIDAD EDUCATIVA</span>
            <h1>Noticias</h1>
            <p>Compartimos las actividades, experiencias, eventos y acontecimientos que forman parte de la vida de nuestra institución.</p>
            <nav aria-label="Ruta de navegación">
                <ol class="noticias-breadcrumb">
                    <li><a href="{{ route('institucional.inicio') }}">Inicio</a></li>
                    <li aria-current="page">Noticias</li>
                </ol>
            </nav>
        </div>
    </div>
</section>

<section class="noticias-muro section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-8">
                <div class="noticias-feed-header">
                    <div>
                        <span class="section-eyebrow">ACTUALIDAD INSTITUCIONAL</span>
                        <h2 class="section-heading">Nuestras publicaciones</h2>
                        <p class="section-description">Conoce los momentos, actividades y experiencias que compartimos con nuestra comunidad.</p>
                    </div>
                </div>

                <div class="noticias-filtros" aria-label="Categorías de publicaciones">
                    @foreach($categorias as $categoria)
                        <button type="button"
                                class="noticias-filtro {{ $loop->first ? 'active' : '' }}"
                                data-categoria="{{ $categoria }}"
                                aria-pressed="{{ $loop->first ? 'true' : 'false' }}">
                            {{ $categoria }}
                        </button>
                    @endforeach
                </div>

                <div class="noticias-feed" id="noticiasFeed">
                    @forelse($publicaciones as $publicacion)
                        @php
                            $titulo = data_get($publicacion, 'titulo', '');
                            $contenido = data_get($publicacion, 'contenido', '');
                            $categoria = data_get($publicacion, 'categoria') ?: 'Institucional';
                            $fecha = data_get($publicacion, 'fecha_publicacion');
                            $imagenes = data_get($publicacion, 'imagenes', []);
                            $videos = data_get($publicacion, 'videos', []);
                            $destacado = (bool) data_get($publicacion, 'destacado', false);
                            $imagenes = $imagenes instanceof \Illuminate\Support\Collection ? $imagenes->all() : $imagenes;
                            $videos = $videos instanceof \Illuminate\Support\Collection ? $videos->all() : $videos;
                            $imagenes = is_array($imagenes) ? $imagenes : [];
                            $videos = is_array($videos) ? $videos : [];
                            $fechaCarbon = $fecha ? \Illuminate\Support\Carbon::parse($fecha) : null;
                        @endphp
                        <article class="noticias-post" data-post-categoria="{{ $categoria }}">
                            <div class="noticias-post-header">
                                <div class="noticias-post-avatar">
                                    <img src="{{ $logoColegio }}" alt="Logotipo del Colegio Mixto El Jardín" loading="lazy">
                                </div>
                                <div class="noticias-post-author">
                                    <strong>Colegio Mixto El Jardín</strong>
                                    <div class="noticias-post-meta">
                                        @if($fechaCarbon)
                                            <time datetime="{{ $fechaCarbon->toDateString() }}">
                                                {{ $fechaCarbon->locale('es')->translatedFormat('d \d\e F \d\e Y') }}
                                            </time>
                                            <span class="noticias-meta-dot">·</span>
                                        @endif
                                        <span><i class="bi bi-globe-americas"></i> Público</span>
                                    </div>
                                </div>
                                <span class="noticias-post-category">{{ $categoria }}</span>
                            </div>

                            <div class="noticias-post-body">
                                @if($destacado)
                                    <span class="noticias-post-featured">
                                        <i class="bi bi-pin-angle-fill"></i> Publicación destacada
                                    </span>
                                @endif
                                @if($titulo)
                                    <h3>{{ $titulo }}</h3>
                                @endif
                                @if($contenido)
                                    <div class="noticias-post-text">{!! nl2br(e($contenido)) !!}</div>
                                @endif
                            </div>

                            @if(count($imagenes))
                                <div class="noticias-post-gallery {{ count($imagenes) === 1 ? 'single' : (count($imagenes) === 2 ? 'double' : 'multiple') }}">
                                    @foreach($imagenes as $imagen)
                                        @php
                                            $imagenUrl = is_array($imagen) ? ($imagen['url'] ?? null) : $imagen;
                                        @endphp
                                        @if($imagenUrl)
                                            <div class="noticias-post-image">
                                                <img src="{{ $imagenUrl }}"
                                                     alt="Fotografía {{ $loop->iteration }} de la publicación {{ $titulo }}"
                                                     loading="lazy">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif

                            @foreach($videos as $video)
                                @if(is_string($video) && $video !== '')
                                    <div class="noticias-post-video">
                                        <video controls preload="metadata" playsinline
                                               aria-label="Video {{ $loop->iteration }} de la publicación {{ $titulo }}">
                                            <source src="{{ $video }}">
                                            Tu navegador no admite la reproducción de video.
                                        </video>
                                    </div>
                                @endif
                            @endforeach
                        </article>
                    @empty
                        <div class="noticias-empty-state">
                            <i class="bi bi-newspaper"></i>
                            <h3>Aún no hay publicaciones</h3>
                            <p>Las noticias y actividades aparecerán aquí cuando sean publicadas oficialmente.</p>
                        </div>
                    @endforelse
                </div>

                <div class="noticias-filter-empty" id="noticiasFilterEmpty" hidden>
                    <i class="bi bi-search"></i>
                    <h3>No hay publicaciones en esta categoría</h3>
                    <p>Selecciona otra categoría para consultar las publicaciones disponibles.</p>
                </div>

                @if($publicaciones instanceof \Illuminate\Contracts\Pagination\Paginator && $publicaciones->hasPages())
                    <div class="noticias-pagination">{{ $publicaciones->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const filtros = document.querySelectorAll('.noticias-filtro');
    const publicaciones = document.querySelectorAll('.noticias-post');
    const sinResultados = document.getElementById('noticiasFilterEmpty');

    filtros.forEach(function (filtro) {
        filtro.addEventListener('click', function () {
            const categoria = filtro.dataset.categoria;
            let visibles = 0;

            filtros.forEach(function (item) {
                const activo = item === filtro;
                item.classList.toggle('active', activo);
                item.setAttribute('aria-pressed', activo ? 'true' : 'false');
            });

            publicaciones.forEach(function (publicacion) {
                const mostrar = categoria === 'Todas' ||
                    publicacion.dataset.postCategoria === categoria;
                publicacion.hidden = !mostrar;
                if (mostrar) visibles++;
            });

            if (sinResultados) {
                sinResultados.hidden = visibles > 0 && publicaciones.length > 0;
            }
        });
    });
});
</script>
@endpush
