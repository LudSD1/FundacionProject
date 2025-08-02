@extends('layout')

@section('titulo', 'Tu nivel y logros')

@section('content')
<div class="container py-4">
    <!-- Sección de Nivel y XP -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center mb-3">
                <div class="col">
                    <h2 class="h4 mb-1">Nivel {{ $currentLevel->level_number }}</h2>
                    <p class="text-muted mb-0">{{ $currentLevel->title }}</p>
                </div>
                <div class="col text-end">
                    <h3 class="text-primary mb-1">{{ number_format($totalXP) }} XP</h3>
                    <small class="text-muted">Total acumulado</small>
                </div>
            </div>

            <!-- Barra de progreso -->
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-primary">Progreso al siguiente nivel</span>
                    <small class="text-primary">{{ number_format($progressToNext, 1) }}%</small>
                </div>
                <div class="progress" style="height: 10px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                         role="progressbar"
                         style="width: {{ $progressToNext }}%"
                         aria-valuenow="{{ $progressToNext }}"
                         aria-valuemin="0"
                         aria-valuemax="100">
                    </div>
                </div>
                <small class="text-muted mt-2 d-block">
                    {{ number_format($nextLevel ? $nextLevel->required_xp - $totalXP : 0) }} XP para alcanzar el nivel {{ $nextLevel ? $nextLevel->level_number : $currentLevel->level_number }}
                </small>
            </div>
        </div>
    </div>

    <ul class="nav nav-tabs mb-4" id="achievementTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="unlocked-tab" data-bs-toggle="tab" data-bs-target="#unlocked" type="button" role="tab">
                Logros Desbloqueados
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="available-tab" data-bs-toggle="tab" data-bs-target="#available" type="button" role="tab">
                Próximos Logros
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">
                Historial de XP
            </button>
        </li>
    </ul>

    <div class="tab-content" id="achievementTabsContent">
        <!-- Logros Desbloqueados -->
        <div class="tab-pane fade show active" id="unlocked" role="tabpanel">
            <div class="row g-3">
                @foreach($unlockedAchievements as $achievement)
                <div class="col-12">
                    <div class="d-flex align-items-center p-3 bg-success bg-opacity-10 rounded">
                        <div class="me-3 bg-success bg-opacity-25 rounded-circle p-3">
                            <span class="h4 mb-0">{{ $achievement->icon }}</span>
                        </div>
                        <div>
                            <h4 class="h6 text mb-1">{{ $achievement->title }}</h4>
                            <small class="text">{{ $achievement->xp_reward }} XP</small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Próximos Logros -->
        <div class="tab-pane fade" id="available" role="tabpanel">
            <div class="row g-3">
                @foreach($availableAchievements as $achievement)
                <div class="col-12">
                    <div class="d-flex align-items-center p-3 bg-light rounded">
                        <div class="me-3 bg-secondary bg-opacity-10 rounded-circle p-3">
                            <span class="h4 mb-0 text-muted">{{ $achievement->icon }}</span>
                        </div>
                        <div>
                            <h4 class="h6 mb-1">{{ $achievement->title }}</h4>
                            <small class="text-muted">{{ $achievement->description }}</small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Historial de XP -->
        <div class="tab-pane fade" id="history" role="tabpanel">
            <div class="table-responsive mt-3">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Curso</th>
                            <th>XP</th>
                            <th>Origen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($xpHistory as $event)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($event->created_at)->format('d/m/Y H:i') }}</td>
                            <td>{{ $inscripciones->firstWhere('cursos_id', $event->curso_id)->cursos->nombreCurso }}</td>
                            <td class="text-success">+{{ $event->xp }}</td>
                            <td>{{ $event->origen_type }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@if(session('achievement_unlocked'))
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
        <div class="toast-header bg-success text">
            <strong class="me-auto">¡Nuevo logro desbloqueado!</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <div class="d-flex align-items-center">
                <span class="h4 mb-0 me-2">🏆</span>
                <div>{{ session('achievement_unlocked')->title }}</div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection