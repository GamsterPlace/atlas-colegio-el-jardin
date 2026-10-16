<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class HistorialCambioNota extends Model
{
    protected $table = 'historial_cambios_notas';

    public $timestamps = false;

    protected $fillable = [
        'calificacion_id',
        'nota_periodo_id',
        'nota_final_id',
        'valor_anterior',
        'valor_nuevo',
        'motivo',
        'usuario_id',
        'fecha_cambio',
    ];

    protected function casts(): array
    {
        return [
            'valor_anterior' => 'decimal:2',
            'valor_nuevo' => 'decimal:2',
            'fecha_cambio' => 'datetime',
        ];
    }

    public function calificacion(): BelongsTo
    {
        return $this->belongsTo(Calificacion::class, 'calificacion_id', 'id');
    }

    public function nota_periodo(): BelongsTo
    {
        return $this->belongsTo(NotaPeriodo::class, 'nota_periodo_id', 'id');
    }

    public function nota_final(): BelongsTo
    {
        return $this->belongsTo(NotaFinal::class, 'nota_final_id', 'id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id');
    }
}
