@extends('layoutlanding')

@section('hero')
<div class="container py-5">
    <h1 class="text-center mb-5">Mejores Cursos por Categoría</h1>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5>Total Categorías</h5>
                    <h2>{{ $stats['total_categorias'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5>Total Cursos</h5>
                    <h2>{{ $stats['total_cursos'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5>Calificación Promedio</h5>
                    <h2>{{ number_format($stats['promedio_general'], 1) }}</h2>
                </div>
            </div>
        </div>
    </div>

    @foreach($categorias as $categoria)
    <div class="card mb-5">
        <div class="card-header bg-primary text-white">
            <h3>{{ $categoria->name }}</h3>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($categoria->cursos as $curso)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        @if($curso->imagen)
                        <img src="{{ asset($curso->imagen) }}" class="card-img-top" alt="{{ $curso->nombreCurso }}">
                        @else
                        <div class="bg-light text-center py-5">Sin imagen</div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $curso->nombreCurso }}</h5>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <span class="badge bg-primary">{{ $curso->tipo }}</span>
                                    <span class="badge bg-secondary">{{ $curso->formato }}</span>
                                </div>
                                <div>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <strong>{{ number_format($curso->calificaciones_avg_puntuacion, 1) }}</strong>
                                    <small>({{ $curso->calificaciones_count }})</small>
                                </div>
                            </div>
                            <p class="card-text">{{ Str::limit($curso->descripcionC, 100) }}</p>
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <span class="fw-bold">{{ $curso->precio > 0 ? '$'.number_format($curso->precio, 0) : 'Gratis' }}</span>
                            <a href="{{ route('evento.detalle', $curso->id) }}" class="btn btn-sm btn-outline-primary">Ver detalles</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endforeach

    <!-- Botón flotante para iniciar el tour -->
    <button class="guide-btn" id="startTour">
        <i class="bi bi-question-circle"></i> Tour de la página
    </button>
</div>

<!-- Script para el tour -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Verificar si Driver.js está disponible
        if (typeof Driver !== 'undefined') {
            // Inicializar el driver
            const driver = new Driver({
                animate: true,
                opacity: 0.7,
                padding: 10,
                showButtons: ['close', 'next', 'previous'],
                showProgress: true,
                stagePadding: 10
            });

            // Definir los pasos del tour
            const steps = [
                {
                    element: 'h1',
                    popover: {
                        title: 'Mejores Cursos por Categoría',
                        description: 'Esta página muestra los cursos mejor calificados organizados por categoría.',
                        position: 'bottom'
                    }
                },
                {
                    element: '.card.bg-light:nth-child(1)',
                    popover: {
                        title: 'Estadísticas',
                        description: 'Aquí puedes ver el número total de categorías disponibles.',
                        position: 'bottom'
                    }
                },
                {
                    element: '.card.bg-light:nth-child(2)',
                    popover: {
                        title: 'Total de Cursos',
                        description: 'Este contador muestra el número total de cursos en todas las categorías.',
                        position: 'bottom'
                    }
                },
                {
                    element: '.card.bg-light:nth-child(3)',
                    popover: {
                        title: 'Calificación Promedio',
                        description: 'Esta es la calificación promedio de todos los cursos en la plataforma.',
                        position: 'bottom'
                    }
                },
                {
                    element: '.card.mb-5:first-of-type',
                    popover: {
                        title: 'Categoría',
                        description: 'Cada sección muestra una categoría con sus mejores cursos.',
                        position: 'top'
                    }
                },
                {
                    element: '.card.h-100:first-of-type',
                    popover: {
                        title: 'Tarjeta de Curso',
                        description: 'Cada tarjeta muestra información detallada de un curso, incluyendo su imagen, nombre, tipo, formato, calificación y precio.',
                        position: 'right'
                    }
                },
                {
                    element: '.btn.btn-sm.btn-outline-primary:first-of-type',
                    popover: {
                        title: 'Ver Detalles',
                        description: 'Haz clic aquí para ver más información sobre el curso y poder inscribirte.',
                        position: 'left'
                    }
                }
            ];

            // Definir el tour
            driver.defineSteps(steps);

            // Agregar evento al botón para iniciar el tour
            const startTourBtn = document.getElementById('startTour');
            if (startTourBtn) {
                startTourBtn.addEventListener('click', function() {
                    driver.start();
                });
            }
        } else {
            console.error('Driver.js no está cargado correctamente');
        }
    });
</script>
@endsection
