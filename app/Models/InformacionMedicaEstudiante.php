<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InformacionMedicaEstudiante extends Model
{
    protected $table = 'informacion_medica_estudiantes';

    public $timestamps = false;

    protected $fillable = [
        'estudiante_id',
        'tipo_sangre',
        'alergias',
        'condiciones_relevantes',
        'medicamentos',
        'indicaciones_emergencia',
        'seguro_medico',
    ];

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id', 'id');
    }
}
