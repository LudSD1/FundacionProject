{{-- ============================================================
     MODAL RESPUESTA CLAVE — PREGUNTA ABIERTA
     Recibe: $pregunta
     ============================================================ --}}
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
