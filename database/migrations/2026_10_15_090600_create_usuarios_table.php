
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();

            $table->foreignId('persona_id')
                ->unique('uq_usuarios_persona')
                ->constrained('personas', 'id', 'fk_usuarios_persona');

            $table->string('nombre_usuario', 100)
                ->unique('uq_usuarios_nombre');

            $table->string('email', 190)
                ->nullable()
                ->unique('uq_usuarios_email');

            $table->string('password', 255);

            $table->boolean('activo')->default(true);

            $table->dateTime('ultimo_acceso_at')->nullable();

            $table->rememberToken();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
