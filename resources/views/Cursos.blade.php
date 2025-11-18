@section('titulo')
    {{ $cursos->nombreCurso }}
@endsection


@php
    use BaconQrCode\Encoder\QrCode;
@endphp


@section('contentup')
    <div class="container-fluid my-4">
        <!-- Hero Section -->
        <div class="course-hero">
            <div class="course-hero-content">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div>
                        <h1 class="course-title mb-0">{{ $cursos->nombreCurso }}</h1>
                    </div>
                    <button class="collapse-toggle" type="button" data-bs-toggle="collapse"
                        data-bs-target="#course-info" aria-expanded="true" aria-controls="course-info">
                        <i class="fa fa-chevron-up me-1"></i>
                        <span class="toggle-text">Ocultar detalles</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Course Content -->
        <div id="course-info" class="collapse show">
            <!-- Teacher Card -->
            <div class="teacher-card">
                <div class="d-flex align-items-center gap-4">
                    <div class="teacher-avatar">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="teacher-info flex-grow-1">
                        <p>Docente del curso</p>
                        <h4>
                            <a href="{{ route('perfil', ['id' => encrypt($cursos->docente->id)]) }}">
                                {{ $cursos->docente ? $cursos->docente->name . ' ' . $cursos->docente->lastname1 . ' ' . $cursos->docente->lastname2 : 'N/A' }}
                            </a>
                        </h4>
                    </div>
                    <div>
                        <i class="fas fa-arrow-right text-muted"></i>
                    </div>
                </div>
            </div>

            <!-- Info Cards Grid -->
            <div class="info-grid">
                <!-- Estado -->
                <div class="info-card">
                    <div class="info-card-icon primary">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h5>Estado del Curso</h5>
                    <div class="info-card-content">
                        <span class="status-badge {{ $cursos->estado === 'Activo' ? 'active' : ($cursos->estado === 'Certificado Disponible' ? 'certified' : 'inactive') }}">
                            <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                            {{ $cursos->estado }}
                        </span>
                    </div>
                </div>

                <!-- Tipo -->
                <div class="info-card">
                    <div class="info-card-icon accent">
                        <i class="fas fa-tags"></i>
                    </div>
                    <h5>Tipo de Formación</h5>
                    <div class="info-card-content">
                        <span class="type-badge">
                            <i class="fas {{ $cursos->tipo === 'curso' ? 'fa-book' : 'fa-calendar-alt' }}"></i>
                            {{ ucfirst($cursos->tipo) }}
                        </span>
                    </div>
                </div>

                <!-- Formato (ejemplo adicional) -->
                <div class="info-card">
                    <div class="info-card-icon orange">
                        <i class="fas fa-desktop"></i>
                    </div>
                    <h5>Modalidad</h5>
                    <div class="info-card-content">
                        <span class="type-badge" style="background: var(--gradient-orange);">
                            <i class="fas fa-globe"></i>
                            {{ $cursos->formato ?? 'Virtual' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Description Card -->
            <div class="description-card">
                <h5>
                    <i class="fas fa-align-left"></i>
                    Descripción del Curso
                </h5>
                <p>{{ $cursos->descripcionC }}</p>
            </div>

            <!-- Actions Container -->
            <div class="actions-container">
                <h5 class="actions-title">
                    <i class="fas fa-bolt"></i>
                    Acciones Rápidas
                </h5>

                <div class="d-flex flex-wrap gap-3">
                    <!-- Participantes -->
                    <a class="btn-modern btn-primary-custom" href="{{ route('listacurso', encrypt($cursos->id)) }}">
                        <i class="fas fa-users"></i>
                        <span>Participantes</span>
                    </a>

                    @if ($cursos->tipo == 'curso')
                        <!-- Horarios -->
                        <button class="btn-modern btn-accent-custom" data-bs-toggle="modal" data-bs-target="#modalHorario">
                            <i class="fa fa-calendar"></i>
                            <span>Horarios</span>
                        </button>

                        <!-- Asistencias -->
                        <a href="{{ route('historialAsistencias', encrypt($cursos->id)) }}" class="btn-modern btn-primary-custom">
                            <i class="fas fa-clipboard-list"></i>
                            <span>Asistencias</span>
                        </a>
                    @endif

                    <!-- Gestionar Curso (Dropdown) -->
                    @if ($esDocente || auth()->user()->hasRole('Administrador'))
                        <div class="dropdown">
                            <button class="btn-modern btn-success-custom dropdown-toggle" type="button"
                                    id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-cog"></i>
                                <span>Gestionar Curso</span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li>
                                    <a class="dropdown-item" href="{{ route('curso-imagenes.index', $cursos) }}">
                                        <i class="fas fa-image text-primary"></i>
                                        Imágenes de Presentación
                                    </a>
                                </li>

                                @if ($esDocente)
                                    <li><hr class="dropdown-divider"></li>

                                    @if ($cursos->tipo == 'curso')
                                        <li>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                data-bs-target="#modalCrearHorario">
                                                <i class="fas fa-calendar-plus text-success"></i>
                                                Crear Horarios
                                            </a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('repF', [encrypt($cursos->id)]) }}"
                                                onclick="mostrarAdvertencia2(event)">
                                                <i class="fas fa-star text-warning"></i>
                                                Calificaciones
                                            </a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('asistencias', [encrypt($cursos->id)]) }}">
                                                <i class="fas fa-check text-success"></i>
                                                Dar Asistencia
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{ route('cursos.elementos-eliminados', encrypt($cursos->id)) }}"
                                                class="dropdown-item">
                                                <i class="fas fa-trash text-danger"></i>
                                                Ver Elementos Eliminados
                                            </a>
                                        </li>
                                    @endif

                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('editarCurso', [encrypt($cursos->id)]) }}">
                                            <i class="fas fa-edit text-info"></i>
                                            Editar Curso
                                        </a>
                                    </li>
                                @endif

                                @if (auth()->user()->hasRole('Administrador') && !empty($cursos->archivoContenidodelCurso))
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ asset('storage/' . $cursos->archivoContenidodelCurso) }}">
                                            <i class="fas fa-file-pdf text-danger"></i>
                                            Ver Plan Del Curso
                                        </a>
                                    </li>
                                @endif

                                @role('Administrador')
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('editarCurso', [encrypt($cursos->id)]) }}">
                                            <i class="fas fa-edit text-info"></i>
                                            Editar Curso
                                        </a>
                                    </li>
                                @endrole

                                <!-- Certificate Options -->
                                @if ($cursos->tipo === 'congreso')
                                    <li><hr class="dropdown-divider"></li>

                                    @if ($cursos->estado === 'Certificado Disponible')
                                        <li>
                                            <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                data-bs-target="#certificadoModal">
                                                <i class="fas fa-certificate text-warning"></i>
                                                Obtener Certificado
                                            </button>
                                        </li>
                                    @endif

                                    @if ($cursos->estado == 'Activo')
                                        <li>
                                            <form action="{{ route('cursos.activarCertificados', ['id' => $cursos->id]) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-certificate text-success"></i>
                                                    Activar Certificados
                                                </button>
                                            </form>
                                        </li>
                                    @endif
                                @endif

                                @if (auth()->user()->hasRole('Administrador') || $esDocente)
                                    @if (!isset($template))
                                        <li>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                data-bs-target="#modalCertificado">
                                                <i class="fas fa-file-upload text-primary"></i>
                                                Subir Plantilla de Certificado
                                            </a>
                                        </li>
                                    @else
                                        <li>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                data-bs-target="#modalEditarCertificado">
                                                <i class="fas fa-edit text-primary"></i>
                                                Actualizar Plantilla de Certificado
                                            </a>
                                        </li>
                                    @endif

                                    <li>
                                        <a href="{{ route('certificados.vistaPrevia', encrypt($cursos->id)) }}"
                                            class="dropdown-item" target="_blank">
                                            <i class="fas fa-eye text-info"></i>
                                            Vista Previa del Certificado
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="certificadoModal" tabindex="-1" aria-labelledby="certificadoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="certificadoModalLabel">
                        Descarga tu Certificado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal: Subir Nueva Plantilla -->
    <div class="modal fade" id="modalCertificado" tabindex="-1" aria-labelledby="modalCertificadoLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content shadow">
                <form action="{{ route('certificates.store', $cursos->id) }}" method="POST"
                    enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h1 class="modal-title fs-5" id="modalCertificadoLabel">
                            <i class="bi bi-file-earmark-plus me-2"></i>Subir Plantilla de Certificado
                        </h1>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-4">
                            <!-- Plantilla Frontal -->
                            <div class="col-lg-6">
                                <div class="card h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="card-title mb-0">
                                            <i class="bi bi-image me-2"></i>Parte Frontal del Certificado
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Seleccionar archivo</label>
                                            <input type="file" name="template_front" class="form-control"
                                                accept="image/*" required onchange="previewImage(this, '#preview-front')">
                                            <div class="form-text">
                                                <i class="bi bi-info-circle me-1"></i>
                                                Formatos soportados: JPG, PNG, GIF (máx. 5MB)
                                            </div>
                                        </div>
                                        <div class="preview-container" id="preview-container-front">
                                            <div class="preview-placeholder">
                                                <i class="bi bi-cloud-upload fs-1 d-block mb-2"></i>
                                                Vista previa aparecerá aquí
                                            </div>
                                            <img id="preview-front" class="img-fluid rounded shadow-sm d-none"
                                                style="max-height: 250px;" alt="Vista previa frontal" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Plantilla Trasera -->
                            <div class="col-lg-6">
                                <div class="card h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="card-title mb-0">
                                            <i class="bi bi-image-fill me-2"></i>Parte Trasera del Certificado
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Seleccionar archivo</label>
                                            <input type="file" name="template_back" class="form-control"
                                                accept="image/*" required onchange="previewImage(this, '#preview-back')">
                                            <div class="form-text">
                                                <i class="bi bi-info-circle me-1"></i>
                                                Formatos soportados: JPG, PNG, GIF (máx. 5MB)
                                            </div>
                                        </div>
                                        <div class="preview-container" id="preview-container-back">
                                            <div class="preview-placeholder">
                                                <i class="bi bi-cloud-upload fs-1 d-block mb-2"></i>
                                                Vista previa aparecerá aquí
                                            </div>
                                            <img id="preview-back" class="img-fluid rounded shadow-sm d-none"
                                                style="max-height: 250px;" alt="Vista previa trasera" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Configuración de Texto -->
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0">
                                    <i class="bi bi-type me-2"></i>Configuración del Texto
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-palette me-1"></i>Color Primario del Texto
                                        </label>
                                        <div class="d-flex align-items-center">
                                            <input type="color" name="primary_color"
                                                class="form-control form-control-color me-2" value="#000000"
                                                title="Seleccionar color">
                                            <span class="text-muted small" id="color-value">#000000</span>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-fonts me-1"></i>Fuente del Texto
                                        </label>
                                        <select name="font_family" class="form-select">
                                            <option value="Arial">Arial</option>
                                            <option value="Times New Roman">Times New Roman</option>
                                            <option value="Helvetica">Helvetica</option>
                                            <option value="Courier New">Courier New</option>
                                            <option value="Georgia">Georgia</option>
                                            <option value="Verdana">Verdana</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-text-paragraph me-1"></i>Tamaño de Fuente
                                        </label>
                                        <div class="input-group">
                                            <input type="range" name="font_size_range" class="form-range"
                                                min="8" max="72" value="14"
                                                oninput="updateFontSize(this.value)">
                                            <input type="number" name="font_size" class="form-control" min="8"
                                                max="72" value="14" style="max-width: 80px;">
                                            <span class="input-group-text">px</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-cloud-upload me-1"></i>Subir Plantilla
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Editar Plantilla -->
    @if ($cursos->certificateTemplate)
        <div class="modal fade" id="modalEditarCertificado" tabindex="-1" aria-labelledby="modalEditarCertificadoLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content shadow">
                    <form action="{{ route('certificates.update', $cursos->id) }}" method="POST"
                        enctype="multipart/form-data" novalidate>
                        @csrf
                        <div class="modal-header bg-warning text-dark">
                            <h1 class="modal-title fs-5" id="modalEditarCertificadoLabel">
                                <i class="bi bi-pencil-square me-2"></i>Actualizar Plantilla de Certificado
                            </h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>

                        <div class="modal-body">
                            <div class="row g-4">
                                <!-- Plantillas Actuales -->
                                <div class="col-12">
                                    <div class="alert alert-info d-flex align-items-center" role="alert">
                                        <i class="bi bi-info-circle-fill me-2"></i>
                                        <div>
                                            <strong>Plantillas actuales:</strong> Puedes mantener las plantillas existentes
                                            o
                                            subir nuevas. Si no seleccionas nuevos archivos, se mantendrán los actuales.
                                        </div>
                                    </div>
                                </div>

                                <!-- Plantillas Actuales - Vista Previa -->
                                <div class="col-lg-6">
                                    <div class="current-template">
                                        <h6 class="fw-semibold mb-3">
                                            <i class="bi bi-eye me-2"></i>Plantilla Frontal Actual
                                        </h6>
                                        <div class="text-center">
                                            <img src="{{ asset('storage/' . $template->template_front_path ?? '') }}"
                                                class="img-fluid rounded shadow-sm mb-2" style="max-height: 150px;"
                                                alt="Plantilla frontal actual" />
                                            <div class="text-muted small">Plantilla frontal en uso</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="current-template">
                                        <h6 class="fw-semibold mb-3">
                                            <i class="bi bi-eye-fill me-2"></i>Plantilla Trasera Actual
                                        </h6>
                                        <div class="text-center">
                                            <img src="{{ asset('storage/' . $template->template_back_path ?? '') }}"
                                                class="img-fluid rounded shadow-sm mb-2" style="max-height: 150px;"
                                                alt="Plantilla trasera actual" />
                                            <div class="text-muted small">Plantilla trasera en uso</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Nuevas Plantillas -->
                                <div class="col-lg-6">
                                    <div class="card h-100 border-warning">
                                        <div class="card-header bg-warning bg-opacity-25">
                                            <h6 class="card-title mb-0">
                                                <i class="bi bi-upload me-2"></i>Nueva Parte Frontal (Opcional)
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <input type="file" name="template_front" class="form-control"
                                                    accept="image/*" onchange="previewImage(this, '#edit-preview-front')">
                                                <div class="form-text">Deja vacío para mantener la plantilla actual</div>
                                            </div>
                                            <div class="preview-container">
                                                <div class="preview-placeholder">
                                                    <i class="bi bi-cloud-upload fs-3 d-block mb-2"></i>
                                                    Nueva vista previa
                                                </div>
                                                <img id="edit-preview-front" class="img-fluid rounded shadow-sm d-none"
                                                    style="max-height: 200px;" alt="Nueva vista previa frontal" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="card h-100 border-warning">
                                        <div class="card-header bg-warning bg-opacity-25">
                                            <h6 class="card-title mb-0">
                                                <i class="bi bi-upload me-2"></i>Nueva Parte Trasera (Opcional)
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <input type="file" name="template_back" class="form-control"
                                                    accept="image/*" onchange="previewImage(this, '#edit-preview-back')">
                                                <div class="form-text">Deja vacío para mantener la plantilla actual</div>
                                            </div>
                                            <div class="preview-container">
                                                <div class="preview-placeholder">
                                                    <i class="bi bi-cloud-upload fs-3 d-block mb-2"></i>
                                                    Nueva vista previa
                                                </div>
                                                <img id="edit-preview-back" class="img-fluid rounded shadow-sm d-none"
                                                    style="max-height: 200px;" alt="Nueva vista previa trasera" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Configuración de Texto Actual -->
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="card-title mb-0">
                                        <i class="bi bi-type me-2"></i>Configuración del Texto
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Color Primario del Texto</label>
                                            <div class="d-flex align-items-center">
                                                <input type="color" name="primary_color"
                                                    class="form-control form-control-color me-2" value="#ff6b35">
                                                <span class="text-muted small">#ff6b35</span>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Fuente del Texto</label>
                                            <select name="font_family" class="form-select">
                                                <option value="Arial">Arial</option>
                                                <option value="Times New Roman" selected>Times New Roman</option>
                                                <option value="Helvetica">Helvetica</option>
                                                <option value="Courier New">Courier New</option>
                                                <option value="Georgia">Georgia</option>
                                                <option value="Verdana">Verdana</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Tamaño de Fuente</label>
                                            <div class="input-group">
                                                <input type="range" name="font_size_range" class="form-range"
                                                    min="8" max="72" value="18"
                                                    oninput="updateFontSizeEdit(this.value)">
                                                <input type="number" name="font_size" class="form-control"
                                                    min="8" max="72" value="18"
                                                    style="max-width: 80px;">
                                                <span class="input-group-text">px</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-1"></i>Cancelar
                            </button>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-arrow-repeat me-1"></i>Actualizar Plantilla
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif



    <!-- Modal de Horarios -->
    <div class="modal fade" id="modalHorario" tabindex="-1" aria-labelledby="modalHorarioLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title d-flex align-items-center" id="modalHorarioLabel">
                        <i class="bi bi-calendar3 me-2"></i>
                        Lista de Horarios
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar modal"></button>
                </div>

                <div class="modal-body p-0">
                    @if ($horarios->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-x display-4 text-muted"></i>
                            <p class="mt-3 text-muted">No hay horarios registrados para este curso</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="fw-semibold">
                                            <i class="bi bi-calendar-day me-1"></i>
                                            Día
                                        </th>
                                        <th scope="col" class="fw-semibold">
                                            <i class="bi bi-clock me-1"></i>
                                            Hora Inicio
                                        </th>
                                        <th scope="col" class="fw-semibold">
                                            <i class="bi bi-clock-fill me-1"></i>
                                            Hora Fin
                                        </th>
                                        <th scope="col" class="fw-semibold">
                                            <i class="bi bi-hourglass-split me-1"></i>
                                            Duración
                                        </th>
                                        @if ($cursos->docente_id == auth()->user()->id || auth()->user()->hasRole('Administrador'))
                                            <th scope="col" class="fw-semibold text-center">
                                                <i class="bi bi-gear me-1"></i>
                                                Acciones
                                            </th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($horarios as $horario)
                                        <tr class="{{ $horario->trashed() ? 'table-warning' : '' }}">
                                            <td class="fw-medium">
                                                <span class="badge bg-light text-dark border">
                                                    {{ $horario->horario->dia }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-success fw-medium">
                                                    {{ Carbon\Carbon::parse($horario->horario->hora_inicio)->format('h:i A') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-danger fw-medium">
                                                    {{ Carbon\Carbon::parse($horario->horario->hora_fin)->format('h:i A') }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $inicio = Carbon\Carbon::parse($horario->horario->hora_inicio);
                                                    $fin = Carbon\Carbon::parse($horario->horario->hora_fin);
                                                    $duracion = $inicio->diff($fin);
                                                @endphp
                                                <small class="text-muted">
                                                    {{ $duracion->h }}h {{ $duracion->i }}m
                                                </small>
                                            </td>
                                            @if ($cursos->docente_id == auth()->user()->id || auth()->user()->hasRole('Administrador'))
                                                <td>
                                                    <div class="d-flex gap-2 justify-content-center">
                                                        @if ($horario->trashed())
                                                            <span class="badge bg-warning text-dark mb-2">
                                                                <i class="bi bi-archive"></i> Eliminado
                                                            </span>
                                                            <form
                                                                action="{{ route('horarios.restore', ['id' => $horario->id]) }}"
                                                                method="POST" class="d-inline"
                                                                onsubmit="return confirm('¿Estás seguro de que deseas restaurar este horario?');">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-outline-success"
                                                                    data-bs-toggle="tooltip" title="Restaurar horario">
                                                                    <i class="bi bi-arrow-clockwise"></i>
                                                                    Restaurar
                                                                </button>
                                                            </form>
                                                        @else
                                                            <button
                                                                class="btn btn-sm btn-outline-primary btn-editar-horario"
                                                                data-id="{{ $horario->id }}"
                                                                data-dia="{{ $horario->horario->dia }}"
                                                                data-hora-inicio="{{ $horario->horario->hora_inicio }}"
                                                                data-hora-fin="{{ $horario->horario->hora_fin }}"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modalEditarHorario"
                                                                title="Editar horario">
                                                                <i class="bi bi-pencil"></i>
                                                                Editar
                                                            </button>
                                                            <form
                                                                action="{{ route('horarios.delete', ['id' => $horario->id]) }}"
                                                                method="POST" class="d-inline"
                                                                onsubmit="return confirm('¿Estás seguro de que deseas eliminar este horario? Esta acción se puede revertir.');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-outline-danger"
                                                                    data-bs-toggle="tooltip" title="Eliminar horario">
                                                                    <i class="bi bi-trash"></i>
                                                                    Eliminar
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <div class="modal-footer bg-light">
                    @if ($cursos->docente_id == auth()->user()->id || auth()->user()->hasRole('Administrador'))
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#modalCrearHorario">
                            <i class="bi bi-plus-circle me-1"></i>
                            Agregar Horario
                        </button>
                    @endif
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Estilos adicionales -->
    <style>
        .modal-header.bg-primary {
            border-bottom: none;
        }

        .modal-footer.bg-light {
            border-top: 1px solid #dee2e6;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

        .btn-group-sm>.btn,
        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            border-radius: 0.375rem;
        }

        @media (max-width: 768px) {
            .modal-dialog {
                margin: 0.5rem;
            }

            .table-responsive {
                font-size: 0.875rem;
            }

            .d-flex.gap-2 {
                flex-direction: column;
                gap: 0.25rem !important;
            }

            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
        }

        /* Animaciones sutiles */
        .btn {
            transition: all 0.2s ease-in-out;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .table tbody tr {
            transition: background-color 0.2s ease-in-out;
        }
    </style>

    <!-- JavaScript para tooltips -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar tooltips de Bootstrap
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>

    {{-- <!-- Modal de QR -->
    <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrModalLabel">Código QR para Inscribirte</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ $qrCode }}" alt="QR Code" class="img-fluid mb-3">
                    <a href="{{ $qrCode }}" download="codigo_qr_curso.png" class="btn btn-success">
                        Descargar Código QR
                    </a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="modal fade" id="modalCrearHorario" tabindex="-1" aria-labelledby="modalCrearHorarioLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('horarios.store') }}" id="formCrearHorario" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCrearHorarioLabel">Agregar
                            Horario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="curso_id" id="curso_id" value="{{ $cursos->id }}">
                        <div class="form-group">
                            <label for="dia">Día</label>
                            <select name="dia" id="dia" class="form-control">
                                <option value="Lunes">Lunes</option>
                                <option value="Martes">Martes</option>
                                <option value="Miércoles">Miércoles</option>
                                <option value="Jueves">Jueves</option>
                                <option value="Viernes">Viernes</option>
                                <option value="Sábado">Sábado</option>
                                <option value="Domingo">Domingo</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="hora_inicio">Hora de Inicio</label>
                            <input type="time" name="hora_inicio" id="hora_inicio" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="hora_fin">Hora de Fin</label>
                            <input type="time" name="hora_fin" id="hora_fin" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Edición (fuera del bucle) -->
    <div class="modal fade" id="modalEditarHorario" tabindex="-1" aria-labelledby="modalEditarHorarioLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarHorarioLabel">Editar Horario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarHorario" action="" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="edit_dia" class="form-label">Día</label>
                            <select name="dia" id="edit_dia" class="form-control" required>
                                <option value="lunes">Lunes</option>
                                <option value="martes">Martes</option>
                                <option value="miércoles">Miércoles</option>
                                <option value="jueves">Jueves</option>
                                <option value="viernes">Viernes</option>
                                <option value="sábado">Sábado</option>
                                <option value="domingo">Domingo</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_hora_inicio" class="form-label">Hora de Inicio</label>
                            <input type="time" name="hora_inicio" id="edit_hora_inicio" class="form-control"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_hora_fin" class="form-label">Hora de Fin</label>
                            <input type="time" name="hora_fin" id="edit_hora_fin" class="form-control" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para cambiar el ícono del botón de collapse -->
    <script>
        document.getElementById('course-info').addEventListener('show.bs.collapse', function() {
            document.querySelector('[data-bs-target="#course-info"] i').className = 'fa fa-chevron-up';
        });

        document.getElementById('course-info').addEventListener('hide.bs.collapse', function() {
            document.querySelector('[data-bs-target="#course-info"] i').className = 'fa fa-chevron-down';
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Captura el evento de clic en los botones de editar
            document.querySelectorAll('.btn-editar-horario').forEach(function(button) {
                button.addEventListener('click', function() {
                    // Obtén los datos del horario desde los atributos data-*
                    const id = button.getAttribute('data-id');
                    const dia = button.getAttribute('data-dia');
                    const horaInicio = button.getAttribute('data-hora-inicio');
                    const horaFin = button.getAttribute('data-hora-fin');

                    // Asigna los valores al modal
                    document.getElementById('edit_dia').value = dia;
                    document.getElementById('edit_hora_inicio').value = horaInicio;
                    document.getElementById('edit_hora_fin').value = horaFin;

                    // Actualiza el action del formulario con la ruta correcta
                    const form = document.getElementById('formEditarHorario');
                    form.action = "{{ route('horarios.update', '') }}/" + id;
                });
            });
        });
    </script>

    <!-- Script JS -->
    <script>
        document.getElementById('toggle-button').addEventListener('click', function() {
            var courseInfo = document.getElementById('course-info');
            var icon = this.querySelector('i');

            if (courseInfo.classList.contains('minimized')) {
                courseInfo.classList.remove('minimized');
                courseInfo.classList.add('maximized');
                icon.className = 'fa fa-chevron-up';
                this.textContent = " Ocultar";
                this.prepend(icon);
            } else {
                courseInfo.classList.remove('maximized');
                courseInfo.classList.add('minimized');
                icon.className = 'fa fa-chevron-down';
                this.textContent = " Mostrar";
                this.prepend(icon);
            }
        });
    </script>
@endsection

<style>
    .dropdown-submenu .dropdown-menu {
        top: 0;
        left: 100%;
        margin-top: -1px;
        display: none;
        position: absolute;
    }

    ;

    .dropdown-submenu:hover .dropdown-menu {
        display: block;
    }
</style>


@section('content')
  @if ((auth()->user()->hasRole('Docente') && $esDocente) || (auth()->user()->hasRole('Estudiante') && $inscritos))
        <div class="container-fluid py-4">
            <div class="row">
                <!-- Navegación lateral -->
                <div class="col-lg-3">
                    <div class="course-nav sticky-top" style="top: 2rem;">
                        <h5 class="mb-3 fw-bold" style="color: var(--color-primary);">
                            <i class="fas fa-book-open me-2"></i>Contenido del Curso
                        </h5>

                        <!-- Temas y Subtemas -->
                        <a href="#temario" class="nav-link active" data-bs-toggle="tab">
                            <i class="fas fa-list-ul"></i> <span>Temario General</span>
                        </a>

                        @forelse ($temas as $index => $tema)
                            @php
                                $estaDesbloqueado =
                                    auth()->user()->hasRole('Docente') ||
                                    (auth()->user()->hasRole('Estudiante') && $tema->estaDesbloqueado($inscritos2->id));
                            @endphp

                            <!-- Tema -->
                            <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
                                href="#subtemas-{{ $tema->id }}" role="button" aria-expanded="false"
                                aria-controls="subtemas-{{ $tema->id }}">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-folder me-2"></i>
                                    <span>{{ $tema->titulo_tema }}</span>
                                </div>
                                <i class="fas fa-chevron-down transition-all"></i>
                            </a>

                            <!-- Subtemas -->
                            <div class="collapse" id="subtemas-{{ $tema->id }}">
                                @forelse($tema->subtemas as $subtema)
                                    @php
                                        $desbloqueado =
                                            auth()->user()->hasRole('Docente') ||
                                            (auth()->user()->hasRole('Estudiante') && $subtema->estaDesbloqueado($inscritos2->id));
                                    @endphp

                                    @if ($desbloqueado)
                                        <a href="#subtema-{{ $subtema->id }}" class="nav-link ms-4">
                                            <i class="fas fa-file-alt me-2"></i>
                                            <span>{{ $subtema->titulo_subtema }}</span>
                                        </a>
                                    @else
                                        <a href="#" class="nav-link ms-4 disabled">
                                            <i class="fas fa-lock me-2"></i>
                                            <span class="text-muted">{{ $subtema->titulo_subtema }}</span>
                                        </a>
                                    @endif
                                @empty
                                    <a href="#" class="nav-link ms-4 disabled">
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        <span class="text-muted">No hay subtemas disponibles</span>
                                    </a>
                                @endforelse
                            </div>
                        @empty
                            <a href="#" class="nav-link disabled">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <span>No hay temas disponibles</span>
                            </a>
                        @endforelse
                    </div>
                </div>

                <!-- Contenido principal -->
                <div class="col-lg-9">
                    <!-- Barra de progreso solo para estudiantes -->
                    @if (auth()->user()->hasRole('Estudiante'))
                        @if ($cursos->tipo == 'curso')
                            <div class="progress-container fade-in-up">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h5 class="fw-bold mb-1" style="color: var(--color-primary);">
                                            <i class="fas fa-chart-line me-2"></i>PROGRESO DEL CURSO
                                        </h5>
                                        <p class="text-muted mb-0">Tu avance en el contenido del curso</p>
                                    </div>
                                    <span class="badge bg-primary fs-6 px-3 py-2">
                                        {{ $cursos->calcularProgreso($inscritos2->id) }}%
                                    </span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{ $cursos->calcularProgreso($inscritos2->id) }}%;"
                                        aria-valuenow="{{ $cursos->calcularProgreso($inscritos2->id) }}"
                                        aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif

                    <!-- Contenido principal -->
                    <div class="course-card fade-in-up">
                        <!-- Pestañas de navegación -->
                        <div class="card-header">
                            <ul class="nav nav-tabs nav-fill" id="course-tabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active px-4 py-3" id="temario-tab" data-bs-toggle="tab"
                                        data-bs-target="#tab-actividades" type="button" role="tab"
                                        aria-controls="tab-actividades" aria-selected="true">
                                        <i class="fas fa-list me-2"></i>Temario
                                    </button>
                                </li>

                                @if ($cursos->tipo == 'congreso')
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link px-4 py-3" id="expositores-tab" data-bs-toggle="tab"
                                            data-bs-target="#tab-expositores" type="button" role="tab"
                                            aria-controls="tab-expositores" aria-selected="false">
                                            <i class="fas fa-users me-2"></i>Expositores
                                        </button>
                                    </li>
                                @endif
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link px-4 py-3" id="foros-tab" data-bs-toggle="tab"
                                        data-bs-target="#tab-foros" type="button" role="tab" aria-controls="tab-foros"
                                        aria-selected="false">
                                        <i class="fas fa-comments me-2"></i>Foros
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link px-4 py-3" id="recursos-tab" data-bs-toggle="tab"
                                        data-bs-target="#tab-recursos" type="button" role="tab"
                                        aria-controls="tab-recursos" aria-selected="false">
                                        <i class="fas fa-folder-open me-2"></i>Recursos Globales
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body p-4">
                            <div class="tab-content" id="course-tab-content">
                                <!-- Contenido de las pestañas -->
                                @include('partials.cursos.temario_tab')

                                @if ($cursos->tipo == 'congreso')
                                    <div class="tab-pane fade" id="tab-expositores">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <div>
                                                <h4 class="mb-1" style="color: var(--color-primary);">
                                                    <i class="fas fa-users me-2"></i>Expositores Asignados
                                                </h4>
                                                <p class="text-muted mb-0">Profesionales que impartirán el congreso</p>
                                            </div>
                                            <button class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#modalExpositores">
                                                <i class="fas fa-user-plus me-2"></i> Asignar Expositores
                                            </button>
                                        </div>

                                        {{-- Lista de Expositores asignados --}}
                                        <div class="row">
                                            @forelse ($cursos->expositores as $expositor)
                                                <div class="col-md-6 mb-4">
                                                    <div class="expositor-card h-100">
                                                        <div class="row g-0 h-100">
                                                            <div class="col-4">
                                                                @if ($expositor->imagen && file_exists(public_path('storage/' . $expositor->imagen)))
                                                                    <img src="{{ asset('storage/' . $expositor->imagen) }}"
                                                                        class="img-fluid h-100 w-100" style="object-fit: cover;"
                                                                        alt="Foto de {{ $expositor->nombre }}">
                                                                @else
                                                                    <img src="{{ asset('assets2/img/talker.png') }}"
                                                                        class="img-fluid h-100 w-100" style="object-fit: cover;"
                                                                        alt="Imagen no disponible">
                                                                @endif
                                                            </div>
                                                            <div class="col-8">
                                                                <div class="card-body h-100 d-flex flex-column">
                                                                    <h5 class="card-title mb-2" style="color: var(--color-primary);">
                                                                        {{ $expositor->nombre }}
                                                                    </h5>
                                                                    <div class="mb-2">
                                                                        <small class="text-muted">Cargo:</small>
                                                                        <p class="mb-1 fw-semibold">{{ $expositor->pivot->cargo ?? 'No especificado' }}</p>
                                                                    </div>
                                                                    <div class="mb-2">
                                                                        <small class="text-muted">Tema:</small>
                                                                        <p class="mb-1">{{ $expositor->pivot->tema ?? 'No especificado' }}</p>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <small class="text-muted">Orden:</small>
                                                                        <span class="badge bg-primary">{{ $expositor->pivot->orden ?? '-' }}</span>
                                                                    </div>
                                                                    @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Docente'))
                                                                        <div class="mt-auto">
                                                                            <form
                                                                                action="{{ route('cursos.quitarExpositor', [$cursos->id, $expositor->id]) }}"
                                                                                method="POST"
                                                                                onsubmit="return confirm('¿Deseas quitar este expositor del curso?');">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button class="btn btn-outline-danger btn-sm w-100"
                                                                                    title="Quitar expositor">
                                                                                    <i class="fas fa-times me-1"></i> Quitar Expositor
                                                                                </button>
                                                                            </form>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="col-12">
                                                    <div class="alert alert-info text-center py-4">
                                                        <i class="fas fa-info-circle fa-2x mb-3"></i>
                                                        <h5>No hay expositores asignados</h5>
                                                        <p class="mb-0">Asigna expositores para comenzar con el congreso</p>
                                                    </div>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                @endif

                                @include('partials.cursos.foros_tab')
                                @include('partials.cursos.recursos_tab')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Asignar Expositores -->
        <div class="modal fade" id="modalExpositores" tabindex="-1"
            aria-labelledby="modalExpositoresLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST"
                    action="{{ route('cursos.asignarExpositores', $cursos->id) }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalExpositoresLabel">
                                <i class="fas fa-user-plus me-2"></i>Asignar Expositores al Curso
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-4">
                                <input type="text" id="buscadorExpositores"
                                    class="form-control"
                                    placeholder="🔍 Buscar expositor por nombre...">
                            </div>

                            <div class="overflow-auto" style="max-height: 400px;">
                                @foreach ($expositores as $expositor)
                                    <div class="expositor-item border rounded p-3 mb-3"
                                        data-nombre="{{ strtolower($expositor->nombre) }}">
                                        <div class="d-flex align-items-center mb-2">
                                            @if ($expositor->imagen && file_exists(public_path('storage/' . $expositor->imagen)))
                                                <img src="{{ asset('storage/' . $expositor->imagen) }}"
                                                    class="rounded-circle me-3" width="50" height="50"
                                                    alt="Foto de {{ $expositor->nombre }}">
                                            @else
                                                <img src="{{ asset('assets2/img/talker.png') }}"
                                                    class="rounded-circle me-3" width="50" height="50"
                                                    alt="Imagen no disponible">
                                            @endif
                                            <div>
                                                <strong class="d-block">{{ $expositor->nombre }}</strong>
                                                <small class="text-muted">Expositor disponible</small>
                                            </div>
                                        </div>

                                        <div class="row g-2 align-items-center">
                                            <input type="hidden"
                                                name="expositores[{{ $loop->index }}][id]"
                                                value="{{ $expositor->id }}">

                                            <div class="col-md-4">
                                                <input type="text" class="form-control"
                                                    name="expositores[{{ $loop->index }}][cargo]"
                                                    placeholder="Cargo del expositor">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control"
                                                    name="expositores[{{ $loop->index }}][tema]"
                                                    placeholder="Tema a exponer">
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" class="form-control"
                                                    name="expositores[{{ $loop->index }}][orden]"
                                                    placeholder="Orden" min="1">
                                            </div>
                                            <div class="col-md-2 text-center">
                                                <div class="form-check">
                                                    <input type="checkbox"
                                                        class="form-check-input"
                                                        name="expositoresSeleccionados[]"
                                                        value="{{ $loop->index }}">
                                                    <label class="form-check-label small">Seleccionar</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Guardar Asignaciones
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modales adicionales -->
        @include('partials.cursos.modals.agregar_tema')
        @include('partials.cursos.modals.agregar_subtema')

    @else
        <!-- Acceso denegado -->
        <div class="container-fluid py-4">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="course-card text-center py-5">
                        <div class="card-body">
                            <i class="fas fa-lock fa-4x mb-4" style="color: var(--color-error);"></i>
                            <h3 class="mb-3" style="color: var(--color-primary);">Acceso Denegado</h3>
                            <p class="text-muted mb-4">No tienes permisos para acceder a este curso.</p>
                            <a href="{{ route('Inicio') }}" class="btn btn-primary">
                                <i class="fas fa-home me-2"></i>Volver al Inicio
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Errores de validación',
                    html: `
                    <div style="text-align: left; max-height: 300px; overflow-y: auto;">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                `,
                    confirmButtonColor: '#1a4789'
                });
            });
        </script>
    @endif

@endsection



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-editar-horario').forEach(button => {
            button.addEventListener('click', function() {
                // Obtener los datos del horario
                const id = this.getAttribute('data-id');
                const dia = this.getAttribute('data-dia');
                const horaInicio = this.getAttribute('data-hora-inicio');
                const horaFin = this.getAttribute('data-hora-fin');

                // Establecer los valores en el formulario
                document.getElementById('horario_id').value = id;
                document.getElementById('edit_dia').value = dia;
                document.getElementById('edit_hora_inicio').value = horaInicio;
                document.getElementById('edit_hora_fin').value = horaFin;

                // Cerrar el modal de lista usando jQuery
                $('#modalHorario').modal('hide');

                // Abrir el modal de edición
                $('#modalEditarHorario').modal('show');
            });
        });
    });
</script>

<script>
    function copiarAlPortapapeles(url) {
        // Crear un input temporal
        const inputTemp = document.createElement('input');
        inputTemp.value = url;
        document.body.appendChild(inputTemp);

        // Seleccionar el texto del input
        inputTemp.select();
        inputTemp.setSelectionRange(0, 99999); // Para dispositivos móviles

        // Copiar el texto al portapapeles
        document.execCommand('copy');

        // Eliminar el input temporal
        document.body.removeChild(inputTemp);

        // Mostrar mensaje con SweetAlert
        Swal.fire({
            icon: 'success',
            title: '¡Enlace copiado!',
            text: 'El enlace del certificado se ha copiado al portapapeles.',
            confirmButtonText: 'Aceptar',
            timer: 3000, // Cerrar automáticamente después de 3 segundos
            timerProgressBar: true, // Mostrar barra de progreso
        });
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addHorarioButton = document.getElementById('add-horario');
        if (addHorarioButton) {
            addHorarioButton.addEventListener('click', function() {
                const template = document.getElementById('horario-template').innerHTML;
                const container = document.getElementById('horarios-container');
                container.insertAdjacentHTML('beforeend', template);
            });
        }

        const container = document.getElementById('horarios-container');
        if (container) {
            container.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-horario')) {
                    const horario = e.target.closest('.horario');
                    horario.remove();
                }
            });
        }
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const collapseButton = document.querySelector('.collapse-toggle');
        const toggleText = document.querySelector('.toggle-text');
        const collapseIcon = document.querySelector('.collapse-toggle i');

        collapseButton.addEventListener('click', function() {
            if (toggleText.textContent === 'Ocultar') {
                toggleText.textContent = 'Mostrar';
                collapseIcon.classList.remove('fa-chevron-up');
                collapseIcon.classList.add('fa-chevron-down');
            } else {
                toggleText.textContent = 'Ocultar';
                collapseIcon.classList.remove('fa-chevron-down');
                collapseIcon.classList.add('fa-chevron-up');
            }
        });
    });
</script>

<script>
    function mostrarAdvertencia(event) {
        event.preventDefault();

        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Quieres Eliminar Esta Actividad. ¿Estás seguro de que deseas continuar?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirige al usuario al enlace original
                window.location.href = event.target.getAttribute('href');
            }
        });
    }

    function mostrarAdvertencia2(event) {
        event.preventDefault();

        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Quieres Descargar Los Reportes actuales del cursos. ¿Estás seguro de que deseas continuar?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirige al usuario al enlace original
                window.location.href = event.target.getAttribute('href');
            }
        });
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const buscador = document.getElementById('buscadorExpositores');
        // Verificar si el elemento existe antes de continuar
        if (buscador) {
            const items = document.querySelectorAll('.expositor-item');

            buscador.addEventListener('input', function() {
                const filtro = this.value.toLowerCase();

                items.forEach(item => {
                    const nombre = item.getAttribute('data-nombre');
                    if (nombre.includes(filtro)) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButton = document.querySelector('.collapse-toggle');
        const toggleText = document.querySelector('.toggle-text');
        const toggleIcon = toggleButton.querySelector('i');

        // Initialize Bootstrap collapse events
        const courseInfo = document.getElementById('course-info');
        courseInfo.addEventListener('hide.bs.collapse', function() {
            toggleText.textContent = 'Mostrar';
            toggleIcon.classList.remove('fa-chevron-up');
            toggleIcon.classList.add('fa-chevron-down');
        });

        courseInfo.addEventListener('show.bs.collapse', function() {
            toggleText.textContent = 'Ocultar';
            toggleIcon.classList.remove('fa-chevron-down');
            toggleIcon.classList.add('fa-chevron-up');
        });
    });
</script>


<script>
    function previewImage(input, previewSelector) {
        const file = input.files[0];
        const preview = document.querySelector(previewSelector);
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }
    }
</script>


<script>
    // Función para previsualizar imágenes
    function previewImage(input, previewSelector) {
        const preview = document.querySelector(previewSelector);
        const container = preview.closest('.preview-container');
        const placeholder = container.querySelector('.preview-placeholder');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                if (placeholder) {
                    placeholder.style.display = 'none';
                }
            }

            reader.readAsDataURL(input.files[0]);
        } else {
            preview.classList.add('d-none');
            if (placeholder) {
                placeholder.style.display = 'block';
            }
        }
    }

    // Sincronizar el rango con el input numérico
    function updateFontSize(value) {
        document.querySelector('input[name="font_size"]').value = value;
    }

    function updateFontSizeEdit(value) {
        document.querySelector('#modalEditarCertificado input[name="font_size"]').value = value;
    }

    // Actualizar el valor del color mostrado
    document.addEventListener('DOMContentLoaded', function() {
        const colorInputs = document.querySelectorAll('input[type="color"]');

        colorInputs.forEach(input => {
            const valueSpan = input.parentElement.querySelector('.text-muted');
            if (valueSpan) {
                input.addEventListener('input', function() {
                    valueSpan.textContent = this.value;
                });
            }
        });

        // Sincronizar rangos con inputs numéricos
        const fontSizeRange = document.querySelector('input[name="font_size_range"]');
        const fontSizeInput = document.querySelector('input[name="font_size"]');

        if (fontSizeRange && fontSizeInput) {
            fontSizeInput.addEventListener('input', function() {
                fontSizeRange.value = this.value;
            });
        }

        // Para el modal de edición
        const editFontSizeRange = document.querySelector(
            '#modalEditarCertificado input[name="font_size_range"]');
        const editFontSizeInput = document.querySelector('#modalEditarCertificado input[name="font_size"]');

        if (editFontSizeRange && editFontSizeInput) {
            editFontSizeInput.addEventListener('input', function() {
                editFontSizeRange.value = this.value;
            });
        }
    });
</script>


@include('layout')
