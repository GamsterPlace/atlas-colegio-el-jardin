<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EventoAgenda extends Model
{
    protected $table = 'eventos_agenda';

    public $timestamps = false;

    protected $fillable = [
        'ciclo_escolar_id',
        'titulo',
        'descripcion',
        'inicio',
        'fin',
        'ubicacion',
        'visibilidad',
        'estado',
        'creado_por',
    ];

    protected function casts(): array
    {
        return [
            'inicio' => 'datetime',
            'fin' => 'datetime',
        ];
    }

    public function ciclo_escolar(): BelongsTo
    {
        return $this->belongsTo(CicloEscolar::class, 'ciclo_escolar_id', 'id');
    }

    public function creado_por(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'creado_por', 'id');
    }

    public function registrosEventoAgendaDestinatario(): HasMany
    {
        return $this->hasMany(EventoAgendaDestinatario::class, 'evento_agenda_id', 'id');
    }

    public function registrosEventoAgendaResponsable(): HasMany
    {
        return $this->hasMany(EventoAgendaResponsable::class, 'evento_agenda_id', 'id');
    }

    public function registrosRecordatorioAgenda(): HasMany
    {
        return $this->hasMany(RecordatorioAgenda::class, 'evento_agenda_id', 'id');
    }
}
