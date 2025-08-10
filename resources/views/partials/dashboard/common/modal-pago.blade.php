<div class="modal fade" id="pagoModal" tabindex="-1" aria-labelledby="pagoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('registrarpagoPost') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="pagoModalLabel">Completar Compra</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Campos requeridos -->
                    <input type="hidden" name="curso_id" id="modalCursoId">
                    <input type="hidden" name="inscrito_id" id="modalInscritoId">

                    <!-- Usuario -->
                    <div class="mb-3">
                        <label class="form-label">Usuario:</label>
                        <input type="text" id="modalEstudianteNombre" class="form-control" readonly>
                    </div>

                    <hr>

                    <!-- Curso -->
                    <div class="mb-3">
                        <label class="form-label">Curso:</label>
                        <input type="text" id="modalCursoNombre" class="form-control" readonly>
                    </div>

                    <!-- Monto -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Monto a Pagar:</label>
                            <div class="input-group">
                                <input type="number" name="montopagar" id="modalCursoPrecio"
                                    class="form-control" min="1" step="any" readonly>
                                <span class="input-group-text">$</span>
                            </div>
                        </div>
                    </div>

                    <!-- Campos de pago (solo para cursos pagos) -->
                    <div id="camposPago" style="display: none;">
                        <!-- Comprobante -->
                        <div class="mb-3">
                            <label class="form-label">Comprobante:</label>
                            <input type="file" name="comprobante" id="comprobanteFile" class="form-control">
                        </div>

                        <!-- Descripción -->
                        <div class="mb-3">
                            <label class="form-label">Descripción:</label>
                            <textarea name="descripcion" id="descripcionText" class="form-control" rows="4"></textarea>
                        </div>

                        <div class="m-3 text-center">
                            <img src="{{ asset('assets/img/pago.png') }}" alt="Métodos de pago" class="img-fluid">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success" id="btnConfirmar">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var pagoModal = document.getElementById('pagoModal');
        pagoModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;

            // Obtener data
            var inscritoId = button.getAttribute('data-inscrito-id');
            var cursoId = button.getAttribute('data-curso-id');
            var cursoNombre = button.getAttribute('data-curso-nombre');
            var cursoPrecio = parseFloat(button.getAttribute('data-curso-precio')) || 0;
            var estudianteNombre = button.getAttribute('data-estudiante-nombre');

            // Asignar valores básicos
            document.getElementById('modalCursoId').value = cursoId;
            document.getElementById('modalCursoNombre').value = cursoNombre;
            document.getElementById('modalCursoPrecio').value = cursoPrecio;
            document.getElementById('modalEstudianteNombre').value = estudianteNombre;

            // Solo asignar inscrito_id si existe
            if (inscritoId) {
                document.getElementById('modalInscritoId').value = inscritoId;
            }

            // Mostrar/ocultar campos según si es curso pago
            var camposPago = document.getElementById('camposPago');
            var comprobanteFile = document.getElementById('comprobanteFile');
            var descripcionText = document.getElementById('descripcionText');
            var btnConfirmar = document.getElementById('btnConfirmar');

            if (cursoPrecio > 0) {
                // Curso pago
                camposPago.style.display = 'block';
                comprobanteFile.required = true;
                descripcionText.required = true;
                btnConfirmar.textContent = 'Confirmar Compra';
                document.getElementById('pagoModalLabel').textContent = 'Pago: ' + cursoNombre;
            } else {
                // Curso gratuito
                camposPago.style.display = 'none';
                comprobanteFile.required = false;
                descripcionText.required = false;
                btnConfirmar.textContent = 'Inscribirse';
                document.getElementById('pagoModalLabel').textContent = 'Inscripción: ' + cursoNombre;
            }
        });
    });
</script>
@endpush
