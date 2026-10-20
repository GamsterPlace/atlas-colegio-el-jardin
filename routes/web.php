<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Institucional\SitioWebController;

use App\Http\Controllers\NotificacionController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SeleccionRolController;
use App\Http\Controllers\Auth\CambioPasswordObligatorioController;

use App\Http\Controllers\Direccion\DashboardController;
use App\Http\Controllers\Direccion\EstudianteController;
use App\Http\Controllers\Direccion\DocenteController;
use App\Http\Controllers\Direccion\ControlInscripcionController;
use App\Http\Controllers\Direccion\NotaController;
use App\Http\Controllers\Direccion\InscripcionController;
use App\Http\Controllers\Direccion\DocumentoInscripcionController;
use App\Http\Controllers\Direccion\AgendaController;
use App\Http\Controllers\Direccion\BloqueHorarioController;
use App\Http\Controllers\Direccion\CargaAcademicaController;
use App\Http\Controllers\Direccion\DocenteDisponibilidadController;
use App\Http\Controllers\Direccion\RestriccionHorarioController;
use App\Http\Controllers\Direccion\HorarioAcademicoController;


use App\Support\AccesoRoles;

/*
|--------------------------------------------------------------------------
| Sitio web institucional
|--------------------------------------------------------------------------
*/

Route::controller(SitioWebController::class)
    ->group(function () {

        Route::get('/', 'inicio')
            ->name('institucional.inicio');

        Route::get('/nosotros', 'nosotros')
            ->name('institucional.nosotros');

        Route::get('/oferta-academica', 'ofertaAcademica')
            ->name('institucional.oferta-academica');

        Route::get('/vida-estudiantil', 'vidaEstudiantil')
            ->name('institucional.vida-estudiantil');

        Route::get('/noticias', 'noticias')
            ->name('institucional.noticias');

        Route::get('/contacto', 'contacto')
            ->name('institucional.contacto');

    });

/*
|--------------------------------------------------------------------------
| Autenticación
|--------------------------------------------------------------------------
*/

Route::middleware('guest')
    ->group(function () {

        Route::get(
            '/login',
            [LoginController::class, 'showLogin']
        )->name('login');

        Route::post(
            '/login',
            [LoginController::class, 'login']
        )->name('login.submit');

    });

/*
|--------------------------------------------------------------------------
| Rutas de usuarios autenticados
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'usuario.activo',
])->group(function () {

    Route::post(
        '/logout',
        [LoginController::class, 'logout']
    )->name('logout');

    Route::get(
        '/cambiar-password-obligatorio',
        [CambioPasswordObligatorioController::class, 'edit']
    )->name('password.obligatorio.edit');

    Route::post(
        '/cambiar-password-obligatorio',
        [CambioPasswordObligatorioController::class, 'update']
    )->name('password.obligatorio.update');

    Route::middleware('password.actualizada')
        ->group(function () {

            Route::get(
                '/seleccionar-rol',
                [SeleccionRolController::class, 'index']
            )->name('roles.seleccionar');

            Route::post(
                '/seleccionar-rol',
                [SeleccionRolController::class, 'seleccionar']
            )->name('roles.activar');

            Route::get('/dashboard', function () {

                $rol = AccesoRoles::rolActivo();

                if (!$rol) {
                    return redirect()->route('roles.seleccionar');
                }

                return redirect()->route(
                    AccesoRoles::rutaDashboard($rol->codigo)
                );

            })->name('dashboard');

        });

});

/*
|--------------------------------------------------------------------------
| Módulos protegidos por rol
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'usuario.activo',
    'password.actualizada',
    'rol.seleccionado',
])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dirección
    |--------------------------------------------------------------------------
    */

    Route::prefix('direccion')
        ->name('direccion.')
        ->middleware('rol:DIRECCION')
        ->group(function () {

            Route::get(
                '/dashboard',
                [DashboardController::class, 'index']
            )->name('dashboard');

            /*
            |--------------------------------------------------------------------------
            | Estudiantes y expedientes
            |--------------------------------------------------------------------------
            */

            Route::prefix('estudiantes')
                ->name('estudiantes.')
                ->controller(EstudianteController::class)
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/expedientes', 'expedientes')->name('expedientes.index');
                    Route::get('/{docente}/ficha-acceso', 'fichaAcceso')->whereNumber('docente')->name('ficha-acceso');
                    Route::get('/control', 'control')->name('control.index');
                    Route::get('/expedientes/{estudiante}', 'expediente')
                        ->whereNumber('estudiante')->name('expedientes.show');
                });

            /*
            |--------------------------------------------------------------------------
            | Inscripciones y reinscripciones
            |--------------------------------------------------------------------------
            */

            Route::prefix('inscripciones')
                ->name('inscripciones.')
                ->controller(InscripcionController::class)
                ->group(function () {

                    Route::get(
                        '/',
                        'index'
                    )->name('index');

                    Route::get(
                        '/crear',
                        'create'
                    )->name('create');

                    Route::get(
                        '/buscar-estudiante',
                        'buscarEstudiante'
                    )->name('buscar-estudiante');

                    Route::get(
                        '/buscar-responsable',
                        'buscarResponsable'
                    )->name('buscar-responsable');

                    Route::post(
                        '/',
                        'store'
                    )->name('store');

                    Route::get(
                        '/{inscripcion}/completada',
                        'completada'
                    )->whereNumber('inscripcion')
                     ->name('completada');

                });

            /*
            |--------------------------------------------------------------------------
            | Documentos de inscripción
            |--------------------------------------------------------------------------
            */

            Route::prefix('inscripciones/control')->name('inscripciones.control.')
                ->controller(ControlInscripcionController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/{inscripcion}', 'show')->whereNumber('inscripcion')->name('show');
                    Route::post('/{inscripcion}/trasladar', 'trasladar')->whereNumber('inscripcion')->name('trasladar');
                    Route::post('/{inscripcion}/retirar', 'retirar')->whereNumber('inscripcion')->name('retirar');
                });

            Route::prefix('inscripciones/{inscripcion}/documentos')
                ->whereNumber('inscripcion')
                ->name('inscripciones.documentos.')
                ->controller(DocumentoInscripcionController::class)
                ->group(function () {

                    Route::get(
                        '/solicitud-matricula',
                        'solicitudMatricula'
                    )->name('solicitud-matricula');

                    Route::get(
                        '/ficha-inscripcion',
                        'fichaInscripcion'
                    )->name('ficha-inscripcion');

                    Route::get(
                        '/talonario',
                        'talonario'
                    )->name('talonario');


                });

            /*
            |--------------------------------------------------------------------------
            | Docentes: listado, control y asignaciones
            |--------------------------------------------------------------------------
            */

            Route::prefix('notas')->name('notas.')->controller(NotaController::class)->group(function () {
                Route::get('/consulta', 'consulta')->name('index');
                Route::get('/boleta/{inscripcion}/pdf', 'boletaPdf')->whereNumber('inscripcion')->name('boleta.pdf');
                Route::get('/cuadros', 'cuadros')->name('cuadros.index');
                Route::get('/control', 'control')->name('control.index');
            });

            Route::prefix('docentes/{docente}/disponibilidad')
                ->name('docentes.disponibilidad.')
                ->whereNumber('docente')
                ->controller(DocenteDisponibilidadController::class)
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/', 'store')->name('store');
                    Route::put('/{disponibilidad}', 'update')->whereNumber('disponibilidad')->name('update');
                    Route::delete('/{disponibilidad}', 'destroy')->whereNumber('disponibilidad')->name('destroy');
                });

            Route::prefix('docentes')->name('docentes.')
                ->controller(DocenteController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/{docente}/ficha-acceso', 'fichaAcceso')->whereNumber('docente')->name('ficha-acceso');
                    Route::get('/control', 'control')->name('control.index');
                    Route::get('/control/crear', 'create')->name('control.create');
                    Route::post('/control', 'store')->name('control.store');
                    Route::get('/control/{docente}/editar', 'edit')->whereNumber('docente')->name('control.edit');
                    Route::put('/control/{docente}', 'update')->whereNumber('docente')->name('control.update');
                    Route::patch('/control/{docente}/baja', 'baja')->whereNumber('docente')->name('control.baja');
                    Route::patch('/control/{docente}/reactivar', 'reactivar')->whereNumber('docente')->name('control.reactivar');
                    Route::get('/asignaciones', 'asignaciones')->name('asignaciones.index');
                    Route::post('/asignaciones', 'asignar')->name('asignaciones.store');
                    Route::post('/asignaciones/guias', 'asignarGuia')->name('guias.store');
                    Route::patch('/asignaciones/guias/{guia}/finalizar', 'finalizarGuia')->whereNumber('guia')->name('guias.finalizar');
                    Route::patch('/asignaciones/{asignacion}/finalizar', 'finalizarAsignacion')->whereNumber('asignacion')->name('asignaciones.finalizar');
                    Route::get('/{docente}', 'show')->whereNumber('docente')->name('show');

                });

                /*
                |--------------------------------------------------------------------------
                | Agenda: gestion, control y eventos
                |--------------------------------------------------------------------------
                */

                Route::prefix('agenda')->name('agenda.')->controller(AgendaController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/crear', 'create')->name('create');
                    Route::post('/', 'store')->name('store');
                    Route::get('/gestionar', 'gestionar')->name('gestionar');
                    Route::get('/{evento}/editar', 'edit')->whereNumber('evento')->name('edit');
                    Route::put('/{evento}', 'update')->whereNumber('evento')->name('update');
                    Route::patch('/{evento}/estado', 'estado')->whereNumber('evento')->name('estado');
                    Route::get('/calendario-escolar', 'calendarioEscolar')->name('calendario-escolar.index');
                    Route::post('/calendario-escolar', 'guardarDiaEscolar')->name('calendario-escolar.store');
                    Route::delete('/calendario-escolar/{dia}', 'eliminarDiaEscolar')->name('calendario-escolar.destroy');
                    Route::get('/recordatorios', 'recordatorios')->name('recordatorios.index');
                });

                Route::prefix('horarios/cargas')->name('horarios.cargas.')
                    ->controller(CargaAcademicaController::class)->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::post('/', 'guardar')->name('guardar');
                    });

                Route::prefix('horarios/restricciones')->name('horarios.restricciones.')
                    ->controller(RestriccionHorarioController::class)->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::post('/', 'store')->name('store');
                        Route::put('/{restriccion}', 'update')->whereNumber('restriccion')->name('update');
                        Route::delete('/{restriccion}', 'destroy')->whereNumber('restriccion')->name('destroy');
                    });

                /*
                |--------------------------------------------------------------------------
                | Horarios académicos: generación, propuestas y aprobación
                |--------------------------------------------------------------------------
                */

                Route::prefix('horarios')->name('horarios.')
                    ->controller(HorarioAcademicoController::class)->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::post('/generar', 'generar')->name('generar');
                        Route::post('/guardar', 'guardar')->name('guardar');
                        Route::post('/{horario}/aprobar', 'aprobar')->whereNumber('horario')->name('aprobar');
                    });

                Route::prefix('horarios/bloques')->name('horarios.bloques.')
                    ->controller(BloqueHorarioController::class)->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::get('/crear', 'create')->name('create');
                        Route::post('/', 'store')->name('store');
                        Route::get('/{bloque}/editar', 'edit')->whereNumber('bloque')->name('edit');
                        Route::put('/{bloque}', 'update')->whereNumber('bloque')->name('update');
                    });

                    Route::patch('/horarios/{horario}/detalles/{detalle}', [\App\Http\Controllers\Direccion\HorarioAcademicoController::class, 'actualizarDetalle'])
                    ->whereNumber('horario')
                    ->whereNumber('detalle')
                    ->name('horarios.detalles.actualizar');

        });

    /*
    |--------------------------------------------------------------------------
    | Administrador
    |--------------------------------------------------------------------------
    */

    Route::prefix('administrador')
        ->name('administrador.')
        ->middleware('rol:ADMINISTRADOR')
        ->group(function () {

            Route::view(
                '/dashboard',
                'administrador.dashboard'
            )->name('dashboard');

        });

    /*
    |--------------------------------------------------------------------------
    | Secretaría
    |--------------------------------------------------------------------------
    */

    Route::prefix('secretaria')
        ->name('secretaria.')
        ->middleware('rol:SECRETARIA')
        ->group(function () {

            Route::view(
                '/dashboard',
                'secretaria.dashboard'
            )->name('dashboard');

        });

    /*
    |--------------------------------------------------------------------------
    | Docente
    |--------------------------------------------------------------------------
    */

    Route::prefix('docente')
        ->name('docente.')
        ->middleware('rol:DOCENTE')
        ->group(function () {

            Route::view(
                '/dashboard',
                'secretaria.dashboard'
            )->name('dashboard');

        });

    /*
    |--------------------------------------------------------------------------
    | Padre de Familia
    |--------------------------------------------------------------------------
    */

    Route::prefix('padre-familia')
        ->name('padre-familia.')
        ->middleware('rol:PADRE_FAMILIA')
        ->group(function () {

            Route::view(
                '/dashboard',
                'padre-familia.dashboard'
            )->name('dashboard');

        });


    Route::prefix('notificaciones')->name('notificaciones.')
    ->controller(NotificacionController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::patch('/leer-todas', 'leerTodas')->name('leer-todas');
        Route::patch('/{notificacion}/leer', 'leer')->whereNumber('notificacion')->name('leer');
    });

});
