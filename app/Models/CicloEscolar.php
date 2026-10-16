<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class CicloEscolar extends Model
{
    protected $table = 'ciclos_escolares';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
        ];
    }

    public function scopeEnFecha(
        Builder $query,
        Carbon|string|null $fecha = null
    ): Builder {
        $fechaConsulta = $fecha instanceof Carbon
            ? $fecha->toDateString()
            : ($fecha ?: now()->toDateString());

        return $query
            ->whereDate('fecha_inicio', '<=', $fechaConsulta)
            ->whereDate('fecha_fin', '>=', $fechaConsulta);
    }

    public static function vigente(
        Carbon|string|null $fecha = null
    ): ?self {
        return static::query()
            ->enFecha($fecha)
            ->orderByDesc('fecha_inicio')
            ->orderByDesc('id')
            ->first();
    }

    public function contieneFecha(
        Carbon|string|null $fecha = null
    ): bool {
        if (!$this->fecha_inicio || !$this->fecha_fin) {
            return false;
        }

        $fechaConsulta = $fecha instanceof Carbon
            ? $fecha->toDateString()
            : ($fecha ?: now()->toDateString());

        return $this->fecha_inicio->toDateString() <= $fechaConsulta
            && $this->fecha_fin->toDateString() >= $fechaConsulta;
    }

    public function registrosSeccion(): HasMany
    {
        return $this->hasMany(
            Seccion::class,
            'ciclo_escolar_id',
            'id'
        );
    }

    public function registrosInscripcion(): HasMany
    {
        return $this->hasMany(
            Inscripcion::class,
            'ciclo_escolar_id',
            'id'
        );
    }

    public function registrosPeriodoAcademico(): HasMany
    {
        return $this->hasMany(
            PeriodoAcademico::class,
            'ciclo_escolar_id',
            'id'
        );
    }

    public function registrosCalendarioEscolar(): HasMany
    {
        return $this->hasMany(
            CalendarioEscolar::class,
            'ciclo_escolar_id',
            'id'
        );
    }

    public function registrosDisponibilidadDocente(): HasMany
    {
        return $this->hasMany(
            DisponibilidadDocente::class,
            'ciclo_escolar_id',
            'id'
        );
    }

    public function registrosRestriccionHorario(): HasMany
    {
        return $this->hasMany(
            RestriccionHorario::class,
            'ciclo_escolar_id',
            'id'
        );
    }

    public function registrosEjecucionGeneradorHorario(): HasMany
    {
        return $this->hasMany(
            EjecucionGeneradorHorario::class,
            'ciclo_escolar_id',
            'id'
        );
    }

    public function registrosEventoAgenda(): HasMany
    {
        return $this->hasMany(
            EventoAgenda::class,
            'ciclo_escolar_id',
            'id'
        );
    }

    public function registrosConfiguracionColegiatura(): HasMany
    {
        return $this->hasMany(
            ConfiguracionColegiatura::class,
            'ciclo_escolar_id',
            'id'
        );
    }

    public function registrosTarifaEstudiante(): HasMany
    {
        return $this->hasMany(
            TarifaEstudiante::class,
            'ciclo_escolar_id',
            'id'
        );
    }

    public function registrosDescuento(): HasMany
    {
        return $this->hasMany(
            Descuento::class,
            'ciclo_escolar_id',
            'id'
        );
    }

    public function registrosCuentaPorCobrar(): HasMany
    {
        return $this->hasMany(
            CuentaPorCobrar::class,
            'ciclo_escolar_id',
            'id'
        );
    }

    public function registrosTalonarioPago(): HasMany
    {
        return $this->hasMany(
            TalonarioPago::class,
            'ciclo_escolar_id',
            'id'
        );
    }

    public function registrosConfiguracionMora(): HasMany
    {
        return $this->hasMany(
            ConfiguracionMora::class,
            'ciclo_escolar_id',
            'id'
        );
    }

    public function registrosEntregaUniforme(): HasMany
    {
        return $this->hasMany(
            EntregaUniforme::class,
            'ciclo_escolar_id',
            'id'
        );
    }
}
