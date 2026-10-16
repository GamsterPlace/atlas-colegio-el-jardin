<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EstudianteResponsable extends Model
{
    protected $table = 'estudiante_responsables';

    public $timestamps = false;

    protected $fillable = [
        'estudiante_id',
        'responsable_id',
        'parentesco',
        'es_responsable_financiero',
        'es_contacto_principal',
        'autorizado_recoger',
        'fecha_inicio',
        'fecha_fin',
    ];

    protected function casts(): array
    {
        return [
            'es_responsable_financiero' => 'boolean',
            'es_contacto_principal' => 'boolean',
            'autorizado_recoger' => 'boolean',
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
        ];
    }

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id', 'id');
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(Responsable::class, 'responsable_id', 'id');
    }
}
