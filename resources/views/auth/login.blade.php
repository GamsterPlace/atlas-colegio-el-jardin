</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#14233F">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Iniciar sesión | ATLAS</title>

    <link rel="icon" type="image/png" href="{{ asset('images/atlas/login.png') }}">

    @vite('resources/css/login.css')
</head>
<body>

<main class="login-shell">

    <div class="login-card">

        <section class="institution-panel" aria-label="Colegio Mixto El Jardín">

            <div class="institution-top">
                <a
                    href="{{ route('institucional.inicio') }}"
                    class="back-link"
                >
                    <svg viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round"
                         aria-hidden="true">
                        <path d="M19 12H5m7 7-7-7 7-7"/>
                    </svg>

                    Volver al sitio web
                </a>
            </div>

            <div class="institution-content">

                <div class="institution-emblem">
                    <img
                        src="{{ asset('images/institucional/logo-colegio.png') }}"
                        alt="Escudo del Colegio Mixto El Jardín"
                    >
                </div>

                <span class="institution-label">
                    PORTAL INSTITUCIONAL
                </span>

                <h1>
                    Colegio Mixto<br>
                    El Jardín
                </h1>

                <p>San Raymundo, Guatemala</p>

            </div>

            <div class="institution-footer">
                <span class="institution-footer-mark"></span>
                Sistema Integral de Control Escolar
            </div>

        </section>

        <section class="form-panel" aria-labelledby="login-heading">

            <div class="form-panel-inner">

                <div class="form-heading">

                    <img
                        src="{{ asset('images/atlas/login.png') }}"
                        alt="Logotipo de ATLAS"
                        class="form-atlas-logo"
                    >

                    <h2 id="login-heading">Iniciar sesión</h2>

                </div>

                <form
                    class="access-form"
                    method="POST"
                    action="{{ route('login.submit') }}"
                    autocomplete="on"
                >
                    @csrf

                    @if ($errors->any())
                        <div
                            class="login-error"
                            role="alert"
                            style="color:#a63838; margin-bottom:16px; font-size:14px;"
                        >
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="form-field">
                        <label for="username">Usuario</label>

                        <div class="field-control">
                            <svg viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.7"
                                 stroke-linecap="round" stroke-linejoin="round"
                                 aria-hidden="true">
                                <circle cx="12" cy="8" r="4"/>
                                <path d="M4.5 20a7.5 7.5 0 0 1 15 0"/>
                            </svg>

                            <input
                                type="text"
                                id="username"
                                name="nombre_usuario"
                                value="{{ old('nombre_usuario') }}"
                                placeholder="Nombre de usuario"
                                autocomplete="username"
                                maxlength="100"
                                required
                                autofocus
                            >
                        </div>
                    </div>

                    <div class="form-field">
                        <label for="password">Contraseña</label>

                        <div class="field-control">
                            <svg viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.7"
                                 stroke-linecap="round" stroke-linejoin="round"
                                 aria-hidden="true">
                                <rect x="5" y="10" width="14" height="11" rx="2"/>
                                <path d="M8 10V7a4 4 0 0 1 8 0v3"/>
                            </svg>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Ingresa tu contraseña"
                                autocomplete="current-password"
                                required
                            >

                            <button
                                type="button"
                                class="password-toggle"
                                id="passwordToggle"
                                aria-label="Mostrar contraseña"
                                aria-pressed="false"
                            >
                                <svg viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     aria-hidden="true">
                                    <path d="M2 12s3.5-6 10-6 10 6-3.5 6-10 6S2 12 2 12Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-recovery">
                        <button type="button" id="recoveryTrigger">
                            ¿Olvidaste tu contraseña?
                        </button>
                    </div>

                    <button
                        type="submit"
                        class="submit-button"
                        id="loginSubmit"
                    >
                        <span>Ingresar</span>

                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.9"
                             stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true">
                            <path d="M5 12h14m-6-6 6 6-6 6"/>
                        </svg>
                    </button>

                </form>

                <div class="form-security">
                    <svg viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.7"
                         stroke-linecap="round" stroke-linejoin="round"
                         aria-hidden="true">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>

                    Acceso reservado a usuarios autorizados
                </div>

            </div>

            <footer class="form-footer">
                <span>ATLAS</span>
                <span>Desarrollado por <strong>GamsterPlace</strong></span>
            </footer>

        </section>

    </div>

</main>

<dialog id="recoveryDialog" class="recovery-dialog">
    <div class="recovery-body">

        <button
            type="button"
            class="recovery-close"
            id="recoveryClose"
            aria-label="Cerrar"
        >
            &times;
        </button>

        <div class="recovery-symbol">
            <svg viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.7"
                 stroke-linecap="round" stroke-linejoin="round"
                 aria-hidden="true">
                <rect x="5" y="10" width="14" height="11" rx="2"/>
                <path d="M8 10V7a4 4 0 0 1 8 0v3"/>
                <path d="M12 14v3"/>
            </svg>
        </div>

        <h2>Restablecer contraseña</h2>

        <p>
            Esta opción permitirá enviar una solicitud a Administración
            de ATLAS para verificar tu identidad y autorizar
            el restablecimiento de tu contraseña.
        </p>

        <p class="recovery-status">
            Esta función estará disponible cuando se habilite
            el módulo de Administración.
        </p>

        <button
            type="button"
            class="recovery-confirm"
            id="recoveryConfirm"
        >
            Entendido
        </button>

    </div>
</dialog>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const password = document.getElementById('password');
    const toggle = document.getElementById('passwordToggle');
    const dialog = document.getElementById('recoveryDialog');
    const form = document.querySelector('.access-form');
    const submit = document.getElementById('loginSubmit');

    toggle.addEventListener('click', () => {
        const visible = password.type === 'text';

        password.type = visible ? 'password' : 'text';

        toggle.setAttribute(
            'aria-label',
            visible ? 'Mostrar contraseña' : 'Ocultar contraseña'
        );

        toggle.setAttribute('aria-pressed', String(!visible));
    });

    form.addEventListener('submit', () => {
        if (!form.checkValidity()) {
            return;
        }

        submit.disabled = true;
        submit.querySelector('span').textContent = 'Ingresando...';
    });

    window.addEventListener('pageshow', () => {
        submit.disabled = false;
        submit.querySelector('span').textContent = 'Ingresar';
    });

    document.getElementById('recoveryTrigger').addEventListener('click', () => {
        dialog.showModal();
    });

    document.getElementById('recoveryClose').addEventListener('click', () => {
        dialog.close();
    });

    document.getElementById('recoveryConfirm').addEventListener('click', () => {
        dialog.close();
    });

    dialog.addEventListener('click', (event) => {
        if (event.target === dialog) {
            dialog.close();
        }
    });
});
</script>

</body>
</html>
