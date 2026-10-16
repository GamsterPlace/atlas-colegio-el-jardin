<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class HojaTalonario extends Model
{
    protected $table = 'hojas_talonario';

    public $timestamps = false;

    protected $fillable = [
        'talonario_id',
        'cuenta_por_cobrar_id',
        'autorizacion_detalle_id',
        'hoja_origen_id',
        'codigo',
        'tipo',
        'monto_impreso',
        'fecha_vencimiento_impresa',
        'nombre_estudiante_impreso',
        'concepto_impreso',
        'periodo_impreso',
        'estado',
        'fecha_emision',
        'anulada_por',
        'anulada_at',
        'motivo_anulacion',
    ];

    protected function casts(): array
    {
        return [
            'monto_impreso' => 'decimal:2',
            'fecha_vencimiento_impresa' => 'date',
            'fecha_emision' => 'datetime',
            'anulada_at' => 'datetime',
        ];
    }

    public function talonario(): BelongsTo
    {
        return $this->belongsTo(TalonarioPago::class, 'talonario_id', 'id');
    }

    public function cuenta_por_cobrar(): BelongsTo
    {
        return $this->belongsTo(CuentaPorCobrar::class, 'cuenta_por_cobrar_id', 'id');
    }

    public function autorizacion_detalle(): BelongsTo
    {
        return $this->belongsTo(AutorizacionPagoParcialDetalle::class, 'autorizacion_detalle_id', 'id');
    }

    public function hoja_origen(): BelongsTo
    {
        return $this->belongsTo(HojaTalonario::class, 'hoja_origen_id', 'id');
    }

    public function anulada_por(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'anulada_por', 'id');
    }

    public function registrosHojaTalonario(): HasMany
    {
        return $this->hasMany(HojaTalonario::class, 'hoja_origen_id', 'id');
    }

    public function registrosReimpresionTalonario(): HasMany
    {
        return $this->hasMany(ReimpresionTalonario::class, 'hoja_talonario_id', 'id');
    }

    public function registrosPago(): HasMany
    {
        return $this->hasMany(Pago::class, 'hoja_talonario_id', 'id');
    }
}
