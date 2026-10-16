<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BloqueHorario extends Model
{
    protected $table = 'bloques_horarios';

    public $timestamps = false;

    protected $fillable = [
        'jornada_id',
        'nombre',
        'orden',
        'hora_inicio',
        'hora_fin',
        'tipo',
    ];

    protected function casts(): array
    {
        return [
            'orden' => 'integer',
        ];
    }

    public function jornada(): BelongsTo
    {
        return $this->belongsTo(Jornada::class, 'jornada_id', 'id');
    }

    public function registrosHorarioDetalle(): HasMany
    {
        return $this->hasMany(HorarioDetalle::class, 'bloque_horario_id', 'id');
    }
}
