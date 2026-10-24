
@extends('layouts.direccion')

@section('title', 'Detalle del comunicado')
@section('module-title', 'Comunicados institucionales')

@section('content')
<style>
    .atlas-comunicado-detalle {
        max-width: 1200px;
        margin: 0 auto;
        padding: 26px 22px 45px;
        color: #183454;
    }

    .atlas-comunicado-detalle * {
        box-sizing: border-box;
    }

    .atlas-comunicado-detalle-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        flex-wrap: wrap;
        margin-bottom: 25px;
    }

    .atlas-comunicado-detalle-eyebrow {
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: #3573b7;
        margin-bottom: 8px;
    }

    .atlas-comunicado-detalle h1 {
        font-size: clamp(23px, 3vw, 30px);
        line-height: 1.3;
        font-weight: 800;
        color: #142f52;
        margin: 0 0 8px;
    }

    .atlas-comunicado-detalle-subtitle {
        margin: 0;
        color: #718399;
        font-size: 14px;
        line-height: 1.6;
    }

    .atlas-comunicado-detalle-btn {
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

    .atlas-comunicado-detalle-btn:hover {
        background: #edf4fc;
        color: #173e70;
    }

    .atlas-comunicado-detalle-stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 23px;
    }

    .atlas-comunicado-detalle-stat {
        background: #fff;
        border: 1px solid #e0e8f1;
        border-radius: 15px;
        padding: 21px;
        box-shadow: 0 4px 16px rgba(22, 48, 80, .035);
    }

    .atlas-comunicado-detalle-stat-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        border-radius: 11px;
        background: #edf4fd;
        color: #28649e;
        font-size: 19px;
        margin-bottom: 14px;
    }

    .atlas-comunicado-detalle-stat-label {
        font-size: 12px;
        font-weight: 700;
        color: #78899c;
        margin-bottom: 7px;
    }

    .atlas-comunicado-detalle-stat-value {
        font-size: 27px;
        line-height: 1.2;
        font-weight: 800;
        color: #193b5c;
    }

    .atlas-comunicado-detalle-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.6fr) minmax(270px, .8fr);
        gap: 20px;
        margin-bottom: 22px;
    }

    .atlas-comunicado-detalle-card {
        background: #fff;
        border: 1px solid #dfe8f1;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 18px rgba(20, 50, 80, .035);
    }

    .atlas-comunicado-detalle-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 20px 23px;
        border-bottom: 1px solid #e8eef5;
    }

    .atlas-comunicado-detalle-card-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 39px;
        height: 39px;
        border-radius: 10px;
        background: #edf4fd;
        color: #28649e;
        font-size: 18px;
        flex-shrink: 0;
    }

    .atlas-comunicado-detalle-card-title {
        margin: 0 0 4px;
        font-size: 16px;
        font-weight: 800;
        color: #1b3c60;
    }

    .atlas-comunicado-detalle-card-subtitle {
        margin: 0;
        font-size: 12px;
        color: #8090a4;
        line-height: 1.5;
    }

    .atlas-comunicado-detalle-card-body {
        padding: 24px;
    }

    .atlas-comunicado-detalle-message-title {
        font-size: 21px;
        line-height: 1.45;
        font-weight: 800;
        color: #173858;
        margin: 0 0 20px;
    }

    .atlas-comunicado-detalle-message {
        color: #405873;
        font-size: 14px;
        line-height: 1.9;
        white-space: pre-wrap;
        overflow-wrap: anywhere;
    }

    .atlas-comunicado-detalle-meta {
        display: flex;
        flex-direction: column;
        gap: 19px;
    }

    .atlas-comunicado-detalle-meta-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .atlas-comunicado-detalle-meta-item > i {
        font-size: 17px;
        color: #4478a9;
        margin-top: 2px;
    }

    .atlas-comunicado-detalle-meta-label {
        display: block;
        font-size: 11px;
        font-weight: 800;
        color: #8998a9;
        text-transform: uppercase;
        letter-spacing: .04em;
        margin-bottom: 5px;
    }

    .atlas-comunicado-detalle-meta-value {
        font-size: 13px;
        font-weight: 700;
        color: #34516e;
        line-height: 1.5;
    }

    .atlas-comunicado-detalle-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 30px;
        padding: 6px 10px;
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }

    .atlas-comunicado-detalle-badge-publicado {
        background: #e8f7ef;
        color: #197347;
    }

    .atlas-comunicado-detalle-badge-pendiente {
        background: #fff4df;
        color: #96660c;
    }

    .atlas-comunicado-detalle-badge-otro {
        background: #edf1f6;
        color: #63758a;
    }

    .atlas-comunicado-detalle-progress {
        height: 9px;
        background: #e8eff7;
        border-radius: 12px;
        overflow: hidden;
        margin-top: 15px;
    }

    .atlas-comunicado-detalle-progress-bar {
        height: 100%;
        background: #2879b8;
        border-radius: inherit;
        transition: width .3s ease;
    }

    .atlas-comunicado-detalle-table-wrap {
        overflow-x: auto;
    }

    .atlas-comunicado-detalle-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .atlas-comunicado-detalle-table th {
        background: #f7f9fc;
        padding: 15px 19px;
        color: #697d92;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .04em;
        white-space: nowrap;
    }

    .atlas-comunicado-detalle-table td {
        padding: 16px 19px;
        border-top: 1px solid #edf1f6;
        font-size: 13px;
        color: #405873;
        vertical-align: middle;
    }

    .atlas-comunicado-detalle-table tbody tr:hover {
        background: #fbfdff;
    }

    .atlas-comunicado-detalle-user {
        font-size: 13px;
        font-weight: 800;
        color: #244664;
        margin-bottom: 4px;
    }

    .atlas-comunicado-detalle-username {
        font-size: 11px;
        color: #8797a8;
    }

    .atlas-comunicado-detalle-empty {
        text-align: center;
        padding: 50px 20px;
        color: #8091a4;
        font-size: 13px;
        line-height: 1.7;
    }

    .atlas-comunicado-detalle-footer {
        padding: 18px 23px;
        border-top: 1px solid #e8eef5;
    }

    .atlas-comunicado-detalle-note {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 17px 19px;
        background: #edf5ff;
        border: 1px solid #d4e5f8;
        border-radius: 12px;
        color: #365d84;
        font-size: 12px;
        line-height: 1.7;
        margin-top: 20px;
    }

    .atlas-comunicado-detalle-note i {
        font-size: 18px;
        margin-top: 2px;
    }

    @media (max-width: 900px) {
        .atlas-comunicado-detalle-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 650px) {
        .atlas-comunicado-detalle {
            padding: 18px 12px 35px;
        }

        .atlas-comunicado-detalle-stats {
            grid-template-columns: 1fr;
            gap: 11px;
        }

        .atlas-comunicado-detalle-card-body {
            padding: 18px;
        }
    }
</style>

@php
    $totalDestinatarios = (int) $totalDestinatarios;
    $totalLecturas = (int) $totalLecturas;

    $totalPendientes = max(
        0,
        $totalDestinatarios - $totalLecturas
    );

    $porcentajeLectura = $totalDestinatarios > 0
        ? min(
            100,
            round(
                ($totalLecturas / $totalDestinatarios) * 100
            )
        )
        : 0;

    $autorPersona = $comunicado->creado_por?->persona;

    $nombreAutor = $autorPersona
        ? trim(
            ($autorPersona->nombres ?? '') . ' ' .
            ($autorPersona->apellidos ?? '')
        )
        : ($comunicado->creado_por?->nombre_usuario ?? 'No disponible');

    $estadoComunicado = strtoupper(
        (string) $comunicado->estado
    );
@endphp

<div class="atlas-comunicado-detalle">

    <div class="atlas-comunicado-detalle-header">
        <div>
            <div class="atlas-comunicado-detalle-eyebrow">
                Dirección / Comunicados / Detalle
            </div>

            <h1>Detalle del comunicado</h1>

            <p class="atlas-comunicado-detalle-subtitle">
                Consulte la información publicada y el seguimiento
                de lectura de los destinatarios.
            </p>
        </div>

        <a
            href="{{ route('direccion.comunicados.index') }}"
            class="atlas-comunicado-detalle-btn"
        >
            <i class="bi bi-arrow-left"></i>
            Volver al historial
        </a>
    </div>

    <div class="atlas-comunicado-detalle-stats">

        <div class="atlas-comunicado-detalle-stat">
            <div class="atlas-comunicado-detalle-stat-icon">
                <i class="bi bi-people"></i>
            </div>

            <div class="atlas-comunicado-detalle-stat-label">
                Destinatarios
            </div>

            <div class="atlas-comunicado-detalle-stat-value">
                {{ number_format($totalDestinatarios) }}
            </div>
        </div>

        <div class="atlas-comunicado-detalle-stat">
            <div class="atlas-comunicado-detalle-stat-icon">
                <i class="bi bi-check2-circle"></i>
            </div>

            <div class="atlas-comunicado-detalle-stat-label">
                Lecturas registradas
            </div>

            <div class="atlas-comunicado-detalle-stat-value">
                {{ number_format($totalLecturas) }}
            </div>
        </div>

        <div class="atlas-comunicado-detalle-stat">
            <div class="atlas-comunicado-detalle-stat-icon">
                <i class="bi bi-clock-history"></i>
            </div>

            <div class="atlas-comunicado-detalle-stat-label">
                Pendientes de lectura
            </div>

            <div class="atlas-comunicado-detalle-stat-value">
                {{ number_format($totalPendientes) }}
            </div>
        </div>

    </div>

    <div class="atlas-comunicado-detalle-grid">

        <div class="atlas-comunicado-detalle-card">
            <div class="atlas-comunicado-detalle-card-header">
                <div class="atlas-comunicado-detalle-card-icon">
                    <i class="bi bi-envelope-paper"></i>
                </div>

                <div>
                    <h2 class="atlas-comunicado-detalle-card-title">
                        Contenido del comunicado
                    </h2>

                    <p class="atlas-comunicado-detalle-card-subtitle">
                        Información original registrada en ATLAS.
                    </p>
                </div>
            </div>

            <div class="atlas-comunicado-detalle-card-body">
                <h3 class="atlas-comunicado-detalle-message-title">
                    {{ $comunicado->titulo }}
                </h3>

                <div class="atlas-comunicado-detalle-message">{{ $comunicado->contenido }}</div>
            </div>
        </div>

        <div class="atlas-comunicado-detalle-card">
            <div class="atlas-comunicado-detalle-card-header">
                <div class="atlas-comunicado-detalle-card-icon">
                    <i class="bi bi-info-circle"></i>
                </div>

                <div>
                    <h2 class="atlas-comunicado-detalle-card-title">
                        Información de publicación
                    </h2>

                    <p class="atlas-comunicado-detalle-card-subtitle">
                        Datos de registro y seguimiento.
                    </p>
                </div>
            </div>

            <div class="atlas-comunicado-detalle-card-body">

                <div class="atlas-comunicado-detalle-meta">

                    <div class="atlas-comunicado-detalle-meta-item">
                        <i class="bi bi-hash"></i>

                        <div>
                            <span class="atlas-comunicado-detalle-meta-label">
                                Número de registro
                            </span>

                            <span class="atlas-comunicado-detalle-meta-value">
                                COM-{{ str_pad($comunicado->id, 6, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                    </div>

                    <div class="atlas-comunicado-detalle-meta-item">
                        <i class="bi bi-person-badge"></i>

                        <div>
                            <span class="atlas-comunicado-detalle-meta-label">
                                Publicado por
                            </span>

                            <span class="atlas-comunicado-detalle-meta-value">
                                {{ $nombreAutor }}
                            </span>
                        </div>
                    </div>

                    <div class="atlas-comunicado-detalle-meta-item">
                        <i class="bi bi-calendar-event"></i>

                        <div>
                            <span class="atlas-comunicado-detalle-meta-label">
                                Fecha de publicación
                            </span>

                            <span class="atlas-comunicado-detalle-meta-value">
                                @if($comunicado->publicado_at)
                                    {{ $comunicado->publicado_at->format('d/m/Y') }}
                                    a las
                                    {{ $comunicado->publicado_at->format('h:i A') }}
                                @else
                                    Sin publicar
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="atlas-comunicado-detalle-meta-item">
                        <i class="bi bi-check-circle"></i>

                        <div>
                            <span class="atlas-comunicado-detalle-meta-label">
                                Estado
                            </span>

                            @if($estadoComunicado === 'PUBLICADO')
                                <span class="atlas-comunicado-detalle-badge atlas-comunicado-detalle-badge-publicado">
                                    <i class="bi bi-check-circle"></i>
                                    Publicado
                                </span>
                            @elseif($estadoComunicado === 'BORRADOR')
                                <span class="atlas-comunicado-detalle-badge atlas-comunicado-detalle-badge-pendiente">
                                    <i class="bi bi-pencil-square"></i>
                                    Borrador
                                </span>
                            @else
                                <span class="atlas-comunicado-detalle-badge atlas-comunicado-detalle-badge-otro">
                                    {{ ucfirst(strtolower($estadoComunicado)) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="atlas-comunicado-detalle-meta-item">
                        <i class="bi bi-bar-chart"></i>

                        <div style="width:100%;">
                            <span class="atlas-comunicado-detalle-meta-label">
                                Porcentaje de lectura
                            </span>

                            <span class="atlas-comunicado-detalle-meta-value">
                                {{ $porcentajeLectura }}%
                            </span>

                            <div
                                class="atlas-comunicado-detalle-progress"
                                role="progressbar"
                                aria-valuenow="{{ $porcentajeLectura }}"
                                aria-valuemin="0"
                                aria-valuemax="100"
                                aria-label="Porcentaje de lectura"
                            >
                                <div
                                    class="atlas-comunicado-detalle-progress-bar"
                                    style="width:{{ $porcentajeLectura }}%"
                                ></div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>

    <div class="atlas-comunicado-detalle-card">

        <div class="atlas-comunicado-detalle-card-header">
            <div class="atlas-comunicado-detalle-card-icon">
                <i class="bi bi-people"></i>
            </div>

            <div>
                <h2 class="atlas-comunicado-detalle-card-title">
                    Destinatarios del comunicado
                </h2>

                <p class="atlas-comunicado-detalle-card-subtitle">
                    Usuarios incluidos en la publicación y estado
                    individual de lectura.
                </p>
            </div>
        </div>

        <div class="atlas-comunicado-detalle-table-wrap">
            <table class="atlas-comunicado-detalle-table">

                <thead>
                    <tr>
                        <th>Destinatario</th>
                        <th>Estado de lectura</th>
                        <th>Fecha y hora de lectura</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($destinatarios as $destinatario)
                        @php
                            $nombreDestinatario = trim(
                                ($destinatario->nombres ?? '') . ' ' .
                                ($destinatario->apellidos ?? '')
                            );

                            $leido = !empty($destinatario->leido_at);
                        @endphp

                        <tr>
                            <td>
                                <div class="atlas-comunicado-detalle-user">
                                    {{ $nombreDestinatario ?: $destinatario->nombre_usuario }}
                                </div>

                                <div class="atlas-comunicado-detalle-username">
                                    {{ '@' . $destinatario->nombre_usuario }}
                                </div>
                            </td>

                            <td>
                                @if($leido)
                                    <span class="atlas-comunicado-detalle-badge atlas-comunicado-detalle-badge-publicado">
                                        <i class="bi bi-check-circle"></i>
                                        Leído
                                    </span>
                                @else
                                    <span class="atlas-comunicado-detalle-badge atlas-comunicado-detalle-badge-pendiente">
                                        <i class="bi bi-clock"></i>
                                        Pendiente
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if($leido)
                                    {{ \Illuminate\Support\Carbon::parse(
                                        $destinatario->leido_at
                                    )->format('d/m/Y h:i A') }}
                                @else
                                    <span style="color:#9ba9b8;">
                                        Sin lectura registrada
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">
                                <div class="atlas-comunicado-detalle-empty">
                                    <i
                                        class="bi bi-inbox"
                                        style="font-size:35px;display:block;margin-bottom:12px;"
                                    ></i>

                                    No se encontraron destinatarios registrados
                                    para este comunicado.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        @if($destinatarios->hasPages())
            <div class="atlas-comunicado-detalle-footer">
                {{ $destinatarios->links() }}
            </div>
        @endif

    </div>

    <div class="atlas-comunicado-detalle-note">
        <i class="bi bi-shield-check"></i>

        <div>
            <strong>Registro institucional de comunicaciones.</strong>
            Los destinatarios corresponden a los usuarios seleccionados
            al momento de publicar el comunicado. La lectura se registra
            individualmente cuando cada destinatario abre el mensaje
            desde su cuenta de ATLAS. Esta información se conserva
            para fines de seguimiento y trazabilidad institucional.
        </div>
    </div>

</div>
@endsection
