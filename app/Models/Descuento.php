<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Descuento extends Model
{
    protected $table = 'descuentos';

    public $timestamps = false;

    protected $fillable = [
        'estudiante_id',
        'ciclo_escolar_id',
        'concepto_cobro_id',
        'tipo',
        'valor',
        'periodo_inicio',
        'periodo_fin',
        'motivo',
        'autorizado_por',
        'permite_combinacion_tarifa',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'valor' => 'decimal:2',
            'periodo_inicio' => 'date',
            'periodo_fin' => 'date',
            'permite_combinacion_tarifa' => 'boolean',
        ];
    }

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id', 'id');
    }

    public function ciclo_escolar(): BelongsTo
    {
        return $this->belongsTo(CicloEscolar::class, 'ciclo_escolar_id', 'id');
    }

    public function concepto_cobro(): BelongsTo
    {
        return $this->belongsTo(ConceptoCobro::class, 'concepto_cobro_id', 'id');
    }

    public function autorizado_por(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'autorizado_por', 'id');
    }
}
