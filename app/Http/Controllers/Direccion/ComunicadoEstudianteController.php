<?php

namespace App\Http\Controllers\Direccion;

use App\Http\Controllers\Controller;
use App\Models\CicloEscolar;
use App\Models\Estudiante;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ComunicadoEstudianteController extends Controller
{
    /**
     * Obtener los estudiantes disponibles para dirigir
     * comunicados a sus responsables.
     *
     * La consulta permite seleccionar ciclos futuros,
     * siempre que existan inscripciones completadas.
     *
     * Este endpoint debe registrarse dentro del grupo
     * protegido del rol Dirección.
     */
    public function index(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'ciclo_escolar_id' => [
                'required',
                'integer',
                'exists:ciclos_escolares,id',
            ],
        ]);

        $cicloId = (int) $datos['ciclo_escolar_id'];

        $ciclo = CicloEscolar::query()
            ->findOrFail($cicloId);

        $estudiantes = Estudiante::query()
            ->whereHas(
                'registrosInscripcion',
                function ($consulta) use ($cicloId) {
                    $consulta
                        ->where(
                            'ciclo_escolar_id',
                            $cicloId
                        )
                        ->where(
                            'estado',
                            'COMPLETADA'
                        )
                        ->where(function ($q) {
                            $q->whereNull('fecha_fin')
                                ->orWhereDate(
                                    'fecha_fin',
                                    '>=',
                                    today()
                                );
                        });
                }
            )
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->orderBy('id')
            ->get([
                'id',
                'nombres',
                'apellidos',
                'numero_carnet',
            ])
            ->map(function (Estudiante $estudiante) {
                return [
                    'id' => $estudiante->id,

                    'nombre' => trim(
                        ($estudiante->nombres ?? '')
                        . ' '
                        . ($estudiante->apellidos ?? '')
                    ),

                    'numero_carnet' =>
                        $estudiante->numero_carnet
                        ?: 'No asignado',
                ];
            })
            ->values();

        return response()->json([
            'success' => true,

            'ciclo' => [
                'id' => $ciclo->id,
                'nombre' => $ciclo->nombre,
            ],

            'total' => $estudiantes->count(),

            'estudiantes' => $estudiantes,
        ]);
    }
}
