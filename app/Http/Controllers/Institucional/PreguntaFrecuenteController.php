<?php

namespace App\Http\Controllers\Institucional;

use App\Http\Controllers\Controller;
use App\Models\PreguntaFrecuente;
use Illuminate\View\View;

class PreguntaFrecuenteController extends Controller
{
    public function index(): View
    {
        $preguntas = PreguntaFrecuente::publicadas()
            ->orderBy('orden')
            ->orderBy('id')
            ->get();

        $categorias = $preguntas->groupBy('categoria');

        return view('institucional.preguntas-frecuentes', compact('categorias', 'preguntas'));
    }
}
