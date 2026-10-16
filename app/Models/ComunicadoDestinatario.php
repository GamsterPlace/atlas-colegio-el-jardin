<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ComunicadoDestinatario extends Model
{
    protected $table = 'comunicado_destinatarios';

    public $timestamps = false;

    protected $fillable = [
        'comunicado_id',
        'rol_id',
        'seccion_id',
        'usuario_id',
    ];

    public function comunicado(): BelongsTo
    {
        return $this->belongsTo(Comunicado::class, 'comunicado_id', 'id');
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'rol_id', 'id');
    }

    public function seccion(): BelongsTo
    {
        return $this->belongsTo(Seccion::class, 'seccion_id', 'id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id');
    }
}
