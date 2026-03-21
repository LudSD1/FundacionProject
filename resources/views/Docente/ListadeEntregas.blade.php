@extends('layout')

@section('titulo')
    Calificación de Actividad: {{ $actividad->titulo }}
@endsection
@section('content')
<div class="container-fluid py-4">
    <div class="back-button-wrapper mb-4">
        <a href="javascript:history.back()" class="btn-back-modern">
            <i class="bi bi-arrow-left-circle-fill"></i>
            <span>Volver a Actividades</span>
        </a>
    </div>

    <div class="tbl-card">
        {{-- Hero Section --}}
        <div class="tbl-card-hero">
            <div class="tbl-card-hero-content">
                <h1 class="tbl-card-hero-title text-white">
                    <i class="bi bi-journal-check me-2"></i>Calificación de Actividad
                </h1>
                <p class="tbl-card-hero-subtitle text-white">
                    {{$actividad->tipoActividad->nombre}}: <span class="fw-bold">{{ $actividad->titulo }}</span>
                </p>
            </div>

            <div class="tbl-card-hero-actions">
                {{-- Buscador --}}
                <div class="tbl-hero-search">
                    <i class="bi bi-search tbl-hero-search-icon"></i>
                    <input type="text" class="tbl-hero-search-input" id="searchInput"
                        placeholder="Buscar estudiante..." autocomplete="off">
                </div>

                <div class="d-flex gap-2 mt-2 mt-md-0">
                    <div class="ec-role-badge">
                        <i class="bi bi-award-fill me-1"></i> Puntaje Máx: {{ $actividad->puntos }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Bar --}}
        <div class="tbl-filter-bar bg-light border-bottom">
            <div class="tbl-filter-bar-left d-flex gap-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-people-fill text-primary"></i>
                    <span><strong>{{ $inscritos->count() }}</strong> Estudiantes inscritos</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-arrow-up-fill text-success"></i>
                    <span><strong>{{ $entregas->count() }}</strong> Entregas realizadas</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-clock-fill text-{{ $vencido ? 'danger' : 'warning' }}"></i>
                    <span>Estado: <strong>{{ $vencido ? 'Vencido' : 'En periodo de calificación' }}</strong></span>
                </div>
            </div>
        </div>

        <div class="table-container-modern">
            <form action="{{ route('calificarT', encrypt($actividad->id)) }}" method="POST" id="calificationForm">
                @csrf
                <table class="table-modern" id="dataTable">
                    <thead>
                        <tr>
                            <th style="width: 5%">#</th>
                            <th style="width: 30%">
                                <div class="th-content">
                                    <i class="bi bi-person-fill"></i><span>Estudiante</span>
                                </div>
                            </th>
                            <th style="width: 20%">
                                <div class="th-content">
                                    <i class="bi bi-123"></i><span>Calificación</span>
                                </div>
                            </th>
                            <th style="width: 20%">
                                <div class="th-content">
                                    <i class="bi bi-cloud-download-fill"></i><span>Entrega</span>
                                </div>
                            </th>
                            <th style="width: 25%" class="text-center">
                                <div class="th-content justify-content-center">
                                    <i class="bi bi-chat-left-dots-fill"></i><span>Retroalimentación</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inscritos as $index => $inscrito)
                            @php
                                $notaExistente = $nota->where('inscripcion_id', $inscrito->id)->where('actividad_id', $actividad->id)->first();
                                $entrega = $entregas->firstWhere('user_id', $inscrito->estudiante_id);
                            @endphp

                            <tr>
                                <td><span class="text-muted fw-bold">{{ $loop->iteration }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle-sm me-3 bg-primary text-white">
                                            {{ substr($inscrito->estudiantes->name, 0, 1) }}{{ substr($inscrito->estudiantes->lastname1, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $inscrito->estudiantes->name }} {{ $inscrito->estudiantes->lastname1 }}</div>
                                            <small class="text-muted">{{ $inscrito->estudiantes->lastname2 }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group-modern mb-0">
                                        <div class="input-group input-group-sm" style="max-width: 120px;">
                                            <input type="number" class="form-control-modern calification-input"
                                                name="entregas[{{$index}}][notaTarea]"
                                                min="0" max="{{$actividad->puntos}}"
                                                value="{{ $notaExistente->nota ?? 0 }}"
                                                {{ $vencido ? 'disabled' : 'required' }}
                                                style="padding: 0.4rem 0.8rem;">
                                            <span class="input-group-text bg-light border-0 text-muted" style="font-size: 0.75rem;">/{{$actividad->puntos}}</span>
                                        </div>
                                    </div>

                                    <input type="hidden" name="entregas[{{$index}}][id]" value="{{ $notaExistente->id ?? '' }}">
                                    <input type="hidden" name="entregas[{{$index}}][id_tarea]" value="{{ $actividad->id }}">
                                    <input type="hidden" name="entregas[{{$index}}][id_inscripcion]" value="{{ $inscrito->id }}">
                                </td>
                                <td>
                                    @if($entrega)
                                        <a href="{{ asset('storage/' . $entrega->archivo) }}"
                                           class="btn-download-modern" target="_blank"
                                           data-bs-toggle="tooltip" title="Ver archivo entregado">
                                            <i class="bi bi-file-earmark-text-fill me-1"></i> Ver Tarea
                                        </a>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 rounded-pill" style="font-size: 0.7rem;">
                                            <i class="bi bi-exclamation-circle me-1"></i> No entregado
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn-action-modern btn-info"
                                        data-bs-toggle="modal" data-bs-target="#feedbackModal{{$index}}"
                                        title="Agregar retroalimentación">
                                        <i class="bi bi-chat-dots-fill"></i>
                                    </button>

                                    @if($notaExistente && $notaExistente->retroalimentacion)
                                        <i class="bi bi-check-circle-fill text-success ms-1" title="Tiene retroalimentación"></i>
                                    @endif

                                    <!-- Feedback Modal -->

                                </td>
                            </tr>

                                                               <div class="modal fade" id="feedbackModal{{$index}}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
                                                <div class="modal-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                                                    <h5 class="modal-title">
                                                        <i class="bi bi-chat-quote me-2"></i>Retroalimentación
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold text-dark mb-2">Para: {{ $inscrito->estudiantes->name }} {{ $inscrito->estudiantes->lastname1 }}</label>
                                                        <textarea class="form-control feedback-textarea"
                                                            name="entregas[{{$index}}][retroalimentacion]"
                                                            rows="5" placeholder="Escribe aquí tus comentarios, observaciones o sugerencias..."
                                                            style="border-radius: 10px; border: 1px solid #e2e8f0;">{{ $notaExistente->retroalimentacion ?? '' }}</textarea>
                                                    </div>
                                                    @if($notaExistente && $notaExistente->retroalimentacion)
                                                        <div class="bg-light p-3 rounded-3 border-start border-primary border-4 mt-2">
                                                            <small class="text-muted d-block mb-1 fw-bold">Retroalimentación actual:</small>
                                                            <small class="text-dark italic">"{{ $notaExistente->retroalimentacion }}"</small>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer border-0 bg-light p-3" style="border-radius: 0 0 15px 15px;">
                                                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cerrar</button>
                                                    <button type="button" class="btn btn-primary rounded-pill px-4 save-feedback" data-bs-dismiss="modal">Guardar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                        @endforeach
                    </tbody>
                </table>

                @if(!$vencido)
                    <div class="tbl-pagination justify-content-center mt-4">
                        <button type="submit" class="tbl-hero-btn tbl-hero-btn-primary px-5 py-3" style="width: auto;">
                            <i class="bi bi-cloud-check-fill me-2"></i> Guardar todas las Calificaciones
                        </button>
                    </div>
                @else
                    <div class="alert alert-warning text-center mt-4 rounded-4 shadow-sm">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> El período de calificación ha finalizado y ya no es posible realizar cambios.
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

<style>
    .avatar-circle-sm {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .btn-download-modern {
        color: #39a6cb;
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 6px;
        background: rgba(57, 166, 203, 0.1);
        transition: all 0.2s;
    }
    .btn-download-modern:hover {
        background: rgba(57, 166, 203, 0.2);
        color: #1a4789;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Búsqueda en tiempo real mejorada
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('#dataTable tbody tr');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const value = this.value.toLowerCase().trim();
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(value) ? '' : 'none';
            });
        });
    }

    // Tooltips de Bootstrap 5
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Auto-guardado en localStorage
    function saveToLocalStorage() {
        document.querySelectorAll('.calification-input').forEach(input => {
            localStorage.setItem(input.getAttribute('name'), input.value);
        });

        document.querySelectorAll('.feedback-textarea').forEach(textarea => {
            const name = textarea.getAttribute('name');
            localStorage.setItem(name, textarea.value);
        });

        window.isFormModified = true;
    }

    // Cargar datos guardados
    function loadFromLocalStorage() {
        document.querySelectorAll('.calification-input').forEach(input => {
            const savedValue = localStorage.getItem(input.getAttribute('name'));
            if (savedValue !== null) {
                input.value = savedValue;
            }
        });

        document.querySelectorAll('.feedback-textarea').forEach(textarea => {
            const name = textarea.getAttribute('name');
            const savedValue = localStorage.getItem(name);
            if (savedValue !== null) {
                textarea.value = savedValue;
            }
        });
    }

    // Event listeners para cambios
    document.querySelectorAll('.calification-input, .feedback-textarea').forEach(el => {
        el.addEventListener('input', saveToLocalStorage);
    });

    document.querySelectorAll('.save-feedback').forEach(btn => {
        btn.addEventListener('click', saveToLocalStorage);
    });

    // Cargar datos al iniciar
    loadFromLocalStorage();

    // Confirmar antes de salir si hay cambios
    window.addEventListener('beforeunload', function(e) {
        if (window.isFormModified) {
            e.preventDefault();
            e.returnValue = 'Tiene cambios sin guardar. ¿Está seguro de que quiere salir?';
            return e.returnValue;
        }
    });

    // Limpiar localStorage al enviar el formulario con confirmación SweetAlert2
    const form = document.getElementById('calificationForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (e.isTrusted) { // Solo si no fue disparado manualmente por nosotros
                e.preventDefault();

                Swal.fire({
                    title: '¿Guardar calificaciones?',
                    text: "Se actualizarán las notas y retroalimentaciones de todos los estudiantes.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Sí, guardar todo',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Limpiar localStorage
                        document.querySelectorAll('.calification-input, .feedback-textarea').forEach(el => {
                            localStorage.removeItem(el.getAttribute('name'));
                        });
                        window.isFormModified = false;

                        Swal.fire({
                            title: 'Guardando...',
                            didOpen: () => Swal.showLoading(),
                            allowOutsideClick: false
                        });

                        form.submit();
                    }
                });
            }
        });
    }
});
</script>
@endsection
