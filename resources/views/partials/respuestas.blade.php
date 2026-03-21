<div class="mb-4">
    <h4 class="fw-bold text-dark border-bottom pb-2">
        <i class="bi bi-reply-all-fill me-2 text-primary"></i> Respuestas por Pregunta
    </h4>
</div>

<!-- Navegación de Pestañas Modernizada -->
<div class="bg-light p-2 rounded-4 mb-4">
    <ul class="nav nav-pills gap-2" id="preguntasTabs" role="tablist">
        @foreach ($preguntas as $index => $pregunta)
            <li class="nav-item" role="presentation">
                <button class=" nav-link rounded-pill px-4 {{ $loop->first ? 'active shadow-sm' : '' }}"
                    id="tab-{{ $pregunta->id }}" data-bs-toggle="pill"
                    data-bs-target="#contenido-{{ $pregunta->id }}" type="button" role="tab"
                    aria-controls="contenido-{{ $pregunta->id }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    <i class="bi bi-patch-question me-1 text-white" ></i> P{{ $loop->iteration }}
                </button>
            </li>
        @endforeach
    </ul>
</div>

<!-- Contenido de las Pestañas -->
<div class="tab-content" id="preguntasTabsContent">
    @foreach ($preguntas as $index => $pregunta)
        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="contenido-{{ $pregunta->id }}"
            role="tabpanel" aria-labelledby="tab-{{ $pregunta->id }}">

            <div class="bg-white p-4 rounded-4 border border-light shadow-sm mb-4">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">
                            {{ $pregunta->enunciado }}
                        </h5>
                        <span class="status-badge status-primary">
                            <i class="bi bi-info-circle me-1"></i>
                            {{ ucfirst(str_replace('_', ' ', $pregunta->tipo)) }}
                        </span>
                    </div>

                    <!-- Botón para Crear Respuesta -->
                    <div class="d-flex gap-2">
                        @if ($pregunta->tipo === 'opcion_multiple')
                            <button class="tbl-hero-btn tbl-hero-btn-primary" style="width: auto;" data-bs-toggle="modal"
                                data-bs-target="#crearRespuestaModal-{{ $pregunta->id }}">
                                <i class="bi bi-plus-circle-fill"></i>
                                <span>Crear Respuesta</span>
                            </button>
                        @elseif ($pregunta->tipo === 'abierta')
                            <button class="tbl-hero-btn tbl-hero-btn-primary" style="width: auto;" data-bs-toggle="modal"
                                data-bs-target="#crearRespuestaClaveModal-{{ $pregunta->id }}">
                                <i class="bi bi-key-fill"></i>
                                <span>Respuesta Clave</span>
                            </button>
                        @elseif ($pregunta->tipo === 'boolean')
                            <form method="POST" action="{{ route('respuestas.storeVerdaderoFalso', encrypt($pregunta->id)) }}">
                                @csrf
                                <button type="submit" class="tbl-hero-btn tbl-hero-btn-primary" style="width: auto;">
                                    <i class="bi bi-toggle-on"></i>
                                    <span>Generar V/F</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Tabla de Respuestas Modernizada -->
                <div class="table-container-modern shadow-none border-0 p-0">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th style="width: 10%">#</th>
                                <th style="width: 50%">
                                    <div class="th-content">
                                        <i class="bi bi-chat-left-text"></i><span>Respuesta</span>
                                    </div>
                                </th>
                                <th style="width: 20%">
                                    <div class="th-content">
                                        <i class="bi bi-check-circle"></i><span>¿Es Correcta?</span>
                                    </div>
                                </th>
                                <th style="width: 20%" class="text-center">
                                    <div class="th-content justify-content-center">
                                        <i class="bi bi-gear"></i><span>Acciones</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pregunta->respuestas as $respuesta)
                                <tr>
                                    <td><span class="text-muted fw-bold">{{ $loop->iteration }}</span></td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $respuesta->contenido }}</div>
                                    </td>
                                    <td>
                                        @if ($respuesta->es_correcta)
                                            <span class="status-badge status-success">
                                                <i class="bi bi-check-lg"></i> Correcta
                                            </span>
                                        @else
                                            <span class="status-badge status-secondary">
                                                <i class="bi bi-x-lg"></i> Incorrecta
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons-cell">
                                            <!-- Botón para Editar Respuesta -->
                                            <button class="btn-action-modern btn-info" data-bs-toggle="modal"
                                                data-bs-target="#editarRespuestaModal-{{ $respuesta->id }}"
                                                title="Editar">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            <!-- Botón para Eliminar Respuesta -->
                                            <form method="POST" action="{{ route('respuestas.delete', encrypt($respuesta->id)) }}"
                                                class="form-eliminar-resp">
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
                                        <div class="empty-state-table py-4">
                                            <i class="bi bi-inbox display-6 text-muted mb-2"></i>
                                            <p class="text-muted small">No hay respuestas registradas.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach
</div>

@push('modals')
    <!-- Modales para Editar Respuestas Modernizados -->
    @foreach ($preguntas as $pregunta)
        @foreach ($pregunta->respuestas as $respuesta)
            <div class="modal fade" id="editarRespuestaModal-{{ $respuesta->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
                        <form method="POST" action="{{ route('respuestas.update', encrypt($respuesta->id)) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                                <h5 class="modal-title">
                                    <i class="bi bi-pencil-square me-2"></i>Editar Respuesta
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body p-4">
                                <!-- Contenido de la Respuesta -->
                                <div class="form-group-modern mb-3">
                                    <label class="form-label-modern">Contenido de la Respuesta</label>
                                    <input type="text" name="contenido"
                                        class="form-control-modern" value="{{ old('contenido', $respuesta->contenido) }}"
                                        @if ($pregunta->tipo === 'boolean') readonly @endif required>
                                    @if ($pregunta->tipo === 'boolean')
                                        <small class="helper-text-modern text-info">
                                            <i class="bi bi-info-circle"></i> Los enunciados Verdadero/Falso son fijos.
                                        </small>
                                    @endif
                                </div>

                                <!-- ¿Es Correcta? -->
                                @if ($pregunta->tipo === 'opcion_multiple' || $pregunta->tipo === 'boolean')
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">¿Es Correcta?</label>
                                        <select name="es_correcta" class="form-select-modern" required>
                                            <option value="1" {{ old('es_correcta', $respuesta->es_correcta) ? 'selected' : '' }}>Sí, es correcta</option>
                                            <option value="0" {{ !old('es_correcta', $respuesta->es_correcta) ? 'selected' : '' }}>No, es incorrecta</option>
                                        </select>
                                    </div>
                                @elseif ($pregunta->tipo === 'abierta')
                                    <input type="hidden" name="es_correcta" value="1">
                                    <div class="alert alert-info border-0 rounded-4">
                                        <i class="bi bi-info-circle-fill me-2"></i>
                                        Las respuestas clave de preguntas abiertas siempre se consideran correctas.
                                    </div>
                                @endif
                            </div>
                            <div class="modal-footer border-0 bg-light p-3" style="border-radius: 0 0 15px 15px;">
                                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary rounded-pill px-4">Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach

    <!-- Modal para Crear Respuestas de Tipo Opción Múltiple -->
    @foreach ($preguntas as $pregunta)
        @if ($pregunta->tipo === 'opcion_multiple')
            <div class="modal fade" id="crearRespuestaModal-{{ $pregunta->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
                        <form method="POST" action="{{ route('respuestas.storeMultiple', encrypt($pregunta->id)) }}">
                            @csrf
                            <div class="modal-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                                <h5 class="modal-title">
                                    <i class="bi bi-plus-circle me-2"></i>Agregar Respuestas (Opción Múltiple)
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div id="respuestas-container-{{ $pregunta->id }}">
                                    @for ($i = 0; $i < 2; $i++)
                                        <div class="respuesta-item-modern mb-4 p-3 bg-light rounded-4 border">
                                            <div class="row g-3 align-items-end">
                                                <div class="col-md-8">
                                                    <div class="form-group-modern">
                                                        <label class="form-label-modern">Respuesta {{ $i + 1 }}</label>
                                                        <input type="text" name="respuestas[{{ $i }}][contenido]"
                                                            class="form-control-modern" placeholder="Escriba la respuesta..." required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group-modern">
                                                        <label class="form-label-modern">¿Es Correcta?</label>
                                                        <select name="respuestas[{{ $i }}][es_correcta]" class="form-select-modern" required>
                                                            <option value="">Seleccione...</option>
                                                            <option value="1">✓ Sí</option>
                                                            <option value="0">✗ No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                                <button type="button" class="btn btn-outline-primary rounded-pill w-100 py-2 border-dashed"
                                    id="addRespuestaButton-{{ $pregunta->id }}">
                                    <i class="bi bi-plus-circle me-2"></i>Agregar Más Respuestas
                                </button>
                            </div>
                            <div class="modal-footer border-0 bg-light p-3" style="border-radius: 0 0 15px 15px;">
                                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary rounded-pill px-4">Guardar Respuestas</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    <!-- Modal para Crear Respuestas de Tipo Abierta -->
    @foreach ($preguntas as $pregunta)
        @if ($pregunta->tipo === 'abierta')
            <div class="modal fade" id="crearRespuestaClaveModal-{{ $pregunta->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
                        <form method="POST" action="{{ route('respuestas.storeRespuestasClave', encrypt($pregunta->id)) }}">
                            @csrf
                            <div class="modal-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                                <h5 class="modal-title">
                                    <i class="bi bi-key-fill me-2"></i>Respuesta Clave (Abierta)
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div id="respuestas-clave-container-{{ $pregunta->id }}">
                                    <div class="form-group-modern mb-3">
                                        <label class="form-label-modern">Respuesta Clave 1</label>
                                        <input type="text" name="respuestas[0][contenido]"
                                            class="form-control-modern" placeholder="Escriba una respuesta clave..." required>
                                        <input type="hidden" name="respuestas[0][es_correcta]" value="1">
                                        <small class="helper-text-modern text-muted">
                                            <i class="bi bi-info-circle"></i> Posible respuesta correcta.
                                        </small>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-primary rounded-pill w-100 py-2 border-dashed"
                                    id="addRespuestaClaveButton-{{ $pregunta->id }}">
                                    <i class="bi bi-plus-circle me-2"></i>Agregar Otra Clave
                                </button>
                            </div>
                            <div class="modal-footer border-0 bg-light p-3" style="border-radius: 0 0 15px 15px;">
                                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary rounded-pill px-4">Guardar Respuestas</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endpush

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Confirmación de eliminación con SweetAlert2
        document.querySelectorAll('.form-eliminar-resp').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Eliminar respuesta?',
                    text: "Esta acción no se puede deshacer.",
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

        @foreach ($preguntas as $pregunta)
            @if ($pregunta->tipo === 'opcion_multiple')
                const container{{ $pregunta->id }} = document.getElementById('respuestas-container-{{ $pregunta->id }}');
                const addButton{{ $pregunta->id }} = document.getElementById('addRespuestaButton-{{ $pregunta->id }}');
                let respuestaIndex{{ $pregunta->id }} = 2;

                if (addButton{{ $pregunta->id }}) {
                    addButton{{ $pregunta->id }}.addEventListener('click', function () {
                        const html = `
                            <div class="respuesta-item-modern mb-4 p-3 bg-light rounded-4 border position-relative animate__animated animate__fadeIn">
                                <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-resp" style="font-size: 0.7rem;"></button>
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-8">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">Respuesta ${respuestaIndex{{ $pregunta->id }} + 1}</label>
                                            <input type="text" name="respuestas[${respuestaIndex{{ $pregunta->id }}}][contenido]"
                                                class="form-control-modern" placeholder="Escriba la respuesta..." required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">¿Es Correcta?</label>
                                            <select name="respuestas[${respuestaIndex{{ $pregunta->id }}}][es_correcta]" class="form-select-modern" required>
                                                <option value="">Seleccione...</option>
                                                <option value="1">✓ Sí</option>
                                                <option value="0">✗ No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        container{{ $pregunta->id }}.insertAdjacentHTML('beforeend', html);
                        respuestaIndex{{ $pregunta->id }}++;
                    });

                    container{{ $pregunta->id }}.addEventListener('click', function(e) {
                        if (e.target.classList.contains('remove-resp')) {
                            e.target.closest('.respuesta-item-modern').remove();
                        }
                    });
                }
            @endif

            @if ($pregunta->tipo === 'abierta')
                const containerClave{{ $pregunta->id }} = document.getElementById('respuestas-clave-container-{{ $pregunta->id }}');
                const addButtonClave{{ $pregunta->id }} = document.getElementById('addRespuestaClaveButton-{{ $pregunta->id }}');
                let respuestaClaveIndex{{ $pregunta->id }} = 1;

                if (addButtonClave{{ $pregunta->id }}) {
                    addButtonClave{{ $pregunta->id }}.addEventListener('click', function() {
                        const html = `
                            <div class="form-group-modern mb-3 position-relative animate__animated animate__fadeIn">
                                <button type="button" class="btn-close position-absolute top-0 end-0 remove-clave" style="font-size: 0.7rem;"></button>
                                <label class="form-label-modern">Respuesta Clave ${respuestaClaveIndex{{ $pregunta->id }} + 1}</label>
                                <input type="text" name="respuestas[${respuestaClaveIndex{{ $pregunta->id }}}][contenido]"
                                    class="form-control-modern" placeholder="Escriba una respuesta clave..." required>
                                <input type="hidden" name="respuestas[${respuestaClaveIndex{{ $pregunta->id }}}][es_correcta]" value="1">
                            </div>
                        `;
                        containerClave{{ $pregunta->id }}.insertAdjacentHTML('beforeend', html);
                        respuestaClaveIndex{{ $pregunta->id }}++;
                    });

                    containerClave{{ $pregunta->id }}.addEventListener('click', function(e) {
                        if (e.target.classList.contains('remove-clave')) {
                            e.target.closest('.form-group-modern').remove();
                        }
                    });
                }
            @endif
        @endforeach
    });
</script>

<style>
    .nav-pills .nav-link {
        color: #64748b;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }
    .nav-pills .nav-link.active {
        background-color: white !important;
        color: #1a4789 !important;
        border-color: #e2e8f0;
    }
    .border-dashed {
        border-style: dashed !important;
        border-width: 2px !important;
    }
    .respuesta-item-modern {
        transition: all 0.3s ease;
    }
    .respuesta-item-modern:hover {
        border-color: #1a4789 !important;
        background-color: #f8fafc !important;
    }
</style>

