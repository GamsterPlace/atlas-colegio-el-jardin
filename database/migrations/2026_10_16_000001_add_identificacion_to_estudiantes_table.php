<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('estudiantes', function (Blueprint $table): void {
            $table->string('cui', 13)->nullable()->unique('uq_estudiantes_cui');
            $table->string('lugar_nacimiento', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('estudiantes', function (Blueprint $table): void {
            $table->dropUnique('uq_estudiantes_cui');
            $table->dropColumn(['cui', 'lugar_nacimiento']);
        });
    }
};
