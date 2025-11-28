@extends('layout')

@section('content')
    <style>
        :root {
            --color-primary: #1a4789;
            --color-secondary: #39a6cb;
            --color-accent1: #63becf;
            --color-accent2: #055c9d;
            --color-accent3: #2197bd;
            --color-accent4: #2f89a8;
            --color-accent5: #145da0;
            --color-accent6: #2a81c2;
            --orange-accent: #ffa500;
            --gradient-primary: linear-gradient(135deg, #1a4789 0%, #055c9d 100%);
            --gradient-secondary: linear-gradient(135deg, #39a6cb 0%, #63becf 100%);
            --gradient-accent: linear-gradient(135deg, #2197bd 0%, #2a81c2 100%);
            --gradient-orange: linear-gradient(135deg, #ffa500 0%, #ff8c00 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }



        /* Timer Header */
        .quiz-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .timer-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 900px;
            margin: 0 auto;
        }

        .timer-display {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--color-primary);
        }

        .timer-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--gradient-accent);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        .timer-warning {
            animation: shake 0.5s infinite;
            color: #dc3545;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        /* Progress Bar */
        .progress-container {
            max-width: 900px;
            margin: 2rem auto 3rem;
            padding: 0 1rem;
        }

        .progress {
            height: 30px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            overflow: visible;
        }

        .progress-bar {
            background: var(--gradient-orange) !important;
            border-radius: 18px;
            transition: width 0.5s ease;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            box-shadow: 0 0 20px rgba(255, 165, 0, 0.5);
        }

        /* Question Card */
        .question-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 1rem 3rem;
        }

        .pregunta {
            background: white;
            border-radius: 25px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: none;
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Question Header */
        .question-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 3px solid #f0f0f0;
        }

        .question-badge {
            background: var(--gradient-primary);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 15px;
            font-weight: 700;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .question-points {
            background: var(--gradient-orange);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 15px;
            font-weight: 700;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Question Title */
        .question-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--color-primary);
            margin-bottom: 2.5rem;
            line-height: 1.4;
        }

        /* Answer Options */
        .answer-option {
            background: #f8f9fa;
            border: 3px solid #e0e0e0;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .answer-option::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--gradient-accent);
            transition: left 0.3s ease;
            opacity: 0.1;
        }

        .answer-option:hover {
            border-color: var(--color-accent3);
            transform: translateX(5px);
            box-shadow: 0 5px 20px rgba(33, 151, 189, 0.2);
        }

        .answer-option:hover::before {
            left: 0;
        }

        .form-check-input {
            width: 24px;
            height: 24px;
            border: 3px solid var(--color-accent3);
            cursor: pointer;
            margin-top: 0;
        }

        .form-check-input:checked {
            background-color: var(--color-accent3);
            border-color: var(--color-accent3);
        }

        .form-check-input:checked~.form-check-label {
            font-weight: 700;
            color: var(--color-primary);
        }

        .answer-option.selected {
            background: rgba(33, 151, 189, 0.1);
            border-color: var(--color-accent3);
            box-shadow: 0 5px 20px rgba(33, 151, 189, 0.3);
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 0;
        }

        .form-check-label {
            font-size: 1.1rem;
            cursor: pointer;
            flex-grow: 1;
            margin: 0;
        }

        /* Textarea for open questions */
        .answer-textarea {
            width: 100%;
            padding: 1.5rem;
            border: 3px solid #e0e0e0;
            border-radius: 15px;
            font-size: 1rem;
            resize: vertical;
            min-height: 150px;
            transition: all 0.3s ease;
        }

        .answer-textarea:focus {
            outline: none;
            border-color: var(--color-accent3);
            box-shadow: 0 0 0 4px rgba(33, 151, 189, 0.1);
        }

        /* Navigation Buttons */
        .question-navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 3rem;
            gap: 1rem;
        }

        .btn-nav {
            padding: 1rem 2.5rem;
            border: none;
            border-radius: 15px;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            min-width: 150px;
            justify-content: center;
        }

        .btn-nav:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .btn-anterior {
            background: white;
            color: var(--color-primary);
            border: 3px solid var(--color-accent3);
        }

        .btn-anterior:hover {
            background: #f8f9fa;
        }

        .btn-siguiente {
            background: var(--gradient-primary);
            color: white;
        }

        .btn-submit {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .btn-submit:hover {
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
        }

        /* Alert Messages */
        .alert-modern {
            border-radius: 15px;
            padding: 1.5rem;
            border: none;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .alert-modern i {
            font-size: 1.5rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .pregunta {
                padding: 2rem 1.5rem;
            }

            .question-title {
                font-size: 1.4rem;
            }

            .question-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .question-navigation {
                flex-direction: column;
            }

            .btn-nav {
                width: 100%;
            }

            .timer-display {
                font-size: 1.2rem;
            }
        }

        /* Loading Animation */
        .btn-nav.loading {
            position: relative;
            pointer-events: none;
            opacity: 0.7;
        }

        .btn-nav.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>

    <!-- Header con Timer -->
    <div class="quiz-header">
        <div class="timer-container">
            <div class="timer-display" id="timerDisplay">
                <div class="timer-icon">
                    <i class="bi bi-clock-history"></i>
                </div>
                <span id="tiempoRestante">
                    @if ($cuestionario->tiempo_limite)
                        {{ $cuestionario->tiempo_limite }}:00
                    @else
                        ⏳ Tiempo ilimitado
                    @endif
                </span>
            </div>
            <div class="d-none d-md-block">
                <span class="text-muted" id="currentQuestion">Pregunta 1 de {{ $cuestionario->preguntas->count() }}</span>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Progress Bar -->
        <div class="progress-container">
            <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                    style="width: {{ (1 / $cuestionario->preguntas->count()) * 100 }}%;" id="progressBar">
                    <span id="progressText">{{ number_format((1 / $cuestionario->preguntas->count()) * 100, 0) }}%</span>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session('error'))
            <div class="question-container">
                <div class="alert alert-danger alert-modern alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>
                        <strong>¡Atención!</strong>
                        <p class="mb-0">{{ session('error') }}</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        <!-- Questions -->
        <div class="question-container">
            <form id="cuestionarioForm" method="POST"
                action="{{ route('responderCuestionario', encrypt($cuestionario->id)) }}">
                @csrf

                @foreach ($cuestionario->preguntas->shuffle() as $pregunta)
                    <div class="pregunta" id="pregunta-{{ $loop->index }}" data-question-index="{{ $loop->index }}"
                        style="{{ $loop->index > 0 ? 'display: none;' : '' }}">

                        <!-- Question Header -->
                        <div class="question-header">
                            <span class="question-badge">
                                <i class="bi bi-chat-square-text"></i>
                                Pregunta {{ $loop->iteration }} de {{ $cuestionario->preguntas->count() }}
                            </span>
                            <span class="question-points">
                                <i class="bi bi-star-fill"></i>
                                {{ $pregunta->puntaje }} puntos
                            </span>
                        </div>

                        <!-- Question Title -->
                        <h3 class="question-title">{{ $pregunta->enunciado }}</h3>

                        <!-- Answer Options -->
                        <div class="answers-container">
                            @if ($pregunta->tipo === 'opcion_multiple')
                                @foreach ($pregunta->respuestas as $respuesta)
                                    <div class="answer-option" data-answer-id="{{ $respuesta->id }}">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio"
                                                name="respuestas[{{ $pregunta->id }}]" id="respuesta{{ $respuesta->id }}"
                                                value="{{ $respuesta->id }}"
                                                {{ old('respuestas.' . $pregunta->id) == $respuesta->id || (session('respuestas_previas') && isset(session('respuestas_previas')[$pregunta->id]) && session('respuestas_previas')[$pregunta->id] == $respuesta->id) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="respuesta{{ $respuesta->id }}">
                                                {{ $respuesta->contenido }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            @elseif ($pregunta->tipo === 'boolean')
                                <div class="answer-option" data-answer-value="1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                            name="respuestas[{{ $pregunta->id }}]" id="verdadero{{ $pregunta->id }}"
                                            value="1"
                                            {{ old('respuestas.' . $pregunta->id) == '1' || (session('respuestas_previas') && isset(session('respuestas_previas')[$pregunta->id]) && session('respuestas_previas')[$pregunta->id] == '1') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="verdadero{{ $pregunta->id }}">
                                            <i class="bi bi-check-circle me-2"></i>Verdadero
                                        </label>
                                    </div>
                                </div>
                                <div class="answer-option" data-answer-value="0">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                            name="respuestas[{{ $pregunta->id }}]" id="falso{{ $pregunta->id }}"
                                            value="0"
                                            {{ old('respuestas.' . $pregunta->id) == '0' || (session('respuestas_previas') && isset(session('respuestas_previas')[$pregunta->id]) && session('respuestas_previas')[$pregunta->id] == '0') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="falso{{ $pregunta->id }}">
                                            <i class="bi bi-x-circle me-2"></i>Falso
                                        </label>
                                    </div>
                                </div>
                            @else
                                <textarea class="answer-textarea" name="respuestas[{{ $pregunta->id }}]" placeholder="Escribe tu respuesta aquí..."
                                    rows="5">{{ old('respuestas.' . $pregunta->id) ?? (session('respuestas_previas') && isset(session('respuestas_previas')[$pregunta->id]) ? session('respuestas_previas')[$pregunta->id] : '') }}</textarea>
                            @endif
                        </div>

                        <!-- Navigation -->
                        <div class="question-navigation">
                            @if (!$loop->first)
                                <button type="button" class="btn-nav btn-anterior">
                                    <i class="bi bi-arrow-left"></i>
                                    Anterior
                                </button>
                            @else
                                <span></span>
                            @endif

                            @if (!$loop->last)
                                <button type="button" class="btn-nav btn-siguiente">
                                    Siguiente
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            @else
                                <button type="submit" class="btn-nav btn-submit" id="btnSubmit">
                                    <i class="bi bi-check-circle-fill"></i>
                                    Enviar Respuestas
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ==================== VARIABLES GLOBALES ====================
            let currentQuestion = 0;
            const totalQuestions = {{ $cuestionario->preguntas->count() }};
            const tiempoLimite = {{ $cuestionario->tiempo_limite ?? 0 }};
            let tiempoRestante = tiempoLimite * 60; // Convertir a segundos
            let timerInterval;
            let formSubmitted = false;

            const form = document.getElementById('cuestionarioForm');
            const preguntas = document.querySelectorAll('.pregunta');
            const progressBar = document.getElementById('progressBar');
            const btnSubmit = document.getElementById('btnSubmit');

            // Verificar si hay error de validación
            const hayError = @json(session('error') ? true : false);

            // ==================== MODO ERROR (Mostrar todas las preguntas) ====================
            if (hayError) {
                preguntas.forEach((pregunta) => {
                    pregunta.style.display = 'block';
                    pregunta.style.marginBottom = '2rem';

                    // Verificar si tiene respuesta
                    const inputs = pregunta.querySelectorAll('input[type="radio"], textarea');
                    let tieneRespuesta = false;

                    inputs.forEach(input => {
                        if (input.type === 'radio' && input.checked) {
                            tieneRespuesta = true;
                        } else if (input.type !== 'radio' && input.value.trim() !== '') {
                            tieneRespuesta = true;
                        }
                    });

                    // Resaltar preguntas sin respuesta
                    if (!tieneRespuesta) {
                        pregunta.style.border = '3px solid #dc3545';
                        pregunta.style.boxShadow = '0 0 15px rgba(220, 53, 69, 0.3)';

                        const badge = pregunta.querySelector('.question-badge');
                        if (badge) {
                            badge.style.background = 'linear-gradient(135deg, #dc3545 0%, #c82333 100%)';
                            badge.innerHTML = '<i class="bi bi-exclamation-triangle"></i> ' + badge
                                .textContent;
                        }
                    }
                });

                progressBar.style.width = '100%';
                document.getElementById('progressText').textContent = '100%';

                // Mensaje informativo
                const mensajeInfo = document.createElement('div');
                mensajeInfo.className = 'alert alert-modern alert-warning';
                mensajeInfo.innerHTML = `
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>
                        <strong>¡Preguntas sin responder!</strong>
                        <p class="mb-0">Revisa todas las preguntas y completa las que faltan. Las preguntas sin responder están resaltadas en rojo.</p>
                    </div>
                `;
                form.insertBefore(mensajeInfo, form.firstChild);

                // Scroll a la primera pregunta sin respuesta
                const primeraSinResponder = document.querySelector('.pregunta[style*="border"]');
                if (primeraSinResponder) {
                    setTimeout(() => {
                        primeraSinResponder.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }, 500);
                }

                return; // Salir para no ejecutar el resto del código
            }

            // ==================== MODO NORMAL ====================

            // ========== TEMPORIZADOR ==========
            if (tiempoLimite > 0) {
                // Mostrar advertencia inicial
                Swal.fire({
                    title: 'Instrucciones Importantes',
                    html: `
                        <div style="text-align: left; padding: 1rem;">
                            <ul style="line-height: 2;">
                                <li>⏰ Tienes <strong>${tiempoLimite} minutos</strong> para completar el cuestionario</li>
                                <li>🚫 No uses los botones de navegación del navegador</li>
                                <li>🚫 No cambies de pestaña o ventana</li>
                                <li>🚫 No está permitido copiar o pegar</li>
                                <li>✅ Usa el botón "Enviar" cuando termines</li>
                            </ul>
                        </div>
                    `,
                    icon: 'info',
                    confirmButtonText: 'Entendido, comenzar',
                    confirmButtonColor: '#1a4789',
                    allowOutsideClick: false
                });

                // Iniciar timer
                timerInterval = setInterval(updateTimer, 1000);
            }

            function updateTimer() {
                if (tiempoRestante <= 0) {
                    clearInterval(timerInterval);
                    formSubmitted = true;

                    Swal.fire({
                        title: '⏰ Tiempo Agotado',
                        text: 'El cuestionario se enviará automáticamente',
                        icon: 'warning',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        form.submit();
                    });
                    return;
                }

                tiempoRestante--;
                const minutos = Math.floor(tiempoRestante / 60);
                const segundos = tiempoRestante % 60;

                document.getElementById('tiempoRestante').textContent =
                    `${minutos}:${segundos.toString().padStart(2, '0')}`;

                // Warning cuando quedan 2 minutos
                if (tiempoRestante === 120) {
                    document.getElementById('timerDisplay').classList.add('timer-warning');

                    Swal.fire({
                        title: '⚠️ ¡Quedan 2 minutos!',
                        text: 'Date prisa para terminar',
                        icon: 'warning',
                        timer: 3000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                }
            }

            // ========== PROGRESO ==========
            function updateProgress() {
                const progress = ((currentQuestion + 1) / totalQuestions) * 100;
                progressBar.style.width = progress + '%';
                document.getElementById('progressText').textContent = Math.round(progress) + '%';

                const currentQuestionEl = document.getElementById('currentQuestion');
                if (currentQuestionEl) {
                    currentQuestionEl.textContent = `Pregunta ${currentQuestion + 1} de ${totalQuestions}`;
                }
            }

            // ========== NAVEGACIÓN ==========
            function showQuestion(index) {
                preguntas.forEach((q, i) => {
                    q.style.display = i === index ? 'block' : 'none';
                });
                currentQuestion = index;
                updateProgress();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }

            // Botones siguiente
            document.querySelectorAll('.btn-siguiente').forEach(btn => {
                btn.addEventListener('click', () => {
                    if (currentQuestion < totalQuestions - 1) {
                        showQuestion(currentQuestion + 1);
                    }
                });
            });

            // Botones anterior
            document.querySelectorAll('.btn-anterior').forEach(btn => {
                btn.addEventListener('click', () => {
                    if (currentQuestion > 0) {
                        showQuestion(currentQuestion - 1);
                    }
                });
            });

            // ========== SELECCIÓN DE RESPUESTAS ==========
            document.querySelectorAll('.answer-option').forEach(option => {
                const input = option.querySelector('input[type="radio"]');

                if (input) {
                    option.addEventListener('click', (e) => {
                        if (!e.target.classList.contains('form-check-input')) {
                            input.checked = true;
                            updateAnswerSelection(option);
                        }
                    });

                    input.addEventListener('change', () => {
                        updateAnswerSelection(option);
                    });
                }
            });

            function updateAnswerSelection(clickedOption) {
                const container = clickedOption.closest('.answers-container');
                if (container) {
                    container.querySelectorAll('.answer-option').forEach(opt => {
                        opt.classList.remove('selected');
                    });
                }
                clickedOption.classList.add('selected');
            }

            // Marcar respuestas previas
            document.querySelectorAll('input[type="radio"]:checked').forEach(input => {
                const option = input.closest('.answer-option');
                if (option) {
                    option.classList.add('selected');
                }
            });

            // ========== ENVÍO DEL FORMULARIO ==========
            if (btnSubmit) {
                btnSubmit.addEventListener('click', (e) => {
                    e.preventDefault();

                    Swal.fire({
                        title: '¿Enviar respuestas?',
                        text: 'No podrás modificarlas después',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, enviar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            formSubmitted = true;

                            // Detener timer
                            if (timerInterval) {
                                clearInterval(timerInterval);
                            }

                            // Loading
                            btnSubmit.classList.add('loading');
                            btnSubmit.disabled = true;

                            Swal.fire({
                                title: 'Enviando respuestas...',
                                allowOutsideClick: false,
                                showConfirmButton: false,
                                willOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            // Enviar
                            form.submit();
                        }
                    });
                });
            }

            // ========== PROTECCIONES ==========

            // Prevenir salida accidental
            window.addEventListener('beforeunload', (e) => {
                if (!formSubmitted) {
                    e.preventDefault();
                    e.returnValue = '¿Seguro que quieres salir? Perderás todas tus respuestas.';
                    return e.returnValue;
                }
            });

            // Prevenir navegación con botones del navegador
            window.history.pushState(null, '', window.location.href);
            window.onpopstate = () => {
                window.history.pushState(null, '', window.location.href);

                Swal.fire({
                    title: '¡Atención!',
                    text: 'No uses los botones de navegación del navegador. Usa los botones de "Siguiente" y "Anterior" del cuestionario.',
                    icon: 'warning',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#1a4789'
                });
            };

            // Detectar cambio de pestaña
            document.addEventListener('visibilitychange', () => {
                if (document.visibilityState === 'hidden' && !formSubmitted) {
                    fetch('/cuestionarios/{{ $cuestionario->id }}/registrar-abandono', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                ?.content || '',
                            'Content-Type': 'application/json'
                        }
                    }).catch(err => console.error('Error al registrar abandono:', err));
                }
            });

            // Prevenir copiar/pegar
            document.addEventListener('copy', (e) => {
                e.preventDefault();
                Swal.fire({
                    title: '¡Acción no permitida!',
                    text: 'No está permitido copiar durante el cuestionario',
                    icon: 'error',
                    toast: true,
                    position: 'top-end',
                    timer: 2000,
                    showConfirmButton: false
                });
            });

            document.addEventListener('paste', (e) => {
                e.preventDefault();
                Swal.fire({
                    title: '¡Acción no permitida!',
                    text: 'No está permitido pegar durante el cuestionario',
                    icon: 'error',
                    toast: true,
                    position: 'top-end',
                    timer: 2000,
                    showConfirmButton: false
                });
            });

            // Prevenir enter accidental
            form.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
                    e.preventDefault();
                }
            });

            // ========== INICIALIZAR ==========
            showQuestion(0);
        });
    </script>
@endsection
