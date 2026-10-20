<?php

namespace App\Services\Horarios;

use App\Models\CicloEscolar;
use App\Models\Seccion;
use Carbon\CarbonImmutable;
use InvalidArgumentException;

/**
 * Orquesta la generación de propuestas para las secciones activas de un ciclo.
 * No guarda ni aprueba horarios.
 */
final class GeneradorHorariosCicloEscolar
{
    public function __construct(
        private readonly PreparadorDatosHorario $preparador,
        private readonly GeneradorCoordinadoHorarios $coordinador,
    ) {}

    /**
     * @return array{exito:bool,horarios:array,errores:array,intentos:int,ciclo_escolar_id:int,secciones:array}
     */
    public function generar(int $cicloEscolarId, ?string $fechaReferencia = null, int $limiteIntentosPorSeccion = 30000): array
    {
        if ($cicloEscolarId <= 0) {
            throw new InvalidArgumentException('El identificador del ciclo escolar debe ser positivo.');
        }

        $fecha = $fechaReferencia ?? CarbonImmutable::today()->toDateString();
        if (!CarbonImmutable::hasFormat($fecha, 'Y-m-d')) {
            throw new InvalidArgumentException('La fecha de referencia debe tener formato Y-m-d.');
        }

        $ciclo = CicloEscolar::query()->findOrFail($cicloEscolarId);
        if ($ciclo->estado === 'CERRADO') {
            return $this->fallo($cicloEscolarId, 'No se pueden generar horarios de un ciclo escolar cerrado.');
        }

        if ($limiteIntentosPorSeccion < 1) {
            return $this->fallo($cicloEscolarId, 'El límite de intentos debe ser mayor que cero.');
        }

        $secciones = Seccion::query()
            ->where('ciclo_escolar_id', $cicloEscolarId)
            ->where('activo', true)
            ->orderBy('id')
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        if (!$secciones) {
            return $this->fallo($cicloEscolarId, 'El ciclo escolar no tiene secciones activas.');
        }

        $contextos = [];
        foreach ($secciones as $seccionId) {
            $contexto = $this->preparador->preparar($seccionId, [], $fecha);
            $cargasActivas = array_filter($contexto['cargas'], fn ($carga) => (bool) ($carga['activo'] ?? false));
            if (!$cargasActivas) {
                return $this->fallo(
                    $cicloEscolarId,
                    "La sección {$seccionId} no tiene cargas académicas activas.",
                    $secciones,
                );
            }
            $contextos[$seccionId] = $contexto;
        }

        $resultado = $this->coordinador->generar($contextos, $limiteIntentosPorSeccion);

        return [
            'exito' => (bool) $resultado['exito'],
            'horarios' => $resultado['exito'] ? $resultado['horarios'] : [],
            'errores' => $resultado['errores'],
            'intentos' => (int) $resultado['intentos'],
            'ciclo_escolar_id' => $cicloEscolarId,
            'secciones' => $secciones,
        ];
    }

    private function fallo(int $cicloId, string $mensaje, array $secciones = []): array
    {
        return [
            'exito' => false,
            'horarios' => [],
            'errores' => [$mensaje],
            'intentos' => 0,
            'ciclo_escolar_id' => $cicloId,
            'secciones' => $secciones,
        ];
    }
}
