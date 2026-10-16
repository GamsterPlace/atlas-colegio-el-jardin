
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reportes_generados', function (Blueprint $table) {
            $table->id();

            $table->string('tipo', 120);

            $table->json('parametros')->nullable();

            $table->string('ruta_privada', 500)->nullable();

            $table->unsignedBigInteger('generado_por');

            $table->dateTime('fecha_generacion')->useCurrent();

            $table->timestamps();

            $table->foreign(
                'generado_por',
                'fk_reportes_generados_generado_por'
            )
                ->references('id')
                ->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reportes_generados');
    }
};
