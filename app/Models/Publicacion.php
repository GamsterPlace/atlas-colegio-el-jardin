<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Publicacion extends Model
{
    protected $table = 'publicaciones';

    protected $fillable = [
        'titulo',
        'slug',
        'resumen',
        'contenido',
        'categoria',
        'destacada',
        'estado',
        'publicada_at',
        'autor_id',
    ];

    protected function casts(): array
    {
        return [
            'destacada' => 'boolean',
            'publicada_at' => 'datetime',
        ];
    }

    public function autor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'autor_id', 'id');
    }

    public function registrosArchivoPublicacion(): HasMany
    {
        return $this->hasMany(ArchivoPublicacion::class, 'publicacion_id', 'id');
    }
}
