
@extends('layouts.atlas')

@section('title', 'Leer comunicado')

@section('content')
<style>
    .atlas-leer-comunicado {
        max-width: 950px;
        margin: 0 auto;
        padding: 26px 22px 45px;
        color: #183454;
    }

    .atlas-leer-comunicado * {
        box-sizing: border-box;
    }

    .atlas-leer-comunicado-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 18px;
        margin-bottom: 25px;
    }

    .atlas-leer-comunicado-eyebrow {
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: #3573b7;
        margin-bottom: 8px;
    }

    .atlas-leer-comunicado h1 {
        font-size: clamp(23px, 3vw, 30px);
        line-height: 1.3;
        font-weight: 800;
        color: #142f52;
        margin: 0 0 8px;
    }

    .atlas-leer-comunicado-subtitle {
        margin: 0;
        color: #718399;
        font-size: 14px;
        line-height: 1.65;
    }

    .atlas-leer-comunicado-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        padding: 11px 17px;
        border: 1px solid #d6e1ec;
        border-radius: 10px;
        background: #fff;
        color: #315675;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        transition: .2s ease;
    }

    .atlas-leer-comunicado-btn:hover {
        background: #edf4fc;
        color: #173e70;
    }

    .atlas-leer-comunicado-card {
        background: #fff;
        border: 1px solid #dfe8f1;
        border-radius: 17px;
        overflow: hidden;
        box-shadow: 0 5px 22px rgba(20, 50, 80, .045);
    }

    .atlas-leer-comunicado-card-top {
        padding: 26px 30px;
        border-bottom: 1px solid #e8eef5;
        background: linear-gradient(
            120deg,
            #f6faff 0%,
            #fff 100%
        );
    }

    .atlas-leer-comunicado-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #eaf3ff;
        color: #245f9a;
        border-radius: 30px;
        padding: 8px 12px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .04em;
        margin-bottom: 20px;
    }

    .atlas-leer-comunicado-title {
        font-size: clamp(22px, 3vw, 29px);
        font-weight: 800;
        line-height: 1.4;
        color: #183858;
        margin: 0 0 23px;
        overflow-wrap: anywhere;
    }

    .atlas-leer-comunicado-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 12px 25px;
        color: #6d8095;
        font-size: 12px;
    }

    .atlas-leer-comunicado-meta-item {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        line-height: 1.6;
    }

    .atlas-leer-comunicado-meta-item i {
        color: #3975ad;
        font-size: 15px;
    }

    .atlas-leer-comunicado-body {
        padding: 35px 30px 42px;
    }

    .atlas-leer-comunicado-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        font-weight: 800;
        color: #315777;
        margin-bottom: 22px;
    }

    .atlas-leer-comunicado-section-title i {
        color: #3878b4;
        font-size: 17px;
    }

    .atlas-leer-comunicado-content {
        color: #344d68;
        font-size: 15px;
        line-height: 2;
        white-space: pre-wrap;
        overflow-wrap: anywhere;
        min-height: 100px;
    }

    .atlas-leer-comunicado-divider {
        height: 1px;
        background: #e9eef5;
        margin: 35px 0 25px;
    }

    .atlas-leer-comunicado-footer {
        padding: 22px 30px;
        border-top: 1px solid #e8eef5;
        background: #fbfdff;
    }

    .atlas-leer-comunicado-status {
        display: flex;
        align-items: flex-start;
        gap: 13px;
        background: #eaf8f0;
        border: 1px solid #ccebd9;
        border-radius: 12px;
        padding: 17px 19px;
    }

    .atlas-leer-comunicado-status-icon {
        color: #208451;
        font-size: 22px;
        flex-shrink: 0;
    }

    .atlas-leer-comunicado-status-title {
        color: #196a43;
        font-size: 13px;
        font-weight: 800;
        margin-bottom: 5px;
    }

    .atlas-leer-comunicado-status-text {
        color: #4e7962;
        font-size: 12px;
        line-height: 1.7;
    }

    .atlas-leer-comunicado-note {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-top: 20px;
        padding: 16px 19px;
        border: 1px solid #d4e5f8;
        background: #edf5ff;
        border-radius: 12px;
        color: #365d84;
        font-size: 12px;
        line-height: 1.7;
    }

    .atlas-leer-comunicado-note i {
        font-size: 18px;
        margin-top: 2px;
    }

    @media (max-width: 650px) {
        .atlas-leer-comunicado {
            padding: 18px 12px 35px;
        }

        .atlas-leer-comunicado-card-top,
        .atlas-leer-comunicado-body,
        .atlas-leer-comunicado-footer {
            padding: 22px 18px;
        }

        .atlas-leer-comunicado-meta {
            flex-direction: column;
            gap: 9px;
        }

        .atlas-leer-comunicado-btn {
            width: 100%;
        }
    }
</style>

@php
    $autorPersona = $comunicado->creado_por?->persona;

    $nombreAutor = $autorPersona
        ? trim(
            ($autorPersona->nombres ?? '') . ' ' .
            ($autorPersona->apellidos ?? '')
        )
        : (
            $comunicado->creado_por?->nombre_usuario
            ?? 'Dirección institucional'
        );

    $fechaPublicacion = $comunicado->publicado_at;

    $fechaLectura = $lectura?->leido_at;
@endphp

<div class="atlas-leer-comunicado">

    <div class="atlas-leer-comunicado-header">
        <div>
            <div class="atlas-leer-comunicado-eyebrow">
                ATLAS / Comunicados / Lectura
            </div>

            <h1>Comunicado institucional</h1>

            <p class="atlas-leer-comunicado-subtitle">
                Información oficial enviada a su cuenta
                por las autoridades del colegio.
            </p>
        </div>

        <a
            href="{{ route('comunicados.recibidos') }}"
            class="atlas-leer-comunicado-btn"
        >
            <i class="bi bi-arrow-left"></i>
            Volver a mis comunicados
        </a>
    </div>

    <article class="atlas-leer-comunicado-card">

        <div class="atlas-leer-comunicado-card-top">

            <div class="atlas-leer-comunicado-label">
                <i class="bi bi-megaphone"></i>
                Comunicado institucional
            </div>

            <h2 class="atlas-leer-comunicado-title">
                {{ $comunicado->titulo }}
            </h2>

            <div class="atlas-leer-comunicado-meta">

                <span class="atlas-leer-comunicado-meta-item">
                    <i class="bi bi-person-badge"></i>
                    {{ $nombreAutor }}
                </span>

                <span class="atlas-leer-comunicado-meta-item">
                    <i class="bi bi-calendar-event"></i>

                    @if($fechaPublicacion)
                        {{ $fechaPublicacion->format('d/m/Y') }}
                    @else
                        Fecha no disponible
                    @endif
                </span>

                @if($fechaPublicacion)
                    <span class="atlas-leer-comunicado-meta-item">
                        <i class="bi bi-clock"></i>
                        {{ $fechaPublicacion->format('h:i A') }}
                    </span>
                @endif

            </div>
        </div>

        <div class="atlas-leer-comunicado-body">

            <div class="atlas-leer-comunicado-section-title">
                <i class="bi bi-file-text"></i>
                Contenido del comunicado
            </div>

            <div class="atlas-leer-comunicado-content">{{ $comunicado->contenido }}</div>

            <div class="atlas-leer-comunicado-divider"></div>

            <div class="atlas-leer-comunicado-status">

                <i class="bi bi-check-circle-fill atlas-leer-comunicado-status-icon"></i>

                <div>
                    <div class="atlas-leer-comunicado-status-title">
                        Lectura registrada correctamente
                    </div>

                    <div class="atlas-leer-comunicado-status-text">
                        @if($fechaLectura)
                            Este comunicado fue marcado como leído
                            el {{ $fechaLectura->format('d/m/Y') }}
                            a las {{ $fechaLectura->format('h:i A') }}.
                        @else
                            No se encuentra disponible la fecha
                            de lectura de este comunicado.
                        @endif
                    </div>
                </div>

            </div>

        </div>

        <div class="atlas-leer-comunicado-footer">
            <div style="display:flex;justify-content:flex-end;">
                <a
                    href="{{ route('comunicados.recibidos') }}"
                    class="atlas-leer-comunicado-btn"
                >
                    <i class="bi bi-arrow-left"></i>
                    Regresar a la bandeja
                </a>
            </div>
        </div>

    </article>

    <div class="atlas-leer-comunicado-note">
        <i class="bi bi-shield-check"></i>

        <div>
            <strong>Comunicación institucional.</strong>
            Este mensaje forma parte de los comunicados oficiales
            del Colegio Mixto El Jardín. Su recepción y lectura
            quedan registradas en ATLAS para fines de seguimiento
            institucional.
        </div>
    </div>

</div>
@endsection
