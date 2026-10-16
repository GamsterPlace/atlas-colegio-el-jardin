@extends('layouts.direccion')

@section('title', 'Inscripciones')
@section('role', 'Dirección')
@section('module-title', 'Inscripciones y reinscripciones')
@section('dashboard-url', route('direccion.dashboard'))

@section('content')
<style>
    .atlas-inscripciones {
        --atlas-primary: #163b70;
        --atlas-primary-dark: #102d56;
        --atlas-border: #e3e9f2;
        --atlas-text: #24364d;
        --atlas-muted: #718096;
        color: var(--atlas-text);
        width: 100%;
    }

    .atlas-inscripciones *,
    .atlas-inscripciones *::before,
    .atlas-inscripciones *::after {
        box-sizing: border-box;
    }

    .atlas-inscripciones .atlas-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 18px;
        margin-bottom: 24px;
    }

    .atlas-inscripciones .atlas-title {
        margin: 0 0 8px;
        color: var(--atlas-primary-dark);
        font-size: 24px;
        font-weight: 700;
        line-height: 1.3;
    }

    .atlas-inscripciones .atlas-description {
        margin: 0;
        color: var(--atlas-muted);
        font-size: 14px;
        line-height: 1.6;
    }

    .atlas-inscripciones .atlas-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 42px;
        padding: 10px 18px;
        border: 1px solid transparent;
        border-radius: 9px;
        background: var(--atlas-primary);
        color: #ffffff;
        font-family: inherit;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.3;
        text-decoration: none;
        cursor: pointer;
        transition: *background* .2s, border-color .2s;
    }

    .atlas-inscripciones .atlas-button:hover {
        background: var(--atlas-primary-dark);
        color: #ffffff;
    }

    .atlas-inscripciones .atlas-button-secondary {
        background: #ffffff;
        border-color: #d7e0ec;
        color: var(--atlas-primary);
    }

    .atlas-inscripciones .atlas-button-secondary:hover {
        background: #f1f5fa;
        color: var(--atlas-primary-dark);
    }

    .atlas-inscripciones .atlas-card {
        overflow: hidden;
        margin-bottom: 22px;
        background: #ffffff;
        border: 1px solid var(--atlas-border);
        border-radius: 13px;
        box-shadow: 0 3px 16px rgba(24, 49, 85, .045);
    }

    .atlas-inscripciones .atlas-card-body {
        padding: 24px;
    }

    .atlas-inscripciones .atlas-card-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        padding: 20px 24px;
        border-bottom: 1px solid var(--atlas-border);
    }

    .atlas-inscripciones .atlas-card-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
        color: var(--atlas-primary-dark);
        font-size: 17px;
        font-weight: 700;
    }

    .atlas-inscripciones .atlas-count {
        color: var(--atlas-muted);
        font-size: 13px;
    }

    .atlas-inscripciones .atlas-filter-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr) auto;
        gap: 18px;
        align-items: end;
    }

    .atlas-inscripciones .atlas-field {
        display: flex;
        flex-direction: column;
        gap: 8px;
        min-width: 0;
    }

    .atlas-inscripciones .atlas-label {
        color: #344861;
        font-size: 13px;
        font-weight: 600;
    }

    .atlas-inscripciones .atlas-input,
    .atlas-inscripciones .atlas-select {
        width: 100%;
        min-height: 43px;
        padding: 10px 13px;
        background: #ffffff;
        border: 1px solid #d6dfeb;
        border-radius: 8px;
        outline: none;
        color: var(--atlas-text);
        font-family: inherit;
        font-size: 14px;
        transition: border-color .2s, box-shadow .2s;
    }

    .atlas-inscripciones .atlas-input:focus,
    .atlas-inscripciones .atlas-select:focus {
        border-color: #5489c8;
        box-shadow: 0 0 0 3px rgba(50, 111, 184, .12);
    }

    .atlas-inscripciones .atlas-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 9px;
    }

    .atlas-inscripciones .atlas-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .atlas-inscripciones .atlas-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        text-align: left;
    }

    .atlas-inscripciones .atlas-table thead {
        background: #f5f8fc;
    }

    .atlas-inscripciones .atlas-table th {
        padding: 15px 17px;
        color: #4d6078;
        font-weight: 700;
        white-space: nowrap;
        border-bottom: 1px solid var(--atlas-border);
    }

    .atlas-inscripciones .atlas-table td {
        padding: 16px 17px;
        color: var(--atlas-text);
        border-bottom: 1px solid #edf1f6;
        vertical-align: middle;
    }

    .atlas-inscripciones .atlas-table tbody tr:hover {
        background: #f9fbfe;
    }

    .atlas-inscripciones .atlas-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .atlas-inscripciones .atlas-student-name {
        color: var(--atlas-primary-dark);
        font-weight: 700;
    }

    .atlas-inscripciones .atlas-muted {
        color: var(--atlas-muted);
    }

    .atlas-inscripciones .atlas-status {
        display: inline-flex;
        align-items: center;
        padding: 6px 11px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .2px;
        white-space: nowrap;
    }

    .atlas-inscripciones .atlas-status-success {
        background: #e5f6ed;
        color: #187447;
    }

    .atlas-inscripciones .atlas-status-warning {
        background: #fff3d9;
        color: #9a6800;
    }

    .atlas-inscripciones .atlas-status-info {
        background: #e5f1ff;
        color: #215c9e;
    }

    .atlas-inscripciones .atlas-status-secondary {
        background: #eef1f5;
        color: #596779;
    }

    .atlas-inscripciones .atlas-status-danger {
        background: #fde9e9;
        color: #aa3030;
    }

    .atlas-inscripciones .atlas-empty {
        padding: 55px 20px;
        text-align: center;
    }

    .atlas-inscripciones .atlas-empty-icon {
        display: block;
        margin-bottom: 15px;
        color: #a5b4c7;
        font-size: 43px;
    }

    .atlas-inscripciones .atlas-empty-title {
        margin: 0 0 8px;
        color: var(--atlas-primary-dark);
        font-size: 16px;
        font-weight: 700;
    }

    .atlas-inscripciones .atlas-empty-description {
        margin: 0;
        color: var(--atlas-muted);
        font-size: 13px;
    }

    .atlas-inscripciones .atlas-alert {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 15px 18px;
        margin-bottom: 20px;
        border: 1px solid #b9e7cc;
        border-radius: 10px;
        background: #ecfaf1;
        color: #17663d;
        font-size: 14px;
    }

    .atlas-inscripciones .atlas-alert-text {
        flex: 1;
    }

    .atlas-inscripciones .atlas-alert-close {
        border: 0;
        background: transparent;
        color: inherit;
        font-size: 19px;
        cursor: pointer;
    }

    .atlas-inscripciones .atlas-pagination {
        padding: 18px 24px;
        border-top: 1px solid var(--atlas-border);
    }

    @media (max-width: 1050px) {
        .atlas-inscripciones .atlas-filter-grid {
            grid-template-columns: 1fr 1fr;
        }

        .atlas-inscripciones .atlas-filter-actions {
            grid-column: 1 / -1;
        }
    }

    @media (max-width: 640px) {
        .atlas-inscripciones .atlas-title {
            font-size: 21px;
        }

        .atlas-inscripciones .atlas-header {
            align-items: stretch;
        }

        .atlas-inscripciones .atlas-card-body,
        .atlas-inscripciones .atlas-card-heading {
            padding: 18px;
        }

        .atlas-inscripciones .atlas-filter-grid {
            grid-template-columns: 1fr;
        }

        .atlas-inscripciones .atlas-filter-actions {
            grid-column: auto;
        }

        .atlas-inscripciones .atlas-actions {
            width: 100%;
        }

        .atlas-inscripciones .atlas-actions .atlas-button {
            flex: 1;
        }
    }

    .atlas-inscripciones .atlas-print-actions {
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .atlas-inscripciones .atlas-print-link {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #d7e0ec;
        border-radius: 8px;
        color: #163b70;
        background: #f5f8fc;
        text-decoration: none;
        font-size: 16px;
    }

    .atlas-inscripciones .atlas-print-link:hover {
        background: #163b70;
        color: #fff;
    }
</style>

<div class="atlas-inscripciones">
    <div class="atlas-header">
        <div>
            <h1 class="atlas-title">
                <i class="bi bi-person-plus"></i>
                Inscripciones y reinscripciones
            </h1>
            <p class="atlas-description">
                Administración de matrículas y registros estudiantiles por ciclo escolar.
            </p>
        </div>
        <a href="{{ route('direccion.inscripciones.create') }}" class="atlas-button">
            <i class="bi bi-plus-circle"></i>
            Nueva inscripción
        </a>
    </div>

    @if(session('success'))
        <div class="atlas-alert" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            <div class="atlas-alert-text">
                {{ session('success') }}
            </div>
            <button type="button" class="atlas-alert-close" aria-label="Cerrar" onclick="this.closest('.atlas-alert').remove()">
                &times;
            </button>
        </div>
    @endif

    <div class="atlas-card">
        <div class="atlas-card-body">
            <form method="GET" action="{{ route('direccion.inscripciones.index') }}">
                <div class="atlas-filter-grid">
                    <div class="atlas-field">
                        <label for="buscar" class="atlas-label">
                            Buscar estudiante
                        </label>
                        <input type="search" name="buscar" id="buscar" class="atlas-input" maxlength="120" value="{{ $buscar ?? '' }}" placeholder="Código histórico, carné o nombre">
                    </div>
                    <div class="atlas-field">
                        <label for="ciclo_escolar_id" class="atlas-label">
                            Ciclo escolar
                        </label>
                        <select name="ciclo_escolar_id" id="ciclo_escolar_id" class="atlas-select">
                            @foreach($ciclosEscolares as $ciclo)
                                <option value="{{ $ciclo->id }}" @selected((string) $cicloEscolarId === (string) $ciclo->id)>
                                    {{ $ciclo->nombre }}
                                    ({{ $ciclo->estado }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="atlas-filter-actions">
                        <div class="atlas-actions">
                            <button type="submit" class="atlas-button">
                                <i class="bi bi-search"></i>
                                Buscar
                            </button>
                            <a href="{{ route('direccion.inscripciones.index') }}" class="atlas-button atlas-button-secondary">
                                <i class="bi bi-arrow-clockwise"></i>
                                Limpiar
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="atlas-card">
        <div class="atlas-card-heading">
            <h2 class="atlas-card-title">
                <i class="bi bi-list-check"></i>
                Registro de inscripciones
            </h2>
            <span class="atlas-count">
                {{ $inscripciones->total() }} registros
            </span>
        </div>
        <div class="atlas-table-wrapper">
            <table class="atlas-table">
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Imprimibles</th>
                        <th>Código histórico</th>
                        <th>Número de carné</th>
                        <th>Ciclo escolar</th>
                        <th>Sección</th>
                        <th>Fecha de inscripción</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inscripciones as $inscripcion)
                        <tr>
                            <td>
                                <span class="atlas-student-name">
                                    {{ $inscripcion->estudiante?->nombre_completo ?? 'Sin información' }}
                                </span>
                            </td>
                            <td>
                                @if($inscripcion->estado === 'COMPLETADA')
                                    <div class="atlas-print-actions">
                                        <a class="atlas-print-link" title="Solicitud de matrícula" aria-label="Solicitud de matrícula" target="_blank" rel="noopener noreferrer" href="{{ route('direccion.inscripciones.documentos.solicitud-matricula', ['inscripcion' => $inscripcion->id]) }}"><i class="bi bi-file-earmark-text"></i></a>
                                        <a class="atlas-print-link" title="Ficha de inscripción" aria-label="Ficha de inscripción" target="_blank" rel="noopener noreferrer" href="{{ route('direccion.inscripciones.documentos.ficha-inscripcion', ['inscripcion' => $inscripcion->id]) }}"><i class="bi bi-person-vcard"></i></a>
                                        <a class="atlas-print-link" title="Talonario de pagos" aria-label="Talonario de pagos" target="_blank" rel="noopener noreferrer" href="{{ route('direccion.inscripciones.documentos.talonario', ['inscripcion' => $inscripcion->id]) }}"><i class="bi bi-receipt"></i></a>
                                    </div>
                                @else
                                    <span class="atlas-muted">—</span>
                                @endif
                            </td>
                            <td>
                                {{ $inscripcion->estudiante?->codigo ?? '—' }}
                            </td>
                            <td>
                                @if($inscripcion->estudiante?->numero_carnet)
                                    {{ $inscripcion->estudiante->numero_carnet }}
                                @else
                                    <span class="atlas-muted">Pendiente</span>
                                @endif
                            </td>
                            <td>
                                {{ $inscripcion->ciclo_escolar?->nombre ?? '—' }}
                            </td>
                            <td>
                                {{ $inscripcion->seccion?->nombre ?? '—' }}
                            </td>
                            <td>
                                {{ $inscripcion->fecha_inscripcion?->format('d/m/Y') ?? '—' }}
                            </td>
                            <td>
                                @php
                                    $estado = $inscripcion->estado;
                                    $colorEstado = match ($estado) {
                                        'COMPLETADA' => 'success',
                                        'TRASLADADA' => 'info',
                                        'RETIRADA' => 'secondary',
                                        'ANULADA' => 'danger',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="atlas-status atlas-status-{{ $colorEstado }}">
                                    {{ str_replace('_', ' ', $estado) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="atlas-empty">
                                    <i class="bi bi-inbox atlas-empty-icon"></i>
                                    <h3 class="atlas-empty-title">
                                        No se encontraron inscripciones
                                    </h3>
                                    <p class="atlas-empty-description">
                                        Prueba con otro criterio de búsqueda
                                        o registra una nueva inscripción.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($inscripciones->hasPages())
            <div class="atlas-pagination">
                {{ $inscripciones->links() }}
            </div>
        @endif
    </div>
</div>

@if(session('documentos_inscripcion_id'))
    <div class="atlas-inscripciones" style="margin: 12px 0 20px">
        <div class="atlas-card" style="padding:16px 20px">
            <strong>Documentos de la inscripción registrada</strong>
            <p class="atlas-description" style="margin:7px 0 12px">Si el navegador no abre automáticamente alguna pestaña, utilice estos enlaces.</p>
            <div class="atlas-actions">
                <a class="atlas-button" target="_blank" rel="noopener noreferrer" href="{{ route('direccion.inscripciones.documentos.solicitud-matricula', ['inscripcion' => session('documentos_inscripcion_id')]) }}">Solicitud de matrícula</a>
                <a class="atlas-button" target="_blank" rel="noopener noreferrer" href="{{ route('direccion.inscripciones.documentos.ficha-inscripcion', ['inscripcion' => session('documentos_inscripcion_id')]) }}">Ficha de inscripción</a>
                <a class="atlas-button" target="_blank" rel="noopener noreferrer" href="{{ route('direccion.inscripciones.documentos.talonario', ['inscripcion' => session('documentos_inscripcion_id')]) }}">Talonario de pagos</a>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const rutas = {
                'solicitud-matricula': @json(route('direccion.inscripciones.documentos.solicitud-matricula', ['inscripcion' => session('documentos_inscripcion_id')])),
                'ficha-inscripcion': @json(route('direccion.inscripciones.documentos.ficha-inscripcion', ['inscripcion' => session('documentos_inscripcion_id')])),
                'talonario': @json(route('direccion.inscripciones.documentos.talonario', ['inscripcion' => session('documentos_inscripcion_id')]))
            };


            Object.entries(rutas).forEach(function ([tipo, url]) {
                const nombre = 'atlas_pdf_' + tipo.replace(/-/g, '_');
                const pestaña = window.open(url, nombre);
                if (!pestaña) console.warn('Apertura automática bloqueada:', tipo);
            });
        });
    </script>
@endif

@endsection
