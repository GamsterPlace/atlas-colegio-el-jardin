<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ConfiguracionMora extends Model
{
    protected $table = 'configuraciones_mora';

    public $timestamps = false;

    protected $fillable = [
        'ciclo_escolar_id',
        'concepto_cobro_id',
        'tipo',
        'valor',
        'dias_gracia',
        'periodicidad',
        'vigente_desde',
        'vigente_hasta',
        'aprobado_por',
    ];

    protected function casts(): array
    {
        return [
            'valor' => 'decimal:2',
            'dias_gracia' => 'integer',
            'vigente_desde' => 'date',
            'vigente_hasta' => 'date',
        ];
    }

    public function ciclo_escolar(): BelongsTo
    {
        return $this->belongsTo(CicloEscolar::class, 'ciclo_escolar_id', 'id');
    }

    public function concepto_cobro(): BelongsTo
    {
        return $this->belongsTo(ConceptoCobro::class, 'concepto_cobro_id', 'id');
    }

    public function aprobado_por(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'aprobado_por', 'id');
    }

    public function registrosRecargoMora(): HasMany
    {
        return $this->hasMany(RecargoMora::class, 'configuracion_mora_id', 'id');
    }
}
