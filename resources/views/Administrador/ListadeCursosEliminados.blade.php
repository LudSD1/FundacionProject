@section('titulo')
    Área Personal
@endsection


@section('content')
    <div class="container-fluid py-5">
        {{-- Estructura tbl-card moderna --}}
        <div class="tbl-card">
            {{-- Cabecera con lenguaje visual moderno --}}
            <div class="tbl-card-hero">
                <div class="tbl-hero-left">
                    <div class="tbl-hero-eyebrow">
                        <i class="fas fa-trash-alt"></i> Papelera de Reciclaje
                    </div>
                    <h2 class="tbl-hero-title">Cursos Eliminados</h2>
                    <p class="tbl-hero-sub">Gestione y restaure los cursos que han sido retirados del sistema</p>
                </div>
                <div class="tbl-hero-controls">
                    <a href="{{ route('ListaCursos') }}" class="tbl-hero-btn tbl-hero-btn-glass">
                        <i class="fas fa-book"></i> Ver Cursos Activos
                    </a>
                </div>
            </div>

            <div class="card-body p-4">
                {{-- Barra de búsqueda --}}
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="search-box-table w-100">
                            <i class="fas fa-search search-icon-table"></i>
                            <input type="text" id="searchInput" class="search-input-table"
                                placeholder="Buscar curso por nombre, docente o tipo…">
                            <span class="search-indicator"></span>
                        </div>
                    </div>
                </div>

                <div class="table-container-modern">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th style="width:48px">
                                    <div class="th-content">#</div>
                                </th>
                                <th>
                                    <div class="th-content">Información del Curso</div>
                                </th>
                                <th>
                                    <div class="th-content">Docente Responsable</div>
                                </th>
                                <th>
                                    <div class="th-content">Periodo</div>
                                </th>
                                <th>
                                    <div class="th-content">Formato / Tipo</div>
                                </th>
                                <th class="text-center">
                                    <div class="th-content text-center w-100">Acciones</div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cursos as $curso)
                                <tr class="opacity-75 bg-light">
                                    <td><span class="row-number">#{{ $loop->iteration }}</span></td>

                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-secondary bg-opacity-10 rounded-3 p-2 me-3 text-secondary">
                                                <i class="fas fa-book"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">
                                                    {{ ucfirst(strtolower($curso->nombreCurso)) }}</div>
                                                <code class="text-muted" style="font-size: 0.7rem;">ID:
                                                    {{ $curso->id }}</code>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="teacher-cell">
                                            <i class="fas fa-user-tie"></i>
                                            <span>{{ $curso->docente ? $curso->docente->name . ' ' . $curso->docente->lastname1 : 'No asignado' }}</span>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            <div class="date-badge date-start">
                                                <i class="fas fa-calendar-check me-1"></i> {{ $curso->fecha_ini ?? 'N/A' }}
                                            </div>
                                            <div class="date-badge date-end">
                                                <i class="fas fa-calendar-times me-1"></i> {{ $curso->fecha_fin ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            <span class="format-badge">
                                                <i class="fas fa- chalkboard-teacher me-1"></i>
                                                {{ $curso->formato ?? 'N/A' }}
                                            </span>
                                            <span class="type-badge type-{{ strtolower($curso->tipo ?? 'curso') }}">
                                                <i
                                                    class="fas fa-{{ $curso->tipo == 'congreso' ? 'calendar-day' : 'graduation-cap' }} me-1"></i>
                                                {{ ucfirst(strtolower($curso->tipo)) ?? 'Curso' }}
                                            </span>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <button class="btn btn-sm btn-outline-info rounded-pill px-3"
                                                data-bs-toggle="modal" data-bs-target="#courseModal{{ $curso->id }}"
                                                title="Detalles">
                                                <i class="fas fa-info-circle me-1"></i> Detalles
                                            </button>
                                            <a href="{{ route('restaurarCurso', [encrypt($curso->id)]) }}"
                                                class="btn btn-sm btn-outline-success rounded-pill px-3"
                                                onclick="return confirm('¿Desea restaurar este curso?')" title="Restaurar">
                                                <i class="fas fa-undo me-1"></i> Restaurar
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                {{-- El modal se mantiene pero actualizado con estilos modernos si es necesario --}}
                                <div class="modal fade" id="courseModal{{ $curso->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 rounded-4 shadow overflow-hidden">
                                            <div class="modal-header border-0 bg-secondary text-white py-3">
                                                <h5 class="modal-title fw-bold">
                                                    <i class="fas fa-info-circle me-2"></i> Detalles del Curso (Eliminado)
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <div class="row g-3">
                                                    <div class="col-12">
                                                        <div class="bg-light p-3 rounded-3">
                                                            <small class="text-muted d-block text-uppercase fw-bold"
                                                                style="font-size: 0.65rem;">Nombre del Curso</small>
                                                            <span
                                                                class="fw-bold text-dark">{{ $curso->nombreCurso }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="bg-light p-3 rounded-3">
                                                            <small class="text-muted d-block text-uppercase fw-bold"
                                                                style="font-size: 0.65rem;">Nivel</small>
                                                            <span>{{ $curso->nivel ?? 'N/A' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="bg-light p-3 rounded-3">
                                                            <small class="text-muted d-block text-uppercase fw-bold"
                                                                style="font-size: 0.65rem;">Formato</small>
                                                            <span>{{ $curso->formato ?? 'N/A' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="bg-light p-3 rounded-3">
                                                            <small class="text-muted d-block text-uppercase fw-bold"
                                                                style="font-size: 0.65rem;">Docente</small>
                                                            <span>{{ $curso->docente ? $curso->docente->name . ' ' . $curso->docente->lastname1 : 'N/A' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 bg-light p-3">
                                                <button type="button" class="btn btn-secondary rounded-pill px-4"
                                                    data-bs-dismiss="modal">Cerrar</button>
                                                <a href="{{ route('restaurarCurso', [encrypt($curso->id)]) }}"
                                                    class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">
                                                    <i class="fas fa-undo me-2"></i> Restaurar Ahora
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-check-circle fa-3x mb-3 opacity-25"></i>
                                            <h5 class="fw-bold">No hay cursos eliminados</h5>
                                            <p class="small">Todo está en orden. No hay elementos en la papelera.</p>
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
@empty
    <tr>
        <td colspan="10" class="text-center">
            <div class="empty-state-table">
                <div class="empty-icon-table">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <h5 class="empty-title-table">No hay cursos cerrados</h5>
                <p class="empty-text-table">Puedes crear un nuevo curso cuando lo necesites</p>
                <a href="{{ route('CrearCurso') }}" class="btn btn-modern btn-create">
                    <i class="bi bi-plus-circle-fill me-2"></i>
                    Crear Curso
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
@endsection

@if (auth()->user()->hasRole('Docente') || auth()->user()->hasRole('Estudiante'))
    @include('FundacionPlantillaUsu.index')
@endif



@if (auth()->user()->hasRole('Administrador'))
    @include('layout')
@endif

<!-- Scripts para búsqueda y tooltips -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Búsqueda en tiempo real
        const input = document.getElementById('searchInput');
        if (input) {
            input.addEventListener('input', function() {
                const q = input.value.toLowerCase();
                document.querySelectorAll('tbody tr').forEach(function(row) {
                    row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
                });
            });
        }

        // Inicializar tooltips de Bootstrap 5
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function(tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
