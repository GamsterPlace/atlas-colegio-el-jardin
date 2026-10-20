<?php

namespace Tests\Feature;

use App\Services\Horarios\GeneradorHorariosCicloEscolar;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class GeneradorHorariosCicloEscolarIntegracionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:',
            'database.connections.sqlite.foreign_key_constraints' => false]);
        DB::purge('sqlite');
        DB::setDefaultConnection('sqlite');

        Schema::create('ciclos_escolares', function (Blueprint $t) {
            $t->id(); $t->string('estado');
        });
        Schema::create('secciones', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('ciclo_escolar_id');
            $t->unsignedBigInteger('jornada_id'); $t->boolean('activo');
        });
        Schema::create('bloques_horarios', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('jornada_id'); $t->string('tipo');
            $t->time('hora_inicio'); $t->time('hora_fin');
        });
        Schema::create('pensum_cursos', function (Blueprint $t) {
            $t->id(); $t->unsignedInteger('horas_semanales');
        });
        Schema::create('cargas_academicas', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('seccion_id');
            $t->unsignedBigInteger('pensum_curso_id'); $t->boolean('activo');
        });
        Schema::create('asignaciones_docentes', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('carga_academica_id');
            $t->unsignedBigInteger('docente_id'); $t->boolean('activo');
            $t->date('fecha_inicio'); $t->date('fecha_fin')->nullable();
        });
        Schema::create('disponibilidad_docentes', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('ciclo_escolar_id');
            $t->unsignedBigInteger('docente_id'); $t->unsignedTinyInteger('dia_semana');
            $t->time('hora_inicio'); $t->time('hora_fin'); $t->boolean('disponible');
        });
        Schema::create('restricciones_horarios', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('ciclo_escolar_id');
            $t->unsignedBigInteger('seccion_id')->nullable();
            $t->unsignedBigInteger('docente_id')->nullable(); $t->string('tipo');
            $t->text('parametros'); $t->boolean('obligatoria');
        });
        Schema::create('horarios', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('seccion_id'); $t->string('estado');
        });
        Schema::create('horario_detalles', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('horario_id');
            $t->unsignedBigInteger('docente_id'); $t->unsignedTinyInteger('dia_semana');
            $t->unsignedBigInteger('bloque_horario_id');
        });

        DB::table('ciclos_escolares')->insert(['id' => 10, 'estado' => 'ACTIVO']);
        DB::table('secciones')->insert([
            ['id' => 1, 'ciclo_escolar_id' => 10, 'jornada_id' => 1, 'activo' => true],
            ['id' => 2, 'ciclo_escolar_id' => 10, 'jornada_id' => 1, 'activo' => true],
            ['id' => 3, 'ciclo_escolar_id' => 10, 'jornada_id' => 1, 'activo' => false],
        ]);
        DB::table('bloques_horarios')->insert([
            ['id' => 11, 'jornada_id' => 1, 'tipo' => 'CLASE', 'hora_inicio' => '08:00', 'hora_fin' => '09:00'],
        ]);
        DB::table('pensum_cursos')->insert(['id' => 21, 'horas_semanales' => 1]);
        DB::table('cargas_academicas')->insert([
            ['id' => 31, 'seccion_id' => 1, 'pensum_curso_id' => 21, 'activo' => true],
            ['id' => 32, 'seccion_id' => 2, 'pensum_curso_id' => 21, 'activo' => true],
        ]);
        DB::table('asignaciones_docentes')->insert([
            ['id' => 41, 'carga_academica_id' => 31, 'docente_id' => 51, 'activo' => true, 'fecha_inicio' => '2027-01-01', 'fecha_fin' => null],
            ['id' => 42, 'carga_academica_id' => 32, 'docente_id' => 51, 'activo' => true, 'fecha_inicio' => '2027-01-01', 'fecha_fin' => null],
        ]);
    }

    private function disponibilidad(int $dia): void
    {
        DB::table('disponibilidad_docentes')->insert([
            'ciclo_escolar_id' => 10, 'docente_id' => 51, 'dia_semana' => $dia,
            'hora_inicio' => '08:00', 'hora_fin' => '09:00', 'disponible' => true,
        ]);
    }

    public function test_genera_dos_secciones_sin_choques_y_sin_persistir(): void
    {
        $this->disponibilidad(1);
        $this->disponibilidad(2);

        $resultado = app(GeneradorHorariosCicloEscolar::class)->generar(10, '2027-02-01', 2000);

        $this->assertTrue($resultado['exito'], implode(' | ', $resultado['errores']));
        $this->assertSame([1, 2], $resultado['secciones']);
        $this->assertCount(2, $resultado['horarios']);
        $primero = $resultado['horarios'][1][0];
        $segundo = $resultado['horarios'][2][0];
        $this->assertSame(51, $primero['docente_id']);
        $this->assertSame(51, $segundo['docente_id']);
        $this->assertNotSame($primero['dia_semana'], $segundo['dia_semana']);
        $this->assertSame(0, DB::table('horarios')->count());
        $this->assertSame(0, DB::table('horario_detalles')->count());
    }

    public function test_devuelve_cero_horarios_si_una_seccion_no_puede_generarse(): void
    {
        $this->disponibilidad(1);

        $resultado = app(GeneradorHorariosCicloEscolar::class)->generar(10, '2027-02-01', 2000);

        $this->assertFalse($resultado['exito']);
        $this->assertSame([], $resultado['horarios']);
        $this->assertNotEmpty($resultado['errores']);
        $this->assertSame(0, DB::table('horarios')->count());
    }
}
