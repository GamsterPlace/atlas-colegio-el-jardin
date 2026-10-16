
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grados', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('nivel_id');

            $table->string('nombre', 100);

            $table->unsignedSmallInteger('orden')->default(0);

            $table->boolean('activo')->default(true);

            $table->unique(
                ['nivel_id', 'nombre'],
                'uq_grado_nivel'
            );

            $table->foreign('nivel_id')
                ->references('id')
                ->on('niveles');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grados');
    }
};
