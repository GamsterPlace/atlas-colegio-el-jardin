
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos_uniformes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('categoria_uniforme_id');

            $table->string('sku', 70)->unique();

            $table->string('nombre', 150);

            $table->string('talla', 40)->nullable();

            $table->string('color', 60)->nullable();

            $table->decimal('precio_referencia', 12, 2)->nullable();

            $table->boolean('activo')->default(true);

            $table->timestamps();

            $table->foreign(
                'categoria_uniforme_id',
                'fk_productos_uniformes_categoria_uniforme_id'
            )
                ->references('id')
                ->on('categorias_uniformes');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos_uniformes');
    }
};
