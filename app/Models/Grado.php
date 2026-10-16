<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Grado extends Model
{
    protected $table = 'grados';

    public $timestamps = false;

    protected $fillable = [
        'nivel_id',
        'nombre',
        'orden',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'orden' => 'integer',
            'activo' => 'boolean',
        ];
    }

    public function nivel(): BelongsTo
    {
        return $this->belongsTo(Nivel::class, 'nivel_id', 'id');
    }

    public function registrosSeccion(): HasMany
    {
        return $this->hasMany(Seccion::class, 'grado_id', 'id');
    }

    public function registrosPensumCurso(): HasMany
    {
        return $this->hasMany(PensumCurso::class, 'grado_id', 'id');
    }

    public function registrosEventoAgendaDestinatario(): HasMany
    {
        return $this->hasMany(EventoAgendaDestinatario::class, 'grado_id', 'id');
    }

    public function registrosConfiguracionColegiatura(): HasMany
    {
        return $this->hasMany(ConfiguracionColegiatura::class, 'grado_id', 'id');
    }
}
