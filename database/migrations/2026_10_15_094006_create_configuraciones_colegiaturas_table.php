
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuraciones_colegiaturas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ciclo_escolar_id');

            $table->unsignedBigInteger('concepto_cobro_id');

            $table->unsignedBigInteger('nivel_id');

            $table->unsignedBigInteger('grado_id')->nullable();

            $table->decimal('monto', 12, 2);

            $table->json('meses_cobro')->nullable();

            $table->unsignedTinyInteger('dia_vencimiento')->nullable();

            $table->date('vigente_desde')->nullable();

            $table->date('vigente_hasta')->nullable();

            $table->timestamps();

            $table->index(
                [
                    'ciclo_escolar_id',
                    'nivel_id',
                    'grado_id',
                    'concepto_cobro_id'
                ],
                'idx_tarifa_general'
            );

            $table->foreign(
                'ciclo_escolar_id',
                'fk_configuraciones_colegiaturas_ciclo_escolar_id'
            )
                ->references('id')
                ->on('ciclos_escolares');

            $table->foreign(
                'concepto_cobro_id',
                'fk_configuraciones_colegiaturas_concepto_cobro_id'
            )
                ->references('id')
                ->on('conceptos_cobro');

            $table->foreign(
                'nivel_id',
                'fk_configuraciones_colegiaturas_nivel_id'
            )
                ->references('id')
                ->on('niveles');

            $table->foreign(
                'grado_id',
                'fk_configuraciones_colegiaturas_grado_id'
            )
                ->references('id')
                ->on('grados');
        });

        DB::statement("
            ALTER TABLE configuraciones_colegiaturas
            ADD CONSTRAINT chk_tarifa_general_monto
            CHECK (monto >= 0)
        ");

        DB::statement("
            ALTER TABLE configuraciones_colegiaturas
            ADD CONSTRAINT chk_tarifa_dia
            CHECK (
                dia_vencimiento IS NULL
                OR dia_vencimiento BETWEEN 1 AND 31
            )
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('configuraciones_colegiaturas');
    }
};
