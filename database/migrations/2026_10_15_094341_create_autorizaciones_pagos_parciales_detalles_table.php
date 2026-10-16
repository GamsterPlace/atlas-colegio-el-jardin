
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('autorizaciones_pagos_parciales_detalles', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('autorizacion_id');

            $table->unsignedSmallInteger('numero_parcialidad');

            $table->decimal('monto', 12, 2);

            $table->timestamps();

            $table->unique(
                [
                    'autorizacion_id',
                    'numero_parcialidad'
                ],
                'uq_detalle_parcialidad'
            );

            $table->foreign(
                'autorizacion_id',
                'fk_autorizaciones_pagos_parciales_detalles_autorizacion_id'
            )
                ->references('id')
                ->on('autorizaciones_pagos_parciales');
        });

        DB::statement("
            ALTER TABLE autorizaciones_pagos_parciales_detalles
            ADD CONSTRAINT chk_parcialidad_monto
            CHECK (monto > 0)
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('autorizaciones_pagos_parciales_detalles');
    }
};
