<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EntregaUniforme extends Model
{
    protected $table = 'entregas_uniformes';

    public $timestamps = false;

    protected $fillable = [
        'estudiante_id',
        'ciclo_escolar_id',
        'fecha_entrega',
        'entregado_por',
        'recibido_por_responsable_id',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha_entrega' => 'date',
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

    public function entregado_por(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'entregado_por', 'id');
    }

    public function recibido_por_responsable(): BelongsTo
    {
        return $this->belongsTo(Responsable::class, 'recibido_por_responsable_id', 'id');
    }

    public function registrosEntregaUniformeDetalle(): HasMany
    {
        return $this->hasMany(EntregaUniformeDetalle::class, 'entrega_uniforme_id', 'id');
    }
}
