<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TalonarioPago extends Model
{
    protected $table = 'talonarios_pago';

    public $timestamps = false;

    protected $fillable = [
        'estudiante_id',
        'ciclo_escolar_id',
        'codigo',
        'fecha_generacion',
        'emitido_por',
    ];

    protected function casts(): array
    {
        return [
            'fecha_generacion' => 'datetime',
        ];
    }

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id', 'id');
    }

    public function ciclo_escolar(): BelongsTo
    {
        return $this->belongsTo(CicloEscolar::class, 'ciclo_escolar_id', 'id');
    }

    public function emitido_por(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'emitido_por', 'id');
    }

    public function registrosHojaTalonario(): HasMany
    {
        return $this->hasMany(HojaTalonario::class, 'talonario_id', 'id');
    }

    public function registrosReimpresionTalonario(): HasMany
    {
        return $this->hasMany(ReimpresionTalonario::class, 'talonario_id', 'id');
    }
}
