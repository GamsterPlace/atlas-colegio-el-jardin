@extends('layouts.direccion')

@section('title', 'Nueva publicación')
@section('role', 'Dirección')
@section('module-title', 'Noticias y publicaciones')
@section('dashboard-url', route('direccion.dashboard'))

@section('page-heading')
    <h2>Nueva publicación institucional</h2>
    <p>Redacta una noticia y adjunta fotografías o videos para el sitio web del colegio.</p>
@endsection

@section('content')
<div style="max-width:1120px;margin:0 auto;">
    <div style="display:flex;justify-content:flex-end;margin-bottom:20px;">
        <a href="{{ route('direccion.publicaciones.index') }}" class="atlas-btn atlas-btn-outline">
            <i class="bi bi-arrow-left"></i> Volver al listado
        </a>
    </div>

    <form action="{{ route('direccion.publicaciones.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <section class="atlas-card" style="margin-bottom:22px;">
            <div class="atlas-card-header">
                <div>
                    <h3 class="atlas-card-title">Información de la noticia</h3>
                    <p class="atlas-card-subtitle">Completa los datos que se mostrarán en el sitio institucional.</p>
                </div>
                <i class="bi bi-file-earmark-text" aria-hidden="true" style="font-size:22px;color:var(--atlas-navy);"></i>
            </div>
            <div class="atlas-card-body">
                <div class="atlas-form-group">
                    <label for="titulo" class="atlas-label">Título <span style="color:var(--atlas-red);">*</span></label>
                    <input type="text" id="titulo" name="titulo" maxlength="190" required
                           class="atlas-input" value="{{ old('titulo') }}"
                           placeholder="Ej. Feria científica del Colegio Mixto El Jardín"
                           aria-invalid="{{ $errors->has('titulo') ? 'true' : 'false' }}">
                    @error('titulo')<small style="display:block;margin-top:7px;color:var(--atlas-red);">{{ $message }}</small>@enderror
                </div>

                <div class="atlas-grid atlas-grid-2" style="align-items:end;">
                    <div class="atlas-form-group">
                        <label for="categoria" class="atlas-label">Categoría <span style="color:var(--atlas-red);">*</span></label>
                        <select id="categoria" name="categoria" required class="atlas-select">
                            <option value="">Selecciona una categoría</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria }}" @selected(old('categoria') === $categoria)>{{ $categoria }}</option>
                            @endforeach
                        </select>
                        @error('categoria')<small style="display:block;margin-top:7px;color:var(--atlas-red);">{{ $message }}</small>@enderror
                    </div>
                    <div class="atlas-form-group">
                        <div style="display:flex;align-items:flex-start;gap:12px;padding:12px 14px;border:1px solid var(--atlas-border);border-radius:9px;background:#F9FBFE;min-height:76px;">
                            <input type="hidden" name="destacada" value="0">
                            <input type="checkbox" id="destacada" name="destacada" value="1" @checked(old('destacada', '0') == '1')
                                   style="width:18px;height:18px;margin-top:2px;accent-color:var(--atlas-blue);">
                            <div>
                                <label for="destacada" class="atlas-label" style="margin-bottom:3px;cursor:pointer;">Destacar publicación</label>
                                <span class="atlas-form-help" style="margin:0;">Resaltarla en el sitio institucional.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="atlas-form-group">
                    <label for="resumen" class="atlas-label">Resumen</label>
                    <textarea id="resumen" name="resumen" rows="3" maxlength="2000" class="atlas-textarea"
                              placeholder="Breve introducción de la noticia">{{ old('resumen') }}</textarea>
                    @error('resumen')<small style="display:block;margin-top:7px;color:var(--atlas-red);">{{ $message }}</small>@enderror
                </div>

                <div class="atlas-form-group" style="margin-bottom:0;">
                    <label for="contenido" class="atlas-label">Contenido <span style="color:var(--atlas-red);">*</span></label>
                    <textarea id="contenido" name="contenido" rows="12" required class="atlas-textarea"
                              placeholder="Escribe aquí el contenido completo de la noticia...">{{ old('contenido') }}</textarea>
                    <span class="atlas-form-help">El texto se almacenará tal como lo escribas. Evita incluir etiquetas HTML.</span>
                    @error('contenido')<small style="display:block;margin-top:7px;color:var(--atlas-red);">{{ $message }}</small>@enderror
                </div>
            </div>
        </section>

        <section class="atlas-card" style="margin-bottom:22px;">
            <div class="atlas-card-header">
                <div>
                    <h3 class="atlas-card-title">Fotografías y videos</h3>
                    <p class="atlas-card-subtitle">Adjunta varios archivos multimedia a una misma publicación.</p>
                </div>
                <i class="bi bi-images" aria-hidden="true" style="font-size:22px;color:var(--atlas-navy);"></i>
            </div>
            <div class="atlas-card-body">
                <div class="atlas-form-group" style="margin-bottom:12px;">
                    <label for="archivos" class="atlas-label">Seleccionar archivos multimedia</label>
                    <input id="archivos" name="archivos[]" type="file" multiple
                           accept="image/jpeg,image/png,image/webp,video/mp4,video/webm,video/quicktime,.mov"
                           class="atlas-input" style="height:auto;">
                    <span class="atlas-form-help">Formatos: JPG, PNG, WEBP, MP4, WEBM y MOV. Máximo 25 MB por fotografía y 200 MB por video, sujeto a los límites del servidor.</span>
                    @error('archivos')<small style="display:block;margin-top:7px;color:var(--atlas-red);">{{ $message }}</small>@enderror
                    @error('archivos.*')<small style="display:block;margin-top:7px;color:var(--atlas-red);">{{ $message }}</small>@enderror
                </div>
                <div class="atlas-alert atlas-alert-info" style="margin:0;">
                    <i class="bi bi-shield-check" aria-hidden="true"></i>
                    <span>Antes de publicar fotografías o videos de estudiantes, verifica que exista la autorización correspondiente.</span>
                </div>
            </div>
        </section>

        <section class="atlas-card">
            <div class="atlas-card-body" style="display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap;">
                <p style="margin:0;max-width:590px;color:var(--atlas-muted);font-size:12px;line-height:1.7;">
                    La noticia se guardará como <strong>borrador</strong>. Podrás publicarla desde el listado o la pantalla de edición.
                </p>
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    <a href="{{ route('direccion.publicaciones.index') }}" class="atlas-btn atlas-btn-outline">Cancelar</a>
                    <button type="submit" class="atlas-btn atlas-btn-primary"><i class="bi bi-floppy"></i> Guardar borrador</button>
                </div>
            </div>
        </section>
    </form>
</div>
@endsection
