
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hojas_talonario', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('talonario_id');

            $table->unsignedBigInteger('cuenta_por_cobrar_id');

            $table->unsignedBigInteger('autorizacion_detalle_id')->nullable();

            $table->unsignedBigInteger('hoja_origen_id')->nullable();

            $table->string('codigo', 100)->unique();

            $table->enum('tipo', [
                'ORDINARIA',
                'SUSTITUTIVA',
                'EXTRAORDINARIA'
            ]);

            $table->decimal('monto_impreso', 12, 2);

            $table->date('fecha_vencimiento_impresa');

            $table->string('nombre_estudiante_impreso', 255);

            $table->string('concepto_impreso', 255);

            $table->string('periodo_impreso', 80)->nullable();

            $table->enum('estado', [
                'VIGENTE',
                'SUSTITUIDA',
                'ANULADA'
            ])->default('VIGENTE');

            $table->dateTime('fecha_emision');

            $table->unsignedBigInteger('anulada_por')->nullable();

            $table->dateTime('anulada_at')->nullable();

            $table->text('motivo_anulacion')->nullable();

            $table->timestamps();

            $table->unique(
                'autorizacion_detalle_id',
                'uq_hoja_detalle'
            );

            $table->index(
                ['cuenta_por_cobrar_id', 'estado'],
                'idx_hoja_obligacion'
            );

            $table->foreign(
                'talonario_id',
                'fk_hojas_talonario_talonario_id'
            )
                ->references('id')
                ->on('talonarios_pago');

            $table->foreign(
                'cuenta_por_cobrar_id',
                'fk_hojas_talonario_cuenta_por_cobrar_id'
            )
                ->references('id')
                ->on('cuentas_por_cobrar');

            $table->foreign(
                'autorizacion_detalle_id',
                'fk_hojas_talonario_autorizacion_detalle_id'
            )
                ->references('id')
                ->on('autorizaciones_pagos_parciales_detalles');

            $table->foreign(
                'hoja_origen_id',
                'fk_hojas_talonario_hoja_origen_id'
            )
                ->references('id')
                ->on('hojas_talonario');

            $table->foreign(
                'anulada_por',
                'fk_hojas_talonario_anulada_por'
            )
                ->references('id')
                ->on('usuarios');
        });

        DB::statement("
            ALTER TABLE hojas_talonario
            ADD CONSTRAINT chk_hoja_monto
            CHECK (monto_impreso >= 0)
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('hojas_talonario');
    }
};
