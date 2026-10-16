
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horario_detalles', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('horario_id');

            $table->unsignedBigInteger('bloque_horario_id');

            $table->unsignedTinyInteger('dia_semana');

            $table->unsignedBigInteger('carga_academica_id')->nullable();

            $table->unsignedBigInteger('docente_id')->nullable();

            $table->string('aula', 80)->nullable();

            $table->timestamps();

            $table->unique(
                ['horario_id', 'dia_semana', 'bloque_horario_id'],
                'uq_horario_casilla'
            );

            $table->foreign(
                'horario_id',
                'fk_horario_detalles_horario_id'
            )
                ->references('id')
                ->on('horarios');

            $table->foreign(
                'bloque_horario_id',
                'fk_horario_detalles_bloque_horario_id'
            )
                ->references('id')
                ->on('bloques_horarios');

            $table->foreign(
                'carga_academica_id',
                'fk_horario_detalles_carga_academica_id'
            )
                ->references('id')
                ->on('cargas_academicas');

            $table->foreign(
                'docente_id',
                'fk_horario_detalles_docente_id'
            )
                ->references('id')
                ->on('docentes');
        });

        DB::statement("
            ALTER TABLE horario_detalles
            ADD CONSTRAINT chk_horario_dia
            CHECK (dia_semana BETWEEN 1 AND 7)
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('horario_detalles');
    }
};
