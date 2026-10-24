
@extends('layouts.direccion')

@section('title', 'Movimientos de inventario')
@section('module-title', 'Inventario · Movimientos')

@push('styles')
<style>
.atlas-movimientos {
    max-width: 1500px;
    margin: 0 auto;
    padding: 26px 22px 45px;
    color: #193656;
}
.atlas-movimientos * { box-sizing: border-box; }
.atlas-movimientos h1 {
    font-size: 27px;
    margin: 0 0 7px;
    color: #142f52;
}
.atlas-movimientos .muted {
    color: #6c7e93;
    font-size: 13px;
    line-height: 1.6;
}
.atlas-movimientos .topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 22px;
}
.atlas-movimientos .panel {
    background: #fff;
    border: 1px solid #dbe5ef;
    border-radius: 15px;
    padding: 21px;
    margin-bottom: 20px;
    box-shadow: 0 4px 18px #142c4d08;
}
.atlas-movimientos .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    border: 0;
    border-radius: 9px;
    padding: 10px 14px;
    background: #195b9d;
    color: white;
    text-decoration: none;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
}
.atlas-movimientos .btn.secondary {
    background: #e8f1fb;
    color: #174e85;
}
.atlas-movimientos .btn:focus-visible {
    outline: 3px solid #84b8e7;
    outline-offset: 2px;
}
.atlas-movimientos .actions {
    display: flex;
    gap: 9px;
    flex-wrap: wrap;
}
.atlas-movimientos .stats {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}
.atlas-movimientos .stat {
    background: #fff;
    border: 1px solid #dbe5ef;
    border-radius: 13px;
    padding: 19px;
}
.atlas-movimientos .stat span {
    display: block;
    font-size: 12px;
    color: #687d92;
    margin-bottom: 8px;
}
.atlas-movimientos .stat strong {
    font-size: 25px;
    color: #14385f;
}
.atlas-movimientos .filters {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 13px;
}
.atlas-movimientos label {
    display: block;
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 6px;
}
.atlas-movimientos input,
.atlas-movimientos select {
    width: 100%;
    border: 1px solid #ccd8e5;
    border-radius: 8px;
    padding: 10px;
    background: white;
    color: #193656;
    font: inherit;
    font-size: 13px;
}
.atlas-movimientos .table-scroll {
    overflow-x: auto;
}
.atlas-movimientos table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
}
.atlas-movimientos th,
.atlas-movimientos td {
    padding: 12px 10px;
    border-bottom: 1px solid #e8edf4;
    text-align: left;
    vertical-align: middle;
}
.atlas-movimientos th {
    background: #f0f5fb;
    color: #234667;
    white-space: nowrap;
}
.atlas-movimientos .tag {
    display: inline-block;
    padding: 5px 9px;
    border-radius: 7px;
    font-size: 11px;
    font-weight: 800;
}
.atlas-movimientos .entrada {
    background: #e5f5ec;
    color: #17603c;
}
.atlas-movimientos .salida {
    background: #fff0f1;
    color: #a22b38;
}
.atlas-movimientos .otro {
    background: #edf2f7;
    color: #41556b;
}
.atlas-movimientos .details-button {
    border: 1px solid #cbdbea;
    background: #f3f8fd;
    color: #174e85;
    padding: 7px 11px;
    border-radius: 8px;
    font-weight: 700;
    cursor: pointer;
}
.atlas-movimientos dialog {
    border: 0;
    border-radius: 16px;
    padding: 0;
    width: min(650px, 95vw);
    max-width: 95vw;
    color: #193656;
    box-shadow: 0 15px 80px #0a213c55;
}
.atlas-movimientos dialog::backdrop {
    background: #0a1c30aa;
}
.atlas-movimientos .dialog-inner {
    padding: 25px;
    max-height: 85vh;
    overflow: auto;
}
.atlas-movimientos .dialog-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-bottom: 18px;
}
.atlas-movimientos .dialog-head h2 {
    margin: 0;
    font-size: 20px;
}
.atlas-movimientos .detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}
.atlas-movimientos .detail-grid div {
    border-bottom: 1px solid #e8edf4;
    padding-bottom: 10px;
    min-width: 0;
    overflow-wrap: anywhere;
}
.atlas-movimientos .detail-grid small {
    display: block;
    color: #6c7e93;
    margin-bottom: 5px;
}
.atlas-movimientos .detail-grid strong {
    font-size: 13px;
}
.atlas-movimientos .full {
    grid-column: 1 / -1;
}
.atlas-movimientos .pagination {
    margin-top: 18px;
}
@media (max-width: 850px) {
    .atlas-movimientos .filters {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}
@media (max-width: 600px) {
    .atlas-movimientos { padding: 14px; }
    .atlas-movimientos .stats {
        grid-template-columns: 1fr;
    }
    .atlas-movimientos .filters {
        grid-template-columns: 1fr;
    }
    .atlas-movimientos .detail-grid {
        grid-template-columns: 1fr;
    }
    .atlas-movimientos .panel { padding: 14px; }
}
</style>
@endpush

@section('content')
<div class="atlas-movimientos">

    <div class="topbar">
        <div>
            <h1>
                <i class="bi bi-clock-history"></i>
                Movimientos de inventario
            </h1>
            <p class="muted">
                Historial y trazabilidad de entradas y salidas
                de uniformes. Información de solo lectura.
            </p>
        </div>

        <div class="actions">
            <a
                class="btn secondary"
                href="{{ route('direccion.inventario.uniformes.index') }}"
            >
                <i class="bi bi-arrow-left"></i>
                Volver a uniformes
            </a>

            <a
                class="btn"
                href="{{ route('direccion.inventario.uniformes.movimientos.pdf', request()->query()) }}"
                target="_blank"
                rel="noopener"
            >
                <i class="bi bi-file-earmark-pdf"></i>
                Reporte PDF
            </a>
        </div>
    </div>

    <div class="stats">
        <div class="stat">
            <span>Movimientos encontrados</span>
            <strong>{{ number_format($resumen->total ?? 0) }}</strong>
        </div>

        <div class="stat">
            <span>Unidades ingresadas</span>
            <strong>{{ number_format($resumen->entradas ?? 0) }}</strong>
        </div>

        <div class="stat">
            <span>Unidades retiradas</span>
            <strong>{{ number_format($resumen->salidas ?? 0) }}</strong>
        </div>
    </div>

    <section class="panel">
        <h2 style="font-size:18px;margin:0 0 15px">
            <i class="bi bi-funnel"></i>
            Filtros de búsqueda
        </h2>

        <form
            method="GET"
            action="{{ route('direccion.inventario.uniformes.movimientos.index') }}"
        >
            <div class="filters">
                <div>
                    <label for="desde">Desde</label>
                    <input
                        id="desde"
                        type="date"
                        name="desde"
                        value="{{ $filtros['desde'] ?? '' }}"
                    >
                </div>

                <div>
                    <label for="hasta">Hasta</label>
                    <input
                        id="hasta"
                        type="date"
                        name="hasta"
                        value="{{ $filtros['hasta'] ?? '' }}"
                    >
                </div>

                <div>
                    <label for="tipo">Tipo de movimiento</label>
                    <select id="tipo" name="tipo">
                        <option value="">Todos</option>
                        <option
                            value="ENTRADA"
                            @selected(($filtros['tipo'] ?? '') === 'ENTRADA')
                        >
                            Entradas
                        </option>
                        <option
                            value="SALIDA"
                            @selected(($filtros['tipo'] ?? '') === 'SALIDA')
                        >
                            Salidas
                        </option>
                    </select>
                </div>

                <div>
                    <label for="uniforme_id">Uniforme</label>
                    <select id="uniforme_id" name="uniforme_id">
                        <option value="">Todos los uniformes</option>
                        @foreach($uniformes as $uniforme)
                            <option
                                value="{{ $uniforme->id }}"
                                @selected(
                                    (string)($filtros['uniforme_id'] ?? '')
                                    === (string)$uniforme->id
                                )
                            >
                                {{ $uniforme->nombre }}
                                · {{ $uniforme->color }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="talla">Talla</label>
                    <select id="talla" name="talla">
                        <option value="">Todas las tallas</option>
                        @foreach($tallas as $talla)
                            <option
                                value="{{ $talla }}"
                                @selected(($filtros['talla'] ?? '') === $talla)
                            >
                                {{ $talla }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="usuario_id">Responsable</label>
                    <select id="usuario_id" name="usuario_id">
                        <option value="">Todos los usuarios</option>
                        @foreach($usuarios as $usuario)
                            <option
                                value="{{ $usuario->id }}"
                                @selected(
                                    (string)($filtros['usuario_id'] ?? '')
                                    === (string)$usuario->id
                                )
                            >
                                {{ trim(
                                    ($usuario->persona?->nombres ?? '') . ' ' .
                                    ($usuario->persona?->apellidos ?? '')
                                ) ?: $usuario->nombre_usuario }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="grid-column:span 2">
                    <label for="buscar">Buscar movimiento</label>
                    <input
                        id="buscar"
                        type="search"
                        name="buscar"
                        maxlength="150"
                        placeholder="Prenda, SKU, motivo o referencia"
                        value="{{ $filtros['buscar'] ?? '' }}"
                    >
                </div>
            </div>

            <div class="actions" style="margin-top:18px">
                <button type="submit" class="btn">
                    <i class="bi bi-search"></i>
                    Buscar
                </button>

                <a
                    class="btn secondary"
                    href="{{ route('direccion.inventario.uniformes.movimientos.index') }}"
                >
                    Limpiar filtros
                </a>
            </div>
        </form>
    </section>

    <section class="panel">
        <h2 style="font-size:18px;margin:0 0 6px">
            Historial de movimientos
        </h2>

        <p class="muted">
            Los registros aparecen del más reciente al más antiguo.
            Las reservas de pedidos no se consideran salidas físicas.
        </p>

        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>Fecha y hora</th>
                        <th>Movimiento</th>
                        <th>Uniforme</th>
                        <th>Talla</th>
                        <th>Cantidad</th>
                        <th>Motivo</th>
                        <th>Responsable</th>
                        <th>Referencia</th>
                        <th>Detalle</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($movimientos as $movimiento)
                        @php
                            $producto = $movimiento->producto_uniforme;
                            $uniforme = $producto?->uniforme;
                            $usuario = $movimiento->registrado_por;

                            $nombreUsuario = trim(
                                ($usuario?->persona?->nombres ?? '') . ' ' .
                                ($usuario?->persona?->apellidos ?? '')
                            );

                            $nombreUsuario = $nombreUsuario
                                ?: ($usuario?->nombre_usuario ?? 'No disponible');

                            $claseTipo = match($movimiento->tipo) {
                                'ENTRADA' => 'entrada',
                                'SALIDA' => 'salida',
                                default => 'otro',
                            };
                        @endphp

                        <tr>
                            <td style="white-space:nowrap">
                                {{ $movimiento->fecha_movimiento?->format('d/m/Y H:i') ?? '—' }}
                            </td>

                            <td>
                                <span class="tag {{ $claseTipo }}">
                                    {{ $movimiento->tipo }}
                                </span>
                            </td>

                            <td>
                                <strong>
                                    {{ $uniforme?->nombre ?? $producto?->nombre ?? 'Producto no disponible' }}
                                </strong>
                                <div class="muted">
                                    {{ $uniforme?->color ?? $producto?->color ?? '—' }}
                                </div>
                            </td>

                            <td>{{ $producto?->talla ?? '—' }}</td>

                            <td>
                                <strong>
                                    {{ $movimiento->tipo === 'SALIDA' ? '−' : '+' }}{{ number_format($movimiento->cantidad) }}
                                </strong>
                            </td>

                            <td>{{ $movimiento->motivo ?: '—' }}</td>
                            <td>{{ $nombreUsuario }}</td>
                            <td>{{ $movimiento->referencia ?: '—' }}</td>

                            <td>
                                <button
                                    type="button"
                                    class="details-button"
                                    onclick="document.getElementById('movimiento-{{ $movimiento->id }}').showModal()"
                                >
                                    <i class="bi bi-eye"></i>
                                    Ver
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align:center;padding:35px">
                                No se encontraron movimientos con los filtros seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {{ $movimientos->links() }}
        </div>
    </section>

    @foreach($movimientos as $movimiento)
        @php
            $producto = $movimiento->producto_uniforme;
            $uniforme = $producto?->uniforme;
            $usuario = $movimiento->registrado_por;

            $nombreUsuario = trim(
                ($usuario?->persona?->nombres ?? '') . ' ' .
                ($usuario?->persona?->apellidos ?? '')
            );

            $nombreUsuario = $nombreUsuario
                ?: ($usuario?->nombre_usuario ?? 'No disponible');
        @endphp

        <dialog id="movimiento-{{ $movimiento->id }}">
            <div class="dialog-inner">

                <div class="dialog-head">
                    <h2>Movimiento #{{ $movimiento->id }}</h2>

                    <button
                        type="button"
                        class="btn secondary"
                        onclick="this.closest('dialog').close()"
                    >
                        Cerrar ×
                    </button>
                </div>

                <div class="detail-grid">
                    <div>
                        <small>Fecha y hora</small>
                        <strong>
                            {{ $movimiento->fecha_movimiento?->format('d/m/Y H:i:s') ?? '—' }}
                        </strong>
                    </div>

                    <div>
                        <small>Tipo de movimiento</small>
                        <strong>{{ $movimiento->tipo }}</strong>
                    </div>

                    <div>
                        <small>Uniforme</small>
                        <strong>
                            {{ $uniforme?->nombre ?? $producto?->nombre ?? 'No disponible' }}
                        </strong>
                    </div>

                    <div>
                        <small>Color</small>
                        <strong>
                            {{ $uniforme?->color ?? $producto?->color ?? '—' }}
                        </strong>
                    </div>

                    <div>
                        <small>Talla</small>
                        <strong>{{ $producto?->talla ?? '—' }}</strong>
                    </div>

                    <div>
                        <small>SKU</small>
                        <strong>{{ $producto?->sku ?? '—' }}</strong>
                    </div>

                    <div>
                        <small>Cantidad</small>
                        <strong>{{ number_format($movimiento->cantidad) }} unidades</strong>
                    </div>

                    <div>
                        <small>Responsable</small>
                        <strong>{{ $nombreUsuario }}</strong>
                    </div>

                    <div>
                        <small>Referencia</small>
                        <strong>{{ $movimiento->referencia ?: 'Sin referencia' }}</strong>
                    </div>

                    <div>
                        <small>ID del producto</small>
                        <strong>{{ $movimiento->producto_uniforme_id }}</strong>
                    </div>

                    <div class="full">
                        <small>Motivo registrado</small>
                        <strong>{{ $movimiento->motivo ?: 'Sin motivo registrado' }}</strong>
                    </div>
                </div>

                <p class="muted" style="margin-top:20px">
                    Registro histórico de solo lectura. La información
                    corresponde al movimiento almacenado en ATLAS.
                </p>
            </div>
        </dialog>
    @endforeach

</div>
@endsection
