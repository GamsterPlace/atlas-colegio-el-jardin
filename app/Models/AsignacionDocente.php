<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AsignacionDocente extends Model
{
    protected $table = 'asignaciones_docentes';

    public $timestamps = false;

    protected $fillable = [
        'docente_id',
        'carga_academica_id',
        'fecha_inicio',
        'fecha_fin',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
            'activo' => 'boolean',
        ];
    }

    public function docente(): BelongsTo
    {
        return $this->belongsTo(Docente::class, 'docente_id', 'id');
    }

    public function carga_academica(): BelongsTo
    {
        return $this->belongsTo(CargaAcademica::class, 'carga_academica_id', 'id');
    }
}
