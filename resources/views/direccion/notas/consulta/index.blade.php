@extends('layouts.direccion')
@section('title','Consulta de calificaciones')
@section('role','Dirección')
@section('module-title','Notas · Consulta')
@section('dashboard-url',route('direccion.dashboard'))
@section('content')
@include('direccion.notas.partials.estilos')
@include('direccion.notas.consulta.boleta-estilos')
@php
    $nivelesDisponibles = $grados->filter(fn ($grado) => $grado->nivel_id !== null)
        ->unique('nivel_id')
        ->sortBy(fn ($grado) => $grado->nivel?->nombre ?? '')
        ->values();
    $gradoSeleccionado = $grados->firstWhere('id', $gradoId);
    $nivelInicial = old('nivel_id', request('nivel_id', $gradoSeleccionado?->nivel_id));
@endphp
<div class="container-fluid py-3 atlas-notas">
    <div class="ai-header">
        <div>
            <h2>Consulta de calificaciones</h2>
            <p class="ai-muted">Seleccione ciclo escolar, nivel, grado, sección y estudiante para visualizar e imprimir su boleta.</p>
        </div>
    </div>

    <div class="ai-card">
        <div class="ai-card-head"><strong>Selección académica</strong></div>
        <div class="ai-card-body">
            <form method="GET" action="{{ route('direccion.notas.index') }}" id="filtrosBoleta" class="ai-filtros-boleta">
                <div class="ai-field">
                    <label for="ciclo">Ciclo escolar</label>
                    <select id="ciclo" name="ciclo_escolar_id" required>
                        @foreach($ciclos as $ciclo)
                            <option value="{{ $ciclo->id }}" @selected((string)$cicloId === (string)$ciclo->id)>{{ $ciclo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ai-field">
                    <label for="nivel">Nivel educativo</label>
                    <select id="nivel" name="nivel_id" required>
                        <option value="">Seleccione nivel</option>
                        @foreach($nivelesDisponibles as $gradoNivel)
                            <option value="{{ $gradoNivel->nivel_id }}" @selected((string)$nivelInicial === (string)$gradoNivel->nivel_id)>{{ $gradoNivel->nivel?->nombre ?? 'Nivel '.$gradoNivel->nivel_id }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ai-field">
                    <label for="grado">Grado</label>
                    <select id="grado" name="grado_id" required disabled>
                        <option value="">Seleccione grado</option>
                        @foreach($grados as $grado)
                            <option value="{{ $grado->id }}" data-nivel="{{ $grado->nivel_id }}" @selected((string)$gradoId === (string)$grado->id)>{{ $grado->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ai-field">
                    <label for="seccion">Sección</label>
                    <select id="seccion" name="seccion_id" required disabled>
                        <option value="">Seleccione sección</option>
                        @foreach($secciones as $seccion)
                            <option value="{{ $seccion->id }}" data-grado="{{ $seccion->grado_id }}" data-ciclo="{{ $seccion->ciclo_escolar_id }}" @selected((string)$seccionId === (string)$seccion->id)>{{ $seccion->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    @if($seccionId)
        <div class="ai-card">
            <div class="ai-card-head">
                <strong>Estudiantes de la sección</strong>
                <span class="ai-count">{{ $inscripciones->count() }} estudiantes</span>
            </div>
            <div class="ai-table-wrap">
                <table class="ai-table">
                    <thead><tr><th>No.</th><th>Estudiante</th><th>Acción</th></tr></thead>
                    <tbody>
                        @forelse($inscripciones as $i)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $i->estudiante?->apellidos }}, {{ $i->estudiante?->nombres }}</td>
                                <td>
                                    <a class="ai-btn {{ (string)$inscripcionId === (string)$i->id ? 'primary' : '' }}" href="{{ route('direccion.notas.index', ['ciclo_escolar_id'=>$cicloId, 'nivel_id'=>$nivelInicial, 'grado_id'=>$gradoId, 'seccion_id'=>$seccionId, 'inscripcion_id'=>$i->id]) }}">
                                        <i class="bi bi-eye"></i> Ver boleta
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="ai-empty">No hay estudiantes inscritos en esta sección.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @if($boleta)
        <div class="ai-card">
            <div class="ai-card-head">
                <strong>Vista previa de boleta</strong>
                <a class="ai-btn primary" href="{{ route('direccion.notas.boleta.pdf', $boleta['inscripcion']->id) }}" target="_blank" rel="noopener">
                    <i class="bi bi-printer"></i> Imprimir boleta PDF
                </a>
            </div>
            <div class="ai-preview-boleta">@include('direccion.notas.consulta.boleta', $boleta)</div>
        </div>
    @endif
</div>
<style>
.ai-filtros-boleta{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:15px}
.ai-filtros-boleta select:disabled{opacity:.6;cursor:not-allowed;background-color:#f2f5f9}
.ai-preview-boleta{max-width:850px;margin:20px auto;border:1px solid #dce4ee;box-shadow:0 6px 20px #142b4410}
.ai-preview-boleta .boleta-documento{padding:22px}
@media(max-width:1000px){.ai-filtros-boleta{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media(max-width:700px){.ai-filtros-boleta{grid-template-columns:1fr}.ai-preview-boleta{overflow-x:auto}.ai-preview-boleta .boleta-documento{min-width:650px}}
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('filtrosBoleta');
    const ciclo = document.getElementById('ciclo');
    const nivel = document.getElementById('nivel');
    const grado = document.getElementById('grado');
    const seccion = document.getElementById('seccion');

    function filtrarOpciones(select, permitido) {
        Array.from(select.options).forEach(function (opcion) {
            if (!opcion.value) return;
            const visible = permitido(opcion);
            opcion.hidden = !visible;
            opcion.disabled = !visible;
        });
        if (select.selectedOptions[0]?.disabled) select.value = '';
    }

    function actualizarGrados() {
        grado.disabled = !nivel.value;
        filtrarOpciones(grado, opcion => opcion.dataset.nivel === nivel.value);
    }

    function actualizarSecciones() {
        seccion.disabled = !grado.value;
        filtrarOpciones(seccion, opcion => opcion.dataset.grado === grado.value && opcion.dataset.ciclo === ciclo.value);
    }

    ciclo.addEventListener('change', function () {
        nivel.value = '';
        grado.value = '';
        seccion.value = '';
        actualizarGrados();
        actualizarSecciones();
        form.submit();
    });
    nivel.addEventListener('change', function () {
        grado.value = '';
        seccion.value = '';
        actualizarGrados();
        actualizarSecciones();
    });
    grado.addEventListener('change', function () {
        seccion.value = '';
        actualizarSecciones();
    });
    seccion.addEventListener('change', function () {
        if (seccion.value) form.submit();
    });

    actualizarGrados();
    actualizarSecciones();
});
</script>
@endsection
