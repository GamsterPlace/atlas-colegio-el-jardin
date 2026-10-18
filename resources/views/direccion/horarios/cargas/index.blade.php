@extends('layouts.direccion')

@section('module-title', 'Cargas académicas')

@section('content')
<div class="atlas-page-heading">
    <h2>Cargas académicas</h2>
    <p>Habilita las materias del pensum para cada sección y consulta sus períodos semanales.</p>
</div>

@if(session('success'))
    <div class="atlas-alert atlas-alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="atlas-alert atlas-alert-danger" role="alert">
        <strong>No se pudo completar la operación.</strong>
        <ul style="margin:8px 0 0 20px">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="atlas-card" style="margin-bottom:20px">
    <div class="atlas-card-body">
        <form method="GET" action="{{ route('direccion.horarios.cargas.index') }}">
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:16px;align-items:end">
                <div class="atlas-form-group">
                    <label for="ciclo_id" class="atlas-label">Ciclo escolar</label>
                    <select class="atlas-select" name="ciclo_id" id="ciclo_id" onchange="this.form.submit()">
                        @foreach($ciclos as $ciclo)
                            <option value="{{ $ciclo->id }}" @selected($cicloId == $ciclo->id)>
                                {{ $ciclo->nombre }} ({{ $ciclo->estado }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="atlas-form-group">
                    <label for="seccion_id" class="atlas-label">Grado y sección</label>
                    <select class="atlas-select" name="seccion_id" id="seccion_id" onchange="this.form.submit()">
                        @foreach($secciones as $item)
                            <option value="{{ $item->id }}" @selected($seccionId == $item->id)>
                                {{ $item->nivel }} / {{ $item->grado }} / {{ $item->nombre }} — {{ $item->jornada }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div><button type="submit" class="atlas-btn atlas-btn-outline">Consultar</button></div>
            </div>
        </form>
    </div>
</div>

<div class="atlas-card">
    <div class="atlas-card-body">
        @if(!$ciclos->count())
            <p>No existen ciclos escolares registrados.</p>
        @elseif(!$seccion)
            <p>No existen secciones para el ciclo seleccionado.</p>
        @elseif(!$materias->count())
            <p>No hay materias vigentes del pensum configuradas para este grado. Primero deben registrarse el pensum y sus cursos.</p>
        @else
            <div style="display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:16px">
                <div>
                    <h3 style="margin:0">Materias disponibles</h3>
                    <p style="margin:5px 0 0">Sección {{ $seccion->nombre }} — {{ $seccion->grado }}</p>
                </div>
                <div><strong>{{ $cargas->where('activo', 1)->count() }}</strong> Cursos Habilitados</div>
            </div>
            <div class="atlas-table-responsive">
                <table class="atlas-table">
                    <thead>
                        <tr><th>Materia</th><th>Pensum</th><th>Períodos semanales</th><th>Estado</th><th>Acción</th></tr>
                    </thead>
                    <tbody>
                        @foreach($materias as $materia)
                            @php
                                $carga = $cargas->get($materia->id);
                                $habilitada = $carga && (bool) $carga->activo;
                                $cicloCerrado = $ciclos->firstWhere('id', $cicloId)?->estado === 'CERRADO';
                            @endphp
                            <tr>
                                <td>{{ $materia->curso }}</td>
                                <td>{{ $materia->pensum }}{{ $materia->version ? ' · '.$materia->version : '' }}</td>
                                <td>{{ $materia->horas_semanales }}</td>
                                <td>{{ $habilitada ? 'Habilitada' : ($carga ? 'Inactiva' : 'Sin asignar') }}</td>
                                <td>
                                    @if(!$cicloCerrado)
                                        <form method="POST" action="{{ route('direccion.horarios.cargas.guardar') }}">
                                            @csrf
                                            <input type="hidden" name="seccion_id" value="{{ $seccionId }}">
                                            <input type="hidden" name="pensum_curso_id" value="{{ $materia->id }}">
                                            <input type="hidden" name="activo" value="{{ $habilitada ? 0 : 1 }}">
                                            <button type="submit" class="atlas-btn {{ $habilitada ? 'atlas-btn-outline' : 'atlas-btn-primary' }}">
                                                {{ $habilitada ? 'Desactivar' : 'Habilitar' }}
                                            </button>
                                        </form>
                                    @else
                                        <span>Ciclo cerrado</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p style="margin-top:16px;color:var(--atlas-text-muted,#64748b);font-size:13px">
                Los períodos semanales provienen del pensum. La asignación de docentes se implementará en la siguiente fase.
            </p>
        @endif
    </div>
</div>
@endsection
