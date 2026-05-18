@section('titulo')
Crear Usuario
@endsection

@section('content')
<div class="container my-4">
    <div class="tbl-card shadow-lg">

        <div class="tbl-card-hero">
            <div class="tbl-hero-left">
                <a href="{{ route('ListaUsuarios') }}"
                   class="tbl-hero-btn tbl-hero-btn-glass prt-back-btn mb-2">
                    <i class="bi bi-arrow-left-circle-fill"></i> Volver al Listado
                </a>
                <div class="tbl-hero-eyebrow">
                    <i class="bi bi-person-plus-fill"></i> Nuevo Usuario
                </div>
                <h2 class="tbl-hero-title">Crear Nuevo Usuario</h2>
                <p class="tbl-hero-sub">
                    Sigue los pasos para registrar un nuevo usuario en el sistema.
                </p>
            </div>

            <div class="tbl-hero-controls text-end">
                <div class="ec-role-badge mb-2 d-inline-block">
                    <i class="bi bi-shield-fill me-1"></i> {{ auth()->user()->getRoleNames()->first() }}
                </div>
                <div class="text-white small mb-1">
                    Paso <span id="stepCounter">1</span> de <span id="totalStepsCounter">3</span>
                </div>
                <div class="progress" style="height: 6px; background: rgba(255,255,255,0.2); width: 150px; margin-left: auto;">
                    <div class="progress-bar bg-white" id="progressBar" role="progressbar" style="width: 33%"></div>
                </div>
            </div>
        </div>

        <div class="adm-tabs-header bg-light border-bottom p-0">
            <div class="wizard-steps-nav d-flex overflow-auto">
                <div class="step-nav-item active" data-step="1">
                    <span class="step-num">1</span>
                    <span class="step-text">Datos Personales</span>
                </div>
                <div class="step-nav-item" data-step="2">
                    <span class="step-num">2</span>
                    <span class="step-text">Identificación</span>
                </div>
                <div class="step-nav-item" data-step="3">
                    <span class="step-num">3</span>
                    <span class="step-text">Contacto y Rol</span>
                </div>
            </div>
        </div>

        @if(auth()->user()->hasRole('Administrador'))
            <form id="wizardForm" action="{{ route('CrearDocentePost') }}" method="POST" novalidate>
                @csrf
                <div class="p-4 p-md-5">
                    <div class="form-step active" data-step="1">
                        <div class="step-header mb-4">
                            <h4 class="text-primary fw-bold mb-1">
                                <i class="bi bi-person-fill me-2"></i>Información Personal
                            </h4>
                            <p class="text-muted small">Nombre completo y datos básicos del usuario.</p>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Nombre</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-person text-primary"></i></span>
                                    <input type="text" name="name" class="form-control bg-light" value="{{ old('name') }}" placeholder="Ej. Juan" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold text-muted small text-uppercase">Apellido Paterno</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-person-badge text-primary"></i></span>
                                    <input type="text" name="lastname1" class="form-control bg-light" value="{{ old('lastname1') }}" placeholder="Ej. Pérez" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold text-muted small text-uppercase">Apellido Materno</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-person-badge text-primary"></i></span>
                                    <input type="text" name="lastname2" class="form-control bg-light" value="{{ old('lastname2') }}" placeholder="Ej. López" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-step" data-step="2" style="display: none;">
                        <div class="step-header mb-4">
                            <h4 class="text-primary fw-bold mb-1">
                                <i class="bi bi-card-text me-2"></i>Identificación y Ubicación
                            </h4>
                            <p class="text-muted small">Documentos de identidad y datos de contacto.</p>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted small text-uppercase">Cédula de Identidad</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-credit-card text-primary"></i></span>
                                    <input type="text" name="CI" class="form-control bg-light" value="{{ old('CI') }}" placeholder="Ej. 12345678" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted small text-uppercase">Celular</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-telephone text-primary"></i></span>
                                    <input type="text" name="Celular" class="form-control bg-light" value="{{ old('Celular') }}" placeholder="Ej. 78901234" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted small text-uppercase">Fecha de Nacimiento</label>
                                <input type="date" name="fechadenac" id="fechadenac" class="form-control bg-light" value="{{ old('fechadenac') }}" required max="{{ date('Y-m-d', strtotime('-5 years')) }}" min="{{ date('Y-m-d', strtotime('-100 years')) }}">
                                <div class="form-text text-muted small">Debe tener entre 5 y 100 años</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">País de Residencia</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-geo-alt text-primary"></i></span>
                                    <input type="text" name="PaisReside" class="form-control bg-light" value="{{ old('PaisReside') }}" placeholder="Ej. Bolivia">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Ciudad de Residencia</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-building text-primary"></i></span>
                                    <input type="text" name="CiudadReside" class="form-control bg-light" value="{{ old('CiudadReside') }}" placeholder="Ej. La Paz">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-step" data-step="3" style="display: none;">
                        <div class="step-header mb-4">
                            <h4 class="text-primary fw-bold mb-1">
                                <i class="bi bi-envelope-fill me-2"></i>Contacto y Rol
                            </h4>
                            <p class="text-muted small">Correo electrónico y rol del usuario en el sistema.</p>
                        </div>

                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label fw-bold text-muted small text-uppercase">Correo Electrónico</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-envelope text-primary"></i></span>
                                    <input type="email" name="email" class="form-control bg-light" value="{{ old('email') }}" placeholder="usuario@correo.com" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold text-muted small text-uppercase">Rol del Usuario</label>
                                <div class="role-grid mb-4">
                                    <div class="role-option estudiante" style="position: relative;">
                                        <input type="radio" name="role" id="rol_estudiante" value="Estudiante"
                                            {{ old('role', 'Estudiante') == 'Estudiante' ? 'checked' : '' }} style="opacity: 0; position: absolute; width: 100%; height: 100%; cursor: pointer; z-index: 10;">
                                        <label for="rol_estudiante" style="position: relative; z-index: 1; display: flex; flex-direction: column; align-items: center; gap: 0.75rem; padding: 1.5rem 1rem; border: 3px solid #e2e8f0; border-radius: 16px; cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); background: linear-gradient(180deg, #fff 0%, #f8fafc 100%); font-size: 0.9rem; font-weight: 700; color: #475569; text-align: center; user-select: none; overflow: hidden;">
                                            <div style="width: 70px; height: 70px; border-radius: 50%; background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); display: flex; align-items: center; justify-content: center; transition: all 0.3s;">
                                                <i class="bi bi-book-fill" style="font-size: 2rem; color: #3b82f6;"></i>
                                            </div>
                                            <div>
                                                <div style="font-size: 1rem; color: #1e293b; margin-bottom: 0.25rem;">Estudiante</div>
                                                <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 500;">Accede a cursos y aprende</div>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="role-option docente" style="position: relative;">
                                        <input type="radio" name="role" id="rol_docente" value="Docente"
                                            {{ old('role') == 'Docente' ? 'checked' : '' }} style="opacity: 0; position: absolute; width: 100%; height: 100%; cursor: pointer; z-index: 10;">
                                        <label for="rol_docente" style="position: relative; z-index: 1; display: flex; flex-direction: column; align-items: center; gap: 0.75rem; padding: 1.5rem 1rem; border: 3px solid #e2e8f0; border-radius: 16px; cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); background: linear-gradient(180deg, #fff 0%, #f8fafc 100%); font-size: 0.9rem; font-weight: 700; color: #475569; text-align: center; user-select: none; overflow: hidden;">
                                            <div style="width: 70px; height: 70px; border-radius: 50%; background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); display: flex; align-items: center; justify-content: center; transition: all 0.3s;">
                                                <i class="bi bi-mortarboard-fill" style="font-size: 2rem; color: #10b981;"></i>
                                            </div>
                                            <div>
                                                <div style="font-size: 1rem; color: #1e293b; margin-bottom: 0.25rem;">Docente</div>
                                                <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 500;">Crea y enseña cursos</div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-5 pt-4 border-top">
                        <button type="button" class="btn btn-light rounded-pill px-4 py-2 fw-bold" id="prevBtn" style="display: none;">
                            <i class="bi bi-arrow-left me-2"></i> Anterior
                        </button>
                        <button type="button" class="tbl-hero-btn tbl-hero-btn-primary px-5 py-2 ms-auto" id="nextBtn">
                            Siguiente <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                        <button type="submit" class="tbl-hero-btn tbl-hero-btn-primary px-5 py-2 ms-auto" id="submitBtn" style="display: none;">
                            <i class="bi bi-check-circle-fill me-2"></i> Guardar Usuario
                        </button>
                    </div>
                </div>
            </form>
            @if($errors->any())
                <div class="p-4">
                    <div class="errors-box">
                        <div class="errors-title">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            Corrige los siguientes errores:
                        </div>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        @else
            <div class="p-4 p-md-5">
                <div class="no-access-box">
                    <i class="bi bi-lock-fill me-2"></i>
                    No tienes permisos de administrador para acceder a esta función.
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .role-option input:checked + label {
        border-color: #3b82f6;
        background: linear-gradient(180deg, #eff6ff 0%, #dbeafe 100%);
        box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.25), 0 8px 10px -6px rgba(59, 130, 246, 0.15);
        transform: translateY(-4px);
    }

    .role-option input:checked + label div:first-child {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }

    .role-option input:checked + label div:first-child i {
        color: #fff;
    }

    .role-option input:checked + label div div:first-child {
        color: #1e40af;
    }

    .role-option.docente input:checked + label {
        border-color: #10b981;
        background: linear-gradient(180deg, #f0fdf4 0%, #dcfce7 100%);
        box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.25), 0 8px 10px -6px rgba(16, 185, 129, 0.15);
    }

    .role-option.docente input:checked + label div:first-child {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }

    .role-option.docente input:checked + label div div:first-child {
        color: #065f46;
    }

    .role-option label:hover {
        border-color: #cbd5e1;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let currentStep = 1;
    const totalSteps = 3;

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

    document.getElementById('wizardForm').addEventListener('submit', function(e) {
        if (!validateStep(currentStep)) {
            e.preventDefault();
            return;
        }
        e.preventDefault();
        const form = this;
        Swal.fire({
            title: '¿Confirmar Registro?', text: "Se creará un nuevo usuario.", icon: 'question',
            showCancelButton: true, confirmButtonColor: '#10b981', confirmButtonText: 'Sí, crear usuario'
        }).then((result) => { if (result.isConfirmed) form.submit(); });
    });

    document.addEventListener('DOMContentLoaded', () => {
        showStep(1);
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
