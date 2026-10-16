<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CierreAcademico extends Model
{
    protected $table = 'cierres_academicos';

    public $timestamps = false;

    protected $fillable = [
        'carga_academica_id',
        'periodo_academico_id',
        'cerrado_por',
        'cerrado_at',
        'motivo',
    ];

    protected function casts(): array
    {
        return [
            'cerrado_at' => 'datetime',
        ];
    }

    public function carga_academica(): BelongsTo
    {
        return $this->belongsTo(CargaAcademica::class, 'carga_academica_id', 'id');
    }

    public function periodo_academico(): BelongsTo
    {
        return $this->belongsTo(PeriodoAcademico::class, 'periodo_academico_id', 'id');
    }

    public function cerrado_por(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'cerrado_por', 'id');
    }
}
