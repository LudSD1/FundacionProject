@extends('layout')

@section('titulo', 'Editar Perfil')

@section('content')
<div class="container my-4">
    <div class="tbl-card shadow-lg">
        <div class="tbl-card-hero">
            <div class="tbl-hero-left">
                <a href="{{ route('perfil', [encrypt(auth()->user()->id)]) }}"
                   class="tbl-hero-btn tbl-hero-btn-glass prt-back-btn mb-2">
                    <i class="bi bi-arrow-left-circle-fill"></i> Volver a Perfil
                </a>
                <div class="tbl-hero-eyebrow">
                    <i class="bi bi-person-badge-fill"></i> Mi Cuenta
                </div>
                <h2 class="tbl-hero-title">Editar Perfil</h2>
                <p class="tbl-hero-sub text-white-50">
                    Mantén tu información actualizada para una mejor experiencia.
                </p>
            </div>

        </div>

        {{-- Pestañas de Navegación Estilo Dashboard --}}
        <div class="adm-tabs-header bg-light border-bottom">
            <ul class="nav adm-tabs-nav" id="profileTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="adm-tab active" id="contacto-tab" data-bs-toggle="tab" data-bs-target="#contacto" type="button" role="tab">
                        <i class="bi bi-person-lines-fill me-2"></i> Datos de Contacto
                    </button>
                </li>
                @if (auth()->user()->hasRole('Docente'))
                    <li class="nav-item" role="presentation">
                        <button class="adm-tab" id="profesional-tab" data-bs-toggle="tab" data-bs-target="#profesional" type="button" role="tab">
                            <i class="bi bi-briefcase-fill me-2"></i> Información Profesional
                        </button>
                    </li>
                @endif
                <li class="nav-item" role="presentation">
                    <button class="adm-tab" id="confirmacion-tab" data-bs-toggle="tab" data-bs-target="#confirmacion" type="button" role="tab">
                        <i class="bi bi-shield-check-fill me-2"></i> Confirmar
                    </button>
                </li>
            </ul>
        </div>

        <div class="p-0">
            <form action="{{ route('EditarperfilPost', encrypt(auth()->user()->id)) }}" method="POST" enctype="multipart/form-data" id="profileForm">
                @csrf

                <div class="tab-content" id="profileTabContent">

                    <!-- Pestaña de Datos de Contacto -->
                    <div class="tab-pane fade show active p-4 p-md-5" id="contacto" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-12 border-bottom pb-3 mb-2">
                                <h5 class="text-primary mb-1"><i class="bi bi-info-circle-fill me-2"></i>Información Personal</h5>
                                <p class="text-muted small">Actualiza tus nombres y apellidos principales.</p>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold text-muted small text-uppercase">Nombre Completo</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-person text-primary"></i></span>
                                    <input type="text" name="name" value="{{ auth()->user()->name }}" class="form-control bg-light" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Apellido Paterno</label>
                                <input type="text" name="lastname1" value="{{ auth()->user()->lastname1 }}" class="form-control bg-light">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Apellido Materno</label>
                                <input type="text" name="lastname2" value="{{ auth()->user()->lastname2 }}" class="form-control bg-light">
                            </div>

                            <div class="col-12 border-bottom pb-3 mt-5 mb-2">
                                <h5 class="text-primary mb-1"><i class="bi bi-telephone-fill me-2"></i>Contacto y Ubicación</h5>
                                <p class="text-muted small">Datos para que la institución pueda contactarte.</p>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Número de Celular</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-whatsapp text-success"></i></span>
                                    <input type="tel" name="Celular" value="{{ auth()->user()->Celular }}" class="form-control bg-light" placeholder="+591 ...">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Fecha de Nacimiento</label>
                                <input type="date" name="fecha_nac" value="{{ auth()->user()->fechadenac }}" class="form-control bg-light">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">País</label>
                                <input type="text" name="PaisReside" value="{{ auth()->user()->PaisReside }}" class="form-control bg-light">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Ciudad</label>
                                <input type="text" name="CiudadReside" value="{{ auth()->user()->CiudadReside }}" class="form-control bg-light">
                            </div>
                        </div>

                        <div class="mt-5 text-end">
                            @if (auth()->user()->hasRole('Docente'))
                                <button type="button" class="tbl-hero-btn tbl-hero-btn-primary px-5" onclick="nextTab('profesional-tab')">
                                    Siguiente <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            @else
                                <button type="button" class="tbl-hero-btn tbl-hero-btn-primary px-5" onclick="nextTab('confirmacion-tab')">
                                    Siguiente <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Pestaña Profesional (Docente) -->
                    @if (auth()->user()->hasRole('Docente'))
                        <div class="tab-pane fade p-4 p-md-5" id="profesional" role="tabpanel">
                            <div class="row g-4">
                                <div class="col-12 border-bottom pb-3 mb-2">
                                    <h5 class="text-primary mb-1"><i class="bi bi-mortarboard-fill me-2"></i>Formación Académica</h5>
                                    <p class="text-muted small">Tu grado académico y especialidad actual.</p>
                                </div>

                                @foreach ($atributosD as $atributo)
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold text-muted small text-uppercase">Título / Grado</label>
                                        <input type="text" name="formacion" value="{{ $atributo->formacion ?? '' }}" class="form-control bg-light">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold text-muted small text-uppercase">Especialización</label>
                                        <input type="text" name="Especializacion" value="{{ $atributo->Especializacion ?? '' }}" class="form-control bg-light">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold text-muted small text-uppercase">Años Exp.</label>
                                        <input type="number" name="ExperienciaL" value="{{ $atributo->ExperienciaL ?? '' }}" class="form-control bg-light">
                                    </div>
                                @endforeach

                                <div class="col-12 border-bottom pb-3 mt-5 mb-2 d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="text-primary mb-1"><i class="bi bi-briefcase-fill me-2"></i>Historial Laboral</h5>
                                        <p class="text-muted small mb-0">Tus últimos empleos relevantes.</p>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3" onclick="agregarTrabajo()">
                                        <i class="bi bi-plus-lg me-1"></i> Agregar
                                    </button>
                                </div>

                                <div class="col-12">
                                    <div class="table-container-modern shadow-none border">
                                        <table class="table-modern" id="tablaTrabajo">
                                            <thead>
                                                <tr>
                                                    <th>Empresa</th>
                                                    <th>Cargo</th>
                                                    <th>Inicio</th>
                                                    <th>Fin</th>
                                                    <th width="50"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($ultimosTrabajos ?? [] as $index => $trabajo)
                                                    <tr>
                                                        <td><input type="text" name="trabajos[{{ $index }}][empresa]" value="{{ $trabajo->empresa ?? '' }}" class="form-control form-control-sm border-0 bg-transparent"></td>
                                                        <td><input type="text" name="trabajos[{{ $index }}][cargo]" value="{{ $trabajo->cargo ?? '' }}" class="form-control form-control-sm border-0 bg-transparent"></td>
                                                        <td><input type="date" name="trabajos[{{ $index }}][fechain]" value="{{ $trabajo->fecha_inicio ?? '' }}" class="form-control form-control-sm border-0 bg-transparent"></td>
                                                        <td><input type="date" name="trabajos[{{ $index }}][fechasal]" value="{{ $trabajo->fecha_fin ?? '' }}" class="form-control form-control-sm border-0 bg-transparent"></td>
                                                        <td class="text-center"><button type="button" class="text-danger border-0 bg-transparent" onclick="eliminarFila(this)"><i class="bi bi-trash-fill"></i></button></td>
                                                    </tr>
                                                @empty
                                                    @for ($i = 0; $i < 2; $i++)
                                                        <tr>
                                                            <td><input type="text" name="trabajos[{{ $i }}][empresa]" class="form-control form-control-sm border-0 bg-transparent"></td>
                                                            <td><input type="text" name="trabajos[{{ $i }}][cargo]" class="form-control form-control-sm border-0 bg-transparent"></td>
                                                            <td><input type="date" name="trabajos[{{ $i }}][fechain]" class="form-control form-control-sm border-0 bg-transparent"></td>
                                                            <td><input type="date" name="trabajos[{{ $i }}][fechasal]" class="form-control form-control-sm border-0 bg-transparent"></td>
                                                            <td class="text-center"><button type="button" class="text-danger border-0 bg-transparent" onclick="eliminarFila(this)"><i class="bi bi-trash-fill"></i></button></td>
                                                        </tr>
                                                    @endfor
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5 d-flex justify-content-between">
                                <button type="button" class="btn btn-light rounded-pill px-4" onclick="previousTab('contacto-tab')">
                                    <i class="bi bi-arrow-left me-2"></i> Anterior
                                </button>
                                <button type="button" class="tbl-hero-btn tbl-hero-btn-primary px-5" onclick="nextTab('confirmacion-tab')">
                                    Siguiente <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Pestaña de Confirmación -->
                    <div class="tab-pane fade p-4 p-md-5" id="confirmacion" role="tabpanel">
                        <div class="row justify-content-center text-center">
                            <div class="col-md-8">
                                <div class="tbl-avatar bg-warning-subtle text-warning mb-4 mx-auto" style="width: 80px; height: 80px; font-size: 2rem;">
                                    <i class="bi bi-shield-lock"></i>
                                </div>
                                <h4 class="text-dark fw-bold">Verifica tu Identidad</h4>
                                <p class="text-muted mb-5">Por seguridad, ingresa tu contraseña actual para guardar todos los cambios realizados en tu perfil.</p>

                                <div class="mb-4 text-start">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Contraseña Actual</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-key-fill text-primary"></i></span>
                                        <input type="password" id="confirmpassword" name="confirmpassword" class="form-control bg-light border-start-0" placeholder="••••••••" required>
                                        <button class="btn btn-light border border-start-0" type="button" id="togglePassword">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="alert alert-info border-0 rounded-4 p-3 mb-5">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-info-circle-fill fs-4 me-3 text-info"></i>
                                        <div class="text-start">
                                            <span class="d-block fw-bold small text-uppercase">Aviso Importante</span>
                                            <span class="small opacity-75">Al guardar, tu información se actualizará en todo el sistema.</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-3">
                                    <button type="button" class="btn btn-light rounded-pill px-4 flex-fill" onclick="previousTab(authRole == 'Docente' ? 'profesional-tab' : 'contacto-tab')">
                                        Revisar Datos
                                    </button>
                                    <button type="submit" class="tbl-hero-btn tbl-hero-btn-primary px-5 flex-fill py-3 fs-5" id="btnGuardar">
                                        <i class="bi bi-save-fill me-2"></i> Guardar Cambios
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


<style>
    .form-control {
        border-radius: 10px;
        border: 1.5px solid #e9ecef;
        padding: 0.75rem 1rem;
        transition: all 0.2s;
    }
    .form-control:focus {
        border-color: #145da0;
        box-shadow: 0 0 0 0.25rem rgba(20, 93, 160, 0.1);
        background-color: #fff !important;
    }
    .input-group-text {
        border-radius: 10px 0 0 10px;
        border: 1.5px solid #e9ecef;
        border-right: none;
    }
    .input-group .form-control {
        border-radius: 0 10px 10px 0;
    }
    .input-group .btn {
        border-radius: 0 10px 10px 0;
        border: 1.5px solid #e9ecef;
        border-left: none;
    }
    .adm-tab {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    .table-modern input.form-control:focus {
        background-color: rgba(20,93,160,0.05) !important;
        border: none !important;
        box-shadow: none !important;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const authRole = "{{ auth()->user()->hasRole('Docente') ? 'Docente' : 'Estudiante' }}";

    function nextTab(tabId) {
        const tab = new bootstrap.Tab(document.getElementById(tabId));
        tab.show();
    }

    function previousTab(tabId) {
        const tab = new bootstrap.Tab(document.getElementById(tabId));
        tab.show();
    }

    function updateProgressBar() {
        const tabs = document.querySelectorAll('.adm-tab');
        const activeTab = document.querySelector('.adm-tab.active');
        const index = Array.from(tabs).indexOf(activeTab);
        const progress = ((index + 1) / tabs.length) * 100;
        document.getElementById('progressBar').style.width = progress + '%';
    }

    document.querySelectorAll('.adm-tab').forEach(tab => {
        tab.addEventListener('shown.bs.tab', updateProgressBar);
    });

    document.getElementById('togglePassword')?.addEventListener('click', function() {
        const pass = document.getElementById('confirmpassword');
        const icon = this.querySelector('i');
        if (pass.type === 'password') {
            pass.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            pass.type = 'password';
            icon.className = 'bi bi-eye';
        }
    });

    function agregarTrabajo() {
        const tbody = document.querySelector('#tablaTrabajo tbody');
        const index = tbody.rows.length;
        const row = tbody.insertRow();
        row.innerHTML = `
            <td><input type="text" name="trabajos[${index}][empresa]" class="form-control form-control-sm border-0 bg-transparent"></td>
            <td><input type="text" name="trabajos[${index}][cargo]" class="form-control form-control-sm border-0 bg-transparent"></td>
            <td><input type="date" name="trabajos[${index}][fechain]" class="form-control form-control-sm border-0 bg-transparent"></td>
            <td><input type="date" name="trabajos[${index}][fechasal]" class="form-control form-control-sm border-0 bg-transparent"></td>
            <td class="text-center"><button type="button" class="text-danger border-0 bg-transparent" onclick="eliminarFila(this)"><i class="bi bi-trash-fill"></i></button></td>
        `;
    }

    function eliminarFila(btn) {
        if (document.querySelectorAll('#tablaTrabajo tbody tr').length > 1) {
            btn.closest('tr').remove();
        } else {
            Swal.fire('Atención', 'Debe mantener al menos una fila.', 'info');
        }
    }

    // Alertas de Servidor
    @if(session('success'))
        Swal.fire({ icon: 'success', title: '¡Éxito!', text: "{{ session('success') }}", confirmButtonColor: '#145da0' });
    @endif
    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Error', text: "{{ session('error') }}", confirmButtonColor: '#145da0' });
    @endif
    @if($errors->any())
        Swal.fire({ icon: 'error', title: 'Validación', text: "{{ $errors->first() }}", confirmButtonColor: '#145da0' });
    @endif

    document.getElementById('profileForm').addEventListener('submit', function(e) {
        const pass = document.getElementById('confirmpassword').value;
        if (!pass) {
            e.preventDefault();
            Swal.fire('Contraseña requerida', 'Por favor ingresa tu contraseña para confirmar.', 'warning');
            nextTab('confirmacion-tab');
            return;
        }
    });
</script>
