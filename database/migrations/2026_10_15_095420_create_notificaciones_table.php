
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('usuario_id');

            $table->string('titulo', 190);

            $table->text('mensaje');

            $table->string('tipo', 80)->nullable();

            $table->string('enlace', 500)->nullable();

            $table->dateTime('leida_at')->nullable();

            $table->timestamps();

            $table->index(
                ['usuario_id', 'leida_at'],
                'idx_notificaciones'
            );

            $table->foreign(
                'usuario_id',
                'fk_notificaciones_usuario_id'
            )
                ->references('id')
                ->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
