@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Sección de Nivel y XP -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2 class="mb-0">Nivel {{ $userLevel }}</h2>
                    <p class="text-muted">{{ $currentXP }} / {{ $nextLevelXP }} XP</p>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-success" role="progressbar"
                             style="width: {{ ($currentXP / $nextLevelXP) * 100 }}%">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <h3 class="mb-0">XP Total: {{ $totalXP }}</h3>
                    <p class="text-muted">Siguiente nivel en: {{ $nextLevelXP - $currentXP }} XP</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas Rápidas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $totalAchievements }}</h3>
                    <p class="text-muted">Logros Totales</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $unlockedAchievements }}</h3>
                    <p class="text-muted">Logros Desbloqueados</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $completionPercentage }}%</h3>
                    <p class="text-muted">Completado</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $userRank }}</h3>
                    <p class="text-muted">Ranking</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Logros -->
    <div class="row">
        @foreach($achievements as $achievement)
        <div class="col-md-6 mb-4">
            <div class="card h-100 {{ $achievement->isUnlocked ? 'border-success' : 'border-secondary' }}">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="achievement-icon me-3 {{ $achievement->isUnlocked ? '' : 'opacity-50' }}">
                            {{ $achievement->icon }}
                        </div>
                        <div>
                            <h4 class="mb-1">{{ $achievement->title }}</h4>
                            <p class="mb-2">{{ $achievement->description }}</p>
                            @if($achievement->isUnlocked)
                                <span class="badge bg-success">Desbloqueado - {{ $achievement->earned_at->format('d/m/Y') }}</span>
                            @else
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-info" role="progressbar"
                                         style="width: {{ ($achievement->current_progress / $achievement->requirement_value) * 100 }}%">
                                        {{ $achievement->current_progress }}/{{ $achievement->requirement_value }}
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="ms-auto text-end">
                            <span class="badge bg-primary">+{{ $achievement->xp_reward }} XP</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<style>
.achievement-icon {
    font-size: 2.5rem;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endsection
