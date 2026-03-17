<div class="container-fluid ch-outer">
    <div class="ch-hero">
        @if($cursos->imagen)
        <div class="ch-hero-bg"
             style="background-image:url('{{ asset('storage/'.$cursos->imagen) }}')"></div>
        @endif
        <div class="ch-hero-overlay"></div>

        <div class="ch-hero-body container">
            <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">

                {{-- Título + badges --}}
                <div class="ch-hero-text">
                    {{-- Tipo pill --}}
                    <div class="ch-eyebrow">
                        @if($cursos->tipo === 'curso')
                            <i class="bi bi-book-fill"></i> Curso
                        @else
                            <i class="bi bi-calendar-event-fill"></i> Congreso
                        @endif
                        @if($cursos->formato ?? false)
                            <span class="ch-sep">·</span>
                            <i class="bi bi-globe2"></i> {{ $cursos->formato }}
                        @endif
                    </div>

                    <h1 class="ch-title">{{ $cursos->nombreCurso }}</h1>

                    {{-- Estado --}}
                    <div class="ch-badges">
                        @php
                            $estadoClass = match($cursos->estado) {
                                'Activo'                => 'ch-badge-green',
                                'Certificado Disponible'=> 'ch-badge-orange',
                                default                 => 'ch-badge-gray',
                            };
                            $estadoIcon = match($cursos->estado) {
                                'Activo'                => 'bi-check-circle-fill',
                                'Certificado Disponible'=> 'bi-patch-check-fill',
                                default                 => 'bi-pause-circle-fill',
                            };
                        @endphp
                        <span class="ch-badge {{ $estadoClass }}">
                            <i class="bi {{ $estadoIcon }}"></i>
                            {{ $cursos->estado }}
                        </span>

                        @if($cursos->duracion)
                        <span class="ch-badge ch-badge-glass">
                            <i class="bi bi-clock"></i> {{ $cursos->duracion }} horas
                        </span>
                        @endif

                        @if($cursos->tipo === 'congreso')
                        <span class="ch-badge ch-badge-glass">
                            <i class="bi bi-gift-fill"></i> Gratuito
                        </span>
                        @endif
                    </div>
                </div>

                {{-- Botón collapse --}}
                <button class="ch-collapse-btn" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#courseInfo"
                        aria-expanded="true"
                        id="chCollapseBtn">
                    <i class="bi bi-chevron-up ch-chevron-icon"></i>
                    <span class="ch-collapse-label">Ocultar detalles</span>
                </button>

            </div>
        </div>
    </div>


    <div id="courseInfo" class="collapse show">
        <div class="container ch-panel-wrap">
            <div class="ch-panel">

                {{-- ── MAIN: Descripción ── --}}
                <div class="ch-panel-main">
                    <div class="ch-card">
                        <div class="ch-card-header">
                            <div class="ch-card-icon">
                                <i class="bi bi-file-text-fill"></i>
                            </div>
                            <h5 class="ch-card-title">Descripción del Curso</h5>
                        </div>
                        <p class="ch-description">{{ $cursos->descripcionC ?? 'Sin descripción disponible.' }}</p>
                    </div>
                </div>

                {{-- ── SIDEBAR: Docente + Acciones ── --}}
                <div class="ch-panel-sidebar">

                    {{-- Docente --}}
                    <div class="ch-card ch-teacher-card">
                        <div class="ch-card-header">
                            <div class="ch-card-icon">
                                <i class="bi bi-person-video3"></i>
                            </div>
                            <h5 class="ch-card-title">Docente</h5>
                        </div>
                        @if($cursos->docente)
                        <a href="{{ route('perfil', encrypt($cursos->docente->id)) }}"
                           class="ch-teacher-link">
                            {{-- Avatar con inicial --}}
                            <div class="ch-teacher-avatar">
                                {{ strtoupper(substr($cursos->docente->name, 0, 1)) }}
                            </div>
                            <div class="ch-teacher-info">
                                <span class="ch-teacher-name">
                                    {{ $cursos->docente->name }}
                                    {{ $cursos->docente->lastname1 }}
                                    {{ $cursos->docente->lastname2 }}
                                </span>
                                <span class="ch-teacher-sub">Ver perfil completo</span>
                            </div>
                            <i class="bi bi-chevron-right ch-teacher-arrow"></i>
                        </a>
                        @else
                            <p class="text-muted mb-0" style="font-size:.85rem">Sin docente asignado</p>
                        @endif
                    </div>

                    {{-- Acciones rápidas --}}
                    <div class="ch-card ch-actions-card">
                        <div class="ch-card-header">
                            <div class="ch-card-icon ch-card-icon--orange">
                                <i class="bi bi-lightning-fill"></i>
                            </div>
                            <h5 class="ch-card-title">Acciones Rápidas</h5>
                        </div>

                        <div class="ch-action-list">

                            <a class="ch-action" href="{{ route('listacurso', encrypt($cursos->id)) }}">
                                <i class="bi bi-people-fill"></i>
                                <span>Participantes</span>
                                <i class="bi bi-chevron-right ch-action-arrow"></i>
                            </a>

                            @if($cursos->tipo == 'curso')
                                <button class="ch-action" type="button"
                                        data-bs-toggle="modal" data-bs-target="#modalHorario">
                                    <i class="bi bi-calendar3"></i>
                                    <span>Horarios</span>
                                    <i class="bi bi-chevron-right ch-action-arrow"></i>
                                </button>

                                <a class="ch-action"
                                   href="{{ route('historialAsistencias', encrypt($cursos->id)) }}">
                                    <i class="bi bi-clipboard-check-fill"></i>
                                    <span>Asistencias</span>
                                    <i class="bi bi-chevron-right ch-action-arrow"></i>
                                </a>
                            @endif

                            {{-- ── Menú Gestionar (Docente / Admin) ── --}}
                            @if($esDocente || auth()->user()->hasRole('Administrador'))
                            <div class="ch-manage-wrap">
                                <button class="ch-action ch-action--manage"
                                        type="button"
                                        id="chManageBtn"
                                        aria-expanded="false">
                                    <i class="bi bi-gear-fill"></i>
                                    <span>Gestionar Curso</span>
                                    <i class="bi bi-chevron-down ch-action-arrow ch-manage-chevron"></i>
                                </button>

                                <div class="ch-manage-menu" id="chManageMenu">

                                    <a class="ch-manage-item"
                                       href="{{ route('curso-imagenes.index', $cursos) }}">
                                        <i class="bi bi-images text-primary"></i>
                                        Imágenes de Presentación
                                    </a>

                                    {{-- FIX: Editar curso aparecía 2 veces. Unificado aquí. --}}
                                    @if($esDocente || auth()->user()->hasRole('Administrador'))
                                        <a class="ch-manage-item"
                                           href="{{ route('editarCurso', [encrypt($cursos->id)]) }}">
                                            <i class="bi bi-pencil-square" style="color:#0ea5e9"></i>
                                            Editar Curso
                                        </a>
                                    @endif

                                    @if($esDocente && $cursos->tipo == 'curso')
                                        <hr class="ch-manage-divider">
                                        <a class="ch-manage-item" href="#"
                                           data-bs-toggle="modal" data-bs-target="#modalCrearHorario">
                                            <i class="bi bi-calendar-plus-fill text-success"></i>
                                            Crear Horarios
                                        </a>
                                        <a class="ch-manage-item"
                                           href="{{ route('repF', [encrypt($cursos->id)]) }}"
                                           onclick="mostrarAdvertencia2(event)">
                                            <i class="bi bi-star-fill text-warning"></i>
                                            Calificaciones
                                        </a>
                                        <a class="ch-manage-item"
                                           href="{{ route('asistencias', [encrypt($cursos->id)]) }}">
                                            <i class="bi bi-check2-square text-success"></i>
                                            Dar Asistencia
                                        </a>
                                        <a class="ch-manage-item"
                                           href="{{ route('cursos.elementos-eliminados', encrypt($cursos->id)) }}">
                                            <i class="bi bi-trash3-fill text-danger"></i>
                                            Elementos Eliminados
                                        </a>
                                    @endif

                                    @if(auth()->user()->hasRole('Administrador') && !empty($cursos->archivoContenidodelCurso))
                                        <a class="ch-manage-item"
                                           href="{{ asset('storage/'.$cursos->archivoContenidodelCurso) }}"
                                           target="_blank">
                                            <i class="bi bi-file-earmark-pdf-fill text-danger"></i>
                                            Ver Plan del Curso
                                        </a>
                                    @endif

                                    <hr class="ch-manage-divider">

                                    @if($cursos->estado === 'Certificado Disponible')
                                        <button type="button" class="ch-manage-item"
                                                data-bs-toggle="modal" data-bs-target="#certificadoModal">
                                            <i class="bi bi-patch-check-fill text-warning"></i>
                                            Obtener Certificado
                                        </button>
                                    @endif

                                    @if($cursos->estado == 'Activo')
                                        <form action="{{ route('cursos.activarCertificados', ['id' => $cursos->id]) }}"
                                              method="POST">
                                            @csrf
                                            <button type="submit" class="ch-manage-item w-100">
                                                <i class="bi bi-patch-check text-success"></i>
                                                Activar Certificados
                                            </button>
                                        </form>
                                    @endif

                                    @if(auth()->user()->hasRole('Administrador') || $esDocente)
                                        @if(!isset($template))
                                            <a class="ch-manage-item" href="#"
                                               data-bs-toggle="modal" data-bs-target="#modalCertificado">
                                                <i class="bi bi-cloud-upload-fill text-primary"></i>
                                                Subir Plantilla de Certificado
                                            </a>
                                        @else
                                            <a class="ch-manage-item" href="#"
                                               data-bs-toggle="modal" data-bs-target="#modalEditarCertificado">
                                                <i class="bi bi-pencil-fill text-primary"></i>
                                                Actualizar Plantilla
                                            </a>
                                        @endif
                                        <a class="ch-manage-item"
                                           href="{{ route('certificados.vistaPrevia', encrypt($cursos->id)) }}"
                                           target="_blank">
                                            <i class="bi bi-eye-fill" style="color:#0ea5e9"></i>
                                            Vista Previa del Certificado
                                        </a>
                                    @endif

                                </div>{{-- /ch-manage-menu --}}
                            </div>{{-- /ch-manage-wrap --}}
                            @endif

                        </div>{{-- /ch-action-list --}}
                    </div>{{-- /ch-actions-card --}}

                </div>{{-- /ch-panel-sidebar --}}
            </div>{{-- /ch-panel --}}
        </div>
    </div>

</div>


<script>
    (function () {
        /* ── 1. Toggle text del botón collapse ── */
        const btn     = document.getElementById('chCollapseBtn');
        const panel   = document.getElementById('courseInfo');
        const label   = btn?.querySelector('.ch-collapse-label');

        if (panel && btn) {
            panel.addEventListener('show.bs.collapse',  () => {
                btn.setAttribute('aria-expanded', 'true');
                if (label) label.textContent = 'Ocultar detalles';
            });
            panel.addEventListener('hide.bs.collapse',  () => {
                btn.setAttribute('aria-expanded', 'false');
                if (label) label.textContent = 'Ver detalles';
            });
        }

        /* ── 2. Menú "Gestionar Curso" personalizado ── */
        const manageBtn  = document.getElementById('chManageBtn');
        const manageMenu = document.getElementById('chManageMenu');

        manageBtn?.addEventListener('click', function (e) {
            e.stopPropagation();
            const open = manageMenu?.classList.toggle('open');
            manageBtn.classList.toggle('open', open);
            manageBtn.setAttribute('aria-expanded', String(open));
        });

        /* Cerrar al hacer click fuera */
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.ch-manage-wrap')) {
                manageMenu?.classList.remove('open');
                manageBtn?.classList.remove('open');
                manageBtn?.setAttribute('aria-expanded', 'false');
            }
        });

    })();
</script>




<div class="modal fade" id="certificadoModal" tabindex="-1" aria-labelledby="certificadoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="certificadoModalLabel">Descarga tu Certificado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
        </div>
    </div>
</div>

{{-- Modal: Subir Nueva Plantilla --}}
<div class="modal fade" id="modalCertificado" tabindex="-1" aria-labelledby="modalCertificadoLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content shadow">
            <form action="{{ route('certificates.store', $cursos->id) }}" method="POST"
                enctype="multipart/form-data" novalidate>
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h1 class="modal-title fs-5" id="modalCertificadoLabel">
                        <i class="bi bi-file-earmark-plus me-2"></i>Subir Plantilla de Certificado
                    </h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        {{-- Frontal --}}
                        <div class="col-lg-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="card-title mb-0"><i class="bi bi-image me-2"></i>Parte Frontal</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Seleccionar archivo</label>
                                        <input type="file" name="template_front" class="form-control"
                                            accept="image/*" required onchange="previewImage(this, '#preview-front')">
                                        <div class="form-text"><i class="bi bi-info-circle me-1"></i>JPG, PNG, GIF (máx. 5MB)</div>
                                    </div>
                                    <div class="preview-container" id="preview-container-front">
                                        <div class="preview-placeholder">
                                            <i class="bi bi-cloud-upload fs-1 d-block mb-2"></i>
                                            Vista previa aparecerá aquí
                                        </div>
                                        <img id="preview-front" class="img-fluid rounded shadow-sm d-none"
                                            style="max-height:250px;" alt="Vista previa frontal" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Trasera --}}
                        <div class="col-lg-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="card-title mb-0"><i class="bi bi-image-fill me-2"></i>Parte Trasera</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Seleccionar archivo</label>
                                        <input type="file" name="template_back" class="form-control"
                                            accept="image/*" required onchange="previewImage(this, '#preview-back')">
                                        <div class="form-text"><i class="bi bi-info-circle me-1"></i>JPG, PNG, GIF (máx. 5MB)</div>
                                    </div>
                                    <div class="preview-container" id="preview-container-back">
                                        <div class="preview-placeholder">
                                            <i class="bi bi-cloud-upload fs-1 d-block mb-2"></i>
                                            Vista previa aparecerá aquí
                                        </div>
                                        <img id="preview-back" class="img-fluid rounded shadow-sm d-none"
                                            style="max-height:250px;" alt="Vista previa trasera" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-4">
                    {{-- Configuración de texto --}}
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0"><i class="bi bi-type me-2"></i>Configuración del Texto</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-palette me-1"></i>Color Primario
                                    </label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="color" name="primary_color"
                                            class="form-control form-control-color" value="#000000">
                                        <span class="text-muted small color-value">#000000</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-fonts me-1"></i>Fuente
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
                                        <input type="range" name="font_size_range" class="form-range font-size-range"
                                            min="8" max="72" value="14">
                                        <input type="number" name="font_size" class="form-control font-size-number"
                                            min="8" max="72" value="14" style="max-width:80px;">
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

{{-- Modal: Editar Plantilla --}}
@if ($cursos->certificateTemplate)
    <div class="modal fade" id="modalEditarCertificado" tabindex="-1"
        aria-labelledby="modalEditarCertificadoLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content shadow">
                <form action="{{ route('certificates.update', $cursos->id) }}" method="POST"
                    enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="modal-header bg-warning text-dark">
                        <h1 class="modal-title fs-5" id="modalEditarCertificadoLabel">
                            <i class="bi bi-pencil-square me-2"></i>Actualizar Plantilla de Certificado
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info d-flex align-items-center" role="alert">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            <div><strong>Plantillas actuales:</strong> Si no seleccionas nuevos archivos, se mantendrán los actuales.</div>
                        </div>
                        <div class="row g-4">
                            {{-- Vistas previas actuales --}}
                            <div class="col-lg-6">
                                <h6 class="fw-semibold mb-3"><i class="bi bi-eye me-2"></i>Plantilla Frontal Actual</h6>
                                <div class="text-center">
                                    <img src="{{ asset('storage/' . ($template->template_front_path ?? '')) }}"
                                        class="img-fluid rounded shadow-sm mb-2" style="max-height:150px;" alt="Frontal actual">
                                    <div class="text-muted small">Plantilla frontal en uso</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <h6 class="fw-semibold mb-3"><i class="bi bi-eye-fill me-2"></i>Plantilla Trasera Actual</h6>
                                <div class="text-center">
                                    <img src="{{ asset('storage/' . ($template->template_back_path ?? '')) }}"
                                        class="img-fluid rounded shadow-sm mb-2" style="max-height:150px;" alt="Trasera actual">
                                    <div class="text-muted small">Plantilla trasera en uso</div>
                                </div>
                            </div>
                            {{-- Nuevas plantillas --}}
                            <div class="col-lg-6">
                                <div class="card h-100 border-warning">
                                    <div class="card-header bg-warning bg-opacity-25">
                                        <h6 class="card-title mb-0"><i class="bi bi-upload me-2"></i>Nueva Parte Frontal (Opcional)</h6>
                                    </div>
                                    <div class="card-body">
                                        <input type="file" name="template_front" class="form-control mb-2"
                                            accept="image/*" onchange="previewImage(this, '#edit-preview-front')">
                                        <div class="form-text mb-3">Deja vacío para mantener la actual</div>
                                        <div class="preview-container">
                                            <div class="preview-placeholder"><i class="bi bi-cloud-upload fs-3 d-block mb-2"></i>Nueva vista previa</div>
                                            <img id="edit-preview-front" class="img-fluid rounded shadow-sm d-none" style="max-height:200px;" alt="Nueva frontal">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card h-100 border-warning">
                                    <div class="card-header bg-warning bg-opacity-25">
                                        <h6 class="card-title mb-0"><i class="bi bi-upload me-2"></i>Nueva Parte Trasera (Opcional)</h6>
                                    </div>
                                    <div class="card-body">
                                        <input type="file" name="template_back" class="form-control mb-2"
                                            accept="image/*" onchange="previewImage(this, '#edit-preview-back')">
                                        <div class="form-text mb-3">Deja vacío para mantener la actual</div>
                                        <div class="preview-container">
                                            <div class="preview-placeholder"><i class="bi bi-cloud-upload fs-3 d-block mb-2"></i>Nueva vista previa</div>
                                            <img id="edit-preview-back" class="img-fluid rounded shadow-sm d-none" style="max-height:200px;" alt="Nueva trasera">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0"><i class="bi bi-type me-2"></i>Configuración del Texto</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Color Primario</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="color" name="primary_color"
                                                class="form-control form-control-color" value="#ff6b35">
                                            <span class="text-muted small color-value">#ff6b35</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Fuente</label>
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
                                            <input type="range" name="font_size_range" class="form-range font-size-range"
                                                min="8" max="72" value="18">
                                            <input type="number" name="font_size" class="form-control font-size-number"
                                                min="8" max="72" value="18" style="max-width:80px;">
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

{{-- Modal: Horarios --}}
<div class="modal fade" id="modalHorario" tabindex="-1" aria-labelledby="modalHorarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title d-flex align-items-center" id="modalHorarioLabel">
                    <i class="bi bi-calendar3 me-2"></i>Lista de Horarios
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
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
                                    <th><i class="bi bi-calendar-day me-1"></i>Día</th>
                                    <th><i class="bi bi-clock me-1"></i>Hora Inicio</th>
                                    <th><i class="bi bi-clock-fill me-1"></i>Hora Fin</th>
                                    <th><i class="bi bi-hourglass-split me-1"></i>Duración</th>
                                    @if ($cursos->docente_id == auth()->user()->id || auth()->user()->hasRole('Administrador'))
                                        <th class="text-center"><i class="bi bi-gear me-1"></i>Acciones</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($horarios as $horario)
                                    @php
                                        $inicio  = Carbon\Carbon::parse($horario->horario->hora_inicio);
                                        $fin     = Carbon\Carbon::parse($horario->horario->hora_fin);
                                        $duracion = $inicio->diff($fin);
                                    @endphp
                                    <tr class="{{ $horario->trashed() ? 'table-warning' : '' }}">
                                        <td class="fw-medium">
                                            <span class="badge bg-light text-dark border">{{ $horario->horario->dia }}</span>
                                        </td>
                                        <td><span class="text-success fw-medium">{{ $inicio->format('h:i A') }}</span></td>
                                        <td><span class="text-danger fw-medium">{{ $fin->format('h:i A') }}</span></td>
                                        <td><small class="text-muted">{{ $duracion->h }}h {{ $duracion->i }}m</small></td>

                                        @if ($cursos->docente_id == auth()->user()->id || auth()->user()->hasRole('Administrador'))
                                            <td>
                                                <div class="d-flex gap-2 justify-content-center">
                                                    @if ($horario->trashed())
                                                        <span class="badge bg-warning text-dark">
                                                            <i class="bi bi-archive"></i> Eliminado
                                                        </span>
                                                        <form action="{{ route('horarios.restore', ['id' => $horario->id]) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('¿Restaurar este horario?')">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                                <i class="bi bi-arrow-clockwise"></i> Restaurar
                                                            </button>
                                                        </form>
                                                    @else
                                                        <button class="btn btn-sm btn-outline-primary btn-editar-horario"
                                                            data-id="{{ $horario->id }}"
                                                            data-dia="{{ $horario->horario->dia }}"
                                                            data-hora-inicio="{{ $horario->horario->hora_inicio }}"
                                                            data-hora-fin="{{ $horario->horario->hora_fin }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalEditarHorario">
                                                            <i class="bi bi-pencil"></i> Editar
                                                        </button>
                                                        <form action="{{ route('horarios.delete', ['id' => $horario->id]) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('¿Eliminar este horario? Esta acción se puede revertir.')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                <i class="bi bi-trash"></i> Eliminar
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
                    <button type="button" class="btn btn-success"
                        data-bs-toggle="modal" data-bs-target="#modalCrearHorario">
                        <i class="bi bi-plus-circle me-1"></i>Agregar Horario
                    </button>
                @endif
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal: Crear Horario --}}
<div class="modal fade" id="modalCrearHorario" tabindex="-1" aria-labelledby="modalCrearHorarioLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('horarios.store') }}" id="formCrearHorario" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCrearHorarioLabel">Agregar Horario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="curso_id" value="{{ $cursos->id }}">
                    <div class="mb-3">
                        <label for="dia" class="form-label">Día</label>
                        <select name="dia" id="dia" class="form-select">
                            @foreach (['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'] as $dia)
                                <option value="{{ $dia }}">{{ $dia }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="hora_inicio" class="form-label">Hora de Inicio</label>
                        <input type="time" name="hora_inicio" id="hora_inicio" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="hora_fin" class="form-label">Hora de Fin</label>
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

{{-- Modal: Editar Horario --}}
<div class="modal fade" id="modalEditarHorario" tabindex="-1" aria-labelledby="modalEditarHorarioLabel" aria-hidden="true">
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
                        <select name="dia" id="edit_dia" class="form-select" required>
                            @foreach (['lunes','martes','miércoles','jueves','viernes','sábado','domingo'] as $dia)
                                <option value="{{ $dia }}">{{ ucfirst($dia) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_hora_inicio" class="form-label">Hora de Inicio</label>
                        <input type="time" name="hora_inicio" id="edit_hora_inicio" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_hora_fin" class="form-label">Hora de Fin</label>
                        <input type="time" name="hora_fin" id="edit_hora_fin" class="form-control" required>
                    </div>
                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


{{-- ===== ESTILOS ===== --}}
<style>
    /* ---- Modales ---- */
    .modal-header.bg-primary { border-bottom: none; }
    .modal-footer.bg-light   { border-top: 1px solid #dee2e6; }

    /* ---- Tabla de horarios ---- */
    .table-hover tbody tr:hover { background-color: rgba(0,123,255,.05); }

    @media (max-width: 768px) {
        .modal-dialog { margin: .5rem; }
        .table-responsive { font-size: .875rem; }
        .d-flex.gap-2 { flex-direction: column; gap: .25rem !important; }
        .btn-sm { padding: .25rem .5rem; font-size: .75rem; }
    }

    /* ---- Botones ---- */
    .btn { transition: transform .2s ease, box-shadow .2s ease; }
    .btn:hover { transform: translateY(-1px); box-shadow: 0 2px 4px rgba(0,0,0,.1); }
    .table tbody tr { transition: background-color .2s ease; }

    /* ---- Sidebar: Actividades ---- */
    .activities-calendar { display: flex; flex-direction: column; gap: .75rem; }

    .activity-item {
        display: flex; align-items: center; gap: .75rem;
        padding: .75rem; background: #fff; border-radius: 12px;
        border: 1px solid #e9ecef; text-decoration: none; color: inherit;
        transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,.05);
        animation: fadeInUp .5s ease forwards; opacity: 0;
    }
    .activity-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,.1);
        border-color: var(--color-secondary, #39a6cb);
        text-decoration: none;
    }
    .activity-item.urgent {
        border-left: 4px solid #dc3545;
        background: linear-gradient(135deg, #fff 0%, #fff5f5 100%);
    }
    .activity-item:nth-child(1) { animation-delay: .1s; }
    .activity-item:nth-child(2) { animation-delay: .2s; }
    .activity-item:nth-child(3) { animation-delay: .3s; }
    .activity-item:nth-child(4) { animation-delay: .4s; }
    .activity-item:nth-child(5) { animation-delay: .5s; }

    .activity-icon {
        width: 40px; height: 40px; border-radius: 10px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 1.1rem;
        background: linear-gradient(135deg, var(--color-primary,#1a4789) 0%, var(--color-secondary,#39a6cb) 100%);
    }
    .activity-item.urgent .activity-icon {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    }
    .activity-info { flex: 1; min-width: 0; }
    .activity-title { font-size: .875rem; font-weight: 600; color: #333; margin-bottom: .25rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .activity-date  { font-size: .75rem; color: #6c757d; display: flex; align-items: center; gap: .25rem; }

    /* ---- Sidebar: Certificado ---- */
    .certificate-card {
        background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
        border-radius: 16px; padding: 1.5rem; text-align: center;
        box-shadow: 0 4px 12px rgba(255,215,0,.3); border: 2px solid #f0c800;
        position: relative; overflow: hidden;
        animation: fadeInScale .6s ease forwards;
    }
    .certificate-card::before {
        content: ''; position: absolute; top: -50%; left: -50%;
        width: 200%; height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,.3) 0%, transparent 70%);
        animation: shimmer 3s infinite;
    }
    .certificate-card.pending {
        background: linear-gradient(135deg, #e9ecef 0%, #f8f9fa 100%);
        border-color: #dee2e6; box-shadow: 0 2px 8px rgba(0,0,0,.1);
    }
    .certificate-card.pending::before { display: none; }

    .certificate-icon {
        font-size: 3rem; margin-bottom: 1rem; color: #b8860b;
        position: relative; z-index: 1; animation: pulse 2s infinite;
    }
    .certificate-card.pending .certificate-icon { color: #6c757d; animation: none; }
    .certificate-title { font-size: 1.1rem; font-weight: 700; color: #333; margin-bottom: .5rem; position: relative; z-index: 1; }
    .certificate-text  { font-size: .875rem; color: #555; margin-bottom: 1rem; position: relative; z-index: 1; }

    .btn-certificate {
        background: linear-gradient(135deg, #1a4789 0%, #055c9d 100%);
        color: #fff; border: none; border-radius: 12px;
        padding: .75rem 1.5rem; font-weight: 600; font-size: .875rem;
        transition: transform .3s ease, box-shadow .3s ease, background .3s ease;
        box-shadow: 0 4px 8px rgba(26,71,137,.3);
        position: relative; z-index: 1; width: 100%;
    }
    .btn-certificate:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(26,71,137,.4);
        background: linear-gradient(135deg, #055c9d 0%, #1a4789 100%);
        color: #fff;
    }
    .btn-certificate:active { transform: translateY(0); }

    /* ---- Keyframes ---- */
    @keyframes shimmer {
        0%,100% { transform: translate(-50%,-50%) rotate(0deg); }
        50%      { transform: translate(-30%,-30%) rotate(180deg); }
    }
    @keyframes pulse {
        0%,100% { transform: scale(1); }
        50%      { transform: scale(1.1); }
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInScale {
        from { opacity: 0; transform: scale(.9); }
        to   { opacity: 1; transform: scale(1); }
    }

    /* ---- Responsive sidebar ---- */
    @media (max-width: 992px) {
        .activity-item { padding: .5rem; }
        .activity-icon { width: 35px; height: 35px; font-size: 1rem; }
        .certificate-card { padding: 1rem; }
        .certificate-icon { font-size: 2.5rem; }
    }

    /* ---- Dropdown submenu (legacy) ---- */
    .dropdown-submenu .dropdown-menu {
        top: 0; left: 100%; margin-top: -1px;
        display: none; position: absolute;
    }
    .dropdown-submenu:hover .dropdown-menu { display: block; }
</style>