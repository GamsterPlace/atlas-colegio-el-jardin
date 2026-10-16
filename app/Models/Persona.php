<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Persona extends Model
{
    protected $table = 'personas';

    public $timestamps = false;

    protected $fillable = [
        'nombres',
        'apellidos',
        'cui',
        'fecha_nacimiento',
        'sexo',
        'telefono',
        'correo',
        'direccion',
    ];

    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
        ];
    }

    public function registrosUsuario(): HasOne
    {
        return $this->hasOne(Usuario::class, 'persona_id', 'id');
    }

    public function registrosResponsable(): HasOne
    {
        return $this->hasOne(Responsable::class, 'persona_id', 'id');
    }

    public function registrosPersonal(): HasOne
    {
        return $this->hasOne(Personal::class, 'persona_id', 'id');
    }

    public function registrosPersonaAutorizadaRetiro(): HasMany
    {
        return $this->hasMany(PersonaAutorizadaRetiro::class, 'persona_id', 'id');
    }
}
