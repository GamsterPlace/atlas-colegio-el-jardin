
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscripciones', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('estudiante_id');

            $table->unsignedBigInteger('ciclo_escolar_id');

            $table->unsignedBigInteger('seccion_id');

            $table->unsignedBigInteger('responsable_financiero_id')
                ->nullable();

            $table->date('fecha_inscripcion');

            $table->date('fecha_inicio');

            $table->date('fecha_fin')->nullable();

            $table->enum('estado', [
                'EN_PROCESO',
                'COMPLETADA',
                'TRASLADADA',
                'RETIRADA',
                'ANULADA'
            ])->default('EN_PROCESO');

            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->index(
                ['estudiante_id', 'ciclo_escolar_id'],
                'idx_inscripcion_estudiante_ciclo'
            );

            $table->foreign('estudiante_id')
                ->references('id')
                ->on('estudiantes');

            $table->foreign('ciclo_escolar_id')
                ->references('id')
                ->on('ciclos_escolares');

            $table->foreign('seccion_id')
                ->references('id')
                ->on('secciones');

            $table->foreign('responsable_financiero_id')
                ->references('id')
                ->on('responsables');
        });

        DB::statement("
            ALTER TABLE inscripciones
            ADD CONSTRAINT chk_inscripcion_fechas
            CHECK (
                fecha_fin IS NULL
                OR fecha_fin >= fecha_inicio
            )
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('inscripciones');
    }
};
