<div class="modal fade" id="replyModal-{{ $mensaje->id }}" tabindex="-1"
    aria-labelledby="replyModalLabel-{{ $mensaje->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0 bg-light">
                <h5 class="modal-title" id="replyModalLabel-{{ $mensaje->id }}">
                    <i class="bi bi-reply-fill text-primary me-2"></i>
                    Responder a {{ $mensaje->tituloMensaje }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4 p-3 bg-light rounded">
                    <small class="text-muted d-block mb-2">Respondiendo a:</small>
                    <p class="mb-0">{{ $mensaje->mensaje }}</p>
                </div>

                <form action="{{ route('foro.mensaje.store', $foro->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="foro_id" value="{{ $foro->id }}">
                    <input type="hidden" name="estudiante_id" value="{{ auth()->id() }}">
                    <input type="hidden" name="respuesta_a" value="{{ $mensaje->id }}">

                    <div class="mb-4">
                        <label for="tituloMensaje" class="form-label">Título de la respuesta</label>
                        <input type="text"
                               class="form-control form-control-lg"
                               name="tituloMensaje"
                               placeholder="Re: {{ $mensaje->tituloMensaje }}"
                               required>
                    </div>

                    <div class="mb-4">
                        <label for="mensaje" class="form-label">Tu respuesta</label>
                        <textarea class="form-control"
                                  name="mensaje"
                                  rows="6"
                                  placeholder="Escribe tu respuesta aquí..."
                                  required></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-send-fill me-2"></i>
                            Enviar Respuesta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
