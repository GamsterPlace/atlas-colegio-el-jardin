<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MovimientoInventario extends Model
{
    protected $table = 'movimientos_inventario';

    public $timestamps = false;

    protected $fillable = [
        'producto_uniforme_id',
        'tipo',
        'cantidad',
        'motivo',
        'referencia',
        'registrado_por',
        'fecha_movimiento',
    ];

    protected function casts(): array
    {
        return [
            'cantidad' => 'integer',
            'fecha_movimiento' => 'datetime',
        ];
    }

    public function producto_uniforme(): BelongsTo
    {
        return $this->belongsTo(ProductoUniforme::class, 'producto_uniforme_id', 'id');
    }

    public function registrado_por(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'registrado_por', 'id');
    }
}
