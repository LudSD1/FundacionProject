<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailVerificationController extends Controller
{
    public function sendVerificationEmail(Request $request)
    {
        Log::info('Iniciando envío de verificación de email');

        $user = Auth::user();
        Log::info('Usuario autenticado: ' . $user->email);

        if ($user->hasVerifiedEmail()) {
            Log::info('Usuario ya verificado');
            return response()->json([
                'success' => false,
                'message' => 'Tu cuenta ya está verificada.'
            ]);
        }

        try {
            Log::info('Enviando notificación de verificación...');

            // Enviar email de verificación
            $user->sendEmailVerificationNotification();

            Log::info('Email de verificación enviado exitosamente');

            return back()->with('success', 'Se ha enviado un email de verificación a tu dirección de correo electrónico.');
        } catch (\Exception $e) {
            Log::error('Error al enviar email de verificación: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return back()->with('error', 'Error al enviar el email de verificación: ' . $e->getMessage());
        }
    }


    public function verify(Request $request, User $user, $hash)
    {
        Log::info('Iniciando verificación de email para usuario: ' . $user->id);

        // Verificar que la URL tenga una firma válida (previene expiraciones o manipulaciones)
        if (! $request->hasValidSignature()) {
            Log::warning('Firma de URL inválida o expirada para usuario: ' . $user->email);
            return redirect()->route('Inicio')->with('error', 'El enlace de verificación ha expirado o no es válido.');
        }

        // Verificar si ya está verificado
        if ($user->hasVerifiedEmail()) {
            Log::info('Usuario ya verificado: ' . $user->email);
            return redirect()->route('Inicio')->with('info', 'Tu cuenta ya está verificada.');
        }

        // Validar el hash del correo electrónico
        if (! hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            Log::warning('Hash de verificación inválido para usuario: ' . $user->email);
            return redirect()->route('Inicio')->with('error', 'El enlace de verificación no es válido.');
        }

        // Marcar como verificado
        try {
            $user->markEmailAsVerified();
            event(new Verified($user));

            Log::info('Email verificado exitosamente para usuario: ' . $user->email);

            return redirect()->route('Inicio')->with('success', '¡Tu cuenta ha sido verificada correctamente!');
        } catch (\Exception $e) {
            Log::error('Error al marcar email como verificado para usuario: ' . $user->email . ' - Error: ' . $e->getMessage());
            return redirect()->route('Inicio')->with('error', 'Ocurrió un error durante la verificación.');
        }
    }

    public function show()
    {
        return view('auth.verify-email');
    }
    public function resend(Request $request)
    {
        $user = Auth::user();
        Log::info('Reenviando verificación de email para usuario: ' . $user->email);

        if ($user->hasVerifiedEmail()) {
            Log::info('Usuario ya verificado, no se reenvía email: ' . $user->email);
            return back()->with('info', 'Tu cuenta ya está verificada.');
        }

        try {
            $user->sendEmailVerificationNotification();
            Log::info('Email de verificación reenviado exitosamente a: ' . $user->email);

            return back()->with('resent', true);
        } catch (\Exception $e) {
            Log::error('Error al reenviar email de verificación: ' . $e->getMessage());
            return back()->with('error', 'Error al enviar el email de verificación.');
        }
    }
}
