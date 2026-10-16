<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';

    public $timestamps = false;

    protected $fillable = [
        'persona_id',
        'nombre_usuario',
        'email',
        'password',
        'debe_cambiar_password',
        'activo',
        'ultimo_acceso_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'debe_cambiar_password' => 'boolean',
            'ultimo_acceso_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'persona_id', 'id');
    }

    public function registrosUsuarioRol(): HasMany
    {
        return $this->hasMany(UsuarioRol::class, 'usuario_id', 'id');
    }

    public function registrosBitacoraAuditoria(): HasMany
    {
        return $this->hasMany(BitacoraAuditoria::class, 'usuario_id', 'id');
    }

    public function registrosSesionUsuario(): HasMany
    {
        return $this->hasMany(SesionUsuario::class, 'usuario_id', 'id');
    }

    public function registrosRestablecimientoPassword(): HasMany
    {
        return $this->hasMany(RestablecimientoPassword::class, 'usuario_id', 'id');
    }

    public function registrosConfiguracionSistema(): HasMany
    {
        return $this->hasMany(ConfiguracionSistema::class, 'actualizado_por', 'id');
    }

    public function registrosDocumentoEstudiante(): HasMany
    {
        return $this->hasMany(DocumentoEstudiante::class, 'subido_por', 'id');
    }

    public function registrosHistorialSeccion(): HasMany
    {
        return $this->hasMany(HistorialSeccion::class, 'registrado_por', 'id');
    }

    public function registrosRetiroEstudiante(): HasMany
    {
        return $this->hasMany(RetiroEstudiante::class, 'registrado_por', 'id');
    }

    public function registrosAsistenciaPersonal(): HasMany
    {
        return $this->hasMany(AsistenciaPersonal::class, 'registrado_por', 'id');
    }

    public function registrosJustificacionPersonal(): HasMany
    {
        return $this->hasMany(JustificacionPersonal::class, 'aprobado_por', 'id');
    }

    public function registrosCalificacion(): HasMany
    {
        return $this->hasMany(Calificacion::class, 'registrado_por', 'id');
    }

    public function registrosObservacionAcademica(): HasMany
    {
        return $this->hasMany(ObservacionAcademica::class, 'registrado_por', 'id');
    }

    public function registrosCierreAcademico(): HasMany
    {
        return $this->hasMany(CierreAcademico::class, 'cerrado_por', 'id');
    }

    public function registrosHistorialCambioNota(): HasMany
    {
        return $this->hasMany(HistorialCambioNota::class, 'usuario_id', 'id');
    }

    public function registrosAsistenciaEstudiante(): HasMany
    {
        return $this->hasMany(AsistenciaEstudiante::class, 'registrado_por', 'id');
    }

    public function registrosJustificacionAsistenciaRegistrado_por(): HasMany
    {
        return $this->hasMany(JustificacionAsistencia::class, 'registrado_por', 'id');
    }

    public function registrosJustificacionAsistenciaAprobado_por(): HasMany
    {
        return $this->hasMany(JustificacionAsistencia::class, 'aprobado_por', 'id');
    }

    public function registrosObservacionAsistencia(): HasMany
    {
        return $this->hasMany(ObservacionAsistencia::class, 'usuario_id', 'id');
    }

    public function registrosHorarioGenerado_por(): HasMany
    {
        return $this->hasMany(Horario::class, 'generado_por', 'id');
    }

    public function registrosHorarioAprobado_por(): HasMany
    {
        return $this->hasMany(Horario::class, 'aprobado_por', 'id');
    }

    public function registrosEjecucionGeneradorHorario(): HasMany
    {
        return $this->hasMany(EjecucionGeneradorHorario::class, 'solicitado_por', 'id');
    }

    public function registrosEventoAgenda(): HasMany
    {
        return $this->hasMany(EventoAgenda::class, 'creado_por', 'id');
    }

    public function registrosEventoAgendaDestinatario(): HasMany
    {
        return $this->hasMany(EventoAgendaDestinatario::class, 'usuario_id', 'id');
    }

    public function registrosEventoAgendaResponsable(): HasMany
    {
        return $this->hasMany(EventoAgendaResponsable::class, 'usuario_id', 'id');
    }

    public function registrosRecordatorioAgenda(): HasMany
    {
        return $this->hasMany(RecordatorioAgenda::class, 'usuario_id', 'id');
    }

    public function registrosTarifaEstudiante(): HasMany
    {
        return $this->hasMany(TarifaEstudiante::class, 'autorizado_por', 'id');
    }

    public function registrosDescuento(): HasMany
    {
        return $this->hasMany(Descuento::class, 'autorizado_por', 'id');
    }

    public function registrosCuentaPorCobrar(): HasMany
    {
        return $this->hasMany(CuentaPorCobrar::class, 'anulada_por', 'id');
    }

    public function registrosTalonarioPago(): HasMany
    {
        return $this->hasMany(TalonarioPago::class, 'emitido_por', 'id');
    }

    public function registrosAutorizacionPagoParcialAutorizado_por(): HasMany
    {
        return $this->hasMany(AutorizacionPagoParcial::class, 'autorizado_por', 'id');
    }

    public function registrosAutorizacionPagoParcialAnulada_por(): HasMany
    {
        return $this->hasMany(AutorizacionPagoParcial::class, 'anulada_por', 'id');
    }

    public function registrosHojaTalonario(): HasMany
    {
        return $this->hasMany(HojaTalonario::class, 'anulada_por', 'id');
    }

    public function registrosReimpresionTalonario(): HasMany
    {
        return $this->hasMany(ReimpresionTalonario::class, 'usuario_id', 'id');
    }

    public function registrosPagoRegistrado_por(): HasMany
    {
        return $this->hasMany(Pago::class, 'registrado_por', 'id');
    }

    public function registrosPagoAnulado_por(): HasMany
    {
        return $this->hasMany(Pago::class, 'anulado_por', 'id');
    }

    public function registrosConfiguracionMora(): HasMany
    {
        return $this->hasMany(ConfiguracionMora::class, 'aprobado_por', 'id');
    }

    public function registrosRecargoMora(): HasMany
    {
        return $this->hasMany(RecargoMora::class, 'anulado_por', 'id');
    }

    public function registrosMovimientoInventario(): HasMany
    {
        return $this->hasMany(MovimientoInventario::class, 'registrado_por', 'id');
    }

    public function registrosEntregaUniforme(): HasMany
    {
        return $this->hasMany(EntregaUniforme::class, 'entregado_por', 'id');
    }

    public function registrosComunicado(): HasMany
    {
        return $this->hasMany(Comunicado::class, 'creado_por', 'id');
    }

    public function registrosComunicadoDestinatario(): HasMany
    {
        return $this->hasMany(ComunicadoDestinatario::class, 'usuario_id', 'id');
    }

    public function registrosLecturaComunicado(): HasMany
    {
        return $this->hasMany(LecturaComunicado::class, 'usuario_id', 'id');
    }

    public function registrosPublicacion(): HasMany
    {
        return $this->hasMany(Publicacion::class, 'autor_id', 'id');
    }

    public function registrosNotificacion(): HasMany
    {
        return $this->hasMany(Notificacion::class, 'usuario_id', 'id');
    }

    public function registrosDocumentoGenerado(): HasMany
    {
        return $this->hasMany(DocumentoGenerado::class, 'generado_por', 'id');
    }

    public function registrosReporteGenerado(): HasMany
    {
        return $this->hasMany(ReporteGenerado::class, 'generado_por', 'id');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Rol::class,
            'usuario_roles',
            'usuario_id',
            'rol_id'
        );
    }
}
