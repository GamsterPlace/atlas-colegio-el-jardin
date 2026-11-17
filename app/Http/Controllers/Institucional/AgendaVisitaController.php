<?php

namespace App\Http\Controllers\Institucional;

use App\Http\Controllers\Controller;
use App\Services\AgendaVisitasService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class AgendaVisitaController extends Controller
{
    public function disponibilidad(Request $request, AgendaVisitasService $agenda): JsonResponse
    {
        $d = $request->validate(['fecha' => ['required', 'date', 'after_or_equal:today']]);
        return response()->json(['horarios' => $agenda->horarios($d['fecha'])]);
    }
    public function store(Request $request, AgendaVisitasService $agenda): RedirectResponse
    {
        $d = $request->validate([
            'nombre_visitante' => ['required', 'string', 'max:160'],
            'telefono' => ['required', 'string', 'max:30'],
            'correo' => ['nullable', 'email', 'max:190'],
            'motivo' => ['required', 'string', 'max:190'],
            'observaciones' => ['nullable', 'string', 'max:1000'],
            'fecha' => ['required', 'date', 'after:today'],
            'hora_inicio' => ['required', 'date_format:H:i'],
        ]);
        $agenda->reservar($d);
        return redirect('/#contacto')->with('visita_ok', 'Solicitud registrada. El horario queda reservado provisionalmente hasta su confirmación por el colegio.');
    }
}
