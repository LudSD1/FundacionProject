<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class OpenAIController extends Controller
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.openai.key');
        $this->client = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            'timeout' => 30.0,
        ]);
    }

    public function showChat()
    {
        return view('chat');
    }

    public function sendMessage(Request $request)
    {
        // Validate input
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        try {
            if (empty($this->apiKey)) {
                throw new \Exception('OpenAI API key is not configured');
            }

            Log::debug('Sending message to OpenAI:', ['message' => $request->message]); // Debug log

            $response = $this->client->post('chat/completions', [
                'json' => [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are a helpful AI assistant for an educational platform. Provide clear, concise, and accurate responses.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $request->message
                        ],
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 500,
                ]
            ]);

            $result = json_decode($response->getBody(), true);

            Log::debug('OpenAI Response:', ['result' => $result]); // Debug log

            if (!isset($result['choices'][0]['message']['content'])) {
                Log::error('Invalid OpenAI response format:', ['result' => $result]);
                throw new \Exception('Formato de respuesta inválido de OpenAI');
            }

            return response()->json([
                'success' => true,
                'reply' => $result['choices'][0]['message']['content']
            ]);

        } catch (GuzzleException $e) {
            Log::error('OpenAI API Error:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => 'Error de conexión con OpenAI',
                'details' => $e->getMessage()
            ], 503);

        } catch (\Exception $e) {
            Log::error('Chat Error:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => 'Error en el procesamiento',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}