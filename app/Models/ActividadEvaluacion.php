<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ActividadEvaluacion extends Model
{
    protected $table = 'actividades_evaluacion';

    public $timestamps = false;

    protected $fillable = [
        'carga_academica_id',
        'periodo_academico_id',
        'titulo',
        'tipo',
        'fecha',
        'punteo_maximo',
        'ponderacion',
        'publicada',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'punteo_maximo' => 'decimal:2',
            'ponderacion' => 'decimal:2',
            'publicada' => 'boolean',
        ];
    }

    public function carga_academica(): BelongsTo
    {
        return $this->belongsTo(CargaAcademica::class, 'carga_academica_id', 'id');
    }

    public function periodo_academico(): BelongsTo
    {
        return $this->belongsTo(PeriodoAcademico::class, 'periodo_academico_id', 'id');
    }

    public function registrosCalificacion(): HasMany
    {
        return $this->hasMany(Calificacion::class, 'actividad_evaluacion_id', 'id');
    }
}
