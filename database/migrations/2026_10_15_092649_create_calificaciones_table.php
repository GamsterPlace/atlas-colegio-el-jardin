
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calificaciones', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('actividad_evaluacion_id');

            $table->unsignedBigInteger('inscripcion_id');

            $table->decimal('nota', 8, 2);

            $table->text('observaciones')->nullable();

            $table->unsignedBigInteger('registrado_por');

            $table->timestamps();

            $table->unique(
                ['actividad_evaluacion_id', 'inscripcion_id'],
                'uq_nota_actividad'
            );

            $table->foreign(
                'actividad_evaluacion_id',
                'fk_calificaciones_actividad_evaluacion_id'
            )
                ->references('id')
                ->on('actividades_evaluacion');

            $table->foreign(
                'inscripcion_id',
                'fk_calificaciones_inscripcion_id'
            )
                ->references('id')
                ->on('inscripciones');

            $table->foreign(
                'registrado_por',
                'fk_calificaciones_registrado_por'
            )
                ->references('id')
                ->on('usuarios');
        });

        DB::statement("
            ALTER TABLE calificaciones
            ADD CONSTRAINT chk_calificacion_no_negativa
            CHECK (nota >= 0)
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('calificaciones');
    }
};
