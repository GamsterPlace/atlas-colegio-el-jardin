<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EventoAgendaResponsable extends Model
{
    protected $table = 'eventos_agenda_responsables';

    public $timestamps = false;

    protected $fillable = [
        'evento_agenda_id',
        'usuario_id',
    ];

    public function evento_agenda(): BelongsTo
    {
        return $this->belongsTo(EventoAgenda::class, 'evento_agenda_id', 'id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id');
    }
}
