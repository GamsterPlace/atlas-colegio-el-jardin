
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asignaciones_docentes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('docente_id');

            $table->unsignedBigInteger('carga_academica_id');

            $table->date('fecha_inicio');

            $table->date('fecha_fin')->nullable();

            $table->boolean('activo')->default(true);

            $table->timestamps();

            $table->index(
                ['carga_academica_id', 'activo'],
                'idx_asignacion_carga'
            );

            $table->foreign(
                'docente_id',
                'fk_asignaciones_docentes_docente_id'
            )
                ->references('id')
                ->on('docentes');

            $table->foreign(
                'carga_academica_id',
                'fk_asignaciones_docentes_carga_academica_id'
            )
                ->references('id')
                ->on('cargas_academicas');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asignaciones_docentes');
    }
};
