<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EntregaUniformeDetalle extends Model
{
    protected $table = 'entregas_uniformes_detalles';

    public $timestamps = false;

    protected $fillable = [
        'entrega_uniforme_id',
        'producto_uniforme_id',
        'cantidad',
    ];

    protected function casts(): array
    {
        return [
            'cantidad' => 'integer',
        ];
    }

    public function entrega_uniforme(): BelongsTo
    {
        return $this->belongsTo(EntregaUniforme::class, 'entrega_uniforme_id', 'id');
    }

    public function producto_uniforme(): BelongsTo
    {
        return $this->belongsTo(ProductoUniforme::class, 'producto_uniforme_id', 'id');
    }
}
