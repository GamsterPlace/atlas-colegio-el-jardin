<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Publicacion extends Model
{
    protected $table = 'publicaciones';

    public $timestamps = false;

    protected $fillable = [
        'titulo',
        'slug',
        'resumen',
        'contenido',
        'estado',
        'publicada_at',
        'autor_id',
    ];

    protected function casts(): array
    {
        return [
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
