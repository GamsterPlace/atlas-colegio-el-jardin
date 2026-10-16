<?php

namespace App\Http\Controllers\Institucional;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class SitioWebController extends Controller
{
    public function inicio(): View
    {
        return view('institucional.inicio');
    }

    public function nosotros(): View
    {
        return view('institucional.nosotros');
    }

    public function ofertaAcademica(): View
    {
        return view('institucional.oferta-academica');
    }

    public function vidaEstudiantil(): View
    {
        return view('institucional.vida-estudiantil');
    }

    public function noticias(): View
    {
        return view('institucional.noticias');
    }

    public function contacto(): View
    {
        return view('institucional.contacto');
    }
}
