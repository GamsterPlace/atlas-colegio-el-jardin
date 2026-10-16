<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ObservacionAcademica extends Model
{
    protected $table = 'observaciones_academicas';

    public $timestamps = false;

    protected $fillable = [
        'inscripcion_id',
        'carga_academica_id',
        'periodo_academico_id',
        'observacion',
        'registrado_por',
    ];

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

    public function registrado_por(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'registrado_por', 'id');
    }
}
