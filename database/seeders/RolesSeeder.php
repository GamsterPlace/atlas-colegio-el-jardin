<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['DIRECCION', 'Dirección'],
            ['ADMINISTRADOR', 'Administrador'],
            ['SECRETARIA', 'Secretaría'],
            ['DOCENTE', 'Docente'],
            ['PADRE_FAMILIA', 'Padre de Familia'],
        ] as [$codigo, $nombre]) {
            DB::table('roles')->updateOrInsert(['codigo' => $codigo], [
                'nombre' => $nombre, 'activo' => true,
            ]);
        }
    }
}
