<?php

namespace  App\Http\Controllers\Direccion;

use App\Http\Controllers\Controller;
use App\Models\CicloEscolar;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(): View
    {
        $hoy = now()->toDateString();
        $anioActual = (int) now()->year;
        $ciclosEscolares = $this->obtenerCiclosDisponibles($anioActual);
        $cicloEscolar = $this->obtenerCicloEscolar($ciclosEscolares, $anioActual);
        $anioSeleccionado = $cicloEscolar ? $this->anioDelCiclo($cicloEscolar) : null;
        $esCicloActual = $anioSeleccionado === $anioActual;
        $fechaReferencia = $esCicloActual
            ? $hoy
            : ($anioSeleccionado !== null
                ? DB::table('asistencia_estudiantes as a')
                    ->join('inscripciones as i', 'i.id', '=', 'a.inscripcion_id')
                    ->where('i.ciclo_escolar_id', $cicloEscolar->id)
                    ->max('a.fecha')
                : null);

        $indicadores = [
            'estudiantes' => null,
            'docentes' => 0,
            'asistencia_hoy' => null,
            'pagos' => null,
        ];

        $asistencia = [
            'entradas' => null,
            'salidas' => null,
            'registros_hoy' => null,
            'presentes' => null,
            'ausentes' => null,
            'tardes' => null,
            'justificados' => null,
            'sin_registro' => null,
            'porcentaje' => null,
        ];

        $finanzas = [
            'pagos_registrados' => null,
            'saldos_pendientes' => null,
            'total_recaudado' => null,
        ];

        $proximasActividades = collect();
        $actividadReciente = collect();

        if ($esCicloActual) {
        $indicadores['docentes'] = DB::table('docentes')
            ->join(
                'personal',
                'personal.id',
                '=',
                'docentes.personal_id'
            )
            ->where('docentes.activo', true)
            ->where('personal.activo', true)
            ->count('docentes.id');
        } else {
            $indicadores['docentes'] = null;
        }

        if ($cicloEscolar !== null) {
            $cicloEscolarId = $cicloEscolar->id;

            $inscripcionesVigentes = DB::table('inscripciones')
                ->where(
                    'ciclo_escolar_id',
                    $cicloEscolarId
                )
                ->where('estado', 'COMPLETADA')
                ->whereExists(function ($query) {
                    $query->selectRaw('1')
                        ->from('estudiantes')
                        ->whereColumn(
                            'estudiantes.id',
                            'inscripciones.estudiante_id'
                        )
                        ->where('estudiantes.estado', 'ACTIVO')
                        ->whereNull('estudiantes.deleted_at');
                });

            $totalEstudiantes = (clone $inscripcionesVigentes)
                ->distinct()
                ->count('estudiante_id');

            $indicadores['estudiantes'] = $totalEstudiantes;

            if ($fechaReferencia !== null) {
            $asistenciasPorEstudiante = DB::table(
                'asistencia_estudiantes as ae'
            )
                ->join(
                    'inscripciones as i',
                    'i.id',
                    '=',
                    'ae.inscripcion_id'
                )
                ->where(
                    'i.ciclo_escolar_id',
                    $cicloEscolarId
                )
                ->where('i.estado', 'COMPLETADA')
                ->whereDate('ae.fecha', $fechaReferencia)
                ->whereExists(function ($query) {
                    $query->selectRaw('1')
                        ->from('estudiantes as e')
                        ->whereColumn(
                            'e.id',
                            'i.estudiante_id'
                        )
                        ->where('e.estado', 'ACTIVO')
                        ->whereNull('e.deleted_at');
                })
                ->select(
                    'i.estudiante_id',
                    'ae.estado'
                )
                ->selectRaw(
                    'ROW_NUMBER() OVER (
                        PARTITION BY i.estudiante_id
                        ORDER BY ae.id DESC
                    ) as numero_registro'
                );

            $resumen = DB::query()
                ->fromSub(
                    $asistenciasPorEstudiante,
                    'asistencias'
                )
                ->where('numero_registro', 1)
                ->selectRaw(
                    'estado, COUNT(*) as total'
                )
                ->groupBy('estado')
                ->pluck('total', 'estado');

            $presentes = (int) $resumen->get(
                'PRESENTE',
                0
            );

            $ausentes = (int) $resumen->get(
                'AUSENTE',
                0
            );

            $tardes = (int) $resumen->get(
                'TARDE',
                0
            );

            $justificados = (int) $resumen->get(
                'JUSTIFICADA',
                0
            );

            $registrosHoy = $presentes
                + $ausentes
                + $tardes
                + $justificados;

            $sinRegistro = max(
                0,
                $totalEstudiantes - $registrosHoy
            );

            $porcentajeAsistencia = $totalEstudiantes > 0
                ? round(
                    (
                        ($presentes + $tardes)
                        / $totalEstudiantes
                    ) * 100,
                    1
                )
                : null;

            $asistencia = [
                'entradas' => $presentes,
                'salidas' => $ausentes,
                'registros_hoy' => $registrosHoy,
                'presentes' => $presentes,
                'ausentes' => $ausentes,
                'tardes' => $tardes,
                'justificados' => $justificados,
                'sin_registro' => $sinRegistro,
                'porcentaje' => $porcentajeAsistencia,
            ];

            $indicadores['asistencia_hoy'] =
                $porcentajeAsistencia;
            } else {
                $asistencia['sin_registro'] = $totalEstudiantes;
            }

            $pagosValidos = DB::table('pagos')
                ->where('estado', 'VALIDO')
                ->whereExists(function ($query) use (
                    $cicloEscolarId
                ) {
                    $query->selectRaw('1')
                        ->from('cuentas_por_cobrar as c')
                        ->whereColumn(
                            'c.id',
                            'pagos.cuenta_por_cobrar_id'
                        )
                        ->where(
                            'c.ciclo_escolar_id',
                            $cicloEscolarId
                        );
                });

            $totalPagos = (clone $pagosValidos)
                ->count();

            $totalRecaudado = (clone $pagosValidos)
                ->sum('monto');

            $cuentasVigentes = DB::table(
                'cuentas_por_cobrar'
            )
                ->where(
                    'ciclo_escolar_id',
                    $cicloEscolarId
                )
                ->where(
                    'estado_administrativo',
                    'VIGENTE'
                );

            $totalExigible = (clone $cuentasVigentes)
                ->sum('monto_exigible');

            $totalRecargos = DB::table(
                'recargos_mora as rm'
            )
                ->join(
                    'cuentas_por_cobrar as c',
                    'c.id',
                    '=',
                    'rm.cuenta_por_cobrar_id'
                )
                ->where(
                    'c.ciclo_escolar_id',
                    $cicloEscolarId
                )
                ->where(
                    'c.estado_administrativo',
                    'VIGENTE'
                )
                ->where('rm.estado', 'VIGENTE')
                ->sum('rm.monto');

            $totalAplicado = DB::table('pagos as p')
                ->join(
                    'cuentas_por_cobrar as c',
                    'c.id',
                    '=',
                    'p.cuenta_por_cobrar_id'
                )
                ->where(
                    'c.ciclo_escolar_id',
                    $cicloEscolarId
                )
                ->where(
                    'c.estado_administrativo',
                    'VIGENTE'
                )
                ->where('p.estado', 'VALIDO')
                ->sum('p.monto');

            $saldoPendiente = max(
                0,
                round(
                    (float) $totalExigible
                    + (float) $totalRecargos
                    - (float) $totalAplicado,
                    2
                )
            );

            $indicadores['pagos'] = $totalPagos;

            $finanzas = [
                'pagos_registrados' => $totalPagos,
                'saldos_pendientes' => $saldoPendiente,
                'total_recaudado' => round(
                    (float) $totalRecaudado,
                    2
                ),
            ];

            $proximasActividades = DB::table(
                'eventos_agenda'
            )
                ->where(
                    'ciclo_escolar_id',
                    $cicloEscolarId
                )
                ->where(
                    'estado',
                    'PROGRAMADO'
                )
                ->where(function ($query) {
                    $query->where(
                        'inicio',
                        '>=',
                        now()
                    )
                        ->orWhere(
                            'fin',
                            '>=',
                            now()
                        );
                })
                ->orderBy('inicio')
                ->limit(6)
                ->get();
        }

        if ($esCicloActual) {
        $actividadReciente = DB::table(
            'bitacora_auditoria'
        )
            ->leftJoin(
                'usuarios',
                'usuarios.id',
                '=',
                'bitacora_auditoria.usuario_id'
            )
            ->select(
                'bitacora_auditoria.id',
                'bitacora_auditoria.accion',
                'bitacora_auditoria.entidad',
                'bitacora_auditoria.entidad_id',
                'bitacora_auditoria.fecha_evento',
                'usuarios.nombre_usuario'
            )
            ->orderByDesc(
                'bitacora_auditoria.fecha_evento'
            )
            ->orderByDesc(
                'bitacora_auditoria.id'
            )
            ->limit(8)
            ->get();
        }

        return view('direccion.dashboard', [
            'cicloEscolar' => $cicloEscolar,
            'ciclosEscolares' => $ciclosEscolares,
            'anioSeleccionado' => $anioSeleccionado,
            'esCicloActual' => $esCicloActual,
            'fechaReferenciaAsistencia' => $fechaReferencia,
            'indicadores' => $indicadores,
            'asistencia' => $asistencia,
            'finanzas' => $finanzas,
            'proximasActividades' => $proximasActividades,
            'actividadReciente' => $actividadReciente,
        ]);
    }

    private function obtenerCiclosDisponibles(int $anioActual): \Illuminate\Support\Collection
    {
        return CicloEscolar::query()
            ->orderByDesc('fecha_inicio')
            ->orderByDesc('id')
            ->get()
            ->filter(function (CicloEscolar $ciclo) use ($anioActual) {
                $anio = $this->anioDelCiclo($ciclo);

                if ($anio <= $anioActual) {
                    return true;
                }

                return DB::table('inscripciones')
                    ->where('ciclo_escolar_id', $ciclo->id)
                    ->where('estado', 'COMPLETADA')
                    ->exists();
            })
            ->values();
    }

    private function obtenerCicloEscolar(\Illuminate\Support\Collection $ciclos, int $anioActual): ?CicloEscolar
    {
        $seleccion = request()->query('ciclo_escolar_id');

        if (is_scalar($seleccion) && ctype_digit((string) $seleccion)) {
            $encontrado = $ciclos->firstWhere('id', (int) $seleccion);
            if ($encontrado !== null) {
                return $encontrado;
            }
        }

        return $ciclos->first(fn (CicloEscolar $ciclo) => $this->anioDelCiclo($ciclo) === $anioActual);
    }

    private function anioDelCiclo(CicloEscolar $ciclo): int
    {
        if (preg_match('/(?:19|20)\d{2}/', (string) $ciclo->nombre, $coincidencias)) {
            return (int) $coincidencias[0];
        }

        return (int) \Illuminate\Support\Carbon::parse($ciclo->fecha_inicio)->year;
    }
}
