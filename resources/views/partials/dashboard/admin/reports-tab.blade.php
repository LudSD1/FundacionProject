@php
    $estado = request('estado', 'all');
    $q      = request('q');
    $query  = \App\Models\Cursos::with('docente')->latest();
    $hoy    = \Carbon\Carbon::today();

    if ($estado !== 'all') {
        if ($estado === 'Activo')
            $query->whereDate('fecha_ini', '<=', $hoy)->whereDate('fecha_fin', '>=', $hoy);
        elseif ($estado === 'Inactivo')
            $query->whereDate('fecha_ini', '>', $hoy);
        elseif ($estado === 'Finalizado')
            $query->whereDate('fecha_fin', '<', $hoy);
    }
    if ($q) $query->where('nombreCurso', 'like', '%'.$q.'%');

    $reports = $query->paginate(10);
@endphp

<div class="ntf-wrap">

    {{-- ╔══════════════════════════════════════╗
         ║  TOOLBAR                            ║
         ╚══════════════════════════════════════╝ --}}
    <div class="ntf-toolbar">

        {{-- Buscador --}}
        <form id="rptSearchForm" method="GET" action="" class="ntf-toolbar-search">
            <input type="hidden" name="tab"    value="reportes">
            <input type="hidden" name="estado" value="{{ $estado }}">
            <div class="tbl-hero-search">
                <i class="bi bi-search tbl-hero-search-icon" style="color:#94a3b8"></i>
                <input type="text"
                       class="tbl-hero-search-input ntf-search-light"
                       name="q"
                       id="rptSearchInput"
                       value="{{ $q }}"
                       placeholder="Buscar cursos…"
                       autocomplete="off">
            </div>
        </form>

        {{-- Filtro estado --}}
        <form id="rptFilterForm" method="GET" action="">
            <input type="hidden" name="tab" value="reportes">
            <input type="hidden" name="q"   value="{{ $q }}">
            <div class="tbl-hero-select-wrap ntf-select-light">
                <i class="bi bi-funnel-fill tbl-hero-select-icon" style="color:#94a3b8"></i>
                <select name="estado" class="tbl-hero-select ntf-select-field" id="rptFilterSelect">
                    <option value="all"       {{ $estado === 'all'       ? 'selected' : '' }}>Todos</option>
                    <option value="Activo"    {{ $estado === 'Activo'    ? 'selected' : '' }}>Activos</option>
                    <option value="Inactivo"  {{ $estado === 'Inactivo'  ? 'selected' : '' }}>Inactivos</option>
                    <option value="Finalizado"{{ $estado === 'Finalizado'? 'selected' : '' }}>Finalizados</option>
                </select>
            </div>
        </form>

        {{-- Exportar --}}
        <div class="ntf-toolbar-actions">
            <a href="?tab=reportes&{{ http_build_query(request()->except('tab','page')) }}&export=cursos"
               class="tbl-hero-btn tbl-hero-btn-primary">
                <i class="bi bi-download"></i>
                <span>Exportar Cursos</span>
            </a>
        </div>

    </div>{{-- /ntf-toolbar --}}

    {{-- Filtros activos --}}
    @if($q || $estado !== 'all')
    <div class="tbl-filter-bar">
        <div class="tbl-filter-bar-left">
            <i class="bi bi-funnel-fill"></i>
            @if($q)
                Búsqueda: <strong>{{ $q }}</strong>
            @endif
            @if($estado !== 'all')
                @if($q) · @endif
                Estado: <span class="tbl-filter-chip">{{ $estado }}</span>
            @endif
            — <strong>{{ $reports->total() }}</strong> resultado(s)
        </div>
        <a href="?tab=reportes" class="tbl-filter-clear">
            <i class="bi bi-x-circle"></i> Limpiar filtros
        </a>
    </div>
    @endif

    {{-- ╔══════════════════════════════════════╗
         ║  TABLA                              ║
         ╚══════════════════════════════════════╝ --}}
    <div class="table-container-modern">
        <table class="table-modern">
            <thead>
                <tr>
                    <th width="28%">
                        <div class="th-content">
                            <i class="bi bi-book-fill"></i><span>Curso</span>
                        </div>
                    </th>
                    <th width="15%">
                        <div class="th-content">
                            <i class="bi bi-person-badge-fill"></i><span>Instructor</span>
                        </div>
                    </th>
                    <th width="25%">
                        <div class="th-content">
                            <i class="bi bi-file-text-fill"></i><span>Descripción</span>
                        </div>
                    </th>
                    <th width="10%">
                        <div class="th-content">
                            <i class="bi bi-circle-half"></i><span>Estado</span>
                        </div>
                    </th>
                    <th width="12%">
                        <div class="th-content">
                            <i class="bi bi-calendar-event-fill"></i><span>Fecha inicio</span>
                        </div>
                    </th>
                    <th width="10%" class="text-center">
                        <div class="th-content justify-content-center">
                            <i class="bi bi-gear-fill"></i><span>Acciones</span>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $curso)
                @php
                    $estadoCurso = $curso->estado ?? 'Inactivo';
                    $estadoClass = match($estadoCurso) {
                        'Activo'     => 'status-active',
                        'Finalizado' => 'status-inactive',
                        default      => 'status-pending',
                    };
                    $estadoIcon = match($estadoCurso) {
                        'Activo'     => 'bi-check-circle-fill',
                        'Finalizado' => 'bi-x-circle-fill',
                        default      => 'bi-hourglass-split',
                    };
                @endphp
                <tr data-estado="{{ $estadoCurso }}"
                    data-nombre="{{ strtolower($curso->nombreCurso) }}">

                    {{-- Curso con thumbnail --}}
                    <td>
                        <div class="rpt-course-cell">
                            <img src="{{ $curso->imagen
                                    ? asset('storage/'.$curso->imagen)
                                    : asset('assets/img/bg2.png') }}"
                                 alt="{{ $curso->nombreCurso }}"
                                 class="rpt-thumb"
                                 onerror="this.src='{{ asset('assets/img/bg2.png') }}'">
                            <span class="course-name">
                                {{ ucfirst(strtolower($curso->nombreCurso)) }}
                            </span>
                        </div>
                    </td>

                    {{-- Instructor --}}
                    <td>
                        <div class="teacher-cell">
                            <div class="tbl-avatar">
                                {{ strtoupper(substr($curso->docente->name ?? '?', 0, 1)) }}
                            </div>
                            <span>{{ $curso->docente->name ?? '—' }}</span>
                        </div>
                    </td>

                    {{-- Descripción truncada --}}
                    <td>
                        <span class="rpt-desc"
                              data-bs-toggle="tooltip"
                              data-bs-placement="top"
                              title="{{ $curso->descripcionC }}">
                            {{ Str::limit($curso->descripcionC, 60) }}
                        </span>
                    </td>

                    {{-- Estado → status-badge del sistema --}}
                    <td>
                        <span class="status-badge {{ $estadoClass }}">
                            <i class="bi {{ $estadoIcon }}"></i>
                            {{ $estadoCurso }}
                        </span>
                    </td>

                    {{-- Fecha --}}
                    <td>
                        <span class="date-badge date-start"
                              data-bs-toggle="tooltip"
                              data-bs-placement="top"
                              title="Inicio: {{ $curso->fecha_ini }} · Fin: {{ $curso->fecha_fin }}">
                            <i class="bi bi-calendar-event"></i>
                            {{ \Carbon\Carbon::parse($curso->fecha_ini)->format('d/m/Y') }}
                        </span>
                    </td>

                    {{-- Acciones --}}
                    <td>
                        <div class="action-buttons-cell">
                            <a class="btn-action-modern btn-view"
                               href="{{ route('rfc', encrypt($curso->id)) }}"
                               data-bs-toggle="tooltip"
                               title="Ver detalles">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            <a class="btn-action-modern btn-edit"
                               href="{{ route('editarCurso', encrypt($curso->id)) }}"
                               data-bs-toggle="tooltip"
                               title="Editar curso">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state-table">
                            <div class="empty-icon-table">
                                <i class="bi bi-journal-x"></i>
                            </div>
                            <h5 class="empty-title-table">No hay cursos para mostrar</h5>
                            <p class="empty-text-table">
                                @if($q || $estado !== 'all')
                                    Intenta con otros filtros de búsqueda
                                @else
                                    Aún no hay cursos registrados
                                @endif
                            </p>
                            @if($q || $estado !== 'all')
                            <a href="?tab=reportes"
                               class="tbl-hero-btn tbl-hero-btn-primary"
                               style="width:auto;margin:0 auto">
                                <i class="bi bi-arrow-clockwise"></i> Ver todos
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    @if($reports->hasPages() || $reports->total() > 0)
    <div class="ntf-pagination-wrap">
        <span class="ntf-pagination-info">
            <i class="bi bi-info-circle me-1"></i>
            Mostrando
            <strong>{{ $reports->firstItem() ?? 0 }}</strong> –
            <strong>{{ $reports->lastItem()  ?? 0 }}</strong>
            de <strong>{{ $reports->total() }}</strong> cursos
        </span>
        @if($reports->hasPages())
        <div class="tbl-pagination" style="border:none;background:transparent;padding:.5rem 0">
            {{ $reports->links('vendor.pagination.custom') }}
        </div>
        @endif
    </div>
    @endif

</div>


<script>
    (function () {
        document.addEventListener('DOMContentLoaded', function () {

            /* Filtro estado → submit automático */
            document.getElementById('rptFilterSelect')
                ?.addEventListener('change', function () {
                    document.getElementById('rptFilterForm').submit();
                });

            /* Buscador con debounce */
            let rptTimer;
            document.getElementById('rptSearchInput')
                ?.addEventListener('input', function () {
                    clearTimeout(rptTimer);
                    rptTimer = setTimeout(() =>
                        document.getElementById('rptSearchForm').submit()
                    , 500);
                });

            /* Tooltips */
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                new bootstrap.Tooltip(el, { trigger: 'hover' });
            });

        });
    })();
    </script>