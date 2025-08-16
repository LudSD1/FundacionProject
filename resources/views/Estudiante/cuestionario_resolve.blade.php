@extends('layout')

@section('content')
<!-- Temporizador -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5>
        <i class="bi bi-clock-history me-2"></i>
        <span id="tiempoRestante">
            @if ($cuestionario->tiempo_limite)
                Tiempo restante: {{ $cuestionario->tiempo_limite }}:00
            @else
                ⏳ Tiempo ilimitado
            @endif
        </span>
    </h5>
</div>

<div class="container py-5">
    <!-- Mostrar mensaje de error si hay preguntas sin responder -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Barra de progreso visual tipo Kahoot -->
    <div class="progress mb-5 rounded-pill" style="height: 25px;">
        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" id="progressBar"></div>
    </div>

    <form id="cuestionarioForm" method="POST" action="{{ route('responderCuestionario', encrypt($cuestionario->id)) }}">
        @csrf
        @foreach ($cuestionario->preguntas->shuffle() as $pregunta)
        <div class="card shadow-lg pregunta" id="pregunta-{{ $loop->index }}" style="{{ $loop->index > 0 ? 'display: none;' : '' }}">
            <div class="card-body">
                <div class="mb-4">
                    <span class="badge bg-primary fs-6">Pregunta {{ $loop->iteration }} de {{ $cuestionario->preguntas->count() }}</span>
                    <h4 class="mt-3">{{ $pregunta->enunciado }}</h4>
                    <p class="text-muted">Puntos: {{ $pregunta->puntaje }}</p>
                </div>

                @if ($pregunta->tipo === 'opcion_multiple')
                    @foreach ($pregunta->respuestas as $respuesta)
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="respuestas[{{ $pregunta->id }}]" id="respuesta{{ $respuesta->id }}" value="{{ $respuesta->id }}"
                            {{ (old('respuestas.'.$pregunta->id) == $respuesta->id || (session('respuestas_previas') && isset(session('respuestas_previas')[$pregunta->id]) && session('respuestas_previas')[$pregunta->id] == $respuesta->id)) ? 'checked' : '' }}>
                        <label class="form-check-label" for="respuesta{{ $respuesta->id }}">
                            {{ $respuesta->contenido }}
                        </label>
                    </div>
                    @endforeach
                @elseif ($pregunta->tipo === 'boolean')
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="respuestas[{{ $pregunta->id }}]" id="verdadero{{ $pregunta->id }}" value="1"
                            {{ (old('respuestas.'.$pregunta->id) == '1' || (session('respuestas_previas') && isset(session('respuestas_previas')[$pregunta->id]) && session('respuestas_previas')[$pregunta->id] == '1')) ? 'checked' : '' }}>
                        <label class="form-check-label" for="verdadero{{ $pregunta->id }}">
                            Verdadero
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="respuestas[{{ $pregunta->id }}]" id="falso{{ $pregunta->id }}" value="0"
                            {{ (old('respuestas.'.$pregunta->id) == '0' || (session('respuestas_previas') && isset(session('respuestas_previas')[$pregunta->id]) && session('respuestas_previas')[$pregunta->id] == '0')) ? 'checked' : '' }}>
                        <label class="form-check-label" for="falso{{ $pregunta->id }}">
                            Falso
                        </label>
                    </div>
                @else
                    <div class="form-group">
                        <textarea class="form-control" name="respuestas[{{ $pregunta->id }}]" rows="3" placeholder="Escribe tu respuesta aquí...">{{ old('respuestas.'.$pregunta->id) ?? (session('respuestas_previas') && isset(session('respuestas_previas')[$pregunta->id]) ? session('respuestas_previas')[$pregunta->id] : '') }}</textarea>
                    </div>
                @endif

                <div class="d-flex justify-content-between mt-4">
                    @if (!$loop->first)
                        <button type="button" class="btn btn-outline-secondary btn-lg btn-anterior">
                            ← Anterior
                        </button>
                    @else
                        <span></span>
                    @endif

                    @if (!$loop->last)
                        <button type="button" class="btn btn-primary btn-lg btn-siguiente">
                            Siguiente →
                        </button>
                    @else
                        <button type="submit" class="btn btn-success btn-lg" id="btnSubmit">
                            ✅ Enviar Respuestas
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </form>
</div>

<!-- Script mejorado -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    let formSubmitted = false;

    const form = document.querySelector('form');
    const preguntas = document.querySelectorAll('.pregunta');
    const progressBar = document.getElementById('progressBar');
    const btnSubmit = document.getElementById('btnSubmit');
    let preguntaActual = 0;

    // Verificar si hay un error de validación (respuestas previas)
    const hayError = @json(session('error') ? true : false);

    // Si hay error, mostrar todas las preguntas para que el usuario pueda ver sus respuestas
    if (hayError) {
        // Mostrar todas las preguntas cuando hay error de validación
        preguntas.forEach((pregunta, i) => {
            pregunta.style.display = 'block';
            pregunta.style.marginBottom = '2rem';

            // Verificar si la pregunta tiene respuesta
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
                pregunta.classList.add('pregunta-sin-respuesta');

                // Agregar icono de advertencia
                const badge = pregunta.querySelector('.badge');
                if (badge) {
                    badge.innerHTML = '<i class="bi bi-exclamation-triangle me-1"></i>' + badge.textContent;
                }
            }
        });
        progressBar.style.width = '100%';

        // Agregar mensaje informativo
        const mensajeInfo = document.createElement('div');
        mensajeInfo.className = 'alert alert-info mt-3';
        mensajeInfo.innerHTML = '<i class="bi bi-info-circle me-2"></i>Revisa todas las preguntas y completa las que faltan antes de enviar. Las preguntas sin responder están resaltadas en rojo.';
        form.insertBefore(mensajeInfo, form.firstChild);

    } else {
        // Comportamiento normal: mostrar preguntas una por una

        // Enviar - protección doble
        form.addEventListener('submit', function() {
            formSubmitted = true;
            if (btnSubmit) {
                btnSubmit.disabled = true;
                btnSubmit.innerText = 'Enviando...';
            }
        });

        // Advertencia si intenta abandonar
        window.addEventListener('beforeunload', function(e) {
            if (!formSubmitted) {
                e.preventDefault();
                e.returnValue = '';
                return '';
            }
        });

        function actualizarProgreso() {
            const progreso = ((preguntaActual + 1) / preguntas.length) * 100;
            progressBar.style.width = `${progreso}%`;
        }

        function mostrarPregunta(index) {
            preguntas.forEach((pregunta, i) => {
                pregunta.style.display = i === index ? 'block' : 'none';
            });
            actualizarProgreso();
        }

        preguntas.forEach((pregunta, index) => {
            const btnSiguiente = pregunta.querySelector('.btn-siguiente');
            const btnAnterior = pregunta.querySelector('.btn-anterior');

            if (btnSiguiente) {
                btnSiguiente.addEventListener('click', () => {
                    if (preguntaActual < preguntas.length - 1) {
                        preguntaActual++;
                        mostrarPregunta(preguntaActual);
                    }
                });
            }

            if (btnAnterior) {
                btnAnterior.addEventListener('click', () => {
                    if (preguntaActual > 0) {
                        preguntaActual--;
                        mostrarPregunta(preguntaActual);
                    }
                });
            }
        });

        mostrarPregunta(preguntaActual);
    }
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tiempoLimite = @json($cuestionario->tiempo_limite); // en minutos
        const tiempoDisplay = document.getElementById('tiempoRestante');
        const form = document.querySelector('form');

        if (tiempoLimite) {
            let tiempoRestante = tiempoLimite * 60; // en segundos

            const actualizarTemporizador = () => {
                const minutos = Math.floor(tiempoRestante / 60);
                const segundos = tiempoRestante % 60;
                tiempoDisplay.textContent = `⏳ Tiempo restante: ${minutos}:${segundos.toString().padStart(2, '0')}`;

                if (tiempoRestante <= 0) {
                    tiempoDisplay.textContent = "⏳ Tiempo agotado. Enviando...";
                    form.submit();
                }

                tiempoRestante--;
            };

            actualizarTemporizador();
            setInterval(actualizarTemporizador, 1000);
        }
    });
    </script>

@push('scripts')
<script>
    // Variables para el temporizador
    let tiempoLimite = {{ $cuestionario->tiempo_limite ?? 0 }} * 60; // Convertir a segundos
    let tiempoRestante = tiempoLimite;
    let temporizador;

    // Función para formatear el tiempo
    function formatearTiempo(segundos) {
        const minutos = Math.floor(segundos / 60);
        const segs = segundos % 60;
        return `${minutos.toString().padStart(2, '0')}:${segs.toString().padStart(2, '0')}`;
    }

    // Función para actualizar el temporizador
    function actualizarTemporizador() {
        if (tiempoLimite > 0) {
            tiempoRestante--;
            document.getElementById('temporizador').textContent = formatearTiempo(tiempoRestante);

            if (tiempoRestante <= 0) {
                clearInterval(temporizador);
                alert('¡Se acabó el tiempo! El cuestionario se enviará automáticamente.');
                document.getElementById('cuestionarioForm').submit();
            } else if (tiempoRestante <= 60) {
                document.getElementById('temporizador').style.color = 'red';
            }
        }
    }

    // Iniciar temporizador si hay tiempo límite
    if (tiempoLimite > 0) {
        temporizador = setInterval(actualizarTemporizador, 1000);
    }

    // Prevenir navegación con botones del navegador
    window.history.pushState(null, '', window.location.href);
    window.onpopstate = function() {
        window.history.pushState(null, '', window.location.href);
        mostrarAdvertencia();
    };

    // Prevenir recarga de página
    window.onbeforeunload = function(e) {
        if (!window.submitted) {
            const mensaje = '¿Estás seguro de que quieres abandonar? Se perderán todas tus respuestas.';
            e.returnValue = mensaje;
            return mensaje;
        }
    };

    // Función para mostrar advertencia
    function mostrarAdvertencia() {
        Swal.fire({
            title: '¡Atención!',
            text: 'No uses los botones de navegación del navegador. Si necesitas salir, usa el botón "Enviar" para guardar tus respuestas.',
            icon: 'warning',
            confirmButtonText: 'Entendido'
        });
    }

    // Manejar envío del formulario
    document.getElementById('cuestionarioForm').onsubmit = function() {
        window.submitted = true;
        // Registrar el abandono si se cierra la página
        window.onbeforeunload = null;

        // Detener el temporizador si existe
        if (temporizador) {
            clearInterval(temporizador);
        }
    };

    // Detectar cuando la pestaña pierde el foco
    document.addEventListener('visibilitychange', function() {
        if (document.visibilityState === 'hidden') {
            // Registrar intento de cambio de pestaña
            fetch('/cuestionarios/{{ $cuestionario->id }}/registrar-abandono', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            }).catch(function(error) {
                console.error('Error al registrar abandono:', error);
            });
        }
    });

    // Detectar si el usuario intenta copiar o pegar
    document.addEventListener('copy', function(e) {
        e.preventDefault();
        mostrarAdvertenciaCopiar();
    });

    document.addEventListener('paste', function(e) {
        e.preventDefault();
        mostrarAdvertenciaCopiar();
    });

    function mostrarAdvertenciaCopiar() {
        Swal.fire({
            title: '¡Acción no permitida!',
            text: 'No está permitido copiar o pegar durante el cuestionario.',
            icon: 'error',
            confirmButtonText: 'Entendido'
        });
    }

    // Mostrar advertencia inicial
    window.onload = function() {
        Swal.fire({
            title: 'Instrucciones importantes',
            html: `
                <ul class="text-left">
                    <li>No uses los botones de navegación del navegador</li>
                    <li>No cambies de pestaña o ventana</li>
                    <li>No está permitido copiar o pegar</li>
                    ${tiempoLimite > 0 ? `<li>Tienes ${Math.floor(tiempoLimite/60)} minutos para completar el cuestionario</li>` : ''}
                    <li>Usa el botón "Enviar" cuando termines</li>
                </ul>
            `,
            icon: 'info',
            confirmButtonText: 'Entendido, comenzar'
        });
    };
</script>
@endpush

@section('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    #temporizador {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 10px 20px;
        background-color: #fff;
        border: 2px solid #ddd;
        border-radius: 5px;
        font-size: 1.2em;
        font-weight: bold;
        z-index: 1000;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .pregunta {
        margin-bottom: 2rem;
        padding: 1rem;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #fff;
    }

    .pregunta-sin-respuesta {
        border: 2px solid #dc3545;
        background-color: #fff5f5;
    }

    .pregunta-sin-respuesta .badge {
        background-color: #dc3545 !important;
    }

    .respuestas {
        margin-top: 1rem;
    }

    .form-check {
        margin: 0.5rem 0;
    }
</style>
@endsection

@endsection

