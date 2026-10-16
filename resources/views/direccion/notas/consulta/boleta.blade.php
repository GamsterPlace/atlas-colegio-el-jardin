<div class="boleta-documento">
    <header class="boleta-encabezado">
        <img src="{{ !empty($paraPdf) ? public_path('images/institucional/logo-colegio.png') : asset('images/institucional/logo-colegio.png') }}" alt="" class="boleta-logo">
        <div><h1>COLEGIO MIXTO EL JARDÍN</h1><p>SAN RAYMUNDO, GUATEMALA</p><small>FORMACIÓN · VALORES · FUTURO</small></div>
    </header>
    <h2>BOLETA DE CALIFICACIONES {{ $inscripcion->ciclo_escolar?->nombre }}</h2>
    <h3>{{ $inscripcion->seccion?->grado?->nivel?->nombre ?? 'NIVEL ACADÉMICO' }}</h3>
    <table class="boleta-datos"><tr><th>GRADO Y SECCIÓN</th><td>{{ $inscripcion->seccion?->grado?->nombre }} — {{ $inscripcion->seccion?->nombre }}</td><th>CICLO</th><td>{{ $inscripcion->ciclo_escolar?->nombre }}</td></tr>
    <tr><th>ESTUDIANTE</th><td>{{ $inscripcion->estudiante?->apellidos }}, {{ $inscripcion->estudiante?->nombres }}</td><th>CÓDIGO</th><td>{{ $inscripcion->estudiante?->codigo ?? '—' }}</td></tr>
    <tr><th>DOCENTE GUÍA</th><td>{{ $nombreGuia ?? 'No asignado' }}</td><th>CARNÉ</th><td>{{ $inscripcion->estudiante?->numero_carnet ?? '—' }}</td></tr></table>
    <table class="boleta-notas"><thead><tr><th>No.</th><th>Materia</th><th>1er bim.</th><th>2do bim.</th><th>3er bim.</th><th>4to bim.</th><th>Total</th><th>Prom.</th></tr></thead><tbody>
    @foreach($filas as $fila)<tr><td>{{ $loop->iteration }}</td><td class="boleta-materia">{{ $fila['materia'] }}</td>@for($n=1;$n<=4;$n++)<td>{{ $fila['bimestres'][$n] === null ? '—' : number_format($fila['bimestres'][$n],2) }}</td>@endfor<td>{{ $fila['total'] === null ? '—' : number_format($fila['total'],2) }}</td><td><strong>{{ $fila['promedio'] === null ? '—' : number_format($fila['promedio'],2) }}</strong></td></tr>@endforeach
    <tr class="boleta-promedios"><th colspan="2">PROMEDIO DE UNIDAD</th>@for($n=1;$n<=4;$n++)<th>{{ $promedios[$n] === null ? '—' : number_format($promedios[$n],2) }}</th>@endfor<th>—</th><th>{{ $general === null ? '—' : number_format($general,2) }}</th></tr>
    </tbody></table>
    <div class="boleta-observaciones"><strong>OBSERVACIONES</strong><p>________________________________________________________________________________________</p><p>________________________________________________________________________________________</p></div>
    <table class="boleta-firmas"><tr><td>________________________________<br>Firma de docente guía<br>{{ $nombreGuia ?? '' }}</td><td>________________________________<br>Firma y sello de Dirección</td></tr></table>
    <footer>Disciplina · Respeto · Conocimiento · Mejores Personas <span>Fecha de emisión: {{ now()->format('d/m/Y') }}</span></footer>
    @if(collect($filas)->contains(fn($fila)=>!$fila['completa']))<p class="boleta-parcial">Reporte parcial: existen bimestres pendientes. Los promedios son provisionales.</p>@endif
</div>
