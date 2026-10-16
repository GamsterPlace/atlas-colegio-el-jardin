<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarCambioPassword
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $usuario = $request->user();

        if (!$usuario) {
            return $next($request);
        }

        if (!$usuario->debe_cambiar_password) {
            return $next($request);
        }

        if (
            $request->routeIs(
                'password.obligatorio.edit',
                'password.obligatorio.update',
                'logout'
            )
        ) {
            return $next($request);
        }

        return redirect()->route('password.obligatorio.edit');
    }
}
