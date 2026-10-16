<?php

namespace App\Services;

use App\Models\ContactoEmergencia;
use App\Models\Estudiante;
use App\Models\InformacionComplementariaEstudiante;
use App\Models\InformacionMedicaEstudiante;
use App\Models\Persona;
use App\Models\PersonaAutorizadaRetiro;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RegistroInformacionComplementariaInscripcionService
{
    public function registrar(Estudiante $estudiante, array $datos): void
    {
        DB::transaction(function () use ($estudiante, $datos) {
            foreach (['informacion_medica', 'contactos_emergencia', 'personas_autorizadas_retiro'] as $campo) {
                if (!array_key_exists($campo, $datos) || !is_array($datos[$campo])) {
                    throw ValidationException::withMessages([
                        $campo => 'Debe completar o confirmar esta información.',
                    ]);
                }
            }

            $this->registrarInformacionMedica(
                $estudiante,
                $datos['informacion_medica'],
                $datos['seguro_medico'] ?? null
            );
            $this->registrarInformacionComplementaria(
                $estudiante,
                $datos['informacion_complementaria'] ?? []
            );
            $this->registrarContactosEmergencia($estudiante, $datos['contactos_emergencia']);
            $this->registrarPersonasAutorizadas($estudiante, $datos['personas_autorizadas_retiro']);
        }, 3);
    }

    private function registrarInformacionMedica(Estudiante $estudiante, array $datos, mixed $seguroMedico): void
    {
        $atributos = [
            'tipo_sangre' => $this->texto($datos['tipo_sangre'] ?? null),
            'alergias' => $this->texto($datos['alergias'] ?? null),
            'condiciones_relevantes' => $this->texto($datos['condiciones_relevantes'] ?? null),
            'medicamentos' => $this->texto($datos['medicamentos'] ?? null),
            'indicaciones_emergencia' => $this->texto($datos['indicaciones_emergencia'] ?? null),
            'seguro_medico' => $this->texto($seguroMedico),
        ];

        InformacionMedicaEstudiante::query()->updateOrCreate(
            ['estudiante_id' => $estudiante->id],
            $atributos
        );
    }

    private function registrarInformacionComplementaria(Estudiante $estudiante, array $datos): void
    {
        $atributos = [
            'religion' => $this->texto($datos['religion'] ?? null),
            'telefono_casa' => $this->texto($datos['telefono_casa'] ?? null),
            'transporte' => $this->texto($datos['transporte'] ?? null),
            'numero_hermanos' => $this->enteroOpcional($datos['numero_hermanos'] ?? null),
            'hermanos_mayores' => $this->enteroOpcional($datos['hermanos_mayores'] ?? null),
            'hermanos_menores' => $this->enteroOpcional($datos['hermanos_menores'] ?? null),
            'hermanos_inscritos' => $this->texto($datos['hermanos_inscritos'] ?? null),
        ];

        InformacionComplementariaEstudiante::query()->updateOrCreate(
            ['estudiante_id' => $estudiante->id],
            $atributos
        );
    }

    private function registrarContactosEmergencia(Estudiante $estudiante, array $contactos): void
    {
        if (empty($contactos)) {
            throw ValidationException::withMessages([
                'contactos_emergencia' => 'Debe registrar al menos un contacto de emergencia.',
            ]);
        }

        $normalizados = [];
        $telefonos = [];

        foreach ($contactos as $indice => $contacto) {
            if (!is_array($contacto)) {
                throw ValidationException::withMessages([
                    "contactos_emergencia.$indice" => 'Los datos del contacto no son válidos.',
                ]);
            }

            $nombre = $this->texto($contacto['nombre'] ?? null);
            $parentesco = $this->texto($contacto['parentesco'] ?? null);
            $telefono = $this->texto($contacto['telefono'] ?? null);

            if (!$nombre || !$parentesco || !$telefono) {
                throw ValidationException::withMessages([
                    "contactos_emergencia.$indice" => 'Cada contacto debe incluir nombre, parentesco y teléfono.',
                ]);
            }

            $telefonoNormalizado = preg_replace('/\D/', '', $telefono);
            if ($telefonoNormalizado === '') {
                throw ValidationException::withMessages([
                    "contactos_emergencia.$indice.telefono" => 'El teléfono del contacto no es válido.',
                ]);
            }

            if (in_array($telefonoNormalizado, $telefonos, true)) {
                throw ValidationException::withMessages([
                    'contactos_emergencia' => 'No puede registrar dos contactos con el mismo teléfono.',
                ]);
            }

            $telefonos[] = $telefonoNormalizado;
            $normalizados[] = [
                'nombre' => $nombre,
                'parentesco' => $parentesco,
                'telefono' => $telefono,
                'prioridad' => count($normalizados) + 1,
            ];
        }

        $existentes = ContactoEmergencia::query()
            ->where('estudiante_id', $estudiante->id)
            ->orderBy('id')
            ->lockForUpdate()
            ->get();

        foreach ($normalizados as $indice => $atributos) {
            $existente = $existentes->get($indice);
            if ($existente) {
                $existente->update($atributos);
            } else {
                ContactoEmergencia::query()->create(array_merge($atributos, [
                    'estudiante_id' => $estudiante->id,
                ]));
            }
        }

        $sobrantes = $existentes->slice(count($normalizados))->pluck('id')->all();
        if (!empty($sobrantes)) {
            ContactoEmergencia::query()
                ->where('estudiante_id', $estudiante->id)
                ->whereIn('id', $sobrantes)
                ->delete();
        }
    }

    private function registrarPersonasAutorizadas(Estudiante $estudiante, array $personas): void
    {
        $normalizadas = [];
        $identificaciones = [];

        foreach ($personas as $indice => $datos) {
            if (!is_array($datos)) {
                throw ValidationException::withMessages([
                    "personas_autorizadas_retiro.$indice" => 'Los datos de la persona autorizada no son válidos.',
                ]);
            }

            $nombre = $this->texto($datos['nombre'] ?? null);
            $identificacion = $this->texto($datos['identificacion'] ?? null);
            $telefono = $this->texto($datos['telefono'] ?? null);
            $parentesco = $this->texto($datos['parentesco'] ?? null);

            if (!$nombre || !$identificacion || !$telefono) {
                throw ValidationException::withMessages([
                    "personas_autorizadas_retiro.$indice" => 'Cada persona autorizada debe tener nombre, identificación y teléfono.',
                ]);
            }

            $clave = strtoupper(preg_replace('/[\s-]+/', '', $identificacion));
            if (in_array($clave, $identificaciones, true)) {
                throw ValidationException::withMessages([
                    'personas_autorizadas_retiro' => 'No puede registrar dos veces la misma identificación.',
                ]);
            }

            $identificaciones[] = $clave;
            $normalizadas[] = [
                'nombre' => $nombre,
                'identificacion' => $identificacion,
                'telefono' => $telefono,
                'parentesco' => $parentesco,
                'clave' => $clave,
            ];
        }

        $existentes = PersonaAutorizadaRetiro::query()
            ->where('estudiante_id', $estudiante->id)
            ->lockForUpdate()
            ->get();

        $idsActivos = [];
        foreach ($normalizadas as $datos) {
            $registro = $existentes->first(function ($existente) use ($datos) {
                $claveExistente = strtoupper(preg_replace(
                    '/[\s-]+/',
                    '',
                    (string) $existente->identificacion
                ));
                return $claveExistente === $datos['clave'];
            });

            $personaId = $this->buscarPersonaId($datos['identificacion']);
            $atributos = [
                'persona_id' => $personaId,
                'nombre' => $datos['nombre'],
                'identificacion' => $datos['identificacion'],
                'telefono' => $datos['telefono'],
                'parentesco' => $datos['parentesco'],
                'activo' => true,
            ];

            if ($registro) {
                $registro->update($atributos);
            } else {
                $registro = PersonaAutorizadaRetiro::query()->create(array_merge($atributos, [
                    'estudiante_id' => $estudiante->id,
                ]));
            }
            $idsActivos[] = $registro->id;
        }

        $consulta = PersonaAutorizadaRetiro::query()->where('estudiante_id', $estudiante->id);
        if (!empty($idsActivos)) {
            $consulta->whereNotIn('id', $idsActivos);
        }
        $consulta->update(['activo' => false]);
    }

    private function buscarPersonaId(string $identificacion): ?int
    {
        $cui = preg_replace('/\D/', '', $identificacion);
        if (strlen($cui) !== 13) {
            return null;
        }
        return Persona::query()->where('cui', $cui)->value('id');
    }

    private function enteroOpcional(mixed $valor): ?int
    {
        if ($valor === null || $valor === '') {
            return null;
        }
        if (filter_var($valor, FILTER_VALIDATE_INT) === false || (int) $valor < 0 || (int) $valor > 65535) {
            throw ValidationException::withMessages([
                'informacion_complementaria' => 'Las cantidades de hermanos deben ser números enteros válidos.',
            ]);
        }
        return (int) $valor;
    }

    private function texto(mixed $valor): ?string
    {
        if ($valor === null) {
            return null;
        }
        $texto = trim((string) $valor);
        return $texto === '' ? null : $texto;
    }
}
