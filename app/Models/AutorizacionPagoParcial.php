<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AutorizacionPagoParcial extends Model
{
    protected $table = 'autorizaciones_pagos_parciales';

    public $timestamps = false;

    protected $fillable = [
        'cuenta_por_cobrar_id',
        'numero_autorizacion',
        'autorizacion_origen_id',
        'monto_autorizado',
        'motivo',
        'estado',
        'autorizado_por',
        'fecha_autorizacion',
        'motivo_sustitucion',
        'motivo_anulacion',
        'anulada_por',
        'anulada_at',
        'ruta_pdf_firmado',
    ];

    protected function casts(): array
    {
        return [
            'monto_autorizado' => 'decimal:2',
            'fecha_autorizacion' => 'datetime',
            'anulada_at' => 'datetime',
        ];
    }

    public function cuenta_por_cobrar(): BelongsTo
    {
        return $this->belongsTo(CuentaPorCobrar::class, 'cuenta_por_cobrar_id', 'id');
    }

    public function autorizacion_origen(): BelongsTo
    {
        return $this->belongsTo(AutorizacionPagoParcial::class, 'autorizacion_origen_id', 'id');
    }

    public function autorizado_por(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'autorizado_por', 'id');
    }

    public function anulada_por(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'anulada_por', 'id');
    }

    public function registrosAutorizacionPagoParcial(): HasOne
    {
        return $this->hasOne(AutorizacionPagoParcial::class, 'autorizacion_origen_id', 'id');
    }

    public function registrosAutorizacionPagoParcialDetalle(): HasMany
    {
        return $this->hasMany(AutorizacionPagoParcialDetalle::class, 'autorizacion_id', 'id');
    }
}
