<?php

namespace App\Services;

use Google\Cloud\Dialogflow\V2\SessionsClient;
use Google\Cloud\Dialogflow\V2\TextInput;
use Google\Cloud\Dialogflow\V2\QueryInput;

class DialogflowService
{
    protected $sessionsClient;
    protected $projectId;
    protected $sessionId;

    public function __construct()
    {
        $this->projectId = config('services.dialogflow.project_id');
        $this->sessionId = uniqid();
        $this->sessionsClient = new SessionsClient([
            'credentials' => storage_path('app/dialogflow-credentials.json')
        ]);
    }

    public function detectIntent($text, $languageCode = 'es')
    {
        $session = $this->sessionsClient->sessionName($this->projectId, $this->sessionId);

        $textInput = new TextInput();
        $textInput->setText($text);
        $textInput->setLanguageCode($languageCode);

        $queryInput = new QueryInput();
        $queryInput->setText($textInput);

        $response = $this->sessionsClient->detectIntent($session, $queryInput);
        $queryResult = $response->getQueryResult();

        return [
            'intent' => $queryResult->getIntent()->getDisplayName(),
            'confidence' => $queryResult->getIntentDetectionConfidence(),
            'fulfillment_text' => $queryResult->getFulfillmentText(),
            'parameters' => $queryResult->getParameters()->getFields()
        ];
    }

    public function __destruct()
    {
        $this->sessionsClient->close();
    }
}
