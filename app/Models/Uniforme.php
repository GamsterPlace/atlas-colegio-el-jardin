<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Uniforme extends Model
{
    protected $table = 'uniformes';

    protected $fillable = ['categoria_uniforme_id', 'nombre', 'color', 'descripcion', 'foto_path', 'activo'];

    protected function casts(): array
    {
        return ['activo' => 'boolean'];
    }

    public function categoria_uniforme(): BelongsTo
    {
        return $this->belongsTo(CategoriaUniforme::class, 'categoria_uniforme_id');
    }

    public function tallas(): HasMany
    {
        return $this->hasMany(ProductoUniforme::class, 'uniforme_id')->orderBy('id');
    }
}
