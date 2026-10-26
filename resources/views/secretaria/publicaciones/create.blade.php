
@extends('layouts.secretaria')

@section('title', 'Editar publicación')
@section('role', 'Secretaría')
@section('module-title', 'Noticias y publicaciones')
@section('dashboard-url', route('secretaria.dashboard'))

@section('page-heading')
    <h2>Editar publicación</h2>
    <p>Actualiza la noticia y administra las fotografías y videos que se muestran en el sitio institucional.</p>
@endsection

@section('content')
<div style="max-width:1120px;margin:0 auto;">
    <div style="display:flex;justify-content:flex-end;margin-bottom:20px;">
        <a href="{{ route('secretaria.publicaciones.index') }}" class="atlas-btn atlas-btn-outline">
            <i class="bi bi-arrow-left" aria-hidden="true"></i> Volver al listado
        </a>
    </div>

    @php
        $estadoColor = match ($publicacion->estado) {
            'PUBLICADA' => 'atlas-badge-green',
            'ARCHIVADA' => 'atlas-badge-red',
            default => 'atlas-badge-amber',
        };
    @endphp

    <section class="atlas-card" style="margin-bottom:22px;">
        <div class="atlas-card-body" style="display:flex;justify-content:space-between;align-items:center;gap:18px;flex-wrap:wrap;">
            <div>
                <div style="display:flex;gap:9px;align-items:center;flex-wrap:wrap;">
                    <span class="atlas-badge {{ $estadoColor }}">{{ ucfirst(mb_strtolower($publicacion->estado)) }}</span>
                    @if($publicacion->destacada)
                        <span class="atlas-badge atlas-badge-amber"><i class="bi bi-star-fill"></i> Destacada</span>
                    @endif
                </div>
                <p class="atlas-card-subtitle" style="margin-top:9px;">Registrada el {{ $publicacion->created_at?->format('d/m/Y H:i') ?? '—' }}</p>
            </div>
            <div style="display:flex;flex-wrap:wrap;gap:9px;">
                @if($publicacion->estado !== 'PUBLICADA')
                    <form method="POST" action="{{ route('secretaria.publicaciones.publicar', $publicacion) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="atlas-btn atlas-btn-success"><i class="bi bi-send"></i> Publicar</button>
                    </form>
                @endif
                @if($publicacion->estado === 'PUBLICADA')
                    <form method="POST" action="{{ route('secretaria.publicaciones.archivar', $publicacion) }}" onsubmit="return confirm('¿Retirar esta noticia del sitio institucional?');">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="atlas-btn atlas-btn-outline"><i class="bi bi-archive"></i> Archivar</button>
                    </form>
                @endif
                @if($publicacion->estado === 'ARCHIVADA')
                    <form method="POST" action="{{ route('secretaria.publicaciones.borrador', $publicacion) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="atlas-btn atlas-btn-secondary"><i class="bi bi-file-earmark"></i> Volver a borrador</button>
                    </form>
                @endif
            </div>
        </div>
    </section>

    <form method="POST" action="{{ route('secretaria.publicaciones.update', $publicacion) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <section class="atlas-card" style="margin-bottom:22px;">
            <div class="atlas-card-header">
                <div>
                    <h3 class="atlas-card-title">Información de la noticia</h3>
                    <p class="atlas-card-subtitle">Modifica los datos de la publicación.</p>
                </div>
                <i class="bi bi-file-earmark-text" aria-hidden="true" style="font-size:22px;color:var(--atlas-navy);"></i>
            </div>
            <div class="atlas-card-body">
                <div class="atlas-form-group">
                    <label for="titulo" class="atlas-label">Título <span style="color:var(--atlas-red);">*</span></label>
                    <input id="titulo" name="titulo" type="text" maxlength="190" required class="atlas-input" value="{{ old('titulo', $publicacion->titulo) }}" aria-invalid="{{ $errors->has('titulo') ? 'true' : 'false' }}">
                    @error('titulo')<small style="color:var(--atlas-red);">{{ $message }}</small>@enderror
                </div>
                <div class="atlas-grid atlas-grid-2" style="align-items:end;">
                    <div class="atlas-form-group">
                        <label for="categoria" class="atlas-label">Categoría <span style="color:var(--atlas-red);">*</span></label>
                        <select id="categoria" name="categoria" required class="atlas-select">
                            <option value="">Selecciona una categoría</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria }}" @selected(old('categoria', $publicacion->categoria) === $categoria)>{{ $categoria }}</option>
                            @endforeach
                        </select>
                        @error('categoria')<small style="color:var(--atlas-red);">{{ $message }}</small>@enderror
                    </div>
                    <div class="atlas-form-group">
                        <div style="display:flex;gap:12px;align-items:flex-start;padding:12px 14px;border:1px solid var(--atlas-border);border-radius:9px;background:#F9FBFE;min-height:76px;">
                            <input type="hidden" name="destacada" value="0">
                            <input id="destacada" name="destacada" type="checkbox" value="1" @checked((bool) old('destacada', $publicacion->destacada)) style="width:18px;height:18px;margin-top:2px;accent-color:var(--atlas-blue);">
                            <div>
                                <label for="destacada" class="atlas-label" style="margin-bottom:3px;cursor:pointer;">Destacar publicación</label>
                                <span class="atlas-form-help" style="margin:0;">Resaltarla en el sitio institucional.</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="atlas-form-group">
                    <label for="resumen" class="atlas-label">Resumen</label>
                    <textarea id="resumen" name="resumen" rows="3" maxlength="2000" class="atlas-textarea">{{ old('resumen', $publicacion->resumen) }}</textarea>
                    @error('resumen')<small style="color:var(--atlas-red);">{{ $message }}</small>@enderror
                </div>
                <div class="atlas-form-group" style="margin-bottom:0;">
                    <label for="contenido" class="atlas-label">Contenido <span style="color:var(--atlas-red);">*</span></label>
                    <textarea id="contenido" name="contenido" rows="12" required class="atlas-textarea">{{ old('contenido', $publicacion->contenido) }}</textarea>
                    <span class="atlas-form-help">Escribe el contenido sin etiquetas HTML.</span>
                    @error('contenido')<small style="color:var(--atlas-red);">{{ $message }}</small>@enderror
                </div>
            </div>
        </section>

        <section class="atlas-card" style="margin-bottom:22px;">
            <div class="atlas-card-header">
                <div>
                    <h3 class="atlas-card-title">Agregar fotografías y videos</h3>
                    <p class="atlas-card-subtitle">Los nuevos archivos se añadirán a los existentes.</p>
                </div>
                <i class="bi bi-images" aria-hidden="true" style="font-size:22px;color:var(--atlas-navy);"></i>
            </div>
            <div class="atlas-card-body">
                <label for="archivos" class="atlas-label">Archivos adicionales</label>
                <input id="archivos" name="archivos[]" type="file" multiple accept="image/jpeg,image/png,image/webp,video/mp4,video/webm,video/quicktime,.mov" class="atlas-input">
                <span class="atlas-form-help">JPG, PNG, WEBP, MP4, WEBM y MOV. Máximo 25 MB por fotografía y 200 MB por video; el servidor debe permitir estos tamaños.</span>
                @error('archivos')<small style="color:var(--atlas-red);">{{ $message }}</small>@enderror
                @error('archivos.*')<small style="color:var(--atlas-red);">{{ $message }}</small>@enderror
                <p style="margin:14px 0 0;color:var(--atlas-muted);font-size:12px;">Antes de publicar fotografías de estudiantes, verifica la autorización correspondiente.</p>
            </div>
        </section>
        <div style="display:flex;justify-content:flex-end;flex-wrap:wrap;gap:10px;margin-bottom:22px;">
            <a href="{{ route('secretaria.publicaciones.index') }}" class="atlas-btn atlas-btn-outline">Cancelar</a>
            <button type="submit" class="atlas-btn atlas-btn-primary"><i class="bi bi-floppy"></i> Guardar cambios</button>
        </div>
    </form>

    <section class="atlas-card" style="margin-bottom:22px;">
        <div class="atlas-card-header">
            <div>
                <h3 class="atlas-card-title">Multimedia guardada</h3>
                <p class="atlas-card-subtitle">Los archivos aparecen en orden de carga. Eliminarlos del servidor es irreversible.</p>
            </div>
        </div>
        <div class="atlas-card-body">
            <div class="atlas-grid atlas-grid-3">
                @forelse($publicacion->registrosArchivoPublicacion as $archivo)
                    @php
                        $rutaPublica = ltrim((string) $archivo->ruta_archivo, '/');
                        $esFotografia = $archivo->tipo === 'FOTOGRAFIA';
                        $esVideo = $archivo->tipo === 'VIDEO';
                    @endphp
                    <div style="min-width:0;border:1px solid var(--atlas-border);border-radius:12px;overflow:hidden;background:#fff;">
                        <div style="aspect-ratio:16/9;background:#F4F6FA;display:flex;align-items:center;justify-content:center;overflow:hidden;">
                            @if($esFotografia)
                                <img src="{{ asset($rutaPublica) }}" alt="{{ $archivo->nombre_original ?: 'Fotografía de la publicación' }}" style="width:100%;height:100%;object-fit:cover;">
                            @elseif($esVideo)
                                <video controls preload="metadata" style="width:100%;height:100%;object-fit:contain;">
                                    <source src="{{ asset($rutaPublica) }}">
                                    Tu navegador no puede reproducir este video.
                                </video>
                            @else
                                <span style="color:var(--atlas-muted);font-size:12px;">Archivo multimedia</span>
                            @endif
                        </div>
                        <div style="padding:15px;">
                            <strong style="display:block;overflow-wrap:anywhere;color:var(--atlas-navy);font-size:12px;">{{ $archivo->nombre_original ?: 'Archivo ' . $archivo->id }}</strong>
                            <span class="atlas-form-help">{{ $esFotografia ? 'Fotografía' : ($esVideo ? 'Video' : 'Archivo') }} · Orden {{ $archivo->orden + 1 }}</span>
                            <form method="POST" action="{{ route('secretaria.publicaciones.archivos.destroy', [$publicacion, $archivo]) }}" onsubmit="return confirm('¿Eliminar definitivamente este archivo multimedia?');" style="margin-top:12px;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="atlas-btn atlas-btn-danger atlas-btn-sm" style="width:100%;"><i class="bi bi-trash"></i> Eliminar archivo</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p style="color:var(--atlas-muted);font-size:13px;">Esta publicación todavía no tiene fotografías ni videos.</p>
                @endforelse
            </div>
        </div>
    </section>
</div>
@endsection
