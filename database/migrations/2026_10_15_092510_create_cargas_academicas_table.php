
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cargas_academicas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('seccion_id');

            $table->unsignedBigInteger('pensum_curso_id');

            $table->boolean('activo')->default(true);

            $table->timestamps();

            $table->unique(
                ['seccion_id', 'pensum_curso_id'],
                'uq_carga'
            );

            $table->foreign(
                'seccion_id',
                'fk_cargas_academicas_seccion_id'
            )
                ->references('id')
                ->on('secciones');

            $table->foreign(
                'pensum_curso_id',
                'fk_cargas_academicas_pensum_curso_id'
            )
                ->references('id')
                ->on('pensum_cursos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cargas_academicas');
    }
};
