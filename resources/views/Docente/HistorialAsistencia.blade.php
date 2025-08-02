@extends('layout')

@section('titulo', 'Historial de Asistencia: ' . $cursos->nombreCurso)

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <a href="javascript:history.back()" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <h6 class="m-0 font-weight-bold text-primary">Historial de Asistencia</h6>
            <div class="d-flex align-items-center">
                <span class="mr-3 text-muted">
                    <i class="fas fa-calendar-day"></i> {{ now()->format('Y-m-d') }}
                </span>
                @if(auth()->user()->hasAnyRole(['Docente', 'Administrador']))
                    <a href="{{ route('repA', encrypt($cursos->id)) }}" class="btn btn-sm btn-success">
                        <i class="fas fa-file-export"></i> Generar Reporte
                    </a>
                @endif
            </div>
        </div>

        <div class="card-body">
            <!-- Filtros de búsqueda -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-search"></i> Filtros de Búsqueda
                                <button class="btn btn-sm btn-outline-secondary float-right" type="button" data-toggle="collapse" data-target="#filtrosCollapse">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </h6>
                        </div>
                        <div class="collapse show" id="filtrosCollapse">
                            <div class="card-body">
                                <form method="GET" action="{{ route('historialAsistencias', encrypt($cursos->id)) }}" id="filtrosForm">

                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="busqueda">Buscar Estudiante:</label>
                                            <div class="input-group">
                                                <input type="text"
                                                       class="form-control"
                                                       id="busqueda"
                                                       name="busqueda"
                                                       value="{{ request('busqueda') }}"
                                                       placeholder="Nombre del estudiante...">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-search"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="fecha_desde">Fecha Desde:</label>
                                            <input type="date"
                                                   class="form-control"
                                                   id="fecha_desde"
                                                   name="fecha_desde"
                                                   value="{{ request('fecha_desde') }}">
                                        </div>

                                        <div class="col-md-3">
                                            <label for="fecha_hasta">Fecha Hasta:</label>
                                            <input type="date"
                                                   class="form-control"
                                                   id="fecha_hasta"
                                                   name="fecha_hasta"
                                                   value="{{ request('fecha_hasta') }}">
                                        </div>

                                        <div class="col-md-2">
                                            <label for="tipo_asistencia">Tipo:</label>
                                            <select name="tipo_asistencia" id="tipo_asistencia" class="form-control">
                                                <option value="">Todos</option>
                                                <option value="Presente" {{ request('tipo_asistencia') == 'Presente' ? 'selected' : '' }}>Presente</option>
                                                <option value="Retraso" {{ request('tipo_asistencia') == 'Retraso' ? 'selected' : '' }}>Retraso</option>
                                                <option value="Licencia" {{ request('tipo_asistencia') == 'Licencia' ? 'selected' : '' }}>Licencia</option>
                                                <option value="Falta" {{ request('tipo_asistencia') == 'Falta' ? 'selected' : '' }}>Falta</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-search"></i> Buscar
                                            </button>
                                            <a href="{{ route('historialAsistencias', encrypt($cursos->id)) }}" class="btn btn-secondary">
                                                <i class="fas fa-eraser"></i> Limpiar Filtros
                                            </a>
                                            <div class="float-right">
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
                        <thead class="thead-dark">
                            <tr>
                                <th width="5%">#</th>
                                <th width="35%">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'estudiante', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}"
                                       class="text-white text-decoration-none">
                                        Estudiante
                                        @if(request('sort') == 'estudiante')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort"></i>
                                        @endif
                                    </a>
                                </th>
                                <th width="25%">Tipo de Asistencia</th>
                                <th width="20%">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'fecha', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}"
                                       class="text-white text-decoration-none">
                                        Fecha
                                        @if(request('sort') == 'fecha')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort"></i>
                                        @endif
                                    </a>
                                </th>
                                @if(auth()->user()->hasAnyRole(['Docente', 'Administrador']))
                                    <th width="15%">Acciones</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($asistencias as $index => $asistencia)
                                @if($asistencia->curso_id == $cursos->id &&
                                   (auth()->user()->hasAnyRole(['Docente', 'Administrador']) ||
                                   (auth()->user()->hasRole('Estudiante') && auth()->user()->id == $asistencia->inscritos->estudiantes->id)))
                                    <tr>
                                        <td>{{ ($asistencias->currentPage() - 1) * $asistencias->perPage() + $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm mr-2">
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

                                        @if(auth()->user()->hasAnyRole(['Docente', 'Administrador']))
                                            <td>
                                                <input type="hidden" name="asistencia[{{ $asistencia->id }}][id]" value="{{ $asistencia->id }}">
                                                <select name="asistencia[{{ $asistencia->id }}][tipo_asistencia]"
                                                        class="form-control form-control-sm attendance-select">
                                                    <option value="Presente" {{ $asistencia->tipoAsitencia == 'Presente' ? 'selected' : '' }}>Presente</option>
                                                    <option value="Retraso" {{ $asistencia->tipoAsitencia == 'Retraso' ? 'selected' : '' }}>Retraso</option>
                                                    <option value="Licencia" {{ $asistencia->tipoAsitencia == 'Licencia' ? 'selected' : '' }}>Licencia</option>
                                                    <option value="Falta" {{ $asistencia->tipoAsitencia == 'Falta' ? 'selected' : '' }}>Falta</option>
                                                </select>
                                            </td>
                                        @else
                                            <td>
                                                <span class="badge badge-lg
                                                    @if($asistencia->tipoAsitencia == 'Presente') badge-success
                                                    @elseif($asistencia->tipoAsitencia == 'Retraso') badge-warning
                                                    @elseif($asistencia->tipoAsitencia == 'Licencia') badge-info
                                                    @else badge-danger
                                                    @endif">
                                                    <i class="fas
                                                        @if($asistencia->tipoAsitencia == 'Presente') fa-check
                                                        @elseif($asistencia->tipoAsitencia == 'Retraso') fa-clock
                                                        @elseif($asistencia->tipoAsitencia == 'Licencia') fa-file-medical
                                                        @else fa-times
                                                        @endif mr-1">
                                                    </i>
                                                    {{ $asistencia->tipoAsitencia }}
                                                </span>
                                            </td>
                                        @endif

                                        <td>
                                            <i class="fas fa-calendar-alt mr-1"></i>
                                            {{ \Carbon\Carbon::parse($asistencia->fechaasistencia)->format('d/m/Y') }}
                                            <br>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($asistencia->fechaasistencia)->diffForHumans() }}
                                            </small>
                                        </td>

                                        @if(auth()->user()->hasAnyRole(['Docente', 'Administrador']))
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-info"
                                                            data-toggle="tooltip" title="Ver detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger delete-attendance"
                                                            data-id="{{ $asistencia->id }}" data-toggle="tooltip"
                                                            title="Eliminar registro">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->hasAnyRole(['Docente', 'Administrador']) ? 5 : 4 }}"
                                        class="text-center text-muted py-5">
                                        <div class="empty-state">
                                            <i class="fas fa-clipboard-list fa-3x mb-3 text-muted"></i>
                                            <h5>No hay registros de asistencia</h5>
                                            <p class="text-muted">No se encontraron registros que coincidan con los filtros aplicados.</p>
                                            @if(request()->hasAny(['busqueda', 'fecha_desde', 'fecha_hasta', 'tipo_asistencia']))
                                                <a href="{{ route('historialAsistencias', encrypt($cursos->id)) }}" class="btn btn-outline-primary">
                                                    <i class="fas fa-eraser"></i> Limpiar Filtros
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
                @if($asistencias->hasPages())
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="pagination-info">
                                <span class="text-muted">
                                    Mostrando {{ $asistencias->firstItem() }} a {{ $asistencias->lastItem() }}
                                    de {{ $asistencias->total() }} registros
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="pagination-wrapper float-right">
                                {{ $asistencias->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                @endif

                @if(auth()->user()->hasRole('Docente') && (!$cursos->fecha_fin || now() <= $cursos->fecha_fin))
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Guardar Cambios
                            <span class="badge badge-light ml-1" id="changesCount">0</span>
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle"></i> Confirmar Eliminación
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                    <h5>¿Está seguro que desea eliminar este registro?</h5>
                    <p class="text-muted">Esta acción no se puede deshacer.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <form id="deleteForm" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let changesCount = 0;

    // Auto-submit form on filter change
    $('#busqueda').on('input', debounce(function() {
        $('#filtrosForm').submit();
    }, 500));

    $('#fecha_desde, #fecha_hasta, #tipo_asistencia').change(function() {
        $('#filtrosForm').submit();
    });

    // Función debounce para evitar múltiples requests
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Inicializar tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Cambiar color del select según la asistencia
    $('.attendance-select').change(function() {
        $(this).removeClass('border-success border-warning border-info border-danger');
        if($(this).val() === 'Presente') {
            $(this).addClass('border-success');
        } else if($(this).val() === 'Retraso') {
            $(this).addClass('border-warning');
        } else if($(this).val() === 'Licencia') {
            $(this).addClass('border-info');
        } else {
            $(this).addClass('border-danger');
        }

        // Actualizar contador de cambios
        updateChangesCount();
    }).trigger('change');

    // Manejar eliminación de asistencia
    $('.delete-attendance').click(function() {
        const id = $(this).data('id');
        const url = '{{ url("asistencia") }}/' + id;
        $('#deleteForm').attr('action', url);
        $('#deleteModal').modal('show');
    });

    // Confirmar antes de salir si hay cambios
    let formChanged = false;
    $('#attendanceForm').on('change', 'select', function() {
        formChanged = true;
    });

    function updateChangesCount() {
        changesCount++;
        $('#changesCount').text(changesCount);
        if (changesCount > 0) {
            $('#changesCount').removeClass('badge-light').addClass('badge-warning');
        }
    }

    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = 'Tiene cambios sin guardar. ¿Está seguro de que quiere salir?';
            return e.returnValue;
        }
    });

    $('#attendanceForm').submit(function() {
        formChanged = false;
        changesCount = 0;
    });

    // Resetear fecha hasta cuando cambie fecha desde
    $('#fecha_desde').change(function() {
        const fechaDesde = $(this).val();
        if (fechaDesde) {
            $('#fecha_hasta').attr('min', fechaDesde);
        }
    });

    // Validar que fecha hasta no sea menor que fecha desde
    $('#fecha_hasta').change(function() {
        const fechaDesde = $('#fecha_desde').val();
        const fechaHasta = $(this).val();

        if (fechaDesde && fechaHasta && fechaHasta < fechaDesde) {
            alert('La fecha hasta no puede ser menor que la fecha desde');
            $(this).val('');
        }
    });
});
</script>

<style>
.attendance-select.border-success {
    border-color: #28a745 !important;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}
.attendance-select.border-warning {
    border-color: #ffc107 !important;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}
.attendance-select.border-info {
    border-color: #17a2b8 !important;
    box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
}
.attendance-select.border-danger {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.avatar-sm {
    width: 2rem;
    height: 2rem;
}

.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    font-weight: 500;
    font-size: 0.875rem;
}

.badge-lg {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
}

.empty-state {
    padding: 2rem;
}

.pagination-wrapper .pagination {
    margin-bottom: 0;
}

.table th a {
    color: inherit;
}

.table th a:hover {
    color: #fff;
    text-decoration: none;
}

.card-header h6 button {
    border: none;
    background: none;
    color: inherit;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }

    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }
}
</style>
@endsection
