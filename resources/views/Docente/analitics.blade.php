@extends('FundacionPlantillaUsu.index')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12 mb-4">
            <h3 class="text-primary">
                <i class="bi bi-journal-text me-2"></i>Mis Cursos Asignados
            </h3>
            <p class="text-muted">Gestiona y visualiza los cursos donde eres docente</p>
        </div>
    </div>

    <div class="row g-4">
        @forelse ($cursos2 as $curso)
            @if (auth()->user()->id == $curso->docente_id)
                @php
                    $estadisticas = $curso->obtenerEstadisticasProgreso();
                @endphp
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card h-100 shadow-sm border-0 hover-zoom position-relative overflow-hidden">
                        <!-- Indicador de estado -->
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge bg-{{ $curso->estado == 'activo' ? 'success' : 'warning' }} rounded-pill">
                                <i class="bi bi-circle-fill me-1 small"></i>
                                {{ ucfirst($curso->estado ?? 'En proceso') }}
                            </span>
                        </div>

                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <!-- Icono del curso con fondo dinámico -->
                                <div class="course-icon me-3">
                                    <i class="bi bi-journal-text display-6 text-primary"></i>
                                </div>

                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-1 text-dark">{{ $curso->nombreCurso }}</h5>
                                    <p class="card-text text-muted mb-0">
                                        <i class="bi bi-person-video3 me-1"></i>
                                        Docente: {{ auth()->user()->name }}
                                    </p>
                                </div>
                            </div>

                            <!-- Estadísticas del curso -->
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <div class="p-2 rounded bg-light">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-people text-primary me-2"></i>
                                            <div>
                                                <span class="d-block">{{ $estadisticas['estudiantes_total'] }} Estudiantes</span>
                                                <small class="text-muted">
                                                    {{ $estadisticas['estudiantes_completados'] }} completados
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 rounded bg-light">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-calendar-event text-primary me-2"></i>
                                            <div>
                                                <span class="d-block">{{ $curso->fecha_fin ? \Carbon\Carbon::parse($curso->fecha_fin)->format('d/m/Y') : 'Sin fecha' }}</span>
                                                <small class="text-muted">Fecha límite</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Estado de los estudiantes -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-muted">Estado de estudiantes</small>
                                <div>
                                        <span class="badge bg-success me-1" title="Completados">
                                            {{ $estadisticas['estudiantes_completados'] }}
                                        </span>
                                        <span class="badge bg-primary me-1" title="En progreso">
                                            {{ $estadisticas['estudiantes_en_progreso'] }}
                                        </span>
                                        <span class="badge bg-secondary" title="Sin iniciar">
                                            {{ $estadisticas['estudiantes_sin_iniciar'] }}
                                        </span>
                                    </div>
                                </div>
                                </div>

                            <!-- Barra de progreso -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted">Progreso general del curso</small>
                                    <small class="text-primary">{{ $estadisticas['porcentaje_total'] }}%</small>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-primary" role="progressbar"
                                         style="width: {{ $estadisticas['porcentaje_total'] }}%"
                                         aria-valuenow="{{ $estadisticas['porcentaje_total'] }}"
                                         aria-valuemin="0"
                                         aria-valuemax="100">
                                    </div>
                                </div>
                            </div>

                            <!-- Botón de acción -->
                            <a href="{{ route('rfc', encrypt($curso->id)) }}" class="btn btn-primary w-100">
                                <i class="bi bi-eye me-2"></i>Ver Detalles
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        @empty
            <div class="col-12">
                <div class="alert alert-info d-flex align-items-center">
                    <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                    <div>
                        <h5 class="mb-1">No hay cursos asignados</h5>
                        <p class="mb-0">Actualmente no tienes ningún curso asignado como docente.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>

<style>
/* Estilos para las tarjetas */
.hover-zoom {
    transition: all 0.3s ease;
}

.hover-zoom:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}

.course-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: rgba(var(--bs-primary-rgb), 0.1);
}

/* Animación para los badges */
.badge {
    animation: fadeInRight 0.5s ease-out;
    cursor: help;
}

@keyframes fadeInRight {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Estilos para las estadísticas */
.bg-light {
    transition: background-color 0.3s ease;
}

.bg-light:hover {
    background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
}

/* Estilo para el mensaje de no cursos */
.alert-info {
    border-left: 4px solid var(--bs-primary);
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips para los badges
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush

@endsection

