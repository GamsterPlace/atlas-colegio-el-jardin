<?php

namespace App\Services;

use App\Models\CicloEscolar;
use App\Models\Estudiante;
use App\Models\Inscripcion;
use App\Models\Seccion;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InscripcionService
{
    public function __construct(
        private readonly GeneradorCarnetEstudiantil $generadorCarnet,
        private readonly RegistroResponsablesInscripcionService $registroResponsables,
        private readonly RegistroInformacionComplementariaInscripcionService $registroInformacionComplementaria,
        private readonly GeneracionFinancieraInscripcionService $generacionFinanciera,
        private readonly GeneracionAccesosPadresInscripcionService $generacionAccesosPadres
    ) {
    }

    private array $credencialesIniciales = [];

    public function credencialesIniciales(): array
    {
        return $this->credencialesIniciales;
    }

    public function registrar(array $datos, int $usuarioId): Inscripcion
    {
        $this->credencialesIniciales = [];

        return DB::transaction(function () use ($datos, $usuarioId) {
            $ciclo = CicloEscolar::query()
                ->whereKey($datos['ciclo_escolar_id'])
                ->whereIn('estado', ['PLANIFICACION', 'ACTIVO'])
                ->firstOrFail();

            $seccion = Seccion::query()
                ->whereKey($datos['seccion_id'])
                ->where('ciclo_escolar_id', $ciclo->id)
                ->where('activo', true)
                ->firstOrFail();

            $tipo = $datos['tipo_inscripcion'] ?? null;

            if ($tipo === 'NUEVA') {
                $estudiante = $this->crearEstudiante($datos, $ciclo);
            } elseif ($tipo === 'REINSCRIPCION') {
                $estudiante = $this->obtenerEstudianteExistente($datos);
            } else {
                throw ValidationException::withMessages([
                    'tipo_inscripcion' => 'Seleccione un tipo de inscripción válido.',
                ]);
            }

            $this->verificarInscripcionExistente($estudiante->id, $ciclo->id);

            if (empty($datos['responsables']) || !is_array($datos['responsables'])) {
                throw ValidationException::withMessages([
                    'responsables' => 'Debe registrar los responsables del estudiante.',
                ]);
            }

            $responsableFinanciero = $this->registroResponsables->registrar(
                $estudiante,
                $datos['responsables']
            );

            $this->registroInformacionComplementaria->registrar(
                $estudiante,
                [
                    'informacion_medica' => $datos['informacion_medica'] ?? [],
                    'contactos_emergencia' => $datos['contactos_emergencia'] ?? [],
                    'personas_autorizadas_retiro' => $datos['personas_autorizadas_retiro'] ?? [],
                    'informacion_complementaria' => [
                        'religion' => $datos['religion'] ?? null,
                        'telefono_casa' => $datos['telefono_casa'] ?? null,
                        'transporte' => $datos['transporte'] ?? null,
                        'numero_hermanos' => $datos['numero_hermanos'] ?? null,
                        'hermanos_mayores' => $datos['hermanos_mayores'] ?? null,
                        'hermanos_menores' => $datos['hermanos_menores'] ?? null,
                        'hermanos_inscritos' => $datos['hermanos_inscritos'] ?? null,
                    ],
                    'seguro_medico' => $datos['seguro_medico'] ?? null,
                ]
            );

            $inscripcion = Inscripcion::query()->create([
                'estudiante_id' => $estudiante->id,
                'ciclo_escolar_id' => $ciclo->id,
                'seccion_id' => $seccion->id,
                'responsable_financiero_id' => $responsableFinanciero->id,
                'fecha_inscripcion' => now()->toDateString(),
                'fecha_inicio' => $ciclo->fecha_inicio,
                'fecha_fin' => null,
                'estado' => 'COMPLETADA',
                'observaciones' => $datos['observaciones'] ?? null,
            ]);

            $this->generacionFinanciera->generar($inscripcion, $usuarioId);

            $this->credencialesIniciales = $this->generacionAccesosPadres->generar($estudiante);

            return $inscripcion->load(['estudiante', 'ciclo_escolar', 'seccion']);
        }, 3);
    }

    private function crearEstudiante(array $datos, CicloEscolar $ciclo): Estudiante
    {
        $codigo = trim((string) ($datos['codigo'] ?? ''));

        if ($codigo === '') {
            throw ValidationException::withMessages([
                'codigo' => 'Debe ingresar el código histórico del estudiante.',
            ]);
        }

        if (Estudiante::withTrashed()->where('codigo', $codigo)->exists()) {
            throw ValidationException::withMessages([
                'codigo' => 'El código histórico ya pertenece a otro estudiante.',
            ]);
        }

        $cui = preg_replace('/\D/', '', (string) ($datos['cui'] ?? ''));

        if (strlen($cui) !== 13) {
            throw ValidationException::withMessages([
                'cui' => 'El CUI del estudiante debe tener exactamente 13 dígitos.',
            ]);
        }

        if (Estudiante::withTrashed()->where('cui', $cui)->exists()) {
            throw ValidationException::withMessages([
                'cui' => 'El CUI ya pertenece a otro estudiante.',
            ]);
        }

        $anio = (int) substr((string) $ciclo->fecha_inicio, 0, 4);
        $numeroCarnet = $this->generadorCarnet->generar($anio, $cui);

        return Estudiante::query()->create([
            'codigo' => $codigo,
            'numero_carnet' => $numeroCarnet,
            'cui' => $cui,
            'lugar_nacimiento' => $this->texto($datos['lugar_nacimiento'] ?? null),
            'nombres' => trim((string) $datos['nombres']),
            'apellidos' => trim((string) $datos['apellidos']),
            'fecha_nacimiento' => $datos['fecha_nacimiento'] ?? null,
            'sexo' => $datos['sexo'] ?? null,
            'direccion' => $datos['direccion'] ?? null,
            'estado' => 'ACTIVO',
        ]);
    }

    private function obtenerEstudianteExistente(array $datos): Estudiante
    {
        $estudianteId = $datos['estudiante_id'] ?? null;

        if (!$estudianteId) {
            throw ValidationException::withMessages([
                'estudiante_id' => 'Debe seleccionar un estudiante existente.',
            ]);
        }

        $estudiante = Estudiante::query()
            ->whereKey($estudianteId)
            ->lockForUpdate()
            ->firstOrFail();

        if ($estudiante->estado === 'EGRESADO') {
            throw ValidationException::withMessages([
                'estudiante_id' => 'No puede reinscribir un estudiante egresado.',
            ]);
        }

        if (!$estudiante->numero_carnet) {
            throw ValidationException::withMessages([
                'estudiante_id' => 'El estudiante no tiene un número de carné asignado.',
            ]);
        }

        $actualizacion = [
            'nombres' => trim((string) ($datos['nombres'] ?? $estudiante->nombres)),
            'apellidos' => trim((string) ($datos['apellidos'] ?? $estudiante->apellidos)),
            'fecha_nacimiento' => $datos['fecha_nacimiento'] ?? $estudiante->fecha_nacimiento,
            'sexo' => $datos['sexo'] ?? $estudiante->sexo,
            'direccion' => $datos['direccion'] ?? $estudiante->direccion,
            'estado' => 'ACTIVO',
        ];

        if (array_key_exists('lugar_nacimiento', $datos)) {
            $actualizacion['lugar_nacimiento'] = $this->texto($datos['lugar_nacimiento']);
        }

        $cui = preg_replace('/\D/', '', (string) ($datos['cui'] ?? ''));
        if ($cui !== '') {
            if (strlen($cui) !== 13) {
                throw ValidationException::withMessages([
                    'cui' => 'El CUI del estudiante debe tener exactamente 13 dígitos.',
                ]);
            }
            if ($estudiante->cui && $estudiante->cui !== $cui) {
                throw ValidationException::withMessages([
                    'cui' => 'El CUI no coincide con el registrado. Solicite una corrección de identidad.',
                ]);
            }
            if (Estudiante::withTrashed()->where('cui', $cui)->whereKeyNot($estudiante->id)->exists()) {
                throw ValidationException::withMessages([
                    'cui' => 'El CUI ya pertenece a otro estudiante.',
                ]);
            }
            $actualizacion['cui'] = $cui;
        }

        $estudiante->update($actualizacion);

        return $estudiante;
    }

    private function verificarInscripcionExistente(int $estudianteId, int $cicloEscolarId): void
    {
        if (Inscripcion::query()
            ->where('estudiante_id', $estudianteId)
            ->where('ciclo_escolar_id', $cicloEscolarId)
            ->exists()) {
            throw ValidationException::withMessages([
                'estudiante_id' => 'El estudiante ya tiene una inscripción registrada para este ciclo escolar.',
            ]);
        }
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
