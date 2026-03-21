<div class="d-flex justify-content-between align-items-center mb-4 p-2">
    <div class="tbl-hero-search" style="max-width: 300px;">
        <i class="bi bi-search tbl-hero-search-icon"></i>
        <input type="text" class="tbl-hero-search-input" id="buscadorPreguntas" placeholder="Buscar pregunta..."
            autocomplete="off">
    </div>
    <button class="tbl-hero-btn tbl-hero-btn-primary" style="width: auto;" data-bs-toggle="modal"
        data-bs-target="#crearMultiplesPreguntasModal">
        <i class="bi bi-plus-circle-fill"></i>
        <span>Crear Múltiples Preguntas</span>
    </button>
</div>

@push('modals')
    {{-- Modales Modernizados --}}
    <div class="modal fade" id="crearMultiplesPreguntasModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
                <form method="POST" action="{{ route('pregunta.store', encrypt($cuestionario->id)) }}">
                    @csrf
                    <div class="modal-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                        <h5 class="modal-title">
                            <i class="bi bi-plus-circle-fill me-2"></i>Crear Múltiples Preguntas
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div id="preguntas-container">
                            <div class="pregunta-item-modern mb-4 p-3 bg-light rounded-4 border">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">Texto de la Pregunta</label>
                                            <input type="text" class="form-control-modern" name="preguntas[0][enunciado]"
                                                placeholder="Escribe la pregunta aquí..." required>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">Tipo de Pregunta</label>
                                            <select class="form-select-modern" name="preguntas[0][tipo]" required>
                                                <option value="opcion_multiple">Opción Múltiple</option>
                                                <option value="abierta">Respuesta Abierta</option>
                                                <option value="boolean">Verdadero/Falso</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">Puntos</label>
                                            <input type="number" class="form-control-modern" name="preguntas[0][puntaje]"
                                                min="1" placeholder="5" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-primary rounded-pill w-100 py-2 border-dashed"
                            id="addPreguntaButton">
                            <i class="bi bi-plus-circle me-2"></i>Agregar Otra Pregunta
                        </button>
                    </div>
                    <div class="modal-footer border-0 bg-light p-3" style="border-radius: 0 0 15px 15px;">
                        <button type="button" class="btn btn-secondary rounded-pill px-4"
                            data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Guardar Preguntas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($preguntas as $pregunta)
        <div class="modal fade" id="editarPreguntaModal-{{ $pregunta->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
                    <form method="POST" action="{{ route('pregunta.update', encrypt($pregunta->id)) }}">
                        @csrf
                        <div class="modal-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                            <h5 class="modal-title">
                                <i class="bi bi-pencil-square me-2"></i>Editar Pregunta
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="form-group-modern mb-3">
                                <label class="form-label-modern">Texto de la Pregunta</label>
                                <input type="text" name="enunciado" class="form-control-modern"
                                    value="{{ $pregunta->enunciado }}" required>
                            </div>

                            <div class="form-group-modern mb-3">
                                <label class="form-label-modern">Tipo de Pregunta</label>
                                <select name="tipo" class="form-select-modern" required>
                                    <option value="opcion_multiple"
                                        {{ $pregunta->tipo === 'opcion_multiple' ? 'selected' : '' }}>Opción Múltiple
                                    </option>
                                    <option value="abierta" {{ $pregunta->tipo === 'abierta' ? 'selected' : '' }}>Respuesta
                                        Abierta</option>
                                    <option value="boolean" {{ $pregunta->tipo === 'boolean' ? 'selected' : '' }}>
                                        Verdadero/Falso</option>
                                </select>
                            </div>

                            <div class="form-group-modern">
                                <label class="form-label-modern">Puntos</label>
                                <input type="number" name="puntaje" class="form-control-modern"
                                    value="{{ $pregunta->puntaje }}" min="1" required>
                            </div>
                        </div>
                        <div class="modal-footer border-0 bg-light p-3" style="border-radius: 0 0 15px 15px;">
                            <button type="button" class="btn btn-secondary rounded-pill px-4"
                                data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endpush

<div class="table-container-modern shadow-none border-0">
    <table class="table-modern" id="tablaPreguntas">
        <thead>
            <tr>
                <th style="width: 5%">#</th>
                <th style="width: 50%">
                    <div class="th-content">
                        <i class="bi bi-chat-left-text-fill"></i><span>Pregunta</span>
                    </div>
                </th>
                <th style="width: 20%">
                    <div class="th-content">
                        <i class="bi bi-list-stars"></i><span>Tipo</span>
                    </div>
                </th>
                <th style="width: 10%">
                    <div class="th-content">
                        <i class="bi bi-award-fill"></i><span>Puntos</span>
                    </div>
                </th>
                <th style="width: 15%" class="text-center">
                    <div class="th-content justify-content-center">
                        <i class="bi bi-gear-fill"></i><span>Acciones</span>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse ($preguntas as $pregunta)
                <tr>
                    <td><span class="text-muted fw-bold">{{ $loop->iteration }}</span></td>
                    <td>
                        <div class="fw-bold text-dark">{{ $pregunta->enunciado }}</div>
                    </td>
                    <td>
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
                        <span class="status-badge status-{{ $tipoColor }}">
                            <i class="bi {{ $tipoIcon }}"></i>
                            {{ ucfirst(str_replace('_', ' ', $pregunta->tipo)) }}
                        </span>
                    </td>
                    <td>
                        <span
                            class="badge bg-light text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-bold">
                            {{ $pregunta->puntaje }} pts
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons-cell">
                            @if ($pregunta->deleted_at)
                                <form method="POST" action="{{ route('pregunta.restore', encrypt($pregunta->id)) }}"
                                    class="form-restaurar">
                                    @csrf
                                    <button type="submit" class="btn-action-modern btn-info" title="Restaurar">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </button>
                                </form>
                            @else
                                <button class="btn-action-modern btn-info" data-bs-toggle="modal"
                                    data-bs-target="#editarPreguntaModal-{{ $pregunta->id }}" title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <form method="POST" action="{{ route('pregunta.delete', encrypt($pregunta->id)) }}"
                                    class="form-eliminar">
                                    @csrf
                                    <button type="submit" class="btn-action-modern btn-delete" title="Eliminar">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state-table">
                            <i class="bi bi-patch-question-fill display-4 text-muted mb-3"></i>
                            <h5 class="text-muted">No hay preguntas registradas</h5>
                            <p class="text-muted small">Comienza agregando preguntas al cuestionario.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Buscador interno
        const buscador = document.getElementById('buscadorPreguntas');
        const tabla = document.getElementById('tablaPreguntas');
        if (buscador && tabla) {
            buscador.addEventListener('input', function() {
                const q = this.value.toLowerCase().trim();
                const filas = tabla.querySelectorAll('tbody tr:not(.empty-state-table tr)');
                filas.forEach(f => {
                    f.style.display = f.textContent.toLowerCase().includes(q) ? '' : 'none';
                });
            });
        }

        // Script para agregar preguntas dinámicas en el modal
        const container = document.getElementById('preguntas-container');
        const btnAdd = document.getElementById('addPreguntaButton');
        let index = 1;

        if (btnAdd && container) {
            btnAdd.addEventListener('click', function() {
                const html = `
                    <div class="pregunta-item-modern mb-4 p-3 bg-light rounded-4 border position-relative animate__animated animate__fadeIn">
                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-pregunta" style="font-size: 0.7rem;"></button>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">Texto de la Pregunta</label>
                                    <input type="text" class="form-control-modern" name="preguntas[${index}][enunciado]"
                                        placeholder="Escribe la pregunta aquí..." required>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">Tipo de Pregunta</label>
                                    <select class="form-select-modern" name="preguntas[${index}][tipo]" required>
                                        <option value="opcion_multiple">Opción Múltiple</option>
                                        <option value="abierta">Respuesta Abierta</option>
                                        <option value="boolean">Verdadero/Falso</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">Puntos</label>
                                    <input type="number" class="form-control-modern" name="preguntas[${index}][puntaje]" min="1"
                                        placeholder="5" required>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', html);
                index++;
            });

            container.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-pregunta')) {
                    e.target.closest('.pregunta-item-modern').remove();
                }
            });
        }
    });
</script>

<style>
    .border-dashed {
        border-style: dashed !important;
        border-width: 2px !important;
    }

    .pregunta-item-modern {
        transition: all 0.3s ease;
    }

    .pregunta-item-modern:hover {
        border-color: #1a4789 !important;
        background-color: #f1f5f9 !important;
    }
</style>
