<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Responsable extends Model
{
    protected $table = 'responsables';

    public $timestamps = false;

    protected $fillable = [
        'persona_id',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'persona_id', 'id');
    }

    public function registrosEstudianteResponsable(): HasMany
    {
        return $this->hasMany(EstudianteResponsable::class, 'responsable_id', 'id');
    }

    public function registrosInscripcion(): HasMany
    {
        return $this->hasMany(Inscripcion::class, 'responsable_financiero_id', 'id');
    }

    public function registrosEntregaUniforme(): HasMany
    {
        return $this->hasMany(EntregaUniforme::class, 'recibido_por_responsable_id', 'id');
    }
}
