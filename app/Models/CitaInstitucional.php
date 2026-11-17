<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CitaInstitucional extends Model
{
    protected $table = 'citas_institucionales';
    protected $fillable = ['nombre_visitante', 'telefono', 'correo', 'motivo', 'observaciones', 'fecha', 'hora_inicio', 'hora_fin', 'estado', 'gestionado_por', 'nota_interna'];
    protected function casts(): array
    {
        return ['fecha' => 'date'];
    }
}
