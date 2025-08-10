@section('titulo')
    Editar Perfil
@endsection




@section('content')
<h1>Editar Perfíl</h1>

<form action="{{ route('EditarperfilPost', encrypt(auth()->user()->id)) }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- Indicador de Progreso -->
    <div class="progress mb-4" style="height: 6px;">
        <div class="progress-bar bg-gradient" id="progressBar" role="progressbar" style="width: 33%"></div>
    </div>

    <!-- Pestañas de Navegación -->
    <ul class="nav nav-pills nav-justified mb-4" id="profileTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active d-flex align-items-center justify-content-center"
                    id="contacto-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#contacto"
                    type="button"
                    role="tab"
                    aria-controls="contacto"
                    aria-selected="true">
                <i class="fas fa-address-card me-2"></i>
                <span>Datos de Contacto</span>
            </button>
        </li>
        @if (auth()->user()->hasRole('Docente'))
            <li class="nav-item" role="presentation">
                <button class="nav-link d-flex align-items-center justify-content-center"
                        id="profesional-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#profesional"
                        type="button"
                        role="tab"
                        aria-controls="profesional"
                        aria-selected="false">
                    <i class="fas fa-briefcase me-2"></i>
                    <span>Datos Profesionales</span>
                </button>
            </li>
        @endif
        <li class="nav-item" role="presentation">
            <button class="nav-link d-flex align-items-center justify-content-center"
                    id="confirmacion-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#confirmacion"
                    type="button"
                    role="tab"
                    aria-controls="confirmacion"
                    aria-selected="false">
                <i class="fas fa-check-circle me-2"></i>
                <span>Confirmar Cambios</span>
            </button>
        </li>
    </ul>

    <!-- Contenido de las Pestañas -->
    <div class="tab-content border rounded p-4" id="profileTabContent">

        <!-- Pestaña de Datos de Contacto -->
        <div class="tab-pane fade show active" id="contacto" role="tabpanel" aria-labelledby="contacto-tab">
            <div class="d-flex align-items-center mb-4">
                <i class="fas fa-user-circle text-primary fs-2 me-3"></i>
                <div>
                    <h3 class="mb-1">Información Personal</h3>
                    <p class="text-muted mb-0">Actualiza tus datos de contacto y información personal</p>
                </div>
            </div>

            <!-- Nombre -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="name" class="form-label fw-semibold">
                        <i class="fas fa-user me-1"></i>Nombre
                    </label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ auth()->user()->name }}"
                           class="form-control form-control-lg"
                           placeholder="Ingresa tu nombre">
                </div>
            </div>

            <!-- Apellidos -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="lastname1" class="form-label fw-semibold">
                        <i class="fas fa-id-card me-1"></i>Apellido Paterno
                    </label>
                    <input type="text"
                           name="lastname1"
                           id="lastname1"
                           value="{{ auth()->user()->lastname1 }}"
                           class="form-control form-control-lg"
                           placeholder="Apellido paterno">
                </div>
                <div class="col-md-6">
                    <label for="lastname2" class="form-label fw-semibold">
                        <i class="fas fa-id-card me-1"></i>Apellido Materno
                    </label>
                    <input type="text"
                           name="lastname2"
                           id="lastname2"
                           value="{{ auth()->user()->lastname2 }}"
                           class="form-control form-control-lg"
                           placeholder="Apellido materno">
                </div>
            </div>

            <!-- Contacto -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="Celular" class="form-label fw-semibold">
                        <i class="fas fa-mobile-alt me-1"></i>Número de Celular
                    </label>
                    <input type="tel"
                           name="Celular"
                           id="Celular"
                           value="{{ auth()->user()->Celular }}"
                           class="form-control form-control-lg"
                           placeholder="Ej: +591 70000000"
                           pattern="[0-9+\-\s]+">
                </div>
                <div class="col-md-6">
                    <label for="fecha_nac" class="form-label fw-semibold">
                        <i class="fas fa-calendar-alt me-1"></i>Fecha de Nacimiento
                    </label>
                    <input type="date"
                           name="fecha_nac"
                           id="fecha_nac"
                           value="{{ auth()->user()->fechadenac }}"
                           class="form-control form-control-lg">
                </div>
            </div>

            <!-- Ubicación -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="PaisReside" class="form-label fw-semibold">
                        <i class="fas fa-globe me-1"></i>País de Residencia
                    </label>
                    <input type="text"
                           name="PaisReside"
                           id="PaisReside"
                           value="{{ auth()->user()->PaisReside }}"
                           class="form-control form-control-lg"
                           placeholder="Ej: Bolivia">
                </div>
                <div class="col-md-6">
                    <label for="CiudadReside" class="form-label fw-semibold">
                        <i class="fas fa-map-marker-alt me-1"></i>Ciudad de Residencia
                    </label>
                    <input type="text"
                           name="CiudadReside"
                           id="CiudadReside"
                           value="{{ auth()->user()->CiudadReside }}"
                           class="form-control form-control-lg"
                           placeholder="Ej: La Paz">
                </div>
            </div>

            <!-- Botones de navegación -->
            <div class="d-flex justify-content-end">
                @if (auth()->user()->hasRole('Docente'))
                    <button type="button" class="btn btn-primary btn-lg" onclick="nextTab('profesional-tab')">
                        Siguiente: Datos Profesionales
                        <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                @else
                    <button type="button" class="btn btn-primary btn-lg" onclick="nextTab('confirmacion-tab')">
                        Siguiente: Confirmar Cambios
                        <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                @endif
            </div>
        </div>

        <!-- Pestaña de Datos Profesionales (solo para Docentes) -->
        @if (auth()->user()->hasRole('Docente'))
            <div class="tab-pane fade" id="profesional" role="tabpanel" aria-labelledby="profesional-tab">
                <div class="d-flex align-items-center mb-4">
                    <i class="fas fa-graduation-cap text-success fs-2 me-3"></i>
                    <div>
                        <h3 class="mb-1">Información Profesional</h3>
                        <p class="text-muted mb-0">Datos académicos y experiencia laboral</p>
                    </div>
                </div>

                <!-- Formación Académica -->
                @foreach ($atributosD as $atributo)
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-certificate me-2"></i>Formación Académica
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="formacion" class="form-label fw-semibold">Título/Grado</label>
                                    <input type="text"
                                           name="formacion"
                                           id="formacion"
                                           placeholder="Ej: Licenciatura en Ingeniería"
                                           value="{{ $atributo->formacion ?? '' }}"
                                           class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="Especializacion" class="form-label fw-semibold">Especialización</label>
                                    <input type="text"
                                           name="Especializacion"
                                           id="Especializacion"
                                           placeholder="Ej: Desarrollo de Software"
                                           value="{{ $atributo->Especializacion ?? '' }}"
                                           class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="ExperienciaL" class="form-label fw-semibold">Años de Experiencia</label>
                                    <input type="number"
                                           name="ExperienciaL"
                                           id="ExperienciaL"
                                           placeholder="Ej: 5"
                                           value="{{ $atributo->ExperienciaL ?? '' }}"
                                           class="form-control"
                                           min="0"
                                           max="50">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Historial Laboral -->
                <div class="card mb-4">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-briefcase me-2"></i>Historial Laboral
                        </h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="agregarTrabajo()">
                            <i class="fas fa-plus me-1"></i>Agregar Trabajo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="tablaTrabajo">
                                <thead class="table-light">
                                    <tr>
                                        <th><i class="fas fa-building me-1"></i>Empresa</th>
                                        <th><i class="fas fa-user-tie me-1"></i>Cargo</th>
                                        <th><i class="fas fa-calendar-plus me-1"></i>Inicio</th>
                                        <th><i class="fas fa-calendar-minus me-1"></i>Fin</th>
                                        <th><i class="fas fa-phone me-1"></i>Referencia</th>
                                        <th width="50">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($ultimosTrabajos ?? [] as $index => $trabajo)
                                        <tr>
                                            <td>
                                                <input type="text"
                                                       name="trabajos[{{ $index }}][empresa]"
                                                       value="{{ $trabajo->empresa ?? '' }}"
                                                       class="form-control"
                                                       placeholder="Nombre de la empresa">
                                            </td>
                                            <td>
                                                <input type="text"
                                                       name="trabajos[{{ $index }}][cargo]"
                                                       value="{{ $trabajo->cargo ?? '' }}"
                                                       class="form-control"
                                                       placeholder="Cargo desempeñado">
                                            </td>
                                            <td>
                                                <input type="date"
                                                       name="trabajos[{{ $index }}][fechain]"
                                                       value="{{ $trabajo->fecha_inicio ?? '' }}"
                                                       class="form-control">
                                            </td>
                                            <td>
                                                <input type="date"
                                                       name="trabajos[{{ $index }}][fechasal]"
                                                       value="{{ $trabajo->fecha_fin ?? '' }}"
                                                       class="form-control">
                                            </td>
                                            <td>
                                                <input type="text"
                                                       name="trabajos[{{ $index }}][contacto]"
                                                       value="{{ $trabajo->contacto_ref ?? '' }}"
                                                       class="form-control"
                                                       placeholder="Contacto de referencia">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarFila(this)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        @for ($i = 0; $i < 3; $i++)
                                            <tr>
                                                <td>
                                                    <input type="text"
                                                           name="trabajos[{{ $i }}][empresa]"
                                                           class="form-control"
                                                           placeholder="Nombre de la empresa">
                                                </td>
                                                <td>
                                                    <input type="text"
                                                           name="trabajos[{{ $i }}][cargo]"
                                                           class="form-control"
                                                           placeholder="Cargo desempeñado">
                                                </td>
                                                <td>
                                                    <input type="date"
                                                           name="trabajos[{{ $i }}][fechain]"
                                                           class="form-control">
                                                </td>
                                                <td>
                                                    <input type="date"
                                                           name="trabajos[{{ $i }}][fechasal]"
                                                           class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text"
                                                           name="trabajos[{{ $i }}][contacto]"
                                                           class="form-control"
                                                           placeholder="Contacto de referencia">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarFila(this)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endfor
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Navegación -->
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary btn-lg" onclick="previousTab('contacto-tab')">
                        <i class="fas fa-arrow-left me-2"></i>
                        Anterior: Datos de Contacto
                    </button>
                    <button type="button" class="btn btn-primary btn-lg" onclick="nextTab('confirmacion-tab')">
                        Siguiente: Confirmar Cambios
                        <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </div>
            </div>
        @endif

        <!-- Pestaña de Confirmación -->
        <div class="tab-pane fade" id="confirmacion" role="tabpanel" aria-labelledby="confirmacion-tab">
            <div class="d-flex align-items-center mb-4">
                <i class="fas fa-shield-alt text-warning fs-2 me-3"></i>
                <div>
                    <h3 class="mb-1">Confirmar Cambios</h3>
                    <p class="text-muted mb-0">Verifica tu contraseña para guardar los cambios</p>
                </div>
            </div>

            <!-- Resumen de cambios -->
            <div class="alert alert-info mb-4">
                <h6 class="alert-heading">
                    <i class="fas fa-info-circle me-2"></i>Resumen de Cambios
                </h6>
                <p class="mb-0">
                    Los cambios realizados en tu perfil se guardarán permanentemente.
                    Asegúrate de que toda la información sea correcta antes de continuar.
                </p>
            </div>

            <!-- Verificación de contraseña -->
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <label for="confirmpassword" class="form-label fw-semibold">
                                <i class="fas fa-lock me-1"></i>Confirma tu Contraseña
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <input type="password"
                                       id="confirmpassword"
                                       name="confirmpassword"
                                       placeholder="Ingresa tu contraseña actual"
                                       class="form-control"
                                       required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Necesitamos verificar tu identidad para guardar los cambios
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navegación final -->
            <div class="d-flex justify-content-between mt-4">
                @if (auth()->user()->hasRole('Docente'))
                    <button type="button" class="btn btn-outline-secondary btn-lg" onclick="previousTab('profesional-tab')">
                        <i class="fas fa-arrow-left me-2"></i>
                        Anterior: Datos Profesionales
                    </button>
                @else
                    <button type="button" class="btn btn-outline-secondary btn-lg" onclick="previousTab('contacto-tab')">
                        <i class="fas fa-arrow-left me-2"></i>
                        Anterior: Datos de Contacto
                    </button>
                @endif

                <button type="submit" class="btn btn-success btn-lg" id="btnGuardar">
                    <i class="fas fa-save me-2"></i>
                    Guardar Todos los Cambios
                </button>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('confirmpassword');
    const eyeIcon = document.getElementById('eyeIcon');

    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            if (type === 'password') {
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            } else {
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            }
        });
    }

    // Actualizar barra de progreso
    updateProgressBar();

    // Event listeners para las pestañas
    document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', updateProgressBar);
    });
});

// Funciones de navegación entre pestañas
function nextTab(tabId) {
    const tab = new bootstrap.Tab(document.getElementById(tabId));
    tab.show();
    updateProgressBar();
}

function previousTab(tabId) {
    const tab = new bootstrap.Tab(document.getElementById(tabId));
    tab.show();
    updateProgressBar();
}

// Actualizar barra de progreso
function updateProgressBar() {
    const activeTab = document.querySelector('.nav-link.active');
    const totalTabs = document.querySelectorAll('.nav-link').length;
    const currentIndex = Array.from(document.querySelectorAll('.nav-link')).indexOf(activeTab);
    const progress = ((currentIndex + 1) / totalTabs) * 100;

    document.getElementById('progressBar').style.width = progress + '%';

    // Cambiar color según progreso
    const progressBar = document.getElementById('progressBar');
    if (progress < 50) {
        progressBar.className = 'progress-bar bg-primary';
    } else if (progress < 100) {
        progressBar.className = 'progress-bar bg-warning';
    } else {
        progressBar.className = 'progress-bar bg-success';
    }
}

// Agregar nueva fila de trabajo
function agregarTrabajo() {
    const tbody = document.querySelector('#tablaTrabajo tbody');
    const rowCount = tbody.rows.length;

    const newRow = tbody.insertRow();
    newRow.innerHTML = `
        <td><input type="text" name="trabajos[${rowCount}][empresa]" class="form-control" placeholder="Nombre de la empresa"></td>
        <td><input type="text" name="trabajos[${rowCount}][cargo]" class="form-control" placeholder="Cargo desempeñado"></td>
        <td><input type="date" name="trabajos[${rowCount}][fechain]" class="form-control"></td>
        <td><input type="date" name="trabajos[${rowCount}][fechasal]" class="form-control"></td>
        <td><input type="text" name="trabajos[${rowCount}][contacto]" class="form-control" placeholder="Contacto de referencia"></td>
        <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarFila(this)"><i class="fas fa-trash"></i></button></td>
    `;
}

// Eliminar fila de trabajo
function eliminarFila(button) {
    const row = button.closest('tr');
    const tbody = row.parentElement;

    if (tbody.rows.length > 1) {
        row.remove();
        // Reindexar los nombres de los inputs
        reindexarTrabajos();
    } else {
        alert('Debe mantener al menos una fila.');
    }
}

// Reindexar nombres de inputs después de eliminar
function reindexarTrabajos() {
    const rows = document.querySelectorAll('#tablaTrabajo tbody tr');
    rows.forEach((row, index) => {
        const inputs = row.querySelectorAll('input');
        inputs.forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                const newName = name.replace(/\[\d+\]/, `[${index}]`);
                input.setAttribute('name', newName);
            }
        });
    });
}

// Validación antes de enviar
document.querySelector('form').addEventListener('submit', function(e) {
    const password = document.getElementById('confirmpassword').value;

    if (!password.trim()) {
        e.preventDefault();
        alert('Por favor ingresa tu contraseña para confirmar los cambios.');
        document.getElementById('confirmpassword').focus();
        return;
    }

    // Confirmar envío
    if (!confirm('¿Estás seguro de que deseas guardar todos los cambios?')) {
        e.preventDefault();
    }
});
</script>

<style>
.nav-pills .nav-link {
    border-radius: 10px;
    padding: 12px 20px;
    margin: 0 5px;
    transition: all 0.3s ease;
}

.nav-pills .nav-link:hover {
    background-color: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
}

.nav-pills .nav-link.active {
    background: linear-gradient(135deg, #0d6efd, #6610f2);
    border: none;
}

.form-control-lg {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    transition: border-color 0.3s ease;
}

.form-control-lg:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.card {
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-radius: 12px;
}

.progress {
    border-radius: 10px;
    background-color: #f8f9fa;
}

.progress-bar {
    border-radius: 10px;
    transition: width 0.5s ease;
}

.btn-lg {
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 500;
}

.table th {
    background-color: #f8f9fa;
    border: none;
    font-weight: 600;
    color: #495057;
}

.table td {
    border: none;
    border-bottom: 1px solid #e9ecef;
    vertical-align: middle;
}
</style>

<!-- Script para mostrar/ocultar contraseña -->
<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('confirmpassword');
        const icon = this;
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    });
</script>
@endsection

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>


@include('layout')
