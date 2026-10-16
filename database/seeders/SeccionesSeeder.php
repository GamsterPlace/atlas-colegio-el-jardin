<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeccionesSeeder extends Seeder
{
    public function run(): void
    {
        $cicloId = DB::table('ciclos_escolares')->where('nombre', (string) env('ATLAS_CICLO_ANIO', 2027))->value('id');
        $jornadaId = DB::table('jornadas')->where('nombre', 'Única')->value('id');
        foreach (DB::table('grados')->pluck('id') as $gradoId) {
            DB::table('secciones')->updateOrInsert([
                'ciclo_escolar_id' => $cicloId,
                'grado_id' => $gradoId,
                'jornada_id' => $jornadaId,
                'nombre' => 'A',
            ], ['activo' => true]);
        }
    }
}
