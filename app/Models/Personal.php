<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Personal extends Model
{
    protected $table = 'personal';

    public $timestamps = false;

    protected $fillable = [
        'persona_id',
        'codigo_empleado',
        'fecha_ingreso',
        'cargo',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'fecha_ingreso' => 'date',
            'activo' => 'boolean',
        ];
    }

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'persona_id', 'id');
    }

    public function registrosDocente(): HasOne
    {
        return $this->hasOne(Docente::class, 'personal_id', 'id');
    }

    public function registrosAsistenciaPersonal(): HasMany
    {
        return $this->hasMany(AsistenciaPersonal::class, 'personal_id', 'id');
    }
}
