
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('secciones', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ciclo_escolar_id');

            $table->unsignedBigInteger('grado_id');

            $table->unsignedBigInteger('jornada_id');

            $table->string('nombre', 30);

            $table->unsignedSmallInteger('cupo')->nullable();

            $table->boolean('activo')->default(true);

            $table->unique(
                [
                    'ciclo_escolar_id',
                    'grado_id',
                    'jornada_id',
                    'nombre'
                ],
                'uq_seccion'
            );

            $table->foreign('ciclo_escolar_id')
                ->references('id')
                ->on('ciclos_escolares');

            $table->foreign('grado_id')
                ->references('id')
                ->on('grados');

            $table->foreign('jornada_id')
                ->references('id')
                ->on('jornadas');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('secciones');
    }
};
