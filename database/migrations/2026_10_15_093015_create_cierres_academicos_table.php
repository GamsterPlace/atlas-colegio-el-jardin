
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cierres_academicos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('carga_academica_id');

            $table->unsignedBigInteger('periodo_academico_id');

            $table->unsignedBigInteger('cerrado_por');

            $table->dateTime('cerrado_at');

            $table->text('motivo')->nullable();

            $table->timestamps();

            $table->unique(
                ['carga_academica_id', 'periodo_academico_id'],
                'uq_cierre'
            );

            $table->foreign(
                'carga_academica_id',
                'fk_cierres_academicos_carga_academica_id'
            )
                ->references('id')
                ->on('cargas_academicas');

            $table->foreign(
                'periodo_academico_id',
                'fk_cierres_academicos_periodo_academico_id'
            )
                ->references('id')
                ->on('periodos_academicos');

            $table->foreign(
                'cerrado_por',
                'fk_cierres_academicos_cerrado_por'
            )
                ->references('id')
                ->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cierres_academicos');
    }
};
