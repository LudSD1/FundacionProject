<!-- filepath: /c:/Users/LUD015GTR/Desktop/fundacion4.0/Fundacion/resources/views/chat.blade.php -->
@extends('layout')

@section('content')
<div class="container py-5">
    <div class="chat-container">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0">
                    <i class="bi bi-chat-dots-fill me-2"></i>
                    Chat con OpenAI
                </h5>
            </div>
            <div class="card-body">
                <div id="chat-box" class="shadow-sm"></div>

                <div class="typing-indicator" id="typing-indicator">
                    <i class="bi bi-chat-dots me-2"></i>
                    <span>El asistente está escribiendo</span>
                    <span class="dots">...</span>
                </div>

                <form id="chat-form" class="input-group">
                    @csrf <!-- Add CSRF Token -->
                    <input type="text"
                           id="message"
                           class="form-control"
                           placeholder="Escribe tu mensaje..."
                           required>
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-send-fill me-1"></i>
                        Enviar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatBox = document.getElementById('chat-box');
    const form = document.getElementById('chat-form');
    const input = document.getElementById('message');
    const typingIndicator = document.getElementById('typing-indicator');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function appendMessage(content, type = 'user') {
        const div = document.createElement('div');
        div.className = `message ${type}-message`;
        div.innerHTML = content;
        chatBox.appendChild(div);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const message = input.value.trim();
        if (!message) return;

        // Disable input and show typing indicator
        input.disabled = true;
        form.querySelector('button').disabled = true;
        typingIndicator.style.display = 'block';

        // Show user message
        appendMessage(`<strong>Tú:</strong> ${message}`, 'user');
        input.value = '';

        try {
            const response = await fetch('/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ message })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.error || 'Error en la comunicación con el servidor');
            }

            if (data.success && data.reply) {
                appendMessage(`<strong>Asistente:</strong> ${data.reply}`, 'bot');
            } else {
                throw new Error('No se recibió una respuesta válida');
            }
        } catch (error) {
            console.error('Chat error:', error);
            appendMessage(`<strong>Error:</strong> ${error.message}`, 'error');
        } finally {
            input.disabled = false;
            form.querySelector('button').disabled = false;
            typingIndicator.style.display = 'none';
            input.focus();
        }
    });
});
</script>
@endpush
@endsection