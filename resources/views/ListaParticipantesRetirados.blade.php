@section('titulo')
    Lista de Paticipantes {{ $cursos->nombreCurso }}
@endsection




@section('content')
    <div class="container">
        <!-- Header con botones de acción -->
        <div class="row mb-4">
            <div class="col-md-6">
                <a href="{{ route('listacurso', ['id' => encrypt($cursos->id)]) }}"
                    class="btn btn-outline-primary btn-lg shadow-sm">
                    <i class="bi bi-arrow-left-circle me-2"></i>Volver al Curso
                </a>
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group shadow-sm">
                    <form id="restaurar-todos-form" action="{{ route('cursos.restaurarTodos', ['cursoId' => $cursos->id]) }}"
                        method="POST" style="display: none;">
                        @csrf
                    </form>
                    <button type="button" class="btn btn-success" onclick="confirmarRestaurarTodos()"
                        {{ $inscritos->where('cursos_id', $cursos->id)->count() == 0 ? 'disabled' : '' }}>
                        <i class="fas fa-undo"></i> Restaurar Todas las Inscripciones
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="selectAllBtn">
                        <i class="bi bi-check-square me-2"></i>Seleccionar Todo
                    </button>
                </div>
            </div>
        </div>

        <!-- Alertas -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Barra de búsqueda mejorada -->
        <div class="row mb-4">
            <div class="col-md-6 mx-auto">
                <div class="input-group input-group-lg shadow-sm">
                    <span class="input-group-text bg-white">
                        <i class="bi bi-search text-primary"></i>
                    </span>
                    <input type="text" class="form-control border-start-0" id="searchInput"
                        placeholder="Buscar estudiante retirado..." autocomplete="off">
                    <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="text-center mt-2">
                    <small class="text-muted">
                        Total: <span id="totalCount">{{ $inscritos->where('cursos_id', $cursos->id)->count() }}</span>
                        estudiantes |
                        Mostrando: <span id="visibleCount">{{ $inscritos->where('cursos_id', $cursos->id)->count() }}</span>
                    </small>
                </div>
            </div>
        </div>

        <!-- Tabla mejorada -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle" id="estudiantesTable">
                        <thead>
                            <tr>
                                <th class="px-4" scope="col" width="5%">
                                    <input type="checkbox" id="masterCheckbox" class="form-check-input">
                                </th>
                                <th class="px-4" scope="col" width="8%">#</th>
                                <th scope="col" width="40%">Nombre y Apellidos</th>
                                <th scope="col" width="20%">Celular</th>
                                <th scope="col" width="15%">Fecha Retiro</th>
                                <th scope="col" width="12%" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($inscritos as $inscrito)
                                @if ($inscrito->cursos_id == $cursos->id)
                                    <tr class="align-middle estudiante-row" data-estudiante-id="{{ $inscrito->id }}">
                                        <td class="px-4">
                                            <input type="checkbox" class="form-check-input student-checkbox"
                                                value="{{ $inscrito->id }}">
                                        </td>
                                        <td class="px-4 fw-bold text-primary">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle bg-warning text-white me-3">
                                                    {{ substr($inscrito->estudiantes->name ?? 'E', 0, 1) }}
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">
                                                        {{ $inscrito->estudiantes->name ?? 'Estudiante Eliminado' }}
                                                        {{ $inscrito->estudiantes->lastname1 ?? '' }}
                                                        {{ $inscrito->estudiantes->lastname2 ?? '' }}
                                                    </h6>
                                                    @if (isset($inscrito->estudiantes->email))
                                                        <small class="text-muted">
                                                            <i
                                                                class="bi bi-envelope me-1"></i>{{ $inscrito->estudiantes->email }}
                                                        </small>
                                                    @endif
                                                    <span class="badge bg-danger">Retirado</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($inscrito->estudiantes->Celular ?? false)
                                                <a href="tel:{{ $inscrito->estudiantes->Celular }}"
                                                    class="text-decoration-none">
                                                    <i class="bi bi-telephone me-2"></i>
                                                    +{{ $inscrito->estudiantes->Celular }}
                                                </a>
                                            @else
                                                <span class="text-muted">Sin celular</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar-date me-1"></i>
                                                {{ $inscrito->updated_at ? $inscrito->updated_at->format('d/m/Y') : 'N/A' }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <button class="btn btn-sm btn-outline-success"
                                                    onclick="confirmarRestauracion('{{ encrypt($inscrito->id ?? '') }}', '{{ $inscrito->estudiantes->name ?? 'Estudiante' }}')"
                                                    data-bs-toggle="tooltip" title="Restaurar inscripción">
                                                    <i class="bi bi-arrow-clockwise"></i>
                                                </button>
                                                <a class="btn btn-sm btn-outline-info"
                                                    href="{{ route('perfil', [encrypt($inscrito->estudiantes->id)]) }}"
                                                    data-bs-toggle="tooltip" title="Ver Perfil">
                                                    <i class="bi bi-person-badge"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr id="noResultsRow">
                                    <td colspan="6" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="bi bi-emoji-smile display-1 text-success mb-3"></i>
                                            <h4 class="text-muted">¡Excelente!</h4>
                                            <p class="text-muted">No hay estudiantes retirados en este curso</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Mensaje cuando no hay resultados de búsqueda -->
        <div class="card shadow-sm mt-3" id="noSearchResults" style="display: none;">
            <div class="card-body text-center py-5">
                <i class="bi bi-search display-1 text-muted mb-3"></i>
                <h4 class="text-muted">Sin resultados</h4>
                <p class="text-muted">No se encontraron estudiantes que coincidan con la búsqueda</p>
                <button class="btn btn-outline-primary" id="clearSearchBtn">
                    <i class="bi bi-x-lg me-1"></i>Limpiar búsqueda
                </button>
            </div>
        </div>

        <!-- Acciones masivas -->
        <div class="card shadow-sm mt-3" id="massActionsCard" style="display: none;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">
                        <i class="bi bi-check-square me-2"></i>
                        <strong id="selectedCount">0</strong> estudiante(s) seleccionado(s)
                    </span>
                    <div class="btn-group">
                        <button class="btn btn-outline-success" id="restaurarSeleccionados">
                            <i class="bi bi-arrow-clockwise me-1"></i>Restaurar Seleccionados
                        </button>
                        <button class="btn btn-outline-secondary" id="deselectAll">
                            <i class="bi bi-square me-1"></i>Deseleccionar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estilos adicionales -->
    <style>
        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .table th {
            font-weight: 600;
            background-color: #f8f9fa;
        }

        .btn-group .btn {
            position: relative;
            transition: all 0.2s;
        }

        .btn-group .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .table tbody tr {
            transition: all 0.2s;
        }

        .table tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .estudiante-row {
            transition: all 0.3s ease;
        }

        .alert {
            border: none;
            border-radius: 10px;
        }

        .card {
            border-radius: 10px;
        }

        .badge {
            font-size: 0.75em;
        }

        .table-active {
            background-color: rgba(13, 110, 253, 0.1) !important;
        }
    </style>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        $(document).ready(function() {
            let totalRows = $('.estudiante-row').length;

            // Inicializar tooltips de Bootstrap
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Función de búsqueda mejorada
            $('#searchInput').on('input', function() {
                const searchText = $(this).val().toLowerCase().trim();
                let visibleCount = 0;

                $('.estudiante-row').each(function() {
                    const rowText = $(this).text().toLowerCase();
                    const match = searchText === '' || rowText.includes(searchText);
                    $(this).toggle(match);

                    // Añadir efecto de resaltado
                    if (match && searchText.length > 0) {
                        $(this).addClass('table-active');
                    } else {
                        $(this).removeClass('table-active');
                    }

                    if (match) visibleCount++;
                });

                // Actualizar contadores
                $('#visibleCount').text(visibleCount);

                // Mostrar/ocultar mensaje de sin resultados
                if (visibleCount === 0 && searchText !== '' && totalRows > 0) {
                    $('#noSearchResults').show();
                    $('.table-responsive').hide();
                } else {
                    $('#noSearchResults').hide();
                    $('.table-responsive').show();
                }
            });

            // Limpiar búsqueda
            $('#clearSearch, #clearSearchBtn').on('click', function() {
                $('#searchInput').val('');
                $('.estudiante-row').show().removeClass('table-active');
                $('#visibleCount').text(totalRows);
                $('#noSearchResults').hide();
                $('.table-responsive').show();
            });

            // Manejo de checkboxes
            $('#masterCheckbox').on('change', function() {
                $('.student-checkbox:visible').prop('checked', this.checked);
                updateSelectedCount();
            });

            $('.student-checkbox').on('change', function() {
                updateSelectedCount();
                updateMasterCheckbox();
            });

            // Seleccionar/Deseleccionar todos
            $('#selectAllBtn').on('click', function() {
                $('.student-checkbox:visible').prop('checked', true);
                updateSelectedCount();
                updateMasterCheckbox();
            });

            $('#deselectAll').on('click', function() {
                $('.student-checkbox').prop('checked', false);
                updateSelectedCount();
                updateMasterCheckbox();
            });

            function updateSelectedCount() {
                const selectedCount = $('.student-checkbox:checked').length;
                $('#selectedCount').text(selectedCount);
                $('#massActionsCard').toggle(selectedCount > 0);
            }

            function updateMasterCheckbox() {
                const visibleCheckboxes = $('.student-checkbox:visible');
                const checkedVisible = $('.student-checkbox:visible:checked');
                $('#masterCheckbox').prop('checked', visibleCheckboxes.length > 0 && visibleCheckboxes.length ===
                    checkedVisible.length);
            }

            // Restaurar seleccionados
            $('#restaurarSeleccionados').on('click', function() {
                const selected = $('.student-checkbox:checked').map(function() {
                    return this.value;
                }).get();

                if (selected.length > 0) {
                    confirmarRestauracionMasiva(selected);
                }
            });
        });

        // Funciones de confirmación con SweetAlert2
        function confirmarRestauracion(id, nombre) {
            Swal.fire({
                title: '¿Restaurar inscripción?',
                text: `¿Estás seguro de restaurar la inscripción de ${nombre}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, restaurar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('restaurarIncripcion', '') }}/" + id;
                }
            });
        }



        function confirmarRestaurarTodos() {
            const total = {{ $inscritos->where('cursos_id', $cursos->id)->count() }};

            // Verificar que haya inscripciones para restaurar
            if (total === 0) {
                Swal.fire({
                    title: 'Sin inscripciones',
                    text: 'No hay inscripciones retiradas para restaurar en este curso.',
                    icon: 'info',
                    confirmButtonText: 'Entendido'
                });
                return;
            }

            Swal.fire({
                title: '¿Restaurar todas las inscripciones?',
                text: `Se restaurarán todas las inscripciones retiradas del curso. Esta acción no se puede deshacer.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, restaurar todas',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Enviar el formulario
                    document.getElementById('restaurar-todos-form').submit();
                }
            });
        }

        function confirmarRestauracionMasiva(ids) {
            Swal.fire({
                title: '¿Restaurar inscripciones seleccionadas?',
                text: `Se restaurarán ${ids.length} inscripciones seleccionadas.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, restaurar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {


                    Swal.fire({
                        title: 'Función pendiente',
                        text: 'Esta funcionalidad requiere implementar la ruta correspondiente.',
                        icon: 'info'
                    });
                }
            });
        }
    </script>
@endsection

@include('layout')
