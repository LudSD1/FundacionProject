@extends('layout')

@section('titulo', 'Gestión de Cuestionario')

@section('content')
    <div class="container-fluid py-4">
        {{-- Botón Volver --}}
        <div class="back-button-wrapper mb-4">
            <a href="{{ route('Curso', $cuestionario->actividad->subtema->tema->curso->codigoCurso) }}" class="btn-back-modern">
                <i class="bi bi-arrow-left-circle-fill"></i>
                <span>Volver al Curso</span>
            </a>
        </div>

        <div class="tbl-card">
            {{-- Hero Section --}}
            <div class="tbl-card-hero">
                <div class="tbl-card-hero-content">
                    <h1 class="tbl-card-hero-title text-white">
                        <i class="bi bi-patch-question-fill me-2"></i>Gestión de Cuestionario
                    </h1>
                    <p class="tbl-card-hero-subtitle text-white">
                        Configure las preguntas y respuestas para: <span class="fw-bold">{{ $cuestionario->actividad->titulo }}</span>
                    </p>
                </div>

                <div class="tbl-card-hero-actions">
                    <div class="d-flex gap-2">
                        <div class="ec-role-badge text-white">
                            <i class="bi bi-check-circle-fill me-1"></i> {{ $cuestionario->preguntas->count() }} Preguntas
                        </div>
                        <div class="ec-role-badge bg-info">
                            <i class="bi bi-star-fill me-1"></i> {{ $cuestionario->preguntas->sum('puntaje') }} Puntos Totales
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4">
                {{-- Navegación de las pestañas Modernizada --}}
                <ul class="nav nav-tabs border-0 mb-4 bg-light p-1 rounded-pill" id="preguntasRespuestasTabs" role="tablist" style="width: fit-content;">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-pill px-4 border-0" id="preguntas-tab" data-bs-toggle="tab" data-bs-target="#preguntas"
                            type="button" role="tab" aria-controls="preguntas" aria-selected="true">
                            <i class="bi bi-question-circle me-2"></i>Preguntas
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-4 border-0" id="respuestas-tab" data-bs-toggle="tab" data-bs-target="#respuestas"
                            type="button" role="tab" aria-controls="respuestas" aria-selected="false">
                            <i class="bi bi-reply-all me-2"></i>Respuestas
                        </button>
                    </li>
                </ul>

                {{-- Contenido de las pestañas --}}
                <div class="tab-content" id="preguntasRespuestasContent">
                    {{-- Pestaña de Preguntas --}}
                    <div class="tab-pane fade show active" id="preguntas" role="tabpanel" aria-labelledby="preguntas-tab">
                        <div class="table-container-modern shadow-none border-0 p-0">
                            @include('partials.preguntas', ['preguntas' => $cuestionario->preguntas])
                        </div>
                    </div>

                    {{-- Pestaña de Respuestas --}}
                    <div class="tab-pane fade" id="respuestas" role="tabpanel" aria-labelledby="respuestas-tab">
                        <div class="table-container-modern shadow-none border-0 p-0">
                            @include('partials.respuestas', ['preguntas' => $cuestionario->preguntas])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('modals')
        {{-- Modal Crear Respuesta (Modernizado) --}}
        <div class="modal fade" id="crearRespuestaModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
                    <form method="POST" action="">
                        @csrf
                        <div class="modal-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                            <h5 class="modal-title">
                                <i class="bi bi-plus-circle me-2"></i>Crear Respuesta
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <input type="hidden" name="pregunta_id" id="pregunta_id" value="">

                            <div class="form-group-modern mb-3">
                                <label class="form-label-modern">Texto de la Respuesta</label>
                                <input type="text" class="form-control-modern" name="respuesta"
                                    placeholder="Escribe la respuesta aquí..." required>
                            </div>

                            <div class="form-group-modern mb-3">
                                <label class="form-label-modern d-block">¿Es correcta?</label>
                                <div class="d-flex gap-3 mt-2">
                                    <div class="form-check form-check-inline custom-radio">
                                        <input class="form-check-input" type="radio" name="es_correcta" id="verdadero"
                                            value="1" required>
                                        <label class="form-check-label" for="verdadero">Verdadero</label>
                                    </div>
                                    <div class="form-check form-check-inline custom-radio">
                                        <input class="form-check-input" type="radio" name="es_correcta" id="falso"
                                            value="0">
                                        <label class="form-check-label" for="falso">Falso</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group-modern">
                                <label class="form-label-modern">Puntos (opcional)</label>
                                <input type="number" class="form-control-modern" name="puntos" min="0" placeholder="0">
                            </div>
                        </div>
                        <div class="modal-footer border-0 bg-light p-3" style="border-radius: 0 0 15px 15px;">
                            <button type="button" class="btn btn-secondary rounded-pill px-4"
                                data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">Guardar Respuesta</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endpush

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Confirmación de eliminación con SweetAlert2
            document.querySelectorAll('.form-eliminar').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "¡No podrás revertir esta acción!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) this.submit();
                    });
                });
            });

            // Confirmación de restauración
            document.querySelectorAll('.form-restaurar').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Restaurar pregunta?',
                        text: "¿Quieres habilitar esta pregunta nuevamente?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#10b981',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Sí, restaurar',
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) this.submit();
                    });
                });
            });

            // Animación de pestañas
            const tabButtons = document.querySelectorAll('button[data-bs-toggle="tab"]');
            tabButtons.forEach(button => {
                button.addEventListener('show.bs.tab', () => {
                    const target = document.querySelector(button.dataset.bsTarget);
                    target.style.opacity = '0';
                    target.style.transform = 'translateY(10px)';
                    setTimeout(() => {
                        target.style.transition = 'all 0.3s ease';
                        target.style.opacity = '1';
                        target.style.transform = 'translateY(0)';
                    }, 50);
                });
            });
        });
    </script>

    <style>
        .nav-tabs .nav-link {
            color: #64748b;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .nav-tabs .nav-link.active {
            background-color: #1a4789 !important;
            color: white !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .custom-radio .form-check-input:checked {
            background-color: #1a4789;
            border-color: #1a4789;
        }
    </style>
@endsection

