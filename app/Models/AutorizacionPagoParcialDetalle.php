<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AutorizacionPagoParcialDetalle extends Model
{
    protected $table = 'autorizaciones_pagos_parciales_detalles';

    public $timestamps = false;

    protected $fillable = [
        'autorizacion_id',
        'numero_parcialidad',
        'monto',
    ];

    protected function casts(): array
    {
        return [
            'numero_parcialidad' => 'integer',
            'monto' => 'decimal:2',
        ];
    }

    public function autorizacion(): BelongsTo
    {
        return $this->belongsTo(AutorizacionPagoParcial::class, 'autorizacion_id', 'id');
    }

    public function registrosHojaTalonario(): HasOne
    {
        return $this->hasOne(HojaTalonario::class, 'autorizacion_detalle_id', 'id');
    }
}
