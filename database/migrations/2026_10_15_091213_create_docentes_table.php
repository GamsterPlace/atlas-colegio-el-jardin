
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('docentes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('personal_id');

            $table->boolean('activo')->default(true);

            $table->timestamps();

            $table->unique('personal_id');

            $table->foreign('personal_id')
                ->references('id')
                ->on('personal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('docentes');
    }
};
