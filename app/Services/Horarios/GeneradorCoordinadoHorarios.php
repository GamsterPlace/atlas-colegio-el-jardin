<?php

namespace App\Services\Horarios;

use InvalidArgumentException;

/**
 * Coordina propuestas en memoria. No persiste horarios.
 * Los contextos deben corresponder al mismo ciclo escolar y fecha de referencia.
 */
final class GeneradorCoordinadoHorarios
{
    public function __construct(
        private readonly GeneradorAutomaticoHorario $generador,
        private readonly ValidadorConflictosHorario $validador,
    ) {}

    /**
     * @param array<int, array<string, array>> $contextosPorSeccion Contextos de PreparadorDatosHorario::preparar().
     * @return array{exito:bool, horarios:array, errores:array, intentos:int}
     */
    public function generar(array $contextosPorSeccion, int $limiteIntentosPorSeccion = 30000): array
    {
        if (!$contextosPorSeccion) {
            return $this->fallo('Debe indicar al menos una sección.');
        }
        if ($limiteIntentosPorSeccion < 1) {
            return $this->fallo('El límite de intentos debe ser mayor que cero.');
        }

        $contextos = [];
        $bloquesGlobales = [];
        foreach ($contextosPorSeccion as $clave => $contexto) {
            $id = filter_var($clave, FILTER_VALIDATE_INT);
            if ($id === false || $id <= 0 || !is_array($contexto)) {
                throw new InvalidArgumentException('Cada contexto debe tener un ID de sección positivo.');
            }
            if (isset($contextos[$id])) {
                throw new InvalidArgumentException('Hay secciones duplicadas.');
            }
            foreach (['secciones', 'bloques', 'cargas', 'asignaciones', 'disponibilidades', 'restricciones', 'ocupaciones'] as $campo) {
                if (!isset($contexto[$campo]) || !is_array($contexto[$campo])) {
                    throw new InvalidArgumentException("Falta el conjunto de datos: {$campo}.");
                }
            }
            if (!array_filter($contexto['secciones'], fn ($s) => (int) ($s['id'] ?? 0) === $id)) {
                throw new InvalidArgumentException("La sección {$id} no existe en su contexto.");
            }
            foreach ($contexto['bloques'] as $bloque) {
                $bloqueId = (int) ($bloque['id'] ?? 0);
                if ($bloqueId <= 0 || (isset($bloquesGlobales[$bloqueId]) && $bloquesGlobales[$bloqueId] !== $bloque)) {
                    throw new InvalidArgumentException('Los bloques contienen IDs inválidos o definiciones inconsistentes.');
                }
                $bloquesGlobales[$bloqueId] = $bloque;
            }
            $contextos[$id] = $contexto;
        }

        // Se generan primero las secciones con menos períodos libres estimados.
        $orden = array_keys($contextos);
        usort($orden, function (int $a, int $b) use ($contextos): int {
            $opciones = function (int $id) use ($contextos): int {
                $c = $contextos[$id];
                $seccion = current(array_filter($c['secciones'], fn ($s) => (int) $s['id'] === $id));
                return count(array_filter($c['bloques'], fn ($b) => (int) ($b['jornada_id'] ?? 0) === (int) $seccion['jornada_id'] && ($b['tipo'] ?? '') === 'CLASE'));
            };
            return $opciones($a) <=> $opciones($b) ?: $a <=> $b;
        });

        $horarios = [];
        $intentos = 0;
        foreach ($orden as $id) {
            $contexto = $contextos[$id];
            // Para comprobar cruces entre jornadas se necesitan todos los bloques involucrados.
            $contexto['bloques'] = array_values($bloquesGlobales);
            foreach ($horarios as $detalles) {
                foreach ($detalles as $detalle) {
                    $contexto['ocupaciones'][] = [
                        'docente_id' => $detalle['docente_id'],
                        'dia_semana' => $detalle['dia_semana'],
                        'bloque_horario_id' => $detalle['bloque_horario_id'],
                    ];
                }
            }
            $resultado = $this->generador->generar($contexto, $id, $limiteIntentosPorSeccion);
            $intentos += $resultado['intentos'];
            if (!$resultado['exito']) {
                return [
                    'exito' => false, 'horarios' => [], 'intentos' => $intentos,
                    'errores' => array_merge(["No se pudo completar la sección {$id}."], $resultado['errores']),
                ];
            }
            $contexto['detalles'] = $resultado['detalles'];
            $validacion = $this->validador->validar($contexto);
            if (!$validacion['valido']) {
                return [
                    'exito' => false, 'horarios' => [], 'intentos' => $intentos,
                    'errores' => array_merge(["La sección {$id} no superó la validación."], $validacion['errores']),
                ];
            }
            $horarios[$id] = $resultado['detalles'];
        }
        return ['exito' => true, 'horarios' => $horarios, 'errores' => [], 'intentos' => $intentos];
    }

    private function fallo(string $error): array
    {
        return ['exito' => false, 'horarios' => [], 'errores' => [$error], 'intentos' => 0];
    }
}
