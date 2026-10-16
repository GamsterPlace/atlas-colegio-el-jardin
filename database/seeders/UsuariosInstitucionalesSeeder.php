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
        $adminPassword = env('ATLAS_ADMIN_PASSWORD');
        $direccionPassword = env('ATLAS_DIRECCION_PASSWORD');
        if (!$adminPassword || !$direccionPassword || strlen($adminPassword) < 16 || strlen($direccionPassword) < 16 || $adminPassword === $direccionPassword) {
            throw new RuntimeException('Configure ATLAS_ADMIN_PASSWORD y ATLAS_DIRECCION_PASSWORD, distintas y de 16 caracteres o más, antes de ejecutar los seeders.');
        }
        DB::transaction(function () use ($adminPassword, $direccionPassword): void {
            $this->crearUsuario('ATLAS_ADMIN_USERNAME', 'atlas.admin', 'Superusuario', 'ATLAS', 'ADMINISTRADOR', $adminPassword);
            $this->crearUsuario('ATLAS_DIRECCION_USERNAME', 'Raquel.Reyes', 'Eneida Raquel', 'Reyes Rojas', 'DIRECCION', $direccionPassword);
        });
    }

    private function crearUsuario(string $variableUsuario, string $prefijo, string $nombres, string $apellidos, string $rolCodigo, string $password): void
    {
        $nombreUsuario = env($variableUsuario);
        if (!$nombreUsuario) {
            $existente = DB::table('personas')->where('nombres', $nombres)->where('apellidos', $apellidos)->first();
            if ($existente) {
                $nombreUsuario = DB::table('usuarios')->where('persona_id', $existente->id)->value('nombre_usuario');
            }
        }
        if (!$nombreUsuario) {
            do {
                $nombreUsuario = $prefijo . random_int(1000, 9999);
            } while (DB::table('usuarios')->where('nombre_usuario', $nombreUsuario)->exists());
        }
        $persona = DB::table('personas')->where('nombres', $nombres)->where('apellidos', $apellidos)->first();
        if (!$persona) {
            $personaId = DB::table('personas')->insertGetId([
                'nombres' => $nombres, 'apellidos' => $apellidos,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        } else {
            $personaId = $persona->id;
        }
        $usuario = DB::table('usuarios')->where('persona_id', $personaId)->first();
        if (!$usuario) {
            if (DB::table('usuarios')->where('nombre_usuario', $nombreUsuario)->exists()) {
                throw new RuntimeException("El nombre de usuario {$nombreUsuario} ya está ocupado.");
            }
            $usuarioId = DB::table('usuarios')->insertGetId([
                'persona_id' => $personaId, 'nombre_usuario' => $nombreUsuario,
                'password' => Hash::make($password), 'activo' => true,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        } else {
            $usuarioId = $usuario->id;
        }
        $rolId = DB::table('roles')->where('codigo', $rolCodigo)->value('id');
        DB::table('usuario_roles')->insertOrIgnore([
            'usuario_id' => $usuarioId, 'rol_id' => $rolId,
            'created_at' => now(),
        ]);
        $this->command?->info("Cuenta {$rolCodigo}: {$nombreUsuario} (contraseña no mostrada; no se modifica si la cuenta ya existe)");
    }
}
