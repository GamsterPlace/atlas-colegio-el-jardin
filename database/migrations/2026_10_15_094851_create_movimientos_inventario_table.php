
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('producto_uniforme_id');

            $table->enum('tipo', [
                'ENTRADA',
                'SALIDA',
                'AJUSTE_POSITIVO',
                'AJUSTE_NEGATIVO'
            ]);

            $table->unsignedInteger('cantidad');

            $table->string('motivo', 255);

            $table->string('referencia', 120)->nullable();

            $table->unsignedBigInteger('registrado_por');

            $table->dateTime('fecha_movimiento')->useCurrent();

            $table->timestamps();

            $table->foreign(
                'producto_uniforme_id',
                'fk_movimientos_inventario_producto_uniforme_id'
            )
                ->references('id')
                ->on('productos_uniformes');

            $table->foreign(
                'registrado_por',
                'fk_movimientos_inventario_registrado_por'
            )
                ->references('id')
                ->on('usuarios');
        });

        DB::statement("
            ALTER TABLE movimientos_inventario
            ADD CONSTRAINT chk_movimiento_cantidad
            CHECK (cantidad > 0)
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_inventario');
    }
};
