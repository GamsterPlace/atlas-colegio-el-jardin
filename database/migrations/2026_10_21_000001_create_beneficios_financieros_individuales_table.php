<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('beneficios_financieros_individuales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuenta_por_cobrar_id')->unique()->constrained('cuentas_por_cobrar');
            $table->enum('tipo', ['DESCUENTO_FIJO', 'DESCUENTO_PORCENTAJE', 'TARIFA_ESPECIAL']);
            $table->decimal('valor', 12, 2);
            $table->decimal('monto_anterior', 12, 2);
            $table->decimal('monto_nuevo', 12, 2);
            $table->text('motivo');
            $table->foreignId('autorizado_por')->constrained('usuarios');
            $table->timestamp('autorizado_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beneficios_financieros_individuales');
    }
};
