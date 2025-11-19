<div class="modal fade" id="modalCrearForo" tabindex="-1" aria-labelledby="modalCrearForoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-stable">
        <div class="modal-content">
            <!-- Header del Modal -->
            <div class="modal-header bg-primary text-white">
                <div class="d-flex align-items-center">
                    <i class="fas fa-comments me-3"></i>
                    <div>
                        <h5 class="modal-title mb-0">Crear Nuevo Foro</h5>
                        <small class="opacity-75">Comunidad de aprendizaje</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <!-- Body del Modal -->
            <div class="modal-body">
                <form method="post" action="{{ route('CrearForoPost', encrypt($cursos->id)) }}" id="formCrearForo">
                    @csrf
                    <input type="hidden" name="curso_id" value="{{ $cursos->id }}">

                    <!-- Nombre del Foro -->
                    <div class="mb-3">
                        <label for="nombreForo" class="form-label fw-semibold">
                            Nombre del Foro *
                        </label>
                        <input type="text" class="form-control" id="nombreForo" name="nombreForo"
                            placeholder="Ej: Discusión sobre temas avanzados" required>
                        <div class="form-text">Título principal que identificará el foro</div>
                    </div>

                    <!-- Subtítulo -->
                    <div class="mb-3">
                        <label for="subtituloForo" class="form-label fw-semibold">
                            Subtítulo (Opcional)
                        </label>
                        <input type="text" class="form-control" id="subtituloForo" name="SubtituloForo"
                            placeholder="Ej: Espacio para debates y consultas">
                        <div class="form-text">Texto adicional que aparecerá bajo el título principal</div>
                    </div>

                    <!-- Descripción -->
                    <div class="mb-3">
                        <label for="descripcionForo" class="form-label fw-semibold">
                            Descripción del Foro *
                        </label>
                        <textarea class="form-control" id="descripcionForo" name="descripcionForo" rows="4"
                            placeholder="Describe el propósito y temas a discutir..." required></textarea>
                        <div class="form-text">Explica claramente los objetivos y reglas de participación</div>
                    </div>

                    <!-- Fecha de Finalización -->
                    <div class="mb-4">
                        <label for="fechaFin" class="form-label fw-semibold">
                            Fecha de Finalización
                        </label>
                        <input type="date" class="form-control" id="fechaFin" name="fechaFin"
                            min="{{ date('Y-m-d') }}">
                        <div class="form-text">Fecha límite para participar en el foro (opcional)</div>
                    </div>

                    <!-- Mostrar errores de validación -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <h6 class="mb-0">Errores en el formulario:</h6>
                            </div>
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </form>
            </div>

            <!-- Footer del Modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancelar
                </button>
                <button type="submit" form="formCrearForo" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Crear Foro
                </button>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalCrearForo = document.getElementById('modalCrearForo');
        const formCrearForo = document.getElementById('formCrearForo');

        // Limpiar formulario cuando se cierra el modal
        modalCrearForo.addEventListener('hidden.bs.modal', function() {
            formCrearForo.reset();

            // Limpiar errores de validación
            const errorAlert = formCrearForo.querySelector('.alert-danger');
            if (errorAlert) {
                errorAlert.remove();
            }
        });

        // Configurar fecha mínima
        const fechaFinInput = document.getElementById('fechaFin');
        if (fechaFinInput) {
            fechaFinInput.min = new Date().toISOString().split('T')[0];
        }

        // Validación simple del formulario
        formCrearForo.addEventListener('submit', function(e) {
            const nombreForo = document.getElementById('nombreForo').value.trim();
            const descripcionForo = document.getElementById('descripcionForo').value.trim();

            if (!nombreForo || !descripcionForo) {
                e.preventDefault();
                // Mostrar mensaje simple sin animaciones
                alert('Por favor, completa todos los campos requeridos');
                return;
            }
        });
    });
</script>
