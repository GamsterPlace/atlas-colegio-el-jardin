
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eventos_agenda', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ciclo_escolar_id');

            $table->string('titulo', 190);

            $table->text('descripcion')->nullable();

            $table->dateTime('inicio');

            $table->dateTime('fin')->nullable();

            $table->string('ubicacion', 255)->nullable();

            $table->enum('visibilidad', [
                'PUBLICO',
                'INSTITUCIONAL',
                'DESTINATARIOS'
            ])->default('DESTINATARIOS');

            $table->enum('estado', [
                'PROGRAMADO',
                'CANCELADO',
                'REALIZADO'
            ])->default('PROGRAMADO');

            $table->unsignedBigInteger('creado_por');

            $table->timestamps();

            $table->foreign(
                'ciclo_escolar_id',
                'fk_eventos_agenda_ciclo_escolar_id'
            )
                ->references('id')
                ->on('ciclos_escolares');

            $table->foreign(
                'creado_por',
                'fk_eventos_agenda_creado_por'
            )
                ->references('id')
                ->on('usuarios');
        });

        DB::statement("
            ALTER TABLE eventos_agenda
            ADD CONSTRAINT chk_evento_fechas
            CHECK (fin IS NULL OR fin >= inicio)
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('eventos_agenda');
    }
};
