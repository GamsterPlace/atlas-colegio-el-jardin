<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Jornada extends Model
{
    protected $table = 'jornadas';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'hora_inicio',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function registrosSeccion(): HasMany
    {
        return $this->hasMany(Seccion::class, 'jornada_id', 'id');
    }

    public function registrosBloqueHorario(): HasMany
    {
        return $this->hasMany(BloqueHorario::class, 'jornada_id', 'id');
    }
}
