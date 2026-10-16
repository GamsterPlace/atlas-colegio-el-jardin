@php
    $nivelSeleccionado = (string) request('nivel_id', '');
    $gradoSeleccionado = (string) request('grado_id', '');
    $seccionSeleccionada = (string) ($seccionId ?? '');
    $materiaSeleccionada = (string) ($materiaId ?? '');
    $periodoSeleccionado = (string) ($periodoId ?? '');

    $gradosDisponibles = $secciones->map(fn ($seccion) => $seccion->grado)
        ->filter()->unique('id')->sortBy('nombre')->values();
    $nivelesDisponibles = $gradosDisponibles->map(fn ($grado) => $grado->nivel)
        ->filter()->unique('id')->sortBy('id')->values();

    if ($gradoSeleccionado === '' && $seccionSeleccionada !== '') {
        $gradoSeleccionado = (string) optional($secciones->firstWhere('id', $seccionSeleccionada))->grado_id;
    }
    if ($nivelSeleccionado === '' && $gradoSeleccionado !== '') {
        $nivelSeleccionado = (string) optional($gradosDisponibles->firstWhere('id', $gradoSeleccionado))->nivel_id;
    }
@endphp
<form method="GET" class="ai-card" action="{{ url()->current() }}" id="filtrosAcademicosNotas">
    <div class="ai-card-body">
        <div class="ai-filters">
            <div class="ai-field">
                <label for="ciclo">Ciclo escolar</label>
                <select name="ciclo_escolar_id" id="ciclo" required>
                    @foreach($ciclos as $ciclo)
                        <option value="{{ $ciclo->id }}" @selected((string) $cicloId === (string) $ciclo->id)>{{ $ciclo->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="ai-field">
                <label for="nivel_notas">Nivel educativo</label>
                <select name="nivel_id" id="nivel_notas" required>
                    <option value="">Seleccione nivel</option>
                    @foreach($nivelesDisponibles as $nivel)
                        <option value="{{ $nivel->id }}" @selected($nivelSeleccionado === (string) $nivel->id)>{{ $nivel->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="ai-field">
                <label for="grado_notas">Grado</label>
                <select name="grado_id" id="grado_notas" required disabled>
                    <option value="">Seleccione grado</option>
                    @foreach($gradosDisponibles as $grado)
                        <option value="{{ $grado->id }}" data-nivel="{{ $grado->nivel_id }}" @selected($gradoSeleccionado === (string) $grado->id)>{{ $grado->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="ai-field">
                <label for="seccion">Sección</label>
                <select name="seccion_id" id="seccion" required disabled>
                    <option value="">Seleccione sección</option>
                    @foreach($secciones as $seccion)
                        <option value="{{ $seccion->id }}" data-grado="{{ $seccion->grado_id }}" @selected($seccionSeleccionada === (string) $seccion->id)>{{ $seccion->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="ai-field">
                <label for="materia">Materia</label>
                <select name="carga_academica_id" id="materia" disabled>
                    <option value="">Todas las materias</option>
                    @foreach($materias as $m)
                        <option value="{{ $m->id }}" data-seccion="{{ $m->seccion_id }}" @selected($materiaSeleccionada === (string) $m->id)>{{ $m->pensum_curso?->curso?->nombre ?? 'Materia' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="ai-field">
                <label for="periodo">Bimestre</label>
                <select name="periodo_academico_id" id="periodo" disabled>
                    <option value="">Todos los bimestres</option>
                    @foreach($periodos as $p)
                        <option value="{{ $p->id }}" @selected($periodoSeleccionado === (string) $p->id)>{{ $p->nombre }}</option>
                    @endforeach
                </select>
            </div>
            @if($conBusqueda ?? false)
                <div class="ai-field">
                    <label for="buscar">Estudiante</label>
                    <input name="buscar" id="buscar" value="{{ $filtros['buscar'] ?? '' }}" placeholder="Nombre, código o carné">
                </div>
            @endif
        </div>
        <div class="ai-actions">
            <button class="ai-btn primary" type="submit">Consultar</button>
            <a class="ai-btn" href="{{ url()->current() }}">Limpiar</a>
        </div>
    </div>
</form>
@once
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('filtrosAcademicosNotas');
    if (!form) return;
    const ciclo = form.querySelector('#ciclo');
    const nivel = form.querySelector('#nivel_notas');
    const grado = form.querySelector('#grado_notas');
    const seccion = form.querySelector('#seccion');
    const materia = form.querySelector('#materia');
    const periodo = form.querySelector('#periodo');

    function filtrar(select, atributo, valor, conservar) {
        const anterior = conservar ? select.value : '';
        let valido = false;
        Array.from(select.options).forEach(function (opcion) {
            if (!opcion.value) return;
            const coincide = Boolean(valor) && opcion.dataset[atributo] === valor;
            opcion.hidden = !coincide;
            opcion.disabled = !coincide;
            if (coincide && opcion.value === anterior) valido = true;
        });
        select.value = valido ? anterior : '';
        select.disabled = !valor;
    }

    function actualizar(conservar) {
        filtrar(grado, 'nivel', nivel.value, conservar);
        filtrar(seccion, 'grado', grado.value, conservar);
        filtrar(materia, 'seccion', seccion.value, conservar);
        periodo.disabled = !seccion.value;
        if (!conservar || periodo.disabled) periodo.value = '';
    }

    nivel.addEventListener('change', function () { actualizar(false); });
    grado.addEventListener('change', function () {
        filtrar(seccion, 'grado', grado.value, false);
        filtrar(materia, 'seccion', seccion.value, false);
        periodo.value = '';
        periodo.disabled = true;
    });
    seccion.addEventListener('change', function () {
        filtrar(materia, 'seccion', seccion.value, false);
        periodo.value = '';
        periodo.disabled = !seccion.value;
    });
    ciclo.addEventListener('change', function () {
        // Recargar el catálogo del ciclo elegido; no reutilizar secciones del ciclo anterior.
        nivel.value = '';
        grado.value = '';
        seccion.value = '';
        materia.value = '';
        periodo.value = '';
        form.submit();
    });
    actualizar(true);
});
</script>
@endpush
@endonce
