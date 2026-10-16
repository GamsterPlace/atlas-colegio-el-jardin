
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entregas_uniformes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('estudiante_id');

            $table->unsignedBigInteger('ciclo_escolar_id');

            $table->date('fecha_entrega');

            $table->unsignedBigInteger('entregado_por');

            $table->unsignedBigInteger('recibido_por_responsable_id')
                ->nullable();

            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->foreign(
                'estudiante_id',
                'fk_entregas_uniformes_estudiante_id'
            )
                ->references('id')
                ->on('estudiantes');

            $table->foreign(
                'ciclo_escolar_id',
                'fk_entregas_uniformes_ciclo_escolar_id'
            )
                ->references('id')
                ->on('ciclos_escolares');

            $table->foreign(
                'entregado_por',
                'fk_entregas_uniformes_entregado_por'
            )
                ->references('id')
                ->on('usuarios');

            $table->foreign(
                'recibido_por_responsable_id',
                'fk_entregas_uniformes_recibido_por_responsable_id'
            )
                ->references('id')
                ->on('responsables');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entregas_uniformes');
    }
};
