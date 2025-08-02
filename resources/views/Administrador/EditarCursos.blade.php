

@extends('layout')

@section('titulo', 'Editar Curso')

@section('content')
    <ul class="nav nav-tabs mb-4" id="cursoTab" role="tablist">
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
        <!-- First Tab: Edit Course -->
        <div class="tab-pane fade show active" id="curso" role="tabpanel" aria-labelledby="curso-tab">
            <!-- ... (mantén todo tu formulario de edición de curso existente) ... -->
            <div class="container-fluid py-4">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="text-center">Editar Curso</h3>
                    </div>
                </div>
                <div class="container-fluid py-4">
                    <nav aria-label="breadcrumb" class="mb-4">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('ListadeCursos') }}" class="text-primary">Cursos</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Editar Curso</li>
                        </ol>
                    </nav>

                    <div class="card shadow">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <a href="{{ route('Curso', $cursos->id)}}" class="btn btn-sm btn-primary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                            <h6 class="m-0 fw-bold text-primary">Editar Curso: {{ $cursos->nombreCurso }}</h6>
                        </div>

                        <div class="card-body">

                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form action="{{ route('editarCursoPost', $cursos->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row mb-4">
                                    <!-- Nombre del curso -->
                                    <div class="col-md-6">
                                        <label for="nombre" class="form-label">Nombre del Curso</label>
                                        @if (auth()->user()->hasRole('Administrador'))
                                            <input type="text" class="form-control" id="nombre" name="nombre"
                                                value="{{ $cursos->nombreCurso }}" required>
                                        @else
                                            <input type="hidden" name="nombre" value="{{ $cursos->nombreCurso }}">
                                            <input type="text" class="form-control" value="{{ $cursos->nombreCurso }}" disabled>
                                        @endif
                                    </div>

                                    <!-- Descripción -->
                                    <div class="col-md-6">
                                        <label for="descripcion" class="form-label">Descripción</label>
                                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required>{{ $cursos->descripcionC }}</textarea>
                                    </div>
                                </div>

                                <!-- Fechas, Formato, Tipo -->

                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <label for="fecha_ini" class="form-label">Fecha Inicio</label>
                                            <input type="datetime-local" class="form-control" id="fecha_ini" name="fecha_ini"
                                                value="{{ old('fecha_ini', $cursos->fecha_ini ? \Carbon\Carbon::parse($cursos->fecha_ini)->format('Y-m-d\TH:i') : '') }}"
                                                required>

                                    </div>

                                    <div class="col-md-3">
                                        <label for="fecha_fin" class="form-label">Fecha Fin</label>
                                            <input type="datetime-local" class="form-control" id="fecha_fin" name="fecha_fin"
                                                value="{{ old('fecha_fin', $cursos->fecha_fin ? \Carbon\Carbon::parse($cursos->fecha_fin)->format('Y-m-d\TH:i') : '') }}"
                                                required>

                                    </div>

                                    <!-- Formato -->
                                    <div class="col-md-3">
                                        <label for="formato" class="form-label">Formato</label>
                                        @if (auth()->user()->hasRole('Administrador'))
                                            <select class="form-select" id="formato" name="formato">
                                                <option value="Presencial"
                                                    {{ $cursos->formato === 'Presencial' ? 'selected' : '' }}>
                                                    Presencial</option>
                                                <option value="Virtual" {{ $cursos->formato === 'Virtual' ? 'selected' : '' }}>
                                                    Virtual
                                                </option>
                                                <option value="Híbrido" {{ $cursos->formato === 'Híbrido' ? 'selected' : '' }}>
                                                    Híbrido
                                                </option>
                                            </select>
                                        @else
                                            <input type="hidden" name="formato" value="{{ $cursos->formato }}">
                                            <input type="text" class="form-control" value="{{ $cursos->formato }}" disabled>
                                        @endif
                                    </div>

                                    <!-- Tipo -->
                                    <div class="col-md-3">
                                        <label for="tipo" class="form-label">Tipo</label>
                                        @if (auth()->user()->hasRole('Administrador'))
                                            <select class="form-select" id="tipo" name="tipo">
                                                <option value="curso" {{ $cursos->tipo === 'curso' ? 'selected' : '' }}>
                                                    Curso
                                                </option>
                                                <option value="congreso" {{ $cursos->tipo === 'congreso' ? 'selected' : '' }}>
                                                    Evento
                                                </option>
                                            </select>
                                        @else
                                            <input type="hidden" name="tipo" value="{{ $cursos->tipo }}">
                                            <input type="text" class="form-control"
                                                value="{{ $cursos->tipo == 'congreso' ? 'Evento' : $cursos->tipo }}" disabled>
                                        @endif
                                    </div>
                                </div>

                                <!-- Docente -->
                                @if (auth()->user()->hasRole('Administrador'))
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label for="docente_id" class="form-label">Docente</label>
                                            <select class="form-select" id="docente_id" name="docente_id">
                                                @foreach ($docente as $doc)
                                                    <option value="{{ $doc->id }}"
                                                        {{ $cursos->docente_id == $doc->id ? 'selected' : '' }}>
                                                        {{ $doc->name }} {{ $doc->lastname1 }}
                                                        {{ $doc->lastname2 }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="docente_id" value="{{ auth()->user()->id }}">
                                @endif

                                <!-- Edad y Niveles -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label for="edad_id" class="form-label">Edad Dirigida</label>
                                        <input type="text" class="form-control" id="edad_id" name="edad_id"
                                            value="{{ $cursos->edad_dirigida }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="nivel_id" class="form-label">Niveles</label>
                                        <input type="text" class="form-control" id="nivel_id" name="nivel_id"
                                            value="{{ $cursos->nivel }}">
                                    </div>
                                </div>

                                <!-- Solo Admin: Duración, Visibilidad, Cupos, Precio -->
                                @if (auth()->user()->hasRole('Administrador'))
                                    <div class="row mb-4">
                                        <div class="col-md-3">
                                            <label for="duracion" class="form-label">Duración (en horas)</label>
                                            <input type="number" class="form-control" id="duracion" name="duracion"
                                                value="{{ $cursos->duracion }}" min="1" required>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="visibilidad" class="form-label">Visibilidad</label>
                                            <select class="form-select" id="visibilidad" name="visibilidad">
                                                <option value="publico"
                                                    {{ $cursos->visibilidad === 'publico' ? 'selected' : '' }}>
                                                    Público</option>
                                                <option value="privado"
                                                    {{ $cursos->visibilidad === 'privado' ? 'selected' : '' }}>
                                                    Privado</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="cupos" class="form-label">Cupos Disponibles</label>
                                            <input type="number" class="form-control" id="cupos" name="cupos"
                                                value="{{ $cursos->cupos }}" min="1" required>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="precio" class="form-label">Precio (en Bs)</label>
                                            <input type="number" class="form-control" id="precio" name="precio"
                                                value="{{ $cursos->precio }}" step="0.01" min="0" required>
                                        </div>
                                    </div>
                                @endif


                                <div class="col-md-6 mb-4">
                                    <label for="archivo" class="form-label">Archivo del Curso (PDF)</label>
                                    <input type="file" class="form-control" id="archivo" name="archivo" accept=".pdf">

                                    @if ($cursos->archivoContenidodelCurso)
                                        <div class="mt-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-pdf text-danger fs-3 me-2"></i>
                                                <div>
                                                    <small class="d-block">Archivo actual:</small>
                                                    <a href="{{ asset('storage/' . $cursos->archivoContenidodelCurso) }}"
                                                        target="_blank" class="text-primary">
                                                        {{ basename($cursos->archivoContenidodelCurso) }}
                                                    </a>
                                                    <div class="form-check mt-2">
                                                        <input class="form-check-input" type="checkbox" id="eliminar_archivo"
                                                            name="eliminar_archivo">
                                                        <label class="form-check-label text-danger" for="eliminar_archivo">
                                                            <small>Eliminar archivo actual</small>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="imagen" class="form-label">Imagen del Curso</label>
                                    <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">

                                    @if ($cursos->imagen)
                                        <div class="mt-3">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <img src="{{ asset('storage/' . $cursos->imagen) }}"
                                                        alt="Imagen actual del curso" style="max-width: 80px; max-height: 80px;"
                                                        class="img-thumbnail">
                                                </div>
                                                <div>
                                                    <small class="d-block">Imagen actual:</small>
                                                    <div class="form-check mt-2">
                                                        <input class="form-check-input" type="checkbox" id="eliminar_imagen"
                                                            name="eliminar_imagen">
                                                        <label class="form-check-label text-danger" for="eliminar_imagen">
                                                            <small>Eliminar imagen actual</small>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Guardar -->
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-success btn-lg">
                                            <i class="fas fa-save"></i> Guardar Cambios
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Second Tab: Categories -->
        <div class="tab-pane fade" id="categoria" role="tabpanel" aria-labelledby="categoria-tab">
            <div class="container-fluid py-4">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-primary">Categorías asignadas: {{ $cursos->nombreCurso }}</h6>
                        <div>
                            <input type="text" id="buscadorCategorias"
                                class="form-control form-control-sm d-inline-block w-auto me-2"
                                placeholder="Buscar categorías...">

                        </div>
                    </div>

                    <div class="card-body">
                        <form id="categoriasForm" action="{{ route('cursos.updateCategories', $cursos->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row" id="contenedorCategorias">
                                @foreach ($categorias->chunk(ceil($categorias->count() / 3)) as $chunk)
                                    <div class="col-md-4">
                                        @foreach ($chunk as $categoria)
                                            <div class="form-check mb-3 categoria-item">
                                                <input class="form-check-input" type="checkbox"
                                                    id="cat_{{ $categoria->id }}" name="categorias[]"
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

                            <button id="guardarCategorias" class="btn btn-sm btn-success">
                                <i class="fas fa-save me-1"></i> Guardar Cambios
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
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
                                text: xhr.responseJSON.message || 'Ocurrió un error al guardar',
                            });
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
