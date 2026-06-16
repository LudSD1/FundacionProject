<div class="tab-pane fade" id="tab-recursos" role="tabpanel" aria-labelledby="recursos-tab">
    <div class="tbl-card">
        <!-- Hero Section -->
        <div class="tbl-card-hero">
            <div class="tbl-hero-left">
                <div class="tbl-hero-eyebrow">
                    <i class="bi bi-folder-fill"></i> Material de Apoyo
                </div>
                <h2 class="tbl-hero-title">Recursos del Curso</h2>
                <p class="tbl-hero-sub text-white-50">
                    Accede a todo el material complementario para tu aprendizaje
                </p>
            </div>

        <div class="tbl-hero-controls">
            <!-- Filtro de Tipo -->
            <div class="tbl-hero-select-wrap">
                <i class="bi bi-filter-circle tbl-hero-select-icon"></i>
                <select class="tbl-hero-select" id="typeFilter">
                    <option value="all">Todos los tipos</option>
                    <option value="document">Documentos</option>
                    <option value="media">Multimedia</option>
                    <option value="link">Enlaces y Drive</option>
                </select>
            </div>

            <!-- Buscador -->
            <div class="tbl-hero-search">
                <i class="bi bi-search tbl-hero-search-icon"></i>
                <input type="text" class="tbl-hero-search-input" id="searchResources"
                    placeholder="Buscar recursos..." autocomplete="off">
            </div>

            @if (auth()->user()->hasRole('Docente') || auth()->user()->hasRole('Administrador'))
                <div class="d-flex gap-2 mt-2 mt-md-0">
                    <button class="tbl-hero-btn tbl-hero-btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearRecurso">
                        <i class="bi bi-plus-lg"></i>
                        <span>Nuevo Recurso</span>
                    </button>
                    <a href="{{ route('ListaRecursosEliminados', $cursos->id) }}" class="tbl-hero-btn tbl-hero-btn-danger">
                        <i class="bi bi-trash-fill"></i>
                        <span>Papelera</span>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Stats Bar -->
    <div class="tbl-filter-bar border-bottom">
        <div class="tbl-filter-bar-left d-flex flex-wrap gap-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-files text-primary"></i>
                <span><strong>{{ $recursos->count() }}</strong> Recursos totales</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-file-earmark-pdf text-danger"></i>
                <span><strong>{{ $recursos->whereIn('tipoRecurso', ['pdf', 'word', 'excel', 'powerpoint'])->count() }}</strong> Documentos</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-play-btn text-warning"></i>
                <span><strong>{{ $recursos->whereIn('tipoRecurso', ['video', 'youtube', 'audio'])->count() }}</strong> Multimedia</span>
            </div>
        </div>
    </div>

    <div class="px-3 py-4 p-md-4 bg-white">
            @if ($recursos->count() > 0)

                @php
                    $iconosRec = [
                        'pdf' => 'bi-file-earmark-pdf-fill', 'word' => 'bi-file-earmark-word-fill',
                        'excel' => 'bi-file-earmark-excel-fill', 'powerpoint' => 'bi-file-earmark-ppt-fill',
                        'video' => 'bi-play-circle-fill', 'youtube' => 'bi-youtube',
                        'imagen' => 'bi-image-fill', 'enlace' => 'bi-link-45deg',
                        'drive' => 'bi-google', 'docs' => 'bi-file-earmark-text-fill',
                        'audio' => 'bi-mic-fill', 'forms' => 'bi-ui-checks',
                        'kahoot' => 'bi-controller', 'canva' => 'bi-brush-fill',
                        'archivos-adjuntos' => 'bi-file-earmark-zip-fill',
                        'zoom' => 'bi-zoom', 'meet' => 'bi-google', 'teams' => 'bi-people'
                    ];
                    $coloresRec = [
                        'pdf' => '#ef4444', 'word' => '#2b6cb0', 'excel' => '#10b981',
                        'powerpoint' => '#f97316', 'video' => '#f59e0b', 'youtube' => '#ff0000',
                        'enlace' => '#6366f1', 'drive' => '#34a853', 'imagen' => '#8e24aa',
                        'audio' => '#00897b', 'docs' => '#4285f4', 'forms' => '#00897b',
                        'kahoot' => '#46178f', 'canva' => '#00c4cc',
                        'archivos-adjuntos' => '#5d4037',
                        'zoom' => '#0b5cff', 'meet' => '#00897b', 'teams' => '#6264a7'
                    ];
                    $categorias = [
                        'pdf' => 'document', 'word' => 'document', 'excel' => 'document', 'powerpoint' => 'document',
                        'video' => 'media', 'youtube' => 'media', 'audio' => 'media',
                        'enlace' => 'link', 'drive' => 'link', 'docs' => 'link', 'forms' => 'link',
                        'kahoot' => 'link', 'canva' => 'link', 'archivos-adjuntos' => 'document',
                        'zoom' => 'link', 'meet' => 'link', 'teams' => 'link', 'imagen' => 'media'
                    ];
                @endphp

                <!-- Lista de Recursos -->
                <div class="d-flex flex-column" id="resourcesGrid">
                    @foreach ($recursos as $recurso)
                        @php
                            $tipo = $recurso->tipoRecurso;
                            $cat = $categorias[$tipo] ?? 'document';
                            $icono = $iconosRec[$tipo] ?? 'bi-file-earmark-fill';
                            $color = $coloresRec[$tipo] ?? '#145da0';
                            $esVideoYouTube = $tipo === 'youtube';
                        @endphp

                        {{-- Extraer videoId una sola vez para YouTube --}}
                        @php
                            $videoId = '';
                            if ($esVideoYouTube) {
                                // 1. Intentar desde archivoRecurso (URL directa)
                                $rawUrl = trim($recurso->archivoRecurso ?? '');

                                // 2. Si está vacío, buscar dentro de la descripción (puede tener iframes o URLs)
                                if (empty($rawUrl)) {
                                    $rawDesc = $recurso->descripcionRecursos ?? '';
                                    // Buscar src dentro de un iframe embebido
                                    if (preg_match('/src=["\']([^"\']+youtube[^"\']+)["\']/i', $rawDesc, $srcMatch)) {
                                        $rawUrl = html_entity_decode($srcMatch[1]);
                                    } else {
                                        // Buscar URL de YouTube en texto plano
                                        $rawUrl = html_entity_decode(strip_tags($rawDesc));
                                    }
                                }

                                // 3. Extraer el video ID de cualquier formato de URL de YouTube
                                if (preg_match('/(?:youtube\.com\/(?:watch\?.*v=|embed\/|shorts\/|v\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/i', $rawUrl, $vidMatch)) {
                                    $videoId = $vidMatch[1];
                                }
                            }

                            // Texto limpio de la descripción (sin iframes ni HTML)
                            $descLimpia = trim(strip_tags($recurso->descripcionRecursos ?? ''));
                        @endphp

                        <!-- Elemento de la lista -->
                        <div class="resource-card border-bottom py-3 px-2 px-md-4 d-flex flex-column flex-md-row gap-3 align-items-md-center"
                            data-name="{{ strtolower($recurso->nombreRecurso) }}"
                            data-type="{{ $cat }}">

                            <!-- Grupo Principal: Miniatura + Texto (siempre en fila) -->
                            <div class="d-flex align-items-center gap-3 flex-grow-1" style="min-width: 0;">
                                <!-- Icono / Miniatura -->
                                <div class="flex-shrink-0 position-relative rounded-3 overflow-hidden d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background-color: #f1f5f9;">
                                    @if($esVideoYouTube && $videoId)
                                        <img src="https://img.youtube.com/vi/{{ $videoId }}/hqdefault.jpg"
                                             alt="YouTube" class="w-100 h-100"
                                             style="object-fit: cover; transform: scale(1.35);">
                                        <div class="position-absolute top-50 start-50 translate-middle">
                                            <div class="bg-danger rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 22px; height: 22px;">
                                                <i class="bi bi-play-fill text-white" style="font-size: 0.8rem; margin-left: 2px;"></i>
                                            </div>
                                        </div>
                                    @elseif($tipo === 'imagen' && $recurso->archivoRecurso)
                                        <img src="{{ asset('storage/' . $recurso->archivoRecurso) }}"
                                             alt="{{ $recurso->nombreRecurso }}" class="w-100 h-100"
                                             style="object-fit: cover;">
                                    @else
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center" style="background-color: {{ $color }}10;">
                                            <i class="bi {{ $icono }}" style="font-size: 1.6rem; color: {{ $color }};"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Detalles del recurso -->
                                <div class="flex-grow-1" style="min-width: 0;">
                                    <h6 class="fw-bold mb-1 text-dark" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $recurso->nombreRecurso }}">
                                        {{ $recurso->nombreRecurso }}
                                        <span class="badge ms-1" style="background-color: {{ $color }}15; color: {{ $color }}; font-size: 0.6rem; vertical-align: middle;">
                                            {{ ucfirst($tipo) }}
                                        </span>
                                    </h6>

                                    @if(!empty($descLimpia))
                                        <p class="text-muted small mb-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; word-break: break-word;">
                                            {{ $descLimpia }}
                                        </p>
                                    @endif

                                    <small class="text-muted" style="font-size: 0.72rem;">
                                        <i class="bi bi-calendar3 me-1"></i>{{ $recurso->created_at ? $recurso->created_at->format('d/m/Y') : '—' }}
                                    </small>
                                </div>
                            </div>

                            <!-- Acciones -->
                            <div class="d-flex align-items-center gap-2 flex-shrink-0 ms-md-auto">
                                @if($esVideoYouTube && $videoId)
                                    <button class="btn-action-modern btn-view" onclick="window.open('https://www.youtube.com/watch?v={{ $videoId }}', '_blank')" title="Ver en YouTube">
                                        <i class="bi bi-play-fill"></i>
                                    </button>
                                @elseif($recurso->archivoRecurso)
                                    <a href="{{ route('recursos.descargar', $recurso->id) }}" class="btn-action-modern btn-download" title="Descargar">
                                        <i class="bi bi-download"></i>
                                    </a>
                                @elseif(!empty($descLimpia))
                                    @php
                                        $esPlataformaEnlace = in_array($tipo, ['enlace','drive','docs','forms','zoom','meet','teams','canva','kahoot']);
                                        $pareceUrl = preg_match('/^(https?:\/\/|www\.)/i', $descLimpia);
                                    @endphp
                                    @if($esPlataformaEnlace || $pareceUrl)
                                        @php
                                            $urlFinal = $descLimpia;
                                            if (!preg_match('/^https?:\/\//i', $urlFinal)) {
                                                $urlFinal = 'https://' . $urlFinal;
                                            }
                                        @endphp
                                        <a href="{{ $urlFinal }}" class="btn-action-modern btn-info" target="_blank" title="Abrir enlace">
                                            <i class="bi bi-box-arrow-up-right"></i>
                                        </a>
                                    @endif
                                @endif

                                @if (auth()->user()->hasRole('Docente') || auth()->user()->hasRole('Administrador'))
                                    <button class="btn-action-modern btn-edit" data-bs-toggle="modal" data-bs-target="#modalEditarRecurso-{{ $recurso->id }}" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form class="d-inline ntf-form-delete-rec m-0 p-0" action="{{ route('quitarRecurso', $recurso->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-action-modern btn-delete" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- No results message -->
                <div id="noResourcesResults" class="empty-state-table py-5 d-none text-center">
                    <div class="empty-icon-table">
                        <i class="bi bi-search"></i>
                    </div>
                    <h5 class="empty-title-table">No se encontraron recursos</h5>
                    <p class="empty-text-table">Intenta ajustar tu búsqueda o el filtro de tipo.</p>
                    <button class="tbl-hero-btn tbl-hero-btn-primary mt-3" style="width: auto;" onclick="resetRecursoFilters()">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        Ver todos los recursos
                    </button>
                </div>
            @else
                <div class="empty-state-table py-5 text-center bg-white rounded-4 border shadow-sm my-4">
                    <div class="empty-icon-table" style="font-size: 4rem; color: #cbd5e1; margin-bottom: 1rem;">
                        <i class="bi bi-folder-x"></i>
                    </div>
                    <h5 class="empty-title-table fw-bold text-secondary">No hay recursos disponibles</h5>
                    <p class="empty-text-table text-muted">El instructor aún no ha subido material de apoyo para este curso.</p>

                    @if (auth()->user()->hasRole('Docente') || auth()->user()->hasRole('Administrador'))
                        <button class="tbl-hero-btn tbl-hero-btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#modalCrearRecurso">
                            <i class="bi bi-plus-lg"></i>
                            Subir Primer Recurso
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para Crear Recurso -->
<div class="modal fade" id="modalCrearRecurso" tabindex="-1" aria-labelledby="modalCrearRecursoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <!-- Header del Modal -->
            <div class="modal-header bg-light border-bottom-0 p-4">
                <h5 class="modal-title fw-bold text-primary">
                    <i class="bi bi-plus-circle-fill me-2"></i>Nuevo Recurso
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Body del Modal -->
            <div class="modal-body p-4">
                <form id="resourceForm" action="{{ route('CrearRecursosPost', ['id' => encrypt($cursos->id)]) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Título del Recurso -->
                    <div class="mb-3">
                        <label for="fileTitle" class="form-label fw-bold text-muted small text-uppercase">
                            <i class="bi bi-fonts text-primary me-2"></i>Título del Recurso *
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-fonts text-primary"></i></span>
                            <input type="text" id="fileTitle" name="tituloRecurso" class="form-control bg-light"
                                placeholder="Ej: Guía de Estudio - Tema 1" required minlength="3" maxlength="100">
                        </div>
                    </div>

                    <!-- Descripción del Recurso -->
                    <div class="mb-3">
                        <label for="fileDescription" class="form-label fw-bold text-muted small text-uppercase">
                            <i class="bi bi-text-paragraph text-primary me-2"></i>Descripción / Enlace *
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-text-paragraph text-primary"></i></span>
                            <textarea id="fileDescription" name="descripcionRecurso" class="form-control bg-light"
                                rows="4" placeholder="Describe el contenido o pega el enlace..."
                                required minlength="10" maxlength="500"></textarea>
                        </div>
                    </div>

                    <!-- Tipo de Recurso -->
                    <div class="mb-3">
                        <label for="resourceSelect" class="form-label fw-bold text-muted small text-uppercase">
                            <i class="bi bi-tag text-primary me-2"></i>Tipo de Recurso *
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-tag text-primary"></i></span>
                            <select id="resourceSelect" name="tipoRecurso" class="form-select bg-light" required>
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
                        </div>
                    </div>

                    <!-- Selección de Archivo -->
                    <div class="mb-3">
                        <label for="fileUpload" class="form-label fw-bold text-muted small text-uppercase">
                            <i class="bi bi-paperclip text-primary me-2"></i>Seleccionar Archivo (opcional)
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-paperclip text-primary"></i></span>
                            <input type="file" id="fileUpload" name="archivo" class="form-control bg-light">
                        </div>
                        <small class="form-text text-muted">Formatos soportados: documentos, imágenes, audio, video (máx. 10MB)</small>
                    </div>
                </form>
            </div>

            <!-- Footer del Modal -->
            <div class="modal-footer border-top-0 p-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="resourceForm" class="tbl-hero-btn tbl-hero-btn-primary px-4">
                    <i class="bi bi-check-lg me-2"></i> Guardar Recurso
                </button>
            </div>
        </div>
    </div>
</div>

@foreach ($recursos as $recurso)
<!-- Modal para Editar Recurso -->
<div class="modal fade" id="modalEditarRecurso-{{ $recurso->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <!-- Header del Modal -->
            <div class="modal-header bg-light border-bottom-0 p-4">
                <h5 class="modal-title fw-bold text-primary">
                    <i class="bi bi-pencil-square me-2"></i>Editar Recurso
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Body del Modal -->
            <div class="modal-body p-4">
                <form method="POST" enctype="multipart/form-data" action="{{ route('editarRecursosPost', encrypt($recurso->id)) }}" id="resourceForm-{{ $recurso->id }}">
                    @csrf
                    <input type="hidden" value="{{ $recurso->cursos_id }}" name="cursos_id">
                    <input type="hidden" value="{{ $recurso->id }}" name="idRecurso">

                    <!-- Título del recurso -->
                    <div class="mb-3">
                        <label for="tituloRecurso-{{ $recurso->id }}" class="form-label fw-bold text-muted small text-uppercase">
                            <i class="bi bi-fonts text-primary me-2"></i>Título del Recurso *
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-fonts text-primary"></i></span>
                            <input type="text" id="tituloRecurso-{{ $recurso->id }}" name="tituloRecurso" class="form-control bg-light"
                                value="{{ old('tituloRecurso', $recurso->nombreRecurso) }}" placeholder="Ingrese el título del recurso" required>
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="mb-3">
                        <label for="descripcionRecurso-{{ $recurso->id }}" class="form-label fw-bold text-muted small text-uppercase">
                            <i class="bi bi-text-paragraph text-primary me-2"></i>Descripción / Enlace *
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-text-paragraph text-primary"></i></span>
                            <textarea id="descripcionRecurso-{{ $recurso->id }}" name="descripcionRecurso" rows="3"
                                class="form-control bg-light" placeholder="Describe el contenido o pega el enlace" required>{{ old('descripcionRecurso', $recurso->descripcionRecursos) }}</textarea>
                        </div>
                    </div>

                    <!-- Tipo de Recurso -->
                    <div class="mb-3">
                        <label for="resourceSelect-{{ $recurso->id }}" class="form-label fw-bold text-muted small text-uppercase">
                            <i class="bi bi-tag text-primary me-2"></i>Tipo de Recurso *
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-tag text-primary"></i></span>
                            <select id="resourceSelect-{{ $recurso->id }}" name="tipoRecurso" class="form-select bg-light" required>
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
                        </div>
                    </div>

                    <!-- Upload de archivo -->
                    @if($recurso->archivoRecurso)
                        <div class="mb-3 p-3 bg-light rounded-4 border">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-file-earmark text-success me-3" style="font-size: 2rem;"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">Archivo Actual</h6>
                                    <p class="mb-0 text-muted small">{{ basename($recurso->archivoRecurso) }}</p>
                                </div>
                                <a href="{{ asset('storage/' . $recurso->archivoRecurso) }}" class="btn btn-outline-success rounded-pill btn-sm" target="_blank">
                                    <i class="bi bi-download me-1"></i> Descargar
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">
                            <i class="bi bi-paperclip text-primary me-2"></i>Reemplazar Archivo (opcional)
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-paperclip text-primary"></i></span>
                            <input type="file" name="archivo" class="form-control bg-light">
                        </div>
                        <small class="form-text text-muted">Deja vacío si no deseas cambiar el archivo actual</small>
                    </div>
                </form>
            </div>

            <div class="modal-footer border-top-0 p-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="resourceForm-{{ $recurso->id }}" class="tbl-hero-btn tbl-hero-btn-primary px-4">
                    <i class="bi bi-check-lg me-2"></i> Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchResources');
        const typeFilter = document.getElementById('typeFilter');
        const resourceCards = document.querySelectorAll('.resource-card');
        const noResultsMsg = document.getElementById('noResourcesResults');

        function applyFilters() {
            const q = searchInput?.value.toLowerCase().trim() || '';
            const type = typeFilter?.value || 'all';
            let count = 0;

            resourceCards.forEach(card => {
                const name = card.getAttribute('data-name') || '';
                const cat = card.getAttribute('data-type') || '';
                const show = name.includes(q) && (type === 'all' || cat === type);
                
                // Usar classList para evitar conflicto con d-flex !important de Bootstrap
                if (show) {
                    card.classList.remove('d-none');
                    count++;
                } else {
                    card.classList.add('d-none');
                }
            });

            if (noResultsMsg) {
                if (count > 0) {
                    noResultsMsg.classList.add('d-none');
                } else {
                    noResultsMsg.classList.remove('d-none');
                }
            }
        }

        window.resetRecursoFilters = function() {
            if (searchInput) searchInput.value = '';
            if (typeFilter) typeFilter.value = 'all';
            applyFilters();
        };

        searchInput?.addEventListener('input', applyFilters);
        typeFilter?.addEventListener('change', applyFilters);

        // Delete confirmation
        document.querySelectorAll('.ntf-form-delete-rec').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const self = this;
                Swal.fire({
                    title: '¿Eliminar recurso?',
                    text: 'Esta acción enviará el recurso a la papelera.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) self.submit();
                });
            });
        });
    });
</script>

<style>
    .resource-card {
        transition: background-color 0.2s ease, transform 0.2s ease;
    }

    .resource-card:hover {
        background-color: rgba(20, 93, 160, 0.03) !important;
    }

    /* Eliminar el borde inferior del último elemento de la lista */
    .resource-card:last-child {
        border-bottom: none !important;
    }
</style>
