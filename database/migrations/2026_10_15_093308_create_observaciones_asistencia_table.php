
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('observaciones_asistencia', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('asistencia_estudiante_id');

            $table->text('observacion');

            $table->unsignedBigInteger('usuario_id');

            $table->timestamps();

            $table->foreign(
                'asistencia_estudiante_id',
                'fk_observaciones_asistencia_asistencia_estudiante_id'
            )
                ->references('id')
                ->on('asistencia_estudiantes');

            $table->foreign(
                'usuario_id',
                'fk_observaciones_asistencia_usuario_id'
            )
                ->references('id')
                ->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('observaciones_asistencia');
    }
};
