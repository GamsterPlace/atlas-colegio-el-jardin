<!DOCTYPE html>
<html lang="es"><head><meta charset="utf-8"><style>
@page { size: letter portrait; margin: 0; }
* { box-sizing: border-box; }
body { margin: 0; font-family: DejaVu Sans, sans-serif; color: #183451; font-size: 11px; }
.membrete { position: absolute; top: 0; left: 0; width: 612pt; height: 792pt; z-index: -1; }
.contenido { position: absolute; top: 143pt; left: 53pt; width: 506pt; }
h1 { text-align: center; font-size: 17px; color: #163b70; margin: 0 0 7px; }
.sub { text-align: center; font-size: 9px; color: #63778a; }
h2 { margin-top: 24px; font-size: 12px; border-bottom: 2px solid #244d78; padding-bottom: 7px; }
table { border-collapse: collapse; width: 100%; margin-top: 10px; }
td { padding: 10px; border-bottom: 1px solid #dce4ee; vertical-align: top; }
.etiqueta { font-size: 9px; color: #6b7c8c; }
.valor { font-size: 12px; font-weight: bold; padding-top: 4px; overflow-wrap: break-word; }
.cred { background: #f2f7fd; border: 1px solid #cbdceb; padding: 15px; margin-top: 12px; }
.nota { margin-top: 22px; font-size: 9px; line-height: 1.7; color: #53677d; }
</style></head><body>
@php
$ruta = public_path('images/Institucional/hoja-membretada-atlas.png');
$membrete = is_file($ruta) ? 'data:image/png;base64,'.base64_encode(file_get_contents($ruta)) : null;
@endphp
@if($membrete)<img class="membrete" src="{{ $membrete }}" alt="">@endif
<div class="contenido">
<h1>FICHA DE CREDENCIALES DE ACCESO</h1>
<div class="sub">Colegio Mixto El Jardín · Sistema institucional ATLAS</div>
<h2>Identificación del usuario</h2>
<table><tr><td><div class="etiqueta">Nombre completo</div><div class="valor">{{ $usuario->persona?->nombres }} {{ $usuario->persona?->apellidos }}</div></td></tr>
<tr><td><div class="etiqueta">Roles asignados</div><div class="valor">{{ $usuario->roles->pluck('nombre')->join(', ') }}</div></td></tr></table>
<h2>Credenciales para primer ingreso</h2>
<div class="cred"><p><strong>Nombre de usuario:</strong> {{ $usuario->nombre_usuario }}</p><p><strong>Contraseña temporal:</strong> {{ $password }}</p></div>
<div class="nota"><strong>Instrucciones:</strong> Ingrese al sitio institucional, seleccione ATLAS e identifíquese con estas credenciales. El sistema solicitará cambiar la contraseña en el primer ingreso. No comparta este documento ni la contraseña. Esta ficha solo puede emitirse una vez por creación o restablecimiento de contraseña.</div>
</div></body></html>
