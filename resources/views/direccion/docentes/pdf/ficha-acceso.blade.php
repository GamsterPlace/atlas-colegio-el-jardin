<!DOCTYPE html>
<html lang="es"><head><meta charset="utf-8"><style>
@page { size: letter portrait; margin: 0; }
* { box-sizing: border-box; }
body { margin:0; font-family: DejaVu Sans, sans-serif; color:#183451; font-size:10px; }
.membrete { position:absolute; top:0; left:0; width:612pt; height:792pt; z-index:-1; }
.contenido { position:absolute; top:145pt; left:53pt; width:506pt; }
h1 { text-align:center; font-size:17px; color:#163b70; margin:0 0 6px; }
.subtitulo { text-align:center; color:#68788c; font-size:9px; margin-bottom:18px; }
h2 { font-size:11px; border-bottom:1.5px solid #244d78; padding-bottom:5px; margin-top:19px; }
table { width:100%; border-collapse:collapse; margin-top:8px; }
td { width:50%; padding:8px; border-bottom:1px solid #dce4ee; vertical-align:top; }
.etiqueta { color:#68788c; font-size:8px; }
.valor { font-size:10px; font-weight:bold; margin-top:4px; }
.acceso { margin-top:12px; border:1px solid #d6e0ec; padding:13px; }
.acceso p { margin:7px 0; }
.aviso { margin-top:17px; font-size:8px; line-height:1.6; color:#56667a; }
</style></head><body>
@php
$rutaMembrete = public_path('images/Institucional/hoja-membretada-atlas.png');
$membrete = is_file($rutaMembrete) ? 'data:image/png;base64,'.base64_encode(file_get_contents($rutaMembrete)) : null;
$persona = $docente->personal?->persona;
$personal = $docente->personal;
@endphp
@if($membrete)<img class="membrete" src="{{ $membrete }}" alt="">@endif
<div class="contenido">
<h1>FICHA INSTITUCIONAL DEL DOCENTE</h1>
<div class="subtitulo">Colegio Mixto El Jardín · Sistema institucional ATLAS · Registro No. {{ $docente->id }}</div>
<h2>Información personal</h2>
<table>
<tr><td><div class="etiqueta">Nombre completo</div><div class="valor">{{ trim(($persona?->nombres ?? '').' '.($persona?->apellidos ?? '')) }}</div></td><td><div class="etiqueta">CUI</div><div class="valor">{{ $persona?->cui ?: 'No registrado' }}</div></td></tr>
<tr><td><div class="etiqueta">Teléfono</div><div class="valor">{{ $persona?->telefono ?: 'No registrado' }}</div></td><td><div class="etiqueta">Correo electrónico</div><div class="valor">{{ $persona?->correo ?: 'No registrado' }}</div></td></tr>
<tr><td colspan="2"><div class="etiqueta">Dirección</div><div class="valor">{{ $persona?->direccion ?: 'No registrada' }}</div></td></tr>
</table>
<h2>Información laboral</h2>
<table>
<tr><td><div class="etiqueta">Código de empleado</div><div class="valor">{{ $personal?->codigo_empleado }}</div></td><td><div class="etiqueta">Cargo</div><div class="valor">{{ $personal?->cargo }}</div></td></tr>
<tr><td><div class="etiqueta">Fecha de ingreso</div><div class="valor">{{ $personal?->fecha_ingreso?->format('d/m/Y') ?? '—' }}</div></td><td><div class="etiqueta">Estado del docente</div><div class="valor">{{ $docente->activo ? 'ACTIVO' : 'INACTIVO' }}</div></td></tr>
</table>
<h2>Credenciales de acceso a ATLAS</h2>
<div class="acceso">
<p><strong>Usuario:</strong> {{ $usuario->nombre_usuario }}</p>
<p><strong>Contraseña:</strong> {{ $passwordTemporal ?: 'Utilizar su contraseña institucional' }}</p>
@if(!$usuario->activo)<p><strong>Estado:</strong> Cuenta inactiva; solicitar habilitación institucional.</p>
@elseif($passwordTemporal)<p><strong>Importante:</strong> Contraseña temporal de primer uso. Debe cambiarla al iniciar sesión.</p>
@endif
</div>
<div class="aviso">Documento de uso institucional. Las credenciales son personales e intransferibles. La contraseña temporal solo aparece en la primera emisión; no puede recuperarse mediante reimpresiones. En caso de pérdida, solicite un restablecimiento autorizado. Este documento no acredita asignaciones de materias ni grado guía.</div>
</div></body></html>
