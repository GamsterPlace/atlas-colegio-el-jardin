<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RestriccionHorario extends Model
{
    protected $table = 'restricciones_horarios';

    public $timestamps = false;

    protected $fillable = [
        'ciclo_escolar_id',
        'seccion_id',
        'docente_id',
        'tipo',
        'parametros',
        'prioridad',
        'obligatoria',
    ];

    protected function casts(): array
    {
        return [
            'parametros' => 'array',
            'prioridad' => 'integer',
            'obligatoria' => 'boolean',
        ];
    }

    public function ciclo_escolar(): BelongsTo
    {
        return $this->belongsTo(CicloEscolar::class, 'ciclo_escolar_id', 'id');
    }

    public function seccion(): BelongsTo
    {
        return $this->belongsTo(Seccion::class, 'seccion_id', 'id');
    }

    public function docente(): BelongsTo
    {
        return $this->belongsTo(Docente::class, 'docente_id', 'id');
    }
}
