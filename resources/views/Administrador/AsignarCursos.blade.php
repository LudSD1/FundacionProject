@extends('layout')

@section('titulo', 'Asignar Cursos')

@section('content')
<div class="container-fluid py-5">
    {{-- Estructura tbl-card moderna --}}
    <div class="tbl-card">
        {{-- Cabecera con lenguaje visual moderno --}}
        <div class="tbl-card-hero">
            <div class="tbl-hero-left">
                <div class="tbl-hero-eyebrow">
                    <i class="fas fa-user-plus"></i> Inscripciones Manuales
                </div>
                <h2 class="tbl-hero-title">Asignar Cursos</h2>
                <p class="tbl-hero-sub">Inscriba múltiples estudiantes a un curso de forma rápida y masiva</p>
            </div>
            <div class="tbl-hero-controls">
                <a href="{{ route('import.users.form') }}" class="tbl-hero-btn tbl-hero-btn-glass">
                    <i class="fas fa-file-excel"></i> Importar desde Excel
                </a>
            </div>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('inscribir') }}" method="POST" id="formulario-inscripcion">
                @csrf
                <div class="row g-4">
                    {{-- Selección de Curso --}}
                    <div class="col-lg-5">
                        <div class="card border-0 shadow-sm rounded-4 h-100 bg-light bg-opacity-50">
                            <div class="card-body p-4">
                                <label for="curso_id" class="form-label small fw-bold text-muted mb-3 text-uppercase">
                                    <i class="fas fa-book me-2 text-primary"></i> 1. Seleccionar Curso
                                </label>
                                <div class="position-relative">
                                    <select class="form-select border-0 shadow-sm rounded-3 py-3 px-4 @error('curso_id') is-invalid @enderror"
                                            id="curso_id" name="curso_id" required style="font-size: 0.95rem;">
                                        <option value="">-- Elige un curso disponible --</option>
                                        @foreach ($cursos as $curso)
                                            <option value="{{ $curso->id }}" {{ old('curso_id') == $curso->id ? 'selected' : '' }}>
                                                {{ $curso->nombreCurso }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('curso_id')
                                        <div class="invalid-feedback ps-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Detalles del curso seleccionado (se llenan por JS o se muestran dinámicamente) --}}
                                <div id="curso-info-extra" class="mt-4 p-3 bg-white rounded-3 shadow-sm d-none">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                                        <span class="small fw-bold text-dark" id="curso-fechas">-</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-users text-primary me-2"></i>
                                        <span class="small fw-bold text-dark" id="curso-cupos">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Selección de Estudiantes --}}
                    <div class="col-lg-7">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <label class="form-label small fw-bold text-muted mb-0 text-uppercase">
                                        <i class="fas fa-users me-2 text-primary"></i> 2. Seleccionar Estudiante(s)
                                    </label>
                                    <button type="button" id="seleccionar-todos" class="btn btn-sm btn-link text-decoration-none fw-bold p-0">
                                        <i class="fas fa-check-double me-1"></i> Seleccionar Todos
                                    </button>
                                </div>

                                <div class="search-box-table w-100 mb-3 bg-light border-0">
                                    <i class="fas fa-search search-icon-table"></i>
                                    <input type="text" id="buscador" class="search-input-table bg-transparent"
                                        placeholder="Filtrar por nombre o correo electrónico…">
                                    <button type="button" class="btn-search-clear d-none" id="btn-clear-search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>

                                <div class="table-container-modern border rounded-3" style="max-height: 400px; overflow-y: auto;">
                                    <div id="lista-estudiantes" class="p-0">
                                        <div class="text-center py-5 opacity-50">
                                            <i class="fas fa-arrow-left fa-3x mb-3 text-primary"></i>
                                            <p class="mb-0 fw-bold">Seleccione un curso primero</p>
                                            <small>Los estudiantes se cargarán automáticamente</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-3 px-2">
                                    <span class="small fw-bold text-primary" id="contador-seleccionados">
                                        <i class="fas fa-user-check me-1"></i> 0 seleccionados
                                    </span>
                                    @error('estudiante_id')
                                        <div class="text-danger small fw-bold"><i class="fas fa-exclamation-circle me-1"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 d-flex justify-content-end pt-4 border-top">
                    <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-sm" id="btn-inscribir"
                            style="background: var(--gradient-primary) !important; border: none;">
                        <i class="fas fa-save me-2"></i> Procesar Inscripción
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
                var curso_text = $("#curso_id option:selected").text();

                if (curso_id) {
                    // Mostrar info extra del curso
                    $('#curso-info-extra').removeClass('d-none').hide().fadeIn();

                    // Mostrar spinner mientras carga
                    $('#lista-estudiantes').html(`
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-3 fw-bold text-primary">Buscando estudiantes disponibles...</p>
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
                                    <div class="text-center py-5 opacity-75">
                                        <i class="fas fa-user-slash fa-3x mb-3 text-warning"></i>
                                        <p class="mb-0 fw-bold">No hay estudiantes disponibles</p>
                                        <small>Todos los estudiantes registrados ya están en este curso.</small>
                                    </div>
                                `);
                                return;
                            }

                            let html = '<div class="list-group list-group-flush">';
                            $.each(data, function(key, value) {
                                const isSelected = @json(old('estudiante_id', []))
                                    .includes(value.id);
                                html += `
                                    <label class="list-group-item list-group-item-action border-0 border-bottom px-4 py-3 cursor-pointer" for="estudiante_${value.id}">
                                        <div class="form-check d-flex align-items-center mb-0">
                                            <input class="form-check-input me-3 estudiante-checkbox"
                                                   style="width: 1.2rem; height: 1.2rem;"
                                                   type="checkbox"
                                                   name="estudiante_id[]"
                                                   value="${value.id}"
                                                   id="estudiante_${value.id}"
                                                   ${isSelected ? 'checked' : ''}>
                                            <div class="ms-2">
                                                <div class="fw-bold text-dark">${value.name} ${value.lastname1} ${value.lastname2}</div>
                                                <div class="text-muted small"><i class="fas fa-envelope me-1"></i>${value.email}</div>
                                            </div>
                                        </div>
                                    </label>
                                `;
                            });
                            html += '</div>';
                            $('#lista-estudiantes').html(html);

                            actualizarContador();
                        },
                        error: function(xhr, status, error) {
                            $('#lista-estudiantes').html(`
                                <div class="p-4 text-center">
                                    <i class="fas fa-exclamation-triangle text-danger fa-3x mb-3"></i>
                                    <p class="text-danger fw-bold">Error al conectar con el servidor</p>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="$('#curso_id').trigger('change')">Reintentar</button>
                                </div>
                            `);
                        }
                    });
                } else {
                    $('#curso-info-extra').addClass('d-none');
                    $('#lista-estudiantes').html(`
                        <div class="text-center py-5 opacity-50">
                            <i class="fas fa-arrow-left fa-3x mb-3 text-primary"></i>
                            <p class="mb-0 fw-bold">Seleccione un curso primero</p>
                            <small>Los estudiantes se cargarán automáticamente</small>
                        </div>
                    `);
                    actualizarContador();
                }
            });

            // Buscar estudiantes
            $('#buscador').on('input', function() {
                var searchTerm = $(this).val().toLowerCase();

                if (searchTerm.length > 0) {
                    $('#btn-clear-search').removeClass('d-none');
                } else {
                    $('#btn-clear-search').addClass('d-none');
                }

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
            $('#btn-clear-search').click(function() {
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
