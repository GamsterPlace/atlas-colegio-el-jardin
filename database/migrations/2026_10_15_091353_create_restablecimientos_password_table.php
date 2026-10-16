
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restablecimientos_password', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('usuario_id');

            $table->string('token_hash', 255)->unique();

            $table->dateTime('vence_at');

            $table->dateTime('utilizado_at')->nullable();

            $table->timestamps();

            $table->foreign(
                'usuario_id',
                'fk_restablecimientos_password_usuario_id'
            )
                ->references('id')
                ->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restablecimientos_password');
    }
};
