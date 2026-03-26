@extends('layout')

@section('titulo', 'Editar Curso')

@section('content')
    <div class="container my-4">
        <div class="tbl-card shadow-lg">
            <div class="tbl-card-hero">
                <div class="tbl-hero-left">
                    <a href="{{ route('Curso', $cursos->codigoCurso) }}"
                        class="tbl-hero-btn tbl-hero-btn-glass prt-back-btn mb-2">
                        <i class="bi bi-arrow-left-circle-fill"></i> Volver al Curso
                    </a>
                    <div class="tbl-hero-eyebrow">
                        <i class="bi bi-pencil-square"></i> Gestión de Contenido
                    </div>
                    <h2 class="tbl-hero-title">Editar Curso o Evento</h2>
                    <p class="tbl-hero-sub">
                        Actualizando: <strong>{{ ucfirst(strtolower($cursos->nombreCurso)) }}</strong>
                    </p>
                </div>

                <div class="tbl-hero-controls text-end">
                    <div class="ec-role-badge mb-2 d-inline-block">
                        <i class="bi bi-shield-fill me-1"></i> {{ auth()->user()->getRoleNames()->first() }}
                    </div>
                    <div class="text-white small mb-1">
                        Paso <span id="stepCounter">1</span> de <span
                            id="totalStepsCounter">{{ auth()->user()->hasRole('Administrador') ? 5 : 4 }}</span>
                    </div>
                    <div class="progress"
                        style="height: 6px; background: rgba(255,255,255,0.2); width: 150px; margin-left: auto;">
                        <div class="progress-bar bg-white" id="progressBar" role="progressbar" style="width: 20%"></div>
                    </div>
                </div>
            </div>

            {{-- ╔══════════════════════════════════════╗
             ║  INDICADOR DE PASOS (WIZARD)        ║
             ╚══════════════════════════════════════╝ --}}
            <div class="adm-tabs-header bg-light border-bottom p-0">
                <div class="wizard-steps-nav d-flex overflow-auto">
                    <div class="step-nav-item active" data-step="1">
                        <span class="step-num">1</span>
                        <span class="step-text">Datos Básicos</span>
                    </div>
                    <div class="step-nav-item" data-step="2">
                        <span class="step-num">2</span>
                        <span class="step-text">Configuración</span>
                    </div>
                    @if (auth()->user()->hasRole('Administrador'))
                        <div class="step-nav-item" data-step="3">
                            <span class="step-num">3</span>
                            <span class="step-text">Administración</span>
                        </div>
                    @endif
                    <div class="step-nav-item" data-step="{{ auth()->user()->hasRole('Administrador') ? 4 : 3 }}">
                        <span class="step-num">{{ auth()->user()->hasRole('Administrador') ? 4 : 3 }}</span>
                        <span class="step-text">Recursos</span>
                    </div>
                    <div class="step-nav-item" data-step="{{ auth()->user()->hasRole('Administrador') ? 5 : 4 }}">
                        <span class="step-num">{{ auth()->user()->hasRole('Administrador') ? 5 : 4 }}</span>
                        <span class="step-text">Categorías</span>
                    </div>
                </div>
            </div>

            <form id="wizardForm" action="{{ route('editarCursoPost', $cursos->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="p-4 p-md-5">
                    <!-- Step 1: Datos Básicos -->
                    <div class="form-step active" data-step="1">
                        <div class="step-header mb-4">
                            <h4 class="text-primary fw-bold mb-1">
                                <i class="bi bi-info-circle-fill me-2"></i>Información General
                            </h4>
                            <p class="text-muted small">Nombre, descripción y público objetivo del curso.</p>
                        </div>

                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label fw-bold text-muted small text-uppercase">Nombre del Curso</label>
                                @if (auth()->user()->hasRole('Administrador'))
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i
                                                class="bi bi-bookmark-fill text-primary"></i></span>
                                        <input type="text" name="nombre" class="form-control bg-light"
                                            value="{{ old('nombre', $cursos->nombreCurso) }}" required>
                                    </div>
                                @else
                                    <input type="hidden" name="nombre" value="{{ $cursos->nombreCurso }}">
                                    <input type="text" class="form-control bg-light" value="{{ $cursos->nombreCurso }}"
                                        disabled>
                                @endif
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold text-muted small text-uppercase">Descripción del
                                    Curso</label>
                                <textarea name="descripcion" id="descripcionTA" class="form-control bg-light" rows="4" maxlength="500" required
                                    placeholder="Describe los objetivos...">{{ old('descripcion', $cursos->descripcionC) }}</textarea>
                                <div class="d-flex justify-content-end mt-1">
                                    <span class="badge bg-light text-muted border" id="charCount">0/500</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Edad Dirigida</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i
                                            class="bi bi-person-check text-primary"></i></span>
                                    <select id="edad_id" name="edad_id" class="form-select bg-light"
                                        onchange="actualizarNiveles()">
                                        <option value="">Seleccione un rango</option>
                                        <option value="3-5"
                                            {{ old('edad_id', $cursos->edad_dirigida) == '3-5' ? 'selected' : '' }}>👶 3 a
                                            5 años</option>
                                        <option value="6-8"
                                            {{ old('edad_id', $cursos->edad_dirigida) == '6-8' ? 'selected' : '' }}>🧒 6 a
                                            8 años</option>
                                        <option value="9-12"
                                            {{ old('edad_id', $cursos->edad_dirigida) == '9-12' ? 'selected' : '' }}>👦 9 a
                                            12 años</option>
                                        <option value="13-15"
                                            {{ old('edad_id', $cursos->edad_dirigida) == '13-15' ? 'selected' : '' }}>👨 13
                                            a 15 años</option>
                                        <option value="16-18"
                                            {{ old('edad_id', $cursos->edad_dirigida) == '16-18' ? 'selected' : '' }}>🎓 16
                                            a 18 años</option>
                                        <option value="18+"
                                            {{ old('edad_id', $cursos->edad_dirigida) == '18+' ? 'selected' : '' }}>👔 18
                                            años o más</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Nivel Académico</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i
                                            class="bi bi-bar-chart-fill text-primary"></i></span>
                                    <select id="nivel_id" name="nivel_id" class="form-select bg-light">
                                        <option value="">Seleccione un nivel</option>
                                        @if ($cursos->nivel)
                                            <option value="{{ $cursos->nivel }}" selected>{{ $cursos->nivel }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Configuración -->
                    <div class="form-step" data-step="2" style="display: none;">
                        <div class="step-header mb-4">
                            <h4 class="text-primary fw-bold mb-1">
                                <i class="bi bi-gear-fill me-2"></i>Configuración Logística
                            </h4>
                            <p class="text-muted small">Fechas, formato de clases y tipo de evento.</p>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Fecha y Hora de
                                    Inicio</label>
                                <input type="datetime-local" name="fecha_ini" class="form-control bg-light" required
                                    value="{{ old('fecha_ini', $cursos->fecha_ini ? \Carbon\Carbon::parse($cursos->fecha_ini)->format('Y-m-d\TH:i') : '') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Fecha y Hora de
                                    Fin</label>
                                <input type="datetime-local" name="fecha_fin" class="form-control bg-light" required
                                    value="{{ old('fecha_fin', $cursos->fecha_fin ? \Carbon\Carbon::parse($cursos->fecha_fin)->format('Y-m-d\TH:i') : '') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Formato de
                                    Impartición</label>
                                @if (auth()->user()->hasRole('Administrador'))
                                    <select name="formato" class="form-select bg-light">
                                        @foreach (['Virtual'] as $fmt)
                                            <option value="{{ $fmt }}"
                                                {{ $cursos->formato == $fmt ? 'selected' : '' }}>
                                                {{ $fmt == 'Virtual' ? '💻 Virtual' : $fmt }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="hidden" name="formato" value="{{ $cursos->formato }}">
                                    <input type="text" class="form-control bg-light" value="{{ $cursos->formato }}"
                                        disabled>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Tipo de Actividad</label>
                                @if (auth()->user()->hasRole('Administrador'))
                                    <select name="tipo" class="form-select bg-light">
                                        <option value="curso" {{ $cursos->tipo == 'curso' ? 'selected' : '' }}>📚 Curso
                                            Regular</option>
                                        <option value="congreso" {{ $cursos->tipo == 'congreso' ? 'selected' : '' }}>📅
                                            Evento / Congreso</option>
                                    </select>
                                @else
                                    <input type="hidden" name="tipo" value="{{ $cursos->tipo }}">
                                    <input type="text" class="form-control bg-light"
                                        value="{{ $cursos->tipo == 'congreso' ? 'Evento' : 'Curso' }}" disabled>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Admin (Solo Administrador) -->
                    @if (auth()->user()->hasRole('Administrador'))
                        <div class="form-step" data-step="3" style="display: none;">
                            <div class="step-header mb-4">
                                <h4 class="text-primary fw-bold mb-1">
                                    <i class="bi bi-sliders me-2"></i>Gestión Administrativa
                                </h4>
                                <p class="text-muted small">Asignación de docente, cupos y precios.</p>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Docente
                                        Asignado</label>
                                    <select name="docente_id" class="form-select bg-light" required>
                                        @foreach ($docente as $doc)
                                            <option value="{{ $doc->id }}"
                                                {{ $cursos->docente_id == $doc->id ? 'selected' : '' }}>
                                                {{ $doc->name }} {{ $doc->lastname1 }} {{ $doc->lastname2 }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Visibilidad del
                                        Curso</label>
                                    <select name="visibilidad" class="form-select bg-light">
                                        <option value="publico" {{ $cursos->visibilidad == 'publico' ? 'selected' : '' }}>
                                            🌐 Público (Todos)</option>
                                        <option value="privado" {{ $cursos->visibilidad == 'privado' ? 'selected' : '' }}>
                                            🔒 Privado (Restringido)</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Duración Estimada
                                        (Horas)</label>
                                    <input type="number" name="duracion" class="form-control bg-light"
                                        value="{{ old('duracion', $cursos->duracion) }}" min="1" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Cupos
                                        Disponibles</label>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="cupos_ilimitados"
                                            {{ old('cupos', $cursos->cupos) == 0 ? 'checked' : '' }}
                                            onchange="toggleCuposIlimitados(this)">
                                        <label class="form-check-label small fw-semibold"
                                            for="cupos_ilimitados">Ilimitados</label>
                                    </div>
                                    <input type="number" name="cupos" id="cupos_input" class="form-control bg-light"
                                        value="{{ old('cupos', $cursos->cupos) == 0 ? '' : old('cupos', $cursos->cupos) }}"
                                        min="1" {{ old('cupos', $cursos->cupos) == 0 ? 'disabled' : '' }} required>
                                    <input type="hidden" name="cupos" id="cupos_hidden" value="0"
                                        {{ old('cupos', $cursos->cupos) == 0 ? '' : 'disabled' }}>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Precio de Inscripción
                                        (Bs)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light fw-bold text-primary">Bs</span>
                                        <input type="number" name="precio" class="form-control bg-light"
                                            value="{{ old('precio', $cursos->precio) }}" step="0.01" min="0"
                                            required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <input type="hidden" name="docente_id" value="{{ auth()->user()->id }}">
                    @endif

                    <!-- Step 4/3: Archivos -->
                    <div class="form-step" data-step="{{ auth()->user()->hasRole('Administrador') ? 4 : 3 }}"
                        style="display: none;">
                        <div class="step-header mb-4">
                            <h4 class="text-primary fw-bold mb-1">
                                <i class="bi bi-folder-fill me-2"></i>Recursos del Curso
                            </h4>
                            <p class="text-muted small">Material PDF e imagen publicitaria.</p>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Contenido Programático
                                    (PDF)</label>
                                <div class="ec-file-drop-area p-4 text-center border rounded-4 bg-light">
                                    <i class="bi bi-file-earmark-pdf-fill fs-1 text-danger mb-2 d-block"></i>
                                    <input type="file" name="archivo" accept=".pdf" id="archivoInput"
                                        class="form-control form-control-sm">
                                    <small class="text-muted mt-2 d-block">Selecciona un archivo PDF actualizado</small>
                                </div>
                                @if ($cursos->archivoContenidodelCurso)
                                    <div class="mt-3 p-3 bg-white border rounded-3 d-flex align-items-center">
                                        <i class="bi bi-file-earmark-check-fill text-success fs-4 me-3"></i>
                                        <div class="overflow-hidden">
                                            <span class="d-block small text-muted">Archivo actual:</span>
                                            <a href="{{ asset('storage/' . $cursos->archivoContenidodelCurso) }}"
                                                target="_blank" class="small fw-bold text-primary text-truncate d-block">
                                                {{ basename($cursos->archivoContenidodelCurso) }}
                                            </a>
                                        </div>
                                        <div class="form-check form-switch ms-auto">
                                            <input class="form-check-input" type="checkbox" name="eliminar_archivo"
                                                id="eliminar_archivo">
                                            <label class="form-check-label text-danger small"
                                                for="eliminar_archivo">Eliminar</label>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Imagen de Portada</label>
                                <div class="ec-file-drop-area p-4 text-center border rounded-4 bg-light">
                                    <i class="bi bi-image-fill fs-1 text-primary mb-2 d-block"></i>
                                    <input type="file" name="imagen" accept="image/*" id="imagenInput"
                                        class="form-control form-control-sm">
                                    <small class="text-muted mt-2 d-block">Imagen recomendada: 1200x800px</small>
                                </div>
                                @if ($cursos->imagen)
                                    <div class="mt-3 p-3 bg-white border rounded-3 d-flex align-items-center">
                                        <img src="{{ asset('storage/' . $cursos->imagen) }}" class="rounded border me-3"
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                        <div>
                                            <span class="d-block small text-muted">Imagen actual activa</span>
                                        </div>
                                        <div class="form-check form-switch ms-auto">
                                            <input class="form-check-input" type="checkbox" name="eliminar_imagen"
                                                id="eliminar_imagen">
                                            <label class="form-check-label text-danger small"
                                                for="eliminar_imagen">Eliminar</label>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Step 5/4: Categorías -->
                    <div class="form-step" data-step="{{ auth()->user()->hasRole('Administrador') ? 5 : 4 }}"
                        style="display: none;">
                        <div class="step-header mb-4">
                            <h4 class="text-primary fw-bold mb-1">
                                <i class="bi bi-tag-fill me-2"></i>Clasificación
                            </h4>
                            <p class="text-muted small">Selecciona las categorías para facilitar la búsqueda.</p>
                        </div>

                        <div class="tbl-hero-search mb-4 shadow-sm border rounded-pill overflow-hidden bg-white">
                            <i class="bi bi-search ms-3 text-muted"></i>
                            <input type="text" class="form-control border-0 bg-transparent py-2" id="buscarCat"
                                placeholder="Filtrar categorías...">
                        </div>

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
                            <span class="small fw-bold"><span id="catCount">{{ $cursos->categorias->count() }}</span>
                                categorías seleccionadas</span>
                        </div>
                    </div>

                    <!-- Wizard Buttons -->
                    <div class="d-flex justify-content-between mt-5 pt-4 border-top">
                        <button type="button" class="btn btn-light rounded-pill px-4 py-2 fw-bold" id="prevBtn"
                            style="display: none;">
                            <i class="bi bi-arrow-left me-2"></i> Anterior
                        </button>
                        <button type="button" class="tbl-hero-btn tbl-hero-btn-primary px-5 py-2 ms-auto"
                            id="nextBtn">
                            Siguiente <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                        <button type="submit" class="tbl-hero-btn tbl-hero-btn-primary px-5 py-2 ms-auto" id="submitBtn"
                            style="display: none;">
                            <i class="bi bi-save-fill me-2"></i> Guardar Cambios
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        .wizard-steps-nav {
            gap: 0.5rem;
            padding: 1rem;
        }

        .step-nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 1.25rem;
            border-radius: 50px;
            background: #f8fafc;
            border: 1.5px solid #e2eaf4;
            color: #64748b;
            font-weight: 700;
            font-size: 0.82rem;
            white-space: nowrap;
            cursor: default;
            transition: all 0.3s;
        }

        .step-nav-item.active {
            background: rgba(20, 93, 160, 0.08);
            border-color: #145da0;
            color: #145da0;
        }

        .step-nav-item.completed {
            background: #f0fdf4;
            border-color: #16a34a;
            color: #16a34a;
        }

        .step-num {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: currentColor;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
        }

        .ec-role-badge {
            background: rgba(255, 165, 0, 0.15);
            color: #ffa500;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 800;
            border: 1px solid rgba(255, 165, 0, 0.3);
        }

        .ec-cat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 0.75rem;
            max-height: 300px;
            overflow-y: auto;
            padding: 0.5rem;
        }

        .ec-cat-item {
            padding: 0.75rem;
            border-radius: 12px;
            background: #fff;
            border: 1.5px solid #e2eaf4;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .ec-cat-item:hover {
            border-color: #145da0;
            background: #f8fafc;
        }

        .ec-cat-item.checked {
            border-color: #145da0;
            background: rgba(20, 93, 160, 0.05);
        }

        .ec-cat-check {
            width: 20px;
            height: 20px;
            border-radius: 6px;
            border: 2px solid #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            color: #fff;
        }

        .ec-cat-item.checked .ec-cat-check {
            background: #145da0;
            border-color: #145da0;
        }

        .ec-cat-name {
            font-size: 0.82rem;
            font-weight: 600;
            color: #334155;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            border: 1.5px solid #e2eaf4;
            padding: 0.6rem 1rem;
            transition: all 0.2s;
            font-size: 0.88rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #145da0;
            box-shadow: 0 0 0 4px rgba(20, 93, 160, 0.1);
            background: #fff !important;
        }

        .input-group-text {
            border-radius: 12px 0 0 12px;
            border: 1.5px solid #e2eaf4;
            border-right: none;
        }

        .input-group .form-control,
        .input-group .form-select {
            border-radius: 0 12px 12px 0;
        }

        /* Custom scrollbar para categorías */
        .ec-cat-grid::-webkit-scrollbar {
            width: 5px;
        }

        .ec-cat-grid::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .ec-cat-grid::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const isAdmin = {{ auth()->user()->hasRole('Administrador') ? 'true' : 'false' }};
        const totalSteps = isAdmin ? 5 : 4;
        let currentStep = 1;

        // Niveles por edad
        const nivelesPorEdad = {
            "3-5": ["Preescolar", "Educación Inicial", "Estimulación Temprana"],
            "6-8": ["Primaria (1º y 2º)", "Primaria (3º)", "Educación Básica"],
            "9-12": ["Primaria (4º a 6º)", "Educación Intermedia", "Pre-adolescentes"],
            "13-15": ["Secundaria Básica", "Educación Media (1º-3º)", "Adolescentes"],
            "16-18": ["Preparatoria", "Bachillerato", "Educación Media Superior", "Universitario (inicial)"],
            "18+": ["Público General", "Adultos", "Profesionales", "Interesados en Salud Mental", "Bienestar"]
        };

        function actualizarNiveles() {
            const edad = document.getElementById("edad_id").value;
            const nivelSelect = document.getElementById("nivel_id");
            if (!nivelSelect) return;

            const currentVal = nivelSelect.value;
            nivelSelect.innerHTML = '<option value="">Seleccione un nivel</option>';

            if (edad && nivelesPorEdad[edad]) {
                nivelesPorEdad[edad].forEach(nivel => {
                    const option = document.createElement("option");
                    option.value = nivel;
                    option.textContent = nivel;
                    if (nivel === currentVal) option.selected = true;
                    nivelSelect.appendChild(option);
                });
            }
        }

        function updateProgress() {
            const progress = ((currentStep - 1) / (totalSteps - 1)) * 100;
            const bar = document.getElementById('progressBar');
            const counter = document.getElementById('stepCounter');
            if (bar) bar.style.width = progress + '%';
            if (counter) counter.textContent = currentStep;

            document.querySelectorAll('.step-nav-item').forEach((item, index) => {
                const stepNum = index + 1;
                item.classList.remove('active', 'completed');
                if (stepNum === currentStep) {
                    item.classList.add('active');
                } else if (stepNum < currentStep) {
                    item.classList.add('completed');
                    const icon = item.querySelector('.step-num');
                    icon.innerHTML = '<i class="bi bi-check-lg"></i>';
                } else {
                    item.querySelector('.step-num').textContent = stepNum;
                }
            });
        }

        function showStep(step) {
            document.querySelectorAll('.form-step').forEach(s => s.style.display = 'none');
            const target = document.querySelector(`[data-step="${step}"].form-step`);
            if (target) target.style.display = 'block';

            document.getElementById('prevBtn').style.display = step === 1 ? 'none' : 'block';
            document.getElementById('nextBtn').style.display = step === totalSteps ? 'none' : 'block';
            document.getElementById('submitBtn').style.display = step === totalSteps ? 'block' : 'none';

            updateProgress();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function validateStep(step) {
            const el = document.querySelector(`[data-step="${step}"].form-step`);
            if (!el) return true;

            // Solo validar campos que NO estén deshabilitados
            const inputs = el.querySelectorAll(
                'input[required]:not(:disabled), select[required]:not(:disabled), textarea[required]:not(:disabled)');

            for (let input of inputs) {
                if (!input.value.trim()) {
                    input.focus();
                    input.classList.add('is-invalid');
                    setTimeout(() => input.classList.remove('is-invalid'), 3000);
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campo Requerido',
                        text: 'Completa los campos obligatorios.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    return false;
                }
            }
            return true;
        }

        function toggleCat(el) {
            const cb = el.querySelector('input[type="checkbox"]');
            el.classList.toggle('checked');
            cb.checked = el.classList.contains('checked');
            document.getElementById('catCount').textContent = document.querySelectorAll('#catGrid .ec-cat-item.checked')
                .length;
        }

        document.getElementById('nextBtn').addEventListener('click', () => {
            if (validateStep(currentStep)) {
                if (currentStep < totalSteps) {
                    currentStep++;
                    showStep(currentStep);
                }
            }
        });

        document.getElementById('prevBtn').addEventListener('click', () => {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        });

        document.getElementById('buscarCat')?.addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('.ec-cat-item').forEach(item => {
                const name = item.querySelector('.ec-cat-name').textContent.toLowerCase();
                item.style.display = name.includes(q) ? '' : 'none';
            });
        });

        const ta = document.getElementById('descripcionTA');
        const cc = document.getElementById('charCount');
        if (ta && cc) {
            ta.addEventListener('input', () => {
                cc.textContent = `${ta.value.length}/500`;
            });
        }

        function toggleCuposIlimitados(checkbox) {
            const input = document.getElementById('cupos_input');
            const hidden = document.getElementById('cupos_hidden');
            if (checkbox.checked) {
                input.disabled = true;
                input.value = '';
                input.required = false;
                hidden.disabled = false;
            } else {
                input.disabled = false;
                input.required = true;
                hidden.disabled = true;
                input.focus();
            }
        }

        document.getElementById('wizardForm').addEventListener('submit', function(e) {
            e.preventDefault();
            if (!validateStep(currentStep)) return;
            const cats = document.querySelectorAll('#catGrid .ec-cat-item.checked').length;
            if (cats === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Categorías',
                    text: 'Selecciona al menos una.'
                });
                return;
            }
            Swal.fire({
                title: '¿Guardar Cambios?',
                text: "Se actualizará la información.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                confirmButtonText: 'Sí, guardar'
            }).then((result) => {
                if (result.isConfirmed) this.submit();
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            showStep(1);
            actualizarNiveles();
        });

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: "{{ session('success') }}"
            });
        @endif
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}"
            });
        @endif
    </script>
@endsection
