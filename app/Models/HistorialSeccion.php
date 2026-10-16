<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class HistorialSeccion extends Model
{
    protected $table = 'historial_secciones';

    public $timestamps = false;

    protected $fillable = [
        'inscripcion_id',
        'seccion_anterior_id',
        'seccion_nueva_id',
        'fecha_traslado',
        'motivo',
        'registrado_por',
    ];

    protected function casts(): array
    {
        return [
            'fecha_traslado' => 'date',
        ];
    }

    public function inscripcion(): BelongsTo
    {
        return $this->belongsTo(Inscripcion::class, 'inscripcion_id', 'id');
    }

    public function seccion_anterior(): BelongsTo
    {
        return $this->belongsTo(Seccion::class, 'seccion_anterior_id', 'id');
    }

    public function seccion_nueva(): BelongsTo
    {
        return $this->belongsTo(Seccion::class, 'seccion_nueva_id', 'id');
    }

    public function registrado_por(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'registrado_por', 'id');
    }
}
