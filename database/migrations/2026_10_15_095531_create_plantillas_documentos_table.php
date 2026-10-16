
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plantillas_documentos', function (Blueprint $table) {
            $table->id();

            $table->string('codigo', 100)->unique();

            $table->string('nombre', 150);

            $table->unsignedInteger('version')->default(1);

            $table->longText('contenido');

            $table->boolean('activa')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plantillas_documentos');
    }
};
