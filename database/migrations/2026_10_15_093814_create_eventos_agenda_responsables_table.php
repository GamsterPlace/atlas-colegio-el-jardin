
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eventos_agenda_responsables', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('evento_agenda_id');

            $table->unsignedBigInteger('usuario_id');

            $table->timestamps();

            $table->unique(
                ['evento_agenda_id', 'usuario_id'],
                'uq_evento_responsable'
            );

            $table->foreign(
                'evento_agenda_id',
                'fk_eventos_agenda_responsables_evento_agenda_id'
            )
                ->references('id')
                ->on('eventos_agenda');

            $table->foreign(
                'usuario_id',
                'fk_eventos_agenda_responsables_usuario_id'
            )
                ->references('id')
                ->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eventos_agenda_responsables');
    }
};
