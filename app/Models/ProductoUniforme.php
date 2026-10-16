<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductoUniforme extends Model
{
    protected $table = 'productos_uniformes';

    public $timestamps = false;

    protected $fillable = [
        'categoria_uniforme_id',
        'sku',
        'nombre',
        'talla',
        'color',
        'precio_referencia',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'precio_referencia' => 'decimal:2',
            'activo' => 'boolean',
        ];
    }

    public function categoria_uniforme(): BelongsTo
    {
        return $this->belongsTo(CategoriaUniforme::class, 'categoria_uniforme_id', 'id');
    }

    public function registrosExistenciaUniforme(): HasOne
    {
        return $this->hasOne(ExistenciaUniforme::class, 'producto_uniforme_id', 'id');
    }

    public function registrosMovimientoInventario(): HasMany
    {
        return $this->hasMany(MovimientoInventario::class, 'producto_uniforme_id', 'id');
    }

    public function registrosEntregaUniformeDetalle(): HasMany
    {
        return $this->hasMany(EntregaUniformeDetalle::class, 'producto_uniforme_id', 'id');
    }
}
