<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class JustificacionPersonal extends Model
{
    protected $table = 'justificaciones_personal';

    public $timestamps = false;

    protected $fillable = [
        'asistencia_personal_id',
        'motivo',
        'ruta_documento',
        'aprobado_por',
        'estado',
    ];

    public function asistencia_personal(): BelongsTo
    {
        return $this->belongsTo(AsistenciaPersonal::class, 'asistencia_personal_id', 'id');
    }

    public function aprobado_por(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'aprobado_por', 'id');
    }
}
