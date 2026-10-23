@extends('layouts.direccion')
@section('title', 'Detalle de obligación')
@section('module-title', 'Detalle de obligación')
@section('content')
<style>.cxc-det{max-width:1100px;margin:auto;padding:24px;color:#183454}.cxc-det .panel{background:white;border:1px solid #dce5ef;border-radius:14px;padding:22px;margin:18px 0}.cxc-det h1{font-size:26px}.cxc-det .grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}.cxc-det .dato{font-size:13px;color:#64798e}.cxc-det strong{display:block;color:#173c66;font-size:18px;margin-top:4px}.cxc-det table{width:100%;border-collapse:collapse}.cxc-det td,.cxc-det th{padding:10px;border-bottom:1px solid #e4ebf2;text-align:left}.cxc-det th{background:#f2f6fb}.cxc-det textarea{width:100%;padding:10px;border:1px solid #cdd9e6;border-radius:8px}.cxc-det button{background:#a53b35;color:white;border:0;border-radius:8px;padding:11px 16px;cursor:pointer}.cxc-det a{color:#15579b}.cxc-det .scroll{overflow-x:auto}@media(max-width:650px){.cxc-det .grid{grid-template-columns:1fr}}</style>
<div class="cxc-det"><a href="{{ route('direccion.finanzas.cuentas.index') }}">← Volver a cuentas por cobrar</a><h1>Detalle de obligación #{{ $detalle->id }}</h1>
@if(session('success'))<div class="panel" style="color:#16744e">{{ session('success') }}</div>@endif
@if($errors->any())<div class="panel" style="color:#a53b35">{{ $errors->first() }}</div>@endif
<div class="panel grid">
<div class="dato">Estudiante<strong>{{ $detalle->nombres }} {{ $detalle->apellidos }}</strong><small>{{ $detalle->numero_carnet }}</small></div>
<div class="dato">Ciclo escolar<strong>{{ $detalle->ciclo }}</strong></div><div class="dato">Concepto<strong>{{ $detalle->concepto }}</strong></div>
<div class="dato">Período<strong>{{ \Carbon\Carbon::parse($detalle->periodo_referencia)->format('m/Y') }}</strong></div><div class="dato">Vencimiento<strong>{{ \Carbon\Carbon::parse($detalle->fecha_vencimiento)->format('d/m/Y') }}</strong></div><div class="dato">Estado<strong>{{ $detalle->estado_calculado }}</strong></div>
<div class="dato">Monto original<strong>Q {{ number_format($detalle->monto_base,2) }}</strong></div><div class="dato">Descuento aplicado<strong>Q {{ number_format($detalle->monto_descuento,2) }}</strong></div><div class="dato">Monto exigible<strong>Q {{ number_format($detalle->monto_exigible,2) }}</strong></div>
<div class="dato">Principal abonado<strong>Q {{ number_format($detalle->pagado_principal,2) }}</strong></div><div class="dato">Principal pendiente<strong>Q {{ number_format($detalle->saldo_principal,2) }}</strong></div><div class="dato">Mora pendiente<strong>Q {{ number_format($detalle->saldo_mora,2) }}</strong></div>
</div>
<div class="panel"><h2>Pagos registrados</h2><div class="scroll"><table><thead><tr><th>Fecha</th><th>Referencia</th><th>Banco</th><th>Monto</th><th>Estado</th></tr></thead><tbody>@forelse($pagos as $p)<tr><td>{{ $p->fecha_deposito }}</td><td>{{ $p->referencia_bancaria }}</td><td>{{ $p->banco }}</td><td>Q {{ number_format($p->monto,2) }}</td><td>{{ $p->estado }}</td></tr>@empty<tr><td colspan="5">Sin pagos registrados.</td></tr>@endforelse</tbody></table></div></div>
<div class="panel"><h2>Hojas de talonario</h2><div class="scroll"><table><thead><tr><th>Código</th><th>Tipo</th><th>Monto impreso</th><th>Estado</th></tr></thead><tbody>@forelse($hojas as $h)<tr><td>{{ $h->codigo }}</td><td>{{ $h->tipo }}</td><td>Q {{ number_format($h->monto_impreso,2) }}</td><td>{{ $h->estado }}</td></tr>@empty<tr><td colspan="4">Sin hojas emitidas.</td></tr>@endforelse</tbody></table></div></div>
@if($detalle->estado_administrativo === 'VIGENTE')
<div class="panel"><h2>Anulación administrativa</h2><p>Solo Dirección puede anular obligaciones sin pagos válidos, hojas bancarias vigentes ni mora vigente. Esta operación conserva el registro.</p><form method="POST" action="{{ route('direccion.finanzas.cuentas.anular',$detalle->id) }}" onsubmit="return confirm('¿Confirma la anulación administrativa de esta obligación?')">@csrf @method('PATCH')<label for="motivo">Motivo obligatorio</label><textarea name="motivo" id="motivo" rows="3" minlength="10" maxlength="2000" required>{{ old('motivo') }}</textarea><p><button type="submit">Anular obligación</button></p></form></div>
@else<div class="panel"><h2>Obligación anulada</h2><p>{{ $detalle->motivo_anulacion }}</p></div>@endif
</div>
@endsection
