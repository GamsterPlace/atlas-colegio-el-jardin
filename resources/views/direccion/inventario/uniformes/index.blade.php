@extends('layouts.direccion')
@section('title', 'Control de uniformes')
@section('module-title', 'Tienda escolar · Uniformes')
@push('styles')
<style>
.atlas-uniformes{max-width:1500px;margin:auto;padding:24px;color:#193656}.atlas-uniformes *{box-sizing:border-box}.atlas-uniformes h1{font-size:28px;margin:0 0 5px}.atlas-uniformes h2{font-size:20px;margin:0 0 15px}.atlas-uniformes .muted{color:#6c7e93;font-size:13px}.atlas-uniformes .panel{background:#fff;border:1px solid #dbe5ef;border-radius:16px;padding:22px;margin-top:20px;box-shadow:0 4px 18px #142c4d08}.atlas-uniformes .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:13px}.atlas-uniformes label{display:block;font-size:12px;font-weight:700;margin:8px 0 5px}.atlas-uniformes input,.atlas-uniformes select,.atlas-uniformes textarea{width:100%;border:1px solid #ccd8e5;border-radius:8px;padding:10px;background:white;color:#193656}.atlas-uniformes input[type=file]{padding:7px}.atlas-uniformes .btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;border:0;border-radius:9px;padding:10px 14px;background:#195b9d;color:white;text-decoration:none;font-size:13px;font-weight:700;cursor:pointer}.atlas-uniformes .btn.secondary{background:#e8f1fb;color:#174e85}.atlas-uniformes .btn.danger{background:#aa2d36;color:white}.atlas-uniformes .btn:disabled{opacity:.5;cursor:not-allowed}.atlas-uniformes .actions{display:flex;gap:8px;flex-wrap:wrap;margin-top:14px}.atlas-uniformes .action-card{border:1px solid #d8e5f2;border-radius:13px;padding:18px;background:#f7fbff;text-align:left;cursor:pointer;color:#17446e;font-weight:700;min-height:95px}.atlas-uniformes .action-card i{font-size:22px;display:block;margin-bottom:9px}.atlas-uniformes .pedido{border:1px solid #dce5ef;border-radius:12px;padding:17px;margin-bottom:12px;display:flex;justify-content:space-between;align-items:center;gap:15px;flex-wrap:wrap}.atlas-uniformes .estado{font-size:11px;padding:5px 8px;background:#eaf1f9;border-radius:6px;font-weight:700}.atlas-uniformes .table-scroll{overflow:auto}.atlas-uniformes table{width:100%;border-collapse:collapse;font-size:13px}.atlas-uniformes th,.atlas-uniformes td{padding:9px;border-bottom:1px solid #e8edf4;text-align:left}.atlas-uniformes th{background:#f0f5fb}.atlas-uniformes .inventory{display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:17px}.atlas-uniformes .product-card{border:1px solid #dbe5ef;border-radius:14px;overflow:hidden;background:white}.atlas-uniformes .product-card.low{border:2px solid #c92d3d}.atlas-uniformes .product-photo{width:100%;aspect-ratio:1/1;object-fit:cover;background:#f2f5f9}.atlas-uniformes .placeholder{aspect-ratio:1/1;display:grid;place-items:center;background:#f2f5f9;color:#8da0b6;font-size:40px}.atlas-uniformes .product-body{padding:13px}.atlas-uniformes .low-row{background:#fff0f1;color:#ac2030;font-weight:800}.atlas-uniformes .alert{padding:12px 15px;background:#e8f7ec;border-radius:9px;margin:12px 0;color:#236342}.atlas-uniformes .alert.error{background:#fff0f0;color:#a12b35}.atlas-uniformes dialog{border:0;border-radius:16px;padding:0;max-width:min(900px,95vw);width:100%;box-shadow:0 15px 80px #0a213c55;color:#193656}.atlas-uniformes dialog::backdrop{background:#0a1c30aa}.atlas-uniformes .dialog-inner{padding:24px;max-height:85vh;overflow:auto}.atlas-uniformes .dialog-head{display:flex;justify-content:space-between;align-items:center;gap:12px}.atlas-uniformes .x{border:0;background:#edf3f9;padding:8px 12px;border-radius:8px;cursor:pointer}.atlas-uniformes .stock-input{width:90px!important}.atlas-uniformes .notice{background:#f1f6fc;padding:12px;border-radius:8px;font-size:13px}.atlas-uniformes .photo-small{width:44px;height:44px;object-fit:cover;border-radius:7px}@media(max-width:650px){.atlas-uniformes{padding:12px}.atlas-uniformes .panel{padding:14px}}

/* Tarjetas desplazables para operaciones por presentación */
.atlas-uniformes .uniform-carousel{display:flex;gap:16px;overflow-x:auto;scroll-snap-type:x mandatory;padding:12px 3px 20px;scrollbar-width:thin;align-items:stretch}
.atlas-uniformes .uniform-carousel .product-card{flex:0 0 clamp(245px,36%,310px);scroll-snap-align:start;min-width:0}
.atlas-uniformes .uniform-carousel .product-photo,.atlas-uniformes .uniform-carousel .placeholder{aspect-ratio:5/3}
.atlas-uniformes .size-entry{display:grid;grid-template-columns:minmax(45px,1fr) minmax(55px,1fr) 92px;align-items:center;gap:7px;padding:9px 0;border-bottom:1px solid #e8edf4;font-size:12px}
.atlas-uniformes .size-entry:last-child{border-bottom:0}.atlas-uniformes .size-entry input{padding:8px;min-width:0}.atlas-uniformes .size-head{font-weight:700;color:#536e88;background:#f0f5fb;border-radius:6px;padding:7px 4px}.atlas-uniformes .carousel-tools{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-top:10px}.atlas-uniformes .carousel-tools .btn{padding:8px 12px}.atlas-uniformes .confirm-dialog{max-width:min(460px,94vw);z-index:1000}.atlas-uniformes .confirm-icon{width:48px;height:48px;display:grid;place-items:center;border-radius:14px;background:#fff0f1;color:#a52a36;font-size:24px;margin-bottom:12px}.atlas-uniformes .confirm-actions{display:flex;justify-content:flex-end;gap:10px;margin-top:22px}.atlas-uniformes .confirm-dialog p{line-height:1.6}
@media(max-width:650px){.atlas-uniformes .uniform-carousel .product-card{flex-basis:85%}}
</style>
@endpush
@section('content')
<div class="atlas-uniformes">
    <h1>Control de uniformes</h1><p class="muted">Pedidos por orden de llegada, administración de productos y existencias disponibles.</p>
    @if(session('success'))<div class="alert">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert error"><strong>No se completó la operación:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

    <section class="panel"><h2><i class="bi bi-bag-check"></i> Pedidos pendientes y en preparación ({{ $pedidos->count() }})</h2>
        <p class="muted">Los pedidos se presentan del más antiguo al más reciente. Los preparados permanecen aquí hasta su entrega.</p>
        @forelse($pedidos as $pedido)
            <div class="pedido">
                <div>
                    <strong>{{ $pedido->numero }}</strong> <span class="estado">{{ str_replace('_', ' ', $pedido->estado) }}</span>
                    <div>{{ $pedido->estudiante?->nombre_completo }} · {{ $pedido->responsable?->persona?->nombres }} {{ $pedido->responsable?->persona?->apellidos }}</div>
                    <div class="muted">{{ $pedido->created_at?->format('d/m/Y H:i') }} · Vence {{ $pedido->vence_at?->format('d/m/Y H:i') }} · Q {{ number_format($pedido->total, 2) }} · Pago: {{ $pedido->estado_pago }}</div>
                </div>
                <div class="actions">
                    @if($pedido->estado === 'PENDIENTE')
                        <form method="POST" action="{{ route('direccion.inventario.uniformes.procesar', $pedido) }}">@csrf<button class="btn"><i class="bi bi-box-seam"></i> Procesar pedido</button></form>
                    @elseif($pedido->estado === 'EN_PROCESO')
                        <button class="btn" type="button" onclick="abrirDialogo('pedido-{{ $pedido->id }}')">Ver preparación</button>
                    @elseif($pedido->estado === 'PREPARADO')
                        <button class="btn" type="button" onclick="abrirDialogo('pedido-{{ $pedido->id }}')">Entregar</button>
                    @endif
                    @if($pedido->estado === 'PREPARADO')
                        <a class="btn secondary" target="_blank" rel="noopener" href="{{ route('direccion.inventario.uniformes.etiqueta', $pedido) }}"><i class="bi bi-printer"></i> Etiqueta de identificación</a>
                    @else
                        <a class="btn secondary" target="_blank" rel="noopener" href="{{ route('direccion.inventario.uniformes.comprobante', $pedido) }}">Comprobante</a>
                    @endif
                </div>
            </div>
            <dialog id="pedido-{{ $pedido->id }}"><div class="dialog-inner">
                <div class="dialog-head"><h2>Pedido {{ $pedido->numero }}</h2><button class="x" type="button" onclick="this.closest('dialog').close()">Cerrar ×</button></div>
                <p class="muted">Estudiante: {{ $pedido->estudiante?->nombre_completo }} · Vence: {{ $pedido->vence_at?->format('d/m/Y H:i') }}</p>
                <p class="notice">Verifica cuidadosamente cada prenda, talla y cantidad antes de confirmar la preparación.</p>
                <div class="table-scroll"><table><thead><tr><th>Prenda</th><th>Talla</th><th>Color</th><th>Cantidad</th></tr></thead><tbody>
                    @foreach($pedido->detalles as $d)<tr><td>{{ $d->producto?->nombre }}</td><td>{{ $d->producto?->talla }}</td><td>{{ $d->producto?->color }}</td><td>{{ $d->cantidad }}</td></tr>@endforeach
                </tbody></table></div>
                @if($pedido->observaciones)<p class="notice">Observaciones: {{ $pedido->observaciones }}</p>@endif
                @if($pedido->estado === 'EN_PROCESO')
                    <form method="POST" action="{{ route('direccion.inventario.uniformes.preparado', $pedido) }}" class="actions form-preparado">@csrf<button class="btn" type="submit">Preparado · Abrir etiqueta PDF</button></form>
                @elseif($pedido->estado === 'PREPARADO')
                    <div class="actions"><a class="btn secondary" target="_blank" href="{{ route('direccion.inventario.uniformes.etiqueta', $pedido) }}">Imprimir identificación (media carta horizontal)</a></div>
                    @if($pedido->estado_pago === 'PENDIENTE')
                        <h3>Registrar pago antes de entregar</h3><form method="POST" action="{{ route('direccion.inventario.uniformes.pago', $pedido) }}">@csrf
                            <div class="grid"><div><label>Método</label><select name="metodo" class="metodo-pago" required><option value="EFECTIVO">Efectivo en colegio</option><option value="DEPOSITO">Depósito con boleta original</option></select></div>
                            <div class="datos-deposito" hidden><label>Referencia boleta original</label><input name="referencia_deposito" maxlength="120"><label>Fecha del depósito</label><input name="fecha_deposito" type="date"></div></div>
                            <div class="actions"><button class="btn secondary">Confirmar pago de Q {{ number_format($pedido->total, 2) }}</button></div>
                        </form>
                    @else
                        <p class="alert">Pago registrado: {{ $pedido->pago?->metodo }}.</p>
                        <form method="POST" action="{{ route('direccion.inventario.uniformes.entregar', $pedido) }}">@csrf<label>Nombre de quien recibe físicamente el pedido</label><input name="recibido_por" required maxlength="180"><div class="actions"><button class="btn" data-confirm="¿Confirmas la entrega física y el descuento definitivo del inventario?">Confirmar entrega</button></div></form>
                    @endif
                @endif
                <hr><details><summary>Anulación presencial con motivo</summary><form method="POST" action="{{ route('direccion.inventario.uniformes.anular', $pedido) }}">@csrf<label>Motivo de anulación</label><textarea name="motivo" required maxlength="255"></textarea><div class="actions"><button class="btn danger" data-confirm="¿Anular el pedido y liberar su reserva?">Anular pedido</button></div></form></details>
            </div></dialog>
        @empty <p class="muted">No hay pedidos activos por preparar o entregar.</p> @endforelse
    </section>

    <section class="panel"><h2>Acciones administrativas</h2><div class="grid">
        <button type="button" class="action-card" onclick="abrirDialogo('nuevo')"><i class="bi bi-plus-circle"></i> Registrar nuevo uniforme</button>
        <button type="button" class="action-card" onclick="abrirDialogo('editar')"><i class="bi bi-pencil-square"></i> Editar información del producto</button>
        <button type="button" class="action-card" onclick="abrirDialogo('ingreso')"><i class="bi bi-box-arrow-in-down"></i> Ingreso de uniformes</button>
        <button type="button" class="action-card" onclick="abrirDialogo('nuevo-pedido')"><i class="bi bi-cart-plus"></i> Realizar pedido</button>
    </div></section>

    <section class="panel"><h2>Inventario disponible</h2><p class="muted">Las cantidades descuentan las reservas vigentes. Una talla con 2 prendas o menos se marca en rojo.</p>
        <div class="inventory">@forelse($uniformes as $uniforme)
            @php $bajo = $uniforme->tallas->contains(fn ($t) => ($disponibles[$t->id] ?? 0) <= 2); @endphp
            <article class="product-card {{ $bajo ? 'low' : '' }}">
                @if($uniforme->foto_path)<img class="product-photo" src="{{ asset($uniforme->foto_path) }}" alt="{{ $uniforme->nombre }}">@else<div class="placeholder"><i class="bi bi-image"></i></div>@endif
                <div class="product-body"><strong>{{ $uniforme->nombre }}</strong><p class="muted">{{ $uniforme->color }} · {{ $uniforme->categoria_uniforme?->nombre }} @unless($uniforme->activo)· Descontinuado @endunless</p>
                    <table><thead><tr><th>Talla</th><th>Stock disponible</th></tr></thead><tbody>@foreach($uniforme->tallas as $t)<tr class="{{ ($disponibles[$t->id] ?? 0) <= 2 ? 'low-row' : '' }}"><td>{{ $t->talla }} @unless($t->activo)(Inactivo)@endunless</td><td>{{ $disponibles[$t->id] ?? 0 }}</td></tr>@endforeach</tbody></table>
                    @if($bajo)<p style="color:#b32635;font-size:12px;font-weight:800;margin-top:9px"><i class="bi bi-exclamation-triangle"></i> Existencia baja (2 o menos)</p>@endif
                </div>
            </article>
        @empty <p class="muted">Aún no hay uniformes registrados.</p> @endforelse</div>
    </section>

    <dialog id="nuevo"><div class="dialog-inner"><div class="dialog-head"><h2>Registrar nuevo uniforme</h2><button class="x" type="button" onclick="this.closest('dialog').close()">Cerrar ×</button></div>
        <p class="notice">Registra una sola vez los datos del uniforme y agrega todas las tallas necesarias. ATLAS genera un SKU único por talla.</p>
        <form method="POST" action="{{ route('direccion.inventario.uniformes.crear') }}" enctype="multipart/form-data">@csrf
            <div class="grid"><div><label>Categoría</label><select name="categoria_uniforme_id" required>@foreach($categorias as $c)<option value="{{ $c->id }}">{{ $c->nombre }}</option>@endforeach</select></div>
            <div><label>Nombre del uniforme</label><input name="nombre" maxlength="180" required></div>
            <div><label>Color</label><input name="color" maxlength="80"></div>
            <div><label>Fotografía</label><input name="foto" type="file" accept="image/jpeg,image/png,image/webp"></div></div>
            <label>Descripción</label><textarea name="descripcion" maxlength="4000"></textarea>
            <h3>Tallas, precios y stock inicial</h3><div id="tallas-nuevo"></div>
            <div class="actions"><button class="btn secondary" type="button" onclick="agregarTalla('nuevo',true)"><i class="bi bi-plus-circle"></i> Agregar talla</button><button class="btn">Registrar uniforme</button></div>
        </form></div></dialog>

    <dialog id="editar"><div class="dialog-inner"><div class="dialog-head"><h2>Editar información del uniforme</h2><button class="x" type="button" onclick="this.closest('dialog').close()">Cerrar ×</button></div>
        <p class="notice">Selecciona un producto completo, no una talla. El stock se gestiona únicamente desde Ingreso de uniformes. Los SKU existentes no cambian.</p>
        <label>Seleccionar uniforme</label><select id="selector-editar" onchange="cargarEdicion()"><option value="">Selecciona un uniforme</option>@foreach($uniformes as $u)<option value="{{ $u->id }}">{{ $u->nombre }} · {{ $u->color }}</option>@endforeach</select>
        <form id="form-editar" method="POST" enctype="multipart/form-data" style="display:none">@csrf @method('PUT')
            <div class="grid"><div><label>Categoría</label><select name="categoria_uniforme_id" id="edit-categoria" required>@foreach($categorias as $c)<option value="{{ $c->id }}">{{ $c->nombre }}</option>@endforeach</select></div>
            <div><label>Nombre</label><input name="nombre" id="edit-nombre" required></div>
            <div><label>Color</label><input name="color" id="edit-color"></div>
            <div><label>Reemplazar foto</label><input name="foto" type="file" accept="image/jpeg,image/png,image/webp"></div></div>
            <label>Descripción</label><textarea name="descripcion" id="edit-descripcion"></textarea>
            <h3>Tallas y precios</h3><div id="tallas-editar"></div>
            <div class="actions"><button class="btn secondary" type="button" onclick="agregarTalla('editar',false)"><i class="bi bi-plus-circle"></i> Agregar otra talla</button><button class="btn">Guardar cambios</button></div>
        </form>
        <form id="form-estado" method="POST" style="display:none;margin-top:15px">@csrf @method('PATCH')<button class="btn danger" id="btn-estado" data-confirm="¿Confirmas cambiar el estado de este uniforme y todas sus tallas?">Descontinuar / Reactivar</button></form>
    </div></dialog>

    <dialog id="ingreso"><div class="dialog-inner"><div class="dialog-head"><h2>Ingreso de uniformes</h2><button class="x" type="button" onclick="this.closest('dialog').close()">Cerrar ×</button></div>
        <p class="notice">Selecciona las cantidades que ingresan por talla. Se sumarán al stock físico, sin reemplazar las existencias.</p>
        <form method="POST" action="{{ route('direccion.inventario.uniformes.ingreso') }}">@csrf
            <div class="carousel-tools"><strong>Uniformes y tallas</strong><div class="actions" style="margin:0"><button type="button" class="btn secondary" onclick="desplazarCarrusel('carrusel-ingreso',-1)" aria-label="Anterior uniforme">‹</button><button type="button" class="btn secondary" onclick="desplazarCarrusel('carrusel-ingreso',1)" aria-label="Siguiente uniforme">›</button></div></div>
            <div class="uniform-carousel" id="carrusel-ingreso">
                @forelse($uniformes as $uniforme)
                <article class="product-card">
                    @if($uniforme->foto_path)<img class="product-photo" src="{{ asset($uniforme->foto_path) }}" alt="{{ $uniforme->nombre }}">@else<div class="placeholder"><i class="bi bi-image"></i></div>@endif
                    <div class="product-body"><strong>{{ $uniforme->nombre }}</strong><p class="muted">{{ $uniforme->color }}</p>
                        <div class="size-entry size-head"><span>Talla</span><span>Físico</span><span>Ingreso</span></div>
                        @foreach($uniforme->tallas as $t)
                        <div class="size-entry"><strong>{{ $t->talla }}</strong><span>{{ $t->existenciaUniforme?->cantidad ?? 0 }}</span><input aria-label="Unidades que ingresan talla {{ $t->talla }} de {{ $uniforme->nombre }}" name="cantidades[{{ $t->id }}]" type="number" min="0" step="1" value="0"></div>
                        @endforeach
                    </div>
                </article>
                @empty <p class="muted">Aún no hay uniformes registrados.</p> @endforelse
            </div>
            <div class="actions"><button class="btn" type="submit">Registrar ingresos</button></div>
        </form>
    </div></dialog>

    <dialog id="nuevo-pedido"><div class="dialog-inner"><div class="dialog-head"><h2>Realizar pedido desde Dirección</h2><button class="x" onclick="this.closest('dialog').close()">Cerrar ×</button></div><p class="notice">El pedido reservará existencias por 72 horas y aparecerá en el panel para su preparación.</p>
        <form method="POST" action="{{ route('direccion.inventario.uniformes.pedido') }}">@csrf
            <div class="grid"><div><label>Estudiante</label><select name="estudiante_id" id="pedido-estudiante" required onchange="filtrarResponsables()"><option value="">Seleccionar estudiante</option>@foreach($estudiantes as $e)<option value="{{ $e->id }}">{{ $e->nombre_completo }} · {{ $e->numero_carnet }}</option>@endforeach</select></div>
            <div><label>Responsable vinculado</label><select name="responsable_id" id="pedido-responsable" required><option value="">Seleccionar responsable</option>@foreach($responsables as $r)<option value="{{ $r->id }}" data-estudiante="{{ $r->estudiante_id }}">{{ $r->nombres }} {{ $r->apellidos }}</option>@endforeach</select></div></div>
            <div class="carousel-tools"><strong>Seleccionar uniformes</strong><div class="actions" style="margin:0"><button type="button" class="btn secondary" onclick="desplazarCarrusel('carrusel-pedido',-1)" aria-label="Anterior uniforme">‹</button><button type="button" class="btn secondary" onclick="desplazarCarrusel('carrusel-pedido',1)" aria-label="Siguiente uniforme">›</button></div></div>
            <div class="uniform-carousel" id="carrusel-pedido">
                @forelse($uniformes->where('activo', true) as $uniforme)
                @php $tallasActivas = $uniforme->tallas->where('activo', true); @endphp
                @if($tallasActivas->isNotEmpty())
                <article class="product-card">
                    @if($uniforme->foto_path)<img class="product-photo" src="{{ asset($uniforme->foto_path) }}" alt="{{ $uniforme->nombre }}">@else<div class="placeholder"><i class="bi bi-image"></i></div>@endif
                    <div class="product-body"><strong>{{ $uniforme->nombre }}</strong><p class="muted">{{ $uniforme->color }}</p>
                        <div class="size-entry size-head"><span>Talla / Disp.</span><span>Precio</span><span>Cantidad</span></div>
                        @foreach($tallasActivas as $t)
                        @php $disp = (int)($disponibles[$t->id] ?? 0); @endphp
                        <div class="size-entry {{ $disp <= 2 ? 'low-row' : '' }}"><span><strong>{{ $t->talla }}</strong><small style="display:block">{{ $disp }} disponibles</small></span><span>Q {{ number_format($t->precio_referencia,2) }}</span><input class="pedido-cantidad" aria-label="Cantidad talla {{ $t->talla }} de {{ $uniforme->nombre }}" name="cantidades[{{ $t->id }}]" type="number" min="0" step="1" max="{{ $disp }}" value="0" data-precio="{{ $t->precio_referencia }}" oninput="actualizarTotal()" {{ $disp === 0 ? 'disabled' : '' }}></div>
                        @endforeach
                    </div>
                </article>
                @endif
                @empty <p class="muted">No hay uniformes activos disponibles.</p> @endforelse
            </div>
            <label>Observaciones para preparación</label><textarea name="observaciones" maxlength="2000"></textarea><h3>Total estimado: <span id="pedido-total">Q 0.00</span></h3><div class="actions"><button class="btn">Registrar pedido y reservar prendas</button></div>
        </form>
    </div></dialog>
    <dialog id="confirmacion-atlas" class="confirm-dialog"><div class="dialog-inner">
        <div class="confirm-icon"><i class="bi bi-exclamation-triangle"></i></div>
        <h2 id="confirmacion-titulo">Confirmar operación</h2>
        <p id="confirmacion-texto" class="muted"></p>
        <div class="confirm-actions"><button type="button" class="btn secondary" onclick="document.getElementById('confirmacion-atlas').close()">Cancelar</button><button type="button" class="btn danger" id="confirmacion-aceptar">Confirmar</button></div>
    </div></dialog>
</div>
@endsection
@push('scripts')
<script>
// El pedido se prepara sin abandonar ATLAS y la etiqueta se abre en otra pestaña.
document.querySelectorAll('.form-preparado').forEach(form => {
    form.addEventListener('submit', async function(event) {
        event.preventDefault();
        if (!form.reportValidity()) return;
        const button = form.querySelector('button[type="submit"]');
        if (button.disabled) return;
        const pdfTab = window.open('about:blank', '_blank');
        if (!pdfTab) {
            mostrarErrorPreparacion('Permite las ventanas emergentes para ATLAS e inténtalo de nuevo.');
            return;
        }
        pdfTab.document.title = 'Preparando etiqueta';
        pdfTab.document.body.textContent = 'Generando etiqueta de identificación…';
        button.disabled = true;
        try {
            const response = await fetch(form.action, {
                method: 'POST', body: new FormData(form), credentials: 'same-origin',
                headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}
            });
            const result = await response.json();
            if (!response.ok || !result.etiqueta_url) {
                throw new Error(result.message || Object.values(result.errors || {}).flat().join(' ') || 'No se pudo preparar el pedido.');
            }
            pdfTab.location.replace(result.etiqueta_url);
            window.location.reload();
        } catch (error) {
            pdfTab.close();
            button.disabled = false;
            mostrarErrorPreparacion(error.message || 'No se pudo completar la operación.');
        }
    });
});
function mostrarErrorPreparacion(message) {
    let aviso = document.getElementById('error-preparacion');
    if (!aviso) {
        aviso = document.createElement('div');
        aviso.id = 'error-preparacion'; aviso.className = 'alert error';
        document.querySelector('.atlas-uniformes').prepend(aviso);
    }
    aviso.textContent = message;
    aviso.scrollIntoView({behavior: 'smooth', block: 'center'});
}
function desplazarCarrusel(id,direccion){const el=document.getElementById(id);if(!el)return;const card=el.querySelector('.product-card');el.scrollBy({left:direccion*((card?.getBoundingClientRect().width||280)+16),behavior:'smooth'});}
let formularioConfirmacion=null;
document.addEventListener('click',function(event){
    const boton=event.target.closest('[data-confirm]');if(!boton)return;
    event.preventDefault();
    const formulario=boton.closest('form');if(!formulario)return;
    if(!formulario.reportValidity())return;
    formularioConfirmacion=formulario;
    document.getElementById('confirmacion-texto').textContent=boton.dataset.confirm;
    document.getElementById('confirmacion-atlas').showModal();
});
document.getElementById('confirmacion-aceptar').addEventListener('click',function(){
    const formulario=formularioConfirmacion;formularioConfirmacion=null;
    document.getElementById('confirmacion-atlas').close();
    if(formulario)formulario.submit();
});
function abrirDialogo(id){document.getElementById(id)?.showModal();}
const productosUniformes = @json($datosEdicion);
const rutaEditar = @json(route('direccion.inventario.uniformes.actualizar', ['uniforme' => '__ID__']));
const rutaEstado = @json(route('direccion.inventario.uniformes.estado', ['uniforme' => '__ID__']));
const contadoresTallas = {nuevo:0,editar:0};
function agregarTalla(tipo, conStock, datos = null) {
    const indice = contadoresTallas[tipo]++;
    const contenedor = document.getElementById('tallas-'+tipo);
    const fila = document.createElement('div');
    fila.className = 'grid';
    fila.style.cssText = 'border-bottom:1px solid #dbe5ef;padding:10px 0;align-items:end';
    const prefix = 'tallas['+indice+']';
    const campo = (etiqueta,nombre,valor,tipoInput='text',atributos={}) => {
        const caja=document.createElement('div'), label=document.createElement('label'), input=document.createElement('input');
        label.textContent=etiqueta;input.name=prefix+'['+nombre+']';input.type=tipoInput;input.value=valor??'';input.required=true;
        Object.entries(atributos).forEach(([k,v])=>input.setAttribute(k,v));
        caja.append(label,input);return caja;
    };
    fila.appendChild(campo('Talla','talla',datos?.talla??'', 'text',{maxlength:'40'}));
    fila.appendChild(campo('Precio (Q)','precio',datos?.precio??'', 'number',{min:'0',step:'0.01'}));
    if (datos?.id) {
        const oculto=document.createElement('input');oculto.type='hidden';oculto.name=prefix+'[id]';oculto.value=datos.id;fila.appendChild(oculto);
        const sku=document.createElement('div');sku.className='muted';sku.textContent='SKU: '+datos.sku;fila.appendChild(sku);
    } else if(conStock) {
        fila.appendChild(campo('Stock inicial','stock_inicial',datos?.stock_inicial??0,'number',{min:'0',step:'1'}));
    } else {
        const aviso=document.createElement('div');aviso.className='muted';aviso.textContent='Nueva talla: stock inicial 0';fila.appendChild(aviso);
    }
    if(!datos?.id) {
        const quitar=document.createElement('button');quitar.type='button';quitar.className='btn secondary';quitar.textContent='Quitar talla';
        quitar.addEventListener('click',()=>fila.remove());fila.appendChild(quitar);
    }
    contenedor.appendChild(fila);
}
function cargarEdicion(){
    const id=document.getElementById('selector-editar').value,p=productosUniformes[id],f=document.getElementById('form-editar'),e=document.getElementById('form-estado');
    f.style.display=p?'block':'none';e.style.display=p?'block':'none';if(!p)return;
    f.action=rutaEditar.replace('__ID__',id);e.action=rutaEstado.replace('__ID__',id);
    for(const [key,val] of Object.entries({categoria:p.categoria,nombre:p.nombre,color:p.color,descripcion:p.descripcion}))document.getElementById('edit-'+key).value=val??'';
    document.getElementById('tallas-editar').replaceChildren();contadoresTallas.editar=0;
    p.tallas.forEach(t=>agregarTalla('editar',false,t));
    document.getElementById('btn-estado').textContent=p.activo?'Descontinuar uniforme':'Reactivar uniforme';
}
agregarTalla('nuevo',true);
function filtrarResponsables(){const id=document.getElementById('pedido-estudiante').value,s=document.getElementById('pedido-responsable');s.value='';for(const o of s.options)if(o.value)o.hidden=o.dataset.estudiante!==id;}
function actualizarTotal(){let total=0;document.querySelectorAll('.pedido-cantidad').forEach(i=>total+=(parseInt(i.value||'0',10)||0)*Number(i.dataset.precio));document.getElementById('pedido-total').textContent='Q '+total.toLocaleString('es-GT',{minimumFractionDigits:2,maximumFractionDigits:2});}
document.querySelectorAll('.metodo-pago').forEach(s=>{const update=()=>{const box=s.closest('form').querySelector('.datos-deposito'),active=s.value==='DEPOSITO';box.hidden=!active;box.querySelectorAll('input').forEach(i=>i.required=active);};s.addEventListener('change',update);update();});
filtrarResponsables();
@if(session('abrir_pedido'))
document.addEventListener('DOMContentLoaded', () => abrirDialogo('pedido-{{ (int) session('abrir_pedido') }}'));
@endif
</script>
@endpush
