
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('autorizaciones_pagos_parciales', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('cuenta_por_cobrar_id');

            $table->string('numero_autorizacion', 90)->unique();

            $table->unsignedBigInteger('autorizacion_origen_id')->nullable();

            $table->decimal('monto_autorizado', 12, 2);

            $table->text('motivo');

            $table->enum('estado', [
                'AUTORIZADA',
                'COMPLETADA',
                'SUSTITUIDA',
                'ANULADA'
            ])->default('AUTORIZADA');

            $table->unsignedBigInteger('autorizado_por');

            $table->dateTime('fecha_autorizacion');

            $table->text('motivo_sustitucion')->nullable();

            $table->text('motivo_anulacion')->nullable();

            $table->unsignedBigInteger('anulada_por')->nullable();

            $table->dateTime('anulada_at')->nullable();

            $table->string('ruta_pdf_firmado', 500)->nullable();

            $table->timestamps();

            $table->unique(
                'autorizacion_origen_id',
                'uq_autorizacion_sustituta'
            );

            $table->foreign(
                'cuenta_por_cobrar_id',
                'fk_autorizaciones_pagos_parciales_cuenta_por_cobrar_id'
            )
                ->references('id')
                ->on('cuentas_por_cobrar');

            $table->foreign(
                'autorizacion_origen_id',
                'fk_autorizaciones_pagos_parciales_autorizacion_origen_id'
            )
                ->references('id')
                ->on('autorizaciones_pagos_parciales');

            $table->foreign(
                'autorizado_por',
                'fk_autorizaciones_pagos_parciales_autorizado_por'
            )
                ->references('id')
                ->on('usuarios');

            $table->foreign(
                'anulada_por',
                'fk_autorizaciones_pagos_parciales_anulada_por'
            )
                ->references('id')
                ->on('usuarios');
        });

        DB::statement("
            ALTER TABLE autorizaciones_pagos_parciales
            ADD CONSTRAINT chk_autorizacion_monto
            CHECK (monto_autorizado > 0)
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('autorizaciones_pagos_parciales');
    }
};
