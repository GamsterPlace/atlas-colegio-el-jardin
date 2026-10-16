
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recordatorios_agenda', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('evento_agenda_id');

            $table->unsignedBigInteger('usuario_id');

            $table->dateTime('recordar_at');

            $table->dateTime('enviado_at')->nullable();

            $table->timestamps();

            $table->unique(
                ['evento_agenda_id', 'usuario_id', 'recordar_at'],
                'uq_recordatorio'
            );

            $table->foreign(
                'evento_agenda_id',
                'fk_recordatorios_agenda_evento_agenda_id'
            )
                ->references('id')
                ->on('eventos_agenda');

            $table->foreign(
                'usuario_id',
                'fk_recordatorios_agenda_usuario_id'
            )
                ->references('id')
                ->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recordatorios_agenda');
    }
};
