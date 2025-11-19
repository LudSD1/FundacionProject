
<!-- Modal para Modificar Recurso -->
<div class="modal fade" id="modalEditarRecurso-{{ $recurso->id }}" tabindex="-1" aria-labelledby="modalEditarRecursoLabel-{{ $recurso->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Header del Modal -->
            <div class="modal-header bg-gradient-warning text-dark">
                <div class="modal-header-content">
                    <i class="fas fa-edit fa-lg me-3"></i>
                    <div>
                        <h5 class="modal-title mb-0">Modificar Recurso</h5>
                        <small class="opacity-75">Actualiza la información del recurso educativo</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body del Modal -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-lg-8">
                        <!-- Formulario principal -->
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-white py-3">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-edit text-warning me-2"></i>Información del Recurso
                                </h6>
                            </div>
                            <div class="card-body">
                                <form method="POST" enctype="multipart/form-data" action="{{ route('editarRecursosPost', encrypt($recurso->id)) }}" id="resourceForm-{{ $recurso->id }}">
                                    @csrf
                                    <input type="hidden" value="{{ $recurso->cursos_id }}" name="cursos_id">
                                    <input type="hidden" value="{{ $recurso->id }}" name="idRecurso">

                                    <!-- Título del recurso -->
                                    <div class="form-group mb-4">
                                        <label for="tituloRecurso-{{ $recurso->id }}" class="form-label fw-semibold">
                                            <i class="fas fa-heading text-warning me-2"></i>Título del Recurso *
                                        </label>
                                        <input type="text"
                                               id="tituloRecurso-{{ $recurso->id }}"
                                               name="tituloRecurso"
                                               class="form-control modern-input @error('tituloRecurso') is-invalid @enderror"
                                               value="{{ old('tituloRecurso', $recurso->nombreRecurso) }}"
                                               placeholder="Ingrese el título del recurso"
                                               required>
                                        @error('tituloRecurso')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Descripción -->
                                    <div class="form-group mb-4">
                                        <label for="descripcionRecurso-{{ $recurso->id }}" class="form-label fw-semibold">
                                            <i class="fas fa-align-left text-warning me-2"></i>Descripción del Recurso *
                                        </label>
                                        <textarea id="descripcionRecurso-{{ $recurso->id }}"
                                                  name="descripcionRecurso"
                                                  rows="4"
                                                  class="form-control modern-input @error('descripcionRecurso') is-invalid @enderror"
                                                  placeholder="Describe el contenido y propósito del recurso"
                                                  required>{{ old('descripcionRecurso', $recurso->descripcionRecursos) }}</textarea>
                                        @error('descripcionRecurso')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Upload de archivo -->
                                    <div class="form-group mb-4">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-upload text-warning me-2"></i>Reemplazar Archivo
                                        </label>
                                        
                                        <!-- Archivo actual -->
                                        @if($recurso->archivoRecurso)
                                        <div class="current-file mb-3 p-3 bg-light rounded">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file text-success fa-2x me-3"></i>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">Archivo Actual</h6>
                                                    <p class="mb-0 text-muted small">{{ basename($recurso->archivoRecurso) }}</p>
                                                </div>
                                                <a href="{{ asset('storage/' . $recurso->archivoRecurso) }}" 
                                                   class="btn btn-outline-success btn-sm"
                                                   target="_blank">
                                                    <i class="fas fa-download me-1"></i> Descargar
                                                </a>
                                            </div>
                                        </div>
                                        @endif

                                        <div class="upload-area border-2 border-dashed border-secondary rounded p-4 text-center">
                                            <input type="file"
                                                   id="archivo-{{ $recurso->id }}"
                                                   name="archivo"
                                                   class="form-control @error('archivo') is-invalid @enderror"
                                                   style="display: none;"
                                                   accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.jpg,.jpeg,.png,.gif,.mp4,.mp3">
                                            <div id="upload-display-{{ $recurso->id }}">
                                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                                <p class="mb-2 text-muted">Haz clic para seleccionar un archivo o arrastra y suelta</p>
                                                <button type="button" class="btn btn-outline-warning" onclick="document.getElementById('archivo-{{ $recurso->id }}').click()">
                                                    Seleccionar Archivo
                                                </button>
                                            </div>
                                            <div id="file-info-{{ $recurso->id }}" class="d-none">
                                                <i class="fas fa-file fa-2x text-success mb-2"></i>
                                                <p class="mb-0" id="file-name-{{ $recurso->id }}"></p>
                                                <small class="text-muted" id="file-size-{{ $recurso->id }}"></small>
                                            </div>
                                        </div>
                                        @error('archivo')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            Deja vacío si no deseas cambiar el archivo actual
                                        </small>
                                    </div>

                                    <input type="hidden" id="tipoRecurso-{{ $recurso->id }}" name="tipoRecurso" value="{{ old('tipoRecurso', $recurso->tipoRecurso ?? '') }}">
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-4">
                        <!-- Selector de tipo de recurso -->
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white py-3">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-icons text-warning me-2"></i>Tipo de Recurso *
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="selected-type-display mb-3 p-3 bg-light rounded">
                                    <div class="d-flex align-items-center" id="selected-display-{{ $recurso->id }}">
                                        @if($recurso->tipoRecurso)
                                            @php
                                                $iconos = [
                                                    'word' => 'fas fa-file-word text-primary',
                                                    'excel' => 'fas fa-file-excel text-success',
                                                    'powerpoint' => 'fas fa-file-powerpoint text-warning',
                                                    'pdf' => 'fas fa-file-pdf text-danger',
                                                    'docs' => 'fab fa-google-drive text-primary',
                                                    'imagen' => 'fas fa-image text-info',
                                                    'video' => 'fas fa-video text-dark',
                                                    'audio' => 'fas fa-music text-purple',
                                                    'youtube' => 'fab fa-youtube text-danger',
                                                    'forms' => 'fas fa-wpforms text-success',
                                                    'drive' => 'fab fa-google-drive text-warning',
                                                    'kahoot' => 'fas fa-gamepad text-info',
                                                    'canva' => 'fas fa-palette text-pink',
                                                    'enlace' => 'fas fa-link text-secondary',
                                                    'archivos-adjuntos' => 'fas fa-paperclip text-muted',
                                                    'zoom' => 'fas fa-video text-primary',
                                                    'meet' => 'fas fa-video text-success',
                                                    'teams' => 'fas fa-users text-info'
                                                ];
                                                $currentIcon = $iconos[$recurso->tipoRecurso] ?? 'fas fa-file text-muted';
                                                $currentName = ucfirst($recurso->tipoRecurso);
                                            @endphp
                                            <i class="{{ $currentIcon }} fa-2x me-3"></i>
                                            <div>
                                                <h6 class="mb-1">{{ $currentName }}</h6>
                                                <small class="text-muted">Tipo actual</small>
                                            </div>
                                        @else
                                            <i class="fas fa-question-circle fa-2x text-muted me-3"></i>
                                            <div>
                                                <h6 class="mb-1">Ninguno seleccionado</h6>
                                                <small class="text-muted">Selecciona un tipo de recurso</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Selector de tipo mejorado -->
                                <div class="resource-type-selector">
                                    <select class="form-select modern-input" id="resourceSelect-{{ $recurso->id }}" required>
                                        <option value="" disabled {{ !$recurso->tipoRecurso ? 'selected' : '' }}>Selecciona un tipo...</option>
                                        <optgroup label="📄 Documentos">
                                            <option value="word" {{ $recurso->tipoRecurso == 'word' ? 'selected' : '' }}>📝 Word</option>
                                            <option value="excel" {{ $recurso->tipoRecurso == 'excel' ? 'selected' : '' }}>📊 Excel</option>
                                            <option value="powerpoint" {{ $recurso->tipoRecurso == 'powerpoint' ? 'selected' : '' }}>📈 PowerPoint</option>
                                            <option value="pdf" {{ $recurso->tipoRecurso == 'pdf' ? 'selected' : '' }}>📕 PDF</option>
                                        </optgroup>
                                        <optgroup label="🌐 Google Workspace">
                                            <option value="docs" {{ $recurso->tipoRecurso == 'docs' ? 'selected' : '' }}>📝 Google Docs</option>
                                            <option value="forms" {{ $recurso->tipoRecurso == 'forms' ? 'selected' : '' }}>📋 Google Forms</option>
                                            <option value="drive" {{ $recurso->tipoRecurso == 'drive' ? 'selected' : '' }}>☁️ Google Drive</option>
                                        </optgroup>
                                        <optgroup label="🎥 Multimedia">
                                            <option value="imagen" {{ $recurso->tipoRecurso == 'imagen' ? 'selected' : '' }}>🖼️ Imagen</option>
                                            <option value="video" {{ $recurso->tipoRecurso == 'video' ? 'selected' : '' }}>🎬 Video</option>
                                            <option value="audio" {{ $recurso->tipoRecurso == 'audio' ? 'selected' : '' }}>🎵 Audio</option>
                                            <option value="youtube" {{ $recurso->tipoRecurso == 'youtube' ? 'selected' : '' }}>📺 YouTube</option>
                                        </optgroup>
                                        <optgroup label="💬 Videoconferencia">
                                            <option value="zoom" {{ $recurso->tipoRecurso == 'zoom' ? 'selected' : '' }}>📹 Zoom</option>
                                            <option value="meet" {{ $recurso->tipoRecurso == 'meet' ? 'selected' : '' }}>🎥 Google Meet</option>
                                            <option value="teams" {{ $recurso->tipoRecurso == 'teams' ? 'selected' : '' }}>💬 Microsoft Teams</option>
                                        </optgroup>
                                        <optgroup label="🎮 Herramientas Educativas">
                                            <option value="kahoot" {{ $recurso->tipoRecurso == 'kahoot' ? 'selected' : '' }}>🎮 Kahoot</option>
                                            <option value="canva" {{ $recurso->tipoRecurso == 'canva' ? 'selected' : '' }}>🎨 Canva</option>
                                        </optgroup>
                                        <optgroup label="🔗 Otros">
                                            <option value="enlace" {{ $recurso->tipoRecurso == 'enlace' ? 'selected' : '' }}>🔗 Enlace Web</option>
                                            <option value="archivos-adjuntos" {{ $recurso->tipoRecurso == 'archivos-adjuntos' ? 'selected' : '' }}>📎 Archivos Adjuntos</option>
                                        </optgroup>
                                    </select>
                                    <div class="form-text">Selecciona la categoría que mejor describa tu recurso</div>
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="card-footer bg-white border-0 pt-0">
                                <div class="d-grid gap-2">
                                    <button type="submit" form="resourceForm-{{ $recurso->id }}" class="btn btn-warning btn-lg">
                                        <i class="fas fa-save me-2"></i>Guardar Cambios
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-2"></i>Cancelar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mostrar errores -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
                        <h6 class="alert-heading">
                            <i class="fas fa-exclamation-triangle me-2"></i>Se encontraron errores:
                        </h6>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
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
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 12px 12px 0 0;
}

.modal-body {
    padding: 1.5rem;
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
    border-color: var(--color-warning);
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.15);
    transform: translateY(-1px);
}

/* Upload area mejorada */
.upload-area {
    transition: all 0.3s ease;
    cursor: pointer;
    min-height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px dashed #dee2e6 !important;
}

.upload-area:hover {
    border-color: var(--color-warning) !important;
    background-color: #fffbf0;
}

.upload-area.dragover {
    border-color: var(--color-success) !important;
    background-color: #f8fff9;
}

/* Current file display */
.current-file {
    border-left: 4px solid var(--color-success);
}

/* Cards mejoradas */
.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
}

.card-header {
    background: white;
    border-bottom: 1px solid #f0f0f0;
    border-radius: 10px 10px 0 0 !important;
}

/* Selected type display */
.selected-type-display {
    border-left: 4px solid var(--color-warning);
    transition: all 0.3s ease;
}

/* Botones mejorados */
.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
}

.btn-warning {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    color: #212529;
}

.btn-warning:hover {
    background: #e0a800;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
}

.btn-outline-warning {
    border: 1px solid var(--color-warning);
    color: var(--color-warning);
}

.btn-outline-warning:hover {
    background: var(--color-warning);
    color: #212529;
}

/* Responsive */
@media (max-width: 768px) {
    .modal-dialog {
        margin: 0.5rem;
    }
    
    .modal-header-content {
        flex-direction: column;
        text-align: center;
        gap: 0.5rem;
    }
    
    .row {
        flex-direction: column;
    }
    
    .col-lg-4 {
        margin-top: 1rem;
    }
}

/* Animaciones suaves */
.modal.fade .modal-dialog {
    transform: translateY(-50px);
    transition: transform 0.3s ease-out;
}

.modal.show .modal-dialog {
    transform: translateY(0);
}

/* Select personalizado */
.form-select.modern-input {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.75rem center;
    background-repeat: no-repeat;
    background-size: 16px 12px;
    padding-right: 2.5rem;
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalId = '{{ $recurso->id }}';
    const resourceForm = document.getElementById('resourceForm-' + modalId);
    const resourceSelect = document.getElementById('resourceSelect-' + modalId);
    const tipoRecursoInput = document.getElementById('tipoRecurso-' + modalId);
    const selectedDisplay = document.getElementById('selected-display-' + modalId);
    const fileInput = document.getElementById('archivo-' + modalId);
    const uploadDisplay = document.getElementById('upload-display-' + modalId);
    const fileInfo = document.getElementById('file-info-' + modalId);
    const fileName = document.getElementById('file-name-' + modalId);
    const fileSize = document.getElementById('file-size-' + modalId);
    const uploadArea = document.querySelector('.upload-area');

    // Inicializar selección actual
    if (resourceSelect.value) {
        updateSelectedDisplay(resourceSelect.value, resourceSelect.options[resourceSelect.selectedIndex].text);
    }

    // Manejo de selección de tipo
    resourceSelect.addEventListener('change', function() {
        const value = this.value;
        const text = this.options[this.selectedIndex].text.replace(/[📄📝📊📈📕🌐📋☁️🎥🖼️🎬🎵📺💬📹🎥💬🎮🎨🔗📎]/g, '').trim();
        
        tipoRecursoInput.value = value;
        updateSelectedDisplay(value, text);
    });

    function updateSelectedDisplay(value, text) {
        const iconMap = {
            'word': 'fas fa-file-word text-primary',
            'excel': 'fas fa-file-excel text-success',
            'powerpoint': 'fas fa-file-powerpoint text-warning',
            'pdf': 'fas fa-file-pdf text-danger',
            'docs': 'fab fa-google-drive text-primary',
            'forms': 'fas fa-wpforms text-success',
            'drive': 'fab fa-google-drive text-warning',
            'imagen': 'fas fa-image text-info',
            'video': 'fas fa-video text-dark',
            'audio': 'fas fa-music text-purple',
            'youtube': 'fab fa-youtube text-danger',
            'zoom': 'fas fa-video text-primary',
            'meet': 'fas fa-video text-success',
            'teams': 'fas fa-users text-info',
            'kahoot': 'fas fa-gamepad text-info',
            'canva': 'fas fa-palette text-pink',
            'enlace': 'fas fa-link text-secondary',
            'archivos-adjuntos': 'fas fa-paperclip text-muted'
        };

        const iconClass = iconMap[value] || 'fas fa-file text-muted';
        
        selectedDisplay.innerHTML = `
            <i class="${iconClass} fa-2x me-3"></i>
            <div>
                <h6 class="mb-1">${text}</h6>
                <small class="text-muted">Tipo seleccionado</small>
            </div>
        `;
    }

    // Manejo de upload de archivos
    fileInput.addEventListener('change', function(e) {
        handleFileSelect(e.target.files[0]);
    });

    // Drag and drop
    if (uploadArea) {
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelect(files[0]);
            }
        });
    }

    function handleFileSelect(file) {
        if (file) {
            uploadDisplay.classList.add('d-none');
            fileInfo.classList.remove('d-none');

            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
        }
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';

        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));

        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Validación del formulario
    resourceForm.addEventListener('submit', function(e) {
        const titulo = document.getElementById('tituloRecurso-' + modalId).value.trim();
        const descripcion = document.getElementById('descripcionRecurso-' + modalId).value.trim();
        const tipoRecurso = tipoRecursoInput.value;

        let errors = [];

        if (!titulo) {
            errors.push('El título es obligatorio');
        }

        if (!descripcion) {
            errors.push('La descripción es obligatoria');
        }

        if (!tipoRecurso) {
            errors.push('Debe seleccionar un tipo de recurso');
        }

        if (errors.length > 0) {
            e.preventDefault();
            showAlert('Por favor corrige los siguientes errores:\n\n' + errors.join('\n'), 'danger');
            return;
        }

        // Mostrar estado de carga
        const submitBtn = this.querySelector('[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
        submitBtn.disabled = true;

        // Restaurar después de 5 segundos (en caso de error)
        setTimeout(() => {
            submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Guardar Cambios';
            submitBtn.disabled = false;
        }, 5000);
    });

    function showAlert(message, type) {
        // Implementar sistema de alertas según tu framework
        alert(message);
    }

    // Limpiar formulario al cerrar el modal
    const modalElement = document.getElementById('modalEditarRecurso-' + modalId);
    modalElement.addEventListener('hidden.bs.modal', function() {
        resourceForm.reset();
        if (uploadDisplay) uploadDisplay.classList.remove('d-none');
        if (fileInfo) fileInfo.classList.add('d-none');
        
        // Restaurar botón submit
        const submitBtn = resourceForm.querySelector('[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Guardar Cambios';
        submitBtn.disabled = false;
    });
});
</script>
