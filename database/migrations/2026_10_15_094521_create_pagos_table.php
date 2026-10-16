
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('hoja_talonario_id');

            $table->unsignedBigInteger('pago_origen_id')->nullable();

            $table->unsignedBigInteger('estudiante_id');

            $table->unsignedBigInteger('cuenta_por_cobrar_id');

            $table->date('fecha_deposito');

            $table->dateTime('fecha_registro')->useCurrent();

            $table->decimal('monto', 12, 2);

            $table->decimal('monto_aplicado_principal', 12, 2)
                ->default(0);

            $table->decimal('monto_aplicado_mora', 12, 2)
                ->default(0);

            $table->string('banco', 120)->nullable();

            $table->string('referencia_bancaria', 120);

            $table->unsignedBigInteger('registrado_por');

            $table->enum('estado', [
                'VALIDO',
                'ANULADO'
            ])->default('VALIDO');

            $table->unsignedBigInteger('anulado_por')->nullable();

            $table->dateTime('anulado_at')->nullable();

            $table->text('motivo_anulacion')->nullable();

            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->unique(
                'pago_origen_id',
                'uq_pago_correccion'
            );

            $table->index(
                ['hoja_talonario_id', 'estado'],
                'idx_pago_hoja_estado'
            );

            $table->index(
                'referencia_bancaria',
                'idx_pago_referencia'
            );

            $table->foreign(
                'hoja_talonario_id',
                'fk_pagos_hoja_talonario_id'
            )
                ->references('id')
                ->on('hojas_talonario');

            $table->foreign(
                'pago_origen_id',
                'fk_pagos_pago_origen_id'
            )
                ->references('id')
                ->on('pagos');

            $table->foreign(
                'estudiante_id',
                'fk_pagos_estudiante_id'
            )
                ->references('id')
                ->on('estudiantes');

            $table->foreign(
                'cuenta_por_cobrar_id',
                'fk_pagos_cuenta_por_cobrar_id'
            )
                ->references('id')
                ->on('cuentas_por_cobrar');

            $table->foreign(
                'registrado_por',
                'fk_pagos_registrado_por'
            )
                ->references('id')
                ->on('usuarios');

            $table->foreign(
                'anulado_por',
                'fk_pagos_anulado_por'
            )
                ->references('id')
                ->on('usuarios');
        });

        DB::statement("
            ALTER TABLE pagos
            ADD CONSTRAINT chk_pago_monto
            CHECK (
                monto > 0
                AND monto_aplicado_principal >= 0
                AND monto_aplicado_mora >= 0
                AND monto = monto_aplicado_principal + monto_aplicado_mora
            )
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
