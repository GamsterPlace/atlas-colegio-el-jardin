@extends('layouts.direccion')
@section('title', 'Cuentas por cobrar')
@section('module-title', 'Cuentas por cobrar')
@section('content')
<style>
.cxc{max-width:1450px;margin:auto;padding:24px;color:#183454}.cxc *{box-sizing:border-box}.cxc h1{font-size:28px;margin:0 0 6px}.cxc p{color:#6b7c8f}.cxc-panel{background:white;border:1px solid #dce5ef;border-radius:14px;padding:20px;margin:18px 0}.cxc-filtros{display:flex;flex-wrap:wrap;gap:12px;align-items:end}.cxc-filtros label{display:block;font-size:12px;font-weight:700;margin-bottom:5px}.cxc input,.cxc select,.cxc textarea{padding:10px;border:1px solid #ccd9e7;border-radius:8px;max-width:100%;background:#fff}.cxc button,.cxc .btn{background:#15579b;color:white;border:0;border-radius:8px;padding:11px 16px;cursor:pointer;text-decoration:none;display:inline-block}.cxc-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px}.cxc-stat{border:1px solid #dce5ef;background:white;border-radius:12px;padding:17px}.cxc-stat span{display:block;color:#687d92;font-size:12px}.cxc-stat strong{font-size:22px}.cxc table{width:100%;border-collapse:collapse;font-size:13px;white-space:nowrap}.cxc th,.cxc td{padding:12px;text-align:left;border-bottom:1px solid #e5ecf3}.cxc th{background:#f0f5fa}.cxc-scroll{overflow-x:auto}.cxc small{color:#72859a}.cxc a{color:#15579b}.cxc .estado{font-size:11px;font-weight:800;border-radius:6px;padding:5px 8px;background:#edf3f9}.cxc .alerta{color:#a13c35}.cxc nav{margin-top:16px}@media(max-width:850px){.cxc-grid{grid-template-columns:repeat(2,1fr)}}@media(max-width:550px){.cxc-grid{grid-template-columns:1fr}.cxc{padding:12px}}
</style>
<div class="cxc">
<h1>Cuentas por cobrar</h1><p>Obligaciones financieras por estudiante y ciclo escolar. Los saldos se calculan a partir de pagos válidos y recargos vigentes.</p>
<form class="cxc-panel cxc-filtros" method="GET" action="{{ route('direccion.finanzas.cuentas.index') }}">
<div><label for="ciclo">Ciclo escolar</label><select id="ciclo" name="ciclo_escolar_id">@foreach($ciclos as $ciclo)<option value="{{ $ciclo->id }}" @selected($cicloId == $ciclo->id)>{{ $ciclo->nombre }}</option>@endforeach</select></div>
<div><label for="buscar">Buscar estudiante</label><input id="buscar" name="buscar" value="{{ $buscar }}" placeholder="Nombre, código o carné"></div>
<div><label for="concepto">Concepto</label><select id="concepto" name="concepto_cobro_id"><option value="">Todos</option>@foreach($conceptos as $concepto)<option value="{{ $concepto->id }}" @selected(request('concepto_cobro_id') == $concepto->id)>{{ $concepto->nombre }}</option>@endforeach</select></div>
<div><label for="estado">Estado</label><select id="estado" name="estado">@foreach(['PENDIENTE'=>'Pendientes','VENCIDA'=>'Vencidas','PAGADA'=>'Pagadas','ANULADA'=>'Anuladas','TODAS'=>'Todas'] as $valor=>$texto)<option value="{{ $valor }}" @selected($estado===$valor)>{{ $texto }}</option>@endforeach</select></div>
<button type="submit">Consultar</button>
</form>
<div class="cxc-grid"><div class="cxc-stat"><span>Obligaciones encontradas</span><strong>{{ number_format($resumen['obligaciones']) }}</strong></div><div class="cxc-stat"><span>Estudiantes</span><strong>{{ number_format($resumen['estudiantes']) }}</strong></div><div class="cxc-stat"><span>Principal pendiente</span><strong>Q {{ number_format($resumen['principal'],2) }}</strong></div><div class="cxc-stat"><span>Mora pendiente</span><strong>Q {{ number_format($resumen['mora'],2) }}</strong></div></div>
<div class="cxc-panel"><div class="cxc-scroll"><table><thead><tr><th>Estudiante</th><th>Concepto</th><th>Período</th><th>Vencimiento</th><th>Importe</th><th>Pagado</th><th>Principal pendiente</th><th>Mora pendiente</th><th>Estado</th><th></th></tr></thead><tbody>
@forelse($obligaciones as $o)
<tr><td><strong>{{ $o->apellidos }}, {{ $o->nombres }}</strong><div><small>{{ $o->numero_carnet ?: $o->codigo }}</small></div></td><td>{{ $o->concepto }}</td><td>{{ \Carbon\Carbon::parse($o->periodo_referencia)->format('m/Y') }}</td><td>{{ \Carbon\Carbon::parse($o->fecha_vencimiento)->format('d/m/Y') }}</td><td>Q {{ number_format($o->monto_exigible,2) }}</td><td>Q {{ number_format($o->pagado_principal,2) }}</td><td>Q {{ number_format($o->saldo_principal,2) }}</td><td>Q {{ number_format($o->saldo_mora,2) }}</td><td><span class="estado {{ $o->estado_calculado === 'VENCIDA' ? 'alerta' : '' }}">{{ $o->estado_calculado }}</span></td><td><a href="{{ route('direccion.finanzas.cuentas.show',$o->id) }}">Ver detalle</a></td></tr>
@empty<tr><td colspan="10" style="text-align:center;padding:25px">No se encontraron obligaciones con los filtros seleccionados.</td></tr>@endforelse
</tbody></table></div><div>{{ $obligaciones->links() }}</div></div>
<p><small>Los importes mostrados corresponden exclusivamente al ciclo y los filtros seleccionados. Cada estudiante conserva una cuenta financiera permanente.</small></p>
</div>
@endsection
