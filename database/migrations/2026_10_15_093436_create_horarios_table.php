
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('seccion_id');

            $table->unsignedInteger('version')->default(1);

            $table->enum('origen', [
                'MANUAL',
                'GENERADO'
            ])->default('MANUAL');

            $table->enum('estado', [
                'BORRADOR',
                'APROBADO',
                'ARCHIVADO'
            ])->default('BORRADOR');

            $table->unsignedBigInteger('generado_por')->nullable();

            $table->unsignedBigInteger('aprobado_por')->nullable();

            $table->dateTime('aprobado_at')->nullable();

            $table->timestamps();

            $table->unique(
                ['seccion_id', 'version'],
                'uq_horario_version'
            );

            $table->foreign(
                'seccion_id',
                'fk_horarios_seccion_id'
            )
                ->references('id')
                ->on('secciones');

            $table->foreign(
                'generado_por',
                'fk_horarios_generado_por'
            )
                ->references('id')
                ->on('usuarios');

            $table->foreign(
                'aprobado_por',
                'fk_horarios_aprobado_por'
            )
                ->references('id')
                ->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
