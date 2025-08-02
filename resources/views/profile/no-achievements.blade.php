@extends('layout')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <!-- Icono de información -->
                    <div class="mb-4">
                        <i class="fas fa-info-circle text-primary" style="font-size: 4rem;"></i>
                    </div>

                    <!-- Mensaje principal -->
                    <h2 class="h3 mb-4">{{ $message }}</h2>

                    <!-- Sugerencia -->
                    <p class="text-muted mb-4">{{ $suggestion }}</p>

                    <!-- Botones de acción -->
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('lista.cursos.congresos') }}" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Ver cursos disponibles
                        </a>
                        <a href="{{ route('Inicio') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-home me-2"></i>Ir al inicio
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sección de ayuda -->
            <div class="card mt-4 border-0 shadow-sm">
                <div class="card-body">
                    <h3 class="h5 mb-3">¿Cómo funciona el sistema de logros?</h3>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="text-primary me-3">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <div>
                                    <h4 class="h6">Gana XP y Sube de Nivel</h4>
                                    <p class="small text-muted">Completa actividades en tus cursos para ganar experiencia y subir de nivel.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="text-primary me-3">
                                    <i class="fas fa-medal"></i>
                                </div>
                                <div>
                                    <h4 class="h6">Desbloquea Logros</h4>
                                    <p class="small text-muted">Consigue logros especiales participando activamente en la plataforma.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="text-primary me-3">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div>
                                    <h4 class="h6">Sigue tu Progreso</h4>
                                    <p class="small text-muted">Visualiza tu avance y compite con otros estudiantes en el ranking.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="text-primary me-3">
                                    <i class="fas fa-certificate"></i>
                                </div>
                                <div>
                                    <h4 class="h6">Obtén Recompensas</h4>
                                    <p class="small text-muted">Recibe insignias y certificados especiales por tus logros.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.card {
    transition: transform 0.2s;
}
.card:hover {
    transform: translateY(-5px);
}
</style>
@endpush
@endsection 