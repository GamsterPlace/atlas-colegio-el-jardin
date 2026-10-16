
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('observaciones_academicas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('inscripcion_id');

            $table->unsignedBigInteger('carga_academica_id')->nullable();

            $table->unsignedBigInteger('periodo_academico_id')->nullable();

            $table->text('observacion');

            $table->unsignedBigInteger('registrado_por');

            $table->timestamps();

            $table->foreign(
                'inscripcion_id',
                'fk_observaciones_academicas_inscripcion_id'
            )
                ->references('id')
                ->on('inscripciones');

            $table->foreign(
                'carga_academica_id',
                'fk_observaciones_academicas_carga_academica_id'
            )
                ->references('id')
                ->on('cargas_academicas');

            $table->foreign(
                'periodo_academico_id',
                'fk_observaciones_academicas_periodo_academico_id'
            )
                ->references('id')
                ->on('periodos_academicos');

            $table->foreign(
                'registrado_por',
                'fk_observaciones_academicas_registrado_por'
            )
                ->references('id')
                ->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('observaciones_academicas');
    }
};
