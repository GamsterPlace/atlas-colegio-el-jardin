
@extends('layouts.atlas')

@section('title', 'Comunicados recibidos')

@section('content')
<style>
    .atlas-recibidos {
        max-width: 1200px;
        margin: 0 auto;
        padding: 26px 22px 45px;
        color: #183454;
    }

    .atlas-recibidos * {
        box-sizing: border-box;
    }

    .atlas-recibidos-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 18px;
        margin-bottom: 26px;
    }

    .atlas-recibidos-eyebrow {
        color: #3573b7;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .atlas-recibidos h1 {
        margin: 0 0 8px;
        color: #142f52;
        font-size: clamp(24px, 3vw, 31px);
        font-weight: 800;
    }

    .atlas-recibidos-subtitle {
        color: #718399;
        font-size: 14px;
        line-height: 1.65;
        margin: 0;
    }

    .atlas-recibidos-stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .atlas-recibidos-stat {
        background: #fff;
        border: 1px solid #e0e8f1;
        border-radius: 15px;
        padding: 21px;
        box-shadow: 0 4px 16px rgba(22, 48, 80, .035);
    }

    .atlas-recibidos-stat-icon {
        width: 43px;
        height: 43px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #edf4fd;
        border-radius: 11px;
        color: #28649e;
        font-size: 20px;
        margin-bottom: 14px;
    }

    .atlas-recibidos-stat-label {
        font-size: 12px;
        font-weight: 700;
        color: #78899c;
        margin-bottom: 8px;
    }

    .atlas-recibidos-stat-value {
        color: #193b5c;
        font-size: 28px;
        line-height: 1.2;
        font-weight: 800;
    }

    .atlas-recibidos-panel {
        background: #fff;
        border: 1px solid #dfe8f1;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 18px rgba(20, 50, 80, .035);
    }

    .atlas-recibidos-panel-header {
        padding: 21px 24px;
        border-bottom: 1px solid #e9eef5;
    }

    .atlas-recibidos-panel-title {
        font-size: 17px;
        font-weight: 800;
        color: #1b3c60;
        margin: 0 0 5px;
    }

    .atlas-recibidos-panel-description {
        color: #8090a4;
        font-size: 12px;
        margin: 0;
    }

    .atlas-recibidos-filtros {
        display: flex;
        align-items: end;
        flex-wrap: wrap;
        gap: 12px;
        padding: 20px 24px;
        background: #fafcff;
        border-bottom: 1px solid #e9eef5;
    }

    .atlas-recibidos-field {
        display: flex;
        flex-direction: column;
        gap: 7px;
        min-width: 175px;
    }

    .atlas-recibidos-field-search {
        flex: 1;
        min-width: 230px;
    }

    .atlas-recibidos-field label {
        color: #405a77;
        font-size: 12px;
        font-weight: 700;
    }

    .atlas-recibidos-input,
    .atlas-recibidos-select {
        width: 100%;
        min-height: 43px;
        padding: 10px 12px;
        border: 1px solid #d6e1ec;
        border-radius: 10px;
        background: #fff;
        color: #233e5b;
        font-family: inherit;
        font-size: 13px;
        outline: none;
    }

    .atlas-recibidos-input:focus,
    .atlas-recibidos-select:focus {
        border-color: #3977bb;
        box-shadow: 0 0 0 3px rgba(57, 119, 187, .1);
    }

    .atlas-recibidos-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 43px;
        padding: 10px 17px;
        border-radius: 10px;
        border: 1px solid transparent;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
    }

    .atlas-recibidos-btn-primary {
        background: #173f73;
        color: #fff;
    }

    .atlas-recibidos-btn-primary:hover {
        background: #102f57;
        color: #fff;
    }

    .atlas-recibidos-btn-secondary {
        background: #fff;
        border-color: #d6e1ec;
        color: #365777;
    }

    .atlas-recibidos-btn-secondary:hover {
        background: #edf4fc;
    }

    .atlas-recibidos-list {
        display: flex;
        flex-direction: column;
    }

    .atlas-recibidos-item {
        display: flex;
        align-items: flex-start;
        gap: 17px;
        padding: 21px 24px;
        border-bottom: 1px solid #edf1f6;
        text-decoration: none;
        color: inherit;
        transition: background .2s ease;
    }

    .atlas-recibidos-item:last-child {
        border-bottom: 0;
    }

    .atlas-recibidos-item:hover {
        background: #f7fbff;
    }

    .atlas-recibidos-item-pendiente {
        background: #f8fbff;
        border-left: 4px solid #2879b8;
        padding-left: 20px;
    }

    .atlas-recibidos-item-icon {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 12px;
        background: #edf4fd;
        color: #28649e;
        font-size: 20px;
    }

    .atlas-recibidos-item-pendiente .atlas-recibidos-item-icon {
        background: #dcecff;
        color: #175eaa;
    }

    .atlas-recibidos-item-body {
        flex: 1;
        min-width: 0;
    }

    .atlas-recibidos-item-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 7px;
    }

    .atlas-recibidos-item-title {
        font-size: 15px;
        font-weight: 800;
        color: #244664;
        line-height: 1.5;
    }

    .atlas-recibidos-item-description {
        color: #788a9e;
        font-size: 13px;
        line-height: 1.7;
        margin-bottom: 11px;
        overflow-wrap: anywhere;
    }

    .atlas-recibidos-item-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 9px 18px;
        color: #8b9aab;
        font-size: 11px;
    }

    .atlas-recibidos-item-meta span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .atlas-recibidos-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }

    .atlas-recibidos-badge-pendiente {
        background: #e6f1ff;
        color: #1e63a2;
    }

    .atlas-recibidos-badge-leido {
        background: #e8f7ef;
        color: #197347;
    }

    .atlas-recibidos-item-arrow {
        color: #8aa5c0;
        font-size: 19px;
        align-self: center;
    }

    .atlas-recibidos-empty {
        padding: 65px 22px;
        text-align: center;
    }

    .atlas-recibidos-empty-icon {
        color: #a1b6cc;
        font-size: 43px;
        margin-bottom: 15px;
    }

    .atlas-recibidos-empty h3 {
        font-size: 17px;
        font-weight: 800;
        color: #294a6c;
        margin: 0 0 9px;
    }

    .atlas-recibidos-empty p {
        color: #8393a5;
        font-size: 13px;
        line-height: 1.7;
        margin: 0;
    }

    .atlas-recibidos-footer {
        padding: 18px 24px;
        border-top: 1px solid #e9eef5;
    }

    .atlas-recibidos-notice {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-top: 20px;
        padding: 16px 18px;
        border: 1px solid #d4e5f8;
        background: #edf5ff;
        border-radius: 12px;
        color: #365d84;
        font-size: 12px;
        line-height: 1.7;
    }

    .atlas-recibidos-notice i {
        font-size: 18px;
        margin-top: 2px;
    }

    @media (max-width: 760px) {
        .atlas-recibidos {
            padding: 18px 12px 35px;
        }

        .atlas-recibidos-stats {
            grid-template-columns: 1fr;
            gap: 11px;
        }

        .atlas-recibidos-filtros {
            padding: 16px;
        }

        .atlas-recibidos-field {
            width: 100%;
        }

        .atlas-recibidos-btn {
            width: 100%;
        }

        .atlas-recibidos-item {
            padding: 17px 15px;
            gap: 12px;
        }

        .atlas-recibidos-item-pendiente {
            padding-left: 11px;
        }

        .atlas-recibidos-item-icon {
            width: 39px;
            height: 39px;
            font-size: 17px;
        }

        .atlas-recibidos-item-arrow {
            display: none;
        }
    }
</style>

<div class="atlas-recibidos">

    <div class="atlas-recibidos-header">
        <div>
            <div class="atlas-recibidos-eyebrow">
                ATLAS / Comunicación institucional
            </div>

            <h1>Mis comunicados</h1>

            <p class="atlas-recibidos-subtitle">
                Consulte los comunicados institucionales que
                han sido enviados a su cuenta.
            </p>
        </div>
    </div>

    <div class="atlas-recibidos-stats">

        <div class="atlas-recibidos-stat">
            <div class="atlas-recibidos-stat-icon">
                <i class="bi bi-envelope-paper"></i>
            </div>

            <div class="atlas-recibidos-stat-label">
                Comunicados recibidos
            </div>

            <div class="atlas-recibidos-stat-value">
                {{ number_format($totalComunicados) }}
            </div>
        </div>

        <div class="atlas-recibidos-stat">
            <div class="atlas-recibidos-stat-icon">
                <i class="bi bi-check2-circle"></i>
            </div>

            <div class="atlas-recibidos-stat-label">
                Comunicados leídos
            </div>

            <div class="atlas-recibidos-stat-value">
                {{ number_format($totalLeidos) }}
            </div>
        </div>

        <div class="atlas-recibidos-stat">
            <div class="atlas-recibidos-stat-icon">
                <i class="bi bi-clock-history"></i>
            </div>

            <div class="atlas-recibidos-stat-label">
                Pendientes de lectura
            </div>

            <div class="atlas-recibidos-stat-value">
                {{ number_format($totalPendientes) }}
            </div>
        </div>

    </div>

    <div class="atlas-recibidos-panel">

        <div class="atlas-recibidos-panel-header">
            <h2 class="atlas-recibidos-panel-title">
                Bandeja de comunicados
            </h2>

            <p class="atlas-recibidos-panel-description">
                Los mensajes pendientes se destacan para facilitar su identificación.
            </p>
        </div>

        <form
            method="GET"
            action="{{ route('comunicados.recibidos') }}"
            class="atlas-recibidos-filtros"
        >
            <div class="atlas-recibidos-field atlas-recibidos-field-search">
                <label for="buscar">
                    Buscar comunicado
                </label>

                <input
                    type="search"
                    id="buscar"
                    name="buscar"
                    class="atlas-recibidos-input"
                    value="{{ $filtros['buscar'] ?? '' }}"
                    placeholder="Buscar por título o contenido..."
                >
            </div>

            <div class="atlas-recibidos-field">
                <label for="estado_lectura">
                    Estado de lectura
                </label>

                <select
                    id="estado_lectura"
                    name="estado_lectura"
                    class="atlas-recibidos-select"
                >
                    <option
                        value="TODOS"
                        @selected(
                            ($filtros['estado_lectura'] ?? 'TODOS') === 'TODOS'
                        )
                    >
                        Todos
                    </option>

                    <option
                        value="PENDIENTES"
                        @selected(
                            ($filtros['estado_lectura'] ?? '') === 'PENDIENTES'
                        )
                    >
                        Pendientes
                    </option>

                    <option
                        value="LEIDOS"
                        @selected(
                            ($filtros['estado_lectura'] ?? '') === 'LEIDOS'
                        )
                    >
                        Leídos
                    </option>
                </select>
            </div>

            <button
                type="submit"
                class="atlas-recibidos-btn atlas-recibidos-btn-primary"
            >
                <i class="bi bi-search"></i>
                Buscar
            </button>

            <a
                href="{{ route('comunicados.recibidos') }}"
                class="atlas-recibidos-btn atlas-recibidos-btn-secondary"
            >
                <i class="bi bi-arrow-counterclockwise"></i>
                Limpiar
            </a>
        </form>

        <div class="atlas-recibidos-list">

            @forelse($comunicados as $comunicado)
                @php
                    $leido = (bool) $comunicado->fue_leido;

                    $autorPersona = $comunicado->creado_por?->persona;

                    $nombreAutor = $autorPersona
                        ? trim(
                            ($autorPersona->nombres ?? '') . ' ' .
                            ($autorPersona->apellidos ?? '')
                        )
                        : (
                            $comunicado->creado_por?->nombre_usuario
                            ?? 'Administración institucional'
                        );
                @endphp

                <a
                    href="{{ route('comunicados.ver', $comunicado->id) }}"
                    class="atlas-recibidos-item {{ !$leido ? 'atlas-recibidos-item-pendiente' : '' }}"
                >
                    <div class="atlas-recibidos-item-icon">
                        <i class="bi {{ $leido ? 'bi-envelope-open' : 'bi-envelope-fill' }}"></i>
                    </div>

                    <div class="atlas-recibidos-item-body">

                        <div class="atlas-recibidos-item-top">
                            <div class="atlas-recibidos-item-title">
                                {{ $comunicado->titulo }}
                            </div>

                            @if($leido)
                                <span class="atlas-recibidos-badge atlas-recibidos-badge-leido">
                                    <i class="bi bi-check-circle"></i>
                                    Leído
                                </span>
                            @else
                                <span class="atlas-recibidos-badge atlas-recibidos-badge-pendiente">
                                    <i class="bi bi-circle-fill"></i>
                                    Pendiente
                                </span>
                            @endif
                        </div>

                        <div class="atlas-recibidos-item-description">
                            {{ \Illuminate\Support\Str::limit(
                                strip_tags($comunicado->contenido),
                                180
                            ) }}
                        </div>

                        <div class="atlas-recibidos-item-meta">

                            <span>
                                <i class="bi bi-person"></i>
                                {{ $nombreAutor }}
                            </span>

                            <span>
                                <i class="bi bi-calendar-event"></i>

                                @if($comunicado->publicado_at)
                                    {{ $comunicado->publicado_at->format('d/m/Y') }}
                                @else
                                    Sin fecha
                                @endif
                            </span>

                            @if($comunicado->publicado_at)
                                <span>
                                    <i class="bi bi-clock"></i>
                                    {{ $comunicado->publicado_at->format('h:i A') }}
                                </span>
                            @endif

                        </div>
                    </div>

                    <i class="bi bi-chevron-right atlas-recibidos-item-arrow"></i>
                </a>

            @empty
                <div class="atlas-recibidos-empty">
                    <div class="atlas-recibidos-empty-icon">
                        <i class="bi bi-inbox"></i>
                    </div>

                    <h3>No se encontraron comunicados</h3>

                    <p>
                        @if(request()->filled('buscar') || request('estado_lectura', 'TODOS') !== 'TODOS')
                            No hay comunicados que coincidan con los filtros seleccionados.
                        @else
                            Por el momento no tiene comunicados institucionales
                            asignados a su cuenta.
                        @endif
                    </p>
                </div>
            @endforelse

        </div>

        @if($comunicados->hasPages())
            <div class="atlas-recibidos-footer">
                {{ $comunicados->links() }}
            </div>
        @endif

    </div>

    <div class="atlas-recibidos-notice">
        <i class="bi bi-info-circle"></i>

        <div>
            <strong>Información institucional.</strong>
            Los comunicados son enviados por las autoridades del colegio.
            Al abrir un mensaje, ATLAS registra la fecha y hora de su
            primera lectura para fines de seguimiento institucional.
        </div>
    </div>

</div>
@endsection
