@extends('layout')

@section('titulo', 'Editar Curso')

@section('content')

    <ul class="nav nav-tabs mb-4" id="cursoTab" role="tablist">
 <a href="{{ route('Curso', encrypt($cursos->id)) }}" class="btn-back-modern">
        <i class="bi bi-arrow-left-circle"></i><span class="ms-1">Volver al Curso</span>
    </a>
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="curso-tab" data-bs-toggle="tab" data-bs-target="#curso" type="button"
                role="tab" aria-controls="curso" aria-selected="true">Editar Curso</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="categoria-tab" data-bs-toggle="tab" data-bs-target="#categoria" type="button"
                role="tab" aria-controls="categoria" aria-selected="false">Categorías</button>
        </li>

    </ul>



  <style>
    :root {
        --color-primary: #1a4789;
        --color-secondary: #39a6cb;
        --color-accent1: #63becf;
        --color-accent2: #055c9d;
        --color-accent3: #2197bd;
        --color-success: #28a745;
        --color-warning: #ffc107;
        --color-danger: #dc3545;
        --color-info: #17a2b8;
        
        --gradient-primary: linear-gradient(135deg, #1a4789 0%, #055c9d 100%);
        --gradient-secondary: linear-gradient(135deg, #39a6cb 0%, #63becf 100%);
        
        --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
        --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.12);
        --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.15);
        
        --border-radius: 12px;
        --border-radius-sm: 8px;
    }
    
    .curso-edit-container .card-modern {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-md);
        background: white;
    }
    
    .curso-edit-container .card-body {
        padding: 2rem;
    }
    
    .curso-edit-container .form-label-modern {
        font-weight: 600;
        color: var(--color-primary);
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .curso-edit-container .form-control-modern,
    .curso-edit-container .form-select-modern {
        border: 2px solid #e9ecef;
        border-radius: var(--border-radius-sm);
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
        background: white;
    }
    
    .curso-edit-container .form-control-modern:focus,
    .curso-edit-container .form-select-modern:focus {
        border-color: var(--color-accent1);
        box-shadow: 0 0 0 0.2rem rgba(57, 166, 203, 0.25);
        outline: none;
    }
    
    .curso-edit-container .form-control-modern:disabled {
        background-color: #f8f9fa;
        opacity: 0.7;
    }
    
    .curso-edit-container .btn-modern {
        border: none;
        border-radius: var(--border-radius-sm);
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .curso-edit-container .btn-success-custom {
        background: var(--color-success);
        color: white;
    }
    
    .curso-edit-container .btn-success-custom:hover {
        background: #218838;
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }
    
    .curso-edit-container .card-header {
        background: var(--gradient-primary);
        color: white;
        border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
        padding: 1rem 1.5rem;
    }
    
    .curso-edit-container .search-box-table {
        position: relative;
        width: 300px;
    }
    
    .curso-edit-container .search-input-table {
        width: 100%;
        padding: 0.5rem 1rem 0.5rem 2.5rem;
        border: 2px solid #e9ecef;
        border-radius: var(--border-radius-sm);
        transition: all 0.3s ease;
    }
    
    .curso-edit-container .search-input-table:focus {
        border-color: var(--color-accent1);
        box-shadow: 0 0 0 0.2rem rgba(57, 166, 203, 0.25);
        outline: none;
    }
    
    .curso-edit-container .search-icon-table {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--color-muted);
    }
    
    .curso-edit-container .form-check-input:checked {
        background-color: var(--color-primary);
        border-color: var(--color-primary);
    }
    
    .curso-edit-container .categoria-item {
        padding: 0.5rem;
        border-radius: var(--border-radius-sm);
        transition: background-color 0.2s ease;
    }
    
    .curso-edit-container .categoria-item:hover {
        background-color: rgba(57, 166, 203, 0.05);
    }
    
    .curso-edit-container .img-thumbnail {
        border-radius: var(--border-radius-sm);
        border: 2px solid #e9ecef;
    }
    
    .curso-edit-container .nav-tabs .nav-link {
        border: none;
        padding: 1rem 2rem;
        font-weight: 600;
        color: var(--color-muted);
        transition: all 0.3s ease;
    }
    
    .curso-edit-container .nav-tabs .nav-link.active {
        color: var(--color-primary);
        border-bottom: 3px solid var(--color-primary);
        background: transparent;
    }
    
    .curso-edit-container .nav-tabs .nav-link:hover {
        color: var(--color-primary);
        border-bottom: 3px solid var(--color-accent1);
    }
    
    .curso-edit-container .file-preview {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: var(--border-radius-sm);
        margin-top: 0.5rem;
    }
    
    .curso-edit-container .file-preview img {
        border-radius: var(--border-radius-sm);
    }
    
    .curso-edit-container .form-check-label {
        cursor: pointer;
    }
    
    .curso-edit-container .text-danger {
        color: var(--color-danger) !important;
    }
    
    .curso-edit-container .text-primary {
        color: var(--color-primary) !important;
    }
    
    @media (max-width: 768px) {
        .curso-edit-container .card-body {
            padding: 1rem;
        }
        
        .curso-edit-container .search-box-table {
            width: 100%;
            margin-top: 1rem;
        }
        
        .curso-edit-container .nav-tabs .nav-link {
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
        }
    }
</style>

<div class="tab-content curso-edit-container" id="cursoTabContent">
    <div class="tab-pane fade show active" id="curso" role="tabpanel" aria-labelledby="curso-tab">
        <div class="container-fluid">
            <div class="card-modern">
                <div class="card-body">
                    <h3 class="text-center mb-5" style="color: var(--color-primary);">Editar Curso</h3>

                    {{-- Alertas --}}
                    @if (session('success'))
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Éxito',
                                text: '{{ session('success') }}',
                                confirmButtonText: 'Aceptar',
                                confirmButtonColor: '#1a4789'
                            })
                        </script>
                    @endif

                    @if ($errors->any())
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Errores de validación',
                                html: `
                                    <ul style="text-align:left; color: #dc3545;">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                `,
                                confirmButtonText: 'Entendido',
                                confirmButtonColor: '#1a4789'
                            })
                        </script>
                    @endif

                    {{-- FORMULARIO --}}
                    <form action="{{ route('editarCursoPost', $cursos->id) }}" method="POST" enctype="multipart/form-data" id="cursoForm">
                        @csrf

                        <!-- ================= INFORMACIÓN PRINCIPAL ================= -->
                        <div class="row mb-4">
                            {{-- Nombre Curso --}}
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label-modern">
                                    <i class="fas fa-book me-2"></i>Nombre del Curso
                                </label>
                                @if (auth()->user()->hasRole('Administrador'))
                                    <input type="text" name="nombre" id="nombre"
                                           class="form-control-modern"
                                           value="{{ old('nombre', $cursos->nombreCurso) }}" 
                                           required
                                           placeholder="Ingrese el nombre del curso">
                                @else
                                    <input type="hidden" name="nombre" value="{{ $cursos->nombreCurso }}">
                                    <input type="text" class="form-control-modern"
                                           value="{{ $cursos->nombreCurso }}" disabled>
                                @endif
                            </div>

                            {{-- Descripción --}}
                            <div class="col-md-6 mb-3">
                                <label for="descripcion" class="form-label-modern">
                                    <i class="fas fa-align-left me-2"></i>Descripción
                                </label>
                                <textarea name="descripcion" id="descripcion"
                                          class="form-control-modern" 
                                          rows="3" 
                                          required
                                          placeholder="Describa el contenido del curso">{{ old('descripcion', $cursos->descripcionC) }}</textarea>
                                <small class="text-muted">Caracteres: <span id="contadorDescripcion">0</span>/500</small>
                            </div>
                        </div>

                        <!-- ================= FECHAS, FORMATO Y TIPO ================= -->
                        <div class="row mb-4">
                            {{-- Fecha Inicio --}}
                            <div class="col-md-3 mb-3">
                                <label class="form-label-modern">
                                    <i class="fas fa-calendar-plus me-2"></i>Fecha Inicio
                                </label>
                                <input type="datetime-local" name="fecha_ini"
                                       class="form-control-modern"
                                       required
                                       value="{{ old('fecha_ini', $cursos->fecha_ini ? \Carbon\Carbon::parse($cursos->fecha_ini)->format('Y-m-d\TH:i') : '') }}">
                            </div>

                            {{-- Fecha Fin --}}
                            <div class="col-md-3 mb-3">
                                <label class="form-label-modern">
                                    <i class="fas fa-calendar-minus me-2"></i>Fecha Fin
                                </label>
                                <input type="datetime-local" name="fecha_fin"
                                       class="form-control-modern"
                                       required
                                       value="{{ old('fecha_fin', $cursos->fecha_fin ? \Carbon\Carbon::parse($cursos->fecha_fin)->format('Y-m-d\TH:i') : '') }}">
                            </div>

                            {{-- Formato --}}
                            <div class="col-md-3 mb-3">
                                <label class="form-label-modern">
                                    <i class="fas fa-laptop-house me-2"></i>Formato
                                </label>
                                @if (auth()->user()->hasRole('Administrador'))
                                    <select class="form-select-modern" name="formato" id="formato">
                                        <option value="Presencial" {{ $cursos->formato == 'Presencial' ? 'selected' : '' }}>Presencial</option>
                                        <option value="Virtual" {{ $cursos->formato == 'Virtual' ? 'selected' : '' }}>Virtual</option>
                                        <option value="Híbrido" {{ $cursos->formato == 'Híbrido' ? 'selected' : '' }}>Híbrido</option>
                                    </select>
                                @else
                                    <input type="hidden" name="formato" value="{{ $cursos->formato }}">
                                    <input type="text" class="form-control-modern"
                                           value="{{ $cursos->formato }}" disabled>
                                @endif
                            </div>

                            {{-- Tipo --}}
                            <div class="col-md-3 mb-3">
                                <label class="form-label-modern">
                                    <i class="fas fa-graduation-cap me-2"></i>Tipo
                                </label>
                                @if (auth()->user()->hasRole('Administrador'))
                                    <select class="form-select-modern" name="tipo" id="tipo">
                                        <option value="curso" {{ $cursos->tipo === 'curso' ? 'selected' : '' }}>Curso</option>
                                        <option value="congreso" {{ $cursos->tipo === 'congreso' ? 'selected' : '' }}>Evento</option>
                                    </select>
                                @else
                                    <input type="hidden" name="tipo" value="{{ $cursos->tipo }}">
                                    <input type="text" class="form-control-modern"
                                           value="{{ $cursos->tipo == 'congreso' ? 'Evento' : 'Curso' }}" disabled>
                                @endif
                            </div>
                        </div>

                        <!-- ================= DOCENTE ================= -->
                        @if (auth()->user()->hasRole('Administrador'))
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label-modern">
                                        <i class="fas fa-chalkboard-teacher me-2"></i>Docente
                                    </label>
                                    <select class="form-select-modern" name="docente_id" id="docente_id">
                                        @foreach ($docente as $doc)
                                            <option value="{{ $doc->id }}"
                                                {{ $cursos->docente_id == $doc->id ? 'selected' : '' }}>
                                                {{ $doc->name }} {{ $doc->lastname1 }} {{ $doc->lastname2 }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @else
                            <input type="hidden" name="docente_id" value="{{ auth()->user()->id }}">
                        @endif

                        <!-- ================= EDAD Y NIVEL ================= -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label-modern">
                                    <i class="fas fa-users me-2"></i>Edad Dirigida
                                </label>
                                <input type="text" name="edad_id" class="form-control-modern"
                                       value="{{ old('edad_id', $cursos->edad_dirigida) }}"
                                       placeholder="Ej: 18-25 años">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label-modern">
                                    <i class="fas fa-layer-group me-2"></i>Niveles
                                </label>
                                <input type="text" name="nivel_id" class="form-control-modern"
                                       value="{{ old('nivel_id', $cursos->nivel) }}"
                                       placeholder="Ej: Básico, Intermedio, Avanzado">
                            </div>
                        </div>

                        <!-- ================= SOLO ADMIN: DURACIÓN, CUPO, PRECIO ================= -->
                        @if (auth()->user()->hasRole('Administrador'))
                            <div class="row mb-4">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label-modern">
                                        <i class="fas fa-clock me-2"></i>Duración (horas)
                                    </label>
                                    <input type="number" name="duracion" class="form-control-modern"
                                           value="{{ old('duracion', $cursos->duracion) }}" 
                                           required min="1"
                                           placeholder="Ej: 40">
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label-modern">
                                        <i class="fas fa-eye me-2"></i>Visibilidad
                                    </label>
                                    <select class="form-select-modern" name="visibilidad" id="visibilidad">
                                        <option value="publico" {{ $cursos->visibilidad == 'publico' ? 'selected' : '' }}>Público</option>
                                        <option value="privado" {{ $cursos->visibilidad == 'privado' ? 'selected' : '' }}>Privado</option>
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label-modern">
                                        <i class="fas fa-user-friends me-2"></i>Cupos
                                    </label>
                                    <input type="number" name="cupos" class="form-control-modern"
                                           value="{{ old('cupos', $cursos->cupos) }}" 
                                           required min="1"
                                           placeholder="Ej: 30">
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label-modern">
                                        <i class="fas fa-dollar-sign me-2"></i>Precio (Bs)
                                    </label>
                                    <input type="number" name="precio" class="form-control-modern"
                                           value="{{ old('precio', $cursos->precio) }}" 
                                           step="0.01" min="0" required
                                           placeholder="Ej: 150.00">
                                </div>
                            </div>
                        @endif

                        <!-- ================= ARCHIVO PDF ================= -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label-modern">
                                    <i class="fas fa-file-pdf me-2"></i>Archivo del Curso (PDF)
                                </label>
                                <input type="file" name="archivo" class="form-control-modern" 
                                       accept=".pdf" 
                                       id="archivoInput">

                                @if ($cursos->archivoContenidodelCurso)
                                    <div class="file-preview mt-3">
                                        <i class="fas fa-file-pdf text-danger fs-3 me-3"></i>
                                        <div>
                                            <small class="text-muted d-block">Archivo actual:</small>
                                            <a href="{{ asset('storage/' . $cursos->archivoContenidodelCurso) }}"
                                               target="_blank" class="text-primary fw-bold">
                                                {{ basename($cursos->archivoContenidodelCurso) }}
                                            </a>
                                            <div class="form-check mt-2">
                                                <input type="checkbox" name="eliminar_archivo"
                                                       class="form-check-input" id="eliminar_archivo">
                                                <label for="eliminar_archivo" class="form-check-label text-danger">
                                                    <i class="fas fa-trash me-1"></i>Eliminar archivo actual
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- ================= IMAGEN ================= -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label-modern">
                                    <i class="fas fa-image me-2"></i>Imagen del Curso
                                </label>
                                <input type="file" name="imagen" class="form-control-modern" 
                                       accept="image/*" 
                                       id="imagenInput">

                                @if ($cursos->imagen)
                                    <div class="file-preview mt-3">
                                        <img src="{{ asset('storage/' . $cursos->imagen) }}"
                                             class="img-thumbnail me-3"
                                             style="max-width: 80px; max-height: 80px; object-fit: cover;">
                                        <div>
                                            <small class="text-muted d-block">Imagen actual:</small>
                                            <div class="form-check mt-2">
                                                <input type="checkbox" name="eliminar_imagen"
                                                       class="form-check-input" id="eliminar_imagen">
                                                <label for="eliminar_imagen" class="form-check-label text-danger">
                                                    <i class="fas fa-trash me-1"></i>Eliminar imagen actual
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- ================= BOTÓN GUARDAR ================= -->
                        <div class="text-center mt-4">
                            <button type="submit" class="btn-modern btn-success-custom">
                                <i class="fas fa-save me-2"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= TAB 2: CATEGORÍAS ================= -->
    <div class="tab-pane fade" id="categoria" role="tabpanel" aria-labelledby="categoria-tab">
        <div class="container-fluid py-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <h6 class="fw-bold m-0" style="color: var(--color-primary);">
                        <i class="fas fa-tags me-2"></i>Categorías asignadas: {{ $cursos->nombreCurso }}
                    </h6>

                    <div class="search-box-table mt-2 mt-md-0">
                        <i class="fas fa-search search-icon-table"></i>
                        <input type="text" id="buscadorCategorias"
                               class="search-input-table" placeholder="Buscar categorías...">
                    </div>
                </div>

                <div class="card-body">
                    <form id="categoriasForm"
                          action="{{ route('cursos.updateCategories', $cursos->id) }}"
                          method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row" id="contenedorCategorias">
                            @foreach ($categorias->chunk(ceil($categorias->count() / 3)) as $chunk)
                                <div class="col-md-4">
                                    @foreach ($chunk as $categoria)
                                        <div class="form-check mb-3 categoria-item">
                                            <input type="checkbox"
                                                   class="form-check-input categoria-checkbox"
                                                   id="cat_{{ $categoria->id }}"
                                                   name="categorias[]"
                                                   value="{{ $categoria->id }}"
                                                   {{ $cursos->categorias->contains($categoria->id) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="cat_{{ $categoria->id }}">
                                                <i class="fas fa-folder me-2"></i>{{ $categoria->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn-modern btn-success-custom">
                                <i class="fas fa-save me-2"></i> Guardar Cambios de Categorías
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Contador de caracteres para descripción
        const descripcion = document.getElementById('descripcion');
        const contador = document.getElementById('contadorDescripcion');
        
        if (descripcion && contador) {
            contador.textContent = descripcion.value.length;
            
            descripcion.addEventListener('input', function() {
                contador.textContent = this.value.length;
                
                if (this.value.length > 500) {
                    contador.style.color = 'var(--color-danger)';
                } else {
                    contador.style.color = 'var(--color-muted)';
                }
            });
        }
        
        // Búsqueda de categorías
        const buscadorCategorias = document.getElementById('buscadorCategorias');
        const categoriasItems = document.querySelectorAll('.categoria-item');
        
        if (buscadorCategorias) {
            buscadorCategorias.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                
                categoriasItems.forEach(item => {
                    const label = item.querySelector('label').textContent.toLowerCase();
                    if (label.includes(searchTerm)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }
        
        // Validación de fechas
        const fechaInicio = document.querySelector('input[name="fecha_ini"]');
        const fechaFin = document.querySelector('input[name="fecha_fin"]');
        
        if (fechaInicio && fechaFin) {
            fechaInicio.addEventListener('change', function() {
                if (fechaFin.value && new Date(this.value) > new Date(fechaFin.value)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Fecha inválida',
                        text: 'La fecha de inicio no puede ser posterior a la fecha de fin',
                        confirmButtonColor: 'var(--color-primary)'
                    });
                    this.value = '';
                }
            });
            
            fechaFin.addEventListener('change', function() {
                if (fechaInicio.value && new Date(this.value) < new Date(fechaInicio.value)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Fecha inválida',
                        text: 'La fecha de fin no puede ser anterior a la fecha de inicio',
                        confirmButtonColor: 'var(--color-primary)'
                    });
                    this.value = '';
                }
            });
        }
        
        // Validación de archivos
        const archivoInput = document.getElementById('archivoInput');
        if (archivoInput) {
            archivoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file && file.type !== 'application/pdf') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Archivo no válido',
                        text: 'Solo se permiten archivos PDF',
                        confirmButtonColor: 'var(--color-primary)'
                    });
                    this.value = '';
                }
            });
        }
        
        const imagenInput = document.getElementById('imagenInput');
        if (imagenInput) {
            imagenInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file && !file.type.startsWith('image/')) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Archivo no válido',
                        text: 'Solo se permiten archivos de imagen',
                        confirmButtonColor: 'var(--color-primary)'
                    });
                    this.value = '';
                }
            });
        }
        
        // Contador de categorías seleccionadas
        const categoriaCheckboxes = document.querySelectorAll('.categoria-checkbox');
        const categoriasForm = document.getElementById('categoriasForm');
        
        if (categoriasForm) {
            categoriasForm.addEventListener('submit', function(e) {
                const checked = document.querySelectorAll('.categoria-checkbox:checked');
                if (checked.length === 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Sin categorías',
                        text: 'Debe seleccionar al menos una categoría',
                        confirmButtonColor: 'var(--color-primary)'
                    });
                }
            });
        }
    });
</script>

@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Buscador de categorías
        $('#buscadorCategorias').on('keyup', function() {
            const searchText = $(this).val().toLowerCase();

            $('.categoria-item').each(function() {
                const text = $(this).text().toLowerCase();
                $(this).toggle(text.includes(searchText));
            });
        });

        // Envío del formulario
        $('#guardarCategorias').click(function(e) {
            e.preventDefault();

            const form = $('#categoriasForm');
            const checkedCount = form.find('input[type="checkbox"]:checked').length;

            Swal.fire({
                title: '¿Confirmar cambios?',
                text: `Vas a asignar ${checkedCount} categoría(s) al curso`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar loader
                    Swal.fire({
                        title: 'Guardando...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Enviar formulario
                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Guardado!',
                                text: 'Categorías actualizadas correctamente',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON.message ||
                                    'Ocurrió un error al guardar',
                            });
                        }
                    });
                }
            });
        });
    });
</script>
