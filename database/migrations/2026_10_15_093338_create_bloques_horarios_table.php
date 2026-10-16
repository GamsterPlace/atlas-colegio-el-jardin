
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bloques_horarios', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('jornada_id');

            $table->string('nombre', 80);

            $table->unsignedSmallInteger('orden');

            $table->time('hora_inicio');

            $table->time('hora_fin');

            $table->enum('tipo', [
                'CLASE',
                'RECESO',
                'OTRO'
            ])->default('CLASE');

            $table->timestamps();

            $table->unique(
                ['jornada_id', 'orden'],
                'uq_bloque_orden'
            );

            $table->foreign(
                'jornada_id',
                'fk_bloques_horarios_jornada_id'
            )
                ->references('id')
                ->on('jornadas');
        });

        DB::statement("
            ALTER TABLE bloques_horarios
            ADD CONSTRAINT chk_bloque_horas
            CHECK (hora_fin > hora_inicio)
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('bloques_horarios');
    }
};
