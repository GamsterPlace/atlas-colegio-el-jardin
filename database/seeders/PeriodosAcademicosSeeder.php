<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class PeriodosAcademicosSeeder extends Seeder
{
    public function run(): void
    {
        $anio = (int) env('ATLAS_CICLO_ANIO', 2027);
        $ciclo = DB::table('ciclos_escolares')->where('nombre', (string) $anio)->first();
        if (!$ciclo) {
            throw new RuntimeException('No existe el ciclo escolar configurado.');
        }
        $rangos = [
            [1, 'Primer Bimestre', "$anio-02-01", "$anio-03-31"],
            [2, 'Segundo Bimestre', "$anio-04-01", "$anio-05-31"],
            [3, 'Tercer Bimestre', "$anio-06-01", "$anio-07-31"],
            [4, 'Cuarto Bimestre', "$anio-08-01", "$anio-09-30"],
        ];
        foreach ($rangos as [$numero, $nombre, $inicioDefault, $finDefault]) {
            $inicio = env("ATLAS_BIMESTRE_{$numero}_INICIO", $inicioDefault);
            $fin = env("ATLAS_BIMESTRE_{$numero}_FIN", $finDefault);
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $inicio) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fin) || $inicio > $fin || $inicio < $ciclo->fecha_inicio || $fin > $ciclo->fecha_fin) {
                throw new RuntimeException("Fechas inválidas para el bimestre {$numero}.");
            }
            if (!DB::table('periodos_academicos')->where('ciclo_escolar_id', $ciclo->id)->where('numero', $numero)->exists()) {
                DB::table('periodos_academicos')->insert([
                    'ciclo_escolar_id' => $ciclo->id, 'nombre' => $nombre,
                    'numero' => $numero, 'fecha_inicio' => $inicio,
                    'fecha_fin' => $fin, 'estado' => 'ABIERTO',
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }
        }
    }
}
