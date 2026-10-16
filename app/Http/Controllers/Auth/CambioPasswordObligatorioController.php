<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\AccesoRoles;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class CambioPasswordObligatorioController extends Controller
{
    public function edit(): View
    {
        return view('auth.password-obligatorio');
    }

    public function update(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'password_actual' => [
                'required',
                'current_password',
            ],
            'password' => [
                'required',
                'confirmed',
                'different:password_actual',
                Password::min(12)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols(),
            ],
        ]);

        $usuario = $request->user();

        DB::transaction(function () use ($usuario, $datos) {
            $usuario->forceFill([
                'password' => $datos['password'],
                'debe_cambiar_password' => false,
            ])->save();
        });

        $request->session()->regenerate();

        $request->session()->forget('rol_activo_id');

        $roles = AccesoRoles::disponibles($usuario);

        if ($roles->isEmpty()) {
            return redirect()
                ->route('dashboard')
                ->with(
                    'success',
                    'Tu contraseña fue actualizada correctamente.'
                );
        }

        if ($roles->count() === 1) {
            $rol = $roles->first();

            $request->session()->put(
                'rol_activo_id',
                $rol->id
            );

            return redirect()
                ->route(
                    AccesoRoles::rutaDashboard($rol->codigo)
                )
                ->with(
                    'success',
                    'Tu contraseña fue actualizada correctamente.'
                );
        }

        return redirect()
            ->route('roles.seleccionar')
            ->with(
                'success',
                'Tu contraseña fue actualizada correctamente.'
            );
    }
}
