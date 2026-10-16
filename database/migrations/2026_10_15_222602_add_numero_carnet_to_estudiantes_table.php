
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            $table->string('numero_carnet', 14)
                ->nullable()
                ->unique('uq_estudiantes_numero_carnet')
                ->after('codigo');
        });
    }

    public function down(): void
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            $table->dropUnique('uq_estudiantes_numero_carnet');
            $table->dropColumn('numero_carnet');
        });
    }
};
