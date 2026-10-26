<?php

namespace App\Http\Controllers\Institucional;

use App\Http\Controllers\Controller;
use App\Models\Publicacion;
use Illuminate\View\View;

class SitioWebController extends Controller
{
    public function inicio(): View
    {
        return view('institucional.inicio');
    }

    public function nosotros(): View
    {
        return view('institucional.nosotros');
    }

    public function ofertaAcademica(): View
    {
        return view('institucional.oferta-academica');
    }

    public function vidaEstudiantil(): View
    {
        return view('institucional.vida-estudiantil');
    }

    public function noticias(): View
    {
        $publicaciones = Publicacion::query()
            ->where('estado', 'PUBLICADA')
            ->whereNotNull('publicada_at')
            ->where('publicada_at', '<=', now())
            ->with(['registrosArchivoPublicacion' => function ($consulta) {
                $consulta->orderBy('orden')->orderBy('id');
            }])
            ->orderByDesc('destacada')
            ->orderByDesc('publicada_at')
            ->orderByDesc('id')
            ->get()
            ->map(function (Publicacion $publicacion): array {
                $archivos = $publicacion->registrosArchivoPublicacion;

                $imagenes = $archivos
                    ->filter(fn ($archivo) => $archivo->tipo === 'FOTOGRAFIA')
                    ->map(fn ($archivo) => [
                        'url' => asset(ltrim($archivo->ruta_archivo, '/')),
                    ])
                    ->values()
                    ->all();

                $videos = $archivos
                    ->filter(fn ($archivo) => $archivo->tipo === 'VIDEO')
                    ->map(fn ($archivo) => asset(ltrim($archivo->ruta_archivo, '/')))
                    ->values()
                    ->all();

                return [
                    'id' => $publicacion->id,
                    'titulo' => $publicacion->titulo,
                    'contenido' => $publicacion->contenido,
                    'categoria' => $publicacion->categoria ?: 'Institucional',
                    'fecha_publicacion' => $publicacion->publicada_at,
                    'imagenes' => $imagenes,
                    'video_url' => $videos[0] ?? null,
                    'videos' => $videos,
                    'destacado' => (bool) $publicacion->destacada,
                    'slug' => $publicacion->slug,
                ];
            });

        return view('institucional.noticias', compact('publicaciones'));
    }

    public function contacto(): View
    {
        return view('institucional.contacto');
    }
}
