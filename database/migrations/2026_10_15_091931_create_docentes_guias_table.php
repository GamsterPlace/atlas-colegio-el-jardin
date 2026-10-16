
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('docentes_guias', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('docente_id');

            $table->unsignedBigInteger('seccion_id');

            $table->date('fecha_inicio');

            $table->date('fecha_fin')->nullable();

            $table->timestamps();

            $table->index(
                ['seccion_id', 'fecha_fin'],
                'idx_guia_seccion'
            );

            $table->foreign(
                'docente_id',
                'fk_docentes_guias_docente_id'
            )
                ->references('id')
                ->on('docentes');

            $table->foreign(
                'seccion_id',
                'fk_docentes_guias_seccion_id'
            )
                ->references('id')
                ->on('secciones');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('docentes_guias');
    }
};
