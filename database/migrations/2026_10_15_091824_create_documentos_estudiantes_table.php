
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos_estudiantes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('estudiante_id');

            $table->string('tipo', 100);

            $table->string('nombre_original', 255);

            $table->string('ruta_privada', 500);

            $table->string('mime', 100)->nullable();

            $table->unsignedBigInteger('subido_por')->nullable();

            $table->timestamps();

            $table->foreign(
                'estudiante_id',
                'fk_documentos_estudiantes_estudiante_id'
            )
                ->references('id')
                ->on('estudiantes');

            $table->foreign(
                'subido_por',
                'fk_documentos_estudiantes_subido_por'
            )
                ->references('id')
                ->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos_estudiantes');
    }
};
