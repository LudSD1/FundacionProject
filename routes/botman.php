<?php


use BotMan\BotMan\BotMan;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Web\WebDriver;
use BotMan\BotMan\Cache\LaravelCache;
use BotMan\BotMan\Container\LaravelContainer;
use BotMan\BotMan\BotManFactory;
use Illuminate\Support\Facades\Log;

// Configuración del driver web
DriverManager::loadDriver(WebDriver::class);

// Configuración del bot
$config = [
    'web' => [
        'matchingData' => [
            'driver' => 'web',
        ],
    ],
];

// Crear instancia del bot con configuración
$botman = BotManFactory::create($config, new LaravelCache(), app()->make('request'), new LaravelContainer());

// Configurar el manejo de errores
$botman->exceptionHandler(function($e) {
    Log::error('Error en BotMan: ' . $e->getMessage(), [
        'exception' => $e,
        'trace' => $e->getTraceAsString()
    ]);
});

// Configurar el manejo de mensajes no entendidos
$botman->fallback(function($bot) {
    $bot->reply('Lo siento, no entiendo tu mensaje. Por favor, intenta reformularlo o selecciona una opción del menú.');
});

// Rutas del bot
$botman->hears('redirect', function (BotMan $bot) {
    $bot->reply("Te redirijo a la página de inicio de sesión: <a href='/login'>Iniciar sesión</a>");
});

$botman->hears('help', function (BotMan $bot) {
    $bot->reply("Vamos a iniciar el proceso de ayuda paso a paso...");
});

// Manejar el inicio de la conversación
$botman->hears('start', function (BotMan $bot) {
    $bot->reply('¡Bienvenido! Soy el asistente virtual de Fundación Educar para la Vida. ¿En qué puedo ayudarte hoy?');
});

// Manejar el final de la conversación
$botman->hears('(bye|adios|hasta luego|chao)', function (BotMan $bot) {
    $bot->reply('¡Gracias por conversar conmigo! Si necesitas más ayuda, no dudes en volver a preguntar.');
});

// Manejar agradecimientos
$botman->hears('(gracias|thank you|thanks)', function (BotMan $bot) {
    $bot->reply('¡De nada! ¿Hay algo más en lo que pueda ayudarte?');
});

// Manejar preguntas sobre el bot
$botman->hears('(quien eres|qué eres|qué puedes hacer)', function (BotMan $bot) {
    $bot->reply('Soy un asistente virtual diseñado para ayudarte con información sobre la Fundación Educar para la Vida. Puedo ayudarte con información sobre certificados, cursos, inscripciones y más.');
});
