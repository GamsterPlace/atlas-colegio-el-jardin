<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EjecucionGeneradorHorario extends Model
{
    protected $table = 'ejecuciones_generador_horarios';

    public $timestamps = false;

    protected $fillable = [
        'ciclo_escolar_id',
        'solicitado_por',
        'estado',
        'parametros',
        'resultado',
        'iniciado_at',
        'finalizado_at',
    ];

    protected function casts(): array
    {
        return [
            'parametros' => 'array',
            'resultado' => 'array',
            'iniciado_at' => 'datetime',
            'finalizado_at' => 'datetime',
        ];
    }

    public function ciclo_escolar(): BelongsTo
    {
        return $this->belongsTo(CicloEscolar::class, 'ciclo_escolar_id', 'id');
    }

    public function solicitado_por(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'solicitado_por', 'id');
    }
}
