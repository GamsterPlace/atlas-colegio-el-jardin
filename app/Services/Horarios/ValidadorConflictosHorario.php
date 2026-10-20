<?php

namespace App\Services\Horarios;

/**
 * Valida una propuesta de horario ya normalizada, sin consultar ni modificar BD.
 *
 * Datos requeridos:
 * - bloques: [{id, jornada_id, tipo, hora_inicio, hora_fin}]
 * - secciones: [{id, jornada_id}]
 * - cargas: [{id, seccion_id, horas_semanales, activo}]
 * - asignaciones: [{carga_academica_id, docente_id, activo}]
 * - disponibilidades: [{docente_id, dia_semana, hora_inicio, hora_fin, disponible}]
 * - restricciones: [{tipo, seccion_id?, docente_id?, parametros, obligatoria}]
 * - detalles: [{seccion_id, dia_semana, bloque_horario_id, carga_academica_id, docente_id}]
 * - ocupaciones: detalles de otros horarios vigentes que deban bloquear al docente.
 *
 * El adaptador que construya estos datos debe filtrar por ciclo escolar, asignaciones
 * vigentes, horarios aprobados y fechas aplicables. Los IDs se comparan como enteros.
 */
class ValidadorConflictosHorario
{
    public function validar(array $datos): array
    {
        $errores = [];
        $bloques = $this->indexar($datos['bloques'] ?? []);
        $secciones = $this->indexar($datos['secciones'] ?? []);
        $cargas = $this->indexar($datos['cargas'] ?? []);
        $asignaciones = $datos['asignaciones'] ?? [];
        $disponibilidades = $datos['disponibilidades'] ?? [];
        $restricciones = $datos['restricciones'] ?? [];
        $detalles = $datos['detalles'] ?? [];
        $ocupaciones = $datos['ocupaciones'] ?? [];
        $ocupacionSeccion = [];
        $ocupacionDocente = [];
        $conteoCarga = [];
        $clasesDocenteDia = [];
        $diasDocente = [];

        foreach ($detalles as $indice => $detalle) {
            $n = $indice + 1;
            $seccionId = (int) ($detalle['seccion_id'] ?? 0);
            $bloqueId = (int) ($detalle['bloque_horario_id'] ?? 0);
            $cargaId = (int) ($detalle['carga_academica_id'] ?? 0);
            $docenteId = (int) ($detalle['docente_id'] ?? 0);
            $dia = (int) ($detalle['dia_semana'] ?? 0);
            $bloque = $bloques[$bloqueId] ?? null;
            $seccion = $secciones[$seccionId] ?? null;
            $carga = $cargas[$cargaId] ?? null;

            if ($dia < 1 || $dia > 7) {
                $errores[] = "Fila {$n}: día de la semana inválido.";
            }
            if (!$seccion) {
                $errores[] = "Fila {$n}: sección inexistente en el contexto.";
            }
            if (!$bloque || ($bloque['tipo'] ?? '') !== 'CLASE') {
                $errores[] = "Fila {$n}: el bloque no existe o no es de clase.";
            } elseif (!$this->intervaloValido($bloque)) {
                $errores[] = "Fila {$n}: el bloque tiene horas inválidas.";
            }
            if ($seccion && $bloque && (int) ($seccion['jornada_id'] ?? 0) !== (int) ($bloque['jornada_id'] ?? 0)) {
                $errores[] = "Fila {$n}: el bloque no pertenece a la jornada de la sección.";
            }
            if (!$carga || (int) ($carga['seccion_id'] ?? 0) !== $seccionId || !($carga['activo'] ?? false)) {
                $errores[] = "Fila {$n}: la carga académica no está activa o no corresponde a la sección.";
            } else {
                $conteoCarga[$cargaId] = ($conteoCarga[$cargaId] ?? 0) + 1;
            }
            $asignado = false;
            foreach ($asignaciones as $asignacion) {
                if ((int) ($asignacion['carga_academica_id'] ?? 0) === $cargaId
                    && (int) ($asignacion['docente_id'] ?? 0) === $docenteId
                    && ($asignacion['activo'] ?? false)) {
                    $asignado = true;
                    break;
                }
            }
            if (!$asignado) {
                $errores[] = "Fila {$n}: el docente no tiene asignación activa para esta carga.";
            }
            if (!$bloque || !$this->intervaloValido($bloque) || $dia < 1 || $dia > 7) {
                continue;
            }

            $inicio = $this->minutos($bloque['hora_inicio']);
            $fin = $this->minutos($bloque['hora_fin']);
            $claveSeccion = "{$seccionId}:{$dia}";
            $claveDocente = "{$docenteId}:{$dia}";
            foreach ($ocupacionSeccion[$claveSeccion] ?? [] as $otra) {
                if ($this->solapa($inicio, $fin, $otra[0], $otra[1])) {
                    $errores[] = "Fila {$n}: la sección tiene otra clase en un horario superpuesto.";
                    break;
                }
            }
            $ocupacionSeccion[$claveSeccion][] = [$inicio, $fin];
            foreach ($ocupacionDocente[$claveDocente] ?? [] as $otra) {
                if ($this->solapa($inicio, $fin, $otra[0], $otra[1])) {
                    $errores[] = "Fila {$n}: el docente tiene otra clase en un horario superpuesto.";
                    break;
                }
            }
            $ocupacionDocente[$claveDocente][] = [$inicio, $fin];
            $clasesDocenteDia[$claveDocente][] = [$inicio, $fin];
            $diasDocente[$claveDocente] = ['docente_id' => $docenteId, 'dia_semana' => $dia];

            // Política estricta: la franja completa debe estar cubierta por intervalos disponibles.
            $permitidas = [];
            $prohibidas = [];
            foreach ($disponibilidades as $franja) {
                if ((int) ($franja['docente_id'] ?? 0) !== $docenteId
                    || (int) ($franja['dia_semana'] ?? 0) !== $dia
                    || !$this->intervaloValido($franja)) {
                    continue;
                }
                $par = [$this->minutos($franja['hora_inicio']), $this->minutos($franja['hora_fin'])];
                if ($franja['disponible'] ?? false) {
                    $permitidas[] = $par;
                } else {
                    $prohibidas[] = $par;
                }
            }
            if (!$this->cubierto($inicio, $fin, $permitidas)) {
                $errores[] = "Fila {$n}: el docente no tiene disponibilidad explícita para todo el período.";
            }
            foreach ($prohibidas as $par) {
                if ($this->solapa($inicio, $fin, $par[0], $par[1])) {
                    $errores[] = "Fila {$n}: el período coincide con una franja no disponible del docente.";
                    break;
                }
            }
            foreach ($restricciones as $regla) {
                if (!($regla['obligatoria'] ?? false) || ($regla['tipo'] ?? '') !== 'BLOQUEO_PERIODO') {
                    continue;
                }
                $parametros = $regla['parametros'] ?? [];
                if ((int) ($parametros['dia_semana'] ?? 0) !== $dia
                    || (int) ($parametros['bloque_horario_id'] ?? 0) !== $bloqueId) {
                    continue;
                }
                $seccionRegla = (int) ($regla['seccion_id'] ?? 0);
                $docenteRegla = (int) ($regla['docente_id'] ?? 0);
                if (($seccionRegla || $docenteRegla)
                    && (!$seccionRegla || $seccionRegla === $seccionId)
                    && (!$docenteRegla || $docenteRegla === $docenteId)) {
                    $errores[] = "Fila {$n}: coincide con un bloqueo obligatorio.";
                }
            }
        }

        foreach ($ocupaciones as $externa) {
            $docenteId = (int) ($externa['docente_id'] ?? 0);
            $dia = (int) ($externa['dia_semana'] ?? 0);
            $bloque = $bloques[(int) ($externa['bloque_horario_id'] ?? 0)] ?? null;
            if (!$bloque || !$this->intervaloValido($bloque)) {
                $errores[] = 'Ocupación externa con bloque inexistente o inválido.';
                continue;
            }
            $inicio = $this->minutos($bloque['hora_inicio']);
            $fin = $this->minutos($bloque['hora_fin']);
            foreach ($ocupacionDocente["{$docenteId}:{$dia}"] ?? [] as $intervalo) {
                if ($this->solapa($inicio, $fin, $intervalo[0], $intervalo[1])) {
                    $errores[] = "Docente {$docenteId}: conflicto con un horario existente el día {$dia}.";
                    break;
                }
            }
        }

        foreach ($cargas as $id => $carga) {
            if (!($carga['activo'] ?? false)) {
                continue;
            }
            $esperadas = (int) ($carga['horas_semanales'] ?? 0);
            $reales = $conteoCarga[$id] ?? 0;
            if ($reales !== $esperadas) {
                $errores[] = "Carga {$id}: se programaron {$reales} períodos de {$esperadas} requeridos.";
            }
        }

        foreach ($restricciones as $regla) {
            if (!($regla['obligatoria'] ?? false)) {
                continue;
            }
            $tipo = $regla['tipo'] ?? '';
            if (!in_array($tipo, ['MAX_DIARIAS_DOCENTE', 'MAX_CONSECUTIVAS_DOCENTE'], true)) {
                continue;
            }
            $docenteId = (int) ($regla['docente_id'] ?? 0);
            $maximo = (int) ($regla['parametros']['maximo'] ?? 0);
            if (!$docenteId || $maximo < 1) {
                $errores[] = "Restricción {$tipo}: parámetros inválidos.";
                continue;
            }
            foreach ($diasDocente as $clave => $contexto) {
                if ($contexto['docente_id'] !== $docenteId) {
                    continue;
                }
                $intervalos = $clasesDocenteDia[$clave];
                usort($intervalos, fn ($a, $b) => $a[0] <=> $b[0]);
                if ($tipo === 'MAX_DIARIAS_DOCENTE' && count($intervalos) > $maximo) {
                    $errores[] = "Docente {$docenteId}, día {$contexto['dia_semana']}: excede el máximo diario {$maximo}.";
                }
                if ($tipo === 'MAX_CONSECUTIVAS_DOCENTE') {
                    $racha = 0;
                    $finAnterior = null;
                    foreach ($intervalos as $intervalo) {
                        $racha = $finAnterior !== null && $intervalo[0] === $finAnterior ? $racha + 1 : 1;
                        $finAnterior = $intervalo[1];
                        if ($racha > $maximo) {
                            $errores[] = "Docente {$docenteId}, día {$contexto['dia_semana']}: excede {$maximo} clases consecutivas.";
                            break;
                        }
                    }
                }
            }
        }

        return ['valido' => $errores === [], 'errores' => array_values(array_unique($errores))];
    }

    private function indexar(array $filas): array
    {
        $salida = [];
        foreach ($filas as $fila) {
            $salida[(int) ($fila['id'] ?? 0)] = $fila;
        }
        return $salida;
    }

    private function minutos(string $hora): int
    {
        $partes = explode(':', $hora);
        return (int) $partes[0] * 60 + (int) ($partes[1] ?? 0);
    }

    private function intervaloValido(array $fila): bool
    {
        $inicio = (string) ($fila['hora_inicio'] ?? '');
        $fin = (string) ($fila['hora_fin'] ?? '');
        if (!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d(?::[0-5]\d)?$/', $inicio)
            || !preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d(?::[0-5]\d)?$/', $fin)) {
            return false;
        }
        return $this->minutos($inicio) < $this->minutos($fin);
    }

    private function solapa(int $inicio, int $fin, int $otroInicio, int $otroFin): bool
    {
        return $inicio < $otroFin && $otroInicio < $fin;
    }

    private function cubierto(int $inicio, int $fin, array $intervalos): bool
    {
        usort($intervalos, fn ($a, $b) => $a[0] <=> $b[0]);
        $cursor = $inicio;
        foreach ($intervalos as [$desde, $hasta]) {
            if ($desde > $cursor) {
                break;
            }
            $cursor = max($cursor, $hasta);
            if ($cursor >= $fin) {
                return true;
            }
        }
        return false;
    }
}
