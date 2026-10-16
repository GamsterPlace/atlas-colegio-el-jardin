
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estudiante_responsables', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('estudiante_id');

            $table->unsignedBigInteger('responsable_id');

            $table->string('parentesco', 80);

            $table->boolean('es_responsable_financiero')
                ->default(false);

            $table->boolean('es_contacto_principal')
                ->default(false);

            $table->boolean('autorizado_recoger')
                ->default(false);

            $table->date('fecha_inicio');

            $table->date('fecha_fin')->nullable();

            $table->timestamps();

            $table->unique(
                [
                    'estudiante_id',
                    'responsable_id',
                    'fecha_inicio'
                ],
                'uq_vinculo_inicio'
            );

            $table->foreign('estudiante_id')
                ->references('id')
                ->on('estudiantes');

            $table->foreign('responsable_id')
                ->references('id')
                ->on('responsables');
        });

        DB::statement("
            ALTER TABLE estudiante_responsables
            ADD CONSTRAINT chk_vinculo_fechas
            CHECK (
                fecha_fin IS NULL
                OR fecha_fin >= fecha_inicio
            )
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('estudiante_responsables');
    }
};
