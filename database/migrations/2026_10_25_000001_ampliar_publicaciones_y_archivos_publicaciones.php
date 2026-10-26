<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('publicaciones', function (Blueprint $table): void {
            $table->string('categoria', 30)->default('Institucional')->after('contenido');
            $table->boolean('destacada')->default(false)->after('categoria');
        });

        Schema::table('archivos_publicaciones', function (Blueprint $table): void {
            $table->enum('tipo', ['FOTOGRAFIA', 'VIDEO'])->nullable()->after('nombre_original');
        });
    }

    public function down(): void
    {
        Schema::table('archivos_publicaciones', function (Blueprint $table): void {
            $table->dropColumn('tipo');
        });

        Schema::table('publicaciones', function (Blueprint $table): void {
            $table->dropColumn(['categoria', 'destacada']);
        });
    }
};
