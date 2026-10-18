<?php

namespace App\Http\Controllers\Direccion;

use App\Http\Controllers\Controller;
use App\Models\CicloEscolar;
use App\Models\EventoAgenda;
use App\Models\Grado;
use App\Models\Nivel;
use App\Models\Seccion;
use App\Models\Usuario;
use App\Models\EventoAgendaResponsable;
use App\Models\RecordatorioAgenda;
use App\Models\CalendarioEscolar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AgendaController extends Controller
{
    private function catalogos(): array
    {
        return [
            'ciclos' => CicloEscolar::orderByDesc('fecha_inicio')->get(),
            'niveles' => Nivel::where('activo', true)->orderBy('orden')->get(),
            'grados' => Grado::where('activo', true)->orderBy('orden')->get(),
            'secciones' => Seccion::where('activo', true)->get(),
            'usuariosResponsables' => Usuario::where('activo', true)->orderBy('nombre_usuario')->get(['id', 'nombre_usuario']),
        ];
    }

    public function index(Request $request): View
    {
        $ciclos = CicloEscolar::orderByDesc('fecha_inicio')->get();
        $cicloId = $request->integer('ciclo_escolar_id') ?: $ciclos->firstWhere('estado', 'ACTIVO')?->id ?: $ciclos->first()?->id;
        $mes = $request->input('mes', now()->format('Y-m'));
        if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $mes)) $mes = now()->format('Y-m');
        $inicio = \Carbon\Carbon::createFromFormat('Y-m-d', $mes.'-01')->startOfMonth();
        $fin = $inicio->copy()->endOfMonth();
        $eventos = EventoAgenda::with(['registrosEventoAgendaDestinatario', 'registrosEventoAgendaResponsable.usuario'])
            ->when($cicloId, fn ($q) => $q->where('ciclo_escolar_id', $cicloId))
            ->where('inicio', '<=', $fin->endOfDay())
            ->where(fn ($q) => $q->whereNull('fin')->orWhere('fin', '>=', $inicio->startOfDay()))
            ->orderBy('inicio')->get();
        return view('direccion.agenda.index', compact('ciclos', 'cicloId', 'mes', 'inicio', 'eventos'));
    }

    public function gestionar(Request $request): View
    {
        $ciclos = CicloEscolar::orderByDesc('fecha_inicio')->get();
        $cicloId = $request->integer('ciclo_escolar_id') ?: $ciclos->firstWhere('estado', 'ACTIVO')?->id ?: $ciclos->first()?->id;
        $eventos = EventoAgenda::with('ciclo_escolar')
            ->when($cicloId, fn ($q) => $q->where('ciclo_escolar_id', $cicloId))
            ->when($request->filled('estado'), fn ($q) => $q->where('estado', $request->estado))
            ->orderByDesc('inicio')->paginate(20)->withQueryString();
        return view('direccion.agenda.gestionar', compact('ciclos', 'cicloId', 'eventos'));
    }

    public function create(): View
    {
        return view('direccion.agenda.formulario', array_merge($this->catalogos(), ['evento' => null]));
    }

    public function edit(EventoAgenda $evento): View
    {
        $evento->load('registrosEventoAgendaDestinatario.grado', 'registrosEventoAgendaDestinatario.seccion', 'registrosEventoAgendaResponsable', 'registrosRecordatorioAgenda');
        return view('direccion.agenda.formulario', array_merge($this->catalogos(), compact('evento')));
    }

    private function validar(Request $request): array
    {
        $data = $request->validate([
            'ciclo_escolar_id' => ['required', 'exists:ciclos_escolares,id'],
            'titulo' => ['required', 'string', 'max:190'],
            'descripcion' => ['nullable', 'string'],
            'inicio' => ['required', 'date'],
            'fin' => ['nullable', 'date', 'after_or_equal:inicio'],
            'ubicacion' => ['nullable', 'string', 'max:255'],
            'visibilidad' => ['required', Rule::in(['PUBLICO', 'INSTITUCIONAL', 'DESTINATARIOS'])],
            'nivel_id' => ['nullable', 'integer', 'exists:niveles,id'],
            'grado_id' => ['nullable', 'integer', 'exists:grados,id'],
            'seccion_id' => ['nullable', 'integer', 'exists:secciones,id'],
            'responsables' => ['nullable', 'array'],
            'responsables.*' => ['integer', 'distinct', Rule::exists('usuarios', 'id')->where('activo', true)],
            'recordar_at' => ['nullable', 'date', 'before:inicio'],
        ]);
        if (!empty($data['recordar_at']) && empty($data['responsables'])) {
            throw ValidationException::withMessages(['responsables' => 'Seleccione al menos un responsable para programar un recordatorio.']);
        }
        if ($data['visibilidad'] === 'DESTINATARIOS') {
            if (empty($data['nivel_id'])) throw ValidationException::withMessages(['nivel_id' => 'Seleccione al menos un nivel para definir los destinatarios.']);
            if (!empty($data['grado_id']) && !Grado::whereKey($data['grado_id'])->where('nivel_id', $data['nivel_id'])->exists()) throw ValidationException::withMessages(['grado_id' => 'El grado no pertenece al nivel seleccionado.']);
            if (!empty($data['seccion_id']) && !Seccion::whereKey($data['seccion_id'])->where('grado_id', $data['grado_id'])->where('ciclo_escolar_id', $data['ciclo_escolar_id'])->exists()) throw ValidationException::withMessages(['seccion_id' => 'La sección no corresponde al grado y ciclo seleccionados.']);
        }
        return $data;
    }

    private function guardarDestinatario(EventoAgenda $evento, array $data): void
    {
        $evento->registrosEventoAgendaDestinatario()->delete();
        if ($data['visibilidad'] !== 'DESTINATARIOS') return;
        $campo = !empty($data['seccion_id']) ? 'seccion_id' : (!empty($data['grado_id']) ? 'grado_id' : 'nivel_id');
        $evento->registrosEventoAgendaDestinatario()->create([$campo => $data[$campo]]);
    }


    private function guardarResponsablesYRecordatorios(EventoAgenda $evento, array $data): void
    {
        $ids = array_values(array_unique(array_map('intval', $data['responsables'] ?? [])));
        EventoAgendaResponsable::where('evento_agenda_id', $evento->id)
            ->whereNotIn('usuario_id', $ids ?: [0])->delete();
        foreach ($ids as $id) {
            EventoAgendaResponsable::firstOrCreate(['evento_agenda_id' => $evento->id, 'usuario_id' => $id]);
        }
        // Se conservan los recordatorios ya enviados; los pendientes se sincronizan.
        RecordatorioAgenda::where('evento_agenda_id', $evento->id)->whereNull('enviado_at')->delete();
        if (!empty($data['recordar_at'])) {
            foreach ($ids as $id) {
                RecordatorioAgenda::firstOrCreate([
                    'evento_agenda_id' => $evento->id,
                    'usuario_id' => $id,
                    'recordar_at' => $data['recordar_at'],
                ]);
            }
        }
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validar($request);
        DB::transaction(function () use ($data, $request) {
            $evento = EventoAgenda::create(array_merge(collect($data)->only(['ciclo_escolar_id','titulo','descripcion','inicio','fin','ubicacion','visibilidad'])->all(), [
                'estado' => 'PROGRAMADO', 'creado_por' => $request->user()->id,
            ]));
            $this->guardarDestinatario($evento, $data);
            $this->guardarResponsablesYRecordatorios($evento, $data);
        });
        return redirect()->route('direccion.agenda.gestionar')->with('success', 'Actividad registrada correctamente.');
    }

    public function update(Request $request, EventoAgenda $evento): RedirectResponse
    {
        if ($evento->estado !== 'PROGRAMADO') return back()->withErrors(['evento' => 'Solo pueden editarse actividades programadas.']);
        $data = $this->validar($request);
        DB::transaction(function () use ($evento, $data) {
            $evento->update(collect($data)->only(['ciclo_escolar_id','titulo','descripcion','inicio','fin','ubicacion','visibilidad'])->all());
            $this->guardarDestinatario($evento, $data);
            $this->guardarResponsablesYRecordatorios($evento, $data);
        });
        return redirect()->route('direccion.agenda.gestionar')->with('success', 'Actividad actualizada.');
    }

    public function estado(Request $request, EventoAgenda $evento): RedirectResponse
    {
        $data = $request->validate(['estado' => ['required', Rule::in(['CANCELADO', 'REALIZADO'])]]);
        if ($evento->estado !== 'PROGRAMADO') return back()->withErrors(['estado' => 'La actividad ya fue finalizada o cancelada.']);
        $evento->update(['estado' => $data['estado']]);
        if ($data['estado'] === 'CANCELADO') {
            RecordatorioAgenda::where('evento_agenda_id', $evento->id)->whereNull('enviado_at')->delete();
        }
        return back()->with('success', 'Estado de la actividad actualizado.');
    }

    public function calendarioEscolar(Request $request): View
    {
        $ciclos = CicloEscolar::orderByDesc('fecha_inicio')->get();
        $cicloId = $request->integer('ciclo_escolar_id') ?: ($ciclos->firstWhere('estado', 'ACTIVO')?->id ?: $ciclos->first()?->id);
        $dias = CalendarioEscolar::query()->when($cicloId, fn ($q) => $q->where('ciclo_escolar_id', $cicloId))
            ->orderBy('fecha')->get();
        return view('direccion.agenda.calendario-escolar', compact('ciclos', 'cicloId', 'dias'));
    }

    public function guardarDiaEscolar(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ciclo_escolar_id' => ['required', 'exists:ciclos_escolares,id'],
            'fecha' => ['required', 'date'],
            'tipo' => ['required', Rule::in(['LECTIVO','FERIADO','ASUETO','SUSPENSION','OTRO'])],
            'descripcion' => ['nullable', 'string', 'max:255'],
        ]);
        $ciclo = CicloEscolar::findOrFail($data['ciclo_escolar_id']);
        if ($data['fecha'] < \Carbon\Carbon::parse($ciclo->fecha_inicio)->toDateString() || $data['fecha'] > \Carbon\Carbon::parse($ciclo->fecha_fin)->toDateString()) {
            throw ValidationException::withMessages(['fecha' => 'La fecha debe estar dentro del ciclo escolar.']);
        }
        CalendarioEscolar::updateOrCreate(
            ['ciclo_escolar_id' => $data['ciclo_escolar_id'], 'fecha' => $data['fecha']],
            ['tipo' => $data['tipo'], 'descripcion' => $data['descripcion'] ?? null]
        );
        return redirect()->route('direccion.agenda.calendario-escolar.index', ['ciclo_escolar_id' => $data['ciclo_escolar_id']])
            ->with('success', 'Día del calendario escolar guardado.');
    }

    public function eliminarDiaEscolar(CalendarioEscolar $dia): RedirectResponse
    {
        $cicloId = $dia->ciclo_escolar_id;
        $dia->delete();
        return redirect()->route('direccion.agenda.calendario-escolar.index', ['ciclo_escolar_id' => $cicloId])
            ->with('success', 'Registro eliminado del calendario escolar.');
    }

    public function recordatorios(Request $request): View
    {
        $ciclos = CicloEscolar::orderByDesc('fecha_inicio')->get();
        $cicloId = $request->integer('ciclo_escolar_id') ?: ($ciclos->firstWhere('estado', 'ACTIVO')?->id ?: $ciclos->first()?->id);
        $recordatorios = RecordatorioAgenda::with(['evento_agenda', 'usuario'])
            ->whereHas('evento_agenda', fn ($q) => $q->where('ciclo_escolar_id', $cicloId))
            ->orderBy('recordar_at')->paginate(30)->withQueryString();
        return view('direccion.agenda.recordatorios', compact('ciclos', 'cicloId', 'recordatorios'));
    }

}
