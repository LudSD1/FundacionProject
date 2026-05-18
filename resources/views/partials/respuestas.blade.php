
<div class="mb-4">
    {{-- Encabezado de sección --}}
    <div class="step-header mb-4">
        <h4 class="text-primary fw-bold mb-1"><i class="bi bi-reply-all-fill me-2"></i>Respuestas por Pregunta</h4>
        <p class="text-muted small">Gestiona las opciones de respuesta para cada pregunta.</p>
    </div>

    {{-- Barra de tabs unificada --}}
    <div class="adm-tabs-header bg-light border-bottom mb-4">
        <div class="wizard-steps-nav d-flex overflow-auto" id="respuestasTabsBar">
            @foreach ($preguntas as $pregunta)
                <button class="adm-tab {{ $loop->first ? 'active' : '' }}"
                        id="resp-tab-{{ $pregunta->id }}"
                        data-bs-toggle="pill"
                        data-bs-target="#resp-pane-{{ $pregunta->id }}"
                        type="button"
                        role="tab"
                        aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    <i class="bi bi-patch-question me-1"></i>P{{ $loop->iteration }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Paneles de contenido --}}
    <div class="tab-content">
        @foreach ($preguntas as $pregunta)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                 id="resp-pane-{{ $pregunta->id }}"
                 role="tabpanel">

                <div class="card border-0 shadow-sm">
                    {{-- Cabecera de la pregunta --}}
                    <div class="card-header bg-light border-bottom-0">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                            <div>
                                <h5 class="fw-bold text-dark mb-2">{{ $pregunta->enunciado }}</h5>
                                @php
                                    $tipoIcon = match ($pregunta->tipo) {
                                        'opcion_multiple' => 'bi-ui-checks',
                                        'abierta' => 'bi-textarea-t',
                                        'boolean' => 'bi-toggle-on',
                                        default => 'bi-question-circle',
                                    };
                                    $tipoColor = match ($pregunta->tipo) {
                                        'opcion_multiple' => 'primary',
                                        'abierta' => 'info',
                                        'boolean' => 'success',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $tipoColor }} bg-opacity-10 text-{{ $tipoColor }} border border-{{ $tipoColor }} border-opacity-25 px-3 py-2 rounded-pill fw-bold text-uppercase" style="font-size: 0.7rem;">
                                    <i class="bi {{ $tipoIcon }} me-1"></i>
                                    {{ ucfirst(str_replace('_', ' ', $pregunta->tipo)) }}
                                </span>
                            </div>

                            {{-- Botón de acción según tipo --}}
                            <div>
                                @if ($pregunta->tipo === 'opcion_multiple')
                                    <button class="tbl-hero-btn tbl-hero-btn-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#rp-modal-crear-{{ $pregunta->id }}">
                                        <i class="bi bi-plus-circle-fill me-1"></i> Crear Respuesta
                                    </button>
                                @elseif ($pregunta->tipo === 'abierta')
                                    <button class="tbl-hero-btn tbl-hero-btn-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#rp-modal-clave-{{ $pregunta->id }}">
                                        <i class="bi bi-key-fill me-1"></i> Respuesta Clave
                                    </button>
                                @elseif ($pregunta->tipo === 'boolean')
                                    <form method="POST"
                                          action="{{ route('respuestas.storeVerdaderoFalso', encrypt($pregunta->id)) }}"
                                          class="d-inline">
                                        @csrf
                                        <button type="submit" class="tbl-hero-btn tbl-hero-btn-primary">
                                            <i class="bi bi-toggle-on me-1"></i> Generar V/F
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Tabla de respuestas --}}
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-modern mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">#</th>
                                        <th>Respuesta</th>
                                        <th style="width: 160px; text-align: center;">Estado</th>
                                        <th style="width: 120px; text-align: center;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pregunta->respuestas as $respuesta)
                                        <tr>
                                            <td><span class="fw-bold text-muted">{{ $loop->iteration }}</span></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-2" style="width: 36px; height: 36px; background: rgba(20,93,160,.08); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="bi bi-chat-left-text text-primary"></i>
                                                    </div>
                                                    <span class="fw-semibold">{{ $respuesta->contenido }}</span>
                                                </div>
                                            </td>
                                            <td style="text-align: center;">
                                                @if ($respuesta->es_correcta)
                                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill fw-bold">
                                                        <i class="bi bi-check-circle-fill me-1"></i> Correcta
                                                    </span>
                                                @else
                                                    <span class="badge bg-light text-muted border px-3 py-2 rounded-pill fw-bold">
                                                        <i class="bi bi-x-circle-fill me-1"></i> Incorrecta
                                                    </span>
                                                @endif
                                            </td>
                                            <td style="text-align: center;">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <button class="btn-action-modern btn-info"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#rp-modal-edit-{{ $respuesta->id }}"
                                                            title="Editar">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    <form method="POST"
                                                          action="{{ route('respuestas.delete', encrypt($respuesta->id)) }}"
                                                          class="form-eliminar d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-action-modern btn-delete" title="Eliminar">
                                                            <i class="bi bi-trash-fill"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">
                                                <div class="text-center py-5">
                                                    <i class="bi bi-inbox display-4 text-muted mb-3"></i>
                                                    <h5 class="text-muted mb-1">Sin respuestas registradas</h5>
                                                    <p class="text-muted small mb-0">Comienza agregando opciones para esta pregunta.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        @endforeach
    </div>
</div>
@push('modals')
    {{-- Modales de editar respuesta --}}
    @foreach ($preguntas as $pregunta)
        @foreach ($pregunta->respuestas as $respuesta)
            <div class="modal fade" id="rp-modal-edit-{{ $respuesta->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <form method="POST" action="{{ route('respuestas.update', encrypt($respuesta->id)) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-header bg-light border-bottom-0 p-4">
                                <h5 class="modal-title fw-bold text-primary">
                                    <i class="bi bi-pencil-square me-2"></i>Editar Respuesta
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Contenido de la respuesta</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-chat-left-text text-primary"></i></span>
                                        <input type="text"
                                               name="contenido"
                                               class="form-control bg-light"
                                               value="{{ old('contenido', $respuesta->contenido) }}"
                                               @if ($pregunta->tipo === 'boolean') readonly @endif
                                               required>
                                    </div>
                                    @if ($pregunta->tipo === 'boolean')
                                        <div class="form-text text-muted small mt-2">
                                            <i class="bi bi-info-circle me-1"></i>Los enunciados Verdadero/Falso son fijos.
                                        </div>
                                    @endif
                                </div>

                                @if ($pregunta->tipo === 'opcion_multiple' || $pregunta->tipo === 'boolean')
                                    <div class="mb-4">
                                        <label class="form-label fw-bold text-muted small text-uppercase d-block">¿Es correcta?</label>
                                        <div class="d-flex gap-4 mt-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="radio" name="es_correcta" id="verdadero-edit-{{ $respuesta->id }}" value="1" {{ old('es_correcta', $respuesta->es_correcta) ? 'checked' : '' }} required>
                                                <label class="form-check-label fw-semibold text-success" for="verdadero-edit-{{ $respuesta->id }}">
                                                    <i class="bi bi-check-circle-fill me-1"></i> Correcta
                                                </label>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="radio" name="es_correcta" id="falso-edit-{{ $respuesta->id }}" value="0" {{ !old('es_correcta', $respuesta->es_correcta) ? 'checked' : '' }}>
                                                <label class="form-check-label fw-semibold text-danger" for="falso-edit-{{ $respuesta->id }}">
                                                    <i class="bi bi-x-circle-fill me-1"></i> Incorrecta
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @elseif ($pregunta->tipo === 'abierta')
                                    <input type="hidden" name="es_correcta" value="1">
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle-fill me-2"></i>
                                        Las respuestas clave de preguntas abiertas siempre se consideran correctas.
                                    </div>
                                @endif
                            </div>
                            <div class="modal-footer border-top-0 p-4 pt-0">
                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="tbl-hero-btn tbl-hero-btn-primary px-4">
                                    <i class="bi bi-check-lg me-2"></i>Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach

    {{-- Modales de crear respuesta múltiple --}}
    @foreach ($preguntas as $pregunta)
        @if ($pregunta->tipo === 'opcion_multiple')
            <div class="modal fade" id="rp-modal-crear-{{ $pregunta->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <form method="POST" action="{{ route('respuestas.storeMultiple', encrypt($pregunta->id)) }}">
                            @csrf
                            <div class="modal-header bg-light border-bottom-0 p-4">
                                <h5 class="modal-title fw-bold text-primary">
                                    <i class="bi bi-plus-circle-fill me-2"></i>Agregar Respuestas — Opción Múltiple
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div id="rp-container-{{ $pregunta->id }}">
                                    @for ($i = 0; $i < 2; $i++)
                                        <div class="mb-3 p-3 bg-light rounded-4 border">
                                            <div class="row g-3 align-items-end">
                                                <div class="col-md-8">
                                                    <label class="form-label fw-bold text-muted small text-uppercase">Respuesta {{ $i + 1 }}</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-white"><i class="bi bi-chat-left-text text-primary"></i></span>
                                                        <input type="text"
                                                               name="respuestas[{{ $i }}][contenido]"
                                                               class="form-control bg-white"
                                                               placeholder="Escriba la respuesta…"
                                                               required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label fw-bold text-muted small text-uppercase">¿Correcta?</label>
                                                    <select name="respuestas[{{ $i }}][es_correcta]" class="form-select bg-white" required>
                                                        <option value="">Seleccione…</option>
                                                        <option value="1">✓ Sí</option>
                                                        <option value="0">✗ No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                                <button type="button"
                                        class="btn btn-outline-primary rounded-pill w-100 py-2 border-dashed"
                                        id="rp-add-{{ $pregunta->id }}">
                                    <i class="bi bi-plus-circle me-2"></i>Agregar Más Respuestas
                                </button>
                            </div>
                            <div class="modal-footer border-top-0 p-4 pt-0">
                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="tbl-hero-btn tbl-hero-btn-primary px-4">
                                    <i class="bi bi-check-lg me-2"></i>Guardar Respuestas
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    {{-- Modales de respuesta clave abierta --}}
    @foreach ($preguntas as $pregunta)
        @if ($pregunta->tipo === 'abierta')
            <div class="modal fade" id="rp-modal-clave-{{ $pregunta->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <form method="POST" action="{{ route('respuestas.storeRespuestasClave', encrypt($pregunta->id)) }}">
                            @csrf
                            <div class="modal-header bg-light border-bottom-0 p-4">
                                <h5 class="modal-title fw-bold text-primary">
                                    <i class="bi bi-key-fill me-2"></i>Respuesta Clave — Pregunta Abierta
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div id="rp-container-clave-{{ $pregunta->id }}">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted small text-uppercase">Respuesta Clave 1</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="bi bi-chat-left-text text-primary"></i></span>
                                            <input type="text"
                                                   name="respuestas[0][contenido]"
                                                   class="form-control bg-light"
                                                   placeholder="Escriba una respuesta clave…"
                                                   required>
                                        </div>
                                        <input type="hidden" name="respuestas[0][es_correcta]" value="1">
                                        <div class="form-text text-muted small mt-2">
                                            <i class="bi bi-info-circle me-1"></i>Posible respuesta correcta aceptada.
                                        </div>
                                    </div>
                                </div>
                                <button type="button"
                                        class="btn btn-outline-primary rounded-pill w-100 py-2 border-dashed"
                                        id="rp-add-clave-{{ $pregunta->id }}">
                                    <i class="bi bi-plus-circle me-2"></i>Agregar Otra Clave
                                </button>
                            </div>
                            <div class="modal-footer border-top-0 p-4 pt-0">
                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="tbl-hero-btn tbl-hero-btn-primary px-4">
                                    <i class="bi bi-check-lg me-2"></i>Guardar Respuestas
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endpush

{{-- ============================================================
     JAVASCRIPT - MANTENIENDO LA FUNCIONALIDAD ORIGINAL
     ============================================================ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    /* ── Confirmación de eliminación ─────────────────── */
    document.querySelectorAll('.form-eliminar').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const self = this;
            Swal.fire({
                title: '¿Eliminar respuesta?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then(r => { if (r.isConfirmed) self.submit(); });
        });
    });

    /* ── Dinámica de respuestas opciones múltiples ─── */
    @foreach ($preguntas as $pregunta)
        @if ($pregunta->tipo === 'opcion_multiple')
        (function () {
            const container = document.getElementById('rp-container-{{ $pregunta->id }}');
            const addBtn    = document.getElementById('rp-add-{{ $pregunta->id }}');
            let   idx       = 2;

            if (!addBtn) return;

            addBtn.addEventListener('click', () => {
                const div = document.createElement('div');
                div.className = 'mb-3 p-3 bg-light rounded-4 border position-relative';
                div.innerHTML = `
                    <button type="button"
                            class="btn-close position-absolute top-0 end-0 m-2"
                            style="font-size: 0.7rem;"
                            onclick="this.closest('.mb-3').remove()">
                    </button>
                    <div class="row g-3 align-items-end">
                        <div class="col-md-8">
                            <label class="form-label fw-bold text-muted small text-uppercase">Respuesta ${idx + 1}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-chat-left-text text-primary"></i></span>
                                <input type="text"
                                       name="respuestas[${idx}][contenido]"
                                       class="form-control bg-white"
                                       placeholder="Escriba la respuesta…"
                                       required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small text-uppercase">¿Correcta?</label>
                            <select name="respuestas[${idx}][es_correcta]" class="form-select bg-white" required>
                                <option value="">Seleccione…</option>
                                <option value="1">✓ Sí</option>
                                <option value="0">✗ No</option>
                            </select>
                        </div>
                    </div>`;
                container.appendChild(div);
                idx++;
            });
        })();
        @endif

        @if ($pregunta->tipo === 'abierta')
        (function () {
            const container = document.getElementById('rp-container-clave-{{ $pregunta->id }}');
            const addBtn    = document.getElementById('rp-add-clave-{{ $pregunta->id }}');
            let   idx       = 1;

            if (!addBtn) return;

            addBtn.addEventListener('click', () => {
                const div = document.createElement('div');
                div.className = 'mb-3 position-relative';
                div.innerHTML = `
                    <button type="button"
                            class="btn-close position-absolute top-0 end-0"
                            style="font-size: 0.7rem;"
                            onclick="this.closest('.mb-3').remove()">
                    </button>
                    <label class="form-label fw-bold text-muted small text-uppercase">Respuesta Clave ${idx + 1}</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-chat-left-text text-primary"></i></span>
                        <input type="text"
                               name="respuestas[${idx}][contenido]"
                               class="form-control bg-light"
                               placeholder="Escriba una respuesta clave…"
                               required>
                    </div>
                    <input type="hidden" name="respuestas[${idx}][es_correcta]" value="1">`;
                container.appendChild(div);
                idx++;
            });
        })();
        @endif
    @endforeach
});
</script>
