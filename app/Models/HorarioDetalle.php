<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class HorarioDetalle extends Model
{
    protected $table = 'horario_detalles';

    public $timestamps = false;

    protected $fillable = [
        'horario_id',
        'bloque_horario_id',
        'dia_semana',
        'carga_academica_id',
        'docente_id',
        'aula',
    ];

    protected function casts(): array
    {
        return [
            'dia_semana' => 'integer',
        ];
    }

    public function horario(): BelongsTo
    {
        return $this->belongsTo(Horario::class, 'horario_id', 'id');
    }

    public function bloque_horario(): BelongsTo
    {
        return $this->belongsTo(BloqueHorario::class, 'bloque_horario_id', 'id');
    }

    public function carga_academica(): BelongsTo
    {
        return $this->belongsTo(CargaAcademica::class, 'carga_academica_id', 'id');
    }

    public function docente(): BelongsTo
    {
        return $this->belongsTo(Docente::class, 'docente_id', 'id');
    }
}
