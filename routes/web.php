<?php

use App\Http\Controllers\ActividadCompletionController;
use App\Http\Controllers\ActividadController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\AportesController;
use App\Http\Controllers\CursosController;
use App\Http\Controllers\RecursosController;
use App\Http\Controllers\InscritosController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ForoController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\BoletinController;
use App\Http\Controllers\CertificadoController;
use App\Http\Controllers\CuestionarioController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\NotaEntregaController;
use App\Http\Controllers\PreguntaController;
use App\Http\Controllers\TemaController;
use App\Http\Controllers\SubtemaController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\RecursoSubtemaController;
use App\Http\Controllers\BotManController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CursoCalificacionController;
use App\Http\Controllers\CursoImagenController;
use App\Http\Controllers\ExpositoresController;
use App\Http\Controllers\OpenAIController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\RespuestaController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\XPController;
use Illuminate\Support\Facades\Storage;

Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle']);
Route::get('/mejores-cursos', [MenuController::class, 'mejoresCursosPorCategoria'])->name('mejores.cursos');
Route::get('/botman/tinker', function () {
    return view('botman.tinker');
});

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{user}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware(['signed' ])
    ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::post('/email/resend-verification-notification', [EmailVerificationController::class, 'resend'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.resend');



Route::middleware(['auth'])->group(function () {
    Route::get('/chat', [OpenAIController::class, 'showChat'])
        ->name('chat.show');

    Route::post('/chat', [OpenAIController::class, 'sendMessage'])
        ->name('chat.send')
        ->middleware('throttle:60,1'); // Rate limiting

    // Ruta para logros y niveles
    Route::get('/profile/achievements', [AchievementController::class, 'index'])->name('profile.achievements');

    // Ruta para ver XP y logros
    Route::get('/perfil/xp', [XPController::class, 'index'])->name('perfil.xp');
});

Route::get('/login', function () {
    return view('login');
})->middleware('noCache')->name('login');


Route::get('/registro', function () {
    return view('CrearUsuario.registrarse');
})->middleware('noCache')->name('signin');

Route::post('/resgistrarse', [UserController::class, 'storeUsuario'])->name('registrarse');

Route::post('/resgistrarse/Congreso/{id}', [CertificadoController::class, 'register'])->name('registrarseCongreso');
Route::post('/congreso/inscribir', [CertificadoController::class, 'inscribir'])
    ->name('congreso.inscribir');

Route::get('/Detalle/{curso}', [MenuController::class, 'detalle'])->name('evento.detalle');
Route::get('/Lista-General', [MenuController::class, 'lista'])->name('lista.cursos.congresos');
Route::get('/', [MenuController::class, 'home'])->middleware('noCache')->name('home');




// Ruta para mostrar el formulario de solicitud de restablecimiento
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->middleware('guest')
    ->name('password.request');

// Ruta para procesar la solicitud de enlace de restablecimiento
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->middleware('guest')
    ->name('password.email');

// Ruta para mostrar el formulario de restablecimiento de contraseña
Route::get('/password/reset/{token}', [ForgotPasswordController::class, 'showResetForm'])
    ->middleware('guest')
    ->name('password.reset');

// Ruta para procesar el restablecimiento de contraseña
Route::post('/password/reset', [ForgotPasswordController::class, 'reset'])
    ->middleware('guest')
    ->name('password.update');


Route::fallback(function () {
    return view('errors.404');
});

Route::get('/cuestionario', [CuestionarioController::class, 'responder']);


Route::post('/login', [UserController::class, 'authenticate'])->name('login.signin');
Route::get('/verificar-certificado/{codigo}', [CertificadoController::class, 'verificarCertificado'])->name('verificar.certificado');



Route::group(['middleware' => ['auth']], function () {

    Route::post('/cuestionarios/{cuestionario}/registrar-abandono', [CuestionarioController::class, 'registrarAbandono'])
        ->name('cuestionarios.registrar-abandono');

    // RUTAS DE PAGO - Sin middleware de roles ni verificación para evitar interrupciones
    Route::get('/pago/{$id}', [AportesController::class, 'factura'])->name('factura');
    Route::get('/RealizarPagos', [AportesController::class, 'indexStore'])->name('registrarpago');
    Route::post('/RealizarPagos', [AportesController::class, 'comprarCurso'])->name('registrarpagoPost');


    //Solo Estudiante
    Route::group(['middleware' => ['role:Estudiante', 'verified']], function () {
        Route::post('/Inscribirse-Curso/{id}', [InscritosController::class, 'storeCongreso'])
            ->name('inscribirse_congreso');
        Route::post('/recurso/{recurso}/marcar-visto', [RecursoSubtemaController::class, 'marcarRecursoComoVisto'])->name('recurso.marcarVisto');
        Route::post('/foros/{id}/completar', [ActividadCompletionController::class, 'marcarForoCompletado'])->name('foros.completar');
        Route::post('/evaluaciones/{id}/completar', [ActividadCompletionController::class, 'marcarEvaluacionCompletada'])->name('evaluaciones.completar');
        Route::post('/recursos/{id}/completar', [ActividadCompletionController::class, 'marcarRecursoCompletado'])->name('recursos.completar');
    });




    //Ver perfil del usuario logueado

    Route::get('/Miperfil', [UserController::class, 'UserProfile'])->name('Miperfil');
    Route::post('/Miperfil', [UserController::class, 'updateUserAvatar'])->name('avatar');



    //Editar Usuario Logueado

    Route::get('/EditarPerfil/{id}', [UserController::class, 'EditProfileIndex'])->name('EditarperfilIndex');
    Route::post('/EditarPerfil/{id}', [UserController::class, 'UserProfileEdit'])->name('EditarperfilPost');

    //Rutas Sesion
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/Inicio', [MenuController::class, 'index'])->name('Inicio');

    Route::group(['middleware' => ['role:Administrador', 'verified']], function () {

        Route::post('/HabilitarCurso/{id}', [AportesController::class, 'habilitarCurso'])->name('habilitar.curso');

        Route::post('/cambiar-rol/{usuario}', [AdministradorController::class, 'cambiarRol'])->name('CambiarRolUser');

        Route::post('/reenviar-recibo/{id}', [AportesController::class, 'reenviarRecibo'])->name('recibo.reenviar')->middleware('auth');
        Route::post('/Curso/{id}', [CursosController::class, 'update'])->name('cursos.update');
        Route::get('/import/users', [App\Http\Controllers\ImportController::class, 'showImportForm'])->name('import.users.form');
        Route::post('/import/users', [App\Http\Controllers\ImportController::class, 'importUsers'])->name('import.users');

        Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
        Route::post('/categorias', [CategoriaController::class, 'store'])->name('categorias.store');
        Route::put('/categorias/{categoria}', [CategoriaController::class, 'update'])->name('categorias.update');
        Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');
        Route::post('/categorias/{id}/restore', [CategoriaController::class, 'restore'])->name('categorias.restore');
        Route::delete('/categorias/{id}/force', [CategoriaController::class, 'forceDelete'])
            ->name('categorias.forceDelete');

        Route::get('certificadosCongreso/generarAdm/{id}/', [CertificadoController::class, 'generarCertificadoAdmin'])->name('certificadosCongreso.generar.admin');



        // Route::get('/certificates', [cer::class, 'index'])->name('certificates.index');


        //Pagos
        Route::get('/CrearPagos', [AportesController::class, 'indexAdmin'])->name('registrarpagoadmin');
        Route::post('/CrearPagos', [AportesController::class, 'storeadmin'])->name('registrarpagopost');
        Route::get('/VistaPrevia/{id}', [AportesController::class, 'vistaPrevia'])->name('vistaprevia');


        //EditarUsuarios
        Route::get('/EditarUsuario/{user}', [AdministradorController::class, 'EditUserIndex']);
        Route::post('/EditarUsuario/{id}', [AdministradorController::class, 'EditUser'])->name('EditarperfilUser');
        Route::get('/RestaurarUsuario/{id}', [UserController::class, 'restaurarUsuario'])->name('restaurarUsuario');
        Route::get('/admin/logs', [AdministradorController::class, 'viewLogs'])->name('admin.logs');
        Route::get('/admin/test-log', [AdministradorController::class, 'testLog'])->name('admin.test.log');

        //Administrador/Docentes Rutas
        Route::get('/ListaDocente', [MenuController::class, 'ListaDocentes'])->name('ListaDocentes');
        Route::get('/ListaDocentesEliminados', [MenuController::class, 'ListaDocentesEliminados'])->name('DocentesEliminados');
        Route::get('/CrearDocente', [MenuController::class, 'storeDIndex'])->name('CrearDocente');
        Route::post('/CrearDocente', [AdministradorController::class, 'storeDocente'])->name('CrearDocentePost');
        Route::post('/EliminarUsuario/{id}', [UserController::class, 'delete'])->name('deleteUser');
        //Expositores

        Route::get('/expositores', [ExpositoresController::class, 'ListaExpositores'])->name('ListaExpositores');
        Route::post('/expositores', [ExpositoresController::class, 'store'])->name('expositores.store');
        Route::get('/expositores/{id}/edit', [ExpositoresController::class, 'edit']);
        Route::put('/expositores/{id}', [ExpositoresController::class, 'update'])->name('expositores.update');
        Route::delete('/expositores/{id}', [ExpositoresController::class, 'destroy'])->name('expositores.destroy');
        Route::post('/expositores/{id}/restore', [ExpositoresController::class, 'restore'])->name('expositores.restore');





        //Administrador/Cursos
        Route::get('/ListadeCursos', [MenuController::class, 'ListaDeCursos'])->name('ListadeCursos');
        Route::get('/ListaCursosCerrados', [MenuController::class, 'ListaDeCursosEliminados'])->name('ListadeCursosEliminados');
        Route::get('/CrearCursos', [MenuController::class, 'storeCIndex'])->name('CrearCurso');
        Route::post('/CrearCursos', [AdministradorController::class, 'storeCurso'])->name('CrearCursoPost');

        Route::get('/EliminarCurso/{id}', [CursosController::class, 'eliminarCurso'])->name('quitarCurso');
        Route::get('/RetaurarCurso/{id}', [CursosController::class, 'restaurarCurso'])->name('restaurarCurso');

        //Route::post('/CrearCursos', [CursosController::class, 'store'])->name('storeCursos');
        //Administrador/Estudiantes
        Route::get('/ListaEstudiante', [MenuController::class, 'ListaEstudiantes'])->name('ListaEstudiantes');
        Route::get('/ListaEstudianteEliminados', [MenuController::class, 'ListaEstudiantesEliminados'])->name('ListaEstudiantesEliminados');
        Route::get('/CrearEstudiante', [MenuController::class, 'storeEIndex'])->name('CrearEstudiante');
        Route::post('/CrearEstudiante', [AdministradorController::class, 'storeEstudiante'])->name('CrearEstudiantePost');
        Route::get('/CrearEstudianteMenor', [MenuController::class, 'storeETIndex'])->name('CrearEstudianteMenor');
        Route::post('/CrearEstudianteMenor', [AdministradorController::class, 'storeEstudianteMenor'])->name('CrearEstudianteMenorPost');

        Route::get('/ListaAportes', [MenuController::class, 'ListaAportes'])->name('aportesLista');

        Route::put('/ActualizarPago/{codigopago}', [AportesController::class, 'actualizarPago'])->name('pagos.update');

        // Rutas para métodos de pago
        Route::resource('payment-methods', PaymentMethodController::class);

        Route::patch('/payment-methods/{paymentMethod}/toggle-status', [PaymentMethodController::class, 'toggleStatus'])->name('payment-methods.toggle-status');
        Route::patch('/payment-methods/{id}/restore', [PaymentMethodController::class, 'restore'])->name('payment-methods.restore');

        Route::get('/validar-certificado/{codigo}', [CertificadoController::class, 'validarCertificado']);
        Route::put('/listaParticipantes/{inscrito}/actualizar-pago', [InscritosController::class, 'actualizarPago'])->name('curso.actualizarPago');
        Route::get('/backups', [AdministradorController::class, 'listBackups'])->name('admin.backups');
        Route::post('/backups', [AdministradorController::class, 'createBackup'])->name('admin.backup.create');
        Route::get('/backups/download/{filename}', [AdministradorController::class, 'downloadBackup'])->name('admin.backup.download');
        Route::delete('/backups/{filename}', [AdministradorController::class, 'deleteBackup'])->name('admin.backup.delete');
    });


    //DOCENTE
    Route::group(['middleware' => ['role:Docente|Administrador', 'verified']], function () {

        Route::post('/cursos/{curso}/asignar-expositores', [ExpositoresController::class, 'asignarExpositores'])->name('cursos.asignarExpositores');
        Route::delete('/cursos/{curso}/expositores/{expositor}', [ExpositoresController::class, 'quitarExpositor'])->name('cursos.quitarExpositor');
        Route::put('/cursos/{id}', [CursosController::class, 'updateCategories'])->name('cursos.updateCategories');

        Route::post('/cursos/{id}/activar-certificados', [CursosController::class, 'activarCertificados'])
            ->name('cursos.activarCertificados');

        Route::get('/certificados/vista-previa/{curso_id}', [CertificadoController::class, 'vistaPreviaCertificado'])
            ->name('certificados.vistaPrevia');

        Route::post('/certificates/{id}', [CertificadoController::class, 'store'])->name('certificates.store');
        Route::post('/certificates/update/{id}', [CertificadoController::class, 'update'])->name('certificates.update');
        Route::delete('/certificates-delete/{id}', [CertificadoController::class, 'destroy'])->name('certificates.destroy');
        Route::get('/certificados/reenviar/{inscrito_id}', [CertificadoController::class, 'reenviarCertificadoPorEmail'])->name('certificados.reenviar.email');
        //Curso
        Route::get('/sumario',  [MenuController::class, 'analytics'])->name('sumario');
        Route::get('/getEstudiantesNoInscritos/{curso_id}', [InscritosController::class, 'getEstudiantesNoInscritos']);

        Route::prefix('cursos/{curso}')->group(function () {
            Route::get('imagenes', [CursoImagenController::class, 'index'])->name('curso-imagenes.index');
            Route::post('imagenes', [CursoImagenController::class, 'store'])->name('curso-imagenes.store');
        });

        Route::put('curso-imagenes/{imagen}', [CursoImagenController::class, 'update'])->name('curso-imagenes.update');
        Route::delete('curso-imagenes/{imagen}', [CursoImagenController::class, 'destroy'])->name('curso-imagenes.destroy');
        Route::put('curso-imagenes/{imagen}/restaurar', [CursoImagenController::class, 'restore'])->name('curso-imagenes.restore');
        Route::post('/cursos/{curso}/editar-youtube', [CursosController::class, 'updateYoutube'])->name('cursos.updateYoutube');

        //HORARIO
        Route::post('/store', [HorarioController::class, 'store'])->name('horarios.store');
        Route::post('/horarios/{id}', [HorarioController::class, 'update'])->name('horarios.update');
        Route::delete('/horarios/{id}', [HorarioController::class, 'delete'])->name('horarios.delete');
        Route::post('/horarios/{id}/restore', [HorarioController::class, 'restore'])->name('horarios.restore');


        //Cuestionarios

        Route::delete('/intentos/{intentoId}/eliminar', [CuestionarioController::class, 'eliminarIntento'])->name('intentos.eliminar');
        Route::delete('/cuestionarios/{id}/eliminar', [CuestionarioController::class, 'eliminarCuestionario'])->name('cuestionarios.eliminar');
        Route::get('/cuestionarios/{cuestionarioId}/intentos/{intentoId}/revisar', [CuestionarioController::class, 'revisarIntento'])->name('cuestionarios.revisarIntento');
        Route::post('/cuestionarios/{cuestionarioId}/intentos/{intentoId}/actualizar', [CuestionarioController::class, 'actualizarNota'])->name('cuestionarios.actualizarNota');
        Route::get('/cuestionarios/{id}', [CuestionarioController::class, 'index'])->name('cuestionarios.index');
        Route::post('/cuestionarios/{actividadId}/store', [CuestionarioController::class, 'store'])->name('cuestionarios.store');
        Route::put('/cuestionarios/update/{id}', [CuestionarioController::class, 'update'])->name('cuestionarios.update');


        //Preguntas
        Route::post('/cuestionarios/{cuestionarioId}/preguntas/multiple', [PreguntaController::class, 'store'])->name('pregunta.store');
        Route::post('/Pregunta/{id}/edit', [PreguntaController::class, 'update'])->name('pregunta.update');
        Route::post('/Pregunta/{id}/delete', [PreguntaController::class, 'delete'])->name('pregunta.delete');
        Route::post('/Pregunta/{id}/restore', [PreguntaController::class, 'restore'])->name('pregunta.restore');

        //Opciones
        Route::post('/preguntas/{preguntaId}/respuestas/multiple', [RespuestaController::class, 'storeMultiple'])->name('respuestas.storeMultiple');
        Route::post('/preguntas/{preguntaId}/respuestas/storeVerdaderoFalso', [RespuestaController::class, 'storeVerdaderoFalso'])->name('respuestas.storeVerdaderoFalso');
        Route::post('/preguntas/{preguntaId}/respuestas', [RespuestaController::class, 'store'])->name('respuestas.store');
        Route::put('/respuestas/{id}', [RespuestaController::class, 'update'])->name('respuestas.update');
        Route::delete('/respuestas/{id}', [RespuestaController::class, 'delete'])->name('respuestas.delete');
        Route::post('/respuestas/{id}/restore', [RespuestaController::class, 'restore'])->name('respuestas.restore');

        //Respuestas

        Route::post('/cuestionarios/{id}/respuestas', [CuestionarioController::class, 'storeRespuestas'])->name('cuestionarios.storeRespuestas');
        Route::post('/preguntas/{id}/respuestas-clave', [RespuestaController::class, 'storeRespuestasClave'])->name('respuestas.storeRespuestasClave');


        //EditarCursos
        Route::get('/EditarCurso/{id}', [CursosController::class, 'EditCIndex'])->name('editarCurso');
        Route::post('/EditarCurso/{id}', [CursosController::class, 'EditC'])->name('editarCursoPost');

        //Foros
        Route::get('CrearForo/cursoid={id}', [ForoController::class, 'Crearforo'])->name('CrearForo');
        Route::post('CrearForo/cursoid={id}', [ForoController::class, 'store'])->name('CrearForoPost');
        Route::get('EditarForo/{id}', [ForoController::class, 'EditarForoIndex'])->name('EditarForo');
        Route::post('EditarForo/{id}', [ForoController::class, 'update'])->name('EditarForoPost');
        Route::get('QuitarForo/{id}', [ForoController::class, 'delete'])->name('quitarForo');
        Route::get('ForosEliminados/{id}', [ForoController::class, 'indexE'])->name('forosE');
        Route::get('RestaurarForo/{id}', [ForoController::class, 'restore'])->name('restaurar');

        // Temas
        Route::get('/curso/{cursoId}/temas', [TemaController::class, 'index'])->name('temas.index');
        Route::post('/curso/{cursoId}/temas', [TemaController::class, 'store'])->name('temas.store');
        Route::post('/curso/{cursoId}/temas/update', [TemaController::class, 'update'])->name('temas.update');
        Route::delete('/curso/{cursoId}/temas/delete', [TemaController::class, 'destroy'])->name('temas.delete');
        Route::post('/curso/{cursoId}/temas/restore', [TemaController::class, 'restore'])->name('temas.restore');

        // Subtemas
        Route::post('/tema/{temaId}/subtemas', [SubtemaController::class, 'store'])->name('subtemas.store');
        Route::post('/tema/{temaId}/subtemas/update', [SubtemaController::class, 'update'])->name('subtemas.update');
        Route::delete('/tema/{temaId}/subtemas/delete', [SubtemaController::class, 'delete'])->name('subtemas.delete');
        Route::post('/tema/{temaId}/subtemas/restore', [SubtemaController::class, 'restore'])->name('subtemas.restore');

        //Actividades

        Route::post('/actividades/{subtema}', [ActividadController::class, 'store'])->name('actividades.store');
        Route::get('/actividades/{id}', [ActividadController::class, 'show'])->name('actividades.show');
        Route::put('/actividades/update/{id}', [ActividadController::class, 'update'])->name('actividades.update');
        Route::delete('/actividades/{id}', [ActividadController::class, 'destroy'])->name('actividades.destroy');
        Route::patch('/actividades/{id}/ocultar', [ActividadController::class, 'ocultar'])->name('actividades.ocultar');
        Route::patch('/actividades/{id}/mostrar', [ActividadController::class, 'mostrar'])->name('actividades.mostrar');
        Route::get('/actividad/calificar/{id}', [ActividadController::class, 'listadeEntregas'])->name('calificarT');
        Route::post('/actividad/calificar/{id}', [ActividadController::class, 'listadeEntregasCalificar'])->name('entregas.calificar');

        //RecursosGlobal

        Route::get('CrearRecurso/cursoid={id}', [RecursosController::class, 'index'])->name('CrearRecursos');
        Route::post('CrearRecurso/cursoid={id}', [RecursosController::class, 'store'])->name('CrearRecursosPost');
        Route::get('ModificarRecurso/cursoid={id}', [RecursosController::class, 'edit'])->name('editarRecursos');
        Route::post('ModificarRecurso/cursoid={id}', [RecursosController::class, 'update'])->name('editarRecursosPost');
        Route::get('QuitarRecurso/{id}', [RecursosController::class, 'delete'])->name('quitarRecurso');
        Route::get('RecursosEliminados/cursoid={id}', [RecursosController::class, 'indexE'])->name('ListaRecursosEliminados');
        Route::get('RestaurarRecurso/{id}', [RecursosController::class, 'restore'])->name('RestaurarRecurso');

        //RecursosSubtema
        Route::post('CrearRecursoSubtema/cursoid={id}', [RecursoSubtemaController::class, 'store'])->name('CrearRecursosSubtemaPost');
        Route::put('ModificarRecursoSubtema/cursoid={id}', [RecursoSubtemaController::class, 'update'])->name('editarRecursosSubtemaPost');
        Route::delete('QuitarRecursoSubtema/{id}', [RecursoSubtemaController::class, 'delete'])->name('eliminarRecursosSubtemaPost');
        Route::get('RestaurarRecursoSubtema/{id}', [RecursoSubtemaController::class, 'restore'])->name('restaurarRecursoSubtema');

        //AsignarCursos
        Route::get('/AsignarCursos', [InscritosController::class, 'index'])->name('AsignarCurso');
        Route::post('/AsignarCursos', [InscritosController::class, 'store'])->name('inscribir');
        //QuitarInscripcion

        Route::post('/quitarInscripcion/{id}', [InscritosController::class, 'quitarInscripcion'])->name('quitarInscripcion');

        Route::get('/RestaurarInscripcion/{id}', [InscritosController::class, 'restaurarInscrito'])->name('restaurarIncripcion');
        //ListaDeInscritos
        Route::get('listaRetirados/cursoid={id}', [CursosController::class, 'listaRetirados'])->name('listaretirados');
        // Retirar estudiantes masivamente
        Route::post('/cursos/retirar-masivo', [InscritosController::class, 'retirarEstudiantesMasivo'])
            ->name('cursos.retirarMasivo');

        // Restaurar estudiantes masivamente
        Route::post('/cursos/restaurar-masivo', [InscritosController::class, 'restaurarEstudiantesMasivo'])
            ->name('cursos.restaurarMasivo');

        // Restaurar todos los estudiantes de un curso
        Route::post('/cursos/{cursoId}/restaurar-todos', [InscritosController::class, 'restaurarTodosEstudiantes'])
            ->name('cursos.restaurarTodos');
        //ASISTENCIA
        Route::get('listaAsistencia/cursoid={id}', [AsistenciaController::class, 'show'])->name('asistencias');
        Route::get('DarAsistencia/cursoid={id}', [AsistenciaController::class, 'index2'])->name('darasistencias');
        Route::post('DarAsistencia/cursoid={id}', [AsistenciaController::class, 'store2'])->name('darasistenciasPostIndividual');
        Route::post('listaAsistencia/cursoid={id}', [AsistenciaController::class, 'store'])->name('darasistenciasPostMultiple');
        Route::post('HistorialAsistencia/cursoid={id}', [AsistenciaController::class, 'edit'])->name('historialAsistenciasPost');

        //REPORTES
        Route::get('ReportesAsistencia/{id}', [CursosController::class, 'ReporteAsistencia'])->name('repA');
        Route::get('Reportes/{id}', [CursosController::class, 'ReporteFinal'])->name('repF');

        //BOLETIN
        Route::get('/boletinDeCalificaciones/{id}', [BoletinController::class, 'boletin'])->name('boletin');
        Route::post('/boletinDeCalificaciones/{id}', [BoletinController::class, 'guardar_boletin'])->name('boletinPost');

        //Lista
        Route::get('/listaGeneral/{id}', [CursosController::class, 'imprimir'])->name('lista');
        //REPORTE GENERAL
        Route::get('/reportegeneralCurso/{id}', [CursosController::class, 'ReporteFinalCurso'])->name('rfc');


        Route::post('/enviar-boletin/{id}', [BoletinController::class, 'enviarBoletin'])->name('enviarBoletinPost');
        Route::post('/enviar-boletin/{id}', [BoletinController::class, 'enviarBoletin'])->name('enviarBoletinPost');

        //CERTIFICADOS
        Route::get('certificados/generar/{id}/', [CertificadoController::class, 'generarCertificado'])->name('certificados.generar');
        Route::get('certificadosCongreso/generar/{id}/', [CertificadoController::class, 'generarCertificadoCongreso'])->name('certificadosCongreso.generar');
        Route::get('completado/{curso_id}/{estudiante_id}', [InscritosController::class, 'completado'])->name('completado');

        Route::get('/cursos/{id}/elementos-eliminados', [CursosController::class, 'elementosEliminados'])->name('cursos.elementos-eliminados');
        Route::post('/cursos/restaurar-elemento', [CursosController::class, 'restaurarElemento'])->name('cursos.restaurar-elemento');
    });
    //ENDDOCENTE

    //ESTUDIANTE
    Route::group(['middleware' => ['role:Estudiante|Docente|Administrador', 'verified']], function () {


        //notification
        Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
        Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        Route::delete('/notifications/{id}', [NotificationController::class, 'delete'])->name('notifications.delete');
        Route::delete('/notifications/delete-all-read', [NotificationController::class, 'deleteAllRead'])->name('notifications.delete-all-read');
        //endnotification
        // Nueva ruta recomendada (por ID)
        Route::get('/recursos/{id}/descargar', [RecursosController::class, 'descargar'])->name('recursos.descargar');


        Route::post('/actividad/subir/{id}', [ActividadController::class, 'subirArchivo'])->name('subirArchivo');
        Route::get('/actividad/quitar/{id}', [ActividadController::class, 'quitarEntrega'])->name('quitarEntrega');


        Route::get('/ranking-quizz/{id}', [CuestionarioController::class, 'rankingQuizz'])->name('rankingQuizz');

        //Calendario
        Route::get('listaParticipantes/cursoid={id}', [CursosController::class, 'listaCurso'])->name('listacurso');
        // Ruta para obtener el certificado
        Route::post('/certificados/obtener/{id}', [CertificadoController::class, 'obtenerCertificado'])
            ->name('certificados.obtener');
        Route::get('/Notificaciones', [UserController::class, 'notificaciones'])->name('notificaciones');
        Route::get('/user/{id}', [UserController::class, 'Profile'])->name('perfil');

        Route::get('/Calendario', [MenuController::class, 'calendario'])->middleware('noCache')->name('calendario');
        //PAGOS (solo visualización, el proceso de pago está fuera del middleware verified)
        Route::get('/ListadePagos', [AportesController::class, 'index'])->name('pagos');

        //CURSO
        Route::get('/Cursos/{curso}', [CursosController::class, 'index'])->name('Curso');
        //FORO
        Route::get('/foro/id={id}', [ForoController::class, 'index'])->name('foro');
        Route::post('/foro/id={id}', [ForoController::class, 'storeMensaje'])->name('foro.mensaje.store');
        Route::post('/foro/mensaje/edit/{id}', [ForoController::class, 'editMensaje'])->name('foro.mensaje.edit');
        Route::post('/foro/mensaje/delete/{id}', [ForoController::class, 'deleteMensaje'])->name('foro.mensaje.delete');
        Route::post('/foro/respuesta/edit/{id}', [ForoController::class, 'editRespuesta'])->name('foro.respuesta.edit');
        Route::post('/foro/respuesta/delete/{id}', [ForoController::class, 'deleteRespuesta'])->name('foro.respuesta.delete');
        //RECURSOS
        Route::get('VerRecursos/cursoid={id}', [RecursosController::class, 'showRecurso'])->name('VerRecursos');
        //Actividad Subida de archivos
        Route::get('VerActividad/{id}', [ActividadController::class, 'index'])->name('actividad.show');
        //Evaluaciones
        Route::get('/descargar-archivo/{nombreArchivo}', [CursosController::class, 'descargar'])->name('descargas');
        Route::get('/verBoletin/{id}', [BoletinController::class, 'boletinEstudiantes'])->name('verBoletin');
        Route::get('/verCalificacionFinal/{id}', [BoletinController::class, 'boletinEstudiantes2'])->name('verBoletin2');
        Route::get('/validar-certificado/{codigo}', [CertificadoController::class, 'validarCertificado']);
        Route::get('/ResolverCuestionario/{id}', [CuestionarioController::class, 'cuestionarioTSolve'])->name('resolvercuestionario');
        //MarcarCompleto
        Route::post('/tarea/{tarea}/completar', [ActividadCompletionController::class, 'marcarTareaCompletada'])->name('tarea.completar');
        Route::post('/cuestionario/{cuestionario}/completar', [ActividadCompletionController::class, 'marcarCuestionarioCompletado'])->name('cuestionario.completar');
        //ENDESTUDIANTE
        Route::post('/guardar-resultados', [NotaEntregaController::class, 'CuestionarioResultado'])->name('guardar.resultados');
        Route::post('/actividad/{actividad}/completar', [ActividadController::class, 'completarActividad'])->name('actividad.completar');
        //CAMBIARcONTRASEÑA
        Route::get('CambiarContrasena/{id}', [UserController::class, 'EditPasswordIndex'])->name('CambiarContrasena');
        Route::post('CambiarContrasena/{id}', [UserController::class, 'CambiarContrasena'])->name('cambiarContrasenaPost');

        Route::get('HistorialAsistencia/cursoid={id}', [AsistenciaController::class, 'historialAsistencia'])->name('historialAsistencias');
        //CUESTIONARIO
        Route::get('/cuestionario/{id}/responder', [CuestionarioController::class, 'mostrarCuestionario'])->name('cuestionario.mostrar');
        Route::post('/cuestionarios/{id}/responder', [CuestionarioController::class, 'procesarRespuestas'])->name('responderCuestionario');
        Route::post('/cuestionarios/{id}/abandonar', [CuestionarioController::class, 'registrarAbandono'])->name('cuestionario.abandonar');

        Route::get('/descargar-comprobante/{ruta}', function ($ruta) {
            $rutaCompleta = "comprobantes/" . $ruta;
            return Storage::disk('public')->download($rutaCompleta);
        })->name('descargar.comprobante');
    });

    // Rutas para calificaciones de cursos
    Route::middleware(['auth'])->group(function () {
        // Guardar calificación
        Route::post('/cursos/{curso}/calificar', [CursoCalificacionController::class, 'store'])
            ->name('cursos.calificar');

        // Actualizar calificación
        Route::put('/calificaciones/{id}', [CursoCalificacionController::class, 'update'])
            ->name('calificaciones.update')
            ->middleware('auth');
        Route::delete('/calificaciones/{calificacion}', [CursoCalificacionController::class, 'destroy'])
            ->name('calificaciones.destroy');

        // Ver todas las calificaciones (opcional)
        Route::get('/cursos/{curso}/calificaciones', [CursoCalificacionController::class, 'index'])
            ->name('cursos.calificaciones');

        Route::get('/recibo/{id}', [AportesController::class, 'generarRecibo'])->name('recibo.generar');
    });
    Route::get('/recibo/verificar/{codigo}', [AportesController::class, 'verificarReciboPorCodigo'])
        ->name('recibo.verificar');



    Route::get('certificado/qr/{codigo}', [CertificadoController::class, 'descargarQR'])->name('descargar.qr');
    Route::get('qr/{codigo}', [CertificadoController::class, 'generarQR'])->name('certificado.qr');
    //QR
    // Ruta para inscribirse utilizando el QR
    Route::get('/inscribirse/{id}/{token}', [InscritosController::class, 'inscribirse'])->name('inscribirse.qr');

    // Rutas para el sistema de logros
    Route::post('/achievements/unlock', [AchievementController::class, 'unlockAchievement'])->name('achievements.unlock');
    Route::get('/achievements/progress', [AchievementController::class, 'getProgress'])->name('achievements.progress');
});
