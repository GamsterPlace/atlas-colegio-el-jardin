<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InformacionComplementariaEstudiante extends Model
{
    protected $table = 'informacion_complementaria_estudiantes';

    protected $fillable = [
        'estudiante_id',
        'religion',
        'telefono_casa',
        'transporte',
        'numero_hermanos',
        'hermanos_mayores',
        'hermanos_menores',
        'hermanos_inscritos',
    ];

    protected function casts(): array
    {
        return [
            'numero_hermanos' => 'integer',
            'hermanos_mayores' => 'integer',
            'hermanos_menores' => 'integer',
        ];
    }

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id', 'id');
    }
}
