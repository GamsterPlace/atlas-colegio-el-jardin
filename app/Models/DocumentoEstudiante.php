<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DocumentoEstudiante extends Model
{
    protected $table = 'documentos_estudiantes';

    public $timestamps = false;

    protected $fillable = [
        'estudiante_id',
        'tipo',
        'nombre_original',
        'ruta_privada',
        'mime',
        'subido_por',
    ];

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id', 'id');
    }

    public function subido_por(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'subido_por', 'id');
    }
}
