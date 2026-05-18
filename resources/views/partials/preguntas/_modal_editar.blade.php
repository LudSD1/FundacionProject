{{-- ============================================================
     MODAL EDITAR PREGUNTA
     Recibe: $pregunta
     ============================================================ --}}
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
