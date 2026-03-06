@section('titulo')
    Lista de cursos
@endsection



@section('content')
<div class="container my-4">
    <div class="card card-modern">

        {{-- ── Header ── --}}
        <div class="card-header-modern">
            <div class="row align-items-center g-3">

                {{-- Botones Administrador --}}
                @hasrole('Administrador')
                <div class="col-lg-4 col-md-12">
                    <div class="action-buttons-header">
                        <a href="{{ route('CrearCurso') }}" class="btn btn-modern btn-create">
                            <i class="bi bi-plus-circle-fill me-2"></i>
                            <span>Crear Curso</span>
                        </a>
                        <a href="{{ route('ListadeCursosEliminados') }}" class="btn btn-modern btn-deleted">
                            <i class="bi bi-trash-fill me-2"></i>
                            <span>Eliminados</span>
                        </a>
                    </div>
                </div>

                {{-- Filtro tipo --}}
                <div class="col-lg-4 col-md-6">
                    <form action="{{ route('ListadeCursos') }}" method="GET" id="tipoFilterForm">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <select name="tipo" class="form-select search-input-table"
                            onchange="document.getElementById('tipoFilterForm').submit()"
                            style="padding-left: 1rem;">
                            <option value="">📋 Todos los tipos</option>
                            <option value="curso"    {{ request('tipo') == 'curso'    ? 'selected' : '' }}>🎓 Cursos</option>
                            <option value="congreso" {{ request('tipo') == 'congreso' ? 'selected' : '' }}>📅 Evento</option>
                        </select>
                    </form>
                </div>

                

                {{-- Buscador --}}
                <div class="col-lg-4 col-md-6">
                    <form action="{{ route('ListadeCursos') }}" method="GET" class="w-100">
                        <input type="hidden" name="tipo" value="{{ request('tipo') }}">
                        <div class="search-box-table">
                            <i class="bi bi-search search-icon-table"></i>
                            <input type="text"
                                   class="form-control search-input-table"
                                   placeholder="Buscar curso, docente..."
                                   name="search"
                                   value="{{ request('search') }}">
                            <div class="search-indicator"></div>
                        </div>
                    </form>
                </div>
                @endrole

                {{-- Solo buscador para Docente/Estudiante --}}
                @unless(auth()->user()->hasRole('Administrador'))
                <div class="col-12">
                    <div class="search-box-table">
                        <i class="bi bi-search search-icon-table"></i>
                        <input type="text"
                               class="form-control search-input-table"
                               placeholder="Buscar curso..."
                               id="searchInputCursos">
                        <div class="search-indicator"></div>
                    </div>
                </div>
                @endunless

            </div>
        </div>

        {{-- ── Alerta filtros activos (Admin) ── --}}
        @hasrole('Administrador')
        @if(request('search') || request('tipo'))
            <div class="alert alert-info mx-3 mt-3 d-flex justify-content-between align-items-center">
                <span>
                    @if(request('search'))
                        Búsqueda: <strong>{{ request('search') }}</strong>
                    @endif
                    @if(request('tipo'))
                        &nbsp; Tipo: <strong class="badge bg-primary">{{ ucfirst(request('tipo')) }}</strong>
                    @endif
                    &nbsp;— <strong>{{ $cursos->total() }}</strong> resultado(s)
                </span>
                <a href="{{ route('ListadeCursos') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-x-circle me-1"></i> Limpiar filtros
                </a>
            </div>
        @endif
        @endrole

        {{-- ══════════════════════════════════════
             VISTA ADMINISTRADOR
        ══════════════════════════════════════ --}}
        @hasrole('Administrador')
        <div class="table-responsive table-container-modern">
            <table class="table table-modern align-middle">
                <thead>
                    <tr>
                        <th width="4%">
                            <div class="th-content"><i class="bi bi-hash"></i><span>Nº</span></div>
                        </th>
                        <th width="22%">
                            <div class="th-content"><i class="bi bi-book-fill"></i><span>Curso</span></div>
                        </th>
                        <th width="16%">
                            <div class="th-content"><i class="bi bi-person-fill"></i><span>Docente</span></div>
                        </th>
                        <th width="11%">
                            <div class="th-content"><i class="bi bi-calendar-check"></i><span>Inicio</span></div>
                        </th>
                        <th width="11%">
                            <div class="th-content"><i class="bi bi-calendar-x"></i><span>Fin</span></div>
                        </th>
                        <th width="10%">
                            <div class="th-content"><i class="bi bi-display"></i><span>Formato</span></div>
                        </th>
                        <th width="8%">
                            <div class="th-content"><i class="bi bi-tags-fill"></i><span>Tipo</span></div>
                        </th>
                        <th width="18%" class="text-center">
                            <div class="th-content justify-content-center">
                                <i class="bi bi-gear-fill"></i><span>Acciones</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cursos as $curso)
                        <tr class="curso-row" data-course-id="{{ $curso->id }}">
                            <td><span class="row-number">{{ $loop->iteration }}</span></td>
                            <td>
                                <div class="course-name-cell" style="cursor:pointer"
                                    data-bs-toggle="modal"
                                    data-bs-target="#courseModal{{ $curso->id }}">
                                    <i class="bi bi-journal-bookmark-fill course-icon"></i>
                                    <span class="course-name">{{ ucfirst(strtolower($curso->nombreCurso)) }}</span>
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
                                    <i class="bi bi-{{ $curso->tipo == 'congreso' ? 'calendar-event' : 'mortarboard' }}-fill me-1"></i>
                                    {{ ucfirst(strtolower($curso->tipo ?? 'N/A')) == 'Congreso' ? 'Evento' : ucfirst(strtolower($curso->tipo ?? 'N/A')) }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons-cell">
                                    <a class="btn-action-modern btn-edit"
                                        href="{{ route('editarCurso', encrypt($curso->id)) }}"
                                        data-bs-toggle="tooltip" title="Editar">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a class="btn-action-modern btn-delete"
                                        href="{{ route('quitarCurso', [encrypt($curso->id)]) }}"
                                        data-bs-toggle="tooltip" title="Eliminar">
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
                                    <div class="empty-icon-table"><i class="bi bi-inbox"></i></div>
                                    <h5 class="empty-title-table">No hay cursos registrados</h5>
                                    <p class="empty-text-table">
                                        @if(request('tipo'))
                                            No se encontraron <strong>{{ ucfirst(request('tipo')) }}s</strong>.
                                        @else
                                            Comienza creando tu primer curso.
                                        @endif
                                    </p>
                                    <a href="{{ route('CrearCurso') }}" class="btn btn-modern btn-create">
                                        <i class="bi bi-plus-circle-fill me-2"></i>Crear Primer Curso
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación Admin --}}
        <div class="d-flex justify-content-center mt-3 mb-3">
            {{ $cursos->appends(['search' => request('search'), 'tipo' => request('tipo')])->links('custom-pagination') }}
        </div>
        @endrole

        {{-- ══════════════════════════════════════
             VISTA ESTUDIANTE
        ══════════════════════════════════════ --}}
        @hasrole('Estudiante')
        <div class="p-3">
            <div class="row g-3" id="cursosEstudiante">
                @forelse ($inscritos as $inscrito)
                    @if(auth()->user()->id == $inscrito->estudiante_id && $inscrito->cursos && $inscrito->cursos->deleted_at === null)
                        <div class="col-lg-4 col-md-6 col-12 curso-card-item">
                            <a href="{{ route('Curso', $inscrito->cursos_id) }}" class="text-decoration-none">
                                <div class="curso-card-rol">
                                    <div class="curso-card-icon-wrap">
                                        <i class="bi bi-journal-bookmark-fill"></i>
                                    </div>
                                    <div class="curso-card-body">
                                        <h6 class="curso-card-title">
                                            {{ ucfirst(strtolower($inscrito->cursos->nombreCurso)) }}
                                        </h6>
                                        <span class="curso-card-go">
                                            Ver curso <i class="bi bi-arrow-right ms-1"></i>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                @empty
                    <div class="col-12">
                        <div class="empty-state-table">
                            <div class="empty-icon-table"><i class="bi bi-journal-x"></i></div>
                            <h5 class="empty-title-table">No tienes cursos asignados</h5>
                            <p class="empty-text-table">Contacta a tu administrador para inscribirte.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
        @endrole

        {{-- ══════════════════════════════════════
             VISTA DOCENTE
        ══════════════════════════════════════ --}}
        @hasrole('Docente')
        <div class="p-3">
            <div class="row g-3" id="cursosDocente">
                @forelse ($cursos as $curso)
                    @if(auth()->user()->id == $curso->docente_id)
                        <div class="col-lg-4 col-md-6 col-12 curso-card-item">
                            <a href="{{ route('Curso', $curso->codigoCurso) }}" class="text-decoration-none">
                                <div class="curso-card-rol curso-card-docente">
                                    <div class="curso-card-icon-wrap">
                                        <i class="bi bi-mortarboard-fill"></i>
                                    </div>
                                    <div class="curso-card-body">
                                        <h6 class="curso-card-title">
                                            {{ ucfirst(strtolower($curso->nombreCurso)) }}
                                        </h6>
                                        <span class="curso-card-meta">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            {{ $curso->fecha_ini ? \Carbon\Carbon::parse($curso->fecha_ini)->format('d/m/Y') : 'Sin fecha' }}
                                        </span>
                                        <span class="curso-card-go">
                                            Ir al curso <i class="bi bi-arrow-right ms-1"></i>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                @empty
                    <div class="col-12">
                        <div class="empty-state-table">
                            <div class="empty-icon-table"><i class="bi bi-journal-x"></i></div>
                            <h5 class="empty-title-table">No tienes cursos asignados</h5>
                            <p class="empty-text-table">Aún no tienes cursos asignados como docente.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
        @endrole

    </div>{{-- fin card-modern --}}
</div>

{{-- ── Modales (Admin) ── --}}
@hasrole('Administrador')
@foreach ($cursos as $curso)
    <div class="modal fade" id="courseModal{{ $curso->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content modal-modern">
                <div class="modal-header-course">
                    <div class="modal-title-wrapper">
                        <i class="bi bi-book-half modal-icon-course"></i>
                        <h5 class="modal-title">Detalles del Curso</h5>
                    </div>
                    <button type="button" class="btn-close-modern-course" data-bs-dismiss="modal">
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
                                <span class="detail-value">{{ ucfirst(strtolower($curso->tipo ?? 'N/A')) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer-course">
                    <button type="button" class="btn btn-modern btn-close-modal" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endrole

{{-- ── Estilos cards Docente/Estudiante ── --}}
<style>
    .curso-card-rol {
        display: flex;
        align-items: center;
        gap: 1rem;
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.1rem 1.2rem;
        box-shadow: 0 2px 8px rgba(79,70,229,0.07);
        transition: all 0.22s cubic-bezier(.4,0,.2,1);
    }
    .curso-card-rol:hover {
        border-color: #4f46e5;
        box-shadow: 0 6px 20px rgba(79,70,229,0.15);
        transform: translateY(-3px);
    }
    .curso-card-docente:hover {
        border-color: #10b981;
        box-shadow: 0 6px 20px rgba(16,185,129,0.15);
    }
    .curso-card-icon-wrap {
        flex-shrink: 0;
        width: 48px; height: 48px;
        background: #eef2ff;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        color: #4f46e5;
        transition: all 0.22s;
    }
    .curso-card-docente .curso-card-icon-wrap {
        background: #ecfdf5;
        color: #10b981;
    }
    .curso-card-rol:hover .curso-card-icon-wrap { transform: scale(1.1); }
    .curso-card-body {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
        min-width: 0;
    }
    .curso-card-title {
        font-size: 0.92rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .curso-card-meta { font-size: 0.78rem; color: #64748b; }
    .curso-card-go   { font-size: 0.78rem; font-weight: 600; color: #4f46e5; }
    .curso-card-docente .curso-card-go { color: #10b981; }
</style>

{{-- ── Scripts ── --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Búsqueda client-side para Docente y Estudiante
    const searchLocal = document.getElementById('searchInputCursos');
    if (searchLocal) {
        searchLocal.addEventListener('input', function () {
            const text = this.value.toLowerCase();
            document.querySelectorAll('.curso-card-item').forEach(card => {
                card.style.display = card.textContent.toLowerCase().includes(text) ? '' : 'none';
            });
        });
    }

    // Confirmación eliminar (Admin)
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            const url = this.getAttribute('href');
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción eliminará el curso.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6366f1',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then(result => {
                if (result.isConfirmed) window.location.href = url;
            });
        });
    });

    // Tooltips Bootstrap
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el);
    });
});
</script>

@endsection

@include('layout')

