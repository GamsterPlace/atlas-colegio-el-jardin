
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eventos_agenda_destinatarios', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('evento_agenda_id');

            $table->unsignedBigInteger('rol_id')->nullable();

            $table->unsignedBigInteger('nivel_id')->nullable();

            $table->unsignedBigInteger('grado_id')->nullable();

            $table->unsignedBigInteger('seccion_id')->nullable();

            $table->unsignedBigInteger('usuario_id')->nullable();

            $table->timestamps();

            $table->foreign(
                'evento_agenda_id',
                'fk_eventos_agenda_destinatarios_evento_agenda_id'
            )
                ->references('id')
                ->on('eventos_agenda');

            $table->foreign(
                'rol_id',
                'fk_eventos_agenda_destinatarios_rol_id'
            )
                ->references('id')
                ->on('roles');

            $table->foreign(
                'nivel_id',
                'fk_eventos_agenda_destinatarios_nivel_id'
            )
                ->references('id')
                ->on('niveles');

            $table->foreign(
                'grado_id',
                'fk_eventos_agenda_destinatarios_grado_id'
            )
                ->references('id')
                ->on('grados');

            $table->foreign(
                'seccion_id',
                'fk_eventos_agenda_destinatarios_seccion_id'
            )
                ->references('id')
                ->on('secciones');

            $table->foreign(
                'usuario_id',
                'fk_eventos_agenda_destinatarios_usuario_id'
            )
                ->references('id')
                ->on('usuarios');
        });

        DB::statement("
            ALTER TABLE eventos_agenda_destinatarios
            ADD CONSTRAINT chk_destino_agenda
            CHECK (
                (rol_id IS NOT NULL) +
                (nivel_id IS NOT NULL) +
                (grado_id IS NOT NULL) +
                (seccion_id IS NOT NULL) +
                (usuario_id IS NOT NULL) = 1
            )
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('eventos_agenda_destinatarios');
    }
};
