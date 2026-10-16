<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ReimpresionTalonario extends Model
{
    protected $table = 'reimpresiones_talonario';

    public $timestamps = false;

    protected $fillable = [
        'talonario_id',
        'hoja_talonario_id',
        'usuario_id',
        'fecha_reimpresion',
        'ip',
    ];

    protected function casts(): array
    {
        return [
            'fecha_reimpresion' => 'datetime',
        ];
    }

    public function talonario(): BelongsTo
    {
        return $this->belongsTo(TalonarioPago::class, 'talonario_id', 'id');
    }

    public function hoja_talonario(): BelongsTo
    {
        return $this->belongsTo(HojaTalonario::class, 'hoja_talonario_id', 'id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id');
    }
}
