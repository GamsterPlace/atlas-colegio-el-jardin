
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuentas_por_cobrar', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('estudiante_id');

            $table->unsignedBigInteger('ciclo_escolar_id');

            $table->unsignedBigInteger('concepto_cobro_id');

            $table->date('periodo_referencia');

            $table->string('descripcion', 255)->nullable();

            $table->date('fecha_emision');

            $table->date('fecha_vencimiento');

            $table->decimal('monto_base', 12, 2);

            $table->decimal('monto_descuento', 12, 2)
                ->default(0);

            $table->decimal('monto_exigible', 12, 2);

            $table->enum('estado_administrativo', [
                'VIGENTE',
                'ANULADA'
            ])->default('VIGENTE');

            $table->unsignedBigInteger('anulada_por')->nullable();

            $table->dateTime('anulada_at')->nullable();

            $table->text('motivo_anulacion')->nullable();

            $table->timestamps();

            $table->unique(
                [
                    'estudiante_id',
                    'ciclo_escolar_id',
                    'concepto_cobro_id',
                    'periodo_referencia'
                ],
                'uq_obligacion_periodo'
            );

            $table->foreign(
                'estudiante_id',
                'fk_cuentas_por_cobrar_estudiante_id'
            )
                ->references('id')
                ->on('estudiantes');

            $table->foreign(
                'ciclo_escolar_id',
                'fk_cuentas_por_cobrar_ciclo_escolar_id'
            )
                ->references('id')
                ->on('ciclos_escolares');

            $table->foreign(
                'concepto_cobro_id',
                'fk_cuentas_por_cobrar_concepto_cobro_id'
            )
                ->references('id')
                ->on('conceptos_cobro');

            $table->foreign(
                'anulada_por',
                'fk_cuentas_por_cobrar_anulada_por'
            )
                ->references('id')
                ->on('usuarios');
        });

        DB::statement("
            ALTER TABLE cuentas_por_cobrar
            ADD CONSTRAINT chk_cxc_montos
            CHECK (
                monto_base >= 0
                AND monto_descuento >= 0
                AND monto_exigible >= 0
            )
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('cuentas_por_cobrar');
    }
};
