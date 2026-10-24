<?php

namespace App\Http\Controllers;

use App\Models\Comunicado;
use App\Models\LecturaComunicado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ComunicadoRecibidoController extends Controller
{
    /**
     * Mostrar los comunicados recibidos por el usuario autenticado.
     */
    public function index(Request $request): View
    {
        $usuarioId = (int) $request->user()->id;

        $filtros = $request->validate([
            'buscar' => ['nullable', 'string', 'max:150'],
            'estado_lectura' => [
                'nullable',
                'in:TODOS,LEIDOS,PENDIENTES',
            ],
        ]);

        $estadoLectura = $filtros['estado_lectura'] ?? 'TODOS';

        $consulta = Comunicado::query()
            ->where('estado', 'PUBLICADO')
            ->whereHas(
                'registrosComunicadoDestinatario',
                function ($query) use ($usuarioId) {
                    $query->where('usuario_id', $usuarioId);
                }
            )
            ->with('creado_por.persona')
            ->withExists([
                'registrosLecturaComunicado as fue_leido' =>
                    function ($query) use ($usuarioId) {
                        $query->where('usuario_id', $usuarioId);
                    },
            ])
            ->when(
                $filtros['buscar'] ?? null,
                function ($query, $buscar) {
                    $query->where(function ($q) use ($buscar) {
                        $q->where(
                            'titulo',
                            'like',
                            '%' . $buscar . '%'
                        )->orWhere(
                            'contenido',
                            'like',
                            '%' . $buscar . '%'
                        );
                    });
                }
            );

        if ($estadoLectura === 'LEIDOS') {
            $consulta->whereHas(
                'registrosLecturaComunicado',
                fn ($query) =>
                    $query->where('usuario_id', $usuarioId)
            );
        }

        if ($estadoLectura === 'PENDIENTES') {
            $consulta->whereDoesntHave(
                'registrosLecturaComunicado',
                fn ($query) =>
                    $query->where('usuario_id', $usuarioId)
            );
        }

        $comunicados = $consulta
            ->orderByDesc('publicado_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $base = Comunicado::query()
            ->where('estado', 'PUBLICADO')
            ->whereHas(
                'registrosComunicadoDestinatario',
                fn ($query) =>
                    $query->where('usuario_id', $usuarioId)
            );

        $totalComunicados = (clone $base)->count();

        $totalLeidos = (clone $base)
            ->whereHas(
                'registrosLecturaComunicado',
                fn ($query) =>
                    $query->where('usuario_id', $usuarioId)
            )
            ->count();

        $totalPendientes = max(
            0,
            $totalComunicados - $totalLeidos
        );

        return view(
            'comunicados.recibidos',
            compact(
                'comunicados',
                'filtros',
                'totalComunicados',
                'totalLeidos',
                'totalPendientes'
            )
        );
    }

    /**
     * Mostrar un comunicado recibido y registrar
     * su primera lectura.
     */
    public function show(
        Request $request,
        Comunicado $comunicado
    ): View {
        $usuarioId = (int) $request->user()->id;

        /*
         * La publicación debe estar activa.
         */
        abort_unless(
            $comunicado->estado === 'PUBLICADO',
            404
        );

        /*
         * La asignación individual es obligatoria.
         * No basta con pertenecer a un rol o grupo.
         */
        $esDestinatario = DB::table(
            'comunicado_destinatarios'
        )
            ->where('comunicado_id', $comunicado->id)
            ->where('usuario_id', $usuarioId)
            ->exists();

        abort_unless($esDestinatario, 403);

        /*
         * Registrar la lectura únicamente si todavía
         * no existe un registro para este usuario.
         *
         * lockForUpdate protege las operaciones
         * concurrentes dentro de la transacción.
         */
        DB::transaction(function () use (
            $comunicado,
            $usuarioId
        ): void {
            DB::table('comunicados')
                ->where('id', $comunicado->id)
                ->lockForUpdate()
                ->first();

            $lecturaExistente = LecturaComunicado::query()
                ->where('comunicado_id', $comunicado->id)
                ->where('usuario_id', $usuarioId)
                ->exists();

            if (!$lecturaExistente) {
                LecturaComunicado::create([
                    'comunicado_id' => $comunicado->id,
                    'usuario_id' => $usuarioId,
                    'leido_at' => now(),
                ]);
            }
        });

        /*
         * Cargar el autor para mostrarlo en la vista.
         */
        $comunicado->load('creado_por.persona');

        /*
         * Recuperar la fecha original de lectura.
         * Las visitas posteriores no la modificarán.
         */
        $lectura = LecturaComunicado::query()
            ->where('comunicado_id', $comunicado->id)
            ->where('usuario_id', $usuarioId)
            ->first();

        return view(
            'comunicados.ver',
            compact('comunicado', 'lectura')
        );
    }
}
