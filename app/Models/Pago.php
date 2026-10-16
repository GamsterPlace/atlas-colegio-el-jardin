<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pago extends Model
{
    protected $table = 'pagos';

    public $timestamps = false;

    protected $fillable = [
        'hoja_talonario_id',
        'pago_origen_id',
        'estudiante_id',
        'cuenta_por_cobrar_id',
        'fecha_deposito',
        'fecha_registro',
        'monto',
        'monto_aplicado_principal',
        'monto_aplicado_mora',
        'banco',
        'referencia_bancaria',
        'registrado_por',
        'estado',
        'anulado_por',
        'anulado_at',
        'motivo_anulacion',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha_deposito' => 'date',
            'fecha_registro' => 'datetime',
            'monto' => 'decimal:2',
            'monto_aplicado_principal' => 'decimal:2',
            'monto_aplicado_mora' => 'decimal:2',
            'anulado_at' => 'datetime',
        ];
    }

    public function hoja_talonario(): BelongsTo
    {
        return $this->belongsTo(HojaTalonario::class, 'hoja_talonario_id', 'id');
    }

    public function pago_origen(): BelongsTo
    {
        return $this->belongsTo(Pago::class, 'pago_origen_id', 'id');
    }

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id', 'id');
    }

    public function cuenta_por_cobrar(): BelongsTo
    {
        return $this->belongsTo(CuentaPorCobrar::class, 'cuenta_por_cobrar_id', 'id');
    }

    public function registrado_por(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'registrado_por', 'id');
    }

    public function anulado_por(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'anulado_por', 'id');
    }

    public function registrosPago(): HasOne
    {
        return $this->hasOne(Pago::class, 'pago_origen_id', 'id');
    }
}
