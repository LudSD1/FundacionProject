

<div class="tab-pane fade" id="tab-foros" role="tabpanel" aria-labelledby="foros-tab">
    <!-- Header Mejorado -->
    <div class="forums-modern-header">
        <div class="header-content">
            <div class="header-info">
                <div class="header-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <div>
                    <h2 class="header-title">Foros de Discusión</h2>
                    <p class="header-subtitle">Comunidad de aprendizaje e intercambio de ideas</p>
                </div>
            </div>
            
            @if (auth()->user()->id == $cursos->docente_id)
            <div class="header-actions">
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearForo">
                    <i class="fas fa-plus-circle me-2"></i>
                    Nuevo Foro
                </button>
                <a href="{{ route('forosE', encrypt($cursos->id)) }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-trash-restore me-2"></i>
                    Foros Eliminados
                </a>
            </div>
            @endif
        </div>
        
        <!-- Estadísticas Rápidas -->
        <div class="forums-stats">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-icon total">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-number">{{ $foros->count() }}</h3>
                        <p class="stat-label">Total Foros</p>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon messages">
                        <i class="fas fa-comment-dots"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-number">{{ $foros->sum('foromensaje.count') }}</h3>
                        <p class="stat-label">Total Mensajes</p>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon active">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-number">{{ $foros->where('created_at', '>=', now()->subDays(7))->count() }}</h3>
                        <p class="stat-label">Activos (7 días)</p>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon views">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-number">{{ $foros->sum('vistas_count') ?? 0 }}</h3>
                        <p class="stat-label">Total Visitas</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Barra de Herramientas -->
    <div class="forums-toolbar">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="form-control search-input" placeholder="Buscar en foros..." id="searchForums">
                    <button class="btn btn-outline-secondary search-clear" type="button">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <div class="filter-buttons">
                    <button class="btn btn-outline-primary active" data-filter="all">
                        <i class="fas fa-th me-1"></i>Todos
                    </button>
                    <button class="btn btn-outline-primary" data-filter="recent">
                        <i class="fas fa-clock me-1"></i>Recientes
                    </button>
                    <button class="btn btn-outline-primary" data-filter="popular">
                        <i class="fas fa-fire me-1"></i>Populares
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Foros -->
    @if ($foros->count() > 0)
    <div class="forums-grid" id="forumsGrid">
        @foreach ($foros as $foro)
        <div class="forum-card" data-forum-name="{{ strtolower($foro->nombreForo) }}" data-messages="{{ $foro->foromensaje->count() }}">
            <div class="forum-card-header">
                <div class="forum-icon">
                    <i class="fas fa-comment-dots"></i>
                </div>
                <div class="forum-info">
                    <h3 class="forum-title">
                        <a href="{{ route('foro', encrypt($foro->id)) }}" class="forum-link">
                            {{ $foro->nombreForo }}
                        </a>
                    </h3>
                    @if($foro->SubtituloForo)
                    <p class="forum-subtitle">{{ $foro->SubtituloForo }}</p>
                    @endif
                </div>
                <div class="forum-status">
                    @if($foro->foromensaje->count() > 10)
                    <span class="badge bg-warning">Popular</span>
                    @elseif($foro->created_at->diffInDays(now()) <= 1)
                    <span class="badge bg-success">Nuevo</span>
                    @endif
                </div>
            </div>
            
            <div class="forum-card-body">
                <p class="forum-description">
                    {{ Str::limit($foro->descripcionForo ?? $foro->contenido, 200) }}
                </p>
                
                <div class="forum-meta">
                    <div class="meta-item">
                        <i class="fas fa-comments"></i>
                        <span>{{ $foro->foromensaje->count() }} mensajes</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-eye"></i>
                        <span>{{ $foro->vistas_count ?? 0 }} vistas</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-clock"></i>
                        <span>{{ $foro->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
            
            <div class="forum-card-footer">
                <div class="forum-actions">
                    <a href="{{ route('foro', encrypt($foro->id)) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-eye me-1"></i>
                        Ver Foro
                    </a>
                    
                    @if (auth()->user()->id == $cursos->docente_id)
                    <div class="admin-actions">
                        <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#modalEditarForo-{{ $foro->id }}"
                                data-bs-tooltip="tooltip" title="Editar foro">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form class="d-inline" action="{{ route('quitarForo', encrypt($foro->id)) }}" method="POST"
                              onsubmit="return confirm('¿Estás seguro de eliminar este foro?')">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm"
                                    data-bs-tooltip="tooltip" title="Eliminar foro">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
                
                @if($foro->fechaFin)
                <div class="forum-deadline">
                    <small class="text-muted">
                        <i class="fas fa-calendar-times me-1"></i>
                        Cierra: {{ $foro->fechaFin }}
                    </small>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- Estado Vacío Mejorado -->
    <div class="empty-forums-state">
        <div class="empty-content">
            <div class="empty-icon">
                <i class="fas fa-comments fa-4x"></i>
            </div>
            <h3 class="empty-title">No hay foros de discusión</h3>
            <p class="empty-text">Aún no se han creado temas para discutir en este curso.</p>
            
            @if (auth()->user()->id == $cursos->docente_id)
            <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#modalCrearForo">
                <i class="fas fa-plus-circle me-2"></i>
                Crear Primer Foro
            </button>
            @else
            <p class="text-muted">Contacta al instructor para sugerir temas de discusión.</p>
            @endif
        </div>
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
    --gradient-secondary: linear-gradient(135deg, #39a6cb 0%, #63becf 100%);
    --gradient-success: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    --gradient-warning: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    
    --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
    --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.12);
    --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.15);
    
    --border-radius: 12px;
    --border-radius-sm: 8px;
}

/* Header Mejorado */
.forums-modern-header {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-sm);
    margin-bottom: 2rem;
    overflow: hidden;
}

.header-content {
    padding: 2rem;
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
    font-size: 1.75rem;
}

.header-subtitle {
    color: #6c757d;
    margin: 0;
    font-size: 1.1rem;
}

.header-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

/* Estadísticas */
.forums-stats {
    background: #f8f9fa;
    padding: 1.5rem 2rem;
    border-top: 1px solid #e9ecef;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stat-icon.total {
    background: var(--gradient-primary);
}

.stat-icon.messages {
    background: var(--gradient-success);
}

.stat-icon.active {
    background: var(--gradient-warning);
}

.stat-icon.views {
    background: var(--gradient-secondary);
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0;
    color: var(--color-primary);
}

.stat-label {
    color: #6c757d;
    margin: 0;
    font-size: 0.9rem;
}

/* Barra de Herramientas */
.forums-toolbar {
    background: white;
    padding: 1.5rem;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-sm);
    margin-bottom: 1.5rem;
}

.search-container {
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

.filter-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
}

.filter-buttons .btn {
    border-radius: 20px;
    padding: 0.5rem 1rem;
    font-weight: 500;
}

.filter-buttons .btn.active {
    background: var(--color-primary);
    color: white;
    border-color: var(--color-primary);
}

/* Grid de Foros */
.forums-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 1.5rem;
}

/* Tarjetas de Foros */
.forum-card {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.forum-card:hover {
    border-color: var(--color-secondary);
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.forum-card-header {
    padding: 1.5rem 1.5rem 1rem;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    border-bottom: 1px solid #f8f9fa;
}

.forum-icon {
    width: 50px;
    height: 50px;
    border-radius: var(--border-radius-sm);
    background: var(--gradient-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.forum-info {
    flex: 1;
}

.forum-title {
    margin: 0 0 0.5rem 0;
    font-weight: 600;
}

.forum-link {
    color: var(--color-primary);
    text-decoration: none;
    transition: color 0.3s ease;
}

.forum-link:hover {
    color: var(--color-secondary);
}

.forum-subtitle {
    color: #6c757d;
    font-size: 0.9rem;
    margin: 0;
}

.forum-status {
    flex-shrink: 0;
}

.forum-card-body {
    padding: 1rem 1.5rem;
    flex: 1;
}

.forum-description {
    color: #6c757d;
    line-height: 1.5;
    margin-bottom: 1rem;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.forum-meta {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6c757d;
    font-size: 0.85rem;
}

.meta-item i {
    width: 16px;
    text-align: center;
}

.forum-card-footer {
    padding: 1rem 1.5rem;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.forum-actions {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.admin-actions {
    display: flex;
    gap: 0.25rem;
}

.forum-deadline {
    text-align: right;
}

/* Estado Vacío */
.empty-forums-state {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-sm);
    padding: 4rem 2rem;
    text-align: center;
}

.empty-content {
    max-width: 500px;
    margin: 0 auto;
}

.empty-icon {
    color: var(--color-secondary);
    margin-bottom: 2rem;
}

.empty-title {
    color: var(--color-primary);
    margin-bottom: 1rem;
    font-weight: 600;
}

.empty-text {
    color: #6c757d;
    margin-bottom: 2rem;
    font-size: 1.1rem;
}

/* Badges */
.badge {
    font-weight: 500;
    padding: 0.5rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
}

/* Botones */
.btn {
    border-radius: var(--border-radius-sm);
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
}

.btn:hover {
    transform: translateY(-1px);
}

.btn-primary {
    background: var(--gradient-primary);
}

.btn-primary:hover {
    background: var(--color-primary);
}

.btn-outline-primary {
    border: 1px solid var(--color-primary);
    color: var(--color-primary);
}

.btn-outline-primary:hover {
    background: var(--color-primary);
    color: white;
}

.btn-outline-warning {
    border: 1px solid var(--color-warning);
    color: var(--color-warning);
}

.btn-outline-warning:hover {
    background: var(--color-warning);
    color: white;
}

.btn-outline-danger {
    border: 1px solid var(--color-danger);
    color: var(--color-danger);
}

.btn-outline-danger:hover {
    background: var(--color-danger);
    color: white;
}

/* Responsive */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        text-align: center;
        padding: 1.5rem;
    }
    
    .header-info {
        flex-direction: column;
        text-align: center;
    }
    
    .header-actions {
        justify-content: center;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .stat-item {
        justify-content: center;
        text-align: center;
    }
    
    .forums-toolbar .row {
        gap: 1rem;
    }
    
    .filter-buttons {
        justify-content: center;
    }
    
    .forums-grid {
        grid-template-columns: 1fr;
    }
    
    .forum-card-header {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .forum-card-footer {
        flex-direction: column;
        text-align: center;
    }
    
    .forum-actions {
        justify-content: center;
    }
    
    .forum-meta {
        justify-content: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Búsqueda en tiempo real
    const searchInput = document.getElementById('searchForums');
    const searchClear = document.querySelector('.search-clear');
    const forumCards = document.querySelectorAll('.forum-card');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            
            forumCards.forEach(card => {
                const forumName = card.getAttribute('data-forum-name');
                if (forumName.includes(searchTerm)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
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
            
            forumCards.forEach(card => {
                card.style.display = 'flex';
            });
        });
        
        // Ocultar botón de limpiar inicialmente
        searchClear.style.display = 'none';
    }
    
    // Filtros
    const filterButtons = document.querySelectorAll('.filter-buttons .btn');
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Actualizar botones activos
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Aplicar filtro
            forumCards.forEach(card => {
                const messages = parseInt(card.getAttribute('data-messages'));
                
                if (filter === 'all') {
                    card.style.display = 'flex';
                } else if (filter === 'recent') {
                    // Mostrar foros recientes (últimos 7 días)
                    card.style.display = 'flex';
                } else if (filter === 'popular') {
                    // Mostrar foros populares (más de 5 mensajes)
                    if (messages > 5) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                }
            });
        });
    });
    
    // Inicializar tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-tooltip="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Efectos hover en tarjetas
    forumCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Confirmación antes de eliminar
    const deleteForms = document.querySelectorAll('form[action*="quitarForo"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('¿Estás seguro de que deseas eliminar este foro y todos sus mensajes?')) {
                e.preventDefault();
            }
        });
    });
});
</script>
