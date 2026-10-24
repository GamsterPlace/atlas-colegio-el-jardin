
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Identificación de paquete {{ $pedido->numero }}</title>

    <style>
        /*
         * ETIQUETA MEDIA CARTA HORIZONTAL
         * 8.5 x 5.5 pulgadas (612 x 396 puntos)
         */

        @page {
            size: 612pt 396pt;
            margin: 0;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        body {
            padding: 18pt 25pt 16pt 25pt;
            font-family: DejaVu Sans, sans-serif;
            font-size: 8pt;
            line-height: 1.25;
            color: #173253;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        tr {
            page-break-inside: avoid;
        }

        /* ENCABEZADO INSTITUCIONAL - SIN CAMBIOS */

        .header {
            margin-bottom: 9pt;
        }

        .header td {
            border: 0;
            vertical-align: middle;
            padding: 0 7pt;
        }

        .logo-area {
            width: 24%;
            text-align: center;
            border-right: 1pt solid #d7c6b5 !important;
        }

        .logo-area img {
            max-width: 85pt;
            max-height: 57pt;
        }

        .school {
            text-align: center;
        }

        .school-name {
            font-family: DejaVu Serif, serif;
            font-size: 16pt;
            font-weight: bold;
            color: #092d54;
        }

        .school-place {
            font-size: 8pt;
            letter-spacing: 3pt;
            margin-top: 2pt;
        }

        .school-rule {
            border-top: 2pt solid #16654d;
            border-bottom: 1pt solid #bd243e;
            height: 1pt;
            margin: 5pt 12pt 3pt;
        }

        .school-motto {
            font-size: 6pt;
            letter-spacing: 1.3pt;
        }

        /* TÍTULO COMPACTO */

        .title {
            background: #0b365f;
            color: #ffffff;
            text-align: center;
            padding: 6pt 8pt;
            margin-bottom: 5pt;
        }

        .title-text {
            font-size: 11pt;
            font-weight: bold;
            line-height: 1.15;
        }

        /* NÚMERO DEL PEDIDO */

        .number-wrap {
            text-align: center;
            margin-bottom: 5pt;
        }

        .number-box {
            display: inline-block;
            background: #fae9d7;
            padding: 4pt 12pt;
            color: #a51d32;
            font-size: 11pt;
            font-weight: bold;
            letter-spacing: 0.3pt;
            line-height: 1.15;
            word-wrap: break-word;
        }

        /* INFORMACIÓN DEL PAQUETE */

        .info {
            border: 1pt solid #bdcfe0;
            background: #f4f8fc;
            padding: 5pt 8pt;
            margin-bottom: 6pt;
        }

        .info-table {
            table-layout: fixed;
        }

        .info-table td {
            border: 0;
            vertical-align: top;
            padding: 2pt 5pt;
            font-size: 7pt;
        }

        .info-left {
            width: 50%;
            border-right: 1pt solid #bdcfe0 !important;
        }

        .info-right {
            padding-left: 13pt !important;
        }

        .info-line {
            margin-bottom: 3pt;
        }

        .info-line:last-child {
            margin-bottom: 0;
        }

        .info-label {
            font-weight: bold;
            color: #0b3159;
        }

        /* TABLA DE PRENDAS */

        .items {
            table-layout: fixed;
            margin-bottom: 6pt;
        }

        .items th {
            background: #0b365f;
            color: #ffffff;
            font-size: 7pt;
            padding: 4pt 3pt;
            border: 1pt solid #a9bed2;
            text-align: center;
        }

        .items td {
            border: 1pt solid #c0cfde;
            padding: 4pt;
            font-size: 7pt;
            word-wrap: break-word;
        }

        .items tbody tr:nth-child(odd) {
            background: #f0f5fa;
        }

        .center {
            text-align: center;
        }

        /* AVISO DE ENTREGA */

        .notice {
            border: 1pt solid #c51f3b;
            background: #fff7f8;
            padding: 6pt 9pt;
            font-size: 7pt;
            line-height: 1.25;
            page-break-inside: avoid;
        }

        .notice-title {
            color: #b91c35;
            font-weight: bold;
            font-size: 8pt;
        }

        .notice-divider {
            color: #b91c35;
            font-weight: bold;
            margin: 0 5pt;
        }

        .notice-text {
            color: #173253;
        }
    </style>
</head>

<body>

@php
    $logo = public_path('images/institucional/logo-colegio.png');

    $nombreResponsable = trim(
        ($pedido->responsable?->persona?->nombres ?? '')
        . ' ' .
        ($pedido->responsable?->persona?->apellidos ?? '')
    );
@endphp

<!-- ENCABEZADO INSTITUCIONAL -->

<table class="header">
    <tr>
        <td class="logo-area">
            @if(is_file($logo))
                <img
                    src="{{ $logo }}"
                    alt="Colegio Mixto El Jardín"
                >
            @endif
        </td>

        <td class="school">
            <div class="school-name">
                COLEGIO MIXTO EL JARDÍN
            </div>

            <div class="school-place">
                SAN RAYMUNDO
            </div>

            <div class="school-rule"></div>

            <div class="school-motto">
                FORMACIÓN · VALORES · FUTURO
            </div>
        </td>
    </tr>
</table>

<!-- TÍTULO -->

<div class="title">
    <div class="title-text">
        IDENTIFICACIÓN DE PAQUETE · UNIFORMES
    </div>
</div>

<!-- NÚMERO DE PEDIDO -->

<div class="number-wrap">
    <div class="number-box">
        {{ $pedido->numero }}
    </div>
</div>

<!-- INFORMACIÓN DEL PAQUETE -->

<div class="info">
    <table class="info-table">
        <tr>
            <td class="info-left">

                <div class="info-line">
                    <span class="info-label">Estudiante:</span>
                    {{ $pedido->estudiante?->nombre_completo }}
                </div>

                <div class="info-line">
                    <span class="info-label">Preparado:</span>
                    {{ $pedido->preparado_at?->format('d/m/Y H:i') }}
                </div>

            </td>

            <td class="info-right">

                <div class="info-line">
                    <span class="info-label">Responsable:</span>
                    {{ $nombreResponsable }}
                </div>

                <div class="info-line">
                    <span class="info-label">Recoger antes de:</span>
                    {{ $pedido->vence_at?->format('d/m/Y H:i') }}
                </div>

            </td>
        </tr>
    </table>
</div>

<!-- CONTENIDO DEL PAQUETE -->

<table class="items">
    <thead>
        <tr>
            <th style="width: 9%;">No.</th>
            <th style="width: 35%;">Prenda</th>
            <th style="width: 16%;">Talla</th>
            <th style="width: 24%;">Color</th>
            <th style="width: 16%;">Cantidad</th>
        </tr>
    </thead>

    <tbody>
        @foreach($pedido->detalles as $detalle)
            <tr>
                <td class="center">
                    {{ $loop->iteration }}
                </td>

                <td>
                    {{
                        $detalle->producto?->uniforme?->nombre
                        ?? $detalle->producto?->nombre
                        ?? 'Uniforme'
                    }}
                </td>

                <td class="center">
                    {{ $detalle->producto?->talla }}
                </td>

                <td class="center">
                    {{
                        $detalle->producto?->uniforme?->color
                        ?? $detalle->producto?->color
                        ?? '—'
                    }}
                </td>

                <td class="center">
                    {{ $detalle->cantidad }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- AVISO DE IDENTIFICACIÓN -->

<div class="notice">

    <span class="notice-title">
        LISTO PARA RECOGER
    </span>

    <span class="notice-divider">|</span>

    <span class="notice-text">
        Documento de identificación interna, no acredita pago.
        Verificar el estado vigente del pedido antes de entregar.
    </span>

</div>

</body>
</html>
