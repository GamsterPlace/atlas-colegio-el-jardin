<?php

namespace App\Services;

use App\Models\Publicacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Reglas de negocio compartidas por Dirección y Secretaría.
 * Los controladores son responsables de autenticar, autorizar y validar la solicitud.
 */
class PublicacionService
{
    public const CATEGORIAS = [
        'Institucional',
        'Actividades',
        'Eventos',
        'Proyectos',
        'Logros',
    ];

    public const ESTADOS = ['BORRADOR', 'PUBLICADA', 'ARCHIVADA'];

    /** @param array<string, mixed> $datos Datos previamente validados. */
    public function crear(array $datos, int $autorId): Publicacion
    {
        return DB::transaction(function () use ($datos, $autorId): Publicacion {
            $this->verificarCategoria($datos['categoria'] ?? null);
            $estado = $datos['estado'] ?? 'BORRADOR';
            $this->verificarEstado($estado);

            $publicacion = new Publicacion();
            $publicacion->titulo = $datos['titulo'];
            $publicacion->slug = $this->generarSlugUnico($datos['titulo']);
            $publicacion->resumen = $datos['resumen'] ?? null;
            $publicacion->contenido = $datos['contenido'];
            $publicacion->categoria = $datos['categoria'];
            $publicacion->destacada = (bool) ($datos['destacada'] ?? false);
            $publicacion->estado = $estado;
            $publicacion->publicada_at = $estado === 'PUBLICADA' ? now() : null;
            $publicacion->autor_id = $autorId;
            $publicacion->save();

            return $publicacion;
        });
    }

    /** @param array<string, mixed> $datos Datos previamente validados. */
    public function actualizar(Publicacion $publicacion, array $datos): Publicacion
    {
        return DB::transaction(function () use ($publicacion, $datos): Publicacion {
            if (array_key_exists('categoria', $datos)) {
                $this->verificarCategoria($datos['categoria']);
            }

            foreach (['titulo', 'resumen', 'contenido', 'categoria', 'destacada'] as $campo) {
                if (array_key_exists($campo, $datos)) {
                    $publicacion->{$campo} = $campo === 'destacada'
                        ? (bool) $datos[$campo]
                        : $datos[$campo];
                }
            }

            // Mantener el slug estable preserva los enlaces públicos ya compartidos.
            $publicacion->save();

            return $publicacion->refresh();
        });
    }

    public function publicar(Publicacion $publicacion): Publicacion
    {
        return DB::transaction(function () use ($publicacion): Publicacion {
            if ($publicacion->estado !== 'PUBLICADA') {
                $publicacion->estado = 'PUBLICADA';
                $publicacion->publicada_at = now();
                $publicacion->save();
            }

            return $publicacion->refresh();
        });
    }

    public function archivar(Publicacion $publicacion): Publicacion
    {
        return DB::transaction(function () use ($publicacion): Publicacion {
            $publicacion->estado = 'ARCHIVADA';
            $publicacion->save();

            return $publicacion->refresh();
        });
    }

    public function guardarBorrador(Publicacion $publicacion): Publicacion
    {
        return DB::transaction(function () use ($publicacion): Publicacion {
            $publicacion->estado = 'BORRADOR';
            $publicacion->save();

            return $publicacion->refresh();
        });
    }

    private function verificarCategoria(mixed $categoria): void
    {
        if (! is_string($categoria) || ! in_array($categoria, self::CATEGORIAS, true)) {
            throw ValidationException::withMessages([
                'categoria' => 'Selecciona una categoría válida.',
            ]);
        }
    }

    private function verificarEstado(mixed $estado): void
    {
        if (! is_string($estado) || ! in_array($estado, self::ESTADOS, true)) {
            throw ValidationException::withMessages([
                'estado' => 'El estado de la publicación no es válido.',
            ]);
        }
    }

    private function generarSlugUnico(string $titulo): string
    {
        $base = Str::slug($titulo);
        $base = Str::limit($base !== '' ? $base : 'noticia', 155, '');
        $slug = $base;
        $contador = 2;

        while (Publicacion::query()->where('slug', $slug)->exists()) {
            $sufijo = '-' . $contador++;
            $slug = Str::limit($base, 160 - strlen($sufijo), '') . $sufijo;
        }

        return $slug;
    }
}
