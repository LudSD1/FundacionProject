@section('titulo')
    Asignar Cursos
@endsection




@section('content')
<div class="container py-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0"><i class="fas fa-user-graduate me-2"></i>Asignar Cursos a Estudiantes</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('inscribir') }}" method="POST" id="formulario-inscripcion">
                @csrf
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="form-group">
                            <label for="curso_id" class="form-label fw-bold">Seleccionar Curso</label>
                            <select class="form-control @error('curso_id') is-invalid @enderror" id="curso_id" name="curso_id" required>
                                <option value="">Seleccione un curso</option>
                                @foreach($cursos as $curso)
                                    <option value="{{ $curso->id }}" {{ old('curso_id') == $curso->id ? 'selected' : '' }}>
                                        {{ $curso->nombreCurso }} - {{ $curso->fecha_ini }} a {{ $curso->fecha_fin }}
                                        @if($curso->cupos > 0)
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
                            <label class="form-label fw-bold">Seleccionar Estudiante(s)</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" id="buscador" class="form-control" placeholder="Buscar estudiante por nombre o email...">
                            </div>

                            <div class="card mt-3">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <span>Lista de Estudiantes</span>
                                    <button type="button" id="seleccionar-todos" class="btn btn-sm btn-outline-primary">Seleccionar Todos</button>
                                </div>
                                <div class="card-body" style="max-height: 350px; overflow-y: auto;">
                                    <div id="lista-estudiantes" class="list-group">
                                        <!-- Los estudiantes se cargarán aquí dinámicamente -->
                                        <div class="text-center text-muted py-5">
                                            <i class="fa fa-info-circle"></i>
                                            <p>Seleccione un curso para ver los estudiantes disponibles</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-light">
                                    <span id="contador-seleccionados" class="text-muted">0 estudiantes seleccionados</span>
                                </div>
                            </div>
                            @error('estudiante_id')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary" id="btn-inscribir">
                        <i class="fas fa-save me-1"></i> Inscribir Estudiantes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        // Mostrar alertas de Laravel con SweetAlert
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#28a745'
            });
        @endif

        @if($errors->any())
            let errorMessages = '';
            @foreach($errors->all() as $error)
                errorMessages += '{{ $error }}\n';
            @endforeach

            Swal.fire({
                icon: 'error',
                title: 'Error en la inscripción',
                text: errorMessages,
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#dc3545'
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
                    confirmButtonColor: '#ffc107'
                });
                return;
            }

            if (!$('#curso_id').val()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: 'Debe seleccionar un curso.',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#ffc107'
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
                confirmButtonColor: '#007bff',
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
                $('#lista-estudiantes').html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Cargando estudiantes...</p></div>');

                $.ajax({
                    url: "{{ url('getEstudiantesNoInscritos') }}/" + curso_id,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#lista-estudiantes').empty();

                        if (data.length === 0) {
                            $('#lista-estudiantes').html('<div class="text-center text-muted py-5"><i class="fas fa-exclamation-circle mb-2 display-6"></i><p>No hay estudiantes disponibles para este curso</p></div>');
                            return;
                        }

                        $.each(data, function(key, value) {
                            const isSelected = @json(old('estudiante_id', [])).includes(value.id);
                            $('#lista-estudiantes').append(
                                '<div class="list-group-item list-group-item-action">' +
                                '<div class="form-check">' +
                                '<input class="form-check-input estudiante-checkbox" type="checkbox" name="estudiante_id[]" value="' + value.id + '" id="estudiante_' + value.id + '"' + (isSelected ? ' checked' : '') + '>' +
                                '<label class="form-check-label w-100" for="estudiante_' + value.id + '">' +
                                '<div class="d-flex justify-content-between align-items-center">' +
                                '<div><strong>' + value.name + ' ' + value.lastname1 + ' ' + value.lastname2 + '</strong></div>' +
                                '<small class="text-muted">' + value.email + '</small>' +
                                '</div>' +
                                '</label>' +
                                '</div>' +
                                '</div>'
                            );
                        });

                        actualizarContador();
                    },
                    error: function(xhr, status, error) {
                        $('#lista-estudiantes').html('<div class="alert alert-danger">Error al cargar los estudiantes. Intente nuevamente.</div>');

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudieron cargar los estudiantes. Intente nuevamente.',
                            confirmButtonText: 'Aceptar',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                });
            } else {
                $('#lista-estudiantes').html('<div class="text-center text-muted py-5"><i class="fas fa-info-circle mb-2 display-6"></i><p>Seleccione un curso para ver los estudiantes disponibles</p></div>');
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

        // Seleccionar todos los estudiantes
        $('#seleccionar-todos').click(function() {
            var todosSeleccionados = $('.estudiante-checkbox:visible:not(:checked)').length === 0;

            if (todosSeleccionados) {
                // Deseleccionar todos
                $('.estudiante-checkbox:visible').prop('checked', false);
                $(this).text('Seleccionar Todos');
            } else {
                // Seleccionar todos
                $('.estudiante-checkbox:visible').prop('checked', true);
                $(this).text('Deseleccionar Todos');
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
            $('#contador-seleccionados').text(count + ' estudiante' + (count !== 1 ? 's' : '') + ' seleccionado' + (count !== 1 ? 's' : ''));
        }

        // Función para actualizar el botón "Seleccionar Todos"
        function actualizarBotonSeleccionar() {
            var todosSeleccionados = $('.estudiante-checkbox:visible:not(:checked)').length === 0;
            $('#seleccionar-todos').text(todosSeleccionados ? 'Deseleccionar Todos' : 'Seleccionar Todos');
        }

        // Cargar curso seleccionado si hay old input
        @if(old('curso_id'))
            $('#curso_id').trigger('change');
        @endif
    });
</script>
@endsection






@extends('layout')
