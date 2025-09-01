@extends('layout')

@section('titulo', 'Lista de Asistencia: ' . $cursos->nombreCurso)

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <a href="{{route('Curso', encrypt($cursos->id)) }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <h6 class="m-0 font-weight-bold text-primary">Registrar Asistencia</h6>
            <div>
                <a href="{{ route('darasistencias', encrypt($cursos->id)) }}" class="btn btn-info">
                    <i class="fas fa-user-edit"></i> Asistencia Personalizada
                </a>
            </div>
            <div>
                <a href="{{ route('historialAsistencias', encrypt($cursos->id)) }}" class="btn btn-info">
                    <i class="fas fa-user-edit"></i> Historial de Asistencias
                </a>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('darasistenciasPostMultiple', encrypt($cursos->id)) }}" method="POST">
                @csrf
                <div class="form-row align-items-center mb-4">
                    <div class="col-md-4">
                        <label for="fecha_asistencia">Fecha:</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                            </div>
                            <input type="date" class="form-control" id="fecha_asistencia" name="fecha_asistencia"
                                   value="{{ now()->format('Y-m-d') }}"
                                   @if($cursos->fecha_fin && now() > $cursos->fecha_fin) disabled @endif>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable">
                        <thead class="">
                            <tr>
                                <th width="5%">#</th>
                                <th width="40%">Estudiante</th>
                                <th width="40%">Tipo de Asistencia</th>
                                <th width="15%">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inscritos as $index => $inscrito)
                                @if($inscrito->cursos_id == $cursos->id)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            {{ $inscrito->estudiantes->name }}
                                            {{ $inscrito->estudiantes->lastname1 }}
                                            {{ $inscrito->estudiantes->lastname2 }}
                                        </td>
                                        <td>
                                            <input type="hidden" name="asistencia[{{ $index }}][inscritos_id]"
                                                   value="{{ $inscrito->id }}">
                                            <input type="hidden" name="asistencia[{{ $index }}][curso_id]"
                                                   value="{{ $cursos->id }}">

                                            <select name="asistencia[{{ $index }}][tipo_asistencia]"
                                                    class="form-control attendance-select"
                                                    data-row-index="{{ $index }}"
                                                    @if($cursos->fecha_fin && now() > $cursos->fecha_fin) disabled @endif>
                                                <option value="">Seleccione...</option>
                                                <option value="Presente">Presente</option>
                                                <option value="Retraso">Retraso</option>
                                                <option value="Licencia">Licencia</option>
                                                <option value="Falta">Falta</option>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <span class="status-badge status-pending" id="status-{{ $index }}">
                                                <i class="fas fa-clock text-warning"></i> Pendiente
                                            </span>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="fas fa-users-slash fa-2x mb-2"></i>
                                        <h5>No hay estudiantes inscritos</h5>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(auth()->user()->hasRole('Docente') && (!$cursos->fecha_fin || now() <= $cursos->fecha_fin))
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg" id="save-btn">
                            <i class="fas fa-save"></i> Guardar Asistencias
                        </button>
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                Solo se registrarán las asistencias con tipo seleccionado
                            </small>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Set current date
    function getCurrentDate() {
        const today = new Date();
        const year = today.getFullYear();
        let month = today.getMonth() + 1;
        let day = today.getDate();

        month = month < 10 ? `0${month}` : month;
        day = day < 10 ? `0${day}` : day;

        return `${year}-${month}-${day}`;
    }

    document.getElementById('fecha_asistencia').value = getCurrentDate();

    // Función para contar selecciones realizadas
    function updateSelectionCount() {
        const selects = document.querySelectorAll('.attendance-select');
        const selectedCount = Array.from(selects).filter(select => select.value !== '').length;
        const totalCount = selects.length;

        const saveBtn = document.getElementById('save-btn');
        if (saveBtn) {
            if (selectedCount === 0) {
                saveBtn.classList.add('btn-secondary');
                saveBtn.classList.remove('btn-primary');
                saveBtn.innerHTML = '<i class="fas fa-save"></i> Guardar Asistencias (0/' + totalCount + ')';
            } else {
                saveBtn.classList.remove('btn-secondary');
                saveBtn.classList.add('btn-primary');
                saveBtn.innerHTML = '<i class="fas fa-save"></i> Guardar Asistencias (' + selectedCount + '/' + totalCount + ')';
            }
        }
    }

    // Función para confirmar envío del formulario
    function confirmSubmission() {
        const selects = document.querySelectorAll('.attendance-select');
        const selectedCount = Array.from(selects).filter(select => select.value !== '').length;

        if (selectedCount === 0) {
            alert('Debe seleccionar al menos un tipo de asistencia antes de guardar.');
            return false;
        }

        return confirm(`¿Está seguro de registrar la asistencia para ${selectedCount} estudiante(s)?`);
    }

    // Agregar evento al formulario
    document.getElementById('attendance-form').addEventListener('submit', function(e) {
        if (!confirmSubmission()) {
            e.preventDefault();
        }
    });

    // Función para actualizar el estado visual
    function updateStatusDisplay(selectElement, statusElement) {
        const value = selectElement.value;

        // Limpiar todas las clases de estado
        statusElement.className = 'status-badge';

        switch(value) {
            case 'Presente':
                statusElement.innerHTML = '<i class="fas fa-check-circle text-success"></i> Presente';
                statusElement.classList.add('status-present');
                break;
            case 'Retraso':
                statusElement.innerHTML = '<i class="fas fa-clock text-warning"></i> Retraso';
                statusElement.classList.add('status-late');
                break;
            case 'Licencia':
                statusElement.innerHTML = '<i class="fas fa-info-circle text-info"></i> Con Licencia';
                statusElement.classList.add('status-excuse');
                break;
            case 'Falta':
                statusElement.innerHTML = '<i class="fas fa-times-circle text-danger"></i> Ausente';
                statusElement.classList.add('status-absent');
                break;
            default:
                statusElement.innerHTML = '<i class="fas fa-clock text-warning"></i> Pendiente';
                statusElement.classList.add('status-pending');
                break;
        }
    }

    // Cambiar color del select y actualizar estado según la asistencia
    document.querySelectorAll('.attendance-select').forEach(select => {
        select.addEventListener('change', function() {
            // Actualizar estilos del select
            this.classList.remove('border-success', 'border-warning', 'border-info', 'border-danger');

            if(this.value === 'Presente') {
                this.classList.add('border-success');
            } else if(this.value === 'Retraso') {
                this.classList.add('border-warning');
            } else if(this.value === 'Licencia') {
                this.classList.add('border-info');
            } else if(this.value === 'Falta') {
                this.classList.add('border-danger');
            }

            // Actualizar el estado visual
            const rowIndex = this.getAttribute('data-row-index');
            const statusElement = document.getElementById(`status-${rowIndex}`);
            if(statusElement) {
                updateStatusDisplay(this, statusElement);
            }

            // Actualizar contador de selecciones
            updateSelectionCount();
        });
    });

    // Inicializar contador al cargar la página
    updateSelectionCount();
});
</script>

<style>
.attendance-select.border-success {
    border-color: #28a745 !important;
}
.attendance-select.border-warning {
    border-color: #ffc107 !important;
}
.attendance-select.border-info {
    border-color: #17a2b8 !important;
}
.attendance-select.border-danger {
    border-color: #dc3545 !important;
}

.status-badge {
    font-weight: 500;
    padding: 4px 8px;
    border-radius: 4px;
    display: inline-block;
    min-width: 100px;
}

.status-pending {
    background-color: #fff3cd;
    color: #856404;
}

.status-present {
    background-color: #d4edda;
    color: #155724;
}

.status-late {
    background-color: #fff3cd;
    color: #856404;
}

.status-excuse {
    background-color: #d1ecf1;
    color: #0c5460;
}

.status-absent {
    background-color: #f8d7da;
    color: #721c24;
}

/* Estilos para alertas personalizadas */
.alert {
    border-radius: 8px;
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.alert i {
    margin-right: 8px;
}

.alert ul {
    padding-left: 20px;
}

.alert-success {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    border-left: 4px solid #28a745;
}

.alert-warning {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    border-left: 4px solid #ffc107;
}

.alert-danger {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    border-left: 4px solid #dc3545;
}

/* Animación para el botón de guardar */
#save-btn {
    transition: all 0.3s ease;
}

#save-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}
</style>
@endsection
