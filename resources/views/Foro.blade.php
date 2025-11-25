@section('titulo')
Foro de Discusión
@endsection

@section('content')

<!-- Header Modernizado del Foro -->
<div class="forum-hero-header">
    <div class="container">
        <div class="forum-header-content">
            <!-- Información Principal -->
            <div class="forum-main-info">
                <div class="forum-icon-badge">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="forum-text-content">
                    <h1 class="forum-title">{{ $foro->nombreForo }}</h1>
                    @if($foro->SubtituloForo)
                    <p class="forum-subtitle">{{ $foro->SubtituloForo }}</p>
                    @endif

                    <!-- Metadatos del Foro -->
                    <div class="forum-metadata">
                        <div class="meta-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Creado: {{ $foro->created_at }}</span>
                        </div>
                        @if($foro->fechaFin)
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            <span>Cierra: {{ $foro->fechaFin }}</span>
                        </div>
                        @endif
                        <div class="meta-item">
                            <i class="fas fa-comment-dots"></i>
                            <span>{{ $foro->foromensaje->count() }} mensajes</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-eye"></i>
                            <span>{{ $foro->vistas_count ?? 0 }} vistas</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="forum-actions">
                <a href="{{ route('Curso', $foro->cursos) }}" class="btn btn-outline-light btn-back">
                    <i class="fas fa-arrow-left me-2"></i>
                    Volver al Curso
                </a>

                @if(auth()->user()->id == $foro->docente_id)
                <div class="admin-actions">
                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditarForo-{{ $foro->id }}">
                        <i class="fas fa-edit me-1"></i>
                        Editar
                    </button>
                    <form class="d-inline" action="{{ route('quitarForo', encrypt($foro->id)) }}" method="POST"
                          onsubmit="return confirm('¿Estás seguro de eliminar este foro?')">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-trash me-1"></i>
                            Eliminar
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>

        <!-- Breadcrumb Mejorado -->
        <nav class="forum-breadcrumb" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('Inicio') }}" class="breadcrumb-link">
                        <i class="fas fa-home me-1"></i>
                        Inicio
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('Curso', $foro->cursos) }}" class="breadcrumb-link">
                        <i class="fas fa-book me-1"></i>
                        {{ $foro->cursos->nombreCurso }}
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="#tab-foros" class="breadcrumb-link">
                        <i class="fas fa-comments me-1"></i>
                        Foros
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <i class="fas fa-comment-dots me-1"></i>
                    Discusión
                </li>
            </ol>
        </nav>
    </div>
</div>

<style>
/* Variables CSS */
:root {
    --color-primary: #1a4789;
    --color-secondary: #39a6cb;
    --color-success: #28a745;
    --color-warning: #ffc107;
    --color-danger: #dc3545;

    --gradient-primary: linear-gradient(135deg, #1a4789 0%, #055c9d 100%);
    --gradient-primary-hover: linear-gradient(135deg, #0d3568 0%, #044a7a 100%);

    --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
    --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.12);
    --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.15);

    --border-radius: 12px;
    --border-radius-sm: 8px;
}

/* Header Principal */
.forum-hero-header {
    background: var(--gradient-primary);
    color: white;
    padding: 2.5rem 0 1.5rem 0;
    position: relative;
    overflow: hidden;
}

.forum-hero-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.03' fill-rule='evenodd'/%3E%3C/svg%3E");
    opacity: 0.1;
}

/* Contenido del Header */
.forum-header-content {
    position: relative;
    z-index: 2;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 2rem;
    margin-bottom: 1.5rem;
}

.forum-main-info {
    display: flex;
    align-items: flex-start;
    gap: 1.5rem;
    flex: 1;
    min-width: 300px;
}

.forum-icon-badge {
    width: 80px;
    height: 80px;
    border-radius: var(--border-radius);
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    flex-shrink: 0;
}

.forum-text-content {
    flex: 1;
}

.forum-title {
    font-size: 2.25rem;
    font-weight: 700;
    margin: 0 0 0.5rem 0;
    line-height: 1.2;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.forum-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin: 0 0 1.5rem 0;
    font-weight: 400;
    line-height: 1.4;
}

/* Metadatos */
.forum-metadata {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    align-items: center;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    opacity: 0.9;
}

.meta-item i {
    width: 16px;
    text-align: center;
    opacity: 0.8;
}

/* Acciones */
.forum-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    align-items: flex-end;
}

.btn-back {
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: var(--border-radius-sm);
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
    text-decoration: none;
    display: flex;
    align-items: center;
}

.btn-back:hover {
    background: rgba(255, 255, 255, 0.15);
    border-color: rgba(255, 255, 255, 0.5);
    transform: translateY(-1px);
}

.admin-actions {
    display: flex;
    gap: 0.5rem;
}

.admin-actions .btn {
    border-radius: var(--border-radius-sm);
    font-weight: 500;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.admin-actions .btn-light {
    background: rgba(255, 255, 255, 0.95);
    color: var(--color-primary);
    border-color: rgba(255, 255, 255, 0.3);
}

.admin-actions .btn-light:hover {
    background: white;
    transform: translateY(-1px);
    box-shadow: var(--shadow-sm);
}

.admin-actions .btn-outline-light {
    border: 2px solid rgba(255, 255, 255, 0.3);
    background: transparent;
}

.admin-actions .btn-outline-light:hover {
    background: rgba(255, 255, 255, 0.15);
    border-color: rgba(255, 255, 255, 0.5);
}

/* Breadcrumb Mejorado */
.forum-breadcrumb {
    position: relative;
    z-index: 2;
}

.breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.breadcrumb-item {
    display: flex;
    align-items: center;
    font-size: 0.9rem;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    color: rgba(255, 255, 255, 0.6);
    padding: 0 0.5rem;
    font-size: 1.2rem;
}

.breadcrumb-link {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    padding: 0.25rem 0.5rem;
    border-radius: var(--border-radius-sm);
}

.breadcrumb-link:hover {
    color: white;
    background: rgba(255, 255, 255, 0.1);
}

.breadcrumb-item.active {
    color: white;
    opacity: 0.9;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Estados del Foro */
.forum-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255, 255, 255, 0.15);
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    margin-left: 1rem;
}

/* Responsive */
@media (max-width: 768px) {
    .forum-hero-header {
        padding: 2rem 0 1rem 0;
    }

    .forum-header-content {
        flex-direction: column;
        gap: 1.5rem;
        text-align: center;
    }

    .forum-main-info {
        flex-direction: column;
        text-align: center;
        align-items: center;
    }

    .forum-actions {
        align-items: center;
        width: 100%;
    }

    .forum-actions .btn-back {
        width: 100%;
        justify-content: center;
    }

    .admin-actions {
        justify-content: center;
        width: 100%;
    }

    .forum-title {
        font-size: 1.75rem;
    }

    .forum-icon-badge {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }

    .forum-metadata {
        justify-content: center;
        gap: 1rem;
    }

    .breadcrumb {
        justify-content: center;
    }

    .breadcrumb-item {
        font-size: 0.8rem;
    }
}

@media (max-width: 576px) {
    .forum-metadata {
        flex-direction: column;
        gap: 0.75rem;
        align-items: center;
    }

    .meta-item {
        justify-content: center;
    }

    .admin-actions {
        flex-direction: column;
        width: 100%;
    }

    .admin-actions .btn {
        width: 100%;
        justify-content: center;
    }
}

/* Animaciones */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.forum-header-content,
.forum-breadcrumb {
    animation: fadeInUp 0.6s ease-out;
}

/* Efectos de hover mejorados */
.breadcrumb-link,
.btn-back,
.admin-actions .btn {
    position: relative;
    overflow: hidden;
}

.breadcrumb-link::before,
.btn-back::before,
.admin-actions .btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.breadcrumb-link:hover::before,
.btn-back:hover::before,
.admin-actions .btn:hover::before {
    left: 100%;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Efecto de parallax suave en el header
    const forumHeader = document.querySelector('.forum-hero-header');

    if (forumHeader) {
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            forumHeader.style.transform = `translateY(${rate}px)`;
        });
    }

    // Animación de los elementos al cargar
    const animatedElements = document.querySelectorAll('.forum-main-info, .forum-actions, .forum-breadcrumb');
    animatedElements.forEach((element, index) => {
        element.style.animationDelay = `${index * 0.2}s`;
    });

    // Tooltips para botones de administración
    const adminButtons = document.querySelectorAll('.admin-actions .btn');
    adminButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });

        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Confirmación mejorada para eliminar
    const deleteButtons = document.querySelectorAll('form[action*="quitarForo"] button[type="submit"]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('¿Estás seguro de que deseas eliminar este foro y todos sus mensajes?\nEsta acción no se puede deshacer.')) {
                e.preventDefault();
            }
        });
    });
});
</script>
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

    @foreach ($forosmensajes as $mensaje)
                        <!-- Modal para editar mensaje -->
                        @include('partials.modals.edit-message', ['mensaje' => $mensaje])

                        <!-- Modal para responder -->
                        @include('partials.modals.reply-message', ['mensaje' => $mensaje, 'foro' => $foro])

                        <!-- Modal para editar respuesta -->
                        @foreach ($mensaje->respuestas as $respuesta)
                            @include('partials.modals.edit-reply', ['respuesta' => $respuesta])
                        @endforeach
    @endforeach

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
