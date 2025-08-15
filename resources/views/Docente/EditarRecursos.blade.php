




@section('content')
<div class="container-fluid py-4">
    <!-- Header con navegación -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Modificar Recurso</h1>
            <p class="text-muted mb-0">Actualiza la información y configuración del recurso educativo</p>
        </div>
        <button onclick="history.back()" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </button>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <!-- Formulario principal -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit text-primary me-2"></i>Información del Recurso
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" action="{{route('editarRecursosPost', encrypt($recurso->id))}}" id="resourceForm">
                        @csrf
                        <input type="hidden" value="{{$recurso->cursos_id}}" name="cursos_id">
                        <input type="hidden" value="{{$recurso->id}}" name="idRecurso">

                        <!-- Título del recurso -->
                        <div class="form-group mb-4">
                            <label for="tituloRecurso" class="form-label fw-semibold">
                                <i class="fas fa-heading text-primary me-2"></i>Título del Recurso
                            </label>
                            <input type="text"
                                   id="tituloRecurso"
                                   name="tituloRecurso"
                                   class="form-control form-control-lg @error('tituloRecurso') is-invalid @enderror"
                                   value="{{old('tituloRecurso', $recurso->nombreRecurso)}}"
                                   placeholder="Ingrese el título del recurso"
                                   required>
                            @error('tituloRecurso')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Descripción -->
                        <div class="form-group mb-4">
                            <label for="descripcionRecurso" class="form-label fw-semibold">
                                <i class="fas fa-align-left text-primary me-2"></i>Descripción del Recurso
                            </label>
                            <textarea id="descripcionRecurso"
                                      name="descripcionRecurso"
                                      rows="4"
                                      class="form-control @error('descripcionRecurso') is-invalid @enderror"
                                      placeholder="Describe el contenido y propósito del recurso"
                                      required>{{old('descripcionRecurso', $recurso->descripcionRecursos)}}</textarea>
                            @error('descripcionRecurso')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Upload de archivo -->
                        <div class="form-group mb-4">
                            <label for="archivo" class="form-label fw-semibold">
                                <i class="fas fa-upload text-primary me-2"></i>Reemplazar Archivo (Opcional)
                            </label>
                            <div class="upload-area border-2 border-dashed border-secondary rounded p-4 text-center">
                                <input type="file"
                                       id="archivo"
                                       name="archivo"
                                       class="form-control @error('archivo') is-invalid @enderror"
                                       style="display: none;"
                                       accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.jpg,.jpeg,.png,.gif,.mp4,.mp3">
                                <div id="upload-display">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                    <p class="mb-2 text-muted">Haz clic para seleccionar un archivo o arrastra y suelta</p>
                                    <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('archivo').click()">
                                        Seleccionar Archivo
                                    </button>
                                </div>
                                <div id="file-info" class="d-none">
                                    <i class="fas fa-file fa-2x text-success mb-2"></i>
                                    <p class="mb-0" id="file-name"></p>
                                    <small class="text-muted" id="file-size"></small>
                                </div>
                            </div>
                            @error('archivo')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Deja vacío si no deseas cambiar el archivo actual
                            </small>
                        </div>

                        <input type="hidden" id="tipoRecurso" name="tipoRecurso" value="{{old('tipoRecurso', $recurso->tipoRecurso ?? '')}}">
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <!-- Selector de tipo de recurso -->
            <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-icons text-primary me-2"></i>Tipo de Recurso
                    </h5>
                </div>
                <div class="card-body">
                    <div class="selected-type-display mb-3 p-3 bg-light rounded">
                        <div class="d-flex align-items-center" id="selected-display">
                            <i class="fas fa-question-circle fa-2x text-muted me-3"></i>
                            <div>
                                <h6 class="mb-1">Ninguno seleccionado</h6>
                                <small class="text-muted">Selecciona un tipo de recurso</small>
                            </div>
                        </div>
                    </div>

                    <!-- Acordeón de categorías -->
                    <div class="accordion" id="iconAccordion">
                        <!-- Documentos -->
                        <div class="accordion-item border-0 mb-2">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#documents">
                                    <i class="fas fa-file-alt text-primary me-2"></i>Documentos
                                </button>
                            </h2>
                            <div id="documents" class="accordion-collapse collapse" data-bs-parent="#iconAccordion">
                                <div class="accordion-body">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="icon-option" data-value="word" data-name="Word" data-icon="fas fa-file-word">
                                                <img src="{{asset('resources/icons/word.png')}}" alt="Word" height="40">
                                                <small>Word</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="icon-option" data-value="excel" data-name="Excel" data-icon="fas fa-file-excel">
                                                <img src="{{asset('resources/icons/excel.png')}}" alt="Excel" height="40">
                                                <small>Excel</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="icon-option" data-value="powerpoint" data-name="PowerPoint" data-icon="fas fa-file-powerpoint">
                                                <img src="{{asset('resources/icons/powerpoint.png')}}" alt="PowerPoint" height="40">
                                                <small>PowerPoint</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="icon-option" data-value="pdf" data-name="PDF" data-icon="fas fa-file-pdf">
                                                <img src="{{asset('resources/icons/pdf.png')}}" alt="PDF" height="40">
                                                <small>PDF</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Google Workspace -->
                        <div class="accordion-item border-0 mb-2">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#google">
                                    <i class="fab fa-google text-primary me-2"></i>Google Workspace
                                </button>
                            </h2>
                            <div id="google" class="accordion-collapse collapse" data-bs-parent="#iconAccordion">
                                <div class="accordion-body">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="icon-option" data-value="drive" data-name="Google Drive" data-icon="fab fa-google-drive">
                                                <img src="{{asset('resources/icons/drive.png')}}" alt="Drive" height="40">
                                                <small>Drive</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="icon-option" data-value="forms" data-name="Google Forms" data-icon="fas fa-wpforms">
                                                <img src="{{asset('resources/icons/forms.png')}}" alt="Forms" height="40">
                                                <small>Forms</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Multimedia -->
                        <div class="accordion-item border-0 mb-2">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#multimedia">
                                    <i class="fas fa-play-circle text-primary me-2"></i>Multimedia
                                </button>
                            </h2>
                            <div id="multimedia" class="accordion-collapse collapse" data-bs-parent="#iconAccordion">
                                <div class="accordion-body">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="icon-option" data-value="imagen" data-name="Imagen" data-icon="fas fa-image">
                                                <img src="{{asset('resources/icons/imagen.png')}}" alt="Imagen" height="40">
                                                <small>Imagen</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="icon-option" data-value="video" data-name="Video" data-icon="fas fa-video">
                                                <img src="{{asset('resources/icons/video.png')}}" alt="Video" height="40">
                                                <small>Video</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="icon-option" data-value="audio" data-name="Audio" data-icon="fas fa-music">
                                                <img src="{{asset('resources/icons/audio.png')}}" alt="Audio" height="40">
                                                <small>Audio</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="icon-option" data-value="youtube" data-name="YouTube" data-icon="fab fa-youtube">
                                                <img src="{{asset('resources/icons/youtube.png')}}" alt="YouTube" height="40">
                                                <small>YouTube</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Videoconferencia -->
                        <div class="accordion-item border-0 mb-2">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#video-conf">
                                    <i class="fas fa-video text-primary me-2"></i>Videoconferencia
                                </button>
                            </h2>
                            <div id="video-conf" class="accordion-collapse collapse" data-bs-parent="#iconAccordion">
                                <div class="accordion-body">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="icon-option" data-value="zoom" data-name="Zoom" data-icon="fas fa-video">
                                                <img src="{{asset('resources/icons/zoom.png')}}" alt="Zoom" height="40">
                                                <small>Zoom</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="icon-option" data-value="meet" data-name="Google Meet" data-icon="fas fa-video">
                                                <img src="{{asset('resources/icons/meet.png')}}" alt="Meet" height="40">
                                                <small>Meet</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="icon-option" data-value="teams" data-name="Microsoft Teams" data-icon="fas fa-users">
                                                <img src="{{asset('resources/icons/teams.png')}}" alt="Teams" height="40">
                                                <small>Teams</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Herramientas Educativas -->
                        <div class="accordion-item border-0 mb-2">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#educational">
                                    <i class="fas fa-graduation-cap text-primary me-2"></i>Herramientas Educativas
                                </button>
                            </h2>
                            <div id="educational" class="accordion-collapse collapse" data-bs-parent="#iconAccordion">
                                <div class="accordion-body">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="icon-option" data-value="kahoot" data-name="Kahoot" data-icon="fas fa-gamepad">
                                                <img src="{{asset('resources/icons/kahoot.png')}}" alt="Kahoot" height="40">
                                                <small>Kahoot</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="icon-option" data-value="canva" data-name="Canva" data-icon="fas fa-palette">
                                                <img src="{{asset('resources/icons/canva.png')}}" alt="Canva" height="40">
                                                <small>Canva</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Otros -->
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#others">
                                    <i class="fas fa-ellipsis-h text-primary me-2"></i>Otros
                                </button>
                            </h2>
                            <div id="others" class="accordion-collapse collapse" data-bs-parent="#iconAccordion">
                                <div class="accordion-body">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="icon-option" data-value="enlace" data-name="Enlace Web" data-icon="fas fa-link">
                                                <img src="{{asset('resources/icons/enlace.png')}}" alt="Enlace" height="40">
                                                <small>Enlace</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="icon-option" data-value="archivos-adjuntos" data-name="Archivo Adjunto" data-icon="fas fa-paperclip">
                                                <img src="{{asset('resources/icons/archivos-adjuntos.png')}}" alt="Archivos" height="40">
                                                <small>Archivos</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="card-footer bg-white border-0 pt-0">
                    <div class="d-grid gap-2">
                        <button type="submit" form="resourceForm" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Guardar Cambios
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="history.back()">
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

<style>
    .icon-option {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 12px 8px;
        border: 2px solid transparent;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
        text-decoration: none;
        color: #6c757d;
        min-height: 80px;
    }

    .icon-option:hover {
        border-color: #007bff;
        background-color: #f8f9ff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,123,255,0.15);
        color: #007bff;
    }

    .icon-option.selected {
        border-color: #007bff;
        background-color: #e7f3ff;
        color: #007bff;
    }

    .icon-option img {
        margin-bottom: 4px;
        filter: grayscale(20%);
        transition: filter 0.3s ease;
    }

    .icon-option:hover img,
    .icon-option.selected img {
        filter: grayscale(0%);
    }

    .icon-option small {
        font-size: 11px;
        font-weight: 500;
        text-align: center;
    }

    .upload-area {
        transition: all 0.3s ease;
        cursor: pointer;
        min-height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .upload-area:hover {
        border-color: #007bff !important;
        background-color: #f8f9ff;
    }

    .upload-area.dragover {
        border-color: #28a745 !important;
        background-color: #f8fff9;
    }

    .accordion-button {
        font-size: 14px;
        font-weight: 600;
        padding: 12px 16px;
    }

    .accordion-button:not(.collapsed) {
        background-color: #e7f3ff;
        color: #007bff;
    }

    .selected-type-display {
        transition: all 0.3s ease;
    }

    .card {
        border-radius: 12px;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
    }

    @media (max-width: 768px) {
        .sticky-top {
            position: relative !important;
            top: auto !important;
        }

        .col-lg-4 {
            margin-top: 20px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const iconOptions = document.querySelectorAll('.icon-option');
    const tipoRecursoInput = document.getElementById('tipoRecurso');
    const selectedDisplay = document.getElementById('selected-display');
    const fileInput = document.getElementById('archivo');
    const uploadDisplay = document.getElementById('upload-display');
    const fileInfo = document.getElementById('file-info');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    const uploadArea = document.querySelector('.upload-area');

    // Inicializar selección actual si existe
    const currentType = tipoRecursoInput.value;
    if (currentType) {
        const currentOption = document.querySelector(`[data-value="${currentType}"]`);
        if (currentOption) {
            selectResourceType(currentOption);
        }
    }

    // Manejo de selección de tipo de recurso
    iconOptions.forEach(option => {
        option.addEventListener('click', function() {
            selectResourceType(this);
        });
    });

    function selectResourceType(option) {
        // Remover selección anterior
        iconOptions.forEach(opt => opt.classList.remove('selected'));

        // Seleccionar nuevo
        option.classList.add('selected');

        // Actualizar input oculto
        const value = option.getAttribute('data-value');
        const name = option.getAttribute('data-name');
        const icon = option.getAttribute('data-icon');

        tipoRecursoInput.value = value;

        // Actualizar display
        selectedDisplay.innerHTML = `
            <i class="${icon} fa-2x text-primary me-3"></i>
            <div>
                <h6 class="mb-1">${name}</h6>
                <small class="text-muted">Tipo seleccionado</small>
            </div>
        `;

        // Cerrar acordeón después de seleccionar (opcional)
        const accordionCollapse = option.closest('.accordion-collapse');
        if (accordionCollapse) {
            const bsCollapse = new bootstrap.Collapse(accordionCollapse, {toggle: false});
            bsCollapse.hide();
        }
    }

    // Manejo de upload de archivos
    fileInput.addEventListener('change', function(e) {
        handleFileSelect(e.target.files[0]);
    });

    // Drag and drop
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
    const form = document.getElementById('resourceForm');
    form.addEventListener('submit', function(e) {
        const titulo = document.getElementById('tituloRecurso').value.trim();
        const descripcion = document.getElementById('descripcionRecurso').value.trim();
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
            alert('Por favor corrige los siguientes errores:\n\n' + errors.join('\n'));
        }
    });

    // Loading state para el botón submit
    const submitBtn = form.querySelector('[type="submit"]');
    form.addEventListener('submit', function() {
        if (this.checkValidity()) {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
            submitBtn.disabled = true;
        }
    });
});
</script>
@endsection

@include('layout')

