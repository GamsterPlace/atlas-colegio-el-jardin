
@extends('layouts.direccion')

@section('title', 'Crear comunicado')
@section('module-title', 'Comunicados institucionales')

@section('content')
<style>
    .atlas-comunicado-crear {
        max-width: 1180px;
        margin: 0 auto;
        padding: 26px 22px 45px;
        color: #183454;
    }

    .atlas-comunicado-crear * {
        box-sizing: border-box;
    }

    .atlas-comunicado-crear-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 18px;
        flex-wrap: wrap;
        margin-bottom: 25px;
    }

    .atlas-comunicado-crear-eyebrow {
        color: #3974b2;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .08em;
        margin-bottom: 8px;
    }

    .atlas-comunicado-crear h1 {
        font-size: clamp(24px, 3vw, 31px);
        font-weight: 800;
        color: #142f52;
        margin: 0 0 8px;
    }

    .atlas-comunicado-crear-subtitle {
        font-size: 14px;
        color: #718299;
        line-height: 1.65;
        margin: 0;
    }

    .atlas-comunicado-crear-card {
        background: #fff;
        border: 1px solid #dfe8f1;
        border-radius: 16px;
        margin-bottom: 20px;
        overflow: hidden;
        box-shadow: 0 4px 18px rgba(20, 50, 80, .035);
    }

    .atlas-comunicado-crear-card-header {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 21px 24px;
        border-bottom: 1px solid #e9eef5;
    }

    .atlas-comunicado-crear-icon {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #edf4fd;
        color: #27609e;
        border-radius: 11px;
        font-size: 19px;
        flex-shrink: 0;
    }

    .atlas-comunicado-crear-card-title {
        font-size: 16px;
        font-weight: 800;
        color: #1b3c60;
        margin: 0 0 5px;
    }

    .atlas-comunicado-crear-card-subtitle {
        font-size: 12px;
        color: #8090a4;
        margin: 0;
        line-height: 1.5;
    }

    .atlas-comunicado-crear-card-body {
        padding: 24px;
    }

    .atlas-comunicado-crear-field {
        margin-bottom: 20px;
    }

    .atlas-comunicado-crear-field:last-child {
        margin-bottom: 0;
    }

    .atlas-comunicado-crear-field label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: #36516f;
        margin-bottom: 8px;
    }

    .atlas-comunicado-crear-required {
        color: #bb3f4a;
    }

    .atlas-comunicado-crear-input,
    .atlas-comunicado-crear-select,
    .atlas-comunicado-crear-textarea {
        width: 100%;
        border: 1px solid #d4e0eb;
        border-radius: 10px;
        background: #fff;
        color: #243f5d;
        font-size: 14px;
        padding: 12px 14px;
        outline: none;
        transition: .2s ease;
        font-family: inherit;
    }

    .atlas-comunicado-crear-input:focus,
    .atlas-comunicado-crear-select:focus,
    .atlas-comunicado-crear-textarea:focus {
        border-color: #3e7cba;
        box-shadow: 0 0 0 3px rgba(62, 124, 186, .1);
    }

    .atlas-comunicado-crear-textarea {
        min-height: 220px;
        resize: vertical;
        line-height: 1.7;
    }

    .atlas-comunicado-crear-help {
        font-size: 12px;
        color: #8796a8;
        margin-top: 7px;
        line-height: 1.5;
    }

    .atlas-comunicado-crear-error {
        color: #b63846;
        font-size: 12px;
        margin-top: 7px;
    }

    .atlas-comunicado-crear-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .atlas-comunicado-crear-group {
        border: 1px solid #dfe7f0;
        border-radius: 13px;
        overflow: hidden;
    }

    .atlas-comunicado-crear-group-header {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 16px 18px;
        background: #f8fbff;
        border-bottom: 1px solid #e6edf5;
    }

    .atlas-comunicado-crear-group-header i {
        font-size: 19px;
        color: #2a679f;
    }

    .atlas-comunicado-crear-group-title {
        font-size: 13px;
        font-weight: 800;
        color: #274767;
    }

    .atlas-comunicado-crear-group-body {
        padding: 17px 18px;
    }

    .atlas-comunicado-crear-check {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        padding: 10px 0;
        cursor: pointer;
        font-size: 13px;
        color: #415a75;
        line-height: 1.5;
    }

    .atlas-comunicado-crear-check input {
        margin-top: 3px;
        width: 16px;
        height: 16px;
        accent-color: #2467a6;
        flex-shrink: 0;
    }

    .atlas-comunicado-crear-check strong {
        display: block;
        color: #284867;
        font-size: 13px;
    }

    .atlas-comunicado-crear-check small {
        display: block;
        color: #8292a5;
        font-size: 11px;
        margin-top: 3px;
    }

    .atlas-comunicado-crear-selector {
        border: 1px solid #dfe7f0;
        border-radius: 12px;
        padding: 16px;
        background: #fbfdff;
    }

    .atlas-comunicado-crear-search {
        margin-bottom: 12px;
    }

    .atlas-comunicado-crear-list {
        max-height: 245px;
        overflow-y: auto;
        border: 1px solid #e3ebf3;
        border-radius: 10px;
        background: #fff;
        padding: 5px 13px;
    }

    .atlas-comunicado-crear-list-empty {
        padding: 15px;
        text-align: center;
        color: #8a98a8;
        font-size: 12px;
    }

    .atlas-comunicado-crear-selection-count {
        font-size: 12px;
        font-weight: 700;
        color: #3974b2;
        margin-top: 11px;
    }

    .atlas-comunicado-crear-notice {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        padding: 16px 18px;
        background: #edf5ff;
        border: 1px solid #d1e3f8;
        border-radius: 12px;
        color: #315a83;
        font-size: 12px;
        line-height: 1.7;
        margin-top: 20px;
    }

    .atlas-comunicado-crear-notice i {
        font-size: 19px;
        margin-top: 1px;
    }

    .atlas-comunicado-crear-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        flex-wrap: wrap;
        padding: 22px 0;
    }

    .atlas-comunicado-crear-btn {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 9px;
        padding: 12px 20px;
        border-radius: 10px;
        border: 1px solid transparent;
        text-decoration: none;
        font-size: 13px;
        font-weight: 800;
        cursor: pointer;
        transition: .2s ease;
    }

    .atlas-comunicado-crear-btn-secondary {
        background: #fff;
        color: #345675;
        border-color: #d5e0eb;
    }

    .atlas-comunicado-crear-btn-primary {
        background: #173f73;
        color: #fff;
    }

    .atlas-comunicado-crear-btn-primary:hover {
        background: #102f57;
        color: #fff;
    }

    .atlas-comunicado-crear-btn:disabled {
        opacity: .65;
        cursor: not-allowed;
    }

    .atlas-comunicado-crear-alert {
        padding: 15px 18px;
        border: 1px solid #f0c8ce;
        background: #fff2f3;
        color: #a23847;
        border-radius: 11px;
        margin-bottom: 20px;
        font-size: 13px;
        line-height: 1.6;
    }

    .atlas-comunicado-crear-modal {
        position: fixed;
        inset: 0;
        z-index: 2000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(13, 31, 53, .58);
    }

    .atlas-comunicado-crear-modal[hidden] {
        display: none;
    }

    .atlas-comunicado-crear-modal-dialog {
        width: 100%;
        max-width: 510px;
        background: #fff;
        border-radius: 17px;
        padding: 27px;
        box-shadow: 0 20px 65px rgba(0, 0, 0, .18);
    }

    .atlas-comunicado-crear-modal-dialog h2 {
        font-size: 21px;
        color: #173858;
        margin: 0 0 12px;
    }

    .atlas-comunicado-crear-modal-dialog p {
        font-size: 13px;
        line-height: 1.7;
        color: #64788e;
    }

    .atlas-comunicado-crear-modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 23px;
    }

    @media (max-width: 760px) {
        .atlas-comunicado-crear-grid {
            grid-template-columns: 1fr;
        }

        .atlas-comunicado-crear {
            padding: 18px 12px 35px;
        }

        .atlas-comunicado-crear-card-body {
            padding: 17px;
        }

        .atlas-comunicado-crear-actions {
            flex-direction: column-reverse;
        }

        .atlas-comunicado-crear-actions .atlas-comunicado-crear-btn {
            width: 100%;
        }
    }
</style>

<div class="atlas-comunicado-crear">

    <div class="atlas-comunicado-crear-header">
        <div>
            <div class="atlas-comunicado-crear-eyebrow">
                Dirección / Comunicación institucional
            </div>

            <h1>Crear comunicado</h1>

            <p class="atlas-comunicado-crear-subtitle">
                Redacte un comunicado institucional y seleccione
                los grupos o destinatarios que deben recibirlo.
            </p>
        </div>

        <a href="{{ route('direccion.comunicados.index') }}"
           class="atlas-comunicado-crear-btn atlas-comunicado-crear-btn-secondary">
            <i class="bi bi-arrow-left"></i>
            Volver al historial
        </a>
    </div>

    @if($errors->any())
        <div class="atlas-comunicado-crear-alert" role="alert">
            <strong>No se pudo procesar el comunicado.</strong>
            <ul style="margin:9px 0 0;padding-left:20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        id="atlasFormularioComunicado"
        method="POST"
        action="{{ route('direccion.comunicados.store') }}"
    >
        @csrf

        <div class="atlas-comunicado-crear-card">
            <div class="atlas-comunicado-crear-card-header">
                <div class="atlas-comunicado-crear-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>

                <div>
                    <h2 class="atlas-comunicado-crear-card-title">
                        Información del comunicado
                    </h2>

                    <p class="atlas-comunicado-crear-card-subtitle">
                        El título y contenido serán visibles para los destinatarios.
                    </p>
                </div>
            </div>

            <div class="atlas-comunicado-crear-card-body">

                <div class="atlas-comunicado-crear-field">
                    <label for="titulo">
                        Título del comunicado
                        <span class="atlas-comunicado-crear-required">*</span>
                    </label>

                    <input
                        type="text"
                        id="titulo"
                        name="titulo"
                        class="atlas-comunicado-crear-input"
                        maxlength="190"
                        minlength="5"
                        required
                        value="{{ old('titulo') }}"
                        placeholder="Ej. Información importante para padres de familia"
                    >

                    @error('titulo')
                        <div class="atlas-comunicado-crear-error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="atlas-comunicado-crear-field">
                    <label for="contenido">
                        Contenido del comunicado
                        <span class="atlas-comunicado-crear-required">*</span>
                    </label>

                    <textarea
                        id="contenido"
                        name="contenido"
                        class="atlas-comunicado-crear-textarea"
                        minlength="10"
                        maxlength="20000"
                        required
                        placeholder="Escriba aquí el mensaje que recibirán los destinatarios..."
                    >{{ old('contenido') }}</textarea>

                    <div class="atlas-comunicado-crear-help">
                        Redacte el mensaje de forma clara. Una vez publicado,
                        quedará registrado en el historial institucional.
                    </div>

                    @error('contenido')
                        <div class="atlas-comunicado-crear-error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

            </div>
        </div>

        <div class="atlas-comunicado-crear-card">
            <div class="atlas-comunicado-crear-card-header">
                <div class="atlas-comunicado-crear-icon">
                    <i class="bi bi-people"></i>
                </div>

                <div>
                    <h2 class="atlas-comunicado-crear-card-title">
                        Selección de destinatarios
                    </h2>

                    <p class="atlas-comunicado-crear-card-subtitle">
                        Puede combinar grupos generales y personas específicas.
                    </p>
                </div>
            </div>

            <div class="atlas-comunicado-crear-card-body">

                <div class="atlas-comunicado-crear-field">
                    <label for="ciclo_escolar_id">
                        Ciclo escolar
                    </label>

                    <select
                        id="ciclo_escolar_id"
                        name="ciclo_escolar_id"
                        class="atlas-comunicado-crear-select"
                    >
                        <option value="">Seleccionar ciclo escolar</option>

                        @foreach($ciclos as $ciclo)
                            <option
                                value="{{ $ciclo->id }}"
                                @selected(
                                    (string) old(
                                        'ciclo_escolar_id',
                                        $cicloId
                                    ) === (string) $ciclo->id
                                )
                            >
                                {{ $ciclo->nombre }}
                            </option>
                        @endforeach
                    </select>

                    <div class="atlas-comunicado-crear-help">
                        Obligatorio para comunicados dirigidos a padres de familia.
                        Se tomarán en cuenta las inscripciones vigentes del ciclo.
                    </div>
                </div>

                <div class="atlas-comunicado-crear-grid">

                    <div class="atlas-comunicado-crear-group">
                        <div class="atlas-comunicado-crear-group-header">
                            <i class="bi bi-globe-americas"></i>
                            <span class="atlas-comunicado-crear-group-title">
                                Grupos generales
                            </span>
                        </div>

                        <div class="atlas-comunicado-crear-group-body">

                            @php
                                $gruposSeleccionados = old('grupos', []);
                            @endphp

                            <label class="atlas-comunicado-crear-check">
                                <input
                                    type="checkbox"
                                    name="grupos[]"
                                    value="COMUNIDAD"
                                    @checked(in_array('COMUNIDAD', $gruposSeleccionados))
                                >
                                <span>
                                    <strong>Toda la comunidad educativa</strong>
                                    <small>
                                        Personal institucional y padres autorizados.
                                    </small>
                                </span>
                            </label>

                            <label class="atlas-comunicado-crear-check">
                                <input
                                    type="checkbox"
                                    name="grupos[]"
                                    value="DOCENTES"
                                    @checked(in_array('DOCENTES', $gruposSeleccionados))
                                >
                                <span>
                                    <strong>Todos los docentes</strong>
                                    <small>
                                        Docentes activos con acceso a ATLAS.
                                    </small>
                                </span>
                            </label>

                            <label class="atlas-comunicado-crear-check">
                                <input
                                    type="checkbox"
                                    name="grupos[]"
                                    value="PERSONAL"
                                    @checked(in_array('PERSONAL', $gruposSeleccionados))
                                >
                                <span>
                                    <strong>Personal del colegio</strong>
                                    <small>
                                        Personal activo con usuario institucional.
                                    </small>
                                </span>
                            </label>

                            <label class="atlas-comunicado-crear-check">
                                <input
                                    type="checkbox"
                                    name="grupos[]"
                                    value="PADRES"
                                    @checked(in_array('PADRES', $gruposSeleccionados))
                                >
                                <span>
                                    <strong>Todos los padres de familia</strong>
                                    <small>
                                        Únicamente responsables autorizados
                                        para recibir comunicaciones.
                                    </small>
                                </span>
                            </label>

                        </div>
                    </div>

                    <div class="atlas-comunicado-crear-group">
                        <div class="atlas-comunicado-crear-group-header">
                            <i class="bi bi-mortarboard"></i>
                            <span class="atlas-comunicado-crear-group-title">
                                Padres por grado
                            </span>
                        </div>

                        <div class="atlas-comunicado-crear-group-body">

                            <input
                                type="search"
                                class="atlas-comunicado-crear-input atlas-comunicado-crear-search"
                                placeholder="Buscar grado..."
                                data-buscar-lista="atlasListaGrados"
                                aria-label="Buscar grado"
                            >

                            <div class="atlas-comunicado-crear-list"
                                 id="atlasListaGrados">

                                @forelse($grados as $grado)
                                    <label class="atlas-comunicado-crear-check"
                                           data-item-busqueda>
                                        <input
                                            type="checkbox"
                                            name="grado_ids[]"
                                            value="{{ $grado->id }}"
                                            @checked(
                                                in_array(
                                                    $grado->id,
                                                    old('grado_ids', [])
                                                )
                                            )
                                        >

                                        <span>
                                            <strong>{{ $grado->nombre }}</strong>
                                            <small>
                                                {{ $grado->nivel?->nombre ?? 'Nivel académico' }}
                                            </small>
                                        </span>
                                    </label>
                                @empty
                                    <div class="atlas-comunicado-crear-list-empty">
                                        No hay grados disponibles.
                                    </div>
                                @endforelse

                            </div>

                            <div class="atlas-comunicado-crear-selection-count"
                                 id="atlasConteoGrados">
                                0 grados seleccionados
                            </div>
                        </div>
                    </div>

                    <div class="atlas-comunicado-crear-group">
                        <div class="atlas-comunicado-crear-group-header">
                            <i class="bi bi-person-badge"></i>
                            <span class="atlas-comunicado-crear-group-title">
                                Docentes específicos
                            </span>
                        </div>

                        <div class="atlas-comunicado-crear-group-body">

                            <input
                                type="search"
                                class="atlas-comunicado-crear-input atlas-comunicado-crear-search"
                                placeholder="Buscar docente..."
                                data-buscar-lista="atlasListaDocentes"
                                aria-label="Buscar docente"
                            >

                            <div class="atlas-comunicado-crear-list"
                                 id="atlasListaDocentes">

                                @forelse($docentes as $docente)
                                    @php
                                        $personaDocente = $docente->personal?->persona;

                                        $nombreDocente = trim(
                                            ($personaDocente?->nombres ?? '')
                                            . ' '
                                            . ($personaDocente?->apellidos ?? '')
                                        );
                                    @endphp

                                    <label class="atlas-comunicado-crear-check"
                                           data-item-busqueda>
                                        <input
                                            type="checkbox"
                                            name="docente_ids[]"
                                            value="{{ $docente->id }}"
                                            @checked(
                                                in_array(
                                                    $docente->id,
                                                    old('docente_ids', [])
                                                )
                                            )
                                        >

                                        <span>
                                            <strong>
                                                {{ $nombreDocente ?: 'Docente #' . $docente->id }}
                                            </strong>
                                            <small>
                                                {{ $docente->personal?->cargo ?? 'Personal docente' }}
                                            </small>
                                        </span>
                                    </label>
                                @empty
                                    <div class="atlas-comunicado-crear-list-empty">
                                        No hay docentes disponibles.
                                    </div>
                                @endforelse

                            </div>

                            <div class="atlas-comunicado-crear-selection-count"
                                 id="atlasConteoDocentes">
                                0 docentes seleccionados
                            </div>

                        </div>
                    </div>

                    <div class="atlas-comunicado-crear-group">
                        <div class="atlas-comunicado-crear-group-header">
                            <i class="bi bi-person-lines-fill"></i>
                            <span class="atlas-comunicado-crear-group-title">
                                Padres de estudiantes específicos
                            </span>
                        </div>

                        <div class="atlas-comunicado-crear-group-body">

                            <input
                                type="search"
                                class="atlas-comunicado-crear-input atlas-comunicado-crear-search"
                                placeholder="Buscar estudiante o carné..."
                                data-buscar-lista="atlasListaEstudiantes"
                                aria-label="Buscar estudiante"
                            >

                            <div class="atlas-comunicado-crear-list"
                                 id="atlasListaEstudiantes">

                                @forelse($estudiantes as $estudiante)
                                    <label class="atlas-comunicado-crear-check"
                                           data-item-busqueda>
                                        <input
                                            type="checkbox"
                                            name="estudiante_ids[]"
                                            value="{{ $estudiante->id }}"
                                            @checked(
                                                in_array(
                                                    $estudiante->id,
                                                    old('estudiante_ids', [])
                                                )
                                            )
                                        >

                                        <span>
                                            <strong>
                                                {{ $estudiante->nombre_completo }}
                                            </strong>
                                            <small>
                                                Carné:
                                                {{ $estudiante->numero_carnet ?? 'No asignado' }}
                                            </small>
                                        </span>
                                    </label>
                                @empty
                                    <div class="atlas-comunicado-crear-list-empty">
                                        No hay estudiantes con inscripción vigente.
                                    </div>
                                @endforelse

                            </div>

                            <div class="atlas-comunicado-crear-selection-count"
                                 id="atlasConteoEstudiantes">
                                0 estudiantes seleccionados
                            </div>

                        </div>
                    </div>

                </div>

                <div class="atlas-comunicado-crear-notice">
                    <i class="bi bi-shield-check"></i>

                    <div>
                        <strong>Protección de destinatarios</strong>

                        <div>
                            Los comunicados para padres de familia se enviarán
                            únicamente a responsables con autorización vigente
                            y acceso activo a ATLAS. Cuando una persona pertenezca
                            a varios grupos seleccionados, recibirá una sola copia.
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="atlas-comunicado-crear-actions">

            <a href="{{ route('direccion.comunicados.index') }}"
               class="atlas-comunicado-crear-btn atlas-comunicado-crear-btn-secondary">
                <i class="bi bi-x-circle"></i>
                Cancelar
            </a>

            <button
                type="button"
                id="atlasBotonRevisar"
                class="atlas-comunicado-crear-btn atlas-comunicado-crear-btn-primary"
            >
                <i class="bi bi-send"></i>
                Revisar y publicar
            </button>

        </div>

    </form>

</div>

<div
    class="atlas-comunicado-crear-modal"
    id="atlasModalPublicacion"
    role="dialog"
    aria-modal="true"
    aria-labelledby="atlasModalTitulo"
    hidden
>
    <div class="atlas-comunicado-crear-modal-dialog">
        <h2 id="atlasModalTitulo">
            Confirmar publicación
        </h2>

        <p>
            El comunicado será publicado y se generarán notificaciones
            para los destinatarios activos y autorizados que coincidan
            con los criterios seleccionados.
        </p>

        <p>
            <strong>Importante:</strong> una vez publicado, el comunicado
            quedará registrado en el historial institucional.
        </p>

        <div class="atlas-comunicado-crear-modal-actions">
            <button
                type="button"
                id="atlasCancelarPublicacion"
                class="atlas-comunicado-crear-btn atlas-comunicado-crear-btn-secondary"
            >
                Regresar
            </button>

            <button
                type="button"
                id="atlasConfirmarPublicacion"
                class="atlas-comunicado-crear-btn atlas-comunicado-crear-btn-primary"
            >
                <i class="bi bi-check-circle"></i>
                Confirmar publicación
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    const formulario = document.getElementById(
        'atlasFormularioComunicado'
    );

    const botonRevisar = document.getElementById(
        'atlasBotonRevisar'
    );

    const modal = document.getElementById(
        'atlasModalPublicacion'
    );

    const botonCancelar = document.getElementById(
        'atlasCancelarPublicacion'
    );

    const botonConfirmar = document.getElementById(
        'atlasConfirmarPublicacion'
    );

    const ciclo = document.getElementById(
        'ciclo_escolar_id'
    );

    let botonAnterior = null;
    let enviando = false;

    document.querySelectorAll('[data-buscar-lista]')
        .forEach(function (buscador) {
            buscador.addEventListener('input', function () {
                const lista = document.getElementById(
                    buscador.dataset.buscarLista
                );

                if (!lista) {
                    return;
                }

                const texto = buscador.value
                    .trim()
                    .toLocaleLowerCase('es');

                lista.querySelectorAll('[data-item-busqueda]')
                    .forEach(function (elemento) {
                        const contenido = elemento.textContent
                            .toLocaleLowerCase('es');

                        elemento.hidden = !contenido.includes(texto);
                    });
            });
        });

    function contarSeleccionados(nombre, elementoId, singular, plural) {
        const total = formulario.querySelectorAll(
            'input[name="' + nombre + '"]:checked'
        ).length;

        const elemento = document.getElementById(elementoId);

        if (elemento) {
            elemento.textContent = total + ' ' +
                (total === 1 ? singular : plural) +
                ' seleccionados';
        }
    }

    function actualizarConteos() {
        contarSeleccionados(
            'grado_ids[]',
            'atlasConteoGrados',
            'grado',
            'grados'
        );

        contarSeleccionados(
            'docente_ids[]',
            'atlasConteoDocentes',
            'docente',
            'docentes'
        );

        contarSeleccionados(
            'estudiante_ids[]',
            'atlasConteoEstudiantes',
            'estudiante',
            'estudiantes'
        );
    }

    formulario.querySelectorAll('input[type="checkbox"]')
        .forEach(function (checkbox) {
            checkbox.addEventListener('change', actualizarConteos);
        });

    actualizarConteos();

    function destinatariosSeleccionados() {
        return formulario.querySelectorAll(
            'input[name="grupos[]"]:checked,' +
            'input[name="grado_ids[]"]:checked,' +
            'input[name="docente_ids[]"]:checked,' +
            'input[name="estudiante_ids[]"]:checked'
        ).length > 0;
    }

    function requiereCiclo() {
        const grupos = Array.from(
            formulario.querySelectorAll(
                'input[name="grupos[]"]:checked'
            )
        ).map(function (elemento) {
            return elemento.value;
        });

        return grupos.includes('PADRES')
            || grupos.includes('COMUNIDAD')
            || formulario.querySelectorAll(
                'input[name="grado_ids[]"]:checked'
            ).length > 0
            || formulario.querySelectorAll(
                'input[name="estudiante_ids[]"]:checked'
            ).length > 0;
    }

    function mostrarError(mensaje) {
        const anterior = document.getElementById(
            'atlasErrorDestinatarios'
        );

        if (anterior) {
            anterior.remove();
        }

        const error = document.createElement('div');

        error.id = 'atlasErrorDestinatarios';
        error.className = 'atlas-comunicado-crear-alert';
        error.setAttribute('role', 'alert');
        error.textContent = mensaje;

        formulario.prepend(error);
        error.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
    }

    function abrirModal() {
        botonAnterior = document.activeElement;
        modal.hidden = false;
        botonCancelar.focus();
    }

    function cerrarModal() {
        modal.hidden = true;

        if (botonAnterior && typeof botonAnterior.focus === 'function') {
            botonAnterior.focus();
        }
    }

    botonRevisar.addEventListener('click', function () {
        if (!formulario.reportValidity()) {
            return;
        }

        if (!destinatariosSeleccionados()) {
            mostrarError(
                'Seleccione al menos un grupo o destinatario para publicar el comunicado.'
            );
            return;
        }

        if (requiereCiclo() && !ciclo.value) {
            mostrarError(
                'Debe seleccionar el ciclo escolar para los comunicados dirigidos a padres de familia.'
            );
            ciclo.focus();
            return;
        }

        const error = document.getElementById(
            'atlasErrorDestinatarios'
        );

        if (error) {
            error.remove();
        }

        abrirModal();
    });

    botonCancelar.addEventListener('click', cerrarModal);

    modal.addEventListener('click', function (evento) {
        if (evento.target === modal) {
            cerrarModal();
        }
    });

    document.addEventListener('keydown', function (evento) {
        if (evento.key === 'Escape' && !modal.hidden) {
            cerrarModal();
        }
    });

    botonConfirmar.addEventListener('click', function () {
        if (enviando) {
            return;
        }

        enviando = true;
        botonConfirmar.disabled = true;
        botonRevisar.disabled = true;

        botonConfirmar.innerHTML =
            '<i class="bi bi-hourglass-split"></i> Publicando...';

        formulario.submit();
    });
});
</script>
@endpush
