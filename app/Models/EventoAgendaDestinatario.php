<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EventoAgendaDestinatario extends Model
{
    protected $table = 'eventos_agenda_destinatarios';

    public $timestamps = false;

    protected $fillable = [
        'evento_agenda_id',
        'rol_id',
        'nivel_id',
        'grado_id',
        'seccion_id',
        'usuario_id',
    ];

    public function evento_agenda(): BelongsTo
    {
        return $this->belongsTo(EventoAgenda::class, 'evento_agenda_id', 'id');
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'rol_id', 'id');
    }

    public function nivel(): BelongsTo
    {
        return $this->belongsTo(Nivel::class, 'nivel_id', 'id');
    }

    public function grado(): BelongsTo
    {
        return $this->belongsTo(Grado::class, 'grado_id', 'id');
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
