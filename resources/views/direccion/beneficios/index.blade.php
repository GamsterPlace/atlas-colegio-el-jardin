@extends('layouts.direccion')
@section('title', 'Beneficios financieros individuales')
@section('module-title', 'Beneficios financieros individuales')
@section('content')
<style>
.atlas-beneficios{max-width:1200px;margin:auto;padding:24px;color:#183454}.atlas-beneficios *{box-sizing:border-box}.atlas-beneficios h1{font-size:26px}.atlas-beneficios .panel{background:white;border:1px solid #dce6f0;border-radius:14px;padding:20px;margin-bottom:18px;overflow-x:auto}.atlas-beneficios input,.atlas-beneficios select,.atlas-beneficios textarea{border:1px solid #cbd8e7;border-radius:8px;padding:10px;width:100%;color:#183454}.atlas-beneficios .grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}.atlas-beneficios label{font-size:13px;font-weight:700;display:block;margin-bottom:6px}.atlas-beneficios button{background:#15549c;border:0;color:white;border-radius:8px;padding:11px 20px;cursor:pointer}.atlas-beneficios table{border-collapse:collapse;width:100%;font-size:13px}.atlas-beneficios th,.atlas-beneficios td{padding:11px;border-bottom:1px solid #e4ebf3;text-align:left}.atlas-beneficios th{background:#edf4fb}.atlas-beneficios .note{background:#edf4fb;border-radius:9px;padding:12px;margin-bottom:16px}.atlas-beneficios .error{color:#a52a2a}@media(max-width:650px){.atlas-beneficios .grid{grid-template-columns:1fr}}
</style>
<div class="atlas-beneficios">
<h1>Autorizaciones financieras individuales</h1>
<p>Los descuentos y tarifas especiales se aplican a una sola obligación, sin afectar otros meses o ciclos escolares.</p>
@if(session('success'))<div class="note">{{ session('success') }}</div>@endif
@if($errors->any())<div class="panel error"><strong>Corrige lo siguiente:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<div class="panel"><h2>Autorizar beneficio</h2>
<div class="note">Solo se permiten obligaciones vigentes sin pagos, hojas de talonario emitidas ni historial de mora. La autorización es única por obligación y queda registrada permanentemente.</div>
<form method="POST" action="{{ route('direccion.finanzas.beneficios.store') }}">@csrf
<div class="grid">
<div><label for="cuenta_por_cobrar_id">Obligación específica</label><select id="cuenta_por_cobrar_id" name="cuenta_por_cobrar_id" required><option value="">Seleccionar obligación</option>@foreach($obligaciones as $o)<option value="{{ $o->id }}" @selected(old('cuenta_por_cobrar_id')==$o->id)>{{ $o->estudiante?->apellidos }}, {{ $o->estudiante?->nombres }} — {{ $o->concepto_cobro?->nombre }} — {{ $o->periodo_referencia?->format('m/Y') }} — Q{{ number_format((float)$o->monto_exigible,2) }}</option>@endforeach</select></div>
<div><label for="tipo">Tipo de beneficio</label><select id="tipo" name="tipo" required><option value="DESCUENTO_FIJO">Descuento fijo (Q)</option><option value="DESCUENTO_PORCENTAJE">Descuento porcentual (%)</option><option value="TARIFA_ESPECIAL">Tarifa especial (nuevo total Q)</option></select></div>
<div><label for="valor">Valor</label><input id="valor" name="valor" type="number" min="0" step="0.01" value="{{ old('valor') }}" required></div>
<div><label for="motivo">Motivo de autorización</label><textarea id="motivo" name="motivo" rows="3" minlength="10" required>{{ old('motivo') }}</textarea></div>
</div><p><button type="submit" onclick="return confirm('¿Autorizar y aplicar este beneficio únicamente a la obligación seleccionada?')">Autorizar beneficio</button></p></form></div>
<div class="panel"><h2>Buscar obligaciones</h2><form method="GET"><div class="grid"><input name="buscar" value="{{ $buscar }}" placeholder="Nombre o carné del estudiante"><button>Buscar</button></div></form><table><thead><tr><th>Estudiante</th><th>Concepto</th><th>Período</th><th>Monto exigible</th></tr></thead><tbody>@forelse($obligaciones as $o)<tr><td>{{ $o->estudiante?->nombre_completo }}</td><td>{{ $o->concepto_cobro?->nombre }}</td><td>{{ $o->periodo_referencia?->format('m/Y') }}</td><td>Q{{ number_format((float)$o->monto_exigible,2) }}</td></tr>@empty<tr><td colspan="4">No hay obligaciones disponibles.</td></tr>@endforelse</tbody></table>{{ $obligaciones->links() }}</div>
<div class="panel"><h2>Últimas autorizaciones</h2><table><thead><tr><th>Estudiante</th><th>Período</th><th>Tipo</th><th>Anterior</th><th>Nuevo</th><th>Fecha</th></tr></thead><tbody>@forelse($beneficios as $b)<tr><td>{{ $b->apellidos }}, {{ $b->nombres }}</td><td>{{ $b->periodo_referencia }}</td><td>{{ str_replace('_',' ',$b->tipo) }}</td><td>Q{{ number_format($b->monto_anterior,2) }}</td><td>Q{{ number_format($b->monto_nuevo,2) }}</td><td>{{ $b->autorizado_at }}</td></tr>@empty<tr><td colspan="6">No hay autorizaciones registradas.</td></tr>@endforelse</tbody></table></div>
</div>
@endsection
