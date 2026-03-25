@extends('layout')

@section('titulo', 'Revisar Intento')

@section('content')
<div class="container my-4">
    <div class="tbl-card shadow-lg">

        <div class="tbl-card-hero">
            <div class="tbl-hero-left">
                <a href="javascript:history.back()"
                   class="tbl-hero-btn tbl-hero-btn-glass prt-back-btn mb-2">
                    <i class="bi bi-arrow-left-circle-fill"></i> Volver al Listado
                </a>
                <div class="tbl-hero-eyebrow">
                    <i class="bi bi-journal-check"></i> Revisión de Evaluación
                </div>
                <h2 class="tbl-hero-title">Revisar Intento de Estudiante</h2>
                <p class="tbl-hero-sub text-white-50">
                    Calificación y retroalimentación para: <strong>{{ $intento->cuestionario->actividad->titulo }}</strong>
                </p>
            </div>
            <div class="tbl-hero-controls text-end">
                <div class="ec-role-badge mb-2 d-inline-block">
                    <i class="bi bi-shield-check-fill me-1"></i> Docente
                </div>
                <div class="d-flex gap-2 justify-content-end">
                    <div class="badge bg-white text-primary border p-2 rounded-3 shadow-sm">
                        <i class="bi bi-person-fill me-1"></i> Estudiante: {{ $intento->inscrito->estudiantes->name }}
                    </div>
                    <div class="badge bg-white text-info border p-2 rounded-3 shadow-sm">
                        <i class="bi bi-star-fill me-1"></i> Nota: {{ $intento->nota }}
                    </div>
                </div>
            </div>
        </div>

        <div class="p-4 p-md-5">
            {{-- Tarjetas de Información Rápida --}}
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="st-card p-4 border-0 bg-light rounded-4 h-100">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary-subtle text-primary p-2 rounded-3 me-3">
                                <i class="bi bi-person-badge-fill fs-4"></i>
                            </div>
                            <h6 class="mb-0 fw-bold">Estudiante</h6>
                        </div>
                        <p class="mb-0 fw-bold text-dark">{{ $intento->inscrito->estudiantes->name }} {{ $intento->inscrito->estudiantes->lastname1 }} {{ $intento->inscrito->estudiantes->lastname2 }}</p>
                        <small class="text-muted">Participante del curso</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="st-card p-4 border-0 bg-light rounded-4 h-100">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-info-subtle text-info p-2 rounded-3 me-3">
                                <i class="bi bi-clipboard-data-fill fs-4"></i>
                            </div>
                            <h6 class="mb-0 fw-bold">Cuestionario</h6>
                        </div>
                        <p class="mb-0 fw-bold text-dark">{{ $intento->cuestionario->actividad->titulo }}</p>
                        <small class="text-muted">Actividad de evaluación</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="st-card p-4 border-0 bg-light rounded-4 h-100">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success-subtle text-success p-2 rounded-3 me-3">
                                <i class="bi bi-check2-square fs-4"></i>
                            </div>
                            <h6 class="mb-0 fw-bold">Calificación Actual</h6>
                        </div>
                        <h3 class="mb-0 fw-bold text-success">{{ $intento->nota }} <small class="fs-6 text-muted">puntos</small></h3>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                {{-- Columna Izquierda: Respuestas --}}
                <div class="col-lg-8">
                    <div class="mb-4">
                        <h5 class="text-primary fw-bold mb-1"><i class="bi bi-list-check me-2"></i>Desglose de Respuestas</h5>
                        <p class="text-muted small">Revisa cada respuesta enviada por el estudiante.</p>
                    </div>

                    {{-- Navegación de Pestañas Estilo Dashboard --}}
                    <div class="adm-tabs-header bg-light border rounded-top-4">
                        <ul class="nav adm-tabs-nav" id="respuestasTabs" role="tablist">
                            @foreach ($intento->respuestasEst as $index => $respuesta)
                                <li class="nav-item" role="presentation">
                                    <button class="adm-tab {{ $loop->first ? 'active' : '' }}" id="tab-{{ $respuesta->id }}" data-bs-toggle="tab"
                                        data-bs-target="#contenido-{{ $respuesta->id }}" type="button" role="tab"
                                        aria-controls="contenido-{{ $respuesta->id }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                        P{{ $loop->iteration }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="tab-content border border-top-0 rounded-bottom-4 bg-white" id="respuestasTabsContent">
                        @foreach ($intento->respuestasEst as $index => $respuesta)
                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }} p-4" id="contenido-{{ $respuesta->id }}"
                                role="tabpanel" aria-labelledby="tab-{{ $respuesta->id }}">

                                <div class="d-flex align-items-center mb-4">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                        {{ $loop->iteration }}
                                    </div>
                                    <h6 class="fw-bold mb-0 text-dark">{{ $respuesta->pregunta->enunciado }}</h6>
                                </div>

                                <div class="bg-light rounded-4 p-4 mb-4">
                                    <label class="form-label fw-bold text-muted small text-uppercase mb-2">Respuesta del Estudiante:</label>
                                    <div class="p-3 bg-white border rounded-3 text-dark fw-semibold shadow-sm">
                                        {{ $respuesta->respuesta ?: 'Sin respuesta' }}
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <span class="small fw-bold text-muted text-uppercase me-3">Resultado:</span>
                                    @if ($respuesta->es_correcta)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 fw-bold">
                                            <i class="bi bi-check-circle-fill me-1"></i> Respuesta Correcta
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-2 fw-bold">
                                            <i class="bi bi-x-circle-fill me-1"></i> Respuesta Incorrecta
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Columna Derecha: Actualizar Nota --}}
                <div class="col-lg-4">
                    <div class="st-card p-4 border rounded-4 shadow-sm bg-white sticky-top" style="top: 20px;">
                        <div class="mb-4">
                            <h5 class="text-primary fw-bold mb-1"><i class="bi bi-pencil-square me-2"></i>Calificar</h5>
                            <p class="text-muted small">Modifica la nota final si es necesario.</p>
                        </div>

                        <form id="notaForm" method="POST" action="{{ route('cuestionarios.actualizarNota', [encrypt($intento->cuestionario_id), encrypt($intento->id)]) }}">
                            @csrf
                            <div class="mb-4">
                                <label for="nota" class="form-label fw-bold text-muted small text-uppercase">Nueva Nota Final</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-star-fill text-warning"></i></span>
                                    <input type="number" class="form-control bg-light border-start-0 fw-bold" id="nota" name="nota" value="{{ $intento->nota }}" min="0" required>
                                </div>
                                <div class="form-text small">La nota se guardará inmediatamente en el sistema.</div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="tbl-hero-btn tbl-hero-btn-primary py-3">
                                    <i class="bi bi-save-fill me-2"></i> Guardar Calificación
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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
        padding: 1rem 1.5rem !important;
    }

    .form-control {
        border-radius: 12px; border: 1.5px solid #e2eaf4; padding: 0.75rem 1rem;
        transition: all 0.2s;
    }
    .form-control:focus {
        border-color: #145da0; box-shadow: 0 0 0 4px rgba(20, 93, 160, 0.1);
        background: #fff !important;
    }
    .input-group-text {
        border-radius: 12px 0 0 12px; border: 1.5px solid #e2eaf4;
    }
    .input-group .form-control { border-radius: 0 12px 12px 0; }

    .rounded-top-4 { border-top-left-radius: 1rem !important; border-top-right-radius: 1rem !important; }
    .rounded-bottom-4 { border-bottom-left-radius: 1rem !important; border-bottom-right-radius: 1rem !important; }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Alertas del servidor
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#145da0',
                timer: 3000
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}",
                confirmButtonColor: '#145da0'
            });
        @endif

        // Confirmación al guardar nota
        const notaForm = document.getElementById('notaForm');
        notaForm?.addEventListener('submit', function(e) {
            e.preventDefault();
            const nuevaNota = document.getElementById('nota').value;

            Swal.fire({
                title: '¿Actualizar Calificación?',
                text: `Se cambiará la nota del estudiante a ${nuevaNota} puntos.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#145da0',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Sí, actualizar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Procesando...',
                        didOpen: () => Swal.showLoading(),
                        allowOutsideClick: false
                    });
                    this.submit();
                }
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
