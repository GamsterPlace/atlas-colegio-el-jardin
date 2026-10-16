<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ArchivoPublicacion extends Model
{
    protected $table = 'archivos_publicaciones';

    public $timestamps = false;

    protected $fillable = [
        'publicacion_id',
        'ruta_archivo',
        'nombre_original',
        'orden',
    ];

    protected function casts(): array
    {
        return [
            'orden' => 'integer',
        ];
    }

    public function publicacion(): BelongsTo
    {
        return $this->belongsTo(Publicacion::class, 'publicacion_id', 'id');
    }
}
