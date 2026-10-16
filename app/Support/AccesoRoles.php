<?php

namespace App\Support;

use App\Models\Usuario;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class AccesoRoles
{
    private const DASHBOARDS = [
        'DIRECCION' => 'direccion.dashboard',
        'ADMINISTRADOR' => 'administrador.dashboard',
        'SECRETARIA' => 'secretaria.dashboard',
        'DOCENTE' => 'docente.dashboard',
        'PADRE_FAMILIA' => 'padre-familia.dashboard',
    ];

    public static function disponibles(Usuario $usuario): Collection
    {
        return $usuario->roles()
            ->where('roles.activo', true)
            ->get()
            ->filter(function ($rol) {
                return array_key_exists(
                    strtoupper($rol->codigo),
                    self::DASHBOARDS
                );
            })
            ->values();
    }

    public static function rutaDashboard(string $codigo): string
    {
        $codigo = strtoupper($codigo);

        abort_unless(
            isset(self::DASHBOARDS[$codigo]),
            403,
            'Rol no reconocido.'
        );

        return self::DASHBOARDS[$codigo];
    }

    public static function rolActivo(): mixed
    {
        $usuario = Auth::user();

        if (!$usuario instanceof Usuario) {
            return null;
        }

        $rolId = session('rol_activo_id');

        if (!$rolId) {
            return null;
        }

        return self::disponibles($usuario)
            ->firstWhere('id', (int) $rolId);
    }
}
