<?php

namespace Tests\Unit;

use App\Services\Horarios\GeneradorAutomaticoHorario;
use App\Services\Horarios\ValidadorConflictosHorario;
use PHPUnit\Framework\TestCase;

class GeneradorAutomaticoHorarioTest extends TestCase
{
    private function datos(): array
    {
        return [
            'secciones' => [['id' => 1, 'jornada_id' => 1]],
            'bloques' => [
                ['id' => 10, 'jornada_id' => 1, 'tipo' => 'CLASE', 'hora_inicio' => '08:00:00', 'hora_fin' => '09:00:00'],
                ['id' => 11, 'jornada_id' => 1, 'tipo' => 'CLASE', 'hora_inicio' => '09:00:00', 'hora_fin' => '10:00:00'],
            ],
            'cargas' => [['id' => 20, 'seccion_id' => 1, 'horas_semanales' => 2, 'activo' => true]],
            'asignaciones' => [['carga_academica_id' => 20, 'docente_id' => 30, 'activo' => true]],
            'disponibilidades' => [['docente_id' => 30, 'dia_semana' => 1, 'hora_inicio' => '08:00:00', 'hora_fin' => '10:00:00', 'disponible' => true]],
            'restricciones' => [],
            'ocupaciones' => [],
            'detalles' => [],
        ];
    }

    private function generador(): GeneradorAutomaticoHorario
    {
        return new GeneradorAutomaticoHorario(new ValidadorConflictosHorario());
    }

    public function test_genera_horario_completo_y_valido(): void
    {
        $datos = $this->datos();
        $resultado = $this->generador()->generar($datos, 1);
        $this->assertTrue($resultado['exito'], implode('; ', $resultado['errores']));
        $this->assertCount(2, $resultado['detalles']);
        $datos['detalles'] = $resultado['detalles'];
        $this->assertTrue((new ValidadorConflictosHorario())->validar($datos)['valido']);
    }

    public function test_no_inventa_disponibilidad_docente(): void
    {
        $datos = $this->datos();
        $datos['disponibilidades'] = [];
        $resultado = $this->generador()->generar($datos, 1);
        $this->assertFalse($resultado['exito']);
        $this->assertSame([], $resultado['detalles']);
    }

    public function test_respeta_bloqueo_obligatorio(): void
    {
        $datos = $this->datos();
        $datos['restricciones'][] = [
            'tipo' => 'BLOQUEO_PERIODO', 'seccion_id' => 1, 'docente_id' => null,
            'parametros' => ['dia_semana' => 1, 'bloque_horario_id' => 10], 'obligatoria' => true,
        ];
        $resultado = $this->generador()->generar($datos, 1);
        $this->assertFalse($resultado['exito']);
    }

    public function test_evade_ocupacion_de_otra_seccion(): void
    {
        $datos = $this->datos();
        $datos['ocupaciones'][] = ['docente_id' => 30, 'dia_semana' => 1, 'bloque_horario_id' => 10];
        $resultado = $this->generador()->generar($datos, 1);
        $this->assertFalse($resultado['exito']);
    }

    public function test_rechaza_carga_sin_docente(): void
    {
        $datos = $this->datos();
        $datos['asignaciones'] = [];
        $resultado = $this->generador()->generar($datos, 1);
        $this->assertFalse($resultado['exito']);
        $this->assertStringContainsString('no tiene docente', $resultado['errores'][0]);
    }
}
