<?php

namespace App\Services;

use App\Models\Estudiante;
use App\Models\EstudianteResponsable;
use App\Models\Persona;
use App\Models\Responsable;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RegistroResponsablesInscripcionService
{
    public function registrar(
        Estudiante $estudiante,
        array $responsables
    ): Responsable {
        return DB::transaction(function () use (
            $estudiante,
            $responsables
        ) {
            if (empty($responsables)) {
                throw ValidationException::withMessages([
                    'responsables' =>
                        'Debe registrar al menos un responsable.',
                ]);
            }

            $responsableFinanciero = null;
            $cantidadFinancieros = 0;
            $cantidadPrincipales = 0;
            $responsablesProcesados = [];

            foreach ($responsables as $indice => $datos) {
                $datos = $this->validarDatos(
                    $datos,
                    $indice
                );

                $persona = $this->obtenerOCrearPersona($datos);

                $responsable = Responsable::query()
                    ->firstOrCreate(
                        ['persona_id' => $persona->id],
                        ['activo' => true]
                    );

                if (!$responsable->activo) {
                    $responsable->update([
                        'activo' => true,
                    ]);
                }

                if (in_array(
                    $responsable->id,
                    $responsablesProcesados,
                    true
                )) {
                    throw ValidationException::withMessages([
                        'responsables' =>
                            'No puede registrar dos veces a la misma persona.',
                    ]);
                }

                $responsablesProcesados[] = $responsable->id;

                $esFinanciero = $datos['es_responsable_financiero'];
                $esPrincipal = $datos['es_contacto_principal'];

                if ($esFinanciero) {
                    $cantidadFinancieros++;
                    $responsableFinanciero = $responsable;
                }

                if ($esPrincipal) {
                    $cantidadPrincipales++;
                }

                $this->vincularEstudiante(
                    $estudiante,
                    $responsable,
                    $datos
                );
            }

            if ($cantidadFinancieros !== 1) {
                throw ValidationException::withMessages([
                    'responsables' =>
                        'Debe seleccionar exactamente un responsable financiero.',
                ]);
            }

            if ($cantidadPrincipales !== 1) {
                throw ValidationException::withMessages([
                    'responsables' =>
                        'Debe seleccionar exactamente un contacto principal.',
                ]);
            }

            return $responsableFinanciero;
        }, 3);
    }

    private function validarDatos(
        array $datos,
        int $indice
    ): array {
        $nombres = trim((string) ($datos['nombres'] ?? ''));
        $apellidos = trim((string) ($datos['apellidos'] ?? ''));
        $cui = preg_replace(
            '/\D/',
            '',
            (string) ($datos['cui'] ?? '')
        );

        if ($nombres === '' || $apellidos === '') {
            throw ValidationException::withMessages([
                "responsables.$indice.nombres" =>
                    'Los nombres y apellidos son obligatorios.',
            ]);
        }

        if (strlen($cui) !== 13) {
            throw ValidationException::withMessages([
                "responsables.$indice.cui" =>
                    'El CUI debe contener exactamente 13 dígitos.',
            ]);
        }

        $parentesco = trim(
            (string) ($datos['parentesco'] ?? '')
        );

        if ($parentesco === '') {
            throw ValidationException::withMessages([
                "responsables.$indice.parentesco" =>
                    'Debe indicar el parentesco.',
            ]);
        }

        return [
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'cui' => $cui,
            'fecha_nacimiento' =>
                $datos['fecha_nacimiento'] ?? null,
            'sexo' => $datos['sexo'] ?? null,
            'telefono' => $datos['telefono'] ?? null,
            'correo' => $datos['correo'] ?? null,
            'direccion' => $datos['direccion'] ?? null,
            'parentesco' => $parentesco,
            'es_responsable_financiero' =>
                $this->aBooleano(
                    $datos['es_responsable_financiero'] ?? false
                ),
            'es_contacto_principal' =>
                $this->aBooleano(
                    $datos['es_contacto_principal'] ?? false
                ),
            'autorizado_recoger' =>
                $this->aBooleano(
                    $datos['autorizado_recoger'] ?? false
                ),
        ];
    }

    private function obtenerOCrearPersona(array $datos): Persona
    {
        $persona = Persona::query()
            ->where('cui', $datos['cui'])
            ->lockForUpdate()
            ->first();

        if ($persona) {
            return $persona;
        }

        return Persona::query()->create([
            'nombres' => $datos['nombres'],
            'apellidos' => $datos['apellidos'],
            'cui' => $datos['cui'],
            'fecha_nacimiento' => $datos['fecha_nacimiento'],
            'sexo' => $datos['sexo'],
            'telefono' => $datos['telefono'],
            'correo' => $datos['correo'],
            'direccion' => $datos['direccion'],
        ]);
    }

    private function vincularEstudiante(
        Estudiante $estudiante,
        Responsable $responsable,
        array $datos
    ): void {
        $relacion = EstudianteResponsable::query()
            ->where('estudiante_id', $estudiante->id)
            ->where('responsable_id', $responsable->id)
            ->lockForUpdate()
            ->first();

        $atributos = [
            'parentesco' => $datos['parentesco'],
            'es_responsable_financiero' =>
                $datos['es_responsable_financiero'],
            'es_contacto_principal' =>
                $datos['es_contacto_principal'],
            'autorizado_recoger' =>
                $datos['autorizado_recoger'],
            'fecha_fin' => null,
        ];

        if ($relacion) {
            $relacion->update($atributos);

            return;
        }

        EstudianteResponsable::query()->create(
            array_merge($atributos, [
                'estudiante_id' => $estudiante->id,
                'responsable_id' => $responsable->id,
                'fecha_inicio' => now()->toDateString(),
            ])
        );
    }

    private function aBooleano(mixed $valor): bool
    {
        return filter_var(
            $valor,
            FILTER_VALIDATE_BOOLEAN
        );
    }
}
