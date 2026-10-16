
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restricciones_horarios', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ciclo_escolar_id');

            $table->unsignedBigInteger('seccion_id')->nullable();

            $table->unsignedBigInteger('docente_id')->nullable();

            $table->string('tipo', 100);

            $table->json('parametros');

            $table->unsignedSmallInteger('prioridad')->default(1);

            $table->boolean('obligatoria')->default(true);

            $table->timestamps();

            $table->foreign(
                'ciclo_escolar_id',
                'fk_restricciones_horarios_ciclo_escolar_id'
            )
                ->references('id')
                ->on('ciclos_escolares');

            $table->foreign(
                'seccion_id',
                'fk_restricciones_horarios_seccion_id'
            )
                ->references('id')
                ->on('secciones');

            $table->foreign(
                'docente_id',
                'fk_restricciones_horarios_docente_id'
            )
                ->references('id')
                ->on('docentes');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restricciones_horarios');
    }
};
