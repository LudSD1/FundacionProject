@section('titulo')
    Lista de Foros Eliminados
@endsection





@section('content')

@foreach ($foro as $foroItem)
           <div class="modal fade" id="modalDetallesForo-{{ $foroItem->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    Detalles del Foro
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="foro-details">
                                                    <h6 class="text-primary">{{ $foroItem->nombreForo }}</h6>
                                                    @if($foroItem->SubtituloForo)
                                                        <p class="text-muted">{{ $foroItem->SubtituloForo }}</p>
                                                    @endif
                                                    <div class="mb-3">
                                                        <strong>Descripción:</strong>
                                                        <p class="mt-1">{{ $foroItem->descripcionForo ?: 'Sin descripción' }}</p>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <small class="text-muted">
                                                                <i class="fas fa-calendar-plus me-1"></i>
                                                                Creado: {{ $foroItem->created_at->format('d/m/Y') }}
                                                            </small>
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="text-muted">
                                                                <i class="fas fa-calendar-times me-1"></i>
                                                                Eliminado: {{ $foroItem->deleted_at ? $foroItem->deleted_at->format('d/m/Y') : 'N/A' }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                <a href="{{ route('restaurar', encrypt($foroItem->id)) }}" class="btn btn-success">
                                                    <i class="fas fa-arrow-clockwise me-1"></i> Restaurar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
@endforeach
 
<div class="container my-5">
    <!-- Header de la página -->
    <div class="page-header mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-trash-restore me-3"></i>
                    Foros Eliminados
                </h1>
                <p class="page-subtitle text-muted">
                    Gestiona y restaura foros que han sido eliminados del curso
                </p>
            </div>
            <a href="javascript:history.back()" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i> Volver al Curso
            </a>
        </div>
    </div>

    <!-- Panel de estadísticas -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stats-card danger">
                <div class="stats-icon">
                    <i class="fas fa-trash"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">{{ $foro->where('cursos_id', $cursos->id)->count() }}</h3>
                    <p class="stats-label">Foros Eliminados</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card warning">
                <div class="stats-icon">
                    <i class="fas fa-history"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">0</h3>
                    <p class="stats-label">Pendientes Restauración</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card success">
                <div class="stats-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">0</h3>
                    <p class="stats-label">Restaurados Hoy</p>
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
                    <input type="text" class="form-control search-input" placeholder="Buscar foros eliminados..." id="searchForos">
                    <button class="btn btn-outline-secondary search-clear" type="button">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary" id="btnFiltrarTodos">
                        <i class="fas fa-list me-2"></i>Todos
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="btnFiltrarRecientes">
                        <i class="fas fa-clock me-2"></i>Más Recientes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Foros Eliminados Mejorada -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-gradient-danger text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-trash-alt me-2"></i>
                    Lista de Foros Eliminados
                </h5>
                <span class="badge bg-light text-danger">
                    {{ $foro->where('cursos_id', $cursos->id)->count() }} elementos
                </span>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-container">
                <table class="table table-hover align-middle mb-0" id="tablaForosEliminados">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" width="60">#</th>
                            <th scope="col">Información del Foro</th>
                            <th scope="col" width="120" class="text-center">Estado</th>
                            <th scope="col" width="150" class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($foro as $foroItem)
                            @if ($foroItem->cursos_id == $cursos->id)
                                <tr class="foro-row" data-foro-name="{{ strtolower($foroItem->nombreForo) }}">
                                    <td class="text-muted fw-bold">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        <div class="foro-info">
                                            <h6 class="foro-title mb-1">
                                                <i class="fas fa-comments me-2 text-primary"></i>
                                                {{ $foroItem->nombreForo }}
                                            </h6>
                                            @if($foroItem->SubtituloForo)
                                                <p class="foro-subtitle text-muted mb-1">
                                                    <small>{{ $foroItem->SubtituloForo }}</small>
                                                </p>
                                            @endif
                                            <div class="foro-meta">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    Eliminado: {{ $foroItem->deleted_at ? $foroItem->deleted_at->format('d/m/Y') : 'Fecha no disponible' }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-danger">
                                            <i class="fas fa-trash me-1"></i> Eliminado
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('restaurar', encrypt($foroItem->id)) }}"
                                               class="btn btn-success btn-sm btn-action"
                                               data-bs-toggle="tooltip"
                                               title="Restaurar foro">
                                                <i class="fas fa-arrow-clockwise"></i>
                                                <span class="d-none d-md-inline">Restaurar</span>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-outline-info btn-sm btn-action"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalDetallesForo-{{ $foroItem->id }}"
                                                    title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal de Detalles del Foro -->
                     
                            @endif
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state text-center py-5">
                                        <i class="fas fa-trash-alt fa-4x text-muted mb-3"></i>
                                        <h5 class="text-muted">No hay foros eliminados</h5>
                                        <p class="text-muted mb-4">Los foros que elimines aparecerán aquí para su posible restauración.</p>
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
    
    --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
    --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.12);
    --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.15);
    
    --border-radius: 12px;
    --border-radius-sm: 8px;
}

/* Header de página */
.page-header {
    background: white;
    padding: 2rem;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-sm);
}

.page-title {
    color: var(--color-primary);
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.page-subtitle {
    font-size: 1.1rem;
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

.stats-card.success {
    border-left-color: var(--color-success);
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.stats-card.danger .stats-icon {
    background: var(--gradient-danger);
    color: white;
}

.stats-card.warning .stats-icon {
    background: var(--gradient-warning);
    color: white;
}

.stats-card.success .stats-icon {
    background: var(--gradient-success);
    color: white;
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

/* Barra de búsqueda mejorada */
.search-box {
    position: relative;
    display: flex;
    align-items: center;
}

.search-icon {
    position: absolute;
    left: 1rem;
    color: #6c757d;
    z-index: 3;
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
    z-index: 3;
}

/* Tabla mejorada */
.table-container {
    overflow: hidden;
    border-radius: 0 0 var(--border-radius) var(--border-radius);
}

.table {
    margin: 0;
}

.table th {
    border-bottom: 2px solid #e9ecef;
    font-weight: 600;
    color: var(--color-primary);
    padding: 1rem 0.75rem;
}

.table td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
}

.foro-row:hover {
    background-color: #f8f9fa;
}

.foro-title {
    color: var(--color-primary);
    font-weight: 600;
}

.foro-subtitle {
    font-size: 0.9rem;
}

.foro-meta {
    font-size: 0.8rem;
}

/* Botones de acción */
.btn-action {
    border-radius: var(--border-radius-sm);
    padding: 0.5rem 0.75rem;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
}

.btn-action:hover {
    transform: translateY(-1px);
}

.btn-success {
    background: var(--gradient-success);
}

.btn-success:hover {
    background: var(--color-success);
}

/* Badges */
.badge {
    font-weight: 500;
    padding: 0.5rem 0.75rem;
    border-radius: 20px;
}

/* Estados vacíos */
.empty-state {
    padding: 3rem 2rem;
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

/* Toolbar */
.toolbar-section {
    background: white;
    padding: 1.5rem;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-sm);
}

/* Responsive */
@media (max-width: 768px) {
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
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Búsqueda en tiempo real
    const searchInput = document.getElementById('searchForos');
    const searchClear = document.querySelector('.search-clear');
    const foroRows = document.querySelectorAll('.foro-row');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            
            foroRows.forEach(row => {
                const foroName = row.getAttribute('data-foro-name');
                if (foroName.includes(searchTerm)) {
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
            
            foroRows.forEach(row => {
                row.style.display = '';
            });
        });
        
        // Ocultar botón de limpiar inicialmente
        searchClear.style.display = 'none';
    }
    
    // Filtrar por más recientes
    const btnFiltrarRecientes = document.getElementById('btnFiltrarRecientes');
    if (btnFiltrarRecientes) {
        btnFiltrarRecientes.addEventListener('click', function() {
            // Aquí implementarías la lógica de filtrado por fecha
            alert('Funcionalidad de filtrado por fecha próximamente');
        });
    }
    
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
    const restoreButtons = document.querySelectorAll('a[href*="restaurar"]');
    restoreButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('¿Estás seguro de que deseas restaurar este foro?')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endsection

@include('layout')
