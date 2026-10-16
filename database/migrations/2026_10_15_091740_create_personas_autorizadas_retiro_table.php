
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personas_autorizadas_retiro', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('estudiante_id');

            $table->unsignedBigInteger('persona_id')->nullable();

            $table->string('nombre', 190);

            $table->string('identificacion', 40)->nullable();

            $table->string('telefono', 40)->nullable();

            $table->string('parentesco', 80)->nullable();

            $table->boolean('activo')->default(true);

            $table->timestamps();

            $table->foreign(
                'estudiante_id',
                'fk_personas_autorizadas_retiro_estudiante_id'
            )
                ->references('id')
                ->on('estudiantes');

            $table->foreign(
                'persona_id',
                'fk_personas_autorizadas_retiro_persona_id'
            )
                ->references('id')
                ->on('personas');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personas_autorizadas_retiro');
    }
};
