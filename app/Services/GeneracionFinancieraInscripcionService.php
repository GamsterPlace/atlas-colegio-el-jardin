<?php

namespace App\Services;

use App\Models\ConfiguracionColegiatura;
use App\Models\CuentaPorCobrar;
use App\Models\Descuento;
use App\Models\Estudiante;
use App\Models\HojaTalonario;
use App\Models\Inscripcion;
use App\Models\TalonarioPago;
use App\Models\TarifaEstudiante;
use App\Models\CicloEscolar;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class GeneracionFinancieraInscripcionService
{
    public function generar(
        Inscripcion $inscripcion,
        int $usuarioId
    ): TalonarioPago {
        return DB::transaction(function () use ($inscripcion, $usuarioId) {
            $inscripcion->loadMissing([
                'estudiante',
                'ciclo_escolar',
                'seccion',
            ]);
            if ($inscripcion->estado !== 'COMPLETADA') {
                throw ValidationException::withMessages([
                    'inscripcion' => 'La inscripción debe estar completada.',
                ]);
            }
            $estudiante = Estudiante::query()
                ->whereKey($inscripcion->estudiante_id)
                ->lockForUpdate()
                ->firstOrFail();
            $ciclo = $inscripcion->ciclo_escolar;
            $seccion = $inscripcion->seccion;
            $seccion->loadMissing('grado');
            $grado = $seccion->grado;
            if (!$grado) {
                throw ValidationException::withMessages([
                    'seccion_id' => 'La sección no tiene un grado asociado.',
                ]);
            }
            $grado->loadMissing('nivel');
            if (!$grado->nivel) {
                throw ValidationException::withMessages([
                    'seccion_id' => 'El grado no tiene un nivel asociado.',
                ]);
            }
            $configuraciones = ConfiguracionColegiatura::query()
                ->with('concepto_cobro')
                ->where('ciclo_escolar_id', $ciclo->id)
                ->where('nivel_id', $grado->nivel->id)
                ->where(function ($query) use ($grado) {
                    $query->whereNull('grado_id')
                        ->orWhere('grado_id', $grado->id);
                })
                ->get()
                ->filter(fn ($configuracion) =>
                    $configuracion->concepto_cobro?->activo === true
                );
            if ($configuraciones->isEmpty()) {
                throw ValidationException::withMessages([
                    'seccion_id' => 'No existen configuraciones de cobro activas para este grado y ciclo.',
                ]);
            }
            $obligaciones = $this->prepararObligaciones(
                $configuraciones,
                $ciclo,
                $grado->id,
                $estudiante->id
            );
            if (empty($obligaciones)) {
                throw ValidationException::withMessages([
                    'ciclo_escolar_id' => 'No se encontraron períodos de cobro aplicables.',
                ]);
            }
            $talonario = TalonarioPago::query()
                ->where('estudiante_id', $estudiante->id)
                ->where('ciclo_escolar_id', $ciclo->id)
                ->first();
            if (!$talonario) {
                $talonario = TalonarioPago::query()->create([
                    'estudiante_id' => $estudiante->id,
                    'ciclo_escolar_id' => $ciclo->id,
                    'codigo' => sprintf(
                        'ATL-T-%d-%d',
                        $ciclo->id,
                        $estudiante->id
                    ),
                    'fecha_generacion' => now(),
                    'emitido_por' => $usuarioId,
                ]);
            }
            foreach ($obligaciones as $obligacion) {
                $configuracion = $obligacion['configuracion'];
                $periodo = $obligacion['periodo'];
                $cuenta = CuentaPorCobrar::query()
                    ->where('estudiante_id', $estudiante->id)
                    ->where('ciclo_escolar_id', $ciclo->id)
                    ->where('concepto_cobro_id', $configuracion->concepto_cobro_id)
                    ->whereDate('periodo_referencia', $periodo->toDateString())
                    ->first();
                if (!$cuenta) {
                    [$base, $descuento] = $this->calcularImportes(
                        $estudiante->id,
                        $ciclo->id,
                        $configuracion,
                        $periodo
                    );
                    $cuenta = CuentaPorCobrar::query()->create([
                        'estudiante_id' => $estudiante->id,
                        'ciclo_escolar_id' => $ciclo->id,
                        'concepto_cobro_id' => $configuracion->concepto_cobro_id,
                        'periodo_referencia' => $periodo->toDateString(),
                        'descripcion' => $configuracion->concepto_cobro->nombre,
                        'fecha_emision' => now()->toDateString(),
                        'fecha_vencimiento' => $this->fechaVencimiento(
                            $periodo,
                            $configuracion->dia_vencimiento
                        ),
                        'monto_base' => $this->aDecimal($base),
                        'monto_descuento' => $this->aDecimal($descuento),
                        'monto_exigible' => $this->aDecimal($base - $descuento),
                        'estado_administrativo' => 'VIGENTE',
                    ]);
                }
                if ($cuenta->estado_administrativo === 'ANULADA') {
                    continue;
                }
                $existeHoja = HojaTalonario::query()
                    ->where('cuenta_por_cobrar_id', $cuenta->id)
                    ->where('tipo', 'ORDINARIA')
                    ->exists();
                if ($existeHoja) {
                    continue;
                }
                HojaTalonario::query()->create([
                    'talonario_id' => $talonario->id,
                    'cuenta_por_cobrar_id' => $cuenta->id,
                    'autorizacion_detalle_id' => null,
                    'hoja_origen_id' => null,
                    'codigo' => sprintf(
                        'ATL-H-%d-%d',
                        $talonario->id,
                        $cuenta->id
                    ),
                    'tipo' => 'ORDINARIA',
                    'monto_impreso' => $cuenta->monto_exigible,
                    'fecha_vencimiento_impresa' => $cuenta->fecha_vencimiento,
                    'nombre_estudiante_impreso' => trim(
                        $estudiante->nombres . ' ' . $estudiante->apellidos
                    ),
                    'concepto_impreso' => $cuenta->descripcion
                        ?: $configuracion->concepto_cobro->nombre,
                    'periodo_impreso' => $periodo->locale('es')
                        ->translatedFormat('F Y'),
                    'estado' => 'VIGENTE',
                    'fecha_emision' => now(),
                ]);
            }
            return $talonario->load('registrosHojaTalonario');
        }, 3);
    }
    private function prepararObligaciones(
        Collection $configuraciones,
        CicloEscolar $ciclo,
        int $gradoId,
        int $estudianteId
    ): array {
        $obligaciones = [];
        $inicioCiclo = Carbon::parse($ciclo->fecha_inicio)
            ->startOfMonth();
        $finCiclo = Carbon::parse($ciclo->fecha_fin)
            ->startOfMonth();
        foreach ($configuraciones as $configuracion) {
            $tipo = $configuracion->concepto_cobro?->tipo;
            if ($tipo === 'INSCRIPCION') {
               // La inscripción es un único cobro, sin meses recurrentes.
               $periodos = [$inicioCiclo->copy()];
           } else {
                $meses = $configuracion->meses_cobro;

                if (is_string($meses)) {
                    $meses = json_decode($meses, true);
                }

                if (!is_array($meses) || empty($meses)) {
                    throw ValidationException::withMessages([
                        'ciclo_escolar_id' => 'La configuración de cobro '
                            . $configuracion->id . ' no tiene meses definidos.',
                    ]);
                }

                $periodos = [];
                foreach ($meses as $mes) {
                    if (filter_var($mes, FILTER_VALIDATE_INT, [
                        'options' => ['min_range' => 1, 'max_range' => 12],
                    ]) === false) {
                        throw ValidationException::withMessages([
                            'ciclo_escolar_id' => 'Existe un mes de cobro inválido en la configuración '
                                . $configuracion->id . '.',
                        ]);
                    }

                    $periodo = $inicioCiclo->copy()->month((int) $mes);
                    if ($periodo->lt($inicioCiclo)) {
                        $periodo->addYear();
                    }
                    if ($periodo->lte($finCiclo)) {
                        $periodos[] = $periodo;
                    }
                }
            }

            foreach ($periodos as $periodo) {
                $inicioPeriodo = $periodo->copy()->startOfMonth();
                $finPeriodo = $periodo->copy()->endOfMonth();

                if (

                    $configuracion->vigente_desde &&

                    $finPeriodo->lt($configuracion->vigente_desde)

                ) {

                    continue;

                }

                if (

                    $configuracion->vigente_hasta &&

                    $inicioPeriodo->gt($configuracion->vigente_hasta)

                ) {

                    continue;

                }

                $clave = $configuracion->concepto_cobro_id

                    . ':' . $periodo->format('Y-m');

                $esEspecifica = (int) $configuracion->grado_id === $gradoId;

                if (isset($obligaciones[$clave])) {

                    $actual = $obligaciones[$clave]['configuracion'];

                    $actualEsEspecifica =

                        (int) $actual->grado_id === $gradoId;

                    if ($esEspecifica && !$actualEsEspecifica) {

                        $obligaciones[$clave] = [

                            'configuracion' => $configuracion,

                            'periodo' => $periodo,

                        ];

                        continue;

                    }

                    if ($esEspecifica === $actualEsEspecifica) {

                        throw ValidationException::withMessages([

                            'ciclo_escolar_id' =>

                                'Existen configuraciones de cobro superpuestas '

                                . 'para el mismo concepto y período.',

                        ]);

                    }

                    continue;

                }

                $obligaciones[$clave] = [

                    'configuracion' => $configuracion,

                    'periodo' => $periodo,

                ];

            }

        }

        ksort($obligaciones);

        return array_values($obligaciones);

    }

    private function calcularImportes(

        int $estudianteId,

        int $cicloId,

        ConfiguracionColegiatura $configuracion,

        Carbon $periodo

    ): array {

        $fecha = $periodo->toDateString();

        $tarifas = TarifaEstudiante::query()

            ->where('estudiante_id', $estudianteId)

            ->where('ciclo_escolar_id', $cicloId)

            ->where('concepto_cobro_id', $configuracion->concepto_cobro_id)

            ->where('estado', 'ACTIVA')

            ->whereDate('periodo_inicio', '<=', $fecha)

            ->whereDate('periodo_fin', '>=', $fecha)

            ->get();

        if ($tarifas->count() > 1) {

            throw ValidationException::withMessages([

                'ciclo_escolar_id' =>

                    'Existen tarifas especiales superpuestas para el estudiante.',

            ]);

        }

        $tarifa = $tarifas->first();

        $base = $tarifa

            ? $this->aCentavos($tarifa->monto_especial)

            : $this->aCentavos($configuracion->monto);

        if ($base < 0) {

            throw ValidationException::withMessages([

                'ciclo_escolar_id' => 'El monto configurado no puede ser negativo.',

            ]);

        }

        $descuentos = Descuento::query()

            ->where('estudiante_id', $estudianteId)

            ->where('ciclo_escolar_id', $cicloId)

            ->where('concepto_cobro_id', $configuracion->concepto_cobro_id)

            ->where('estado', 'ACTIVO')

            ->whereDate('periodo_inicio', '<=', $fecha)

            ->whereDate('periodo_fin', '>=', $fecha)

            ->get();

        if ($tarifa && $descuentos->isNotEmpty()) {

            if (!$tarifa->permite_combinacion_descuento) {

                throw ValidationException::withMessages([

                    'ciclo_escolar_id' =>

                        'La tarifa especial no permite combinar descuentos.',

                ]);

            }

            foreach ($descuentos as $descuento) {

                if (!$descuento->permite_combinacion_tarifa) {

                    throw ValidationException::withMessages([

                        'ciclo_escolar_id' =>

                            'Uno de los descuentos no permite combinarse '

                            . 'con una tarifa especial.',

                    ]);

                }

            }

        }

        $totalDescuento = 0;

        foreach ($descuentos as $descuento) {

            if ((float) $descuento->valor < 0) {

                throw ValidationException::withMessages([

                    'ciclo_escolar_id' => 'El descuento no puede ser negativo.',

                ]);

            }

            if ($descuento->tipo === 'PORCENTAJE') {

                $porcentaje = (float) $descuento->valor;

                if ($porcentaje > 100) {

                    throw ValidationException::withMessages([

                        'ciclo_escolar_id' =>

                            'Un descuento porcentual supera el 100%.',

                    ]);

                }

                $importe = (int) round(

                    $base * $porcentaje / 100,

                    0,

                    PHP_ROUND_HALF_UP

                );

            } elseif ($descuento->tipo === 'FIJO') {

                $importe = $this->aCentavos($descuento->valor);

            } else {

                throw ValidationException::withMessages([

                    'ciclo_escolar_id' => 'Existe un tipo de descuento inválido.',

                ]);

            }

            $totalDescuento += $importe;

        }

        return [

            $base,

            min($base, $totalDescuento),

        ];

    }

    private function fechaVencimiento(

        Carbon $periodo,

        ?int $dia

    ): string {

        if ($dia === null || $dia < 1 || $dia > 31) {

            throw ValidationException::withMessages([

                'ciclo_escolar_id' =>

                    'Debe configurarse un día de vencimiento entre 1 y 31.',

            ]);

        }

        $ultimoDia = $periodo->copy()->endOfMonth()->day;

        return $periodo->copy()

            ->day(min($dia, $ultimoDia))

            ->toDateString();

    }

    private function aCentavos(string|int|float $monto): int

    {

        $valor = trim((string) $monto);

        if (!preg_match('/^-?\d+(\.\d{1,2})?$/', $valor)) {

            throw ValidationException::withMessages([

                'ciclo_escolar_id' => 'Existe un importe monetario inválido.',

            ]);

        }

        $negativo = str_starts_with($valor, '-');

        $partes = explode('.', ltrim($valor, '-'));

        $centavos = ((int) $partes[0] * 100)

            + (int) str_pad($partes[1] ?? '0', 2, '0');

        return $negativo ? -$centavos : $centavos;

    }

    private function aDecimal(int $centavos): string

    {

        return sprintf(

            '%s%d.%02d',

            $centavos < 0 ? '-' : '',

            intdiv(abs($centavos), 100),

            abs($centavos) % 100

        );

    }

}
