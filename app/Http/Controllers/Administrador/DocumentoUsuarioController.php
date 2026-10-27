<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class DocumentoUsuarioController extends Controller
{
    public function credenciales(Request $request, Usuario $usuario): Response
    {
        $clave = 'atlas:admin:credenciales:'.$request->user()->id.':'.$usuario->id;
        $cifrado = Cache::get($clave);
        abort_unless($cifrado, 404, 'Las credenciales ya fueron entregadas o vencieron. Restablezca la contraseña si necesita una nueva ficha.');
        $password = Crypt::decryptString($cifrado);
        $usuario->load(['persona', 'roles']);
        $pdf = Pdf::loadView('administrador.usuarios.pdf.credenciales', compact('usuario', 'password'))
            ->setPaper('letter');
        $respuesta = $pdf->stream('credenciales-atlas-'.$usuario->id.'.pdf');
        Cache::forget($clave);
        $respuesta->headers->set('Cache-Control', 'private, no-store, no-cache, max-age=0');
        $respuesta->headers->set('Pragma', 'no-cache');
        return $respuesta;
    }
}
