<?php

namespace App\Http\Controllers;

use App\Services\RasaService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    protected $rasaService;

    public function __construct(RasaService $rasaService)
    {
        $this->rasaService = $rasaService;
    }

    public function handleMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        try {
            $response = $this->rasaService->sendMessage($request->message);

            return response()->json([
                'status' => 'success',
                'fulfillment_text' => $response['fulfillment_text'],
                'buttons' => $response['buttons'],
                'custom' => $response['custom']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al procesar el mensaje'
            ], 500);
        }
    }
}
