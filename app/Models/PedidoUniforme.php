<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PedidoUniforme extends Model
{
    protected $table = 'pedidos_uniformes';
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['vence_at' => 'datetime', 'procesado_at' => 'datetime', 'preparado_at' => 'datetime', 'entregado_at' => 'datetime', 'anulado_at' => 'datetime', 'total' => 'decimal:2'];
    }

    public function estudiante(): BelongsTo { return $this->belongsTo(Estudiante::class); }
    public function responsable(): BelongsTo { return $this->belongsTo(Responsable::class); }
    public function detalles(): HasMany { return $this->hasMany(PedidoUniformeDetalle::class, 'pedido_uniforme_id'); }
    public function pago(): HasOne { return $this->hasOne(PagoUniforme::class, 'pedido_uniforme_id'); }
}
