
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publicaciones', function (Blueprint $table) {
            $table->id();

            $table->string('titulo', 190);

            $table->string('slug', 220)->unique();

            $table->text('resumen')->nullable();

            $table->longText('contenido');

            $table->enum('estado', [
                'BORRADOR',
                'PUBLICADA',
                'ARCHIVADA'
            ])->default('BORRADOR');

            $table->dateTime('publicada_at')->nullable();

            $table->unsignedBigInteger('autor_id');

            $table->timestamps();

            $table->foreign(
                'autor_id',
                'fk_publicaciones_autor_id'
            )
                ->references('id')
                ->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publicaciones');
    }
};
