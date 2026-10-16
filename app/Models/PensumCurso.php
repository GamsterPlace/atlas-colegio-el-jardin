<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PensumCurso extends Model
{
    protected $table = 'pensum_cursos';

    public $timestamps = false;

    protected $fillable = [
        'pensum_id',
        'grado_id',
        'curso_id',
        'horas_semanales',
        'orden',
    ];

    protected function casts(): array
    {
        return [
            'horas_semanales' => 'integer',
            'orden' => 'integer',
        ];
    }

    public function pensum(): BelongsTo
    {
        return $this->belongsTo(PensumEstudio::class, 'pensum_id', 'id');
    }

    public function grado(): BelongsTo
    {
        return $this->belongsTo(Grado::class, 'grado_id', 'id');
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class, 'curso_id', 'id');
    }

    public function registrosCargaAcademica(): HasMany
    {
        return $this->hasMany(CargaAcademica::class, 'pensum_curso_id', 'id');
    }
}
