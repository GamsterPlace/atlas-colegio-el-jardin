
@extends('layouts.direccion')
@vite('resources/css/direccion/inscripciones/create.css')
@section('title', 'Inscripción de estudiantes')
@section('role', 'Dirección')
@section('module-title', 'Inscripción y reinscripción')
@section('dashboard-url', route('direccion.dashboard'))

@section('content')

@php
    $modoInicial = old('tipo_inscripcion', 'NUEVA');
@endphp

<div class="container-fluid py-3">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="fw-semibold mb-1">
                <i class="bi bi-person-plus me-2"></i>
                Inscripción y reinscripción de estudiantes
            </h4>
            <p class="text-muted mb-0">
                Solicitud de matrícula del Colegio Mixto El Jardín.
            </p>
        </div>

        <a href="{{ route('direccion.inscripciones.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Regresar
        </a>
    </div>

    <form id="formInscripcion" method="POST" action="{{ route('direccion.inscripciones.store') }}" autocomplete="off">
        @csrf

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>No fue posible completar la inscripción.</strong> Revise los datos indicados.
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent py-3">
                <h5 class="mb-0">
                    <i class="bi bi-ui-checks me-2"></i>
                    Tipo de inscripción
                </h5>
            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="tipo_inscripcion" class="form-label">
                            Procedimiento
                        </label>

                        <select id="tipo_inscripcion"
                                name="tipo_inscripcion"
                                class="form-select"
                                required>
                            <option value="NUEVA" @selected($modoInicial === 'NUEVA')>
                                Nueva inscripción
                            </option>
                            <option value="REINSCRIPCION" @selected($modoInicial === 'REINSCRIPCION')>
                                Reinscripción
                            </option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="ciclo_escolar_id" class="form-label">
                            Ciclo escolar
                        </label>

                        <select id="ciclo_escolar_id"
                                name="ciclo_escolar_id"
                                class="form-select"
                                required>

                            @foreach($ciclosEscolares as $ciclo)
                                <option value="{{ $ciclo->id }}"
                                        @selected((string) old('ciclo_escolar_id', $cicloEscolarId) === (string) $ciclo->id)>
                                    {{ $ciclo->nombre }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                </div>

                <div id="panelReinscripcion"
                     class="border rounded p-3 mt-3"
                     hidden>

                    <label for="identificador_estudiante" class="form-label">
                        Buscar estudiante por código histórico o carné
                    </label>

                    <div class="input-group">
                        <input type="text"
                               id="identificador_estudiante"
                               class="form-control"
                               maxlength="40"
                               placeholder="Ingrese el código o número de carné">

                        <button type="button"
                                id="btnBuscarEstudiante"
                                class="btn btn-outline-primary">
                            <i class="bi bi-search me-1"></i>
                            Buscar
                        </button>
                    </div>

                    <div id="resultadoBusqueda"
                         class="mt-3"
                         aria-live="polite"></div>

                </div>

                <input type="hidden"
                       id="estudiante_id"
                       name="estudiante_id"
                       value="{{ old('estudiante_id') }}">

            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent py-3">
                <h5 class="mb-0">
                    <i class="bi bi-person-vcard me-2"></i>
                    Información general del estudiante
                </h5>
            </div>

            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="nombres" class="form-label">
                            Nombres completos
                        </label>
                        <input type="text"
                               id="nombres"
                               name="nombres"
                               class="form-control"
                               maxlength="120"
                               value="{{ old('nombres') }}"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label for="apellidos" class="form-label">
                            Apellidos completos
                        </label>
                        <input type="text"
                               id="apellidos"
                               name="apellidos"
                               class="form-control"
                               maxlength="120"
                               value="{{ old('apellidos') }}"
                               required>
                    </div>

                    <div class="col-md-4">
                        <label for="codigo" class="form-label">
                            Código histórico del colegio
                        </label>
                        <input type="text"
                               id="codigo"
                               name="codigo"
                               class="form-control"
                               maxlength="40"
                               value="{{ old('codigo') }}"
                               placeholder="Código del sistema anterior"
                                required>
                    </div>

                    <div class="col-md-4">
                        <label for="numero_carnet" class="form-label">
                            Número de carné ATLAS
                        </label>
                        <input type="text"
                               id="numero_carnet"
                               class="form-control"
                               placeholder="Generado automáticamente"
                               readonly>
                    </div>

                    <div class="col-md-4">
                        <label for="cui" class="form-label">
                            CUI del estudiante
                        </label>
                        <input type="text"
                               id="cui"
                               name="cui"
                               class="form-control"
                               maxlength="13"
                               minlength="13"
                               inputmode="numeric"
                               pattern="[0-9]{13}"
                               value="{{ old('cui') }}"
                               required>
                    </div>

                    <div class="col-md-4">
                        <label for="fecha_nacimiento" class="form-label">
                            Fecha de nacimiento
                        </label>
                        <input type="date"
                               id="fecha_nacimiento"
                               name="fecha_nacimiento"
                               class="form-control"
                               max="{{ now()->format('Y-m-d') }}"
                               value="{{ old('fecha_nacimiento') }}"
                               required>
                    </div>

                    <div class="col-md-4">
                        <label for="edad" class="form-label">
                            Edad
                        </label>
                        <input type="text"
                               id="edad"
                               class="form-control"
                               readonly>
                    </div>

                    <div class="col-md-4">
                        <label for="sexo" class="form-label">
                            Sexo
                        </label>
                        <select id="sexo" name="sexo" class="form-select" required>
                            <option value="">Seleccione</option>
                            <option value="MASCULINO" @selected(old('sexo') === 'MASCULINO')>Masculino</option>
                            <option value="FEMENINO" @selected(old('sexo') === 'FEMENINO')>Femenino</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="lugar_nacimiento" class="form-label">
                            Lugar de nacimiento
                        </label>
                        <input type="text"
                               id="lugar_nacimiento"
                               name="lugar_nacimiento"
                               class="form-control"
                               value="{{ old('lugar_nacimiento') }}">
                    </div>

                    <div class="col-md-6">
                        <label for="religion" class="form-label">
                            Religión
                        </label>
                        <input type="text"
                               id="religion"
                               name="religion"
                               class="form-control"
                               value="{{ old('religion') }}">
                    </div>

                    <div class="col-12">
                        <label for="direccion" class="form-label">
                            Dirección de residencia
                        </label>
                        <textarea id="direccion"
                                  name="direccion"
                                  rows="2"
                                  class="form-control">{{ old('direccion') }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label for="telefono_casa" class="form-label">
                            Teléfono de casa
                        </label>
                        <input type="tel"
                               id="telefono_casa"
                               name="telefono_casa"
                               class="form-control"
                               value="{{ old('telefono_casa') }}">
                    </div>

                    <div class="col-md-6">
                        <label for="transporte" class="form-label">
                            Medio de transporte
                        </label>
                        <input type="text"
                               id="transporte"
                               name="transporte"
                               class="form-control"
                               value="{{ old('transporte') }}">
                    </div>

                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent py-3">
                <h5 class="mb-0">
                    <i class="bi bi-mortarboard me-2"></i>
                    Información académica
                </h5>
            </div>

            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="seccion_id" class="form-label">
                            Grado y sección
                        </label>

                        <select id="seccion_id"
                                name="seccion_id"
                                class="form-select"
                                required>

                            <option value="">Seleccione una sección</option>

                            @foreach($secciones as $seccion)
                                <option value="{{ $seccion->id }}"
                                        data-ciclo="{{ $seccion->ciclo_escolar_id }}"
                                        @selected((string) old('seccion_id') === (string) $seccion->id)>
                                    {{ $seccion->nombre }}
                                </option>
                            @endforeach

                        </select>

                        <small class="text-muted">
                            Las secciones corresponden al ciclo escolar seleccionado.
                        </small>
                    </div>

                    <div class="col-md-6">
                        <label for="observaciones" class="form-label">
                            Observaciones de matrícula
                        </label>

                        <textarea id="observaciones"
                                  name="observaciones"
                                  class="form-control"
                                  rows="2">{{ old('observaciones') }}</textarea>
                    </div>

                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent py-3">
                <h5 class="mb-0">
                    <i class="bi bi-heart-pulse me-2"></i>
                    Información médica
                </h5>
            </div>

            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-4">
                        <label for="tipo_sangre" class="form-label">
                            Tipo de sangre
                        </label>
                        <select id="tipo_sangre"
                                name="informacion_medica[tipo_sangre]"
                                class="form-select">
                            <option value="">Seleccione</option>
                            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $tipo)
                                <option value="{{ $tipo }}" @selected(old('informacion_medica.tipo_sangre') === $tipo)>
                                    {{ $tipo }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-8">
                        <label for="enfermedades_alergias" class="form-label">
                            Enfermedades y alergias
                        </label>
                        <textarea id="enfermedades_alergias"
                                  name="informacion_medica[alergias]"
                                  rows="2"
                                  class="form-control">{{ old('informacion_medica.alergias') }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label for="condiciones_relevantes" class="form-label">Condiciones médicas relevantes</label>
                        <textarea id="condiciones_relevantes" name="informacion_medica[condiciones_relevantes]" rows="2" class="form-control">{{ old('informacion_medica.condiciones_relevantes') }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label for="medicamentos" class="form-label">
                            Medicamentos
                        </label>
                        <textarea id="medicamentos"
                                  name="informacion_medica[medicamentos]"
                                  rows="2"
                                  class="form-control">{{ old('informacion_medica.medicamentos') }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label for="seguro_medico" class="form-label">
                            Seguro médico
                        </label>
                        <input type="text"
                               id="seguro_medico"
                               name="seguro_medico"
                               class="form-control"
                               value="{{ old('seguro_medico') }}">
                    </div>

                    <div class="col-12">
                        <label for="centro_emergencia" class="form-label">
                            Centro médico al que debe trasladarse en caso de emergencia
                        </label>
                        <input type="text"
                               id="centro_emergencia"
                               name="informacion_medica[indicaciones_emergencia]"
                               class="form-control"
                               value="{{ old('informacion_medica.indicaciones_emergencia') }}">
                    </div>

                </div>
            </div>
        </div>

        @foreach (['padre' => 'Padre', 'madre' => 'Madre'] as $clave => $titulo)
            @php
                $indice = $clave === 'padre' ? 0 : 1;
                $incluir = old("incluir_responsable.$indice", $indice === 0 ? '1' : '0');
            @endphp
            <div class="card border-0 shadow-sm mb-4" data-responsable="{{ $indice }}">
                <div class="card-header bg-transparent py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0"><i class="bi bi-people me-2"></i>Información del {{ strtolower($titulo) }}</h5>
                    <div class="form-check">
                        <input class="form-check-input incluir-responsable" type="checkbox" id="incluir_{{ $indice }}"
                               name="incluir_responsable[{{ $indice }}]" value="1" @checked($incluir)>
                        <label class="form-check-label" for="incluir_{{ $indice }}">Registrar {{ strtolower($titulo) }}</label>
                    </div>
                </div>
                <div class="card-body">
                    <fieldset class="datos-responsable">
                        <input type="hidden" name="responsables[{{ $indice }}][parentesco]" value="{{ strtoupper($titulo) }}">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="small" data-estado-busqueda-responsable="{{ $indice }}" role="status" aria-live="polite"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nombres</label>
                                <input type="text" name="responsables[{{ $indice }}][nombres]" class="form-control" maxlength="120"
                                       value="{{ old("responsables.$indice.nombres") }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Apellidos</label>
                                <input type="text" name="responsables[{{ $indice }}][apellidos]" class="form-control" maxlength="120"
                                       value="{{ old("responsables.$indice.apellidos") }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">DPI / CUI</label>
                                <input type="text" name="responsables[{{ $indice }}][cui]" class="form-control" maxlength="13"
                                       pattern="[0-9]{13}" inputmode="numeric" value="{{ old("responsables.$indice.cui") }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Teléfono</label>
                                <input type="tel" name="responsables[{{ $indice }}][telefono]" class="form-control"
                                       value="{{ old("responsables.$indice.telefono") }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Correo electrónico</label>
                                <input type="email" name="responsables[{{ $indice }}][correo]" class="form-control"
                                       value="{{ old("responsables.$indice.correo") }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Dirección</label>
                                <input type="text" name="responsables[{{ $indice }}][direccion]" class="form-control"
                                       value="{{ old("responsables.$indice.direccion") }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fecha de nacimiento (opcional)</label>
                                <input type="date" name="responsables[{{ $indice }}][fecha_nacimiento]" class="form-control"
                                       value="{{ old("responsables.$indice.fecha_nacimiento") }}" max="{{ now()->toDateString() }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sexo (opcional)</label>
                                <select name="responsables[{{ $indice }}][sexo]" class="form-select">
                                    <option value="">Seleccione</option>
                                    <option value="MASCULINO" @selected(old("responsables.$indice.sexo") === 'MASCULINO')>Masculino</option>
                                    <option value="FEMENINO" @selected(old("responsables.$indice.sexo") === 'FEMENINO')>Femenino</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mt-2">
                                    <input type="checkbox" class="form-check-input" id="recoger_{{ $indice }}"
                                           name="responsables[{{ $indice }}][autorizado_recoger]" value="1"
                                           @checked(old("responsables.$indice.autorizado_recoger"))>
                                    <label for="recoger_{{ $indice }}" class="form-check-label">Autorizado para recoger al estudiante</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mt-2">
                                    <input type="checkbox" class="form-check-input" disabled id="portal_{{ $indice }}">
                                    <label for="portal_{{ $indice }}" class="form-check-label">Acceso al portal (se autoriza en un procedimiento separado)</label>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        @endforeach

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent py-3">
                <h5 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i>Responsabilidades familiares</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="responsable_financiero">Responsable financiero</label>
                        <select class="form-select" id="responsable_financiero" required>
                            <option value="">Seleccione</option>
                            <option value="0" @selected(old('responsable_financiero') === '0')>Padre</option>
                            <option value="1" @selected(old('responsable_financiero') === '1')>Madre</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="contacto_principal">Contacto principal</label>
                        <select class="form-select" id="contacto_principal" required>
                            <option value="">Seleccione</option>
                            <option value="0" @selected(old('contacto_principal') === '0')>Padre</option>
                            <option value="1" @selected(old('contacto_principal') === '1')>Madre</option>
                        </select>
                    </div>
                </div>
                <p class="small text-muted mt-2 mb-0">Debe seleccionar exactamente un responsable financiero y un contacto principal entre las personas registradas.</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent py-3">
                <h5 class="mb-0">
                    <i class="bi bi-people-fill me-2"></i>
                    Información familiar adicional
                </h5>
            </div>

            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label">Número de hermanos</label>
                        <input type="number"
                               name="numero_hermanos"
                               min="0"
                               class="form-control"
                               value="{{ old('numero_hermanos') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Hermanos mayores</label>
                        <input type="number"
                               name="hermanos_mayores"
                               min="0"
                               class="form-control"
                               value="{{ old('hermanos_mayores') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Hermanos menores</label>
                        <input type="number"
                               name="hermanos_menores"
                               min="0"
                               class="form-control"
                               value="{{ old('hermanos_menores') }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label">
                            Hermanos inscritos en el colegio
                        </label>
                        <textarea name="hermanos_inscritos"
                                  rows="2"
                                  class="form-control"
                                  placeholder="Nombres y grados">{{ old('hermanos_inscritos') }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Persona responsable del estudiante (referencia adicional)
                        </label>
                        <input type="text"
                               name="responsable_estudiante"
                               class="form-control"
                               value="{{ old('responsable_estudiante') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Teléfono de emergencia
                        </label>
                        <input type="tel"
                               name="telefono_emergencia"
                               class="form-control"
                               value="{{ old('telefono_emergencia') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Persona que cubre los gastos (referencia adicional)
                        </label>
                        <input type="text"
                               name="responsable_gastos"
                               class="form-control"
                               value="{{ old('responsable_gastos') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Responsable del pago de colegiaturas (referencia adicional)
                        </label>
                        <input type="text"
                               name="responsable_colegiatura"
                               class="form-control"
                               value="{{ old('responsable_colegiatura') }}">
                    </div>

                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent py-3">
                <h5 class="mb-0"><i class="bi bi-telephone-inbound me-2"></i>Contacto de emergencia</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label">Nombre completo</label>
                        <input type="text" name="contactos_emergencia[0][nombre]" class="form-control" required
                               value="{{ old('contactos_emergencia.0.nombre') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Parentesco</label>
                        <input type="text" name="contactos_emergencia[0][parentesco]" class="form-control" required
                               value="{{ old('contactos_emergencia.0.parentesco') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Teléfono</label>
                        <input type="tel" name="contactos_emergencia[0][telefono]" class="form-control" required
                               value="{{ old('contactos_emergencia.0.telefono', old('telefono_emergencia')) }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent py-3">
                <h5 class="mb-0">
                    <i class="bi bi-person-check me-2"></i>
                    Personas autorizadas para retirar al estudiante
                </h5>
            </div>

            <div class="card-body">

                @for($i = 0; $i < 3; $i++)
                    <div class="row g-3 mb-3">

                        <div class="col-md-4">
                            <label class="form-label">
                                Nombre de la persona {{ $i + 1 }}
                            </label>
                            <input type="text"
                                   name="personas_autorizadas_retiro[{{ $i }}][nombre]"
                                   class="form-control"
                                   value="{{ old("personas_autorizadas_retiro.$i.nombre") }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">DPI / Identificación</label>
                            <input type="text" name="personas_autorizadas_retiro[{{ $i }}][identificacion]"
                                   class="form-control" value="{{ old("personas_autorizadas_retiro.$i.identificacion") }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Parentesco</label>
                            <input type="text"
                                   name="personas_autorizadas_retiro[{{ $i }}][parentesco]"
                                   class="form-control"
                                   value="{{ old("personas_autorizadas_retiro.$i.parentesco") }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Teléfono</label>
                            <input type="tel"
                                   name="personas_autorizadas_retiro[{{ $i }}][telefono]"
                                   class="form-control"
                                   value="{{ old("personas_autorizadas_retiro.$i.telefono") }}">
                        </div>

                    </div>
                @endfor

            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent py-3">
                <h5 class="mb-0">
                    <i class="bi bi-receipt me-2"></i>
                    Generación de documentos y colegiaturas
                </h5>
            </div>

            <div class="card-body">
                <p class="text-muted mb-3">
                    Al completar la inscripción, ATLAS deberá generar los documentos
                    y obligaciones correspondientes al ciclo escolar.
                </p>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <i class="bi bi-file-earmark-text me-2"></i>
                            Solicitud de matrícula para firmas
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <i class="bi bi-key me-2"></i>
                            Fichas de credenciales autorizadas
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <i class="bi bi-cash-stack me-2"></i>
                            Cuentas por cobrar y colegiaturas
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <i class="bi bi-file-earmark-pdf me-2"></i>
                            Talonario de pagos en PDF
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap justify-content-end gap-2 mb-4">
            <a href="{{ route('direccion.inscripciones.index') }}"
               class="btn btn-outline-secondary">
                Cancelar
            </a>

            <button type="submit"
                    id="btnGuardarInscripcion"
                    class="btn btn-primary">
                <i class="bi bi-check-circle me-1"></i>
                Guardar inscripción
            </button>
        </div>

    </form>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    @if ($errors->any())
    ['solicitud-matricula', 'ficha-inscripcion', 'talonario'].forEach(function (tipoDocumento) {
        // No abrir ventanas nuevas si el servidor devolvió errores.
        // Las pestañas pendientes pueden cerrarse manualmente.
    });
    @endif
    const form = document.getElementById('formInscripcion');
    const tipo = document.getElementById('tipo_inscripcion');
    const panel = document.getElementById('panelReinscripcion');
    const buscar = document.getElementById('btnBuscarEstudiante');
    const identificador = document.getElementById('identificador_estudiante');
    const resultado = document.getElementById('resultadoBusqueda');
    const estudianteId = document.getElementById('estudiante_id');
    const fechaNacimiento = document.getElementById('fecha_nacimiento');
    const edad = document.getElementById('edad');
    const ciclo = document.getElementById('ciclo_escolar_id');
    const seccion = document.getElementById('seccion_id');
    const financiero = document.getElementById('responsable_financiero');
    const principal = document.getElementById('contacto_principal');
    const guardar = document.getElementById('btnGuardarInscripcion');
    let enviando = false;
    let busquedaActual = 0;

    function actualizarModo() {
        const reinscripcion = tipo.value === 'REINSCRIPCION';
        panel.hidden = !reinscripcion;
        if (!reinscripcion) {
            estudianteId.value = '';
            resultado.textContent = '';
            identificador.value = '';
            document.getElementById('numero_carnet').value = '';
        }
        document.getElementById('codigo').required = !reinscripcion;
        document.getElementById('cui').required = !reinscripcion;
    }

    function calcularEdad() {
        if (!fechaNacimiento.value) { edad.value = ''; return; }
        const partes = fechaNacimiento.value.split('-').map(Number);
        const hoy = new Date();
        let anios = hoy.getFullYear() - partes[0];
        if (hoy.getMonth() + 1 < partes[1] ||
            (hoy.getMonth() + 1 === partes[1] && hoy.getDate() < partes[2])) anios--;
        edad.value = anios >= 0 ? anios : '';
    }

    function actualizarSecciones() {
        const seleccionado = ciclo.value;
        Array.from(seccion.options).forEach(function (opcion) {
            if (!opcion.value) return;
            opcion.hidden = opcion.dataset.ciclo !== seleccionado;
            opcion.disabled = opcion.dataset.ciclo !== seleccionado;
        });
        if (seccion.selectedOptions[0]?.disabled) seccion.value = '';
    }

    function asignarValor(id, valor) {
        const campo = document.getElementById(id);
        if (campo) campo.value = valor ?? '';
    }

    function actualizarResponsables() {
        document.querySelectorAll('[data-responsable]').forEach(function (tarjeta) {
            const incluir = tarjeta.querySelector('.incluir-responsable').checked;
            tarjeta.querySelector('.datos-responsable').disabled = !incluir;
        });
        [financiero, principal].forEach(function (selector) {
            Array.from(selector.options).forEach(function (opcion) {
                if (!opcion.value) return;
                const casilla = document.getElementById('incluir_' + opcion.value);
                opcion.disabled = !casilla?.checked;
                opcion.hidden = !casilla?.checked;
            });
            if (selector.selectedOptions[0]?.disabled) selector.value = '';
        });
    }

    async function buscarEstudiante() {
        const valor = identificador.value.trim();
        if (!valor) { resultado.textContent = 'Ingrese un código o número de carné.'; return; }
        const solicitud = ++busquedaActual;
        buscar.disabled = true;
        resultado.textContent = 'Buscando estudiante...';
        estudianteId.value = '';
        try {
            const url = new URL(@json(route('direccion.inscripciones.buscar-estudiante')), window.location.origin);
            url.searchParams.set('identificador', valor);
            const respuesta = await fetch(url, {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin'
            });
            const datos = await respuesta.json();
            if (solicitud !== busquedaActual || tipo.value !== 'REINSCRIPCION') return;
            if (!respuesta.ok || !datos.encontrado) {
                resultado.textContent = datos.mensaje ?? 'No se encontró el estudiante.';
                return;
            }
            const estudiante = datos.estudiante;
            estudianteId.value = estudiante.id;
            ['codigo', 'numero_carnet', 'cui', 'nombres', 'apellidos', 'fecha_nacimiento', 'sexo', 'direccion', 'lugar_nacimiento', 'religion', 'telefono_casa', 'transporte', 'seguro_medico', 'numero_hermanos', 'hermanos_mayores', 'hermanos_menores']
                .forEach(campo => asignarValor(campo, estudiante[campo]));
            const hermanosInscritos = document.querySelector('[name="hermanos_inscritos"]');
            if (hermanosInscritos) hermanosInscritos.value = estudiante.hermanos_inscritos ?? '';
            calcularEdad();
            resultado.textContent = 'Estudiante encontrado: ' + estudiante.nombre_completo
                + '. Revise y actualice sus datos antes de continuar.';
        } catch (error) {
            if (solicitud === busquedaActual) resultado.textContent = 'No fue posible consultar el estudiante.';
        } finally {
            if (solicitud === busquedaActual) buscar.disabled = false;
        }
    }

    // Autocompletar los datos del padre o de la madre a partir de su CUI.
    // Se consulta únicamente al completar los 13 dígitos, sin alterar la selección
    // de responsable financiero ni los permisos de acceso al portal.
    document.querySelectorAll('[data-responsable]').forEach(function (tarjeta) {
        const cuiInput = tarjeta.querySelector('input[name$="[cui]"]');
        const estado = tarjeta.querySelector('[data-estado-busqueda-responsable]');
        const indice = tarjeta.dataset.responsable;
        let secuencia = 0;
        let espera = null;
        let controlador = null;

        function mensaje(texto, clase) {
            estado.textContent = texto;
            estado.className = 'small ' + (clase || 'text-muted');
        }

        cuiInput.addEventListener('input', function () {
            const cui = cuiInput.value.replace(/\D/g, '');
            secuencia++;
            clearTimeout(espera);
            if (controlador) controlador.abort();
            mensaje('');
            if (cui.length !== 13 || !tarjeta.querySelector('.incluir-responsable').checked) return;
            const actual = secuencia;
            espera = setTimeout(async function () {
                controlador = new AbortController();
                mensaje('Verificando si el responsable ya está registrado...');
                try {
                    const url = new URL(@json(route('direccion.inscripciones.buscar-responsable')), window.location.origin);
                    url.searchParams.set('cui', cui);
                    const respuesta = await fetch(url, {
                        headers: { 'Accept': 'application/json' },
                        credentials: 'same-origin',
                        signal: controlador.signal
                    });
                    const datos = await respuesta.json();
                    if (actual !== secuencia || cuiInput.value.replace(/\D/g, '') !== cui) return;
                    if (!respuesta.ok) {
                        mensaje('No fue posible verificar el CUI. Puede ingresar los datos manualmente.', 'text-danger');
                        return;
                    }
                    if (!datos.encontrado) {
                        mensaje('Responsable nuevo. Complete sus datos.', 'text-muted');
                        return;
                    }
                    const persona = datos.persona;
                    ['nombres', 'apellidos', 'telefono', 'correo', 'direccion', 'fecha_nacimiento', 'sexo']
                        .forEach(function (campo) {
                            const input = tarjeta.querySelector('[name="responsables[' + indice + '][' + campo + ']"]');
                            if (input) input.value = persona[campo] ?? '';
                        });
                    mensaje('Responsable registrado en ATLAS. Sus datos se cargaron; verifíquelos antes de guardar.', 'text-success');
                } catch (error) {
                    if (error.name !== 'AbortError' && actual === secuencia) {
                        mensaje('No fue posible consultar al responsable. Puede ingresar los datos manualmente.', 'text-danger');
                    }
                }
            }, 350);
        });
    });

    tipo.addEventListener('change', function () { busquedaActual++; buscar.disabled = false; actualizarModo(); });
    fechaNacimiento.addEventListener('change', calcularEdad);
    ciclo.addEventListener('change', actualizarSecciones);
    buscar.addEventListener('click', buscarEstudiante);
    identificador.addEventListener('input', function () {
        busquedaActual++;
        estudianteId.value = '';
        resultado.textContent = '';
        buscar.disabled = false;
    });
    identificador.addEventListener('keydown', function (evento) {
        if (evento.key === 'Enter') { evento.preventDefault(); buscarEstudiante(); }
    });
    document.querySelectorAll('.incluir-responsable').forEach(casilla =>
        casilla.addEventListener('change', actualizarResponsables));

    form.addEventListener('submit', function (evento) {
        if (enviando) { evento.preventDefault(); return; }
        if (tipo.value === 'REINSCRIPCION' && !estudianteId.value) {
            evento.preventDefault();
            resultado.textContent = 'Busque y seleccione un estudiante antes de continuar.';
            identificador.focus();
            return;
        }
        if (!financiero.value || !principal.value ||
            !document.getElementById('incluir_' + financiero.value)?.checked ||
            !document.getElementById('incluir_' + principal.value)?.checked) {
            evento.preventDefault();
            alert('Seleccione un responsable financiero y un contacto principal válidos.');
            return;
        }
        [financiero, principal].forEach(function (selector, posicion) {
            const clave = posicion === 0 ? 'es_responsable_financiero' : 'es_contacto_principal';
            const campo = document.createElement('input');
            campo.type = 'hidden';
            campo.name = 'responsables[' + selector.value + '][' + clave + ']';
            campo.value = '1';
            form.appendChild(campo);
        });
        document.querySelectorAll('[name^="personas_autorizadas_retiro["]').forEach(function (campo) {
            const fila = campo.closest('.row');
            const nombre = fila?.querySelector('[name$="[nombre]"]');
            if (nombre && !nombre.value.trim()) campo.disabled = true;
        });
        // Reservar las pestañas durante la interacción del usuario, sin contenido provisional.
        // Sus URLs definitivas se conocerán después de registrar la inscripción.
        ['solicitud-matricula', 'ficha-inscripcion', 'talonario'].forEach(function (tipoDocumento) {
            const nombreVentana = 'atlas_pdf_' + tipoDocumento.replace(/-/g, '_');
            window.open('', nombreVentana);
        });
        enviando = true;
        guardar.disabled = true;
        guardar.innerHTML = '<span class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span> Guardando...';
    });

    actualizarModo();
    calcularEdad();
    actualizarSecciones();
    actualizarResponsables();
});
</script>
@endpush
