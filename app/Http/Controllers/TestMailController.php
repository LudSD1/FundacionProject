<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Mail\Message;

class TestMailController extends Controller
{
    public function testMail()
    {
        try {
            // Registrar la configuración de correo actual
            Log::info('Configuración de correo:', [
                'driver' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'encryption' => config('mail.mailers.smtp.encryption'),
                'username' => config('mail.mailers.smtp.username'),
                'from_address' => config('mail.from.address'),
                'from_name' => config('mail.from.name'),
            ]);

            // Enviar un correo de prueba
            Mail::raw('Correo de prueba desde Laravel', function (Message $message) {
                $message->to(auth()->user()->email)
                        ->subject('Prueba de correo');
            });

            return response()->json([
                'success' => true,
                'message' => 'Correo de prueba enviado. Revisa los logs para más detalles.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al enviar correo de prueba: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error al enviar correo de prueba: ' . $e->getMessage()
            ], 500);
        }
    }
}
