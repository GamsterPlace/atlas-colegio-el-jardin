
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comunicado_destinatarios', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('comunicado_id');

            $table->unsignedBigInteger('rol_id')->nullable();

            $table->unsignedBigInteger('seccion_id')->nullable();

            $table->unsignedBigInteger('usuario_id')->nullable();

            $table->timestamps();

            $table->foreign(
                'comunicado_id',
                'fk_comunicado_destinatarios_comunicado_id'
            )
                ->references('id')
                ->on('comunicados');

            $table->foreign(
                'rol_id',
                'fk_comunicado_destinatarios_rol_id'
            )
                ->references('id')
                ->on('roles');

            $table->foreign(
                'seccion_id',
                'fk_comunicado_destinatarios_seccion_id'
            )
                ->references('id')
                ->on('secciones');

            $table->foreign(
                'usuario_id',
                'fk_comunicado_destinatarios_usuario_id'
            )
                ->references('id')
                ->on('usuarios');
        });

        DB::statement("
            ALTER TABLE comunicado_destinatarios
            ADD CONSTRAINT chk_comunicado_destino
            CHECK (
                (rol_id IS NOT NULL) +
                (seccion_id IS NOT NULL) +
                (usuario_id IS NOT NULL) = 1
            )
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('comunicado_destinatarios');
    }
};
