<?php

namespace App\Http\Controllers\Direccion;

use App\Http\Controllers\Controller;
use App\Models\Inscripcion;
use App\Models\TalonarioPago;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class DocumentoInscripcionController extends Controller
{
    public function solicitudMatricula(Inscripcion $inscripcion): Response
    {
        $this->cargarInscripcion($inscripcion);

        return Pdf::loadView('direccion.inscripciones.pdf.solicitud-matricula', [
            'inscripcion' => $inscripcion,
        ])->setPaper('letter')->stream('solicitud-matricula-'.$inscripcion->id.'.pdf');
    }

    public function fichaInscripcion(Inscripcion $inscripcion): Response
    {
        $this->cargarInscripcion($inscripcion);

        $vinculos = $inscripcion->estudiante->registrosEstudianteResponsable
            ->filter(fn ($vinculo) => in_array($vinculo->parentesco, ['PADRE', 'MADRE'], true));

        $claveCache = 'atlas:credenciales-iniciales:'.Auth::id().':'.$inscripcion->id;
        $cifrado = Cache::get($claveCache);
        $iniciales = $cifrado ? json_decode(Crypt::decryptString($cifrado), true) : [];

        $accesos = [];
        foreach ($vinculos as $vinculo) {
            $persona = $vinculo->responsable?->persona;
            if (!$persona) {
                continue;
            }
            $usuario = $persona->registrosUsuario;
            if (!$usuario) {
                continue;
            }
            $accesos[] = [
                'parentesco' => $vinculo->parentesco,
                'nombre' => trim($persona->nombres.' '.$persona->apellidos),
                'usuario' => $usuario->nombre_usuario,
                'password' => $iniciales[$persona->id]['password'] ?? null,
                'activo' => $usuario->activo,
            ];
        }

        $pdf = Pdf::loadView('direccion.inscripciones.pdf.ficha-inscripcion', [
            'inscripcion' => $inscripcion,
            'accesos' => $accesos,
        ])->setPaper('letter');

        $respuesta = $pdf->stream('ficha-inscripcion-'.$inscripcion->id.'.pdf');
        if ($cifrado) {
            Cache::forget($claveCache);
        }
        $respuesta->headers->set('Cache-Control', 'private, no-store, no-cache, max-age=0');
        $respuesta->headers->set('Pragma', 'no-cache');
        return $respuesta;
    }

    public function talonario(Inscripcion $inscripcion): Response
    {
        $this->cargarInscripcion($inscripcion);

        $talonario = TalonarioPago::query()
            ->where('estudiante_id', $inscripcion->estudiante_id)
            ->where('ciclo_escolar_id', $inscripcion->ciclo_escolar_id)
            ->with(['registrosHojaTalonario' => function ($query) {
                $query->orderBy('fecha_vencimiento_impresa')->orderBy('id');
            }])
            ->first();

        if (!$talonario) {
            throw ValidationException::withMessages([
                'talonario' => 'No existe un talonario para esta inscripción.',
            ]);
        }

        return Pdf::loadView('direccion.inscripciones.pdf.talonario', [
            'inscripcion' => $inscripcion,
            'talonario' => $talonario,
            'hojas' => $talonario->registrosHojaTalonario,
        ])->setPaper('letter')->stream('talonario-'.$talonario->codigo.'.pdf');
    }

    private function cargarInscripcion(Inscripcion $inscripcion): void
    {
        abort_unless($inscripcion->estado === 'COMPLETADA', 404);

        $inscripcion->load([
            'estudiante.registrosEstudianteResponsable.responsable.persona.registrosUsuario',
            'ciclo_escolar',
            'seccion.grado',
            'seccion.jornada',
            'responsable_financiero.persona',
        ]);
    }
}
