<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class UsuariosInstitucionalesSeeder extends Seeder
{
    public function run(): void
    {
        if (!$this->command) {
            throw new RuntimeException(
                'Este seeder debe ejecutarse mediante Artisan en una terminal interactiva.'
            );
        }

        $adminPassword = $this->solicitarPassword('Administrador');
        $direccionPassword = $this->solicitarPassword('Dirección');

        if ($adminPassword === $direccionPassword) {
            throw new RuntimeException(
                'Las contraseñas de Administrador y Dirección deben ser diferentes.'
            );
        }

        DB::transaction(function () use (
            $adminPassword,
            $direccionPassword
        ): void {
            $this->crearUsuario(
                'atlas.admin',
                'Superusuario',
                'ATLAS',
                'ADMINISTRADOR',
                $adminPassword
            );

            $this->crearUsuario(
                'Raquel.Reyes',
                'Eneida Raquel',
                'Reyes Rojas',
                'DIRECCION',
                $direccionPassword
            );
        });
    }

    private function solicitarPassword(string $rol): string
    {
        if (!$this->command) {
            throw new RuntimeException(
                "La creación de {$rol} requiere una terminal interactiva."
            );
        }

        do {
            $password = $this->command->secret(
                "Ingrese la contraseña para {$rol} (mínimo 16 caracteres)"
            );

            if (!is_string($password) || strlen($password) < 16) {
                $this->command->error(
                    'La contraseña debe tener al menos 16 caracteres.'
                );

                continue;
            }

            $confirmacion = $this->command->secret(
                "Confirme la contraseña para {$rol}"
            );

            if ($password !== $confirmacion) {
                $this->command->error(
                    'Las contraseñas no coinciden.'
                );

                continue;
            }

            return $password;
        } while (true);
    }

    private function crearUsuario(
        string $nombreUsuario,
        string $nombres,
        string $apellidos,
        string $rolCodigo,
        string $password
    ): void {
        $rolId = DB::table('roles')
            ->where('codigo', $rolCodigo)
            ->value('id');

        if (!$rolId) {
            throw new RuntimeException(
                "No existe el rol {$rolCodigo}."
            );
        }

        $persona = DB::table('personas')
            ->where('nombres', $nombres)
            ->where('apellidos', $apellidos)
            ->first();

        if (!$persona) {
            $personaId = DB::table('personas')->insertGetId([
                'nombres' => $nombres,
                'apellidos' => $apellidos,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $personaId = $persona->id;
        }

        $usuario = DB::table('usuarios')
            ->where('persona_id', $personaId)
            ->first();

        if (!$usuario) {
            $nombreOcupado = DB::table('usuarios')
                ->where('nombre_usuario', $nombreUsuario)
                ->exists();

            if ($nombreOcupado) {
                throw new RuntimeException(
                    "El nombre de usuario {$nombreUsuario} ya está ocupado."
                );
            }

            $usuarioId = DB::table('usuarios')->insertGetId([
                'persona_id' => $personaId,
                'nombre_usuario' => $nombreUsuario,
                'password' => Hash::make($password),
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $usuarioId = $usuario->id;
        }

        DB::table('usuario_roles')->insertOrIgnore([
            'usuario_id' => $usuarioId,
            'rol_id' => $rolId,
            'created_at' => now(),
        ]);

        $this->command?->info(
            "Cuenta {$rolCodigo}: {$nombreUsuario} configurada correctamente."
        );
    }
}
