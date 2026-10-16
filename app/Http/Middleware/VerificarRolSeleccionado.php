<?php

namespace App\Http\Middleware;

use App\Support\AccesoRoles;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarRolSeleccionado
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        if (!AccesoRoles::rolActivo()) {
            $request->session()->forget('rol_activo_id');

            return redirect()->route('roles.seleccionar');
        }

        return $next($request);
    }
}
