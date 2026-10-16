
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ejecuciones_generador_horarios', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ciclo_escolar_id');

            $table->unsignedBigInteger('solicitado_por');

            $table->enum('estado', [
                'EN_PROCESO',
                'EXITOSA',
                'FALLIDA'
            ])->default('EN_PROCESO');

            $table->json('parametros')->nullable();

            $table->json('resultado')->nullable();

            $table->dateTime('iniciado_at');

            $table->dateTime('finalizado_at')->nullable();

            $table->timestamps();

            $table->foreign(
                'ciclo_escolar_id',
                'fk_ejecuciones_generador_horarios_ciclo_escolar_id'
            )
                ->references('id')
                ->on('ciclos_escolares');

            $table->foreign(
                'solicitado_por',
                'fk_ejecuciones_generador_horarios_solicitado_por'
            )
                ->references('id')
                ->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ejecuciones_generador_horarios');
    }
};
