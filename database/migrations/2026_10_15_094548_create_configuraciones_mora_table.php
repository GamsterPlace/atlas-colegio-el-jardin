
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuraciones_mora', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ciclo_escolar_id');

            $table->unsignedBigInteger('concepto_cobro_id');

            $table->enum('tipo', [
                'FIJO',
                'PORCENTAJE'
            ]);

            $table->decimal('valor', 12, 2);

            $table->unsignedSmallInteger('dias_gracia')
                ->default(0);

            $table->enum('periodicidad', [
                'UNICA',
                'DIARIA',
                'MENSUAL'
            ])->default('UNICA');

            $table->date('vigente_desde');

            $table->date('vigente_hasta')->nullable();

            $table->unsignedBigInteger('aprobado_por');

            $table->timestamps();

            $table->foreign(
                'ciclo_escolar_id',
                'fk_configuraciones_mora_ciclo_escolar_id'
            )
                ->references('id')
                ->on('ciclos_escolares');

            $table->foreign(
                'concepto_cobro_id',
                'fk_configuraciones_mora_concepto_cobro_id'
            )
                ->references('id')
                ->on('conceptos_cobro');

            $table->foreign(
                'aprobado_por',
                'fk_configuraciones_mora_aprobado_por'
            )
                ->references('id')
                ->on('usuarios');
        });

        DB::statement("
            ALTER TABLE configuraciones_mora
            ADD CONSTRAINT chk_mora_valor
            CHECK (valor >= 0)
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('configuraciones_mora');
    }
};
