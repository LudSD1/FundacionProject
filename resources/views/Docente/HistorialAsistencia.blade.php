@extends('layout')

@section('titulo', 'Historial de Asistencia: ' . $cursos->nombreCurso)

@section('content')

<div class="container my-4">
    <div class="tbl-card">

        {{-- ╔══════════════════════════════════════╗
             ║  HERO — CABECERA AZUL               ║
             ╚══════════════════════════════════════╝ --}}
        <div class="tbl-card-hero">
            <div class="tbl-hero-left">
                <div class="d-flex gap-2 mb-2">
                    <a href="{{ route('Curso', $cursos->codigoCurso) }}" class="tbl-hero-btn tbl-hero-btn-glass prt-back-btn">
                        <i class="bi bi-arrow-left-circle-fill"></i> Volver
                    </a>
                    <a href="{{ route('asistencias', encrypt($cursos->id)) }}" class="tbl-hero-btn tbl-hero-btn-glass prt-back-btn">
                        <i class="bi bi-calendar-check-fill"></i> Asistencias
                    </a>
                </div>
                <div class="tbl-hero-eyebrow">
                    <i class="bi bi-clock-history"></i> Control de Asistencia
                </div>
                <h2 class="tbl-hero-title">Historial de Asistencia</h2>
                <p class="tbl-hero-sub">
                    Curso: <strong>{{ $cursos->nombreCurso }}</strong>
                </p>
            </div>

            <div class="tbl-hero-controls">
                <div class="d-flex gap-2 flex-wrap justify-content-end align-items-center">
                    @if (auth()->user()->hasAnyRole(['Docente', 'Administrador']))
                        <a href="{{ route('repA', encrypt($cursos->id)) }}" class="tbl-hero-btn tbl-hero-btn-primary">
                            <i class="bi bi-file-earmark-pdf-fill"></i> <span>Reporte</span>
                        </a>
                    @endif

                    <div class="tbl-hero-search">
                        <i class="bi bi-search tbl-hero-search-icon"></i>
                        <input type="text" class="tbl-hero-search-input"
                               id="busqueda" name="busqueda" value="{{ request('busqueda') }}"
                               placeholder="Buscar estudiante..."
                               form="filtrosForm">
                    </div>
                </div>

                <div class="text-white opacity-75 small mt-2 text-end">
                    <i class="bi bi-calendar3 me-1"></i> {{ now()->format('d/m/Y') }}
                </div>
            </div>
        </div>{{-- /tbl-card-hero --}}

        {{-- Barra de estadísticas rápidas --}}
        <div class="tbl-filter-bar bg-light border-bottom">
            <div class="row w-100 g-0 px-3 py-2 text-center">
                <div class="col-md-3 border-end">
                    <div class="small text-muted">Asistencia Total</div>
                    <strong class="text-primary">{{ $stats['presente_percent'] ?? '0%' }}</strong>
                </div>
                <div class="col-md-3 border-end">
                    <div class="small text-muted">Retrasos</div>
                    <strong class="text-warning">{{ $stats['retraso_percent'] ?? '0%' }}</strong>
                </div>
                <div class="col-md-3 border-end">
                    <div class="small text-muted">Licencias</div>
                    <strong class="text-info">{{ $stats['licencia_percent'] ?? '0%' }}</strong>
                </div>
                <div class="col-md-3">
                    <div class="small text-muted">Faltas</div>
                    <strong class="text-danger">{{ $stats['falta_percent'] ?? '0%' }}</strong>
                </div>
            </div>
        </div>

        {{-- Filtros de búsqueda --}}
        <div class="p-3 bg-white border-bottom">
            <form method="GET" action="{{ route('historialAsistencias', encrypt($cursos->id)) }}" id="filtrosForm">
                <div class="row g-2 align-items-end justify-content-end">
                    <div class="col-md-3">
                        <label class="small text-muted ms-2">Desde</label>
                        <input type="date" class="form-control form-control-sm rounded-pill"
                               name="fecha_desde" id="fecha_desde" value="{{ request('fecha_desde') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="small text-muted ms-2">Hasta</label>
                        <input type="date" class="form-control form-control-sm rounded-pill"
                               name="fecha_hasta" id="fecha_hasta" value="{{ request('fecha_hasta') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="small text-muted ms-2">Estado</label>
                        <select name="tipo_asistencia" id="tipo_asistencia" class="form-select form-select-sm rounded-pill">
                            <option value="">Todos los estados</option>
                            <option value="Presente" {{ request('tipo_asistencia') == 'Presente' ? 'selected' : '' }}>Presente</option>
                            <option value="Retraso" {{ request('tipo_asistencia') == 'Retraso' ? 'selected' : '' }}>Retraso</option>
                            <option value="Licencia" {{ request('tipo_asistencia') == 'Licencia' ? 'selected' : '' }}>Licencia</option>
                            <option value="Falta" {{ request('tipo_asistencia') == 'Falta' ? 'selected' : '' }}>Falta</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-1">
                        <button type="submit" class="btn btn-primary btn-sm rounded-pill flex-fill">
                            <i class="bi bi-filter"></i> Filtrar
                        </button>
                        <a href="{{ route('historialAsistencias', encrypt($cursos->id)) }}"
                           class="btn btn-outline-secondary btn-sm rounded-pill flex-fill">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="p-0">
            <form action="{{ route('historialAsistenciasPost', encrypt($cursos->id)) }}" method="POST" id="attendanceForm">
                @csrf
                <div class="table-container-modern">
                    <table class="table-modern" id="dataTable">
                        <thead>
                            <tr>
                                <th width="5%"><div class="th-content">#</div></th>
                                <th width="35%">
                                    <div class="th-content">
                                        <i class="bi bi-person-fill"></i>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'estudiante', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-inherit text-decoration-none">
                                            Estudiante
                                            <i class="bi bi-sort-alpha-{{ request('direction') == 'asc' ? 'down' : 'up' }} ms-1"></i>
                                        </a>
                                    </div>
                                </th>
                                <th width="25%"><div class="th-content"><i class="bi bi-check2-square"></i><span>Asistencia</span></div></th>
                                <th width="20%">
                                    <div class="th-content">
                                        <i class="bi bi-calendar-event"></i>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'fecha', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-inherit text-decoration-none">
                                            Fecha
                                            <i class="bi bi-sort-numeric-{{ request('direction') == 'asc' ? 'down' : 'up' }} ms-1"></i>
                                        </a>
                                    </div>
                                </th>
                                @if (auth()->user()->hasAnyRole(['Docente', 'Administrador']))
                                    <th width="15%"><div class="th-content justify-content-center"><i class="bi bi-gear-fill"></i><span>Acciones</span></div></th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($asistencias as $index => $asistencia)
                                @if ($asistencia->curso_id == $cursos->id && (auth()->user()->hasAnyRole(['Docente', 'Administrador']) || (auth()->user()->hasRole('Estudiante') && auth()->user()->id == $asistencia->inscritos->estudiantes->id)))
                                    <tr>
                                        <td><span class="row-number">{{ ($asistencias->currentPage() - 1) * $asistencias->perPage() + $loop->iteration }}</span></td>
                                        <td>
                                            <div class="prt-student">
                                                <div class="tbl-avatar">
                                                    {{ strtoupper(substr($asistencia->inscritos->estudiantes->name, 0, 1)) }}
                                                </div>
                                                <div class="prt-student-info">
                                                    <div class="prt-student-name">
                                                        {{ $asistencia->inscritos->estudiantes->name }}
                                                        {{ $asistencia->inscritos->estudiantes->lastname1 }}
                                                        {{ $asistencia->inscritos->estudiantes->lastname2 }}
                                                    </div>
                                                    <small class="prt-student-email">{{ $asistencia->inscritos->estudiantes->email ?? 'Sin email' }}</small>
                                                </div>
                                            </div>
                                        </td>

                                        @if (auth()->user()->hasAnyRole(['Docente', 'Administrador']))
                                            <td>
                                                <input type="hidden" name="asistencia[{{ $asistencia->id }}][id]" value="{{ $asistencia->id }}">
                                                <select name="asistencia[{{ $asistencia->id }}][tipo_asistencia]" class="form-select form-select-sm rounded-pill attendance-select">
                                                    <option value="Presente" {{ $asistencia->tipoAsitencia == 'Presente' ? 'selected' : '' }}>Presente</option>
                                                    <option value="Retraso" {{ $asistencia->tipoAsitencia == 'Retraso' ? 'selected' : '' }}>Retraso</option>
                                                    <option value="Licencia" {{ $asistencia->tipoAsitencia == 'Licencia' ? 'selected' : '' }}>Licencia</option>
                                                    <option value="Falta" {{ $asistencia->tipoAsitencia == 'Falta' ? 'selected' : '' }}>Falta</option>
                                                </select>
                                            </td>
                                        @else
                                            <td>
                                                @php
                                                    $badgeClass = match($asistencia->tipoAsitencia) {
                                                        'Presente' => 'status-active',
                                                        'Retraso'  => 'status-pending',
                                                        'Licencia' => 'status-info',
                                                        default    => 'status-danger'
                                                    };
                                                    $iconClass = match($asistencia->tipoAsitencia) {
                                                        'Presente' => 'bi-check-circle-fill',
                                                        'Retraso'  => 'bi-clock-fill',
                                                        'Licencia' => 'bi-file-medical-fill',
                                                        default    => 'bi-x-circle-fill'
                                                    };
                                                @endphp
                                                <span class="status-badge {{ $badgeClass }}">
                                                    <i class="bi {{ $iconClass }} me-1"></i>
                                                    {{ $asistencia->tipoAsitencia }}
                                                </span>
                                            </td>
                                        @endif

                                        <td>
                                            <div class="date-badge">
                                                <i class="bi bi-calendar-check me-1"></i>
                                                {{ \Carbon\Carbon::parse($asistencia->fechaasistencia)->format('d/m/Y') }}
                                            </div>
                                            <small class="text-muted d-block mt-1">
                                                {{ \Carbon\Carbon::parse($asistencia->fechaasistencia)->diffForHumans() }}
                                            </small>
                                        </td>

                                        @if (auth()->user()->hasAnyRole(['Docente', 'Administrador']))
                                            <td class="text-center">
                                                <div class="action-buttons-cell justify-content-center">
                                                    <button type="button" class="btn-action-modern btn-view" data-bs-toggle="tooltip" title="Ver detalles">
                                                        <i class="bi bi-eye-fill"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->hasAnyRole(['Docente', 'Administrador']) ? 5 : 4 }}">
                                        <div class="empty-state-table">
                                            <div class="empty-icon-table"><i class="bi bi-clipboard-x"></i></div>
                                            <h5 class="empty-title-table">No hay registros de asistencia</h5>
                                            <p class="empty-text-table">No se encontraron registros para los filtros seleccionados.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación y Botón Guardar --}}
                <div class="p-3 bg-light border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <div class="pagination-info small text-muted">
                        Mostrando {{ $asistencias->firstItem() ?? 0 }} a {{ $asistencias->lastItem() ?? 0 }} de {{ $asistencias->total() }} registros
                    </div>

                    <div class="tbl-pagination m-0">
                        {{ $asistencias->appends(request()->query())->links('custom-pagination') }}
                    </div>

                    @if (auth()->user()->hasRole('Docente') && (!$cursos->fecha_fin || now() <= $cursos->fecha_fin))
                        <button type="submit" class="tbl-hero-btn tbl-hero-btn-primary" id="saveBtn">
                            <i class="bi bi-save-fill"></i> Guardar Cambios
                            <span class="badge bg-white text-primary ms-1" id="changesCount">0</span>
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

<style>
    .tbl-hero-search-input { padding-left: 2.5rem !important; }
    .tbl-hero-search-icon { left: 1rem !important; }

    .status-info {
        background: rgba(13, 202, 240, 0.1) !important;
        color: #0dcaf0 !important;
        border: 1px solid rgba(13, 202, 240, 0.2) !important;
    }
    .status-danger {
        background: rgba(220, 53, 69, 0.1) !important;
        color: #dc3545 !important;
        border: 1px solid rgba(220, 53, 69, 0.2) !important;
    }

    .attendance-select {
        transition: all 0.2s;
    }
    .attendance-select:focus {
        border-color: var(--tbl-primary);
        box-shadow: 0 0 0 0.2rem rgba(20, 93, 160, 0.15);
    }
</style>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let changesCount = 0;
            let formChanged = false;

            // Inicializar tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Auto-submit form on filter change
            const busquedaInput = document.getElementById('busqueda');
            let debounceTimer;

            busquedaInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    document.getElementById('filtrosForm').submit();
                }, 500);
            });

            document.getElementById('fecha_desde').addEventListener('change', function() {
                document.getElementById('filtrosForm').submit();
            });

            document.getElementById('fecha_hasta').addEventListener('change', function() {
                document.getElementById('filtrosForm').submit();
            });

            document.getElementById('tipo_asistencia').addEventListener('change', function() {
                document.getElementById('filtrosForm').submit();
            });

            // Cambiar color del select según la asistencia
            const attendanceSelects = document.querySelectorAll('.attendance-select');
            attendanceSelects.forEach(select => {
                updateSelectBorder(select);

                select.addEventListener('change', function() {
                    updateSelectBorder(this);
                    updateChangesCount();
                });
            });

            function updateSelectBorder(select) {
                select.classList.remove('border-success', 'border-warning', 'border-info', 'border-danger');

                if (select.value === 'Presente') {
                    select.classList.add('border-success');
                } else if (select.value === 'Retraso') {
                    select.classList.add('border-warning');
                } else if (select.value === 'Licencia') {
                    select.classList.add('border-info');
                } else {
                    select.classList.add('border-danger');
                }
            }

            // Actualizar contador de cambios
            function updateChangesCount() {
                changesCount++;
                document.getElementById('changesCount').textContent = changesCount;

                if (changesCount > 0) {
                    formChanged = true;
                }
            }

            // Confirmar antes de salir si hay cambios
            window.addEventListener('beforeunload', function(e) {
                if (formChanged) {
                    e.preventDefault();
                    e.returnValue = 'Tiene cambios sin guardar. ¿Está seguro de que quiere salir?';
                    return e.returnValue;
                }
            });

            // Resetear cambios al enviar el formulario
            document.getElementById('attendanceForm').addEventListener('submit', function() {
                formChanged = false;
                changesCount = 0;
            });

            // Validar fechas
            document.getElementById('fecha_desde').addEventListener('change', function() {
                const fechaHasta = document.getElementById('fecha_hasta');
                if (this.value) {
                    fechaHasta.setAttribute('min', this.value);
                }
            });

            document.getElementById('fecha_hasta').addEventListener('change', function() {
                const fechaDesde = document.getElementById('fecha_desde');
                if (fechaDesde.value && this.value && this.value < fechaDesde.value) {
                    alert('La fecha hasta no puede ser menor que la fecha desde');
                    this.value = '';
                }
            });
        });
    </script>
