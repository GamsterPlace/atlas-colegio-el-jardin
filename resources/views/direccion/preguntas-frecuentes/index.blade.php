@extends('layouts.direccion')

@section('title', 'Preguntas frecuentes')
@section('module-title', 'Preguntas frecuentes')
@section('role', 'Dirección')
@section('dashboard-url', route('direccion.dashboard'))

@section('page-heading')
    <h2>Preguntas frecuentes</h2>
    <p>Administra la información que las familias consultan en el sitio web institucional.</p>
@endsection

@push('styles')
<style>
.atlas-faq{display:grid;gap:22px;max-width:1320px;margin:0 auto}
.atlas-faq-toolbar{display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap}
.atlas-faq-lead{color:var(--atlas-muted,#68788d);font-size:14px;line-height:1.6;margin:4px 0 0}
.atlas-faq-stats{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}
.atlas-faq-stat{background:#fff;border:1px solid var(--atlas-border,#dfe6ef);border-radius:13px;padding:20px;display:flex;align-items:center;gap:14px}
.atlas-faq-stat i{font-size:23px;color:#1b548b;background:#eaf2fc;padding:12px;border-radius:12px}
.atlas-faq-stat strong{font-size:25px;color:#183756;display:block}
.atlas-faq-stat small{color:#6c7c8e;font-size:12px}
.atlas-faq-table-wrap{overflow-x:auto}
.atlas-faq-table{width:100%;border-collapse:collapse;text-align:left;font-size:13px}
.atlas-faq-table th{background:#f4f7fb;color:#49617c;font-size:11px;text-transform:uppercase;letter-spacing:.045em;padding:15px}
.atlas-faq-table td{padding:16px 15px;border-top:1px solid #e8edf3;color:#30465f;vertical-align:middle}
.atlas-faq-table tbody tr:hover{background:#fafcff}
.atlas-faq-question{font-weight:700;color:#173657;max-width:460px}
.atlas-faq-chip{display:inline-flex;padding:5px 10px;border-radius:30px;font-size:11px;font-weight:800;white-space:nowrap}
.atlas-faq-chip--green{background:#e5f6ed;color:#177349}
.atlas-faq-chip--gray{background:#edf0f4;color:#68778a}
.atlas-faq-chip--blue{background:#e8f1ff;color:#225ca4}
.atlas-faq-actions{display:flex;gap:8px;flex-wrap:wrap}
.atlas-faq-action{display:inline-flex;align-items:center;gap:6px;padding:8px 11px;border:1px solid #d8e3ef;background:white;border-radius:8px;text-decoration:none;color:#23558b;font-weight:700;font-size:12px;cursor:pointer}
.atlas-faq-action--danger{color:#b32d43;border-color:#f1d1d7}
.atlas-faq-empty{text-align:center;padding:48px 20px;color:#68788d}
.atlas-faq-empty i{font-size:36px;color:#9cb4cd;display:block;margin-bottom:12px}
.atlas-faq-form{max-width:1050px;margin:0 auto;display:grid;gap:20px}
.atlas-faq-form-grid{display:grid;grid-template-columns:2fr 1fr;gap:16px}
.atlas-faq-checks{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.atlas-faq-check{border:1px solid #dce5ef;background:#f8fbff;border-radius:12px;padding:16px;display:flex;gap:12px;align-items:flex-start}
.atlas-faq-check input{width:18px;height:18px;accent-color:#1e5b99;flex-shrink:0;margin-top:3px}
.atlas-faq-check strong{display:block;color:#193b60;font-size:13px}
.atlas-faq-check small{display:block;color:#64788e;font-size:12px;margin-top:5px;line-height:1.5}
.atlas-faq-error{color:#b52643;font-size:12px;margin-top:5px;display:block}
.atlas-faq-banner{padding:13px 16px;border-radius:10px;background:#e8f6ee;color:#1b7547;border:1px solid #c9e9d7;font-weight:600}
.atlas-faq-banner--error{background:#fff0f2;color:#a92e46;border-color:#f1cbd2}
.atlas-faq-footer{display:flex;justify-content:space-between;gap:12px;align-items:center;flex-wrap:wrap}
.atlas-faq-visit-filters{display:grid;grid-template-columns:1fr 1fr auto auto;gap:12px;align-items:end}
.atlas-faq-visit-table{min-width:960px}
.atlas-faq-visit-edit{display:grid;grid-template-columns:145px 120px 150px;gap:8px}
.atlas-faq-visit-edit .atlas-input,.atlas-faq-visit-edit .atlas-select{width:100%;min-width:0}
.atlas-faq-visit-edit .atlas-faq-note{grid-column:1 / 3}
.atlas-faq-visit-edit button{grid-column:3}
@media(max-width:760px){.atlas-faq-stats{grid-template-columns:1fr}.atlas-faq-form-grid,.atlas-faq-checks,.atlas-faq-visit-filters{grid-template-columns:1fr}.atlas-faq-toolbar{align-items:flex-start}.atlas-faq-visit-edit{grid-template-columns:1fr 1fr}}
</style>
@endpush

@section('content')
<div class="atlas-faq">
    @if(session('success'))
        <div class="atlas-faq-banner" role="status"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
    @endif

    <div class="atlas-faq-toolbar">
        <div>
            <h3 style="margin:0;color:#183756">Administración de preguntas</h3>
            <p class="atlas-faq-lead">Organiza, publica y destaca respuestas institucionales.</p>
        </div>
        <a href="{{ route('direccion.preguntas-frecuentes.create') }}" class="atlas-btn atlas-btn-primary">
            <i class="bi bi-plus-lg" aria-hidden="true"></i> Nueva pregunta
        </a>
    </div>

    <div class="atlas-faq-stats">
        <div class="atlas-faq-stat"><i class="bi bi-question-circle"></i><div><strong>{{ $preguntas->total() }}</strong><small>Preguntas registradas</small></div></div>
        <div class="atlas-faq-stat"><i class="bi bi-list-ol"></i><div><strong>{{ $preguntas->count() }}</strong><small>Mostradas en esta página</small></div></div>
        <div class="atlas-faq-stat"><i class="bi bi-globe2"></i><div><strong>Web</strong><small>Publicación institucional</small></div></div>
    </div>

    <section class="atlas-card">
        <div class="atlas-card-header">
            <div>
                <h3 class="atlas-card-title">Listado de preguntas frecuentes</h3>
                <p class="atlas-card-subtitle">Las preguntas ocultas permanecen registradas, pero no se muestran al público.</p>
            </div>
        </div>
        <div class="atlas-faq-table-wrap">
            <table class="atlas-faq-table">
                <thead><tr><th>Orden</th><th>Pregunta</th><th>Categoría</th><th>Publicación</th><th>Inicio</th><th>Acciones</th></tr></thead>
                <tbody>
                @forelse($preguntas as $item)
                    <tr>
                        <td>{{ $item->orden }}</td>
                        <td><div class="atlas-faq-question">{{ $item->pregunta }}</div></td>
                        <td>{{ $item->categoria }}</td>
                        <td><span class="atlas-faq-chip {{ $item->publicada ? 'atlas-faq-chip--green' : 'atlas-faq-chip--gray' }}">{{ $item->publicada ? 'Publicada' : 'Oculta' }}</span></td>
                        <td>@if($item->destacada)<span class="atlas-faq-chip atlas-faq-chip--blue"><i class="bi bi-star-fill me-1"></i> Destacada</span>@else<span style="color:#8390a0">—</span>@endif</td>
                        <td>
                            <div class="atlas-faq-actions">
                                <a href="{{ route('direccion.preguntas-frecuentes.edit', $item) }}" class="atlas-faq-action"><i class="bi bi-pencil-square"></i> Editar</a>
                                <form method="POST" action="{{ route('direccion.preguntas-frecuentes.destroy', $item) }}" onsubmit="return confirm('¿Eliminar definitivamente esta pregunta?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="atlas-faq-action atlas-faq-action--danger"><i class="bi bi-trash"></i> Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6"><div class="atlas-faq-empty"><i class="bi bi-chat-square-text"></i><strong>No hay preguntas registradas</strong><p>Comienza creando la primera pregunta frecuente.</p><a class="atlas-btn atlas-btn-primary" href="{{ route('direccion.preguntas-frecuentes.create') }}">Crear pregunta</a></div></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($preguntas->hasPages())
            <div class="atlas-card-body">{{ $preguntas->links() }}</div>
        @endif
    </section>
</div>
@endsection
