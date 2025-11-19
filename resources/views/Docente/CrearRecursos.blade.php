<!-- Modal para Crear Recurso -->
<div class="modal fade" id="modalCrearRecurso" tabindex="-1" aria-labelledby="modalCrearRecursoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Header del Modal -->
            <div class="modal-header bg-gradient-primary text-white">
                <div class="modal-header-content">
                    <i class="fas fa-file-upload fa-lg me-3"></i>
                    <div>
                        <h5 class="modal-title mb-0">Crear Nuevo Recurso</h5>
                        <small class="opacity-75">Agregar material de apoyo al curso</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body del Modal -->
            <div class="modal-body">
                <form id="resourceForm" action="{{ route('CrearRecursosPost', ['id' => encrypt($cursos->id)]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Título del Recurso -->
                    <div class="form-group mb-4">
                        <label for="fileTitle" class="form-label fw-semibold">
                            <i class="fas fa-heading me-2 text-primary"></i>Título del Recurso *
                        </label>
                        <input type="text" id="fileTitle" name="tituloRecurso" class="form-control modern-input" 
                               placeholder="Ej: Guía de Estudio - Tema 1" required minlength="3" maxlength="100">
                        <div class="form-text">Nombre descriptivo para identificar el recurso (3-100 caracteres)</div>
                    </div>

                    <!-- Descripción del Recurso -->
                    <div class="form-group mb-4">
                        <label for="fileDescription" class="form-label fw-semibold">
                            <i class="fas fa-align-left me-2 text-info"></i>Descripción del Recurso *
                        </label>
                        <textarea id="fileDescription" name="descripcionRecurso" class="form-control modern-input" 
                                  rows="4" placeholder="Describe el contenido y propósito de este recurso..." 
                                  required minlength="10" maxlength="500"></textarea>
                        <div class="form-text">Explica cómo este recurso ayudará en el aprendizaje (10-500 caracteres)</div>
                    </div>

                    <!-- Selección de Archivo -->
                    <div class="form-group mb-4">
                        <label for="fileUpload" class="form-label fw-semibold">
                            <i class="fas fa-paperclip me-2 text-warning"></i>Seleccionar Archivo
                        </label>
                        <input type="file" id="fileUpload" name="archivo" class="form-control modern-input">
                        <div class="form-text">Formatos soportados: documentos, imágenes, audio, video (máx. 10MB)</div>
                    </div>

                    <!-- Tipo de Recurso -->
                    <div class="form-group mb-4">
                        <label for="resourceSelect" class="form-label fw-semibold">
                            <i class="fas fa-tags me-2 text-success"></i>Tipo de Recurso *
                        </label>
                        <select id="resourceSelect" name="tipoRecurso" class="form-select modern-input" required>
                            <option value="" disabled selected>Selecciona el tipo de recurso...</option>
                            <optgroup label="📄 Documentos">
                                <option value="word">📝 Documento Word</option>
                                <option value="excel">📊 Hoja de Cálculo Excel</option>
                                <option value="powerpoint">📈 Presentación PowerPoint</option>
                                <option value="pdf">📕 Documento PDF</option>
                                <option value="archivos-adjuntos">📎 Archivos Adjuntos</option>
                            </optgroup>
                            <optgroup label="🌐 Plataformas Google">
                                <option value="docs">📝 Google Docs</option>
                                <option value="forms">📋 Google Forms</option>
                                <option value="drive">☁️ Google Drive</option>
                            </optgroup>
                            <optgroup label="🎥 Multimedia">
                                <option value="youtube">📺 Video de YouTube</option>
                                <option value="imagen">🖼️ Imagen</option>
                                <option value="video">🎬 Video</option>
                                <option value="audio">🎵 Audio</option>
                            </optgroup>
                            <optgroup label="🎮 Herramientas Interactivas">
                                <option value="kahoot">🎮 Kahoot</option>
                                <option value="canva">🎨 Canva</option>
                            </optgroup>
                            <optgroup label="💬 Plataformas de Reunión">
                                <option value="zoom">📹 Zoom</option>
                                <option value="meet">🎥 Google Meet</option>
                                <option value="teams">💬 Microsoft Teams</option>
                            </optgroup>
                            <optgroup label="🔗 Enlaces">
                                <option value="enlace">🔗 Enlace Externo</option>
                            </optgroup>
                        </select>
                        <div class="form-text">Selecciona la categoría que mejor describa tu recurso</div>
                    </div>

                    <!-- Indicador de Selección -->
                    <div class="selection-indicator mb-4">
                        <div class="alert alert-info py-2">
                            <i class="fas fa-info-circle me-2"></i>
                            <span id="selectedResourceText">No se ha seleccionado ningún tipo de recurso</span>
                        </div>
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
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Cancelar
                </button>
                <button type="submit" form="resourceForm" class="btn btn-success">
                    <i class="fas fa-save me-2"></i> Guardar Recurso
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos para el modal moderno */
.modal-header-content {
    display: flex;
    align-items: center;
}

.modal-header-content i {
    font-size: 1.5rem;
}

.modal-title {
    font-weight: 600;
}

.modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.modal-header {
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px 12px 0 0;
}

.modal-footer {
    border-top: 1px solid #e9ecef;
    border-radius: 0 0 12px 12px;
}

/* Inputs modernos */
.modern-input {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: #fff;
}

.modern-input:focus {
    border-color: var(--color-primary);
    box-shadow: 0 0 0 0.2rem rgba(26, 71, 137, 0.15);
    transform: translateY(-1px);
}

.modern-input:hover {
    border-color: #b3b3b3;
}

/* Select personalizado */
.form-select.modern-input {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.75rem center;
    background-repeat: no-repeat;
    background-size: 16px 12px;
    padding-right: 2.5rem;
}

/* Labels mejorados */
.form-label {
    color: var(--color-primary);
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.form-text {
    font-size: 0.85rem;
    color: #6c757d;
    margin-top: 0.25rem;
}

/* Botones mejorados */
.btn {
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
}

.btn-success {
    background: var(--gradient-success);
    color: white;
}

.btn-success:hover {
    background: var(--color-success);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.btn-outline-secondary {
    border: 1px solid #6c757d;
    color: #6c757d;
}

.btn-outline-secondary:hover {
    background: #6c757d;
    color: white;
    transform: translateY(-1px);
}

/* Indicador de selección */
.selection-indicator .alert {
    border: none;
    border-radius: 8px;
    background: #e3f2fd;
    color: var(--color-primary);
    border-left: 4px solid var(--color-primary);
}

/* Alertas mejoradas */
.alert {
    border: none;
    border-radius: 8px;
    padding: 1rem 1.25rem;
}

.alert-danger {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
    border-left: 4px solid #dc3545;
}

.alert-danger h6 {
    color: #721c24;
    font-weight: 600;
}

/* Optgroups mejorados */
optgroup {
    font-weight: 600;
    color: var(--color-primary);
}

optgroup option {
    font-weight: normal;
    padding: 0.5rem;
}

/* Animaciones suaves */
.modal.fade .modal-dialog {
    transform: translateY(-50px);
    transition: transform 0.3s ease-out;
}

.modal.show .modal-dialog {
    transform: translateY(0);
}

/* Responsive */
@media (max-width: 576px) {
    .modal-dialog {
        margin: 0.5rem;
    }
    
    .modal-header-content {
        flex-direction: column;
        text-align: center;
        gap: 0.5rem;
    }
    
    .modal-header-content i {
        margin-right: 0;
    }
    
    .modal-footer {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .modal-footer .btn {
        width: 100%;
    }
}

/* Efectos de focus mejorados */
.form-control:focus,
.form-select:focus {
    outline: none;
}

/* Iconos en labels */
.form-label i {
    width: 20px;
    text-align: center;
}

/* Validación visual */
.modern-input:invalid:not(:focus):not(:placeholder-shown) {
    border-color: #dc3545;
    background-color: #f8d7da;
}

.modern-input:valid:not(:focus):not(:placeholder-shown) {
    border-color: #28a745;
    background-color: #d4edda;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalCrearRecurso = document.getElementById('modalCrearRecurso');
    const resourceForm = document.getElementById('resourceForm');
    const resourceSelect = document.getElementById('resourceSelect');
    const selectedResourceText = document.getElementById('selectedResourceText');
    const fileUpload = document.getElementById('fileUpload');
    
    // Actualizar texto de recurso seleccionado
    resourceSelect.addEventListener('change', function() {
        const selectedValue = this.value;
        const selectedOption = this.options[this.selectedIndex];
        const selectedText = selectedOption.text.replace(/[📄📝📊📈📕📎🌐📋☁️🎥📺🖼️🎬🎵🎮🎨💬📹🎥💬🔗]/g, '').trim();
        
        selectedResourceText.textContent = `Tipo seleccionado: ${selectedText}`;
        
        // Actualizar validación de archivo según el tipo
        updateFileValidation(selectedValue);
    });
    
    // Actualizar validación de archivo
    function updateFileValidation(resourceType) {
        const fileTypes = {
            'word': '.doc,.docx',
            'excel': '.xls,.xlsx',
            'powerpoint': '.ppt,.pptx',
            'pdf': '.pdf',
            'imagen': '.jpg,.jpeg,.png,.gif,.webp',
            'video': '.mp4,.avi,.mov,.wmv',
            'audio': '.mp3,.wav,.ogg,.m4a',
            'archivos-adjuntos': '.zip,.rar,.7z'
        };
        
        if (fileTypes[resourceType]) {
            fileUpload.setAttribute('accept', fileTypes[resourceType]);
            fileUpload.required = true;
        } else {
            fileUpload.removeAttribute('accept');
            fileUpload.required = false;
        }
    }
    
    // Limpiar formulario cuando se cierra el modal
    modalCrearRecurso.addEventListener('hidden.bs.modal', function () {
        resourceForm.reset();
        selectedResourceText.textContent = 'No se ha seleccionado ningún tipo de recurso';
        
        // Limpiar errores de validación
        const errorAlert = resourceForm.querySelector('.alert-danger');
        if (errorAlert) {
            errorAlert.remove();
        }
    });
    
    // Validación del formulario
    resourceForm.addEventListener('submit', function(e) {
        const titulo = document.getElementById('fileTitle').value.trim();
        const descripcion = document.getElementById('fileDescription').value.trim();
        const tipoRecurso = resourceSelect.value;
        
        // Validaciones básicas
        if (!titulo || !descripcion || !tipoRecurso) {
            e.preventDefault();
            showAlert('Por favor, completa todos los campos requeridos', 'danger');
            return;
        }
        
        if (titulo.length < 3 || titulo.length > 100) {
            e.preventDefault();
            showAlert('El título debe tener entre 3 y 100 caracteres', 'danger');
            return;
        }
        
        if (descripcion.length < 10 || descripcion.length > 500) {
            e.preventDefault();
            showAlert('La descripción debe tener entre 10 y 500 caracteres', 'danger');
            return;
        }
        
        // Mostrar estado de carga
        const submitBtn = resourceForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Guardando...';
        submitBtn.disabled = true;
        
        // Restaurar después de 3 segundos (en caso de que falle el envío)
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 3000);
    });
    
    // Mostrar alertas
    function showAlert(message, type) {
        // Remover alertas existentes
        const existingAlert = resourceForm.querySelector('.alert');
        if (existingAlert) {
            existingAlert.remove();
        }
        
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} mt-3`;
        alertDiv.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <div>${message}</div>
            </div>
        `;
        
        resourceForm.insertBefore(alertDiv, resourceForm.firstChild);
        
        // Hacer scroll hasta la alerta
        alertDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    
    // Validación en tiempo real
    const inputs = resourceForm.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            clearFieldValidation(this);
        });
    });
    
    function validateField(field) {
        const value = field.value.trim();
        
        if (field.required && !value) {
            setFieldError(field, 'Este campo es requerido');
            return false;
        }
        
        if (field.id === 'fileTitle' && value.length < 3) {
            setFieldError(field, 'El título debe tener al menos 3 caracteres');
            return false;
        }
        
        if (field.id === 'fileDescription' && value.length < 10) {
            setFieldError(field, 'La descripción debe tener al menos 10 caracteres');
            return false;
        }
        
        setFieldSuccess(field);
        return true;
    }
    
    function setFieldError(field, message) {
        field.classList.add('is-invalid');
        field.classList.remove('is-valid');
        
        // Remover feedback existente
        const existingFeedback = field.parentNode.querySelector('.invalid-feedback');
        if (existingFeedback) {
            existingFeedback.remove();
        }
        
        // Agregar nuevo feedback
        const feedback = document.createElement('div');
        feedback.className = 'invalid-feedback';
        feedback.textContent = message;
        field.parentNode.appendChild(feedback);
    }
    
    function setFieldSuccess(field) {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
        
        // Remover feedback existente
        const existingFeedback = field.parentNode.querySelector('.invalid-feedback');
        if (existingFeedback) {
            existingFeedback.remove();
        }
    }
    
    function clearFieldValidation(field) {
        field.classList.remove('is-invalid', 'is-valid');
        
        const existingFeedback = field.parentNode.querySelector('.invalid-feedback');
        if (existingFeedback) {
            existingFeedback.remove();
        }
    }
});
</script>