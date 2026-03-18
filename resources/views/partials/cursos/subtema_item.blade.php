<div class="si-wrap">


    <div class="si-header">

        {{-- Imagen hero (si existe) --}}
        @if ($subtema->imagen)
            <div class="si-hero-img">
                <img src="{{ asset('storage/' . $subtema->imagen) }}" alt="{{ $subtema->titulo_subtema }}" loading="lazy">
                <div class="si-hero-img-overlay"></div>
            </div>
        @endif

        {{-- Título + descripción --}}
        <div class="si-title-section {{ $subtema->imagen ? 'si-title-section--with-img' : '' }}">
            <h2 class="si-title">{{ $subtema->titulo_subtema }}</h2>

            @if ($subtema->descripcion)
                <div class="si-desc-wrap">
                    <button class="si-desc-toggle" type="button" data-bs-toggle="collapse"
                        data-bs-target="#siDesc-{{ $subtema->id }}" aria-expanded="false">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-info-circle-fill"></i>
                            <span>Descripción del Subtema</span>
                        </div>
                        <i class="bi bi-chevron-down si-desc-chevron"></i>
                    </button>
                    <div class="collapse" id="siDesc-{{ $subtema->id }}">
                        <div class="si-desc-body">
                            {!! nl2br(e($subtema->descripcion)) !!}
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Acciones docente --}}
        @if (auth()->user()->hasRole('Docente') && $cursos->docente_id == auth()->user()->id)
            <div class="si-docente-actions">
                <button class="cc-btn cc-btn-success" data-bs-toggle="modal"
                    data-bs-target="#modalActividad-{{ $subtema->id }}">
                    <i class="bi bi-clipboard-plus-fill me-2"></i>Agregar Actividad
                </button>
                <button class="cc-btn cc-btn-primary" data-bs-toggle="modal"
                    data-bs-target="#modalRecurso-{{ $subtema->id }}">
                    <i class="bi bi-file-earmark-plus-fill me-2"></i>Agregar Recurso
                </button>
                <button class="cc-btn cc-btn-outline-edit" data-bs-toggle="modal"
                    data-bs-target="#modalEditarSubtema-{{ $subtema->id }}">
                    <i class="bi bi-pencil-fill me-2"></i>Editar
                </button>
                {{-- FIX 10: form POST correcto --}}
                <form action="{{ route('subtemas.delete', encrypt($subtema->id)) }}" method="POST"
                    onsubmit="return confirm('¿Eliminar este subtema y todo su contenido?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="cc-btn cc-btn-outline-danger">
                        <i class="bi bi-trash3-fill me-2"></i>Eliminar
                    </button>
                </form>
            </div>
        @endif

    </div>{{-- /si-header --}}

    {{-- ╔══════════════════════════════════════════╗
         ║  RECURSOS DE APRENDIZAJE                ║
         ╚══════════════════════════════════════════╝ --}}
    <div class="si-section">
        <div class="si-section-header">
            <div class="si-section-title">
                <div class="si-section-icon">
                    <i class="bi bi-folder-open"></i>
                </div>
                <div>
                    <h3 class="si-section-name">Recursos de Aprendizaje</h3>
                    <p class="si-section-sub">Material de estudio para este subtema</p>
                </div>
            </div>
            <span class="si-count-badge">
                {{ $subtema->recursos->count() }}
                recurso{{ $subtema->recursos->count() != 1 ? 's' : '' }}
            </span>
        </div>

        @php
            // Mapa de iconos por tipo de recurso
            $iconosRecurso = [
                'word' => 'bi-file-earmark-word-fill',
                'excel' => 'bi-file-earmark-spreadsheet-fill',
                'powerpoint' => 'bi-file-earmark-slides-fill',
                'pdf' => 'bi-file-earmark-pdf-fill',
                'docs' => 'bi-file-earmark-text-fill',
                'imagen' => 'bi-file-earmark-image-fill',
                'video' => 'bi-camera-video-fill',
                'audio' => 'bi-file-earmark-music-fill',
                'youtube' => 'bi-youtube',
                'forms' => 'bi-ui-checks',
                'drive' => 'bi-cloud-fill',
                'kahoot' => 'bi-controller',
                'canva' => 'bi-brush-fill',
                'enlace' => 'bi-link-45deg',
                'archivos-adjuntos' => 'bi-file-earmark-zip-fill',
            ];

            $coloresRecurso = [
                'word' => '#2b579a',
                'excel' => '#217346',
                'powerpoint' => '#d24726',
                'pdf' => '#e53935',
                'docs' => '#4285f4',
                'imagen' => '#8e24aa',
                'video' => '#f57c00',
                'audio' => '#00897b',
                'youtube' => '#ff0000',
                'forms' => '#00897b',
                'drive' => '#4285f4',
                'kahoot' => '#46178f',
                'canva' => '#00c4cc',
                'enlace' => '#145da0',
                'archivos-adjuntos' => '#5d4037',
            ];
        @endphp

        <div class="si-resources-grid">
            @forelse($subtema->recursos as $recurso)
                @php
                    $icon = $iconosRecurso[$recurso->tipoRecurso] ?? 'bi-file-earmark-fill';
                    $color = $coloresRecurso[$recurso->tipoRecurso] ?? '#145da0';
                @endphp

                <div class="si-resource-card" data-type="{{ $recurso->tipoRecurso }}">
                    <div class="si-resource-icon" style="background:{{ $color }}15; color:{{ $color }}">
                        <i class="bi {{ $icon }}"></i>
                    </div>
                    <div class="si-resource-body">
                        <div class="si-resource-head">
                            <h5 class="si-resource-title">{{ $recurso->nombreRecurso }}</h5>
                            <span class="si-resource-type">{{ ucfirst($recurso->tipoRecurso) }}</span>
                        </div>

                        <div class="si-resource-desc">
                            @if (Str::contains($recurso->descripcionRecursos, ['<iframe', '<video', '<img']))
                                <div class="si-media-wrap">
                                    {!! $recurso->descripcionRecursos !!}
                                </div>
                            @else
                                <p>{!! nl2br(e($recurso->descripcionRecursos)) !!}</p>
                            @endif
                        </div>

                        {{-- FIX 4: estructura de acciones sin HTML roto --}}
                        <div class="si-resource-actions">

                            {{-- Descargar --}}
                            @if ($recurso->archivoRecurso)
                                <a href="{{ asset('storage/' . $recurso->archivoRecurso) }}"
                                    class="cc-btn cc-btn-sm cc-btn-outline-edit" target="_blank">
                                    <i class="bi bi-download me-1"></i>Descargar
                                </a>
                            @endif

                            {{-- Acciones estudiante --}}
                            @if (auth()->user()->hasRole('Estudiante') && isset($inscritos2))
                                @if ($recurso->isViewedByInscrito($inscritos2->id))
                                    <span class="si-badge si-badge--done">
                                        <i class="bi bi-check-circle-fill me-1"></i>Visto
                                    </span>
                                @else
                                    <form method="POST"
                                        action="{{ route('recurso.marcarVisto', encrypt($recurso->id)) }}">
                                        @csrf
                                        <input type="hidden" name="inscritos_id" value="{{ $inscritos2->id }}">
                                        <button type="submit" class="cc-btn cc-btn-sm cc-btn-outline-success">
                                            <i class="bi bi-eye-fill me-1"></i>Marcar como visto
                                        </button>
                                    </form>
                                @endif
                            @endif

                            {{-- Acciones docente --}}
                            @if (auth()->user()->hasRole('Docente') && $cursos->docente_id == auth()->user()->id)
                                <button class="cc-btn cc-btn-sm cc-btn-icon" data-bs-toggle="modal"
                                    data-bs-target="#modalEditarRecurso-{{ $recurso->id }}" title="Editar recurso">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <form action="{{ route('eliminarRecursosSubtemaPost', encrypt($recurso->id)) }}"
                                    method="POST" onsubmit="return confirm('¿Eliminar este recurso?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="cc-btn cc-btn-sm cc-btn-icon cc-btn-icon--danger"
                                        title="Eliminar recurso">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            @endif

                        </div>{{-- /si-resource-actions --}}
                    </div>
                </div>

            @empty
                <div class="si-empty">
                    <div class="si-empty-icon"><i class="bi bi-folder2"></i></div>
                    <h5>No hay recursos disponibles</h5>
                    <p>Aún no se han agregado recursos a este subtema.</p>
                </div>
            @endforelse
        </div>
    </div>{{-- /si-section recursos --}}


    {{-- ╔══════════════════════════════════════════╗
         ║  ACTIVIDADES DE EVALUACIÓN              ║
         ╚══════════════════════════════════════════╝ --}}
    <div class="si-section">
        <div class="si-section-header">
            <div class="si-section-title">
                <div class="si-section-icon si-section-icon--orange">
                    <i class="bi bi-clipboard2-check-fill"></i>
                </div>
                <div>
                    <h3 class="si-section-name">Actividades de Evaluación</h3>
                    <p class="si-section-sub">Tareas y cuestionarios del subtema</p>
                </div>
            </div>
            <span class="si-count-badge si-count-badge--orange">
                {{ $subtema->actividades->count() }}
                actividad{{ $subtema->actividades->count() != 1 ? 'es' : '' }}
            </span>
        </div>

        <div class="si-activities-list">
            @forelse($subtema->actividades as $actividad)

                <div class="si-activity-card">
                    <div class="si-activity-head">
                        <div class="si-activity-info">
                            <div class="si-activity-icon">
                                <i class="bi bi-clipboard2-check"></i>
                            </div>
                            <div>
                                <h5 class="si-activity-title">{{ $actividad->titulo }}</h5>
                                <div class="si-activity-meta">
                                    <span class="si-activity-meta-item">
                                        <i class="bi bi-calendar3"></i>
                                        Publicado: {{ $actividad->created_at->format('d/m/Y') }}
                                    </span>
                                    <span class="si-activity-meta-item">
                                        <i class="bi bi-clock"></i>
                                        Vence:
                                        {{ $actividad->fecha_limite ? $actividad->fecha_limite->format('d/m/Y') : 'Sin fecha límite' }}
                                    </span>
                                    <span class="si-activity-meta-item">
                                        <i class="bi bi-tag-fill"></i>
                                        {{ $actividad->tipoActividad->nombre }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <span class="si-activity-badge">{{ $actividad->tipoActividad->nombre }}</span>
                    </div>

                    {{-- Acciones --}}
                    <div class="si-activity-actions">

                        @if ($actividad->tiposEvaluacion->contains('nombre', 'Cuestionario'))
                            {{-- ── Docente: cuestionario ── --}}
                            @role('Docente')
                                <button class="cc-btn cc-btn-sm cc-btn-outline-edit" data-bs-toggle="modal"
                                    data-bs-target="#modalCuestionario-{{ $actividad->id }}">
                                    <i
                                        class="bi bi-{{ $actividad->cuestionario ? 'pencil-fill' : 'plus-circle-fill' }} me-1"></i>
                                    {{ $actividad->cuestionario ? 'Editar Cuestionario' : 'Crear Cuestionario' }}
                                </button>
                                @if ($actividad->cuestionario)
                                    <a href="{{ route('cuestionarios.index', encrypt($actividad->cuestionario->id)) }}"
                                        class="cc-btn cc-btn-sm cc-btn-outline-edit">
                                        <i class="bi bi-gear-fill me-1"></i>Administrar
                                    </a>
                                    <a href="{{ route('rankingQuizz', encrypt($actividad->cuestionario->id)) }}"
                                        class="cc-btn cc-btn-sm cc-btn-outline-edit">
                                        <i class="bi bi-bar-chart-fill me-1"></i>Ver Resultados
                                    </a>
                                @endif

                                {{-- Visibilidad --}}
                                <form method="POST"
                                    action="{{ route($actividad->es_publica ? 'actividades.ocultar' : 'actividades.mostrar', encrypt($actividad->id)) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                        class="cc-btn cc-btn-sm {{ $actividad->es_publica ? 'cc-btn-outline-warning' : 'cc-btn-outline-success' }}">
                                        <i
                                            class="bi bi-{{ $actividad->es_publica ? 'eye-slash-fill' : 'eye-fill' }} me-1"></i>
                                        {{ $actividad->es_publica ? 'Ocultar' : 'Mostrar' }}
                                    </button>
                                </form>
                            @endrole

                            {{-- ── Estudiante: cuestionario ── --}}
                            @role('Estudiante')
                                @if ($actividad->cuestionario)
                                    <a href="{{ route('cuestionario.mostrar', encrypt($actividad->cuestionario->id)) }}"
                                        class="cc-btn cc-btn-sm cc-btn-primary">
                                        <i class="bi bi-play-fill me-1"></i>Responder
                                    </a>
                                    <a href="{{ route('rankingQuizz', encrypt($actividad->cuestionario->id)) }}"
                                        class="cc-btn cc-btn-sm cc-btn-outline-edit">
                                        <i class="bi bi-bar-chart-fill me-1"></i>Ver Resultados
                                    </a>
                                @endif
                            @endrole
                        @else
                            {{-- ── Docente: tarea ── --}}
                            @hasrole('Docente')
                                <a href="{{ route('calificarT', encrypt($actividad->id)) }}"
                                    class="cc-btn cc-btn-sm cc-btn-outline-edit">
                                    <i class="bi bi-calculator-fill me-1"></i>Calificar Tarea
                                </a>
                                <form method="POST"
                                    action="{{ route($actividad->es_publica ? 'actividades.ocultar' : 'actividades.mostrar', encrypt($actividad->id)) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                        class="cc-btn cc-btn-sm {{ $actividad->es_publica ? 'cc-btn-outline-warning' : 'cc-btn-outline-success' }}">
                                        <i
                                            class="bi bi-{{ $actividad->es_publica ? 'eye-slash-fill' : 'eye-fill' }} me-1"></i>
                                        {{ $actividad->es_publica ? 'Ocultar' : 'Mostrar' }}
                                    </button>
                                </form>
                            @endhasrole

                            {{-- ── Estudiante: tarea ── --}}
                            @role('Estudiante')
                                <a href="{{ route('actividad.show', encrypt($actividad->id)) }}"
                                    class="cc-btn cc-btn-sm cc-btn-primary">
                                    <i class="bi bi-eye-fill me-1"></i>Ver Actividad
                                </a>
                            @endrole
                        @endif

                        {{-- ── Estado completado (estudiante) ── --}}
                        @role('Estudiante')
                            @if (isset($inscritos2))
                                @if ($actividad->isCompletedByInscrito($inscritos2->id))
                                    <span class="si-badge si-badge--done">
                                        <i class="bi bi-check-circle-fill me-1"></i>Completada
                                    </span>
                                @else
                                    <form method="POST"
                                        action="{{ route('actividad.completar', encrypt($actividad->id)) }}">
                                        @csrf
                                        <input type="hidden" name="inscritos_id" value="{{ $inscritos2->id }}">
                                        <button type="submit" class="cc-btn cc-btn-sm cc-btn-outline-success">
                                            <i class="bi bi-check2-circle me-1"></i>Marcar como completada
                                        </button>
                                    </form>
                                @endif
                            @endif
                        @endrole

                        {{-- ── Editar / Eliminar (docente) ── --}}
                        @if (auth()->user()->hasRole('Docente'))
                            <button class="cc-btn cc-btn-sm cc-btn-icon" data-bs-toggle="modal"
                                data-bs-target="#modalEditarActividad-{{ $actividad->id }}" title="Editar actividad">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            {{-- FIX 10: form POST en lugar de href="" vacío --}}
                            <form action="{{ route('actividades.destroy', encrypt($actividad->id)) }}" method="POST"
                                onsubmit="return confirm('¿Eliminar esta actividad?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="cc-btn cc-btn-sm cc-btn-icon cc-btn-icon--danger"
                                    title="Eliminar actividad">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </form>
                        @endif

                    </div>{{-- /si-activity-actions --}}
                </div>{{-- /si-activity-card --}}

            @empty
                <div class="si-empty">
                    <div class="si-empty-icon"><i class="bi bi-clipboard2-x"></i></div>
                    <h5>No hay actividades disponibles</h5>
                    <p>Aún no se han agregado actividades a este subtema.</p>
                </div>
            @endforelse
        </div>
    </div>{{-- /si-section actividades --}}

</div>{{-- /si-wrap --}}



<div class="modal fade" id="modalEditarSubtema-{{ $subtema->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <form method="POST" action="{{ route('subtemas.update', encrypt($subtema->id)) }}"
            enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="modal-content cc-modal">
                <div class="cc-modal-header">
                    <div class="cc-modal-icon"><i class="bi bi-pencil-square"></i></div>
                    <div>
                        <h5 class="cc-modal-title">Editar Subtema</h5>
                        <small>{{ $subtema->titulo_subtema }}</small>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="row g-2">
                        <div class="col-12">
                            <div class="cc-field mb-2">
                                <label class="cc-label">Título del Subtema <span class="cc-req">*</span></label>
                                <div class="cc-input-wrap">
                                    <i class="bi bi-type cc-input-icon"></i>
                                    <input type="text" class="cc-input cc-input-with-icon" name="titulo"
                                        value="{{ $subtema->titulo_subtema }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="cc-field mb-2">
                                <label class="cc-label">Descripción</label>
                                <textarea class="cc-input cc-textarea" name="descripcion" rows="3"
                                    placeholder="Describe el contenido del subtema..." style="min-height:70px">{{ $subtema->descripcion }}</textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="cc-field mb-0">
                                <label class="cc-label">Imagen del Subtema</label>
                                @if ($subtema->imagen)
                                    <img src="{{ asset('storage/' . $subtema->imagen) }}" class="cc-img-preview mb-2"
                                        alt="Imagen actual" style="max-height:120px;width:auto">
                                    <small class="text-muted d-block mb-1">Imagen actual</small>
                                @else
                                    <div class="cc-img-placeholder mb-2" style="padding:.8rem">
                                        <i class="bi bi-image"></i><span>Sin imagen</span>
                                    </div>
                                @endif
                                <input type="file" class="cc-input" name="imagen" accept="image/*"
                                    id="siImgSubtema-{{ $subtema->id }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cc-modal-footer">
                    <button type="button" class="cc-btn cc-btn-outline" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="cc-btn cc-btn-primary">
                        <i class="bi bi-floppy-fill me-2"></i>Guardar Cambios
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ── Modal: Agregar Actividad ── --}}
<div class="modal fade" id="modalActividad-{{ $subtema->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <form method="POST" action="{{ route('actividades.store', encrypt($cursos->id)) }}"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="subtema_id" value="{{ $subtema->id }}">
            <div class="modal-content cc-modal">
                <div class="cc-modal-header">
                    <div class="cc-modal-icon cc-modal-icon--green"><i class="bi bi-clipboard-plus-fill"></i></div>
                    <div>
                        <h5 class="cc-modal-title">Agregar Actividad</h5>
                        <small>{{ $subtema->titulo_subtema }}</small>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="row g-2">
                        <div class="col-12">
                            <div class="cc-field">
                                <label class="cc-label">Título <span class="cc-req">*</span></label>
                                <input type="text" class="cc-input" name="titulo" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="cc-field">
                                <label class="cc-label">Descripción <span class="cc-req">*</span></label>
                                <textarea class="cc-input cc-textarea" name="descripcion" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="cc-field">
                                <label class="cc-label">Fecha de Habilitación <span class="cc-req">*</span></label>
                                <input type="date" class="cc-input" name="fecha_inicio" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="cc-field">
                                <label class="cc-label">Fecha de Vencimiento <span class="cc-req">*</span></label>
                                <input type="date" class="cc-input" name="fecha_limite" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="cc-field">
                                <label class="cc-label">Tipo de Actividad <span class="cc-req">*</span></label>
                                <select class="cc-input" name="tipo_actividad_id" required>
                                    <option value="" disabled selected>Selecciona un tipo</option>
                                    @foreach ($tiposActividades as $tipo)
                                        <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="cc-field">
                                <label class="cc-label">Archivo (opcional)</label>
                                <input type="file" class="cc-input" name="archivo"
                                    accept=".jpg,.jpeg,.png,.gif,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.pdf,.txt,.mp4,.mp3,.wav,.ogg,.zip,.rar">
                                <small class="text-muted" style="font-size:.75rem">Máx. 10MB</small>
                            </div>
                        </div>
                        {{-- Tipo de evaluación (único) --}}
                        <div class="col-12">
                            <div class="cc-field">
                                <label class="cc-label">Tipo de Evaluación <span class="cc-req">*</span></label>
                                <div class="si-eval-row">
                                    <select name="tipos_evaluacion[0][tipo_evaluacion_id]" class="cc-input" required>
                                        <option value="" disabled selected>Tipo de evaluación</option>
                                        @foreach ($tiposEvaluaciones as $te)
                                            <option value="{{ $te->id }}">{{ $te->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <input type="number" name="tipos_evaluacion[0][puntaje_maximo]"
                                        class="cc-input si-puntaje-input" placeholder="Puntaje" value="100"
                                        min="1" max="1000" required>
                                    <select name="tipos_evaluacion[0][es_obligatorio]" class="cc-input" required>
                                        <option value="1">Obligatorio</option>
                                        <option value="0">Opcional</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cc-modal-footer">
                    <button type="button" class="cc-btn cc-btn-outline" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="cc-btn cc-btn-success">
                        <i class="bi bi-clipboard-plus-fill me-2"></i>Agregar Actividad
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ── Modal: Agregar Recurso ── --}}
<div class="modal fade" id="modalRecurso-{{ $subtema->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <form method="POST" action="{{ route('CrearRecursosSubtemaPost', encrypt($subtema->id)) }}"
            enctype="multipart/form-data" class="si-recurso-form">
            @csrf
            <div class="modal-content cc-modal">
                <div class="cc-modal-header">
                    <div class="cc-modal-icon"><i class="bi bi-file-earmark-plus-fill"></i></div>
                    <div>
                        <h5 class="cc-modal-title">Agregar Recurso</h5>
                        <small>{{ $subtema->titulo_subtema }}</small>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="cc-field">
                                <label class="cc-label">Título del Recurso <span class="cc-req">*</span></label>
                                <input type="text" class="cc-input" name="tituloRecurso" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="cc-field">
                                <label class="cc-label">Tipo de Recurso <span class="cc-req">*</span></label>
                                <select class="cc-input" name="tipoRecurso" required>
                                    <optgroup label="Documentos">
                                        <option value="word">Word</option>
                                        <option value="excel">Excel</option>
                                        <option value="powerpoint">PowerPoint</option>
                                        <option value="pdf">PDF</option>
                                        <option value="docs">Google Docs</option>
                                    </optgroup>
                                    <optgroup label="Multimedia">
                                        <option value="imagen">Imagen</option>
                                        <option value="video">Video</option>
                                        <option value="audio">Audio</option>
                                    </optgroup>
                                    <optgroup label="Plataformas">
                                        <option value="youtube">YouTube</option>
                                        <option value="forms">Google Forms</option>
                                        <option value="drive">Google Drive</option>
                                        <option value="kahoot">Kahoot</option>
                                        <option value="canva">Canva</option>
                                        <option value="enlace">Enlace externo</option>
                                    </optgroup>
                                    <optgroup label="Otros">
                                        <option value="archivos-adjuntos">Archivos comprimidos</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="cc-field">
                                <label class="cc-label">Descripción <span class="cc-req">*</span></label>
                                <textarea class="cc-input cc-textarea" name="descripcionRecurso" rows="3"
                                    placeholder="Puedes pegar un enlace de YouTube para previsualizar el video..." required></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="cc-field">
                                <label class="cc-label">Archivo (opcional)</label>
                                <input type="file" class="cc-input si-file-input" name="archivo"
                                    accept=".jpg,.jpeg,.png,.gif,.svg,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.mp4,.avi,.mov,.mp3,.wav,.zip,.rar">
                                <div class="si-file-info" style="display:none"></div>
                                <small class="text-muted" style="font-size:.74rem">
                                    Imágenes, documentos, multimedia, comprimidos · Máx. 2MB
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cc-modal-footer">
                    <button type="button" class="cc-btn cc-btn-outline" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="cc-btn cc-btn-primary">
                        <i class="bi bi-plus-circle-fill me-2"></i>Agregar Recurso
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


@foreach ($subtema->recursos as $recurso)
    <div class="modal fade" id="modalEditarRecurso-{{ $recurso->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <form method="POST" action="{{ route('editarRecursosSubtemaPost', encrypt($recurso->id)) }}"
                enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-content cc-modal">
                    <div class="cc-modal-header">
                        <div class="cc-modal-icon"><i class="bi bi-pencil-square"></i></div>
                        <div>
                            <h5 class="cc-modal-title">Editar Recurso</h5>
                            <small>{{ $recurso->nombreRecurso }}</small>
                        </div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <div class="cc-field">
                                    <label class="cc-label">Título <span class="cc-req">*</span></label>
                                    <input type="text" class="cc-input" name="tituloRecurso"
                                        value="{{ $recurso->nombreRecurso }}" required maxlength="255">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="cc-field">
                                    <label class="cc-label">Tipo de Recurso</label>
                                    <select name="tipoRecurso" class="cc-input">
                                        @foreach (['word', 'excel', 'powerpoint', 'pdf', 'docs', 'imagen', 'video', 'audio', 'youtube', 'forms', 'drive', 'kahoot', 'canva', 'enlace', 'archivos-adjuntos'] as $t)
                                            <option value="{{ $t }}"
                                                {{ $recurso->tipoRecurso === $t ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('-', ' ', $t)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="cc-field">
                                    <label class="cc-label">Descripción <span class="cc-req">*</span></label>
                                    <textarea class="cc-input cc-textarea" name="descripcionRecurso" rows="3" required>{{ $recurso->descripcionRecursos }}</textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="cc-field">
                                    <label class="cc-label">Archivo (opcional)</label>
                                    @if ($recurso->archivoRecurso)
                                        <a href="{{ asset('storage/' . $recurso->archivoRecurso) }}" target="_blank"
                                            class="cc-btn cc-btn-sm cc-btn-outline-edit mb-2">
                                            <i class="bi bi-download me-1"></i>Descargar archivo actual
                                        </a>
                                    @endif
                                    <input type="file" class="cc-input si-file-input" name="archivo">
                                    <div class="cc-field mt-2">
                                        <label class="d-flex align-items-center gap-2"
                                            style="font-size:.82rem;cursor:pointer">
                                            <input type="checkbox" name="eliminarArchivo" value="1"
                                                id="siDelArch-{{ $recurso->id }}">
                                            Eliminar archivo actual
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="cc-modal-footer">
                        <button type="button" class="cc-btn cc-btn-outline"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="cc-btn cc-btn-primary">
                            <i class="bi bi-floppy-fill me-2"></i>Guardar Cambios
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach

{{-- ── Modales editar actividad + cuestionario ── --}}
@foreach ($subtema->actividades as $actividad)
    {{-- Modal editar actividad --}}
    <div class="modal fade" id="modalEditarActividad-{{ $actividad->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <form method="POST" action="{{ route('actividades.update', encrypt($actividad->id)) }}"
                enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-content cc-modal">
                    <div class="cc-modal-header">
                        <div class="cc-modal-icon"><i class="bi bi-pencil-square"></i></div>
                        <div>
                            <h5 class="cc-modal-title">Editar Actividad</h5>
                            <small>{{ $actividad->titulo }}</small>
                        </div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="row g-2">
                            <div class="col-12">
                                <div class="cc-field">
                                    <label class="cc-label">Título <span class="cc-req">*</span></label>
                                    <input type="text" class="cc-input" name="titulo"
                                        value="{{ old('titulo', $actividad->titulo) }}" required maxlength="255">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="cc-field">
                                    <label class="cc-label">Descripción</label>
                                    <textarea class="cc-input cc-textarea" name="descripcion" rows="3">{{ old('descripcion', $actividad->descripcion) }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="cc-field">
                                    <label class="cc-label">Fecha de Habilitación</label>
                                    <input type="date" class="cc-input" name="fecha_inicio"
                                        value="{{ old('fecha_inicio', $actividad->fecha_inicio?->format('Y-m-d')) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="cc-field">
                                    <label class="cc-label">Fecha de Vencimiento</label>
                                    <input type="date" class="cc-input" name="fecha_limite"
                                        value="{{ old('fecha_limite', $actividad->fecha_limite?->format('Y-m-d')) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="cc-field">
                                    <label class="cc-label">Tipo de Actividad <span class="cc-req">*</span></label>
                                    <select name="tipo_actividad_id" class="cc-input" required>
                                        <option value="">Seleccionar tipo...</option>
                                        @foreach ($tiposActividades as $tipo)
                                            <option value="{{ $tipo->id }}"
                                                {{ old('tipo_actividad_id', $actividad->tipo_actividad_id) == $tipo->id ? 'selected' : '' }}>
                                                {{ $tipo->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="cc-field">
                                    <label class="cc-label">Archivo (opcional)</label>
                                    @if ($actividad->archivo)
                                        <a href="{{ asset('storage/' . $actividad->archivo) }}" target="_blank"
                                            class="cc-btn cc-btn-sm cc-btn-outline-edit mb-2">
                                            <i class="bi bi-download me-1"></i>Descargar actual
                                        </a>
                                    @endif
                                    <input type="file" class="cc-input si-file-input" name="archivo"
                                        accept=".jpg,.jpeg,.png,.gif,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.pdf,.txt,.mp4,.mp3,.wav,.ogg,.zip,.rar">
                                    <small class="text-muted" style="font-size:.74rem">Máx. 10MB</small>
                                </div>
                            </div>
                            {{-- Tipo de evaluación (único) --}}
                            <div class="col-12">
                                <div class="cc-field">
                                    <label class="cc-label">Tipo de Evaluación <span class="cc-req">*</span></label>
                                    @php $te = $actividad->tiposEvaluacion->first(); @endphp
                                    <div class="si-eval-row">
                                        <select name="tipos_evaluacion[0][tipo_evaluacion_id]" class="cc-input" required>
                                            <option value="">Tipo...</option>
                                            @foreach ($tiposEvaluaciones as $t)
                                                <option value="{{ $t->id }}"
                                                    {{ old('tipos_evaluacion.0.tipo_evaluacion_id', $te?->id) == $t->id ? 'selected' : '' }}>
                                                    {{ $t->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="number" name="tipos_evaluacion[0][puntaje_maximo]"
                                            class="cc-input si-puntaje-input"
                                            value="{{ old('tipos_evaluacion.0.puntaje_maximo', $te?->pivot?->puntaje_maximo ?? 100) }}"
                                            min="1" max="1000" placeholder="Pts" required>
                                        <select name="tipos_evaluacion[0][es_obligatorio]" class="cc-input" required>
                                            <option value="1"
                                                {{ old('tipos_evaluacion.0.es_obligatorio', $te?->pivot?->es_obligatorio) == 1 ? 'selected' : '' }}>
                                                Obligatorio</option>
                                            <option value="0"
                                                {{ old('tipos_evaluacion.0.es_obligatorio', $te?->pivot?->es_obligatorio) == 0 ? 'selected' : '' }}>
                                                Opcional</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="cc-modal-footer">
                        <button type="button" class="cc-btn cc-btn-outline"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="cc-btn cc-btn-primary">
                            <i class="bi bi-floppy-fill me-2"></i>Guardar Cambios
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal cuestionario --}}
    @if ($actividad->tiposEvaluacion->contains('nombre', 'Cuestionario'))
        <div class="modal fade" id="modalCuestionario-{{ $actividad->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form method="POST"
                    action="{{ $actividad->cuestionario
                        ? route('cuestionarios.update', encrypt($actividad->cuestionario->id))
                        : route('cuestionarios.store', encrypt($actividad->id)) }}">
                    @csrf
                    @if ($actividad->cuestionario)
                        @method('PUT')
                    @endif
                    <div class="modal-content cc-modal">
                        <div class="cc-modal-header">
                            <div class="cc-modal-icon cc-modal-icon--green"><i class="bi bi-ui-checks"></i></div>
                            <div>
                                <h5 class="cc-modal-title">
                                    {{ $actividad->cuestionario ? 'Editar' : 'Crear' }} Cuestionario
                                </h5>
                                <small>{{ $actividad->titulo }}</small>
                            </div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="cc-field">
                                <label class="cc-label">Mostrar Resultados <span class="cc-req">*</span></label>
                                <select name="mostrar_resultados" class="cc-input" required>
                                    <option value="1"
                                        {{ $actividad->cuestionario?->mostrar_resultados ? 'selected' : '' }}>Sí
                                    </option>
                                    <option value="0"
                                        {{ $actividad->cuestionario && !$actividad->cuestionario->mostrar_resultados ? 'selected' : '' }}>
                                        No</option>
                                </select>
                            </div>
                            <div class="cc-field">
                                <label class="cc-label">Máximo de Intentos <span class="cc-req">*</span></label>
                                <input type="number" class="cc-input" name="max_intentos"
                                    value="{{ $actividad->cuestionario?->max_intentos ?? 3 }}" min="1"
                                    required>
                            </div>
                            <div class="cc-field">
                                <label class="cc-label">Tiempo Límite (minutos)</label>
                                <input type="number" class="cc-input" name="tiempo_limite"
                                    value="{{ $actividad->cuestionario?->tiempo_limite }}" min="1"
                                    placeholder="Opcional">
                            </div>
                        </div>
                        <div class="cc-modal-footer">
                            <button type="button" class="cc-btn cc-btn-outline"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="cc-btn cc-btn-success">
                                <i class="bi bi-floppy-fill me-2"></i>
                                {{ $actividad->cuestionario ? 'Guardar Cambios' : 'Crear Cuestionario' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endforeach


<script>
    (function() {

        /* ── 1. Toggle chevron descripción subtema ── */
        function bindDescToggles() {
            document.querySelectorAll('.si-desc-toggle').forEach(btn => {
                const panel = document.querySelector(btn.getAttribute('data-bs-target'));
                if (!panel) return;
                panel.addEventListener('show.bs.collapse', () => btn.setAttribute('aria-expanded', 'true'));
                panel.addEventListener('hide.bs.collapse', () => btn.setAttribute('aria-expanded',
                'false'));
            });
        }

        /* ── 2. Tipos de evaluación: agregar / eliminar / total ── */
        function calcTotal(container) {
            let total = 0;
            container.querySelectorAll('.si-puntaje-input').forEach(i => total += parseInt(i.value) || 0);
            const wrap = container.closest('.cc-field, .mb-3');
            if (wrap) {
                const span = wrap.querySelector('.si-total-val');
                if (span) span.textContent = total;
            }
        }

        // Genera la fila de tipo de evaluación — FIX 1: bi bi-* en JS
        function evalRow(index, tiposEval) {
            const opts = tiposEval.map(t =>
                `<option value="${t.id}">${t.nombre}</option>`
            ).join('');
            return `
            <div class="si-eval-row" data-index="${index}">
                <select name="tipos_evaluacion[${index}][tipo_evaluacion_id]" class="cc-input" required>
                    <option value="">Tipo...</option>${opts}
                </select>
                <input type="number" name="tipos_evaluacion[${index}][puntaje_maximo]"
                       class="cc-input si-puntaje-input"
                       value="100" min="1" max="1000" placeholder="Pts" required>
                <select name="tipos_evaluacion[${index}][es_obligatorio]" class="cc-input" required>
                    <option value="1">Obligatorio</option>
                    <option value="0">Opcional</option>
                </select>
                <button type="button" class="cc-btn cc-btn-sm cc-btn-icon cc-btn-icon--danger si-remove-eval">
                    <i class="bi bi-trash3-fill"></i>
                </button>
            </div>`;
        }

        // Reindexar filas tras eliminar
        function reindex(container) {
            container.querySelectorAll('.si-eval-row').forEach((row, i) => {
                row.setAttribute('data-index', i);
                row.querySelectorAll('[name]').forEach(el => {
                    el.name = el.name.replace(/\[\d+\]/, `[${i}]`);
                });
            });
        }

        function bindEvalContainers() {
            // Botón agregar fila
            document.querySelectorAll('.si-add-eval').forEach(btn => {
                if (btn._siBound) return;
                btn._siBound = true;
                btn.addEventListener('click', function() {
                    const container = document.getElementById(this.getAttribute('data-container'));
                    if (!container) return;
                    // Obtener opciones del primer select como referencia
                    const tipos = Array.from(
                        container.querySelector('select')?.options || []
                    ).filter(o => o.value).map(o => ({
                        id: o.value,
                        nombre: o.text
                    }));

                    const idx = container.querySelectorAll('.si-eval-row').length;
                    container.insertAdjacentHTML('beforeend', evalRow(idx, tipos));
                    calcTotal(container);
                });
            });

            // Delegación: eliminar fila + recalcular
            document.querySelectorAll('.si-eval-container').forEach(container => {
                if (container._siBound) return;
                container._siBound = true;

                container.addEventListener('click', function(e) {
                    const btn = e.target.closest('.si-remove-eval');
                    if (!btn) return;
                    const rows = container.querySelectorAll('.si-eval-row');
                    if (rows.length <= 1) {
                        // Mostrar toast si SweetAlert está disponible
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'info',
                                title: 'Mínimo 1 tipo de evaluación',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                        return;
                    }
                    btn.closest('.si-eval-row').remove();
                    reindex(container);
                    calcTotal(container);
                });

                // Recalcular al cambiar puntajes
                container.addEventListener('input', function(e) {
                    if (e.target.classList.contains('si-puntaje-input')) calcTotal(container);
                });

                // Calcular total inicial
                calcTotal(container);
            });
        }

        /* ── 3. FIX 11: Validación de archivos ── */
        const MAX_SIZE_RECURSO = 2 * 1024 * 1024; // 2MB
        const MAX_SIZE_ACTIVIDAD = 10 * 1024 * 1024; // 10MB

        const ALLOWED_EXT = [
            'jpg', 'jpeg', 'png', 'gif', 'svg', 'pdf',
            'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
            'mp4', 'avi', 'mov', 'mp3', 'wav', 'ogg',
            'zip', 'rar', 'txt'
        ];

        function validateFile(file, maxSize) {
            const ext = file.name.split('.').pop().toLowerCase();
            const errors = [];
            if (!ALLOWED_EXT.includes(ext))
                errors.push(`Tipo no permitido (.${ext}). Formatos: ${ALLOWED_EXT.join(', ')}`);
            if (file.size > maxSize)
                errors.push(
                    `Archivo demasiado grande (${(file.size/1024/1024).toFixed(1)}MB). Máx: ${maxSize/1024/1024}MB`
                    );
            return errors;
        }

        function bindFileInputs() {
            document.querySelectorAll('.si-file-input').forEach(input => {
                if (input._siBound) return;
                input._siBound = true;

                const isRecurso = input.closest('form')?.classList.contains('si-recurso-form');
                const maxSize = isRecurso ? MAX_SIZE_RECURSO : MAX_SIZE_ACTIVIDAD;

                input.addEventListener('change', function() {
                    // Limpiar mensaje anterior
                    const prev = this.parentElement.querySelector('.si-file-info');
                    if (prev) prev.style.display = 'none';

                    if (!this.files.length) return;
                    const file = this.files[0];
                    const errors = validateFile(file, maxSize);

                    if (errors.length) {
                        this.value = '';
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Archivo no válido',
                                html: '<ul style="text-align:left">' + errors.map(e =>
                                    `<li>${e}</li>`).join('') + '</ul>',
                                confirmButtonColor: '#145da0'
                            });
                        }
                    } else {
                        // Mostrar info del archivo
                        if (prev) {
                            prev.innerHTML = `<i class="bi bi-file-earmark-check-fill me-1"></i>
                                <strong>${file.name}</strong> — ${(file.size/1024/1024).toFixed(2)} MB`;
                            prev.style.display = 'block';
                        }
                    }
                });
            });

            // Validar también al submit
            document.querySelectorAll('.si-recurso-form').forEach(form => {
                if (form._siBound) return;
                form._siBound = true;
                form.addEventListener('submit', function(e) {
                    const input = this.querySelector('.si-file-input');
                    if (!input || !input.files.length) return;
                    const errors = validateFile(input.files[0], MAX_SIZE_RECURSO);
                    if (errors.length) {
                        e.preventDefault();
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Archivo no válido',
                                html: '<ul style="text-align:left">' + errors.map(e2 =>
                                    `<li>${e2}</li>`).join('') + '</ul>',
                                confirmButtonColor: '#145da0'
                            });
                        }
                    }
                });
            });
        }

        /* ── 4. Preview imagen en modales de edición ── */
        function bindImagePreviews() {
            document.querySelectorAll('input[name="imagen"]').forEach(input => {
                if (input._siBound) return;
                input._siBound = true;
                input.addEventListener('change', function() {
                    if (!this.files.length) return;
                    const reader = new FileReader();
                    reader.onload = e => {
                        const section = this.closest('.cc-field');
                        const preview = section?.querySelector('.cc-img-preview');
                        const placeholder = section?.querySelector('.cc-img-placeholder');
                        if (preview) {
                            preview.src = e.target.result;
                        } else if (placeholder) {
                            placeholder.outerHTML = `<img src="${e.target.result}"
                                class="cc-img-preview mb-2" alt="Vista previa">`;
                        }
                    };
                    reader.readAsDataURL(this.files[0]);
                });
            });
        }

        /* ── 5. Mover modales al body (evita z-index atrapado) ── */
        function hoistModals() {
            const prefixes = [
                'modalEditarSubtema-', 'modalActividad-', 'modalRecurso-',
                'modalEditarRecurso-', 'modalEditarActividad-', 'modalCuestionario-'
            ];
            prefixes.forEach(prefix => {
                document.querySelectorAll(`[id^="${prefix}"]`).forEach(modal => {
                    if (modal.parentElement !== document.body)
                        document.body.appendChild(modal);
                });
            });
        }

        /* ── Init ── */
        function init() {
            bindDescToggles();
            bindEvalContainers();
            bindFileInputs();
            bindImagePreviews();
            hoistModals();
        }

        document.addEventListener('DOMContentLoaded', init);
        document.addEventListener('livewire:load', init);
        document.addEventListener('turbo:load', init);

    })();
</script>
