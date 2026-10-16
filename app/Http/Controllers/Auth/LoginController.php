<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Support\AccesoRoles;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre_usuario' => [
                'required',
                'string',
            ],
            'password' => [
                'required',
                'string',
            ],
        ]);

        $clave = Str::lower(
            trim($datos['nombre_usuario'])
        ).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($clave, 5)) {
            $segundos = RateLimiter::availableIn($clave);

            throw ValidationException::withMessages([
                'nombre_usuario' =>
                    "Demasiados intentos. Espere {$segundos} segundos.",
            ]);
        }

        $credenciales = [
            'nombre_usuario' => trim(
                $datos['nombre_usuario']
            ),
            'password' => $datos['password'],
            'activo' => true,
            'deleted_at' => null,
        ];

        if (!Auth::attempt($credenciales)) {
            RateLimiter::hit($clave, 300);

            throw ValidationException::withMessages([
                'nombre_usuario' =>
                    'Las credenciales son incorrectas o la cuenta está deshabilitada.',
            ]);
        }

        $usuario = Auth::user();

        $roles = AccesoRoles::disponibles($usuario);

        if ($roles->isEmpty()) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'nombre_usuario' =>
                    'No tiene roles habilitados para ingresar.',
            ]);
        }

        $request->session()->regenerate();

        $request->session()->forget('rol_activo_id');

        RateLimiter::clear($clave);

        Usuario::query()
            ->whereKey($usuario->id)
            ->update([
                'ultimo_acceso_at' => now(),
            ]);

        if ($usuario->debe_cambiar_password) {
            return redirect()->route(
                'password.obligatorio.edit'
            );
        }

        if ($roles->count() === 1) {
            $rol = $roles->first();

            $request->session()->put(
                'rol_activo_id',
                $rol->id
            );

            return redirect()->route(
                AccesoRoles::rutaDashboard($rol->codigo)
            );
        }

        return redirect()->route(
            'roles.seleccionar'
        );
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
