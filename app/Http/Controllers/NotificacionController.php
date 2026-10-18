<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificacionController extends Controller
{
    public function index(Request $request): View
    {
        $notificaciones = Notificacion::query()
            ->where('usuario_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->paginate(20);

        $pendientes = Notificacion::query()
            ->where('usuario_id', $request->user()->id)
            ->whereNull('leida_at')
            ->count();

        return view('notificaciones.index', compact('notificaciones', 'pendientes'));
    }

    public function leer(Request $request, Notificacion $notificacion): RedirectResponse
    {
        abort_unless($notificacion->usuario_id === $request->user()->id, 403);
        if (!$notificacion->leida_at) {
            $notificacion->update(['leida_at' => now()]);
        }
        return back()->with('success', 'Notificación marcada como leída.');
    }

    public function leerTodas(Request $request): RedirectResponse
    {
        Notificacion::query()->where('usuario_id', $request->user()->id)
            ->whereNull('leida_at')->update(['leida_at' => now()]);
        return back()->with('success', 'Todas las notificaciones se marcaron como leídas.');
    }
}
