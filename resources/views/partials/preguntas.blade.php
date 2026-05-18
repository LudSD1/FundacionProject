<div class="mb-4">
    {{-- Encabezado de sección --}}
    <div class="step-header mb-4">
        <h4 class="text-primary fw-bold mb-1"><i class="bi bi-list-task me-2"></i>Banco de Preguntas</h4>
        <p class="text-muted small">Visualiza y gestiona el contenido evaluativo.</p>
    </div>

    {{-- Barra de búsqueda y botón de agregar --}}
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

    {{-- Tabla de preguntas --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern mb-0" id="tablaPreguntas">
                    <thead>
                        <tr>
                            <th style="width: 5%">#</th>
                            <th style="width: 50%">Pregunta</th>
                            <th style="width: 20%">Tipo</th>
                            <th style="width: 10%">Puntos</th>
                            <th style="width: 15%" class="text-center">Acciones</th>
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
                                    <span class="badge bg-{{ $tipoColor }} bg-opacity-10 text-{{ $tipoColor }} border border-{{ $tipoColor }} border-opacity-25 px-3 py-2 rounded-pill fw-bold text-uppercase" style="font-size: 0.7rem;">
                                        <i class="bi {{ $tipoIcon }} me-1"></i>
                                        {{ ucfirst(str_replace('_', ' ', $pregunta->tipo)) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-bold">
                                        {{ $pregunta->puntaje }} pts
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @if ($pregunta->deleted_at)
                                            <form method="POST" action="{{ route('pregunta.restore', encrypt($pregunta->id)) }}"
                                                class="form-restaurar d-inline">
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
                                                class="form-eliminar d-inline">
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
                                    <div class="text-center py-5">
                                        <i class="bi bi-inbox display-4 text-muted mb-3"></i>
                                        <h5 class="text-muted mb-1">No hay preguntas registradas</h5>
                                        <p class="text-muted small mb-0">Comienza agregando preguntas al cuestionario.</p>
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

@push('modals')
    <div class="modal fade" id="crearMultiplesPreguntasModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <form method="POST" action="{{ route('pregunta.store', encrypt($cuestionario->id)) }}">
                    @csrf
                    <div class="modal-header bg-light border-bottom-0 p-4">
                        <h5 class="modal-title fw-bold text-primary">
                            <i class="bi bi-plus-circle-fill me-2"></i>Crear Múltiples Preguntas
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div id="preguntas-container">
                            <div class="mb-3 p-3 bg-light rounded-4 border">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label fw-bold text-muted small text-uppercase">Texto de la Pregunta</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i class="bi bi-chat-left-text text-primary"></i></span>
                                            <input type="text" class="form-control bg-white" name="preguntas[0][enunciado]"
                                                placeholder="Escribe la pregunta aquí..." required>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label fw-bold text-muted small text-uppercase">Tipo de Pregunta</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i class="bi bi-list-task text-primary"></i></span>
                                            <select class="form-select bg-white" name="preguntas[0][tipo]" required>
                                                <option value="opcion_multiple">Opción Múltiple</option>
                                                <option value="abierta">Respuesta Abierta</option>
                                                <option value="boolean">Verdadero/Falso</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold text-muted small text-uppercase">Puntos</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i class="bi bi-star text-primary"></i></span>
                                            <input type="number" class="form-control bg-white" name="preguntas[0][puntaje]"
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
                    <div class="modal-footer border-top-0 p-4 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="tbl-hero-btn tbl-hero-btn-primary px-4">
                            <i class="bi bi-check-lg me-2"></i>Guardar Preguntas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($preguntas as $pregunta)
        <div class="modal fade" id="editarPreguntaModal-{{ $pregunta->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <form method="POST" action="{{ route('pregunta.update', encrypt($pregunta->id)) }}">
                        @csrf
                        <div class="modal-header bg-light border-bottom-0 p-4">
                            <h5 class="modal-title fw-bold text-primary">
                                <i class="bi bi-pencil-square me-2"></i>Editar Pregunta
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted small text-uppercase">Texto de la Pregunta</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-chat-left-text text-primary"></i></span>
                                    <input type="text" name="enunciado" class="form-control bg-light"
                                        value="{{ $pregunta->enunciado }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted small text-uppercase">Tipo de Pregunta</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-list-task text-primary"></i></span>
                                    <select name="tipo" class="form-select bg-light" required>
                                        <option value="opcion_multiple"
                                            {{ $pregunta->tipo === 'opcion_multiple' ? 'selected' : '' }}>Opción Múltiple
                                        </option>
                                        <option value="abierta" {{ $pregunta->tipo === 'abierta' ? 'selected' : '' }}>Respuesta
                                            Abierta</option>
                                        <option value="boolean" {{ $pregunta->tipo === 'boolean' ? 'selected' : '' }}>
                                            Verdadero/Falso</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-2">
                                <label class="form-label fw-bold text-muted small text-uppercase">Puntos</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-star text-primary"></i></span>
                                    <input type="number" name="puntaje" class="form-control bg-light"
                                        value="{{ $pregunta->puntaje }}" min="1" required>
                                </div>
                            </div>
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
@endpush

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const buscador = document.getElementById('buscadorPreguntas');
        const tabla = document.getElementById('tablaPreguntas');
        if (buscador && tabla) {
            buscador.addEventListener('input', function() {
                const q = this.value.toLowerCase().trim();
                const filas = tabla.querySelectorAll('tbody tr:not(:has(.text-center))');
                filas.forEach(f => {
                    f.style.display = f.textContent.toLowerCase().includes(q) ? '' : 'none';
                });
            });
        }

        const container = document.getElementById('preguntas-container');
        const btnAdd = document.getElementById('addPreguntaButton');
        let index = 1;

        if (btnAdd && container) {
            btnAdd.addEventListener('click', function() {
                const html = `
                    <div class="mb-3 p-3 bg-light rounded-4 border position-relative">
                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-pregunta" style="font-size: 0.7rem;"></button>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-bold text-muted small text-uppercase">Texto de la Pregunta</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bi bi-chat-left-text text-primary"></i></span>
                                    <input type="text" class="form-control bg-white" name="preguntas[${index}][enunciado]"
                                        placeholder="Escribe la pregunta aquí..." required>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-bold text-muted small text-uppercase">Tipo de Pregunta</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bi bi-list-task text-primary"></i></span>
                                    <select class="form-select bg-white" name="preguntas[${index}][tipo]" required>
                                        <option value="opcion_multiple">Opción Múltiple</option>
                                        <option value="abierta">Respuesta Abierta</option>
                                        <option value="boolean">Verdadero/Falso</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted small text-uppercase">Puntos</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bi bi-star text-primary"></i></span>
                                    <input type="number" class="form-control bg-white" name="preguntas[${index}][puntaje]" min="1"
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
                    e.target.closest('.mb-3').remove();
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
</style>
