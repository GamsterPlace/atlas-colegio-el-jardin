<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CategoriaUniforme extends Model
{
    protected $table = 'categorias_uniformes';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function registrosProductoUniforme(): HasMany
    {
        return $this->hasMany(ProductoUniforme::class, 'categoria_uniforme_id', 'id');
    }
}
