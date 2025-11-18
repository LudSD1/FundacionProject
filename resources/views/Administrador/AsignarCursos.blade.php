@section('titulo')
    Asignar Cursos
@endsection




@section('content')
    <div class="container py-5">
        <div class="card-modern">
            <div class="card-header-modern">
                <h2><i class="fas fa-user-graduate me-2"></i>Asignar Cursos a Estudiantes</h2>
            </div>

            <div class="card-body">
                <a href="{{ route('import.users.form') }}" class="btn-modern btn-create mb-3">
                    <i class="fas fa-file-excel"></i>
                    Importar Usuarios desde Excel
                </a>

                <form action="{{ route('inscribir') }}" method="POST" id="formulario-inscripcion">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <div class="form-group-modern">
                                <label for="curso_id" class="form-label-modern">
                                    <i class="fas fa-book me-2"></i>
                                    Seleccionar Curso
                                </label>
                                <select class="form-select-modern @error('curso_id') is-invalid @enderror" id="curso_id"
                                    name="curso_id" required>
                                    <option value="">Seleccione un curso</option>
                                    @foreach ($cursos as $curso)
                                        <option value="{{ $curso->id }}"
                                            {{ old('curso_id') == $curso->id ? 'selected' : '' }}>
                                            {{ $curso->nombreCurso }} - {{ $curso->fecha_ini }} a {{ $curso->fecha_fin }}
                                            @if ($curso->cupos > 0)
                                                ({{ $curso->cupos - $curso->inscritos_count }} cupos disponibles)
                                            @else
                                                (Cupos ilimitados)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('curso_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label-modern">
                                    <i class="fas fa-users me-2"></i>
                                    Seleccionar Estudiante(s)
                                </label>
                                <div class="search-box-modern mb-3">
                                    <i class="fas fa-search search-icon"></i>
                                    <input type="text" id="buscador" class="search-input-modern"
                                        placeholder="Buscar estudiante por nombre o email...">
                                    <button type="button" class="btn-clear-search" aria-label="Limpiar">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>

                                <div class="info-card-modern mt-3">
                                    <div class="info-card-header">
                                        <span><i class="fas fa-list me-2"></i>Lista de Estudiantes</span>
                                        <button type="button" id="seleccionar-todos"
                                            class="btn-modern btn-read-all btn-sm">
                                            <i class="fas fa-check-double me-1"></i>
                                            Seleccionar Todos
                                        </button>
                                    </div>
                                    <div class="info-card-body">
                                        <div id="lista-estudiantes" class="list-group">
                                            <div class="empty-state">
                                                <i class="fas fa-info-circle"></i>
                                                <p>Seleccione un curso para ver los estudiantes disponibles</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <i class="fas fa-users me-2"></i>
                                        <span id="contador-seleccionados">0 estudiantes seleccionados</span>
                                    </div>
                                </div>
                                @error('estudiante_id')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-end">
                        <button type="submit" class="btn-modern btn-submit" id="btn-inscribir">
                            <i class="fas fa-save me-2"></i>
                            Inscribir Estudiantes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Colores personalizados para SweetAlert
            const swalColors = {
                primary: '#1a4789',
                success: '#28a745',
                error: '#dc3545',
                warning: '#ffc107',
                info: '#2197bd'
            };

            // Mostrar alertas de Laravel con SweetAlert
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: swalColors.success
                });
            @endif

            @if ($errors->any())
                let errorMessages = '';
                @foreach ($errors->all() as $error)
                    errorMessages += '{{ $error }}\n';
                @endforeach

                Swal.fire({
                    icon: 'error',
                    title: 'Error en la inscripción',
                    text: errorMessages,
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: swalColors.error
                });
            @endif

            // Confirmación antes de enviar el formulario
            $('#formulario-inscripcion').on('submit', function(e) {
                e.preventDefault();

                const estudiantesSeleccionados = $('.estudiante-checkbox:checked').length;
                const cursoSeleccionado = $('#curso_id option:selected').text();

                if (estudiantesSeleccionados === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atención',
                        text: 'Debe seleccionar al menos un estudiante para inscribir.',
                        confirmButtonText: 'Aceptar',
                        confirmButtonColor: swalColors.warning
                    });
                    return;
                }

                if (!$('#curso_id').val()) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atención',
                        text: 'Debe seleccionar un curso.',
                        confirmButtonText: 'Aceptar',
                        confirmButtonColor: swalColors.warning
                    });
                    return;
                }

                Swal.fire({
                    title: '¿Confirmar inscripción?',
                    html: `
                        <div class="text-left">
                            <p><strong>Curso:</strong> ${cursoSeleccionado}</p>
                            <p><strong>Estudiantes a inscribir:</strong> ${estudiantesSeleccionados}</p>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: swalColors.primary,
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, inscribir',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Mostrar loading mientras procesa
                        Swal.fire({
                            title: 'Procesando inscripción...',
                            text: 'Por favor espere',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Enviar formulario
                        this.submit();
                    }
                });
            });

            // Cargar estudiantes cuando se selecciona un curso
            $('#curso_id').change(function() {
                var curso_id = $(this).val();

                if (curso_id) {
                    // Mostrar spinner mientras carga
                    $('#lista-estudiantes').html(`
                        <div class="text-center py-5">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-2 text-muted">Cargando estudiantes...</p>
                        </div>
                    `);

                    $.ajax({
                        url: "{{ url('getEstudiantesNoInscritos') }}/" + curso_id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#lista-estudiantes').empty();

                            if (data.length === 0) {
                                $('#lista-estudiantes').html(`
                                    <div class="empty-state">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <p>No hay estudiantes disponibles para este curso</p>
                                    </div>
                                `);
                                return;
                            }

                            $.each(data, function(key, value) {
                                const isSelected = @json(old('estudiante_id', []))
                                    .includes(value.id);
                                $('#lista-estudiantes').append(`
                                    <div class="list-group-item">
                                        <div class="form-check">
                                            <input class="form-check-input estudiante-checkbox"
                                                   type="checkbox"
                                                   name="estudiante_id[]"
                                                   value="${value.id}"
                                                   id="estudiante_${value.id}"
                                                   ${isSelected ? 'checked' : ''}>
                                            <label class="form-check-label" for="estudiante_${value.id}">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <strong>${value.name} ${value.lastname1} ${value.lastname2}</strong>
                                                    <small class="text-muted">${value.email}</small>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                `);
                            });

                            actualizarContador();
                        },
                        error: function(xhr, status, error) {
                            $('#lista-estudiantes').html(`
                                <div class="alert alert-danger m-3">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Error al cargar los estudiantes. Intente nuevamente.
                                </div>
                            `);

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'No se pudieron cargar los estudiantes. Intente nuevamente.',
                                confirmButtonText: 'Aceptar',
                                confirmButtonColor: swalColors.error
                            });
                        }
                    });
                } else {
                    $('#lista-estudiantes').html(`
                        <div class="empty-state">
                            <i class="fas fa-info-circle"></i>
                            <p>Seleccione un curso para ver los estudiantes disponibles</p>
                        </div>
                    `);
                    actualizarContador();
                }
            });

            // Buscar estudiantes
            $('#buscador').on('input', function() {
                var searchTerm = $(this).val().toLowerCase();

                if (searchTerm === '') {
                    $('#lista-estudiantes .list-group-item').show();
                    return;
                }

                $('#lista-estudiantes .list-group-item').each(function() {
                    var text = $(this).text().toLowerCase();
                    $(this).toggle(text.includes(searchTerm));
                });

                actualizarBotonSeleccionar();
            });

            // Botón limpiar búsqueda
            $('.btn-clear-search').click(function() {
                $('#buscador').val('').trigger('input');
            });

            // Seleccionar todos los estudiantes
            $('#seleccionar-todos').click(function() {
                var todosSeleccionados = $('.estudiante-checkbox:visible:not(:checked)').length === 0;

                if (todosSeleccionados) {
                    // Deseleccionar todos
                    $('.estudiante-checkbox:visible').prop('checked', false);
                    $(this).html('<i class="fas fa-check-double me-1"></i> Seleccionar Todos');
                } else {
                    // Seleccionar todos
                    $('.estudiante-checkbox:visible').prop('checked', true);
                    $(this).html('<i class="fas fa-times me-1"></i> Deseleccionar Todos');
                }

                actualizarContador();
            });

            // Actualizar contador cuando se marcan checkboxes
            $(document).on('change', '.estudiante-checkbox', function() {
                actualizarContador();
                actualizarBotonSeleccionar();
            });

            // Función para actualizar el contador de estudiantes seleccionados
            function actualizarContador() {
                var count = $('.estudiante-checkbox:checked').length;
                $('#contador-seleccionados').text(count + ' estudiante' + (count !== 1 ? 's' : '') +
                    ' seleccionado' + (count !== 1 ? 's' : ''));
            }

            // Función para actualizar el botón "Seleccionar Todos"
            function actualizarBotonSeleccionar() {
                var todosSeleccionados = $('.estudiante-checkbox:visible:not(:checked)').length === 0;
                var iconHtml = todosSeleccionados ?
                    '<i class="fas fa-times me-1"></i>' :
                    '<i class="fas fa-check-double me-1"></i>';
                var textoBtn = todosSeleccionados ? 'Deseleccionar Todos' : 'Seleccionar Todos';
                $('#seleccionar-todos').html(iconHtml + textoBtn);
            }

            // Cargar curso seleccionado si hay old input
            @if (old('curso_id'))
                $('#curso_id').trigger('change');
            @endif
        });
    </script>
@endsection






@extends('layout')
