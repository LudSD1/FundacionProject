<div class="tab-pane fade" id="tab-recursos" role="tabpanel" aria-labelledby="recursos-tab">
    <div class="resources-modern-container">
        <!-- Header del Panel -->
        <div class="resources-header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="header-content">
                    <i class="fas fa-book-open header-icon"></i>
                    <div>
                        <h2 class="header-title">Material de Apoyo</h2>
                        <p class="header-subtitle">Recursos complementarios para tu aprendizaje</p>
                    </div>
                </div>

                @if (auth()->user()->id == $cursos->docente_id)
                    <div class="header-actions">
                        <a href="{{ route('CrearRecursos', encrypt($cursos->id)) }}" class="btn btn-primary btn-action"
                            data-bs-toggle="modal" data-bs-target="#modalCrearRecurso">
                            <i class="fas fa-plus-circle me-2"></i>
                            Nuevo Recurso
                        </a>
                        <a href="{{ route('ListaRecursosEliminados', encrypt($cursos->id)) }}"
                            class="btn btn-outline-primary btn-action">
                            <i class="fas fa-trash-restore me-2"></i>
                            Recursos Eliminados
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Contador de Recursos -->
        <div class="resources-stats">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-icon total">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-number">{{ $recursos->count() }}</h3>
                        <p class="stat-label">Total Recursos</p>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon documents">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-number">0</h3>
                        <p class="stat-label">Documentos</p>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon media">
                        <i class="fas fa-video"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-number">0</h3>
                        <p class="stat-label">Multimedia</p>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon links">
                        <i class="fas fa-link"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-number">0</h3>
                        <p class="stat-label">Enlaces</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barra de Filtros y Búsqueda -->
        <div class="resources-toolbar">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="search-container">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="form-control search-input" placeholder="Buscar recursos..."
                            id="searchResources">
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
                        <button class="btn btn-outline-primary" data-filter="document">
                            <i class="fas fa-file-pdf me-1"></i>Documentos
                        </button>
                        <button class="btn btn-outline-primary" data-filter="media">
                            <i class="fas fa-video me-1"></i>Multimedia
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid de Recursos -->
        @if ($recursos->count() > 0)
            <div class="resources-grid" id="resourcesGrid">
                @foreach ($recursos as $recurso)
                    <div class="resource-card" data-resource-type="{{ $recurso->tipoRecurso ?? 'document' }}"
                        data-resource-name="{{ strtolower($recurso->nombreRecurso) }}">
                        <div class="resource-header">
                            <div class="resource-icon">
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
                                <i class="{{ $icono }}"></i>
                            </div>
                            <div class="resource-badge">
                                <span class="badge">{{ ucfirst($recurso->tipoRecurso) }}</span>
                            </div>
                        </div>

                        <div class="resource-body">
                            <h5 class="resource-title">{{ $recurso->nombreRecurso }}</h5>
                            <p class="resource-description">{!! $recurso->descripcionRecursos !!}</p>

                            @if ($recurso->created_at)
                                <div class="resource-meta">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ $recurso->created_at->format('d/m/Y') }}
                                    </small>
                                </div>
                            @endif
                        </div>

                        <div class="resource-footer">
                            <div class="resource-actions">
                                @if (isset($recurso->archivoRecurso))
                                    <a href="{{ route('recursos.descargar', encrypt($recurso->id)) }}"
                                        class="btn btn-download" data-bs-toggle="tooltip" title="Descargar recurso">
                                        <i class="fas fa-download"></i>
                                        <span>Descargar</span>
                                    </a>
                                @endif

                                @if (auth()->user()->id == $cursos->docente_id)
                                    <div class="admin-actions">
                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modalEditarRecurso-{{ $recurso->id }}">
                                            <i class="fas fa-edit me-1"></i> Editar
                                        </button>
                                        <a href="{{ route('quitarRecurso', encrypt($recurso->id)) }}"
                                            class="btn btn-delete" data-bs-toggle="tooltip" title="Eliminar recurso"
                                            onclick="return confirm('¿Estás seguro de eliminar este recurso?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Estado Vacío -->
            <div class="empty-state">
                <div class="empty-content">
                    <h3 class="empty-title">No hay recursos disponibles</h3>
                    <p class="empty-text">El instructor aún no ha subido material de apoyo para este curso.</p>

                    @if (auth()->user()->id == $cursos->docente_id)
                        <a href="{{ route('CrearRecursos', encrypt($cursos->id)) }}" class="btn btn-primary btn-action"
                            data-bs-toggle="modal" data-bs-target="#modalCrearRecurso">
                            Nuevo Recurso
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <style>
        /* Variables CSS */


        /* Contenedor Principal */
        .resources-modern-container {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        /* Header del Panel */
        .resources-header {
            background: var(white);
            color: white;
        }

        .header-content {
            padding: 2rem display: flex;
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


        .btn-action {
            border-radius: var(--border-radius-sm);
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            display: flex;
            align-items: center;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* Estadísticas */
        .resources-stats {
            padding: 1.5rem 2rem;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .stat-item {
            background: white;
            border-radius: var(--border-radius-sm);
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
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

        .stat-icon.documents {
            background: var(--gradient-danger);
        }

        .stat-icon.media {
            background: var(--gradient-warning);
        }

        .stat-icon.links {
            background: var(--gradient-success);
        }

        .stat-number {
            font-size: 1.75rem;
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
        .resources-toolbar {
            padding: 1.5rem 2rem;
            background: white;
            border-bottom: 1px solid #e9ecef;
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

        /* Grid de Recursos */
        .resources-grid {
            padding: 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
        }

        /* Tarjetas de Recursos */
        .resource-card {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .resource-card:hover {
            border-color: var(--color-secondary);
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .resource-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .resource-icon {
            width: 50px;
            height: 50px;
            border-radius: var(--border-radius-sm);
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .resource-badge .badge {
            background: #e9ecef;
            color: #6c757d;
            font-size: 0.7rem;
            font-weight: 500;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
        }

        .resource-body {
            flex: 1;
            margin-bottom: 1.5rem;
        }

        .resource-title {
            color: var(--color-primary);
            font-weight: 600;
            margin-bottom: 0.75rem;
            font-size: 1.1rem;
            line-height: 1.4;
        }

        .resource-description {
            color: #6c757d;
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .resource-meta {
            border-top: 1px solid #f8f9fa;
            padding-top: 0.75rem;
        }

        .resource-footer {
            margin-top: auto;
        }

        .resource-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-download {
            background: var(--gradient-success);
            color: white;
            border: none;
            border-radius: var(--border-radius-sm);
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-download:hover {
            background: var(--color-success);
            color: white;
            transform: translateY(-1px);
        }

        .admin-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-edit,
        .btn-delete {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-edit {
            background: #e3f2fd;
            color: var(--color-info);
        }

        .btn-edit:hover {
            background: var(--color-info);
            color: white;
            transform: translateY(-1px);
        }

        .btn-delete {
            background: #f8d7da;
            color: var(--color-danger);
        }

        .btn-delete:hover {
            background: var(--color-danger);
            color: white;
            transform: translateY(-1px);
        }

        /* Estado Vacío */
        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
        }

        .empty-content {
            max-width: 400px;
            margin: 0 auto;
        }

        .empty-title {
            color: var(--color-primary);
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .empty-text {
            color: #6c757d;
            margin-bottom: 2rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .resources-header {
                padding: 1.5rem;
            }

            .header-content {
                flex-direction: column;
                text-align: center;
                gap: 0.5rem;
            }

            .header-actions {
                flex-direction: column;
                width: 100%;
                margin-top: 1rem;
            }

            .btn-action {
                justify-content: center;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .resources-toolbar .row {
                gap: 1rem;
            }

            .filter-buttons {
                justify-content: center;
            }

            .resources-grid {
                grid-template-columns: 1fr;
                padding: 1rem;
            }

            .resource-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .admin-actions {
                justify-content: center;
                margin-top: 0.5rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Búsqueda en tiempo real
            const searchInput = document.getElementById('searchResources');
            const searchClear = document.querySelector('.search-clear');
            const resourceCards = document.querySelectorAll('.resource-card');

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase().trim();

                    resourceCards.forEach(card => {
                        const resourceName = card.getAttribute('data-resource-name');
                        if (resourceName.includes(searchTerm)) {
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

                    resourceCards.forEach(card => {
                        card.style.display = 'flex';
                    });
                });

                // Ocultar botón de limpiar inicialmente
                searchClear.style.display = 'none';
            }

            // Filtros por tipo
            const filterButtons = document.querySelectorAll('.filter-buttons .btn');
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');

                    // Actualizar botones activos
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');

                    // Aplicar filtro
                    resourceCards.forEach(card => {
                        const resourceType = card.getAttribute('data-resource-type');
                        if (filter === 'all' || resourceType === filter) {
                            card.style.display = 'flex';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });

            // Inicializar tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Efectos hover en tarjetas
            resourceCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });

                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Confirmación antes de eliminar
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if (!confirm('¿Estás seguro de que deseas eliminar este recurso?')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</div>
