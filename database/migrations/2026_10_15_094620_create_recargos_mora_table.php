
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recargos_mora', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('cuenta_por_cobrar_id');

            $table->unsignedBigInteger('configuracion_mora_id');

            $table->date('fecha_calculo');

            $table->decimal('monto', 12, 2);

            $table->enum('estado', [
                'VIGENTE',
                'ANULADO'
            ])->default('VIGENTE');

            $table->unsignedBigInteger('anulado_por')->nullable();

            $table->text('motivo_anulacion')->nullable();

            $table->timestamps();

            $table->unique(
                [
                    'cuenta_por_cobrar_id',
                    'configuracion_mora_id',
                    'fecha_calculo'
                ],
                'uq_recargo_calculo'
            );

            $table->foreign(
                'cuenta_por_cobrar_id',
                'fk_recargos_mora_cuenta_por_cobrar_id'
            )
                ->references('id')
                ->on('cuentas_por_cobrar');

            $table->foreign(
                'configuracion_mora_id',
                'fk_recargos_mora_configuracion_mora_id'
            )
                ->references('id')
                ->on('configuraciones_mora');

            $table->foreign(
                'anulado_por',
                'fk_recargos_mora_anulado_por'
            )
                ->references('id')
                ->on('usuarios');
        });

        DB::statement("
            ALTER TABLE recargos_mora
            ADD CONSTRAINT chk_recargo_monto
            CHECK (monto >= 0)
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('recargos_mora');
    }
};
