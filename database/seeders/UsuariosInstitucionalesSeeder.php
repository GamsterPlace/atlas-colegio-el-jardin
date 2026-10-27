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
        DB::transaction(function (): void {

            /*
            |--------------------------------------------------------------------------
            | Administrador general de ATLAS
            |--------------------------------------------------------------------------
            */

            $this->crearUsuario(
                'Administrador.ATLAS',
                'Administrador',
                'ATLAS',
                'ADMINISTRADOR',
                'ATLAS.admin#123*'
            );

            /*
            |--------------------------------------------------------------------------
            | Dirección institucional
            |--------------------------------------------------------------------------
            */

            $this->crearUsuario(
                'Raquel.Reyes',
                'Eneida Raquel',
                'Reyes Rojas',
                'DIRECCION',
                'RaquelReyes#123*'
            );

        });

        $this->command?->info(
            'Usuarios institucionales configurados correctamente.'
        );
    }

    private function crearUsuario(
        string $nombreUsuario,
        string $nombres,
        string $apellidos,
        string $rolCodigo,
        string $password
    ): void {
        /*
        |--------------------------------------------------------------------------
        | Verificar rol institucional
        |--------------------------------------------------------------------------
        */

        $rolId = DB::table('roles')
            ->where('codigo', $rolCodigo)
            ->where('activo', true)
            ->value('id');

        if (!$rolId) {
            throw new RuntimeException(
                "El rol {$rolCodigo} no existe o está inactivo."
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Buscar cuenta existente por nombre de usuario
        |--------------------------------------------------------------------------
        */

        $usuario = DB::table('usuarios')
            ->where('nombre_usuario', $nombreUsuario)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Buscar o registrar persona
        |--------------------------------------------------------------------------
        */

        if ($usuario) {
            $personaId = $usuario->persona_id;
        } else {
            $persona = DB::table('personas')
                ->where('nombres', $nombres)
                ->where('apellidos', $apellidos)
                ->first();

            if ($persona) {
                $personaId = $persona->id;

                $cuentaExistente = DB::table('usuarios')
                    ->where('persona_id', $personaId)
                    ->first();

                if ($cuentaExistente) {
                    throw new RuntimeException(
                        "La persona {$nombres} {$apellidos} ya tiene "
                        . "una cuenta: {$cuentaExistente->nombre_usuario}. "
                        . "No se creará una segunda cuenta."
                    );
                }
            } else {
                $personaId = DB::table('personas')
                    ->insertGetId([
                        'nombres' => $nombres,
                        'apellidos' => $apellidos,
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Crear o actualizar cuenta institucional
        |--------------------------------------------------------------------------
        */

        if (!$usuario) {
            $usuarioId = DB::table('usuarios')
                ->insertGetId([
                    'persona_id' => $personaId,
                    'nombre_usuario' => $nombreUsuario,
                    'email' => null,
                    'password' => Hash::make($password),
                    'debe_cambiar_password' => false,
                    'activo' => true,
                ]);
        } else {
            $usuarioId = $usuario->id;

            DB::table('usuarios')
                ->where('id', $usuarioId)
                ->update([
                    'password' => Hash::make($password),
                    'debe_cambiar_password' => false,
                    'activo' => true,
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Asignar rol sin eliminar otros roles existentes
        |--------------------------------------------------------------------------
        */

        DB::table('usuario_roles')
            ->insertOrIgnore([
                'usuario_id' => $usuarioId,
                'rol_id' => $rolId,
            ]);

        $this->command?->info(
            "Cuenta {$rolCodigo}: {$nombreUsuario} configurada correctamente."
        );
    }
}
