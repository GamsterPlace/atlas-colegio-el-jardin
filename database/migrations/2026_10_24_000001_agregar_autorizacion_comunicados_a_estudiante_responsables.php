
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('estudiante_responsables', function (Blueprint $table) {
            $table->boolean('autorizado_comunicados')
                ->default(false)
                ->after('es_contacto_principal');
        });
    }

    public function down(): void
    {
        Schema::table('estudiante_responsables', function (Blueprint $table) {
            $table->dropColumn('autorizado_comunicados');
        });
    }
};
