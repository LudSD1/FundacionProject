<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\AportesController;
use App\Http\Controllers\CursosController;
use App\Http\Controllers\InscritosController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CertificadoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ExpositoresController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\UserController;


    Route::group(['middleware' => ['role:Administrador', 'verified']], function () {

        Route::post('/HabilitarCurso/{id}', [AportesController::class, 'habilitarCurso'])->name('habilitar.curso');
        Route::post('/cambiar-rol/{usuario}', [AdministradorController::class, 'cambiarRol'])->name('CambiarRolUser');
        Route::post('/reenviar-recibo/{id}', [AportesController::class, 'reenviarRecibo'])->name('recibo.reenviar')->middleware('auth');
        Route::post('/Curso/{id}', [CursosController::class, 'update'])->name('cursos.update');
        Route::post('/ListadeCursos}', [CursosController::class, 'index'])->name('ListadeCursos');
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
        Route::get('/CrearPagos', [AportesController::class, 'indexAdmin'])->name('registrarpagoadmin');
        Route::post('/CrearPagos', [AportesController::class, 'storeadmin'])->name('registrarpagopost');
        Route::get('/VistaPrevia/{id}', [AportesController::class, 'vistaPrevia'])->name('vistaprevia');
        Route::get('/EditarUsuario/{user}', [AdministradorController::class, 'EditUserIndex']);
        Route::post('/EditarUsuario/{id}', [AdministradorController::class, 'EditUser'])->name('EditarperfilUser');
        Route::get('/RestaurarUsuario/{id}', [UserController::class, 'restaurarUsuario'])->name('restaurarUsuario');
        Route::get('/admin/logs', [AdministradorController::class, 'viewLogs'])->name('admin.logs');
        Route::get('/admin/test-log', [AdministradorController::class, 'testLog'])->name('admin.test.log');
        Route::get('/ListadeUsuarios', [MenuController::class, 'ListaUsuarios'])->name('ListaUsuarios');
        Route::get('/ListaUsuariosEliminados', [MenuController::class, 'ListaUsuariosEliminados'])->name('ListaUsuariosEliminados');
        Route::get('/CrearUsuario', [MenuController::class, 'storeUIndex'])->name('CrearUsuario');
        Route::post('/CrearUsuario', [AdministradorController::class, 'storeUsuario'])->name('CrearDocentePost');
        Route::post('/EliminarUsuario/{id}', [UserController::class, 'delete'])->name('deleteUser');
        Route::get('/expositores', [ExpositoresController::class, 'ListaExpositores'])->name('ListaExpositores');
        Route::post('/expositores', [ExpositoresController::class, 'store'])->name('expositores.store');
        Route::get('/expositores/{id}/edit', [ExpositoresController::class, 'edit']);
        Route::put('/expositores/{id}', [ExpositoresController::class, 'update'])->name('expositores.update');
        Route::delete('/expositores/{id}', [ExpositoresController::class, 'destroy'])->name('expositores.destroy');
        Route::post('/expositores/{id}/restore', [ExpositoresController::class, 'restore'])->name('expositores.restore');
        //Administracion/Cursos
        Route::get('/ListadeCursos', [MenuController::class, 'ListaDeCursos'])->name('ListadeCursos');
        Route::get('/ListaCursosCerrados', [MenuController::class, 'ListaDeCursosEliminados'])->name('ListadeCursosEliminados');
        Route::get('/CrearCursos', [MenuController::class, 'storeCIndex'])->name('CrearCurso');
        Route::post('/CrearCursos', [AdministradorController::class, 'storeCurso'])->name('CrearCursoPost');
        Route::get('/EliminarCurso/{id}', [CursosController::class, 'eliminarCurso'])->name('quitarCurso');
        Route::get('/RetaurarCurso/{id}', [CursosController::class, 'restaurarCurso'])->name('restaurarCurso');
        Route::get('/ListaEstudiante', [MenuController::class, 'ListaEstudiantes'])->name('ListaEstudiantes');
        Route::get('/ListaEstudianteEliminados', [MenuController::class, 'ListaEstudiantesEliminados'])->name('ListaEstudiantesEliminados');
        Route::get('/ListaPagos', [MenuController::class, 'ListaAportes'])->name('aportesLista');
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
