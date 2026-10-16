
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuraciones_sistema', function (Blueprint $table) {
            $table->id();

            $table->string('clave', 120)->unique();

            $table->json('valor')->nullable();

            $table->string('descripcion', 255)->nullable();

            $table->unsignedBigInteger('actualizado_por')->nullable();

            $table->timestamps();

            $table->foreign(
                'actualizado_por',
                'fk_configuraciones_sistema_actualizado_por'
            )
                ->references('id')
                ->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuraciones_sistema');
    }
};
