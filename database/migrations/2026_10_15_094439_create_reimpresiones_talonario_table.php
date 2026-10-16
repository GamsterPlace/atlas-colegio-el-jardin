
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reimpresiones_talonario', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('talonario_id');

            $table->unsignedBigInteger('hoja_talonario_id')->nullable();

            $table->unsignedBigInteger('usuario_id');

            $table->dateTime('fecha_reimpresion')->useCurrent();

            $table->string('ip', 45)->nullable();

            $table->timestamps();

            $table->foreign(
                'talonario_id',
                'fk_reimpresiones_talonario_talonario_id'
            )
                ->references('id')
                ->on('talonarios_pago');

            $table->foreign(
                'hoja_talonario_id',
                'fk_reimpresiones_talonario_hoja_talonario_id'
            )
                ->references('id')
                ->on('hojas_talonario');

            $table->foreign(
                'usuario_id',
                'fk_reimpresiones_talonario_usuario_id'
            )
                ->references('id')
                ->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reimpresiones_talonario');
    }
};
