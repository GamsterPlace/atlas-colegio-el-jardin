<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('preguntas_frecuentes', function (Blueprint $table) {
            $table->id();
            $table->string('pregunta', 250);
            $table->text('respuesta');
            $table->string('categoria', 80)->default('General');
            $table->unsignedInteger('orden')->default(0);
            $table->boolean('publicada')->default(false);
            $table->boolean('destacada')->default(false);
            $table->foreignId('creado_por')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->foreignId('actualizado_por')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->timestamps();
            $table->index(['publicada', 'orden']);
            $table->index(['publicada', 'destacada', 'orden'], 'faq_destacadas_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preguntas_frecuentes');
    }
};
