@section('content')
<div class="container my-4">
    <div class="tbl-card shadow-lg">

        <div class="tbl-card-hero">
            <div class="tbl-hero-left">
                <a href="{{ route('ListadeCursos') }}"
                   class="tbl-hero-btn tbl-hero-btn-glass prt-back-btn mb-2">
                    <i class="bi bi-arrow-left-circle-fill"></i> Volver al Listado
                </a>
                <div class="tbl-hero-eyebrow">
                    <i class="bi bi-plus-circle-fill"></i> Nueva Oferta Académica
                </div>
                <h2 class="tbl-hero-title">Crear Nuevo Curso o Evento</h2>
                <p class="tbl-hero-sub">
                    Sigue los pasos para registrar un nuevo curso en el sistema.
                </p>
            </div>

            <div class="tbl-hero-controls text-end">
                <div class="ec-role-badge mb-2 d-inline-block">
                    <i class="bi bi-shield-fill me-1"></i> {{ auth()->user()->getRoleNames()->first() }}
                </div>
                <div class="text-white small mb-1">
                    Paso <span id="stepCounter">1</span> de <span id="totalStepsCounter">4</span>
                </div>
                <div class="progress" style="height: 6px; background: rgba(255,255,255,0.2); width: 150px; margin-left: auto;">
                    <div class="progress-bar bg-white" id="progressBar" role="progressbar" style="width: 25%"></div>
                </div>
            </div>
        </div>


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
                <div class="step-nav-item" data-step="3">
                    <span class="step-num">3</span>
                    <span class="step-text">Público Objetivo</span>
                </div>
                <div class="step-nav-item" data-step="4">
                    <span class="step-num">4</span>
                    <span class="step-text">Precio y Cupos</span>
                </div>
            </div>
        </div>

        <form id="wizardForm" action="{{ route('CrearCursoPost') }}" method="POST">
            @csrf
            <div class="p-4 p-md-5">
                <!-- Step 1: Datos Básicos -->
                <div class="form-step active" data-step="1">
                    <div class="step-header mb-4">
                        <h4 class="text-primary fw-bold mb-1">
                            <i class="bi bi-info-circle-fill me-2"></i>Información Fundamental
                        </h4>
                        <p class="text-muted small">Nombre, descripción y cronograma inicial del curso.</p>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small text-uppercase">Nombre del Curso</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-bookmark-fill text-primary"></i></span>
                                <input type="text" name="nombre" class="form-control bg-light" value="{{ old('nombre') }}" placeholder="Ej: Introducción a la Programación" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small text-uppercase">Breve Descripción</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-text-paragraph text-primary"></i></span>
                                <input type="text" name="descripcion" class="form-control bg-light" value="{{ old('descripcion') }}" placeholder="Resumen corto del curso">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold text-muted small text-uppercase">Fecha Inicio</label>
                            <input type="date" name="fecha_ini" class="form-control bg-light" value="{{ old('fecha_ini') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-muted small text-uppercase">Hora Inicio</label>
                            <input type="time" name="hora_ini" class="form-control bg-light" value="{{ old('hora_ini') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-muted small text-uppercase">Fecha Fin</label>
                            <input type="date" name="fecha_fin" class="form-control bg-light" value="{{ old('fecha_fin') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-muted small text-uppercase">Hora Fin</label>
                            <input type="time" name="hora_fin" class="form-control bg-light" value="{{ old('hora_fin') }}">
                        </div>
                    </div>
                </div>

                <!-- Step 2: Configuración -->
                <div class="form-step" data-step="2" style="display: none;">
                    <div class="step-header mb-4">
                        <h4 class="text-primary fw-bold mb-1">
                            <i class="bi bi-gear-fill me-2"></i>Configuración y Docencia
                        </h4>
                        <p class="text-muted small">Define el formato, tipo y el docente responsable.</p>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small text-uppercase">Formato</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-laptop text-primary"></i></span>
                                <select name="formato" class="form-select bg-light">
                                    <option value="Virtual" {{ old('formato') == 'Virtual' ? 'selected' : '' }}>💻 Virtual</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small text-uppercase">Tipo de Oferta</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-tags-fill text-primary"></i></span>
                                <select name="tipo" class="form-select bg-light">
                                    <option value="curso" {{ old('tipo') == 'curso' ? 'selected' : '' }}>📚 Curso Regular</option>
                                    <option value="congreso" {{ old('tipo') == 'congreso' ? 'selected' : '' }}>📅 Evento / Congreso</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small text-uppercase">Docente Asignado</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text bg-light"><i class="bi bi-person-badge text-primary"></i></span>
                                <select name="docente_id" class="form-select bg-light" required>
                                    <option value="">Seleccione un docente</option>
                                    @forelse ($docente as $doc)
                                        <option value="{{ $doc->id }}" {{ old('docente_id') == $doc->id ? 'selected' : '' }}>
                                            {{ $doc->name }} {{ $doc->lastname1 }} {{ $doc->lastname2 }}
                                        </option>
                                    @empty
                                        <option value="" disabled>No hay docentes registrados</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="text-end">
                                <a href="{{ route('CrearUsuario') }}" class="small fw-bold text-primary text-decoration-none">
                                    <i class="bi bi-plus-circle me-1"></i>Nuevo Docente
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Público Objetivo -->
                <div class="form-step" data-step="3" style="display: none;">
                    <div class="step-header mb-4">
                        <h4 class="text-primary fw-bold mb-1">
                            <i class="bi bi-people-fill me-2"></i>Público Objetivo
                        </h4>
                        <p class="text-muted small">Especifica el rango de edad y nivel educativo.</p>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small text-uppercase">Rango de Edad</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-person-check text-primary"></i></span>
                                <select id="edad_id" name="edad_id" class="form-select bg-light" onchange="actualizarNiveles()">
                                    <option value="">Seleccione un rango</option>
                                    <option value="3-5" {{ old('edad_id') == '3-5' ? 'selected' : '' }}>👶 3 a 5 años</option>
                                    <option value="6-8" {{ old('edad_id') == '6-8' ? 'selected' : '' }}>🧒 6 a 8 años</option>
                                    <option value="9-12" {{ old('edad_id') == '9-12' ? 'selected' : '' }}>👦 9 a 12 años</option>
                                    <option value="13-15" {{ old('edad_id') == '13-15' ? 'selected' : '' }}>👨 13 a 15 años</option>
                                    <option value="16-18" {{ old('edad_id') == '16-18' ? 'selected' : '' }}>🎓 16 a 18 años</option>
                                    <option value="18+" {{ old('edad_id') == '18+' ? 'selected' : '' }}>👔 18 años o más</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small text-uppercase">Nivel Educativo</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-bar-chart-fill text-primary"></i></span>
                                <select id="nivel_id" name="nivel_id" class="form-select bg-light">
                                    <option value="">Seleccione un nivel</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Detalles Finales -->
                <div class="form-step" data-step="4" style="display: none;">
                    <div class="step-header mb-4">
                        <h4 class="text-primary fw-bold mb-1">
                            <i class="bi bi-card-checklist me-2"></i>Inversión y Disponibilidad
                        </h4>
                        <p class="text-muted small">Completa los detalles de duración, cupos y precio.</p>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-muted small text-uppercase">Duración (horas)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-hourglass-split text-primary"></i></span>
                                <input type="number" name="duracion" class="form-control bg-light" value="{{ old('duracion') }}" min="1" placeholder="Ej: 40" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-muted small text-uppercase">Visibilidad</label>
                            <select name="visibilidad" class="form-select bg-light">
                                <option value="publico" {{ old('visibilidad') == 'publico' ? 'selected' : '' }}>🌐 Público</option>
                                <option value="privado" {{ old('visibilidad') == 'privado' ? 'selected' : '' }}>🔒 Privado</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-muted small text-uppercase">Cupos Disponibles</label>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="cupos_ilimitados" {{ old('cupos') == '0' ? 'checked' : '' }} onchange="toggleCuposIlimitados(this)">
                                <label class="form-check-label small fw-semibold" for="cupos_ilimitados">Ilimitado</label>
                            </div>
                            <input type="number" name="cupos" id="cupos_input" class="form-control bg-light" value="{{ old('cupos', '') }}" min="1" placeholder="Ej: 30" {{ old('cupos') == '0' ? 'disabled' : '' }} required>
                            <input type="hidden" name="cupos" id="cupos_hidden" value="0" disabled>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-muted small text-uppercase">Precio Inscripción</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light fw-bold text-primary">Bs</span>
                                <input type="number" name="precio" class="form-control bg-light" value="{{ old('precio') }}" step="0.01" min="0" placeholder="Ej: 250.00" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Wizard Buttons -->
                <div class="d-flex justify-content-between mt-5 pt-4 border-top">
                    <button type="button" class="btn btn-light rounded-pill px-4 py-2 fw-bold" id="prevBtn" style="display: none;">
                        <i class="bi bi-arrow-left me-2"></i> Anterior
                    </button>
                    <button type="button" class="tbl-hero-btn tbl-hero-btn-primary px-5 py-2 ms-auto" id="nextBtn">
                        Siguiente <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                    <button type="submit" class="tbl-hero-btn tbl-hero-btn-primary px-5 py-2 ms-auto" id="submitBtn" style="display: none;">
                        <i class="bi bi-check-circle-fill me-2"></i> Guardar Curso
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .wizard-steps-nav { gap: 0.5rem; padding: 1rem; }
    .step-nav-item {
        display: flex; align-items: center; gap: 0.75rem;
        padding: 0.6rem 1.25rem; border-radius: 50px;
        background: #f8fafc; border: 1.5px solid #e2eaf4;
        color: #64748b; font-weight: 700; font-size: 0.82rem;
        white-space: nowrap; cursor: default; transition: all 0.3s;
    }
    .step-nav-item.active {
        background: rgba(20, 93, 160, 0.08); border-color: #145da0; color: #145da0;
    }
    .step-nav-item.completed {
        background: #f0fdf4; border-color: #16a34a; color: #16a34a;
    }
    .step-num {
        width: 24px; height: 24px; border-radius: 50%;
        background: currentColor; color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.7rem;
    }
    .ec-role-badge {
        background: rgba(255,165,0,0.15); color: #ffa500;
        padding: 0.25rem 0.75rem; border-radius: 50px; font-size: 0.7rem; font-weight: 800;
        border: 1px solid rgba(255,165,0,0.3);
    }
    .form-control, .form-select {
        border-radius: 12px; border: 1.5px solid #e2eaf4; padding: 0.6rem 1rem;
        transition: all 0.2s; font-size: 0.88rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #145da0; box-shadow: 0 0 0 4px rgba(20, 93, 160, 0.1);
        background: #fff !important;
    }
    .input-group-text {
        border-radius: 12px 0 0 12px; border: 1.5px solid #e2eaf4; border-right: none;
    }
    .input-group .form-control, .input-group .form-select { border-radius: 0 12px 12px 0; }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let currentStep = 1;
    const totalSteps = 4;

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
        nivelSelect.innerHTML = '<option value="">Seleccione un nivel</option>';
        if (edad && nivelesPorEdad[edad]) {
            nivelesPorEdad[edad].forEach(nivel => {
                const option = document.createElement("option");
                option.value = nivel;
                option.textContent = nivel;
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
                item.querySelector('.step-num').innerHTML = '<i class="bi bi-check-lg"></i>';
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
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function validateStep(step) {
        const el = document.querySelector(`[data-step="${step}"].form-step`);
        if (!el) return true;

        // Solo validar inputs que NO estén deshabilitados
        const inputs = el.querySelectorAll('input[required]:not(:disabled), select[required]:not(:disabled)');

        for (let input of inputs) {
            if (!input.value.trim()) {
                input.focus();
                input.classList.add('is-invalid');
                setTimeout(() => input.classList.remove('is-invalid'), 3000);
                Swal.fire({
                    icon: 'warning', title: 'Campo Requerido',
                    text: 'Completa los campos obligatorios para continuar.',
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 3000
                });
                return false;
            }
        }
        return true;
    }

    document.getElementById('nextBtn').addEventListener('click', () => {
        if (validateStep(currentStep)) {
            if (currentStep < totalSteps) { currentStep++; showStep(currentStep); }
        }
    });

    document.getElementById('prevBtn').addEventListener('click', () => {
        if (currentStep > 1) { currentStep--; showStep(currentStep); }
    });

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
        if (!validateStep(currentStep)) {
            e.preventDefault();
            return;
        }
        e.preventDefault();
        Swal.fire({
            title: '¿Confirmar Registro?', text: "Se creará una nueva oferta académica.", icon: 'question',
            showCancelButton: true, confirmButtonColor: '#10b981', confirmButtonText: 'Sí, crear curso'
        }).then((result) => { if (result.isConfirmed) this.submit(); });
    });

    document.addEventListener('DOMContentLoaded', () => {
        showStep(1);
        actualizarNiveles();
    });

    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Éxito', text: "{{ session('success') }}" });
    @endif
    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Error', text: "{{ session('error') }}" });
    @endif
</script>
@endsection



@include('layout')
