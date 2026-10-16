<?php

namespace App\Services;

use App\Models\Responsable;
use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AccesoPortalPadresService
{
    public function autorizar(Responsable $responsable): array
    {
        return DB::transaction(function () use ($responsable) {

            $responsable = Responsable::query()
                ->whereKey($responsable->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (!$responsable->activo) {
                throw ValidationException::withMessages([
                    'responsable' =>
                        'El responsable se encuentra inactivo.',
                ]);
            }

            $persona = $responsable->persona;

            if (!$persona) {
                throw ValidationException::withMessages([
                    'responsable' =>
                        'El responsable no tiene información personal asociada.',
                ]);
            }

            $rol = Rol::query()
                ->where('codigo', 'PADRE_FAMILIA')
                ->where('activo', true)
                ->first();

            if (!$rol) {
                throw ValidationException::withMessages([
                    'responsable' =>
                        'El rol Padre de Familia no está disponible.',
                ]);
            }

            $usuario = Usuario::query()
                ->where('persona_id', $persona->id)
                ->lockForUpdate()
                ->first();

            $cuentaNueva = false;
            $contrasenaTemporal = null;

            if ($usuario) {
                if ($usuario->deleted_at !== null) {
                    throw ValidationException::withMessages([
                        'responsable' =>
                            'La persona tiene una cuenta eliminada. '
                            . 'Debe revisarse antes de autorizar el acceso.',
                    ]);
                }

                if (!$usuario->activo) {
                    throw ValidationException::withMessages([
                        'responsable' =>
                            'La persona tiene una cuenta inactiva. '
                            . 'Debe solicitarse su revisión antes de continuar.',
                    ]);
                }
            } else {
                $contrasenaTemporal = Str::password(
                    length: 16,
                    letters: true,
                    numbers: true,
                    symbols: false
                );

                $usuario = Usuario::query()->create([
                    'persona_id' => $persona->id,
                    'nombre_usuario' =>
                        $this->generarNombreUsuario($persona->id),
                    'email' => $this->correoDisponible(
                        $persona->correo
                    ),
                    'password' => $contrasenaTemporal,
                    'debe_cambiar_password' => true,
                    'activo' => true,
                    'ultimo_acceso_at' => null,
                ]);

                $cuentaNueva = true;
            }

            $usuario->roles()->syncWithoutDetaching([
                $rol->id,
            ]);

            return [
                'usuario' => $usuario->fresh(),
                'cuenta_nueva' => $cuentaNueva,
                'contrasena_temporal' => $contrasenaTemporal,
                'requiere_cambio_password' =>
                    (bool) $usuario->debe_cambiar_password,
            ];

        }, 3);
    }

    private function generarNombreUsuario(
        int $personaId
    ): string {
        $base = 'padre' . $personaId;

        $nombreUsuario = $base;
        $contador = 1;

        while (
            Usuario::query()
                ->where('nombre_usuario', $nombreUsuario)
                ->exists()
        ) {
            $nombreUsuario = $base . '_' . $contador;
            $contador++;
        }

        return $nombreUsuario;
    }

    private function correoDisponible(
        ?string $correo
    ): ?string {
        $correo = trim((string) $correo);

        if ($correo === '') {
            return null;
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            throw ValidationException::withMessages([
                'correo' =>
                    'El correo electrónico del responsable no es válido.',
            ]);
        }

        $existe = Usuario::query()
            ->where('email', $correo)
            ->exists();

        if ($existe) {
            throw ValidationException::withMessages([
                'correo' =>
                    'El correo electrónico ya pertenece a otra cuenta de usuario.',
            ]);
        }

        return $correo;
    }
}
