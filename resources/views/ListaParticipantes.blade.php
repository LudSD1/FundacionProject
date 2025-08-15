@section('titulo')
    Lista de Participantes: {{ $cursos->nombreCurso }}
@endsection

@section('content')
    <div class="container">
        <!-- Header con botones de acción -->
        <div class="row mb-4">
            <div class="col-md-6">
                <a href="{{ route('Curso', ['id' => encrypt($cursos->id)]) }}" class="btn btn-outline-primary btn-lg shadow-sm">
                    <i class="bi bi-arrow-left-circle me-2"></i>Volver al Curso
                </a>
            </div>
            <div class="col-md-6 text-end">
                @if (auth()->user()->id == $cursos->docente_id || auth()->user()->hasRole('Administrador'))
                    <div class="btn-group shadow-sm">
                        <a class="btn btn-danger" href="{{ route('listaretirados', encrypt($cursos->id)) }}">
                            <i class="bi bi-person-x me-2"></i>Retirados
                        </a>
                        @if ($cursos->tipo == 'congreso')
                            <a class="btn btn-info text-white"
                                href="{{ route('certificadosCongreso.generar', $cursos->id) }}">
                                <i class="bi bi-award me-2"></i>Certificados
                            </a>
                        @endif
                        <a class="btn btn-success" href="{{ route('lista', encrypt($cursos->id)) }}">
                            <i class="bi bi-download me-2"></i>Descargar
                        </a>
                        <button type="button" class="btn btn-outline-secondary" id="selectAllBtn">
                            <i class="bi bi-check-square me-2"></i>Seleccionar Todo
                        </button>
                    </div>
                @endif
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

        <!-- Barra de búsqueda y filtros mejorada -->
        <div class="row mb-4">
            <div class="col-md-8 mx-auto">
                <div class="input-group input-group-lg shadow-sm">
                    <span class="input-group-text bg-white">
                        <i class="bi bi-search text-primary"></i>
                    </span>
                    <input type="text" class="form-control border-start-0" id="searchInput"
                        placeholder="Buscar participante..." autocomplete="off">
                    <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                        <i class="bi bi-x-lg"></i>
                    </button>
                    @role('Administrador')
                        <select class="form-select" id="statusFilter" style="max-width: 200px;">
                            <option value="">Todos los estados</option>
                            <option value="pago-completado">Pago Completado</option>
                            <option value="pago-revision">Pago en Revisión</option>
                            <option value="sin-pago">Sin información de pago</option>
                        </select>
                    @endrole
                </div>
                <div class="text-center mt-2">
                    <small class="text-muted">
                        Total: <span id="totalCount">{{ $inscritos->where('cursos_id', $cursos->id)->count() }}</span>
                        participantes |
                        Mostrando: <span id="visibleCount">{{ $inscritos->where('cursos_id', $cursos->id)->count() }}</span>
                        @role('Administrador')
                            | Pagos pendientes: <span id="pendingPayments"
                                class="text-warning fw-bold">{{ $inscritos->where('cursos_id', $cursos->id)->where('pago_completado', false)->count() }}</span>
                        @endrole
                    </small>
                </div>
            </div>
        </div>

        <!-- Tabla mejorada -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle" id="participantesTable">
                        <thead>
                            <tr>
                                @if (auth()->user()->hasRole('Docente') || auth()->user()->hasRole('Administrador'))
                                    <th class="px-4" scope="col" width="5%">
                                        <input type="checkbox" id="masterCheckbox" class="form-check-input">
                                    </th>
                                @endif
                                <th class="px-4" scope="col" width="8%">#</th>
                                <th scope="col" width="35%">Nombre y Apellidos</th>
                                <th scope="col" width="20%">Celular</th>
                                @role('Administrador')
                                    <th scope="col" width="15%">Estado Pago</th>
                                @endrole
                                <th scope="col" width="17%" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($inscritos as $inscrito)
                                @if ($inscrito->cursos_id == $cursos->id)
                                    <tr class="align-middle participante-row" data-participante-id="{{ $inscrito->id }}"
                                        data-pago-status="{{ $inscrito->pago_completado ? 'completado' : 'pendiente' }}">
                                        @if (auth()->user()->hasRole('Docente') || auth()->user()->hasRole('Administrador'))
                                            <td class="px-4">
                                                <input type="checkbox" class="form-check-input student-checkbox"
                                                    value="{{ $inscrito->id }}">
                                            </td>
                                        @endif
                                        <td class="px-4 fw-bold text-primary">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle bg-primary text-white me-3">
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
                                                    @role('Administrador')
                                                        @if ($cursos->tipo == 'curso' && !$inscrito->pago_completado)
                                                            <div class="mt-1">
                                                                <span class="badge bg-warning text-dark">Pago en Revisión</span>
                                                            </div>
                                                        @elseif ($cursos->tipo == 'curso' && $inscrito->pago_completado)
                                                            <div class="mt-1">
                                                                <span class="badge bg-success">Pago Completado</span>
                                                            </div>
                                                        @endif
                                                    @endrole
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($inscrito->estudiantes->Celular ?? false)
                                                <p href="tel:{{ $inscrito->estudiantes->Celular }}"
                                                    class="text-decoration-none">
                                                    <i class="bi bi-telephone me-2"></i>
                                                    +{{ $inscrito->estudiantes->Celular }}
                                                </p>
                                            @else
                                                <span class="text-muted">Sin celular</span>
                                            @endif
                                        </td>
                                        @role('Administrador')
                                            <td>
                                                @if ($cursos->tipo == 'curso')
                                                    @if ($inscrito->pago_completado)
                                                        <span class="badge bg-success">
                                                            <i class="bi bi-check-circle me-1"></i>Completado
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">
                                                            <i class="bi bi-clock me-1"></i>En Revisión
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                        @endrole
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a class="btn btn-sm btn-outline-info"
                                                    href="{{ route('perfil', [encrypt($inscrito->estudiantes->id)]) }}"
                                                    data-bs-toggle="tooltip" title="Ver Perfil">
                                                    <i class="bi bi-person-badge"></i>
                                                </a>

                                                @if (auth()->user()->hasRole('Docente') || auth()->user()->hasRole('Administrador'))
                                                    <form action="{{ route('quitarInscripcion', $inscrito->id) }}"
                                                        method="POST" style="display:inline;" class="retire-form">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            onclick="mostrarAdvertencia(event)" data-bs-toggle="tooltip"
                                                            title="Retirar Estudiante">
                                                            <i class="bi bi-person-x"></i>
                                                        </button>
                                                    </form>

                                                    @if ($cursos->tipo == 'congreso')
                                                        <a class="btn btn-sm btn-outline-success"
                                                            href="{{ route('certificados.reenviar.email', encrypt($inscrito->id)) }}"
                                                            data-bs-toggle="tooltip"
                                                            title="{{ !isset($inscrito->certificado) ? 'Generar Certificado' : 'Reenviar Certificado' }}">
                                                            <i class="bi bi-award"></i>
                                                        </a>
                                                    @endif

                                                    @if ($cursos->tipo == 'curso')
                                                        <div class="btn-group">
                                                            <a class="btn btn-sm btn-outline-primary"
                                                                href="{{ route('boletin', [$inscrito->id]) }}"
                                                                data-bs-toggle="tooltip" title="Ver Boletín">
                                                                <i class="bi bi-journal-text"></i>
                                                            </a>
                                                            <a class="btn btn-sm btn-outline-primary"
                                                                href="{{ route('verBoletin2', [$inscrito->id]) }}"
                                                                data-bs-toggle="tooltip" title="Calificaciones Finales">
                                                                <i class="bi bi-journal-check"></i>
                                                            </a>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr id="noResultsRow">
                                    <td colspan="{{ auth()->user()->hasRole('Administrador') ? '6' : '5' }}"
                                        class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="bi bi-emoji-frown display-1 text-muted mb-3"></i>
                                            <h4 class="text-muted">No hay participantes inscritos</h4>
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
                <p class="text-muted">No se encontraron participantes que coincidan con los filtros</p>
                <button class="btn btn-outline-primary" id="clearSearchBtn">
                    <i class="bi bi-x-lg me-1"></i>Limpiar filtros
                </button>
            </div>
        </div>

        <!-- Acciones masivas -->
        @if (auth()->user()->hasRole('Docente') || auth()->user()->hasRole('Administrador'))
            <div class="card shadow-sm mt-3" id="massActionsCard" style="display: none;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">
                            <i class="bi bi-check-square me-2"></i>
                            <strong id="selectedCount">0</strong> participante(s) seleccionado(s)
                        </span>
                        <div class="btn-group">
                            <button class="btn btn-outline-danger" id="retirarSeleccionados">
                                <i class="bi bi-person-x me-1"></i>Retirar Seleccionados
                            </button>
                            @if ($cursos->tipo == 'congreso')
                                <button class="btn btn-outline-success" id="generarCertificados">
                                    <i class="bi bi-award me-1"></i>Generar Certificados
                                </button>
                            @endif
                            <button class="btn btn-outline-secondary" id="deselectAll">
                                <i class="bi bi-square me-1"></i>Deseleccionar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Estadísticas rápidas -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-people display-4 text-primary mb-2"></i>
                        <h5 class="card-title">Total</h5>
                        <p class="card-text display-6" id="statsTotal">
                            {{ $inscritos->where('cursos_id', $cursos->id)->count() }}</p>
                    </div>
                </div>
            </div>
            @role('Administrador')
                @if ($cursos->tipo == 'curso')
                    <div class="col-md-3">
                        <div class="card text-center shadow-sm">
                            <div class="card-body">
                                <i class="bi bi-check-circle display-4 text-success mb-2"></i>
                                <h5 class="card-title">Pagos OK</h5>
                                <p class="card-text display-6 text-success" id="statsPaid">
                                    {{ $inscritos->where('cursos_id', $cursos->id)->where('pago_completado', true)->count() }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center shadow-sm">
                            <div class="card-body">
                                <i class="bi bi-clock display-4 text-warning mb-2"></i>
                                <h5 class="card-title">Pendientes</h5>
                                <p class="card-text display-6 text-warning" id="statsPending">
                                    {{ $inscritos->where('cursos_id', $cursos->id)->where('pago_completado', false)->count() }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            @endrole
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-eye display-4 text-info mb-2"></i>
                        <h5 class="card-title">Mostrando</h5>
                        <p class="card-text display-6 text-info" id="statsVisible">
                            {{ $inscritos->where('cursos_id', $cursos->id)->count() }}</p>
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

        .participante-row {
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

        .stats-card {
            transition: transform 0.2s;
        }

        .stats-card:hover {
            transform: translateY(-2px);
        }
    </style>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        $(document).ready(function() {
            let totalRows = $('.participante-row').length;

            // Inicializar tooltips de Bootstrap
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Función de búsqueda y filtros mejorada
            function applyFilters() {
                const searchText = $('#searchInput').val().toLowerCase().trim();
                const statusFilter = $('#statusFilter').val();
                let visibleCount = 0;

                $('.participante-row').each(function() {
                    const rowText = $(this).text().toLowerCase();
                    const pagoStatus = $(this).data('pago-status');

                    let matchSearch = searchText === '' || rowText.includes(searchText);
                    let matchStatus = true;

                    // Aplicar filtro de estado de pago
                    if (statusFilter) {
                        if (statusFilter === 'pago-completado') {
                            matchStatus = pagoStatus === 'completado';
                        } else if (statusFilter === 'pago-revision') {
                            matchStatus = pagoStatus === 'pendiente';
                        }
                    }

                    const shouldShow = matchSearch && matchStatus;
                    $(this).toggle(shouldShow);

                    // Añadir efecto de resaltado
                    if (shouldShow && searchText.length > 0) {
                        $(this).addClass('table-active');
                    } else {
                        $(this).removeClass('table-active');
                    }

                    if (shouldShow) visibleCount++;
                });

                // Actualizar contadores
                $('#visibleCount, #statsVisible').text(visibleCount);

                // Mostrar/ocultar mensaje de sin resultados
                if (visibleCount === 0 && (searchText !== '' || statusFilter !== '') && totalRows > 0) {
                    $('#noSearchResults').show();
                    $('.table-responsive').hide();
                } else {
                    $('#noSearchResults').hide();
                    $('.table-responsive').show();
                }
            }

            // Event listeners para filtros
            $('#searchInput').on('input', applyFilters);
            $('#statusFilter').on('change', applyFilters);

            // Limpiar búsqueda y filtros
            $('#clearSearch, #clearSearchBtn').on('click', function() {
                $('#searchInput').val('');
                $('#statusFilter').val('');
                $('.participante-row').show().removeClass('table-active');
                $('#visibleCount, #statsVisible').text(totalRows);
                $('#noSearchResults').hide();
                $('.table-responsive').show();
            });

            // Manejo de checkboxes
            $('#masterCheckbox').on('change', function() {
                $('.student-checkbox:visible').prop('checked', this.checked);
                updateSelectedCount();
            });

            $(document).on('change', '.student-checkbox', function() {
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

            // Acciones masivas
            $('#retirarSeleccionados').on('click', function() {
                const selected = $('.student-checkbox:checked').map(function() {
                    return this.value;
                }).get();

                if (selected.length > 0) {
                    confirmarRetiroMasivo(selected);
                }
            });

            $('#generarCertificados').on('click', function() {
                const selected = $('.student-checkbox:checked').map(function() {
                    return this.value;
                }).get();

                if (selected.length > 0) {
                    confirmarGenerarCertificados(selected);
                }
            });
        });

        // Funciones de confirmación
        function mostrarAdvertencia(event) {
            event.preventDefault();
            const form = event.target.closest('form');

            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción retirará al estudiante del curso. Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, retirar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        // Función auxiliar para obtener el CSRF token
        function getCSRFToken() {
            // Método 1: Desde meta tag
            let token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // Método 2: Desde input hidden (si existe)
            if (!token) {
                token = document.querySelector('input[name="_token"]')?.value;
            }

            // Método 3: Desde variable global de Laravel (si está definida)
            if (!token && typeof window.Laravel !== 'undefined') {
                token = window.Laravel.csrfToken;
            }

            return token || '';
        }

        function confirmarRetiroMasivo(ids) {
            // Verificar que ids sea un array válido
            if (!Array.isArray(ids) || ids.length === 0) {
                Swal.fire({
                    title: 'Error',
                    text: 'No hay participantes seleccionados para retirar.',
                    icon: 'error',
                    confirmButtonText: 'Entendido'
                });
                return;
            }

            const cursoId = {{ $cursos->id }}; // Asegúrate de que esta variable esté disponible

            // Verificar que cursoId esté definido
            if (!cursoId) {
                Swal.fire({
                    title: 'Error',
                    text: 'ID del curso no disponible.',
                    icon: 'error',
                    confirmButtonText: 'Entendido'
                });
                return;
            }

            Swal.fire({
                title: '¿Retirar participantes seleccionados?',
                text: `Se retirarán ${ids.length} participantes del curso. Esta acción no se puede deshacer.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, retirar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                showLoaderOnConfirm: true,
                allowOutsideClick: false, // Evita cerrar durante la carga
                preConfirm: () => {
                    return fetch('{{ route('cursos.retirarMasivo') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': getCSRFToken(),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                inscripciones: ids,
                                curso_id: cursoId
                            })
                        })
                        .then(response => {
                            // Verificar el status HTTP
                            if (!response.ok) {
                                return response.json().then(err => {
                                    throw new Error(err.message ||
                                        `Error HTTP ${response.status}: ${response.statusText}`);
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Verificar la respuesta del servidor
                            if (!data.success) {
                                throw new Error(data.message || 'Error desconocido en el servidor');
                            }
                            return data;
                        })
                        .catch(error => {
                            console.error('Error en retiro masivo:', error);
                            Swal.showValidationMessage(`Error: ${error.message}`);
                            return false; // Evita que se cierre el modal
                        });
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    const data = result.value;

                    Swal.fire({
                        title: '¡Completado!',
                        html: `
                    <div class="text-start">
                        <p><strong>Participantes retirados:</strong> ${data.exitosos || 0}</p>
                        ${data.fallidos > 0 ? `<p class="text-warning"><strong>Errores:</strong> ${data.fallidos}</p>` : ''}
                        <p><strong>Total procesados:</strong> ${data.total_procesados || 0}</p>
                    </div>
                `,
                        icon: data.fallidos > 0 ? 'warning' : 'success',
                        confirmButtonText: 'Entendido',
                        allowOutsideClick: false
                    }).then(() => {
                        // Recargar la página para reflejar los cambios
                        window.location.reload();
                    });
                }
            });
        }


        function confirmarGenerarCertificados(ids) {
            Swal.fire({
                title: '¿Generar certificados?',
                text: `Se generarán certificados para ${ids.length} participantes.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, generar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Implementar ruta para generación masiva de certificados
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
