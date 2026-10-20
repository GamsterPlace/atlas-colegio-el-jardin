<?php

namespace Tests\Unit;

use App\Services\Horarios\GeneradorAutomaticoHorario;
use App\Services\Horarios\GeneradorCoordinadoHorarios;
use App\Services\Horarios\ValidadorConflictosHorario;
use PHPUnit\Framework\TestCase;

class GeneradorCoordinadoHorariosTest extends TestCase
{
    private function contexto(int $seccion, int $carga, int $docente, int $jornada = 1, int $bloque = 10): array
    {
        return [
            'secciones' => [['id' => $seccion, 'jornada_id' => $jornada]],
            'bloques' => [['id' => $bloque, 'jornada_id' => $jornada, 'tipo' => 'CLASE', 'hora_inicio' => '08:00:00', 'hora_fin' => '09:00:00']],
            'cargas' => [['id' => $carga, 'seccion_id' => $seccion, 'horas_semanales' => 1, 'activo' => true]],
            'asignaciones' => [['carga_academica_id' => $carga, 'docente_id' => $docente, 'activo' => true]],
            'disponibilidades' => [['docente_id' => $docente, 'dia_semana' => 1, 'hora_inicio' => '08:00:00', 'hora_fin' => '09:00:00', 'disponible' => true]],
            'restricciones' => [], 'ocupaciones' => [], 'detalles' => [],
        ];
    }

    private function servicio(): GeneradorCoordinadoHorarios
    {
        $v = new ValidadorConflictosHorario();
        return new GeneradorCoordinadoHorarios(new GeneradorAutomaticoHorario($v), $v);
    }

    public function test_genera_secciones_con_docentes_diferentes(): void
    {
        $r = $this->servicio()->generar([1 => $this->contexto(1, 21, 31), 2 => $this->contexto(2, 22, 32)]);
        $this->assertTrue($r['exito'], implode('; ', $r['errores']));
        $this->assertCount(2, $r['horarios']);
    }

    public function test_impide_choque_del_mismo_docente_entre_secciones(): void
    {
        $r = $this->servicio()->generar([1 => $this->contexto(1, 21, 31), 2 => $this->contexto(2, 22, 31)]);
        $this->assertFalse($r['exito']);
        $this->assertSame([], $r['horarios']);
    }

    public function test_detecta_colision_entre_jornadas_con_bloques_diferentes(): void
    {
        $r = $this->servicio()->generar([1 => $this->contexto(1, 21, 31, 1, 10), 2 => $this->contexto(2, 22, 31, 2, 20)]);
        $this->assertFalse($r['exito']);
        $this->assertSame([], $r['horarios']);
    }

    public function test_no_inventa_disponibilidad(): void
    {
        $segundo = $this->contexto(2, 22, 32);
        $segundo['disponibilidades'] = [];
        $r = $this->servicio()->generar([1 => $this->contexto(1, 21, 31), 2 => $segundo]);
        $this->assertFalse($r['exito']);
        $this->assertSame([], $r['horarios']);
    }

    public function test_rechaza_solicitud_vacia(): void
    {
        $r = $this->servicio()->generar([]);
        $this->assertFalse($r['exito']);
        $this->assertSame([], $r['horarios']);
    }
}
