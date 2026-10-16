<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CicloEscolarSeeder extends Seeder
{
    public function run(): void
    {
        $anio = (int) env('ATLAS_CICLO_ANIO', 2027);
        $inicio = env('ATLAS_CICLO_INICIO', "$anio-01-01");
        $fin = env('ATLAS_CICLO_FIN', "$anio-10-31");
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $inicio) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fin) || $fin < $inicio) {
            throw new RuntimeException('Fechas inválidas para el ciclo escolar ATLAS.');
        }
        $nombre = (string) $anio;
        if (!DB::table('ciclos_escolares')->where('nombre', $nombre)->exists()) {
            DB::table('ciclos_escolares')->insert([
                'nombre' => $nombre, 'fecha_inicio' => $inicio,
                'fecha_fin' => $fin, 'estado' => 'PLANIFICACION',
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }
    }
}
