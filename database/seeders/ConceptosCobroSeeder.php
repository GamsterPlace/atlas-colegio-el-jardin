<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConceptosCobroSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['INSCRIPCION', 'Inscripción', 'INSCRIPCION'],
            ['COLEGIATURA', 'Colegiatura mensual', 'COLEGIATURA'],
        ] as [$codigo, $nombre, $tipo]) {
            DB::table('conceptos_cobro')->updateOrInsert(['codigo' => $codigo], [
                'nombre' => $nombre, 'tipo' => $tipo, 'activo' => true,
            ]);
        }
    }
}
