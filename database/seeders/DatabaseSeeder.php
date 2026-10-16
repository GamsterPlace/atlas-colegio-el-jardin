<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            InstitucionSeeder::class,
            NivelesGradosSeeder::class,
            JornadaSeeder::class,
            CicloEscolarSeeder::class,
            PeriodosAcademicosSeeder::class,
            SeccionesSeeder::class,
            ConceptosCobroSeeder::class,
            UsuariosInstitucionalesSeeder::class,
        ]);
    }
}
