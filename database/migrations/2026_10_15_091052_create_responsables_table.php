
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('responsables', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('persona_id');

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
        Schema::dropIfExists('responsables');
    }
};
