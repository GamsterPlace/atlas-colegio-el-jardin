<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ExistenciaUniforme extends Model
{
    protected $table = 'existencias_uniformes';

    public $timestamps = false;

    protected $fillable = [
        'producto_uniforme_id',
        'cantidad',
        'stock_minimo',
    ];

    protected function casts(): array
    {
        return [
            'cantidad' => 'integer',
            'stock_minimo' => 'integer',
        ];
    }

    public function producto_uniforme(): BelongsTo
    {
        return $this->belongsTo(ProductoUniforme::class, 'producto_uniforme_id', 'id');
    }
}
