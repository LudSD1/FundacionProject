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
            <form action="{{ route('inscribir') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="form-group">
                            <label for="curso_id" class=" form-label fw-bold">Seleccionar Curso</label>
                            <select class="form-control" id="curso_id" name="curso_id" required>
                                <option value="">Seleccione un curso</option>
                                @foreach($cursos as $curso)
                                    <option value="{{ $curso->id }}">{{ $curso->nombreCurso }} - {{ $curso->fecha_ini }} a {{ $curso->fecha_fin }}</option>
                                @endforeach
                            </select>
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
                                            <i class="fa fa-info-circle "></i>
                                            <p>Seleccione un curso para ver los estudiantes disponibles</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-light">
                                    <span id="contador-seleccionados" class="text-muted">0 estudiantes seleccionados</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Inscribir Estudiantes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
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
                            $('#lista-estudiantes').append(
                                '<div class="list-group-item list-group-item-action">' +
                                '<div class="form-check">' +
                                '<input class="form-check-input estudiante-checkbox" type="checkbox" name="estudiante_id[]" value="' + value.id + '" id="estudiante_' + value.id + '">' +
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
                    error: function() {
                        $('#lista-estudiantes').html('<div class="alert alert-danger">Error al cargar los estudiantes. Intente nuevamente.</div>');
                    }
                });
            } else {
                $('#lista-estudiantes').html('<div class="text-center text-muted py-5"><i class="fas fa-info-circle mb-2 display-6"></i><p>Seleccione un curso para ver los estudiantes disponibles</p></div>');
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
        });

        // Función para actualizar el contador de estudiantes seleccionados
        function actualizarContador() {
            var count = $('.estudiante-checkbox:checked').length;
            $('#contador-seleccionados').text(count + ' estudiante' + (count !== 1 ? 's' : '') + ' seleccionado' + (count !== 1 ? 's' : ''));
        }
    });
</script>
@endsection






@extends('layout')
