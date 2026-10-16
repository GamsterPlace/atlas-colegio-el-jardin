<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstitucionSeeder extends Seeder
{
    public function run(): void
    {
        if (!DB::table('datos_institucion')->exists()) {
            DB::table('datos_institucion')->insert([
                'nombre' => 'Colegio Mixto El Jardín',
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }
    }
}
