<?php

namespace App\Services;

use App\Models\Estudiante;
use InvalidArgumentException;
use RuntimeException;

class GeneradorCarnetEstudiantil
{
    public function generar(int $anio, string $cui): string
    {
        $cui = preg_replace('/\D/', '', $cui);

        if (strlen($cui) !== 13) {
            throw new InvalidArgumentException(
                'El CUI del estudiante debe contener 13 dígitos.'
            );
        }

        if ($anio < 2000 || $anio > 9999) {
            throw new InvalidArgumentException(
                'El año del ciclo escolar no es válido.'
            );
        }

        $prefijo = $anio . '-' . substr($cui, 0, 5);

        for ($intento = 0; $intento < 1000; $intento++) {
            $consecutivo = str_pad(
                (string) random_int(0, 999),
                3,
                '0',
                STR_PAD_LEFT
            );

            $carnet = $prefijo . '-' . $consecutivo;

            if (!Estudiante::withTrashed()
                ->where('numero_carnet', $carnet)
                ->exists()) {
                return $carnet;
            }
        }

        throw new RuntimeException(
            'No fue posible generar un carné único.'
        );
    }
}
