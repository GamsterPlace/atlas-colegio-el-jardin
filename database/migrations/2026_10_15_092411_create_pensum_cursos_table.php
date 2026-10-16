
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pensum_cursos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('pensum_id');

            $table->unsignedBigInteger('grado_id');

            $table->unsignedBigInteger('curso_id');

            $table->unsignedTinyInteger('horas_semanales')->default(1);

            $table->unsignedSmallInteger('orden')->default(0);

            $table->timestamps();

            $table->unique(
                ['pensum_id', 'grado_id', 'curso_id'],
                'uq_pensum_curso'
            );

            $table->foreign(
                'pensum_id',
                'fk_pensum_cursos_pensum_id'
            )
                ->references('id')
                ->on('pensum_estudios');

            $table->foreign(
                'grado_id',
                'fk_pensum_cursos_grado_id'
            )
                ->references('id')
                ->on('grados');

            $table->foreign(
                'curso_id',
                'fk_pensum_cursos_curso_id'
            )
                ->references('id')
                ->on('cursos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pensum_cursos');
    }
};
