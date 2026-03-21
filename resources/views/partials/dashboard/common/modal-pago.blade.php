<div class="modal fade" id="pagoModal" tabindex="-1" aria-labelledby="pagoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('registrarpagoPost') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-warning-subtle">
                    <h5 class="modal-title" id="pagoModalLabel">
                        <i class="bi bi-credit-card-2-front me-2"></i>Completar Pago
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Campos ocultos requeridos -->
                    <input type="hidden" name="curso_id" id="modalCursoId">
                    <input type="hidden" name="inscrito_id" id="modalInscritoId">

                    <!-- Info del usuario -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-person me-1"></i>Estudiante:
                        </label>
                        <input type="text" id="modalEstudianteNombre" class="form-control" readonly>
                    </div>

                    <!-- Curso -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-book me-1"></i>Curso:
                        </label>
                        <input type="text" id="modalCursoNombre" class="form-control" readonly>
                    </div>

                    <hr>

                    <!-- Monto a pagar -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-cash-coin me-1"></i>Monto a Pagar:
                        </label>
                        <div class="input-group">
                            <input type="number" name="montopagar" id="modalCursoPrecio"
                                class="form-control" min="1" step="any" readonly>
                            <span class="input-group-text">Bs</span>
                        </div>
                    </div>

                    <!-- Comprobante -->
                    <div class="mb-3" id="campoComprobante">
                        <label class="form-label">
                            <i class="bi bi-file-earmark-arrow-up me-1"></i>Comprobante de Pago:
                        </label>
                        <input type="file" name="comprobante" id="comprobanteFile" class="form-control"
                            accept=".pdf,.jpg,.jpeg,.png" required>
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Formatos: PDF, JPG, PNG (Max. 2MB)
                        </small>
                    </div>

                    <!-- Descripción -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-chat-left-text me-1"></i>Descripción:
                        </label>
                        <textarea name="descripcion" id="descripcionText" class="form-control" rows="3"
                            placeholder="Detalle del pago realizado..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-success" id="btnConfirmar">
                        <i class="bi bi-check2-circle me-1"></i>Confirmar Pago
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var pagoModal = document.getElementById('pagoModal');
        if (!pagoModal) return;

        pagoModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            if (!button) return;

            // Leer data attributes del botón
            var inscritoId = button.getAttribute('data-inscrito-id');
            var cursoId = button.getAttribute('data-curso-id');
            var cursoNombre = button.getAttribute('data-curso-nombre');
            var cursoPrecio = parseFloat(button.getAttribute('data-curso-precio')) || 0;
            var estudianteNombre = button.getAttribute('data-estudiante-nombre');

            // Asignar valores al formulario
            document.getElementById('modalCursoId').value = cursoId || '';
            document.getElementById('modalCursoNombre').value = cursoNombre || '';
            document.getElementById('modalCursoPrecio').value = cursoPrecio;
            document.getElementById('modalEstudianteNombre').value = estudianteNombre || '';

            if (inscritoId) {
                document.getElementById('modalInscritoId').value = inscritoId;
            }

            // Actualizar título del modal
            document.getElementById('pagoModalLabel').innerHTML =
                '<i class="bi bi-credit-card-2-front me-2"></i>Pago: ' + (cursoNombre || 'Curso');
        });
    });
</script>
@endpush
