<?php

namespace App\Http\Controllers\Direccion;

use App\Http\Controllers\Controller;
use App\Models\CicloEscolar;
use App\Models\DisponibilidadDocente;
use App\Models\Docente;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DocenteDisponibilidadController extends Controller
{
    public function index(Request $request, Docente $docente): View
    {
        $docente->load('personal.persona');
        $ciclos = CicloEscolar::orderByDesc('fecha_inicio')->get();
        $cicloId = $request->integer('ciclo_id') ?: (CicloEscolar::vigente()?->id ?? $ciclos->first()?->id);
        $ciclo = $ciclos->firstWhere('id', $cicloId);
        abort_if($cicloId && !$ciclo, 404);

        $franjas = $ciclo ? DisponibilidadDocente::where('docente_id', $docente->id)
            ->where('ciclo_escolar_id', $ciclo->id)
            ->orderBy('dia_semana')->orderBy('hora_inicio')->get() : collect();

        $dias = [1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves',
            5 => 'Viernes', 6 => 'Sábado', 7 => 'Domingo'];

        return view('direccion.docentes.disponibilidad.index',
            compact('docente', 'ciclos', 'ciclo', 'franjas', 'dias'));
    }

    public function store(Request $request, Docente $docente): RedirectResponse
    {
        $datos = $request->validate([
            'ciclo_escolar_id' => ['required', 'integer', 'exists:ciclos_escolares,id'],
            'dia_semana' => ['required', 'integer', 'between:1,7'],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'hora_fin' => ['required', 'date_format:H:i', 'after:hora_inicio'],
            'disponible' => ['required', 'boolean'],
        ]);

        $this->verificarEditable($docente, $datos['ciclo_escolar_id']);
        DB::transaction(function () use ($docente, $datos) {
            $this->verificarSolapamiento($docente->id, $datos);
            DisponibilidadDocente::create($datos + ['docente_id' => $docente->id]);
        }, 3);

        return $this->volver($docente, $datos['ciclo_escolar_id'])
            ->with('success', 'Franja horaria registrada correctamente.');
    }

    public function update(Request $request, Docente $docente, DisponibilidadDocente $disponibilidad): RedirectResponse
    {
        $this->verificarPertenencia($docente, $disponibilidad);
        $datos = $request->validate([
            'dia_semana' => ['required', 'integer', 'between:1,7'],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'hora_fin' => ['required', 'date_format:H:i', 'after:hora_inicio'],
            'disponible' => ['required', 'boolean'],
        ]);

        $this->verificarEditable($docente, $disponibilidad->ciclo_escolar_id);
        $datos['ciclo_escolar_id'] = $disponibilidad->ciclo_escolar_id;

        DB::transaction(function () use ($docente, $datos, $disponibilidad) {
            $this->verificarSolapamiento($docente->id, $datos, $disponibilidad->id);
            $disponibilidad->update($datos);
        }, 3);

        return $this->volver($docente, $disponibilidad->ciclo_escolar_id)
            ->with('success', 'Franja horaria actualizada.');
    }

    public function destroy(Docente $docente, DisponibilidadDocente $disponibilidad): RedirectResponse
    {
        $this->verificarPertenencia($docente, $disponibilidad);
        $this->verificarEditable($docente, $disponibilidad->ciclo_escolar_id);
        $cicloId = $disponibilidad->ciclo_escolar_id;
        $disponibilidad->delete();

        return $this->volver($docente, $cicloId)
            ->with('success', 'Franja horaria eliminada.');
    }

    private function verificarPertenencia(Docente $docente, DisponibilidadDocente $disponibilidad): void
    {
        abort_unless((int) $disponibilidad->docente_id === (int) $docente->id, 404);
    }

    private function verificarEditable(Docente $docente, int $cicloId): void
    {
        $ciclo = CicloEscolar::findOrFail($cicloId);
        if ($ciclo->estado === 'CERRADO') {
            throw ValidationException::withMessages([
                'ciclo_escolar_id' => 'No se puede modificar la disponibilidad de un ciclo cerrado.',
            ]);
        }

        $docente->loadMissing('personal');
        if (!$docente->activo || !$docente->personal?->activo) {
            throw ValidationException::withMessages([
                'docente' => 'Solo se puede configurar la disponibilidad de docentes activos.',
            ]);
        }
    }

    private function verificarSolapamiento(int $docenteId, array $datos, ?int $excepto = null): void
    {
        $consulta = DisponibilidadDocente::where('docente_id', $docenteId)
            ->where('ciclo_escolar_id', $datos['ciclo_escolar_id'])
            ->where('dia_semana', $datos['dia_semana'])
            ->whereTime('hora_inicio', '<', $datos['hora_fin'])
            ->whereTime('hora_fin', '>', $datos['hora_inicio']);

        if ($excepto !== null) {
            $consulta->whereKeyNot($excepto);
        }
        if ($consulta->exists()) {
            throw ValidationException::withMessages([
                'hora_inicio' => 'La franja se superpone con otra registrada para el mismo día.',
            ]);
        }
    }

    private function volver(Docente $docente, int $cicloId): RedirectResponse
    {
        return redirect()->route('direccion.docentes.disponibilidad.index',
            ['docente' => $docente->id, 'ciclo_id' => $cicloId]);
    }
}
