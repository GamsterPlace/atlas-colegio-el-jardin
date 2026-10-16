
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#14233F">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Panel de Control') | ATLAS</title>

    <link
        rel="icon"
        type="image/png"
        href="{{ asset('images/atlas/login.png') }}"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    @vite('resources/css/atlas.css')

    @stack('styles')
</head>

<body class="atlas-app">

@php
    $atlasUser = \Illuminate\Support\Facades\Auth::user();

    $atlasRoleActive = \App\Support\AccesoRoles::rolActivo();

    $atlasAvailableRoles = $atlasUser
        ? \App\Support\AccesoRoles::disponibles($atlasUser)
        : collect();

    $atlasCanChangeRole = $atlasAvailableRoles->count() > 1;

    $atlasRole = $atlasRoleActive
        ? $atlasRoleActive->nombre
        : trim($__env->yieldContent('role', 'ATLAS'));

    $atlasModule = trim(
        $__env->yieldContent('module-title', 'Panel de control')
    );

    $atlasUserName = 'Usuario';

    if ($atlasUser) {
        $atlasUserName = $atlasUser->nombre
            ?? $atlasUser->name
            ?? $atlasUser->nombre_usuario
            ?? 'Usuario';
    }

    $atlasUserName = trim((string) $atlasUserName);

    if ($atlasUserName === '') {
        $atlasUserName = 'Usuario';
    }

    $atlasInitial = mb_strtoupper(
        mb_substr($atlasUserName, 0, 1)
    );

    $atlasDashboardUrl = trim(
        $__env->yieldContent('dashboard-url', '')
    );

    if ($atlasDashboardUrl === '') {
        $atlasDashboardUrl = route('dashboard');
    }
@endphp

<div class="atlas-layout" id="atlasLayout">

    <aside class="atlas-sidebar" id="atlasSidebar">

        <div class="atlas-sidebar-header">

            <a
                href="{{ $atlasDashboardUrl }}"
                class="atlas-sidebar-brand"
                aria-label="ATLAS - Panel principal"
            >
                <img
                    src="{{ asset('images/atlas/login.png') }}"
                    alt="ATLAS"
                    class="atlas-sidebar-logo"
                >
            </a>

            <button
                type="button"
                class="atlas-sidebar-close"
                id="atlasSidebarClose"
                aria-label="Cerrar menú"
            >
                <i class="bi bi-x-lg"></i>
            </button>

        </div>

        <div class="atlas-sidebar-institution">

            <div class="atlas-sidebar-school-logo">
                <img
                    src="{{ asset('images/institucional/logo-colegio.png') }}"
                    alt="Colegio Mixto El Jardín"
                >
            </div>

            <div class="atlas-sidebar-school-info">
                <strong>Colegio Mixto El Jardín</strong>
                <span>San Raymundo, Guatemala</span>
            </div>

        </div>

        <div class="atlas-sidebar-role">
            <span class="atlas-sidebar-role-indicator"></span>
            <span>{{ $atlasRole }}</span>
        </div>

        <nav
            class="atlas-sidebar-navigation"
            aria-label="Navegación principal"
        >
            @yield('sidebar-menu')
        </nav>

        <div class="atlas-sidebar-footer">

            <div class="atlas-sidebar-footer-content">

                <span class="atlas-sidebar-footer-label">
                    Desarrollado por
                </span>

                <div class="atlas-developer-brand">

                    <img
                        src="{{ asset('images/gamsterplace/logo-gm.png') }}"
                        alt="GamsterPlace"
                        class="atlas-developer-logo"
                    >

                    <strong>GamsterPlace</strong>

                </div>

            </div>

        </div>

    </aside>

    <div
        class="atlas-sidebar-overlay"
        id="atlasSidebarOverlay"
        aria-hidden="true"
    ></div>

    <div class="atlas-main">

        <header class="atlas-topbar">

            <div class="atlas-topbar-left">

                <button
                    type="button"
                    class="atlas-menu-toggle"
                    id="atlasMenuToggle"
                    aria-label="Abrir menú"
                    aria-controls="atlasSidebar"
                    aria-expanded="false"
                >
                    <i class="bi bi-list"></i>
                </button>

                <div class="atlas-topbar-heading">
                    <span class="atlas-topbar-section">
                        {{ $atlasRole }}
                    </span>

                    <h1>{{ $atlasModule }}</h1>
                </div>

            </div>

            <div class="atlas-topbar-right">

                <div class="atlas-topbar-date">

                    <i class="bi bi-calendar3"></i>

                    <time datetime="{{ now()->format('Y-m-d') }}">
                        {{ now()->locale('es')->translatedFormat('d \d\e F \d\e Y') }}
                    </time>

                </div>

                <div class="atlas-user-dropdown">

                    <button
                        type="button"
                        class="atlas-user-trigger"
                        id="atlasUserTrigger"
                        aria-label="Opciones de usuario"
                        aria-expanded="false"
                        aria-controls="atlasUserMenu"
                        aria-haspopup="true"
                    >

                        <span class="atlas-user-avatar">
                            {{ $atlasInitial }}
                        </span>

                        <span class="atlas-user-details">
                            <strong>{{ $atlasUserName }}</strong>
                            <small>{{ $atlasRole }}</small>
                        </span>

                        <i class="bi bi-chevron-down atlas-user-chevron"></i>

                    </button>

                    <div
                        class="atlas-user-menu"
                        id="atlasUserMenu"
                        hidden
                    >

                        <div class="atlas-user-menu-header">
                            <strong>{{ $atlasUserName }}</strong>
                            <span>{{ $atlasRole }}</span>
                        </div>

                        @hasSection('profile-url')

                            <a
                                href="@yield('profile-url')"
                                class="atlas-user-menu-item"
                            >
                                <i class="bi bi-person-circle"></i>
                                Mi perfil
                            </a>

                        @endif

                        @hasSection('password-url')

                            <a
                                href="@yield('password-url')"
                                class="atlas-user-menu-item"
                            >
                                <i class="bi bi-key"></i>
                                Cambiar contraseña
                            </a>

                        @endif

                        @if($atlasCanChangeRole)

                            <div class="atlas-user-menu-divider"></div>

                            <a
                                href="{{ route('roles.seleccionar') }}"
                                class="atlas-user-menu-item"
                            >
                                <i class="bi bi-arrow-left-right"></i>
                                Cambiar de rol
                            </a>

                        @endif

                        <div class="atlas-user-menu-divider"></div>

                        <form
                            method="POST"
                            action="{{ route('logout') }}"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="atlas-user-menu-item atlas-user-menu-logout"
                            >
                                <i class="bi bi-box-arrow-right"></i>
                                Cerrar sesión
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </header>

        <main
            class="atlas-content"
            id="mainContent"
            tabindex="-1"
        >

            @if(session('success'))

                <div
                    class="alert alert-success"
                    role="status"
                >
                    {{ session('success') }}
                </div>

            @endif

            @if(session('error'))

                <div
                    class="alert alert-danger"
                    role="alert"
                >
                    {{ session('error') }}
                </div>

            @endif

            @if($errors->any())

                <div
                    class="alert alert-danger"
                    role="alert"
                >
                    <strong>
                        Verifica la información ingresada.
                    </strong>

                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

            @endif

            @hasSection('page-heading')

                <div class="atlas-page-heading">
                    @yield('page-heading')
                </div>

            @endif

            @yield('content')

        </main>

        <footer class="atlas-main-footer">

            <span>
                &copy; {{ date('Y') }} Colegio Mixto El Jardín
            </span>

            <span>
                ATLAS · Desarrollado por
                <strong>GamsterPlace</strong>
            </span>

        </footer>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const layout = document.getElementById('atlasLayout');
    const sidebar = document.getElementById('atlasSidebar');
    const menuToggle = document.getElementById('atlasMenuToggle');
    const sidebarClose = document.getElementById('atlasSidebarClose');
    const overlay = document.getElementById('atlasSidebarOverlay');

    const userTrigger = document.getElementById('atlasUserTrigger');
    const userMenu = document.getElementById('atlasUserMenu');

    function openSidebar() {

        if (!layout || !menuToggle || !sidebar) {
            return;
        }

        closeUserMenu();

        layout.classList.add('sidebar-open');
        document.body.classList.add('atlas-menu-open');

        menuToggle.setAttribute('aria-expanded', 'true');
        menuToggle.setAttribute('aria-label', 'Cerrar menú');

    }

    function closeSidebar() {

        if (!layout || !menuToggle) {
            return;
        }

        layout.classList.remove('sidebar-open');
        document.body.classList.remove('atlas-menu-open');

        menuToggle.setAttribute('aria-expanded', 'false');
        menuToggle.setAttribute('aria-label', 'Abrir menú');

    }

    function closeUserMenu() {

        if (!userMenu || !userTrigger) {
            return;
        }

        userMenu.hidden = true;

        userTrigger.setAttribute(
            'aria-expanded',
            'false'
        );

    }

    if (menuToggle) {

        menuToggle.addEventListener('click', function () {

            if (layout.classList.contains('sidebar-open')) {
                closeSidebar();
            } else {
                openSidebar();
            }

        });

    }

    if (sidebarClose) {
        sidebarClose.addEventListener('click', closeSidebar);
    }

    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }

    if (userTrigger && userMenu) {

        userTrigger.addEventListener('click', function (event) {

            event.stopPropagation();

            const isOpen = !userMenu.hidden;

            if (!isOpen) {
                closeSidebar();
            }

            userMenu.hidden = isOpen;

            userTrigger.setAttribute(
                'aria-expanded',
                String(!isOpen)
            );

        });

    }

    document.addEventListener('click', function (event) {

        if (
            userMenu &&
            userTrigger &&
            !event.target.closest('.atlas-user-dropdown')
        ) {
            closeUserMenu();
        }

    });

    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {

            closeSidebar();
            closeUserMenu();

        }

    });

    window.addEventListener('resize', function () {

        if (window.innerWidth > 991) {
            closeSidebar();
        }

    });

    if (sidebar) {

        sidebar.querySelectorAll('a[href]').forEach(function (link) {

            link.addEventListener('click', function () {

                if (window.innerWidth <= 991) {
                    closeSidebar();
                }

            });

        });

    }

});
</script>

@stack('scripts')

</body>
</html>
