<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('productos_uniformes', function (Blueprint $table) {
            $table->string('foto_path', 255)->nullable();
            $table->text('descripcion')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('productos_uniformes', function (Blueprint $table) {
            $table->dropColumn(['foto_path', 'descripcion']);
        });
    }
};
