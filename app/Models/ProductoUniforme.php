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
    protected $fillable = ['uniforme_id', 'categoria_uniforme_id', 'sku', 'nombre', 'talla', 'color', 'precio_referencia', 'activo'];

    protected function casts(): array
    {
        return ['precio_referencia' => 'decimal:2', 'activo' => 'boolean'];
    }

    public function uniforme(): BelongsTo
    {
        return $this->belongsTo(Uniforme::class, 'uniforme_id');
    }

    public function categoria_uniforme(): BelongsTo
    {
        return $this->belongsTo(CategoriaUniforme::class, 'categoria_uniforme_id');
    }

    public function r9yMnTm4NSzvG9rrwjM2ec8xZgh1cafXH8(): HasOne
    {
        return $this->hasOne(ExistenciaUniforme::class, 'producto_uniforme_id');
    }

    public function registrosMovimientoInventario(): HasMany
    {
        return $this->hasMany(MovimientoInventario::class, 'producto_uniforme_id');
    }

    public function registrosEntregaUniformeDetalle(): HasMany
    {
        return $this->hasMany(EntregaUniformeDetalle::class, 'producto_uniforme_id');
    }
}
