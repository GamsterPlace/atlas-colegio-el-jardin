<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<style>
@page { size: letter portrait; margin: 9pt 10pt; }
body { margin: 0; padding: 0; font-family: DejaVu Sans, sans-serif; color: #082c54; }
.pagina { page-break-after: always; }
.pagina:last-child { page-break-after: auto; }
.boleta { position: relative; width: 590pt; height: 251pt; margin: 0 0 4pt 0; overflow: hidden; border: .6pt dashed #c8d3df; page-break-inside: avoid; }
/* Todas las dimensiones interiores son absolutas: no hay tablas, floats ni Sflex. */
.cabecera-azul { position:absolute; top:4pt; left:5pt; width:320pt; height:49pt; background:#073660; border-bottom-right-radius:28pt; }
.cabecera-roja { position:absolute; top:4pt; left:325pt; width:75pt; height:4pt; background:#c71942; }
.logo-banco { position:absolute; top:8pt; left:14pt; width:205pt; height:40pt; }
.logo-escuela { position:absolute; top:4pt; left:488pt; width:91pt; height:48pt; }
.fecha { position:absolute; top:15pt; left:352pt; width:127pt; height:23pt; border:.7pt solid #b5c9dc; border-radius:5pt; background:white; text-align:center; line-height:17pt; padding-top:3pt; box-sizing:border-box; font-size:8pt; font-weight:bold; }
.titulo { position:absolute; top:58pt; left:8pt; width:574pt; height:20pt; text-align:center; font-size:12.5pt; line-height:19pt; font-weight:bold; white-space:nowrap; }
.panel-alumno { position:absolute; top:82pt; left:12pt; width:301pt; height:133pt; border:.7pt solid #bdcede; border-radius:6pt; }
.marca-agua { position:absolute; top:-13pt; left:70pt; width:158pt; height:158pt; opacity:0.10; }
.codigo { position:absolute; top:12pt; left:13pt; font-size:8pt; }
.carne { position:absolute; top:32pt; left:13pt; font-size:8pt; }
.etiqueta { display:inline-block; width:53pt; font-weight:bold; }
.nombre { position:absolute; top:55pt; left:8pt; width:283pt; height:22pt; font-size:10.5pt; line-height:12pt; font-weight:bold; text-align:center; }
.linea-alumno { position:absolute; top:81pt; left:19pt; width:263pt; border-top:.7pt solid #8ca5bf; }
.rotulo-alumno { position:absolute; top:84pt; left:0; width:301pt; font-size:7pt; font-weight:bold; text-align:center; }
.grado { position:absolute; top:98pt; left:73pt; width:155pt; height:17pt; border:.7pt solid #bdcede; border-radius:4pt; background:#edf3f8; line-height:12pt; padding-top:2pt; box-sizing:border-box; font-size:7.6pt; font-weight:bold; text-align:center; }
.rotulo-grado { position:absolute; top:119pt; left:0; width:301pt; line-height:9pt; text-align:center; font-size:7pt; font-weight:bold; }
.panel-banco { position:absolute; top:82pt; left:324pt; width:254pt; height:49pt; border:.7pt solid #bfd0df; border-radius:5pt; background:#e5f0f8; text-align:center; }
.banco-texto { position:absolute; top:3pt; left:0; width:254pt; font-size:7pt; }
.cuenta { position:absolute; top:13pt; left:21pt; width:212pt; height:19pt; line-height:14pt; padding-top:2pt; box-sizing:border-box; border-radius:4pt; background:#073660; color:white; font-size:11.5pt; font-weight:bold; text-align:center; }
.titular { position:absolute; top:36pt; left:0; width:254pt; height:9pt; line-height:9pt; font-size:6.5pt; text-align:center; }
.detalle { position:absolute; top:133pt; left:324pt; width:254pt; height:49pt; border:.6pt solid #e2d4c5; background:#fff5e9; }
.fila { position:absolute; left:0; width:254pt; height:16pt; border-bottom:.5pt solid #e2d4c5; font-size:7.5pt; }
.fila-uno { top:0; } .fila-dos { top:16pt; } .fila-tres { top:32pt; border-bottom:0; }
.concepto { position:absolute; top:3pt; left:8pt; width:105pt; font-weight:bold; }
.importe { position:absolute; top:3pt; left:118pt; width:127pt; text-align:right; font-weight:bold; }
.separacion { position:absolute; top:0; left:118pt; height:16pt; border-left:.5pt solid #e2d4c5; }
.nota { position:absolute; top:185pt; left:324pt; width:242pt; height:19pt; border-radius:4pt; background:#fff0ed; padding:4pt 6pt; font-size:6.5pt; line-height:9pt; color:#752323; }
.firma-linea { position:absolute; top:228pt; left:350pt; width:124pt; border-top:.6pt solid #879eb8; }
.firma-texto { position:absolute; top:233pt; left:341pt; width:142pt; text-align:center; font-size:6.5pt; font-weight:bold; }
.pie { position:absolute; top:222pt; left:0; width:311pt; height:24pt; line-height:15pt; padding:3pt 0 0 23pt; box-sizing:border-box; background:#073660; border-top-right-radius:27pt; color:white; font-size:8.5pt; font-weight:bold; }
.pie-rojo { position:absolute; top:222pt; left:0; width:8pt; height:24pt; background:#c71942; }
.numero { position:absolute; top:221pt; left:488pt; width:90pt; height:23pt; line-height:15pt; padding-top:3pt; box-sizing:border-box; border:.6pt solid #e8d8c6; border-radius:5pt; background:#fff3e7; text-align:center; font-size:7pt; font-weight:bold; }
.invalida { position:absolute; top:97pt; left:110pt; width:370pt; padding:9pt; border:2pt solid #ac2030; background:white; color:#ac2030; text-align:center; font-size:10pt; font-weight:bold; }
</style>
</head>
<body>
@php
    $estudiante = $inscripcion->estudiante;
    $grado = $inscripcion->seccion?->grado?->nombre ?? $inscripcion->seccion?->nombre ?? '—';
    $rutaEcosaba = public_path('images/Institucional/logo-ecosaba.png');
    $rutaColegio = public_path('images/Institucional/logo-colegio.png');
    $logoEcosaba = is_file($rutaEcosaba) ? 'data:image/png;base64,'.base64_encode(file_get_contents($rutaEcosaba)) : null;
    $logoColegio = is_file($rutaColegio) ? 'data:image/png;base64,'.base64_encode(file_get_contents($rutaColegio)) : null;
    $copias = ['Copia para el colegio', 'Copia para el registro de Ecosaba', 'Copia para Padre de familia'];
@endphp
@foreach($hojas as $hoja)
@php
    $esColegiatura = str_contains(mb_strtoupper((string) $hoja->concepto_impreso), 'COLEGIATURA');
    $titulo = $esColegiatura ? 'BOLETA DE PAGO DE COLEGIATURA' : 'BOLETA DE PAGO DE '.($hoja->concepto_impreso ?: 'SERVICIOS');
    $periodo = $hoja->periodo_impreso ?: ($hoja->fecha_vencimiento_impresa?->translatedFormat('F Y') ?? '—');
@endphp
<div class="pagina">
@foreach($copias as $copia)
<div class="boleta">
    <div class="cabecera-azul"></div><div class="cabecera-roja"></div>
    @if($logoEcosaba)<img class="logo-banco" src="{{ $logoEcosaba }}" alt="ECOSABA">@endif
    @if($logoColegio)<img class="logo-escuela" src="{{ $logoColegio }}" alt="Colegio Mixto El Jardín">@endif
    <div class="fecha">Fecha: ____ / ____ / ______</div>
    <div class="titulo">{{ $titulo }}</div>
    <div class="panel-alumno">
        @if($logoColegio)<img class="marca-agua" src="{{ $logoColegio }}" alt="">@endif
        <div class="codigo"><span class="etiqueta">Código:</span> {{ $estudiante?->codigo ?? '—' }}</div>
        <div class="carne"><span class="etiqueta">Carné:</span> {{ $estudiante?->numero_carnet ?? '—' }}</div>
        <div class="nombre">{{ $hoja->nombre_estudiante_impreso ?: ($estudiante?->nombre_completo ?? '—') }}</div>
        <div class="linea-alumno"></div><div class="rotulo-alumno">Estudiante</div>
        <div class="grado">{{ $grado }}</div><div class="rotulo-grado">Grado</div>
    </div>
    <div class="panel-banco">
        <div class="banco-texto">El pago se realiza a la cuenta</div>
        <div class="cuenta">125-1100-0301-3474</div>
        <div class="titular">a nombre de Colegio Mixto El Jardín</div>
    </div>
    <div class="detalle">
        <div class="fila fila-uno"><div class="concepto">Mes de:</div><div class="separacion"></div><div class="importe">{{ $periodo }}</div></div>
        <div class="fila fila-dos"><div class="concepto">{{ $esColegiatura ? 'Colegiatura:' : 'Importe:' }}</div><div class="separacion"></div><div class="importe">Q. {{ number_format((float) $hoja->monto_impreso, 2) }}</div></div>
        <div class="fila fila-tres"><div class="concepto">Otros cargos:</div><div class="separacion"></div><div class="importe">Q. 0.00</div></div>
    </div>
    <div class="nota"><b>Nota:</b> {{ $esColegiatura ? 'se cobrará Q.50.00 de mora si paga despues del día 10 del mes de la boleta' : 'Utilizar esta boleta únicamente para el concepto y monto indicados.' }}</div>
    <div class="firma-linea"></div><div class="firma-texto">Firma y sello de receptor</div>
    <div class="pie">{{ $copia }}</div><div class="pie-rojo"></div>
    <div class="numero">No. {{ $hoja->codigo }}</div>
    @if($hoja->estado !== 'VIGENTE')<div class="invalida">DOCUMENTO {{ $hoja->estado }} — NO UTILIZAR PARA DEPÓSITO</div>@endif
</div>
@endforeach
</div>
@endforeach
</body>
</html>
