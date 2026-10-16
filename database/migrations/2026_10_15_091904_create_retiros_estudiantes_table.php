
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('retiros_estudiantes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('inscripcion_id');

            $table->date('fecha_retiro');

            $table->text('motivo');

            $table->unsignedBigInteger('registrado_por');

            $table->timestamps();

            $table->foreign(
                'inscripcion_id',
                'fk_retiros_estudiantes_inscripcion_id'
            )
                ->references('id')
                ->on('inscripciones');

            $table->foreign(
                'registrado_por',
                'fk_retiros_estudiantes_registrado_por'
            )
                ->references('id')
                ->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retiros_estudiantes');
    }
};
