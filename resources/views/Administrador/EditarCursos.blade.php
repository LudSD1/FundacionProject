@extends('layout')

@section('titulo', 'Editar Curso')

@section('content')

    <div class="back-button-wrapper">
        <a href="{{ route('Curso', $cursos->codigoCurso) }}" class="btn-back-modern">
            <i class="bi bi-arrow-left-circle-fill"></i>
            <span>Volver al Curso</span>
        </a>
    </div>

    <div class="wizard-container">
        <!-- Header -->
        <div class="wizard-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2><i class="bi bi-pencil-square me-2"></i>Editar Curso o Evento</h2>
                    <p>Actualice la información del curso: <span class="fw-bold text-primary">{{ ucfirst(strtolower($cursos->nombreCurso)) }}</span></p>
                </div>
                <div class="ec-role-badge">
                    <i class="bi bi-shield-fill me-1"></i> {{ auth()->user()->getRoleNames()->first() }}
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="progress-wrapper">
            <div class="steps-progress">
                <div class="progress-line"></div>
                <div class="progress-line-active" id="progressLine"></div>

                <div class="step-item active" data-step="1">
                    <div class="step-circle">1</div>
                    <span class="step-label">Datos</span>
                </div>

                <div class="step-item" data-step="2">
                    <div class="step-circle">2</div>
                    <span class="step-label">Config</span>
                </div>

                @if(auth()->user()->hasRole('Administrador'))
                <div class="step-item" data-step="3">
                    <div class="step-circle">3</div>
                    <span class="step-label">Detalles</span>
                </div>
                @endif

                <div class="step-item" data-step="{{ auth()->user()->hasRole('Administrador') ? 4 : 3 }}">
                    <div class="step-circle">{{ auth()->user()->hasRole('Administrador') ? 4 : 3 }}</div>
                    <span class="step-label">Archivos</span>
                </div>

                <div class="step-item" data-step="{{ auth()->user()->hasRole('Administrador') ? 5 : 4 }}">
                    <div class="step-circle">{{ auth()->user()->hasRole('Administrador') ? 5 : 4 }}</div>
                    <span class="step-label">Categorías</span>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form id="wizardForm" action="{{ route('editarCursoPost', $cursos->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="wizard-body">
                <!-- Step 1: Datos Básicos -->
                <div class="form-step active" data-step="1">
                    <h3 class="step-title">
                        <i class="bi bi-info-circle-fill"></i>
                        Información Básica
                    </h3>
                    <p class="step-description">Nombre, descripción y público objetivo</p>

                    <div class="row g-4">
                        <div class="col-md-12">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-bookmark-fill label-icon"></i>
                                    Nombre del Curso
                                    <span class="required-badge">*</span>
                                </label>
                                @if(auth()->user()->hasRole('Administrador'))
                                    <input type="text" name="nombre" class="form-control-modern" value="{{ old('nombre', $cursos->nombreCurso) }}"
                                        placeholder="Ej: Introducción a la Programación" required>
                                @else
                                    <input type="hidden" name="nombre" value="{{ $cursos->nombreCurso }}">
                                    <input type="text" class="form-control-modern" value="{{ $cursos->nombreCurso }}" disabled>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-text-paragraph label-icon"></i>
                                    Descripción
                                    <span class="required-badge">*</span>
                                </label>
                                <textarea name="descripcion" id="descripcionTA" class="form-control-modern" rows="4" maxlength="500" required
                                    placeholder="Describe el contenido y objetivos...">{{ old('descripcion', $cursos->descripcionC) }}</textarea>
                                <div class="helper-text-modern mt-1 text-end" id="charCount">0/500 caracteres</div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mt-2">
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-person-check label-icon"></i>
                                    Edad Dirigida
                                </label>
                                <select id="edad_id" name="edad_id" class="form-select-modern" onchange="actualizarNiveles()">
                                    <option value="">Seleccione un rango</option>
                                    <option value="3-5" {{ old('edad_id', $cursos->edad_dirigida) == '3-5' ? 'selected' : '' }}>👶 3 a 5 años</option>
                                    <option value="6-8" {{ old('edad_id', $cursos->edad_dirigida) == '6-8' ? 'selected' : '' }}>🧒 6 a 8 años</option>
                                    <option value="9-12" {{ old('edad_id', $cursos->edad_dirigida) == '9-12' ? 'selected' : '' }}>👦 9 a 12 años</option>
                                    <option value="13-15" {{ old('edad_id', $cursos->edad_dirigida) == '13-15' ? 'selected' : '' }}>👨 13 a 15 años</option>
                                    <option value="16-18" {{ old('edad_id', $cursos->edad_dirigida) == '16-18' ? 'selected' : '' }}>🎓 16 a 18 años</option>
                                    <option value="18+" {{ old('edad_id', $cursos->edad_dirigida) == '18+' ? 'selected' : '' }}>👔 18 años o más</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-bar-chart-fill label-icon"></i>
                                    Nivel
                                </label>
                                <select id="nivel_id" name="nivel_id" class="form-select-modern">
                                    <option value="">Seleccione un nivel</option>
                                    @if($cursos->nivel)
                                        <option value="{{ $cursos->nivel }}" selected>{{ $cursos->nivel }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Configuración -->
                <div class="form-step" data-step="2">
                    <h3 class="step-title">
                        <i class="bi bi-gear-fill"></i>
                        Configuración del Curso
                    </h3>
                    <p class="step-description">Fechas, formato y tipo de evento</p>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-calendar-check-fill label-icon"></i>
                                    Fecha de Inicio
                                    <span class="required-badge">*</span>
                                </label>
                                <input type="datetime-local" name="fecha_ini" class="form-control-modern" required
                                    value="{{ old('fecha_ini', $cursos->fecha_ini ? \Carbon\Carbon::parse($cursos->fecha_ini)->format('Y-m-d\TH:i') : '') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-calendar-x-fill label-icon"></i>
                                    Fecha de Fin
                                    <span class="required-badge">*</span>
                                </label>
                                <input type="datetime-local" name="fecha_fin" class="form-control-modern" required
                                    value="{{ old('fecha_fin', $cursos->fecha_fin ? \Carbon\Carbon::parse($cursos->fecha_fin)->format('Y-m-d\TH:i') : '') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mt-2">
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-display-fill label-icon"></i>
                                    Formato
                                </label>
                                @if(auth()->user()->hasRole('Administrador'))
                                    <select name="formato" class="form-select-modern">
                                        @foreach(['Presencial','Virtual','Híbrido'] as $fmt)
                                            <option value="{{ $fmt }}" {{ $cursos->formato == $fmt ? 'selected' : '' }}>
                                                {{ $fmt == 'Virtual' ? '💻 Virtual' : ($fmt == 'Presencial' ? '🏢 Presencial' : '🌓 Híbrido') }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="hidden" name="formato" value="{{ $cursos->formato }}">
                                    <input type="text" class="form-control-modern" value="{{ $cursos->formato }}" disabled>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-tags-fill label-icon"></i>
                                    Tipo
                                </label>
                                @if(auth()->user()->hasRole('Administrador'))
                                    <select name="tipo" class="form-select-modern">
                                        <option value="curso" {{ $cursos->tipo == 'curso' ? 'selected' : '' }}>📚 Curso</option>
                                        <option value="congreso" {{ $cursos->tipo == 'congreso' ? 'selected' : '' }}>📅 Evento / Congreso</option>
                                    </select>
                                @else
                                    <input type="hidden" name="tipo" value="{{ $cursos->tipo }}">
                                    <input type="text" class="form-control-modern" value="{{ $cursos->tipo == 'congreso' ? 'Evento' : 'Curso' }}" disabled>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Admin (Solo Administrador) -->
                @if(auth()->user()->hasRole('Administrador'))
                <div class="form-step" data-step="3">
                    <h3 class="step-title">
                        <i class="bi bi-sliders"></i>
                        Detalles Administrativos
                    </h3>
                    <p class="step-description">Docente, cupos, precio y visibilidad</p>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-person-badge-fill label-icon"></i>
                                    Docente
                                    <span class="required-badge">*</span>
                                </label>
                                <select name="docente_id" class="form-select-modern" required>
                                    @foreach($docente as $doc)
                                        <option value="{{ $doc->id }}" {{ $cursos->docente_id == $doc->id ? 'selected' : '' }}>
                                            {{ $doc->name }} {{ $doc->lastname1 }} {{ $doc->lastname2 }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-eye-fill label-icon"></i>
                                    Visibilidad
                                </label>
                                <select name="visibilidad" class="form-select-modern">
                                    <option value="publico" {{ $cursos->visibilidad == 'publico' ? 'selected' : '' }}>🌐 Público</option>
                                    <option value="privado" {{ $cursos->visibilidad == 'privado' ? 'selected' : '' }}>🔒 Privado</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mt-2">
                        <div class="col-md-4">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-clock-fill label-icon"></i>
                                    Duración (horas)
                                    <span class="required-badge">*</span>
                                </label>
                                <input type="number" name="duracion" class="form-control-modern" value="{{ old('duracion', $cursos->duracion) }}" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-people-fill label-icon"></i>
                                    Cupos
                                    <span class="required-badge">*</span>
                                </label>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="cupos_ilimitados"
                                        {{ old('cupos', $cursos->cupos) == 0 ? 'checked' : '' }}
                                        onchange="toggleCuposIlimitados(this)">
                                    <label class="form-check-label small fw-semibold" for="cupos_ilimitados">
                                        <i class="bi bi-infinity me-1"></i> Ilimitado
                                    </label>
                                </div>
                                <input type="number" name="cupos" id="cupos_input" class="form-control-modern"
                                    value="{{ old('cupos', $cursos->cupos) == 0 ? '' : old('cupos', $cursos->cupos) }}" min="1"
                                    {{ old('cupos', $cursos->cupos) == 0 ? 'disabled' : '' }} required>
                                <input type="hidden" name="cupos" id="cupos_hidden" value="0"
                                    {{ old('cupos', $cursos->cupos) == 0 ? '' : 'disabled' }}>
                                <div class="helper-text-modern mt-1" id="cupos_helper" style="display: {{ old('cupos', $cursos->cupos) == 0 ? 'block' : 'none' }};">
                                    <i class="bi bi-infinity me-1 text-success"></i>
                                    <span class="text-success fw-semibold">Sin límite de inscripciones</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-currency-dollar label-icon"></i>
                                    Precio (Bs)
                                    <span class="required-badge">*</span>
                                </label>
                                <input type="number" name="precio" class="form-control-modern" value="{{ old('precio', $cursos->precio) }}" step="0.01" min="0" required>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                    <input type="hidden" name="docente_id" value="{{ auth()->user()->id }}">
                @endif

                <!-- Step 4/3: Archivos -->
                <div class="form-step" data-step="{{ auth()->user()->hasRole('Administrador') ? 4 : 3 }}">
                    <h3 class="step-title">
                        <i class="bi bi-folder-fill"></i>
                        Archivos y Recursos
                    </h3>
                    <p class="step-description">PDF del curso e imagen de portada</p>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-file-earmark-pdf-fill label-icon"></i>
                                    Archivo PDF
                                </label>
                                <div class="ec-file-wrap">
                                    <input type="file" name="archivo" accept=".pdf" id="archivoInput">
                                    <div class="ec-file-icon"><i class="bi bi-cloud-arrow-up-fill"></i></div>
                                    <div class="ec-file-text" id="archivoLabel"><strong>Haz clic</strong> o arrastra tu PDF</div>
                                </div>
                                @if($cursos->archivoContenidodelCurso)
                                    <div class="ec-file-preview mt-2">
                                        <i class="bi bi-file-earmark-pdf-fill text-danger me-2" style="font-size: 1.5rem;"></i>
                                        <a href="{{ asset('storage/'.$cursos->archivoContenidodelCurso) }}" target="_blank" class="ec-file-name text-truncate d-inline-block" style="max-width: 200px;">
                                            {{ basename($cursos->archivoContenidodelCurso) }}
                                        </a>
                                        <div class="form-check form-switch ms-auto">
                                            <input class="form-check-input" type="checkbox" name="eliminar_archivo" id="eliminar_archivo">
                                            <label class="form-check-label text-danger" for="eliminar_archivo">Eliminar</label>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-image-fill label-icon"></i>
                                    Imagen de Portada
                                </label>
                                <div class="ec-file-wrap">
                                    <input type="file" name="imagen" accept="image/*" id="imagenInput">
                                    <div class="ec-file-icon"><i class="bi bi-image"></i></div>
                                    <div class="ec-file-text" id="imagenLabel"><strong>Haz clic</strong> o arrastra tu imagen</div>
                                </div>
                                @if($cursos->imagen)
                                    <div class="ec-file-preview mt-2">
                                        <img src="{{ asset('storage/'.$cursos->imagen) }}" alt="Portada" class="me-2" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                        <span class="ec-file-name">Imagen actual</span>
                                        <div class="form-check form-switch ms-auto">
                                            <input class="form-check-input" type="checkbox" name="eliminar_imagen" id="eliminar_imagen">
                                            <label class="form-check-label text-danger" for="eliminar_imagen">Eliminar</label>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 5/4: Categorías -->
                <div class="form-step" data-step="{{ auth()->user()->hasRole('Administrador') ? 5 : 4 }}">
                    <h3 class="step-title">
                        <i class="bi bi-tag-fill"></i>
                        Categorías del Curso
                    </h3>
                    <p class="step-description">Seleccione las categorías que mejor describen el curso</p>

                    <div class="search-box-table mb-4">
                        <i class="bi bi-search search-icon-table"></i>
                        <input type="text" class="form-control search-input-table" id="buscarCat" placeholder="Buscar categoría...">
                    </div>

                    <div class="ec-cat-grid" id="catGrid">
                        @foreach($categorias as $categoria)
                            <div class="ec-cat-item {{ $cursos->categorias->contains($categoria->id) ? 'checked' : '' }}" onclick="toggleCat(this)">
                                <input type="checkbox" name="categorias[]" value="{{ $categoria->id }}"
                                    {{ $cursos->categorias->contains($categoria->id) ? 'checked' : '' }} style="display: none;">
                                <div class="ec-cat-check"><i class="bi bi-check"></i></div>
                                <span class="ec-cat-name">{{ $categoria->name }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 p-3 bg-light rounded-3 border-start border-primary border-4">
                        <div class="d-flex align-items-center text-muted" style="font-size: 0.9rem;">
                            <i class="bi bi-info-circle-fill text-primary me-2"></i>
                            <span><strong id="catCount" class="text-primary">{{ $cursos->categorias->count() }}</strong> categorías seleccionadas</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="wizard-footer mt-5">
                <button type="button" class="btn-wizard btn-prev" id="prevBtn" style="display: none;">
                    <i class="bi bi-arrow-left"></i>
                    Anterior
                </button>
                <button type="button" class="btn-wizard btn-next" id="nextBtn">
                    Siguiente
                    <i class="bi bi-arrow-right"></i>
                </button>
                <button type="submit" class="btn-wizard btn-submit" id="submitBtn" style="display: none;">
                    <i class="bi bi-check-circle-fill"></i>
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>

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
            const line = document.getElementById('progressLine');
            if (line) line.style.width = progress + '%';

            document.querySelectorAll('.step-item').forEach((item, index) => {
                const stepNumber = index + 1;
                item.classList.remove('active', 'completed');

                if (stepNumber === currentStep) {
                    item.classList.add('active');
                    item.querySelector('.step-circle').textContent = stepNumber;
                } else if (stepNumber < currentStep) {
                    item.classList.add('completed');
                    item.querySelector('.step-circle').innerHTML = '<i class="bi bi-check-lg"></i>';
                } else {
                    item.querySelector('.step-circle').textContent = stepNumber;
                }
            });
        }

        function showStep(step) {
            document.querySelectorAll('.form-step').forEach(s => s.classList.remove('active'));
            const targetStep = document.querySelector(`[data-step="${step}"].form-step`);
            if (targetStep) targetStep.classList.add('active');

            document.getElementById('prevBtn').style.display = step === 1 ? 'none' : 'flex';
            document.getElementById('nextBtn').style.display = step === totalSteps ? 'none' : 'flex';
            document.getElementById('submitBtn').style.display = step === totalSteps ? 'flex' : 'none';

            updateProgress();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function validateStep(step) {
            const currentStepElement = document.querySelector(`[data-step="${step}"].form-step`);
            if (!currentStepElement) return true;

            const inputs = currentStepElement.querySelectorAll('input[required], select[required], textarea[required]');

            for (let input of inputs) {
                if (!input.value.trim()) {
                    input.focus();
                    input.classList.add('is-invalid');
                    setTimeout(() => input.classList.remove('is-invalid'), 3000);

                    Swal.fire({
                        icon: 'warning',
                        title: 'Campo Requerido',
                        text: 'Por favor complete todos los campos obligatorios antes de continuar.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                    return false;
                }
            }
            return true;
        }

        function toggleCat(el) {
            const checkbox = el.querySelector('input[type="checkbox"]');
            el.classList.toggle('checked');
            checkbox.checked = el.classList.contains('checked');
            document.getElementById('catCount').textContent = document.querySelectorAll('#catGrid .ec-cat-item.checked').length;
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

        // Búsqueda de categorías
        document.getElementById('buscarCat')?.addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('.ec-cat-item').forEach(item => {
                const name = item.querySelector('.ec-cat-name').textContent.toLowerCase();
                item.style.display = name.includes(q) ? '' : 'none';
            });
        });

        // Contador de caracteres descripción
        const ta = document.getElementById('descripcionTA');
        const cc = document.getElementById('charCount');
        if (ta && cc) {
            const updateCount = () => {
                const len = ta.value.length;
                cc.textContent = `${len}/500 caracteres`;
                cc.style.color = len > 450 ? '#ff4757' : '#94a3b8';
            };
            ta.addEventListener('input', updateCount);
            updateCount();
        }

        // Labels de archivos
        document.getElementById('archivoInput')?.addEventListener('change', function() {
            if (this.files[0]) document.getElementById('archivoLabel').innerHTML = `<strong>${this.files[0].name}</strong>`;
        });
        document.getElementById('imagenInput')?.addEventListener('change', function() {
            if (this.files[0]) document.getElementById('imagenLabel').innerHTML = `<strong>${this.files[0].name}</strong>`;
        });

        // Confirmación al guardar
        document.getElementById('wizardForm').addEventListener('submit', function(e) {
            e.preventDefault();

            if (!validateStep(currentStep)) return;

            // Verificar categorías
            const cats = document.querySelectorAll('#catGrid .ec-cat-item.checked').length;
            if (cats === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Sin Categorías',
                    text: 'Debe seleccionar al menos una categoría para el curso.',
                    confirmButtonColor: '#1a4789'
                });
                return;
            }

            Swal.fire({
                title: '¿Guardar Cambios?',
                text: "Se actualizará la información del curso.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Guardando...',
                        didOpen: () => Swal.showLoading(),
                        allowOutsideClick: false
                    });
                    this.submit();
                }
            });
        });

        // Toggle cupos ilimitados
        function toggleCuposIlimitados(checkbox) {
            const cuposInput = document.getElementById('cupos_input');
            const cuposHidden = document.getElementById('cupos_hidden');
            const cuposHelper = document.getElementById('cupos_helper');

            if (checkbox.checked) {
                cuposInput.disabled = true;
                cuposInput.removeAttribute('required');
                cuposInput.value = '';
                cuposHidden.disabled = false;
                cuposHelper.style.display = 'block';
            } else {
                cuposInput.disabled = false;
                cuposInput.setAttribute('required', 'required');
                cuposHidden.disabled = true;
                cuposHelper.style.display = 'none';
                cuposInput.focus();
            }
        }

        // Inicialización
        document.addEventListener('DOMContentLoaded', () => {
            showStep(1);
            actualizarNiveles();

            // Inicializar estado del toggle de cupos ilimitados
            const cuposCheckbox = document.getElementById('cupos_ilimitados');
            if (cuposCheckbox && cuposCheckbox.checked) {
                toggleCuposIlimitados(cuposCheckbox);
            }
        });
    </script>
@endsection
