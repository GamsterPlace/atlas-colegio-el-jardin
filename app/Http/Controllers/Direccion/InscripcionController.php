<?php

namespace App\Http\Controllers\Direccion;

use App\Http\Controllers\Controller;
use App\Models\CicloEscolar;
use App\Models\Estudiante;
use App\Models\Inscripcion;
use App\Models\Persona;
use App\Models\Seccion;
use App\Services\InscripcionService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class InscripcionController extends Controller
{
    public function index(Request $request): View
    {
        $datos = $request->validate([
            'ciclo_escolar_id' => ['nullable', 'integer', 'exists:ciclos_escolares,id'],
            'buscar' => ['nullable', 'string', 'max:120'],
        ]);

        $ciclosEscolares = CicloEscolar::query()
            ->orderByDesc('fecha_inicio')
            ->get();

        $cicloEscolarId = $datos['ciclo_escolar_id']
            ?? CicloEscolar::query()->where('estado', 'ACTIVO')->value('id');

        $buscar = trim($datos['buscar'] ?? '');

        $inscripciones = Inscripcion::query()
            ->with(['estudiante', 'ciclo_escolar', 'seccion'])
            ->when($cicloEscolarId, fn ($query) => $query->delCiclo($cicloEscolarId))
            ->buscar($buscar)
            ->orderByDesc('fecha_inscripcion')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('direccion.inscripciones.index', compact(
            'ciclosEscolares', 'cicloEscolarId', 'buscar', 'inscripciones'
        ));
    }

    public function create(Request $request): View
    {
        $datos = $request->validate([
            'ciclo_escolar_id' => ['nullable', 'integer', 'exists:ciclos_escolares,id'],
        ]);

        $ciclosEscolares = CicloEscolar::query()
            ->whereIn('estado', ['PLANIFICACION', 'ACTIVO'])
            ->orderByDesc('fecha_inicio')
            ->get();

        $cicloEscolarId = $datos['ciclo_escolar_id']
            ?? $ciclosEscolares->firstWhere('estado', 'ACTIVO')?->id
            ?? $ciclosEscolares->first()?->id;

        $secciones = Seccion::query()
            ->where('activo', true)
            ->when(
                $cicloEscolarId,
                fn ($query) => $query->where('ciclo_escolar_id', $cicloEscolarId)
            )
            ->orderBy('nombre')
            ->get();

        return view('direccion.inscripciones.create', compact(
            'ciclosEscolares', 'cicloEscolarId', 'secciones'
        ));
    }

    public function store(Request $request, InscripcionService $inscripcionService): RedirectResponse
    {
        $datos = $request->validate([
            'tipo_inscripcion' => ['required', Rule::in(['NUEVA', 'REINSCRIPCION'])],
            'estudiante_id' => [
                'required_if:tipo_inscripcion,REINSCRIPCION',
                'nullable', 'integer', 'exists:estudiantes,id',
            ],
            'ciclo_escolar_id' => [
                'required', 'integer',
                Rule::exists('ciclos_escolares', 'id')
                    ->whereIn('estado', ['PLANIFICACION', 'ACTIVO']),
            ],
            'seccion_id' => [
                'required', 'integer',
                Rule::exists('secciones', 'id')
                    ->where('activo', true)
                    ->where('ciclo_escolar_id', $request->input('ciclo_escolar_id')),
            ],
            'codigo' => ['required_if:tipo_inscripcion,NUEVA', 'nullable', 'string', 'max:40'],
            'cui' => ['required_if:tipo_inscripcion,NUEVA', 'nullable', 'digits:13'],
            'nombres' => ['required', 'string', 'max:120'],
            'apellidos' => ['required', 'string', 'max:120'],
            'fecha_nacimiento' => ['required', 'date', 'before_or_equal:today'],
            'sexo' => ['required', Rule::in(['MASCULINO', 'FEMENINO'])],
            'direccion' => ['nullable', 'string', 'max:500'],
            'lugar_nacimiento' => ['nullable', 'string', 'max:255'],
            'religion' => ['nullable', 'string', 'max:120'],
            'telefono_casa' => ['nullable', 'string', 'max:30'],
            'transporte' => ['nullable', 'string', 'max:255'],
            'seguro_medico' => ['nullable', 'string', 'max:255'],
            'numero_hermanos' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'hermanos_mayores' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'hermanos_menores' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'hermanos_inscritos' => ['nullable', 'string', 'max:5000'],
            'observaciones' => ['nullable', 'string', 'max:2000'],
            'responsables' => ['required', 'array', 'min:1'],
            'responsables.*' => ['required', 'array'],
            'informacion_medica' => ['nullable', 'array'],
            'informacion_medica.tipo_sangre' => ['nullable', 'string', 'max:10'],
            'informacion_medica.alergias' => ['nullable', 'string'],
            'informacion_medica.condiciones_relevantes' => ['nullable', 'string'],
            'informacion_medica.medicamentos' => ['nullable', 'string'],
            'informacion_medica.indicaciones_emergencia' => ['nullable', 'string'],
            'contactos_emergencia' => ['nullable', 'array'],
            'contactos_emergencia.*' => ['array'],
            'personas_autorizadas_retiro' => ['nullable', 'array'],
            'personas_autorizadas_retiro.*' => ['array'],
        ]);

        if ($datos['tipo_inscripcion'] === 'NUEVA') {
            $datos['estudiante_id'] = null;
        }

        $inscripcion = $inscripcionService->registrar(
            $datos,
            (int) $request->user()->id
        );

        $iniciales = $inscripcionService->credencialesIniciales();
        if ($iniciales) {
            Cache::put(
                'atlas:credenciales-iniciales:'.$request->user()->id.':'.$inscripcion->id,
                Crypt::encryptString(json_encode($iniciales, JSON_THROW_ON_ERROR)),
                now()->addMinutes(10)
            );
        }

        return redirect()
            ->route('direccion.inscripciones.index', [
                'ciclo_escolar_id' => $inscripcion->ciclo_escolar_id,
            ])
            ->with(
                'success',
                'La inscripción se completó correctamente. '
                . 'Se registró al estudiante y se generaron '
                . 'las obligaciones financieras y el talonario.'
            )
            ->with('documentos_inscripcion_id', $inscripcion->id);
    }

    public function buscarEstudiante(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'identificador' => ['required', 'string', 'max:40'],
        ]);

        $estudiante = Estudiante::query()
            ->with('registrosInformacionComplementariaEstudiante')
            ->buscarPorIdentificador($datos['identificador'])
            ->first();

        if (!$estudiante) {
            return response()->json([
                'encontrado' => false,
                'mensaje' => 'No se encontró un estudiante con ese código o carné.',
            ], 404);
        }

        $complementaria = $estudiante->registrosInformacionComplementariaEstudiante;

        return response()->json([
            'encontrado' => true,
            'estudiante' => [
                'id' => $estudiante->id,
                'codigo' => $estudiante->codigo,
                'numero_carnet' => $estudiante->numero_carnet,
                'cui' => $estudiante->cui,
                'lugar_nacimiento' => $estudiante->lugar_nacimiento,
                'nombres' => $estudiante->nombres,
                'apellidos' => $estudiante->apellidos,
                'nombre_completo' => $estudiante->nombre_completo,
                'fecha_nacimiento' => $estudiante->fecha_nacimiento?->format('Y-m-d'),
                'sexo' => $estudiante->sexo,
                'direccion' => $estudiante->direccion,
                'religion' => $complementaria?->religion,
                'telefono_casa' => $complementaria?->telefono_casa,
                'transporte' => $complementaria?->transporte,
                'numero_hermanos' => $complementaria?->numero_hermanos,
                'hermanos_mayores' => $complementaria?->hermanos_mayores,
                'hermanos_menores' => $complementaria?->hermanos_menores,
                'hermanos_inscritos' => $complementaria?->hermanos_inscritos,
                'seguro_medico' => $estudiante->registrosInformacionMedicaEstudiante?->seguro_medico,
                'estado' => $estudiante->estado,
            ],
        ]);
    }
    public function buscarResponsable(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'cui' => ['required', 'digits:13'],
        ]);

        $persona = Persona::query()
            ->where('cui', $datos['cui'])
            ->first();

        if (!$persona) {
            return response()->json(['encontrado' => false]);
        }

        return response()->json([
            'encontrado' => true,
            'persona' => [
                'nombres' => $persona->nombres,
                'apellidos' => $persona->apellidos,
                'telefono' => $persona->telefono,
                'correo' => $persona->correo,
                'direccion' => $persona->direccion,
                'fecha_nacimiento' => $persona->fecha_nacimiento
                    ? \Illuminate\Support\Carbon::parse($persona->fecha_nacimiento)->format('Y-m-d')
                    : null,
                'sexo' => $persona->sexo,
            ],
        ]);
    }

}
