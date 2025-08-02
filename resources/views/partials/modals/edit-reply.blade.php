<div class="modal fade" id="editRespuestaModal-{{ $respuesta->id }}" tabindex="-1"
    aria-labelledby="editRespuestaModalLabel-{{ $respuesta->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0 bg-light">
                <h5 class="modal-title" id="editRespuestaModalLabel-{{ $respuesta->id }}">
                    <i class="bi bi-pencil-square text-primary me-2"></i>
                    Editar Respuesta
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('foro.respuesta.edit', $respuesta->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="tituloMensaje" class="form-label">Título de la respuesta</label>
                        <input type="text"
                               class="form-control form-control-lg"
                               name="tituloMensaje"
                               value="{{ $respuesta->tituloMensaje }}"
                               required>
                    </div>

                    <div class="mb-4">
                        <label for="mensaje" class="form-label">Contenido de la respuesta</label>
                        <textarea class="form-control"
                                  name="mensaje"
                                  rows="6"
                                  required>{{ $respuesta->mensaje }}</textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check2-circle me-2"></i>
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
