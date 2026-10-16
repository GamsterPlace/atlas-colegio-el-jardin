
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personal', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('persona_id');

            $table->string('codigo_empleado', 40)
                ->nullable()
                ->unique();

            $table->date('fecha_ingreso')->nullable();

            $table->string('cargo', 120)->nullable();

            $table->boolean('activo')->default(true);

            $table->timestamps();

            $table->unique('persona_id');

            $table->foreign('persona_id')
                ->references('id')
                ->on('personas');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal');
    }
};
