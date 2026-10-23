
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Comprobante de uniformes {{ $pedido->numero }}</title>

<style>
    /* HOJA CARTA VERTICAL */
    @page {
        size: letter portrait;
        margin: 0;
    }

    html {
        margin: 0;
        padding: 0;
    }

    body {
        margin: 0;
        padding: 28pt 43pt 25pt 43pt;
        font-family: DejaVu Sans, sans-serif;
        color: #173253;
        font-size: 9pt;
        line-height: 1.3;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    tr {
        page-break-inside: avoid;
    }

    /* ENCABEZADO */
    .header {
        margin-bottom: 9pt;
    }

    .header td {
        vertical-align: middle;
        padding: 2pt 6pt;
        border: 0;
    }

    .logo-area {
        width: 25%;
        text-align: center;
        border-right: 1pt solid #d9c8b6 !important;
    }

    .logo-area img {
        max-width: 95pt;
        max-height: 62pt;
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
        margin-top: 3pt;
    }

    .school-rule {
        border-top: 2pt solid #16654d;
        border-bottom: 1pt solid #bd243e;
        height: 1pt;
        margin: 6pt 10pt 4pt;
    }

    .school-motto {
        font-size: 6.5pt;
        letter-spacing: 1.4pt;
    }

    /* TÍTULO */
    .section-title {
        background: #0b365f;
        color: #ffffff;
        font-size: 12pt;
        font-weight: bold;
        text-align: center;
        padding: 9pt 8pt;
        margin-bottom: 9pt;
    }

    /* INFORMACIÓN DEL PEDIDO */
    .info {
        border: 1pt solid #b9cbdc;
        background: #f4f8fc;
        padding: 8pt 10pt;
        margin-bottom: 10pt;
    }

    .info-table {
        table-layout: fixed;
    }

    .info-table td {
        border: 0;
        vertical-align: top;
        padding: 3pt 4pt;
        font-size: 8.5pt;
    }

    .info-left {
        width: 57%;
        border-right: 1pt solid #bdcfe0 !important;
    }

    .info-right {
        padding-left: 9pt !important;
    }

    .info-line {
        margin-bottom: 3pt;
    }

    .info-label {
        font-weight: bold;
        color: #0b3159;
    }

    .order-id {
        font-size: 7.5pt;
        word-wrap: break-word;
    }

    /* TABLA DE UNIFORMES */
    .items {
        table-layout: fixed;
        margin-bottom: 7pt;
    }

    .items th {
        background: #0b365f;
        color: #ffffff;
        font-size: 8pt;
        padding: 6pt 4pt;
        border: 1pt solid #a9bed2;
    }

    .items td {
        border: 1pt solid #c0cfde;
        padding: 5pt;
        font-size: 8pt;
        word-wrap: break-word;
    }

    .items tbody tr:nth-child(odd) {
        background: #f0f5fa;
    }

    .center {
        text-align: center;
    }

    .right {
        text-align: right;
    }

    /* TOTAL */
    .total-wrap {
        text-align: right;
        margin-bottom: 10pt;
    }

    .total-box {
        display: inline-block;
        padding: 7pt 12pt;
        background: #fae9d7;
        color: #092c52;
        font-size: 13pt;
        font-weight: bold;
    }

    .total-box small {
        font-size: 9pt;
        margin-right: 15pt;
    }

    /* INSTRUCCIONES DE PAGO */
    .payment {
        border: 1pt solid #bdcfe0;
        margin-bottom: 10pt;
        page-break-inside: avoid;
    }

    .payment-title {
        background: #0b365f;
        color: #ffffff;
        font-size: 10pt;
        font-weight: bold;
        padding: 8pt 11pt;
    }

    .payment-body {
        padding: 8pt 12pt;
    }

    .steps td {
        border: 0;
        vertical-align: top;
        padding: 3pt 2pt;
        font-size: 8.5pt;
        line-height: 1.35;
    }

    .step-num {
        width: 22pt;
        color: #c51f3b;
        font-size: 13pt !important;
        font-weight: bold;
    }

    .account-block {
        text-align: center;
        margin: 7pt 0;
    }

    .account-number {
        display: inline-block;
        background: #0b365f;
        color: #ffffff;
        font-size: 12pt;
        font-weight: bold;
        padding: 5pt 13pt;
    }

    .account-owner {
        font-size: 8pt;
        margin-top: 3pt;
    }

    .account-comment {
        display: inline-block;
        background: #fae9d7;
        padding: 4pt 11pt;
        margin-top: 4pt;
        color: #8b3434;
        font-size: 8pt;
    }

    /* ADVERTENCIA */
    .notice {
        border: 1pt solid #c51f3b;
        background: #fff7f8;
        padding: 9pt 11pt;
        font-size: 8pt;
        line-height: 1.4;
        margin-bottom: 0;
        page-break-inside: avoid;
    }

    .notice-heading {
        color: #b91c35;
        font-weight: bold;
        font-size: 9pt;
        margin-bottom: 4pt;
    }
</style>
</head>

<body>

@php
    $logo = public_path('images/institucional/logo-colegio.png');
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

<!-- TÍTULO DEL COMPROBANTE -->
<div class="section-title">
    COMPROBANTE DE PEDIDO DE UNIFORMES
</div>

<!-- INFORMACIÓN DEL PEDIDO -->
<div class="info">
    <table class="info-table">
        <tr>
            <td class="info-left">

                <div class="info-line">
                    <span class="info-label">Pedido:</span>
                    <span class="order-id">
                        {{ $pedido->numero }}
                    </span>
                </div>

                <div class="info-line">
                    <span class="info-label">Estudiante:</span>
                    {{ $pedido->estudiante?->nombre_completo }}
                </div>

                <div class="info-line">
                    <span class="info-label">Responsable:</span>
                    {{
                        trim(
                            ($pedido->responsable?->persona?->nombres ?? '')
                            . ' ' .
                            ($pedido->responsable?->persona?->apellidos ?? '')
                        )
                    }}
                </div>

            </td>

            <td class="info-right">

                <div class="info-line">
                    <span class="info-label">Fecha de emisión:</span>
                    <br>
                    {{ $pedido->created_at?->format('d/m/Y H:i') }}
                </div>

                <div class="info-line">
                    <span class="info-label">Vencimiento:</span>
                    <br>
                    {{ $pedido->vence_at?->format('d/m/Y H:i') }}
                </div>

            </td>
        </tr>
    </table>
</div>

<!-- DETALLE DE UNIFORMES -->
<table class="items">
    <thead>
        <tr>
            <th style="width: 7%;">No.</th>
            <th style="width: 31%;">Uniforme</th>
            <th style="width: 11%;">Talla</th>
            <th style="width: 12%;">Cantidad</th>
            <th style="width: 19%;">Precio unitario</th>
            <th style="width: 20%;">Subtotal</th>
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
                    {{ $detalle->cantidad }}
                </td>

                <td class="right">
                    Q {{ number_format($detalle->precio_unitario, 2) }}
                </td>

                <td class="right">
                    Q {{ number_format($detalle->subtotal, 2) }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- TOTAL DEL PEDIDO -->
<div class="total-wrap">
    <span class="total-box">
        <small>TOTAL:</small>
        Q {{ number_format($pedido->total, 2) }}
    </span>
</div>

<!-- INSTRUCCIONES DE PAGO -->
<div class="payment">

    <div class="payment-title">
        INSTRUCCIONES PARA PAGO CON DEPÓSITO BANCARIO
    </div>

    <div class="payment-body">

        <table class="steps">

            <tr>
                <td class="step-num">
                    1.
                </td>

                <td>
                    Para realizar el pago mediante depósito bancario,
                    presente este comprobante al receptor de la
                    <strong>Cooperativa ECOSABA</strong>
                    y solicite el depósito a la cuenta oficial del colegio:

                    <div class="account-block">

                        <div class="account-number">
                            125-1100-0301-3474
                        </div>

                        <div class="account-owner">
                            A nombre de Colegio Mixto El Jardín
                        </div>

                        <div class="account-comment">
                            Comentario:
                            <strong>Pago de Uniformes</strong>
                        </div>

                    </div>

                    Engrape la boleta bancaria original a este
                    comprobante y presente ambos documentos
                    al recoger su uniforme.
                </td>
            </tr>

            <tr>
                <td class="step-num">
                    2.
                </td>

                <td>
                    Si prefiere pagar en efectivo,
                    preséntese al colegio para recoger su uniforme
                    y cancelar el importe correspondiente.
                </td>
            </tr>

        </table>

    </div>
</div>

<!-- ADVERTENCIA -->
<div class="notice">

    <div class="notice-heading">
        DOCUMENTO PARA PAGO / IDENTIFICACIÓN DE PEDIDO
    </div>

    Este comprobante no constituye constancia de pago.

    El pago se registra en el colegio en efectivo
    o presentando la boleta bancaria original del depósito.

    La reserva vence a las 72 horas de la creación,
    incluso si el pedido ya fue pagado.

</div>

</body>
</html>
