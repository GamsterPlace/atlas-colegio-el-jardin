
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
                        <button type="submit" class="atlas-btn atlas-btn-success">
                            <i class="bi bi-send"></i> Publicar
                        </button>
                    </form>
                @endif

                @if($publicacion->estado === 'PUBLICADA')
                    <form method="POST" action="{{ route('secretaria.publicaciones.archivar', $publicacion) }}" data-atlas-confirmar="¿Retirar esta noticia del sitio institucional?">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="atlas-btn atlas-btn-outline">
                            <i class="bi bi-archive"></i> Archivar
                        </button>
                    </form>
                @endif

                @if($publicacion->estado === 'ARCHIVADA')
                    <form method="POST" action="{{ route('secretaria.publicaciones.borrador', $publicacion) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="atlas-btn atlas-btn-secondary">
                            <i class="bi bi-file-earmark"></i> Volver a borrador
                        </button>
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
                    <label for="titulo" class="atlas-label">
                        Título <span style="color:var(--atlas-red);">*</span>
                    </label>
                    <input
                        id="titulo"
                        name="titulo"
                        type="text"
                        maxlength="190"
                        required
                        class="atlas-input"
                        value="{{ old('titulo', $publicacion->titulo) }}"
                        aria-invalid="{{ $errors->has('titulo') ? 'true' : 'false' }}"
                    >
                    @error('titulo')
                        <small style="color:var(--atlas-red);">{{ $message }}</small>
                    @enderror
                </div>

                <div class="atlas-grid atlas-grid-2" style="align-items:end;">
                    <div class="atlas-form-group">
                        <label for="categoria" class="atlas-label">
                            Categoría <span style="color:var(--atlas-red);">*</span>
                        </label>
                        <select id="categoria" name="categoria" required class="atlas-select">
                            <option value="">Selecciona una categoría</option>
                            @foreach($categorias as $categoria)
                                <option
                                    value="{{ $categoria }}"
                                    @selected(old('categoria', $publicacion->categoria) === $categoria)
                                >
                                    {{ $categoria }}
                                </option>
                            @endforeach
                        </select>
                        @error('categoria')
                            <small style="color:var(--atlas-red);">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="atlas-form-group">
                        <div style="display:flex;gap:12px;align-items:flex-start;padding:12px 14px;border:1px solid var(--atlas-border);border-radius:9px;background:#F9FBFE;min-height:76px;">
                            <input type="hidden" name="destacada" value="0">
                            <input
                                id="destacada"
                                name="destacada"
                                type="checkbox"
                                value="1"
                                @checked((bool) old('destacada', $publicacion->destacada))
                                style="width:18px;height:18px;margin-top:2px;accent-color:var(--atlas-blue);"
                            >
                            <div>
                                <label for="destacada" class="atlas-label" style="margin-bottom:3px;cursor:pointer;">
                                    Destacar publicación
                                </label>
                                <span class="atlas-form-help" style="margin:0;">
                                    Resaltarla en el sitio institucional.
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="atlas-form-group">
                    <label for="resumen" class="atlas-label">Resumen</label>
                    <textarea id="resumen" name="resumen" rows="3" maxlength="2000" class="atlas-textarea">{{ old('resumen', $publicacion->resumen) }}</textarea>
                    @error('resumen')
                        <small style="color:var(--atlas-red);">{{ $message }}</small>
                    @enderror
                </div>

                <div class="atlas-form-group" style="margin-bottom:0;">
                    <label for="contenido" class="atlas-label">
                        Contenido <span style="color:var(--atlas-red);">*</span>
                    </label>
                    <textarea id="contenido" name="contenido" rows="12" required class="atlas-textarea">{{ old('contenido', $publicacion->contenido) }}</textarea>
                    <span class="atlas-form-help">Escribe el contenido sin etiquetas HTML.</span>
                    @error('contenido')
                        <small style="color:var(--atlas-red);">{{ $message }}</small>
                    @enderror
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
                <input
                    id="archivos"
                    name="archivos[]"
                    type="file"
                    multiple
                    accept="image/jpeg,image/png,image/webp,video/mp4,video/webm,video/quicktime,.mov"
                    class="atlas-input"
                >
                <span class="atlas-form-help">
                    JPG, PNG, WEBP, MP4, WEBM y MOV. Máximo 25 MB por fotografía y 200 MB por video; el servidor debe permitir estos tamaños.
                </span>

                @error('archivos')
                    <small style="color:var(--atlas-red);">{{ $message }}</small>
                @enderror
                @error('archivos.*')
                    <small style="color:var(--atlas-red);">{{ $message }}</small>
                @enderror

                <p style="margin:14px 0 0;color:var(--atlas-muted);font-size:12px;">
                    Antes de publicar fotografías de estudiantes, verifica la autorización correspondiente.
                </p>
            </div>
        </section>

        <div style="display:flex;justify-content:flex-end;flex-wrap:wrap;gap:10px;margin-bottom:22px;">
            <a href="{{ route('secretaria.publicaciones.index') }}" class="atlas-btn atlas-btn-outline">
                Cancelar
            </a>
            <button type="submit" class="atlas-btn atlas-btn-primary">
                <i class="bi bi-floppy"></i> Guardar cambios
            </button>
        </div>
    </form>

    <section class="atlas-card" style="margin-bottom:22px;">
        <div class="atlas-card-header">
            <div>
                <h3 class="atlas-card-title">Multimedia guardada</h3>
                <p class="atlas-card-subtitle">
                    Los archivos aparecen en orden de carga. Eliminarlos del servidor es irreversible.
                </p>
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
                                <img
                                    src="{{ asset($rutaPublica) }}"
                                    alt="{{ $archivo->nombre_original ?: 'Fotografía de la publicación' }}"
                                    style="width:100%;height:100%;object-fit:cover;"
                                >
                            @elseif($esVideo)
                                <video controls preload="metadata" style="width:100%;height:100%;object-fit:contain;">
                                    <source src="{{ asset($rutaPublica) }}">
                                    Tu navegador no puede reproducir este video.
                                </video>
                            @else
                                <span style="color:var(--atlas-muted);font-size:12px;">
                                    Archivo multimedia
                                </span>
                            @endif
                        </div>

                        <div style="padding:15px;">
                            <strong style="display:block;overflow-wrap:anywhere;color:var(--atlas-navy);font-size:12px;">
                                {{ $archivo->nombre_original ?: 'Archivo ' . $archivo->id }}
                            </strong>

                            <span class="atlas-form-help">
                                {{ $esFotografia ? 'Fotografía' : ($esVideo ? 'Video' : 'Archivo') }}
                                · Orden {{ $archivo->orden + 1 }}
                            </span>

                            <form
                                method="POST"
                                action="{{ route('secretaria.publicaciones.archivos.destroy', [$publicacion, $archivo]) }}"
                                data-atlas-confirmar="¿Eliminar definitivamente este archivo multimedia?"
                                style="margin-top:12px;"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="atlas-btn atlas-btn-danger atlas-btn-sm" style="width:100%;">
                                    <i class="bi bi-trash"></i> Eliminar archivo
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p style="color:var(--atlas-muted);font-size:13px;">
                        Esta publicación todavía no tiene fotografías ni videos.
                    </p>
                @endforelse
            </div>
        </div>
    </section>
</div>

{{-- Modal institucional ATLAS. Sin ventanas genéricas del navegador. --}}
<div
    id="atlas-confirmacion"
    role="dialog"
    aria-modal="true"
    aria-labelledby="atlas-confirmacion-titulo"
    aria-describedby="atlas-confirmacion-mensaje"
    hidden
    style="position:fixed;inset:0;z-index:9999;background:rgba(12,29,52,.68);display:none;align-items:center;justify-content:center;padding:20px;"
>
    <div style="width:min(100%,470px);background:#fff;border-radius:16px;box-shadow:0 24px 80px rgba(0,0,0,.24);overflow:hidden;">
        <div style="padding:24px 26px 16px;display:flex;gap:14px;align-items:center;border-bottom:1px solid #e7edf4;">
            <span style="width:44px;height:44px;display:grid;place-items:center;border-radius:12px;background:#fff4e5;color:#a66b14;font-size:23px;flex:none;">
                <i class="bi bi-exclamation-triangle" aria-hidden="true"></i>
            </span>
            <div>
                <h3 id="atlas-confirmacion-titulo" style="font-size:18px;color:var(--atlas-navy,#17385a);font-weight:800;margin:0;">
                    Confirmar operación
                </h3>
                <p style="font-size:12px;color:var(--atlas-muted,#65748a);margin:5px 0 0;">
                    Noticias y publicaciones · ATLAS
                </p>
            </div>
        </div>

        <div style="padding:22px 26px;">
            <p id="atlas-confirmacion-mensaje" style="font-size:14px;line-height:1.7;color:var(--atlas-text,#30445e);margin:0;"></p>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:10px;flex-wrap:wrap;padding:0 26px 24px;">
            <button type="button" id="atlas-confirmacion-cancelar" class="atlas-btn atlas-btn-outline">
                Cancelar
            </button>
            <button type="button" id="atlas-confirmacion-aceptar" class="atlas-btn atlas-btn-danger">
                Confirmar
            </button>
        </div>
    </div>
</div>

<script>
(() => {
    const dialogo = document.getElementById('atlas-confirmacion');
    if (!dialogo) return;

    const mensaje = document.getElementById('atlas-confirmacion-mensaje');
    const aceptar = document.getElementById('atlas-confirmacion-aceptar');
    const cancelar = document.getElementById('atlas-confirmacion-cancelar');

    let formularioPendiente = null;
    let botonAnterior = null;

    const cerrar = () => {
        dialogo.hidden = true;
        dialogo.style.display = 'none';
        document.body.style.overflow = '';
        formularioPendiente = null;

        if (botonAnterior) {
            botonAnterior.focus();
        }
    };

    document.addEventListener('submit', (evento) => {
        const form = evento.target;

        if (!(form instanceof HTMLFormElement) ||
            !form.hasAttribute('data-atlas-confirmar')) {
            return;
        }

        evento.preventDefault();

        formularioPendiente = form;
        botonAnterior = document.activeElement;

        mensaje.textContent =
            form.getAttribute('data-atlas-confirmar') ||
            '¿Deseas continuar con esta operación?';

        aceptar.disabled = false;
        dialogo.hidden = false;
        dialogo.style.display = 'flex';
        document.body.style.overflow = 'hidden';

        cancelar.focus();
    });

    cancelar.addEventListener('click', cerrar);

    aceptar.addEventListener('click', () => {
        if (!formularioPendiente) return;

        aceptar.disabled = true;

        const form = formularioPendiente;
        formularioPendiente = null;

        HTMLFormElement.prototype.submit.call(form);
    });

    dialogo.addEventListener('click', (evento) => {
        if (evento.target === dialogo) {
            cerrar();
        }
    });

    document.addEventListener('keydown', (evento) => {
        if (dialogo.hidden) return;

        if (evento.key === 'Escape') {
            evento.preventDefault();
            cerrar();
        }

        if (evento.key === 'Tab') {
            if (evento.shiftKey && document.activeElement === cancelar) {
                evento.preventDefault();
                aceptar.focus();
            } else if (!evento.shiftKey && document.activeElement === aceptar) {
                evento.preventDefault();
                cancelar.focus();
            }
        }
    });
})();
</script>
@endsection
