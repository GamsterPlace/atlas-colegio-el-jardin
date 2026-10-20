<?php

namespace Tests\Feature;

use App\Services\Horarios\PreparadorDatosHorario;
use App\Services\Horarios\GeneradorAutomaticoHorario;
use App\Services\Horarios\ValidadorConflictosHorario;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class GeneradorHorarioIntegracionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Base SQLite aislada: no utiliza ni modifica los datos de desarrollo.
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:',
            'database.connections.sqlite.foreign_key_constraints' => false]);
        DB::purge('sqlite');
        DB::setDefaultConnection('sqlite');

        Schema::create('secciones', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('ciclo_escolar_id'); $t->unsignedBigInteger('jornada_id');
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
            $t->id(); $t->unsignedBigInteger('carga_academica_id'); $t->unsignedBigInteger('docente_id');
            $t->boolean('activo'); $t->date('fecha_inicio'); $t->date('fecha_fin')->nullable();
        });
        Schema::create('disponibilidad_docentes', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('ciclo_escolar_id'); $t->unsignedBigInteger('docente_id');
            $t->unsignedTinyInteger('dia_semana'); $t->time('hora_inicio');
            $t->time('hora_fin'); $t->boolean('disponible');
        });
        Schema::create('restricciones_horarios', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('ciclo_escolar_id');
            $t->unsignedBigInteger('seccion_id')->nullable(); $t->unsignedBigInteger('docente_id')->nullable();
            $t->string('tipo'); $t->text('parametros'); $t->boolean('obligatoria');
        });
        Schema::create('horarios', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('seccion_id'); $t->string('estado');
        });
        Schema::create('horario_detalles', function (Blueprint $t) {
            $t->id(); $t->unsignedBigInteger('horario_id'); $t->unsignedBigInteger('docente_id');
            $t->unsignedTinyInteger('dia_semana'); $t->unsignedBigInteger('bloque_horario_id');
        });

        DB::table('secciones')->insert([
            ['id' => 1, 'ciclo_escolar_id' => 10, 'jornada_id' => 1],
            ['id' => 2, 'ciclo_escolar_id' => 10, 'jornada_id' => 2],
            ['id' => 3, 'ciclo_escolar_id' => 20, 'jornada_id' => 1],
        ]);
        DB::table('bloques_horarios')->insert([
            ['id' => 11, 'jornada_id' => 1, 'tipo' => 'CLASE', 'hora_inicio' => '08:00', 'hora_fin' => '09:00'],
            ['id' => 12, 'jornada_id' => 2, 'tipo' => 'CLASE', 'hora_inicio' => '08:30', 'hora_fin' => '09:30'],
        ]);
        DB::table('pensum_cursos')->insert(['id' => 21, 'horas_semanales' => 1]);
        DB::table('cargas_academicas')->insert(['id' => 31, 'seccion_id' => 1, 'pensum_curso_id' => 21, 'activo' => true]);
        DB::table('asignaciones_docentes')->insert(['id' => 41, 'carga_academica_id' => 31,
            'docente_id' => 51, 'activo' => true, 'fecha_inicio' => '2027-01-01', 'fecha_fin' => null]);
    }

    private function generar(): array
    {
        // Se prepara el contexto SIN detalles porque el generador los construye.
        $datos = app(PreparadorDatosHorario::class)->preparar(1, [], '2027-02-01');
        return app(GeneradorAutomaticoHorario::class)->generar($datos, 1, 2000);
    }

    private function disponibilidad(int $dia = 1): void
    {
        DB::table('disponibilidad_docentes')->insert([
            'ciclo_escolar_id' => 10, 'docente_id' => 51,
            'dia_semana' => $dia, 'hora_inicio' => '08:00',
            'hora_fin' => '09:00', 'disponible' => true,
        ]);
    }

    public function test_genera_propuesta_con_datos_de_la_base_y_la_valida(): void
    {
        $this->disponibilidad();
        $resultado = $this->generar();
        $this->assertTrue($resultado['exito'], implode(' | ', $resultado['errores']));
        $this->assertCount(1, $resultado['detalles']);
        $this->assertSame(31, $resultado['detalles'][0]['carga_academica_id']);
        $this->assertSame(51, $resultado['detalles'][0]['docente_id']);
        $this->assertSame(1, $resultado['detalles'][0]['dia_semana']);
        $this->assertSame(11, $resultado['detalles'][0]['bloque_horario_id']);
        $this->assertSame(0, DB::table('horarios')->count());
        $this->assertSame(0, DB::table('horario_detalles')->count());
    }

    public function test_no_inventa_disponibilidad(): void
    {
        $resultado = $this->generar();
        $this->assertFalse($resultado['exito']);
        $this->assertSame([], $resultado['detalles']);
    }

    public function test_no_utiliza_docente_con_asignacion_vencida(): void
    {
        DB::table('asignaciones_docentes')->where('id', 41)->update(['fecha_fin' => '2027-01-31']);
        $this->disponibilidad();
        $resultado = $this->generar();
        $this->assertFalse($resultado['exito']);
        $this->assertStringContainsString('docente asignado', implode(' ', $resultado['errores']));
    }

    public function test_no_ocupa_periodo_ya_aprobado_para_docente_en_otra_jornada(): void
    {
        $this->disponibilidad();
        DB::table('horarios')->insert(['id' => 61, 'seccion_id' => 2, 'estado' => 'APROBADO']);
        DB::table('horario_detalles')->insert([
            'horario_id' => 61, 'docente_id' => 51,
            'dia_semana' => 1, 'bloque_horario_id' => 12,
        ]);
        $resultado = $this->generar();
        $this->assertFalse($resultado['exito']);
        $this->assertSame([], $resultado['detalles']);
    }

    public function test_aplica_restriccion_obligatoria_de_la_base(): void
    {
        $this->disponibilidad();
        DB::table('restricciones_horarios')->insert([
            'ciclo_escolar_id' => 10, 'seccion_id' => 1,
            'docente_id' => null, 'tipo' => 'BLOQUEO_PERIODO',
            'parametros' => json_encode(['dia_semana' => 1, 'bloque_horario_id' => 11]),
            'obligatoria' => true,
        ]);
        $resultado = $this->generar();
        $this->assertFalse($resultado['exito']);
        $this->assertSame([], $resultado['detalles']);
    }
}
