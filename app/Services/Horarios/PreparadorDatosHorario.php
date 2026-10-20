<?php

namespace App\Services\Horarios;

use App\Models\Seccion;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

/**
 * Prepara los datos reales de ATLAS para ValidadorConflictosHorario.
 * No guarda, modifica ni aprueba horarios.
 */
final class PreparadorDatosHorario
{
    /**
     * @param array<int, array<string, mixed>> $detalles Propuesta completa de la sección.
     * @param string|null $fechaReferencia Fecha de vigencia de las asignaciones (Y-m-d).
     * @return array<string, array>
     */
    public function preparar(int $seccionId, array $detalles, ?string $fechaReferencia = null): array
    {
        $seccion = Seccion::query()->findOrFail($seccionId);
        $cicloId = (int) $seccion->ciclo_escolar_id;
        $fecha = $fechaReferencia ?? CarbonImmutable::today()->toDateString();

        if (!CarbonImmutable::hasFormat($fecha, 'Y-m-d')) {
            throw new InvalidArgumentException('La fecha de referencia debe tener formato Y-m-d.');
        }

        // El validador compara todos los intervalos, incluso entre jornadas distintas.
        $secciones = DB::table('secciones')
            ->where('ciclo_escolar_id', $cicloId)
            ->get(['id', 'jornada_id'])
            ->map(fn ($fila) => (array) $fila)->all();

        $jornadas = array_unique(array_map(
            fn ($fila) => (int) $fila['jornada_id'], $secciones
        ));
        $bloques = DB::table('bloques_horarios')
            ->whereIn('jornada_id', $jornadas)
            ->get(['id', 'jornada_id', 'tipo', 'hora_inicio', 'hora_fin'])
            ->map(fn ($fila) => (array) $fila)->all();

        // Se valida la propuesta completa de una sección, no solo una fila.
        $cargas = DB::table('cargas_academicas as ca')
            ->join('pensum_cursos as pc', 'pc.id', '=', 'ca.pensum_curso_id')
            ->where('ca.seccion_id', $seccionId)
            ->get(['ca.id', 'ca.seccion_id', 'ca.activo', 'pc.horas_semanales'])
            ->map(fn ($fila) => (array) $fila)->all();
        $cargaIds = array_column($cargas, 'id');

        $asignaciones = DB::table('asignaciones_docentes')
            ->whereIn('carga_academica_id', $cargaIds)
            ->where('activo', true)
            ->whereDate('fecha_inicio', '<=', $fecha)
            ->where(function ($consulta) use ($fecha) {
                $consulta->whereNull('fecha_fin')
                    ->orWhereDate('fecha_fin', '>=', $fecha);
            })
            ->get(['carga_academica_id', 'docente_id', 'activo'])
            ->map(fn ($fila) => (array) $fila)->all();

        $docentes = array_values(array_unique(array_merge(
            array_map('intval', array_column($asignaciones, 'docente_id')),
            array_map(fn ($fila) => (int) ($fila['docente_id'] ?? 0), $detalles)
        )));
        $docentes = array_values(array_filter($docentes, fn ($id) => $id > 0));

        $disponibilidades = DB::table('disponibilidad_docentes')
            ->where('ciclo_escolar_id', $cicloId)
            ->whereIn('docente_id', $docentes)
            ->get(['docente_id', 'dia_semana', 'hora_inicio', 'hora_fin', 'disponible'])
            ->map(fn ($fila) => (array) $fila)->all();

        $restricciones = DB::table('restricciones_horarios')
            ->where('ciclo_escolar_id', $cicloId)
            ->where(function ($consulta) use ($seccionId, $docentes) {
                $consulta->whereNull('seccion_id')
                    ->orWhere('seccion_id', $seccionId);
            })
            ->where(function ($consulta) use ($docentes) {
                $consulta->whereNull('docente_id')
                    ->orWhereIn('docente_id', $docentes);
            })
            ->get(['tipo', 'seccion_id', 'docente_id', 'parametros', 'obligatoria'])
            ->map(function ($fila) {
                $item = (array) $fila;
                $item['parametros'] = json_decode($item['parametros'], true, 512, JSON_THROW_ON_ERROR);
                return $item;
            })->all();

        // Solo los horarios aprobados de OTRAS secciones bloquean docentes.
        // El horario actual de la sección se reemplaza por la propuesta recibida.
        $ocupaciones = DB::table('horario_detalles as hd')
            ->join('horarios as h', 'h.id', '=', 'hd.horario_id')
            ->join('secciones as s', 's.id', '=', 'h.seccion_id')
            ->where('s.ciclo_escolar_id', $cicloId)
            ->where('h.estado', 'APROBADO')
            ->where('h.seccion_id', '<>', $seccionId)
            ->whereIn('hd.docente_id', $docentes)
            ->get(['hd.docente_id', 'hd.dia_semana', 'hd.bloque_horario_id'])
            ->map(fn ($fila) => (array) $fila)->all();

        $propuesta = array_map(function (array $detalle) use ($seccionId): array {
            if (isset($detalle['seccion_id']) && (int) $detalle['seccion_id'] !== $seccionId) {
                throw new InvalidArgumentException('La propuesta contiene una sección distinta de la solicitada.');
            }
            return [
                'seccion_id' => $seccionId,
                'dia_semana' => $detalle['dia_semana'] ?? null,
                'bloque_horario_id' => $detalle['bloque_horario_id'] ?? null,
                'carga_academica_id' => $detalle['carga_academica_id'] ?? null,
                'docente_id' => $detalle['docente_id'] ?? null,
            ];
        }, $detalles);

        return [
            'bloques' => $bloques,
            'secciones' => $secciones,
            'cargas' => $cargas,
            'asignaciones' => $asignaciones,
            'disponibilidades' => $disponibilidades,
            'restricciones' => $restricciones,
            'detalles' => $propuesta,
            'ocupaciones' => $ocupaciones,
        ];
    }
}
