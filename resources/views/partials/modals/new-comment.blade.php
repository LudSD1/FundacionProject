<div class="modal fade" id="commentModal" tabindex="-1"
    aria-labelledby="commentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0 bg-light">
                <h5 class="modal-title" id="commentModalLabel">
                    <i class="bi bi-chat-dots-fill text-primary me-2"></i>
                    Nueva Discusión
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form class="comment-form" method="POST" action="{{ route('foro.mensaje.store', $foro->id) }}">
                    @csrf
                    <input type="hidden" name="foro_id" value="{{ $foro->id }}">
                    <input type="hidden" name="estudiante_id" value="{{ auth()->user()->id }}">

                    <div class="mb-4">
                        <label for="tituloMensaje" class="form-label">Título de la discusión</label>
                        <input type="text"
                               class="form-control form-control-lg"
                               name="tituloMensaje"
                               placeholder="Escribe un título descriptivo..."
                               required>
                    </div>

                    <div class="mb-4">
                        <label for="mensaje" class="form-label">Contenido de la discusión</label>
                        <textarea class="form-control"
                                  name="mensaje"
                                  rows="6"
                                  placeholder="Desarrolla tu tema aquí..."
                                  required></textarea>
                        <small class="text-muted">
                            Sé claro y específico en tu mensaje para obtener mejores respuestas.
                        </small>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-send-fill me-2"></i>
                            Publicar Discusión
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
