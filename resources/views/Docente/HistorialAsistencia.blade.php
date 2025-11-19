@extends('layout')

@section('titulo', 'Historial de Asistencia: ' . $cursos->nombreCurso)

@section('content')

<style>
    :root {
        --color-primary: #1a4789;
        --color-secondary: #39a6cb;
        --color-accent1: #63becf;
        --color-accent2: #055c9d;
        --color-accent3: #2197bd;
        --color-success: #28a745;
        --color-warning: #ffc107;
        --color-danger: #dc3545;
        --color-info: #17a2b8;
        
        --gradient-primary: linear-gradient(135deg, #1a4789 0%, #055c9d 100%);
        --gradient-secondary: linear-gradient(135deg, #39a6cb 0%, #63becf 100%);
        
        --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
        --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.12);
        --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.15);
        
        --border-radius: 12px;
        --border-radius-sm: 8px;
    }
    
    .attendance-container .card {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-md);
    }
    
    .attendance-container .card-header {
        background: var(--gradient-primary);
        color: white;
        border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
        padding: 1rem 1.5rem;
    }
    
    .attendance-container .btn-primary {
        background: var(--color-primary);
        border-color: var(--color-primary);
        border-radius: var(--border-radius-sm);
    }
    
    .attendance-container .btn-primary:hover {
        background: var(--color-accent2);
        border-color: var(--color-accent2);
    }
    
    .attendance-container .btn-success {
        background: var(--color-success);
        border-color: var(--color-success);
        border-radius: var(--border-radius-sm);
    }
    
    .attendance-container .btn-outline-secondary {
        border-radius: var(--border-radius-sm);
    }
    
    .attendance-container .table thead th {
        background: var(--color-primary);
        color: white;
        border: none;
        padding: 1rem;
        font-weight: 600;
    }
    
    .attendance-container .table tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-color: #e9ecef;
    }
    
    .attendance-container .table-hover tbody tr:hover {
        background-color: rgba(57, 166, 203, 0.05);
    }
    
    .attendance-container .badge-presente {
        background-color: var(--color-success);
    }
    
    .attendance-container .badge-retraso {
        background-color: var(--color-warning);
        color: #212529;
    }
    
    .attendance-container .badge-licencia {
        background-color: var(--color-info);
    }
    
    .attendance-container .badge-falta {
        background-color: var(--color-danger);
    }
    
    .attendance-container .avatar-title {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
    }
    
    .attendance-container .form-control {
        border-radius: var(--border-radius-sm);
        border: 1px solid #dee2e6;
    }
    
    .attendance-container .form-control:focus {
        border-color: var(--color-accent1);
        box-shadow: 0 0 0 0.2rem rgba(57, 166, 203, 0.25);
    }
    
    .attendance-container .attendance-select {
        border-radius: var(--border-radius-sm);
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .attendance-container .attendance-select.border-success {
        border-width: 2px;
    }
    
    .attendance-container .attendance-select.border-warning {
        border-width: 2px;
    }
    
    .attendance-container .attendance-select.border-info {
        border-width: 2px;
    }
    
    .attendance-container .attendance-select.border-danger {
        border-width: 2px;
    }
    
    .attendance-container .empty-state {
        padding: 3rem 1rem;
    }
    
    .attendance-container .empty-state i {
        opacity: 0.5;
    }
    
    .attendance-container .pagination .page-link {
        color: var(--color-primary);
        border-radius: var(--border-radius-sm);
        margin: 0 2px;
    }
    
    .attendance-container .pagination .page-item.active .page-link {
        background-color: var(--color-primary);
        border-color: var(--color-primary);
    }
    
    .attendance-container .filter-card .card-header {
        background: var(--gradient-secondary);
        color: white;
    }
    
    .attendance-container .stats-card {
        background: var(--gradient-primary);
        color: white;
        border-radius: var(--border-radius);
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .attendance-container .modal-header.bg-danger {
        background: var(--color-danger) !important;
    }
    
    .attendance-container .btn-danger {
        background: var(--color-danger);
        border-color: var(--color-danger);
    }
    
    @media (max-width: 768px) {
        .attendance-container .card-header .d-flex {
            flex-direction: column;
            align-items: flex-start !important;
        }
        
        .attendance-container .card-header .d-flex > * {
            margin-bottom: 0.5rem;
        }
        
        .attendance-container .table-responsive {
            font-size: 0.875rem;
        }
    }
</style>

<div class="container-fluid attendance-container">
    <div class="card shadow-lg">
        <div class="card-header py-3 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <a href="{{ route('Curso', $cursos) }}" class="btn btn-primary me-3">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
                <a href="{{ route('asistencias', encrypt($cursos->id)) }}" class="btn btn-primary me-3">
                    Asistencias
                </a>
                <h5 class="m-0 font-weight-bold">Historial de Asistencia</h5>
            </div>
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center">
                <span class="me-3 mb-2 mb-md-0">
                    <i class="fas fa-calendar-day me-1"></i> {{ now()->format('Y-m-d') }}
                </span>
                @if (auth()->user()->hasAnyRole(['Docente', 'Administrador']))
                    <a href="{{ route('repA', encrypt($cursos->id)) }}" class="btn btn-sm btn-success">
                        <i class="fas fa-file-export me-1"></i> Generar Reporte
                    </a>
                @endif
            </div>
        </div>

        <div class="card-body">
            <!-- Tarjeta de estadísticas -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="stats-card">
                        <div class="row text-center">
                            <div class="col-md-3 mb-3 mb-md-0">
                                <h4 class="mb-1">{{ $stats['presente_percent'] ?? '0%' }}</h4>
                                <small>Asistencia Total</small>
                            </div>
                            <div class="col-md-3 mb-3 mb-md-0">
                                <h4 class="mb-1">{{ $stats['retraso_percent'] ?? '0%' }}</h4>
                                <small>Retrasos</small>
                            </div>
                            <div class="col-md-3 mb-3 mb-md-0">
                                <h4 class="mb-1">{{ $stats['licencia_percent'] ?? '0%' }}</h4>
                                <small>Licencias</small>
                            </div>
                            <div class="col-md-3">
                                <h4 class="mb-1">{{ $stats['falta_percent'] ?? '0%' }}</h4>
                                <small>Faltas</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros de búsqueda -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card filter-card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <i class="fas fa-search me-2"></i> Filtros de Búsqueda
                                </h6>
                                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#filtrosCollapse">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </div>
                        </div>
                        <div class="collapse show" id="filtrosCollapse">
                            <div class="card-body">
                                <form method="GET" action="{{ route('historialAsistencias', encrypt($cursos->id)) }}" id="filtrosForm">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="busqueda" class="form-label">Buscar Estudiante:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="busqueda" name="busqueda" value="{{ request('busqueda') }}" placeholder="Nombre del estudiante...">
                                                <span class="input-group-text">
                                                    <i class="fas fa-search"></i>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="fecha_desde" class="form-label">Fecha Desde:</label>
                                            <input type="date" class="form-control" id="fecha_desde" name="fecha_desde" value="{{ request('fecha_desde') }}">
                                        </div>

                                        <div class="col-md-3">
                                            <label for="fecha_hasta" class="form-label">Fecha Hasta:</label>
                                            <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta" value="{{ request('fecha_hasta') }}">
                                        </div>

                                        <div class="col-md-2">
                                            <label for="tipo_asistencia" class="form-label">Tipo:</label>
                                            <select name="tipo_asistencia" id="tipo_asistencia" class="form-control">
                                                <option value="">Todos</option>
                                                <option value="Presente" {{ request('tipo_asistencia') == 'Presente' ? 'selected' : '' }}>Presente</option>
                                                <option value="Retraso" {{ request('tipo_asistencia') == 'Retraso' ? 'selected' : '' }}>Retraso</option>
                                                <option value="Licencia" {{ request('tipo_asistencia') == 'Licencia' ? 'selected' : '' }}>Licencia</option>
                                                <option value="Falta" {{ request('tipo_asistencia') == 'Falta' ? 'selected' : '' }}>Falta</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-12 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                                            <div class="mb-2 mb-md-0">
                                                <button type="submit" class="btn btn-primary me-2">
                                                    <i class="fas fa-search me-1"></i> Buscar
                                                </button>
                                                <a href="{{ route('historialAsistencias', encrypt($cursos->id)) }}" class="btn btn-outline-secondary">
                                                    <i class="fas fa-eraser me-1"></i> Limpiar Filtros
                                                </a>
                                            </div>
                                            <div>
                                                <span class="text-muted">
                                                    Mostrando {{ $asistencias->count() }} de {{ $asistencias->total() }} registros
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de asistencias -->
            <form action="{{ route('historialAsistenciasPost', encrypt($cursos->id)) }}" method="POST" id="attendanceForm">
                @csrf
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="35%">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'estudiante', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-white text-decoration-none">
                                        Estudiante
                                        @if (request('sort') == 'estudiante')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                        @else
                                            <i class="fas fa-sort ms-1"></i>
                                        @endif
                                    </a>
                                </th>
                                <th width="25%">Tipo de Asistencia</th>
                                <th width="20%">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'fecha', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-white text-decoration-none">
                                        Fecha
                                        @if (request('sort') == 'fecha')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                        @else
                                            <i class="fas fa-sort ms-1"></i>
                                        @endif
                                    </a>
                                </th>
                                @if (auth()->user()->hasAnyRole(['Docente', 'Administrador']))
                                    <th width="15%">Acciones</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($asistencias as $index => $asistencia)
                                @if ($asistencia->curso_id == $cursos->id && (auth()->user()->hasAnyRole(['Docente', 'Administrador']) || (auth()->user()->hasRole('Estudiante') && auth()->user()->id == $asistencia->inscritos->estudiantes->id)))
                                    <tr>
                                        <td>{{ ($asistencias->currentPage() - 1) * $asistencias->perPage() + $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-3">
                                                    <div class="avatar-title bg-primary rounded-circle">
                                                        {{ substr($asistencia->inscritos->estudiantes->name, 0, 1) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <strong>
                                                        {{ $asistencia->inscritos->estudiantes->name }}
                                                        {{ $asistencia->inscritos->estudiantes->lastname1 }}
                                                        {{ $asistencia->inscritos->estudiantes->lastname2 }}
                                                    </strong>
                                                    <br>
                                                    <small class="text-muted">{{ $asistencia->inscritos->estudiantes->email ?? 'Sin email' }}</small>
                                                </div>
                                            </div>
                                        </td>

                                        @if (auth()->user()->hasAnyRole(['Docente', 'Administrador']))
                                            <td>
                                                <input type="hidden" name="asistencia[{{ $asistencia->id }}][id]" value="{{ $asistencia->id }}">
                                                <select name="asistencia[{{ $asistencia->id }}][tipo_asistencia]" class="form-control form-control-sm attendance-select">
                                                    <option value="Presente" {{ $asistencia->tipoAsitencia == 'Presente' ? 'selected' : '' }}>Presente</option>
                                                    <option value="Retraso" {{ $asistencia->tipoAsitencia == 'Retraso' ? 'selected' : '' }}>Retraso</option>
                                                    <option value="Licencia" {{ $asistencia->tipoAsitencia == 'Licencia' ? 'selected' : '' }}>Licencia</option>
                                                    <option value="Falta" {{ $asistencia->tipoAsitencia == 'Falta' ? 'selected' : '' }}>Falta</option>
                                                </select>
                                            </td>
                                        @else
                                            <td>
                                                <span class="badge badge-lg 
                                                    @if($asistencia->tipoAsitencia == 'Presente') badge-presente
                                                    @elseif($asistencia->tipoAsitencia == 'Retraso') badge-retraso
                                                    @elseif($asistencia->tipoAsitencia == 'Licencia') badge-licencia
                                                    @else badge-falta @endif">
                                                    <i class="fas
                                                        @if($asistencia->tipoAsitencia == 'Presente') fa-check
                                                        @elseif($asistencia->tipoAsitencia == 'Retraso') fa-clock
                                                        @elseif($asistencia->tipoAsitencia == 'Licencia') fa-file-medical
                                                        @else fa-times @endif me-1">
                                                    </i>
                                                    {{ $asistencia->tipoAsitencia }}
                                                </span>
                                            </td>
                                        @endif

                                        <td>
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            {{ \Carbon\Carbon::parse($asistencia->fechaasistencia)->format('d/m/Y') }}
                                            <br>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($asistencia->fechaasistencia)->diffForHumans() }}
                                            </small>
                                        </td>

                                        @if (auth()->user()->hasAnyRole(['Docente', 'Administrador']))
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Ver detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger delete-attendance" data-id="{{ $asistencia->id }}" data-bs-toggle="tooltip" title="Eliminar registro">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->hasAnyRole(['Docente', 'Administrador']) ? 5 : 4 }}" class="text-center text-muted py-5">
                                        <div class="empty-state">
                                            <i class="fas fa-clipboard-list fa-3x mb-3 text-muted"></i>
                                            <h5>No hay registros de asistencia</h5>
                                            <p class="text-muted">No se encontraron registros que coincidan con los filtros aplicados.</p>
                                            @if (request()->hasAny(['busqueda', 'fecha_desde', 'fecha_hasta', 'tipo_asistencia']))
                                                <a href="{{ route('historialAsistencias', encrypt($cursos->id)) }}" class="btn btn-outline-primary">
                                                    <i class="fas fa-eraser me-1"></i> Limpiar Filtros
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                @if ($asistencias->hasPages())
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="pagination-info">
                                <span class="text-muted">
                                    Mostrando {{ $asistencias->firstItem() }} a {{ $asistencias->lastItem() }} de {{ $asistencias->total() }} registros
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="pagination-wrapper float-end">
                                {{ $asistencias->appends(request()->query())->links('vendor.pagination.custom') }}
                            </div>
                        </div>
                    </div>
                @endif

                @if (auth()->user()->hasRole('Docente') && (!$cursos->fecha_fin || now() <= $cursos->fecha_fin))
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i> Guardar Cambios
                            <span class="badge bg-warning ms-1" id="changesCount">0</span>
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i> Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                    <h5>¿Está seguro que desea eliminar este registro?</h5>
                    <p class="text-muted">Esta acción no se puede deshacer.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancelar
                </button>
                <form id="deleteForm" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt me-1"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let changesCount = 0;
        let formChanged = false;
        
        // Inicializar tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
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
        
        // Manejar eliminación de asistencia
        const deleteButtons = document.querySelectorAll('.delete-attendance');
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const url = '{{ url("asistencia") }}/' + id;
                document.getElementById('deleteForm').setAttribute('action', url);
                deleteModal.show();
            });
        });
        
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
@endsection
