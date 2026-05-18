@extends('layout')

@section('titulo', 'Gestión de Cuestionario')

@section('content')
<div class="container my-4">
    <div class="tbl-card shadow-lg">
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

        <div class="adm-tabs-header bg-light border-bottom p-0">
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

                <div class="tab-pane fade p-4 p-md-5" id="respuestas" role="tabpanel" aria-labelledby="respuestas-tab">
                    @include('partials.respuestas', ['preguntas' => $cuestionario->preguntas])
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.form-eliminar').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const self = this;
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
                }).then((result) => { if (result.isConfirmed) self.submit(); });
            });
        });

        document.querySelectorAll('.form-restaurar').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const self = this;
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
                }).then((result) => { if (result.isConfirmed) self.submit(); });
            });
        });

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

    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Éxito', text: "{{ session('success') }}" });
    @endif
    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Error', text: "{{ session('error') }}" });
    @endif
</script>
@endsection
