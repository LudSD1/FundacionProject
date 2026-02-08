@section('titulo')
    Lista de cursos
@endsection


@section('content')

    <style>
        :root {
            --color-primary: #1a4789;
            --color-secondary: #39a6cb;
            --color-accent: #63becf;
            --color-success: #28a745;
            --color-danger: #dc3545;
            --color-warning: #ffc107;
            --color-info: #17a2b8;
            --gradient-primary: linear-gradient(135deg, #1a4789 0%, #055c9d 100%);
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 8px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 8px 16px rgba(0, 0, 0, 0.15);
            --radius: 8px;
            --radius-sm: 6px;
        }

        .container {
            max-width: 1400px;
            padding: 1.25rem;
        }

        /* Card Modern */
        .card-modern {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
            border: none;
            overflow: hidden;
        }

        /* Header Compacto */
        .card-header-modern {
            background: var(--gradient-primary);
            padding: 1rem 1.25rem;
            border: none;
        }

        .action-buttons-header {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn-modern {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            white-space: nowrap;
        }

        .btn-create {
            background: white;
            color: var(--color-primary);
        }

        .btn-create:hover {
            background: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm);
        }

        .btn-deleted {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-deleted:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
        }

        /* Search Box Mejorado */
        .search-box-table {
            position: relative;
            width: 100%;
        }

        .search-icon-table {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.274);
            font-size: 1rem;
            z-index: 2;
        }

        .search-input-table {
            width: 100%;
            padding: 0.6rem 1rem 0.6rem 2.75rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: var(--radius-sm);
            background: rgba(255, 255, 255, 0.15);
            color: white;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .search-input-table::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .search-input-table:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.95);
            color: var(--color-primary);
            border-color: white;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.2);
        }

        .search-input-table:focus::placeholder {
            color: #999;
        }

    .search-input-table:focus + .search-icon-table {
            color: var(--color-primary);
        }

        /* Tabla Compacta */
        .table-container-modern {
            overflow-x: auto;
        }

        .table-modern {
            margin: 0;
            font-size: 0.875rem;
        }

        .table-modern thead {
            background: linear-gradient(to bottom, #f8f9fa, #e9ecef);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .table-modern th {
            padding: 0.75rem 0.5rem;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: var(--color-primary);
            white-space: nowrap;
            vertical-align: middle;
        }

        .th-content {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.85rem;
        }

        .th-content i {
            color: var(--color-accent);
            font-size: 0.9rem;
        }

        .table-modern td {
            padding: 0.65rem 0.5rem;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
        }

        .table-modern tbody tr {
            transition: all 0.2s ease;
        }

        .table-modern tbody tr:hover {
            background-color: rgba(57, 166, 203, 0.05);
            transform: scale(1.002);
        }

        /* Row Number */
        .row-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            background: var(--gradient-primary);
            color: white;
            border-radius: 50%;
            font-weight: 700;
            font-size: 0.75rem;
        }

        /* Course Name Cell */
        .course-name-cell {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .course-name-cell:hover {
            color: var(--color-primary);
        }

        .course-icon {
            color: var(--color-accent);
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .course-name {
            font-weight: 600;
            line-height: 1.3;
        }

        /* Teacher Cell */
        .teacher-cell {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            color: #495057;
        }

        .teacher-cell i {
            color: var(--color-accent);
            flex-shrink: 0;
        }

        /* Badges Compactos */
        .date-badge,
        .format-badge,
        .type-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.6rem;
            border-radius: var(--radius-sm);
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .date-badge {
            background: #e7f3ff;
            color: #0066cc;
        }

        .date-start {
            background: #d4edda;
            color: #155724;
        }

        .date-end {
            background: #fff3cd;
            color: #856404;
        }

        .format-badge {
            background: #e2e3e5;
            color: #383d41;
        }

        .type-badge {
            background: #cfe2ff;
            color: #084298;
        }

        .type-congreso {
            background: #f8d7da;
            color: #721c24;
        }

        /* Action Buttons */
        .action-buttons-cell {
            display: flex;
            gap: 0.35rem;
            justify-content: center;
        }

        .btn-action-modern {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: var(--radius-sm);
            border: none;
            transition: all 0.2s ease;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-edit {
            background: #fff3cd;
            color: #856404;
        }

        .btn-edit:hover {
            background: var(--color-warning);
            color: white;
            transform: translateY(-2px);
        }

        .btn-delete {
            background: #f8d7da;
            color: #721c24;
        }

        .btn-delete:hover {
            background: var(--color-danger);
            color: white;
            transform: translateY(-2px);
        }

        .btn-view {
            background: #d1ecf1;
            color: #0c5460;
        }

        .btn-view:hover {
            background: var(--color-info);
            color: white;
            transform: translateY(-2px);
        }

        /* Empty State */
        .empty-state-table {
            text-align: center;
            padding: 3rem 1.5rem;
        }

        .empty-icon-table {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 1rem;
        }

        .empty-title-table {
            color: #6c757d;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .empty-text-table {
            color: #adb5bd;
            margin-bottom: 1.5rem;
        }

        /* Modal Mejorado */
        .modal-modern {
            border-radius: var(--radius);
            overflow: hidden;
            border: none;
        }

        .modal-header-course {
            background: var(--gradient-primary);
            color: white;
            padding: 1.25rem 1.5rem;
            border: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title-wrapper {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .modal-icon-course {
            font-size: 1.5rem;
        }

        .modal-title {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .btn-close-modern-course {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-close-modern-course:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        .modal-body-course {
            padding: 1.5rem;
        }

        .course-details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .detail-item {
            display: flex;
            gap: 0.75rem;
            padding: 0.875rem;
            background: #f8f9fa;
            border-radius: var(--radius-sm);
            transition: all 0.2s ease;
        }

        .detail-item:hover {
            background: #e9ecef;
            transform: translateX(3px);
        }

        .detail-icon {
            font-size: 1.5rem;
            color: var(--color-accent);
            flex-shrink: 0;
        }

        .detail-content {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .detail-label {
            font-size: 0.75rem;
            color: #6c757d;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-value {
            font-size: 0.95rem;
            color: #212529;
            font-weight: 500;
        }

        .modal-footer-course {
            padding: 1rem 1.5rem;
            border-top: 1px solid #dee2e6;
            display: flex;
            justify-content: flex-end;
        }

        .btn-close-modal {
            background: #6c757d;
            color: white;
        }

        .btn-close-modal:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 0.75rem;
            }

            .card-header-modern {
                padding: 0.875rem;
            }

            .action-buttons-header {
                width: 100%;
            }

            .btn-modern {
                font-size: 0.8rem;
                padding: 0.45rem 0.75rem;
            }

            .table-modern {
                font-size: 0.8rem;
            }

            .table-modern th,
            .table-modern td {
                padding: 0.5rem 0.35rem;
            }

            .course-name {
                font-size: 0.85rem;
            }

            .btn-action-modern {
                width: 28px;
                height: 28px;
                font-size: 0.8rem;
            }

            .course-details-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

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
                        <!-- Tabs Navigation -->
                        <ul class="nav nav-pills justify-content-end" id="courseTypeTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="cursos-tab" data-bs-toggle="pill"
                                    data-bs-target="#cursos-content" type="button" role="tab"
                                    aria-controls="cursos-content" aria-selected="true">
                                    <i class="bi bi-mortarboard-fill me-2"></i>Cursos
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="eventos-tab" data-bs-toggle="pill"
                                    data-bs-target="#eventos-content" type="button" role="tab"
                                    aria-controls="eventos-content" aria-selected="false">
                                    <i class="bi bi-calendar-event-fill me-2"></i>Eventos
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="tab-content" id="courseTypeTabContent">
                <!-- Cursos Tab -->
                <div class="tab-pane fade show active" id="cursos-content" role="tabpanel" aria-labelledby="cursos-tab">
                    <div class="p-3">
                        <div class="search-box-table mb-3">
                            <i class="bi bi-search search-icon-table"></i>
                            <input type="text" class="form-control search-input-table"
                                placeholder="Buscar cursos por nombre, docente..." id="searchInputCursos">
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
                                        <th width="25%">
                                            <div class="th-content">
                                                <i class="bi bi-book-fill"></i>
                                                <span>Curso</span>
                                            </div>
                                        </th>
                                        <th width="18%">
                                            <div class="th-content">
                                                <i class="bi bi-person-fill"></i>
                                                <span>Docente</span>
                                            </div>
                                        </th>
                                        <th width="12%">
                                            <div class="th-content">
                                                <i class="bi bi-calendar-check"></i>
                                                <span>Inicio</span>
                                            </div>
                                        </th>
                                        <th width="12%">
                                            <div class="th-content">
                                                <i class="bi bi-calendar-x"></i>
                                                <span>Fin</span>
                                            </div>
                                        </th>
                                        <th width="10%">
                                            <div class="th-content">
                                                <i class="bi bi-display"></i>
                                                <span>Formato</span>
                                            </div>
                                        </th>
                                        <th width="8%">
                                            <div class="th-content">
                                                <i class="bi bi-tags-fill"></i>
                                                <span>Tipo</span>
                                            </div>
                                        </th>
                                        <th width="10%" class="text-center">
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
                                                <div class="course-name-cell" data-bs-toggle="modal"
                                                    data-bs-target="#courseModal{{ $curso->id }}">
                                                    <i class="bi bi-journal-bookmark-fill course-icon"></i>
                                                    <span
                                                        class="course-name">{{ ucfirst(strtolower($curso->nombreCurso)) }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="teacher-cell">
                                                    <i class="bi bi-person-badge"></i>
                                                    <span>{{ $curso->docente ? $curso->docente->name . ' ' . $curso->docente->lastname1 : 'N/A' }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="date-badge date-start">
                                                    <i class="bi bi-calendar-event me-1"></i>
                                                    {{ $curso->fecha_ini ? \Carbon\Carbon::parse($curso->fecha_ini)->format('d/m/Y') : 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="date-badge date-end">
                                                    <i class="bi bi-calendar-event me-1"></i>
                                                    {{ $curso->fecha_fin ? \Carbon\Carbon::parse($curso->fecha_fin)->format('d/m/Y') : 'N/A' }}
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
                                                    <i
                                                        class="bi bi-{{ $curso->tipo == 'congreso' ? 'calendar-event' : 'mortarboard' }}-fill me-1"></i>
                                                    {{ ucfirst(strtolower($curso->tipo)) ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="action-buttons-cell">
                                                    <a class="btn-action-modern btn-edit"
                                                        href="{{ route('editarCurso', encrypt($curso->id)) }}"
                                                        data-bs-toggle="tooltip" title="Editar curso">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <a class="btn-action-modern btn-delete"
                                                        href="{{ route('quitarCurso', [encrypt($curso->id)]) }}"
                                                        data-bs-toggle="tooltip" title="Eliminar curso">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </a>
                                                    <a class="btn-action-modern btn-view"
                                                        href="{{ route('Curso', $curso->codigoCurso) }}"
                                                        data-bs-toggle="tooltip" title="Ver detalles">
                                                        <i class="bi bi-eye-fill"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8">
                                                <div class="empty-state-table">
                                                    <div class="empty-icon-table">
                                                        <i class="bi bi-inbox"></i>
                                                    </div>
                                                    <h5 class="empty-title-table">No hay cursos registrados</h5>
                                                    <p class="empty-text-table">Comienza creando tu primer curso</p>
                                                    <a href="{{ route('CrearCurso') }}"
                                                        class="btn btn-modern btn-create">
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

            <!-- Modales -->
            @foreach ($cursos as $curso)
                <div class="modal fade" id="courseModal{{ $curso->id }}" tabindex="-1"
                    aria-labelledby="courseModalLabel{{ $curso->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content modal-modern">
                            <div class="modal-header-course">
                                <div class="modal-title-wrapper">
                                    <i class="bi bi-book-half modal-icon-course"></i>
                                    <h5 class="modal-title" id="courseModalLabel{{ $curso->id }}">
                                        Detalles del Curso
                                    </h5>
                                </div>
                                <button type="button" class="btn-close-modern-course" data-bs-dismiss="modal"
                                    aria-label="Cerrar">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                            <div class="modal-body-course">
                                <div class="course-details-grid">
                                    <div class="detail-item">
                                        <i class="bi bi-bookmark-star-fill detail-icon"></i>
                                        <div class="detail-content">
                                            <span class="detail-label">Nombre</span>
                                            <span
                                                class="detail-value">{{ ucfirst(strtolower($curso->nombreCurso)) }}</span>
                                        </div>
                                    </div>
                                    <div class="detail-item">
                                        <i class="bi bi-bar-chart-fill detail-icon"></i>
                                        <div class="detail-content">
                                            <span class="detail-label">Nivel</span>
                                            <span
                                                class="detail-value">{{ $curso->nivel ? ucfirst(strtolower($curso->nivel)) : 'N/A' }}</span>
                                        </div>
                                    </div>
                                    <div class="detail-item">
                                        <i class="bi bi-person-badge-fill detail-icon"></i>
                                        <div class="detail-content">
                                            <span class="detail-label">Instructor</span>
                                            <span
                                                class="detail-value">{{ $curso->docente ? $curso->docente->name . ' ' . $curso->docente->lastname1 . ' ' . $curso->docente->lastname2 : 'N/A' }}</span>
                                        </div>
                                    </div>
                                    <div class="detail-item">
                                        <i class="bi bi-people-fill detail-icon"></i>
                                        <div class="detail-content">
                                            <span class="detail-label">Edad Dirigida</span>
                                            <span
                                                class="detail-value">{{ $curso->edad_dirigida ? ucfirst(strtolower($curso->edad_dirigida)) : 'N/A' }}</span>
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
                                            <span
                                                class="detail-value">{{ ucfirst(strtolower($curso->tipo)) ?? 'N/A' }}</span>
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
            @endforeach

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

                            <a href="{{ route('Curso', encrypt($inscrito->cursos_id)) }}"
                                class="block bg-white border rounded shadow p-2">
                                <div class="flex flex-row items-center">
                                    <div class="flex-shrink pr-4">
                                        <div class="rounded p-3 bg-blue-400"><i
                                                class="fa fa-bars fa-2x fa-fw fa-inverse"></i>
                                        </div>
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

                            <a href="{{ route('Curso', encrypt($cursos->id)) }}"
                                class="block bg-white border rounded shadow p-2">
                                <div class="flex flex-row items-center">
                                    <div class="flex-shrink pr-4">
                                        <div class="rounded p-3 bg-blue-400"><i
                                                class="fa fa-bars fa-2x fa-fw fa-inverse"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 text-right md:text-center">
                                        <h3 class="atma text-3xl">{{ $cursos->nombreCurso }} <span
                                                class="text-green-500"></span>
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
