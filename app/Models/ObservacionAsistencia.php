<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ObservacionAsistencia extends Model
{
    protected $table = 'observaciones_asistencia';

    public $timestamps = false;

    protected $fillable = [
        'asistencia_estudiante_id',
        'observacion',
        'usuario_id',
    ];

    public function asistencia_estudiante(): BelongsTo
    {
        return $this->belongsTo(AsistenciaEstudiante::class, 'asistencia_estudiante_id', 'id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id');
    }
}
