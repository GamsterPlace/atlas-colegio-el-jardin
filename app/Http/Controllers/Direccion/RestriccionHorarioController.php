<?php

namespace App\Http\Controllers\Direccion;

use App\Http\Controllers\Controller;
use App\Models\BloqueHorario;
use App\Models\CicloEscolar;
use App\Models\Docente;
use App\Models\RestriccionHorario;
use App\Models\Seccion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RestriccionHorarioController extends Controller
{
    private const TIPOS = [
        'MAX_CONSECUTIVAS_DOCENTE' => 'Máximo de clases consecutivas del docente',
        'MAX_DIARIAS_DOCENTE' => 'Máximo de clases diarias del docente',
        'BLOQUEO_PERIODO' => 'Bloquear período de una sección o docente',
    ];

    public function index(Request $request): View
    {
        $ciclos = CicloEscolar::query()->orderByDesc('fecha_inicio')->get();
        $cicloId = $request->integer('ciclo_escolar_id') ?: $ciclos->first()?->id;
        $ciclo = $ciclos->firstWhere('id', $cicloId);
        $secciones = $ciclo ? Seccion::query()->where('ciclo_escolar_id', $ciclo->id)->get() : collect();
        $docentes = Docente::query()->with('personal.persona')->get();
        $bloques = BloqueHorario::query()->where('tipo', 'CLASE')->orderBy('orden')->get();
        $restricciones = $ciclo ? RestriccionHorario::query()
            ->where('ciclo_escolar_id', $ciclo->id)
            ->with(['seccion', 'docente.personal.persona'])
            ->orderByDesc('obligatoria')->orderByDesc('prioridad')->get() : collect();

        return view('direccion.horarios.restricciones.index', compact(
            'ciclos', 'ciclo', 'secciones', 'docentes', 'bloques', 'restricciones'
        ) + ['tipos' => self::TIPOS]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $this->validar($request);
        $this->validarIntegridad($datos);
        RestriccionHorario::create($datos);
        return $this->volver($datos['ciclo_escolar_id'], 'Restricción registrada correctamente.');
    }

    public function update(Request $request, RestriccionHorario $restriccion): RedirectResponse
    {
        $this->verificarEditable($restriccion->ciclo_escolar_id);
        $datos = $this->validar($request);
        if ((int) $datos['ciclo_escolar_id'] !== (int) $restriccion->ciclo_escolar_id) {
            throw ValidationException::withMessages(['ciclo_escolar_id' => 'No se puede trasladar una restricción a otro ciclo.']);
        }
        $this->validarIntegridad($datos);
        $restriccion->update($datos);
        return $this->volver($datos['ciclo_escolar_id'], 'Restricción actualizada correctamente.');
    }

    public function destroy(RestriccionHorario $restriccion): RedirectResponse
    {
        $cicloId = $restriccion->ciclo_escolar_id;
        $this->verificarEditable($cicloId);
        $restriccion->delete();
        return $this->volver($cicloId, 'Restricción eliminada correctamente.');
    }

    private function validar(Request $request): array
    {
        $datos = $request->validate([
            'ciclo_escolar_id' => ['required', 'integer', 'exists:ciclos_escolares,id'],
            'seccion_id' => ['nullable', 'integer', 'exists:secciones,id'],
            'docente_id' => ['nullable', 'integer', 'exists:docentes,id'],
            'tipo' => ['required', Rule::in(array_keys(self::TIPOS))],
            'maximo' => ['nullable', 'integer', 'between:1,20'],
            'dia_semana' => ['nullable', 'integer', 'between:1,7'],
            'bloque_horario_id' => ['nullable', 'integer', 'exists:bloques_horarios,id'],
            'prioridad' => ['required', 'integer', 'between:1,100'],
            'obligatoria' => ['required', 'boolean'],
        ]);
        if (in_array($datos['tipo'], ['MAX_CONSECUTIVAS_DOCENTE', 'MAX_DIARIAS_DOCENTE'], true)) {
            if (empty($datos['docente_id']) || empty($datos['maximo'])) {
                throw ValidationException::withMessages(['maximo' => 'Selecciona un docente y el máximo de clases.']);
            }
            $datos['seccion_id'] = null;
            $datos['parametros'] = ['maximo' => (int) $datos['maximo']];
        } else {
            if (empty($datos['seccion_id']) && empty($datos['docente_id'])) {
                throw ValidationException::withMessages(['seccion_id' => 'Selecciona una sección o un docente.']);
            }
            if (empty($datos['dia_semana']) || empty($datos['bloque_horario_id'])) {
                throw ValidationException::withMessages(['dia_semana' => 'Selecciona día y bloque.']);
            }
            $datos['parametros'] = [
                'dia_semana' => (int) $datos['dia_semana'],
                'bloque_horario_id' => (int) $datos['bloque_horario_id'],
            ];
        }
        unset($datos['maximo'], $datos['dia_semana'], $datos['bloque_horario_id']);
        return $datos;
    }

    private function validarIntegridad(array $datos): void
    {
        $this->verificarEditable($datos['ciclo_escolar_id']);
        if ($datos['seccion_id'] && !Seccion::query()->whereKey($datos['seccion_id'])
            ->where('ciclo_escolar_id', $datos['ciclo_escolar_id'])->exists()) {
            throw ValidationException::withMessages(['seccion_id' => 'La sección no pertenece al ciclo seleccionado.']);
        }
        if ($datos['tipo'] === 'BLOQUEO_PERIODO') {
            $bloque = BloqueHorario::findOrFail($datos['parametros']['bloque_horario_id']);
            if ($bloque->tipo !== 'CLASE') {
                throw ValidationException::withMessages(['bloque_horario_id' => 'Solo se pueden bloquear períodos de clase.']);
            }
            if ($datos['seccion_id']) {
                $seccion = Seccion::findOrFail($datos['seccion_id']);
                if ((int) $seccion->jornada_id !== (int) $bloque->jornada_id) {
                    throw ValidationException::withMessages(['bloque_horario_id' => 'El bloque no pertenece a la jornada de la sección.']);
                }
            }
        }
    }

    private function verificarEditable(int $cicloId): void
    {
        $ciclo = CicloEscolar::findOrFail($cicloId);
        if ($ciclo->estado === 'CERRADO') {
            throw ValidationException::withMessages(['ciclo_escolar_id' => 'No se pueden modificar restricciones de un ciclo cerrado.']);
        }
    }

    private function volver(int $cicloId, string $mensaje): RedirectResponse
    {
        return redirect()->route('direccion.horarios.restricciones.index', [
            'ciclo_escolar_id' => $cicloId,
        ])->with('success', $mensaje);
    }
}
