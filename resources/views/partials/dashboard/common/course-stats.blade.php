@php
    $estadisticas = isset($curso) ? $curso->obtenerEstadisticasProgreso() : null;
    $showProgress = isset($inscrito) && $inscrito->progreso;
@endphp

<div class="course-stats">
    @if($showProgress)
        {{-- Progreso Individual --}}
        <div class="progress-stats mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="small text-muted">Progreso del curso</span>
                <span class="small fw-bold text-primary">{{ $inscrito->progreso }}%</span>
            </div>
            <div class="progress">
                <div class="progress-bar" role="progressbar"
                     style="width: {{ $inscrito->progreso }}%"
                     aria-valuenow="{{ $inscrito->progreso }}"
                     aria-valuemin="0"
                     aria-valuemax="100">
                </div>
            </div>
        </div>
    @endif

    @if($estadisticas)
        {{-- Estadísticas Generales --}}
        <div class="general-stats">
            <div class="row g-2">
                <div class="col-6">
                    <div class="stat-card">
                        <i class="bi bi-people"></i>
                        <div class="stat-info">
                            <span class="stat-value">{{ $estadisticas['estudiantes_total'] }}</span>
                            <span class="stat-label">Estudiantes</span>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="stat-card">
                        <i class="bi bi-check-circle"></i>
                        <div class="stat-info">
                            <span class="stat-value">{{ $estadisticas['estudiantes_completados'] }}</span>
                            <span class="stat-label">Completados</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.course-stats {
    padding: 1rem 0;
}

.progress {
    height: 6px;
    border-radius: 10px;
    background-color: rgba(26, 71, 137, 0.1);
}

.progress-bar {
    background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
    transition: width 0.6s ease;
}

.stat-card {
    background: rgba(26, 71, 137, 0.05);
    border-radius: 8px;
    padding: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.3s ease;
}

.stat-card:hover {
    background: rgba(26, 71, 137, 0.1);
    transform: translateY(-2px);
}

.stat-card i {
    font-size: 1.5rem;
    color: var(--primary-color);
}

.stat-info {
    display: flex;
    flex-direction: column;
}

.stat-value {
    font-weight: 600;
    color: var(--primary-color);
    font-size: 1.1rem;
}

.stat-label {
    font-size: 0.8rem;
    color: #6c757d;
}
</style>
