<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asistente Virtual - Fundación Educar para la Vida</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .chat-container {
            height: calc(100vh - 180px);
        }
        .message {
            max-width: 80%;
            margin-bottom: 1rem;
            padding: 0.75rem 1rem;
            border-radius: 1rem;
        }
        .bot-message {
            background-color: #f3f4f6;
            margin-right: auto;
            border-bottom-left-radius: 0.25rem;
        }
        .user-message {
            background-color: #3b82f6;
            color: white;
            margin-left: auto;
            border-bottom-right-radius: 0.25rem;
        }
        .button-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }
        .chat-button {
            background-color: #e5e7eb;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .chat-button:hover {
            background-color: #d1d5db;
        }
        .typing-indicator {
            display: none;
            padding: 0.75rem 1rem;
            background-color: #f3f4f6;
            border-radius: 1rem;
            margin-bottom: 1rem;
            width: fit-content;
        }
        .typing-indicator span {
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: #9ca3af;
            border-radius: 50%;
            margin-right: 4px;
            animation: typing 1s infinite;
        }
        .typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
        .typing-indicator span:nth-child(3) { animation-delay: 0.4s; }
        @keyframes typing {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-blue-600 text-white p-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-robot text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">Asistente Virtual</h1>
                        <p class="text-sm text-blue-100">Fundación Educar para la Vida</p>
                    </div>
                </div>
            </div>

            <!-- Chat Container -->
            <div class="chat-container overflow-y-auto p-4" id="chatContainer">
                <!-- Bot Welcome Message -->
                <div class="message bot-message">
                    <p>¡Hola! Soy el asistente virtual de Fundación Educar para la Vida. ¿En qué puedo ayudarte hoy?</p>
                    <div class="button-container">
                        <button class="chat-button" onclick="sendMessage('certificados')">📋 Certificados</button>
                        <button class="chat-button" onclick="sendMessage('cursos')">🎓 Cursos</button>
                        <button class="chat-button" onclick="sendMessage('contacto')">📞 Contacto</button>
                        <button class="chat-button" onclick="sendMessage('ayuda')">❓ Ayuda</button>
                    </div>
                </div>

                <!-- Typing Indicator -->
                <div class="typing-indicator" id="typingIndicator">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>

            <!-- Input Area -->
            <div class="border-t p-4">
                <form id="chatForm" class="flex gap-2">
                    <input type="text"
                           id="messageInput"
                           class="flex-1 border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                           placeholder="Escribe tu mensaje aquí..."
                           autocomplete="off">
                    <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const chatContainer = document.getElementById('chatContainer');
        const messageInput = document.getElementById('messageInput');
        const chatForm = document.getElementById('chatForm');
        const typingIndicator = document.getElementById('typingIndicator');

        // Función para agregar mensaje al chat
        function addMessage(text, isUser = false) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${isUser ? 'user-message' : 'bot-message'}`;

            if (typeof text === 'string') {
                messageDiv.innerHTML = `<p>${text}</p>`;
            } else if (text.type === 'buttons') {
                const buttonsHtml = text.buttons.map(button =>
                    `<button class="chat-button" onclick="sendMessage('${button.value}')">${button.text}</button>`
                ).join('');
                messageDiv.innerHTML = `<p>${text.text || ''}</p><div class="button-container">${buttonsHtml}</div>`;
            }

            chatContainer.appendChild(messageDiv);
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        // Función para mostrar/ocultar el indicador de escritura
        function toggleTypingIndicator(show) {
            typingIndicator.style.display = show ? 'block' : 'none';
            if (show) {
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }
        }

        // Función para enviar mensaje
        async function sendMessage(message) {
            if (!message) return;

            // Agregar mensaje del usuario
            addMessage(message, true);
            messageInput.value = '';

            // Mostrar indicador de escritura
            toggleTypingIndicator(true);

            try {
                const response = await fetch('/botman', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ message })
                });

                const data = await response.json();

                // Ocultar indicador de escritura
                toggleTypingIndicator(false);

                if (data.status === 'success') {
                    data.messages.forEach(msg => {
                        if (msg.type === 'text') {
                            addMessage(msg.text);
                        } else if (msg.type === 'buttons') {
                            addMessage(msg);
                        }
                    });
                } else {
                    addMessage('Lo siento, hubo un error al procesar tu mensaje. Por favor, intenta de nuevo.');
                }
            } catch (error) {
                console.error('Error:', error);
                toggleTypingIndicator(false);
                addMessage('Lo siento, hubo un error al comunicarse con el servidor. Por favor, intenta de nuevo.');
            }
        }

        // Manejar envío del formulario
        chatForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const message = messageInput.value.trim();
            if (message) {
                sendMessage(message);
            }
        });

        // Manejar tecla Enter
        messageInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                chatForm.dispatchEvent(new Event('submit'));
            }
        });
    </script>
</body>
</html>
