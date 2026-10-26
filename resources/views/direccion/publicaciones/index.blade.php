@extends('layouts.direccion')

@section('title', 'Noticias y publicaciones')
@section('role', 'Dirección')
@section('module-title', 'Noticias y publicaciones')
@section('dashboard-url', route('direccion.dashboard'))

@section('page-heading')
    <h2>Noticias y publicaciones</h2>
    <p>Administra el contenido que se muestra en el sitio web institucional.</p>

@endsection

@section('content')
<div class="atlas-grid" style="gap: 22px;">
    <div style="display:flex;justify-content:flex-end;">
        <a href="{{ route('direccion.publicaciones.create') }}" class="atlas-btn atlas-btn-primary">
            <i class="bi bi-plus-lg" aria-hidden="true"></i> Nueva publicación
        </a>
    </div>

    <section class="atlas-card" aria-label="Filtros de publicaciones">
        <div class="atlas-card-header">
            <h3 class="atlas-card-title">Buscar publicaciones</h3>
        </div>
        <div class="atlas-card-body">
            <form action="{{ route('direccion.publicaciones.index') }}" method="GET">
                <div class="atlas-grid atlas-grid-2">
                    <div class="atlas-form-group" style="margin-bottom:0;">
                        <label class="atlas-label" for="estado">Estado</label>
                        <select class="atlas-select" name="estado" id="estado">
                            <option value="">Todos los estados</option>
                            @foreach($estados as $estado)
                                <option value="{{ $estado }}" @selected(request('estado') === $estado)>
                                    {{ ucfirst(mb_strtolower($estado)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="atlas-form-group" style="margin-bottom:0;">
                        <label class="atlas-label" for="categoria">Categoría</label>
                        <select class="atlas-select" name="categoria" id="categoria">
                            <option value="">Todas las categorías</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria }}" @selected(request('categoria') === $categoria)>
                                    {{ $categoria }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div style="display:flex;justify-content:flex-end;flex-wrap:wrap;gap:10px;margin-top:20px;">
                    <a class="atlas-btn atlas-btn-outline" href="{{ route('direccion.publicaciones.index') }}">
                        <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i> Limpiar
                    </a>
                    <button class="atlas-btn atlas-btn-primary" type="submit">
                        <i class="bi bi-funnel" aria-hidden="true"></i> Aplicar filtros
                    </button>
                </div>
            </form>
        </div>
    </section>

    <section class="atlas-card" aria-label="Listado de publicaciones">
        <div class="atlas-card-header">
            <div>
                <h3 class="atlas-card-title">Publicaciones registradas</h3>
                <p class="atlas-card-subtitle">{{ $publicaciones->total() }} registro(s) encontrados</p>
            </div>
        </div>
        <div class="atlas-table-responsive">
            <table class="atlas-table">
                <thead>
                    <tr>
                        <th scope="col">Noticia</th>
                        <th scope="col">Categoría</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Multimedia</th>
                        <th scope="col">Creación</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($publicaciones as $publicacion)
                        <tr>
                            <td>
                                <div class="atlas-table-primary">{{ $publicacion->titulo }}</div>
                                @if($publicacion->destacada)
                                    <div style="margin-top:7px;">
                                        <span class="atlas-badge atlas-badge-amber"><i class="bi bi-star-fill" aria-hidden="true"></i> Destacada</span>
                                    </div>
                                @endif
                                @if($publicacion->resumen)
                                    <div style="max-width:340px;margin-top:7px;color:var(--atlas-muted);line-height:1.6;">
                                        {{ \Illuminate\Support\Str::limit($publicacion->resumen, 95) }}
                                    </div>
                                @endif
                            </td>
                            <td>{{ $publicacion->categoria ?: 'Sin categoría' }}</td>
                            <td>
                                @php
                                    $claseEstado = match ($publicacion->estado) {
                                        'PUBLICADA' => 'atlas-badge-green',
                                        'ARCHIVADA' => '',
                                        default => 'atlas-badge-amber',
                                    };
                                @endphp
                                <span class="atlas-badge {{ $claseEstado }}">
                                    {{ ucfirst(mb_strtolower($publicacion->estado)) }}
                                </span>
                            </td>
                            <td>
                                <span style="white-space:nowrap;">
                                    <i class="bi bi-images" aria-hidden="true"></i>
                                    {{ $publicacion->registros_archivo_publicacion_count }} archivo(s)
                                </span>
                            </td>
                            <td style="white-space:nowrap;">
                                {{ $publicacion->created_at?->format('d/m/Y') ?? '—' }}
                                <div style="color:var(--atlas-muted);margin-top:4px;">
                                    {{ $publicacion->created_at?->format('H:i') }}
                                </div>
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;flex-wrap:wrap;gap:7px;min-width:180px;">
                                    <a class="atlas-btn atlas-btn-outline atlas-btn-sm"
                                       href="{{ route('direccion.publicaciones.edit', $publicacion) }}">
                                        <i class="bi bi-pencil-square" aria-hidden="true"></i> Editar
                                    </a>

                                    @if($publicacion->estado !== 'PUBLICADA')
                                        <form method="POST" action="{{ route('direccion.publicaciones.publicar', $publicacion) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="atlas-btn atlas-btn-success atlas-btn-sm" type="submit">
                                                <i class="bi bi-send" aria-hidden="true"></i> Publicar
                                            </button>
                                        </form>
                                    @endif

                                    @if($publicacion->estado === 'PUBLICADA')
                                        <form method="POST" action="{{ route('direccion.publicaciones.archivar', $publicacion) }}"
                                              data-atlas-confirmar="¿Retirar esta noticia del sitio web?">
                                            @csrf
                                            @method('PATCH')
                                            <button class="atlas-btn atlas-btn-outline atlas-btn-sm" type="submit">
                                                <i class="bi bi-archive" aria-hidden="true"></i> Archivar
                                            </button>
                                        </form>
                                    @endif

                                    @if($publicacion->estado === 'ARCHIVADA')
                                        <form method="POST" action="{{ route('direccion.publicaciones.borrador', $publicacion) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="atlas-btn atlas-btn-outline atlas-btn-sm" type="submit">
                                                <i class="bi bi-file-earmark" aria-hidden="true"></i> Borrador
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding:45px 20px;text-align:center;">
                                <i class="bi bi-newspaper" aria-hidden="true" style="font-size:32px;color:var(--atlas-muted);"></i>
                                <div style="margin-top:12px;font-weight:800;color:var(--atlas-navy);">No hay publicaciones para mostrar.</div>
                                <p style="color:var(--atlas-muted);">Crea una noticia o modifica los filtros.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($publicaciones->hasPages())
            <div class="atlas-card-footer">{{ $publicaciones->links() }}</div>
        @endif
    </section>
</div>
    {{-- Confirmación institucional: nunca utilizar alert(), confirm() ni prompt(). --}}
    <div id="atlas-confirmacion" role="dialog" aria-modal="true" aria-labelledby="atlas-confirmacion-titulo" aria-describedby="atlas-confirmacion-mensaje" hidden style="position:fixed;inset:0;z-index:9999;background:rgba(12,29,52,.68);display:none;align-items:center;justify-content:center;padding:20px;">
        <div style="width:min(100%,470px);background:#fff;border-radius:16px;box-shadow:0 24px 80px rgba(0,0,0,.24);overflow:hidden;">
            <div style="padding:24px 26px 16px;display:flex;gap:14px;align-items:center;border-bottom:1px solid #e7edf4;">
                <span style="width:44px;height:44px;display:grid;place-items:center;border-radius:12px;background:#fff4e5;color:#a66b14;font-size:23px;flex:none;"><i class="bi bi-exclamation-triangle" aria-hidden="true"></i></span>
                <div><h3 id="atlas-confirmacion-titulo" style="font-size:18px;color:var(--atlas-navy,#17385a);font-weight:800;margin:0;">Confirmar operación</h3><p style="font-size:12px;color:var(--atlas-muted,#65748a);margin:5px 0 0;">Noticias y publicaciones · ATLAS</p></div>
            </div>
            <div style="padding:22px 26px;"><p id="atlas-confirmacion-mensaje" style="font-size:14px;line-height:1.7;color:var(--atlas-text,#30445e);margin:0;"></p></div>
            <div style="display:flex;justify-content:flex-end;gap:10px;flex-wrap:wrap;padding:0 26px 24px;">
                <button type="button" id="atlas-confirmacion-cancelar" class="atlas-btn atlas-btn-outline">Cancelar</button>
                <button type="button" id="atlas-confirmacion-aceptar" class="atlas-btn atlas-btn-danger">Confirmar</button>
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
            if (botonAnterior) botonAnterior.focus();
        };
        document.addEventListener('submit', (evento) => {
            const form = evento.target;
            if (!(form instanceof HTMLFormElement) || !form.hasAttribute('data-atlas-confirmar')) return;
            evento.preventDefault();
            formularioPendiente = form;
            botonAnterior = document.activeElement;
            mensaje.textContent = form.getAttribute('data-atlas-confirmar') || '¿Deseas continuar con esta operación?';
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
        dialogo.addEventListener('click', (evento) => { if (evento.target === dialogo) cerrar(); });
        document.addEventListener('keydown', (evento) => {
            if (dialogo.hidden) return;
            if (evento.key === 'Escape') { evento.preventDefault(); cerrar(); }
            if (evento.key === 'Tab') {
                const destino = evento.shiftKey ? cancelar : aceptar;
                if ((evento.shiftKey && document.activeElement === cancelar) || (!evento.shiftKey && document.activeElement === aceptar)) {
                    evento.preventDefault(); destino === cancelar ? aceptar.focus() : cancelar.focus();
                }
            }
        });
    })();
    </script>

@endsection
