<?php

namespace App\Services;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BotManService
{
    protected $botman;
    protected $messages = [];

    public function __construct(BotMan $botman)
    {
        $this->botman = $botman;
    }

    public function handle()
    {
        $this->registerCommands();
        $this->botman->listen();
    }

    protected function registerCommands()
    {
        // Comando de saludo con más variaciones
        $this->botman->hears('(hola|buenos días|buenas tardes|buenas noches|saludos|hey|hi|hello|buen día)', function (BotMan $bot) {
            $this->handleGreeting($bot);
        });

        // Comando de certificados con más contexto
        $this->botman->hears('(certificado|certificados|congreso|diploma|constancia|acreditación|acreditación de asistencia|certificado de participación)', function (BotMan $bot) {
            $this->handleCertificates($bot);
        });

        // Comando de inscripción más detallado
        $this->botman->hears('(inscripción|inscribir|curso|cursos|programa|programas|matrícula|matricular|registro|registrar)', function (BotMan $bot) {
            $this->handleRegistration($bot);
        });

        // Comando de contacto más completo
        $this->botman->hears('(contacto|teléfono|dirección|email|correo|ubicación|dónde están|cómo contactar|cómo comunicarme)', function (BotMan $bot) {
            $this->handleContact($bot);
        });

        // Comando de ayuda más específico
        $this->botman->hears('(ayuda|soporte|información|info|cómo funciona|qué puedo hacer|necesito ayuda)', function (BotMan $bot) {
            $this->handleHelp($bot);
        });

        // Comando por defecto con sugerencias
        $this->botman->fallback(function (BotMan $bot) {
            $this->handleFallback($bot);
        });
    }

    protected function handleGreeting(BotMan $bot)
    {
        $hour = date('H');
        $greeting = '';

        if ($hour >= 5 && $hour < 12) {
            $greeting = '¡Buenos días!';
        } elseif ($hour >= 12 && $hour < 19) {
            $greeting = '¡Buenas tardes!';
        } else {
            $greeting = '¡Buenas noches!';
        }

        $this->addMessage($greeting . ' Soy el asistente virtual de Fundación Educar para la Vida. ¿En qué puedo ayudarte hoy?');
        $this->addMessage('Puedo ayudarte con:');

        $question = Question::create('Selecciona una opción:')
            ->fallback('No se pudo mostrar las opciones')
            ->callbackId('main_menu')
            ->addButtons([
                Button::create('📋 Certificados')->value('certificados'),
                Button::create('🎓 Cursos')->value('cursos'),
                Button::create('📞 Contacto')->value('contacto'),
                Button::create('❓ Ayuda')->value('ayuda')
            ]);
        $this->addMessage($question);
    }

    protected function handleCertificates(BotMan $bot)
    {
        $this->addMessage('Sobre los certificados de congresos:');
        $this->addMessage('1️⃣ Debes estar inscrito y haber completado los requisitos de asistencia.');
        $this->addMessage('2️⃣ Los certificados se habilitan cuando el estado cambia a "Certificado Disponible".');
        $this->addMessage('3️⃣ Recibirás una notificación por correo electrónico cuando esté listo.');
        $this->addMessage('4️⃣ El certificado incluye tu nombre completo, el nombre del congreso y la fecha.');
        $this->addMessage('5️⃣ Puedes descargarlo en formato PDF desde tu panel de usuario.');

        $question = Question::create('¿Necesitas ayuda adicional?')
            ->fallback('No se pudo mostrar las opciones')
            ->callbackId('certificate_help')
            ->addButtons([
                Button::create('Sí, necesito ayuda')->value('help_cert'),
                Button::create('No, gracias')->value('no_help'),
                Button::create('Volver al menú principal')->value('main_menu')
            ]);
        $this->addMessage($question);
    }

    protected function handleRegistration(BotMan $bot)
    {
        $this->addMessage('Para inscribirte en nuestros cursos:');
        $this->addMessage('1️⃣ Visita nuestra página web');
        $this->addMessage('2️⃣ Selecciona el curso de tu interés');
        $this->addMessage('3️⃣ Completa el formulario de inscripción');
        $this->addMessage('4️⃣ Realiza el pago correspondiente');
        $this->addMessage('5️⃣ Recibirás un correo de confirmación');
        $this->addMessage('6️⃣ Accede a tu panel de usuario para ver los detalles');

        $question = Question::create('¿Qué te gustaría hacer?')
            ->fallback('No se pudo mostrar las opciones')
            ->callbackId('show_courses')
            ->addButtons([
                Button::create('Ver cursos disponibles')->value('show_courses'),
                Button::create('Ver precios')->value('show_prices'),
                Button::create('Volver al menú principal')->value('main_menu')
            ]);
        $this->addMessage($question);
    }

    protected function handleContact(BotMan $bot)
    {
        $this->addMessage('Puedes contactarnos a través de:');
        $this->addMessage('📞 Teléfono: (+591) 72087186');
        $this->addMessage('📧 Email: contacto@educarparalavida.org.bo');
        $this->addMessage('📍 Dirección: Av. Melchor Pérez de Olguín e Idelfonso Murgía Nro. 1253, Cochabamba - Bolivia');
        $this->addMessage('⏰ Horario: Lun - Vier: 9AM a 5PM');
        $this->addMessage('🌐 Sitio web: www.educarparalavida.org.bo');
        $this->addMessage('📱 WhatsApp: (+591) 72087186');

        $question = Question::create('¿Necesitas más información?')
            ->fallback('No se pudo mostrar las opciones')
            ->callbackId('contact_help')
            ->addButtons([
                Button::create('Sí, más información')->value('more_info'),
                Button::create('Volver al menú principal')->value('main_menu')
            ]);
        $this->addMessage($question);
    }

    protected function handleHelp(BotMan $bot)
    {
        $this->addMessage('Puedo ayudarte con:');
        $this->addMessage('1️⃣ Información sobre certificados y acreditaciones');
        $this->addMessage('2️⃣ Proceso de inscripción a cursos y programas');
        $this->addMessage('3️⃣ Información de contacto y ubicación');
        $this->addMessage('4️⃣ Horarios y fechas importantes');
        $this->addMessage('5️⃣ Requisitos y documentación necesaria');
        $this->addMessage('6️⃣ Dudas sobre pagos y facturación');
        $this->addMessage('Solo pregúntame lo que necesites saber.');

        $question = Question::create('¿Sobre qué tema necesitas ayuda?')
            ->fallback('No se pudo mostrar las opciones')
            ->callbackId('help_topic')
            ->addButtons([
                Button::create('Certificados')->value('certificates'),
                Button::create('Inscripciones')->value('registration'),
                Button::create('Contacto')->value('contact'),
                Button::create('Volver al menú principal')->value('main_menu')
            ]);
        $this->addMessage($question);
    }

    protected function handleFallback(BotMan $bot)
    {
        $this->addMessage('Lo siento, no entiendo tu pregunta. Puedo ayudarte con:');
        $this->addMessage('1️⃣ Certificados y acreditaciones');
        $this->addMessage('2️⃣ Inscripciones a cursos');
        $this->addMessage('3️⃣ Información de contacto');
        $this->addMessage('4️⃣ Horarios y ubicaciones');
        $this->addMessage('Por favor, intenta reformular tu pregunta o selecciona una de las opciones del menú.');

        $question = Question::create('¿Qué te gustaría hacer?')
            ->fallback('No se pudo mostrar las opciones')
            ->callbackId('fallback_menu')
            ->addButtons([
                Button::create('Ver menú principal')->value('main_menu'),
                Button::create('Contactar soporte')->value('support')
            ]);
        $this->addMessage($question);
    }

    protected function addMessage($message)
    {
        if ($message instanceof Question) {
            $this->messages[] = [
                'type' => 'buttons',
                'buttons' => $message->getButtons()
            ];
        } else {
            $this->messages[] = [
                'type' => 'text',
                'text' => $message
            ];
        }
    }

    public function getMessages()
    {
        return $this->messages;
    }

    protected function handleLogin(BotMan $bot)
    {
        $loginUrl = route('login');
        $bot->reply('Para acceder a tu cuenta:');
        $bot->reply("1️⃣ Puedes iniciar sesión directamente <a href='$loginUrl'>aquí</a>.");
        $bot->reply("2️⃣ O si prefieres, puedo ayudarte con el proceso paso a paso.");

        $question = Question::create('¿Cómo prefieres continuar?')
            ->fallback('No se pudo mostrar las opciones')
            ->callbackId('login_options')
            ->addButtons([
                Button::create('Ir a la página de login')->value('redirect'),
                Button::create('Ayúdame con el proceso')->value('help'),
            ]);

        $bot->ask($question, function (Answer $answer, BotMan $bot) use ($loginUrl) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue() === 'redirect') {
                    $bot->reply("Te redirijo a la página de inicio de sesión: <a href='$loginUrl'>Iniciar sesión</a>");
                } else {
                    $bot->ask('Por favor, introduce tu correo electrónico:', function (Answer $answer, BotMan $bot) {
                        $email = $answer->getText();

                        $bot->userStorage()->save([
                            'email' => $email,
                        ]);

                        $bot->ask('Ahora, introduce tu contraseña:', function (Answer $answer, BotMan $bot) {
                            $password = $answer->getText();

                            try {
                                if ($this->validateCredentials($bot->userStorage()->find()['email'], $password)) {
                                    $bot->reply('¡Inicio de sesión exitoso! Bienvenido.');
                                    $bot->reply('¿En qué más puedo ayudarte hoy?');
                                } else {
                                    $bot->reply('Correo electrónico o contraseña incorrectos. Por favor, intenta de nuevo o usa la opción "Olvidé mi contraseña" en la página de inicio de sesión.');
                                }
                            } catch (\Exception $e) {
                                $bot->reply('Lo siento, ocurrió un error al intentar iniciar sesión. Por favor, intenta más tarde o contacta a soporte técnico.');
                                Log::error('Error en inicio de sesión del bot: ' . $e->getMessage());
                            }
                        });
                    });
                }
            }
        });
    }

    protected function validateCredentials($email, $password)
    {
        try {
            // Implementación de la autenticación
            // Por ejemplo, usando Auth::attempt() de Laravel
            return Auth::attempt([
                'email' => $email,
                'password' => $password
            ]);
        } catch (\Exception $e) {
            Log::error('Error validando credenciales: ' . $e->getMessage());
            return false;
        }
    }

    public function handleMessage($message)
    {
        $this->messages = []; // Limpiar mensajes anteriores

        if (preg_match('/(hola|buenos días|buenas tardes|buenas noches|saludos|hey|hi|hello|buen día)/i', $message)) {
            $this->handleGreeting($this->botman);
        } elseif (preg_match('/(certificado|certificados|congreso|diploma|constancia|acreditación|acreditación de asistencia|certificado de participación)/i', $message)) {
            $this->handleCertificates($this->botman);
        } elseif (preg_match('/(inscripción|inscribir|curso|cursos|programa|programas|matrícula|matricular|registro|registrar)/i', $message)) {
            $this->handleRegistration($this->botman);
        } elseif (preg_match('/(contacto|teléfono|dirección|email|correo|ubicación|dónde están|cómo contactar|cómo comunicarme)/i', $message)) {
            $this->handleContact($this->botman);
        } elseif (preg_match('/(ayuda|soporte|información|info|cómo funciona|qué puedo hacer|necesito ayuda)/i', $message)) {
            $this->handleHelp($this->botman);
        } else {
            $this->handleFallback($this->botman);
        }
    }
}
