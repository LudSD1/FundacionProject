<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\Messages\Incoming\Answer;
use Illuminate\Support\Facades\Log;
use App\Services\BotmanService;
use Illuminate\Http\Request;

class BotManController extends Controller
{
    protected $botmanService;

    public function __construct(BotmanService $botmanService)
    {
        $this->botmanService = $botmanService;
    }

    public function handle(Request $request)
    {
        try {
            // Validar que el mensaje existe
            if (!$request->has('message')) {
                Log::warning('BotMan: Intento de acceso sin mensaje');
                return response()->json([
                    'status' => 'error',
                    'message' => 'El mensaje es requerido'
                ], 400);
            }

            $message = $request->input('message');
            Log::info('BotMan: Mensaje recibido', ['message' => $message]);

            // Validar longitud del mensaje
            if (strlen($message) > 1000) {
                Log::warning('BotMan: Mensaje demasiado largo', ['length' => strlen($message)]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'El mensaje es demasiado largo'
                ], 400);
            }

            // Procesar el mensaje usando el servicio
            $this->botmanService->handleMessage($message);

            // Obtener los mensajes del servicio
            $messages = $this->botmanService->getMessages();

            // Verificar que hay mensajes para enviar
            if (empty($messages)) {
                Log::warning('BotMan: No se generaron respuestas', ['input' => $message]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'No se pudo procesar el mensaje'
                ], 400);
            }

            Log::info('BotMan: Respuesta generada', ['messages' => $messages]);

            // Construir la respuesta
            return response()->json([
                'status' => 'success',
                'messages' => $messages
            ]);

        } catch (\Exception $e) {
            Log::error('Error en BotMan: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Ha ocurrido un error al procesar tu mensaje. Por favor, intenta de nuevo más tarde.'
            ], 500);
        }
    }
}
