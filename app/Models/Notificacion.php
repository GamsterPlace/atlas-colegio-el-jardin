<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Notificacion extends Model
{
    protected $table = 'notificaciones';

    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'titulo',
        'mensaje',
        'tipo',
        'enlace',
        'leida_at',
    ];

    protected function casts(): array
    {
        return [
            'leida_at' => 'datetime',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id');
    }
}
