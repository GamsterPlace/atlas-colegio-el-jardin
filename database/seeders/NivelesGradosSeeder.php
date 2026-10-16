<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NivelesGradosSeeder extends Seeder
{
    public function run(): void
    {
        $niveles = [
            'Pre-Primario' => ['Kinder', 'Párvulos', 'Preparatoria'],
            'Primaria' => ['Primero', 'Segundo', 'Tercero', 'Cuarto', 'Quinto', 'Sexto'],
            'Básicos' => ['Primero', 'Segundo', 'Tercero'],
            'Diversificado' => [
                'Cuarto Bachillerato en Ciencias y Letras con Orientación en Computación',
                'Quinto Bachillerato en Ciencias y Letras con Orientación en Computación',
            ],
        ];

        $ordenNivel = 0;
        foreach ($niveles as $nombreNivel => $grados) {
            $ordenNivel++;
            DB::table('niveles')->updateOrInsert(['nombre' => $nombreNivel], [
                'orden' => $ordenNivel, 'activo' => true,
            ]);
            $nivelId = DB::table('niveles')->where('nombre', $nombreNivel)->value('id');
            foreach ($grados as $indice => $nombreGrado) {
                DB::table('grados')->updateOrInsert([
                    'nivel_id' => $nivelId, 'nombre' => $nombreGrado,
                ], ['orden' => $indice + 1, 'activo' => true]);
            }
        }
    }
}
