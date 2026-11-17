<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreguntaFrecuente extends Model
{
    protected $table = 'preguntas_frecuentes';

    protected $fillable = [
        'pregunta', 'respuesta', 'categoria', 'orden',
        'publicada', 'destacada', 'creado_por', 'actualizado_por',
    ];

    protected $casts = [
        'publicada' => 'boolean',
        'destacada' => 'boolean',
        'orden' => 'integer',
    ];

    public function scopePublicadas($query)
    {
        return $query->where('publicada', true);
    }
}
