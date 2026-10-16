
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notas_periodo', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('inscripcion_id');

            $table->unsignedBigInteger('carga_academica_id');

            $table->unsignedBigInteger('periodo_academico_id');

            $table->decimal('nota', 8, 2);

            $table->enum('estado', [
                'PROVISIONAL',
                'PUBLICADA',
                'CERRADA'
            ])->default('PROVISIONAL');

            $table->timestamps();

            $table->unique(
                [
                    'inscripcion_id',
                    'carga_academica_id',
                    'periodo_academico_id'
                ],
                'uq_nota_periodo'
            );

            $table->foreign(
                'inscripcion_id',
                'fk_notas_periodo_inscripcion_id'
            )
                ->references('id')
                ->on('inscripciones');

            $table->foreign(
                'carga_academica_id',
                'fk_notas_periodo_carga_academica_id'
            )
                ->references('id')
                ->on('cargas_academicas');

            $table->foreign(
                'periodo_academico_id',
                'fk_notas_periodo_periodo_academico_id'
            )
                ->references('id')
                ->on('periodos_academicos');
        });

        DB::statement("
            ALTER TABLE notas_periodo
            ADD CONSTRAINT chk_nota_periodo
            CHECK (nota >= 0)
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('notas_periodo');
    }
};
