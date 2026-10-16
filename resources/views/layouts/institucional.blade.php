
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="theme-color" content="#14233F">

    <meta name="description"
          content="@yield('description', 'Colegio Mixto El Jardín, San Raymundo. Conoce nuestra institución, oferta educativa, actividades y comunidad escolar.')">

    <title>@yield('title', 'Inicio') | Colegio Mixto El Jardín</title>

    <link rel="icon"
          type="image/png"
          href="{{ asset('images/institucional/logo-colegio.png') }}">

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    @stack('styles')
</head>

<body class="institutional-body">

    <a href="#contenido-principal" class="skip-link">
        Saltar al contenido principal
    </a>

    <div class="institutional-topbar">
        <div class="container">
            <div class="topbar-content">

                <div class="topbar-location">
                    <i class="bi bi-geo-alt-fill"></i>
                    <span>San Raymundo, Guatemala</span>
                </div>

                <div class="topbar-message">
                    <i class="bi bi-mortarboard-fill"></i>
                    <span>Colegio Mixto El Jardín</span>
                </div>

            </div>
        </div>
    </div>

    <header class="institutional-header sticky-top">

        <nav class="navbar navbar-expand-lg institutional-navbar"
             aria-label="Navegación institucional">

            <div class="container">

                <a class="navbar-brand institutional-brand"
                   href="{{ route('institucional.inicio') }}">

                    <img
                        src="{{ asset('images/institucional/logo-colegio.png') }}"
                        alt="Logotipo Colegio Mixto El Jardín"
                        class="institutional-logo"
                        width="66"
                        height="66"
                    >

                    <span class="institutional-brand-text">
                        <strong>COLEGIO MIXTO EL JARDÍN</strong>
                        <small>SAN RAYMUNDO</small>
                    </span>

                </a>

                <button
                    class="navbar-toggler institutional-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#institutionalNavigation"
                    aria-controls="institutionalNavigation"
                    aria-expanded="false"
                    aria-label="Mostrar u ocultar menú">

                    <span class="navbar-toggler-icon"></span>

                </button>

                <div class="collapse navbar-collapse"
                     id="institutionalNavigation">

                    <ul class="navbar-nav ms-auto align-items-lg-center institutional-menu">

                        <li class="nav-item">
                            <a
                                class="nav-link {{ request()->routeIs('institucional.inicio') ? 'active' : '' }}"
                                href="{{ route('institucional.inicio') }}"
                                @if(request()->routeIs('institucional.inicio'))
                                    aria-current="page"
                                @endif
                            >
                                Inicio
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link {{ request()->routeIs('institucional.nosotros') ? 'active' : '' }}"
                                href="{{ route('institucional.nosotros') }}"
                                @if(request()->routeIs('institucional.nosotros'))
                                    aria-current="page"
                                @endif
                            >
                                Nosotros
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link {{ request()->routeIs('institucional.oferta-academica') ? 'active' : '' }}"
                                href="{{ route('institucional.oferta-academica') }}"
                                @if(request()->routeIs('institucional.oferta-academica'))
                                    aria-current="page"
                                @endif
                            >
                                Oferta académica
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link {{ request()->routeIs('institucional.vida-estudiantil') ? 'active' : '' }}"
                                href="{{ route('institucional.vida-estudiantil') }}"
                                @if(request()->routeIs('institucional.vida-estudiantil'))
                                    aria-current="page"
                                @endif
                            >
                                Vida estudiantil
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link {{ request()->routeIs('institucional.noticias') ? 'active' : '' }}"
                                href="{{ route('institucional.noticias') }}"
                                @if(request()->routeIs('institucional.noticias'))
                                    aria-current="page"
                                @endif
                            >
                                Noticias
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link {{ request()->routeIs('institucional.contacto') ? 'active' : '' }}"
                                href="{{ route('institucional.contacto') }}"
                                @if(request()->routeIs('institucional.contacto'))
                                    aria-current="page"
                                @endif
                            >
                                Contacto
                            </a>
                        </li>

                        <li class="nav-item institutional-atlas-item">
                            <span
                                class="btn btn-atlas-access"
                                title="Plataforma ATLAS en desarrollo"
                                aria-label="Portal ATLAS, próximamente disponible">


                                    <a href="{{ route('login') }}" class="btn-portal-atlas">
                                        <img
                                            src="{{ asset('images/atlas/logo-atlas.png') }}"
                                            alt="ATLAS"
                                            class="btn-portal-atlas-logo"
                                        >

                                        <span>Portal ATLAS</span>

                                        <i class="bi bi-arrow-up-right btn-portal-atlas-arrow"></i>
                                    </a>


                            </span>
                        </li>

                    </ul>

                </div>

            </div>

        </nav>

    </header>

    <main id="contenido-principal" tabindex="-1">
        @yield('content')
    </main>

    <footer class="institutional-footer">

        <div class="container">

            <div class="row gy-5 footer-main">

                <div class="col-lg-4 col-md-6">

                    <div class="footer-brand">

                        <img
                            src="{{ asset('images/institucional/logo-colegio.png') }}"
                            alt="Colegio Mixto El Jardín"
                            class="footer-logo"
                            width="76"
                            height="76"
                            loading="lazy"
                        >

                        <div class="footer-brand-name">
                            <h4>COLEGIO MIXTO EL JARDÍN</h4>
                            <span>SAN RAYMUNDO</span>
                        </div>

                    </div>

                    <p class="footer-description">
                        Un espacio educativo orientado al aprendizaje,
                        la formación integral y el desarrollo de
                        nuestra comunidad estudiantil.
                    </p>

                </div>

                <div class="col-lg-2 col-md-6">

                    <h5 class="footer-title">Institución</h5>

                    <ul class="footer-links">

                        <li>
                            <a href="{{ route('institucional.inicio') }}">
                                Inicio
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('institucional.nosotros') }}">
                                Nosotros
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('institucional.oferta-academica') }}">
                                Oferta académica
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('institucional.vida-estudiantil') }}">
                                Vida estudiantil
                            </a>
                        </li>

                    </ul>

                </div>

                <div class="col-lg-3 col-md-6">

                    <h5 class="footer-title">Información</h5>

                    <ul class="footer-links">

                        <li>
                            <a href="{{ route('institucional.noticias') }}">
                                Noticias y actividades
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('institucional.contacto') }}">
                                Contáctanos
                            </a>
                        </li>

                    </ul>

                    <div class="footer-atlas">

                        <div class="footer-atlas-icon">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>

                        <div>
                            <strong>ATLAS</strong>
                            <span>Sistema Integral de Control Escolar</span>
                            <small>Plataforma en desarrollo</small>
                        </div>

                    </div>

                </div>

                <div class="col-lg-3 col-md-6">

                    <h5 class="footer-title">Ubicación</h5>

                    <div class="footer-contact-item">

                        <i class="bi bi-geo-alt-fill"></i>

                        <span>San Raymundo, Guatemala</span>

                    </div>

                    <p class="footer-contact-note">
                        Los canales oficiales de contacto y la dirección
                        completa estarán disponibles próximamente.
                    </p>

                    <a href="{{ route('institucional.contacto') }}"
                       class="footer-contact-link">

                        Información de contacto
                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>

            </div>

            <div class="footer-divider"></div>

            <div class="footer-bottom">

                <div class="footer-copyright">
                    &copy; {{ date('Y') }}
                    Colegio Mixto El Jardín.
                    Todos los derechos reservados.
                </div>

                <div class="footer-developer">
                    Tecnología desarrollada por
                    <button
                        type="button"
                        class="gm-footer-trigger"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#gamsterplacePanel"
                        aria-controls="gamsterplacePanel">

                        GamsterPlace
                        <i class="bi bi-arrow-up-right"></i>

                    </button>
                </div>

            </div>

        </div>

    </footer>

    <!-- BOTÓN FLOTANTE GAMSTERPLACE -->

    <button
        type="button"
        class="gm-floating-button"
        data-bs-toggle="offcanvas"
        data-bs-target="#gamsterplacePanel"
        aria-controls="gamsterplacePanel"
        aria-label="Conoce GamsterPlace y solicita una cotización"
        title="Desarrollo de sistemas personalizados">

        <img
            src="{{ asset('images/gamsterplace/logo-gm.png') }}"
            alt=""
            width="42"
            height="42"
        >

        <span class="gm-floating-text">
            <strong>GamsterPlace</strong>
            <small>¿Necesitas un sistema?</small>
        </span>

        <i class="bi bi-chevron-right gm-floating-arrow"></i>

    </button>

    <!-- PANEL COMERCIAL GAMSTERPLACE -->

    <div
        class="offcanvas offcanvas-end gm-offcanvas"
        tabindex="-1"
        id="gamsterplacePanel"
        aria-labelledby="gamsterplacePanelTitle">

        <div class="offcanvas-header gm-panel-header">

            <div class="gm-panel-identity">

                <img
                    src="{{ asset('images/gamsterplace/logo-gm.png') }}"
                    alt="Logotipo GamsterPlace"
                    class="gm-panel-logo"
                    width="64"
                    height="64"
                >

                <div>
                    <span class="gm-panel-overline">
                        SOLUCIONES TECNOLÓGICAS
                    </span>

                    <h2 id="gamsterplacePanelTitle">
                        GamsterPlace
                    </h2>

                    <span class="gm-panel-tagline">
                        Your ideas? Our work!
                    </span>
                </div>

            </div>

            <button
                type="button"
                class="btn-close btn-close-white"
                data-bs-dismiss="offcanvas"
                aria-label="Cerrar panel">
            </button>

        </div>

        <div class="offcanvas-body gm-panel-body">

            <div class="gm-panel-hero">

                <span class="gm-panel-eyebrow">
                    DESARROLLO DE SOFTWARE A MEDIDA
                </span>

                <h3>
                    ¿Necesitas un sistema para tu negocio o institución?
                </h3>

                <p>
                    En GamsterPlace transformamos tus ideas en soluciones
                    tecnológicas personalizadas. Desarrollamos sistemas
                    que se adaptan a tus necesidades y ayudan a
                    optimizar tus procesos.
                </p>

                <div class="gm-panel-highlight">
                    <i class="bi bi-stars"></i>
                    <span>
                        Tu visión, nuestra pasión. Juntos,
                        creamos lo extraordinario.
                    </span>
                </div>

            </div>

            <div class="gm-panel-cta">

                <h4>¡Hagamos realidad tu proyecto!</h4>

                <p>
                    Cuéntanos qué necesitas y solicita una
                    cotización sin compromiso.
                </p>

                <a
                    href="https://wa.me/50241226223?text={{ rawurlencode('Hola, GamsterPlace. Me interesa cotizar el desarrollo de un sistema personalizado. ¿Podrían brindarme información?') }}"
                    class="gm-whatsapp-button"
                    target="_blank"
                    rel="noopener noreferrer">

                    <i class="bi bi-whatsapp"></i>
                    Cotizar por WhatsApp
                    <i class="bi bi-arrow-up-right"></i>

                </a>

            </div>

            <div class="gm-panel-contact">

                <h4>Canales de comunicación</h4>

                <a href="tel:+50241226223"
                   class="gm-contact-row">

                    <span class="gm-contact-icon">
                        <i class="bi bi-telephone-fill"></i>
                    </span>

                    <span class="gm-contact-details">
                        <small>Teléfono</small>
                        <strong>+502 4122-6223</strong>
                    </span>

                    <i class="bi bi-arrow-up-right"></i>

                </a>

                <a href="https://www.gamsterplace.com"
                   class="gm-contact-row"
                   target="_blank"
                   rel="noopener noreferrer">

                    <span class="gm-contact-icon">
                        <i class="bi bi-globe2"></i>
                    </span>

                    <span class="gm-contact-details">
                        <small>Sitio web</small>
                        <strong>www.gamsterplace.com</strong>
                    </span>

                    <i class="bi bi-arrow-up-right"></i>

                </a>
                    <a href="mailto:gamsterplace@gmail.com"
                    class="gm-contact-row">

                        <span class="gm-contact-icon">
                            <i class="bi bi-envelope-fill"></i>
                        </span>

                        <span class="gm-contact-details">
                            <small>Correo electrónico</small>
                            <strong>gamsterplace@gmail.com</strong>
                        </span>

                        <i class="bi bi-arrow-up-right"></i>

                    </a>


            </div>

            <div class="gm-panel-signature">

                <img
                    src="{{ asset('images/gamsterplace/logo-gm.png') }}"
                    alt=""
                    width="38"
                    height="38"
                    loading="lazy"
                >

                <div>
                    <strong>GamsterPlace</strong>
                    <span>
                        Your ideas? Our work!
                    </span>
                </div>

            </div>

        </div>

    </div>

    @stack('scripts')

</body>
</html>
