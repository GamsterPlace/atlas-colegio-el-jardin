<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Comunicado extends Model
{
    protected $table = 'comunicados';

    public $timestamps = false;

    protected $fillable = [
        'titulo',
        'contenido',
        'estado',
        'publicado_at',
        'creado_por',
    ];

    protected function casts(): array
    {
        return [
            'publicado_at' => 'datetime',
        ];
    }

    public function creado_por(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'creado_por', 'id');
    }

    public function registrosComunicadoDestinatario(): HasMany
    {
        return $this->hasMany(ComunicadoDestinatario::class, 'comunicado_id', 'id');
    }

    public function registrosLecturaComunicado(): HasMany
    {
        return $this->hasMany(LecturaComunicado::class, 'comunicado_id', 'id');
    }
}
