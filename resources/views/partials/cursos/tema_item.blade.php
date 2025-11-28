<style>
    :root {
        --color-primary: #1a4789;
        --color-secondary: #39a6cb;
        --color-accent1: #63becf;
        --color-accent2: #055c9d;
        --color-accent3: #2197bd;
        --color-success: #28a745;
        --color-warning: #ffc107;
        --color-danger: #dc3545;
        --color-info: #17a2b8;

        --gradient-primary: linear-gradient(135deg, #1a4789 0%, #055c9d 100%);
        --gradient-secondary: linear-gradient(135deg, #39a6cb 0%, #63becf 100%);
        --gradient-success: linear-gradient(135deg, #28a745 0%, #20c997 100%);

        --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
        --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.12);
        --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.15);

        --border-radius: 16px;
        --border-radius-sm: 12px;
        --border-radius-lg: 20px;
    }

    /* Contenedor Principal Mejorado */
    .tema-container {
        background: linear-gradient(135deg, #f8fafc 0%, #e9ecef 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }

    /* Header del Tema - Diseño Hero */
    .theme-hero-section {
        position: relative;
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-lg);
    }

    .theme-hero-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: var(--gradient-primary);
        z-index: 1;
    }

    .theme-hero-background::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
        opacity: 0.1;
    }

    .theme-hero-content {
        position: relative;
        z-index: 2;
        padding: 3rem 2rem;
        color: white;
    }

    .theme-breadcrumb {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .theme-breadcrumb a {
        color: white;
        text-decoration: none;
        transition: opacity 0.2s ease;
    }

    .theme-breadcrumb a:hover {
        opacity: 0.8;
    }

    .theme-main-info {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
    }

    .theme-title-section h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .theme-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 0;
    }

    .theme-stats-grid {
        display: flex;
        gap: 1rem;
    }

    .theme-stat {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: var(--border-radius-sm);
        padding: 0.75rem 1rem;
        text-align: center;
        min-width: 100px;
    }

    .theme-stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        display: block;
    }

    .theme-stat-label {
        font-size: 0.8rem;
        opacity: 0.9;
    }

    /* Descripción Expandible Mejorada */
    .theme-description-enhanced {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: var(--border-radius-sm);
        overflow: hidden;
    }

    .description-toggle-enhanced {
        padding: 1.5rem;
        border: none;
        background: none;
        color: white;
        width: 100%;
        text-align: left;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .description-toggle-enhanced:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .description-toggle-enhanced i:first-child {
        font-size: 1.2rem;
        margin-right: 0.75rem;
    }

    .toggle-icon-enhanced {
        transition: transform 0.3s ease;
    }

    .description-toggle-enhanced[aria-expanded="true"] .toggle-icon-enhanced {
        transform: rotate(180deg);
    }

    .description-content-enhanced {
        padding: 0 1.5rem 1.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        line-height: 1.6;
    }

    /* Acciones del Docente Mejoradas */
    .teacher-actions-enhanced {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        flex-wrap: wrap;
    }

    .btn-modern-enhanced {
        border: none;
        border-radius: var(--border-radius-sm);
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-primary-enhanced {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .btn-primary-enhanced:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    /* Contenedor de Subtemas - Timeline Design */
    .subtopics-timeline {
        position: relative;
        max-width: 1200px;
        margin: 0 auto;
    }

    .subtopics-timeline::before {
        content: '';
        position: absolute;
        left: 30px;
        top: 0;
        bottom: 0;
        width: 3px;
        background: var(--gradient-secondary);
        border-radius: 3px;
    }

    .subtopic-timeline-item {
        position: relative;
        margin-bottom: 2rem;
        padding-left: 80px;
    }

    .subtopic-timeline-marker {
        position: absolute;
        left: 18px;
        top: 0;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: white;
        border: 3px solid var(--color-secondary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: var(--color-primary);
        z-index: 2;
        box-shadow: var(--shadow-sm);
    }

    .subtopic-timeline-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        transition: all 0.3s ease;
        border-left: 4px solid var(--color-secondary);
    }

    .subtopic-timeline-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }

    .subtopic-timeline-card.locked {
        opacity: 0.7;
        border-left-color: var(--color-warning);
    }

    .subtopic-timeline-header {
        padding: 1.5rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 1px solid #dee2e6;
        cursor: pointer;
    }

    .subtopic-header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .subtopic-timeline-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .subtopic-timeline-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--color-primary);
        margin: 0;
    }

    .subtopic-timeline-meta {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .subtopic-meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--color-muted);
        font-size: 0.875rem;
    }

    .subtopic-timeline-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .subtopic-toggle-timeline {
        background: none;
        border: none;
        color: var(--color-primary);
        font-size: 1.1rem;
        transition: transform 0.3s ease;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: var(--border-radius-sm);
    }

    .subtopic-toggle-timeline:hover {
        background: rgba(57, 166, 203, 0.1);
    }

    .subtopic-toggle-timeline.active {
        transform: rotate(180deg);
    }

    /* Estados de Progreso */
    .progress-indicator {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .progress-circle {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--color-success);
    }

    .progress-circle.pending {
        background: var(--color-warning);
    }

    .progress-circle.locked {
        background: var(--color-muted);
    }

    /* Contenido del Subtema Mejorado */
    .subtopic-timeline-content {
        padding: 0;
    }

    .subtopic-content-body {
        padding: 2rem;
    }

    /* Estado Vacío Mejorado */
    .empty-state-enhanced {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-sm);
    }

    .empty-state-icon {
        font-size: 4rem;
        color: var(--color-secondary);
        opacity: 0.5;
        margin-bottom: 1.5rem;
    }

    .empty-state-enhanced h4 {
        color: var(--color-primary);
        margin-bottom: 1rem;
    }

    .empty-state-enhanced p {
        color: var(--color-muted);
        margin-bottom: 2rem;
    }

    /* Modal Mejorado */
    .modal-enhanced .modal-content {
        border: none;
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-xl);
        overflow: hidden;
    }

    .modal-enhanced .modal-header {
        background: var(--gradient-primary);
        color: white;
        border-bottom: none;
        padding: 2rem;
    }

    .modal-enhanced .modal-body {
        padding: 2rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .theme-main-info {
            flex-direction: column;
            gap: 1rem;
        }

        .theme-stats-grid {
            width: 100%;
            justify-content: space-between;
        }

        .subtopic-timeline-item {
            padding-left: 60px;
        }

        .subtopics-timeline::before {
            left: 20px;
        }

        .subtopic-timeline-marker {
            left: 8px;
            width: 25px;
            height: 25px;
            font-size: 0.8rem;
        }

        .subtopic-header-content {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .teacher-actions-enhanced {
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

    .subtopic-timeline-item {
        animation: fadeInUp 0.5s ease forwards;
    }

    .subtopic-timeline-item:nth-child(even) {
        animation-delay: 0.1s;
    }

    .subtopic-timeline-item:nth-child(odd) {
        animation-delay: 0.2s;
    }
</style>

<div class="tema-container">
    <!-- Header Hero del Tema -->
    <div class="theme-hero-section">
        <div class="theme-hero-background"></div>
        <div class="theme-hero-content">
            <!-- Breadcrumb -->


            <!-- Información Principal -->
            <div class="theme-main-info">
                <div class="theme-title-section">
                    <h1>{{ $tema->titulo_tema }}</h1>
                    <p class="theme-subtitle">Explora el contenido y avanza en tu aprendizaje</p>
                </div>

                <div class="theme-stats-grid">
                    <div class="theme-stat">
                        <span class="theme-stat-number">{{ count($tema->subtemas) }}</span>
                        <span class="theme-stat-label">Subtemas</span>
                    </div>
                    @if (auth()->user()->hasRole('Estudiante'))
                        <div class="theme-stat">
                            <span
                                class="theme-stat-number">{{ $tema->calcularProgreso($inscritos2->id ?? null) }}%</span>
                            <span class="theme-stat-label">Completado</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Descripción Expandible -->
            <div class="theme-description-enhanced">
                <button class="description-toggle-enhanced" type="button" data-bs-toggle="collapse"
                    data-bs-target="#descripcionTema-{{ $tema->id }}" aria-expanded="false">
                    <div>
                        <i class="fas fa-info-circle"></i>
                        <span>Descripción del Tema</span>
                    </div>
                    <i class="toggle-icon-enhanced fas fa-chevron-down"></i>
                </button>
                <div class="collapse" id="descripcionTema-{{ $tema->id }}">
                    <div class="description-content-enhanced">
                        {!! nl2br(e($tema->descripcion)) !!}
                    </div>
                </div>
            </div>

            <!-- Acciones del Docente -->
            @if (auth()->user()->hasRole('Docente') && $cursos->docente_id == auth()->user()->id)
                <div class="teacher-actions-enhanced">
                    <button class="btn-modern-enhanced btn-primary-enhanced" data-bs-toggle="modal"
                        data-bs-target="#modalSubtema-{{ $tema->id }}">
                        <i class="fas fa-plus-circle me-2"></i>
                        Nuevo Subtema
                    </button>
                    <button class="btn-modern-enhanced btn-primary-enhanced" data-bs-toggle="modal"
                        data-bs-target="#modalEditarTema-{{ $tema->id }}">
                        <i class="fas fa-edit me-2"></i>
                        Editar Tema
                    </button>
                    <form action="{{ route('temas.delete', encrypt($tema->id)) }}" method="POST"
                        onsubmit="return confirm('¿Estás seguro de eliminar este tema y todos sus subtemas?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-modern-enhanced"
                            style="background: rgba(220, 53, 69, 0.8); color: white;">
                            <i class="fas fa-trash me-2"></i>
                            Eliminar
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <!-- Timeline de Subtemas -->
    <div class="subtopics-timeline">
        @forelse($tema->subtemas as $subtemaIndex => $subtema)
            @php
                $desbloqueado =
                    auth()->user()->hasRole('Docente') ||
                    (auth()->user()->hasRole('Estudiante') && $subtema->estaDesbloqueado($inscritos2->id ?? null));
                $completado = auth()->user()->hasRole('Estudiante')
                    ? $subtema->estaCompletado($inscritos2->id ?? null)
                    : false;
            @endphp

            <div class="subtopic-timeline-item" id="subtema-{{ $subtema->id }}">
                <!-- Marcador de Timeline -->
                <div class="subtopic-timeline-marker">
                    {{ $subtemaIndex + 1 }}
                </div>

                <!-- Card del Subtema -->
                <div class="subtopic-timeline-card {{ !$desbloqueado ? 'locked' : '' }}"
                    data-subtema-id="{{ $subtema->id }}">
                    <!-- Header -->
                    <div class="subtopic-timeline-header" data-bs-toggle="collapse"
                        data-bs-target="#subtemaCollapse-{{ $subtema->id }}"
                        aria-expanded="{{ $subtemaIndex === 0 ? 'true' : 'false' }}">
                        <div class="subtopic-header-content">
                            <div class="subtopic-timeline-info">
                                <h4 class="subtopic-timeline-title">
                                    {{ $subtema->titulo_subtema }}
                                    @if (!$desbloqueado)
                                        <i class="fas fa-lock ms-2 text-warning"></i>
                                    @endif
                                </h4>

                                <div class="subtopic-timeline-meta">
                                    @if ($subtema->duracion)
                                        <div class="subtopic-meta-item">
                                            <i class="fas fa-clock"></i>
                                            <span>{{ $subtema->duracion }}</span>
                                        </div>
                                    @endif

                                    @if (auth()->user()->hasRole('Estudiante'))
                                        <div class="progress-indicator">
                                            <div
                                                class="progress-circle {{ $completado ? 'completed' : ($desbloqueado ? 'pending' : 'locked') }}">
                                            </div>
                                            <small>{{ $completado ? 'Completado' : ($desbloqueado ? 'Disponible' : 'Bloqueado') }}</small>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="subtopic-timeline-actions">
                                <button class="subtopic-toggle-timeline {{ $subtemaIndex === 0 ? 'active' : '' }}">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Contenido -->
                    @if ($desbloqueado || auth()->user()->hasRole('Docente'))
                        <div class="subtopic-timeline-content collapse {{ $subtemaIndex === 0 ? 'show' : '' }}"
                            id="subtemaCollapse-{{ $subtema->id }}">
                            <div class="subtopic-content-body">
                                @include('partials.cursos.subtema_item', [
                                    'subtema' => $subtema,
                                    'tema' => $tema,
                                ])
                            </div>
                        </div>
                    @else
                        <div class="subtopic-timeline-content">
                            <div class="subtopic-content-body text-center py-4">
                                <i class="fas fa-lock fa-2x text-warning mb-3"></i>
                                <h5 class="text-muted">Contenido Bloqueado</h5>
                                <p class="text-muted mb-0">Completa los requisitos anteriores para desbloquear este
                                    contenido.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <!-- Estado Vacío -->
            <div class="empty-state-enhanced">
                <div class="empty-state-icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <h4>No hay subtemas disponibles</h4>
                <p>Aún no se han agregado subtemas a este tema.</p>
                @if (auth()->user()->hasRole('Docente') && $cursos->docente_id == auth()->user()->id)
                    <button class="btn-modern-enhanced btn-primary-enhanced" data-bs-toggle="modal"
                        data-bs-target="#modalSubtema-{{ $tema->id }}">
                        <i class="fas fa-plus me-2"></i>
                        Crear Primer Subtema
                    </button>
                @endif
            </div>
        @endforelse
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animación para los toggles
        const toggleButtons = document.querySelectorAll('.subtopic-toggle-timeline');

        toggleButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                const isActive = this.classList.contains('active');

                // Remover active de todos los botones
                toggleButtons.forEach(btn => btn.classList.remove('active'));

                // Toggle del estado actual
                if (!isActive) {
                    this.classList.add('active');
                }
            });
        });

        // Efecto de scroll suave para los collapses
        const subtemaHeaders = document.querySelectorAll('.subtopic-timeline-header');

        subtemaHeaders.forEach(header => {
            header.addEventListener('click', function() {
                const targetId = this.getAttribute('data-bs-target');
                const targetElement = document.querySelector(targetId);

                if (targetElement) {
                    setTimeout(() => {
                        targetElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest'
                        });
                    }, 300);
                }
            });
        });

        // Efecto de carga progresiva
        const timelineItems = document.querySelectorAll('.subtopic-timeline-item');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, {
            threshold: 0.1
        });

        timelineItems.forEach(item => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            item.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            observer.observe(item);
        });
    });
</script>
