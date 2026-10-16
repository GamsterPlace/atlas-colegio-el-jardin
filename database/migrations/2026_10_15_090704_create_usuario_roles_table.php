
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuario_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('rol_id');

            $table->timestamp('created_at')->nullable();

            $table->primary(['usuario_id', 'rol_id']);

            $table->foreign('usuario_id')
                ->references('id')
                ->on('usuarios');

            $table->foreign('rol_id')
                ->references('id')
                ->on('roles');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuario_roles');
    }
};
