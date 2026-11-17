<?php

namespace App\Http\Controllers\Direccion;

use App\Http\Controllers\Controller;
use App\Models\CitaInstitucional;
use App\Services\AgendaVisitasService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;

class AgendaVisitaController extends Controller
{
    public function index(Request $request): View
    {
        $datos = $request->validate(['fecha' => ['nullable', 'date'], 'estado' => ['nullable', Rule::in(['PENDIENTE', 'CONFIRMADA', 'REPROGRAMADA', 'CANCELADA', 'ATENDIDA'])]]);
        $citas = CitaInstitucional::query()->when($datos['fecha'] ?? null, fn($q, $v) => $q->whereDate('fecha', $v))
            ->when($datos['estado'] ?? null, fn($q, $v) => $q->where('estado', $v))->orderBy('fecha')->orderBy('hora_inicio')->paginate(20)->withQueryString();
        return view('institucional.visitas.gestion', compact('citas'));
    }
    public function actualizar(Request $request, CitaInstitucional $cita, AgendaVisitasService $agenda): RedirectResponse
    {
        $d = $request->validate([
            'estado' => ['required', Rule::in(['PENDIENTE', 'CONFIRMADA', 'REPROGRAMADA', 'CANCELADA', 'ATENDIDA'])],
            'fecha' => ['required', 'date', 'after_or_equal:today'],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'nota_interna' => ['nullable', 'string', 'max:1000']
        ]);
        $cambioHorario = $cita->fecha->toDateString() !== $d['fecha'] || substr($cita->hora_inicio, 0, 5) !== $d['hora_inicio'];
        if ($cambioHorario && $d['estado'] !== 'CANCELADA') {
            $agenda->reservar(['fecha' => $d['fecha'], 'hora_inicio' => $d['hora_inicio'], 'estado' => 'REPROGRAMADA', 'gestionado_por' => $request->user()->id, 'nota_interna' => $d['nota_interna'] ?? null], $cita->id);
        } else {
            $cita->update(['estado' => $d['estado'], 'gestionado_por' => $request->user()->id, 'nota_interna' => $d['nota_interna'] ?? null]);
        }
        return back()->with('ok', 'Cita actualizada.');
    }
}
