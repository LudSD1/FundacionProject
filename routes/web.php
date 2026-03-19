<?php

use App\Http\Controllers\ActividadCompletionController;
use App\Http\Controllers\ActividadController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
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
use App\Http\Controllers\NotaEntregaController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\BotManController;
use App\Http\Controllers\CursoCalificacionController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Storage;

Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle']);
Route::get('/mejores-cursos', [MenuController::class, 'mejoresCursosPorCategoria'])->name('mejores.cursos');

Route::get('/debug-auth', function () {
    dd([
        'auth()->check()' => auth()->check(),
        'auth()->user()' => auth()->user(),
        'session_id' => session()->getId(),
        'session_all' => session()->all(),
        'cookies' => request()->cookies->all(),
        'headers' => request()->headers->all(),
    ]);
});

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware(['signed'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::post('/email/resend-verification-notification', [EmailVerificationController::class, 'resend'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.resend');


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

Route::get('/curso/{curso}', [MenuController::class, 'detalle'])->name('curso.detalle');
Route::get('/congreso/{curso}', [MenuController::class, 'detalle'])->name('congreso.detalle');

// Ruta legacy para compatibilidad (redirige a la nueva ruta)
Route::get('/Detalle/{curso}', function ($curso) {
    try {
        // Intentar desencriptar si es un ID encriptado
        $cursoId = is_numeric($curso) ? $curso : decrypt($curso);
        $cursoModel = App\Models\Cursos::findOrFail($cursoId);

        // Redirigir a la nueva ruta según el tipo usando codigoCurso
        $routeName = $cursoModel->tipo === 'congreso' ? 'congreso.detalle' : 'curso.detalle';
        return redirect()->route($routeName, $cursoModel->codigoCurso ?? $cursoModel->id, 301);
    } catch (\Exception $e) {
        abort(404);
    }
})->name('evento.detalle');
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
    Route::get('/pago/{id}', [AportesController::class, 'factura'])->name('factura');
    Route::get('/RealizarPagos', [AportesController::class, 'indexStore'])->name('registrarpago');
    Route::post('/RealizarPagos', [AportesController::class, 'comprarCurso'])->name('registrarpagoPost');

    //Ver perfil del usuario logueado

    Route::get('/Miperfil', [UserController::class, 'UserProfile'])->name('Miperfil');
    Route::post('/Miperfil', [UserController::class, 'updateUserAvatar'])->name('avatar');



    //Editar Usuario Logueado

    Route::get('/EditarPerfil/{id}', [UserController::class, 'EditProfileIndex'])->name('EditarperfilIndex');
    Route::post('/EditarPerfil/{id}', [UserController::class, 'UserProfileEdit'])->name('EditarperfilPost');

    //Rutas Sesion
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/Inicio', [MenuController::class, 'index'])->name('Inicio');
    //ESTUDIANTE
    Route::group(['middleware' => ['role:Estudiante|Docente|Administrador', 'verified']], function () {

        //notification
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notificationes');
        Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
        Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        Route::delete('/notifications/{id}', [NotificationController::class, 'delete'])->name('notifications.delete');
        Route::delete('/notifications/delete-all-read', [NotificationController::class, 'deleteAllRead'])->name('notifications.delete-all-read');
        //endnotification
        Route::get('/recursos/{id}/descargar', [RecursosController::class, 'descargar'])->name('recursos.descargar');
        Route::post('/actividad/subir/{id}', [ActividadController::class, 'subirArchivo'])->name('subirArchivo');
        Route::get('/actividad/quitar/{id}', [ActividadController::class, 'quitarEntrega'])->name('quitarEntrega');
        Route::get('/ranking-quizz/{id}', [CuestionarioController::class, 'rankingQuizz'])->name('rankingQuizz');
        //Calendario
        Route::get('listaParticipantes/{id}', [CursosController::class, 'listaCurso'])->name('listacurso');
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
        //CAMBIARCONTRASEÑA
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
        Route::get('/factura-siat/{id}', [AportesController::class, 'verFactura'])->name('factura.siat');
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
