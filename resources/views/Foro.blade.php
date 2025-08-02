@section('titulo')
Foro de Discusión
@endsection

@section('content')

<div class="bg-primary text-white py-4 mb-4">
    <div class="container">
        <h1 class="h3 mb-0">{{ $foro->nombreForo }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('Curso', ['id' => encrypt($foro->cursos->id)]) }}" class="text-white-50">Curso</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Foro de Discusión</li>
            </ol>
        </nav>
    </div>
</div>
    <div class="container py-4">
        <!-- Sistema de Notificaciones -->
        <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1500">
            <!-- Toast para XP -->
            @if (session('xp_earned'))
            <div class="toast show xp-toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
                <div class="toast-header bg-success text-white">
                    <i class="bi bi-stars me-2"></i>
                    <strong class="me-auto">¡XP Ganado!</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    <p class="mb-0">¡Has ganado {{ session('xp_earned') }} XP!</p>
                </div>
            </div>
            @endif

            <!-- Toast para Logros -->
            @if (session('achievement'))
            <div class="toast show achievement-toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="4000">
                <div class="toast-header bg-primary text-white">
                    <i class="bi bi-trophy-fill me-2"></i>
                    <strong class="me-auto">¡Nuevo Logro Desbloqueado!</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    <h6 class="mb-1">{{ session('achievement')['title'] }}</h6>
                    <p class="mb-1">{{ session('achievement')['description'] }}</p>
                    <small class="text-muted">+{{ session('achievement')['xp_reward'] }} XP de bonificación</small>
                </div>
            </div>
            @endif

            <!-- Toast para Subida de Nivel -->
            @if (session('level_up'))
            <div class="toast show level-up-toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                <div class="toast-header bg-warning text-dark">
                    <i class="bi bi-arrow-up-circle-fill me-2"></i>
                    <strong class="me-auto">¡Subiste de Nivel!</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    <h6 class="mb-1">¡Nivel {{ session('level_up')['new_level'] }}!</h6>
                    <p class="mb-0">Has alcanzado un nuevo nivel. ¡Sigue así!</p>
                </div>
            </div>
            @endif
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('Curso', ['id' => encrypt($foro->cursos->id)]) }}"
               class="btn btn-outline-primary btn-lg d-flex align-items-center gap-2">
                <i class="bi bi-arrow-left"></i>
                <span>Volver al Curso</span>
            </a>
            <button class="btn btn-primary btn-lg d-flex align-items-center gap-2"
                    data-bs-toggle="modal"
                    data-bs-target="#commentModal">
                <i class="bi bi-plus-circle-fill"></i>
                <span>Nueva Discusión</span>
            </button>
        </div>

        <!-- Descripción del Foro -->
        <div class="card border-0 shadow-sm mb-4 overflow-hidden">
            <div class="card-header bg-light border-0 py-3">
                <h4 class="card-title mb-0">
                    <i class="bi bi-info-circle-fill text-primary me-2"></i>
                    Descripción del Foro
                </h4>
            </div>
            <div class="card-body">
                <div class="p-3 bg-light rounded">
                    {!! nl2br(e($foro->descripcionForo)) !!}
                </div>
            </div>
        </div>

        <!-- Mensajes -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h3 class="card-title mb-0">
                    <i class="bi bi-chat-square-text-fill text-primary me-2"></i>
                    Discusiones
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="messages-container p-3">
                    @forelse ($forosmensajes as $mensaje)
                        <article class="card border-0 shadow-hover mb-4">
                            <div class="card-body">
                                <!-- Mensaje Principal -->
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-circle bg-primary text-white">
                                            {{ strtoupper(substr($mensaje->estudiantes->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h5 class="mb-1">
                                                <span class="fw-bold">{{ $mensaje->estudiantes->name }} {{ $mensaje->estudiantes->lastname1 }} {{ $mensaje->estudiantes->lastname2 }}</span>
                                            </h5>
                                            <p class="text-muted small mb-0">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $mensaje->created_at->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-link text-muted" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editMessageModal-{{ $mensaje->id }}">
                                                    <i class="bi bi-pencil-fill me-2"></i>Editar
                                                </button>
                                            </li>
                                            <li>
                                                <form action="{{ route('foro.mensaje.delete', encrypt($mensaje->id)) }}" method="POST"
                                                    onsubmit="return confirm('¿Estás seguro de eliminar este mensaje?')">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="bi bi-trash-fill me-2"></i>Eliminar
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <h6 class="card-subtitle mb-2 text-primary">{{ $mensaje->tituloMensaje }}</h6>
                                <p class="card-text mb-3">{{ $mensaje->mensaje }}</p>

                                <button class="btn btn-light btn-sm d-flex align-items-center gap-2"
                                        data-bs-toggle="modal"
                                        data-bs-target="#replyModal-{{ $mensaje->id }}">
                                    <i class="bi bi-reply-fill"></i>
                                    <span>Responder</span>
                                </button>

                                <!-- Respuestas -->
                                @if ($mensaje->respuestas->count() > 0)
                                    <div class="replies-container mt-3 ps-4 border-start">
                                        @foreach ($mensaje->respuestas as $respuesta)
                                            <div class="card bg-light border-0 shadow-sm mb-3">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div class="avatar-circle-sm bg-secondary text-white">
                                                                {{ strtoupper(substr($respuesta->estudiantes->name, 0, 1)) }}
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-1">
                                                                    {{ $respuesta->estudiantes->name }} {{ $respuesta->estudiantes->lastname1 }} {{ $respuesta->estudiantes->lastname2 }}
                                                                </h6>
                                                                <p class="text-muted small mb-0">
                                                                    {{ $respuesta->created_at->format('d/m/Y H:i') }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="dropdown">
                                                            <button class="btn btn-link text-muted btn-sm" type="button" data-bs-toggle="dropdown">
                                                                <i class="bi bi-three-dots-vertical"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <button class="dropdown-item" data-bs-toggle="modal"
                                                                            data-bs-target="#editRespuestaModal-{{ $respuesta->id }}">
                                                                        <i class="bi bi-pencil-fill me-2"></i>Editar
                                                                    </button>
                                                                </li>
                                                                <li>
                                                                    <form action="{{ route('foro.respuesta.delete', encrypt($respuesta->id)) }}"
                                                                          method="POST"
                                                                          onsubmit="return confirm('¿Estás seguro de eliminar esta respuesta?')">
                                                                        @csrf
                                                                        <button type="submit" class="dropdown-item text-danger">
                                                                            <i class="bi bi-trash-fill me-2"></i>Eliminar
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <h6 class="text-primary mb-2">{{ $respuesta->tituloMensaje }}</h6>
                                                    <p class="card-text mb-0">{{ $respuesta->mensaje }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </article>

                        <!-- Modal para editar mensaje -->
                        @include('partials.modals.edit-message', ['mensaje' => $mensaje])

                        <!-- Modal para responder -->
                        @include('partials.modals.reply-message', ['mensaje' => $mensaje, 'foro' => $foro])

                        <!-- Modal para editar respuesta -->
                        @foreach ($mensaje->respuestas as $respuesta)
                            @include('partials.modals.edit-reply', ['respuesta' => $respuesta])
                        @endforeach

                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-chat-square-text display-1 text-muted mb-3"></i>
                            <h4 class="text-muted">No hay mensajes en este foro</h4>
                            <p class="text-muted mb-0">¡Sé el primero en iniciar una discusión!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para nuevo comentario -->
    @include('partials.modals.new-comment', ['foro' => $foro])

    @if (session('success'))
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
                <div class="toast-header bg-success text-white">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <strong class="me-auto">¡Éxito!</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    {{ session('success') }}
                </div>
            </div>
        </div>
    @endif

    <style>
        .shadow-hover {
            transition: all 0.3s ease;
        }
        .shadow-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        }
        .avatar-circle {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
        }
        .avatar-circle-sm {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
        }
        .replies-container {
            border-left-color: #dee2e6!important;
        }
        .modal-content {
            border: 0;
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
        }
        .toast {
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        }
        .toast {
            opacity: 1 !important;
        }

        .xp-toast {
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .achievement-toast {
            background-color: #cce5ff;
            border-color: #b8daff;
        }

        .level-up-toast {
            background-color: #fff3cd;
            border-color: #ffeeba;
        }

        .toast .toast-header {
            border-bottom: none;
        }

        .toast .toast-body {
            padding: 1rem;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar todos los tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Auto-ocultar toasts después de un tiempo
            var toastElList = [].slice.call(document.querySelectorAll('.toast'))
            toastElList.forEach(function(toastEl) {
                var toast = new bootstrap.Toast(toastEl, {
                    autohide: true,
                    delay: parseInt(toastEl.getAttribute('data-bs-delay')) || 3000
                });

                // Añadir animación de desvanecimiento
                toastEl.addEventListener('hide.bs.toast', function () {
                    this.style.transition = 'opacity 0.5s ease-out';
                    this.style.opacity = '0';
                });
            });

            // Reproducir sonido para logros y subidas de nivel
            if (document.querySelector('.achievement-toast')) {
                new Audio('/sounds/achievement.mp3').play().catch(function(error) {
                    console.log("Error reproduciendo sonido de logro:", error);
                });
            }

            if (document.querySelector('.level-up-toast')) {
                new Audio('/sounds/level-up.mp3').play().catch(function(error) {
                    console.log("Error reproduciendo sonido de nivel:", error);
                });
            }
        });
    </script>
@endsection

@include('layout')
