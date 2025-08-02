<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\CuestionarioController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\CursosController;
use App\Models\Inscritos;
use Laravel\Sanctum\Sanctum;
use App\Http\Controllers\Api\DocenteController;
use App\Http\Controllers\Api\EstudianteController;
use App\Http\Controllers\ChatbotController;
use App\Models\Cursos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\BotManController;



Route::post('/chatbot', [ChatbotController::class, 'handle']);
Route::post('/chat', [ChatController::class, 'handleMessage']);
Route::post('/botman', [BotManController::class, 'handle']);
