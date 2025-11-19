<!-- Modal Estable para Editar Foro -->
<div class="modal fade" id="modalEditarForo-{{ $foro->id }}" tabindex="-1"
    aria-labelledby="modalEditarForoLabel-{{ $foro->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-stable">
        <div class="modal-content">
            <!-- Header del Modal -->
            <div class="modal-header bg-warning text-dark">
                <div class="d-flex align-items-center">
                    <i class="fas fa-edit me-3"></i>
                    <div>
                        <h5 class="modal-title mb-0">Editar Foro</h5>
                        <small class="opacity-75">{{ $foro->nombreForo }}</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body del Modal -->
            <div class="modal-body">
                <form method="post" action="{{ route('EditarForoPost', encrypt($foro->id)) }}"
                    id="formEditarForo-{{ $foro->id }}">
                    @csrf
                    <input type="hidden" name="idForo" value="{{ $foro->id }}">
                    <input type="hidden" name="curso_id" value="{{ $foro->cursos_id }}">

                    <!-- Nombre del Foro -->
                    <div class="mb-3">
                        <label for="nombreForo-{{ $foro->id }}" class="form-label fw-semibold">
                            Nombre del Foro *
                        </label>
                        <input type="text" class="form-control" id="nombreForo-{{ $foro->id }}" name="nombreForo"
                            value="{{ $foro->nombreForo }}" placeholder="Introduce el nombre del foro" required>
                        <div class="form-text">Título principal que identificará el foro</div>
                    </div>

                    <!-- Subtítulo -->
                    <div class="mb-3">
                        <label for="subtituloForo-{{ $foro->id }}" class="form-label fw-semibold">
                            Subtítulo (Opcional)
                        </label>
                        <input type="text" class="form-control" id="subtituloForo-{{ $foro->id }}"
                            name="SubtituloForo" value="{{ $foro->SubtituloForo }}"
                            placeholder="Introduce un subtítulo opcional">
                        <div class="form-text">Texto adicional que aparecerá bajo el título principal</div>
                    </div>

                    <!-- Descripción -->
                    <div class="mb-3">
                        <label for="descripcionForo-{{ $foro->id }}" class="form-label fw-semibold">
                            Descripción del Foro *
                        </label>
                        <textarea class="form-control" id="descripcionForo-{{ $foro->id }}" name="descripcionForo" rows="4"
                            placeholder="Escribe aquí la descripción del foro" required>{{ trim($foro->descripcionForo) }}</textarea>
                        <div class="form-text">Explica claramente los objetivos y reglas de participación</div>
                    </div>

                    <!-- Fecha de Finalización -->
                    <div class="mb-4">
                        <label for="fechaFin-{{ $foro->id }}" class="form-label fw-semibold">
                            Fecha de Finalización
                        </label>
                        <input type="date" class="form-control" id="fechaFin-{{ $foro->id }}" name="fechaFin"
                            value="{{ $foro->fechaFin }}">
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
                <button type="submit" form="formEditarForo-{{ $foro->id }}" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Configurar para todos los modales de edición de foros
        const modalesEditarForo = document.querySelectorAll('[id^="modalEditarForo-"]');

        modalesEditarForo.forEach(modal => {
            const formId = modal.querySelector('form').id;
            const form = document.getElementById(formId);

            // Desactivar animaciones problemáticas
            function disableProblematicAnimations() {
                const elements = modal.querySelectorAll(
                    '.fade, .modal-dialog, .modal-content, .modal-header, .modal-body, .modal-footer'
                    );
                elements.forEach(el => {
                    el.style.transition = 'none';
                    el.style.animation = 'none';
                    el.style.transform = 'none';
                });
            }

            // Ejecutar al abrir el modal
            modal.addEventListener('show.bs.modal', function() {
                disableProblematicAnimations();

                // Configurar fecha mínima si existe el campo
                const fechaFinInput = modal.querySelector('input[type="date"]');
                if (fechaFinInput && !fechaFinInput.value) {
                    fechaFinInput.min = new Date().toISOString().split('T')[0];
                }
            });

            // Limpiar errores al cerrar (pero mantener los valores del formulario)
            modal.addEventListener('hidden.bs.modal', function() {
                const errorAlert = modal.querySelector('.alert-danger');
                if (errorAlert) {
                    errorAlert.remove();
                }
            });

            // Validación del formulario
            form.addEventListener('submit', function(e) {
                const nombreForo = form.querySelector('input[name="nombreForo"]').value.trim();
                const descripcionForo = form.querySelector('textarea[name="descripcionForo"]')
                    .value.trim();

                if (!nombreForo || !descripcionForo) {
                    e.preventDefault();
                    alert('Por favor, completa todos los campos requeridos');
                    return;
                }

                // Mostrar estado de carga
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML =
                '<i class="fas fa-spinner fa-spin me-1"></i> Guardando...';
                submitBtn.disabled = true;

                // Restaurar después de 3 segundos (en caso de que falle el envío)
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 3000);
            });
        });
    });

    // Función global para forzar la estabilidad de todos los modales
    function forceAllModalsStable() {
        const style = document.createElement('style');
        style.textContent = `
        .modal.fade .modal-dialog {
            transition: none !important;
            transform: none !important;
        }
        .modal.show .modal-dialog {
            transform: none !important;
        }
        .modal-content,
        .modal-header,
        .modal-body,
        .modal-footer {
            transform: none !important;
            transition: none !important;
            animation: none !important;
        }
        .form-control:focus {
            transform: none !important;
        }
        .btn:hover {
            transform: none !important;
        }
    `;
        document.head.appendChild(style);
    }

    // Ejecutar la función de estabilidad
    forceAllModalsStable();
</script>
