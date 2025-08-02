@section('titulo')
        Lista de Participantes: {{ $cursos->nombreCurso }}
@endsection

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-6">
                <a href="{{ route('Curso', ['id' => $cursos->id]) }}"
                   class="btn btn-outline-primary btn-lg shadow-sm">
                    <i class="bi bi-arrow-left-circle me-2"></i>Volver al Curso
                </a>
            </div>
            <div class="col-md-6 text-end">
                @if (auth()->user()->id == $cursos->docente_id || auth()->user()->hasRole('Administrador'))
                    <div class="btn-group shadow-sm">
                        <a class="btn btn-danger" href="{{ route('listaretirados', $cursos->id) }}">
                            <i class="bi bi-person-x me-2"></i>Retirados
                        </a>
                        @if ($cursos->tipo == 'congreso')
                            <a class="btn btn-info text-white" href="{{ route('certificadosCongreso.generar', $cursos->id) }}">
                                <i class="bi bi-award me-2"></i>Certificados
                            </a>
                        @endif
                        <a class="btn btn-success" href="{{ route('lista', $cursos->id) }}">
                            <i class="bi bi-download me-2"></i>Descargar
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Barra de búsqueda mejorada -->
        <div class="row mb-4">
            <div class="col-md-6 mx-auto">
                <div class="input-group input-group-lg shadow-sm">
                    <span class="input-group-text bg-white">
                        <i class="bi bi-search text-primary"></i>
                    </span>
                    <input type="text"
                           class="form-control border-start-0"
                           id="searchInput"
                           placeholder="Buscar participante..."
                           autocomplete="off">
                </div>
            </div>
        </div>

        <!-- Tabla mejorada -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead >
                            <tr>
                                <th class="px-4" scope="col">#</th>
                                <th scope="col">Nombre y Apellidos</th>
                                <th scope="col">Celular</th>
                                <th scope="col" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($inscritos as $inscrito)
                                @if ($inscrito->cursos_id == $cursos->id)
                                    <tr class="align-middle">
                                        <td class="px-4">{{ $loop->iteration }}</td>
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
                                                    @role('Administrador')
                                                        @if ($cursos->tipo == 'curso' && !$inscrito->pago_completado)
                                                            <span class="badge bg-warning text-dark">Pago en Revisión</span>
                                                        @endif
                                                    @endrole
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($inscrito->estudiantes->Celular)
                                                <a href="tel:{{ $inscrito->estudiantes->Celular }}"
                                                   class="text-decoration-none">
                                                    <i class="bi bi-telephone me-2"></i>
                                                    {{ $inscrito->estudiantes->Celular }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a class="btn btn-sm btn-outline-info"
                                                   href="{{ route('perfil', [$inscrito->estudiantes->id]) }}"
                                                   data-bs-toggle="tooltip"
                                                   title="Ver Perfil">
                                                    <i class="bi bi-person-badge"></i>
                                                </a>

                                                @if (auth()->user()->hasRole('Docente') || auth()->user()->hasRole('Administrador'))
                                                    <a class="btn btn-sm btn-outline-danger"
                                                       href="{{ route('quitar', [$inscrito->id]) }}"
                                                       onclick="mostrarAdvertencia(event)"
                                                       data-bs-toggle="tooltip"
                                                       title="Retirar Estudiante">
                                                        <i class="bi bi-person-x"></i>
                                                    </a>

                                                    @if ($cursos->tipo == 'congreso')
                                                        <a class="btn btn-sm btn-outline-success"
                                                           href="{{ route('certificadosCongreso.generar.admin', [encrypt($inscrito->id)]) }}"
                                                           data-bs-toggle="tooltip"
                                                           title="{{ !isset($inscrito->certificado) ? 'Generar Certificado' : 'Reenviar Certificado' }}">
                                                            <i class="bi bi-award"></i>
                                                        </a>
                                                    @endif

                                                    @if ($cursos->tipo == 'curso')
                                                        <div class="btn-group">
                                                            <a class="btn btn-sm btn-outline-primary"
                                                               href="{{ route('boletin', [$inscrito->id]) }}"
                                                               data-bs-toggle="tooltip"
                                                               title="Ver Boletín">
                                                                <i class="bi bi-journal-text"></i>
                                                            </a>
                                                            <a class="btn btn-sm btn-outline-primary"
                                                               href="{{ route('verBoletin2', [$inscrito->id]) }}"
                                                               data-bs-toggle="tooltip"
                                                               title="Calificaciones Finales">
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
                                <tr>
                                    <td colspan="4" class="text-center py-5">
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
        }

        .btn-group .btn {
            position: relative;
            transition: all 0.2s;
        }

        .btn-group .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .table tbody tr {
            transition: all 0.2s;
        }

        .table tbody tr:hover {
            background-color: rgba(0,0,0,0.02);
        }
    </style>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        // Inicializar tooltips de Bootstrap
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });

        // Búsqueda en tiempo real mejorada
        $(document).ready(function() {
            $('#searchInput').on('input', function() {
                var searchText = $(this).val().toLowerCase();
                $('tbody tr').each(function() {
                    var rowText = $(this).text().toLowerCase();
                    var match = rowText.includes(searchText);
                    $(this).toggle(match);

                    // Añadir efecto de resaltado
                    if (match && searchText.length > 0) {
                        $(this).addClass('table-active');
                    } else {
                        $(this).removeClass('table-active');
                    }
                });
            });
        });

        // Advertencia mejorada antes de eliminar inscripción
        function mostrarAdvertencia(event) {
            event.preventDefault();
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
                    window.location.href = event.target.getAttribute('href');
                }
            });
        }
    </script>
@endsection

@include('layout')
