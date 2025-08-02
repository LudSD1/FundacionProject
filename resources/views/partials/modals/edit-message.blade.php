<div class="modal fade" id="editMessageModal-{{ $mensaje->id }}" tabindex="-1"
    aria-labelledby="editMessageModalLabel-{{ $mensaje->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0 bg-light">
                <h5 class="modal-title" id="editMessageModalLabel-{{ $mensaje->id }}">
                    <i class="bi bi-pencil-square text-primary me-2"></i>
                    Editar Mensaje
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('foro.mensaje.edit', $mensaje->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="tituloMensaje" class="form-label">Título del mensaje</label>
                        <input type="text"
                               class="form-control form-control-lg"
                               name="tituloMensaje"
                               value="{{ $mensaje->tituloMensaje }}"
                               required>
                    </div>

                    <div class="mb-4">
                        <label for="mensaje" class="form-label">Contenido del mensaje</label>
                        <textarea class="form-control"
                                  name="mensaje"
                                  rows="6"
                                  required>{{ $mensaje->mensaje }}</textarea>
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
