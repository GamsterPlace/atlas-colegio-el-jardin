<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<style>
@page { size: letter portrait; margin: 0; }
* { box-sizing: border-box; }
body { margin: 0; font-family: DejaVu Sans, sans-serif; color: #171717; font-size: 11px; }
.pagina { position: relative; width: 612pt; height: 792pt; page-break-after: always; overflow: hidden; }
.pagina:last-child { page-break-after: auto; }
.membrete { position: absolute; top: 0; left: 0; width: 612pt; height: 792pt; z-index: -1; }
.contenido { position: absolute; top: 142pt; left: 53pt; width: 506pt; }
.titulo { font-size: 12px; font-weight: bold; text-align: center; text-decoration: underline; letter-spacing: .15pt; margin: 0 0 13pt; }
.seccion { font-weight: bold; font-size: 10.5px; text-decoration: underline; margin: 15pt 0 10pt; }
/* Filas absolutas: sin flex, inline-block ni tablas de ancho impredecible. */
.fila { position:relative; width:506pt; height:18pt; margin:0 0 7pt; font-size:10.5px; page-break-inside:avoid; }
.fila .etiqueta { position:absolute; top:2pt; left:0; white-space:nowrap; font-size:10px; }
.fila .dato { position:absolute; top:0; height:16pt; line-height:14pt; padding-left:3pt; border-bottom:.65pt solid #555; white-space:nowrap; overflow:hidden; font-size:10px; }
.fila .etiqueta-2 { position:absolute; top:2pt; white-space:nowrap; font-size:10px; }
.fila .dato-2 { position:absolute; top:0; height:16pt; line-height:14pt; padding-left:3pt; border-bottom:.65pt solid #555; white-space:nowrap; overflow:hidden; font-size:10px; }

.check { font-size: 10px; margin-left: 4pt; }
.checkmark { font-family: DejaVu Sans, sans-serif; }
.sangria { margin-left: 0; }
.pagina-dos .contenido { top: 147pt; }
.intro { margin: 18pt 0 12pt; line-height: 16pt; font-size:10px; }
.firmas { position: absolute; top: 635pt; left: 75pt; width: 462pt; }
.firma { display: inline-block; width: 215pt; text-align: center; }
.firma-linea { border-top: 1px solid #333; margin: 0 24pt 5pt; }
</style>
</head>
<body>
@php
    $estudiante = $inscripcion->estudiante;
    $vinculos = $estudiante->registrosEstudianteResponsable ?? collect();
    $padre = $vinculos->first(fn ($v) => strtoupper((string) $v->parentesco) === 'PADRE')?->responsable?->persona;
    $madre = $vinculos->first(fn ($v) => strtoupper((string) $v->parentesco) === 'MADRE')?->responsable?->persona;
    $responsableFinanciero = $inscripcion->responsable_financiero?->persona;
    $nombrePersona = fn ($p) => $p ? trim(($p->nombres ?? '').' '.($p->apellidos ?? '')) : '';
    $medica = $estudiante->registrosInformacionMedicaEstudiante;
    $complementaria = $estudiante->registrosInformacionComplementariaEstudiante;
    $emergencias = $estudiante->registrosContactoEmergencia ?? collect();
    $autorizados = $estudiante->registrosPersonaAutorizadaRetiro ?? collect();
    $fechaNacimiento = $estudiante->fecha_nacimiento;
    $edad = $fechaNacimiento ? $fechaNacimiento->age : '';
    $grado = $inscripcion->seccion?->grado;
    $nivel = $grado?->nivel?->nombre ?? $grado?->nivel_academico?->nombre ?? '';
    if (!$nivel && $grado?->nivel_id) {
        $nivel = \Illuminate\Support\Facades\DB::table('niveles')->where('id', $grado->nivel_id)->value('nombre') ?? '';
    }
    $nivelNormalizado = mb_strtoupper(trim($nivel));
    $ciclo = $inscripcion->ciclo_escolar?->nombre ?? $inscripcion->ciclo_escolar_id;
    $membrete = public_path('images/Institucional/hoja-membretada-atlas.png');
    $membreteSrc = is_file($membrete) ? 'data:image/png;base64,'.base64_encode(file_get_contents($membrete)) : '';
    $contacto = $emergencias->first();
@endphp

<div class="pagina">
@if($membreteSrc)<img class="membrete" src="{{ $membreteSrc }}" alt="">@endif
<div class="contenido">
    <div class="titulo">SOLICITUD DE MATRÍCULA CICLO ESCOLAR {{ $ciclo }}</div>
    <div class="seccion">DATOS GENERALES</div>

    <div class="fila"><span class="etiqueta">Nombre del estudiante:</span><span class="dato" style="left:94pt;right:0">{{ $estudiante->nombre_completo }}</span></div>
    <div class="fila"><span class="etiqueta">Lugar y Fecha de Nacimiento:</span><span class="dato" style="left:117pt;right:0">{{ trim((string) data_get($estudiante, 'lugar_nacimiento')).($fechaNacimiento ? ' · '.$fechaNacimiento->format('d/m/Y') : '') }}</span></div>
    <div class="fila"><span class="etiqueta">Grado:</span><span class="dato" style="left:38pt;width:257pt">{{ $grado?->nombre ?? $inscripcion->seccion?->nombre }}</span><span class="etiqueta-2" style="left:306pt">Nivel:</span><span class="dato-2" style="left:341pt;right:0">{{ $nivel ?: '—' }}</span></div>
    <div class="fila"><span class="etiqueta">CUI en Certificado de Nacimiento:</span><span class="dato" style="left:132pt;width:163pt">{{ data_get($estudiante, 'cui', '') }}</span><span class="etiqueta-2" style="left:306pt">Edad Cumplida:</span><span class="dato-2" style="left:366pt;right:0">{{ $edad }}</span></div>
    <div class="fila"><span class="etiqueta">Religión que profesa el estudiante:</span><span class="dato" style="left:144pt;right:0">{{ data_get($complementaria, 'religion', '') }}</span></div>
    <div class="fila"><span class="etiqueta">Dirección del estudiante:</span><span class="dato" style="left:106pt;right:0">{{ $estudiante->direccion }}</span></div>
    <div class="fila"><span class="etiqueta">&nbsp;</span><span class="dato" style="left:38pt;width:257pt"></span><span class="etiqueta-2" style="left:306pt">Teléfono de la casa:</span><span class="dato-2" style="left:389pt;right:0">{{ data_get($complementaria, 'telefono_casa', '') }}</span></div>
    <div class="fila"><span class="etiqueta">Persona responsable:</span><span class="dato" style="left:83pt;width:212pt">{{ $nombrePersona($responsableFinanciero) }}</span><span class="etiqueta-2" style="left:306pt">Tel. Emergencia:</span><span class="dato-2" style="left:374pt;right:0">{{ $contacto?->telefono ?? '' }}</span></div>
    <div class="fila"><span class="etiqueta">Medio de transporte que utiliza el alumno:</span><span class="dato" style="left:172pt;right:0">{{ data_get($complementaria, 'transporte', '') }}</span></div>
    <div class="fila"><span class="etiqueta">Persona que sostiene gastos y colegiatura:</span><span class="dato" style="left:172pt;right:0">{{ $nombrePersona($responsableFinanciero) }}</span></div>

    <div class="seccion">Datos de salud</div>
    <div class="fila"><span class="etiqueta">Tipo de sangre del estudiante:</span><span class="dato" style="left:125pt;right:0">{{ $medica?->tipo_sangre ?? '' }}</span></div>
    <div class="fila"><span class="etiqueta">Padece alguna enfermedad o alergia:</span><span class="dato" style="left:168pt;right:0">{{ $medica?->alergias ?? '' }}</span></div>
    <div class="fila"><span class="etiqueta">Toma actualmente algún medicamento:</span><span class="dato" style="left:178pt;right:0">{{ $medica?->medicamentos ?? '' }}</span></div>
    <div class="fila"><span class="etiqueta">Cuenta con seguro médico:</span><span class="dato" style="left:106pt;right:0">{{ data_get($medica, 'seguro_medico', '') }}</span></div>
    <div class="fila"><span class="etiqueta">A dónde llevar en caso de emergencia:</span><span class="dato" style="left:152pt;right:0">{{ $medica?->indicaciones_emergencia ?? '' }}</span></div>

    <div class="seccion">DATOS FAMILIARES</div>
    <div class="fila"><span class="etiqueta">Nombre del padre:</span><span class="dato" style="left:72pt;width:223pt">{{ $nombrePersona($padre) }}</span><span class="etiqueta-2" style="left:306pt">Edad:</span><span class="dato-2" style="left:341pt;right:0">{{ $padre?->fecha_nacimiento?->age ?? '' }}</span></div>
    <div class="fila"><span class="etiqueta">Dirección de casa:</span><span class="dato" style="left:78pt;right:0">{{ $padre?->direccion ?? '' }}</span></div>
    <div class="fila"><span class="etiqueta">Teléfono para localizarlo:</span><span class="dato" style="left:106pt;width:189pt">{{ $padre?->telefono ?? '' }}</span><span class="etiqueta-2" style="left:306pt">No. de DPI:</span><span class="dato-2" style="left:355pt;right:0">{{ $padre?->cui ?? '' }}</span></div>
</div>
</div>

<div class="pagina pagina-dos">
@if($membreteSrc)<img class="membrete" src="{{ $membreteSrc }}" alt="">@endif
<div class="contenido">
    <div class="seccion" style="margin-top:0">DATOS FAMILIARES (continuación)</div>
    <div class="fila"><span class="etiqueta">Nombre de la madre:</span><span class="dato" style="left:79pt;width:216pt">{{ $nombrePersona($madre) }}</span><span class="etiqueta-2" style="left:306pt">Edad:</span><span class="dato-2" style="left:341pt;right:0">{{ $madre?->fecha_nacimiento?->age ?? '' }}</span></div>
    <div class="fila"><span class="etiqueta">Dirección de casa:</span><span class="dato" style="left:78pt;right:0">{{ $madre?->direccion ?? '' }}</span></div>
    <div class="fila"><span class="etiqueta">Teléfono para localizarlo:</span><span class="dato" style="left:106pt;width:189pt">{{ $madre?->telefono ?? '' }}</span><span class="etiqueta-2" style="left:306pt">No. de DPI:</span><span class="dato-2" style="left:355pt;right:0">{{ $madre?->cui ?? '' }}</span></div>

    <div class="fila"><span class="etiqueta" style="left:0pt">Número de hermanos:</span><span class="dato" style="left:105pt;width:60pt">{{ data_get($complementaria, 'numero_hermanos', '') }}</span><span class="etiqueta" style="left:175pt">Mayores:</span><span class="dato" style="left:220pt;width:75pt">{{ data_get($complementaria, 'hermanos_mayores', '') }}</span><span class="etiqueta" style="left:310pt">Menores:</span><span class="dato" style="left:355pt;width:151pt">{{ data_get($complementaria, 'hermanos_menores', '') }}</span></div>
    <div class="fila"><span class="etiqueta">Hermanos en el colegio:</span><span class="dato" style="left:98pt;right:0">{{ filled($complementaria?->hermanos_inscritos) ? 'Sí' : '' }}</span></div>
    <div class="fila"><span class="etiqueta">Especificar nombre y grado:</span><span class="dato" style="left:113pt;right:0">{{ data_get($complementaria, 'hermanos_inscritos', '') }}</span></div>

    <p class="intro">Personas a las que se pueda llamar en caso de emergencia o que puedan recoger al alumno(a) de no hacerlo los padres:</p>
    @for($i=0; $i<3; $i++)
        @php
            $autorizado = $autorizados->values()->get($i);
            $emergencia = $emergencias->values()->get($i);
            $nombre = $autorizado?->nombre ?? $emergencia?->nombre ?? '';
            $parentesco = $autorizado?->parentesco ?? $emergencia?->parentesco ?? '';
            $telefono = $autorizado?->telefono ?? $emergencia?->telefono ?? '';
        @endphp
        <div class="fila"><span class="etiqueta" style="left:0pt">Nombre:</span><span class="dato" style="left:30pt;width:141pt">{{ $nombre }}</span><span class="etiqueta" style="left:177pt">Parentesco:</span><span class="dato" style="left:220pt;width:122pt">{{ $parentesco }}</span><span class="etiqueta" style="left:348pt">Tel:</span><span class="dato" style="left:376pt;width:130pt">{{ $telefono }}</span></div>
    @endfor
</div>
<div class="firmas">
    <div class="firma"><div class="firma-linea"></div>Firma de Padre</div>
    <div class="firma"><div class="firma-linea"></div>Firma de Madre</div>
</div>
</div>
</body>
</html>
