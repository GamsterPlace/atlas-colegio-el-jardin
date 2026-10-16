<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PlantillaDocumento extends Model
{
    protected $table = 'plantillas_documentos';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'version',
        'contenido',
        'activa',
    ];

    protected function casts(): array
    {
        return [
            'version' => 'integer',
            'activa' => 'boolean',
        ];
    }
}
