<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inscripcion extends Model
{
    protected $table = 'inscripciones';

    public $timestamps = true;

    protected $fillable = [
        'estudiante_id',
        'ciclo_escolar_id',
        'seccion_id',
        'responsable_financiero_id',
        'fecha_inscripcion',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inscripcion' => 'date',
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
        ];
    }

    public function scopeDelCiclo(
        Builder $query,
        int $cicloEscolarId
    ): Builder {
        return $query->where(
            'ciclo_escolar_id',
            $cicloEscolarId
        );
    }

    public function scopeBuscar(
        Builder $query,
        ?string $buscar
    ): Builder {
        if (blank($buscar)) {
            return $query;
        }

        $buscar = trim($buscar);

        return $query->whereHas(
            'estudiante',
            function (Builder $consulta) use ($buscar) {
                $consulta->where(
                    function (Builder $estudiantes) use ($buscar) {
                        $estudiantes
                            ->where(
                                'codigo',
                                'like',
                                "%{$buscar}%"
                            )
                            ->orWhere(
                                'numero_carnet',
                                'like',
                                "%{$buscar}%"
                            )
                            ->orWhere(
                                'nombres',
                                'like',
                                "%{$buscar}%"
                            )
                            ->orWhere(
                                'apellidos',
                                'like',
                                "%{$buscar}%"
                            );
                    }
                );
            }
        );
    }

    public function scopeCompletadas(
        Builder $query
    ): Builder {
        return $query->where(
            'estado',
            'COMPLETADA'
        );
    }

    public function scopeVigentes(
        Builder $query
    ): Builder {
        return $query
            ->where('estado', 'COMPLETADA')
            ->whereDate(
                'fecha_inicio',
                '<=',
                now()->toDateString()
            )
            ->where(function (Builder $consulta) {
                $consulta
                    ->whereNull('fecha_fin')
                    ->orWhereDate(
                        'fecha_fin',
                        '>=',
                        now()->toDateString()
                    );
            });
    }

    public function scopeDelEstudiante(
        Builder $query,
        int $estudianteId
    ): Builder {
        return $query->where(
            'estudiante_id',
            $estudianteId
        );
    }

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(
            Estudiante::class,
            'estudiante_id',
            'id'
        );
    }

    public function ciclo_escolar(): BelongsTo
    {
        return $this->belongsTo(
            CicloEscolar::class,
            'ciclo_escolar_id',
            'id'
        );
    }

    public function seccion(): BelongsTo
    {
        return $this->belongsTo(
            Seccion::class,
            'seccion_id',
            'id'
        );
    }

    public function responsable_financiero(): BelongsTo
    {
        return $this->belongsTo(
            Responsable::class,
            'responsable_financiero_id',
            'id'
        );
    }

    public function registrosHistorialSeccion(): HasMany
    {
        return $this->hasMany(
            HistorialSeccion::class,
            'inscripcion_id',
            'id'
        );
    }

    public function registrosRetiroEstudiante(): HasMany
    {
        return $this->hasMany(
            RetiroEstudiante::class,
            'inscripcion_id',
            'id'
        );
    }

    public function registrosCalificacion(): HasMany
    {
        return $this->hasMany(
            Calificacion::class,
            'inscripcion_id',
            'id'
        );
    }

    public function registrosNotaPeriodo(): HasMany
    {
        return $this->hasMany(
            NotaPeriodo::class,
            'inscripcion_id',
            'id'
        );
    }

    public function registrosNotaFinal(): HasMany
    {
        return $this->hasMany(
            NotaFinal::class,
            'inscripcion_id',
            'id'
        );
    }

    public function registrosObservacionAcademica(): HasMany
    {
        return $this->hasMany(
            ObservacionAcademica::class,
            'inscripcion_id',
            'id'
        );
    }

    public function registrosAsistenciaEstudiante(): HasMany
    {
        return $this->hasMany(
            AsistenciaEstudiante::class,
            'inscripcion_id',
            'id'
        );
    }
}
