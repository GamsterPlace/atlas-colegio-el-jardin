<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<style>
@page { size: letter portrait; margin: 0; }
* { box-sizing: border-box; }
body { margin: 0; font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #183451; }
.membrete { position: absolute; top: 0; left: 0; width: 612pt; height: 792pt; z-index: -1; }
.contenido { position: absolute; top: 143pt; left: 53pt; width: 506pt; }
h1 { font-size: 17px; text-align: center; margin: 0 0 6px; color: #163b70; }
.subtitulo { text-align: center; font-size: 9px; color: #68788c; margin-bottom: 15px; }
.seccion { margin-top: 15px; font-size: 11px; font-weight: bold; border-bottom: 1.5px solid #244d78; padding-bottom: 5px; }
table { width: 100%; border-collapse: collapse; margin-top: 8px; }
td { padding: 7px 8px; border-bottom: 1px solid #dce4ee; vertical-align: top; }
.etiqueta { color: #68788c; font-size: 8px; }
.valor { font-weight: bold; padding-top: 3px; }
.acceso { margin-top: 10px; border: 1px solid #d6e0ec; padding: 10px 12px; page-break-inside: avoid; }
.acceso-titulo { font-weight: bold; color: #163b70; margin-bottom: 5px; }
.acceso p { margin: 4px 0; }
.aviso { margin-top: 13px; color: #56667a; font-size: 8px; line-height: 1.5; }
</style>
</head>
<body>
@php
    $rutaMembrete = public_path('images/Institucional/hoja-membretada-atlas.png');
    $membrete = is_file($rutaMembrete) ? 'data:image/png;base64,'.base64_encode(file_get_contents($rutaMembrete)) : null;
@endphp
@if($membrete)
<img class="membrete" src="{{ $membrete }}" alt="">
@endif
<div class="contenido">
    <h1>FICHA DE INSCRIPCIÓN</h1>
    <div class="subtitulo">Colegio Mixto El Jardín · Sistema institucional ATLAS · Inscripción No. {{ $inscripcion->id }}</div>
    <div class="seccion">Información académica</div>
    <table>
        <tr>
            <td><div class="etiqueta">Estudiante</div><div class="valor">{{ $inscripcion->estudiante->nombre_completo }}</div></td>
            <td><div class="etiqueta">Código histórico</div><div class="valor">{{ $inscripcion->estudiante->codigo }}</div></td>
        </tr>
        <tr>
            <td><div class="etiqueta">Número de carné</div><div class="valor">{{ $inscripcion->estudiante->numero_carnet ?? '—' }}</div></td>
            <td><div class="etiqueta">Fecha de inscripción</div><div class="valor">{{ $inscripcion->fecha_inscripcion?->format('d/m/Y') ?? '—' }}</div></td>
        </tr>
        <tr>
            <td><div class="etiqueta">Ciclo escolar</div><div class="valor">{{ $inscripcion->ciclo_escolar->nombre ?? $inscripcion->ciclo_escolar_id }}</div></td>
            <td><div class="etiqueta">Grado y sección</div><div class="valor">{{ $inscripcion->seccion->grado->nombre ?? '—' }} · {{ $inscripcion->seccion->nombre }}</div></td>
        </tr>
        <tr>
            <td colspan="2"><div class="etiqueta">Responsable financiero</div><div class="valor">{{ trim(($inscripcion->responsable_financiero?->persona?->nombres ?? '').' '.($inscripcion->responsable_financiero?->persona?->apellidos ?? '')) ?: 'No registrado' }}</div></td>
        </tr>
    </table>
    <div class="seccion">Credenciales de acceso al portal de padres de familia</div>
    @forelse($accesos as $acceso)
        <div class="acceso">
            <div class="acceso-titulo">{{ $acceso['parentesco'] === 'MADRE' ? 'Madre' : 'Padre' }}: {{ $acceso['nombre'] }}</div>
            <p><strong>Usuario:</strong> {{ $acceso['usuario'] }}</p>
            <p><strong>Contraseña:</strong> {{ $acceso['password'] ?: 'Utilizar su contraseña institucional' }}</p>
            @if(!$acceso['activo'])
                <p><strong>Estado:</strong> Cuenta inactiva; solicitar habilitación a Dirección.</p>
            @elseif($acceso['password'])
                <p><strong>Importante:</strong> Contraseña temporal. Debe cambiarla en el primer ingreso.</p>
            @endif
        </div>
    @empty
        <p>No hay cuentas de padre o madre asociadas a esta inscripción.</p>
    @endforelse
    <div class="aviso">Acceso: utilice el portal institucional y las credenciales asignadas. No comparta su contraseña. Las contraseñas temporales se entregan únicamente en la emisión inicial; no podrán recuperarse mediante reimpresión de esta ficha. Este documento no constituye constancia de pago.</div>
</div>
</body>
</html>
