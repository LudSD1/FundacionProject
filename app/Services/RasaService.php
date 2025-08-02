<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RasaService
{
    protected $rasaUrl;
    protected $senderId;

    public function __construct()
    {
        $this->rasaUrl = config('services.rasa.url');
        $this->senderId = uniqid();
    }

    public function sendMessage($message)
    {
        try {
            $response = Http::post($this->rasaUrl . '/webhooks/rest/webhook', [
                'sender' => $this->senderId,
                'message' => $message
            ]);

            if ($response->successful()) {
                $botResponses = $response->json();
                return $this->processResponses($botResponses);
            }

            throw new \Exception('Error en la comunicación con Rasa');
        } catch (\Exception $e) {
            throw new \Exception('Error al procesar el mensaje: ' . $e->getMessage());
        }
    }

    protected function processResponses($responses)
    {
        $result = [
            'status' => 'success',
            'fulfillment_text' => '',
            'buttons' => [],
            'custom' => []
        ];

        foreach ($responses as $response) {
            if (isset($response['text'])) {
                $result['fulfillment_text'] .= $response['text'] . "\n";
            }

            if (isset($response['buttons'])) {
                $result['buttons'] = array_merge($result['buttons'], $response['buttons']);
            }

            if (isset($response['custom'])) {
                $result['custom'] = array_merge($result['custom'], $response['custom']);
            }
        }

        return $result;
    }
}
