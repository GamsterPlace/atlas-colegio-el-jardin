<?php

namespace App\Services\Horarios;

/**
 * Busca una propuesta completa en memoria. No escribe en la base de datos.
 * La búsqueda está acotada para evitar peticiones interminables.
 */
final class GeneradorAutomaticoHorario
{
    public function __construct(private readonly ValidadorConflictosHorario $validador)
    {
    }

    /**
     * @param array<string, array> $datos Contexto normalizado del preparador.
     * @return array{exito: bool, detalles: array, errores: array, intentos: int}
     */
    public function generar(array $datos, int $seccionId, int $limiteIntentos = 30000): array
    {
        if ($limiteIntentos < 1) {
            return $this->fallo('El límite de intentos debe ser mayor que cero.', 0);
        }

        $secciones = $this->indexar($datos['secciones'] ?? []);
        $seccion = $secciones[$seccionId] ?? null;
        if (!$seccion) {
            return $this->fallo('La sección no existe en el contexto.', 0);
        }

        $bloques = $this->indexar($datos['bloques'] ?? []);
        $cargas = array_values(array_filter($datos['cargas'] ?? [], fn ($c) => (int) ($c['seccion_id'] ?? 0) === $seccionId && ($c['activo'] ?? false)));
        $asignaciones = $datos['asignaciones'] ?? [];
        $disponibilidades = $datos['disponibilidades'] ?? [];
        $restricciones = $datos['restricciones'] ?? [];
        $ocupaciones = $datos['ocupaciones'] ?? [];

        $periodos = array_values(array_filter($bloques, fn ($b) => (int) ($b['jornada_id'] ?? 0) === (int) $seccion['jornada_id'] && ($b['tipo'] ?? '') === 'CLASE' && $this->intervalo($b) !== null));
        usort($periodos, fn ($a, $b) => strcmp($a['hora_inicio'], $b['hora_inicio']) ?: ((int) $a['id'] <=> (int) $b['id']));

        $tareas = [];
        foreach ($cargas as $carga) {
            $horas = (int) ($carga['horas_semanales'] ?? 0);
            if ($horas < 0) {
                return $this->fallo('La carga académica contiene horas semanales negativas.', 0);
            }
            $docentes = [];
            foreach ($asignaciones as $asignacion) {
                if ((int) ($asignacion['carga_academica_id'] ?? 0) === (int) $carga['id'] && ($asignacion['activo'] ?? false) && (int) ($asignacion['docente_id'] ?? 0) > 0) {
                    $docentes[(int) $asignacion['docente_id']] = true;
                }
            }
            if ($horas > 0 && !$docentes) {
                return $this->fallo("La carga {$carga['id']} no tiene docente asignado vigente.", 0);
            }
            for ($i = 0; $i < $horas; $i++) {
                $tareas[] = ['carga_id' => (int) $carga['id'], 'docentes' => array_keys($docentes)];
            }
        }

        if (count($tareas) > count($periodos) * 7) {
            return $this->fallo('No existen suficientes períodos de clase para cubrir las cargas semanales.', 0);
        }

        $candidatos = [];
        foreach ($tareas as $i => $tarea) {
            $opciones = [];
            foreach ($tarea['docentes'] as $docenteId) {
                foreach (range(1, 7) as $dia) {
                    foreach ($periodos as $bloque) {
                        $detalle = [
                            'seccion_id' => $seccionId,
                            'dia_semana' => $dia,
                            'bloque_horario_id' => (int) $bloque['id'],
                            'carga_academica_id' => $tarea['carga_id'],
                            'docente_id' => $docenteId,
                        ];
                        if ($this->candidatoPermitido($detalle, $bloque, $disponibilidades, $restricciones, $ocupaciones, $bloques)) {
                            $opciones[] = ['detalle' => $detalle, 'intervalo' => $this->intervalo($bloque)];
                        }
                    }
                }
            }
            if (!$opciones) {
                return $this->fallo("No hay períodos disponibles para la carga {$tarea['carga_id']}.", 0);
            }
            $candidatos[$i] = $opciones;
        }

        // Primero se resuelven las tareas más difíciles.
        $orden = array_keys($tareas);
        usort($orden, fn ($a, $b) => count($candidatos[$a]) <=> count($candidatos[$b]) ?: ($tareas[$a]['carga_id'] <=> $tareas[$b]['carga_id']));
        $elegidos = [];
        $intentos = 0;
        $ultimaValidacion = [];
        $resolver = function (int $posicion) use (&$resolver, &$elegidos, &$intentos, &$ultimaValidacion, $orden, $candidatos, $datos, $limiteIntentos): bool {
            if ($posicion === count($orden)) {
                $propuesta = array_values(array_map(fn ($item) => $item['detalle'], $elegidos));
                $contexto = $datos;
                $contexto['detalles'] = $propuesta;
                $resultado = $this->validador->validar($contexto);
                if ($resultado['valido']) {
                    return true;
                }
                $ultimaValidacion = $resultado['errores'];
                return false;
            }
            foreach ($candidatos[$orden[$posicion]] as $opcion) {
                if ($intentos >= $limiteIntentos) {
                    return false;
                }
                $intentos++;
                if ($this->choca($opcion, $elegidos)) {
                    continue;
                }
                $elegidos[] = $opcion;
                if ($resolver($posicion + 1)) {
                    return true;
                }
                array_pop($elegidos);
            }
            return false;
        };

        if (!$resolver(0)) {
            $mensaje = $intentos >= $limiteIntentos
                ? 'Se alcanzó el límite de búsqueda sin encontrar un horario válido.'
                : 'No existe una combinación válida con los datos y restricciones proporcionados.';
            return ['exito' => false, 'detalles' => [], 'errores' => array_values(array_unique(array_merge([$mensaje], $ultimaValidacion))), 'intentos' => $intentos];
        }

        $detalles = array_values(array_map(fn ($item) => $item['detalle'], $elegidos));
        usort($detalles, fn ($a, $b) => ($a['dia_semana'] <=> $b['dia_semana']) ?: ($a['bloque_horario_id'] <=> $b['bloque_horario_id']));
        return ['exito' => true, 'detalles' => $detalles, 'errores' => [], 'intentos' => $intentos];
    }

    private function candidatoPermitido(array $detalle, array $bloque, array $disponibilidades, array $restricciones, array $ocupaciones, array $bloques): bool
    {
        [$inicio, $fin] = $this->intervalo($bloque);
        $permitidas = [];
        foreach ($disponibilidades as $franja) {
            if ((int) ($franja['docente_id'] ?? 0) !== $detalle['docente_id'] || (int) ($franja['dia_semana'] ?? 0) !== $detalle['dia_semana']) {
                continue;
            }
            $intervalo = $this->intervalo($franja);
            if ($intervalo === null) {
                continue;
            }
            if (!($franja['disponible'] ?? false) && $this->solapa([$inicio, $fin], $intervalo)) {
                return false;
            }
            if ($franja['disponible'] ?? false) {
                $permitidas[] = $intervalo;
            }
        }
        usort($permitidas, fn ($a, $b) => $a[0] <=> $b[0]);
        $cursor = $inicio;
        foreach ($permitidas as [$desde, $hasta]) {
            if ($desde > $cursor) {
                break;
            }
            $cursor = max($cursor, $hasta);
        }
        if ($cursor < $fin) {
            return false;
        }

        foreach ($restricciones as $regla) {
            if (!($regla['obligatoria'] ?? false) || ($regla['tipo'] ?? '') !== 'BLOQUEO_PERIODO') {
                continue;
            }
            $p = $regla['parametros'] ?? [];
            if ((int) ($p['dia_semana'] ?? 0) !== $detalle['dia_semana'] || (int) ($p['bloque_horario_id'] ?? 0) !== $detalle['bloque_horario_id']) {
                continue;
            }
            $seccion = (int) ($regla['seccion_id'] ?? 0);
            $docente = (int) ($regla['docente_id'] ?? 0);
            if (($seccion || $docente) && (!$seccion || $seccion === $detalle['seccion_id']) && (!$docente || $docente === $detalle['docente_id'])) {
                return false;
            }
        }
        foreach ($ocupaciones as $ocupacion) {
            if ((int) ($ocupacion['docente_id'] ?? 0) !== $detalle['docente_id'] || (int) ($ocupacion['dia_semana'] ?? 0) !== $detalle['dia_semana']) {
                continue;
            }
            $externo = $bloques[(int) ($ocupacion['bloque_horario_id'] ?? 0)] ?? null;
            if ($externo && ($intervalo = $this->intervalo($externo)) && $this->solapa([$inicio, $fin], $intervalo)) {
                return false;
            }
        }
        return true;
    }

    private function choca(array $opcion, array $elegidos): bool
    {
        foreach ($elegidos as $elegido) {
            if ($elegido['detalle']['dia_semana'] !== $opcion['detalle']['dia_semana']) {
                continue;
            }
            if ($this->solapa($opcion['intervalo'], $elegido['intervalo'])) {
                // Todas las tareas pertenecen a la misma sección.
                return true;
            }
        }
        return false;
    }

    private function intervalo(array $fila): ?array
    {
        $inicio = (string) ($fila['hora_inicio'] ?? '');
        $fin = (string) ($fila['hora_fin'] ?? '');
        if (!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d(?::[0-5]\d)?$/', $inicio) || !preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d(?::[0-5]\d)?$/', $fin)) {
            return null;
        }
        $a = $this->minutos($inicio);
        $b = $this->minutos($fin);
        return $a < $b ? [$a, $b] : null;
    }

    private function minutos(string $hora): int
    {
        $partes = explode(':', $hora);
        return (int) $partes[0] * 60 + (int) $partes[1];
    }

    private function solapa(array $a, array $b): bool
    {
        return $a[0] < $b[1] && $b[0] < $a[1];
    }

    private function indexar(array $filas): array
    {
        $salida = [];
        foreach ($filas as $fila) {
            $salida[(int) ($fila['id'] ?? 0)] = $fila;
        }
        return $salida;
    }

    private function fallo(string $mensaje, int $intentos): array
    {
        return ['exito' => false, 'detalles' => [], 'errores' => [$mensaje], 'intentos' => $intentos];
    }
}
