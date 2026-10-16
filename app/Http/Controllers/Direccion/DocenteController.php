<?php

namespace App\Http\Controllers\Direccion;

use App\Http\Controllers\Controller;
use App\Models\AsignacionDocente;
use App\Models\CargaAcademica;
use App\Models\CicloEscolar;
use App\Models\Docente;
use App\Models\DocenteGuia;
use App\Models\Persona;
use App\Models\Personal;
use App\Services\GeneracionAccesoDocenteService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class DocenteController extends Controller
{
    public function index(Request $request): View
    {
        $buscar = trim((string) $request->query('buscar', ''));
        $estado = (string) $request->query('estado', '');
        $docentes = Docente::query()->with('personal.persona')
            ->withCount(['registrosAsignacionDocente as asignaciones_activas_count' => fn ($q) => $q->where('activo', true)->whereNull('fecha_fin')])
            ->when($buscar !== '', fn ($q) => $q->whereHas('personal', fn ($p) => $p->where('codigo_empleado', 'like', "%{$buscar}%")
                ->orWhereHas('persona', fn ($persona) => $persona->where('nombres', 'like', "%{$buscar}%")
                    ->orWhere('apellidos', 'like', "%{$buscar}%")
                    ->orWhere('cui', 'like', "%{$buscar}%"))))
            ->when(in_array($estado, ['1', '0'], true), fn ($q) => $q->where('activo', (bool) $estado))
            ->orderByDesc('activo')->orderByDesc('id')->paginate(15)->withQueryString();
        return view('direccion.docentes.index', compact('docentes', 'buscar', 'estado'));
    }

    public function show(Docente $docente): View
    {
        $docente->load(['personal.persona', 'registrosAsignacionDocente' => fn ($q) => $q->with('carga_academica.seccion.grado', 'carga_academica.pensum_curso.curso')->orderByDesc('fecha_inicio'), 'registrosDocenteGuia.seccion.grado']);
        return view('direccion.docentes.show', compact('docente'));
    }

    public function control(Request $request): View
    {
        $docentes = Docente::with('personal.persona')->withCount(['registrosAsignacionDocente as asignaciones_activas_count' => fn ($q) => $q->where('activo', true)->whereNull('fecha_fin')])->orderByDesc('activo')->orderByDesc('id')->paginate(15);
        $activos = Docente::activos()->count();
        $inactivos = Docente::inactivos()->count();
        return view('direccion.docentes.control.index', compact('docentes', 'activos', 'inactivos'));
    }

    public function create(): View
    {
        return view('direccion.docentes.control.form', ['docente' => null]);
    }

    public function edit(Docente $docente): View
    {
        $docente->load('personal.persona');
        return view('direccion.docentes.control.form', compact('docente'));
    }

    private function datos(Request $request, ?Docente $docente = null): array
    {
        $personaId = $docente?->personal?->persona_id;
        $personalId = $docente?->personal_id;
        return $request->validate([
            'nombres' => ['required', 'string', 'max:150'],
            'apellidos' => ['required', 'string', 'max:150'],
            'cui' => ['nullable', 'string', 'max:20', Rule::unique('personas', 'cui')->ignore($personaId)],
            'fecha_nacimiento' => ['nullable', 'date', 'before_or_equal:today'],
            'sexo' => ['nullable', Rule::in(['M', 'F'])],
            'telefono' => ['nullable', 'string', 'max:30'],
            'correo' => ['nullable', 'email', 'max:150'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'codigo_empleado' => ['required', 'string', 'max:50', Rule::unique('personal', 'codigo_empleado')->ignore($personalId)],
            'fecha_ingreso' => ['required', 'date'],
            'cargo' => ['required', 'string', 'max:100'],
        ]);
    }

    public function store(Request $request, GeneracionAccesoDocenteService $accesos): RedirectResponse
    {
        $datos = $this->datos($request);
        $resultado = DB::transaction(function () use ($datos, $accesos) {
            $persona = Persona::create(collect($datos)->only(['nombres', 'apellidos', 'cui', 'fecha_nacimiento', 'sexo', 'telefono', 'correo', 'direccion'])->all());
            $personal = Personal::create(['persona_id' => $persona->id, 'codigo_empleado' => $datos['codigo_empleado'], 'fecha_ingreso' => $datos['fecha_ingreso'], 'cargo' => $datos['cargo'], 'activo' => true]);
            $docente = Docente::create(['personal_id' => $personal->id, 'activo' => true]);
            $acceso = $accesos->generar($persona);
            return ['docente' => $docente, 'acceso' => $acceso];
        }, 3);

        $docente = $resultado['docente'];
        $clave = 'atlas:acceso-docente:'.Auth::id().':'.$docente->id;
        Cache::put($clave, Crypt::encryptString(json_encode($resultado['acceso'], JSON_THROW_ON_ERROR)), now()->addMinutes(10));

        return redirect()->route('direccion.docentes.show', $docente)
            ->with('success', 'Docente registrado. Se abrirá su ficha institucional de acceso.')
            ->with('documento_docente_id', $docente->id);
    }

    public function fichaAcceso(Docente $docente): Response
    {
        $docente->load('personal.persona.registrosUsuario');
        $usuario = $docente->personal?->persona?->registrosUsuario;
        abort_unless($usuario, 404);

        $clave = 'atlas:acceso-docente:'.Auth::id().':'.$docente->id;
        $cifrado = Cache::get($clave);
        $acceso = $cifrado ? json_decode(Crypt::decryptString($cifrado), true, 512, JSON_THROW_ON_ERROR) : [];
        $passwordTemporal = ($acceso['usuario'] ?? null) === $usuario->nombre_usuario
            ? ($acceso['password'] ?? null) : null;

        $respuesta = Pdf::loadView('direccion.docentes.pdf.ficha-acceso', [
            'docente' => $docente,
            'usuario' => $usuario,
            'passwordTemporal' => $passwordTemporal,
        ])->setPaper('letter')->stream('ficha-docente-'.$docente->id.'.pdf');

        if ($cifrado) {
            Cache::forget($clave);
        }
        $respuesta->headers->set('Cache-Control', 'private, no-store, no-cache, max-age=0');
        $respuesta->headers->set('Pragma', 'no-cache');
        return $respuesta;
    }

    public function update(Request $request, Docente $docente): RedirectResponse
    {
        $docente->load('personal.persona');
        $datos = $this->datos($request, $docente);
        DB::transaction(function () use ($docente, $datos) {
            $docente->personal->persona->update(collect($datos)->only(['nombres', 'apellidos', 'cui', 'fecha_nacimiento', 'sexo', 'telefono', 'correo', 'direccion'])->all());
            $docente->personal->update(collect($datos)->only(['codigo_empleado', 'fecha_ingreso', 'cargo'])->all());
        });
        return redirect()->route('direccion.docentes.show', $docente)->with('success', 'Información actualizada.');
    }

    public function baja(Request $request, Docente $docente): RedirectResponse
    {
        $request->validate(['confirmacion' => ['required', Rule::in(['BAJA'])]]);
        DB::transaction(function () use ($docente) {
            $docente = Docente::query()->lockForUpdate()->findOrFail($docente->id);
            if (!$docente->activo) {
                return;
            }
            $hoy = now()->toDateString();
            $docente->registrosAsignacionDocente()->where('activo', true)->update(['activo' => false, 'fecha_fin' => $hoy]);
            $docente->registrosDocenteGuia()->whereNull('fecha_fin')->update(['fecha_fin' => $hoy]);
            $docente->update(['activo' => false]);
        });
        return back()->with('success', 'Docente dado de baja. Sus asignaciones se finalizaron sin eliminar el historial.');
    }

    public function reactivar(Docente $docente): RedirectResponse
    {
        if (!$docente->personal?->activo) {
            return back()->withErrors(['docente' => 'El registro de personal está inactivo. Debe regularizarse antes de reactivar al docente.']);
        }
        $docente->update(['activo' => true]);
        return back()->with('success', 'Docente reactivado. Las asignaciones anteriores no se reactivaron.');
    }

    public function asignaciones(Request $request): View
    {
        $ciclos = CicloEscolar::orderByDesc('fecha_inicio')->get();
        $cicloId = $request->integer('ciclo_escolar_id') ?: (CicloEscolar::vigente()?->id ?? $ciclos->first()?->id);
        $cargas = CargaAcademica::query()->with(['seccion.grado', 'seccion.jornada', 'pensum_curso.curso'])
            ->whereHas('seccion', fn ($q) => $q->where('ciclo_escolar_id', $cicloId))->where('activo', true)->orderBy('seccion_id')->get();
        $asignaciones = AsignacionDocente::query()->with(['docente.personal.persona', 'carga_academica.seccion.grado', 'carga_academica.pensum_curso.curso'])
            ->whereHas('carga_academica.seccion', fn ($q) => $q->where('ciclo_escolar_id', $cicloId))
            ->orderByDesc('activo')->orderByDesc('fecha_inicio')->paginate(20)->withQueryString();
        $seccionesGuia = \App\Models\Seccion::query()->with(['grado', 'jornada'])
            ->where('ciclo_escolar_id', $cicloId)->where('activo', true)
            ->orderBy('grado_id')->orderBy('nombre')->get();
        $guias = DocenteGuia::query()->with(['docente.personal.persona', 'seccion.grado'])
            ->whereHas('seccion', fn ($q) => $q->where('ciclo_escolar_id', $cicloId))
            ->orderByDesc('fecha_inicio')->get();
        $docentes = Docente::activos()->whereHas('personal', fn ($q) => $q->where('activo', true))->with('personal.persona')->get()->sortBy(fn ($d) => ($d->personal?->persona?->apellidos ?? '').' '.($d->personal?->persona?->nombres ?? ''));
        return view('direccion.docentes.asignaciones.index', compact('ciclos', 'cicloId', 'cargas', 'asignaciones', 'docentes', 'seccionesGuia', 'guias'));
    }

    public function asignar(Request $request): RedirectResponse
    {
        $datos = $request->validate(['docente_id' => ['required', 'integer', 'exists:docentes,id'], 'carga_academica_id' => ['required', 'integer', 'exists:cargas_academicas,id'], 'fecha_inicio' => ['required', 'date']]);
        DB::transaction(function () use ($datos) {
            $docente = Docente::with('personal')->findOrFail($datos['docente_id']);
            $carga = CargaAcademica::with('seccion')->lockForUpdate()->findOrFail($datos['carga_academica_id']);
            if (!$docente->activo || !$docente->personal?->activo || !$carga->activo || !$carga->seccion?->activo) {
                throw ValidationException::withMessages(['docente_id' => 'El docente, su registro laboral o la carga académica están inactivos.']);
            }
            if ($carga->seccion->ciclo_escolar_id === null) {
                throw ValidationException::withMessages(['carga_academica_id' => 'La sección no tiene ciclo escolar.']);
            }
            if (AsignacionDocente::where('carga_academica_id', $carga->id)->where('activo', true)->whereNull('fecha_fin')->exists()) {
                throw ValidationException::withMessages(['carga_academica_id' => 'Esta carga académica ya tiene un docente asignado. Finalice la asignación anterior.']);
            }
            AsignacionDocente::create($datos + ['activo' => true, 'fecha_fin' => null]);
        });
        return back()->with('success', 'Asignación académica registrada.');
    }

    public function finalizarAsignacion(Request $request, AsignacionDocente $asignacion): RedirectResponse
    {
        $datos = $request->validate(['fecha_fin' => ['required', 'date', 'after_or_equal:'.$asignacion->fecha_inicio?->toDateString()]]);
        if (!$asignacion->activo || $asignacion->fecha_fin) {
            return back()->withErrors(['asignacion' => 'La asignación ya está finalizada.']);
        }
        $asignacion->update(['activo' => false, 'fecha_fin' => $datos['fecha_fin']]);
        return back()->with('success', 'Asignación finalizada.');
    }

    public function asignarGuia(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'docente_id' => ['required', 'integer', 'exists:docentes,id'],
            'seccion_id' => ['required', 'integer', 'exists:secciones,id'],
            'fecha_inicio' => ['required', 'date'],
        ]);
        DB::transaction(function () use ($datos) {
            $docente = Docente::with('personal')->findOrFail($datos['docente_id']);
            $seccion = \App\Models\Seccion::query()->lockForUpdate()->findOrFail($datos['seccion_id']);
            if (!$docente->activo || !$docente->personal?->activo || !$seccion->activo) {
                throw ValidationException::withMessages(['docente_id' => 'El docente o la sección no están activos.']);
            }
            if (DocenteGuia::where('seccion_id', $seccion->id)->whereNull('fecha_fin')->exists()) {
                throw ValidationException::withMessages(['seccion_id' => 'Esta sección ya tiene docente guía. Finalice la asignación anterior.']);
            }
            DocenteGuia::create($datos + ['fecha_fin' => null]);
        });
        return back()->with('success', 'Docente guía asignado correctamente.');
    }

    public function finalizarGuia(Request $request, DocenteGuia $guia): RedirectResponse
    {
        $datos = $request->validate([
            'fecha_fin' => ['required', 'date', 'after_or_equal:'.$guia->fecha_inicio->toDateString()],
        ]);
        DB::transaction(function () use ($guia, $datos) {
            $registro = DocenteGuia::query()->lockForUpdate()->findOrFail($guia->id);
            if ($registro->fecha_fin !== null) {
                throw ValidationException::withMessages(['guia' => 'Esta asignación de guía ya fue finalizada.']);
            }
            $registro->update(['fecha_fin' => $datos['fecha_fin']]);
        });
        return back()->with('success', 'Asignación de docente guía finalizada.');
    }
}
