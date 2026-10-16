<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Seccion extends Model
{
    protected $table = 'secciones';

    public $timestamps = false;

    protected $fillable = [
        'ciclo_escolar_id',
        'grado_id',
        'jornada_id',
        'nombre',
        'cupo',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'cupo' => 'integer',
            'activo' => 'boolean',
        ];
    }

    public function ciclo_escolar(): BelongsTo
    {
        return $this->belongsTo(CicloEscolar::class, 'ciclo_escolar_id', 'id');
    }

    public function grado(): BelongsTo
    {
        return $this->belongsTo(Grado::class, 'grado_id', 'id');
    }

    public function jornada(): BelongsTo
    {
        return $this->belongsTo(Jornada::class, 'jornada_id', 'id');
    }

    public function registrosInscripcion(): HasMany
    {
        return $this->hasMany(Inscripcion::class, 'seccion_id', 'id');
    }

    public function registrosHistorialSeccionSeccion_anterior(): HasMany
    {
        return $this->hasMany(HistorialSeccion::class, 'seccion_anterior_id', 'id');
    }

    public function registrosHistorialSeccionSeccion_nueva(): HasMany
    {
        return $this->hasMany(HistorialSeccion::class, 'seccion_nueva_id', 'id');
    }

    public function registrosDocenteGuia(): HasMany
    {
        return $this->hasMany(DocenteGuia::class, 'seccion_id', 'id');
    }

    public function registrosCargaAcademica(): HasMany
    {
        return $this->hasMany(CargaAcademica::class, 'seccion_id', 'id');
    }

    public function registrosHorario(): HasMany
    {
        return $this->hasMany(Horario::class, 'seccion_id', 'id');
    }

    public function registrosRestriccionHorario(): HasMany
    {
        return $this->hasMany(RestriccionHorario::class, 'seccion_id', 'id');
    }

    public function registrosEventoAgendaDestinatario(): HasMany
    {
        return $this->hasMany(EventoAgendaDestinatario::class, 'seccion_id', 'id');
    }

    public function registrosComunicadoDestinatario(): HasMany
    {
        return $this->hasMany(ComunicadoDestinatario::class, 'seccion_id', 'id');
    }
}
