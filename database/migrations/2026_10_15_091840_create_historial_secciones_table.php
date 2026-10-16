
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historial_secciones', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('inscripcion_id');

            $table->unsignedBigInteger('seccion_anterior_id')->nullable();

            $table->unsignedBigInteger('seccion_nueva_id');

            $table->date('fecha_traslado');

            $table->text('motivo');

            $table->unsignedBigInteger('registrado_por');

            $table->timestamps();

            $table->foreign(
                'inscripcion_id',
                'fk_historial_secciones_inscripcion_id'
            )
                ->references('id')
                ->on('inscripciones');

            $table->foreign(
                'seccion_anterior_id',
                'fk_historial_secciones_seccion_anterior_id'
            )
                ->references('id')
                ->on('secciones');

            $table->foreign(
                'seccion_nueva_id',
                'fk_historial_secciones_seccion_nueva_id'
            )
                ->references('id')
                ->on('secciones');

            $table->foreign(
                'registrado_por',
                'fk_historial_secciones_registrado_por'
            )
                ->references('id')
                ->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_secciones');
    }
};
