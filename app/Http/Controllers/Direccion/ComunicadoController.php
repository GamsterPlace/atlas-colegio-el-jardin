<?php

namespace App\Http\Controllers\Direccion;

use App\Http\Controllers\Controller;
use App\Models\CicloEscolar;
use App\Models\Comunicado;
use App\Models\Docente;
use App\Models\Estudiante;
use App\Models\Grado;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ComunicadoController extends Controller
{
    public function index(Request $request): View
    {
        $filtros = $request->validate([
            'buscar' => ['nullable', 'string', 'max:150'],
            'estado' => [
                'nullable',
                Rule::in(['PUBLICADO', 'BORRADOR', 'CANCELADO']),
            ],
        ]);

        $comunicados = Comunicado::query()
            ->with('autor.persona')
            ->withCount([
                'registrosComunicadoDestinatario as total_destinatarios' => function ($query) {
                    $query->whereNotNull('usuario_id');
                },
                'registrosLecturaComunicado as total_lecturas',
            ])
            ->when(
                $filtros['buscar'] ?? null,
                function ($query, $buscar) {
                    $query->where(function ($q) use ($buscar) {
                        $q->where('titulo', 'like', "%{$buscar}%")
                            ->orWhere('contenido', 'like', "%{$buscar}%");
                    });
                }
            )
            ->when(
                $filtros['estado'] ?? null,
                fn ($query, $estado) =>
                    $query->where('estado', $estado)
            )
            ->orderByDesc('publicado_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $resumen = [
            'total' => Comunicado::query()->count(),

            'publicados' => Comunicado::query()
                ->where('estado', 'PUBLICADO')
                ->count(),

            'destinatarios' => DB::table('comunicado_destinatarios')
                ->whereNotNull('usuario_id')
                ->count(),

            'lecturas' => DB::table('lecturas_comunicados')
                ->count(),
        ];

        return view(
            'direccion.comunicados.index',
            compact('comunicados', 'filtros', 'resumen')
        );
    }

    public function create(Request $request): View
    {
        $datos = $request->validate([
            'ciclo_escolar_id' => [
                'nullable',
                'integer',
                'exists:ciclos_escolares,id',
            ],
        ]);

        $ciclos = CicloEscolar::query()
            ->orderByDesc('fecha_inicio')
            ->get();

        $cicloId = isset($datos['ciclo_escolar_id'])
            ? (int) $datos['ciclo_escolar_id']
            : (
                $ciclos->firstWhere('estado', 'ACTIVO')?->id
                ?? $ciclos->first()?->id
            );

        $grados = Grado::query()
            ->with('nivel')
            ->where('activo', true)
            ->orderBy('nivel_id')
            ->orderBy('orden')
            ->get();

        $docentes = Docente::query()
            ->with('personal.persona.registrosUsuario')
            ->where('activo', true)
            ->whereHas(
                'personal',
                fn ($q) => $q->where('activo', true)
            )
            ->get();

        /*
         * Para seleccionar destinatarios de un ciclo futuro
         * no se exige que haya comenzado la fecha de clases.
         *
         * Solo se consideran inscripciones completadas
         * que no hayan finalizado.
         */
        $estudiantes = Estudiante::query()
            ->whereHas(
                'registrosInscripcion',
                function ($q) use ($cicloId) {
                    $q->where('estado', 'COMPLETADA')
                        ->where(function ($consulta) {
                            $consulta->whereNull('fecha_fin')
                                ->orWhereDate(
                                    'fecha_fin',
                                    '>=',
                                    today()
                                );
                        });

                    if ($cicloId) {
                        $q->where('ciclo_escolar_id', $cicloId);
                    }
                }
            )
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->get();

        return view(
            'direccion.comunicados.create',
            compact(
                'ciclos',
                'cicloId',
                'grados',
                'docentes',
                'estudiantes'
            )
        );
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'titulo' => [
                'required',
                'string',
                'min:5',
                'max:190',
            ],

            'contenido' => [
                'required',
                'string',
                'min:10',
                'max:20000',
            ],

            'ciclo_escolar_id' => [
                'nullable',
                'integer',
                'exists:ciclos_escolares,id',
            ],

            'grupos' => [
                'nullable',
                'array',
            ],

            'grupos.*' => [
                'string',
                'distinct',
                Rule::in([
                    'COMUNIDAD',
                    'DOCENTES',
                    'PERSONAL',
                    'PADRES',
                ]),
            ],

            'grado_ids' => [
                'nullable',
                'array',
            ],

            'grado_ids.*' => [
                'integer',
                'distinct',
                'exists:grados,id',
            ],

            'estudiante_ids' => [
                'nullable',
                'array',
            ],

            'estudiante_ids.*' => [
                'integer',
                'distinct',
                'exists:estudiantes,id',
            ],

            'docente_ids' => [
                'nullable',
                'array',
            ],

            'docente_ids.*' => [
                'integer',
                'distinct',
                'exists:docentes,id',
            ],
        ]);

        $grupos = $datos['grupos'] ?? [];
        $gradoIds = $datos['grado_ids'] ?? [];
        $estudianteIds = $datos['estudiante_ids'] ?? [];
        $docenteIds = $datos['docente_ids'] ?? [];

        if (
            empty($grupos)
            && empty($gradoIds)
            && empty($estudianteIds)
            && empty($docenteIds)
        ) {
            throw ValidationException::withMessages([
                'grupos' => 'Seleccione al menos un grupo o destinatario.',
            ]);
        }

        $requiereCiclo =
            in_array('COMUNIDAD', $grupos, true)
            || in_array('PADRES', $grupos, true)
            || !empty($gradoIds)
            || !empty($estudianteIds);

        $cicloId = isset($datos['ciclo_escolar_id'])
            ? (int) $datos['ciclo_escolar_id']
            : null;

        if ($requiereCiclo && !$cicloId) {
            throw ValidationException::withMessages([
                'ciclo_escolar_id' =>
                    'Seleccione el ciclo escolar para los destinatarios de padres de familia.',
            ]);
        }

        $destinatarios = $this->resolverDestinatarios(
            $grupos,
            $gradoIds,
            $estudianteIds,
            $docenteIds,
            $cicloId
        );

        if (empty($destinatarios)) {
            throw ValidationException::withMessages([
                'grupos' =>
                    'No se encontraron destinatarios activos y autorizados para los criterios seleccionados.',
            ]);
        }

        $comunicado = DB::transaction(function () use (
            $datos,
            $destinatarios
        ) {
            $fecha = now();

            $comunicado = Comunicado::query()->create([
                'titulo' => trim($datos['titulo']),
                'contenido' => trim($datos['contenido']),
                'estado' => 'PUBLICADO',
                'publicado_at' => $fecha,
                'creado_por' => Auth::id(),
            ]);

            foreach (array_chunk($destinatarios, 500) as $lote) {
                $registros = [];

                foreach ($lote as $usuarioId) {
                    $registros[] = [
                        'comunicado_id' => $comunicado->id,
                        'rol_id' => null,
                        'seccion_id' => null,
                        'usuario_id' => $usuarioId,
                        'created_at' => $fecha,
                        'updated_at' => $fecha,
                    ];
                }

                DB::table('comunicado_destinatarios')
                    ->insert($registros);
            }

            foreach (array_chunk($destinatarios, 500) as $lote) {
                $notificaciones = [];

                foreach ($lote as $usuarioId) {
                    $notificaciones[] = [
                        'usuario_id' => $usuarioId,
                        'titulo' => 'Nuevo comunicado institucional',
                        'mensaje' => $comunicado->titulo,
                        'tipo' => 'COMUNICADO',
                        'enlace' => route(
                            'comunicados.ver',
                            $comunicado->id,
                            false
                        ),
                        'leida_at' => null,
                        'created_at' => $fecha,
                        'updated_at' => $fecha,
                    ];
                }

                DB::table('notificaciones')
                    ->insert($notificaciones);
            }

            return $comunicado;
        }, 3);

        return redirect()
            ->route('direccion.comunicados.show', $comunicado->id)
            ->with(
                'success',
                'Comunicado publicado correctamente para '
                . count($destinatarios)
                . ' destinatarios.'
            );
    }

    public function show(Comunicado $comunicado): View
    {
        $comunicado->load('autor.persona');

        $destinatarios = DB::table(
            'comunicado_destinatarios as cd'
        )
            ->join(
                'usuarios as u',
                'u.id',
                '=',
                'cd.usuario_id'
            )
            ->leftJoin(
                'personas as p',
                'p.id',
                '=',
                'u.persona_id'
            )
            ->leftJoin(
                'lecturas_comunicados as lc',
                function ($join) {
                    $join->on(
                        'lc.comunicado_id',
                        '=',
                        'cd.comunicado_id'
                    )->on(
                        'lc.usuario_id',
                        '=',
                        'cd.usuario_id'
                    );
                }
            )
            ->where('cd.comunicado_id', $comunicado->id)
            ->whereNotNull('cd.usuario_id')
            ->select([
                'u.id',
                'u.nombre_usuario',
                'p.nombres',
                'p.apellidos',
                'lc.leido_at',
            ])
            ->orderBy('p.apellidos')
            ->orderBy('p.nombres')
            ->paginate(20);

        $totalDestinatarios = DB::table(
            'comunicado_destinatarios'
        )
            ->where('comunicado_id', $comunicado->id)
            ->whereNotNull('usuario_id')
            ->count();

        $totalLecturas = DB::table(
            'lecturas_comunicados'
        )
            ->where('comunicado_id', $comunicado->id)
            ->count();

        return view(
            'direccion.comunicados.show',
            compact(
                'comunicado',
                'destinatarios',
                'totalDestinatarios',
                'totalLecturas'
            )
        );
    }

    private function resolverDestinatarios(
        array $grupos,
        array $gradoIds,
        array $estudianteIds,
        array $docenteIds,
        ?int $cicloId
    ): array {
        $ids = [];

        $agregar = function ($nuevos) use (&$ids): void {
            foreach ($nuevos as $id) {
                $id = (int) $id;

                if ($id > 0) {
                    $ids[$id] = $id;
                }
            }
        };

        /*
         * Comunidad educativa:
         * usuarios institucionales activos más padres
         * autorizados del ciclo seleccionado.
         */
        if (in_array('COMUNIDAD', $grupos, true)) {
            $agregar(
                Usuario::query()
                    ->where('activo', true)
                    ->whereHas(
                        'roles',
                        function ($q) {
                            $q->where('activo', true)
                                ->where(
                                    'codigo',
                                    '!=',
                                    'PADRE_FAMILIA'
                                );
                        }
                    )
                    ->pluck('id')
            );
        }

        /*
         * Docentes activos:
         * todos o únicamente los seleccionados.
         */
        if (
            in_array('DOCENTES', $grupos, true)
            || !empty($docenteIds)
        ) {
            $consulta = DB::table('docentes as d')
                ->join(
                    'personal as pe',
                    'pe.id',
                    '=',
                    'd.personal_id'
                )
                ->join(
                    'usuarios as u',
                    'u.persona_id',
                    '=',
                    'pe.persona_id'
                )
                ->where('d.activo', true)
                ->where('pe.activo', true)
                ->where('u.activo', true);

            if (!in_array('DOCENTES', $grupos, true)) {
                $consulta->whereIn('d.id', $docenteIds);
            }

            $agregar(
                $consulta->distinct()->pluck('u.id')
            );
        }

        /*
         * Personal institucional activo.
         */
        if (in_array('PERSONAL', $grupos, true)) {
            $agregar(
                DB::table('personal as pe')
                    ->join(
                        'usuarios as u',
                        'u.persona_id',
                        '=',
                        'pe.persona_id'
                    )
                    ->where('pe.activo', true)
                    ->where('u.activo', true)
                    ->distinct()
                    ->pluck('u.id')
            );
        }

        /*
         * Padres de familia:
         * - responsables activos;
         * - autorización de comunicados;
         * - vínculo vigente;
         * - usuario activo;
         * - inscripción completada en el ciclo;
         * - sin duplicados por hermanos.
         */
        $requierePadres =
            in_array('COMUNIDAD', $grupos, true)
            || in_array('PADRES', $grupos, true)
            || !empty($gradoIds)
            || !empty($estudianteIds);

        if ($requierePadres && $cicloId) {
            $todosPadres =
                in_array('COMUNIDAD', $grupos, true)
                || in_array('PADRES', $grupos, true);

            $consulta = DB::table('inscripciones as i')
                ->join(
                    'estudiante_responsables as er',
                    'er.estudiante_id',
                    '=',
                    'i.estudiante_id'
                )
                ->join(
                    'responsables as r',
                    'r.id',
                    '=',
                    'er.responsable_id'
                )
                ->join(
                    'usuarios as u',
                    'u.persona_id',
                    '=',
                    'r.persona_id'
                )
                ->join(
                    'secciones as s',
                    's.id',
                    '=',
                    'i.seccion_id'
                )
                ->where('i.ciclo_escolar_id', $cicloId)
                ->where('i.estado', 'COMPLETADA')
                ->where(function ($q) {
                    $q->whereNull('i.fecha_fin')
                        ->orWhereDate(
                            'i.fecha_fin',
                            '>=',
                            today()
                        );
                })
                ->where('er.autorizado_comunicados', true)
                ->whereDate(
                    'er.fecha_inicio',
                    '<=',
                    today()
                )
                ->where(function ($q) {
                    $q->whereNull('er.fecha_fin')
                        ->orWhereDate(
                            'er.fecha_fin',
                            '>=',
                            today()
                        );
                })
                ->where('r.activo', true)
                ->where('u.activo', true);

            if (!$todosPadres) {
                $consulta->where(function ($q) use (
                    $gradoIds,
                    $estudianteIds
                ) {
                    if (!empty($gradoIds)) {
                        $q->whereIn(
                            's.grado_id',
                            $gradoIds
                        );
                    }

                    if (!empty($estudianteIds)) {
                        if (!empty($gradoIds)) {
                            $q->orWhereIn(
                                'i.estudiante_id',
                                $estudianteIds
                            );
                        } else {
                            $q->whereIn(
                                'i.estudiante_id',
                                $estudianteIds
                            );
                        }
                    }
                });
            }

            $agregar(
                $consulta->distinct()->pluck('u.id')
            );
        }

        sort($ids, SORT_NUMERIC);

        return array_values($ids);
    }
}
