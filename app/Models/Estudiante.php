<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Estudiante extends Model
{
    use SoftDeletes;

    protected $table = 'estudiantes';

    public $timestamps = true;

    protected $fillable = [
        'codigo',
        'numero_carnet',
        'cui',
        'lugar_nacimiento',
        'nombres',
        'apellidos',
        'fecha_nacimiento',
        'sexo',
        'direccion',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
        ];
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim(
            ($this->nombres ?? '') . ' ' . ($this->apellidos ?? '')
        );
    }

    public function scopeBuscar($query, ?string $buscar)
    {
        if (blank($buscar)) {
            return $query;
        }

        $buscar = trim($buscar);

        return $query->where(function ($consulta) use ($buscar) {
            $consulta
                ->where('codigo', 'like', "%{$buscar}%")
                ->orWhere('numero_carnet', 'like', "%{$buscar}%")
                ->orWhere('nombres', 'like', "%{$buscar}%")
                ->orWhere('apellidos', 'like', "%{$buscar}%");
        });
    }

    public function scopeBuscarPorIdentificador(
        $query,
        string $identificador
    ) {
        $identificador = trim($identificador);

        return $query->where(function ($consulta) use ($identificador) {
            $consulta
                ->where('codigo', $identificador)
                ->orWhere('numero_carnet', $identificador);
        });
    }

    public function scopeActivos($query)
    {
        return $query->where('estado', 'ACTIVO');
    }

    public function registrosEstudianteResponsable(): HasMany
    {
        return $this->hasMany(
            EstudianteResponsable::class,
            'estudiante_id',
            'id'
        );
    }

    public function registrosInscripcion(): HasMany
    {
        return $this->hasMany(
            Inscripcion::class,
            'estudiante_id',
            'id'
        );
    }

    public function registrosContactoEmergencia(): HasMany
    {
        return $this->hasMany(
            ContactoEmergencia::class,
            'estudiante_id',
            'id'
        );
    }

    public function registrosInformacionMedicaEstudiante(): HasOne
    {
        return $this->hasOne(
            InformacionMedicaEstudiante::class,
            'estudiante_id',
            'id'
        );
    }

    public function registrosInformacionComplementariaEstudiante(): HasOne
    {
        return $this->hasOne(
            InformacionComplementariaEstudiante::class,
            'estudiante_id',
            'id'
        );
    }

    public function registrosPersonaAutorizadaRetiro(): HasMany
    {
        return $this->hasMany(
            PersonaAutorizadaRetiro::class,
            'estudiante_id',
            'id'
        );
    }

    public function registrosDocumentoEstudiante(): HasMany
    {
        return $this->hasMany(
            DocumentoEstudiante::class,
            'estudiante_id',
            'id'
        );
    }

    public function registrosTarifaEstudiante(): HasMany
    {
        return $this->hasMany(
            TarifaEstudiante::class,
            'estudiante_id',
            'id'
        );
    }

    public function registrosDescuento(): HasMany
    {
        return $this->hasMany(
            Descuento::class,
            'estudiante_id',
            'id'
        );
    }

    public function registrosCuentaPorCobrar(): HasMany
    {
        return $this->hasMany(
            CuentaPorCobrar::class,
            'estudiante_id',
            'id'
        );
    }

    public function registrosTalonarioPago(): HasMany
    {
        return $this->hasMany(
            TalonarioPago::class,
            'estudiante_id',
            'id'
        );
    }

    public function registrosPago(): HasMany
    {
        return $this->hasMany(
            Pago::class,
            'estudiante_id',
            'id'
        );
    }

    public function registrosEntregaUniforme(): HasMany
    {
        return $this->hasMany(
            EntregaUniforme::class,
            'estudiante_id',
            'id'
        );
    }
}
