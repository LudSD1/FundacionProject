@section('titulo')
    Lista de cursos
@endsection


@section('content')

<div class="container my-4">
    <div class="card card-modern">
        <!-- Header con acciones -->
        <div class="card-header-modern">
            <div class="row align-items-center g-3">
                @if (auth()->user()->hasRole('Administrador'))
                <div class="col-lg-6 col-md-12">
                    <div class="action-buttons-header">
                        <a href="{{ route('CrearCurso') }}" class="btn btn-modern btn-create">
                            <i class="bi bi-plus-circle-fill me-2"></i>
                            <span>Crear Curso</span>
                        </a>
                        <a href="{{ route('ListadeCursosEliminados') }}" class="btn btn-modern btn-deleted">
                            <i class="bi bi-trash-fill me-2"></i>
                            <span>Cursos Eliminados</span>
                        </a>
                    </div>
                </div>
                @endif
                <div class="col-lg-6 col-md-12">
                    <div class="search-box-table">
                        <i class="bi bi-search search-icon-table"></i>
                        <input type="text"
                               class="form-control search-input-table"
                               placeholder="Buscar por nombre, docente, tipo..."
                               id="searchInput">
                        <div class="search-indicator"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla responsive -->
        <div class="table-responsive table-container-modern">
            @if (auth()->user()->hasRole('Administrador'))
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th width="5%">
                                <div class="th-content">
                                    <i class="bi bi-hash"></i>
                                    <span>Nº</span>
                                </div>
                            </th>
                            <th width="20%">
                                <div class="th-content">
                                    <i class="bi bi-book-fill"></i>
                                    <span>Nombre Curso</span>
                                </div>
                            </th>
                            <th width="15%">
                                <div class="th-content">
                                    <i class="bi bi-person-fill"></i>
                                    <span>Docente</span>
                                </div>
                            </th>
                            <th width="10%">
                                <div class="th-content">
                                    <i class="bi bi-calendar-check"></i>
                                    <span>Fecha Inicio</span>
                                </div>
                            </th>
                            <th width="10%">
                                <div class="th-content">
                                    <i class="bi bi-calendar-x"></i>
                                    <span>Fecha Fin</span>
                                </div>
                            </th>
                            <th width="10%">
                                <div class="th-content">
                                    <i class="bi bi-display"></i>
                                    <span>Formato</span>
                                </div>
                            </th>
                            <th width="10%">
                                <div class="th-content">
                                    <i class="bi bi-tags-fill"></i>
                                    <span>Tipo</span>
                                </div>
                            </th>
                            <th width="20%" class="text-center">
                                <div class="th-content justify-content-center">
                                    <i class="bi bi-gear-fill"></i>
                                    <span>Acciones</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cursos as $curso)
                            <tr class="curso-row" data-course-id="{{ $curso->id }}">
                                <td>
                                    <span class="row-number">{{ $loop->iteration }}</span>
                                </td>
                                <td>
                                    <div class="course-name-cell"
                                         data-bs-toggle="modal"
                                         data-bs-target="#courseModal{{ $curso->id }}"
                                         style="cursor: pointer;">
                                        <i class="bi bi-journal-bookmark-fill course-icon"></i>
                                        <span class="course-name">{{ ucfirst(strtolower($curso->nombreCurso)) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="teacher-cell">
                                        <i class="bi bi-person-badge"></i>
                                        <span>{{ $curso->docente ? $curso->docente->name . ' ' . $curso->docente->lastname1 . ' ' . $curso->docente->lastname2 : 'N/A' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="date-badge date-start">
                                        <i class="bi bi-calendar-event me-1"></i>
                                        {{ $curso->fecha_ini ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="date-badge date-end">
                                        <i class="bi bi-calendar-event me-1"></i>
                                        {{ $curso->fecha_fin ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="format-badge">
                                        <i class="bi bi-laptop me-1"></i>
                                        {{ $curso->formato ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="type-badge type-{{ strtolower($curso->tipo ?? 'curso') }}">
                                        <i class="bi bi-{{ $curso->tipo == 'congreso' ? 'calendar-event' : 'mortarboard' }}-fill me-1"></i>
                                        {{ ucfirst(strtolower($curso->tipo)) ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons-cell">
                                        <a class="btn-action-modern btn-edit"
                                           href="{{ route('editarCurso', encrypt($curso->id)) }}"
                                           data-bs-toggle="tooltip"
                                           title="Editar curso">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a class="btn-action-modern btn-delete"
                                           href="{{ route('quitarCurso', [encrypt($curso->id)]) }}"
                                           data-bs-toggle="tooltip"
                                           title="Eliminar curso">
                                            <i class="bi bi-trash-fill"></i>
                                        </a>
                                        <a class="btn-action-modern btn-view"
                                           href="{{ route('Curso', [encrypt($curso->id)]) }}"
                                           data-bs-toggle="tooltip"
                                           title="Ver detalles">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal de Detalles -->
                            <div class="modal fade" id="courseModal{{ $curso->id }}" tabindex="-1" aria-labelledby="courseModalLabel{{ $curso->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content modal-modern">
                                        <div class="modal-header-course">
                                            <div class="modal-title-wrapper">
                                                <i class="bi bi-book-half modal-icon-course"></i>
                                                <h5 class="modal-title" id="courseModalLabel{{ $curso->id }}">
                                                    Detalles del Curso
                                                </h5>
                                            </div>
                                            <button type="button" class="btn-close-modern-course" data-bs-dismiss="modal" aria-label="Cerrar">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                        <div class="modal-body-course">
                                            <div class="course-details-grid">
                                                <div class="detail-item">
                                                    <i class="bi bi-bookmark-star-fill detail-icon"></i>
                                                    <div class="detail-content">
                                                        <span class="detail-label">Nombre</span>
                                                        <span class="detail-value">{{ ucfirst(strtolower($curso->nombreCurso)) }}</span>
                                                    </div>
                                                </div>
                                                <div class="detail-item">
                                                    <i class="bi bi-bar-chart-fill detail-icon"></i>
                                                    <div class="detail-content">
                                                        <span class="detail-label">Nivel</span>
                                                        <span class="detail-value">{{ $curso->nivel ? ucfirst(strtolower($curso->nivel)) : 'N/A' }}</span>
                                                    </div>
                                                </div>
                                                <div class="detail-item">
                                                    <i class="bi bi-person-badge-fill detail-icon"></i>
                                                    <div class="detail-content">
                                                        <span class="detail-label">Instructor</span>
                                                        <span class="detail-value">{{ $curso->docente ? $curso->docente->name . ' ' . $curso->docente->lastname1 . ' ' . $curso->docente->lastname2 : 'N/A' }}</span>
                                                    </div>
                                                </div>
                                                <div class="detail-item">
                                                    <i class="bi bi-people-fill detail-icon"></i>
                                                    <div class="detail-content">
                                                        <span class="detail-label">Edad Dirigida</span>
                                                        <span class="detail-value">{{ $curso->edad_dirigida ? ucfirst(strtolower($curso->edad_dirigida)) : 'N/A' }}</span>
                                                    </div>
                                                </div>
                                                <div class="detail-item">
                                                    <i class="bi bi-calendar-check-fill detail-icon"></i>
                                                    <div class="detail-content">
                                                        <span class="detail-label">Fecha Inicio</span>
                                                        <span class="detail-value">{{ $curso->fecha_ini ?? 'N/A' }}</span>
                                                    </div>
                                                </div>
                                                <div class="detail-item">
                                                    <i class="bi bi-calendar-x-fill detail-icon"></i>
                                                    <div class="detail-content">
                                                        <span class="detail-label">Fecha Fin</span>
                                                        <span class="detail-value">{{ $curso->fecha_fin ?? 'N/A' }}</span>
                                                    </div>
                                                </div>
                                                <div class="detail-item">
                                                    <i class="bi bi-display-fill detail-icon"></i>
                                                    <div class="detail-content">
                                                        <span class="detail-label">Formato</span>
                                                        <span class="detail-value">{{ $curso->formato ?? 'N/A' }}</span>
                                                    </div>
                                                </div>
                                                <div class="detail-item">
                                                    <i class="bi bi-tags-fill detail-icon"></i>
                                                    <div class="detail-content">
                                                        <span class="detail-label">Tipo</span>
                                                        <span class="detail-value">{{ ucfirst(strtolower($curso->tipo)) ?? 'N/A' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer-course">
                                            <button type="button" class="btn btn-modern btn-close-modal" data-bs-dismiss="modal">
                                                <i class="bi bi-x-circle me-2"></i>
                                                Cerrar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state-table">
                                        <div class="empty-icon-table">
                                            <i class="bi bi-inbox"></i>
                                        </div>
                                        <h5 class="empty-title-table">No hay cursos registrados</h5>
                                        <p class="empty-text-table">Comienza creando tu primer curso</p>
                                        <a href="{{ route('CrearCurso') }}" class="btn btn-modern btn-create">
                                            <i class="bi bi-plus-circle-fill me-2"></i>
                                            Crear Primer Curso
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // Búsqueda en tiempo real
        $('#searchInput').on('input', function() {
            let searchText = $(this).val().toLowerCase();
            $('tbody tr').each(function() {
                $(this).toggle($(this).text().toLowerCase().includes(searchText));
            });
        });

        // Confirmación de eliminación con SweetAlert2
        $('.btn-delete').on('click', function(event) {
            event.preventDefault();
            event.stopPropagation(); // Prevent row click event
            let url = $(this).attr('href');

            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción eliminará el curso permanentemente.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });

        // Prevent action buttons from triggering row click
        $('.btn-warning, .btn-info').on('click', function(event) {
            event.stopPropagation();
        });
    });
</script>


    @if (auth()->user()->hasRole('Estudiante'))
        @forelse ($inscritos as $inscrito)
            @if (auth()->user()->id == $inscrito->estudiante_id && $inscrito->cursos && $inscrito->cursos->deleted_at === null)
                <div class="w-full md:w-1/2 xl:w-1/3 p-3">

                    <a href="{{ route('Curso', encrypt($inscrito->cursos_id)) }}" class="block bg-white border rounded shadow p-2">
                        <div class="flex flex-row items-center">
                            <div class="flex-shrink pr-4">
                                <div class="rounded p-3 bg-blue-400"><i class="fa fa-bars fa-2x fa-fw fa-inverse"></i></div>
                            </div>
                            <div class="flex-1 text-right md:text-center">
                                <h3 class="atma text-3xl">{{ $inscrito->cursos->nombreCurso }} <span
                                        class="text-green-500"></span></h3>
                                <h5 class="alegreya uppercase"></h5>
                                <span class="inline-block mt-2">IR</span>
                            </div>
                        </div>
                    </a>
                </div>
            @endif
        @empty
            <h1>NO TIENES CURSOS ASIGNADOS</h1>
        @endforelse
    @endif

    @if (auth()->user()->hasRole('Docente'))
        @forelse ($cursos as $cursos)
            @if (auth()->user()->id == $cursos->docente_id)
                <div class="w-full md:w-1/2 xl:w-1/3 p-3">

                    <a href="{{ route('Curso', encrypt($cursos->id)) }}" class="block bg-white border rounded shadow p-2">
                        <div class="flex flex-row items-center">
                            <div class="flex-shrink pr-4">
                                <div class="rounded p-3 bg-blue-400"><i class="fa fa-bars fa-2x fa-fw fa-inverse"></i>
                                </div>
                            </div>
                            <div class="flex-1 text-right md:text-center">
                                <h3 class="atma text-3xl">{{ $cursos->nombreCurso }} <span class="text-green-500"></span>
                                </h3>
                                <h5 class="alegreya uppercase"></h5>
                                <span class="inline-block mt-2">IR</span>
                            </div>
                        </div>
                    </a>
                </div>
            @else
            @endif
        @empty
            <div class="card pb-3 pt-3 col-xl-12">
                <h4>NO TIENES CURSOS ASIGNADOS</h4>
            </div>
        @endforelse
    @endif



@endsection


@if (auth()->user()->hasRole('Docente') || auth()->user()->hasRole('Estudiante'))
    @include('FundacionPlantillaUsu.index')
@endif



@if (auth()->user()->hasRole('Administrador'))
    @include('layout')
@endif
