<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('informacion_complementaria_estudiantes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('estudiante_id')->unique('uq_info_complementaria_estudiante');
            $table->string('religion', 120)->nullable();
            $table->string('telefono_casa', 30)->nullable();
            $table->string('transporte', 255)->nullable();
            $table->unsignedSmallInteger('numero_hermanos')->nullable();
            $table->unsignedSmallInteger('hermanos_mayores')->nullable();
            $table->unsignedSmallInteger('hermanos_menores')->nullable();
            $table->text('hermanos_inscritos')->nullable();
            $table->timestamps();
            $table->foreign('estudiante_id', 'fk_info_complementaria_estudiante')
                ->references('id')->on('estudiantes')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('informacion_complementaria_estudiantes');
    }
};
