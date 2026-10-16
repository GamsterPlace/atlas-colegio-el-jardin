<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CuentaPorCobrar extends Model
{
    protected $table = 'cuentas_por_cobrar';

    public $timestamps = false;

    protected $fillable = [
        'estudiante_id',
        'ciclo_escolar_id',
        'concepto_cobro_id',
        'periodo_referencia',
        'descripcion',
        'fecha_emision',
        'fecha_vencimiento',
        'monto_base',
        'monto_descuento',
        'monto_exigible',
        'estado_administrativo',
        'anulada_por',
        'anulada_at',
        'motivo_anulacion',
    ];

    protected function casts(): array
    {
        return [
            'periodo_referencia' => 'date',
            'fecha_emision' => 'date',
            'fecha_vencimiento' => 'date',
            'monto_base' => 'decimal:2',
            'monto_descuento' => 'decimal:2',
            'monto_exigible' => 'decimal:2',
            'anulada_at' => 'datetime',
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

    public function anulada_por(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'anulada_por', 'id');
    }

    public function registrosAutorizacionPagoParcial(): HasMany
    {
        return $this->hasMany(AutorizacionPagoParcial::class, 'cuenta_por_cobrar_id', 'id');
    }

    public function registrosHojaTalonario(): HasMany
    {
        return $this->hasMany(HojaTalonario::class, 'cuenta_por_cobrar_id', 'id');
    }

    public function registrosPago(): HasMany
    {
        return $this->hasMany(Pago::class, 'cuenta_por_cobrar_id', 'id');
    }

    public function registrosRecargoMora(): HasMany
    {
        return $this->hasMany(RecargoMora::class, 'cuenta_por_cobrar_id', 'id');
    }
}
