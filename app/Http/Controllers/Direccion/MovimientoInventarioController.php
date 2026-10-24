<?php

namespace App\Http\Controllers\Direccion;

use App\Http\Controllers\Controller;
use App\Models\MovimientoInventario;
use App\Models\Uniforme;
use App\Models\Usuario;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MovimientoInventarioController extends Controller
{
    private function filtros(Request $request): array
    {
        return $request->validate([
            'desde' => ['nullable', 'date'],
            'hasta' => ['nullable', 'date', 'after_or_equal:desde'],
            'tipo' => ['nullable', 'in:ENTRADA,SALIDA'],
            'uniforme_id' => ['nullable', 'integer', 'exists:uniformes,id'],
            'talla' => ['nullable', 'string', 'max:40'],
            'usuario_id' => ['nullable', 'integer', 'exists:usuarios,id'],
            'buscar' => ['nullable', 'string', 'max:150'],
        ]);
    }

    private function consulta(array $filtros): Builder
    {
        return MovimientoInventario::query()
            ->with([
                'producto_uniforme.uniforme',
                'registrado_por.persona',
            ])
            ->when(
                $filtros['desde'] ?? null,
                fn (Builder $q, $fecha) =>
                    $q->whereDate('fecha_movimiento', '>=', $fecha)
            )
            ->when(
                $filtros['hasta'] ?? null,
                fn (Builder $q, $fecha) =>
                    $q->whereDate('fecha_movimiento', '<=', $fecha)
            )
            ->when(
                $filtros['tipo'] ?? null,
                fn (Builder $q, $tipo) =>
                    $q->where('tipo', $tipo)
            )
            ->when(
                $filtros['uniforme_id'] ?? null,
                fn (Builder $q, $id) =>
                    $q->whereHas(
                        'producto_uniforme',
                        fn (Builder $producto) =>
                            $producto->where('uniforme_id', $id)
                    )
            )
            ->when(
                $filtros['talla'] ?? null,
                fn (Builder $q, $talla) =>
                    $q->whereHas(
                        'producto_uniforme',
                        fn (Builder $producto) =>
                            $producto->where('talla', $talla)
                    )
            )
            ->when(
                $filtros['usuario_id'] ?? null,
                fn (Builder $q, $id) =>
                    $q->where('registrado_por', $id)
            )
            ->when(
                $filtros['buscar'] ?? null,
                function (Builder $q, string $buscar) {
                    $q->where(function (Builder $sub) use ($buscar) {
                        $sub->where('referencia', 'like', "%{$buscar}%")
                            ->orWhere('motivo', 'like', "%{$buscar}%")
                            ->orWhereHas(
                                'producto_uniforme',
                                function (Builder $producto) use ($buscar) {
                                    $producto->where(
                                        'nombre',
                                        'like',
                                        "%{$buscar}%"
                                    )->orWhere(
                                        'sku',
                                        'like',
                                        "%{$buscar}%"
                                    );
                                }
                            );
                    });
                }
            )
            ->orderByDesc('fecha_movimiento')
            ->orderByDesc('id');
    }

    public function index(Request $request): View
    {
        $filtros = $this->filtros($request);

        $movimientos = $this->consulta($filtros)
            ->paginate(20)
            ->withQueryString();


        $resumen = $this->consulta($filtros)
            ->withoutEagerLoads()
            ->reorder()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw(
                "COALESCE(SUM(CASE WHEN tipo = 'ENTRADA' THEN cantidad ELSE 0 END), 0) as entradas"
            )
            ->selectRaw(
                "COALESCE(SUM(CASE WHEN tipo = 'SALIDA' THEN cantidad ELSE 0 END), 0) as salidas"
            )
            ->first();


        $uniformes = Uniforme::query()
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'color']);

        $tallas = \App\Models\ProductoUniforme::query()
            ->whereNotNull('talla')
            ->distinct()
            ->orderBy('talla')
            ->pluck('talla');

        $usuarios = Usuario::query()
            ->whereIn(
                'id',
                MovimientoInventario::query()
                    ->whereNotNull('registrado_por')
                    ->select('registrado_por')
                    ->distinct()
            )
            ->with('persona')
            ->get()
            ->sortBy(fn ($usuario) =>
                $usuario->persona?->nombres
                ?? $usuario->nombre_usuario
            );

        return view(
            'direccion.inventario.uniformes.movimientos.index',
            compact(
                'movimientos',
                'resumen',
                'uniformes',
                'tallas',
                'usuarios',
                'filtros'
            )
        );
    }

    public function pdf(Request $request)
    {
        $filtros = $this->filtros($request);

        $movimientos = $this->consulta($filtros)->get();

        $resumen = [
            'total' => $movimientos->count(),
            'entradas' => $movimientos
                ->where('tipo', 'ENTRADA')
                ->sum('cantidad'),
            'salidas' => $movimientos
                ->where('tipo', 'SALIDA')
                ->sum('cantidad'),
        ];

        return Pdf::loadView(
            'direccion.inventario.uniformes.movimientos.pdf',
            compact('movimientos', 'filtros', 'resumen')
        )
            ->setPaper('letter', 'landscape')
            ->stream('historial-movimientos-uniformes.pdf');
    }
}
