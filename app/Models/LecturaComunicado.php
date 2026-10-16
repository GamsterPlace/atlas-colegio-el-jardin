<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LecturaComunicado extends Model
{
    protected $table = 'lecturas_comunicados';

    public $timestamps = false;

    protected $fillable = [
        'comunicado_id',
        'usuario_id',
        'leido_at',
    ];

    protected function casts(): array
    {
        return [
            'leido_at' => 'datetime',
        ];
    }

    public function comunicado(): BelongsTo
    {
        return $this->belongsTo(Comunicado::class, 'comunicado_id', 'id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id');
    }
}
