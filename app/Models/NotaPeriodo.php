<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class NotaPeriodo extends Model
{
    protected $table = 'notas_periodo';

    public $timestamps = false;

    protected $fillable = [
        'inscripcion_id',
        'carga_academica_id',
        'periodo_academico_id',
        'nota',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'nota' => 'decimal:2',
        ];
    }

    public function inscripcion(): BelongsTo
    {
        return $this->belongsTo(Inscripcion::class, 'inscripcion_id', 'id');
    }

    public function carga_academica(): BelongsTo
    {
        return $this->belongsTo(CargaAcademica::class, 'carga_academica_id', 'id');
    }

    public function periodo_academico(): BelongsTo
    {
        return $this->belongsTo(PeriodoAcademico::class, 'periodo_academico_id', 'id');
    }

    public function registrosHistorialCambioNota(): HasMany
    {
        return $this->hasMany(HistorialCambioNota::class, 'nota_periodo_id', 'id');
    }
}
