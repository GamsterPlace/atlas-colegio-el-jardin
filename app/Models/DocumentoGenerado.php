<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DocumentoGenerado extends Model
{
    protected $table = 'documentos_generados';

    public $timestamps = false;

    protected $fillable = [
        'tipo',
        'entidad',
        'entidad_id',
        'codigo',
        'ruta_privada',
        'hash_sha256',
        'generado_por',
        'fecha_generacion',
    ];

    protected function casts(): array
    {
        return [
            'fecha_generacion' => 'datetime',
        ];
    }

    public function generado_por(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'generado_por', 'id');
    }
}
