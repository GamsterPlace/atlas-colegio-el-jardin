
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entregas_uniformes_detalles', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('entrega_uniforme_id');

            $table->unsignedBigInteger('producto_uniforme_id');

            $table->unsignedInteger('cantidad');

            $table->timestamps();

            $table->foreign(
                'entrega_uniforme_id',
                'fk_entregas_uniformes_detalles_entrega_uniforme_id'
            )
                ->references('id')
                ->on('entregas_uniformes');

            $table->foreign(
                'producto_uniforme_id',
                'fk_entregas_uniformes_detalles_producto_uniforme_id'
            )
                ->references('id')
                ->on('productos_uniformes');
        });

        DB::statement("
            ALTER TABLE entregas_uniformes_detalles
            ADD CONSTRAINT chk_entrega_cantidad
            CHECK (cantidad > 0)
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('entregas_uniformes_detalles');
    }
};
