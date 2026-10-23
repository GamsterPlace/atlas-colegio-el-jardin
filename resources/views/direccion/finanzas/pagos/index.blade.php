@extends('layouts.direccion')
@section('title', 'Registro de pagos')
@section('module-title', 'Registro de pagos')
@section('content')
<style>
.atlas-pagos{max-width:1400px;margin:auto;padding:24px;color:#183454}.atlas-pagos *{box-sizing:border-box}.atlas-pagos h1{font-size:27px;margin:0 0 7px}.atlas-pagos p{color:#62758b}.pg-panel{background:#fff;border:1px solid #dbe5ef;border-radius:14px;padding:22px;margin:18px 0;box-shadow:0 5px 18px #102c4a08}.pg-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}.pg-field label{display:block;font-weight:700;font-size:12px;margin-bottom:6px}.pg-field input,.pg-field select,.pg-field textarea{width:100%;border:1px solid #cbd9e8;border-radius:8px;padding:11px;color:#183454;background:#fff}.pg-btn{background:#175a9b;color:#fff;border:0;border-radius:8px;padding:11px 18px;font-weight:700;cursor:pointer;text-decoration:none;display:inline-block}.pg-btn.danger{background:#a33535}.pg-btn.secondary{background:#e8eff8;color:#174c80}.pg-alert{padding:13px 16px;border-radius:9px;margin:15px 0;background:#edf5fc;border:1px solid #cddff2}.pg-alert.error{background:#fff0f0;border-color:#e6b9b9;color:#932d2d}.pg-alert.ok{background:#eaf7ef;border-color:#b5dcc5;color:#196a43}.pg-table-wrap{overflow:auto}.pg-table{width:100%;border-collapse:collapse;font-size:13px;white-space:nowrap}.pg-table th,.pg-table td{padding:12px;border-bottom:1px solid #e5edf5;text-align:left}.pg-table th{background:#eff5fb}.pg-muted{font-size:12px;color:#718398}.pg-actions{margin-top:16px;display:flex;gap:10px;flex-wrap:wrap;align-items:center}.pg-badge{border-radius:7px;background:#eaf2fb;padding:5px 8px;font-weight:700;font-size:11px}.pg-badge.bad{background:#fbe8e8;color:#9e3434}@media(max-width:850px){.pg-grid{grid-template-columns:1fr}.atlas-pagos{padding:12px}}
</style>
<div class="atlas-pagos">
<h1>Registro de pagos</h1><p>Consulta la boleta bancaria por código, registra depósitos comprobados y revisa el historial financiero.</p>
@if(session('success'))<div class="pg-alert ok">{{ session('success') }}</div>@endif
@if($errors->any())<div class="pg-alert error"><strong>Revisa los datos:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<section class="pg-panel">
<h2>Consultar boleta</h2>
<form method="GET" action="{{ route('direccion.finanzas.pagos.index') }}" class="pg-grid" style="align-items:end">
<div class="pg-field"><label for="codigo">Código único de boleta</label><input id="codigo" name="codigo" value="{{ old('codigo', $codigo) }}" required maxlength="100" placeholder="Código impreso en la boleta"></div>
<div><button class="pg-btn" type="submit">Buscar boleta</button></div>
</form>
@if($codigo !== '' && !$hoja)<div class="pg-alert error">No se encontró una boleta con ese código.</div>@endif
@if($hoja && $resumenHoja)
@php $cuenta = $hoja->cuenta_por_cobrar; $disponible = $hoja->estado === 'VIGENTE' && $cuenta->estado_administrativo === 'VIGENTE' && !$resumenHoja['ya_pagada']; @endphp
<div class="pg-alert"><strong>Boleta:</strong> {{ $hoja->codigo }} · {{ $hoja->tipo }} · <strong>{{ $hoja->estado }}</strong></div>
<div class="pg-grid">
<div><div class="pg-muted">Estudiante</div><strong>{{ $cuenta->estudiante?->nombre_completo ?? '—' }}</strong><div class="pg-muted">{{ $cuenta->estudiante?->numero_carnet }}</div></div>
<div><div class="pg-muted">Concepto</div><strong>{{ $cuenta->concepto_cobro?->nombre ?? $hoja->concepto_impreso }}</strong></div>
<div><div class="pg-muted">Importe impreso</div><strong>Q {{ number_format((float)$hoja->monto_impreso,2) }}</strong></div>
<div><div class="pg-muted">Principal pendiente</div><strong>Q {{ number_format($resumenHoja['principal'],2) }}</strong></div>
<div><div class="pg-muted">Mora pendiente</div><strong>Q {{ number_format($resumenHoja['mora'],2) }}</strong></div>
<div><div class="pg-muted">Saldo total</div><strong>Q {{ number_format($resumenHoja['principal']+$resumenHoja['mora'],2) }}</strong></div>
</div>
@if($disponible)
<h3>Registrar depósito</h3>
<p class="pg-muted">El importe se toma de la boleta y no puede modificarse. Primero se aplica al principal y luego a la mora.</p>
<form method="POST" action="{{ route('direccion.finanzas.pagos.store') }}">@csrf<input type="hidden" name="codigo" value="{{ $hoja->codigo }}">
<div class="pg-grid">
<div class="pg-field"><label for="fecha_deposito">Fecha de depósito</label><input type="date" name="fecha_deposito" id="fecha_deposito" value="{{ old('fecha_deposito', now('America/Guatemala')->toDateString()) }}" max="{{ now('America/Guatemala')->toDateString() }}" required></div>
<div class="pg-field"><label for="banco">Banco</label><input name="banco" id="banco" value="{{ old('banco') }}" maxlength="120" required></div>
<div class="pg-field"><label for="referencia_bancaria">Referencia bancaria</label><input name="referencia_bancaria" id="referencia_bancaria" value="{{ old('referencia_bancaria') }}" maxlength="120" required></div>
<div class="pg-field" style="grid-column:1/-1"><label for="observaciones">Observaciones (opcional)</label><textarea name="observaciones" id="observaciones" rows="2" maxlength="2000">{{ old('observaciones') }}</textarea></div>
</div><div class="pg-actions"><button class="pg-btn" type="submit" onclick="return confirm('¿Confirmas que verificaste el depósito y deseas registrarlo?')">Registrar pago</button></div>
</form>
@else<div class="pg-alert error">Esta boleta no está disponible para registrar un pago. Comprueba su estado y los pagos anteriores.</div>@endif
@endif
</section>
<section class="pg-panel"><h2>Historial de pagos</h2>
<form method="GET" class="pg-grid" style="align-items:end">
<div class="pg-field"><label for="buscar">Estudiante, referencia o boleta</label><input id="buscar" name="buscar" value="{{ $buscar }}"></div>
<div class="pg-field"><label for="ciclo_escolar_id">Ciclo escolar</label><select id="ciclo_escolar_id" name="ciclo_escolar_id"><option value="">Todos los ciclos</option>@foreach($ciclos as $ciclo)<option value="{{ $ciclo->id }}" @selected($cicloId == $ciclo->id && $cicloId !== null)>{{ $ciclo->nombre }}</option>@endforeach</select></div>
<div class="pg-field"><label for="estado">Estado</label><select id="estado" name="estado"><option value="TODOS" @selected($estado==='TODOS')>Todos</option><option value="VALIDO" @selected($estado==='VALIDO')>Válidos</option><option value="ANULADO" @selected($estado==='ANULADO')>Anulados</option></select></div>
<div><button class="pg-btn secondary" type="submit">Filtrar historial</button></div>
</form>
<div class="pg-table-wrap" style="margin-top:18px"><table class="pg-table"><thead><tr><th>Depósito</th><th>Estudiante</th><th>Boleta</th><th>Concepto / ciclo</th><th>Referencia</th><th>Importe</th><th>Estado</th><th>Acciones</th></tr></thead><tbody>
@forelse($historial as $pago)<tr>
<td>{{ $pago->fecha_deposito?->format('d/m/Y') }}</td><td>{{ $pago->estudiante?->nombre_completo ?? '—' }}</td><td>{{ $pago->hoja_talonario?->codigo ?? '—' }}</td><td>{{ $pago->cuenta_por_cobrar?->concepto_cobro?->nombre ?? '—' }}<div class="pg-muted">{{ $pago->cuenta_por_cobrar?->ciclo_escolar?->nombre }}</div></td><td>{{ $pago->referencia_bancaria }}<div class="pg-muted">{{ $pago->banco }}</div></td><td>Q {{ number_format((float)$pago->monto,2) }}</td><td><span class="pg-badge {{ $pago->estado==='ANULADO' ? 'bad' : '' }}">{{ $pago->estado }}</span></td>
<td>@if($pago->estado==='VALIDO')<details><summary style="cursor:pointer;color:#a33535;font-weight:700">Anular</summary><form method="POST" action="{{ route('direccion.finanzas.pagos.anular', $pago->id) }}" onsubmit="return confirm('¿Confirmas la anulación de este pago?')">@csrf @method('PATCH')<div class="pg-field"><label>Motivo obligatorio</label><textarea name="motivo" minlength="10" maxlength="2000" required rows="3"></textarea></div><button class="pg-btn danger" type="submit">Confirmar anulación</button></form></details>@else<span class="pg-muted" title="{{ $pago->motivo_anulacion }}">Anulado</span>@endif</td>
</tr>@empty<tr><td colspan="8" style="text-align:center;padding:24px">No se encontraron pagos.</td></tr>@endforelse
</tbody></table></div><div style="margin-top:16px">{{ $historial->links() }}</div>
</section>
</div>
@endsection
