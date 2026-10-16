<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RecargoMora extends Model
{
    protected $table = 'recargos_mora';

    public $timestamps = false;

    protected $fillable = [
        'cuenta_por_cobrar_id',
        'configuracion_mora_id',
        'fecha_calculo',
        'monto',
        'estado',
        'anulado_por',
        'motivo_anulacion',
    ];

    protected function casts(): array
    {
        return [
            'fecha_calculo' => 'date',
            'monto' => 'decimal:2',
        ];
    }

    public function cuenta_por_cobrar(): BelongsTo
    {
        return $this->belongsTo(CuentaPorCobrar::class, 'cuenta_por_cobrar_id', 'id');
    }

    public function configuracion_mora(): BelongsTo
    {
        return $this->belongsTo(ConfiguracionMora::class, 'configuracion_mora_id', 'id');
    }

    public function anulado_por(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'anulado_por', 'id');
    }
}
