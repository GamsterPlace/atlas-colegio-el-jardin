
@extends('layouts.institucional')

@section('title', 'Contacto')

@section('description', 'Comunícate con el Colegio Mixto El Jardín. Consulta nuestros canales de atención, ubicación y formulario de contacto.')

@push('styles')
    @vite('resources/css/contacto.css')
@endpush

@php
    /*
    |--------------------------------------------------------------------------
    | CONFIGURACIÓN INSTITUCIONAL
    |--------------------------------------------------------------------------
    | Sustituir los valores null cuando Dirección proporcione
    | los datos oficiales.
    |
    | No utilizar información de contacto de GamsterPlace.
    |--------------------------------------------------------------------------
    */

    $contacto = [
        'telefono' => null,
        'telefono_visible' => null,
        'correo' => null,
        'whatsapp' => null,
        'direccion' => null,
        'municipio' => 'San Raymundo',
        'departamento' => 'Guatemala',
        'horario' => null,
        'mapa_embed' => null,
    ];

    $redesSociales = [
        [
            'nombre' => 'Facebook',
            'icono' => 'bi-facebook',
            'url' => null,
            'clase' => 'facebook',
        ],
        [
            'nombre' => 'Instagram',
            'icono' => 'bi-instagram',
            'url' => null,
            'clase' => 'instagram',
        ],
        [
            'nombre' => 'TikTok',
            'icono' => 'bi-tiktok',
            'url' => null,
            'clase' => 'tiktok',
        ],
        [
            'nombre' => 'YouTube',
            'icono' => 'bi-youtube',
            'url' => null,
            'clase' => 'youtube',
        ],
    ];

    /*
    |--------------------------------------------------------------------------
    | FORMULARIO DE CONTACTO
    |--------------------------------------------------------------------------
    | Cuando se implemente el receptor de consultas de ATLAS,
    | deberá registrarse la ruta:
    |
    | POST /contacto
    | Nombre: institucional.contacto.enviar
    |
    | El formulario no enviará información hasta que exista
    | esa ruta y su controlador correspondiente.
    |--------------------------------------------------------------------------
    */

    $formularioDisponible = \Illuminate\Support\Facades\Route::has(
        'institucional.contacto.enviar'
    );

    $whatsappUrl = null;

    if ($contacto['whatsapp']) {
        $numeroWhatsApp = preg_replace('/\D+/', '', $contacto['whatsapp']);

        $mensajeWhatsApp = 'Hola, deseo solicitar información sobre el Colegio Mixto El Jardín.';

        $whatsappUrl = 'https://wa.me/' .
            $numeroWhatsApp .
            '?text=' .
            rawurlencode($mensajeWhatsApp);
    }
@endphp

@section('content')

{{-- ==========================================================
     1. ENCABEZADO
========================================================== --}}

<section class="contacto-hero">

    <div class="container">

        <div class="contacto-hero-content">

            <span class="contacto-eyebrow">
                ESTAMOS PARA ATENDERTE
            </span>

            <h1>
                Contáctanos
            </h1>

            <p>
                Estamos a tu disposición para brindarte
                información sobre nuestra institución,
                resolver tus consultas y orientarte
                en lo que necesites.
            </p>

            <nav aria-label="Ruta de navegación">

                <ol class="contacto-breadcrumb">

                    <li>
                        <a href="{{ route('institucional.inicio') }}">
                            Inicio
                        </a>
                    </li>

                    <li aria-current="page">
                        Contacto
                    </li>

                </ol>

            </nav>

        </div>

    </div>

</section>

{{-- ==========================================================
     2. PRESENTACIÓN
========================================================== --}}

<section class="contacto-introduccion section-padding">

    <div class="container">

        <div class="row align-items-end g-4">

            <div class="col-lg-8">

                <span class="section-eyebrow">
                    COMUNICACIÓN INSTITUCIONAL
                </span>

                <h2 class="section-heading">
                    Estamos cerca de ti
                </h2>

                <p class="section-description">
                    Selecciona el canal de comunicación
                    que prefieras o envíanos una consulta
                    mediante nuestro formulario.
                    Nuestro equipo podrá atender tu solicitud
                    a través de los medios oficiales
                    de la institución.
                </p>

            </div>

            <div class="col-lg-4">

                <div class="contacto-intro-detail">

                    <i class="bi bi-chat-heart"></i>

                    <span>
                        Tu comunicación es importante
                        para nosotros.
                    </span>

                </div>

            </div>

        </div>

    </div>

</section>

{{-- ==========================================================
     3. CANALES DE CONTACTO
========================================================== --}}

<section class="contacto-canales">

    <div class="container">

        <div class="row g-4">

            {{-- TELÉFONO --}}

            <div class="col-md-6 col-xl-3">

                <article class="contacto-canal-card h-100">

                    <div class="contacto-canal-icon telefono">

                        <i class="bi bi-telephone"></i>

                    </div>

                    <h3>
                        Teléfono
                    </h3>

                    <p>
                        Comunícate directamente
                        con nuestra institución.
                    </p>

                    @if($contacto['telefono'])

                        <a href="tel:{{ $contacto['telefono'] }}">
                            {{ $contacto['telefono_visible'] ?: $contacto['telefono'] }}
                        </a>

                    @else

                        <span class="contacto-pendiente">
                            Número pendiente de confirmar
                        </span>

                    @endif

                </article>

            </div>

            {{-- CORREO --}}

            <div class="col-md-6 col-xl-3">

                <article class="contacto-canal-card h-100">

                    <div class="contacto-canal-icon correo">

                        <i class="bi bi-envelope"></i>

                    </div>

                    <h3>
                        Correo electrónico
                    </h3>

                    <p>
                        Envíanos tus consultas
                        por correo institucional.
                    </p>

                    @if($contacto['correo'])

                        <a href="mailto:{{ $contacto['correo'] }}">
                            {{ $contacto['correo'] }}
                        </a>

                    @else

                        <span class="contacto-pendiente">
                            Correo pendiente de confirmar
                        </span>

                    @endif

                </article>

            </div>

            {{-- WHATSAPP --}}

            <div class="col-md-6 col-xl-3">

                <article class="contacto-canal-card h-100">

                    <div class="contacto-canal-icon whatsapp">

                        <i class="bi bi-whatsapp"></i>

                    </div>

                    <h3>
                        WhatsApp
                    </h3>

                    <p>
                        Escríbenos para solicitar
                        información de manera sencilla.
                    </p>

                    @if($whatsappUrl)

                        <a
                            href="{{ $whatsappUrl }}"
                            target="_blank"
                            rel="noopener noreferrer">

                            Iniciar conversación

                            <i class="bi bi-arrow-up-right"></i>

                        </a>

                    @else

                        <span class="contacto-pendiente">
                            WhatsApp pendiente de confirmar
                        </span>

                    @endif

                </article>

            </div>

            {{-- UBICACIÓN --}}

            <div class="col-md-6 col-xl-3">

                <article class="contacto-canal-card h-100">

                    <div class="contacto-canal-icon ubicacion">

                        <i class="bi bi-geo-alt"></i>

                    </div>

                    <h3>
                        Ubicación
                    </h3>

                    <p>
                        Encuéntranos en
                        nuestra comunidad.
                    </p>

                    <span class="contacto-canal-location">

                        {{ $contacto['municipio'] }},
                        {{ $contacto['departamento'] }}

                    </span>

                </article>

            </div>

        </div>

    </div>

</section>

{{-- ==========================================================
     4. WHATSAPP DESTACADO
========================================================== --}}

<section class="contacto-whatsapp section-padding">

    <div class="container">

        <div class="contacto-whatsapp-banner">

            <div class="contacto-whatsapp-decoration"></div>

            <div class="contacto-whatsapp-info">

                <div class="contacto-whatsapp-icon">

                    <i class="bi bi-whatsapp"></i>

                </div>

                <div>

                    <span>
                        CONTACTO DIRECTO
                    </span>

                    <h2>
                        ¿Prefieres escribirnos por WhatsApp?
                    </h2>

                    <p>
                        Utiliza nuestro canal de mensajería
                        para solicitar información
                        sobre la institución.
                    </p>

                </div>

            </div>

            <div class="contacto-whatsapp-action">

                @if($whatsappUrl)

                    <a
                        href="{{ $whatsappUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="contacto-whatsapp-btn">

                        <i class="bi bi-whatsapp"></i>

                        Escribir por WhatsApp

                        <i class="bi bi-arrow-up-right"></i>

                    </a>

                @else

                    <span class="contacto-whatsapp-unavailable">

                        <i class="bi bi-clock"></i>

                        Canal pendiente de habilitar

                    </span>

                @endif

            </div>

        </div>

    </div>

</section>

{{-- ==========================================================
     5. FORMULARIO Y REDES SOCIALES
========================================================== --}}

<section class="contacto-formulario-section section-padding">

    <div class="container">

        <div class="row g-5">

            {{-- COLUMNA DEL FORMULARIO --}}

            <div class="col-lg-7">

                <div class="contacto-form-card">

                    <div class="contacto-form-heading">

                        <span class="section-eyebrow">
                            ENVÍANOS TU CONSULTA
                        </span>

                        <h2>
                            ¿En qué podemos ayudarte?
                        </h2>

                        <p>
                            Completa el siguiente formulario.
                            Cuando esté conectado con ATLAS,
                            tu mensaje será enviado al módulo
                            de Dirección para su atención.
                        </p>

                    </div>

                    @if(session('contacto_exito'))

                        <div
                            class="contacto-alert success"
                            role="status">

                            <i class="bi bi-check-circle-fill"></i>

                            <span>
                                {{ session('contacto_exito') }}
                            </span>

                        </div>

                    @endif

                    @if($errors->any())

                        <div
                            class="contacto-alert error"
                            role="alert">

                            <i class="bi bi-exclamation-triangle-fill"></i>

                            <div>

                                <strong>
                                    Revisa la información ingresada.
                                </strong>

                                <ul>

                                    @foreach($errors->all() as $error)

                                        <li>{{ $error }}</li>

                                    @endforeach

                                </ul>

                            </div>

                        </div>

                    @endif

                    @if(!$formularioDisponible)

                        <div class="contacto-demo-notice">

                            <i class="bi bi-info-circle-fill"></i>

                            <span>
                                Formulario en preparación.
                                El envío de mensajes se habilitará
                                al integrar esta página con el
                                módulo de Dirección de ATLAS.
                            </span>

                        </div>

                    @endif

                    <form
                        id="contactoForm"
                        method="POST"
                        @if($formularioDisponible)
                            action="{{ route('institucional.contacto.enviar') }}"
                        @endif
                        class="contacto-form"
                        novalidate>

                        @csrf

                        <div class="row g-4">

                            <div class="col-md-6">

                                <label for="contacto_nombre">
                                    Nombre completo
                                    <span>*</span>
                                </label>

                                <div class="contacto-input-wrap">

                                    <i class="bi bi-person"></i>

                                    <input
                                        type="text"
                                        id="contacto_nombre"
                                        name="nombre"
                                        class="contacto-input @error('nombre') is-invalid @enderror"
                                        value="{{ old('nombre') }}"
                                        placeholder="Escribe tu nombre"
                                        maxlength="150"
                                        autocomplete="name"
                                        required
                                        @disabled(!$formularioDisponible)>

                                </div>

                                @error('nombre')
                                    <small class="contacto-field-error">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>

                            <div class="col-md-6">

                                <label for="contacto_correo">
                                    Correo electrónico
                                    <span>*</span>
                                </label>

                                <div class="contacto-input-wrap">

                                    <i class="bi bi-envelope"></i>

                                    <input
                                        type="email"
                                        id="contacto_correo"
                                        name="correo"
                                        class="contacto-input @error('correo') is-invalid @enderror"
                                        value="{{ old('correo') }}"
                                        placeholder="correo@ejemplo.com"
                                        maxlength="180"
                                        autocomplete="email"
                                        required
                                        @disabled(!$formularioDisponible)>

                                </div>

                                @error('correo')
                                    <small class="contacto-field-error">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>

                            <div class="col-md-6">

                                <label for="contacto_telefono">
                                    Teléfono
                                    <span class="contacto-optional">
                                        (Opcional)
                                    </span>
                                </label>

                                <div class="contacto-input-wrap">

                                    <i class="bi bi-telephone"></i>

                                    <input
                                        type="tel"
                                        id="contacto_telefono"
                                        name="telefono"
                                        class="contacto-input @error('telefono') is-invalid @enderror"
                                        value="{{ old('telefono') }}"
                                        placeholder="Número de contacto"
                                        maxlength="25"
                                        autocomplete="tel"
                                        @disabled(!$formularioDisponible)>

                                </div>

                                @error('telefono')
                                    <small class="contacto-field-error">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>

                            <div class="col-md-6">

                                <label for="contacto_asunto">
                                    Asunto
                                    <span>*</span>
                                </label>

                                <div class="contacto-input-wrap">

                                    <i class="bi bi-chat-left-text"></i>

                                    <select
                                        id="contacto_asunto"
                                        name="asunto"
                                        class="contacto-input contacto-select @error('asunto') is-invalid @enderror"
                                        required
                                        @disabled(!$formularioDisponible)>

                                        <option value="">
                                            Selecciona un asunto
                                        </option>

                                        <option
                                            value="informacion_general"
                                            @selected(old('asunto') === 'informacion_general')>

                                            Información general

                                        </option>

                                        <option
                                            value="oferta_academica"
                                            @selected(old('asunto') === 'oferta_academica')>

                                            Oferta académica

                                        </option>

                                        <option
                                            value="inscripciones"
                                            @selected(old('asunto') === 'inscripciones')>

                                            Inscripciones

                                        </option>

                                        <option
                                            value="visita_institucional"
                                            @selected(old('asunto') === 'visita_institucional')>

                                            Visita a la institución

                                        </option>

                                        <option
                                            value="otro"
                                            @selected(old('asunto') === 'otro')>

                                            Otra consulta

                                        </option>

                                    </select>

                                </div>

                                @error('asunto')
                                    <small class="contacto-field-error">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>

                            <div class="col-12">

                                <label for="contacto_mensaje">
                                    Mensaje
                                    <span>*</span>
                                </label>

                                <div class="contacto-textarea-wrap">

                                    <textarea
                                        id="contacto_mensaje"
                                        name="mensaje"
                                        class="contacto-textarea @error('mensaje') is-invalid @enderror"
                                        rows="6"
                                        maxlength="3000"
                                        placeholder="Escribe aquí tu consulta..."
                                        required
                                        @disabled(!$formularioDisponible)>{{ old('mensaje') }}</textarea>

                                </div>

                                @error('mensaje')
                                    <small class="contacto-field-error">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>

                            <div class="col-12">

                                <label class="contacto-privacy">

                                    <input
                                        type="checkbox"
                                        name="acepta_privacidad"
                                        value="1"
                                        required
                                        @checked(old('acepta_privacidad'))
                                        @disabled(!$formularioDisponible)>

                                    <span>
                                        Acepto que los datos proporcionados
                                        sean utilizados por la institución
                                        para atender esta consulta.
                                        <strong>*</strong>
                                    </span>

                                </label>

                                @error('acepta_privacidad')
                                    <small class="contacto-field-error">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>

                            <div class="col-12">

                                <div class="contacto-form-bottom">

                                    <button
                                        type="submit"
                                        class="contacto-submit-btn"
                                        @disabled(!$formularioDisponible)>

                                        <i class="bi bi-send"></i>

                                        Enviar mensaje

                                        <i class="bi bi-arrow-right"></i>

                                    </button>

                                    <span class="contacto-required-note">
                                        * Campos obligatorios
                                    </span>

                                </div>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

            {{-- COLUMNA INFORMATIVA --}}

            <div class="col-lg-5">

                <div class="contacto-side-content">

                    {{-- REDES SOCIALES --}}

                    <div class="contacto-social-card">

                        <div class="contacto-side-icon">

                            <i class="bi bi-share"></i>

                        </div>

                        <h3>
                            Síguenos en redes sociales
                        </h3>

                        <p>
                            Conoce nuestras publicaciones,
                            actividades y novedades
                            a través de nuestros
                            canales oficiales.
                        </p>

                        <div class="contacto-social-list">

                            @foreach($redesSociales as $red)

                                @if($red['url'])

                                    <a
                                        href="{{ $red['url'] }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="contacto-social-link {{ $red['clase'] }}"
                                        aria-label="Visitar {{ $red['nombre'] }}">

                                        <i class="bi {{ $red['icono'] }}"></i>

                                        <span>
                                            {{ $red['nombre'] }}
                                        </span>

                                        <i class="bi bi-arrow-up-right"></i>

                                    </a>

                                @else

                                    <div
                                        class="contacto-social-link unavailable"
                                        aria-label="{{ $red['nombre'] }} pendiente de confirmar">

                                        <i class="bi {{ $red['icono'] }}"></i>

                                        <span>
                                            {{ $red['nombre'] }}
                                        </span>

                                        <small>
                                            Próximamente
                                        </small>

                                    </div>

                                @endif

                            @endforeach

                        </div>

                    </div>

                    {{-- HORARIOS --}}

                    <div class="contacto-horario-card">

                        <div class="contacto-side-icon">

                            <i class="bi bi-clock-history"></i>

                        </div>

                        <h3>
                            Horario de atención
                        </h3>

                        <p>
                            Consulta nuestros horarios
                            de atención institucional.
                        </p>

                        @if($contacto['horario'])

                            <div class="contacto-horario-value">
                                {{ $contacto['horario'] }}
                            </div>

                        @else

                            <div class="contacto-horario-pendiente">

                                <i class="bi bi-info-circle"></i>

                                Horario pendiente de confirmar

                            </div>

                        @endif

                    </div>

                    {{-- MENSAJE INSTITUCIONAL --}}

                    <div class="contacto-atencion-card">

                        <i class="bi bi-chat-square-heart"></i>

                        <h3>
                            Tu consulta será importante
                        </h3>

                        <p>
                            Nuestro objetivo es facilitar
                            la comunicación con las familias
                            y las personas interesadas
                            en conocer la institución.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

{{-- ==========================================================
     6. UBICACIÓN Y MAPA
========================================================== --}}

<section class="contacto-ubicacion section-padding">

    <div class="container">

        <div class="section-center">

            <span class="section-eyebrow">
                ENCUÉNTRANOS
            </span>

            <h2 class="section-heading">
                Nuestra ubicación
            </h2>

            <p class="section-description">
                Estamos ubicados en San Raymundo,
                Guatemala. Aquí podrás consultar
                nuestra dirección y ubicación exacta.
            </p>

        </div>

        <div class="contacto-location-layout">

            <div class="contacto-location-info">

                <div class="contacto-location-icon">

                    <i class="bi bi-geo-alt-fill"></i>

                </div>

                <span class="contacto-location-eyebrow">
                    DIRECCIÓN INSTITUCIONAL
                </span>

                <h3>
                    Colegio Mixto El Jardín
                </h3>

                <p class="contacto-location-city">

                    <i class="bi bi-pin-map"></i>

                    {{ $contacto['municipio'] }},
                    {{ $contacto['departamento'] }}

                </p>

                <div class="contacto-address-box">

                    <strong>
                        Dirección
                    </strong>

                    @if($contacto['direccion'])

                        <p>
                            {{ $contacto['direccion'] }}
                        </p>

                    @else

                        <p>
                            Dirección exacta pendiente
                            de confirmar.
                        </p>

                    @endif

                </div>

                @if($contacto['direccion'])

                    <a
                        href="https://www.google.com/maps/search/?api=1&query={{ urlencode($contacto['direccion'] . ', ' . $contacto['municipio'] . ', ' . $contacto['departamento']) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="contacto-map-button">

                        <i class="bi bi-sign-turn-right"></i>

                        Cómo llegar

                        <i class="bi bi-arrow-up-right"></i>

                    </a>

                @endif

            </div>

            <div class="contacto-map-container">

                @if($contacto['mapa_embed'])

                    <iframe
                        src="{{ $contacto['mapa_embed'] }}"
                        title="Ubicación del Colegio Mixto El Jardín"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen>
                    </iframe>

                @else

                    <div class="contacto-map-placeholder">

                        <div class="contacto-map-placeholder-icon">

                            <i class="bi bi-map"></i>

                        </div>

                        <h3>
                            Mapa de ubicación
                        </h3>

                        <p>
                            Aquí se mostrará el mapa
                            con la ubicación exacta
                            del Colegio Mixto El Jardín.
                        </p>

                        <span>
                            Ubicación pendiente de configurar
                        </span>

                    </div>

                @endif

            </div>

        </div>

    </div>

</section>

@endsection
