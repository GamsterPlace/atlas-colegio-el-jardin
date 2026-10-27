<?php

namespace App\Services;

use App\Models\Usuario;
use Illuminate\Support\Str;
use RuntimeException;

class GeneracionCredencialesUsuarioService
{
    public function nombreUsuario(string $nombres, string $apellidos): string
    {
        $primero = preg_split('/\s+/u', trim($nombres))[0] ?? '';
        $apellido = preg_split('/\s+/u', trim($apellidos))[0] ?? '';
        $base = Str::of($primero.'.'.$apellido)->ascii()->lower()
            ->replaceMatches('/[^a-z0-9.]/', '')->trim('.')->toString();
        $base = substr($base === '' ? 'usuario' : $base, 0, 95);

        for ($i = 0; $i < 10000; $i++) {
            $candidato = $base.str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
            if (!Usuario::query()->where('nombre_usuario', $candidato)->exists()) {
                return $candidato;
            }
        }
        throw new RuntimeException('No se pudo generar un nombre de usuario disponible.');
    }

    public function passwordTemporal(): string
    {
        return Str::password(16, true, true, true, false);
    }
}
