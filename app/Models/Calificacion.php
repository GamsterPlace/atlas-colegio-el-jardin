<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Calificacion extends Model
{
    protected $table = 'calificaciones';

    public $timestamps = false;

    protected $fillable = [
        'actividad_evaluacion_id',
        'inscripcion_id',
        'nota',
        'observaciones',
        'registrado_por',
    ];

    protected function casts(): array
    {
        return [
            'nota' => 'decimal:2',
        ];
    }

    public function actividad_evaluacion(): BelongsTo
    {
        return $this->belongsTo(ActividadEvaluacion::class, 'actividad_evaluacion_id', 'id');
    }

    public function inscripcion(): BelongsTo
    {
        return $this->belongsTo(Inscripcion::class, 'inscripcion_id', 'id');
    }

    public function registrado_por(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'registrado_por', 'id');
    }

    public function registrosHistorialCambioNota(): HasMany
    {
        return $this->hasMany(HistorialCambioNota::class, 'calificacion_id', 'id');
    }
}
