<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ContactoEmergencia extends Model
{
    protected $table = 'contactos_emergencia';

    public $timestamps = false;

    protected $fillable = [
        'estudiante_id',
        'nombre',
        'parentesco',
        'telefono',
        'prioridad',
    ];

    protected function casts(): array
    {
        return [
            'prioridad' => 'integer',
        ];
    }

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id', 'id');
    }
}
