<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ConfiguracionColegiatura extends Model
{
    protected $table = 'configuraciones_colegiaturas';

    public $timestamps = false;

    protected $fillable = [
        'ciclo_escolar_id',
        'concepto_cobro_id',
        'nivel_id',
        'grado_id',
        'monto',
        'meses_cobro',
        'dia_vencimiento',
        'vigente_desde',
        'vigente_hasta',
    ];

    protected function casts(): array
    {
        return [
            'monto' => 'decimal:2',
            'meses_cobro' => 'array',
            'dia_vencimiento' => 'integer',
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

    public function nivel(): BelongsTo
    {
        return $this->belongsTo(Nivel::class, 'nivel_id', 'id');
    }

    public function grado(): BelongsTo
    {
        return $this->belongsTo(Grado::class, 'grado_id', 'id');
    }
}
