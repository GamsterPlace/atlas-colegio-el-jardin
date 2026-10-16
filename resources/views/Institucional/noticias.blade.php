
@extends('layouts.institucional')

@section('title', 'Noticias')

@section('description', 'Conoce las noticias, actividades, eventos y experiencias del Colegio Mixto El Jardín.')

@push('styles')
    @vite('resources/css/noticias.css')
@endpush

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | PUBLICACIONES PÚBLICAS
    |--------------------------------------------------------------------------
    | La vista utiliza publicaciones demostrativas mientras no exista
    | una colección enviada desde el controlador de ATLAS.
    |
    | En producción, el controlador deberá proporcionar únicamente
    | publicaciones públicas, autorizadas y vigentes.
    |
    | Campos previstos:
    | id, titulo, contenido, categoria, fecha_publicacion,
    | imagenes, video_url, destacado, slug.
    |--------------------------------------------------------------------------
    */

    $modoDemostracion = !isset($publicaciones);

    if ($modoDemostracion) {
        $publicaciones = collect([
            [
                'id' => 1,
                'titulo' => 'Bienvenidos a nuestro espacio de noticias',
                'contenido' => 'Este será el espacio oficial para compartir las actividades, eventos, proyectos y acontecimientos de nuestra comunidad educativa.

Próximamente encontrarás fotografías, videos y publicaciones de las experiencias que forman parte de la vida del Colegio Mixto El Jardín.',
                'categoria' => 'Institucional',
                'fecha_publicacion' => null,
                'imagenes' => [],
                'video_url' => null,
                'destacado' => true,
                'slug' => null
            ],
            [
                'id' => 2,
                'titulo' => 'Actividades culturales y educativas',
                'contenido' => 'Las actividades culturales y educativas permiten fortalecer la creatividad, la convivencia y la participación de los estudiantes.

En este espacio se compartirán fotografías y experiencias de las actividades realizadas en nuestra institución.',
                'categoria' => 'Actividades',
                'fecha_publicacion' => null,
                'imagenes' => [],
                'video_url' => null,
                'destacado' => false,
                'slug' => null
            ],
            [
                'id' => 3,
                'titulo' => 'Proyectos y experiencias de aprendizaje',
                'contenido' => 'Los proyectos educativos ofrecen oportunidades para desarrollar conocimientos, habilidades y nuevas experiencias.

Aquí se compartirán los trabajos, proyectos y actividades que la institución autorice para su publicación.',
                'categoria' => 'Proyectos',
                'fecha_publicacion' => null,
                'imagenes' => [],
                'video_url' => null,
                'destacado' => false,
                'slug' => null
            ],
            [
                'id' => 4,
                'titulo' => 'Celebrando los logros de nuestra comunidad',
                'contenido' => 'Este espacio permitirá reconocer los logros académicos, culturales y deportivos de nuestra comunidad educativa.

Cada publicación estará acompañada de información y material multimedia autorizado por la institución.',
                'categoria' => 'Logros',
                'fecha_publicacion' => null,
                'imagenes' => [],
                'video_url' => null,
                'destacado' => false,
                'slug' => null
            ]
        ]);
    }

    $logoColegio = asset('images/institucional/logo-colegio.png');

    $categorias = [
        'Todas',
        'Institucional',
        'Actividades',
        'Eventos',
        'Proyectos',
        'Logros'
    ];
@endphp

{{-- =====================================================
     ENCABEZADO
===================================================== --}}

<section class="noticias-hero">
    <div class="container">

        <div class="noticias-hero-content">

            <span class="noticias-eyebrow">
                NUESTRA COMUNIDAD EDUCATIVA
            </span>

            <h1>Noticias</h1>

            <p>
                Compartimos las actividades, experiencias,
                eventos y acontecimientos que forman parte
                de la vida de nuestra institución.
            </p>

            <nav aria-label="Ruta de navegación">
                <ol class="noticias-breadcrumb">

                    <li>
                        <a href="{{ route('institucional.inicio') }}">
                            Inicio
                        </a>
                    </li>

                    <li aria-current="page">
                        Noticias
                    </li>

                </ol>
            </nav>

        </div>

    </div>
</section>

{{-- =====================================================
     MURO DE PUBLICACIONES
===================================================== --}}

<section class="noticias-muro section-padding">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-9 col-xl-8">

                {{-- ENCABEZADO DEL MURO --}}

                <div class="noticias-feed-header">

                    <div>

                        <span class="section-eyebrow">
                            ACTUALIDAD INSTITUCIONAL
                        </span>

                        <h2 class="section-heading">
                            Nuestras publicaciones
                        </h2>

                        <p class="section-description">
                            Conoce los momentos, actividades
                            y experiencias que compartimos
                            con nuestra comunidad.
                        </p>

                    </div>

                </div>

                {{-- AVISO SOLO EN MODO DEMOSTRACIÓN --}}

                @if($modoDemostracion)

                    <div class="noticias-demo-notice">

                        <i class="bi bi-info-circle-fill"></i>

                        <span>
                            Publicaciones de demostración.
                            El contenido oficial se incorporará
                            desde la plataforma ATLAS.
                        </span>

                    </div>

                @endif

                {{-- =====================================================
                     FILTROS
                ===================================================== --}}

                <div class="noticias-filtros"
                     aria-label="Categorías de publicaciones">

                    @foreach($categorias as $categoria)

                        <button
                            type="button"
                            class="noticias-filtro {{ $loop->first ? 'active' : '' }}"
                            data-categoria="{{ $categoria }}"
                            aria-pressed="{{ $loop->first ? 'true' : 'false' }}">

                            {{ $categoria }}

                        </button>

                    @endforeach

                </div>

                {{-- =====================================================
                     PUBLICACIONES
                ===================================================== --}}

                <div class="noticias-feed" id="noticiasFeed">

                    @forelse($publicaciones as $publicacion)

                        @php
                            $titulo = data_get($publicacion, 'titulo', '');

                            $contenido = data_get($publicacion, 'contenido', '');

                            $categoria = data_get(
                                $publicacion,
                                'categoria',
                                'Institucional'
                            );

                            $fecha = data_get(
                                $publicacion,
                                'fecha_publicacion'
                            );

                            $imagenes = data_get(
                                $publicacion,
                                'imagenes',
                                []
                            );

                            $video = data_get(
                                $publicacion,
                                'video_url'
                            );

                            $destacado = data_get(
                                $publicacion,
                                'destacado',
                                false
                            );

                            if ($imagenes instanceof \Illuminate\Support\Collection) {
                                $imagenes = $imagenes->all();
                            }

                            $imagenes = is_array($imagenes)
                                ? $imagenes
                                : [];

                            $fechaFormateada = null;

                            if ($fecha) {
                                try {
                                    $fechaFormateada = \Illuminate\Support\Carbon::parse($fecha)
                                        ->locale('es')
                                        ->translatedFormat('d \d\e F \d\e Y');
                                } catch (\Exception $e) {
                                    $fechaFormateada = null;
                                }
                            }

                            $enlacePublicacion = null;

                            if (
                                !$modoDemostracion &&
                                data_get($publicacion, 'slug') &&
                                \Illuminate\Support\Facades\Route::has(
                                    'institucional.noticias.detalle'
                                )
                            ) {
                                $enlacePublicacion = route(
                                    'institucional.noticias.detalle',
                                    data_get($publicacion, 'slug')
                                );
                            }
                        @endphp

                        <article
                            class="noticias-post"
                            data-post-categoria="{{ $categoria }}">

                            {{-- ENCABEZADO DE PUBLICACIÓN --}}

                            <div class="noticias-post-header">

                                <div class="noticias-post-avatar">

                                    <img
                                        src="{{ $logoColegio }}"
                                        alt="Logotipo del Colegio Mixto El Jardín"
                                        loading="lazy">

                                </div>

                                <div class="noticias-post-author">

                                    <strong>
                                        Colegio Mixto El Jardín
                                    </strong>

                                    <div class="noticias-post-meta">

                                        @if($fechaFormateada)

                                            <time datetime="{{ \Illuminate\Support\Carbon::parse($fecha)->toDateString() }}">
                                                {{ $fechaFormateada }}
                                            </time>

                                            <span class="noticias-meta-dot">
                                                ·
                                            </span>

                                        @endif

                                        <span>
                                            <i class="bi bi-globe-americas"></i>
                                            Público
                                        </span>

                                    </div>

                                </div>

                                <span class="noticias-post-category">
                                    {{ $categoria }}
                                </span>

                            </div>

                            {{-- CONTENIDO DE LA PUBLICACIÓN --}}

                            <div class="noticias-post-body">

                                @if($destacado)

                                    <span class="noticias-post-featured">

                                        <i class="bi bi-pin-angle-fill"></i>

                                        Publicación destacada

                                    </span>

                                @endif

                                @if($titulo)

                                    <h3>{{ $titulo }}</h3>

                                @endif

                                @if($contenido)

                                    <div class="noticias-post-text">
                                        {!! nl2br(e($contenido)) !!}
                                    </div>

                                @endif

                            </div>

                            {{-- =====================================================
                                 GALERÍA DE FOTOGRAFÍAS
                            ===================================================== --}}

                            @if(count($imagenes) > 0)

                                <div class="noticias-post-gallery
                                    {{ count($imagenes) === 1 ? 'single' : '' }}
                                    {{ count($imagenes) === 2 ? 'double' : '' }}
                                    {{ count($imagenes) >= 3 ? 'multiple' : '' }}">

                                    @foreach(array_slice($imagenes, 0, 4) as $imagen)

                                        @php
                                            $imagenUrl = is_array($imagen)
                                                ? ($imagen['url'] ?? null)
                                                : $imagen;
                                        @endphp

                                        @if($imagenUrl)

                                            <div class="noticias-post-image">

                                                <img
                                                    src="{{ $imagenUrl }}"
                                                    alt="Fotografía de la publicación {{ $titulo }}"
                                                    loading="lazy">

                                                @if(
                                                    $loop->iteration === 4 &&
                                                    count($imagenes) > 4
                                                )

                                                    <span class="noticias-gallery-more">
                                                        +{{ count($imagenes) - 4 }}
                                                    </span>

                                                @endif

                                            </div>

                                        @endif

                                    @endforeach

                                </div>

                            @elseif($modoDemostracion)

                                <div class="noticias-post-media-placeholder">

                                    <i class="bi bi-images"></i>

                                    <strong>
                                        Fotografías de la publicación
                                    </strong>

                                    <span>
                                        Aquí se mostrarán las imágenes
                                        autorizadas que Dirección
                                        publique desde ATLAS.
                                    </span>

                                </div>

                            @endif

                            {{-- =====================================================
                                 VIDEO
                            ===================================================== --}}

                            @if($video)

                                <div class="noticias-post-video">

                                    <video controls preload="metadata">

                                        <source src="{{ $video }}">

                                        Tu navegador no admite
                                        la reproducción de video.

                                    </video>

                                </div>

                            @endif

                            {{-- =====================================================
                                 ACCIONES DE PUBLICACIÓN
                            ===================================================== --}}

                            @if($enlacePublicacion)

                                <div class="noticias-post-footer">

                                    <div class="noticias-post-actions">

                                        <button
                                            type="button"
                                            class="noticias-action-button"
                                            data-copy-url="{{ $enlacePublicacion }}">

                                            <i class="bi bi-link-45deg"></i>

                                            Copiar enlace

                                        </button>

                                        <button
                                            type="button"
                                            class="noticias-action-button"
                                            data-share-url="{{ $enlacePublicacion }}"
                                            data-share-title="{{ $titulo }}">

                                            <i class="bi bi-share"></i>

                                            Compartir

                                        </button>

                                    </div>

                                </div>

                            @endif

                        </article>

                    @empty

                        <div class="noticias-empty-state">

                            <i class="bi bi-newspaper"></i>

                            <h3>
                                Aún no hay publicaciones
                            </h3>

                            <p>
                                Las actividades, noticias y eventos
                                aparecerán aquí cuando sean
                                publicados oficialmente.
                            </p>

                        </div>

                    @endforelse

                </div>

                {{-- SIN RESULTADOS DEL FILTRO --}}

                <div
                    class="noticias-filter-empty"
                    id="noticiasFilterEmpty"
                    hidden>

                    <i class="bi bi-search"></i>

                    <h3>
                        No hay publicaciones en esta categoría
                    </h3>

                    <p>
                        Selecciona otra categoría para
                        consultar las publicaciones disponibles.
                    </p>

                </div>

                {{-- PAGINACIÓN FUTURA DE ATLAS --}}

                @if(
                    !$modoDemostracion &&
                    $publicaciones instanceof \Illuminate\Contracts\Pagination\Paginator
                )

                    <div class="noticias-pagination">
                        {{ $publicaciones->links() }}
                    </div>

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

                item.setAttribute(
                    'aria-pressed',
                    activo ? 'true' : 'false'
                );

            });

            publicaciones.forEach(function (publicacion) {

                const mostrar =
                    categoria === 'Todas' ||
                    publicacion.dataset.postCategoria === categoria;

                publicacion.hidden = !mostrar;

                if (mostrar) {
                    visibles++;
                }

            });

            if (sinResultados) {
                sinResultados.hidden = visibles > 0;
            }

        });

    });

    document.querySelectorAll('[data-copy-url]').forEach(function (boton) {

        boton.addEventListener('click', async function () {

            const url = boton.dataset.copyUrl;

            if (!url) return;

            try {

                await navigator.clipboard.writeText(url);

                const contenidoOriginal = boton.innerHTML;

                boton.innerHTML =
                    '<i class="bi bi-check2"></i> Enlace copiado';

                setTimeout(function () {
                    boton.innerHTML = contenidoOriginal;
                }, 2000);

            } catch (error) {
                console.error('No se pudo copiar el enlace.', error);
            }

        });

    });

    document.querySelectorAll('[data-share-url]').forEach(function (boton) {

        boton.addEventListener('click', async function () {

            const url = boton.dataset.shareUrl;
            const titulo = boton.dataset.shareTitle;

            if (!url) return;

            if (navigator.share) {

                try {

                    await navigator.share({
                        title: titulo,
                        url: url
                    });

                } catch (error) {

                    if (error.name !== 'AbortError') {
                        console.error('No se pudo compartir.', error);
                    }

                }

            } else {

                window.open(
                    'https://wa.me/?text=' +
                    encodeURIComponent(titulo + ' ' + url),
                    '_blank',
                    'noopener,noreferrer'
                );

            }

        });

    });

});
</script>
@endpush
