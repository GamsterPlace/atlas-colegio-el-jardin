
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bitacora_auditoria', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('usuario_id')->nullable();

            $table->string('accion', 100);

            $table->string('entidad', 100);

            $table->unsignedBigInteger('entidad_id')->nullable();

            $table->json('datos_anteriores')->nullable();

            $table->json('datos_nuevos')->nullable();

            $table->text('motivo')->nullable();

            $table->string('ip', 45)->nullable();

            $table->dateTime('fecha_evento')
                ->useCurrent();

            $table->index(
                ['entidad', 'entidad_id'],
                'idx_auditoria_entidad'
            );

            $table->index(
                'fecha_evento',
                'idx_auditoria_fecha'
            );

            $table->foreign('usuario_id')
                ->references('id')
                ->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bitacora_auditoria');
    }
};
