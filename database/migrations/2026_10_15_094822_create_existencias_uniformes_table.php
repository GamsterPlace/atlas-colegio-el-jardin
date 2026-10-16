
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('existencias_uniformes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('producto_uniforme_id')
                ->unique();

            $table->integer('cantidad')->default(0);

            $table->unsignedInteger('stock_minimo')->default(0);

            $table->timestamps();

            $table->foreign(
                'producto_uniforme_id',
                'fk_existencias_uniformes_producto_uniforme_id'
            )
                ->references('id')
                ->on('productos_uniformes');
        });

        DB::statement("
            ALTER TABLE existencias_uniformes
            ADD CONSTRAINT chk_existencia_no_negativa
            CHECK (cantidad >= 0)
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('existencias_uniformes');
    }
};
