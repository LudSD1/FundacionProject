{{-- ============================================================
     MODAL EDITAR RESPUESTA
     Recibe: $pregunta, $respuesta
     ============================================================ --}}
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
                                    <input class="form-check-input" type="radio" name="es_correcta"
                                           id="verdadero-edit-{{ $respuesta->id }}" value="1"
                                           {{ old('es_correcta', $respuesta->es_correcta) ? 'checked' : '' }} required>
                                    <label class="form-check-label fw-semibold text-success" for="verdadero-edit-{{ $respuesta->id }}">
                                        <i class="bi bi-check-circle-fill me-1"></i> Correcta
                                    </label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="radio" name="es_correcta"
                                           id="falso-edit-{{ $respuesta->id }}" value="0"
                                           {{ !old('es_correcta', $respuesta->es_correcta) ? 'checked' : '' }}>
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
