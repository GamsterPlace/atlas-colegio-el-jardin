<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AsistenciaPersonal extends Model
{
    protected $table = 'asistencia_personal';

    public $timestamps = false;

    protected $fillable = [
        'personal_id',
        'fecha',
        'estado',
        'hora_entrada',
        'hora_salida',
        'registrado_por',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
        ];
    }

    public function personal(): BelongsTo
    {
        return $this->belongsTo(Personal::class, 'personal_id', 'id');
    }

    public function registrado_por(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'registrado_por', 'id');
    }

    public function registrosJustificacionPersonal(): HasMany
    {
        return $this->hasMany(JustificacionPersonal::class, 'asistencia_personal_id', 'id');
    }
}
