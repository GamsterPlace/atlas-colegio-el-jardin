<?php

namespace Tests\Unit;

use App\Services\Horarios\ValidadorConflictosHorario;
use PHPUnit\Framework\TestCase;

class ValidadorConflictosHorarioTest extends TestCase
{
    private function base(): array
    {
        return [
            'bloques' => [
                ['id' => 10, 'jornada_id' => 1, 'tipo' => 'CLASE', 'hora_inicio' => '07:00', 'hora_fin' => '07:40'],
                ['id' => 11, 'jornada_id' => 1, 'tipo' => 'CLASE', 'hora_inicio' => '07:40', 'hora_fin' => '08:20'],
            ],
            'secciones' => [['id' => 2, 'jornada_id' => 1]],
            'cargas' => [['id' => 3, 'seccion_id' => 2, 'horas_semanales' => 1, 'activo' => true]],
            'asignaciones' => [['carga_academica_id' => 3, 'docente_id' => 4, 'activo' => true]],
            'disponibilidades' => [['docente_id' => 4, 'dia_semana' => 1, 'hora_inicio' => '07:00', 'hora_fin' => '08:20', 'disponible' => true]],
            'restricciones' => [],
            'detalles' => [['seccion_id' => 2, 'dia_semana' => 1, 'bloque_horario_id' => 10, 'carga_academica_id' => 3, 'docente_id' => 4]],
            'ocupaciones' => [],
        ];
    }

    public function test_acepta_una_asignacion_valida(): void
    {
        $this->assertTrue((new ValidadorConflictosHorario())->validar($this->base())['valido']);
    }

    public function test_rechaza_docente_sin_disponibilidad_explicita(): void
    {
        $datos = $this->base();
        $datos['disponibilidades'] = [];
        $resultado = (new ValidadorConflictosHorario())->validar($datos);
        $this->assertFalse($resultado['valido']);
        $this->assertStringContainsString('disponibilidad explícita', implode(' ', $resultado['errores']));
    }

    public function test_rechaza_bloqueo_explicito_aunque_exista_disponibilidad(): void
    {
        $datos = $this->base();
        $datos['disponibilidades'][] = ['docente_id' => 4, 'dia_semana' => 1, 'hora_inicio' => '07:20', 'hora_fin' => '07:30', 'disponible' => false];
        $this->assertFalse((new ValidadorConflictosHorario())->validar($datos)['valido']);
    }

    public function test_detecta_colision_con_horario_existente(): void
    {
        $datos = $this->base();
        $datos['ocupaciones'][] = ['docente_id' => 4, 'dia_semana' => 1, 'bloque_horario_id' => 10];
        $resultado = (new ValidadorConflictosHorario())->validar($datos);
        $this->assertStringContainsString('horario existente', implode(' ', $resultado['errores']));
    }

    public function test_detecta_faltante_semanal(): void
    {
        $datos = $this->base();
        $datos['cargas'][0]['horas_semanales'] = 2;
        $this->assertStringContainsString('se programaron 1 períodos de 2', implode(' ', (new ValidadorConflictosHorario())->validar($datos)['errores']));
    }

    public function test_detecta_bloqueo_obligatorio(): void
    {
        $datos = $this->base();
        $datos['restricciones'][] = ['tipo' => 'BLOQUEO_PERIODO', 'docente_id' => 4, 'seccion_id' => null, 'obligatoria' => true, 'parametros' => ['dia_semana' => 1, 'bloque_horario_id' => 10]];
        $this->assertStringContainsString('bloqueo obligatorio', implode(' ', (new ValidadorConflictosHorario())->validar($datos)['errores']));
    }
}
