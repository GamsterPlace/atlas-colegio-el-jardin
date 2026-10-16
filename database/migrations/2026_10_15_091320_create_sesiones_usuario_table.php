
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sesiones_usuario', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('usuario_id');

            $table->string('identificador', 128)->unique();

            $table->string('ip', 45)->nullable();

            $table->text('agente_usuario')->nullable();

            $table->dateTime('ultimo_acceso_at');

            $table->dateTime('revocada_at')->nullable();

            $table->timestamps();

            $table->index(
                ['usuario_id', 'ultimo_acceso_at'],
                'idx_sesion_usuario'
            );

            $table->foreign(
                'usuario_id',
                'fk_sesiones_usuario_usuario_id'
            )
                ->references('id')
                ->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sesiones_usuario');
    }
};
