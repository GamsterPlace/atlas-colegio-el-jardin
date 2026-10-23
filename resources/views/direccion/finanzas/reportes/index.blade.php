@extends('layouts.direccion')
@section('title', 'Reportes financieros')
@section('module-title', 'Reportes financieros')
@section('content')
<style>
.atlas-reportes{max-width:1350px;margin:auto;padding:24px;color:#183454}.atlas-reportes *{box-sizing:border-box}.atlas-reportes h1{font-size:28px;margin:0 0 8px}.atlas-reportes p{color:#61768d}.rf-panel,.rf-card{background:#fff;border:1px solid #dbe5ef;border-radius:14px;padding:22px;box-shadow:0 5px 18px #102c4a08}.rf-panel{margin-bottom:20px}.rf-filtros{display:flex;gap:12px;flex-wrap:wrap;align-items:end}.rf-field{display:flex;flex-direction:column;gap:6px;flex:1;min-width:180px}.rf-field label{font-size:12px;font-weight:700}.rf-field input,.rf-field select{padding:10px;border:1px solid #ccd9e8;border-radius:8px;color:#183454;background:#fff}.rf-btn{border:0;background:#15579b;color:white;padding:11px 16px;border-radius:8px;font-weight:700;text-decoration:none;display:inline-block;cursor:pointer}.rf-btn.alt{background:#e8eff8;color:#15579b}.rf-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px;margin:20px 0}.rf-card span{display:block;font-size:12px;color:#61768d;font-weight:700}.rf-card strong{display:block;margin-top:10px;font-size:25px}.rf-table{width:100%;border-collapse:collapse}.rf-table th,.rf-table td{text-align:left;padding:12px;border-bottom:1px solid #e4ecf5}.rf-table th{background:#f0f5fa}.rf-note{font-size:12px;color:#61768d;margin-top:15px}@media(max-width:800px){.rf-grid{grid-template-columns:1fr}.atlas-reportes{padding:12px}}
</style>
<div class="atlas-reportes">
<h1>Reportes financieros</h1><p>Recaudación y cartera del colegio, separadas por ciclo escolar.</p>
<div class="rf-panel">
<form method="GET" action="{{ route('direccion.finanzas.reportes.index') }}" class="rf-filtros">
<div class="rf-field"><label for="ciclo">Ciclo escolar</label><select name="ciclo_escolar_id" id="ciclo">@foreach($ciclos as $ciclo)<option value="{{ $ciclo->id }}" @selected($cicloId == $ciclo->id)>{{ $ciclo->nombre }}</option>@endforeach</select></div>
<div class="rf-field"><label for="desde">Depósitos desde</label><input type="date" name="desde" id="desde" value="{{ $desde }}"></div>
<div class="rf-field"><label for="hasta">Depósitos hasta</label><input type="date" name="hasta" id="hasta" value="{{ $hasta }}"></div>
<button type="submit" class="rf-btn">Consultar</button>
@if($cicloId)<a class="rf-btn alt" href="{{ route('direccion.finanzas.reportes.exportar', array_filter(['ciclo_escolar_id'=>$cicloId,'desde'=>$desde,'hasta'=>$hasta])) }}">Exportar CSV</a>@endif
</form>
@error('hasta')<p style="color:#a33535">{{ $message }}</p>@enderror
</div>
@if(!$datos)<div class="rf-panel">No hay ciclos escolares registrados.</div>@else
<div class="rf-grid">
<div class="rf-card"><span>Recaudación válida del período</span><strong>Q {{ number_format((float)$datos['recaudacion']->total,2) }}</strong></div>
<div class="rf-card"><span>Operaciones válidas</span><strong>{{ number_format((int)$datos['recaudacion']->operaciones) }}</strong></div>
<div class="rf-card"><span>Principal recaudado</span><strong>Q {{ number_format((float)$datos['recaudacion']->principal,2) }}</strong></div>
<div class="rf-card"><span>Mora recaudada</span><strong>Q {{ number_format((float)$datos['recaudacion']->mora,2) }}</strong></div>
<div class="rf-card"><span>Principal pendiente actual</span><strong>Q {{ number_format($datos['principalPendiente'],2) }}</strong></div>
<div class="rf-card"><span>Mora pendiente actual</span><strong>Q {{ number_format($datos['moraPendiente'],2) }}</strong></div>
<div class="rf-card"><span>Principal vencido actual</span><strong>Q {{ number_format($datos['vencido'],2) }}</strong></div>
<div class="rf-card"><span>Estudiantes con deuda</span><strong>{{ number_format(count($datos['deudores'])) }}</strong></div>
<div class="rf-card"><span>Obligaciones pendientes</span><strong>{{ number_format($datos['obligacionesPendientes']) }}</strong></div>
</div>
<div class="rf-panel"><h2>Recaudación por concepto</h2><div style="overflow:auto"><table class="rf-table"><thead><tr><th>Concepto</th><th>Operaciones</th><th>Importe recaudado</th></tr></thead><tbody>
@forelse($datos['porConcepto'] as $fila)<tr><td>{{ $fila->nombre }}</td><td>{{ number_format($fila->operaciones) }}</td><td>Q {{ number_format((float)$fila->total,2) }}</td></tr>@empty<tr><td colspan="3">No se encontraron pagos válidos en el período.</td></tr>@endforelse
</tbody></table></div><p class="rf-note">El rango de fechas se aplica únicamente a los depósitos. Los saldos pendientes y vencidos representan la cartera actual del ciclo seleccionado, no un corte histórico. Los pagos anulados y las obligaciones anuladas no se contabilizan en los totales correspondientes.</p></div>
@endif
</div>
@endsection
