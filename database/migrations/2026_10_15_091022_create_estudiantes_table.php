
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();

            $table->string('codigo', 40)->unique();

            $table->string('nombres', 120);

            $table->string('apellidos', 120);

            $table->date('fecha_nacimiento')->nullable();

            $table->string('sexo', 30)->nullable();

            $table->text('direccion')->nullable();

            $table->enum('estado', [
                'ACTIVO',
                'INACTIVO',
                'EGRESADO'
            ])->default('ACTIVO');

            $table->timestamps();

            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};
