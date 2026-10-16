<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DisponibilidadDocente extends Model
{
    protected $table = 'disponibilidad_docentes';

    public $timestamps = false;

    protected $fillable = [
        'docente_id',
        'ciclo_escolar_id',
        'dia_semana',
        'hora_inicio',
        'hora_fin',
        'disponible',
    ];

    protected function casts(): array
    {
        return [
            'dia_semana' => 'integer',
            'disponible' => 'boolean',
        ];
    }

    public function docente(): BelongsTo
    {
        return $this->belongsTo(Docente::class, 'docente_id', 'id');
    }

    public function ciclo_escolar(): BelongsTo
    {
        return $this->belongsTo(CicloEscolar::class, 'ciclo_escolar_id', 'id');
    }
}
