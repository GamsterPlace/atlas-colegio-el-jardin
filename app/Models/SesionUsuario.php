<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SesionUsuario extends Model
{
    protected $table = 'sesiones_usuario';

    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'identificador',
        'ip',
        'agente_usuario',
        'ultimo_acceso_at',
        'revocada_at',
    ];

    protected function casts(): array
    {
        return [
            'ultimo_acceso_at' => 'datetime',
            'revocada_at' => 'datetime',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id');
    }
}
