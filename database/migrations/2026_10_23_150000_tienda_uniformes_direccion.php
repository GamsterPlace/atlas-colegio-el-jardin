<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('productos_uniformes', 'descripcion')) {
            Schema::table('productos_uniformes', fn (Blueprint $table) => $table->text('descripcion')->nullable());
        }
        if (!Schema::hasColumn('productos_uniformes', 'foto_path')) {
            Schema::table('productos_uniformes', fn (Blueprint $table) => $table->string('foto_path', 255)->nullable());
        }
        Schema::create('pedidos_uniformes', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 32)->unique();
            $table->foreignId('estudiante_id')->constrained('estudiantes');
            $table->foreignId('responsable_id')->constrained('responsables');
            $table->foreignId('creado_por')->nullable()->constrained('usuarios');
            $table->enum('origen', ['DIRECCION', 'PORTAL'])->default('DIRECCION');
            $table->enum('estado', ['PENDIENTE', 'EN_PROCESO', 'PREPARADO', 'ENTREGADO', 'ANULADO'])->default('PENDIENTE');
            $table->enum('estado_pago', ['PENDIENTE', 'PAGADO'])->default('PENDIENTE');
            $table->decimal('total', 12, 2);
            $table->text('observaciones')->nullable();
            $table->timestamp('vence_at')->index();
            $table->foreignId('procesado_por')->nullable()->constrained('usuarios');
            $table->timestamp('procesado_at')->nullable();
            $table->foreignId('preparado_por')->nullable()->constrained('usuarios');
            $table->timestamp('preparado_at')->nullable();
            $table->foreignId('entregado_por')->nullable()->constrained('usuarios');
            $table->timestamp('entregado_at')->nullable();
            $table->string('recibido_por', 180)->nullable();
            $table->string('motivo_anulacion', 255)->nullable();
            $table->timestamp('anulado_at')->nullable();
            $table->foreignId('anulado_por')->nullable()->constrained('usuarios');
            $table->timestamps();
            $table->index(['estado', 'created_at']);
        });
        Schema::create('pedidos_uniformes_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_uniforme_id')->constrained('pedidos_uniformes')->cascadeOnDelete();
            $table->foreignId('producto_uniforme_id')->constrained('productos_uniformes');
            $table->unsignedInteger('cantidad');
            $table->decimal('precio_unitario', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->unique(['pedido_uniforme_id', 'producto_uniforme_id'], 'pedido_uniforme_producto_unico');
        });
        Schema::create('pagos_uniformes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_uniforme_id')->unique()->constrained('pedidos_uniformes');
            $table->enum('metodo', ['EFECTIVO', 'DEPOSITO']);
            $table->decimal('monto', 12, 2);
            $table->string('referencia_deposito', 120)->nullable();
            $table->date('fecha_deposito')->nullable();
            $table->foreignId('registrado_por')->constrained('usuarios');
            $table->timestamps();
        });
        Schema::create('eventos_pedidos_uniformes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_uniforme_id')->constrained('pedidos_uniformes');
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios');
            $table->string('accion', 60);
            $table->text('detalle')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eventos_pedidos_uniformes');
        Schema::dropIfExists('pagos_uniformes');
        Schema::dropIfExists('pedidos_uniformes_detalles');
        Schema::dropIfExists('pedidos_uniformes');
        Schema::table('productos_uniformes', function (Blueprint $table) {
            $table->dropColumn(['descripcion', 'foto_path']);
        });
    }
};
