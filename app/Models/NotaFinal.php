<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class NotaFinal extends Model
{
    protected $table = 'notas_finales';

    public $timestamps = false;

    protected $fillable = [
        'inscripcion_id',
        'carga_academica_id',
        'nota_final',
        'resultado',
    ];

    protected function casts(): array
    {
        return [
            'nota_final' => 'decimal:2',
        ];
    }

    public function inscripcion(): BelongsTo
    {
        return $this->belongsTo(Inscripcion::class, 'inscripcion_id', 'id');
    }

    public function carga_academica(): BelongsTo
    {
        return $this->belongsTo(CargaAcademica::class, 'carga_academica_id', 'id');
    }

    public function registrosHistorialCambioNota(): HasMany
    {
        return $this->hasMany(HistorialCambioNota::class, 'nota_final_id', 'id');
    }
}
