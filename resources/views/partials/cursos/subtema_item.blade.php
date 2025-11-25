<div class="subtema-content">
    <div class="subtema-header">
        <div class="subtema-hero">
            @if ($subtema->imagen)
                <div class="subtema-hero-image">
                    <img src="{{ asset('storage/' . $subtema->imagen) }}" alt="Imagen del subtema" class="hero-img">
                    <div class="hero-overlay"></div>
                </div>
            @endif

            <div class="subtema-title-section {{ $subtema->imagen ? 'with-hero' : '' }}">
                <h1 class="subtema-title">{{ $subtema->titulo_subtema }}</h1>

                <!-- Descripción Colapsable Mejorada -->
                <div class="description-section">
                    <button class="description-toggle" type="button" data-bs-toggle="collapse"
                        data-bs-target="#descripcionSubtema-{{ $subtema->id }}" aria-expanded="false">
                        <div class="toggle-content">
                            <i class="fas fa-info-circle"></i>
                            <span>Descripción del Subtema</span>
                        </div>
                        <i class="toggle-arrow fas fa-chevron-down"></i>
                    </button>
                    <div class="collapse" id="descripcionSubtema-{{ $subtema->id }}">
                        <div class="description-content">
                            {!! nl2br(e($subtema->descripcion)) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalEditarSubtema-{{ $subtema->id }}" tabindex="-1"
            aria-labelledby="modalEditarSubtemaLabel-{{ $subtema->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-gradient-success text-white">
                        <div class="modal-title-section">
                            <i class="fas fa-edit fa-lg me-3"></i>
                            <div>
                                <h5 class="modal-title mb-0" id="modalEditarSubtemaLabel-{{ $subtema->id }}">Editar
                                    Subtema</h5>
                                <small class="opacity-75">{{ $subtema->titulo_subtema }}</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <form method="POST" action="{{ route('subtemas.update', encrypt($subtema->id)) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="titulo" class="form-label">Título del Subtema *</label>
                                        <input type="text" class="form-control form-control-modern" name="titulo"
                                            value="{{ $subtema->titulo_subtema }}" required
                                            placeholder="Ingresa el título del subtema">
                                    </div>
                                    <div class="form-group">
                                        <label for="descripcion" class="form-label">Descripción</label>
                                        <textarea class="form-control form-control-modern" name="descripcion" rows="4"
                                            placeholder="Describe el contenido de este subtema...">{{ $subtema->descripcion }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="image-upload-section">
                                        <label class="form-label">Imagen del Subtema</label>
                                        <div class="current-image-container">
                                            @if ($subtema->imagen)
                                                <img src="{{ asset('storage/' . $subtema->imagen) }}"
                                                    class="current-image-preview" alt="Imagen actual">
                                                <small class="text-muted d-block text-center mt-2">Imagen actual</small>
                                            @else
                                                <div class="no-image-placeholder">
                                                    <i class="fas fa-image fa-3x text-muted mb-3"></i>
                                                    <small class="text-muted">No hay imagen cargada</small>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="file-upload-wrapper mt-3">
                                            <input type="file" class="form-control form-control-modern"
                                                name="imagen" accept="image/*"
                                                id="subtemaImageUpload-{{ $subtema->id }}">
                                            <label for="subtemaImageUpload-{{ $subtema->id }}"
                                                class="file-upload-label">
                                                <i class="fas fa-upload me-2"></i>Cambiar Imagen
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i> Cancelar
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if (auth()->user()->hasRole('Docente') && $cursos->docente_id == auth()->user()->id)
            <div class="subtema-actions">
                <div class="action-buttons">
                    <!-- Agregar Actividad -->
                    <button class="btn btn-success btn-action" data-bs-toggle="modal"
                        data-bs-target="#modalActividad-{{ $subtema->id }}">
                        <i class="fas fa-tasks"></i>
                        <span>Agregar Actividad</span>
                    </button>

                    <!-- Agregar Recurso -->
                    <button class="btn btn-primary btn-action" data-bs-toggle="modal"
                        data-bs-target="#modalRecurso-{{ $subtema->id }}">
                        <i class="fas fa-file-alt"></i>
                        <span>Agregar Recurso</span>
                    </button>

                    <!-- Separador opcional -->
                    <div class="actions-divider d-none d-md-block"></div>

                    <!-- Editar -->
                    <button class="btn btn-outline-primary btn-action" data-bs-toggle="modal"
                        data-bs-target="#modalEditarSubtema-{{ $subtema->id }}">
                        <i class="fas fa-edit"></i>
                        <span>Editar</span>
                    </button>

                    <!-- Eliminar -->
                    <form class="d-inline" action="{{ route('subtemas.delete', encrypt($subtema->id)) }}"
                        method="POST"
                        onsubmit="return confirm('¿Estás seguro de eliminar este subtema y todo su contenido?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-action">
                            <i class="fas fa-trash"></i>
                            <span>Eliminar</span>
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <!-- Sección de Recursos -->
        <div class="resources-section">
            <div class="section-header">
                <div class="section-title">
                    <i class="fas fa-folder-open"></i>
                    <h3>Recursos de Aprendizaje</h3>
                </div>
                <span class="badge bg-primary">{{ count($subtema->recursos) }} recursos</span>
            </div>

            <div class="info-grid">
                @forelse($subtema->recursos as $recurso)
                    <div class="info-card" data-resource-type="{{ $recurso->tipoRecurso }}">
                        <div class="info-card-icon accent">
                            <i class="{{ $iconos[$recurso->tipoRecurso] ?? 'fas fa-file' }}"></i>
                        </div>
                        <h5>{{ $recurso->nombreRecurso }}</h5>
                        <span class="badge bg-secondary">{{ ucfirst($recurso->tipoRecurso) }}</span>

                        <div class="mt-2">
                            @if (Str::contains($recurso->descripcionRecursos, ['<iframe', '<video', '<img']))
                                <div class="media-container">
                                    {!! $recurso->descripcionRecursos !!}
                                </div>
                            @else
                                <p class="mb-2">{!! nl2br(e($recurso->descripcionRecursos)) !!}</p>
                            @endif
                        </div>

                        <div class="d-flex align-items-center gap-2 mt-2">
                            <div class="student-actions">
                                @if ($recurso->archivoRecurso)
                                    <a href="{{ asset('storage/' . $recurso->archivoRecurso) }}"
                                        class="btn-action btn-action-view" target="_blank">
                                        <i class="fas fa-download"></i> Descargar
                                    </a>
                                @endif

                                @if (auth()->user()->hasRole('Estudiante'))
                                    @if ($recurso->isViewedByInscrito($inscritos2->id))
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i> Visto
                                        </span>
                                    @else
                                        <form method="POST"
                                            action="{{ route('recurso.marcarVisto', encrypt($recurso->id)) }}">
                                            @csrf
                                            <input type="hidden" name="inscritos_id" value="{{ $inscritos2->id }}">
                                            <button type="submit" class="btn-action btn-action-check">
                                                <i class="fas fa-check-circle"></i> Marcar como visto
                                            </button>
                                        </form>
                                    @endif
                            </div>
                @endif

                @if (auth()->user()->hasRole('Docente') && $cursos->docente_id == auth()->user()->id)
                    <div class="teacher-actions d-inline-flex gap-1">
                        <button class="btn-action btn-action-view" data-bs-toggle="modal"
                            data-bs-target="#modalEditarRecurso-{{ $recurso->id }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="{{ route('eliminarRecursosSubtemaPost', encrypt($recurso->id)) }}"
                            method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este recurso?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-action-delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>

                    <div class="modal fade" id="modalEditarRecurso-{{ $recurso->id }}" tabindex="-1"
                        aria-labelledby="modalEditarRecursoLabel-{{ $recurso->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalEditarRecursoLabel-{{ $recurso->id }}">Editar
                                        Recurso
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST"
                                        action="{{ route('editarRecursosSubtemaPost', encrypt($recurso->id)) }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label class="form-label">Título del Recurso *</label>
                                            <input type="text" name="tituloRecurso"
                                                class="form-control form-control-modern"
                                                value="{{ $recurso->nombreRecurso }}" required maxlength="255">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Descripción *</label>
                                            <textarea name="descripcionRecurso" class="form-control form-control-modern" rows="4" required>{{ $recurso->descripcionRecursos }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Tipo de Recurso</label>
                                            <select name="tipoRecurso" class="form-select">
                                                <option value="">Seleccionar tipo...</option>
                                                <option value="word"
                                                    {{ $recurso->tipoRecurso === 'word' ? 'selected' : '' }}>
                                                    Word</option>
                                                <option value="excel"
                                                    {{ $recurso->tipoRecurso === 'excel' ? 'selected' : '' }}>
                                                    Excel</option>
                                                <option value="powerpoint"
                                                    {{ $recurso->tipoRecurso === 'powerpoint' ? 'selected' : '' }}>
                                                    PowerPoint</option>
                                                <option value="pdf"
                                                    {{ $recurso->tipoRecurso === 'pdf' ? 'selected' : '' }}>
                                                    PDF</option>
                                                <option value="docs"
                                                    {{ $recurso->tipoRecurso === 'docs' ? 'selected' : '' }}>
                                                    Google Docs</option>
                                                <option value="imagen"
                                                    {{ $recurso->tipoRecurso === 'imagen' ? 'selected' : '' }}>
                                                    Imagen</option>
                                                <option value="video"
                                                    {{ $recurso->tipoRecurso === 'video' ? 'selected' : '' }}>
                                                    Video</option>
                                                <option value="audio"
                                                    {{ $recurso->tipoRecurso === 'audio' ? 'selected' : '' }}>
                                                    Audio</option>
                                                <option value="youtube"
                                                    {{ $recurso->tipoRecurso === 'youtube' ? 'selected' : '' }}>
                                                    YouTube</option>
                                                <option value="forms"
                                                    {{ $recurso->tipoRecurso === 'forms' ? 'selected' : '' }}>
                                                    Google Forms</option>
                                                <option value="drive"
                                                    {{ $recurso->tipoRecurso === 'drive' ? 'selected' : '' }}>
                                                    Google Drive</option>
                                                <option value="kahoot"
                                                    {{ $recurso->tipoRecurso === 'kahoot' ? 'selected' : '' }}>
                                                    Kahoot</option>
                                                <option value="canva"
                                                    {{ $recurso->tipoRecurso === 'canva' ? 'selected' : '' }}>
                                                    Canva</option>
                                                <option value="enlace"
                                                    {{ $recurso->tipoRecurso === 'enlace' ? 'selected' : '' }}>
                                                    Enlace externo</option>
                                                <option value="archivos-adjuntos"
                                                    {{ $recurso->tipoRecurso === 'archivos-adjuntos' ? 'selected' : '' }}>
                                                    Archivos comprimidos</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Archivo (opcional)</label>
                                            @if ($recurso->archivoRecurso)
                                                <div class="mb-2">
                                                    <a href="{{ asset('storage/' . $recurso->archivoRecurso) }}"
                                                        target="_blank" class="btn btn-sm btn-outline-secondary">
                                                        <i class="fas fa-download me-1"></i> Descargar
                                                        archivo actual
                                                    </a>
                                                </div>
                                            @endif
                                            <input type="file" name="archivo" class="form-control">
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox"
                                                    name="eliminarArchivo" id="eliminarArchivo-{{ $recurso->id }}"
                                                    value="1">
                                                <label class="form-check-label"
                                                    for="eliminarArchivo-{{ $recurso->id }}">Eliminar
                                                    archivo actual</label>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end gap-2">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-save me-1"></i> Guardar Cambios
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="empty-state">
            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
            <h5>No hay recursos disponibles</h5>
            <p class="text-muted">Aún no se han agregado recursos a este subtema.</p>
        </div>
        @endforelse
    </div>
</div>

<div class="resources-section">
    <div class="section-header">
        <div class="section-title">
            <i class="fas fa-tasks"></i>
            <h3>Actividades de Evaluación</h3>
        </div>
        <span class="badge bg-primary">{{ count($subtema->actividades) }} actividades</span>
    </div>

    <div class="activities-list">
        @forelse($subtema->actividades as $actividad)
            <div class="card-modern">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="card-title">
                                <i class="fas fa-tasks me-2"></i>{{ $actividad->titulo }}
                            </h5>
                            <p class="text-muted small mb-1">
                                <i class="far fa-calendar-alt me-1"></i>
                                Publicado: {{ $actividad->created_at->format('d/m/Y') }}
                            </p>
                            <p class="text-muted small">
                                <i class="far fa-clock me-1"></i>
                                Vence:
                                {{ $actividad->fecha_limite ? $actividad->fecha_limite->format('d/m/Y') : 'Sin fecha límite' }}
                            </p>
                            <p class="text-muted small">
                                <i class="fas fa-tag me-1"></i>
                                Tipo: {{ $actividad->tipoActividad->nombre }}
                            </p>
                        </div>
                        <span class="badge bg-primary">{{ $actividad->tipoActividad->nombre }}</span>
                    </div>

                    <div class="mt-3">
                        <!-- Botón para ver la actividad -->

                        @if ($actividad->tiposEvaluacion->contains('nombre', 'Cuestionario'))
                            @role('Docente')
                                <button class="btn btn-sm btn-outline-secondary me-2" data-bs-toggle="modal"
                                    data-bs-target="#modalCuestionario-{{ $actividad->id }}">
                                    @if ($actividad->cuestionario)
                                        <i class="fas fa-edit me-1"></i> Editar Cuestionario
                                    @else
                                        <i class="fas fa-plus me-1"></i> Crear Cuestionario
                                    @endif
                                </button>
                                @if ($actividad->cuestionario)
                                    <a href="{{ route('cuestionarios.index', encrypt($actividad->cuestionario->id)) }}"
                                        class="btn btn-sm btn-outline-secondary me-2">
                                        <i class="fas fa-cog me-1"></i> Administrar
                                    </a>

                                    <a href="{{ route('rankingQuizz', encrypt($actividad->cuestionario->id)) }}"
                                        class="btn btn-sm btn-outline-secondary me-2">
                                        <i class="fas fa-chart-bar me-1"></i> Ver Resultados
                                    </a>
                                @endif



                                <!-- Modal para Crear/Editar Cuestionario -->
                                <div class="modal fade" id="modalCuestionario-{{ $actividad->id }}" tabindex="-1"
                                    aria-labelledby="modalCuestionarioLabel-{{ $actividad->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalCuestionarioLabel-{{ $actividad->id }}">
                                                    @if ($actividad->cuestionario)
                                                        Editar Cuestionario
                                                    @else
                                                        Crear Cuestionario
                                                    @endif
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Cerrar"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST"
                                                    action="{{ $actividad->cuestionario ? route('cuestionarios.update', encrypt($actividad->cuestionario->id)) : route('cuestionarios.store', encrypt($actividad->id)) }}">
                                                    @csrf
                                                    @if ($actividad->cuestionario)
                                                        @method('PUT')
                                                    @endif

                                                    <!-- Mostrar Resultados -->
                                                    <div class="mb-3">
                                                        <label for="mostrar_resultados" class="form-label">Mostrar
                                                            Resultados</label>
                                                        <select name="mostrar_resultados" class="form-select" required>
                                                            <option value="1"
                                                                {{ $actividad->cuestionario && $actividad->cuestionario->mostrar_resultados ? 'selected' : '' }}>
                                                                Sí</option>
                                                            <option value="0"
                                                                {{ $actividad->cuestionario && !$actividad->cuestionario->mostrar_resultados ? 'selected' : '' }}>
                                                                No</option>
                                                        </select>
                                                    </div>

                                                    <!-- Número Máximo de Intentos -->
                                                    <div class="mb-3">
                                                        <label for="max_intentos" class="form-label">Número Máximo
                                                            de
                                                            Intentos</label>
                                                        <input type="number" name="max_intentos" class="form-control"
                                                            value="{{ $actividad->cuestionario ? $actividad->cuestionario->max_intentos : 3 }}"
                                                            min="1" required>
                                                    </div>

                                                    <!-- Tiempo Límite -->
                                                    <div class="mb-3">
                                                        <label for="tiempo_limite" class="form-label">Tiempo
                                                            Límite
                                                            (en
                                                            minutos)</label>
                                                        <input type="number" name="tiempo_limite" class="form-control"
                                                            value="{{ $actividad->cuestionario ? $actividad->cuestionario->tiempo_limite : '' }}"
                                                            min="1" placeholder="Opcional">
                                                    </div>

                                                    <button type="submit" class="btn btn-success">
                                                        @if ($actividad->cuestionario)
                                                            Guardar Cambios
                                                        @else
                                                            Crear Cuestionario
                                                        @endif
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if ($actividad->es_publica)
                                    <form method="POST"
                                        action="{{ route('actividades.ocultar', encrypt($actividad->id)) }}"
                                        class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-eye-slash"></i> Ocultar
                                        </button>
                                    </form>
                                @else
                                    <form method="POST"
                                        action="{{ route('actividades.mostrar', encrypt($actividad->id)) }}"
                                        class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-eye"></i> Mostrar
                                        </button>
                                    </form>
                                @endif
                            @endrole
                            @role('Estudiante')
                                @if ($actividad->cuestionario)
                                    <a href="{{ route('cuestionario.mostrar', encrypt($actividad->cuestionario->id)) }}"
                                        class="btn btn-sm btn-primary me-2">
                                        <i class="fas fa-play me-1"></i> Responder
                                    </a>

                                    <a href="{{ route('rankingQuizz', encrypt($actividad->cuestionario->id)) }}"
                                        class="btn btn-sm btn-outline-secondary me-2">
                                        <i class="fas fa-chart-bar me-1"></i> Ver Resultados
                                    </a>
                                @endif
                            @endrole
                        @else
                            @hasrole('Docente')
                                <a href="{{ route('calificarT', encrypt($actividad->id)) }}"
                                    class="btn btn-sm btn-outline-info me-2">
                                    <i class="fas fa-calculator"></i> Calificar Tarea
                                </a>
                                @if ($actividad->es_publica)
                                    <form method="POST"
                                        action="{{ route('actividades.ocultar', encrypt($actividad->id)) }}"
                                        class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-eye-slash"></i> Ocultar
                                        </button>
                                    </form>
                                @else
                                    <form method="POST"
                                        action="{{ route('actividades.mostrar', encrypt($actividad->id)) }}"
                                        class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-eye"></i> Mostrar
                                        </button>
                                    </form>
                                @endif
                            @endhasrole

                            @role('Estudiante')
                                <a href="{{ route('actividad.show', encrypt($actividad->id)) }}"
                                    class="btn btn-sm btn-primary me-2">
                                    <i class="fas fa-eye me-1"></i> Ver Actividad
                                </a>
                            @endrole
                        @endif
                        @role('Estudiante')
                            <!-- Opciones para estudiantes -->
                            @if ($actividad->isCompletedByInscrito($inscritos2->id))
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i> Completada
                                </span>
                            @else
                                <form method="POST" action="{{ route('actividad.completar', encrypt($actividad->id)) }}"
                                    class="d-inline">
                                    @csrf
                                    <input type="hidden" name="inscritos_id" value="{{ $inscritos2->id }}">
                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-check-circle me-1"></i> Marcar como completada
                                    </button>
                                </form>
                            @endif
                        @endrole
                        <!-- Opciones para docentes -->
                        @if (auth()->user()->hasRole('Docente'))
                            <a href="#" class="btn btn-sm btn-outline-info me-2" data-bs-toggle="modal"
                                data-bs-target="#modalEditarActividad-{{ $actividad->id }}">
                                <i class="fas fa-edit"></i> Editar
                            </a>

                            <div class="modal fade" id="modalEditarActividad-{{ $actividad->id }}" tabindex="-1"
                                aria-labelledby="modalEditarActividadLabel-{{ $actividad->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered"> {{-- Modal más grande para mejor UX --}}
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"
                                                id="modalEditarActividadLabel-{{ $actividad->id }}">
                                                Editar Actividad: {{ $actividad->titulo }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body">
                                            {{-- ✅ Mostrar errores de validación --}}
                                            @if ($errors->any())
                                                <div class="alert alert-danger">
                                                    <ul class="mb-0">
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            <form method="POST"
                                                action="{{ route('actividades.update', encrypt($actividad->id)) }}"
                                                enctype="multipart/form-data"
                                                id="formEditarActividad-{{ $actividad->id }}">
                                                @csrf
                                                @method('PUT')

                                                {{-- Título de la Actividad --}}
                                                <div class="mb-3">
                                                    <label for="titulo-{{ $actividad->id }}"
                                                        class="form-label">Título de la Actividad *</label>
                                                    <input type="text" name="titulo"
                                                        id="titulo-{{ $actividad->id }}"
                                                        class="form-control @error('titulo') is-invalid @enderror"
                                                        value="{{ old('titulo', $actividad->titulo) }}" required
                                                        maxlength="255">
                                                    @error('titulo')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                {{-- Descripción --}}
                                                <div class="mb-3">
                                                    <label for="descripcion-{{ $actividad->id }}"
                                                        class="form-label">Descripción</label>
                                                    <textarea name="descripcion" id="descripcion-{{ $actividad->id }}"
                                                        class="form-control @error('descripcion') is-invalid @enderror" rows="3">{{ old('descripcion', $actividad->descripcion) }}</textarea>
                                                    @error('descripcion')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                {{-- Fechas en una fila --}}
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <label for="fecha_inicio-{{ $actividad->id }}"
                                                            class="form-label">Fecha de Habilitación</label>
                                                        <input type="date" name="fecha_inicio"
                                                            id="fecha_inicio-{{ $actividad->id }}"
                                                            class="form-control @error('fecha_inicio') is-invalid @enderror"
                                                            value="{{ old('fecha_inicio', $actividad->fecha_inicio ? $actividad->fecha_inicio->format('Y-m-d') : '') }}">
                                                        @error('fecha_inicio')
                                                            <div class="invalid-feedback">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="fecha_limite-{{ $actividad->id }}"
                                                            class="form-label">Fecha de Vencimiento</label>
                                                        <input type="date" name="fecha_limite"
                                                            id="fecha_limite-{{ $actividad->id }}"
                                                            class="form-control @error('fecha_limite') is-invalid @enderror"
                                                            value="{{ old('fecha_limite', $actividad->fecha_limite ? $actividad->fecha_limite->format('Y-m-d') : '') }}">
                                                        @error('fecha_limite')
                                                            <div class="invalid-feedback">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                {{-- Tipo de Actividad --}}
                                                <div class="mb-3">
                                                    <label for="tipo_actividad_id-{{ $actividad->id }}"
                                                        class="form-label">Tipo de Actividad *</label>
                                                    <select name="tipo_actividad_id"
                                                        id="tipo_actividad_id-{{ $actividad->id }}"
                                                        class="form-select @error('tipo_actividad_id') is-invalid @enderror"
                                                        required>
                                                        <option value="">Seleccionar tipo...</option>
                                                        @foreach ($tiposActividades as $tipo)
                                                            <option value="{{ $tipo->id }}"
                                                                {{ old('tipo_actividad_id', $actividad->tipo_actividad_id) == $tipo->id ? 'selected' : '' }}>
                                                                {{ $tipo->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('tipo_actividad_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                {{-- ✅ Tipos de Evaluación CORREGIDO --}}
                                                <div class="mb-3">
                                                    <label class="form-label">Tipos de Evaluación *</label>
                                                    <div id="tipos-evaluacion-container-{{ $actividad->id }}"
                                                        class="border rounded p-3">
                                                        @if ($actividad->tiposEvaluacion && $actividad->tiposEvaluacion->count() > 0)
                                                            @foreach ($actividad->tiposEvaluacion as $index => $tipoEvaluacion)
                                                                <div class="tipo-evaluacion mb-3"
                                                                    data-index="{{ $index }}">
                                                                    <div class="row align-items-center">
                                                                        <div class="col-md-5">
                                                                            <select
                                                                                name="tipos_evaluacion[{{ $index }}][tipo_evaluacion_id]"
                                                                                class="form-select" required>
                                                                                <option value="">
                                                                                    Seleccionar
                                                                                    tipo...</option>
                                                                                @foreach ($tiposEvaluaciones as $tipo)
                                                                                    <option
                                                                                        value="{{ $tipo->id }}"
                                                                                        {{-- ✅ CORRECCIÓN APLICADA --}}
                                                                                        {{ old("tipos_evaluacion.{$index}.tipo_evaluacion_id", $tipoEvaluacion->id) == $tipo->id ? 'selected' : '' }}>
                                                                                        {{ $tipo->nombre }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="number"
                                                                                name="tipos_evaluacion[{{ $index }}][puntaje_maximo]"
                                                                                class="form-control puntaje-input"
                                                                                placeholder="100"
                                                                                value="{{ old("tipos_evaluacion.{$index}.puntaje_maximo", $tipoEvaluacion->pivot->puntaje_maximo) }}"
                                                                                min="1" max="1000"
                                                                                required>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <select
                                                                                name="tipos_evaluacion[{{ $index }}][es_obligatorio]"
                                                                                class="form-select" required>
                                                                                <option value="1"
                                                                                    {{ old("tipos_evaluacion.{$index}.es_obligatorio", $tipoEvaluacion->pivot->es_obligatorio) == 1 ? 'selected' : '' }}>
                                                                                    Obligatorio
                                                                                </option>
                                                                                <option value="0"
                                                                                    {{ old("tipos_evaluacion.{$index}.es_obligatorio", $tipoEvaluacion->pivot->es_obligatorio) == 0 ? 'selected' : '' }}>
                                                                                    Opcional
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            <button type="button"
                                                                                class="btn btn-outline-danger btn-sm remove-tipo-evaluacion">
                                                                                <i class="fas fa-trash"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            {{-- Si no hay tipos, mostrar uno vacío --}}
                                                            <div class="tipo-evaluacion mb-3" data-index="0">
                                                                <div class="row align-items-center">
                                                                    <div class="col-md-5">
                                                                        <select
                                                                            name="tipos_evaluacion[0][tipo_evaluacion_id]"
                                                                            class="form-select" required>
                                                                            <option value="">Seleccionar
                                                                                tipo...
                                                                            </option>
                                                                            @foreach ($tiposEvaluaciones as $tipo)
                                                                                <option value="{{ $tipo->id }}">
                                                                                    {{ $tipo->nombre }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <input type="number"
                                                                            name="tipos_evaluacion[0][puntaje_maximo]"
                                                                            class="form-control puntaje-input"
                                                                            placeholder="100" value="100"
                                                                            min="1" max="1000" required>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <select
                                                                            name="tipos_evaluacion[0][es_obligatorio]"
                                                                            class="form-select" required>
                                                                            <option value="1">Obligatorio
                                                                            </option>
                                                                            <option value="0">Opcional
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-1">
                                                                        <button type="button"
                                                                            class="btn btn-outline-danger btn-sm remove-tipo-evaluacion">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-primary add-tipo-evaluacion mt-2"
                                                        data-actividad-id="{{ $actividad->id }}">
                                                        <i class="fas fa-plus me-1"></i> Agregar Tipo de
                                                        Evaluación
                                                    </button>

                                                    {{-- Mostrar total de puntajes --}}
                                                    <div class="alert alert-info mt-2 mb-0">
                                                        <small><strong>Puntaje Total: <span
                                                                    class="total-puntaje">0</span>
                                                                puntos</strong></small>
                                                    </div>

                                                    @error('tipos_evaluacion')
                                                        <div class="text-danger small mt-1">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                {{-- Archivo --}}
                                                <div class="mb-3">
                                                    <label for="archivo-{{ $actividad->id }}"
                                                        class="form-label">Archivo (opcional)</label>
                                                    @if ($actividad->archivo)
                                                        <div class="mb-2">
                                                            <a href="{{ asset('storage/' . $actividad->archivo) }}"
                                                                target="_blank"
                                                                class="btn btn-sm btn-outline-secondary">
                                                                <i class="fas fa-download me-1"></i> Descargar
                                                                Archivo
                                                                Actual
                                                            </a>
                                                        </div>
                                                    @endif
                                                    <input type="file" name="archivo"
                                                        id="archivo-{{ $actividad->id }}"
                                                        class="form-control @error('archivo') is-invalid @enderror"
                                                        accept=".jpg,.jpeg,.png,.gif,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.pdf,.txt,.mp4,.mp3,.wav,.ogg,.zip,.rar">
                                                    <small class="text-muted">Máximo 10MB. Formatos permitidos:
                                                        imágenes, documentos, audio, video, comprimidos.</small>
                                                    @error('archivo')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="d-flex justify-content-end gap-2">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fas fa-save me-1"></i> Guardar Cambios
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="" class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('¿Estás seguro de eliminar esta actividad?')">
                                <i class="fas fa-trash"></i> Eliminar
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                <h5>No hay actividades disponibles</h5>
                <p class="text-muted">Aún no se han agregado actividades a este subtema.</p>
            </div>
        @endforelse
    </div>
</div>
</div>
</div>


<div class="modal fade" id="modalActividad-{{ $subtema->id }}" tabindex="-1"
    aria-labelledby="modalActividadLabel-{{ $subtema->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalActividadLabel-{{ $subtema->id }}">
                    Agregar Actividad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('actividades.store', encrypt($cursos->id)) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="subtema_id" value="{{ $subtema->id }}">

                    <!-- Título de la Actividad -->
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título de la Actividad</label>
                        <input type="text" name="titulo" class="form-control" required>
                    </div>

                    <!-- Descripción -->
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" required></textarea>
                    </div>

                    <!-- Fecha de Habilitación -->
                    <div class="mb-3">
                        <label for="fecha_inicio" class="form-label">Fecha de Habilitación</label>
                        <input type="date" name="fecha_inicio" class="form-control" required>
                    </div>

                    <!-- Fecha de Vencimiento -->
                    <div class="mb-3">
                        <label for="fecha_limite" class="form-label">Fecha de Vencimiento</label>
                        <input type="date" name="fecha_limite" class="form-control" required>
                    </div>

                    <!-- Tipo de Actividad -->
                    <div class="mb-3">
                        <label for="tipo_actividad_id" class="form-label">Tipo de Actividad</label>
                        <select name="tipo_actividad_id" class="form-select" required>
                            <option value="" disabled selected>Selecciona un tipo</option>
                            @foreach ($tiposActividades as $tipo)
                                <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tipos de Evaluación -->
                    <div class="mb-3">
                        <label for="tipos_evaluacion" class="form-label">Tipos de Evaluación</label>
                        <div id="tipos-evaluacion-container">
                            <div class="tipo-evaluacion mb-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <select name="tipos_evaluacion[0][tipo_evaluacion_id]" class="form-select"
                                            required>
                                            <option value="" disabled selected>Selecciona un tipo de
                                                evaluación
                                            </option>
                                            @foreach ($tiposEvaluaciones as $tipoEvaluacion)
                                                <option value="{{ $tipoEvaluacion->id }}">
                                                    {{ $tipoEvaluacion->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" name="tipos_evaluacion[0][puntaje_maximo]"
                                            class="form-control" placeholder="Puntaje Máximo" required>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="tipos_evaluacion[0][es_obligatorio]" class="form-select"
                                            required>
                                            <option value="1">Obligatorio</option>
                                            <option value="0">Opcional</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="add-tipo-evaluacion">
                            <i class="fas fa-plus me-1"></i> Agregar Tipo de Evaluación
                        </button>
                    </div>

                    <!-- Archivo (opcional) -->
                    <div class="mb-3">
                        <label for="archivo" class="form-label">Archivo (opcional)</label>
                        <input type="file" name="archivo" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-success">Agregar Actividad</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalRecurso-{{ $subtema->id }}" tabindex="-1"
    aria-labelledby="modalRecursoLabel-{{ $subtema->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRecursoLabel-{{ $subtema->id }}">Agregar Recurso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('CrearRecursosSubtemaPost', encrypt($subtema->id)) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="tituloRecurso" class="form-label">Título del Recurso</label>
                        <input type="text" name="tituloRecurso" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcionRecurso" class="form-label">Descripción</label>
                        <textarea placeholder="Puedes agregar un link de youtube para previsualizar el video en curso"
                            name="descripcionRecurso" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="archivo" class="form-label">Archivo</label>
                        <input type="file" name="archivo" class="form-control"
                            accept=".jpg,.jpeg,.png,.gif,.svg,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.mp4,.avi,.mov,.mp3,.wav,.zip,.rar">
                        <small class="form-text text-muted">
                            <strong>Formatos permitidos:</strong><br>
                            • <strong>Imágenes:</strong> JPG, PNG, GIF, SVG<br>
                            • <strong>Documentos:</strong> PDF, Word (.doc, .docx), Excel (.xls, .xlsx),
                            PowerPoint
                            (.ppt, .pptx)<br>
                            • <strong>Multimedia:</strong> MP4, AVI, MOV, MP3, WAV<br>
                            • <strong>Comprimidos:</strong> ZIP, RAR<br>
                            • <strong>Tamaño máximo:</strong> 2MB
                        </small>
                    </div>
                    <div class="mb-3">
                        <label for="tipoRecurso" class="form-label">Tipo de Recurso</label>
                        <select class="form-select" name="tipoRecurso" required>
                            <optgroup label="Documentos">
                                <option value="word">Word (.doc, .docx)</option>
                                <option value="excel">Excel (.xls, .xlsx)</option>
                                <option value="powerpoint">PowerPoint (.ppt, .pptx)</option>
                                <option value="pdf">PDF</option>
                                <option value="docs">Google Docs</option>
                            </optgroup>
                            <optgroup label="Multimedia">
                                <option value="imagen">Imagen (JPG, PNG, GIF, SVG)</option>
                                <option value="video">Video (MP4, AVI, MOV)</option>
                                <option value="audio">Audio (MP3, WAV)</option>
                            </optgroup>
                            <optgroup label="Enlaces y Plataformas">
                                <option value="youtube">YouTube</option>
                                <option value="forms">Google Forms</option>
                                <option value="drive">Google Drive</option>
                                <option value="kahoot">Kahoot</option>
                                <option value="canva">Canva</option>
                                <option value="enlace">Enlace externo</option>
                            </optgroup>
                            <optgroup label="Otros">
                                <option value="archivos-adjuntos">Archivos comprimidos (ZIP, RAR)</option>
                            </optgroup>
                        </select>
                    </div>

                    <!-- Mostrar errores de validación -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i> Agregar Recurso
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animación para la descripción colapsable
        const descriptionToggles = document.querySelectorAll('.description-toggle');
        descriptionToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const arrow = this.querySelector('.toggle-arrow');
                arrow.style.transform = this.getAttribute('aria-expanded') === 'true' ?
                    'rotate(0deg)' :
                    'rotate(180deg)';
            });
        });

        // Previsualización de imagen en el modal de edición
        const imageUploads = document.querySelectorAll('input[name="imagen"]');
        imageUploads.forEach(input => {
            input.addEventListener('change', function() {
                const container = this.closest('.image-upload-section');
                const preview = container.querySelector('.current-image-preview');
                const placeholder = container.querySelector('.no-image-placeholder');

                if (this.files && this.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        if (preview) {
                            preview.src = e.target.result;
                        } else if (placeholder) {
                            placeholder.innerHTML = `
                            <img src="${e.target.result}" class="current-image-preview" alt="Vista previa">
                            <small class="text-muted d-block text-center mt-2">Vista previa</small>
                        `;
                        }
                    }

                    reader.readAsDataURL(this.files[0]);
                }
            });
        });

        // Efectos hover para tarjetas
        const resourceCards = document.querySelectorAll('.resource-card');
        resourceCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>

<script>
    // Script de validación mejorado para múltiples tipos de archivo
    document.addEventListener('DOMContentLoaded', function() {
        // Configuración de tipos de archivo permitidos
        const allowedTypes = {
            // Imágenes
            'image/jpeg': '.jpg',
            'image/jpg': '.jpg',
            'image/png': '.png',
            'image/gif': '.gif',
            'image/svg+xml': '.svg',

            // Documentos
            'application/pdf': '.pdf',
            'application/msword': '.doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document': '.docx',
            'application/vnd.ms-excel': '.xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': '.xlsx',
            'application/vnd.ms-powerpoint': '.ppt',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation': '.pptx',

            // Audio
            'audio/mpeg': '.mp3',
            'audio/wav': '.wav',
            'audio/x-wav': '.wav',

            // Video
            'video/mp4': '.mp4',
            'video/avi': '.avi',
            'video/quicktime': '.mov',

            // Comprimidos
            'application/zip': '.zip',
            'application/x-rar-compressed': '.rar',
            'application/x-zip-compressed': '.zip'
        };

        const maxSize = 2 * 1024 * 1024; // 2MB en bytes

        // Función para validar archivo
        function validateFile(file) {
            const errors = [];

            // Validar tipo de archivo
            if (!allowedTypes[file.type]) {
                // Validación adicional por extensión si el MIME type no es reconocido
                const extension = file.name.toLowerCase().split('.').pop();
                const allowedExtensions = Object.values(allowedTypes).map(ext => ext.replace('.', ''));

                if (!allowedExtensions.includes(extension)) {
                    errors.push('Tipo de archivo no permitido. Formatos válidos: ' + allowedExtensions.join(
                        ', '));
                }
            }

            // Validar tamaño
            if (file.size > maxSize) {
                errors.push('El archivo es demasiado grande. Tamaño máximo: 2MB');
            }

            return errors;
        }

        // Función para mostrar errores
        function showErrors(errors, inputElement) {
            // Remover errores anteriores
            const existingError = inputElement.parentNode.querySelector('.file-error');
            if (existingError) {
                existingError.remove();
            }

            if (errors.length > 0) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'file-error alert alert-danger mt-2';
                errorDiv.innerHTML = '<strong>Errores:</strong><ul class="mb-0">' +
                    errors.map(error => '<li>' + error + '</li>').join('') + '</ul>';
                inputElement.parentNode.appendChild(errorDiv);

                // Limpiar el input
                inputElement.value = '';
                return false;
            }
            return true;
        }

        // Función para mostrar información del archivo
        function showFileInfo(file, inputElement) {
            // Remover info anterior
            const existingInfo = inputElement.parentNode.querySelector('.file-info');
            if (existingInfo) {
                existingInfo.remove();
            }

            const infoDiv = document.createElement('div');
            infoDiv.className = 'file-info alert alert-success mt-2';
            infoDiv.innerHTML = `
            <strong>Archivo seleccionado:</strong><br>
            📄 <strong>Nombre:</strong> ${file.name}<br>
            📏 <strong>Tamaño:</strong> ${(file.size / 1024 / 1024).toFixed(2)} MB<br>
            🏷️ <strong>Tipo:</strong> ${file.type || 'Desconocido'}
        `;
            inputElement.parentNode.appendChild(infoDiv);
        }

        // Aplicar validación a todos los inputs de archivo en modales de recursos
        const fileInputs = document.querySelectorAll('input[name="archivo"]');

        fileInputs.forEach(function(input) {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];

                if (!file) {
                    // Limpiar mensajes si no hay archivo
                    const existingError = input.parentNode.querySelector('.file-error');
                    const existingInfo = input.parentNode.querySelector('.file-info');
                    if (existingError) existingError.remove();
                    if (existingInfo) existingInfo.remove();
                    return;
                }

                const errors = validateFile(file);

                if (showErrors(errors, input)) {
                    showFileInfo(file, input);
                    console.log('Archivo válido:', file.name, file.type, file.size);
                } else {
                    console.log('Archivo inválido:', file.name, errors);
                }
            });
        });

        // Función para validar antes de enviar el formulario
        function validateFormOnSubmit(form) {
            const fileInput = form.querySelector('input[name="archivo"]');
            if (fileInput && fileInput.files.length > 0) {
                const file = fileInput.files[0];
                const errors = validateFile(file);

                if (errors.length > 0) {
                    showErrors(errors, fileInput);
                    return false;
                }
            }
            return true;
        }

        // Aplicar validación a los formularios
        const forms = document.querySelectorAll('form[action*="CrearRecursosSubtemaPost"]');
        forms.forEach(function(form) {
            form.addEventListener('submit', function(e) {
                if (!validateFormOnSubmit(form)) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    });

    // Función global para debugging - puedes llamarla desde la consola
    window.debugFileValidation = function() {
        console.log('Inputs de archivo encontrados:', document.querySelectorAll('input[name="archivo"]').length);
        console.log('Formularios encontrados:', document.querySelectorAll(
            'form[action*="CrearRecursosSubtemaPost"]').length);
    };
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Funcionalidad para cada modal de actividad
        document.querySelectorAll('[id^="modalEditarActividad-"]').forEach(modal => {
            const actividadId = modal.id.split('-')[1];
            const container = document.getElementById(`tipos-evaluacion-container-${actividadId}`);
            const addBtn = modal.querySelector('.add-tipo-evaluacion');

            if (!container || !addBtn) return;

            // Evitar duplicidad de eventos
            if (modal.dataset.scriptAttached) return;
            modal.dataset.scriptAttached = "true";

            // Calcular total inicial
            calcularTotal(container);

            // Agregar tipo de evaluación
            addBtn.addEventListener('click', function() {
                const index = container.querySelectorAll('.tipo-evaluacion').length;
                const newRow = createEvaluacionRow(index, actividadId);
                container.insertAdjacentHTML('beforeend', newRow);
                calcularTotal(container);
            });

            // Eliminar tipo de evaluación
            container.addEventListener('click', function(e) {
                if (e.target.closest('.remove-tipo-evaluacion')) {
                    const tipoEvaluaciones = container.querySelectorAll('.tipo-evaluacion');
                    if (tipoEvaluaciones.length > 1) {
                        e.target.closest('.tipo-evaluacion').remove();
                        reindexRows(container);
                        calcularTotal(container);
                    } else {
                        alert('Debe mantener al menos un tipo de evaluación');
                    }
                }
            });

            // Recalcular total al cambiar puntajes
            container.addEventListener('input', function(e) {
                if (e.target.classList.contains('puntaje-input')) {
                    calcularTotal(container);
                }
            });
        });

        function createEvaluacionRow(index, actividadId) {
            return `
            <div class="tipo-evaluacion mb-3" data-index="${index}">
                <div class="row align-items-center">
                    <div class="col-md-5">
                        <select name="tipos_evaluacion[${index}][tipo_evaluacion_id]" class="form-select" required>
                            <option value="">Seleccionar tipo...</option>
                            @foreach ($tiposEvaluaciones as $tipo)
                                <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="tipos_evaluacion[${index}][puntaje_maximo]"
                               class="form-control puntaje-input" placeholder="100"
                               value="100" min="1" max="1000" required>
                    </div>
                    <div class="col-md-3">
                        <select name="tipos_evaluacion[${index}][es_obligatorio]" class="form-select" required>
                            <option value="1">Obligatorio</option>
                            <option value="0">Opcional</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-tipo-evaluacion">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        }

        function reindexRows(container) {
            container.querySelectorAll('.tipo-evaluacion').forEach((row, index) => {
                row.setAttribute('data-index', index);
                row.querySelectorAll('select, input').forEach(input => {
                    const name = input.getAttribute('name');
                    if (name) {
                        input.setAttribute('name', name.replace(/\[\d+\]/, `[${index}]`));
                    }
                });
            });
        }

        function calcularTotal(container) {
            const puntajes = container.querySelectorAll('.puntaje-input');
            let total = 0;
            puntajes.forEach(input => {
                total += parseInt(input.value) || 0;
            });
            const totalSpan = container.closest('.modal').querySelector('.total-puntaje');
            if (totalSpan) totalSpan.textContent = total;
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        ['modalEditarSubtema-', 'modalEditarActividad-', 'modalCuestionario-', 'modalActividad-',
            'modalRecurso-', 'modalEditarRecurso-'
        ].forEach(function(prefix) {
            document.querySelectorAll('[id^="' + prefix + '"]').forEach(function(modal) {
                if (modal.parentElement !== document.body) {
                    document.body.appendChild(modal);
                }
            });
        });
    });
</script>
