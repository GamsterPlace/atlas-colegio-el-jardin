<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ConceptoCobro extends Model
{
    protected $table = 'conceptos_cobro';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'tipo',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function registrosConfiguracionColegiatura(): HasMany
    {
        return $this->hasMany(ConfiguracionColegiatura::class, 'concepto_cobro_id', 'id');
    }

    public function registrosTarifaEstudiante(): HasMany
    {
        return $this->hasMany(TarifaEstudiante::class, 'concepto_cobro_id', 'id');
    }

    public function registrosDescuento(): HasMany
    {
        return $this->hasMany(Descuento::class, 'concepto_cobro_id', 'id');
    }

    public function registrosCuentaPorCobrar(): HasMany
    {
        return $this->hasMany(CuentaPorCobrar::class, 'concepto_cobro_id', 'id');
    }

    public function registrosConfiguracionMora(): HasMany
    {
        return $this->hasMany(ConfiguracionMora::class, 'concepto_cobro_id', 'id');
    }
}
