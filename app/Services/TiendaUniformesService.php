<?php

namespace App\Services;

use App\Models\ExistenciaUniforme;
use App\Models\PedidoUniforme;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TiendaUniformesService
{
    public function expirar(): int
    {
        $ids = PedidoUniforme::query()->whereIn('estado', ['PENDIENTE', 'EN_PROCESO', 'PREPARADO'])
            ->where('vence_at', '<=', now())->pluck('id');
        $total = 0;
        foreach ($ids as $id) {
            DB::transaction(function () use ($id, &$total) {
                $pedido = PedidoUniforme::query()->lockForUpdate()->find($id);
                if (!$pedido || !in_array($pedido->estado, ['PENDIENTE', 'EN_PROCESO', 'PREPARADO'], true) || $pedido->vence_at->isFuture()) return;
                $pedido->update(['estado' => 'ANULADO', 'motivo_anulacion' => 'Vencimiento automático de reserva de 72 horas', 'anulado_at' => now()]);
                DB::table('eventos_pedidos_uniformes')->insert(['pedido_uniforme_id' => $id, 'accion' => 'EXPIRADO', 'detalle' => 'Reserva liberada; si estaba preparado, devolver prendas al inventario disponible.', 'created_at' => now()]);
                $total++;
            });
        }
        return $total;
    }

    public function disponibles(int $productoId, ?int $excluirPedidoId = null): int
    {
        $fisico = (int) (ExistenciaUniforme::query()->where('producto_uniforme_id', $productoId)->value('cantidad') ?? 0);
        $reservado = (int) DB::table('pedidos_uniformes_detalles as d')
            ->join('pedidos_uniformes as p', 'p.id', '=', 'd.pedido_uniforme_id')
            ->where('d.producto_uniforme_id', $productoId)
            ->whereIn('p.estado', ['PENDIENTE', 'EN_PROCESO', 'PREPARADO'])
            ->where('p.vence_at', '>', now())
            ->when($excluirPedidoId, fn ($q) => $q->where('p.id', '!=', $excluirPedidoId))
            ->sum('d.cantidad');
        return max(0, $fisico - $reservado);
    }

    public function comprobarStock(int $productoId, int $cantidad): void
    {
        if ($cantidad < 1 || $this->disponibles($productoId) < $cantidad) {
            throw ValidationException::withMessages(['productos' => 'Existencia insuficiente para una de las tallas solicitadas.']);
        }
    }

    public function evento(PedidoUniforme $pedido, string $accion, ?string $detalle = null, ?int $usuarioId = null): void
    {
        DB::table('eventos_pedidos_uniformes')->insert([
            'pedido_uniforme_id' => $pedido->id, 'usuario_id' => $usuarioId,
            'accion' => $accion, 'detalle' => $detalle, 'created_at' => now(),
        ]);
    }
}
