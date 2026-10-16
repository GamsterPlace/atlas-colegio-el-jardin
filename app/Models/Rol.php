<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Rol extends Model
{
    protected $table = 'roles';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function registrosUsuarioRol(): HasMany
    {
        return $this->hasMany(UsuarioRol::class, 'rol_id', 'id');
    }

    public function registrosEventoAgendaDestinatario(): HasMany
    {
        return $this->hasMany(EventoAgendaDestinatario::class, 'rol_id', 'id');
    }

    public function registrosComunicadoDestinatario(): HasMany
    {
        return $this->hasMany(ComunicadoDestinatario::class, 'rol_id', 'id');
    }

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(Usuario::class, 'usuario_roles', 'rol_id', 'usuario_id');
    }
}
