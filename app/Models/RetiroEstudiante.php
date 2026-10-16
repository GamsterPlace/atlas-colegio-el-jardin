<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RetiroEstudiante extends Model
{
    protected $table = 'retiros_estudiantes';

    public $timestamps = false;

    protected $fillable = [
        'inscripcion_id',
        'fecha_retiro',
        'motivo',
        'registrado_por',
    ];

    protected function casts(): array
    {
        return [
            'fecha_retiro' => 'date',
        ];
    }

    public function inscripcion(): BelongsTo
    {
        return $this->belongsTo(Inscripcion::class, 'inscripcion_id', 'id');
    }

    public function registrado_por(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'registrado_por', 'id');
    }
}
