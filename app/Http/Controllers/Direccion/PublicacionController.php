<?php

namespace App\Http\Controllers\Direccion;

use App\Http\Controllers\Controller;
use App\Models\ArchivoPublicacion;
use App\Models\Publicacion;
use App\Services\PublicacionMultimediaService;
use App\Services\PublicacionService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

/**
 * Administración de noticias para Dirección.
 * Debe registrarse dentro del grupo de rutas protegido por rol:DIRECCION.
 */
class PublicacionController extends Controller
{
    public function __construct(
        private readonly PublicacionService $publicaciones,
        private readonly PublicacionMultimediaService $multimedia,
    ) {}

    public function index(Request $request): View
    {
        $estado = $request->query('estado');
        $categoria = $request->query('categoria');

        $registros = Publicacion::query()
            ->withCount('registrosArchivoPublicacion')
            ->when(in_array($estado, PublicacionService::ESTADOS, true),
                fn ($consulta) => $consulta->where('estado', $estado))
            ->when(in_array($categoria, PublicacionService::CATEGORIAS, true),
                fn ($consulta) => $consulta->where('categoria', $categoria))
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('direccion.publicaciones.index', [
            'publicaciones' => $registros,
            'categorias' => PublicacionService::CATEGORIAS,
            'estados' => PublicacionService::ESTADOS,
        ]);
    }

    public function create(): View
    {
        return view('direccion.publicaciones.create', [
            'categorias' => PublicacionService::CATEGORIAS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $this->validarDatos($request);
        $archivos = $request->file('archivos', []);
        $rutasGuardadas = [];

        try {
            $publicacion = DB::transaction(function () use ($datos, $archivos, &$rutasGuardadas) {
                $publicacion = $this->publicaciones->crear($datos, (int) auth::id());

                foreach ($archivos as $indice => $archivo) {
                    $registro = $this->multimedia->guardar($publicacion, $archivo, $indice);
                    $rutasGuardadas[] = $registro->ruta_archivo;
                }

                return $publicacion;
            });
        } catch (\Throwable $e) {
            $this->limpiarArchivosFallidos($rutasGuardadas);
            throw $e;
        }

        return redirect()->route('direccion.publicaciones.edit', $publicacion)
            ->with('success', 'La publicación se guardó correctamente.');
    }

    public function edit(Publicacion $publicacion): View
    {
        $publicacion->load(['registrosArchivoPublicacion' => fn ($consulta) => $consulta->orderBy('orden')->orderBy('id')]);

        return view('direccion.publicaciones.edit', [
            'publicacion' => $publicacion,
            'categorias' => PublicacionService::CATEGORIAS,
        ]);
    }

    public function update(Request $request, Publicacion $publicacion): RedirectResponse
    {
        $datos = $this->validarDatos($request);
        $archivos = $request->file('archivos', []);
        $rutasGuardadas = [];

        try {
            DB::transaction(function () use ($publicacion, $datos, $archivos, &$rutasGuardadas) {
                $this->publicaciones->actualizar($publicacion, $datos);
                $orden = ((int) $publicacion->registrosArchivoPublicacion()->max('orden')) + 1;

                foreach ($archivos as $archivo) {
                    $registro = $this->multimedia->guardar($publicacion, $archivo, $orden++);
                    $rutasGuardadas[] = $registro->ruta_archivo;
                }
            });
        } catch (\Throwable $e) {
            $this->limpiarArchivosFallidos($rutasGuardadas);
            throw $e;
        }

        return back()->with('success', 'La publicación se actualizó correctamente.');
    }

    public function publicar(Publicacion $publicacion): RedirectResponse
    {
        $this->publicaciones->publicar($publicacion);
        return back()->with('success', 'La noticia ya está publicada.');
    }

    public function archivar(Publicacion $publicacion): RedirectResponse
    {
        $this->publicaciones->archivar($publicacion);
        return back()->with('success', 'La noticia fue retirada del sitio web.');
    }

    public function borrador(Publicacion $publicacion): RedirectResponse
    {
        $this->publicaciones->guardarBorrador($publicacion);
        return back()->with('success', 'La noticia se guardó como borrador.');
    }

    public function eliminarArchivo(Publicacion $publicacion, ArchivoPublicacion $archivo): RedirectResponse
    {
        abort_unless($archivo->publicacion_id === $publicacion->id, 404);
        $this->multimedia->eliminar($archivo);
        return back()->with('success', 'El archivo multimedia fue eliminado.');
    }

    private function validarDatos(Request $request): array
    {
        $datos = $request->validate([
            'titulo' => ['required', 'string', 'max:190'],
            'resumen' => ['nullable', 'string', 'max:2000'],
            'contenido' => ['required', 'string'],
            'categoria' => ['required', Rule::in(PublicacionService::CATEGORIAS)],
            'destacada' => ['sometimes', 'boolean'],
            'archivos' => ['sometimes', 'array'],
            'archivos.*' => ['required', 'file', 'max:204800', 'mimetypes:image/jpeg,image/png,image/webp,video/mp4,video/webm,video/quicktime'],
        ]);

        $datos['destacada'] = $request->boolean('destacada');
        unset($datos['archivos']);
        return $datos;
    }

    private function limpiarArchivosFallidos(array $rutas): void
    {
        foreach ($rutas as $ruta) {
            if (is_string($ruta) && preg_match('~^images/publicaciones/(fotografias|videos)/[a-f0-9-]+\.(jpg|png|webp|mp4|webm|mov)$~', $ruta)) {
                \Illuminate\Support\Facades\File::delete(public_path($ruta));
            }
        }
    }
}
