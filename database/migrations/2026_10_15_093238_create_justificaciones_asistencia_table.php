
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('justificaciones_asistencia', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('asistencia_estudiante_id');

            $table->text('motivo');

            $table->string('ruta_documento', 500)->nullable();

            $table->unsignedBigInteger('registrado_por');

            $table->unsignedBigInteger('aprobado_por')->nullable();

            $table->enum('estado', [
                'PENDIENTE',
                'APROBADA',
                'RECHAZADA'
            ])->default('PENDIENTE');

            $table->timestamps();

            $table->foreign(
                'asistencia_estudiante_id',
                'fk_justificaciones_asistencia_asistencia_estudiante_id'
            )
                ->references('id')
                ->on('asistencia_estudiantes');

            $table->foreign(
                'registrado_por',
                'fk_justificaciones_asistencia_registrado_por'
            )
                ->references('id')
                ->on('usuarios');

            $table->foreign(
                'aprobado_por',
                'fk_justificaciones_asistencia_aprobado_por'
            )
                ->references('id')
                ->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('justificaciones_asistencia');
    }
};
