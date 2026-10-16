
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comunicados', function (Blueprint $table) {
            $table->id();

            $table->string('titulo', 190);

            $table->longText('contenido');

            $table->enum('estado', [
                'BORRADOR',
                'PUBLICADO',
                'ARCHIVADO'
            ])->default('BORRADOR');

            $table->dateTime('publicado_at')->nullable();

            $table->unsignedBigInteger('creado_por');

            $table->timestamps();

            $table->foreign(
                'creado_por',
                'fk_comunicados_creado_por'
            )
                ->references('id')
                ->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comunicados');
    }
};
