@extends('layout')

@section('titulo', 'Lista de Asistencia: ' . $cursos->nombreCurso)

@section('content')
<div class="container-fluid py-4">
    <!-- Header Mejorado -->
    <div class="page-header mb-4">
        <div class="header-content">
            <div class="header-info">
                <div class="header-icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div>
                    <h1 class="header-title">Registrar Asistencia</h1>
                    <p class="header-subtitle">Gestión de asistencia para el curso: {{ $cursos->nombreCurso }}</p>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('Curso', encrypt($cursos->id)) }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i> Volver al Curso
                </a>
            </div>
        </div>
    </div>

    <!-- Panel de Navegación Rápida -->
    <div class="navigation-cards ">
        <div class="row">
            {{-- <div class="col-md-4">
                <a href="{{ route('darasistencias', encrypt($cursos->id)) }}" class="nav-card">
                    <div class="nav-card-icon primary">
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <div class="nav-card-content">
                        <h5>Asistencia Personalizada</h5>
                        <p>Registro individual por estudiante</p>
                    </div>
                    <i class="fas fa-chevron-right nav-card-arrow"></i>
                </a>
            </div> --}}
            <div class="col-md-6">
                <a href="{{ route('historialAsistencias', encrypt($cursos->id)) }}" class="nav-card">
                    <div class="nav-card-icon success">
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="nav-card-content">
                        <h5>Historial de Asistencias</h5>
                        <p>Consulta registros anteriores</p>
                    </div>
                    <i class="fas fa-chevron-right nav-card-arrow"></i>
                </a>
            </div>
            <div class="col-md-6">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number">{{ $inscritos->where('cursos_id', $cursos->id)->count() }}</h3>
                        <p class="stats-label">Estudiantes Inscritos</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario de Asistencia -->
    <div class="attendance-container">
        <div class="attendance-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Registro de Asistencia
                </h4>
                <div class="selection-info">
                    <span class="badge bg-primary" id="selectionCounter">0 seleccionados</span>
                </div>
            </div>
        </div>

        <form action="{{ route('darasistenciasPostMultiple', encrypt($cursos->id)) }}" method="POST" id="attendanceForm">
            @csrf
            
            <!-- Fecha y Controles -->
            <div class="attendance-controls mb-4">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fecha_asistencia" class="form-label fw-semibold">
                                <i class="fas fa-calendar me-2 text-primary"></i>Fecha de Asistencia
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-primary text-white">
                                    <i class="fas fa-calendar"></i>
                                </span>
                                <input type="date" 
                                       class="form-control modern-input" 
                                       id="fecha_asistencia" 
                                       name="fecha_asistencia"
                                       value="{{ now()->format('Y-m-d') }}"
                                       @if($cursos->fecha_fin && now() > $cursos->fecha_fin) disabled @endif>
                            </div>
                            @if($cursos->fecha_fin && now() > $cursos->fecha_fin)
                            <small class="text-warning mt-1">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                El curso ha finalizado - Solo modo consulta
                            </small>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="quick-actions">
                            <h6 class="mb-2">Acciones Rápidas:</h6>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-success btn-sm" id="markAllPresent">
                                    <i class="fas fa-check-circle me-1"></i> Todos Presentes
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" id="markAllAbsent">
                                    <i class="fas fa-times-circle me-1"></i> Todos Ausentes
                                </button>
                                <button type="button" class="btn btn-secondary btn-sm" id="clearAll">
                                    <i class="fas fa-eraser me-1"></i> Limpiar Todo
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Estudiantes -->
            <div class="table-responsive">
                <table class="table table-hover attendance-table">
                    <thead class="table-primary">
                        <tr>
                            <th width="5%" class="text-center">#</th>
                            <th width="35%">Estudiante</th>
                            <th width="40%">Tipo de Asistencia</th>
                            <th width="20%" class="text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inscritos as $index => $inscrito)
                            @if($inscrito->cursos_id == $cursos->id)
                                <tr class="attendance-row">
                                    <td class="text-center fw-bold text-primary">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        <div class="student-info">
                                            <div class="student-avatar bg-primary">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div class="student-details">
                                                <h6 class="mb-0 student-name">
                                                    {{ $inscrito->estudiantes->name }}
                                                    {{ $inscrito->estudiantes->lastname1 }}
                                                    {{ $inscrito->estudiantes->lastname2 }}
                                                </h6>
                                                <small class="text-muted">email: {{ $inscrito->estudiantes->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="hidden" name="asistencia[{{ $index }}][inscritos_id]"
                                               value="{{ $inscrito->id }}">
                                        <input type="hidden" name="asistencia[{{ $index }}][curso_id]"
                                               value="{{ $cursos->id }}">

                                        <select name="asistencia[{{ $index }}][tipo_asistencia]"
                                                class="form-select attendance-select"
                                                data-row-index="{{ $index }}"
                                                @if($cursos->fecha_fin && now() > $cursos->fecha_fin) disabled @endif>
                                            <option value="">Seleccione tipo...</option>
                                            <option value="Presente">🟢 Presente</option>
                                            <option value="Retraso">🟡 Retraso</option>
                                            <option value="Licencia">🔵 Licencia</option>
                                            <option value="Falta">🔴 Falta</option>
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <span class="attendance-status status-pending" id="status-{{ $index }}">
                                            <i class="fas fa-clock me-1"></i>
                                            <span>Pendiente</span>
                                        </span>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state text-center py-5">
                                        <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No hay estudiantes inscritos</h5>
                                        <p class="text-muted">No se pueden registrar asistencias sin estudiantes.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(auth()->user()->hasRole('Docente') && (!$cursos->fecha_fin || now() <= $cursos->fecha_fin) && $inscritos->where('cursos_id', $cursos->id)->count() > 0)
                <!-- Botón de Guardar -->
                <div class="save-section mt-4">
                    <div class="row justify-content-center">
                        <div class="col-md-6 text-center">
                            <button type="submit" class="btn btn-primary btn-lg w-100" id="saveAttendanceBtn">
                                <i class="fas fa-save me-2"></i>
                                <span id="saveButtonText">Guardar Asistencias (0/{{ $inscritos->where('cursos_id', $cursos->id)->count() }})</span>
                            </button>
                            <small class="text-muted mt-2 d-block">
                                <i class="fas fa-info-circle me-1"></i>
                                Solo se registrarán las asistencias con tipo seleccionado
                            </small>
                        </div>
                    </div>
                </div>
            @endif
        </form>
    </div>
</div>

<style>
/* Variables CSS con tu paleta de colores */
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

/* Header de Página */
.page-header {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-sm);
    padding: 2rem;
    margin-bottom: 2rem;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1.5rem;
}

.header-info {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.header-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--gradient-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
}

.header-title {
    color: var(--color-primary);
    font-weight: 700;
    margin-bottom: 0.5rem;
    font-size: 2rem;
}

.header-subtitle {
    color: #6c757d;
    margin: 0;
    font-size: 1.1rem;
}

/* Tarjetas de Navegación */
.navigation-cards {
    margin-bottom: 2rem;
}

.nav-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 1.5rem;
    box-shadow: var(--shadow-sm);
    display: flex;
    align-items: center;
    gap: 1rem;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    height: 100%;
}

.nav-card:hover {
    border-color: var(--color-primary);
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
    text-decoration: none;
    color: inherit;
}

.nav-card-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    flex-shrink: 0;
}

.nav-card-icon.primary {
    background: var(--gradient-primary);
}

.nav-card-icon.success {
    background: var(--gradient-success);
}

.nav-card-content {
    flex: 1;
}

.nav-card-content h5 {
    color: var(--color-primary);
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.nav-card-content p {
    color: #6c757d;
    margin: 0;
    font-size: 0.9rem;
}

.nav-card-arrow {
    color: var(--color-primary);
    font-size: 1.25rem;
}

/* Tarjeta de Estadísticas */
.stats-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 1.5rem;
    box-shadow: var(--shadow-sm);
    display: flex;
    align-items: center;
    gap: 1rem;
    border-left: 4px solid var(--color-primary);
    height: 100%;
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: var(--gradient-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.stats-number {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    color: var(--color-primary);
}

.stats-label {
    color: #6c757d;
    margin: 0;
    font-size: 0.9rem;
}

/* Contenedor de Asistencia */
.attendance-container {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}

.attendance-header {
    background: var(--gradient-primary);
    color: white;
    padding: 1.5rem 2rem;
}

.attendance-header h4 {
    margin: 0;
    font-weight: 600;
}

.selection-info .badge {
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
}

/* Controles de Asistencia */
.attendance-controls {
    padding: 1.5rem 2rem;
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
}

.modern-input {
    border: 1px solid #e0e0e0;
    border-radius: var(--border-radius-sm);
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: #fff;
}

.modern-input:focus {
    border-color: var(--color-primary);
    box-shadow: 0 0 0 0.2rem rgba(26, 71, 137, 0.15);
}

.input-group-text {
    background: var(--color-primary);
    border-color: var(--color-primary);
    color: white;
}

.quick-actions h6 {
    color: var(--color-primary);
    font-weight: 600;
}

/* Tabla de Asistencia */
.attendance-table {
    margin: 0;
}

.attendance-table th {
    background: var(--color-primary);
    color: white;
    font-weight: 600;
    padding: 1.25rem 0.75rem;
    border: none;
}

.attendance-table td {
    padding: 1.25rem 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid #f8f9fa;
}

.attendance-row:hover {
    background-color: #f8f9fa;
}

/* Información del Estudiante */
.student-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.student-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.student-name {
    color: var(--color-primary);
    font-weight: 600;
    margin-bottom: 0.25rem;
}

/* Select de Asistencia */
.attendance-select {
    border: 2px solid #e9ecef;
    border-radius: var(--border-radius-sm);
    padding: 0.75rem 1rem;
    font-weight: 500;
    transition: all 0.3s ease;
    cursor: pointer;
}

.attendance-select:focus {
    border-color: var(--color-primary);
    box-shadow: 0 0 0 0.2rem rgba(26, 71, 137, 0.15);
}

.attendance-select.border-success {
    border-color: var(--color-success);
    background: rgba(40, 167, 69, 0.05);
}

.attendance-select.border-warning {
    border-color: var(--color-warning);
    background: rgba(255, 193, 7, 0.05);
}

.attendance-select.border-info {
    border-color: var(--color-info);
    background: rgba(23, 162, 184, 0.05);
}

.attendance-select.border-danger {
    border-color: var(--color-danger);
    background: rgba(220, 53, 69, 0.05);
}

/* Estados de Asistencia */
.attendance-status {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.status-present {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.status-late {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.status-excuse {
    background: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}

.status-absent {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* Sección de Guardar */
.save-section {
    padding: 2rem;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
}

#saveAttendanceBtn {
    border-radius: var(--border-radius-sm);
    font-weight: 600;
    padding: 1rem 2rem;
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

#saveAttendanceBtn:disabled {
    background: #6c757d;
    border-color: #6c757d;
    transform: none;
}

/* Estados Vacíos */
.empty-state {
    padding: 3rem 2rem;
}

.empty-state i {
    margin-bottom: 1rem;
}

/* Responsive */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        text-align: center;
    }
    
    .header-info {
        flex-direction: column;
        text-align: center;
    }
    
    .attendance-controls .row {
        flex-direction: column;
        gap: 1rem;
    }
    
    .quick-actions .btn-group {
        width: 100%;
    }
    
    .quick-actions .btn-group .btn {
        flex: 1;
    }
    
    .student-info {
        flex-direction: column;
        text-align: center;
    }
}

/* Animaciones */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.attendance-status.status-present {
    animation: pulse 2s infinite;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const attendanceForm = document.getElementById('attendanceForm');
    const attendanceSelects = document.querySelectorAll('.attendance-select');
    const saveAttendanceBtn = document.getElementById('saveAttendanceBtn');
    const selectionCounter = document.getElementById('selectionCounter');
    const saveButtonText = document.getElementById('saveButtonText');
    const markAllPresentBtn = document.getElementById('markAllPresent');
    const markAllAbsentBtn = document.getElementById('markAllAbsent');
    const clearAllBtn = document.getElementById('clearAll');
    const totalStudents = {{ $inscritos->where('cursos_id', $cursos->id)->count() }};

    // Inicializar contadores
    updateSelectionCount();

    // Función para actualizar contadores
    function updateSelectionCount() {
        const selectedCount = Array.from(attendanceSelects).filter(select => select.value !== '').length;
        
        selectionCounter.textContent = `${selectedCount} seleccionados`;
        
        if (saveButtonText) {
            saveButtonText.textContent = `Guardar Asistencias (${selectedCount}/${totalStudents})`;
        }

        // Actualizar estado del botón de guardar
        if (saveAttendanceBtn) {
            if (selectedCount === 0) {
                saveAttendanceBtn.disabled = true;
                saveAttendanceBtn.style.opacity = '0.6';
            } else {
                saveAttendanceBtn.disabled = false;
                saveAttendanceBtn.style.opacity = '1';
            }
        }
    }

    // Función para actualizar estado visual
    function updateStatusDisplay(selectElement) {
        const rowIndex = selectElement.getAttribute('data-row-index');
        const statusElement = document.getElementById(`status-${rowIndex}`);
        
        if (!statusElement) return;

        // Limpiar clases
        statusElement.className = 'attendance-status';
        
        switch(selectElement.value) {
            case 'Presente':
                statusElement.classList.add('status-present');
                statusElement.innerHTML = '<i class="fas fa-check-circle me-1"></i><span>Presente</span>';
                break;
            case 'Retraso':
                statusElement.classList.add('status-late');
                statusElement.innerHTML = '<i class="fas fa-clock me-1"></i><span>Retraso</span>';
                break;
            case 'Licencia':
                statusElement.classList.add('status-excuse');
                statusElement.innerHTML = '<i class="fas fa-info-circle me-1"></i><span>Con Licencia</span>';
                break;
            case 'Falta':
                statusElement.classList.add('status-absent');
                statusElement.innerHTML = '<i class="fas fa-times-circle me-1"></i><span>Ausente</span>';
                break;
            default:
                statusElement.classList.add('status-pending');
                statusElement.innerHTML = '<i class="fas fa-clock me-1"></i><span>Pendiente</span>';
                break;
        }
    }

    // Función para actualizar estilos del select
    function updateSelectStyle(selectElement) {
        selectElement.classList.remove('border-success', 'border-warning', 'border-info', 'border-danger');
        
        switch(selectElement.value) {
            case 'Presente':
                selectElement.classList.add('border-success');
                break;
            case 'Retraso':
                selectElement.classList.add('border-warning');
                break;
            case 'Licencia':
                selectElement.classList.add('border-info');
                break;
            case 'Falta':
                selectElement.classList.add('border-danger');
                break;
        }
    }

    // Event listeners para los selects
    attendanceSelects.forEach(select => {
        select.addEventListener('change', function() {
            updateSelectStyle(this);
            updateStatusDisplay(this);
            updateSelectionCount();
        });
    });

    // Acciones rápidas
    if (markAllPresentBtn) {
        markAllPresentBtn.addEventListener('click', function() {
            attendanceSelects.forEach(select => {
                select.value = 'Presente';
                updateSelectStyle(select);
                updateStatusDisplay(select);
            });
            updateSelectionCount();
        });
    }

    if (markAllAbsentBtn) {
        markAllAbsentBtn.addEventListener('click', function() {
            attendanceSelects.forEach(select => {
                select.value = 'Falta';
                updateSelectStyle(select);
                updateStatusDisplay(select);
            });
            updateSelectionCount();
        });
    }

    if (clearAllBtn) {
        clearAllBtn.addEventListener('click', function() {
            attendanceSelects.forEach(select => {
                select.value = '';
                updateSelectStyle(select);
                updateStatusDisplay(select);
            });
            updateSelectionCount();
        });
    }

    // Validación del formulario
    attendanceForm.addEventListener('submit', function(e) {
        const selectedCount = Array.from(attendanceSelects).filter(select => select.value !== '').length;
        
        if (selectedCount === 0) {
            e.preventDefault();
            showAlert('Debe seleccionar al menos un tipo de asistencia antes de guardar.', 'warning');
            return;
        }

        if (!confirm(`¿Está seguro de registrar la asistencia para ${selectedCount} estudiante(s)?`)) {
            e.preventDefault();
            return;
        }

        // Mostrar estado de carga
        if (saveAttendanceBtn) {
            const originalText = saveAttendanceBtn.innerHTML;
            saveAttendanceBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Guardando...';
            saveAttendanceBtn.disabled = true;

            // Restaurar después de 5 segundos (en caso de error)
            setTimeout(() => {
                saveAttendanceBtn.innerHTML = originalText;
                saveAttendanceBtn.disabled = false;
            }, 5000);
        }
    });

    // Función para mostrar alertas (puedes personalizar según tu sistema)
    function showAlert(message, type) {
        alert(message); // Puedes reemplazar con SweetAlert o tu sistema de alertas
    }
});
</script>


@endsection
