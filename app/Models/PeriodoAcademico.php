<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PeriodoAcademico extends Model
{
    protected $table = 'periodos_academicos';

    public $timestamps = false;

    protected $fillable = [
        'ciclo_escolar_id',
        'nombre',
        'numero',
        'fecha_inicio',
        'fecha_fin',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'numero' => 'integer',
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
        ];
    }

    public function ciclo_escolar(): BelongsTo
    {
        return $this->belongsTo(CicloEscolar::class, 'ciclo_escolar_id', 'id');
    }

    public function registrosActividadEvaluacion(): HasMany
    {
        return $this->hasMany(ActividadEvaluacion::class, 'periodo_academico_id', 'id');
    }

    public function registrosNotaPeriodo(): HasMany
    {
        return $this->hasMany(NotaPeriodo::class, 'periodo_academico_id', 'id');
    }

    public function registrosObservacionAcademica(): HasMany
    {
        return $this->hasMany(ObservacionAcademica::class, 'periodo_academico_id', 'id');
    }

    public function registrosCierreAcademico(): HasMany
    {
        return $this->hasMany(CierreAcademico::class, 'periodo_academico_id', 'id');
    }
}
