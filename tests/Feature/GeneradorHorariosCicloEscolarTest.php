<?php

namespace Tests\Feature;

use App\Services\Horarios\GeneradorHorariosCicloEscolar;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use InvalidArgumentException;
use Tests\TestCase;

class GeneradorHorariosCicloEscolarTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        DB::purge('sqlite');

        Schema::create('ciclos_escolares', function (Blueprint $table) {
            $table->id();
            $table->string('estado');
        });
        Schema::create('secciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ciclo_escolar_id');
            $table->boolean('activo');
        });
    }

    public function test_rechaza_ciclo_cerrado_sin_generar(): void
    {
        DB::table('ciclos_escolares')->insert(['id' => 1, 'estado' => 'CERRADO']);

        $resultado = app(GeneradorHorariosCicloEscolar::class)->generar(1);

        $this->assertFalse($resultado['exito']);
        $this->assertSame([], $resultado['horarios']);
        $this->assertSame(0, $resultado['intentos']);
        $this->assertCount(1, $resultado['errores']);
    }

    public function test_rechaza_ciclo_sin_secciones_activas(): void
    {
        DB::table('ciclos_escolares')->insert(['id' => 2, 'estado' => 'ACTIVO']);
        DB::table('secciones')->insert(['id' => 7, 'ciclo_escolar_id' => 2, 'activo' => false]);

        $resultado = app(GeneradorHorariosCicloEscolar::class)->generar(2);

        $this->assertFalse($resultado['exito']);
        $this->assertSame([], $resultado['secciones']);
        $this->assertSame([], $resultado['horarios']);
    }

    public function test_rechaza_fecha_invalida(): void
    {
        $this->expectException(InvalidArgumentException::class);
        app(GeneradorHorariosCicloEscolar::class)->generar(1, '18/10/2026');
    }

    public function test_rechaza_identificador_no_positivo(): void
    {
        $this->expectException(InvalidArgumentException::class);
        app(GeneradorHorariosCicloEscolar::class)->generar(0);
    }
}
