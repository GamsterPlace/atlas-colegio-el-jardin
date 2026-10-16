
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('datos_institucion', function (Blueprint $table) {
            $table->id();

            $table->string('nombre', 190);

            $table->string('nombre_legal', 190)->nullable();

            $table->string('codigo_establecimiento', 80)->nullable();

            $table->string('nit', 30)->nullable();

            $table->text('direccion')->nullable();

            $table->string('telefono', 40)->nullable();

            $table->string('correo', 190)->nullable();

            $table->string('logo_ruta', 500)->nullable();

            $table->string('cuenta_bancaria_descripcion', 255)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('datos_institucion');
    }
};
