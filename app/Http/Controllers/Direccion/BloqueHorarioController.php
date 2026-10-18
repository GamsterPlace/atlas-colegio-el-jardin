<?php

namespace App\Http\Controllers\Direccion;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class BloqueHorarioController extends Controller
{
    public function index(Request $request): View
    {
        $jornadas = DB::table('jornadas')->orderBy('nombre')->get();
        $jornadaId = $request->integer('jornada_id') ?: ($jornadas->firstWhere('activo', 1)?->id ?? $jornadas->first()?->id);
        if ($jornadaId && !$jornadas->contains('id', $jornadaId)) {
            abort(404);
        }

        $bloques = $jornadaId
            ? DB::table('bloques_horarios')->where('jornada_id', $jornadaId)->orderBy('orden')->get()
            : collect();

        return view('direccion.horarios.bloques.index', compact('jornadas', 'jornadaId', 'bloques'));
    }

    public function create(Request $request): View
    {
        $jornadas = DB::table('jornadas')->where('activo', true)->orderBy('nombre')->get();
        $jornadaId = $request->integer('jornada_id') ?: $jornadas->first()?->id;
        $bloque = null;
        $ordenSugerido = $jornadaId ? ((int) DB::table('bloques_horarios')->where('jornada_id', $jornadaId)->max('orden') + 1) : 1;

        return view('direccion.horarios.bloques.formulario', compact('jornadas', 'jornadaId', 'bloque', 'ordenSugerido'));
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $this->validar($request);
        $this->verificarIntervalo($datos);
        DB::table('bloques_horarios')->insert([...$datos, 'created_at' => now(), 'updated_at' => now()]);

        return redirect()->route('direccion.horarios.bloques.index', ['jornada_id' => $datos['jornada_id']])
            ->with('success', 'Bloque horario registrado correctamente.');
    }

    public function edit(int $bloque): View
    {
        $bloque = DB::table('bloques_horarios')->find($bloque);
        abort_unless($bloque, 404);
        $jornadas = DB::table('jornadas')->where('activo', true)->orWhere('id', $bloque->jornada_id)->orderBy('nombre')->get();
        $jornadaId = $bloque->jornada_id;
        $ordenSugerido = $bloque->orden;

        return view('direccion.horarios.bloques.formulario', compact('jornadas', 'jornadaId', 'bloque', 'ordenSugerido'));
    }

    public function update(Request $request, int $bloque): RedirectResponse
    {
        $actual = DB::table('bloques_horarios')->find($bloque);
        abort_unless($actual, 404);
        $datos = $this->validar($request, $bloque);
        $this->verificarIntervalo($datos, $bloque);
        DB::table('bloques_horarios')->where('id', $bloque)->update([...$datos, 'updated_at' => now()]);

        return redirect()->route('direccion.horarios.bloques.index', ['jornada_id' => $datos['jornada_id']])
            ->with('success', 'Bloque horario actualizado correctamente.');
    }

    private function validar(Request $request, ?int $bloqueId = null): array
    {
        $datos = $request->validate([
            'jornada_id' => ['required', 'integer', Rule::exists('jornadas', 'id')],
            'nombre' => ['required', 'string', 'max:80'],
            'orden' => ['required', 'integer', 'between:1,65535'],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'hora_fin' => ['required', 'date_format:H:i', 'after:hora_inicio'],
            'tipo' => ['required', Rule::in(['CLASE', 'RECESO', 'OTRO'])],
        ]);

        $jornada = DB::table('jornadas')->find($datos['jornada_id']);
        if (!$jornada->activo && (!$bloqueId || DB::table('bloques_horarios')->where('id', $bloqueId)->value('jornada_id') != $jornada->id)) {
            throw ValidationException::withMessages(['jornada_id' => 'La jornada seleccionada está inactiva.']);
        }
        if ($jornada->hora_inicio && $datos['hora_inicio'] < substr($jornada->hora_inicio, 0, 5)) {
            throw ValidationException::withMessages(['hora_inicio' => 'La hora de inicio está fuera de la jornada.']);
        }
        if ($jornada->hora_fin && $datos['hora_fin'] > substr($jornada->hora_fin, 0, 5)) {
            throw ValidationException::withMessages(['hora_fin' => 'La hora de finalización está fuera de la jornada.']);
        }

        $ordenOcupado = DB::table('bloques_horarios')
            ->where('jornada_id', $datos['jornada_id'])->where('orden', $datos['orden'])
            ->when($bloqueId, fn ($q) => $q->where('id', '!=', $bloqueId))->exists();
        if ($ordenOcupado) {
            throw ValidationException::withMessages(['orden' => 'Este número de orden ya está asignado en la jornada.']);
        }

        return $datos;
    }

    private function verificarIntervalo(array $datos, ?int $bloqueId = null): void
    {
        $existeCruce = DB::table('bloques_horarios')
            ->where('jornada_id', $datos['jornada_id'])
            ->where('hora_inicio', '<', $datos['hora_fin'])
            ->where('hora_fin', '>', $datos['hora_inicio'])
            ->when($bloqueId, fn ($q) => $q->where('id', '!=', $bloqueId))
            ->exists();

        if ($existeCruce) {
            throw ValidationException::withMessages(['hora_inicio' => 'El intervalo se superpone con otro bloque de la misma jornada.']);
        }
    }
}
