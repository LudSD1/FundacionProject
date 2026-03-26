<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CursosController;
use App\Http\Controllers\InscritosController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ForoController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\BoletinController;
use App\Http\Controllers\CertificadoController;
use App\Http\Controllers\CuestionarioController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\PreguntaController;
use App\Http\Controllers\RespuestaController;
use App\Http\Controllers\RecursosController;
use App\Http\Controllers\TemaController;
use App\Http\Controllers\SubtemaController;
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\ExpositoresController;
use App\Http\Controllers\CursoImagenController;
use App\Http\Controllers\RecursoSubtemaController;
use App\Http\Controllers\UserAchievementsController;
use App\Models\RecursoSubtema;

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
    Route::post('QuitarForo/{id}', [ForoController::class, 'delete'])->name('quitarForo');
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
    // Route::get('DarAsistencia/cursoid={id}', [AsistenciaController::class, 'index2'])->name('darasistencias');
    // Route::post('DarAsistencia/cursoid={id}', [AsistenciaController::class, 'store2'])->name('darasistenciasPostIndividual');
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

    Route::post('/agregarLogro', [UserAchievementsController::class, 'storeLogro'])->name('logro.store');
    Route::patch('/actualizarLogro/{id}', [UserAchievementsController::class, 'updateLogro'])->name('update.logro');
    Route::post('/eliminarLogro/{id}', [UserAchievementsController::class, 'deleteLogro'])->name('delete.logro');
    Route::post('/restaurarLogro/{id}', [UserAchievementsController::class, 'restoreLogro'])->name('restore.logro');



});


