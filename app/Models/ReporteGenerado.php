<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ReporteGenerado extends Model
{
    protected $table = 'reportes_generados';

    public $timestamps = false;

    protected $fillable = [
        'tipo',
        'parametros',
        'ruta_privada',
        'generado_por',
        'fecha_generacion',
    ];

    protected function casts(): array
    {
        return [
            'parametros' => 'array',
            'fecha_generacion' => 'datetime',
        ];
    }

    public function generado_por(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'generado_por', 'id');
    }
}
