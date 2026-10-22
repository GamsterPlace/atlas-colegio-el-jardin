@extends('layouts.direccion')
@section('title','Estados de cuenta estudiantiles')
@section('module-title','Estados de cuenta estudiantiles')
@section('content')
<style>
.fin-account{max-width:1400px;margin:auto;padding:25px;color:#183454}.fin-account *{box-sizing:border-box}.fin-account h1{font-size:27px;margin:0 0 6px}.fin-account p{color:#708298;margin:0 0 20px}.fin-account .panel{background:#fff;border:1px solid #dce6f0;border-radius:14px;padding:20px;margin-bottom:20px;overflow-x:auto}.fin-account .controls{display:flex;gap:12px;flex-wrap:wrap;align-items:center;margin-bottom:18px}.fin-account input,.fin-account select{border:1px solid #cbd8e7;border-radius:8px;padding:10px;color:#183454;min-width:190px}.fin-account .btn{background:#15549c;color:white!important;padding:10px 16px;border:0;border-radius:8px;text-decoration:none;display:inline-block;font-weight:700;cursor:pointer}.fin-account .btn.light{background:#edf4fb;color:#15549c!important}.fin-account table{width:100%;border-collapse:collapse;font-size:13px}.fin-account th{background:#edf4fb;text-align:left}.fin-account th,.fin-account td{padding:12px;border-bottom:1px solid #e3ebf4}.fin-account .right{text-align:right}.fin-account .cards{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:18px}.fin-account .card{border:1px solid #dce6f0;border-radius:12px;background:white;padding:18px}.fin-account .card strong{display:block;font-size:24px;margin-top:8px}.fin-account .muted{color:#728398;font-size:12px}.fin-account .negative{color:#a23a38}@media(max-width:720px){.fin-account{padding:12px}.fin-account .cards{grid-template-columns:1fr}}
</style>
<div class="fin-account"><h1>Estados de cuenta estudiantiles</h1><p>Una cuenta permanente por estudiante, con historial financiero de todos los ciclos escolares.</p>
<form method="GET" class="panel controls"><input type="search" name="buscar" value="{{ $buscar }}" placeholder="Nombre, código o carné"><button class="btn">Buscar</button><a href="{{ route('direccion.finanzas.estados-cuenta.index') }}" class="btn light">Limpiar</a></form>
<div class="panel"><table><thead><tr><th>Estudiante</th><th>Carné</th><th class="right">Cargos + mora</th><th class="right">Pagado</th><th class="right">Saldo total</th><th>Acción</th></tr></thead><tbody>
@forelse($estudiantes as $e)
@php($saldo = max(0, (float)$e->cargos - (float)$e->abonos_principal) + max(0, (float)$e->recargos - (float)$e->abonos_mora))
<tr><td><strong>{{ $e->apellidos }}, {{ $e->nombres }}</strong><div class="muted">{{ $e->codigo }}</div></td><td>{{ $e->numero_carnet }}</td><td class="right">Q {{ number_format((float)$e->cargos + (float)$e->recargos,2) }}</td><td class="right">Q {{ number_format((float)$e->abonos_principal + (float)$e->abonos_mora,2) }}</td><td class="right"><strong>Q {{ number_format($saldo,2) }}</strong></td><td><a class="btn" href="{{ route('direccion.finanzas.estados-cuenta.show',$e->id) }}">Ver cuenta</a></td></tr>
@empty<tr><td colspan="6">No se encontraron cuentas financieras.</td></tr>@endforelse
</tbody></table><div style="margin-top:15px">{{ $estudiantes->links() }}</div></div></div>
@endsection
