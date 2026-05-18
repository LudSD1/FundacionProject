{{-- ============================================================
     MODAL CREAR MÚLTIPLES PREGUNTAS
     Recibe: $cuestionario
     ============================================================ --}}
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
