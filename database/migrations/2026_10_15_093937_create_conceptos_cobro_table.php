
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conceptos_cobro', function (Blueprint $table) {
            $table->id();

            $table->string('codigo', 50)->unique();

            $table->string('nombre', 150);

            $table->enum('tipo', [
                'INSCRIPCION',
                'COLEGIATURA',
                'OTRO'
            ])->default('OTRO');

            $table->boolean('activo')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conceptos_cobro');
    }
};
