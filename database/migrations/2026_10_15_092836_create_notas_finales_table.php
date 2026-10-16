
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notas_finales', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('inscripcion_id');

            $table->unsignedBigInteger('carga_academica_id');

            $table->decimal('nota_final', 8, 2);

            $table->enum('resultado', [
                'PENDIENTE',
                'APROBADO',
                'REPROBADO'
            ])->default('PENDIENTE');

            $table->timestamps();

            $table->unique(
                ['inscripcion_id', 'carga_academica_id'],
                'uq_nota_final'
            );

            $table->foreign(
                'inscripcion_id',
                'fk_notas_finales_inscripcion_id'
            )
                ->references('id')
                ->on('inscripciones');

            $table->foreign(
                'carga_academica_id',
                'fk_notas_finales_carga_academica_id'
            )
                ->references('id')
                ->on('cargas_academicas');
        });

        DB::statement("
            ALTER TABLE notas_finales
            ADD CONSTRAINT chk_nota_final
            CHECK (nota_final >= 0)
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('notas_finales');
    }
};
