
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asistencia_estudiantes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('inscripcion_id');

            $table->date('fecha');

            $table->enum('estado', [
                'PRESENTE',
                'AUSENTE',
                'TARDE',
                'JUSTIFICADA'
            ]);

            $table->unsignedBigInteger('registrado_por');

            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->unique(
                ['inscripcion_id', 'fecha'],
                'uq_asistencia_estudiante'
            );

            $table->foreign(
                'inscripcion_id',
                'fk_asistencia_estudiantes_inscripcion_id'
            )
                ->references('id')
                ->on('inscripciones');

            $table->foreign(
                'registrado_por',
                'fk_asistencia_estudiantes_registrado_por'
            )
                ->references('id')
                ->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asistencia_estudiantes');
    }
};
