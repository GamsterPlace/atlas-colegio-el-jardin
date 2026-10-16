
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historial_cambios_notas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('calificacion_id')->nullable();

            $table->unsignedBigInteger('nota_periodo_id')->nullable();

            $table->unsignedBigInteger('nota_final_id')->nullable();

            $table->decimal('valor_anterior', 8, 2)->nullable();

            $table->decimal('valor_nuevo', 8, 2);

            $table->text('motivo');

            $table->unsignedBigInteger('usuario_id');

            $table->dateTime('fecha_cambio')->useCurrent();

            $table->timestamps();

            $table->foreign(
                'calificacion_id',
                'fk_historial_cambios_notas_calificacion_id'
            )
                ->references('id')
                ->on('calificaciones');

            $table->foreign(
                'nota_periodo_id',
                'fk_historial_cambios_notas_nota_periodo_id'
            )
                ->references('id')
                ->on('notas_periodo');

            $table->foreign(
                'nota_final_id',
                'fk_historial_cambios_notas_nota_final_id'
            )
                ->references('id')
                ->on('notas_finales');

            $table->foreign(
                'usuario_id',
                'fk_historial_cambios_notas_usuario_id'
            )
                ->references('id')
                ->on('usuarios');
        });

        DB::statement("
            ALTER TABLE historial_cambios_notas
            ADD CONSTRAINT chk_cambio_nota_origen
            CHECK (
                (calificacion_id IS NOT NULL) +
                (nota_periodo_id IS NOT NULL) +
                (nota_final_id IS NOT NULL) = 1
            )
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_cambios_notas');
    }
};
