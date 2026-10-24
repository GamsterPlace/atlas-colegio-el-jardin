
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de movimientos de inventario</title>

    <style>
        @page {
            size: letter landscape;
            margin: 38pt 32pt 38pt 32pt;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 8pt;
            color: #193656;
            line-height: 1.35;
        }

        .header {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14pt;
        }

        .header td {
            border: none;
            vertical-align: middle;
        }

        .logo {
            width: 85pt;
            text-align: center;
        }

        .logo img {
            max-width: 65pt;
            max-height: 65pt;
        }

        .institution {
            text-align: center;
        }

        .school-name {
            font-family: DejaVu Serif, serif;
            font-size: 16pt;
            font-weight: bold;
            color: #092d54;
        }

        .place {
            font-size: 8pt;
            letter-spacing: 2pt;
            margin-top: 3pt;
        }

        .rule {
            border-top: 2pt solid #16654d;
            border-bottom: 1pt solid #bd243e;
            height: 2pt;
            margin: 7pt 12pt;
        }

        .document-title {
            background: #0b365f;
            color: white;
            padding: 10pt;
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 12pt;
        }

        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12pt;
        }

        .summary td {
            border: 1pt solid #d4e0ec;
            background: #f2f7fc;
            text-align: center;
            padding: 8pt;
            width: 33.33%;
        }

        .summary-label {
            font-size: 7pt;
            color: #657b91;
        }

        .summary-value {
            font-size: 14pt;
            font-weight: bold;
            color: #153b62;
        }

        .filters {
            border: 1pt solid #d4e0ec;
            padding: 8pt;
            margin-bottom: 12pt;
            font-size: 8pt;
        }

        .filters strong {
            color: #0b365f;
        }

        .items {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .items thead {
            display: table-header-group;
        }

        .items tr {
            page-break-inside: avoid;
        }

        .items th {
            background: #0b365f;
            color: white;
            padding: 7pt 4pt;
            border: 1pt solid #c0cfde;
            text-align: left;
            font-size: 7pt;
        }

        .items td {
            border: 1pt solid #c0cfde;
            padding: 6pt 4pt;
            font-size: 7pt;
            vertical-align: top;
            overflow-wrap: break-word;
        }

        .items tbody tr:nth-child(odd) {
            background: #f3f7fb;
        }

        .center {
            text-align: center;
        }

        .entrada {
            color: #17603c;
            font-weight: bold;
        }

        .salida {
            color: #a22b38;
            font-weight: bold;
        }

        .footer-note {
            margin-top: 12pt;
            border-top: 1pt solid #d4e0ec;
            padding-top: 8pt;
            color: #657b91;
            font-size: 7pt;
        }
    </style>
</head>

<body>
@php
    $logo = public_path('images/institucional/logo-colegio.png');
@endphp

<table class="header">
    <tr>
        <td class="logo">
            @if(is_file($logo))
                <img src="{{ $logo }}" alt="Colegio Mixto El Jardín">
            @endif
        </td>

        <td class="institution">
            <div class="school-name">
                COLEGIO MIXTO EL JARDÍN
            </div>

            <div class="place">
                SAN RAYMUNDO, GUATEMALA
            </div>

            <div class="rule"></div>

            <div style="font-size:7pt;letter-spacing:1pt">
                SISTEMA INTEGRAL DE CONTROL ESCOLAR · ATLAS
            </div>
        </td>
    </tr>
</table>

<div class="document-title">
    HISTORIAL DE MOVIMIENTOS DE INVENTARIO · UNIFORMES
</div>

<table class="summary">
    <tr>
        <td>
            <div class="summary-label">Movimientos</div>
            <div class="summary-value">
                {{ number_format($resumen['total']) }}
            </div>
        </td>

        <td>
            <div class="summary-label">Unidades ingresadas</div>
            <div class="summary-value">
                {{ number_format($resumen['entradas']) }}
            </div>
        </td>

        <td>
            <div class="summary-label">Unidades retiradas</div>
            <div class="summary-value">
                {{ number_format($resumen['salidas']) }}
            </div>
        </td>
    </tr>
</table>

<div class="filters">
    <strong>Período consultado:</strong>
    {{ $filtros['desde'] ?? 'Inicio del historial' }}
    al
    {{ $filtros['hasta'] ?? 'Fecha actual' }}

    &nbsp; | &nbsp;

    <strong>Tipo:</strong>
    {{ $filtros['tipo'] ?? 'Todos' }}

    &nbsp; | &nbsp;

    <strong>Generado:</strong>
    {{ now()->format('d/m/Y H:i') }}

    @if(!empty($filtros['uniforme_id']))
        <div>
            <strong>ID de uniforme:</strong>
            {{ $filtros['uniforme_id'] }}
        </div>
    @endif

    @if(!empty($filtros['talla']))
        <div>
            <strong>Talla:</strong>
            {{ $filtros['talla'] }}
        </div>
    @endif

    @if(!empty($filtros['usuario_id']))
        <div>
            <strong>ID de responsable:</strong>
            {{ $filtros['usuario_id'] }}
        </div>
    @endif

    @if(!empty($filtros['buscar']))
        <div>
            <strong>Búsqueda:</strong>
            {{ $filtros['buscar'] }}
        </div>
    @endif
</div>

<table class="items">
    <thead>
        <tr>
            <th style="width:12%">Fecha</th>
            <th style="width:9%">Tipo</th>
            <th style="width:19%">Uniforme</th>
            <th style="width:7%">Talla</th>
            <th style="width:8%">Cantidad</th>
            <th style="width:20%">Motivo</th>
            <th style="width:13%">Responsable</th>
            <th style="width:12%">Referencia</th>
        </tr>
    </thead>

    <tbody>
        @forelse($movimientos as $movimiento)
            @php
                $producto = $movimiento->producto_uniforme;
                $uniforme = $producto?->uniforme;
                $usuario = $movimiento->registrado_por;

                $responsable = trim(
                    ($usuario?->persona?->nombres ?? '') . ' ' .
                    ($usuario?->persona?->apellidos ?? '')
                );

                $responsable = $responsable
                    ?: ($usuario?->nombre_usuario ?? 'No disponible');
            @endphp

            <tr>
                <td>
                    {{ $movimiento->fecha_movimiento?->format('d/m/Y H:i') ?? '—' }}
                </td>

                <td class="{{ $movimiento->tipo === 'ENTRADA' ? 'entrada' : ($movimiento->tipo === 'SALIDA' ? 'salida' : '') }}">
                    {{ $movimiento->tipo }}
                </td>

                <td>
                    {{ $uniforme?->nombre ?? $producto?->nombre ?? 'No disponible' }}
                    <div>
                        {{ $uniforme?->color ?? $producto?->color ?? '—' }}
                    </div>
                </td>

                <td class="center">
                    {{ $producto?->talla ?? '—' }}
                </td>

                <td class="center">
                    {{ $movimiento->tipo === 'SALIDA' ? '−' : '+' }}{{ $movimiento->cantidad }}
                </td>

                <td>
                    {{ $movimiento->motivo ?: '—' }}
                </td>

                <td>
                    {{ $responsable }}
                </td>

                <td>
                    {{ $movimiento->referencia ?: '—' }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="center">
                    No existen movimientos para los filtros seleccionados.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="footer-note">
    Documento de consulta institucional.
    Los movimientos corresponden a registros históricos almacenados
    en ATLAS. Las reservas de pedidos no representan salidas físicas.
    Este documento no modifica existencias.
</div>

</body>
</html>
