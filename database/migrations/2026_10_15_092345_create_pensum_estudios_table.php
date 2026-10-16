
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pensum_estudios', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('nivel_id');

            $table->string('nombre', 150);

            $table->string('version', 40)->nullable();

            $table->date('vigente_desde')->nullable();

            $table->date('vigente_hasta')->nullable();

            $table->boolean('activo')->default(true);

            $table->timestamps();

            $table->foreign(
                'nivel_id',
                'fk_pensum_estudios_nivel_id'
            )
                ->references('id')
                ->on('niveles');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pensum_estudios');
    }
};
