
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asistencia_personal', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('personal_id');

            $table->date('fecha');

            $table->enum('estado', [
                'PRESENTE',
                'AUSENTE',
                'TARDE',
                'PERMISO',
                'JUSTIFICADA'
            ]);

            $table->time('hora_entrada')->nullable();

            $table->time('hora_salida')->nullable();

            $table->unsignedBigInteger('registrado_por')->nullable();

            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->unique(
                ['personal_id', 'fecha'],
                'uq_asistencia_personal'
            );

            $table->foreign(
                'personal_id',
                'fk_asistencia_personal_personal_id'
            )
                ->references('id')
                ->on('personal');

            $table->foreign(
                'registrado_por',
                'fk_asistencia_personal_registrado_por'
            )
                ->references('id')
                ->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asistencia_personal');
    }
};
