<?php

namespace App\Services;

use App\Models\Estudiante;
use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class GeneracionAccesosPadresInscripcionService
{
    /** @return array<int, array{usuario:string,password:?string}> */
    public function generar(Estudiante $estudiante): array
    {
        $rol = Rol::query()->where('codigo', 'PADRE_FAMILIA')->where('activo', true)->first();

        if (!$rol) {
            throw ValidationException::withMessages([
                'responsables' => 'El rol PADRE_FAMILIA no está habilitado en ATLAS.',
            ]);
        }

        $vinculos = $estudiante->registrosEstudianteResponsable()
            ->whereIn('parentesco', ['PADRE', 'MADRE'])
            ->with('responsable.persona')
            ->get();

        $credenciales = [];
        foreach ($vinculos as $vinculo) {
            $persona = $vinculo->responsable?->persona;
            if (!$persona || !$vinculo->responsable->activo) {
                continue;
            }

            $usuario = Usuario::query()->where('persona_id', $persona->id)
                ->lockForUpdate()->first();

            $temporal = null;
            if (!$usuario) {
                $temporal = Str::password(16, true, true, true, false);
                $usuario = Usuario::query()->create([
                    'persona_id' => $persona->id,
                    'nombre_usuario' => $this->generarNombreUsuario($persona->nombres, $persona->apellidos),
                    'email' => null,
                    'password' => $temporal,
                    'debe_cambiar_password' => true,
                    'activo' => true,
                ]);
            }

            // Nunca restablecer ni reactivar una cuenta institucional existente.
            $usuario->roles()->syncWithoutDetaching([$rol->id]);
            $credenciales[$persona->id] = [
                'usuario' => $usuario->nombre_usuario,
                'password' => $temporal,
            ];
        }

        return $credenciales;
    }

    private function generarNombreUsuario(string $nombres, string $apellidos): string
    {
        $primerNombre = preg_split('/\s+/u', trim($nombres))[0] ?? '';
        $primerApellido = preg_split('/\s+/u', trim($apellidos))[0] ?? '';

        $base = Str::of($primerNombre.'.'.$primerApellido)
            ->ascii()
            ->lower()
            ->replaceMatches('/[^a-z0-9.]/', '')
            ->trim('.')
            ->toString();

        if ($base === '' || $base === '.') {
            $base = 'familia';
        }

        // Reservar cuatro caracteres para el sufijo numérico.
        $base = substr($base, 0, 95);

        // El sufijo es aleatorio, de cuatro dígitos, incluyendo ceros iniciales.
        // Se verifica unicidad antes de asignarlo.
        for ($intento = 0; $intento < 10000; $intento++) {
            $sufijo = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
            $candidato = $base.$sufijo;

            if (!Usuario::query()->where('nombre_usuario', $candidato)->exists()) {
                return $candidato;
            }
        }

        throw new \RuntimeException('No se pudo generar un nombre de usuario disponible.');
    }
}
