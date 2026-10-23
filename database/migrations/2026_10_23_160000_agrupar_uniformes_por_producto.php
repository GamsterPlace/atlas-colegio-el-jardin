<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('uniformes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_uniforme_id')->constrained('categorias_uniformes');
            $table->string('nombre', 180);
            $table->string('color', 80)->nullable();
            $table->text('descripcion')->nullable();
            $table->string('foto_path')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
        Schema::table('productos_uniformes', function (Blueprint $table) {
            $table->foreignId('uniforme_id')->nullable()->after('id')->constrained('uniformes');
        });
        // Conservar los registros existentes y sus identificadores: pedidos,
        // entregas, movimientos y existencias continúan referenciando la talla.
        $grupos = [];
        foreach (DB::table('productos_uniformes')->orderBy('id')->get() as $variante) {
            $clave = implode('|', [
                $variante->categoria_uniforme_id,
                mb_strtolower(trim($variante->nombre)),
                mb_strtolower(trim((string) $variante->color)),
            ]);
            if (!isset($grupos[$clave])) {
                $grupos[$clave] = DB::table('uniformes')->insertGetId([
                    'categoria_uniforme_id' => $variante->categoria_uniforme_id,
                    'nombre' => $variante->nombre,
                    'color' => $variante->color,
                    'descripcion' => $variante->descripcion ?? null,
                    'foto_path' => $variante->foto_path ?? null,
                    'activo' => (bool) $variante->activo,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }
            DB::table('productos_uniformes')->where('id', $variante->id)
                ->update(['uniforme_id' => $grupos[$clave]]);
        }
    }

    public function down(): void
    {
        Schema::table('productos_uniformes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('uniforme_id');
        });
        Schema::dropIfExists('uniformes');
    }
};
