    @extends ('layout')

    @section('content')
    <div class="container my-4">

        {{-- Errores de validación --}}
        @if ($errors->any())
            <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    <strong>Por favor corrige los siguientes errores:</strong>
                </div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li class="small">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="tbl-card shadow-lg">

            {{-- Hero --}}
            <div class="tbl-card-hero">
                <div class="tbl-hero-left">
                    <a href="{{ route('Curso', $cursos->codigoCurso ?? $cursos->id) }}"
                        class="tbl-hero-btn tbl-hero-btn-glass prt-back-btn mb-2">
                        <i class="bi bi-arrow-left-circle-fill"></i> Volver al Curso
                    </a>
                    <div class="tbl-hero-eyebrow">
                        <i class="bi bi-pencil-square"></i> Editar
                    </div>
                    <h2 class="tbl-hero-title">{{ ucfirst(strtolower($cursos->nombreCurso)) }}</h2>
                    <p class="tbl-hero-sub">Modifica la información del curso usando las pestañas.</p>
                </div>
                <div class="tbl-hero-controls">
                    <span class="ec-role-badge">
                        <i class="bi bi-shield-fill me-1"></i> {{ auth()->user()->getRoleNames()->first() }}
                    </span>
                </div>
            </div>

            {{-- ════ PESTAÑAS ════ --}}
            <div class="ec-tabs-header">
                <nav class="ec-tabs-nav" id="editTabs">
                    <button class="ec-tab-btn active" data-tab="general" type="button">
                        <i class="bi bi-info-circle-fill"></i>
                        <span>General</span>
                    </button>
                    <button class="ec-tab-btn" data-tab="config" type="button">
                        <i class="bi bi-gear-fill"></i>
                        <span>Configuración</span>
                    </button>
                    @if (auth()->user()->hasRole('Administrador'))
                        <button class="ec-tab-btn" data-tab="admin" type="button">
                            <i class="bi bi-sliders"></i>
                            <span>Administración</span>
                        </button>
                    @endif
                    <button class="ec-tab-btn" data-tab="recursos" type="button">
                        <i class="bi bi-folder-fill"></i>
                        <span>Recursos</span>
                    </button>
                    <button class="ec-tab-btn" data-tab="categorias" type="button">
                        <i class="bi bi-tag-fill"></i>
                        <span>Categorías</span>
                    </button>
                </nav>
            </div>

            {{-- ════ CONTENIDO DE TABS ════ --}}

            {{-- ── TAB 1: General ── --}}
            <div class="ec-tab-pane active" data-tab-content="general">
                <form action="{{ route('editarCursoPost', $cursos->id) }}" method="POST"
                    enctype="multipart/form-data" class="ec-tab-form" data-confirm="¿Guardar los cambios generales?">
                    @csrf
                    {{-- Hidden fields for non-visible tabs --}}
                    <input type="hidden" name="fecha_ini" value="{{ $cursos->fecha_ini ? \Carbon\Carbon::parse($cursos->fecha_ini)->format('Y-m-d\TH:i') : '' }}">
                    <input type="hidden" name="fecha_fin" value="{{ $cursos->fecha_fin ? \Carbon\Carbon::parse($cursos->fecha_fin)->format('Y-m-d\TH:i') : '' }}">
                    <input type="hidden" name="formato" value="{{ $cursos->formato }}">
                    <input type="hidden" name="tipo" value="{{ $cursos->tipo }}">
                    @if (!auth()->user()->hasRole('Administrador'))
                        <input type="hidden" name="docente_id" value="{{ auth()->user()->id }}">
                    @else
                        <input type="hidden" name="docente_id" value="{{ $cursos->docente_id }}">
                        <input type="hidden" name="duracion" value="{{ $cursos->duracion }}">
                        <input type="hidden" name="cupos" value="{{ $cursos->cupos }}">
                        <input type="hidden" name="precio" value="{{ $cursos->precio }}">
                        <input type="hidden" name="visibilidad" value="{{ $cursos->visibilidad }}">
                    @endif

                    <div class="p-4 p-md-5">
                        <div class="ec-section-header mb-4">
                            <i class="bi bi-info-circle-fill"></i>
                            <div>
                                <h5 class="ec-section-title">Información General</h5>
                                <p class="ec-section-sub">Nombre, descripción y público objetivo del curso.</p>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-bold text-muted small text-uppercase">Nombre del Curso</label>
                                @if (auth()->user()->hasRole('Administrador'))
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-bookmark-fill text-primary"></i></span>
                                        <input type="text" name="nombre" class="form-control bg-light @error('nombre') is-invalid @enderror"
                                            value="{{ old('nombre', $cursos->nombreCurso) }}" required>
                                    </div>
                                    @error('nombre')
                                        <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                @else
                                    <input type="hidden" name="nombre" value="{{ $cursos->nombreCurso }}">
                                    <input type="text" class="form-control bg-light" value="{{ $cursos->nombreCurso }}" disabled>
                                @endif
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold text-muted small text-uppercase">Descripción</label>
                                <textarea name="descripcion" id="descripcionTA" class="form-control bg-light @error('descripcion') is-invalid @enderror"
                                    rows="4" maxlength="500" required placeholder="Describe los objetivos...">{{ old('descripcion', $cursos->descripcionC) }}</textarea>
                                <div class="d-flex justify-content-end mt-1">
                                    <span class="badge bg-light text-muted border" id="charCount">0/500</span>
                                </div>
                                @error('descripcion')
                                    <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Edad Dirigida</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-person-check text-primary"></i></span>
                                    <select id="edad_id" name="edad_id" class="form-select bg-light" onchange="actualizarNiveles()">
                                        <option value="">Seleccione un rango</option>
                                        @foreach (['3-5' => '👶 3 a 5 años', '6-8' => '🧒 6 a 8 años', '9-12' => '👦 9 a 12 años', '13-15' => '👨 13 a 15 años', '16-18' => '🎓 16 a 18 años', '18+' => '👔 18 años o más'] as $val => $label)
                                            <option value="{{ $val }}" {{ old('edad_id', $cursos->edad_dirigida) == $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Nivel Académico</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-bar-chart-fill text-primary"></i></span>
                                    <select id="nivel_id" name="nivel_id" class="form-select bg-light">
                                        <option value="">Seleccione un nivel</option>
                                        @if ($cursos->nivel)
                                            <option value="{{ $cursos->nivel }}" selected>{{ $cursos->nivel }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="ec-tab-footer">
                            <button type="submit" class="tbl-hero-btn tbl-hero-btn-primary px-5 py-2">
                                <i class="bi bi-save-fill me-2"></i> Guardar General
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- ── TAB 2: Configuración ── --}}
            <div class="ec-tab-pane" data-tab-content="config">
                <form action="{{ route('editarCursoPost', $cursos->id) }}" method="POST"
                    enctype="multipart/form-data" class="ec-tab-form" data-confirm="¿Guardar la configuración?">
                    @csrf
                    {{-- Hidden fields para los otros tabs --}}
                    <input type="hidden" name="nombre" value="{{ $cursos->nombreCurso }}">
                    <input type="hidden" name="descripcion" value="{{ $cursos->descripcionC }}">
                    <input type="hidden" name="edad_id" value="{{ $cursos->edad_dirigida }}">
                    <input type="hidden" name="nivel_id" value="{{ $cursos->nivel }}">
                    @if (!auth()->user()->hasRole('Administrador'))
                        <input type="hidden" name="docente_id" value="{{ auth()->user()->id }}">
                    @else
                        <input type="hidden" name="docente_id" value="{{ $cursos->docente_id }}">
                        <input type="hidden" name="duracion" value="{{ $cursos->duracion }}">
                        <input type="hidden" name="cupos" value="{{ $cursos->cupos }}">
                        <input type="hidden" name="precio" value="{{ $cursos->precio }}">
                        <input type="hidden" name="visibilidad" value="{{ $cursos->visibilidad }}">
                    @endif

                    <div class="p-4 p-md-5">
                        <div class="ec-section-header mb-4">
                            <i class="bi bi-gear-fill"></i>
                            <div>
                                <h5 class="ec-section-title">Configuración Logística</h5>
                                <p class="ec-section-sub">Fechas, formato de clases y tipo de evento.</p>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Fecha y Hora de Inicio</label>
                                <input type="datetime-local" name="fecha_ini" class="form-control bg-light @error('fecha_ini') is-invalid @enderror" required
                                    value="{{ old('fecha_ini', $cursos->fecha_ini ? \Carbon\Carbon::parse($cursos->fecha_ini)->format('Y-m-d\TH:i') : '') }}">
                                @error('fecha_ini')
                                    <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Fecha y Hora de Fin</label>
                                <input type="datetime-local" name="fecha_fin" class="form-control bg-light @error('fecha_fin') is-invalid @enderror" required
                                    value="{{ old('fecha_fin', $cursos->fecha_fin ? \Carbon\Carbon::parse($cursos->fecha_fin)->format('Y-m-d\TH:i') : '') }}">
                                @error('fecha_fin')
                                    <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Formato de Impartición</label>
                                @if (auth()->user()->hasRole('Administrador'))
                                    <select name="formato" class="form-select bg-light @error('formato') is-invalid @enderror">
                                        @foreach (['Virtual'] as $fmt)
                                            <option value="{{ $fmt }}" {{ old('formato', $cursos->formato) == $fmt ? 'selected' : '' }}>
                                                {{ $fmt == 'Virtual' ? '💻 Virtual' : $fmt }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="hidden" name="formato" value="{{ $cursos->formato }}">
                                    <input type="text" class="form-control bg-light" value="{{ $cursos->formato }}" disabled>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Tipo de Actividad</label>
                                @if (auth()->user()->hasRole('Administrador'))
                                    <select name="tipo" class="form-select bg-light @error('tipo') is-invalid @enderror" onchange="toggleCodigoCurso(this.value)">
                                        <option value="curso" {{ old('tipo', $cursos->tipo) == 'curso' ? 'selected' : '' }}>📚 Curso Regular</option>
                                        <option value="congreso" {{ old('tipo', $cursos->tipo) == 'congreso' ? 'selected' : '' }}>📅 Evento / Congreso</option>
                                    </select>
                                @else
                                    <input type="hidden" name="tipo" value="{{ $cursos->tipo }}">
                                    <input type="text" class="form-control bg-light" value="{{ $cursos->tipo == 'congreso' ? 'Evento' : 'Curso' }}" disabled>
                                @endif
                            </div>

                            <div class="col-12" id="codigoCursoGroup" style="display: {{ $cursos->tipo == 'congreso' || $cursos->codigoCurso ? 'block' : 'none' }};">
                                <label class="form-label fw-bold text-muted small text-uppercase">Código del Curso/Congreso (Slug para URL)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-link-45deg text-primary"></i></span>
                                    <input type="text" name="codigoCurso" id="codigoCursoInput" class="form-control bg-light"
                                        value="{{ old('codigoCurso', $cursos->codigoCurso) }}" placeholder="ej: curso-programacion-basica"
                                        {{ $cursos->tipo == 'congreso' ? 'required' : '' }}>
                                </div>
                                <small class="text-muted">Debe ser único y sin espacios.</small>
                            </div>
                        </div>

                        <div class="ec-tab-footer">
                            <button type="submit" class="tbl-hero-btn tbl-hero-btn-primary px-5 py-2">
                                <i class="bi bi-save-fill me-2"></i> Guardar Configuración
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- ── TAB 3: Administración (Solo Admin) ── --}}
            @if (auth()->user()->hasRole('Administrador'))
                <div class="ec-tab-pane" data-tab-content="admin">
                    <form action="{{ route('editarCursoPost', $cursos->id) }}" method="POST"
                        enctype="multipart/form-data" class="ec-tab-form" data-confirm="¿Guardar los datos administrativos?">
                        @csrf
                        {{-- Hidden fields --}}
                        <input type="hidden" name="nombre" value="{{ $cursos->nombreCurso }}">
                        <input type="hidden" name="descripcion" value="{{ $cursos->descripcionC }}">
                        <input type="hidden" name="edad_id" value="{{ $cursos->edad_dirigida }}">
                        <input type="hidden" name="nivel_id" value="{{ $cursos->nivel }}">
                        <input type="hidden" name="fecha_ini" value="{{ $cursos->fecha_ini ? \Carbon\Carbon::parse($cursos->fecha_ini)->format('Y-m-d\TH:i') : '' }}">
                        <input type="hidden" name="fecha_fin" value="{{ $cursos->fecha_fin ? \Carbon\Carbon::parse($cursos->fecha_fin)->format('Y-m-d\TH:i') : '' }}">
                        <input type="hidden" name="formato" value="{{ $cursos->formato }}">
                        <input type="hidden" name="tipo" value="{{ $cursos->tipo }}">

                        <div class="p-4 p-md-5">
                            <div class="ec-section-header mb-4">
                                <i class="bi bi-sliders"></i>
                                <div>
                                    <h5 class="ec-section-title">Gestión Administrativa</h5>
                                    <p class="ec-section-sub">Asignación de docente, cupos y precios.</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Docente Asignado</label>
                                    <select name="docente_id" class="form-select bg-light @error('docente_id') is-invalid @enderror" required>
                                        @foreach ($docente as $doc)
                                            <option value="{{ $doc->id }}" {{ old('docente_id', $cursos->docente_id) == $doc->id ? 'selected' : '' }}>
                                                {{ $doc->name }} {{ $doc->lastname1 }} {{ $doc->lastname2 }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('docente_id')
                                        <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Visibilidad</label>
                                    <select name="visibilidad" class="form-select bg-light">
                                        <option value="publico" {{ old('visibilidad', $cursos->visibilidad) == 'publico' ? 'selected' : '' }}>🌐 Público</option>
                                        <option value="privado" {{ old('visibilidad', $cursos->visibilidad) == 'privado' ? 'selected' : '' }}>🔒 Privado</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Duración (Horas)</label>
                                    <input type="number" name="duracion" class="form-control bg-light @error('duracion') is-invalid @enderror"
                                        value="{{ old('duracion', $cursos->duracion) }}" min="1" required>
                                    @error('duracion')
                                        <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Cupos</label>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="cupos_ilimitados"
                                            {{ old('cupos', $cursos->cupos) == 0 ? 'checked' : '' }}
                                            onchange="toggleCuposIlimitados(this)">
                                        <label class="form-check-label small fw-semibold" for="cupos_ilimitados">Ilimitados</label>
                                    </div>
                                    <input type="number" name="cupos" id="cupos_input" class="form-control bg-light"
                                        value="{{ old('cupos', $cursos->cupos) == 0 ? '' : old('cupos', $cursos->cupos) }}"
                                        min="1" {{ old('cupos', $cursos->cupos) == 0 ? 'disabled' : '' }} required>
                                    <input type="hidden" name="cupos" id="cupos_hidden" value="0"
                                        {{ old('cupos', $cursos->cupos) == 0 ? '' : 'disabled' }}>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Precio (Bs)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light fw-bold text-primary">Bs</span>
                                        <input type="number" name="precio" class="form-control bg-light @error('precio') is-invalid @enderror"
                                            value="{{ old('precio', $cursos->precio) }}" step="0.01" min="0" required>
                                    </div>
                                    @error('precio')
                                        <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="ec-tab-footer">
                                <button type="submit" class="tbl-hero-btn tbl-hero-btn-primary px-5 py-2">
                                    <i class="bi bi-save-fill me-2"></i> Guardar Administración
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif

            {{-- ── TAB 4: Recursos ── --}}
            <div class="ec-tab-pane" data-tab-content="recursos">
                <form action="{{ route('editarCursoPost', $cursos->id) }}" method="POST"
                    enctype="multipart/form-data" class="ec-tab-form" data-confirm="¿Guardar los recursos?">
                    @csrf
                    {{-- Hidden fields --}}
                    <input type="hidden" name="nombre" value="{{ $cursos->nombreCurso }}">
                    <input type="hidden" name="descripcion" value="{{ $cursos->descripcionC }}">
                    <input type="hidden" name="edad_id" value="{{ $cursos->edad_dirigida }}">
                    <input type="hidden" name="nivel_id" value="{{ $cursos->nivel }}">
                    <input type="hidden" name="fecha_ini" value="{{ $cursos->fecha_ini ? \Carbon\Carbon::parse($cursos->fecha_ini)->format('Y-m-d\TH:i') : '' }}">
                    <input type="hidden" name="fecha_fin" value="{{ $cursos->fecha_fin ? \Carbon\Carbon::parse($cursos->fecha_fin)->format('Y-m-d\TH:i') : '' }}">
                    <input type="hidden" name="formato" value="{{ $cursos->formato }}">
                    <input type="hidden" name="tipo" value="{{ $cursos->tipo }}">
                    @if (!auth()->user()->hasRole('Administrador'))
                        <input type="hidden" name="docente_id" value="{{ auth()->user()->id }}">
                    @else
                        <input type="hidden" name="docente_id" value="{{ $cursos->docente_id }}">
                        <input type="hidden" name="duracion" value="{{ $cursos->duracion }}">
                        <input type="hidden" name="cupos" value="{{ $cursos->cupos }}">
                        <input type="hidden" name="precio" value="{{ $cursos->precio }}">
                        <input type="hidden" name="visibilidad" value="{{ $cursos->visibilidad }}">
                    @endif

                    <div class="p-4 p-md-5">
                        <div class="ec-section-header mb-4">
                            <i class="bi bi-folder-fill"></i>
                            <div>
                                <h5 class="ec-section-title">Recursos del Curso</h5>
                                <p class="ec-section-sub">Material PDF e imagen publicitaria.</p>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Contenido Programático (PDF)</label>
                                <div class="ec-file-drop-area p-4 text-center border rounded-4 bg-light">
                                    <i class="bi bi-file-earmark-pdf-fill fs-1 text-danger mb-2 d-block"></i>
                                    <input type="file" name="archivo" accept=".pdf" class="form-control form-control-sm">
                                    <small class="text-muted mt-2 d-block">Selecciona un archivo PDF actualizado</small>
                                </div>
                                @if ($cursos->archivoContenidodelCurso)
                                    <div class="mt-3 p-3 bg-white border rounded-3 d-flex align-items-center">
                                        <i class="bi bi-file-earmark-check-fill text-success fs-4 me-3"></i>
                                        <div class="overflow-hidden">
                                            <span class="d-block small text-muted">Archivo actual:</span>
                                            <a href="{{ asset('storage/' . $cursos->archivoContenidodelCurso) }}" target="_blank"
                                                class="small fw-bold text-primary text-truncate d-block">
                                                {{ basename($cursos->archivoContenidodelCurso) }}
                                            </a>
                                        </div>
                                        <div class="form-check form-switch ms-auto">
                                            <input class="form-check-input" type="checkbox" name="eliminar_archivo" id="eliminar_archivo">
                                            <label class="form-check-label text-danger small" for="eliminar_archivo">Eliminar</label>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Imagen de Portada</label>
                                <div class="ec-file-drop-area p-4 text-center border rounded-4 bg-light">
                                    <i class="bi bi-image-fill fs-1 text-primary mb-2 d-block"></i>
                                    <input type="file" name="imagen" accept="image/*" class="form-control form-control-sm">
                                    <small class="text-muted mt-2 d-block">Imagen recomendada: 1200x800px</small>
                                </div>
                                @if ($cursos->imagen)
                                    <div class="mt-3 p-3 bg-white border rounded-3 d-flex align-items-center">
                                        <img src="{{ asset('storage/' . $cursos->imagen) }}" class="rounded border me-3"
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                        <div><span class="d-block small text-muted">Imagen actual activa</span></div>
                                        <div class="form-check form-switch ms-auto">
                                            <input class="form-check-input" type="checkbox" name="eliminar_imagen" id="eliminar_imagen">
                                            <label class="form-check-label text-danger small" for="eliminar_imagen">Eliminar</label>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="ec-tab-footer">
                            <button type="submit" class="tbl-hero-btn tbl-hero-btn-primary px-5 py-2">
                                <i class="bi bi-save-fill me-2"></i> Guardar Recursos
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- ── TAB 5: Categorías (form independiente) ── --}}
            <div class="ec-tab-pane" data-tab-content="categorias">
                <form action="{{ route('cursos.updateCategories', $cursos->id) }}" method="POST"
                    class="ec-tab-form" data-confirm="¿Actualizar las categorías?">
                    @csrf
                    @method('PUT')

                    <div class="p-4 p-md-5">
                        <div class="ec-section-header mb-4">
                            <i class="bi bi-tag-fill"></i>
                            <div>
                                <h5 class="ec-section-title">Clasificación por Categorías</h5>
                                <p class="ec-section-sub">Estas categorías se guardan de forma independiente.</p>
                            </div>
                        </div>

                        {{-- Buscador --}}
                        <div class="position-relative mb-4">
                            <i class="bi bi-search position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                            <input type="text" class="form-control bg-light rounded-pill ps-5 py-2" id="buscarCat"
                                placeholder="Filtrar categorías...">
                        </div>

                        {{-- Grid --}}
                        <div class="ec-cat-grid" id="catGrid">
                            @foreach ($categorias as $categoria)
                                <div class="ec-cat-item {{ $cursos->categorias->contains($categoria->id) ? 'checked' : '' }}"
                                    onclick="toggleCat(this)">
                                    <input type="checkbox" name="categorias[]" value="{{ $categoria->id }}"
                                        {{ $cursos->categorias->contains($categoria->id) ? 'checked' : '' }}
                                        style="display: none;">
                                    <div class="ec-cat-check"><i class="bi bi-check"></i></div>
                                    <span class="ec-cat-name">{{ $categoria->name }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="alert alert-info border-0 rounded-4 mt-4 py-2">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            <span class="small fw-bold">
                                <span id="catCount">{{ $cursos->categorias->count() }}</span> categorías seleccionadas
                            </span>
                        </div>

                        <div class="ec-tab-footer">
                            <button type="submit" class="tbl-hero-btn tbl-hero-btn-primary px-5 py-2">
                                <i class="bi bi-check-circle-fill me-2"></i> Guardar Categorías
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>{{-- /tbl-card --}}
    </div>

   

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // ── Tab navigation ──
        document.querySelectorAll('.ec-tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tab = this.dataset.tab;
                // Deactivate all
                document.querySelectorAll('.ec-tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.ec-tab-pane').forEach(p => p.classList.remove('active'));
                // Activate clicked
                this.classList.add('active');
                document.querySelector(`[data-tab-content="${tab}"]`).classList.add('active');
            });
        });

        // ── Dynamic levels ──
        const nivelesPorEdad = {
            "3-5": ["Preescolar", "Educación Inicial", "Estimulación Temprana"],
            "6-8": ["Primaria (1º y 2º)", "Primaria (3º)", "Educación Básica"],
            "9-12": ["Primaria (4º a 6º)", "Educación Intermedia", "Pre-adolescentes"],
            "13-15": ["Secundaria Básica", "Educación Media (1º-3º)", "Adolescentes"],
            "16-18": ["Preparatoria", "Bachillerato", "Educación Media Superior", "Universitario (inicial)"],
            "18+": ["Público General", "Adultos", "Profesionales", "Interesados en Salud Mental", "Bienestar"]
        };

        function actualizarNiveles() {
            const edad = document.getElementById("edad_id")?.value;
            const nivelSelect = document.getElementById("nivel_id");
            if (!nivelSelect) return;
            const currentVal = nivelSelect.value;
            nivelSelect.innerHTML = '<option value="">Seleccione un nivel</option>';
            if (edad && nivelesPorEdad[edad]) {
                nivelesPorEdad[edad].forEach(nivel => {
                    const opt = document.createElement("option");
                    opt.value = nivel;
                    opt.textContent = nivel;
                    if (nivel === currentVal) opt.selected = true;
                    nivelSelect.appendChild(opt);
                });
            }
        }

        function toggleCat(el) {
            const cb = el.querySelector('input[type="checkbox"]');
            el.classList.toggle('checked');
            cb.checked = el.classList.contains('checked');
            document.getElementById('catCount').textContent =
                document.querySelectorAll('#catGrid .ec-cat-item.checked').length;
        }

        function toggleCuposIlimitados(checkbox) {
            const input = document.getElementById('cupos_input');
            const hidden = document.getElementById('cupos_hidden');
            if (checkbox.checked) {
                input.disabled = true; input.value = ''; input.required = false;
                hidden.disabled = false;
            } else {
                input.disabled = false; input.required = true;
                hidden.disabled = true; input.focus();
            }
        }

        function toggleCodigoCurso(tipo) {
            const group = document.getElementById('codigoCursoGroup');
            const input = document.getElementById('codigoCursoInput');
            if (tipo === 'congreso') { group.style.display = 'block'; input.required = true; }
            else { group.style.display = 'none'; input.required = false; }
        }

        // ── Category search ──
        document.getElementById('buscarCat')?.addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('.ec-cat-item').forEach(item => {
                item.style.display = item.querySelector('.ec-cat-name').textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });

        // ── Char counter ──
        const ta = document.getElementById('descripcionTA');
        const cc = document.getElementById('charCount');
        if (ta && cc) {
            cc.textContent = `${ta.value.length}/500`;
            ta.addEventListener('input', () => cc.textContent = `${ta.value.length}/500`);
        }

        // ── Form submissions with SweetAlert ──
        document.querySelectorAll('.ec-tab-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const f = this;
                Swal.fire({
                    title: f.dataset.confirm || '¿Guardar cambios?',
                    text: "Se actualizará la información.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Sí, guardar'
                }).then(r => { if (r.isConfirmed) f.submit(); });
            });
        });

        document.addEventListener('DOMContentLoaded', () => actualizarNiveles());

        @if (session('success'))
            Swal.fire({ icon: 'success', title: 'Éxito', text: "{{ session('success') }}" });
        @endif
        @if (session('error'))
            Swal.fire({ icon: 'error', title: 'Error', text: "{{ session('error') }}" });
        @endif
    </script>
@endsection
