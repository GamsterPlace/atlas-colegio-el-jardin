<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UsuarioRol extends Pivot
{
    protected $table = 'usuario_roles';

    public $incrementing = false;

    protected $primaryKey = null;

    public $timestamps = false;

    // Clave primaria compuesta: (usuario_id, rol_id). Eloquent no admite claves compuestas nativamente.
    // Utilizar como pivot mediante belongsToMany; no actualizar/eliminar por save() de este modelo.

    protected $fillable = [
        'usuario_id',
        'rol_id',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id');
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'rol_id', 'id');
    }
}
