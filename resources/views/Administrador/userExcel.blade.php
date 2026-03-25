@extends('layout')

@section('titulo')
    Importar Usuarios
@endsection

@section('content')
<div class="container my-4">
    <div class="tbl-card shadow-lg">
   
        <div class="tbl-card-hero">
            <div class="tbl-hero-left">
                <a href="{{ url()->previous() }}"
                   class="tbl-hero-btn tbl-hero-btn-glass prt-back-btn mb-2">
                    <i class="bi bi-arrow-left-circle-fill"></i> Volver atrás
                </a>
                <div class="tbl-hero-eyebrow">
                    <i class="bi bi-file-earmark-excel-fill"></i> Gestión de Participantes
                </div>
                <h2 class="tbl-hero-title">Importar Usuarios masivamente</h2>
                <p class="tbl-hero-sub text-white-50">
                    Carga múltiples participantes a un congreso mediante un archivo Excel.
                </p>
            </div>
            <div class="tbl-hero-controls text-end d-none d-md-block">
                <div class="ec-role-badge mb-2">
                    <i class="bi bi-shield-fill me-1"></i> {{ auth()->user()->getRoleNames()->first() }}
                </div>
                <div class="text-white small">
                    <i class="bi bi-clock-history me-1"></i> Proceso de Importación
                </div>
            </div>
        </div>

        <div class="p-4 p-md-5">
            <div class="row g-4">
                {{-- Instrucciones del Formato --}}
                <div class="col-lg-5">
                    <div class="st-card h-100 p-4 border-0 bg-light rounded-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary-subtle text-primary p-2 rounded-3 me-3">
                                <i class="bi bi-info-square-fill fs-4"></i>
                            </div>
                            <h5 class="mb-0 fw-bold">Formato del Excel</h5>
                        </div>
                        <p class="text-muted small mb-4">El archivo debe contener exactamente estas columnas en la primera fila:</p>

                        <div class="list-group list-group-flush bg-transparent">
                            <div class="list-group-item bg-transparent border-0 px-0 py-2 d-flex align-items-start">
                                <i class="bi bi-check-circle-fill text-success me-3 mt-1"></i>
                                <div><strong class="d-block">nombres</strong> <span class="text-muted small">Nombres del participante</span></div>
                            </div>
                            <div class="list-group-item bg-transparent border-0 px-0 py-2 d-flex align-items-start">
                                <i class="bi bi-check-circle-fill text-success me-3 mt-1"></i>
                                <div><strong class="d-block">apellidos</strong> <span class="text-muted small">Apellidos del participante</span></div>
                            </div>
                            <div class="list-group-item bg-transparent border-0 px-0 py-2 d-flex align-items-start">
                                <i class="bi bi-check-circle-fill text-success me-3 mt-1"></i>
                                <div><strong class="d-block">correo_electronico</strong> <span class="text-muted small">Email (debe ser único)</span></div>
                            </div>
                            <div class="list-group-item bg-transparent border-0 px-0 py-2 d-flex align-items-start">
                                <i class="bi bi-check-circle-fill text-success me-3 mt-1"></i>
                                <div><strong class="d-block">telefono</strong> <span class="text-muted small">Número con código de país</span></div>
                            </div>
                            <div class="list-group-item bg-transparent border-0 px-0 py-2 d-flex align-items-start">
                                <i class="bi bi-check-circle-fill text-success me-3 mt-1"></i>
                                <div><strong class="d-block">pais_de_residencia</strong> <span class="text-muted small">País actual</span></div>
                            </div>
                        </div>

                        <div class="alert alert-warning border-0 rounded-4 mt-4 py-3">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <span class="small fw-bold">Asegúrate de que el archivo sea .xlsx, .xls o .csv</span>
                        </div>
                    </div>
                </div>

                {{-- Formulario de Carga --}}
                <div class="col-lg-7">
                    @if(session('success'))
                        <div class="alert alert-success border-0 rounded-4 p-4 shadow-sm mb-4">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-check-circle-fill fs-3 me-3"></i>
                                <h5 class="mb-0 fw-bold">¡Importación Exitosa!</h5>
                            </div>
                            {{ session('success') }}

                            @if(session('results'))
                                <div class="mt-3 grid gap-2 d-flex flex-wrap">
                                    <span class="badge bg-white text-dark border p-2">Total: {{ session('results')['total'] }}</span>
                                    <span class="badge bg-white text-primary border p-2">Nuevos: {{ session('results')['created'] }}</span>
                                    <span class="badge bg-white text-secondary border p-2">Existentes: {{ session('results')['existing'] }}</span>
                                    <span class="badge bg-white text-success border p-2">Inscritos: {{ session('results')['registered'] }}</span>
                                </div>

                                @if(count(session('results')['errors']) > 0)
                                    <div class="mt-4 p-3 bg-danger-subtle rounded-3">
                                        <p class="text-danger fw-bold mb-2"><i class="bi bi-x-circle-fill me-2"></i>Errores encontrados:</p>
                                        <ul class="text-danger small mb-0">
                                            @foreach(session('results')['errors'] as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger border-0 rounded-4 p-4 shadow-sm mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-x-octagon-fill fs-4 me-3"></i>
                                <h6 class="mb-0 fw-bold">Error en los datos:</h6>
                            </div>
                            <ul class="mb-0 small">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('import.users') }}" method="POST" enctype="multipart/form-data" id="importForm">
                        @csrf

                        <div class="mb-4">
                            <label for="congreso_id" class="form-label fw-bold text-muted small text-uppercase">Seleccionar Congreso / Evento</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-calendar-event text-primary"></i></span>
                                <select name="congreso_id" id="congreso_id" class="form-select bg-light" required>
                                    <option value="">Seleccione un congreso...</option>
                                    @foreach($congresos as $congreso)
                                        <option value="{{ $congreso->id }}">{{ $congreso->nombreCurso }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label for="excel_file" class="form-label fw-bold text-muted small text-uppercase">Subir Archivo Excel</label>
                            <div class="ec-file-drop-area p-5 text-center border-dashed rounded-4 bg-light position-relative">
                                <i class="bi bi-cloud-arrow-up-fill fs-1 text-primary mb-3 d-block"></i>
                                <input type="file" name="excel_file" id="excel_file" class="form-control position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" accept=".xlsx,.xls,.csv" required onchange="updateFileName(this)">
                                <div id="fileNameDisplay">
                                    <h6 class="fw-bold mb-1">Haz clic para seleccionar el archivo</h6>
                                    <p class="text-muted small mb-0">Formatos: .xlsx, .xls o .csv</p>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="tbl-hero-btn tbl-hero-btn-primary py-3 rounded-pill shadow-sm">
                                <i class="bi bi-upload me-2"></i> Iniciar Importación Masiva
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .ec-role-badge {
        background: rgba(255,165,0,0.15); color: #ffa500;
        padding: 0.25rem 0.75rem; border-radius: 50px; font-size: 0.7rem; font-weight: 800;
        border: 1px solid rgba(255,165,0,0.3);
    }
    .form-control, .form-select {
        border-radius: 12px; border: 1.5px solid #e2eaf4; padding: 0.75rem 1rem;
        transition: all 0.2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #145da0; box-shadow: 0 0 0 4px rgba(20, 93, 160, 0.1);
        background: #fff !important;
    }
    .input-group-text {
        border-radius: 12px 0 0 12px; border: 1.5px solid #e2eaf4; border-right: none;
    }
    .input-group .form-control, .input-group .form-select { border-radius: 0 12px 12px 0; }

    .border-dashed { border: 2px dashed #cbd5e1 !important; }
    .cursor-pointer { cursor: pointer; }

    .ec-file-drop-area { transition: all 0.3s; }
    .ec-file-drop-area:hover { background: #f1f5f9 !important; border-color: #145da0 !important; }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function updateFileName(input) {
        const display = document.getElementById('fileNameDisplay');
        if (input.files && input.files[0]) {
            display.innerHTML = `
                <h6 class="fw-bold mb-1 text-primary text-truncate">${input.files[0].name}</h6>
                <p class="text-success small mb-0"><i class="bi bi-check-circle-fill me-1"></i> Archivo listo para cargar</p>
            `;
        }
    }

    document.getElementById('importForm').addEventListener('submit', function(e) {
        Swal.fire({
            title: 'Procesando...',
            text: 'Por favor espera mientras se importan los usuarios.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    });
</script>
@endsection
