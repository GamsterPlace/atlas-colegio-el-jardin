<?php

namespace App\Http\Middleware;

use App\Support\AccesoRoles;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarRol
{
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {
        $rolActivo = AccesoRoles::rolActivo();

        if (!$rolActivo) {
            $request->session()->forget('rol_activo_id');

            return redirect()->route('roles.seleccionar');
        }

        $permitidos = array_map(
            fn ($rol) => strtoupper(trim($rol)),
            $roles
        );

        abort_unless(
            in_array(strtoupper($rolActivo->codigo), $permitidos, true),
            403,
            'No tiene autorización para acceder con el rol seleccionado.'
        );

        return $next($request);
    }
}
