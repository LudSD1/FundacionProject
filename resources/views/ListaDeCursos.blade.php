{{-- ═══════════════════════════════════════════════════════════════
     LISTADO DE CURSOS — con nuevo sistema tbl-card
     Usa:
       cards.css            → animaciones, tokens base
       table.css            → table-modern, table-container-modern,
                              th-content, row-number
       table-complement.css → btn-action-modern, type-badge, date-badge,
                              course-name-cell, empty-state-table, etc.
       table-card.css       → tbl-card, tbl-card-hero, tbl-hero-*
                              (reemplaza card-modern + card-header-modern)
═══════════════════════════════════════════════════════════════ --}}

@section('titulo') Lista de Cursos @endsection

@section('content')
<div class="container my-4">

    <div class="tbl-card">

        {{-- ╔══════════════════════════════════════╗
             ║  HERO — CABECERA AZUL               ║
             ╚══════════════════════════════════════╝ --}}
        <div class="tbl-card-hero">

            {{-- Izquierda: título --}}
            <div class="tbl-hero-left">
                <div class="tbl-hero-eyebrow">
                    <i class="bi bi-book-fill"></i>
                    @hasrole('Administrador') Gestión @endrole
                    @hasrole('Docente')       Mis Cursos @endrole
                    @hasrole('Estudiante')    Mi Aprendizaje @endrole
                </div>
                <h2 class="tbl-hero-title">Lista de Cursos</h2>
                <p class="tbl-hero-sub">
                    @hasrole('Administrador') Administra todos los cursos y congresos @endrole
                    @hasrole('Docente')       Cursos que impartes @endrole
                    @hasrole('Estudiante')    Tus cursos inscritos @endrole
                </p>
            </div>

            {{-- Derecha: controles --}}
            <div class="tbl-hero-controls">

                @hasrole('Administrador')
                {{-- Botones Admin --}}
                <a href="{{ route('CrearCurso') }}" class="tbl-hero-btn tbl-hero-btn-primary">
                    <i class="bi bi-plus-circle-fill"></i> Crear Curso
                </a>
                <a href="{{ route('ListadeCursosEliminados') }}" class="tbl-hero-btn tbl-hero-btn-glass">
                    <i class="bi bi-trash-fill"></i> Eliminados
                </a>

                {{-- Filtro tipo --}}
                <form action="{{ route('ListadeCursos') }}" method="GET" id="tipoFilterForm">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <div class="tbl-hero-select-wrap">
                        <i class="bi bi-funnel-fill tbl-hero-select-icon"></i>
                        <select name="tipo" class="tbl-hero-select" id="tipoFilterSelect">
                            <option value="">Todos los tipos</option>
                            <option value="curso"    {{ request('tipo') == 'curso'    ? 'selected' : '' }}>Cursos</option>
                            <option value="congreso" {{ request('tipo') == 'congreso' ? 'selected' : '' }}>Congresos</option>
                            <option value="evento"   {{ request('tipo') == 'evento'   ? 'selected' : '' }}>Eventos</option>
                        </select>
                    </div>
                </form>

                {{-- Buscador Admin (POST) --}}
                <form action="{{ route('ListadeCursos') }}" method="GET">
                    <input type="hidden" name="tipo" value="{{ request('tipo') }}">
                    <div class="tbl-hero-search">
                        <i class="bi bi-search tbl-hero-search-icon"></i>
                        <input type="text"
                               class="tbl-hero-search-input"
                               placeholder="Buscar curso, docente..."
                               name="search"
                               value="{{ request('search') }}">
                    </div>
                </form>
                @endrole

                @unless(auth()->user()->hasRole('Administrador'))
                {{-- Buscador client-side para Docente/Estudiante --}}
                <div class="tbl-hero-search">
                    <i class="bi bi-search tbl-hero-search-icon"></i>
                    <input type="text"
                           class="tbl-hero-search-input"
                           placeholder="Buscar curso..."
                           id="searchInputCursos">
                </div>
                @endunless

            </div>
        </div>{{-- /tbl-card-hero --}}


        {{-- Barra de filtros activos (solo Admin) --}}
        @hasrole('Administrador')
        @if(request('search') || request('tipo'))
        <div class="tbl-filter-bar">
            <div class="tbl-filter-bar-left">
                <i class="bi bi-funnel-fill"></i>
                @if(request('search'))
                    Búsqueda: <strong>{{ request('search') }}</strong>
                @endif
                @if(request('tipo'))
                    @if(request('search')) · @endif
                    Tipo: <span class="tbl-filter-chip">{{ ucfirst(request('tipo')) }}</span>
                @endif
                — <strong>{{ $cursos->total() }}</strong> resultado(s)
            </div>
            <a href="{{ route('ListadeCursos') }}" class="tbl-filter-clear">
                <i class="bi bi-x-circle"></i> Limpiar filtros
            </a>
        </div>
        @endif
        @endrole


        {{-- ╔══════════════════════════════════════╗
             ║  TABLA ADMINISTRADOR                ║
             ╚══════════════════════════════════════╝ --}}
        @hasrole('Administrador')
        <div class="table-container-modern">
            <table class="table-modern">
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
                    @forelse($cursos as $curso)
                    @php
                        $tipoSlug  = strtolower($curso->tipo ?? 'curso');
                        $tipoLabel = $tipoSlug === 'congreso' ? 'Evento' : ucfirst($tipoSlug);
                        $tipoIcon  = $tipoSlug === 'congreso'
                            ? 'bi-calendar-event-fill'
                            : 'bi-mortarboard-fill';
                    @endphp
                    <tr data-course-id="{{ $curso->id }}">

                        <td><span class="row-number">{{ $loop->iteration }}</span></td>

                        <td>
                            <div class="course-name-cell"
                                 data-bs-toggle="modal"
                                 data-bs-target="#courseModal{{ $curso->id }}">
                                <i class="bi bi-journal-bookmark-fill course-icon"></i>
                                <span class="course-name">
                                    {{ ucfirst(strtolower($curso->nombreCurso)) }}
                                </span>
                            </div>
                        </td>

                        <td>
                            <div class="teacher-cell">
                                <i class="bi bi-person-badge"></i>
                                <span>{{ $curso->docente
                                    ? $curso->docente->name.' '.$curso->docente->lastname1
                                    : 'N/A' }}</span>
                            </div>
                        </td>

                        <td>
                            <span class="date-badge date-start">
                                <i class="bi bi-calendar-event"></i>
                                {{ $curso->fecha_ini
                                    ? \Carbon\Carbon::parse($curso->fecha_ini)->format('d/m/Y')
                                    : 'N/A' }}
                            </span>
                        </td>

                        <td>
                            <span class="date-badge date-end">
                                <i class="bi bi-calendar-event"></i>
                                {{ $curso->fecha_fin
                                    ? \Carbon\Carbon::parse($curso->fecha_fin)->format('d/m/Y')
                                    : 'N/A' }}
                            </span>
                        </td>

                        <td>
                            <span class="format-badge">
                                <i class="bi bi-laptop"></i>
                                {{ $curso->formato ?? 'N/A' }}
                            </span>
                        </td>

                        <td>
                            <span class="type-badge type-{{ $tipoSlug }}">
                                <i class="bi {{ $tipoIcon }}"></i>
                                {{ $tipoLabel }}
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
                                   href="{{ route('quitarCurso', encrypt($curso->id)) }}"
                                   data-bs-toggle="tooltip" title="Eliminar">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                                <a class="btn-action-modern btn-view"
                                   href="{{ route('Curso', $curso->codigoCurso ?? $curso->id) }}"
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
                                <a href="{{ route('CrearCurso') }}"
                                   class="tbl-hero-btn tbl-hero-btn-primary" style="width:auto">
                                    <i class="bi bi-plus-circle-fill"></i> Crear Primer Curso
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="tbl-pagination">
            {{ $cursos->appends(['search' => request('search'), 'tipo' => request('tipo')])->links('custom-pagination') }}
        </div>
        @endrole


        {{-- ╔══════════════════════════════════════╗
             ║  CARDS ESTUDIANTE                   ║
             ╚══════════════════════════════════════╝ --}}
        @hasrole('Estudiante')
        <div class="p-3">
            <div class="row g-3" id="cursosEstudiante">
                @forelse($inscritos as $inscrito)
                @if(auth()->user()->id == $inscrito->estudiante_id
                    && $inscrito->cursos
                    && $inscrito->cursos->deleted_at === null)
                <div class="col-lg-4 col-md-6 col-12 curso-card-item">
                    <a href="{{ route('Curso', $inscrito->cursos->codigoCurso ?? $inscrito->cursos->id) }}"
                       class="text-decoration-none">
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


        {{-- ╔══════════════════════════════════════╗
             ║  CARDS DOCENTE                      ║
             ╚══════════════════════════════════════╝ --}}
        @hasrole('Docente')
        <div class="p-3">
            <div class="row g-3" id="cursosDocente">
                @forelse($cursos as $curso)
                @if(auth()->user()->id == $curso->docente_id)
                <div class="col-lg-4 col-md-6 col-12 curso-card-item">
                    <a href="{{ route('Curso', $curso->codigoCurso ?? $curso->id) }}"
                       class="text-decoration-none">
                        <div class="curso-card-rol curso-card-docente">
                            <div class="curso-card-icon-wrap">
                                <i class="bi bi-mortarboard-fill"></i>
                            </div>
                            <div class="curso-card-body">
                                <h6 class="curso-card-title">
                                    {{ ucfirst(strtolower($curso->nombreCurso)) }}
                                </h6>
                                @if($curso->fecha_ini)
                                <span class="curso-card-meta">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    {{ \Carbon\Carbon::parse($curso->fecha_ini)->format('d/m/Y') }}
                                </span>
                                @endif
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

    </div>{{-- /tbl-card --}}
</div>{{-- /container --}}


{{-- ╔══════════════════════════════════════╗
     ║  MODALES DETALLE (Admin)            ║
     ╚══════════════════════════════════════╝ --}}
@hasrole('Administrador')
@foreach($cursos as $curso)
<div class="modal fade" id="courseModal{{ $curso->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content cc-modal">
            <div class="cc-modal-header">
                <div class="cc-modal-icon"><i class="bi bi-book-half"></i></div>
                <div>
                    <h5 class="cc-modal-title">Detalles del Curso</h5>
                    <small class="opacity-75">{{ ucfirst(strtolower($curso->nombreCurso)) }}</small>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="lc-detail-grid">
                    @php
                        $detalles = [
                            ['bi-bookmark-star-fill',  'Nombre',        ucfirst(strtolower($curso->nombreCurso))],
                            ['bi-bar-chart-fill',      'Nivel',         $curso->nivel ? ucfirst(strtolower($curso->nivel)) : 'N/A'],
                            ['bi-person-badge-fill',   'Instructor',    $curso->docente ? $curso->docente->name.' '.$curso->docente->lastname1.' '.$curso->docente->lastname2 : 'N/A'],
                            ['bi-people-fill',         'Edad Dirigida', $curso->edad_dirigida ? ucfirst(strtolower($curso->edad_dirigida)) : 'N/A'],
                            ['bi-calendar-check-fill', 'Fecha Inicio',  $curso->fecha_ini ?? 'N/A'],
                            ['bi-calendar-x-fill',     'Fecha Fin',     $curso->fecha_fin ?? 'N/A'],
                            ['bi-display-fill',        'Formato',       $curso->formato ?? 'N/A'],
                            ['bi-tags-fill',           'Tipo',          ucfirst(strtolower($curso->tipo ?? 'N/A'))],
                        ];
                    @endphp
                    @foreach($detalles as [$icon, $label, $val])
                    <div class="lc-detail-item">
                        <div class="lc-detail-icon"><i class="bi {{ $icon }}"></i></div>
                        <div>
                            <div class="lc-detail-label">{{ $label }}</div>
                            <div class="lc-detail-val">{{ $val }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="cc-modal-footer">
                <button type="button" class="cc-btn cc-btn-outline" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cerrar
                </button>
                <a href="{{ route('Curso', $curso->codigoCurso ?? $curso->id) }}" class="cc-btn cc-btn-primary">
                    <i class="bi bi-eye-fill me-1"></i>Ver Curso
                </a>
            </div>
        </div>
    </div>
</div>
@endforeach
@endrole

@endsection


@push('scripts')
<script>
(function () {
    document.addEventListener('DOMContentLoaded', function () {

        /* Filtro tipo → submit automático */
        document.getElementById('tipoFilterSelect')
            ?.addEventListener('change', function () {
                document.getElementById('tipoFilterForm')?.submit();
            });

        /* Búsqueda client-side Docente/Estudiante */
        document.getElementById('searchInputCursos')
            ?.addEventListener('input', function () {
                const q = this.value.toLowerCase();
                document.querySelectorAll('.curso-card-item').forEach(card => {
                    card.style.display =
                        card.textContent.toLowerCase().includes(q) ? '' : 'none';
                });
            });

        /* Confirmación eliminar */
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                const url = this.getAttribute('href');
                Swal.fire({
                    title            : '¿Eliminar curso?',
                    text             : 'Esta acción no se puede deshacer.',
                    icon             : 'warning',
                    showCancelButton : true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor : '#145da0',
                    confirmButtonText : 'Sí, eliminar',
                    cancelButtonText  : 'Cancelar',
                }).then(r => { if (r.isConfirmed) window.location.href = url; });
            });
        });

        /* Tooltips */
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
            new bootstrap.Tooltip(el);
        });

    });
})();
</script>
@endpush
@include('layout')
