<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DatoInstitucion extends Model
{
    protected $table = 'datos_institucion';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'nombre_legal',
        'codigo_establecimiento',
        'nit',
        'direccion',
        'telefono',
        'correo',
        'logo_ruta',
        'cuenta_bancaria_descripcion',
    ];
}
