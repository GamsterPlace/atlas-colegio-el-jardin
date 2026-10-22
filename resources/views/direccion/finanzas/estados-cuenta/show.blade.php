@extends('layouts.direccion')
@section('title','Cuenta financiera estudiantil')
@section('module-title','Cuenta financiera estudiantil')
@section('content')
<style>
.fin-account{max-width:1400px;margin:auto;padding:25px;color:#183454}.fin-account *{box-sizing:border-box}.fin-account h1{font-size:27px;margin:0 0 6px}.fin-account p{color:#708298;margin:0 0 20px}.fin-account .panel{background:#fff;border:1px solid #dce6f0;border-radius:14px;padding:20px;margin-bottom:20px;overflow-x:auto}.fin-account .controls{display:flex;gap:12px;flex-wrap:wrap;align-items:center;margin-bottom:18px}.fin-account input,.fin-account select{border:1px solid #cbd8e7;border-radius:8px;padding:10px;color:#183454;min-width:190px}.fin-account .btn{background:#15549c;color:white!important;padding:10px 16px;border:0;border-radius:8px;text-decoration:none;display:inline-block;font-weight:700;cursor:pointer}.fin-account .btn.light{background:#edf4fb;color:#15549c!important}.fin-account table{width:100%;border-collapse:collapse;font-size:13px}.fin-account th{background:#edf4fb;text-align:left}.fin-account th,.fin-account td{padding:12px;border-bottom:1px solid #e3ebf4}.fin-account .right{text-align:right}.fin-account .cards{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:18px}.fin-account .card{border:1px solid #dce6f0;border-radius:12px;background:white;padding:18px}.fin-account .card strong{display:block;font-size:24px;margin-top:8px}.fin-account .muted{color:#728398;font-size:12px}.fin-account .negative{color:#a23a38}@media(max-width:720px){.fin-account{padding:12px}.fin-account .cards{grid-template-columns:1fr}}
</style>
<div class="fin-account">
<a class="btn light" href="{{ route('direccion.finanzas.estados-cuenta.index') }}">← Volver a estudiantes</a>
<h1 style="margin-top:18px">{{ $estudiante->nombre_completo }}</h1>
<p>Cuenta financiera permanente · Carné {{ $estudiante->numero_carnet }} · Código histórico {{ $estudiante->codigo }}</p>
<form class="panel controls" method="GET"><label for="ciclo">Ciclo escolar</label><select name="ciclo_escolar_id" id="ciclo"><option value="">Todos los ciclos (historial completo)</option>@foreach($ciclos as $ciclo)<option value="{{ $ciclo->id }}" @selected($cicloId === (int)$ciclo->id)>{{ $ciclo->nombre }}</option>@endforeach</select><button class="btn">Consultar</button></form>
@php
$principal = $obligaciones->sum('monto_exigible');
$mora = $obligaciones->sum('mora_generada');
$pagado = $obligaciones->sum('principal_pagado') + $obligaciones->sum('mora_pagada');
$saldo = $obligaciones->sum('saldo_principal') + $obligaciones->sum('saldo_mora');
@endphp
<div class="cards"><div class="card">Cargos y recargos<strong>Q {{ number_format($principal+$mora,2) }}</strong></div><div class="card">Abonos registrados<strong>Q {{ number_format($pagado,2) }}</strong></div><div class="card">Saldo pendiente<strong class="negative">Q {{ number_format($saldo,2) }}</strong></div></div>
<div class="panel"><h2>Conceptos y obligaciones</h2><table><thead><tr><th>Ciclo</th><th>Concepto</th><th>Período</th><th>Vencimiento</th><th class="right">Cargo</th><th class="right">Mora</th><th class="right">Pagado</th><th class="right">Saldo</th></tr></thead><tbody>
@forelse($obligaciones as $o)<tr><td>{{ $o->ciclo_nombre }}</td><td>{{ $o->concepto_nombre ?? $o->descripcion ?? '—' }}</td><td>{{ $o->periodo_referencia }}</td><td>{{ $o->fecha_vencimiento }}</td><td class="right">Q {{ number_format($o->monto_exigible,2) }}</td><td class="right">Q {{ number_format($o->mora_generada,2) }}</td><td class="right">Q {{ number_format($o->principal_pagado+$o->mora_pagada,2) }}</td><td class="right"><strong>Q {{ number_format($o->saldo_principal+$o->saldo_mora,2) }}</strong></td></tr>
@empty<tr><td colspan="8">No hay obligaciones registradas para esta selección.</td></tr>@endforelse
</tbody></table></div>
<div class="panel"><h2>Historial de pagos válidos</h2><table><thead><tr><th>Depósito</th><th>Concepto</th><th>Referencia</th><th>Banco</th><th class="right">Monto</th></tr></thead><tbody>
@forelse($pagos as $p)<tr><td>{{ $p->fecha_deposito?->format('d/m/Y') }}</td><td>{{ $p->cuenta_por_cobrar?->concepto_cobro?->nombre ?? '—' }}</td><td>{{ $p->referencia_bancaria }}</td><td>{{ $p->banco ?: '—' }}</td><td class="right">Q {{ number_format($p->monto,2) }}</td></tr>
@empty<tr><td colspan="5">No hay pagos registrados.</td></tr>@endforelse
</tbody></table><div style="margin-top:15px">{{ $pagos->links() }}</div></div>
</div>
@endsection
