<?php

namespace App\Services;

use App\Models\ArchivoPublicacion;
use App\Models\Publicacion;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class PublicacionMultimediaService
{
    private const DIRECTORIO_FOTOGRAFIAS = 'images/publicaciones/fotografias';
    private const DIRECTORIO_VIDEOS = 'images/publicaciones/videos';

    private const FORMATOS = [
        'FOTOGRAFIA' => ['image/jpeg', 'image/png', 'image/webp'],
        'VIDEO' => ['video/mp4', 'video/webm', 'video/quicktime'],
    ];

    /**
     * Almacena el archivo en public/ y crea su registro en archivos_publicaciones.
     * Los límites predeterminados son 25 MiB por fotografía y 200 MiB por video.
     */
    public function guardar(Publicacion $publicacion, UploadedFile $archivo, int $orden = 0): ArchivoPublicacion
    {
        if (! $publicacion->exists) {
            throw new RuntimeException('La publicación debe estar guardada antes de adjuntar archivos.');
        }

        if (! $archivo->isValid()) {
            throw ValidationException::withMessages([
                'archivos' => 'No se pudo recibir correctamente el archivo multimedia.',
            ]);
        }

        $mime = $archivo->getMimeType();
        $tipo = $this->identificarTipo($mime);

        if ($tipo === null) {
            throw ValidationException::withMessages([
                'archivos' => 'Formato no admitido. Fotografías: JPG, PNG o WebP. Videos: MP4, WebM o MOV.',
            ]);
        }

        $limite = $tipo === 'VIDEO' ? 200 * 1024 * 1024 : 25 * 1024 * 1024;

        if ($archivo->getSize() > $limite) {
            throw ValidationException::withMessages([
                'archivos' => $tipo === 'VIDEO'
                    ? 'Cada video debe ocupar como máximo 200 MiB.'
                    : 'Cada fotografía debe ocupar como máximo 25 MiB.',
            ]);
        }

        $directorioRelativo = $tipo === 'VIDEO'
            ? self::DIRECTORIO_VIDEOS
            : self::DIRECTORIO_FOTOGRAFIAS;

        $directorioAbsoluto = public_path($directorioRelativo);
        File::ensureDirectoryExists($directorioAbsoluto, 0755, true);

        $extension = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'video/mp4' => 'mp4',
            'video/webm' => 'webm',
            'video/quicktime' => 'mov',
        };

        $nombreGenerado = (string) Str::uuid() . '.' . $extension;
        $rutaRelativa = $directorioRelativo . '/' . $nombreGenerado;
        $archivo->move($directorioAbsoluto, $nombreGenerado);

        try {
            return $publicacion->registrosArchivoPublicacion()->create([
                'ruta_archivo' => $rutaRelativa,
                'nombre_original' => mb_substr($archivo->getClientOriginalName(), 0, 190),
                'tipo' => $tipo,
                'orden' => $orden,
            ]);
        } catch (\Throwable $e) {
            File::delete(public_path($rutaRelativa));
            throw $e;
        }
    }

    /** Elimina únicamente archivos registrados dentro de los directorios autorizados. */
    public function eliminar(ArchivoPublicacion $archivo): void
    {
        $ruta = str_replace('\\', '/', $archivo->ruta_archivo);
        $permitida = str_starts_with($ruta, self::DIRECTORIO_FOTOGRAFIAS . '/')
            || str_starts_with($ruta, self::DIRECTORIO_VIDEOS . '/');

        if (! $permitida || str_contains($ruta, '..')) {
            throw new RuntimeException('La ruta del archivo no pertenece a Publicaciones.');
        }

        $archivo->delete();
        File::delete(public_path($ruta));
    }

    private function identificarTipo(?string $mime): ?string
    {
        foreach (self::FORMATOS as $tipo => $formatos) {
            if (in_array($mime, $formatos, true)) {
                return $tipo;
            }
        }

        return null;
    }
}
