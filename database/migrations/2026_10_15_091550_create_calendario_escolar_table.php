
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calendario_escolar', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ciclo_escolar_id');

            $table->date('fecha');

            $table->enum('tipo', [
                'LECTIVO',
                'FERIADO',
                'ASUETO',
                'SUSPENSION',
                'OTRO'
            ]);

            $table->string('descripcion', 255)->nullable();

            $table->timestamps();

            $table->unique(
                ['ciclo_escolar_id', 'fecha'],
                'uq_calendario'
            );

            $table->foreign(
                'ciclo_escolar_id',
                'fk_calendario_escolar_ciclo_escolar_id'
            )
                ->references('id')
                ->on('ciclos_escolares');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendario_escolar');
    }
};
