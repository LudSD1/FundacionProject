@php
    $estado = request('estado', 'all');
    $q      = request('q');

    // Optimizamos la consulta con select para traer solo lo necesario
    $query  = \App\Models\Cursos::select('id', 'nombreCurso', 'codigoCurso', 'docente_id', 'imagen', 'fecha_ini', 'fecha_fin', 'precio', 'tipo')
        ->with(['docente' => function($d) {
            $d->select('id', 'name', 'lastname1', 'lastname2');
        }])
        ->withCount(['inscritos' => function($i) {
            $i->whereNull('deleted_at');
        }, 'certificados' => function($c) {
            $c->whereNull('deleted_at');
        }])
        ->withAvg(['inscritos as promedio_progreso' => function($i) {
            $i->whereNull('deleted_at');
        }], 'progreso')
        ->latest();

    $hoy = \Carbon\Carbon::today();

    if ($estado !== 'all') {
        if ($estado === 'Activo')
            $query->whereDate('fecha_ini', '<=', $hoy)->whereDate('fecha_fin', '>=', $hoy);
        elseif ($estado === 'Inactivo')
            $query->whereDate('fecha_ini', '>', $hoy);
        elseif ($estado === 'Finalizado')
            $query->whereDate('fecha_fin', '<', $hoy);
    }

    if ($q) {
        $query->where(function($sub) use ($q) {
            $sub->where('nombreCurso', 'like', '%'.$q.'%')
                ->orWhere('codigoCurso', 'like', '%'.$q.'%')
                ->orWhereHas('docente', function($d) use ($q) {
                    $d->where('name', 'like', '%'.$q.'%')
                      ->orWhere('lastname1', 'like', '%'.$q.'%')
                      ->orWhere('lastname2', 'like', '%'.$q.'%');
                });
        });
    }

    $reports = $query->paginate(12)->appends(request()->query());
@endphp

<style>
    /* Variables y utilidades */
    :root {
        --rpt-primary: #145da0;
        --rpt-bg-light: #f8fafc;
        --rpt-border: #e2e8f0;
        --rpt-text-main: #1e293b;
        --rpt-text-muted: #64748b;
    }

    .rpt-metric-card {
        background: #fff;
        border-radius: 1.25rem;
        padding: 1.25rem;
        border: 1px solid var(--rpt-border);
        display: flex;
        align-items: center;
        gap: 1.25rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
    }
    .rpt-metric-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -8px rgba(0,0,0,0.1);
        border-color: var(--rpt-primary);
    }
    .rpt-metric-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        flex-shrink: 0;
    }
    .rpt-prog-bar {
        height: 8px;
        background: #f1f5f9;
        border-radius: 10px;
        overflow: hidden;
        margin-top: 6px;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);
    }
    .rpt-prog-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--rpt-primary), #2c7be5);
        border-radius: 10px;
        transition: width 0.6s ease;
    }
    .rpt-course-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .rpt-thumb {
        width: 45px;
        height: 45px;
        border-radius: 8px;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .rpt-teacher-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--rpt-primary);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.8rem;
    }
    .rpt-stat-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.6rem;
        border-radius: 20px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
</style>

<div class="ntf-wrap">

    {{-- ╔══════════════════════════════════════╗
         ║  RESUMEN RÁPIDO                     ║
         ╚══════════════════════════════════════╝ --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-2 col-md-4 col-6">
            <div class="rpt-metric-card shadow-sm">
                <div class="rpt-metric-icon bg-primary-subtle text-primary">
                    <i class="bi bi-journal-text"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="small text-muted fw-medium">Cursos</div>
                    <div class="fw-bold fs-5 text-dark">{{ \App\Models\Cursos::where('tipo', 'curso')->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="rpt-metric-card shadow-sm">
                <div class="rpt-metric-icon bg-info-subtle text-info">
                    <i class="bi bi-megaphone"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="small text-muted fw-medium">Congresos</div>
                    <div class="fw-bold fs-5 text-dark">{{ \App\Models\Cursos::where('tipo', 'congreso')->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-4">
            <div class="rpt-metric-card shadow-sm">
                <div class="rpt-metric-icon bg-success-subtle text-success">
                    <i class="bi bi-people"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="small text-muted fw-medium">Total Inscritos</div>
                    <div class="fw-bold fs-5 text-dark">{{ \App\Models\Inscritos::count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="rpt-metric-card shadow-sm">
                <div class="rpt-metric-icon bg-warning-subtle text-warning">
                    <i class="bi bi-patch-check-fill"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="small text-muted fw-medium">Certificados Emitidos</div>
                    <div class="fw-bold fs-5 text-dark">{{ \App\Models\Certificado::count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6">
            <div class="rpt-metric-card shadow-sm">
                <div class="rpt-metric-icon bg-danger-subtle text-danger">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="small text-muted fw-medium">Progreso</div>
                    <div class="fw-bold fs-5 text-dark">{{ number_format(\App\Models\Inscritos::avg('progreso') ?? 0, 1) }}%</div>
                </div>
            </div>
        </div>
    </div>

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
                       placeholder="Buscar por nombre, código o instructor…"
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
                    <option value="all"       {{ $estado === 'all'       ? 'selected' : '' }}>Todos los estados</option>
                    <option value="Activo"    {{ $estado === 'Activo'    ? 'selected' : '' }}>Activos</option>
                    <option value="Inactivo"  {{ $estado === 'Inactivo'  ? 'selected' : '' }}>Inactivos (Próximos)</option>
                    <option value="Finalizado"{{ $estado === 'Finalizado'? 'selected' : '' }}>Finalizados</option>
                </select>
            </div>
        </form>

        {{-- Exportar --}}
        <div class="ntf-toolbar-actions">
            <a href="?tab=reportes&{{ http_build_query(request()->except('tab','page')) }}&export=cursos"
               class="tbl-hero-btn tbl-hero-btn-primary">
                <i class="bi bi-download"></i>
                <span>Exportar Reporte</span>
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
                    <th width="25%">
                        <div class="th-content">
                            <i class="bi bi-book-fill"></i><span>Curso / Congreso</span>
                        </div>
                    </th>
                    <th width="18%">
                        <div class="th-content text-center w-100">
                            <i class="bi bi-people-fill"></i><span>Participación</span>
                        </div>
                    </th>
                    <th width="15%">
                        <div class="th-content text-center w-100">
                            <i class="bi bi-graph-up"></i><span>Progreso Prom.</span>
                        </div>
                    </th>
                    <th width="14%">
                        <div class="th-content">
                            <i class="bi bi-person-badge-fill"></i><span>Instructor</span>
                        </div>
                    </th>
                    <th width="13%">
                        <div class="th-content">
                            <i class="bi bi-circle-half"></i><span>Estado</span>
                        </div>
                    </th>
                    <th width="15%" class="text-center">
                        <div class="th-content justify-content-center">
                            <i class="bi bi-gear-fill"></i><span>Acciones</span>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $curso)
                @php
                    $estadoCurso = $curso->estado;
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
                    $progreso = round($curso->promedio_progreso ?? 0);
                    $totalInscritos = $curso->inscritos_count;
                    $totalCertificados = $curso->certificados_count;
                    $isCongreso = $curso->tipo === 'congreso';
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
                            <div class="overflow-hidden">
                                <span class="course-name d-block text-truncate mb-0" title="{{ $curso->nombreCurso }}">
                                    {{ ucfirst(strtolower($curso->nombreCurso)) }}
                                </span>
                                <div class="d-flex align-items-center gap-2">
                                    <small class="text-muted fw-mono" style="font-size: 0.65rem;">{{ $curso->codigoCurso }}</small>
                                    @if($isCongreso)
                                        <span class="rpt-stat-badge bg-info-subtle text-info" style="font-size: 0.6rem;">
                                            <i class="bi bi-megaphone-fill"></i> Congreso
                                        </span>
                                    @endif
                                    @if($curso->precio > 0)
                                        <span class="rpt-stat-badge bg-light text-dark border">
                                            <i class="bi bi-tag-fill text-primary"></i>Bs.{{ number_format($curso->precio, 0) }}
                                        </span>
                                    @else
                                        <span class="rpt-stat-badge bg-success-subtle text-success">Gratis</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>

                    {{-- Participación (Inscritos y Certificados) --}}
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-3">
                            {{-- Participantes --}}
                            <div class="d-inline-flex flex-column align-items-center">
                                <div class="fw-bold text-dark fs-5">{{ $totalInscritos }}</div>
                                <span class="rpt-stat-badge bg-primary-subtle text-primary" style="font-size: 0.6rem;" title="Total inscritos">
                                    <i class="bi bi-people-fill"></i> Inscritos
                                </span>
                            </div>

                            {{-- Certificados (Diferenciados para Congresos) --}}
                            @if($isCongreso || $totalCertificados > 0)
                            <div class="d-inline-flex flex-column align-items-center">
                                <div class="fw-bold text-dark fs-5">{{ $totalCertificados }}</div>
                                <span class="rpt-stat-badge bg-warning-subtle text-warning" style="font-size: 0.6rem;" title="Certificados emitidos">
                                    <i class="bi bi-patch-check-fill"></i> Certif.
                                </span>
                            </div>
                            @endif
                        </div>
                    </td>

                    {{-- Progreso Promedio --}}
                    <td>
                        <div class="px-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-primary small">{{ $progreso }}%</span>
                                <span class="text-muted" style="font-size: 0.65rem;">Completado</span>
                            </div>
                            <div class="rpt-prog-bar">
                                <div class="rpt-prog-fill" style="width: {{ $progreso }}%"></div>
                            </div>
                        </div>
                    </td>

                    {{-- Instructor --}}
                    <td>
                        <div class="teacher-cell">
                            <div class="rpt-teacher-avatar">
                                {{ strtoupper(substr($curso->docente->name ?? '?', 0, 1)) }}
                            </div>
                            <div class="d-flex flex-column">
                                <span class="small fw-medium text-dark">{{ $curso->docente->name ?? 'Sin asignar' }}</span>
                                <small class="text-muted" style="font-size: 0.65rem;">Instructor principal</small>
                            </div>
                        </div>
                    </td>

                    {{-- Estado --}}
                    <td>
                        <div class="d-flex justify-content-center">
                            <span class="status-badge {{ $estadoClass }} py-1 px-2">
                                <i class="bi {{ $estadoIcon }}"></i>
                                {{ $estadoCurso }}
                            </span>
                        </div>
                    </td>

                    {{-- Acciones --}}
                    <td>
                        <div class="action-buttons-cell justify-content-center">
                            <a class="btn-action-modern btn-view"
                               href="{{ route('rfc', encrypt($curso->id)) }}"
                               data-bs-toggle="tooltip"
                               data-bs-placement="top"
                               title="Ver estadísticas detalladas">
                                <i class="bi bi-bar-chart-line-fill"></i>
                            </a>
                            <a class="btn-action-modern btn-edit"
                               href="{{ route('editarCurso', encrypt($curso->id)) }}"
                               data-bs-toggle="tooltip"
                               data-bs-placement="top"
                               title="Editar configuración">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a class="btn-action-modern"
                               style="background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;"
                               href="{{ route('Curso', $curso->codigoCurso ?? $curso->id) }}"
                               target="_blank"
                               data-bs-toggle="tooltip"
                               data-bs-placement="top"
                               title="Ver en el aula">
                                <i class="bi bi-eye"></i>
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
