{{-- ============================================================
     MODAL CREAR RESPUESTAS — OPCIÓN MÚLTIPLE
     Recibe: $pregunta
     ============================================================ --}}
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
