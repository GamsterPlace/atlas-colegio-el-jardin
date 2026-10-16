<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JornadaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jornadas')->updateOrInsert(['nombre' => 'Única'], [
            'hora_inicio' => '07:00:00',
            'hora_fin' => '13:30:00',
            'activo' => true,
        ]);
    }
}
