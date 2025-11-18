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



   <div class="tab-content" id="cursoTabContent">
    <div class="tab-pane fade show active" id="curso" role="tabpanel" aria-labelledby="curso-tab">

        <div class="container-fluid">

            <div class="card-modern">
                <div class="card-body">
                    <h3 class="text-center mb-5">Editar Curso</h3>

                    {{-- Alertas --}}
                    @if (session('success'))
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Éxito',
                                text: '{{ session('success') }}',
                                confirmButtonText: 'Aceptar'
                            })
                        </script>
                    @endif

                    @if ($errors->any())
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Errores de validación',
                                html: `
                                    <ul style="text-align:left;">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                `,
                                confirmButtonText: 'Entendido'
                            })
                        </script>
                    @endif

                    {{-- FORMULARIO --}}
                    <form action="{{ route('editarCursoPost', $cursos->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- ================= INFORMACIÓN PRINCIPAL ================= -->
                        <div class="row mb-4">

                            {{-- Nombre Curso --}}
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre del Curso</label>

                                @if (auth()->user()->hasRole('Administrador'))
                                    <input type="text" name="nombre" id="nombre"
                                           class="form-control-modern"
                                           value="{{ $cursos->nombreCurso }}" required>
                                @else
                                    <input type="hidden" name="nombre" value="{{ $cursos->nombreCurso }}">
                                    <input type="text" class="form-control-modern"
                                           value="{{ $cursos->nombreCurso }}" disabled>
                                @endif
                            </div>

                            {{-- Descripción --}}
                            <div class="col-md-6">
                                <label for="descripcion" class="form-label-modern">Descripción</label>
                                <textarea name="descripcion" id="descripcion"
                                          class="form-control-modern" rows="3" required>{{ $cursos->descripcionC }}</textarea>
                            </div>
                        </div>

                        <!-- ================= FECHAS, FORMATO Y TIPO ================= -->
                        <div class="row mb-4">

                            {{-- Fecha Inicio --}}
                            <div class="col-md-3">
                                <label class="form-label-modern">Fecha Inicio</label>
                                <input type="datetime-local" name="fecha_ini"
                                       class="form-control-modern"
                                       required
                                       value="{{ old('fecha_ini', $cursos->fecha_ini ? \Carbon\Carbon::parse($cursos->fecha_ini)->format('Y-m-d\TH:i') : '') }}">
                            </div>

                            {{-- Fecha Fin --}}
                            <div class="col-md-3">
                                <label class="form-label-modern">Fecha Fin</label>
                                <input type="datetime-local" name="fecha_fin"
                                       class="form-control-modern"
                                       required
                                       value="{{ old('fecha_fin', $cursos->fecha_fin ? \Carbon\Carbon::parse($cursos->fecha_fin)->format('Y-m-d\TH:i') : '') }}">
                            </div>

                            {{-- Formato --}}
                            <div class="col-md-3">
                                <label class="form-label-modern">Formato</label>

                                @if (auth()->user()->hasRole('Administrador'))
                                    <select class="form-select-modern" name="formato">
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
                            <div class="col-md-3">
                                <label class="form-label-modern">Tipo</label>

                                @if (auth()->user()->hasRole('Administrador'))
                                    <select class="form-select-modern" name="tipo">
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
                                    <label class="form-label-modern">Docente</label>
                                    <select class="form-select-modern" name="docente_id">
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
                            <div class="col-md-6">
                                <label class="form-label-modern">Edad Dirigida</label>
                                <input type="text" name="edad_id" class="form-control-modern"
                                       value="{{ $cursos->edad_dirigida }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label-modern">Niveles</label>
                                <input type="text" name="nivel_id" class="form-control-modern"
                                       value="{{ $cursos->nivel }}">
                            </div>
                        </div>

                        <!-- ================= SOLO ADMIN: DURACIÓN, CUPO, PRECIO ================= -->
                        @if (auth()->user()->hasRole('Administrador'))
                            <div class="row mb-4">

                                <div class="col-md-3">
                                    <label class="form-label-modern">Duración (horas)</label>
                                    <input type="number" name="duracion" class="form-control-modern"
                                           value="{{ $cursos->duracion }}" required min="1">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label-modern">Visibilidad</label>
                                    <select class="form-select-modern" name="visibilidad">
                                        <option value="publico" {{ $cursos->visibilidad == 'publico' ? 'selected' : '' }}>Público</option>
                                        <option value="privado" {{ $cursos->visibilidad == 'privado' ? 'selected' : '' }}>Privado</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label-modern">Cupos</label>
                                    <input type="number" name="cupos" class="form-control-modern"
                                           value="{{ $cursos->cupos }}" required min="1">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label-modern">Precio (Bs)</label>
                                    <input type="number" name="precio" class="form-control-modern"
                                           value="{{ $cursos->precio }}" step="0.01" min="0" required>
                                </div>

                            </div>
                        @endif

                        <!-- ================= ARCHIVO PDF ================= -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label-modern">Archivo del Curso (PDF)</label>
                            <input type="file" name="archivo" class="form-control-modern" accept=".pdf">

                            @if ($cursos->archivoContenidodelCurso)
                                <div class="mt-3 d-flex align-items-center">
                                    <i class="fas fa-file-pdf text-danger fs-3 me-2"></i>
                                    <div>
                                        <small>Archivo actual:</small>
                                        <a href="{{ asset('storage/' . $cursos->archivoContenidodelCurso) }}"
                                           target="_blank" class="text-primary">
                                            {{ basename($cursos->archivoContenidodelCurso) }}
                                        </a>

                                        <div class="form-check mt-2">
                                            <input type="checkbox" name="eliminar_archivo"
                                                   class="form-check-input" id="eliminar_archivo">
                                            <label for="eliminar_archivo" class="form-check-label text-danger">
                                                <small>Eliminar archivo actual</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- ================= IMAGEN ================= -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label-modern">Imagen del Curso</label>
                            <input type="file" name="imagen" class="form-control-modern" accept="image/*">

                            @if ($cursos->imagen)
                                <div class="mt-3 d-flex align-items-center">
                                    <img src="{{ asset('storage/' . $cursos->imagen) }}"
                                         class="img-thumbnail me-3"
                                         style="max-width: 80px">
                                    <div>
                                        <small>Imagen actual:</small>
                                        <div class="form-check mt-2">
                                            <input type="checkbox" name="eliminar_imagen"
                                                   class="form-check-input" id="eliminar_imagen">
                                            <label for="eliminar_imagen" class="form-check-label text-danger">
                                                <small>Eliminar imagen actual</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- ================= BOTÓN GUARDAR ================= -->
                        <div class="text-center">
                            <button type="submit" class="btn-modern btn-success-custom">
                                <i class="bi bi-save me-1"></i> Guardar Cambios
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

                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-primary m-0">Categorías asignadas: {{ $cursos->nombreCurso }}</h6>

                    <div class="search-box-table">
                        <i class="bi bi-search search-icon-table"></i>
                        <input type="text" id="buscadorCategorias"
                               class="search-input-table" placeholder="Buscar categorías...">
                        <span class="search-indicator"></span>
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
                                                   class="form-check-input"
                                                   id="cat_{{ $categoria->id }}"
                                                   name="categorias[]"
                                                   value="{{ $categoria->id }}"
                                                   {{ $cursos->categorias->contains($categoria->id) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="cat_{{ $categoria->id }}">
                                                {{ $categoria->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>

                        <button id="guardarCategorias" class="btn-modern btn-success-custom mt-3">
                            <i class="bi bi-save me-1"></i> Guardar Cambios
                        </button>

                    </form>

                </div>
            </div>
        </div>

    </div>
</div>

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
