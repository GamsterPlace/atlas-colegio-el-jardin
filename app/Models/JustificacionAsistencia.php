<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class JustificacionAsistencia extends Model
{
    protected $table = 'justificaciones_asistencia';

    public $timestamps = false;

    protected $fillable = [
        'asistencia_estudiante_id',
        'motivo',
        'ruta_documento',
        'registrado_por',
        'aprobado_por',
        'estado',
    ];

    public function asistencia_estudiante(): BelongsTo
    {
        return $this->belongsTo(AsistenciaEstudiante::class, 'asistencia_estudiante_id', 'id');
    }

    public function registrado_por(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'registrado_por', 'id');
    }

    public function aprobado_por(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'aprobado_por', 'id');
    }
}
