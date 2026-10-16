<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AsistenciaEstudiante extends Model
{
    protected $table = 'asistencia_estudiantes';

    public $timestamps = false;

    protected $fillable = [
        'inscripcion_id',
        'fecha',
        'estado',
        'registrado_por',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
        ];
    }

    public function scopeEnFecha(
        Builder $query,
        ?string $fecha = null
    ): Builder {
        return $query->whereDate(
            'fecha',
            $fecha ?? now()->toDateString()
        );
    }

    public function scopeDelCiclo(
        Builder $query,
        int $cicloEscolarId
    ): Builder {
        return $query->whereHas(
            'inscripcion',
            function (Builder $consulta) use ($cicloEscolarId) {
                $consulta->where(
                    'ciclo_escolar_id',
                    $cicloEscolarId
                );
            }
        );
    }

    public function inscripcion(): BelongsTo
    {
        return $this->belongsTo(
            Inscripcion::class,
            'inscripcion_id',
            'id'
        );
    }

    public function registrado_por(): BelongsTo
    {
        return $this->belongsTo(
            Usuario::class,
            'registrado_por',
            'id'
        );
    }

    public function registrosJustificacionAsistencia(): HasMany
    {
        return $this->hasMany(
            JustificacionAsistencia::class,
            'asistencia_estudiante_id',
            'id'
        );
    }

    public function registrosObservacionAsistencia(): HasMany
    {
        return $this->hasMany(
            ObservacionAsistencia::class,
            'asistencia_estudiante_id',
            'id'
        );
    }
}
