
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periodos_academicos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ciclo_escolar_id');

            $table->string('nombre', 100);

            $table->unsignedSmallInteger('numero');

            $table->date('fecha_inicio');

            $table->date('fecha_fin');

            $table->enum('estado', [
                'ABIERTO',
                'CERRADO'
            ])->default('ABIERTO');

            $table->timestamps();

            $table->unique(
                ['ciclo_escolar_id', 'numero'],
                'uq_periodo'
            );

            $table->foreign(
                'ciclo_escolar_id',
                'fk_periodos_academicos_ciclo_escolar_id'
            )
                ->references('id')
                ->on('ciclos_escolares');
        });

        DB::statement("
            ALTER TABLE periodos_academicos
            ADD CONSTRAINT chk_periodo_fechas
            CHECK (fecha_fin >= fecha_inicio)
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('periodos_academicos');
    }
};
