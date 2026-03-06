@section('titulo')
    Crear Curso
@endsection




@section('content')
    <div class="back-button-wrapper">
        <a href="{{ route('ListadeCursos') }}" class="btn-back-modern">
            <i class="bi bi-arrow-left-circle-fill"></i>
            <span>Volver al Listado</span>
        </a>
    </div>

    <div class="wizard-container">
        <!-- Header -->
        <div class="wizard-header">
            <h2><i class="bi bi-plus-circle-fill me-2"></i>Crear Nuevo Curso o Evento</h2>
            <p>Complete el formulario paso a paso para registrar su curso o evento</p>
        </div>

        <!-- Progress Bar -->
        <div class="progress-wrapper">
            <div class="steps-progress">
                <div class="progress-line" id="progressLine"></div>

                <div class="step-item active" data-step="1">
                    <div class="step-circle">1</div>
                    <span class="step-label">Datos Básicos</span>
                </div>

                <div class="step-item" data-step="2">
                    <div class="step-circle">2</div>
                    <span class="step-label">Configuración</span>
                </div>

                <div class="step-item" data-step="3">
                    <div class="step-circle">3</div>
                    <span class="step-label">Público Objetivo</span>
                </div>

                <div class="step-item" data-step="4">
                    <div class="step-circle">4</div>
                    <span class="step-label">Detalles Finales</span>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form id="wizardForm" action="{{ route('CrearCursoPost') }}" method="POST">
            @csrf
            <div class="wizard-body">
                <!-- Step 1: Datos Básicos -->
                <div class="form-step active" data-step="1">
                    <h3 class="step-title">
                        <i class="bi bi-info-circle-fill"></i>
                        Datos Básicos del Curso
                    </h3>
                    <p class="step-description">Ingrese la información fundamental del curso o evento</p>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-bookmark-fill label-icon"></i>
                                    Nombre del Curso
                                    <span class="required-badge">*</span>
                                </label>
                                <input type="text" name="nombre" class="form-control-modern" value="{{ old('nombre') }}"
                                    placeholder="Ej: Introducción a la Programación" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-text-paragraph label-icon"></i>
                                    Descripción
                                    <span class="optional-badge">Opcional</span>
                                </label>
                                <input type="text" name="descripcion" class="form-control-modern"
                                    value="{{ old('descripcion') }}" placeholder="Breve descripción del curso">
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mt-2">
                        <div class="col-md-3">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-calendar-check label-icon"></i>
                                    Fecha Inicio
                                    <span class="required-badge">*</span>
                                </label>
                                <input type="date" name="fecha_ini" class="form-control-modern"
                                    value="{{ old('fecha_ini') }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-clock label-icon"></i>
                                    Hora Inicio
                                </label>
                                <input type="time" name="hora_ini" class="form-control-modern"
                                    value="{{ old('hora_ini') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-calendar-x label-icon"></i>
                                    Fecha Fin
                                    <span class="required-badge">*</span>
                                </label>
                                <input type="date" name="fecha_fin" class="form-control-modern"
                                    value="{{ old('fecha_fin') }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-clock-fill label-icon"></i>
                                    Hora Fin
                                </label>
                                <input type="time" name="hora_fin" class="form-control-modern"
                                    value="{{ old('hora_fin') }}">
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
                    <p class="step-description">Defina el formato, tipo y docente responsable</p>

                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-laptop label-icon"></i>
                                    Formato
                                </label>
                                <select name="formato" class="form-select-modern">
                                    <option value="Virtual" {{ old('formato') == 'Virtual' ? 'selected' : '' }}>💻 Virtual
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-tags-fill label-icon"></i>
                                    Tipo
                                </label>
                                <select name="tipo" class="form-select-modern">
                                    <option value="curso" {{ old('tipo') == 'curso' ? 'selected' : '' }}>📚 Curso</option>
                                    <option value="congreso" {{ old('tipo') == 'congreso' ? 'selected' : '' }}>🎉 Evento
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-person-badge label-icon"></i>
                                    Docente
                                    <span class="required-badge">*</span>
                                </label>
                                <div class="helper-text-modern mb-2">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Si no está registrado,
                                    <a href="{{ route('CrearUsuario') }}" class="helper-link">crear docente aquí</a>
                                </div>
                                <select name="docente_id" class="form-select-modern" required>
                                    <option value="">Seleccione un docente</option>
                                    @forelse ($docente as $doc)
                                        <option value="{{ $doc->id }}"
                                            {{ old('docente_id') == $doc->id ? 'selected' : '' }}>
                                            {{ $doc->name }} {{ $doc->lastname1 }} {{ $doc->lastname2 }}
                                        </option>
                                    @empty
                                        <option value="" disabled>NO HAY DOCENTES REGISTRADOS</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Público Objetivo -->
                <div class="form-step" data-step="3">
                    <h3 class="step-title">
                        <i class="bi bi-people-fill"></i>
                        Público Objetivo
                    </h3>
                    <p class="step-description">Especifique la edad y nivel educativo de los participantes</p>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-person-check label-icon"></i>
                                    Edad Estudiantes (rango aproximado)
                                </label>
                                <select id="edad_id" name="edad_id" class="form-select-modern"
                                    onchange="actualizarNiveles()">
                                    <option value="">Seleccione un rango</option>
                                    <option value="3-5"
                                        {{ old('edad_id', $curso->edad_id ?? '') == '3-5' ? 'selected' : '' }}>👶 3 a 5
                                        años</option>
                                    <option value="6-8"
                                        {{ old('edad_id', $curso->edad_id ?? '') == '6-8' ? 'selected' : '' }}>🧒 6 a 8
                                        años</option>
                                    <option value="9-12"
                                        {{ old('edad_id', $curso->edad_id ?? '') == '9-12' ? 'selected' : '' }}>👦 9 a 12
                                        años</option>
                                    <option value="13-15"
                                        {{ old('edad_id', $curso->edad_id ?? '') == '13-15' ? 'selected' : '' }}>👨 13 a 15
                                        años</option>
                                    <option value="16-18"
                                        {{ old('edad_id', $curso->edad_id ?? '') == '16-18' ? 'selected' : '' }}>🎓 16 a 18
                                        años</option>
                                    <option value="18+"
                                        {{ old('edad_id', $curso->edad_id ?? '') == '18+' ? 'selected' : '' }}>👔 18 años o
                                        más</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-bar-chart-fill label-icon"></i>
                                    Nivel Educativo
                                </label>
                                <select id="nivel_id" name="nivel_id" class="form-select-modern">
                                    <option value="">Seleccione un nivel</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Step 4: Detalles Finales -->
                <div class="form-step" data-step="4">
                    <h3 class="step-title">
                        <i class="bi bi-card-checklist"></i>
                        Detalles Finales
                    </h3>
                    <p class="step-description">Complete la información sobre duración, cupos y precio</p>

                    <div class="row g-4">
                        <div class="col-md-3">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-hourglass-split label-icon"></i>
                                    Duración (horas)
                                    <span class="required-badge">*</span>
                                </label>
                                <input type="number" name="duracion" class="form-control-modern"
                                    value="{{ old('duracion') }}" min="1" placeholder="Ej: 40" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-eye-fill label-icon"></i>
                                    Visibilidad
                                </label>
                                <select name="visibilidad" class="form-select-modern">
                                    <option value="publico" {{ old('visibilidad') == 'publico' ? 'selected' : '' }}>🌐
                                        Público</option>
                                    <option value="privado" {{ old('visibilidad') == 'privado' ? 'selected' : '' }}>🔒
                                        Privado</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-people label-icon"></i>
                                    Cupos Disponibles
                                    <span class="required-badge">*</span>
                                </label>
                                <input type="number" name="cupos" class="form-control-modern"
                                    value="{{ old('cupos') }}" min="1" placeholder="Ej: 30" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-currency-dollar label-icon"></i>
                                    Precio (Bs)
                                    <span class="required-badge">*</span>
                                </label>
                                <input type="number" name="precio" class="form-control-modern"
                                    value="{{ old('precio') }}" step="0.01" min="0" placeholder="Ej: 250.00"
                                    required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="wizard-footer">
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
                    Guardar Curso
                </button>
            </div>
        </form>
    </div>

    <script>
        let currentStep = 1;
        const totalSteps = 4;

        // Niveles por edad (ÚNICO objeto)
        const nivelesPorEdad = {
            "3-5": [
                "Preescolar",
                "Educación Inicial",
                "Estimulación Temprana"
            ],
            "6-8": [
                "Primaria (1º y 2º)",
                "Primaria (3º)",
                "Educación Básica"
            ],
            "9-12": [
                "Primaria (4º a 6º)",
                "Educación Intermedia",
                "Pre-adolescentes"
            ],
            "13-15": [
                "Secundaria Básica",
                "Educación Media (1º-3º)",
                "Adolescentes"
            ],
            "16-18": [
                "Preparatoria",
                "Bachillerato",
                "Educación Media Superior",
                "Universitario (inicial)"
            ],
            "18+": [
                "Público General",
                "Adultos",
                "Profesionales",
                "Interesados en Salud Mental",
                "Motivación y Desarrollo Personal",
                "Cuidado Personal y Bienestar"
            ]
        };


        // Actualizar niveles dinámicamente
        function actualizarNiveles() {
            const edadSeleccionada = document.getElementById("edad_id").value;
            const nivelSelect = document.getElementById("nivel_id");

            if (!nivelSelect) return;

            nivelSelect.innerHTML = '<option value="">Seleccione un nivel</option>';

            if (edadSeleccionada && nivelesPorEdad[edadSeleccionada]) {
                nivelesPorEdad[edadSeleccionada].forEach(nivel => {
                    const option = document.createElement("option");
                    option.value = nivel;
                    option.textContent = nivel;
                    nivelSelect.appendChild(option);
                });
            }
        }

        function updateProgress() {
            const progress = ((currentStep - 1) / (totalSteps - 1)) * 100;
            document.getElementById('progressLine').style.width = progress + '%';

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
            document.querySelector(`[data-step="${step}"].form-step`).classList.add('active');

            document.getElementById('prevBtn').style.display = step === 1 ? 'none' : 'flex';
            document.getElementById('nextBtn').style.display = step === totalSteps ? 'none' : 'flex';
            document.getElementById('submitBtn').style.display = step === totalSteps ? 'flex' : 'none';

            updateProgress();
        }

        function validateStep(step) {
            const currentStepElement = document.querySelector(`[data-step="${step}"].form-step`);
            const inputs = currentStepElement.querySelectorAll('input[required], select[required]');

            for (let input of inputs) {
                if (!input.value) {
                    input.focus();
                    input.style.borderColor = '#ff4757';
                    setTimeout(() => input.style.borderColor = '', 2000);
                    return false;
                }
            }
            return true;
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

        document.getElementById('wizardForm').addEventListener('submit', (e) => {
            if (!validateStep(currentStep)) {
                e.preventDefault();
            }
        });

        // Inicializar
        document.addEventListener('DOMContentLoaded', function() {
            showStep(1);
            actualizarNiveles();
        });
    </script>
@endsection



@include('layout')
