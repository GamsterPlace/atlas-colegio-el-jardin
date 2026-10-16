
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos_generados', function (Blueprint $table) {
            $table->id();

            $table->string('tipo', 100);

            $table->string('entidad', 100)->nullable();

            $table->unsignedBigInteger('entidad_id')->nullable();

            $table->string('codigo', 100)->nullable()->unique();

            $table->string('ruta_privada', 500);

            $table->char('hash_sha256', 64)->nullable();

            $table->unsignedBigInteger('generado_por');

            $table->dateTime('fecha_generacion')->useCurrent();

            $table->timestamps();

            $table->index(
                ['entidad', 'entidad_id'],
                'idx_documentos_entidad'
            );

            $table->foreign(
                'generado_por',
                'fk_documentos_generados_generado_por'
            )
                ->references('id')
                ->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos_generados');
    }
};
