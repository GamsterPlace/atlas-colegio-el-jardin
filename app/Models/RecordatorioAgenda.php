<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RecordatorioAgenda extends Model
{
    protected $table = 'recordatorios_agenda';

    public $timestamps = false;

    protected $fillable = [
        'evento_agenda_id',
        'usuario_id',
        'recordar_at',
        'enviado_at',
    ];

    protected function casts(): array
    {
        return [
            'recordar_at' => 'datetime',
            'enviado_at' => 'datetime',
        ];
    }

    public function evento_agenda(): BelongsTo
    {
        return $this->belongsTo(EventoAgenda::class, 'evento_agenda_id', 'id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id');
    }
}
