<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Horario extends Model
{
    protected $table = 'horarios';

    public $timestamps = false;

    protected $fillable = [
        'seccion_id',
        'version',
        'origen',
        'estado',
        'generado_por',
        'aprobado_por',
        'aprobado_at',
    ];

    protected function casts(): array
    {
        return [
            'version' => 'integer',
            'aprobado_at' => 'datetime',
        ];
    }

    public function seccion(): BelongsTo
    {
        return $this->belongsTo(Seccion::class, 'seccion_id', 'id');
    }

    public function generado_por(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'generado_por', 'id');
    }

    public function aprobado_por(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'aprobado_por', 'id');
    }

    public function registrosHorarioDetalle(): HasMany
    {
        return $this->hasMany(HorarioDetalle::class, 'horario_id', 'id');
    }
}
