
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disponibilidad_docentes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('docente_id');

            $table->unsignedBigInteger('ciclo_escolar_id');

            $table->unsignedTinyInteger('dia_semana');

            $table->time('hora_inicio');

            $table->time('hora_fin');

            $table->boolean('disponible')->default(true);

            $table->timestamps();

            $table->foreign(
                'docente_id',
                'fk_disponibilidad_docentes_docente_id'
            )
                ->references('id')
                ->on('docentes');

            $table->foreign(
                'ciclo_escolar_id',
                'fk_disponibilidad_docentes_ciclo_escolar_id'
            )
                ->references('id')
                ->on('ciclos_escolares');
        });

        DB::statement("
            ALTER TABLE disponibilidad_docentes
            ADD CONSTRAINT chk_disp_dia
            CHECK (dia_semana BETWEEN 1 AND 7)
        ");

        DB::statement("
            ALTER TABLE disponibilidad_docentes
            ADD CONSTRAINT chk_disp_horas
            CHECK (hora_fin > hora_inicio)
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('disponibilidad_docentes');
    }
};
