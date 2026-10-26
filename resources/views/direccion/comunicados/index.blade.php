@extends('layouts.direccion')

@section('title', 'Historial de comunicados')
@section('module-title', 'Comunicados institucionales')

@section('content')
<style>
    .atlas-comunicados {
        max-width: 1250px;
        margin: 0 auto;
        padding: 26px 22px 42px;
        color: #183454;
    }

    .atlas-comunicados * {
        box-sizing: border-box;
    }

    .atlas-comunicados-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 18px;
        flex-wrap: wrap;
        margin-bottom: 26px;
    }

    .atlas-comunicados-eyebrow {
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .09em;
        text-transform: uppercase;
        color: #3573b7;
        margin-bottom: 8px;
    }

    .atlas-comunicados h1 {
        margin: 0 0 8px;
        font-size: clamp(23px, 3vw, 31px);
        font-weight: 800;
        color: #142f52;
    }

    .atlas-comunicados-subtitle {
        color: #63768b;
        font-size: 14px;
        line-height: 1.65;
        margin: 0;
    }

    .atlas-comunicados-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        padding: 12px 19px;
        border-radius: 11px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        border: 1px solid transparent;
        cursor: pointer;
        transition: .2s ease;
    }

    .atlas-comunicados-btn-primary {
        background: #173d70;
        color: #fff;
    }

    .atlas-comunicados-btn-primary:hover {
        background: #102e55;
        color: #fff;
    }

    .atlas-comunicados-btn-secondary {
        background: #fff;
        border-color: #d5e0ed;
        color: #284c75;
    }

    .atlas-comunicados-btn-secondary:hover {
        background: #edf4fc;
    }

    .atlas-comunicados-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .atlas-comunicados-stat {
        background: #fff;
        border: 1px solid #e0e8f1;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 4px 16px rgba(22, 48, 80, .035);
    }

    .atlas-comunicados-stat-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 11px;
        background: #edf4fd;
        color: #245c9b;
        font-size: 19px;
        margin-bottom: 14px;
    }

    .atlas-comunicados-stat-label {
        font-size: 12px;
        font-weight: 700;
        color: #708198;
        margin-bottom: 7px;
    }

    .atlas-comunicados-stat-value {
        font-size: 27px;
        line-height: 1.2;
        font-weight: 800;
        color: #183454;
    }

    .atlas-comunicados-panel {
        background: #fff;
        border: 1px solid #e0e8f1;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(22, 48, 80, .035);
        overflow: hidden;
    }

    .atlas-comunicados-panel-header {
        padding: 22px 24px;
        border-bottom: 1px solid #e8eef5;
    }

    .atlas-comunicados-panel-title {
        font-size: 17px;
        font-weight: 800;
        color: #193958;
        margin: 0 0 5px;
    }

    .atlas-comunicados-panel-description {
        font-size: 13px;
        color: #77879b;
        margin: 0;
    }

    .atlas-comunicados-filtros {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: end;
        padding: 20px 24px;
        background: #fafcff;
        border-bottom: 1px solid #e8eef5;
    }

    .atlas-comunicados-field {
        display: flex;
        flex-direction: column;
        gap: 7px;
        min-width: 175px;
    }

    .atlas-comunicados-field-search {
        flex: 1;
        min-width: 240px;
    }

    .atlas-comunicados-field label {
        font-size: 12px;
        font-weight: 700;
        color: #405a77;
    }

    .atlas-comunicados-field input,
    .atlas-comunicados-field select {
        width: 100%;
        min-height: 43px;
        padding: 10px 12px;
        border: 1px solid #d6e1ec;
        border-radius: 10px;
        background: #fff;
        color: #233e5b;
        font-size: 13px;
        outline: none;
    }

    .atlas-comunicados-field input:focus,
    .atlas-comunicados-field select:focus {
        border-color: #3977bb;
        box-shadow: 0 0 0 3px rgba(57, 119, 187, .1);
    }

    .atlas-comunicados-table-wrap {
        overflow-x: auto;
    }

    .atlas-comunicados-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .atlas-comunicados-table th {
        background: #f7f9fc;
        padding: 15px 18px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .04em;
        text-transform: uppercase;
        color: #64788e;
        white-space: nowrap;
    }

    .atlas-comunicados-table td {
        padding: 17px 18px;
        border-top: 1px solid #edf1f6;
        font-size: 13px;
        color: #38516c;
        vertical-align: middle;
    }

    .atlas-comunicados-table tbody tr:hover {
        background: #fbfdff;
    }

    .atlas-comunicados-item-title {
        font-size: 13px;
        font-weight: 800;
        color: #173858;
        margin-bottom: 5px;
    }

    .atlas-comunicados-item-description {
        max-width: 330px;
        font-size: 12px;
        color: #8492a4;
        line-height: 1.5;
    }

    .atlas-comunicados-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }

    .atlas-comunicados-badge-publicado {
        background: #e8f7ef;
        color: #197347;
    }

    .atlas-comunicados-badge-borrador {
        background: #fff4dc;
        color: #93640b;
    }

    .atlas-comunicados-badge-otro {
        background: #edf1f6;
        color: #5a6d82;
    }

    .atlas-comunicados-count {
        font-weight: 800;
        color: #264b72;
    }

    .atlas-comunicados-progress {
        height: 5px;
        background: #e9eff6;
        border-radius: 8px;
        overflow: hidden;
        margin-top: 8px;
        width: 100px;
    }

    .atlas-comunicados-progress-bar {
        height: 100%;
        background: #2c7bbd;
        border-radius: inherit;
    }

    .atlas-comunicados-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border: 1px solid #d5e1ee;
        border-radius: 10px;
        background: #f7fbff;
        color: #245d96;
        font-size: 16px;
        text-decoration: none;
    }

    .atlas-comunicados-action:hover {
        background: #e9f3ff;
        color: #173e70;
    }

    .atlas-comunicados-empty {
        text-align: center;
        padding: 65px 20px;
    }

    .atlas-comunicados-empty-icon {
        font-size: 42px;
        color: #9fb4cc;
        margin-bottom: 15px;
    }

    .atlas-comunicados-empty h3 {
        color: #294a6c;
        font-size: 17px;
        margin-bottom: 8px;
    }

    .atlas-comunicados-empty p {
        color: #8191a4;
        font-size: 13px;
        margin-bottom: 20px;
    }

    .atlas-comunicados-footer {
        padding: 18px 24px;
        border-top: 1px solid #e8eef5;
    }

    @media (max-width: 1050px) {
        .atlas-comunicados-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 600px) {
        .atlas-comunicados {
            padding: 18px 12px 30px;
        }

        .atlas-comunicados-stats {
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .atlas-comunicados-stat {
            padding: 15px;
        }

        .atlas-comunicados-stat-value {
            font-size: 23px;
        }

        .atlas-comunicados-filtros {
            padding: 16px;
        }

        .atlas-comunicados-field {
            width: 100%;
        }

        .atlas-comunicados-btn {
            width: 100%;
        }
    }
</style>

<div class="atlas-comunicados">

    <div class="atlas-comunicados-header">
        <div>
            <div class="atlas-comunicados-eyebrow">
                Dirección / Comunicación institucional
            </div>

            <h1>Historial de comunicados</h1>

            <p class="atlas-comunicados-subtitle">
                Consulte los comunicados institucionales, sus destinatarios
                y el seguimiento de lectura de cada publicación.
            </p>
        </div>

        <a href="{{ route('direccion.comunicados.create') }}"
           class="atlas-comunicados-btn atlas-comunicados-btn-primary">
            <i class="bi bi-plus-circle"></i>
            Crear comunicado
        </a>
    </div>

    <div class="atlas-comunicados-stats">

        <div class="atlas-comunicados-stat">
            <div class="atlas-comunicados-stat-icon">
                <i class="bi bi-megaphone"></i>
            </div>
            <div class="atlas-comunicados-stat-label">
                Total de comunicados
            </div>
            <div class="atlas-comunicados-stat-value">
                {{ number_format($resumen['total'] ?? 0) }}
            </div>
        </div>

        <div class="atlas-comunicados-stat">
            <div class="atlas-comunicados-stat-icon">
                <i class="bi bi-send-check"></i>
            </div>
            <div class="atlas-comunicados-stat-label">
                Comunicados publicados
            </div>
            <div class="atlas-comunicados-stat-value">
                {{ number_format($resumen['publicados'] ?? 0) }}
            </div>
        </div>

        <div class="atlas-comunicados-stat">
            <div class="atlas-comunicados-stat-icon">
                <i class="bi bi-people"></i>
            </div>
            <div class="atlas-comunicados-stat-label">
                Entregas registradas
            </div>
            <div class="atlas-comunicados-stat-value">
                {{ number_format($resumen['destinatarios'] ?? 0) }}
            </div>
        </div>

        <div class="atlas-comunicados-stat">
            <div class="atlas-comunicados-stat-icon">
                <i class="bi bi-eye"></i>
            </div>
            <div class="atlas-comunicados-stat-label">
                Lecturas registradas
            </div>
            <div class="atlas-comunicados-stat-value">
                {{ number_format($resumen['lecturas'] ?? 0) }}
            </div>
        </div>

    </div>

    <div class="atlas-comunicados-panel">

        <div class="atlas-comunicados-panel-header">
            <h2 class="atlas-comunicados-panel-title">
                Registro de comunicados
            </h2>

            <p class="atlas-comunicados-panel-description">
                Historial de publicaciones realizadas desde ATLAS.
            </p>
        </div>

        <form method="GET"
              action="{{ route('direccion.comunicados.index') }}"
              class="atlas-comunicados-filtros">

            <div class="atlas-comunicados-field atlas-comunicados-field-search">
                <label for="buscar">Buscar comunicado</label>
                <input
                    type="search"
                    id="buscar"
                    name="buscar"
                    value="{{ $filtros['buscar'] ?? '' }}"
                    placeholder="Buscar por título o contenido..."
                >
            </div>

            <div class="atlas-comunicados-field">
                <label for="estado">Estado</label>
                <select id="estado" name="estado">
                    <option value="">Todos los estados</option>

                    <option value="PUBLICADO"
                        @selected(($filtros['estado'] ?? '') === 'PUBLICADO')>
                        Publicado
                    </option>
                </select>
            </div>

            <button type="submit"
                    class="atlas-comunicados-btn atlas-comunicados-btn-primary">
                <i class="bi bi-search"></i>
                Buscar
            </button>

            <a href="{{ route('direccion.comunicados.index') }}"
               class="atlas-comunicados-btn atlas-comunicados-btn-secondary">
                <i class="bi bi-arrow-counterclockwise"></i>
                Limpiar
            </a>

        </form>

        <div class="atlas-comunicados-table-wrap">
            <table class="atlas-comunicados-table">
                <thead>
                    <tr>
                        <th>Comunicado</th>
                        <th>Publicado por</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Destinatarios</th>
                        <th>Lecturas</th>
                        <th style="text-align:center;">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($comunicados as $comunicado)
                        @php
                            $totalDestinatarios = (int) ($comunicado->total_destinatarios ?? 0);
                            $totalLecturas = (int) ($comunicado->total_lecturas ?? 0);

                            $porcentajeLectura = $totalDestinatarios > 0
                                ? min(100, round(($totalLecturas / $totalDestinatarios) * 100))
                                : 0;

                            $autor = $comunicado->autor?->persona;

                            $nombreAutor = $autor
                                ? trim($autor->nombres . ' ' . $autor->apellidos)
                                : ($comunicado->autor?->nombre_usuario ?? 'No disponible');

                            $estado = strtoupper((string) $comunicado->estado);

                            $claseEstado = match ($estado) {
                                'PUBLICADO' => 'atlas-comunicados-badge-publicado',
                                'BORRADOR' => 'atlas-comunicados-badge-borrador',
                                default => 'atlas-comunicados-badge-otro',
                            };
                        @endphp

                        <tr>
                            <td>
                                <div class="atlas-comunicados-item-title">
                                    {{ $comunicado->titulo }}
                                </div>

                                <div class="atlas-comunicados-item-description">
                                    {{ \Illuminate\Support\Str::limit(
                                        strip_tags($comunicado->contenido),
                                        110
                                    ) }}
                                </div>
                            </td>

                            <td>
                                {{ $nombreAutor }}
                            </td>

                            <td>
                                @if($comunicado->publicado_at)
                                    {{ $comunicado->publicado_at->format('d/m/Y') }}

                                    <div style="font-size:11px;color:#8191a4;margin-top:4px;">
                                        {{ $comunicado->publicado_at->format('h:i A') }}
                                    </div>
                                @else
                                    <span style="color:#9ba8b8;">
                                        Sin publicar
                                    </span>
                                @endif
                            </td>

                            <td>
                                <span class="atlas-comunicados-badge {{ $claseEstado }}">
                                    @if($estado === 'PUBLICADO')
                                        <i class="bi bi-check-circle"></i>
                                    @elseif($estado === 'BORRADOR')
                                        <i class="bi bi-pencil-square"></i>
                                    @else
                                        <i class="bi bi-circle"></i>
                                    @endif

                                    {{ ucfirst(strtolower($estado)) }}
                                </span>
                            </td>

                            <td>
                                <span class="atlas-comunicados-count">
                                    {{ number_format($totalDestinatarios) }}
                                </span>

                                <div style="font-size:11px;color:#8492a4;margin-top:4px;">
                                    Usuarios
                                </div>
                            </td>

                            <td>
                                <span class="atlas-comunicados-count">
                                    {{ number_format($totalLecturas) }}
                                    /
                                    {{ number_format($totalDestinatarios) }}
                                </span>

                                <div class="atlas-comunicados-progress"
                                     title="{{ $porcentajeLectura }}% de lecturas">
                                    <div class="atlas-comunicados-progress-bar"
                                         style="width:{{ $porcentajeLectura }}%">
                                    </div>
                                </div>

                                <div style="font-size:11px;color:#8191a4;margin-top:5px;">
                                    {{ $porcentajeLectura }}% leído
                                </div>
                            </td>

                            <td style="text-align:center;">
                                <a href="{{ route('direccion.comunicados.show', $comunicado->id) }}"
                                   class="atlas-comunicados-action"
                                   title="Ver comunicado y destinatarios"
                                   aria-label="Ver comunicado {{ $comunicado->titulo }}">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="atlas-comunicados-empty">
                                    <div class="atlas-comunicados-empty-icon">
                                        <i class="bi bi-envelope-paper"></i>
                                    </div>

                                    <h3>No se encontraron comunicados</h3>

                                    <p>
                                        @if(request()->filled('buscar') || request()->filled('estado'))
                                            No existen comunicados que coincidan con los filtros seleccionados.
                                        @else
                                            Todavía no se han registrado comunicados institucionales.
                                        @endif
                                    </p>

                                    @if(!request()->filled('buscar') && !request()->filled('estado'))
                                        <a href="{{ route('direccion.comunicados.create') }}"
                                           class="atlas-comunicados-btn atlas-comunicados-btn-primary">
                                            <i class="bi bi-plus-circle"></i>
                                            Crear primer comunicado
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($comunicados->hasPages())
            <div class="atlas-comunicados-footer">
                {{ $comunicados->links() }}
            </div>
        @endif

    </div>

</div>
@endsection
