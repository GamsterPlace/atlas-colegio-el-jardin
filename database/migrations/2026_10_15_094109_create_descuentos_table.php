
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('descuentos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('estudiante_id');

            $table->unsignedBigInteger('ciclo_escolar_id');

            $table->unsignedBigInteger('concepto_cobro_id');

            $table->enum('tipo', [
                'FIJO',
                'PORCENTAJE'
            ]);

            $table->decimal('valor', 12, 2);

            $table->date('periodo_inicio');

            $table->date('periodo_fin');

            $table->text('motivo');

            $table->unsignedBigInteger('autorizado_por');

            $table->boolean('permite_combinacion_tarifa')
                ->default(false);

            $table->enum('estado', [
                'ACTIVO',
                'REVOCADO',
                'VENCIDO'
            ])->default('ACTIVO');

            $table->timestamps();

            $table->foreign(
                'estudiante_id',
                'fk_descuentos_estudiante_id'
            )
                ->references('id')
                ->on('estudiantes');

            $table->foreign(
                'ciclo_escolar_id',
                'fk_descuentos_ciclo_escolar_id'
            )
                ->references('id')
                ->on('ciclos_escolares');

            $table->foreign(
                'concepto_cobro_id',
                'fk_descuentos_concepto_cobro_id'
            )
                ->references('id')
                ->on('conceptos_cobro');

            $table->foreign(
                'autorizado_por',
                'fk_descuentos_autorizado_por'
            )
                ->references('id')
                ->on('usuarios');
        });

        DB::statement("
            ALTER TABLE descuentos
            ADD CONSTRAINT chk_descuento_valor
            CHECK (
                valor >= 0
                AND (
                    tipo <> 'PORCENTAJE'
                    OR valor <= 100
                )
            )
        ");

        DB::statement("
            ALTER TABLE descuentos
            ADD CONSTRAINT chk_descuento_fechas
            CHECK (periodo_fin >= periodo_inicio)
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('descuentos');
    }
};
