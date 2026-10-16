
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lecturas_comunicados', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('comunicado_id');

            $table->unsignedBigInteger('usuario_id');

            $table->dateTime('leido_at');

            $table->timestamps();

            $table->unique(
                [
                    'comunicado_id',
                    'usuario_id'
                ],
                'uq_lectura'
            );

            $table->foreign(
                'comunicado_id',
                'fk_lecturas_comunicados_comunicado_id'
            )
                ->references('id')
                ->on('comunicados');

            $table->foreign(
                'usuario_id',
                'fk_lecturas_comunicados_usuario_id'
            )
                ->references('id')
                ->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lecturas_comunicados');
    }
};
