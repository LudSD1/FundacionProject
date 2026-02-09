<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InscritosController;
use App\Http\Controllers\ActividadCompletionController;
use App\Http\Controllers\RecursoSubtemaController;

    Route::group(['middleware' => ['role:Estudiante', 'verified']], function () {
        Route::post('/Inscribirse-Curso/{id}', [InscritosController::class, 'storeCongreso'])
            ->name('inscribirse_congreso');
        Route::post('/recurso/{recurso}/marcar-visto', [RecursoSubtemaController::class, 'marcarRecursoComoVisto'])->name('recurso.marcarVisto');
        Route::post('/foros/{id}/completar', [ActividadCompletionController::class, 'marcarForoCompletado'])->name('foros.completar');
        Route::post('/evaluaciones/{id}/completar', [ActividadCompletionController::class, 'marcarEvaluacionCompletada'])->name('evaluaciones.completar');
        Route::post('/recursos/{id}/completar', [ActividadCompletionController::class, 'marcarRecursoCompletado'])->name('recursos.completar');
    });