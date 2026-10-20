<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('asistencia_estudiantes', function (Blueprint $table) {
            $table->dateTime('hora_entrada')->nullable();
            $table->dateTime('hora_salida')->nullable();
            $table->boolean('entrada_tarde')->default(false);
            $table->foreignId('salida_registrada_por')->nullable()->constrained('usuarios')->nullOnDelete();
        });
    }
    public function down(): void {
        Schema::table('asistencia_estudiantes', function (Blueprint $table) {
            $table->dropForeign(['salida_registrada_por']);
            $table->dropColumn(['hora_entrada','hora_salida','entrada_tarde','salida_registrada_por']);
        });
    }
};
