<?php
namespace App\Http\Controllers\Asistencia;

use App\Http\Controllers\Controller;
use App\Models\AsistenciaEstudiante;
use App\Models\Estudiante;
use App\Models\Inscripcion;
use App\Models\JustificacionAsistencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class RegistroController extends Controller
{
    private const ZONA = 'America/Guatemala';
    private const TOLERANCIA = 10;

    public function index(Request $request)
    {
        $nombre = $request->route()->getName();
        $rol = str_starts_with($nombre, 'direccion.') ? 'direccion' : (str_starts_with($nombre, 'secretaria.') ? 'secretaria' : 'docente');
        $registros = AsistenciaEstudiante::with('inscripcion.estudiante')
            ->whereDate('fecha', now(self::ZONA)->toDateString())->whereNotNull('hora_entrada')
            ->orderByDesc('hora_entrada')->limit(20)->get();
        return view('asistencia.registro', compact('rol', 'registros'));
    }

    public function registrar(Request $request)
    {
        $datos = $request->validate(['modo' => 'required|in:ENTRADA,SALIDA', 'identificador' => 'required|string|max:2048']);
        $carnet = trim($datos['identificador']);
        if (str_starts_with($carnet, 'ATLASQR:')) {
            try {
                $contenido = json_decode(Crypt::decryptString(substr($carnet, 8)), true, 512, JSON_THROW_ON_ERROR);
                if (($contenido['v'] ?? null) !== 1 || !is_string($contenido['carnet'] ?? null)) throw new \RuntimeException();
                $carnet = $contenido['carnet'];
            } catch (\Throwable $e) {
                throw ValidationException::withMessages(['identificador' => 'Código QR inválido.']);
            }
        }
        $estudiante = Estudiante::where('numero_carnet', $carnet)->where('estado', 'ACTIVO')->first();
        if (!$estudiante) throw ValidationException::withMessages(['identificador' => 'Carné no encontrado o estudiante inactivo.']);
        $ahora = now(self::ZONA);
        $resultado = DB::transaction(function () use ($datos, $estudiante, $ahora) {
            $inscripcion = Inscripcion::where('estudiante_id', $estudiante->id)->where('estado', 'COMPLETADA')
                ->whereDate('fecha_inicio', '<=', $ahora->toDateString())
                ->where(fn($q) => $q->whereNull('fecha_fin')->orWhereDate('fecha_fin', '>=', $ahora->toDateString()))
                ->with('seccion.jornada')->lockForUpdate()->first();
            if (!$inscripcion) throw ValidationException::withMessages(['identificador' => 'Sin inscripción vigente.']);
            $registro = AsistenciaEstudiante::where('inscripcion_id', $inscripcion->id)
                ->whereDate('fecha', $ahora->toDateString())->lockForUpdate()->first();
            if ($datos['modo'] === 'ENTRADA') {
                if ($registro?->hora_entrada) throw ValidationException::withMessages(['identificador' => 'Entrada ya registrada.']);
                if ($registro && $registro->estado === 'AUSENTE') throw ValidationException::withMessages(['identificador' => 'Existe ausencia registrada; requiere revisión.']);
                $inicio = $inscripcion->seccion?->jornada?->hora_inicio;
                if (!$inicio) throw ValidationException::withMessages(['identificador' => 'Jornada sin hora de inicio.']);
                $limite = $ahora->copy()->startOfDay()->setTimeFromTimeString($inicio)->addMinutes(self::TOLERANCIA);
                $tarde = $ahora->greaterThan($limite);
                $registro ??= new AsistenciaEstudiante(['inscripcion_id' => $inscripcion->id, 'fecha' => $ahora->toDateString()]);
                $registro->estado = $tarde ? 'TARDE' : 'PRESENTE';
                $registro->hora_entrada = $ahora->format('Y-m-d H:i:s');
                $registro->entrada_tarde = $tarde;
                $registro->registrado_por = auth::id();
                $registro->save();
                return ['tipo' => 'ENTRADA', 'tarde' => $tarde, 'registro_id' => $registro->id, 'estado' => $registro->estado];
            }
            if (!$registro?->hora_entrada) throw ValidationException::withMessages(['identificador' => 'Registre primero la entrada.']);
            if ($registro->hora_salida) throw ValidationException::withMessages(['identificador' => 'Salida ya registrada.']);
            $registro->hora_salida = $ahora->format('Y-m-d H:i:s');
            $registro->salida_registrada_por = auth::id();
            $registro->save();
            return ['tipo' => 'SALIDA', 'tarde' => (bool) $registro->entrada_tarde, 'registro_id' => $registro->id, 'estado' => $registro->estado];
        }, 3);
        return response()->json(['ok' => true, 'estudiante' => $estudiante->nombre_completo, 'hora' => $ahora->format('H:i:s')] + $resultado);
    }

    public function justificar(Request $request)
    {
        $datos = $request->validate(['registro_id' => 'required|integer|exists:asistencia_estudiantes,id', 'motivo' => 'required|string|min:8|max:1000']);
        DB::transaction(function () use ($datos) {
            $registro = AsistenciaEstudiante::lockForUpdate()->findOrFail($datos['registro_id']);
            if (!$registro->entrada_tarde || !$registro->hora_entrada || $registro->fecha->format('Y-m-d') !== now(self::ZONA)->toDateString()) {
                throw ValidationException::withMessages(['motivo' => 'No corresponde a una tardanza de hoy.']);
            }
            if (JustificacionAsistencia::where('asistencia_estudiante_id', $registro->id)->exists()) {
                throw ValidationException::withMessages(['motivo' => 'Ya existe justificación.']);
            }
            JustificacionAsistencia::create([
                'asistencia_estudiante_id' => $registro->id, 'motivo' => $datos['motivo'],
                'registrado_por' => auth::id(), 'aprobado_por' => auth::id(), 'estado' => 'APROBADA',
            ]);
            $registro->estado = 'JUSTIFICADA';
            $registro->save();
        });
        return response()->json(['ok' => true, 'estado' => 'JUSTIFICADA']);
    }

    public function generarQr(Estudiante $estudiante)
    {
        abort_unless($estudiante->estado === 'ACTIVO' && filled($estudiante->numero_carnet), 404);
        return response()->json(['contenido' => 'ATLASQR:' . Crypt::encryptString(json_encode(['v' => 1, 'carnet' => $estudiante->numero_carnet], JSON_THROW_ON_ERROR))]);
    }
}
