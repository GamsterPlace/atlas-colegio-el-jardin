@extends('layouts.direccion')

@section('title', $preguntaFrecuente->exists ? 'Editar pregunta frecuente' : 'Nueva pregunta frecuente')
@section('module-title', 'Preguntas frecuentes')
@section('role', 'Dirección')
@section('dashboard-url', route('direccion.dashboard'))

@section('page-heading')
    <h2>{{ $preguntaFrecuente->exists ? 'Editar pregunta frecuente' : 'Nueva pregunta frecuente' }}</h2>
    <p>Configura el contenido que se mostrará en la página institucional.</p>
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
<div class="atlas-faq-form">
    <div class="atlas-faq-toolbar">
        <div><h3 style="margin:0;color:#183756">Datos de la pregunta</h3><p class="atlas-faq-lead">Los campos marcados con * son obligatorios.</p></div>
        <a href="{{ route('direccion.preguntas-frecuentes.index') }}" class="atlas-btn atlas-btn-outline"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>

    @if($errors->any())
        <div class="atlas-faq-banner atlas-faq-banner--error" role="alert">
            <strong>Revisa los datos ingresados.</strong>
            <ul style="margin:8px 0 0;padding-left:20px">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ $preguntaFrecuente->exists ? route('direccion.preguntas-frecuentes.update', $preguntaFrecuente) : route('direccion.preguntas-frecuentes.store') }}">
        @csrf
        @if($preguntaFrecuente->exists) @method('PUT') @endif
        <section class="atlas-card" style="margin-bottom:20px">
            <div class="atlas-card-header"><div><h3 class="atlas-card-title"><i class="bi bi-chat-left-text me-2"></i>Pregunta y respuesta</h3><p class="atlas-card-subtitle">Redacta información clara y útil para las familias.</p></div></div>
            <div class="atlas-card-body">
                <div class="atlas-form-group">
                    <label for="pregunta" class="atlas-label">Pregunta *</label>
                    <input id="pregunta" name="pregunta" class="atlas-input" type="text" maxlength="250" required value="{{ old('pregunta', $preguntaFrecuente->pregunta) }}" placeholder="Ej. ¿Cómo puedo inscribir a mi hijo?">
                    @error('pregunta')<small class="atlas-faq-error">{{ $message }}</small>@enderror
                </div>
                <div class="atlas-form-group">
                    <label for="respuesta" class="atlas-label">Respuesta *</label>
                    <textarea id="respuesta" name="respuesta" class="atlas-input" rows="8" maxlength="10000" required style="height:auto;min-height:170px;resize:vertical" placeholder="Escribe aquí la respuesta institucional...">{{ old('respuesta', $preguntaFrecuente->respuesta) }}</textarea>
                    <small class="atlas-form-help">Los saltos de línea se conservarán en la página pública.</small>
                    @error('respuesta')<small class="atlas-faq-error">{{ $message }}</small>@enderror
                </div>
            </div>
        </section>

        <section class="atlas-card" style="margin-bottom:20px">
            <div class="atlas-card-header"><div><h3 class="atlas-card-title"><i class="bi bi-sliders me-2"></i>Organización y visibilidad</h3><p class="atlas-card-subtitle">Define dónde y cómo aparecerá esta pregunta.</p></div></div>
            <div class="atlas-card-body">
                <div class="atlas-faq-form-grid">
                    <div class="atlas-form-group">
                        <label for="categoria" class="atlas-label">Categoría *</label>
                        <input id="categoria" name="categoria" class="atlas-input" list="faqCategorias" maxlength="80" required value="{{ old('categoria', $preguntaFrecuente->categoria ?: 'General') }}" placeholder="Ej. Inscripciones">
                        <datalist id="faqCategorias"><option value="General"><option value="Inscripciones"><option value="Horarios"><option value="Pagos"><option value="Oferta académica"><option value="Servicios"></datalist>
                        @error('categoria')<small class="atlas-faq-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="atlas-form-group">
                        <label for="orden" class="atlas-label">Orden de aparición *</label>
                        <input id="orden" name="orden" class="atlas-input" type="number" min="0" max="99999" required value="{{ old('orden', $preguntaFrecuente->orden ?? 0) }}">
                        @error('orden')<small class="atlas-faq-error">{{ $message }}</small>@enderror
                    </div>
                </div>
                <div class="atlas-faq-checks">
                    <label class="atlas-faq-check">
                        <input type="hidden" name="publicada" value="0">
                        <input type="checkbox" name="publicada" value="1" @checked(old('publicada', $preguntaFrecuente->publicada) == 1)>
                        <span><strong>Publicar en el sitio web</strong><small>La pregunta será visible para los visitantes de la página institucional.</small></span>
                    </label>
                    <label class="atlas-faq-check">
                        <input type="hidden" name="destacada" value="0">
                        <input type="checkbox" name="destacada" value="1" @checked(old('destacada', $preguntaFrecuente->destacada) == 1)>
                        <span><strong>Destacar en Inicio</strong><small>Se mostrará en la página principal si también está publicada.</small></span>
                    </label>
                </div>
            </div>
        </section>
        <section class="atlas-card"><div class="atlas-card-body atlas-faq-footer">
            <span class="atlas-faq-lead"><i class="bi bi-shield-check me-1"></i> Los cambios serán administrados por Dirección.</span>
            <div style="display:flex;gap:10px;flex-wrap:wrap">
                <a class="atlas-btn atlas-btn-outline" href="{{ route('direccion.preguntas-frecuentes.index') }}">Cancelar</a>
                <button class="atlas-btn atlas-btn-primary" type="submit"><i class="bi bi-floppy"></i> Guardar pregunta</button>
            </div>
        </div></section>
    </form>
</div>
@endsection
