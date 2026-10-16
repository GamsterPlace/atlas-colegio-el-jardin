
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('talonarios_pago', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('estudiante_id');

            $table->unsignedBigInteger('ciclo_escolar_id');

            $table->string('codigo', 90)->unique();

            $table->dateTime('fecha_generacion');

            $table->unsignedBigInteger('emitido_por')->nullable();

            $table->timestamps();

            $table->unique(
                ['estudiante_id', 'ciclo_escolar_id'],
                'uq_talonario_estudiante_ciclo'
            );

            $table->foreign(
                'estudiante_id',
                'fk_talonarios_pago_estudiante_id'
            )
                ->references('id')
                ->on('estudiantes');

            $table->foreign(
                'ciclo_escolar_id',
                'fk_talonarios_pago_ciclo_escolar_id'
            )
                ->references('id')
                ->on('ciclos_escolares');

            $table->foreign(
                'emitido_por',
                'fk_talonarios_pago_emitido_por'
            )
                ->references('id')
                ->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('talonarios_pago');
    }
};
