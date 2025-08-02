@php
if (auth()->check()) {
    $user = auth()->user();
    $inscripciones = $user->inscritos()->with(['cursos'])->get();
    $xpHistory = \DB::table('xp_events')
        ->where('users_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->get();
    $totalXP = $xpHistory->sum('xp');
    $currentLevel = \App\Models\Level::getCurrentLevel($totalXP);
    $nextLevel = \App\Models\Level::getNextLevel($currentLevel ? $currentLevel->level_number : 1);

    // Calcular el progreso al siguiente nivel
    if ($currentLevel && $nextLevel) {
        $xpForCurrentLevel = $currentLevel->xp_required;
        $xpForNextLevel = $nextLevel->xp_required;
        $xpProgress = $totalXP - $xpForCurrentLevel;
        $xpNeeded = $xpForNextLevel - $xpForCurrentLevel;
        $progressToNext = ($xpNeeded > 0) ? min(100, ($xpProgress / $xpNeeded) * 100) : 0;
    } else {
        $progressToNext = 0;
    }

    $unlockedAchievements = \App\Models\Achievement::whereHas('inscritos', function($query) use ($inscripciones) {
        $query->whereIn('inscrito_id', $inscripciones->pluck('id'));
    })->latest()->take(3)->get();
}
@endphp

<style>
    .floating-xp-button {
        transform: translateY(100px);
        opacity: 0;
        transition: all 0.3s ease-in-out;
        visibility: hidden;
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1050;
    }

    .floating-xp-button.show {
        transform: translateY(0);
        opacity: 1;
        visibility: visible;
    }

    .floating-xp-button .btn {
        transition: transform 0.3s ease;
        width: 50px;
        height: 50px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .floating-xp-button .btn:hover {
        transform: scale(1.1) rotate(15deg);
    }

    .achievement-item {
        opacity: 0;
        transform: translateX(20px);
        transition: all 0.3s ease-out;
    }

    .achievement-item.show {
        opacity: 1;
        transform: translateX(0);
    }

    #xpOffcanvas {
        z-index: 1051;
    }

    .achievement-card {
        border-radius: 10px;
        overflow: hidden;
        transition: transform 0.2s ease;
    }

    .achievement-card:hover {
        transform: translateY(-2px);
    }
</style>

@auth
    <button type="button"
            class="btn  rounded-circle position-fixed bottom-0 end-0 m-4"
            style="z-index: 1050; width: 60px; height: 60px;"
            data-bs-toggle="offcanvas"
            data-bs-target="#achievementsOffcanvas">
        <i class="bi bi-trophy-fill"></i>
    </button>

    <div class="offcanvas offcanvas-end"
         tabindex="-1"
         id="achievementsOffcanvas"
         aria-labelledby="achievementsOffcanvasLabel">

        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="achievementsOffcanvasLabel">
                Mis Logros y Nivel
            </h5>
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
        </div>

        <div class="offcanvas-body">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Nivel {{ $currentLevel ? $currentLevel->level_number : 1 }}</h6>
                            <small>{{ $currentLevel ? $currentLevel->title : 'Principiante' }}</small>
                        </div>
                        <div class="text-end">
                            <h4 class="mb-0">{{ number_format($totalXP) }} XP</h4>
                            <small>Total acumulado</small>
                        </div>
                    </div>
                </div>
            </div>

            <h6 class="mb-3">Últimos Logros</h6>

            @forelse($unlockedAchievements as $achievement)
                <div class="card mb-2">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-award-fill text-success fs-4"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $achievement->name }}</h6>
                                <small class="text-success">+{{ $achievement->xp_value }} XP</small>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted p-4">
                    <i class="bi bi-emoji-smile fs-4 mb-2 d-block"></i>
                    <p class="mb-0">¡Completa cursos para desbloquear logros!</p>
                </div>
            @endforelse

            <div class="mt-4">
                <a href="{{ route('perfil.xp') }}" class="btn btn-primary w-100">
                    Ver todos mis logros
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Esperar a que el DOM esté completamente cargado
        document.addEventListener('DOMContentLoaded', function() {
            const xpButton = document.getElementById('xpButton');
            const achievementItems = document.querySelectorAll('.achievement-item');
            let lastScrollTop = 0;

            // Mostrar el botón después de un retraso
            setTimeout(() => {
                if (xpButton) {
                    xpButton.classList.add('show');
                }
            }, 1000);

            // Manejar el scroll
            function handleScroll() {
                const currentScroll = window.pageYOffset || document.documentElement.scrollTop;

                if (xpButton) {
                    if (currentScroll > lastScrollTop) {
                        xpButton.classList.remove('show');
                    } else {
                        xpButton.classList.add('show');
                    }
                }

                lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
            }

            // Throttle para el evento scroll
            let scrollTimeout;
            window.addEventListener('scroll', function() {
                if (!scrollTimeout) {
                    scrollTimeout = setTimeout(function() {
                        handleScroll();
                        scrollTimeout = null;
                    }, 100);
                }
            });

            // Manejar la animación del offcanvas
            const xpOffcanvas = document.getElementById('xpOffcanvas');
            if (xpOffcanvas) {
                xpOffcanvas.addEventListener('show.bs.offcanvas', function() {
                    achievementItems.forEach((item, index) => {
                        setTimeout(() => {
                            item.classList.add('show');
                        }, index * 100);
                    });
                });

                xpOffcanvas.addEventListener('hidden.bs.offcanvas', function() {
                    achievementItems.forEach(item => {
                        item.classList.remove('show');
                    });
                });
            }
        });
    </script>
    @endpush
@else
    <button type="button"
            class="btn btn-primary rounded-circle position-fixed bottom-0 end-0 m-4"
            style="z-index: 1050; width: 60px; height: 60px;"
            data-bs-toggle="modal"
            data-bs-target="#loginModal">
        <i class="bi bi-trophy-fill"></i>
    </button>

    <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">¡Únete a la aventura!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="bi bi-trophy display-1 text-primary mb-3"></i>
                    <h4>Gana XP y Desbloquea Logros</h4>
                    <p>Regístrate para comenzar a ganar experiencia y desbloquear logros mientras aprendes.</p>
                    <div class="mt-4">
                        <a href="{{ route('signin') }}" class="btn btn-primary">Registrarme ahora</a>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary ms-2">Ya tengo cuenta</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endauth
