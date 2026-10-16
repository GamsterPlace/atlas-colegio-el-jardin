
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contactos_emergencia', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('estudiante_id');

            $table->string('nombre', 190);

            $table->string('parentesco', 80)->nullable();

            $table->string('telefono', 40);

            $table->unsignedSmallInteger('prioridad')->default(1);

            $table->timestamps();

            $table->foreign(
                'estudiante_id',
                'fk_contactos_emergencia_estudiante_id'
            )
                ->references('id')
                ->on('estudiantes');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contactos_emergencia');
    }
};
