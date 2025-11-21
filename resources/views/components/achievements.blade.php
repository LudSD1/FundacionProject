<!-- Floating XP Button -->
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
        --gradient-gold: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
        --gradient-silver: linear-gradient(135deg, #C0C0C0 0%, #A9A9A9 100%);
        --gradient-bronze: linear-gradient(135deg, #CD7F32 0%, #8B4513 100%);

        --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
        --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.12);
        --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.15);
        --shadow-xl: 0 12px 32px rgba(0, 0, 0, 0.2);

        --border-radius: 12px;
        --border-radius-sm: 8px;
    }

    .floating-xp-container .floating-xp-button {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        z-index: 1000;
        animation: float 3s ease-in-out infinite;
    }

    .floating-xp-container .floating-xp-button .btn {
        width: 70px;
        height: 70px;
        border: none;
        box-shadow: var(--shadow-xl);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .floating-xp-container .floating-xp-button .btn::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transform: rotate(45deg);
        transition: all 0.6s ease;
        opacity: 0;
    }

    .floating-xp-container .floating-xp-button .btn:hover::before {
        animation: shine 1.5s ease;
    }

    .floating-xp-container .floating-xp-button .btn:hover {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 8px 32px rgba(26, 71, 137, 0.4);
    }

    .floating-xp-container .floating-xp-button .btn i {
        font-size: 1.5rem;
        transition: transform 0.3s ease;
    }

    .floating-xp-container .floating-xp-button .btn:hover i {
        transform: scale(1.2);
    }

    .floating-xp-container .btn-primary {
        background: var(--gradient-primary);
    }

    .floating-xp-container .btn-secondary {
        background: var(--gradient-secondary);
    }

    .floating-xp-container .offcanvas {
        border-left: 3px solid var(--color-primary);
    }

    .floating-xp-container .offcanvas-header {
        background: var(--gradient-primary);
        color: white;
        border-bottom: none;
        padding: 1.5rem;
    }

    .floating-xp-container .offcanvas-title {
        font-weight: 700;
        font-size: 1.25rem;
    }

    .floating-xp-container .btn-close {
        filter: invert(1);
        opacity: 0.8;
    }

    .floating-xp-container .btn-close:hover {
        opacity: 1;
    }

    .floating-xp-container .offcanvas-body {
        padding: 1.5rem;
        background: #f8fafc;
    }

    .floating-xp-container .level-card {
        background: var(--gradient-primary);
        border: none;
        border-radius: var(--border-radius);
        color: white;
        box-shadow: var(--shadow-md);
        overflow: hidden;
        position: relative;
    }

    .floating-xp-container .level-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-gold);
    }

    .floating-xp-container .xp-badge {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
    }

    .floating-xp-container .achievement-item {
        transition: all 0.3s ease;
        border-radius: var(--border-radius-sm);
    }

    .floating-xp-container .achievement-item:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }

    .floating-xp-container .achievement-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .floating-xp-container .achievement-gold {
        background: var(--gradient-gold);
        color: #000;
    }

    .floating-xp-container .achievement-silver {
        background: var(--gradient-silver);
        color: #000;
    }

    .floating-xp-container .achievement-bronze {
        background: var(--gradient-bronze);
        color: white;
    }

    .floating-xp-container .achievement-default {
        background: var(--gradient-secondary);
        color: white;
    }

    .floating-xp-container .progress {
        height: 8px;
        border-radius: 10px;
        background: #e9ecef;
        overflow: hidden;
        margin: 0.5rem 0;
    }

    .floating-xp-container .progress-bar {
        background: var(--gradient-gold);
        border-radius: 10px;
        transition: width 1s ease-in-out;
    }

    .floating-xp-container .modal-content {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-xl);
        overflow: hidden;
    }

    .floating-xp-container .modal-header {
        background: var(--gradient-primary);
        color: white;
        border-bottom: none;
        padding: 2rem;
    }

    .floating-xp-container .modal-body {
        padding: 2rem;
        text-align: center;
    }

    .floating-xp-container .feature-list {
        text-align: left;
        margin: 1.5rem 0;
    }

    .floating-xp-container .feature-item {
        display: flex;
        align-items: center;
        margin-bottom: 0.75rem;
        padding: 0.5rem;
        border-radius: var(--border-radius-sm);
        transition: background-color 0.2s ease;
    }

    .floating-xp-container .feature-item:hover {
        background: #f8f9fa;
    }

    .floating-xp-container .feature-item i {
        color: var(--color-success);
        margin-right: 0.75rem;
        font-size: 1.1rem;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    @keyframes shine {
        0% {
            opacity: 0;
            transform: rotate(45deg) translateX(-100%);
        }

        50% {
            opacity: 1;
        }

        100% {
            opacity: 0;
            transform: rotate(45deg) translateX(100%);
        }
    }

    @keyframes pulse {

        0%,
        100% {
            box-shadow: 0 0 0 0 rgba(57, 166, 203, 0.7);
        }

        50% {
            box-shadow: 0 0 0 10px rgba(57, 166, 203, 0);
        }
    }

    .floating-xp-container .xp-pulse {
        animation: pulse 2s infinite;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .floating-xp-container .floating-xp-button {
            bottom: 1rem;
            right: 1rem;
        }

        .floating-xp-container .floating-xp-button .btn {
            width: 60px;
            height: 60px;
        }

        .floating-xp-container .floating-xp-button .btn i {
            font-size: 1.25rem;
        }
    }

    /* Notificación de nuevo logro */
    .new-achievement-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: var(--color-danger);
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 0.7rem;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: bounce 1s infinite;
    }

    @keyframes bounce {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.2);
        }
    }
</style>

<div class="floating-xp-container">
    @auth
        <div class="floating-xp-button">
            <button type="button" class="btn btn-secondary rounded-circle p-0 xp-pulse" data-bs-toggle="offcanvas"
                data-bs-target="#xpOffcanvas" aria-controls="xpOffcanvas">
                <i class="fas fa-trophy"></i>
                @if ($hasNewAchievements ?? false)
                    <span class="new-achievement-badge">!</span>
                @endif
            </button>
        </div>

        <!-- XP Offcanvas -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="xpOffcanvas">
            <div class="offcanvas-header">
                <div>
                    <h5 class="offcanvas-title">Mi Progreso</h5>
                    <small class="opacity-75">Sistema de Logros</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                @php
                    $user = auth()->user();
                    $inscripciones = $user
                        ->inscritos()
                        ->with(['cursos'])
                        ->get();
                    $xpHistory = \DB::table('xp_events')
                        ->where('users_id', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->get();
                    $totalXP = $xpHistory->sum('xp');
                    $currentLevel = \App\Models\Level::getCurrentLevel($totalXP);
                    $nextLevel = \App\Models\Level::getNextLevel($totalXP);
                    $xpDifference = $nextLevel ? $nextLevel->xp_required - $currentLevel->xp_required : 0;
                    $progress =
                        $nextLevel && $xpDifference > 0
                            ? (($totalXP - $currentLevel->xp_required) / $xpDifference) * 100
                            : 100;
                @endphp

                <!-- Level and XP -->
                <div class="card mb-4 level-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h4 class="mb-1 text-primary">Nivel {{ $currentLevel ? $currentLevel->level_number : 1 }}</h4>
                                <p class="mb-0 opacity-75 text-primary">{{ $currentLevel ? $currentLevel->title : 'Principiante' }}</p>
                            </div>
                            <div class="xp-badge text-primary">
                                <i class="fas fa-star me-1"></i>
                                {{ number_format($totalXP) }} XP
                            </div>
                        </div>

                        @if ($nextLevel)
                            <div class="progress-info mb-2">
                                <div class="d-flex justify-content-between small mb-1 text-primary">
                                    <span>Próximo nivel: {{ $nextLevel->title }}</span>
                                    <span>{{ number_format($progress, 0) }}%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" style="width: {{ $progress }}%"></div>
                                </div>
                                <small class="text-center d-block mt-1 opacity-75 text-primary">
                                    {{ number_format($totalXP - $currentLevel->xp_required) }} /
                                    {{ number_format($nextLevel->xp_required - $currentLevel->xp_required) }} XP
                                </small>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Achievements -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Logros Recientes</h6>
                    <small class="text-primary">
                        <i class="fas fa-history me-1"></i>
                        Últimos 3
                    </small>
                </div>

                @php
                    $unlockedAchievements = \App\Models\Achievement::whereHas('inscritos', function ($query) use (
                        $inscripciones,
                    ) {
                        $query->whereIn('inscrito_id', $inscripciones->pluck('id'));
                    })
                        ->with('inscritos')
                        ->latest()
                        ->take(3)
                        ->get();

                    $achievementIcons = ['🥇', '🥈', '🥉', '🏆', '⭐', '🎯'];
                    $achievementClasses = [
                        'achievement-gold',
                        'achievement-silver',
                        'achievement-bronze',
                        'achievement-default',
                    ];
                @endphp

                @forelse($unlockedAchievements as $index => $achievement)
                    <div class="d-flex align-items-center mb-3 p-3 bg-white rounded achievement-item border">
                        <div class="achievement-icon {{ $achievementClasses[$index % count($achievementClasses)] }}">
                            {{ $achievementIcons[$index % count($achievementIcons)] }}
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $achievement->title }}</h6>
                            <p class="mb-1 small text-muted">{{ $achievement->description }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-success">
                                    <i class="fas fa-bolt me-1"></i>
                                    +{{ $achievement->xp_reward }} XP
                                </small>
                                <small class="text-muted">
                                    {{ $achievement->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="fas fa-trophy fa-2x text-muted mb-3"></i>
                        <p class="text-muted mb-0">Aún no has desbloqueado logros</p>
                        <small class="text-muted">¡Completa cursos para ganar recompensas!</small>
                    </div>
                @endforelse

                <!-- Quick Stats -->
                <div class="row text-center mt-4 mb-3">
                    <div class="col-4">
                        <div class="border rounded p-2">
                            <h6 class="mb-1 text-primary">{{ $inscripciones->count() }}</h6>
                            <small class="text-muted">Cursos</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-2">
                            <h6 class="mb-1 text-success">{{ $unlockedAchievements->count() }}</h6>
                            <small class="text-muted">Logros</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-2">
                            <h6 class="mb-1 text-warning">{{ $currentLevel ? $currentLevel->level_number : 1 }}</h6>
                            <small class="text-muted">Nivel</small>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('perfil.xp') }}" class="btn btn-primary w-100">
                        <i class="fas fa-chart-line me-2"></i>
                        Ver progreso completo
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="floating-xp-button">
            <button type="button" class="btn btn-primary rounded-circle p-0" data-bs-toggle="modal"
                data-bs-target="#registerModal">
                <i class="fas fa-trophy"></i>
            </button>
        </div>

        <!-- Registration Modal -->
        <div class="modal fade" id="registerModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">¡Únete a la Aventura del Aprendizaje!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <div class="achievement-icon achievement-gold mx-auto mb-3">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <h4 class="text-primary">Gana XP y Desbloquea Logros</h4>
                            <p class="text-muted">Regístrate para comenzar tu viaje de aprendizaje gamificado</p>
                        </div>

                        <div class="feature-list">
                            <div class="feature-item">
                                <i class="fas fa-medal text-warning"></i>
                                <div>
                                    <strong>Gana experiencia (XP)</strong>
                                    <small class="d-block text-muted">Por completar cursos y actividades</small>
                                </div>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-trophy text-primary"></i>
                                <div>
                                    <strong>Desbloquea logros</strong>
                                    <small class="d-block text-muted">Recompensas por tus progresos</small>
                                </div>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-chart-line text-success"></i>
                                <div>
                                    <strong>Sube de nivel</strong>
                                    <small class="d-block text-muted">Mejora tu ranking en la comunidad</small>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 text-center">
                            <a href="{{ route('signin') }}" class="btn btn-primary btn-lg px-4 me-2">
                                <i class="fas fa-user-plus me-2"></i>
                                Crear Cuenta
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg px-4">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Iniciar Sesión
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endauth
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Efectos de animación para el botón flotante
        const floatingButton = document.querySelector('.floating-xp-button .btn');

        if (floatingButton) {
            floatingButton.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.1) rotate(5deg)';
            });

            floatingButton.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1) rotate(0deg)';
            });

            // Mostrar notificación de nuevos logros
            const newAchievementBadge = this.querySelector('.new-achievement-badge');
            if (newAchievementBadge) {
                setTimeout(() => {
                    newAchievementBadge.style.animation = 'bounce 1s infinite';
                }, 1000);
            }
        }

        // Auto-abrir offcanvas si hay nuevos logros
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('new_achievement') && document.getElementById('xpOffcanvas')) {
            const offcanvas = new bootstrap.Offcanvas(document.getElementById('xpOffcanvas'));
            offcanvas.show();
        }
    });

    // Función para mostrar notificación de nuevo logro
    function showNewAchievement(achievement) {
        // Implementar notificación toast para nuevos logros
        console.log('Nuevo logro desbloqueado:', achievement);
    }
</script>
