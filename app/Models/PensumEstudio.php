<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PensumEstudio extends Model
{
    protected $table = 'pensum_estudios';

    public $timestamps = false;

    protected $fillable = [
        'nivel_id',
        'nombre',
        'version',
        'vigente_desde',
        'vigente_hasta',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'vigente_desde' => 'date',
            'vigente_hasta' => 'date',
            'activo' => 'boolean',
        ];
    }

    public function nivel(): BelongsTo
    {
        return $this->belongsTo(Nivel::class, 'nivel_id', 'id');
    }

    public function registrosPensumCurso(): HasMany
    {
        return $this->hasMany(PensumCurso::class, 'pensum_id', 'id');
    }
}
