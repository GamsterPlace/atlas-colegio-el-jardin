<?php

namespace App\Services;

use App\Models\Persona;
use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class GeneracionAccesoDocenteService
{
    /** @return array{usuario:string,password:?string} */
    public function generar(Persona $persona): array
    {
        $rol = Rol::query()->where('codigo', 'DOCENTE')->where('activo', true)->first();
        if (!$rol) {
            throw ValidationException::withMessages(['docente' => 'El rol DOCENTE no está habilitado en ATLAS.']);
        }

        $usuario = Usuario::query()->where('persona_id', $persona->id)->lockForUpdate()->first();
        $temporal = null;
        if (!$usuario) {
            $temporal = Str::password(16, true, true, true, false);
            $usuario = Usuario::create([
                'persona_id' => $persona->id,
                'nombre_usuario' => $this->nombreUsuario($persona->nombres, $persona->apellidos),
                'email' => null,
                'password' => $temporal,
                'debe_cambiar_password' => true,
                'activo' => true,
            ]);
        }

        // Una cuenta existente nunca pierde su contraseña ni cambia de estado.
        $usuario->roles()->syncWithoutDetaching([$rol->id]);
        return ['usuario' => $usuario->nombre_usuario, 'password' => $temporal];
    }

    private function nombreUsuario(string $nombres, string $apellidos): string
    {
        $primero = preg_split('/\s+/u', trim($nombres))[0] ?? '';
        $apellido = preg_split('/\s+/u', trim($apellidos))[0] ?? '';
        $base = Str::of($primero.'.'.$apellido)->ascii()->lower()
            ->replaceMatches('/[^a-z0-9.]/', '')->trim('.')->toString();
        if ($base === '') {
            $base = 'docente';
        }
        $base = substr($base, 0, 95);
        for ($intento = 0; $intento < 10000; $intento++) {
            $candidato = $base.str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
            if (!Usuario::query()->where('nombre_usuario', $candidato)->exists()) {
                return $candidato;
            }
        }
        throw new \RuntimeException('No se pudo generar un nombre de usuario disponible.');
    }
}
