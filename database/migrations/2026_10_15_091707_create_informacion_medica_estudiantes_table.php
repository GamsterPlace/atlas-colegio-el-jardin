
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('informacion_medica_estudiantes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('estudiante_id')->unique();

            $table->string('tipo_sangre', 10)->nullable();

            $table->text('alergias')->nullable();

            $table->text('condiciones_relevantes')->nullable();

            $table->text('medicamentos')->nullable();

            $table->text('indicaciones_emergencia')->nullable();

            $table->timestamps();

            $table->foreign(
                'estudiante_id',
                'fk_informacion_medica_estudiantes_estudiante_id'
            )
                ->references('id')
                ->on('estudiantes');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('informacion_medica_estudiantes');
    }
};
