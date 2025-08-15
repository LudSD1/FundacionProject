<div class="subtema-content">
    <h2>{{ $subtema->titulo_subtema }}</h2>

    @if ($subtema->imagen)
        <img class="img-fluid rounded mb-3" src="{{ asset('storage/' . $subtema->imagen) }}" alt="Imagen del subtema"
            style="max-width: 100%; height: auto;">
    @endif

    <div class="modal fade" id="modalEditarSubtema-{{ $subtema->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>Editar Subtema: {{ $subtema->titulo_subtema }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('subtemas.update', encrypt($subtema->id)) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título*</label>
                            <input type="text" class="form-control" name="titulo"
                                value="{{ $subtema->titulo_subtema }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" name="descripcion" rows="3">{{ $subtema->descripcion }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Imagen Actual</label>
                            @if ($subtema->imagen)
                                <img src="{{ asset('storage/' . $subtema->imagen) }}" class="img-thumbnail mb-2"
                                    style="max-height: 150px;">
                            @endif
                            <input type="file" class="form-control" name="imagen" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
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

    <div class="mb-3">
        <button class="btn btn-link text-decoration-none" type="button" data-bs-toggle="collapse"
            data-bs-target="#descripcionSubtema-{{ $subtema->id }}" aria-expanded="false"
            aria-controls="descripcionSubtema-{{ $subtema->id }}">
            <i class="fas fa-chevron-down me-1"></i> Ver Descripción
        </button>
        <div class="collapse" id="descripcionSubtema-{{ $subtema->id }}">
            <div class="card card-body bg-light">
                {!! nl2br(e($subtema->descripcion)) !!}
            </div>
        </div>
    </div>

    @if (auth()->user()->hasRole('Docente') && $cursos->docente_id == auth()->user()->id)
        <div class="mb-4">
            <button class="btn btn-sm btn-outline-success me-2" data-bs-toggle="modal"
                data-bs-target="#modalActividad-{{ $subtema->id }}">
                <i class="fas fa-tasks me-1"></i> Agregar Actividad
            </button>
            <button class="btn btn-sm btn-outline-success me-2" data-bs-toggle="modal"
                data-bs-target="#modalRecurso-{{ $subtema->id }}">
                <i class="fas fa-file-alt me-1"></i> Agregar Recurso
            </button>
            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                data-bs-target="#modalEditarSubtema-{{ $subtema->id }}">
                <i class="fas fa-edit me-1"></i> Editar Subtema
            </button>
            <form class="d-inline" action="{{ route('subtemas.delete', encrypt($subtema->id)) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-trash me-1"></i> Eliminar Subtema
                </button>
            </form>
        </div>
    @endif

    <!-- Sección de Recursos -->
    <div class="mb-4">

        <h4 class="border-bottom pb-2">
            <i class="fas fa-folder-open me-2"></i>Recursos
        </h4>

        @forelse($subtema->recursos as $recurso)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        @php
                            // Definir iconos según el tipo de recurso
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
                            ];
                            $icono = $iconos[$recurso->tipoRecurso] ?? 'fas fa-file text-secondary';
                        @endphp
                        <i class="{{ $icono }} me-2"></i>
                        {{ $recurso->nombreRecurso }}
                        <small class="badge bg-light text-dark ms-2">{{ ucfirst($recurso->tipoRecurso) }}</small>
                    </h5>


                    @if (Str::contains($recurso->descripcionRecursos, ['<iframe', '<video', '<img']))
                        <div class="ratio ratio-16x9 mb-3">
                            {!! $recurso->descripcionRecursos !!}
                        </div>
                    @else
                        <p class="card-text">{!! nl2br(e($recurso->descripcionRecursos)) !!}</p>
                    @endif

                    @if ($recurso->archivoRecurso)
                        <a href="{{ asset('storage/' . $recurso->archivoRecurso) }}" class="btn btn-sm btn-primary"
                            target="_blank">
                            <i class="fas fa-download me-1"></i> Descargar Recurso
                        </a>
                    @endif

                    @if (auth()->user()->hasRole('Docente') && $cursos->docente_id == auth()->user()->id)
                        <div class="mt-2">
                            <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal"
                                data-bs-target="#modalEditarRecurso-{{ $recurso->id }}">
                                <i class="fas fa-edit me-1"></i> Editar
                            </button>
                            <form action="{{ route('eliminarRecursosSubtemaPost', encrypt($recurso->id)) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('¿Estás seguro de eliminar este recurso?')">
                                    <i class="fas fa-trash me-1"></i> Eliminar
                                </button>
                            </form>
                        </div>

                        <!-- Modal para editar recurso -->
                        <div class="modal fade" id="modalEditarRecurso-{{ $recurso->id }}" tabindex="-1"
                            aria-labelledby="modalEditarRecursoLabel-{{ $recurso->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Editar Recurso</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST"
                                            action="{{ route('editarRecursosSubtemaPost', encrypt($recurso->id)) }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-3">
                                                <label for="tituloRecurso" class="form-label">Título del
                                                    Recurso</label>
                                                <input type="text" name="tituloRecurso" class="form-control"
                                                    value="{{ $recurso->nombreRecurso }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="descripcionRecurso" class="form-label">Descripción</label>
                                                <textarea name="descripcionRecurso" class="form-control" required>{{ $recurso->descripcionRecursos }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="archivo" class="form-label">Archivo</label>
                                                @if ($recurso->archivoRecurso)
                                                    <p class="small text-muted">Archivo actual:
                                                        {{ basename($recurso->archivoRecurso) }}</p>
                                                @endif
                                                <input type="file" name="archivo" class="form-control">
                                                <small class="form-text text-muted">
                                                    Formatos permitidos: Imágenes, documentos, audio, video y archivos
                                                    comprimidos (máx. 2MB)
                                                </small>
                                            </div>
                                            <div class="mb-3">
                                                <label for="tipoRecurso" class="form-label">Tipo de Recurso</label>
                                                <select class="form-select" name="tipoRecurso" required>
                                                    <optgroup label="📄 Documentos">
                                                        <option value="word"
                                                            {{ $recurso->tipoRecurso == 'word' ? 'selected' : '' }}>
                                                            📝 Word</option>
                                                        <option value="excel"
                                                            {{ $recurso->tipoRecurso == 'excel' ? 'selected' : '' }}>
                                                            📊 Excel</option>
                                                        <option value="powerpoint"
                                                            {{ $recurso->tipoRecurso == 'powerpoint' ? 'selected' : '' }}>
                                                            📈 PowerPoint</option>
                                                        <option value="pdf"
                                                            {{ $recurso->tipoRecurso == 'pdf' ? 'selected' : '' }}>
                                                            📕 PDF</option>
                                                        <option value="docs"
                                                            {{ $recurso->tipoRecurso == 'docs' ? 'selected' : '' }}>
                                                            📝 Google Docs</option>
                                                    </optgroup>
                                                    <optgroup label="🎥 Multimedia">
                                                        <option value="imagen"
                                                            {{ $recurso->tipoRecurso == 'imagen' ? 'selected' : '' }}>
                                                            🖼️ Imagen</option>
                                                        <option value="video"
                                                            {{ $recurso->tipoRecurso == 'video' ? 'selected' : '' }}>
                                                            🎬 Video</option>
                                                        <option value="audio"
                                                            {{ $recurso->tipoRecurso == 'audio' ? 'selected' : '' }}>
                                                            🎵 Audio</option>
                                                    </optgroup>
                                                    <optgroup label="🔗 Enlaces y Plataformas">
                                                        <option value="youtube"
                                                            {{ $recurso->tipoRecurso == 'youtube' ? 'selected' : '' }}>
                                                            📺 YouTube</option>
                                                        <option value="forms"
                                                            {{ $recurso->tipoRecurso == 'forms' ? 'selected' : '' }}>
                                                            📋 Google Forms</option>
                                                        <option value="drive"
                                                            {{ $recurso->tipoRecurso == 'drive' ? 'selected' : '' }}>
                                                            ☁️ Google Drive</option>
                                                        <option value="kahoot"
                                                            {{ $recurso->tipoRecurso == 'kahoot' ? 'selected' : '' }}>
                                                            🎮 Kahoot</option>
                                                        <option value="canva"
                                                            {{ $recurso->tipoRecurso == 'canva' ? 'selected' : '' }}>
                                                            🎨 Canva</option>
                                                        <option value="enlace"
                                                            {{ $recurso->tipoRecurso == 'enlace' ? 'selected' : '' }}>
                                                            🔗 Enlace</option>
                                                    </optgroup>
                                                    <optgroup label="📎 Otros">
                                                        <option value="archivos-adjuntos"
                                                            {{ $recurso->tipoRecurso == 'archivos-adjuntos' ? 'selected' : '' }}>
                                                            📎 Archivos Adjuntos</option>
                                                    </optgroup>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-save me-1"></i> Guardar Cambios
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (auth()->user()->hasRole('Estudiante'))
                        <div class="mt-2">
                            @if ($recurso->isViewedByInscrito($inscritos2->id))
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i> Visto
                                </span>
                            @else
                                <form method="POST"
                                    action="{{ route('recurso.marcarVisto', encrypt($recurso->id)) }}"
                                    class="d-inline">
                                    @csrf
                                    <input type="hidden" name="inscritos_id" value="{{ $inscritos2->id }}">
                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-check-circle me-1"></i> Marcar como visto
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> No hay recursos disponibles para este subtema.
            </div>
        @endforelse
    </div>

    <!-- Sección de Actividades -->
    <div class="mb-4">
        <h4 class="border-bottom pb-2">
            <i class="fas fa-tasks me-2"></i>Actividades
        </h4>

        @forelse($subtema->actividades as $actividad)
            <div class="card mb-3 shadow-sm">
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
                                    <div class="modal-dialog">
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
                                                        <label for="max_intentos" class="form-label">Número Máximo de
                                                            Intentos</label>
                                                        <input type="number" name="max_intentos" class="form-control"
                                                            value="{{ $actividad->cuestionario ? $actividad->cuestionario->max_intentos : 3 }}"
                                                            min="1" required>
                                                    </div>

                                                    <!-- Tiempo Límite -->
                                                    <div class="mb-3">
                                                        <label for="tiempo_limite" class="form-label">Tiempo Límite (en
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
                                <div class="modal-dialog modal-lg"> {{-- Modal más grande para mejor UX --}}
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
                                                            <div class="invalid-feedback">{{ $message }}</div>
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
                                                            <div class="invalid-feedback">{{ $message }}</div>
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
                                                                                <option value="">Seleccionar
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
                                                                            <option value="">Seleccionar tipo...
                                                                            </option>
                                                                            @foreach ($tiposEvaluaciones as $tipo)
                                                                                <option value="{{ $tipo->id }}">
                                                                                    {{ $tipo->nombre }}</option>
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
                                                                            <option value="1">Obligatorio</option>
                                                                            <option value="0">Opcional</option>
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
                                                        <i class="fas fa-plus me-1"></i> Agregar Tipo de Evaluación
                                                    </button>

                                                    {{-- Mostrar total de puntajes --}}
                                                    <div class="alert alert-info mt-2 mb-0">
                                                        <small><strong>Puntaje Total: <span
                                                                    class="total-puntaje">0</span>
                                                                puntos</strong></small>
                                                    </div>

                                                    @error('tipos_evaluacion')
                                                        <div class="text-danger small mt-1">{{ $message }}</div>
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
                                                                <i class="fas fa-download me-1"></i> Descargar Archivo
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
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> No hay actividades disponibles para este subtema.
            </div>
        @endforelse




        <script>
            document.querySelectorAll('.add-tipo-evaluacion').forEach(button => {
                button.addEventListener('click', function() {
                    const actividadId = this.dataset.actividadId;
                    const container = document.getElementById(`tipos-evaluacion-container-${actividadId}`);
                    const index = container.children.length;

                    const template = `
                        <div class="tipo-evaluacion mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <select name="tipos_evaluacion[${index}][tipo_evaluacion_id]" class="form-select" required>
                                        <option value="" disabled selected>Selecciona un tipo de evaluación</option>
                                        @foreach ($tiposEvaluaciones as $tipo)
                                            <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="tipos_evaluacion[${index}][puntaje_maximo]" class="form-control"
                                        placeholder="Puntaje Máximo" required>
                                </div>
                                <div class="col-md-3">
                                    <select name="tipos_evaluacion[${index}][es_obligatorio]" class="form-select" required>
                                        <option value="1">Obligatorio</option>
                                        <option value="0">Opcional</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    `;

                    container.insertAdjacentHTML('beforeend', template);
                });
            });
        </script>

    </div>
</div>

<div class="modal fade" id="modalActividad-{{ $subtema->id }}" tabindex="-1"
    aria-labelledby="modalActividadLabel-{{ $subtema->id }}" aria-hidden="true">
    <div class="modal-dialog">
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
                                            <option value="" disabled selected>Selecciona un tipo de evaluación
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Recurso</h5>
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
                            • <strong>Documentos:</strong> PDF, Word (.doc, .docx), Excel (.xls, .xlsx), PowerPoint
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
