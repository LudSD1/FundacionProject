<div class="si-wrap">

    {{-- ══ HEADER: Título + acciones ══ --}}
    <div class="si-header-compact">

        {{-- Imagen hero (si existe) --}}
        @if ($subtema->imagen)
            <div class="si-hero-img">
                <img src="{{ asset('storage/' . $subtema->imagen) }}" alt="{{ $subtema->titulo_subtema }}" loading="lazy">
                <div class="si-hero-img-overlay"></div>
            </div>
        @endif

        <div class="si-title-row">
            <div class="si-title-left">

                <h2 class="si-title">{{ $subtema->titulo_subtema }}</h2>
                @if ($subtema->descripcion)
                    <p class="si-desc-inline">{{ Str::limit($subtema->descripcion, 200) }}</p>
                @endif
            </div>

            {{-- Acciones docente: menú kebab compacto --}}
            @if (auth()->user()->hasRole('Docente') && $cursos->docente_id == auth()->user()->id)
                <div class="dropdown" onclick="event.stopPropagation()">
                    <button class="cl-kebab-btn" data-bs-toggle="dropdown" aria-expanded="false"
                            title="Opciones del subtema">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end cl-dropdown">
                        <li>
                            <button class="dropdown-item" data-bs-toggle="modal"
                                    data-bs-target="#modalActividad-{{ $subtema->id }}">
                                <i class="bi bi-clipboard-plus me-2 text-success"></i>Agregar Actividad
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item" data-bs-toggle="modal"
                                    data-bs-target="#modalRecurso-{{ $subtema->id }}">
                                <i class="bi bi-file-earmark-plus me-2 text-primary"></i>Agregar Recurso
                            </button>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <button class="dropdown-item" data-bs-toggle="modal"
                                    data-bs-target="#modalEditarSubtema-{{ $subtema->id }}">
                                <i class="bi bi-pencil me-2"></i>Editar Subtema
                            </button>
                        </li>
                        <li>
                            <form action="{{ route('subtemas.delete', encrypt($subtema->id)) }}" method="POST"
                                  class="cl-form-delete-sub">
                                @csrf @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-trash me-2"></i>Eliminar Subtema
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endif
        </div>
    </div>

    {{-- ══ RECURSOS ══ --}}
    @if($subtema->recursos->count() > 0)
    <div class="si-section-compact">
        <div class="si-section-label">
            <i class="fas fa-folder-open me-2"></i>Recursos
            <span class="si-count">{{ $subtema->recursos->count() }}</span>
        </div>

        @php
            $iconosRecurso = [
                'word' => 'bi-file-earmark-word-fill', 'excel' => 'bi-file-earmark-spreadsheet-fill',
                'powerpoint' => 'bi-file-earmark-slides-fill', 'pdf' => 'bi-file-earmark-pdf-fill',
                'docs' => 'bi-file-earmark-text-fill', 'imagen' => 'bi-file-earmark-image-fill',
                'video' => 'bi-camera-video-fill', 'audio' => 'bi-file-earmark-music-fill',
                'youtube' => 'bi-youtube', 'forms' => 'bi-ui-checks', 'drive' => 'bi-cloud-fill',
                'kahoot' => 'bi-controller', 'canva' => 'bi-brush-fill', 'enlace' => 'bi-link-45deg',
                'archivos-adjuntos' => 'bi-file-earmark-zip-fill',
            ];
            $coloresRecurso = [
                'word' => '#2b579a', 'excel' => '#217346', 'powerpoint' => '#d24726',
                'pdf' => '#e53935', 'docs' => '#4285f4', 'imagen' => '#8e24aa',
                'video' => '#f57c00', 'audio' => '#00897b', 'youtube' => '#ff0000',
                'forms' => '#00897b', 'drive' => '#4285f4', 'kahoot' => '#46178f',
                'canva' => '#00c4cc', 'enlace' => '#145da0', 'archivos-adjuntos' => '#5d4037',
            ];
        @endphp

        <div class="si-item-list">
            @foreach($subtema->recursos as $recurso)
                @php
                    $icon  = $iconosRecurso[$recurso->tipoRecurso] ?? 'bi-file-earmark-fill';
                    $color = $coloresRecurso[$recurso->tipoRecurso] ?? '#145da0';
                @endphp

                <div class="si-item-row">
                    <div class="si-item-icon" style="color:{{ $color }}">
                        <i class="bi {{ $icon }}"></i>
                    </div>
                    <div class="si-item-info">
                        <span class="si-item-name">{{ $recurso->nombreRecurso }}</span>
                        <span class="si-item-type">{{ ucfirst($recurso->tipoRecurso) }}</span>
                    </div>

                    {{-- Content preview for media --}}
                    @if (Str::contains($recurso->descripcionRecursos, ['<iframe', '<video', '<img']))
                        <button class="si-preview-btn" type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#siPreview-{{ $recurso->id }}">
                            <i class="bi bi-eye me-1"></i>Vista previa
                        </button>
                    @endif

                    <div class="si-item-actions">
                        {{-- Descargar --}}
                        @if ($recurso->archivoRecurso)
                            <a href="{{ asset('storage/' . $recurso->archivoRecurso) }}"
                               class="si-action-link" target="_blank" title="Descargar">
                                <i class="bi bi-download"></i>
                            </a>
                        @endif

                        {{-- Estudiante: marcar visto --}}
                        @if (auth()->user()->hasRole('Estudiante') && isset($inscritos2))
                            @if ($recurso->isViewedByInscrito($inscritos2->id))
                                <span class="si-badge si-badge--done" title="Visto">
                                    <i class="bi bi-check-circle-fill"></i>
                                </span>
                            @else
                                <form method="POST"
                                    action="{{ route('recurso.marcarVisto', encrypt($recurso->id)) }}">
                                    @csrf
                                    <input type="hidden" name="inscritos_id" value="{{ $inscritos2->id }}">
                                    <button type="submit" class="si-action-btn" title="Marcar como visto">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </form>
                            @endif
                        @endif

                        {{-- Docente: editar/eliminar --}}
                        @if (auth()->user()->hasRole('Docente') && $cursos->docente_id == auth()->user()->id)
                            <button class="si-action-btn" data-bs-toggle="modal"
                                data-bs-target="#modalEditarRecurso-{{ $recurso->id }}" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('eliminarRecursosSubtemaPost', encrypt($recurso->id)) }}"
                                method="POST" onsubmit="return confirm('¿Eliminar este recurso?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="si-action-btn si-action-btn--danger" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                {{-- Media preview (colapsable) --}}
                @if (Str::contains($recurso->descripcionRecursos, ['<iframe', '<video', '<img']))
                    <div class="collapse" id="siPreview-{{ $recurso->id }}">
                        <div class="si-media-wrap">
                            {!! $recurso->descripcionRecursos !!}
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    @endif

    {{-- ══ ACTIVIDADES ══ --}}
    @if($subtema->actividades->count() > 0)
    <div class="si-section-compact">
        <div class="si-section-label si-section-label--orange">
            <i class="bi bi-clipboard2-check-fill me-2"></i>Actividades
            <span class="si-count si-count--orange">{{ $subtema->actividades->count() }}</span>
        </div>

        <div class="si-item-list">
            @foreach($subtema->actividades as $actividad)
            <div class="si-item-row si-item-row--activity">
                <div class="si-item-icon si-item-icon--orange">
                    <i class="bi bi-clipboard2-check"></i>
                </div>
                <div class="si-item-info">
                    <span class="si-item-name">{{ $actividad->titulo }}</span>
                    <div class="si-item-meta">
                        <span><i class="bi bi-tag-fill me-1"></i>{{ $actividad->tipoActividad->nombre }}</span>
                        <span><i class="bi bi-clock me-1"></i>Vence:
                            {{ $actividad->fecha_limite ? $actividad->fecha_limite->format('d/m/Y') : 'Sin límite' }}
                        </span>
                    </div>
                </div>

                <div class="si-item-actions">
                    @if ($actividad->tiposEvaluacion->contains('nombre', 'Cuestionario'))
                        {{-- Docente: cuestionario --}}
                        @role('Docente')
                            <button class="si-action-btn" data-bs-toggle="modal"
                                data-bs-target="#modalCuestionario-{{ $actividad->id }}" title="Cuestionario">
                                <i class="bi bi-ui-checks"></i>
                            </button>
                            @if ($actividad->cuestionario)
                                <a href="{{ route('cuestionarios.index', encrypt($actividad->cuestionario->id)) }}"
                                   class="si-action-btn" title="Administrar">
                                    <i class="bi bi-gear"></i>
                                </a>
                                <a href="{{ route('rankingQuizz', encrypt($actividad->cuestionario->id)) }}"
                                   class="si-action-btn" title="Resultados">
                                    <i class="bi bi-bar-chart"></i>
                                </a>
                            @endif
                            <form method="POST"
                                action="{{ route($actividad->es_publica ? 'actividades.ocultar' : 'actividades.mostrar', encrypt($actividad->id)) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="si-action-btn" title="{{ $actividad->es_publica ? 'Ocultar' : 'Mostrar' }}">
                                    <i class="bi bi-{{ $actividad->es_publica ? 'eye-slash' : 'eye' }}"></i>
                                </button>
                            </form>
                        @endrole

                        {{-- Estudiante: cuestionario --}}
                        @role('Estudiante')
                            @if ($actividad->cuestionario)
                                <a href="{{ route('cuestionario.mostrar', encrypt($actividad->cuestionario->id)) }}"
                                   class="cc-btn cc-btn-sm cc-btn-primary">
                                    <i class="bi bi-play-fill me-1"></i>Responder
                                </a>
                                <a href="{{ route('rankingQuizz', encrypt($actividad->cuestionario->id)) }}"
                                   class="si-action-btn" title="Resultados">
                                    <i class="bi bi-bar-chart"></i>
                                </a>
                            @endif
                        @endrole
                    @else
                        {{-- Docente: tarea --}}
                        @hasrole('Docente')
                            <a href="{{ route('calificarT', encrypt($actividad->id)) }}"
                               class="si-action-btn" title="Calificar">
                                <i class="bi bi-calculator"></i>
                            </a>
                            <form method="POST"
                                action="{{ route($actividad->es_publica ? 'actividades.ocultar' : 'actividades.mostrar', encrypt($actividad->id)) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="si-action-btn" title="{{ $actividad->es_publica ? 'Ocultar' : 'Mostrar' }}">
                                    <i class="bi bi-{{ $actividad->es_publica ? 'eye-slash' : 'eye' }}"></i>
                                </button>
                            </form>
                        @endhasrole

                        {{-- Estudiante: tarea --}}
                        @role('Estudiante')
                            <a href="{{ route('actividad.show', encrypt($actividad->id)) }}"
                               class="cc-btn cc-btn-sm cc-btn-primary">
                                <i class="bi bi-eye-fill me-1"></i>Ver
                            </a>
                        @endrole
                    @endif

                    {{-- Estudiante: estado completado --}}
                    @role('Estudiante')
                        @if (isset($inscritos2))
                            @if ($actividad->isCompletedByInscrito($inscritos2->id))
                                <span class="si-badge si-badge--done">
                                    <i class="bi bi-check-circle-fill"></i>
                                </span>
                            @else
                                <form method="POST"
                                    action="{{ route('actividad.completar', encrypt($actividad->id)) }}">
                                    @csrf
                                    <input type="hidden" name="inscritos_id" value="{{ $inscritos2->id }}">
                                    <button type="submit" class="si-action-btn si-action-btn--success" title="Completar">
                                        <i class="bi bi-check2-circle"></i>
                                    </button>
                                </form>
                            @endif
                        @endif
                    @endrole

                    {{-- Docente: editar / eliminar actividad --}}
                    @if (auth()->user()->hasRole('Docente'))
                        <button class="si-action-btn" data-bs-toggle="modal"
                            data-bs-target="#modalEditarActividad-{{ $actividad->id }}" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <form action="{{ route('actividades.destroy', encrypt($actividad->id)) }}" method="POST"
                            onsubmit="return confirm('¿Eliminar esta actividad?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="si-action-btn si-action-btn--danger" title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Empty state: sin recursos ni actividades --}}
    @if($subtema->recursos->count() === 0 && $subtema->actividades->count() === 0)
        <div class="si-empty-compact">
            <i class="bi bi-inbox me-2"></i>
            <span>No hay contenido disponible en esta lección.</span>
        </div>
    @endif

</div>{{-- /si-wrap --}}


{{-- ╔══════════════════════════════════════════════════╗
     ║  MODALES (se mueven al body via JS)             ║
     ╚══════════════════════════════════════════════════╝ --}}

{{-- Modal: Editar Subtema --}}
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

{{-- Modal: Agregar Actividad --}}
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
                                <input type="file" class="cc-input si-file-input" name="archivo"
                                    accept=".jpg,.jpeg,.png,.gif,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.pdf,.txt,.mp4,.mp3,.wav,.ogg,.zip,.rar">
                                <small class="text-muted" style="font-size:.75rem">Máx. 10MB</small>
                            </div>
                        </div>
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

{{-- Modal: Agregar Recurso --}}
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

{{-- Modales: Editar Recurso --}}
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
                                            <input type="checkbox" name="eliminarArchivo" value="1">
                                            Eliminar archivo actual
                                        </label>
                                    </div>
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
@endforeach

{{-- Modales: Editar Actividad + Cuestionario --}}
@foreach ($subtema->actividades as $actividad)
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
                        <button type="button" class="cc-btn cc-btn-outline" data-bs-dismiss="modal">Cancelar</button>
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
                                        {{ $actividad->cuestionario?->mostrar_resultados ? 'selected' : '' }}>Sí</option>
                                    <option value="0"
                                        {{ $actividad->cuestionario && !$actividad->cuestionario->mostrar_resultados ? 'selected' : '' }}>
                                        No</option>
                                </select>
                            </div>
                            <div class="cc-field">
                                <label class="cc-label">Máximo de Intentos <span class="cc-req">*</span></label>
                                <input type="number" class="cc-input" name="max_intentos"
                                    value="{{ $actividad->cuestionario?->max_intentos ?? 3 }}" min="1" required>
                            </div>
                            <div class="cc-field">
                                <label class="cc-label">Tiempo Límite (minutos)</label>
                                <input type="number" class="cc-input" name="tiempo_limite"
                                    value="{{ $actividad->cuestionario?->tiempo_limite }}" min="1"
                                    placeholder="Opcional">
                            </div>
                        </div>
                        <div class="cc-modal-footer">
                            <button type="button" class="cc-btn cc-btn-outline" data-bs-dismiss="modal">Cancelar</button>
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
    /* ── 1. File validation ── */
    const MAX_SIZE_RECURSO   = 2 * 1024 * 1024;
    const MAX_SIZE_ACTIVIDAD = 10 * 1024 * 1024;
    const ALLOWED_EXT = ['jpg','jpeg','png','gif','svg','pdf','doc','docx','xls','xlsx','ppt','pptx','mp4','avi','mov','mp3','wav','ogg','zip','rar','txt'];

    function validateFile(file, maxSize) {
        const ext = file.name.split('.').pop().toLowerCase();
        const errors = [];
        if (!ALLOWED_EXT.includes(ext)) errors.push(`Tipo no permitido (.${ext}).`);
        if (file.size > maxSize) errors.push(`Archivo demasiado grande (${(file.size/1024/1024).toFixed(1)}MB). Máx: ${maxSize/1024/1024}MB`);
        return errors;
    }

    function bindFileInputs() {
        document.querySelectorAll('.si-file-input').forEach(input => {
            if (input._siBound) return;
            input._siBound = true;
            const isRecurso = input.closest('form')?.classList.contains('si-recurso-form');
            const maxSize = isRecurso ? MAX_SIZE_RECURSO : MAX_SIZE_ACTIVIDAD;
            input.addEventListener('change', function() {
                const prev = this.parentElement.querySelector('.si-file-info');
                if (prev) prev.style.display = 'none';
                if (!this.files.length) return;
                const errors = validateFile(this.files[0], maxSize);
                if (errors.length) {
                    this.value = '';
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'error', title: 'Archivo no válido', html: errors.map(e => `<p>${e}</p>`).join(''), confirmButtonColor: '#145da0' });
                    }
                } else if (prev) {
                    prev.innerHTML = `<i class="bi bi-file-earmark-check-fill me-1"></i><strong>${this.files[0].name}</strong> — ${(this.files[0].size/1024/1024).toFixed(2)} MB`;
                    prev.style.display = 'block';
                }
            });
        });
    }

    /* ── 2. Image preview ── */
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
                    if (preview) preview.src = e.target.result;
                    else if (placeholder) placeholder.outerHTML = `<img src="${e.target.result}" class="cc-img-preview mb-2" alt="Vista previa">`;
                };
                reader.readAsDataURL(this.files[0]);
            });
        });
    }

    /* ── 3. Hoist modals to body ── */
    function hoistModals() {
        ['modalEditarSubtema-','modalActividad-','modalRecurso-','modalEditarRecurso-','modalEditarActividad-','modalCuestionario-'].forEach(prefix => {
            document.querySelectorAll(`[id^="${prefix}"]`).forEach(modal => {
                if (modal.parentElement !== document.body) document.body.appendChild(modal);
            });
        });
    }

    /* ── 4. Delete subtema confirmation ── */
    function bindSubDelete() {
        document.querySelectorAll('.cl-form-delete-sub').forEach(form => {
            if (form._siBound) return;
            form._siBound = true;
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning', title: '¿Eliminar este subtema?',
                        text: 'Se eliminará todo su contenido.',
                        showCancelButton: true, confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#64748b', confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar'
                    }).then(r => { if (r.isConfirmed) form.submit(); });
                } else { if (confirm('¿Eliminar?')) form.submit(); }
            });
        });
    }

    function init() {
        bindFileInputs();
        bindImagePreviews();
        hoistModals();
        bindSubDelete();
    }

    document.addEventListener('DOMContentLoaded', init);
    document.addEventListener('livewire:load', init);
    document.addEventListener('turbo:load', init);
})();
</script>
