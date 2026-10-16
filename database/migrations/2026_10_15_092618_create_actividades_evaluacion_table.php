
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actividades_evaluacion', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('carga_academica_id');

            $table->unsignedBigInteger('periodo_academico_id');

            $table->string('titulo', 190);

            $table->string('tipo', 80)->nullable();

            $table->date('fecha')->nullable();

            $table->decimal('punteo_maximo', 8, 2);

            $table->decimal('ponderacion', 6, 2)->nullable();

            $table->boolean('publicada')->default(false);

            $table->timestamps();

            $table->foreign(
                'carga_academica_id',
                'fk_actividades_evaluacion_carga_academica_id'
            )
                ->references('id')
                ->on('cargas_academicas');

            $table->foreign(
                'periodo_academico_id',
                'fk_actividades_evaluacion_periodo_academico_id'
            )
                ->references('id')
                ->on('periodos_academicos');
        });

        DB::statement("
            ALTER TABLE actividades_evaluacion
            ADD CONSTRAINT chk_actividad_punteo
            CHECK (punteo_maximo > 0)
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('actividades_evaluacion');
    }
};
