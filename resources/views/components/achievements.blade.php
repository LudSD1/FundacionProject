<!-- Floating XP Button -->
@auth
    <div class="floating-xp-button">
        <button type="button" class="btn btn-primary rounded-circle p-3 shadow-lg" data-bs-toggle="offcanvas"
            data-bs-target="#xpOffcanvas" aria-controls="xpOffcanvas">
            <i class="bi bi-trophy-fill"></i>
        </button>
    </div>

    <!-- XP Offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="xpOffcanvas">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Mi Nivel y Logros</h5>
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
            @endphp

            <!-- Level and XP -->
            <div class="card mb-3 bg-primary text-white achievement-item">
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

            <!-- Recent Achievements -->
            <h6 class="mb-3 achievement-item">Últimos Logros</h6>
            @php
                $unlockedAchievements = \App\Models\Achievement::whereHas('inscritos', function ($query) use (
                    $inscripciones,
                ) {
                    $query->whereIn('inscrito_id', $inscripciones->pluck('id'));
                })
                    ->latest()
                    ->take(3)
                    ->get();
            @endphp

            @forelse($unlockedAchievements as $achievement)
                <div class="d-flex align-items-center mb-2 p-2 bg-light rounded achievement-item">
                    <div class="me-3">
                        <span class="h5 mb-0">{{ $achievement->icon }}</span>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $achievement->title }}</h6>
                        <small class="text-success">+{{ $achievement->xp_reward }} XP</small>
                    </div>
                </div>
            @empty
                <p class="text-muted achievement-item">Aún no has desbloqueado ningún logro</p>
            @endforelse

            <div class="mt-3 achievement-item">
                <a href="{{ route('perfil.xp') }}" class="btn btn-primary w-100">Ver todos mis logros</a>
            </div>
        </div>
    </div>
@else
    <div class="floating-xp-button">
        <button type="button" class="btn btn-primary rounded-circle p-3 shadow-lg" data-bs-toggle="modal"
            data-bs-target="#registerModal">
            <i class="bi bi-trophy-fill"></i>
        </button>
    </div>

    <!-- Registration Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-hidden="true">
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
