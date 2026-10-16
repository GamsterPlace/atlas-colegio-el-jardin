
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->string('nombres', 120);
            $table->string('apellidos', 120);
            $table->string('cui', 20)->nullable()->unique('uq_personas_cui');
            $table->date('fecha_nacimiento')->nullable();
            $table->string('sexo', 30)->nullable();
            $table->string('telefono', 30)->nullable();
            $table->string('correo', 190)->nullable();
            $table->text('direccion')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
