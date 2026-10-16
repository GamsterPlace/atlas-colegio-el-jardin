
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('justificaciones_personal', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('asistencia_personal_id');

            $table->text('motivo');

            $table->string('ruta_documento', 500)->nullable();

            $table->unsignedBigInteger('aprobado_por')->nullable();

            $table->enum('estado', [
                'PENDIENTE',
                'APROBADA',
                'RECHAZADA'
            ])->default('PENDIENTE');

            $table->timestamps();

            $table->foreign(
                'asistencia_personal_id',
                'fk_justificaciones_personal_asistencia_personal_id'
            )
                ->references('id')
                ->on('asistencia_personal');

            $table->foreign(
                'aprobado_por',
                'fk_justificaciones_personal_aprobado_por'
            )
                ->references('id')
                ->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('justificaciones_personal');
    }
};
