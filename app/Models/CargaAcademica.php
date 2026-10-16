<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CargaAcademica extends Model
{
    protected $table = 'cargas_academicas';

    public $timestamps = false;

    protected $fillable = [
        'seccion_id',
        'pensum_curso_id',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function seccion(): BelongsTo
    {
        return $this->belongsTo(Seccion::class, 'seccion_id', 'id');
    }

    public function pensum_curso(): BelongsTo
    {
        return $this->belongsTo(PensumCurso::class, 'pensum_curso_id', 'id');
    }

    public function registrosAsignacionDocente(): HasMany
    {
        return $this->hasMany(AsignacionDocente::class, 'carga_academica_id', 'id');
    }

    public function registrosActividadEvaluacion(): HasMany
    {
        return $this->hasMany(ActividadEvaluacion::class, 'carga_academica_id', 'id');
    }

    public function registrosNotaPeriodo(): HasMany
    {
        return $this->hasMany(NotaPeriodo::class, 'carga_academica_id', 'id');
    }

    public function registrosNotaFinal(): HasMany
    {
        return $this->hasMany(NotaFinal::class, 'carga_academica_id', 'id');
    }

    public function registrosObservacionAcademica(): HasMany
    {
        return $this->hasMany(ObservacionAcademica::class, 'carga_academica_id', 'id');
    }

    public function registrosCierreAcademico(): HasMany
    {
        return $this->hasMany(CierreAcademico::class, 'carga_academica_id', 'id');
    }

    public function registrosHorarioDetalle(): HasMany
    {
        return $this->hasMany(HorarioDetalle::class, 'carga_academica_id', 'id');
    }
}
