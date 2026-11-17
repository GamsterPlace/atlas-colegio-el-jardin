<?php

namespace App\Http\Controllers\Direccion;

use App\Http\Controllers\Controller;
use App\Models\PreguntaFrecuente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PreguntaFrecuenteController extends Controller
{
    public function index(): View
    {
        $preguntas = PreguntaFrecuente::orderBy('orden')->orderBy('id')->paginate(20);
        return view('direccion.preguntas-frecuentes.index', compact('preguntas'));
    }

    public function create(): View
    {
        return view('direccion.preguntas-frecuentes.formulario', [
            'preguntaFrecuente' => new PreguntaFrecuente(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $this->validar($request);
        $datos['creado_por'] = $request->user()->id;
        $datos['actualizado_por'] = $request->user()->id;
        PreguntaFrecuente::create($datos);

        return redirect()->route('direccion.preguntas-frecuentes.index')
            ->with('success', 'Pregunta frecuente registrada correctamente.');
    }

    public function edit(PreguntaFrecuente $preguntaFrecuente): View
    {
        return view('direccion.preguntas-frecuentes.formulario', compact('preguntaFrecuente'));
    }

    public function update(Request $request, PreguntaFrecuente $preguntaFrecuente): RedirectResponse
    {
        $datos = $this->validar($request);
        $datos['actualizado_por'] = $request->user()->id;
        $preguntaFrecuente->update($datos);

        return redirect()->route('direccion.preguntas-frecuentes.index')
            ->with('success', 'Pregunta frecuente actualizada correctamente.');
    }

    public function destroy(PreguntaFrecuente $preguntaFrecuente): RedirectResponse
    {
        $preguntaFrecuente->delete();
        return redirect()->route('direccion.preguntas-frecuentes.index')
            ->with('success', 'Pregunta frecuente eliminada.');
    }

    private function validar(Request $request): array
    {
        $datos = $request->validate([
            'pregunta' => ['required', 'string', 'min:8', 'max:250'],
            'respuesta' => ['required', 'string', 'min:10', 'max:10000'],
            'categoria' => ['required', 'string', 'max:80'],
            'orden' => ['required', 'integer', 'min:0', 'max:99999'],
            'publicada' => ['nullable', 'boolean'],
            'destacada' => ['nullable', 'boolean'],
        ]);

        $datos['publicada'] = $request->boolean('publicada');
        $datos['destacada'] = $request->boolean('destacada');
        return $datos;
    }
}
