
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarifas_estudiantes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('estudiante_id');

            $table->unsignedBigInteger('ciclo_escolar_id');

            $table->unsignedBigInteger('concepto_cobro_id');

            $table->decimal('monto_especial', 12, 2);

            $table->date('periodo_inicio');

            $table->date('periodo_fin');

            $table->text('motivo');

            $table->unsignedBigInteger('autorizado_por');

            $table->boolean('permite_combinacion_descuento')
                ->default(false);

            $table->enum('estado', [
                'ACTIVA',
                'REVOCADA',
                'VENCIDA'
            ])->default('ACTIVA');

            $table->timestamps();

            $table->index(
                [
                    'estudiante_id',
                    'ciclo_escolar_id',
                    'periodo_inicio',
                    'periodo_fin'
                ],
                'idx_tarifa_especial'
            );

            $table->foreign(
                'estudiante_id',
                'fk_tarifas_estudiantes_estudiante_id'
            )
                ->references('id')
                ->on('estudiantes');

            $table->foreign(
                'ciclo_escolar_id',
                'fk_tarifas_estudiantes_ciclo_escolar_id'
            )
                ->references('id')
                ->on('ciclos_escolares');

            $table->foreign(
                'concepto_cobro_id',
                'fk_tarifas_estudiantes_concepto_cobro_id'
            )
                ->references('id')
                ->on('conceptos_cobro');

            $table->foreign(
                'autorizado_por',
                'fk_tarifas_estudiantes_autorizado_por'
            )
                ->references('id')
                ->on('usuarios');
        });

        DB::statement("
            ALTER TABLE tarifas_estudiantes
            ADD CONSTRAINT chk_tarifa_especial_monto
            CHECK (monto_especial >= 0)
        ");

        DB::statement("
            ALTER TABLE tarifas_estudiantes
            ADD CONSTRAINT chk_tarifa_especial_fechas
            CHECK (periodo_fin >= periodo_inicio)
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('tarifas_estudiantes');
    }
};
