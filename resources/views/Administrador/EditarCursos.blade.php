@extends('layout')

@section('titulo', 'Editar Curso')

@section('content')

<style>
    :root {
        --color-primary: #1a4789;
        --color-secondary: #39a6cb;
        --color-accent1: #63becf;
        --color-accent2: #055c9d;
        --color-success: #28a745;
        --color-danger: #dc3545;
        --gradient-primary: linear-gradient(135deg, #1a4789 0%, #055c9d 100%);
        --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.06);
        --shadow-md: 0 4px 8px rgba(0, 0, 0, 0.1);
        --border-radius: 10px;
        --border-radius-sm: 6px;
    }

    .edit-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 1.5rem;
    }

    .btn-back-modern {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        background: white;
        border: 2px solid var(--color-primary);
        color: var(--color-primary);
        border-radius: var(--border-radius-sm);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }

    .btn-back-modern:hover {
        background: var(--color-primary);
        color: white;
        transform: translateX(-3px);
    }

    .nav-tabs {
        border-bottom: 2px solid #e9ecef;
        margin-bottom: 2rem;
    }

    .nav-tabs .nav-link {
        border: none;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        color: #6c757d;
        transition: all 0.3s ease;
        position: relative;
    }

    .nav-tabs .nav-link.active {
        color: var(--color-primary);
        background: transparent;
    }

    .nav-tabs .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--color-primary);
        border-radius: 3px 3px 0 0;
    }

    .nav-tabs .nav-link:hover:not(.active) {
        color: var(--color-primary);
    }

    .card-modern {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-md);
        border: none;
        overflow: hidden;
    }

    .card-header-modern {
        background: var(--gradient-primary);
        color: white;
        padding: 1.25rem 1.5rem;
        border: none;
    }

    .card-header-modern h5 {
        margin: 0;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .card-body-compact {
        padding: 1.5rem;
    }

    .section-title {
        color: var(--color-primary);
        font-weight: 700;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e9ecef;
        display: flex;
        align-items: center;
    }

    .section-title i {
        margin-right: 0.5rem;
        color: var(--color-accent1);
    }

    .form-label-compact {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.4rem;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
    }

    .form-label-compact i {
        margin-right: 0.4rem;
        color: var(--color-accent1);
        font-size: 0.85rem;
    }

    .form-control-compact,
    .form-select-compact {
        border: 1px solid #dee2e6;
        border-radius: var(--border-radius-sm);
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .form-control-compact:focus,
    .form-select-compact:focus {
        border-color: var(--color-accent1);
        box-shadow: 0 0 0 0.15rem rgba(57, 166, 203, 0.2);
        outline: none;
    }

    .form-control-compact:disabled {
        background-color: #f8f9fa;
        opacity: 0.7;
    }

    .input-group-compact {
        margin-bottom: 1rem;
    }

    .row-compact {
        margin-left: -0.5rem;
        margin-right: -0.5rem;
    }

    .row-compact > [class*="col-"] {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }

    .section-divider {
        margin: 1.5rem 0;
        border-top: 1px dashed #dee2e6;
    }

    .file-preview-compact {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        background: #f8f9fa;
        border-radius: var(--border-radius-sm);
        margin-top: 0.5rem;
        border: 1px solid #e9ecef;
    }

    .file-preview-compact img {
        border-radius: 4px;
        border: 2px solid #dee2e6;
    }

    .btn-compact {
        padding: 0.5rem 1.5rem;
        font-size: 0.9rem;
        font-weight: 600;
        border-radius: var(--border-radius-sm);
        transition: all 0.3s ease;
    }

    .btn-save {
        background: var(--color-success);
        color: white;
        border: none;
        padding: 0.65rem 2rem;
        font-size: 1rem;
    }

    .btn-save:hover {
        background: #218838;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    }

    .search-box-compact {
        position: relative;
        width: 100%;
        max-width: 300px;
    }

    .search-input-compact {
        width: 100%;
        padding: 0.5rem 0.75rem 0.5rem 2.25rem;
        border: 1px solid #dee2e6;
        border-radius: var(--border-radius-sm);
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .search-input-compact:focus {
        border-color: var(--color-accent1);
        box-shadow: 0 0 0 0.15rem rgba(57, 166, 203, 0.2);
        outline: none;
    }

    .search-icon-compact {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        font-size: 0.85rem;
    }

    .categoria-item-compact {
        padding: 0.5rem 0.75rem;
        border-radius: var(--border-radius-sm);
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }

    .categoria-item-compact:hover {
        background-color: rgba(57, 166, 203, 0.05);
        border-color: rgba(57, 166, 203, 0.2);
    }

    .form-check-input:checked {
        background-color: var(--color-primary);
        border-color: var(--color-primary);
    }

    .character-counter {
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }

    .character-counter.warning {
        color: var(--color-danger);
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .edit-container {
            padding: 1rem;
        }

        .card-body-compact {
            padding: 1rem;
        }

        .section-title {
            font-size: 0.85rem;
        }

        .search-box-compact {
            max-width: 100%;
            margin-top: 1rem;
        }
    }
</style>

<div class="edit-container">
    <a href="{{ route('Curso', $cursos->codigoCurso) }}" class="btn-back-modern">
        <i class="bi bi-arrow-left-circle"></i>
        <span class="ms-1">Volver al Curso</span>
    </a>

    <ul class="nav nav-tabs" id="cursoTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="curso-tab" data-bs-toggle="tab" data-bs-target="#curso" type="button"
                role="tab" aria-controls="curso" aria-selected="true">
                <i class="fas fa-edit me-2"></i>Editar Curso
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="categoria-tab" data-bs-toggle="tab" data-bs-target="#categoria" type="button"
                role="tab" aria-controls="categoria" aria-selected="false">
                <i class="fas fa-tags me-2"></i>Categorías
            </button>
        </li>
    </ul>

    {{-- Alertas --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#1a4789',
                timer: 3000
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

    <div class="tab-content" id="cursoTabContent">
        <!-- TAB 1: EDITAR CURSO -->
        <div class="tab-pane fade show active" id="curso" role="tabpanel" aria-labelledby="curso-tab">
            <div class="card-modern">
                <div class="card-body-compact">
                    <form action="{{ route('editarCursoPost', $cursos->id) }}" method="POST" enctype="multipart/form-data" id="cursoForm">
                        @csrf

                        <!-- INFORMACIÓN BÁSICA -->
                        <div class="section-title">
                            <i class="fas fa-info-circle"></i>
                            Información Básica
                        </div>

                        <div class="row row-compact">
                            <div class="col-md-6">
                                <div class="input-group-compact">
                                    <label for="nombre" class="form-label-compact">
                                        <i class="fas fa-book"></i>Nombre del Curso
                                    </label>
                                    @if (auth()->user()->hasRole('Administrador'))
                                        <input type="text" name="nombre" id="nombre" class="form-control-compact"
                                               value="{{ old('nombre', $cursos->nombreCurso) }}" required
                                               placeholder="Ingrese el nombre del curso">
                                    @else
                                        <input type="hidden" name="nombre" value="{{ $cursos->nombreCurso }}">
                                        <input type="text" class="form-control-compact"
                                               value="{{ $cursos->nombreCurso }}" disabled>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group-compact">
                                    <label for="descripcion" class="form-label-compact">
                                        <i class="fas fa-align-left"></i>Descripción
                                    </label>
                                    <textarea name="descripcion" id="descripcion" class="form-control-compact"
                                              rows="2" required maxlength="500"
                                              placeholder="Describa el contenido del curso">{{ old('descripcion', $cursos->descripcionC) }}</textarea>
                                    <small class="character-counter">
                                        <span id="contadorDescripcion">0</span>/500 caracteres
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="section-divider"></div>

                        <!-- FECHAS Y CONFIGURACIÓN -->
                        <div class="section-title">
                            <i class="fas fa-cog"></i>
                            Configuración del Curso
                        </div>

                        <div class="row row-compact">
                            <div class="col-md-3 col-sm-6">
                                <div class="input-group-compact">
                                    <label class="form-label-compact">
                                        <i class="fas fa-calendar-plus"></i>Fecha Inicio
                                    </label>
                                    <input type="datetime-local" name="fecha_ini" class="form-control-compact" required
                                           value="{{ old('fecha_ini', $cursos->fecha_ini ? \Carbon\Carbon::parse($cursos->fecha_ini)->format('Y-m-d\TH:i') : '') }}">
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6">
                                <div class="input-group-compact">
                                    <label class="form-label-compact">
                                        <i class="fas fa-calendar-minus"></i>Fecha Fin
                                    </label>
                                    <input type="datetime-local" name="fecha_fin" class="form-control-compact" required
                                           value="{{ old('fecha_fin', $cursos->fecha_fin ? \Carbon\Carbon::parse($cursos->fecha_fin)->format('Y-m-d\TH:i') : '') }}">
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6">
                                <div class="input-group-compact">
                                    <label class="form-label-compact">
                                        <i class="fas fa-laptop-house"></i>Formato
                                    </label>
                                    @if (auth()->user()->hasRole('Administrador'))
                                        <select class="form-select-compact" name="formato" id="formato">
                                            <option value="Presencial" {{ $cursos->formato == 'Presencial' ? 'selected' : '' }}>Presencial</option>
                                            <option value="Virtual" {{ $cursos->formato == 'Virtual' ? 'selected' : '' }}>Virtual</option>
                                            <option value="Híbrido" {{ $cursos->formato == 'Híbrido' ? 'selected' : '' }}>Híbrido</option>
                                        </select>
                                    @else
                                        <input type="hidden" name="formato" value="{{ $cursos->formato }}">
                                        <input type="text" class="form-control-compact" value="{{ $cursos->formato }}" disabled>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6">
                                <div class="input-group-compact">
                                    <label class="form-label-compact">
                                        <i class="fas fa-graduation-cap"></i>Tipo
                                    </label>
                                    @if (auth()->user()->hasRole('Administrador'))
                                        <select class="form-select-compact" name="tipo" id="tipo">
                                            <option value="curso" {{ $cursos->tipo === 'curso' ? 'selected' : '' }}>Curso</option>
                                            <option value="congreso" {{ $cursos->tipo === 'congreso' ? 'selected' : '' }}>Evento</option>
                                        </select>
                                    @else
                                        <input type="hidden" name="tipo" value="{{ $cursos->tipo }}">
                                        <input type="text" class="form-control-compact"
                                               value="{{ $cursos->tipo == 'congreso' ? 'Evento' : 'Curso' }}" disabled>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if (auth()->user()->hasRole('Administrador'))
                            <div class="row row-compact mt-2">
                                <div class="col-md-3 col-sm-6">
                                    <div class="input-group-compact">
                                        <label class="form-label-compact">
                                            <i class="fas fa-chalkboard-teacher"></i>Docente
                                        </label>
                                        <select class="form-select-compact" name="docente_id" id="docente_id">
                                            @foreach ($docente as $doc)
                                                <option value="{{ $doc->id }}" {{ $cursos->docente_id == $doc->id ? 'selected' : '' }}>
                                                    {{ $doc->name }} {{ $doc->lastname1 }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6">
                                    <div class="input-group-compact">
                                        <label class="form-label-compact">
                                            <i class="fas fa-clock"></i>Duración (horas)
                                        </label>
                                        <input type="number" name="duracion" class="form-control-compact"
                                               value="{{ old('duracion', $cursos->duracion) }}" required min="1">
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6">
                                    <div class="input-group-compact">
                                        <label class="form-label-compact">
                                            <i class="fas fa-user-friends"></i>Cupos
                                        </label>
                                        <input type="number" name="cupos" class="form-control-compact"
                                               value="{{ old('cupos', $cursos->cupos) }}" required min="1">
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6">
                                    <div class="input-group-compact">
                                        <label class="form-label-compact">
                                            <i class="fas fa-dollar-sign"></i>Precio (Bs)
                                        </label>
                                        <input type="number" name="precio" class="form-control-compact"
                                               value="{{ old('precio', $cursos->precio) }}" step="0.01" min="0" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row row-compact mt-2">
                                <div class="col-md-4">
                                    <div class="input-group-compact">
                                        <label class="form-label-compact">
                                            <i class="fas fa-eye"></i>Visibilidad
                                        </label>
                                        <select class="form-select-compact" name="visibilidad" id="visibilidad">
                                            <option value="publico" {{ $cursos->visibilidad == 'publico' ? 'selected' : '' }}>Público</option>
                                            <option value="privado" {{ $cursos->visibilidad == 'privado' ? 'selected' : '' }}>Privado</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @else
                            <input type="hidden" name="docente_id" value="{{ auth()->user()->id }}">
                        @endif

                        <div class="section-divider"></div>

                        <!-- PÚBLICO OBJETIVO -->
                        <div class="section-title">
                            <i class="fas fa-users"></i>
                            Público Objetivo
                        </div>

                        <div class="row row-compact">
                            <div class="col-md-6">
                                <div class="input-group-compact">
                                    <label class="form-label-compact">
                                        <i class="fas fa-child"></i>Edad Dirigida
                                    </label>
                                    <input type="text" name="edad_id" class="form-control-compact"
                                           value="{{ old('edad_id', $cursos->edad_dirigida) }}"
                                           placeholder="Ej: 18-25 años">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group-compact">
                                    <label class="form-label-compact">
                                        <i class="fas fa-layer-group"></i>Niveles
                                    </label>
                                    <input type="text" name="nivel_id" class="form-control-compact"
                                           value="{{ old('nivel_id', $cursos->nivel) }}"
                                           placeholder="Ej: Básico, Intermedio">
                                </div>
                            </div>
                        </div>

                        <div class="section-divider"></div>

                        <!-- ARCHIVOS -->
                        <div class="section-title">
                            <i class="fas fa-folder-open"></i>
                            Archivos y Recursos
                        </div>

                        <div class="row row-compact">
                            <div class="col-md-6">
                                <div class="input-group-compact">
                                    <label class="form-label-compact">
                                        <i class="fas fa-file-pdf"></i>Archivo del Curso (PDF)
                                    </label>
                                    <input type="file" name="archivo" class="form-control-compact"
                                           accept=".pdf" id="archivoInput">

                                    @if ($cursos->archivoContenidodelCurso)
                                        <div class="file-preview-compact mt-2">
                                            <i class="fas fa-file-pdf text-danger fs-5 me-2"></i>
                                            <div class="flex-grow-1">
                                                <small class="text-muted d-block">Archivo actual:</small>
                                                <a href="{{ asset('storage/' . $cursos->archivoContenidodelCurso) }}"
                                                   target="_blank" class="text-primary fw-bold small">
                                                    {{ basename($cursos->archivoContenidodelCurso) }}
                                                </a>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" name="eliminar_archivo" class="form-check-input" id="eliminar_archivo">
                                                <label for="eliminar_archivo" class="form-check-label text-danger small">
                                                    <i class="fas fa-trash"></i> Eliminar
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group-compact">
                                    <label class="form-label-compact">
                                        <i class="fas fa-image"></i>Imagen del Curso
                                    </label>
                                    <input type="file" name="imagen" class="form-control-compact"
                                           accept="image/*" id="imagenInput">

                                    @if ($cursos->imagen)
                                        <div class="file-preview-compact mt-2">
                                            <img src="{{ asset('storage/' . $cursos->imagen) }}"
                                                 class="me-2" style="width: 60px; height: 60px; object-fit: cover;">
                                            <div class="flex-grow-1">
                                                <small class="text-muted d-block">Imagen actual</small>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" name="eliminar_imagen" class="form-check-input" id="eliminar_imagen">
                                                <label for="eliminar_imagen" class="form-check-label text-danger small">
                                                    <i class="fas fa-trash"></i> Eliminar
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- BOTÓN GUARDAR -->
                        <div class="text-center mt-4 pt-3">
                            <button type="button" class="btn btn-save" id="btnGuardarCurso">
                                <i class="fas fa-save me-2"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- TAB 2: CATEGORÍAS -->
        <div class="tab-pane fade" id="categoria" role="tabpanel" aria-labelledby="categoria-tab">
            <div class="card-modern">
                <div class="card-header-modern d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <h5 class="mb-2 mb-md-0">
                        <i class="fas fa-tags me-2"></i>Categorías: {{ $cursos->nombreCurso }}
                    </h5>
                    <div class="search-box-compact">
                        <i class="fas fa-search search-icon-compact"></i>
                        <input type="text" id="buscadorCategorias" class="search-input-compact"
                               placeholder="Buscar categorías...">
                    </div>
                </div>

                <div class="card-body-compact">
                    <form id="categoriasForm" action="{{ route('cursos.updateCategories', $cursos->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row row-compact" id="contenedorCategorias">
                            @foreach ($categorias->chunk(ceil($categorias->count() / 3)) as $chunk)
                                <div class="col-md-4">
                                    @foreach ($chunk as $categoria)
                                        <div class="categoria-item-compact">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input categoria-checkbox"
                                                       id="cat_{{ $categoria->id }}" name="categorias[]"
                                                       value="{{ $categoria->id }}"
                                                       {{ $cursos->categorias->contains($categoria->id) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="cat_{{ $categoria->id }}">
                                                    <i class="fas fa-folder me-1"></i>{{ $categoria->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>

                        <div class="text-center mt-4 pt-3">
                            <button type="button" class="btn btn-save" id="btnGuardarCategorias">
                                <i class="fas fa-save me-2"></i> Guardar Categorías
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ========== CONTADOR DE CARACTERES ==========
        const descripcion = document.getElementById('descripcion');
        const contador = document.getElementById('contadorDescripcion');

        if (descripcion && contador) {
            const actualizarContador = () => {
                const length = descripcion.value.length;
                contador.textContent = length;
                contador.parentElement.classList.toggle('warning', length > 450);
            };

            actualizarContador();
            descripcion.addEventListener('input', actualizarContador);
        }

        // ========== BÚSQUEDA DE CATEGORÍAS ==========
        const buscadorCategorias = document.getElementById('buscadorCategorias');
        if (buscadorCategorias) {
            buscadorCategorias.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                document.querySelectorAll('.categoria-item-compact').forEach(item => {
                    const text = item.textContent.toLowerCase();
                    item.style.display = text.includes(searchTerm) ? 'block' : 'none';
                });
            });
        }

        // ========== VALIDACIÓN DE FECHAS ==========
        const fechaInicio = document.querySelector('input[name="fecha_ini"]');
        const fechaFin = document.querySelector('input[name="fecha_fin"]');

        if (fechaInicio && fechaFin) {
            fechaInicio.addEventListener('change', function() {
                if (fechaFin.value && new Date(this.value) > new Date(fechaFin.value)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Fecha inválida',
                        text: 'La fecha de inicio no puede ser posterior a la fecha de fin',
                        confirmButtonColor: '#1a4789',
                        confirmButtonText: 'Entendido'
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
                        confirmButtonColor: '#1a4789',
                        confirmButtonText: 'Entendido'
                    });
                    this.value = '';
                }
            });
        }

        // ========== VALIDACIÓN DE ARCHIVOS PDF ==========
        const archivoInput = document.getElementById('archivoInput');
        if (archivoInput) {
            archivoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    if (file.type !== 'application/pdf') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Archivo no válido',
                            text: 'Solo se permiten archivos PDF',
                            confirmButtonColor: '#1a4789',
                            confirmButtonText: 'Entendido'
                        });
                        this.value = '';
                        return;
                    }

                    // Validar tamaño (máximo 10MB)
                    const maxSize = 10 * 1024 * 1024; // 10MB en bytes
                    if (file.size > maxSize) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Archivo muy grande',
                            text: 'El archivo no debe superar los 10MB',
                            confirmButtonColor: '#1a4789',
                            confirmButtonText: 'Entendido'
                        });
                        this.value = '';
                    }
                }
            });
        }

        // ========== VALIDACIÓN DE ARCHIVOS DE IMAGEN ==========
        const imagenInput = document.getElementById('imagenInput');
        if (imagenInput) {
            imagenInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    if (!file.type.startsWith('image/')) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Archivo no válido',
                            text: 'Solo se permiten archivos de imagen (JPG, PNG, GIF, etc.)',
                            confirmButtonColor: '#1a4789',
                            confirmButtonText: 'Entendido'
                        });
                        this.value = '';
                        return;
                    }

                    // Validar tamaño (máximo 5MB)
                    const maxSize = 5 * 1024 * 1024; // 5MB en bytes
                    if (file.size > maxSize) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Imagen muy grande',
                            text: 'La imagen no debe superar los 5MB',
                            confirmButtonColor: '#1a4789',
                            confirmButtonText: 'Entendido'
                        });
                        this.value = '';
                    }
                }
            });
        }

        // ========== CONFIRMACIÓN AL GUARDAR CURSO ==========
        const btnGuardarCurso = document.getElementById('btnGuardarCurso');
        const cursoForm = document.getElementById('cursoForm');

        if (btnGuardarCurso && cursoForm) {
            btnGuardarCurso.addEventListener('click', function(e) {
                e.preventDefault();

                // Validar campos obligatorios
                if (!cursoForm.checkValidity()) {
                    cursoForm.reportValidity();
                    return;
                }

                Swal.fire({
                    title: '¿Guardar cambios?',
                    text: '¿Está seguro de que desea modificar la información del curso?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-check me-2"></i>Sí, guardar',
                    cancelButtonText: '<i class="fas fa-times me-2"></i>Cancelar',
                    reverseButtons: true,
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Mostrar loading
                        Swal.fire({
                            title: 'Guardando cambios...',
                            html: 'Por favor espere un momento',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Enviar formulario
                        cursoForm.submit();
                    }
                });
            });
        }

        // ========== CONFIRMACIÓN AL GUARDAR CATEGORÍAS ==========
        const btnGuardarCategorias = document.getElementById('btnGuardarCategorias');
        const categoriasForm = document.getElementById('categoriasForm');

        if (btnGuardarCategorias && categoriasForm) {
            btnGuardarCategorias.addEventListener('click', function(e) {
                e.preventDefault();

                // Contar categorías seleccionadas
                const categoriasSeleccionadas = categoriasForm.querySelectorAll('input[type="checkbox"]:checked');
                const totalSeleccionadas = categoriasSeleccionadas.length;

                if (totalSeleccionadas === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Sin categorías',
                        text: 'Debe seleccionar al menos una categoría para el curso',
                        confirmButtonColor: '#1a4789',
                        confirmButtonText: 'Entendido'
                    });
                    return;
                }

                Swal.fire({
                    title: '¿Guardar categorías?',
                    html: `
                        <p>¿Está seguro de asignar <strong>${totalSeleccionadas}</strong> categoría(s) a este curso?</p>
                        <small class="text-muted">Esto reemplazará las categorías actuales</small>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-check me-2"></i>Sí, guardar',
                    cancelButtonText: '<i class="fas fa-times me-2"></i>Cancelar',
                    reverseButtons: true,
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Mostrar loading
                        Swal.fire({
                            title: 'Guardando categorías...',
                            html: 'Por favor espere un momento',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Si estás usando AJAX (opcional)
                        // Descomenta esto si prefieres envío AJAX en lugar de submit normal
                        /*
                        const formData = new FormData(categoriasForm);

                        fetch(categoriasForm.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Guardado!',
                                text: 'Las categorías se actualizaron correctamente',
                                confirmButtonColor: '#1a4789',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Ocurrió un error al guardar las categorías',
                                confirmButtonColor: '#1a4789'
                            });
                        });
                        */

                        // Envío normal del formulario
                        categoriasForm.submit();
                    }
                });
            });
        }

        // ========== RESALTAR CATEGORÍAS AL BUSCAR ==========
        if (buscadorCategorias) {
            buscadorCategorias.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                const items = document.querySelectorAll('.categoria-item-compact');

                items.forEach(item => {
                    const label = item.querySelector('label');
                    const text = label.textContent.toLowerCase();

                    if (searchTerm === '') {
                        item.style.display = 'block';
                        label.innerHTML = label.textContent; // Remover highlights
                    } else if (text.includes(searchTerm)) {
                        item.style.display = 'block';

                        // Resaltar texto coincidente
                        const regex = new RegExp(`(${searchTerm})`, 'gi');
                        const icon = '<i class="fas fa-folder me-1"></i>';
                        const originalText = label.textContent.replace('📁 ', '');
                        const highlighted = originalText.replace(regex, '<mark>$1</mark>');
                        label.innerHTML = icon + highlighted;
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }

        // ========== CONTADOR DE CATEGORÍAS SELECCIONADAS ==========
        const categoriaCheckboxes = document.querySelectorAll('.categoria-checkbox');
        if (categoriaCheckboxes.length > 0) {
            const actualizarContadorCategorias = () => {
                const seleccionadas = document.querySelectorAll('.categoria-checkbox:checked').length;
                const total = categoriaCheckboxes.length;

                // Puedes agregar un elemento para mostrar esto si lo deseas
                console.log(`Categorías seleccionadas: ${seleccionadas} de ${total}`);
            };

            categoriaCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', actualizarContadorCategorias);
            });

            actualizarContadorCategorias();
        }

        // ========== ANIMACIÓN AL CAMBIAR DE TAB ==========
        const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
        tabButtons.forEach(button => {
            button.addEventListener('shown.bs.tab', function(e) {
                const targetPane = document.querySelector(this.getAttribute('data-bs-target'));
                if (targetPane) {
                    targetPane.style.opacity = '0';
                    setTimeout(() => {
                        targetPane.style.transition = 'opacity 0.3s ease';
                        targetPane.style.opacity = '1';
                    }, 10);
                }
            });
        });

        // ========== PREVENIR ENVÍO DOBLE DEL FORMULARIO ==========
        let formSubmitting = false;

        if (cursoForm) {
            cursoForm.addEventListener('submit', function(e) {
                if (formSubmitting) {
                    e.preventDefault();
                    return false;
                }
                formSubmitting = true;

                // Deshabilitar botón de envío
                if (btnGuardarCurso) {
                    btnGuardarCurso.disabled = true;
                    btnGuardarCurso.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
                }
            });
        }

        if (categoriasForm) {
            categoriasForm.addEventListener('submit', function(e) {
                if (formSubmitting) {
                    e.preventDefault();
                    return false;
                }
                formSubmitting = true;

                // Deshabilitar botón de envío
                if (btnGuardarCategorias) {
                    btnGuardarCategorias.disabled = true;
                    btnGuardarCategorias.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
                }
            });
        }

        // ========== VALIDACIÓN DE NÚMEROS ==========
        const numerosInputs = document.querySelectorAll('input[type="number"]');
        numerosInputs.forEach(input => {
            input.addEventListener('input', function() {
                if (this.value < 0) {
                    this.value = 0;
                }
            });
        });

        // ========== CONFIRMACIÓN AL ELIMINAR ARCHIVOS ==========
        const eliminarArchivo = document.getElementById('eliminar_archivo');
        if (eliminarArchivo) {
            eliminarArchivo.addEventListener('change', function() {
                if (this.checked) {
                    Swal.fire({
                        icon: 'warning',
                        title: '¿Eliminar archivo?',
                        text: 'El archivo PDF actual será eliminado permanentemente',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (!result.isConfirmed) {
                            this.checked = false;
                        }
                    });
                }
            });
        }

        const eliminarImagen = document.getElementById('eliminar_imagen');
        if (eliminarImagen) {
            eliminarImagen.addEventListener('change', function() {
                if (this.checked) {
                    Swal.fire({
                        icon: 'warning',
                        title: '¿Eliminar imagen?',
                        text: 'La imagen actual será eliminada permanentemente',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (!result.isConfirmed) {
                            this.checked = false;
                        }
                    });
                }
            });
        }
    });
</script>
