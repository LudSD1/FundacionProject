<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http as FacadesHttp;
use Illuminate\Support\Facades\Log;
use League\Uri\Http;

class MC4Controller extends Controller
{
    /**
     * Genera un token de acceso para la API de SIP MC4
     */
    public function generarToken()
    {
        try {
            $response = FacadesHttp::withHeaders([
                'apikey' => env('SIP_KEY'),
                'Content-Type' => 'application/json'
            ])->post('https://dev-sip.mc4.com.bo:8443/autenticacion/v1/generarToken', [
                'username' => env('SIP_USER'),
                'password' => env('SIP_PASSWORD')
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // El token viene en objeto.token según la documentación
                if (isset($data['objeto']['token'])) {
                    return [
                        'success' => true,
                        'token' => $data['objeto']['token'],
                        'full_response' => $data
                    ];
                }

                Log::warning('Token no encontrado en la respuesta de MC4', ['response' => $data]);
                return ['success' => false, 'error' => 'Token no encontrado en la respuesta'];
            }

            Log::error('Error al generar token MC4', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return ['success' => false, 'error' => 'Error en la petición', 'status' => $response->status()];
        } catch (\Exception $e) {
            Log::error('Excepción al generar token MC4: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Método de prueba para verificar la conexión con MC4 SIP
     */
    public function testConexion()
    {
        $result = $this->generarToken();

        if ($result['success'] ?? false) {
            return response()->json([
                'success' => true,
                'message' => '✅ Conexión exitosa con MC4 SIP',
                'token_preview' => substr($result['token'], 0, 30) . '...',
                'full_response' => $result['full_response']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => '❌ Error al conectar con MC4 SIP',
            'error' => $result['error'] ?? 'Error desconocido',
            'details' => $result
        ], 500);
    }
}
