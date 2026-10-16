
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('archivos_publicaciones', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('publicacion_id');

            $table->string('ruta_archivo', 500);

            $table->string('nombre_original', 255)->nullable();

            $table->unsignedSmallInteger('orden')->default(0);

            $table->timestamps();

            $table->foreign(
                'publicacion_id',
                'fk_archivos_publicaciones_publicacion_id'
            )
                ->references('id')
                ->on('publicaciones');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('archivos_publicaciones');
    }
};
