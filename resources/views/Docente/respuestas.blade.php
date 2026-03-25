@extends('layout')

@section('titulo', 'Gestión de Cuestionario')

@section('content')
<div class="container my-4">
    <div class="tbl-card shadow-lg">
        {{-- ╔══════════════════════════════════════╗
             ║  HERO — CABECERA AZUL               ║
             ╚══════════════════════════════════════╝ --}}
        <div class="tbl-card-hero">
            <div class="tbl-hero-left">
                <a href="{{ route('Curso', $cuestionario->actividad->subtema->tema->curso->codigoCurso) }}"
                   class="tbl-hero-btn tbl-hero-btn-glass prt-back-btn mb-2">
                    <i class="bi bi-arrow-left-circle-fill"></i> Volver al Curso
                </a>
                <div class="tbl-hero-eyebrow">
                    <i class="bi bi-patch-question-fill"></i> Evaluación Académica
                </div>
                <h2 class="tbl-hero-title">Gestión de Cuestionario</h2>
                <p class="tbl-hero-sub text-white-50">
                    Configurando: <strong>{{ $cuestionario->actividad->titulo }}</strong>
                </p>
            </div>
            <div class="tbl-hero-controls text-end">
                <div class="ec-role-badge mb-2 d-inline-block">
                    <i class="bi bi-shield-check-fill me-1"></i> Docente
                </div>
                <div class="d-flex gap-2 justify-content-end">
                    <div class="badge bg-white text-primary border p-2 rounded-3 shadow-sm">
                        <i class="bi bi-question-circle-fill me-1"></i> {{ $cuestionario->preguntas->count() }} Preguntas
                    </div>
                    <div class="badge bg-white text-info border p-2 rounded-3 shadow-sm">
                        <i class="bi bi-star-fill me-1"></i> {{ $cuestionario->preguntas->sum('puntaje') }} Puntos
                    </div>
                </div>
            </div>
        </div>

        {{-- Pestañas de Navegación Modernizadas --}}
        <div class="adm-tabs-header bg-light border-bottom">
            <ul class="nav adm-tabs-nav" id="preguntasRespuestasTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="adm-tab active" id="preguntas-tab" data-bs-toggle="tab" data-bs-target="#preguntas"
                        type="button" role="tab" aria-controls="preguntas" aria-selected="true">
                        <i class="bi bi-list-task me-2"></i>Preguntas
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="adm-tab" id="respuestas-tab" data-bs-toggle="tab" data-bs-target="#respuestas"
                        type="button" role="tab" aria-controls="respuestas" aria-selected="false">
                        <i class="bi bi-reply-all-fill me-2"></i>Respuestas
                    </button>
                </li>
            </ul>
        </div>

        <div class="p-0">
            <div class="tab-content" id="preguntasRespuestasContent">
                {{-- Pestaña de Preguntas --}}
                <div class="tab-pane fade show active p-4 p-md-5" id="preguntas" role="tabpanel" aria-labelledby="preguntas-tab">
                    <div class="mb-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="text-primary fw-bold mb-1"><i class="bi bi-info-circle-fill me-2"></i>Banco de Preguntas</h5>
                            <p class="text-muted small">Visualiza y gestiona el contenido evaluativo.</p>
                        </div>
                    </div>
                    <div class="table-responsive">
                        @include('partials.preguntas', ['preguntas' => $cuestionario->preguntas])
                    </div>
                </div>

                {{-- Pestaña de Respuestas --}}
                <div class="tab-pane fade p-4 p-md-5" id="respuestas" role="tabpanel" aria-labelledby="respuestas-tab">
                    <div class="mb-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="text-primary fw-bold mb-1"><i class="bi bi-check2-all me-2"></i>Opciones de Respuesta</h5>
                            <p class="text-muted small">Define las alternativas correctas e incorrectas.</p>
                        </div>
                    </div>
                    <div class="table-responsive">
                        @include('partials.respuestas', ['preguntas' => $cuestionario->preguntas])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Crear Respuesta Modernizado (Fuera del container para evitar conflictos) --}}
<div class="modal fade" id="crearRespuestaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form method="POST" action="">
                @csrf
                <div class="modal-header bg-light border-bottom-0 p-4">
                    <h5 class="modal-title fw-bold text-primary">
                        <i class="bi bi-plus-circle-fill me-2"></i>Nueva Respuesta
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="pregunta_id" id="pregunta_id" value="">

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">Texto de la Respuesta</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-chat-left-text text-primary"></i></span>
                            <input type="text" class="form-control bg-light" name="respuesta"
                                placeholder="Escribe la respuesta aquí..." required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase d-block">¿Es la opción correcta?</label>
                        <div class="d-flex gap-4 mt-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="radio" name="es_correcta" id="verdadero" value="1" required>
                                <label class="form-check-label fw-semibold text-success" for="verdadero">
                                    <i class="bi bi-check-circle-fill me-1"></i> Correcta
                                </label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="radio" name="es_correcta" id="falso" value="0">
                                <label class="form-check-label fw-semibold text-danger" for="falso">
                                    <i class="bi bi-x-circle-fill me-1"></i> Incorrecta
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold text-muted small text-uppercase">Puntaje (opcional)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-star text-primary"></i></span>
                            <input type="number" class="form-control bg-light" name="puntos" min="0" placeholder="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="tbl-hero-btn tbl-hero-btn-primary px-4">
                        <i class="bi bi-save-fill me-2"></i>Guardar Opción
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .ec-role-badge {
        background: rgba(255,165,0,0.15); color: #ffa500;
        padding: 0.25rem 0.75rem; border-radius: 50px; font-size: 0.7rem; font-weight: 800;
        border: 1px solid rgba(255,165,0,0.3);
    }

    .adm-tab {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }

    .form-control, .form-select {
        border-radius: 12px; border: 1.5px solid #e2eaf4; padding: 0.75rem 1rem;
        transition: all 0.2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #145da0; box-shadow: 0 0 0 4px rgba(20, 93, 160, 0.1);
        background: #fff !important;
    }
    .input-group-text {
        border-radius: 12px 0 0 12px; border: 1.5px solid #e2eaf4; border-right: none;
    }
    .input-group .form-control { border-radius: 0 12px 12px 0; }

    .table-modern thead th {
        background-color: #f8fafc !important;
        color: #64748b !important;
        font-size: 0.75rem !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        padding: 1rem 0.75rem !important;
        border-bottom: 2px solid #e2eaf4 !important;
    }
</style>

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
                    confirmButtonColor: '#145da0',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Sí, restaurar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) this.submit();
                });
            });
        });

        // Animación suave de pestañas
        const tabButtons = document.querySelectorAll('.adm-tab');
        tabButtons.forEach(button => {
            button.addEventListener('show.bs.tab', () => {
                const target = document.querySelector(button.dataset.bsTarget);
                target.style.opacity = '0';
                target.style.transform = 'translateY(10px)';
                setTimeout(() => {
                    target.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                    target.style.opacity = '1';
                    target.style.transform = 'translateY(0)';
                }, 50);
            });
        });
    });
</script>
@endsection

