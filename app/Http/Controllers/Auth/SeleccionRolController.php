<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\AccesoRoles;
use Illuminate\Http\Request;

class SeleccionRolController extends Controller
{
    public function index(Request $request)
    {
        $roles = AccesoRoles::disponibles($request->user());

        if ($roles->isEmpty()) {
            $request->session()->forget('rol_activo_id');

            abort(403, 'No tiene roles habilitados.');
        }

        if ($roles->count() === 1) {
            $rol = $roles->first();

            $request->session()->put('rol_activo_id', $rol->id);

            return redirect()->route(
                AccesoRoles::rutaDashboard($rol->codigo)
            );
        }

        $request->session()->forget('rol_activo_id');

        return view('auth.seleccionar-rol', compact('roles'));
    }

    public function seleccionar(Request $request)
    {
        $datos = $request->validate([
            'rol_id' => ['required', 'integer'],
        ]);

        $roles = AccesoRoles::disponibles($request->user());

        $rol = $roles->firstWhere('id', (int) $datos['rol_id']);

        abort_unless($rol, 403, 'El rol no está autorizado.');

        if ($roles->count() === 1) {
            abort(403, 'No tiene habilitado el cambio de rol.');
        }

        $request->session()->regenerate();

        $request->session()->put('rol_activo_id', $rol->id);

        return redirect()->route(
            AccesoRoles::rutaDashboard($rol->codigo)
        );
    }
}

