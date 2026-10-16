<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Nivel extends Model
{
    protected $table = 'niveles';

    public $timestamps = false;

    protected $fillable = [
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

    public function registrosGrado(): HasMany
    {
        return $this->hasMany(Grado::class, 'nivel_id', 'id');
    }

    public function registrosPensumEstudio(): HasMany
    {
        return $this->hasMany(PensumEstudio::class, 'nivel_id', 'id');
    }

    public function registrosEventoAgendaDestinatario(): HasMany
    {
        return $this->hasMany(EventoAgendaDestinatario::class, 'nivel_id', 'id');
    }

    public function registrosConfiguracionColegiatura(): HasMany
    {
        return $this->hasMany(ConfiguracionColegiatura::class, 'nivel_id', 'id');
    }
}
