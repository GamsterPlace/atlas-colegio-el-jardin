<?php

namespace App\Http\Controllers\Direccion;

use App\Http\Controllers\Controller;
use App\Models\CicloEscolar;
use App\Models\Horario;
use App\Models\HorarioDetalle;
use App\Models\Seccion;
use App\Services\Horarios\GeneradorHorariosCicloEscolar;
use App\Services\Horarios\PreparadorDatosHorario;
use App\Services\Horarios\ValidadorConflictosHorario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use DomainException;
use Throwable;

final class HorarioAcademicoController extends Controller
{
    public function index(Request $request): View
    {
        $ciclos = CicloEscolar::query()->orderByDesc('fecha_inicio')->get();
        $cicloId = (int) $request->query('ciclo_id', $ciclos->first()?->id ?? 0);
        $secciones = Seccion::query()->where('ciclo_escolar_id', $cicloId)->orderBy('id')->get();
        $horarios = Horario::query()->with(['registrosHorarioDetalle', 'seccion'])
            ->whereIn('seccion_id', $secciones->pluck('id'))
            ->orderBy('seccion_id')->orderByDesc('version')->get()
            ->groupBy('seccion_id')
            ->map(fn ($versiones) => $versiones->sortByDesc(
                fn ($horario) => $horario->estado === 'APROBADO' ? 1000000 + (int) $horario->version : (int) $horario->version
            )->values());
        $bloques = DB::table('bloques_horarios')->orderBy('hora_inicio')->get()->keyBy('id');
        $cargas = DB::table('cargas_academicas as ca')
            ->join('pensum_cursos as pc', 'pc.id', '=', 'ca.pensum_curso_id')
            ->join('cursos as c', 'c.id', '=', 'pc.curso_id')
            ->whereIn('ca.seccion_id', $secciones->pluck('id'))
            ->select('ca.id', 'ca.seccion_id', 'c.nombre')->get()->keyBy('id');
        $docentes = DB::table('docentes')->get()->keyBy('id');
        return view('direccion.horarios.index', compact('ciclos', 'cicloId', 'secciones', 'horarios', 'bloques', 'cargas', 'docentes'));
    }

    public function generar(Request $request, GeneradorHorariosCicloEscolar $generador): RedirectResponse
    {
        $datos = $request->validate(['ciclo_id' => ['required', 'integer', 'exists:ciclos_escolares,id']]);
        $cicloId = (int) $datos['ciclo_id'];
        try {
            $resultado = $generador->generar($cicloId);
        } catch (Throwable $e) {
            report($e);
            return back()->withErrors(['horarios' => 'No fue posible generar los horarios. Revise la configuración académica.']);
        }
        if (!$resultado['exito']) {
            return redirect()->route('direccion.horarios.index', ['ciclo_id' => $cicloId])
                ->withErrors(['horarios' => implode(' ', $resultado['errores'])]);
        }
        $request->session()->put('atlas_horarios_propuesta', [
            'ciclo_id' => $cicloId,
            'horarios' => $resultado['horarios'],
        ]);
        return redirect()->route('direccion.horarios.index', ['ciclo_id' => $cicloId])
            ->with('success', 'Propuesta generada. Revísela antes de guardarla.');
    }

    public function guardar(Request $request, GeneradorHorariosCicloEscolar $generador): RedirectResponse
    {
        $datos = $request->validate(['ciclo_id' => ['required', 'integer', 'exists:ciclos_escolares,id']]);
        $cicloId = (int) $datos['ciclo_id'];
        $propuesta = $request->session()->get('atlas_horarios_propuesta');
        if (!is_array($propuesta) || (int) ($propuesta['ciclo_id'] ?? 0) !== $cicloId) {
            return back()->withErrors(['horarios' => 'Genere una propuesta antes de guardarla.']);
        }
        try {
            $resultado = $generador->generar($cicloId);
            if (!$resultado['exito']) {
                return back()->withErrors(['horarios' => implode(' ', $resultado['errores'])]);
            }
            DB::transaction(function () use ($resultado, $cicloId, $request): void {
                $ciclo = CicloEscolar::query()->whereKey($cicloId)->lockForUpdate()->firstOrFail();
                if ($ciclo->estado === 'CERRADO') {
                    throw new DomainException('El ciclo escolar está cerrado.');
                }
                foreach ($resultado['horarios'] as $seccionId => $detalles) {
                    $version = ((int) Horario::query()->where('seccion_id', $seccionId)->max('version')) + 1;
                    $horario = Horario::query()->create([
                        'seccion_id' => $seccionId,
                        'version' => $version,
                        'origen' => 'GENERADO',
                        'estado' => 'BORRADOR',
                        'generado_por' => $request->user()->id,
                    ]);
                    foreach ($detalles as $detalle) {
                        HorarioDetalle::query()->create([
                            'horario_id' => $horario->id,
                            'bloque_horario_id' => $detalle['bloque_horario_id'],
                            'dia_semana' => $detalle['dia_semana'],
                            'carga_academica_id' => $detalle['carga_academica_id'],
                            'docente_id' => $detalle['docente_id'],
                        ]);
                    }
                }
            });
        } catch (Throwable $e) {
            report($e);
            return back()->withErrors(['horarios' => 'No se pudo guardar la propuesta. Revise el registro del sistema.']);
        }
        $request->session()->forget('atlas_horarios_propuesta');
        return redirect()->route('direccion.horarios.index', ['ciclo_id' => $cicloId])
            ->with('success', 'Se guardaron las nuevas versiones de horarios como borradores.');
    }

    public function actualizarDetalle(Request $request, Horario $horario, HorarioDetalle $detalle, PreparadorDatosHorario $preparador, ValidadorConflictosHorario $validador): RedirectResponse
    {
        $datos = $request->validate([
            'dia_semana' => ['required', 'integer', 'between:1,7'],
            'bloque_horario_id' => ['required', 'integer', 'exists:bloques_horarios,id'],
        ]);
        try {
            DB::transaction(function () use ($horario, $detalle, $datos, $preparador, $validador): void {
                $actual = Horario::query()->whereKey($horario->id)->lockForUpdate()->firstOrFail();
                if ($actual->estado !== 'BORRADOR' || (int) $detalle->horario_id !== (int) $actual->id) {
                    throw new DomainException('Solo se pueden modificar períodos de este borrador.');
                }
                $seccion = Seccion::query()->findOrFail($actual->seccion_id);
                $ciclo = CicloEscolar::query()->findOrFail($seccion->ciclo_escolar_id);
                if ($ciclo->estado === 'CERRADO') {
                    throw new DomainException('El ciclo escolar está cerrado.');
                }
                $filas = $actual->registrosHorarioDetalle()->get()->map(function ($fila) use ($detalle, $datos, $actual) {
                    return [
                        'seccion_id' => (int) $actual->seccion_id,
                        'dia_semana' => (int) ($fila->id === $detalle->id ? $datos['dia_semana'] : $fila->dia_semana),
                        'bloque_horario_id' => (int) ($fila->id === $detalle->id ? $datos['bloque_horario_id'] : $fila->bloque_horario_id),
                        'carga_academica_id' => (int) $fila->carga_academica_id,
                        'docente_id' => (int) $fila->docente_id,
                    ];
                })->all();
                $contexto = $preparador->preparar((int) $actual->seccion_id, $filas, \Carbon\Carbon::parse($ciclo->fecha_inicio)->toDateString());
                $validacion = $validador->validar($contexto);
                if (!$validacion['valido']) {
                    throw new DomainException(implode(' ', $validacion['errores']));
                }
                $detalle->update($datos);
            });
        } catch (DomainException $e) {
            return back()->withErrors(['horarios' => $e->getMessage()]);
        } catch (Throwable $e) {
            report($e);
            return back()->withErrors(['horarios' => 'No fue posible modificar el período.']);
        }
        return back()->with('success', 'Período actualizado y validado.');
    }

    public function aprobar(Horario $horario, Request $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($horario, $request): void {
                $actual = Horario::query()->whereKey($horario->id)->lockForUpdate()->firstOrFail();
                $seccion = Seccion::query()->findOrFail($actual->seccion_id);
                $ciclo = CicloEscolar::query()->whereKey($seccion->ciclo_escolar_id)->lockForUpdate()->firstOrFail();
                if ($ciclo->estado === 'CERRADO' || $actual->estado !== 'BORRADOR') {
                    throw new DomainException('Solo se puede aprobar un borrador de un ciclo abierto.');
                }
                $detalles = $actual->registrosHorarioDetalle()->get();
                if ($detalles->isEmpty()) {
                    throw new DomainException('El horario no contiene períodos.');
                }
                $otros = DB::table('horario_detalles as d')
                    ->join('horarios as h', 'h.id', '=', 'd.horario_id')
                    ->join('secciones as s', 's.id', '=', 'h.seccion_id')
                    ->join('bloques_horarios as b', 'b.id', '=', 'd.bloque_horario_id')
                    ->where('s.ciclo_escolar_id', $ciclo->id)
                    ->where('h.estado', 'APROBADO')
                    ->where('h.seccion_id', '<>', $actual->seccion_id)
                    ->select('d.docente_id', 'd.dia_semana', 'b.hora_inicio', 'b.hora_fin')->get();
                $bloques = DB::table('bloques_horarios')->whereIn('id', $detalles->pluck('bloque_horario_id'))->get()->keyBy('id');
                $ocupacionesLocales = [];
                foreach ($detalles as $detalle) {
                    $bloque = $bloques->get($detalle->bloque_horario_id);
                    if (!$bloque) {
                        throw new DomainException('Existe un bloque horario inválido.');
                    }
                    foreach ($ocupacionesLocales as $ocupacion) {
                        if ((int) $ocupacion['dia'] === (int) $detalle->dia_semana
                            && $bloque->hora_inicio < $ocupacion['fin']
                            && $ocupacion['inicio'] < $bloque->hora_fin) {
                            throw new DomainException('Existen períodos superpuestos dentro del mismo horario.');
                        }
                    }
                    $ocupacionesLocales[] = [
                        'dia' => (int) $detalle->dia_semana,
                        'inicio' => $bloque->hora_inicio,
                        'fin' => $bloque->hora_fin,
                    ];
                    foreach ($otros as $otro) {
                        if ((int) $otro->docente_id === (int) $detalle->docente_id
                            && (int) $otro->dia_semana === (int) $detalle->dia_semana
                            && $bloque->hora_inicio < $otro->hora_fin && $otro->hora_inicio < $bloque->hora_fin) {
                            throw new DomainException('El horario presenta un conflicto con otra sección aprobada.');
                        }
                    }
                }
                Horario::query()->where('seccion_id', $actual->seccion_id)->where('estado', 'APROBADO')
                    ->update(['estado' => 'ARCHIVADO']);
                $actual->update(['estado' => 'APROBADO', 'aprobado_por' => $request->user()->id, 'aprobado_at' => now()]);
            });
        } catch (DomainException $e) {
            return back()->withErrors(['horarios' => $e->getMessage()]);
        } catch (Throwable $e) {
            report($e);
            return back()->withErrors(['horarios' => 'No fue posible aprobar el horario.']);
        }
        return back()->with('success', 'Horario aprobado correctamente.');
    }
}
