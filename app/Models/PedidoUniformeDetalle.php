<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PedidoUniformeDetalle extends Model
{
    protected $table = 'pedidos_uniformes_detalles';
    public $timestamps = false;
    protected $guarded = ['id'];
    public function producto(): BelongsTo { return $this->belongsTo(ProductoUniforme::class, 'producto_uniforme_id'); }
}
