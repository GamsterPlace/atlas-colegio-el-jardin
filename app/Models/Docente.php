<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Docente extends Model
{
    protected $table = 'docentes';

    public $timestamps = false;

    protected $fillable = [
        'personal_id',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('activo', true);
    }

    public function scopeInactivos(Builder $query): Builder
    {
        return $query->where('activo', false);
    }

    public function personal(): BelongsTo
    {
        return $this->belongsTo(
            Personal::class,
            'personal_id',
            'id'
        );
    }

    public function registrosDocenteGuia(): HasMany
    {
        return $this->hasMany(
            DocenteGuia::class,
            'docente_id',
            'id'
        );
    }

    public function registrosDisponibilidadDocente(): HasMany
    {
        return $this->hasMany(
            DisponibilidadDocente::class,
            'docente_id',
            'id'
        );
    }

    public function registrosAsignacionDocente(): HasMany
    {
        return $this->hasMany(
            AsignacionDocente::class,
            'docente_id',
            'id'
        );
    }

    public function registrosHorarioDetalle(): HasMany
    {
        return $this->hasMany(
            HorarioDetalle::class,
            'docente_id',
            'id'
        );
    }

    public function registrosRestriccionHorario(): HasMany
    {
        return $this->hasMany(
            RestriccionHorario::class,
            'docente_id',
            'id'
        );
    }
}
