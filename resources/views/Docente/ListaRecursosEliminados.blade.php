@section('titulo')
    Lista de Recursos Eliminados
@endsection




@section('content')
<div class="resources-deleted-container">
    <!-- Header de la página -->
    <div class="page-header mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-trash-restore me-3"></i>
                    Recursos Eliminados
                </h1>
                <p class="page-subtitle text-muted">
                    Gestiona y restaura recursos que han sido eliminados del curso
                </p>
            </div>
            <a href="javascript:history.back()" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i> Volver al Curso
            </a>
        </div>
    </div>

    <!-- Panel de estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card danger">
                <div class="stats-icon">
                    <i class="fas fa-trash"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">{{ $recursos->where('cursos_id', $cursos->id)->count() }}</h3>
                    <p class="stats-label">Total Eliminados</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card warning">
                <div class="stats-icon">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">0</h3>
                    <p class="stats-label">Documentos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card info">
                <div class="stats-icon">
                    <i class="fas fa-video"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">0</h3>
                    <p class="stats-label">Multimedia</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card secondary">
                <div class="stats-icon">
                    <i class="fas fa-link"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">0</h3>
                    <p class="stats-label">Enlaces</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Barra de herramientas -->
    <div class="toolbar-section mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="form-control search-input" placeholder="Buscar recursos eliminados..." id="searchResources">
                    <button class="btn btn-outline-secondary search-clear" type="button">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary active" id="btnFiltrarTodos">
                        <i class="fas fa-list me-2"></i>Todos
                    </button>
                    <button type="button" class="btn btn-outline-primary" id="btnFiltrarRecientes">
                        <i class="fas fa-clock me-2"></i>Más Recientes
                    </button>
                    <button type="button" class="btn btn-outline-primary" id="btnFiltrarAntiguos">
                        <i class="fas fa-history me-2"></i>Más Antiguos
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Recursos Eliminados Mejorada -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-gradient-danger text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-trash-alt me-2"></i>
                    Lista de Recursos Eliminados
                </h5>
                <span class="badge bg-light text-danger">
                    {{ $recursos->where('cursos_id', $cursos->id)->count() }} elementos
                </span>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-container">
                <table class="table table-hover align-middle mb-0" id="tablaRecursosEliminados">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" width="60">#</th>
                            <th scope="col">Información del Recurso</th>
                            <th scope="col" width="120" class="text-center">Tipo</th>
                            <th scope="col" width="120" class="text-center">Estado</th>
                            <th scope="col" width="150" class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recursos as $recurso)
                            @if ($recurso->cursos_id == $cursos->id)
                                <tr class="resource-row" data-resource-name="{{ strtolower($recurso->nombreRecurso) }}" data-resource-type="{{ $recurso->tipoRecurso }}">
                                    <td class="text-muted fw-bold">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        <div class="resource-info">
                                            <h6 class="resource-title mb-1">
                                                <i class="fas fa-file-alt me-2 text-primary"></i>
                                                {{ $recurso->nombreRecurso }}
                                            </h6>
                                            @if($recurso->descripcionRecursos)
                                                <p class="resource-description text-muted mb-1">
                                                    <small>{{ Str::limit($recurso->descripcionRecursos, 100) }}</small>
                                                </p>
                                            @endif
                                            <div class="resource-meta">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    Eliminado: {{ $recurso->deleted_at ? $recurso->deleted_at->format('d/m/Y H:i') : 'Fecha no disponible' }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge resource-type-badge">
                                            <i class="fas fa-file me-1"></i>
                                            {{ ucfirst($recurso->tipoRecurso) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-danger">
                                            <i class="fas fa-trash me-1"></i> Eliminado
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('RestaurarRecurso', encrypt($recurso->id)) }}"
                                               class="btn btn-success btn-sm btn-action"
                                               data-bs-toggle="tooltip"
                                               title="Restaurar recurso"
                                               onclick="return confirm('¿Estás seguro de que deseas restaurar este recurso?')">
                                                <i class="fas fa-arrow-clockwise"></i>
                                                <span class="d-none d-md-inline">Restaurar</span>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-outline-info btn-sm btn-action"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalDetallesRecurso-{{ $recurso->id }}"
                                                    title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal de Detalles del Recurso -->
                                <div class="modal fade" id="modalDetallesRecurso-{{ $recurso->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    Detalles del Recurso
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="resource-details">
                                                            <h4 class="text-primary mb-3">{{ $recurso->nombreRecurso }}</h4>
                                                            
                                                            @if($recurso->descripcionRecursos)
                                                            <div class="mb-4">
                                                                <h6 class="text-muted mb-2">Descripción:</h6>
                                                                <p class="mb-0">{{ $recurso->descripcionRecursos }}</p>
                                                            </div>
                                                            @endif

                                                            <div class="resource-meta-grid">
                                                                <div class="meta-item">
                                                                    <i class="fas fa-tag me-2 text-warning"></i>
                                                                    <strong>Tipo:</strong>
                                                                    <span class="badge bg-primary ms-2">{{ ucfirst($recurso->tipoRecurso) }}</span>
                                                                </div>
                                                                <div class="meta-item">
                                                                    <i class="fas fa-calendar-plus me-2 text-success"></i>
                                                                    <strong>Creado:</strong>
                                                                    <span>{{ $recurso->created_at->format('d/m/Y H:i') }}</span>
                                                                </div>
                                                                <div class="meta-item">
                                                                    <i class="fas fa-calendar-times me-2 text-danger"></i>
                                                                    <strong>Eliminado:</strong>
                                                                    <span>{{ $recurso->deleted_at ? $recurso->deleted_at->format('d/m/Y H:i') : 'N/A' }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="resource-preview">
                                                            <div class="preview-card text-center">
                                                                <div class="preview-icon">
                                                                    @php
                                                                        $iconos = [
                                                                            'word' => 'fas fa-file-word',
                                                                            'excel' => 'fas fa-file-excel',
                                                                            'powerpoint' => 'fas fa-file-powerpoint',
                                                                            'pdf' => 'fas fa-file-pdf',
                                                                            'docs' => 'fab fa-google-drive',
                                                                            'imagen' => 'fas fa-image',
                                                                            'video' => 'fas fa-video',
                                                                            'audio' => 'fas fa-music',
                                                                            'youtube' => 'fab fa-youtube',
                                                                            'forms' => 'fas fa-wpforms',
                                                                            'drive' => 'fab fa-google-drive',
                                                                            'kahoot' => 'fas fa-gamepad',
                                                                            'canva' => 'fas fa-palette',
                                                                            'enlace' => 'fas fa-link',
                                                                            'archivos-adjuntos' => 'fas fa-paperclip',
                                                                        ];
                                                                        $icono = $iconos[$recurso->tipoRecurso] ?? 'fas fa-file';
                                                                    @endphp
                                                                    <i class="{{ $icono }} fa-3x text-primary"></i>
                                                                </div>
                                                                <h6 class="mt-3">{{ ucfirst($recurso->tipoRecurso) }}</h6>
                                                                <small class="text-muted">Tipo de recurso</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                <a href="{{ route('RestaurarRecurso', encrypt($recurso->id)) }}" 
                                                   class="btn btn-success"
                                                   onclick="return confirm('¿Estás seguro de que deseas restaurar este recurso?')">
                                                    <i class="fas fa-arrow-clockwise me-1"></i> Restaurar Recurso
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state text-center py-5">
                                        <i class="fas fa-trash-alt fa-4x text-muted mb-3"></i>
                                        <h5 class="text-muted">No hay recursos eliminados</h5>
                                        <p class="text-muted mb-4">Los recursos que elimines aparecerán aquí para su posible restauración.</p>
                                        <a href="javascript:history.back()" class="btn btn-primary">
                                            <i class="fas fa-arrow-left me-2"></i> Volver al Curso
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Mensaje de éxito -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle fa-lg me-3"></i>
                <div>
                    <h6 class="mb-1">¡Operación exitosa!</h6>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>

<style>
/* Variables CSS */
:root {
    --color-primary: #1a4789;
    --color-secondary: #39a6cb;
    --color-success: #28a745;
    --color-warning: #ffc107;
    --color-danger: #dc3545;
    --color-info: #17a2b8;
    
    --gradient-primary: linear-gradient(135deg, #1a4789 0%, #055c9d 100%);
    --gradient-danger: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    --gradient-success: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    --gradient-warning: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    --gradient-info: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    
    --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
    --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.12);
    --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.15);
    
    --border-radius: 12px;
    --border-radius-sm: 8px;
}

/* Contenedor Principal */
.resources-deleted-container {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
    padding: 2rem;
}

/* Header de página */
.page-header {
    background: white;
    padding: 2rem;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-sm);
    margin-bottom: 2rem;
}

.page-title {
    color: var(--color-primary);
    font-weight: 700;
    margin-bottom: 0.5rem;
    font-size: 2rem;
}

.page-subtitle {
    font-size: 1.1rem;
    color: #6c757d;
}

/* Tarjetas de estadísticas */
.stats-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 1.5rem;
    box-shadow: var(--shadow-sm);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
    border-left: 4px solid;
    height: 100%;
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.stats-card.danger {
    border-left-color: var(--color-danger);
}

.stats-card.warning {
    border-left-color: var(--color-warning);
}

.stats-card.info {
    border-left-color: var(--color-info);
}

.stats-card.secondary {
    border-left-color: var(--color-secondary);
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stats-card.danger .stats-icon {
    background: var(--gradient-danger);
}

.stats-card.warning .stats-icon {
    background: var(--gradient-warning);
}

.stats-card.info .stats-icon {
    background: var(--gradient-info);
}

.stats-card.secondary .stats-icon {
    background: var(--gradient-primary);
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

/* Barra de herramientas */
.toolbar-section {
    background: white;
    padding: 1.5rem;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-sm);
    margin-bottom: 1.5rem;
}

.search-box {
    position: relative;
    display: flex;
    align-items: center;
}

.search-icon {
    position: absolute;
    left: 1rem;
    color: #6c757d;
    z-index: 2;
}

.search-input {
    padding-left: 3rem;
    padding-right: 3rem;
    border-radius: var(--border-radius-sm);
    border: 1px solid #e0e0e0;
    transition: all 0.3s ease;
}

.search-input:focus {
    border-color: var(--color-primary);
    box-shadow: 0 0 0 0.2rem rgba(26, 71, 137, 0.15);
}

.search-clear {
    position: absolute;
    right: 0.5rem;
    border: none;
    background: transparent;
    color: #6c757d;
    z-index: 2;
    display: none;
}

/* Tabla mejorada */
.table-container {
    overflow: hidden;
    border-radius: 0 0 var(--border-radius) var(--border-radius);
}

.table {
    margin: 0;
    font-size: 0.95rem;
}

.table th {
    border-bottom: 2px solid #e9ecef;
    font-weight: 600;
    color: var(--color-primary);
    padding: 1.25rem 0.75rem;
    background: #f8f9fa;
}

.table td {
    padding: 1.25rem 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid #f8f9fa;
}

.resource-row:hover {
    background-color: #f8f9fa;
}

.resource-title {
    color: var(--color-primary);
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.resource-description {
    font-size: 0.9rem;
    line-height: 1.4;
}

.resource-meta {
    font-size: 0.8rem;
}

/* Badges */
.badge {
    font-weight: 500;
    padding: 0.5rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
}

.resource-type-badge {
    background: #e3f2fd;
    color: var(--color-primary);
}

/* Botones de acción */
.btn-action {
    border-radius: var(--border-radius-sm);
    padding: 0.5rem 0.75rem;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-action:hover {
    transform: translateY(-1px);
}

.btn-success {
    background: var(--gradient-success);
    color: white;
}

.btn-success:hover {
    background: var(--color-success);
    color: white;
}

.btn-outline-info {
    border: 1px solid var(--color-info);
    color: var(--color-info);
}

.btn-outline-info:hover {
    background: var(--color-info);
    color: white;
}

/* Modal de detalles */
.resource-details h4 {
    font-weight: 600;
}

.resource-meta-grid {
    display: grid;
    gap: 1rem;
    margin-top: 1.5rem;
}

.meta-item {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: var(--border-radius-sm);
}

.meta-item i {
    width: 20px;
    text-align: center;
}

.preview-card {
    background: #f8f9fa;
    border-radius: var(--border-radius);
    padding: 2rem;
    height: 100%;
}

.preview-icon {
    margin-bottom: 1rem;
}

/* Estados vacíos */
.empty-state {
    padding: 3rem 2rem;
}

.empty-state i {
    margin-bottom: 1rem;
}

/* Alertas mejoradas */
.alert {
    border: none;
    border-radius: var(--border-radius);
    padding: 1.25rem 1.5rem;
}

.alert-success {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
    border-left: 4px solid var(--color-success);
}

/* Responsive */
@media (max-width: 768px) {
    .resources-deleted-container {
        padding: 1rem;
    }
    
    .page-header {
        padding: 1.5rem 1rem;
        text-align: center;
    }
    
    .stats-card {
        flex-direction: column;
        text-align: center;
        padding: 1rem;
    }
    
    .toolbar-section .row {
        gap: 1rem;
    }
    
    .btn-group {
        width: 100%;
    }
    
    .btn-group .btn {
        flex: 1;
    }
    
    .table-responsive {
        font-size: 0.9rem;
    }
    
    .btn-action span {
        display: none;
    }
    
    .resource-info {
        text-align: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Búsqueda en tiempo real
    const searchInput = document.getElementById('searchResources');
    const searchClear = document.querySelector('.search-clear');
    const resourceRows = document.querySelectorAll('.resource-row');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            
            resourceRows.forEach(row => {
                const resourceName = row.getAttribute('data-resource-name');
                if (resourceName.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Mostrar/ocultar botón de limpiar
            searchClear.style.display = searchTerm ? 'block' : 'none';
        });
        
        // Limpiar búsqueda
        searchClear.addEventListener('click', function() {
            searchInput.value = '';
            searchInput.focus();
            searchClear.style.display = 'none';
            
            resourceRows.forEach(row => {
                row.style.display = '';
            });
        });
        
        // Ocultar botón de limpiar inicialmente
        searchClear.style.display = 'none';
    }
    
    // Filtrar por tipo de recurso
    const filterButtons = document.querySelectorAll('.btn-group .btn');
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filterType = this.id.replace('btnFiltrar', '').toLowerCase();
            
            // Actualizar botones activos
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Aplicar filtro
            resourceRows.forEach(row => {
                if (filterType === 'todos') {
                    row.style.display = '';
                } else if (filterType === 'recientes') {
                    // Lógica para filtrar por fecha reciente
                    row.style.display = '';
                } else if (filterType === 'antiguos') {
                    // Lógica para filtrar por fecha antigua
                    row.style.display = '';
                }
            });
        });
    });
    
    // Inicializar tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Efectos hover en tarjetas de estadísticas
    const statsCards = document.querySelectorAll('.stats-card');
    statsCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Confirmación antes de restaurar
    const restoreButtons = document.querySelectorAll('a[href*="RestaurarRecurso"]');
    restoreButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('¿Estás seguro de que deseas restaurar este recurso?')) {
                e.preventDefault();
            }
        });
    });
    
    // Auto-ocultar alerta de éxito después de 5 segundos
    const successAlert = document.querySelector('.alert-success');
    if (successAlert) {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(successAlert);
            bsAlert.close();
        }, 5000);
    }
});
</script>
@endsection

@include('layout')
