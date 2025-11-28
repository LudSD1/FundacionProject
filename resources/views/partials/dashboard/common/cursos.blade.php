@php
    $userRole = auth()->user()->getRoleNames()->first();

    // Verificar si el usuario tiene cursos de manera segura
    $hasNoCourses = false;

    if ($userRole === 'Estudiante') {
        // Para estudiantes: verificar si tiene inscripciones
        $inscritosCount = auth()->user()->inscritos()->count();
        $hasNoCourses = $inscritosCount === 0;
    } elseif ($userRole === 'Docente') {
        // Para docentes: verificar si la variable $cursos existe y tiene contenido
        $hasNoCourses = !isset($cursos) || $cursos->isEmpty();
    } else {
        // Para administradores u otros roles
        $hasNoCourses = !isset($cursos) || $cursos->isEmpty();
    }
@endphp





@if ($userRole === 'Estudiante')
    @include('partials.dashboard.common.modal-pago')
@endif



<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elementos del DOM
        const elements = {
            gridBtn: document.getElementById('btnGrid'),
            listBtn: document.getElementById('btnList'),
            container: document.getElementById('coursesContainer'),
            searchInput: document.getElementById('courseSearch'),
            filterSelect: document.getElementById('courseFilter'),
            noResults: document.getElementById('noResults')
        };

        // Verificar que los elementos existen
        if (!elements.container) {
            console.error('Container not found');
            return;
        }

        // Manejador de vistas
        const viewManager = {
            init() {
                this.loadPreference();
                this.bindEvents();
            },

            loadPreference() {
                const savedView = localStorage.getItem('courseViewPreference') || 'grid';
                if (savedView === 'list') {
                    this.setListView();
                } else {
                    this.setGridView();
                }
            },

            savePreference(view) {
                try {
                    localStorage.setItem('courseViewPreference', view);
                } catch (e) {
                    console.warn('No se pudo guardar la preferencia de vista:', e);
                }
            },

            setGridView() {
                elements.container.classList.remove('list-view');
                if (elements.gridBtn) {
                    elements.gridBtn.classList.add('active');
                }
                if (elements.listBtn) {
                    elements.listBtn.classList.remove('active');
                }
                this.savePreference('grid');
            },

            setListView() {
                elements.container.classList.add('list-view');
                if (elements.gridBtn) {
                    elements.gridBtn.classList.remove('active');
                }
                if (elements.listBtn) {
                    elements.listBtn.classList.add('active');
                }
                this.savePreference('list');
            },

            bindEvents() {
                if (elements.gridBtn) {
                    elements.gridBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        this.setGridView();
                    });
                }

                if (elements.listBtn) {
                    elements.listBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        this.setListView();
                    });
                }
            }
        };

        // Manejador de filtros
        const filterManager = {
            init() {
                this.bindEvents();
            },

            filterCourses() {
                const query = elements.searchInput?.value.toLowerCase().trim() || '';
                const filter = elements.filterSelect?.value || 'all';
                const items = document.querySelectorAll('.course-item');
                let visibleCount = 0;

                items.forEach(item => {
                    const title = item.dataset.title || '';
                    const progress = parseInt(item.dataset.progress) || 0;
                    const type = item.dataset.type || '';
                    const status = item.dataset.status || '';

                    const matchesSearch = !query || title.includes(query);
                    const matchesFilter = this.matchesFilter(progress, filter, type, status);

                    if (matchesSearch && matchesFilter) {
                        item.style.display = '';
                        // Agregar un pequeño delay para la animación
                        setTimeout(() => {
                            item.classList.add('show');
                        }, 50);
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                        item.classList.remove('show');
                    }
                });

                // Mostrar/ocultar mensaje de "no results"
                if (elements.noResults) {
                    elements.noResults.style.display = visibleCount === 0 && items.length > 0 ? 'block' :
                        'none';
                }
            },

            matchesFilter(progress, filter, type, status) {
                switch (filter) {
                    case 'completados':
                        return progress === 100 || status === 'completado';
                    case 'activos':
                        return progress < 100 || status === 'activo';
                    case 'congresos':
                        return type === 'congreso';
                    case 'all':
                    default:
                        return true;
                }
            },

            bindEvents() {
                if (elements.searchInput) {
                    // Usar input para búsqueda en tiempo real
                    elements.searchInput.addEventListener('input', () => {
                        this.filterCourses();
                    });

                    // También escuchar keyup para mayor compatibilidad
                    elements.searchInput.addEventListener('keyup', () => {
                        this.filterCourses();
                    });
                }

                if (elements.filterSelect) {
                    elements.filterSelect.addEventListener('change', () => {
                        this.filterCourses();
                    });
                }
            }
        };

        // Animaciones de entrada
        const animateCards = () => {
            const cards = document.querySelectorAll('.course-item');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('show');
                }, index * 100);
            });
        };

        // Inicializar todo
        try {
            viewManager.init();
            filterManager.init();

            // Mostrar cards con animación después de un breve delay
            setTimeout(() => {
                animateCards();
            }, 100);

            console.log('Dashboard initialized successfully');
        } catch (error) {
            console.error('Error initializing dashboard:', error);
        }
    });
</script>
<style>
    .dashboard-courses {
        background: linear-gradient(135deg, #f8fafc 0%, #e9ecef 100%);
        min-height: 100vh;
    }

    /* Header Mejorado */
    .dashboard-header-enhanced {
        background: var(--gradient-primary);
        color: white;
        padding: 3rem 0;
        position: relative;
        overflow: hidden;
    }

    .dashboard-header-enhanced::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
        opacity: 0.1;
    }

    .dashboard-title-wrapper {
        position: relative;
        z-index: 2;
    }

    .dashboard-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .dashboard-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 0;
    }

    .dashboard-controls-enhanced {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: var(--border-radius);
        padding: 1.5rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .search-box-enhanced {
        position: relative;
        flex: 1;
    }

    .search-box-enhanced i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--color-primary);
    }

    .search-box-enhanced input {
        padding-left: 2.5rem;
        border: none;
        border-radius: var(--border-radius-sm);
        background: white;
        height: 48px;
    }

    .filters-group-enhanced {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .select-wrapper-enhanced {
        position: relative;
        min-width: 160px;
    }

    .select-wrapper-enhanced select {
        padding-right: 2.5rem;
        border: none;
        border-radius: var(--border-radius-sm);
        background: white;
        height: 48px;
        cursor: pointer;
    }

    .select-icon-enhanced {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--color-primary);
        pointer-events: none;
    }

    .view-controls-enhanced {
        display: flex;
        background: rgba(255, 255, 255, 0.2);
        border-radius: var(--border-radius-sm);
        padding: 4px;
    }

    .view-btn-enhanced {
        background: none;
        border: none;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: var(--border-radius-sm);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .view-btn-enhanced.active {
        background: rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(10px);
    }

    /* Tabs Mejorados */
    .course-tabs-enhanced {
        background: white;
        border-radius: var(--border-radius);
        padding: 1rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-sm);
        border: none;
    }

    .course-tabs-enhanced .nav-link {
        border: none;
        border-radius: var(--border-radius-sm);
        padding: 1rem 1.5rem;
        margin: 0 0.25rem;
        color: var(--color-muted);
        font-weight: 600;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .course-tabs-enhanced .nav-link::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 3px;
        background: var(--color-primary);
        transition: all 0.3s ease;
        transform: translateX(-50%);
    }

    .course-tabs-enhanced .nav-link.active {
        color: var(--color-primary);
        background: rgba(57, 166, 203, 0.1);
    }

    .course-tabs-enhanced .nav-link.active::before {
        width: 100%;
    }

    .course-tabs-enhanced .nav-link .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }

    /* Cards de Cursos Mejoradas */
    .course-grid-enhanced {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .course-list-enhanced {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .course-card-enhanced {
        background: white;
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .course-card-enhanced:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }

    .course-card-enhanced.list-view {
        flex-direction: row;
        height: auto;
    }

    .course-card-enhanced.list-view .course-image-enhanced {
        width: 200px;
        height: 150px;
        flex-shrink: 0;
    }

    .course-card-enhanced.list-view .course-content-enhanced {
        flex: 1;
    }

    .course-image-enhanced {
        position: relative;
        height: 200px;
        overflow: hidden;
    }

    .course-image-enhanced img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .course-card-enhanced:hover .course-image-enhanced img {
        transform: scale(1.05);
    }

    .course-badge-enhanced {
        position: absolute;
        top: 1rem;
        right: 1rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
    }

    .course-badge-enhanced.completed {
        background: rgba(40, 167, 69, 0.9);
        color: white;
    }

    .course-badge-enhanced.teacher {
        background: rgba(57, 166, 203, 0.9);
        color: white;
    }

    .course-badge-enhanced.congress {
        background: rgba(255, 193, 7, 0.9);
        color: #212529;
    }

    .course-content-enhanced {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .course-title-enhanced {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--color-primary);
        margin-bottom: 1rem;
        line-height: 1.4;
    }

    .course-meta-enhanced {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .course-meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--color-muted);
        font-size: 0.875rem;
    }

    .progress-section-enhanced {
        margin-bottom: 1.5rem;
    }

    .progress-header {
        display: flex;
        justify-content: between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .progress-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--color-primary);
    }

    .progress-value {
        font-size: 0.875rem;
        font-weight: 700;
        color: var(--color-success);
    }

    .progress-bar-enhanced {
        height: 6px;
        border-radius: 10px;
        background: #e9ecef;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        border-radius: 10px;
        background: var(--gradient-success);
        transition: width 1s ease-in-out;
    }

    .course-actions-enhanced {
        margin-top: auto;
    }

    .btn-course-action {
        width: 100%;
        border: none;
        border-radius: var(--border-radius-sm);
        padding: 0.75rem 1rem;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-course-action:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-course-primary {
        background: var(--gradient-primary);
        color: white;
    }

    .btn-course-success {
        background: var(--gradient-success);
        color: white;
    }

    .btn-course-warning {
        background: var(--gradient-warning);
        color: white;
    }

    /* Estados Especiales */
    .payment-status {
        background: rgba(255, 193, 7, 0.1);
        border: 1px solid rgba(255, 193, 7, 0.3);
        border-radius: var(--border-radius-sm);
        padding: 0.5rem;
        text-align: center;
        font-size: 0.8rem;
        color: var(--color-warning);
    }

    /* Estado Vacío Mejorado */
    .empty-state-enhanced {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-sm);
        margin: 2rem 0;
    }

    .empty-state-icon {
        font-size: 4rem;
        color: var(--color-secondary);
        opacity: 0.5;
        margin-bottom: 1.5rem;
    }

    .empty-state-enhanced h3 {
        color: var(--color-primary);
        margin-bottom: 1rem;
    }

    .empty-state-enhanced p {
        color: var(--color-muted);
        margin-bottom: 2rem;
        font-size: 1.1rem;
    }

    /* Alertas Mejoradas */
    .alert-enhanced {
        border: none;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        margin: 1rem 0;
    }

    .alert-info-enhanced {
        background: rgba(23, 162, 184, 0.1);
        color: var(--color-info);
        border-left: 4px solid var(--color-info);
    }

    /* No Results */
    .no-results-enhanced {
        text-align: center;
        padding: 3rem 2rem;
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-sm);
        margin: 2rem 0;
    }

    .no-results-enhanced i {
        font-size: 3rem;
        color: var(--color-muted);
        margin-bottom: 1rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .dashboard-title {
            font-size: 2rem;
        }

        .filters-group-enhanced {
            flex-direction: column;
            gap: 1rem;
        }

        .course-grid-enhanced {
            grid-template-columns: 1fr;
        }

        .course-card-enhanced.list-view {
            flex-direction: column;
        }

        .course-card-enhanced.list-view .course-image-enhanced {
            width: 100%;
            height: 200px;
        }

        .course-tabs-enhanced .nav-link {
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
        }
    }

    /* Animaciones */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .course-card-enhanced {
        animation: fadeInUp 0.5s ease forwards;
    }

    .course-card-enhanced:nth-child(even) {
        animation-delay: 0.1s;
    }

    .course-card-enhanced:nth-child(odd) {
        animation-delay: 0.2s;
    }
</style>

<div class="dashboard-courses">
    <!-- Header Mejorado -->
    <div class="dashboard-header-enhanced">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="dashboard-title-wrapper ">
                        <h2 class="dashboard-title text-white">
                            <i class="fas fa-graduation-cap me-3"></i>
                            {{ $userRole === 'Estudiante' ? 'Mis Cursos' : 'Cursos que Impartes' }}
                        </h2>
                        <p class="dashboard-subtitle">
                            {{ $userRole === 'Estudiante' ? 'Gestiona y continúa con tu aprendizaje' : 'Administra los cursos que impartes' }}
                        </p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="dashboard-controls-enhanced">
                        <div class="filters-group-enhanced">
                            <div class="search-box-enhanced">
                                <i class="fas fa-search"></i>
                                <input type="search" id="courseSearch" placeholder="Buscar curso..."
                                    class="form-control">
                            </div>
                            <div class="select-wrapper-enhanced">
                                <select class="form-select" id="courseFilter">
                                    <option value="all">Todos los cursos</option>
                                    <option value="activos">En progreso</option>
                                    <option value="completados">Completados</option>
                                    <option value="congresos">Congresos</option>
                                </select>
                                <span class="select-icon-enhanced"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="view-controls-enhanced">
                                <button id="btnGrid" class="view-btn-enhanced active" title="Vista de cuadrícula">
                                    <i class="fas fa-th-large"></i>
                                </button>
                                <button id="btnList" class="view-btn-enhanced" title="Vista de lista">
                                    <i class="fas fa-list"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        @if ($hasNoCourses)
            <!-- Estado Vacío Mejorado -->
            <div class="empty-state-enhanced">
                <div class="empty-state-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <h3>No tienes cursos {{ $userRole === 'Estudiante' ? 'inscritos' : 'asignados' }}</h3>
                <p>
                    @if ($userRole === 'Estudiante')
                        Explora nuestro catálogo y comienza tu viaje de aprendizaje
                    @else
                        Contacta con el administrador para comenzar a impartir cursos
                    @endif
                </p>
                @if ($userRole === 'Estudiante')
                    <a href="{{ route('lista.cursos.congresos') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-search me-2"></i> Explorar Cursos
                    </a>
                @endif
            </div>
        @else
            @if ($userRole === 'Estudiante')
                <!-- Tabs Mejorados para Estudiantes -->
                <ul class="nav course-tabs-enhanced" id="courseTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="cursos-tab" data-bs-toggle="tab" data-bs-target="#cursos"
                            type="button" role="tab" aria-controls="cursos" aria-selected="true">
                            <i class="fas fa-book me-2"></i> Cursos
                            <span
                                class="badge bg-primary ms-2">{{ $inscritos->where('cursos.tipo', '!=', 'congreso')->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="congresos-tab" data-bs-toggle="tab" data-bs-target="#congresos"
                            type="button" role="tab" aria-controls="congresos" aria-selected="false">
                            <i class="fas fa-calendar-alt me-2"></i> Congresos
                            <span
                                class="badge bg-warning ms-2">{{ $inscritos->where('cursos.tipo', 'congreso')->count() }}</span>
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="courseTabsContent">
                    <!-- Tab Cursos -->
                    <div class="tab-pane fade show active" id="cursos" role="tabpanel">
                        <div class="course-grid-enhanced" id="cursosContainer">
                            @php
                                $cursosRegulares = $inscritos->filter(function ($inscrito) {
                                    return auth()->user()->id == $inscrito->estudiante_id &&
                                        $inscrito->cursos &&
                                        !$inscrito->cursos->deleted_at &&
                                        $inscrito->cursos->tipo != 'congreso';
                                });
                            @endphp

                            @if ($cursosRegulares->count() > 0)
                                @foreach ($cursosRegulares as $inscrito)
                                    <div class="course-card-enhanced" data-progress="{{ $inscrito->progreso ?? 0 }}"
                                        data-type="curso" data-title="{{ strtolower($inscrito->cursos->nombreCurso) }}"
                                        data-status="{{ ($inscrito->progreso ?? 0) == 100 ? 'completado' : 'activo' }}">

                                        <div class="course-image-enhanced">
                                            @php
                                                $imagenRuta = $inscrito->cursos->imagen;
                                                $imagenExiste =
                                                    $imagenRuta &&
                                                    \Illuminate\Support\Facades\Storage::exists($imagenRuta);
                                            @endphp
                                            <img src="{{ $imagenExiste ? asset('storage/' . $imagenRuta) : asset('assets/img/course-default.jpg') }}"
                                                alt="{{ $inscrito->cursos->nombreCurso }}" loading="lazy">

                                            @if (($inscrito->progreso ?? 0) == 100)
                                                <div class="course-badge-enhanced completed">
                                                    <i class="fas fa-check-circle me-1"></i> Completado
                                                </div>
                                            @endif
                                        </div>

                                        <div class="course-content-enhanced">
                                            <h3 class="course-title-enhanced">{{ $inscrito->cursos->nombreCurso }}</h3>

                                            <div class="course-meta-enhanced">
                                                <span class="course-meta-item">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ $inscrito->created_at->format('d/m/Y') }}
                                                </span>
                                                <span class="course-meta-item">
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ $inscrito->cursos->duracion ?? 'N/A' }} horas
                                                </span>
                                            </div>

                                            @if (isset($inscrito->progreso))
                                                <div class="progress-section-enhanced">
                                                    <div class="progress-header">
                                                        <span class="progress-label">Tu progreso</span>
                                                        <span class="progress-value">{{ $inscrito->progreso }}%</span>
                                                    </div>
                                                    <div class="progress-bar-enhanced">
                                                        <div class="progress-fill"
                                                            style="width: {{ $inscrito->progreso }}%"></div>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="course-actions-enhanced">
                                                @if ($inscrito->pago_completado)
                                                    <a href="{{ route('Curso', encrypt($inscrito->cursos_id)) }}"
                                                        class="btn-course-action btn-course-primary">
                                                        <i class="fas fa-play-circle me-2"></i>
                                                        Continuar Curso
                                                    </a>
                                                @else
                                                    <button type="button"
                                                        class="btn-course-action btn-course-warning"
                                                        data-bs-toggle="modal" data-bs-target="#pagoModal"
                                                        data-inscrito-id="{{ $inscrito->id }}"
                                                        data-curso-id="{{ $inscrito->cursos->id }}"
                                                        data-curso-nombre="{{ $inscrito->cursos->nombreCurso }}">
                                                        <i class="fas fa-credit-card me-2"></i>
                                                        Completar Pago
                                                    </button>

                                                    @if ($inscrito->created_at->diffInDays(now()) < 2)
                                                        <div class="payment-status mt-2">
                                                            <i class="fas fa-hourglass-half me-1"></i>
                                                            Pago en revisión
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="alert-enhanced alert-info-enhanced">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>No tienes cursos inscritos aún</strong> -
                                    <a href="{{ route('lista.cursos.congresos') }}" class="alert-link">Explora
                                        nuestro catálogo</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Tab Congresos -->
                    <div class="tab-pane fade" id="congresos" role="tabpanel">
                        <div class="course-grid-enhanced" id="congresosContainer">
                            @php
                                $congresos = $inscritos->filter(function ($inscrito) {
                                    return auth()->user()->id == $inscrito->estudiante_id &&
                                        $inscrito->cursos &&
                                        !$inscrito->cursos->deleted_at &&
                                        $inscrito->cursos->tipo == 'congreso';
                                });
                            @endphp

                            @if ($congresos->count() > 0)
                                @foreach ($congresos as $inscrito)
                                    <div class="course-card-enhanced" data-progress="{{ $inscrito->progreso ?? 0 }}"
                                        data-type="congreso"
                                        data-title="{{ strtolower($inscrito->cursos->nombreCurso) }}"
                                        data-status="{{ ($inscrito->progreso ?? 0) == 100 ? 'completado' : 'activo' }}">

                                        <div class="course-image-enhanced">
                                            @php
                                                $imagenRuta = $inscrito->cursos->imagen;
                                                $imagenExiste =
                                                    $imagenRuta &&
                                                    \Illuminate\Support\Facades\Storage::exists($imagenRuta);
                                            @endphp
                                            <img src="{{ $imagenExiste ? asset('storage/' . $imagenRuta) : asset('assets/img/course-default.jpg') }}"
                                                alt="{{ $inscrito->cursos->nombreCurso }}" loading="lazy">

                                            <div class="course-badge-enhanced congress">
                                                <i class="fas fa-calendar-star me-1"></i> Congreso
                                            </div>
                                        </div>

                                        <div class="course-content-enhanced">
                                            <h3 class="course-title-enhanced">{{ $inscrito->cursos->nombreCurso }}
                                            </h3>

                                            <div class="course-meta-enhanced">
                                                <span class="course-meta-item">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ $inscrito->created_at->format('d/m/Y') }}
                                                </span>
                                                <span class="course-meta-item">
                                                    <i class="fas fa-gift me-1"></i>
                                                    Gratuito
                                                </span>
                                            </div>

                                            @if (isset($inscrito->progreso))
                                                <div class="progress-section-enhanced">
                                                    <div class="progress-header">
                                                        <span class="progress-label">Tu progreso</span>
                                                        <span class="progress-value">{{ $inscrito->progreso }}%</span>
                                                    </div>
                                                    <div class="progress-bar-enhanced">
                                                        <div class="progress-fill"
                                                            style="width: {{ $inscrito->progreso }}%"></div>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="course-actions-enhanced">
                                                <a href="{{ route('evento.detalle', encrypt($inscrito->cursos_id)) }}"
                                                    class="btn-course-action btn-course-success">
                                                    <i class="fas fa-door-open me-2"></i>
                                                    Acceder al Congreso
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="alert-enhanced alert-info-enhanced">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>No tienes congresos inscritos aún</strong> -
                                    <a href="{{ route('lista.cursos.congresos') }}" class="alert-link">Descubre
                                        nuestros eventos</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <!-- Tabs Mejorados para Docentes -->
                <ul class="nav course-tabs-enhanced" id="teacherCourseTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="teacher-cursos-tab" data-bs-toggle="tab"
                            data-bs-target="#teacher-cursos" type="button" role="tab"
                            aria-controls="teacher-cursos" aria-selected="true">
                            <i class="fas fa-book me-2"></i> Mis Cursos
                            <span
                                class="badge bg-primary ms-2">{{ $cursos->where('tipo', '!=', 'congreso')->where('docente_id', auth()->user()->id)->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="teacher-congresos-tab" data-bs-toggle="tab"
                            data-bs-target="#teacher-congresos" type="button" role="tab"
                            aria-controls="teacher-congresos" aria-selected="false">
                            <i class="fas fa-calendar-alt me-2"></i> Mis Congresos
                            <span
                                class="badge bg-warning ms-2">{{ $cursos->where('tipo', 'congreso')->where('docente_id', auth()->user()->id)->count() }}</span>
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="teacherCourseTabsContent">
                    <!-- Tab Cursos Regulares -->
                    <div class="tab-pane fade show active" id="teacher-cursos" role="tabpanel">
                        <div class="course-grid-enhanced" id="teacherCursosContainer">
                            @php
                                $cursosRegulares = $cursos->filter(function ($curso) {
                                    return auth()->user()->id == $curso->docente_id && $curso->tipo != 'congreso';
                                });
                            @endphp

                            @if ($cursosRegulares->count() > 0)
                                @foreach ($cursosRegulares as $curso)
                                    <div class="course-card-enhanced course-item"
                                        data-title="{{ strtolower($curso->nombreCurso) }}" data-type="curso"
                                        data-status="activo">

                                        <div class="course-image-enhanced">
                                            <img src="{{ $curso->imagen ? asset('storage/' . $curso->imagen) : asset('./assets/img/course-default.jpg') }}"
                                                alt="{{ $curso->nombreCurso }}" loading="lazy">
                                            <div class="course-badge-enhanced teacher">
                                                <i class="fas fa-chalkboard-teacher me-1"></i> Docente
                                            </div>
                                        </div>

                                        <div class="course-content-enhanced">
                                            <h3 class="course-title-enhanced">{{ $curso->nombreCurso }}</h3>

                                            <div class="course-meta-enhanced">
                                                <span class="course-meta-item">
                                                    <i class="fas fa-users me-1"></i>
                                                    {{ $curso->inscritos->count() ?? 0 }} estudiantes
                                                </span>
                                                <span class="course-meta-item">
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ $curso->duracion ?? 'N/A' }} horas
                                                </span>
                                            </div>

                                            <div class="progress-section-enhanced">
                                                <div class="progress-header">
                                                    <span class="progress-label">Participación</span>
                                                    <span class="progress-value">{{ $curso->inscritos->count() ?? 0 }}
                                                        estudiantes</span>
                                                </div>
                                                <div class="progress-bar-enhanced">
                                                    <div class="progress-fill"
                                                        style="width: {{ min(($curso->inscritos->count() / 50) * 100, 100) }}%">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="course-actions-enhanced">
                                                <a href="{{ route('Curso', encrypt($curso->id)) }}"
                                                    class="btn-course-action btn-course-primary">
                                                    <i class="fas fa-cogs me-2"></i>
                                                    Gestionar Curso
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="alert-enhanced alert-info-enhanced">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>No tienes cursos regulares asignados</strong>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Tab Congresos -->
                    <div class="tab-pane fade" id="teacher-congresos" role="tabpanel">
                        <div class="course-grid-enhanced" id="teacherCongresosContainer">
                            @php
                                $congresosDocente = $cursos->filter(function ($curso) {
                                    return auth()->user()->id == $curso->docente_id && $curso->tipo == 'congreso';
                                });
                            @endphp

                            @if ($congresosDocente->count() > 0)
                                @foreach ($congresosDocente as $curso)
                                    <div class="course-card-enhanced course-item"
                                        data-title="{{ strtolower($curso->nombreCurso) }}" data-type="congreso"
                                        data-status="activo">

                                        <div class="course-image-enhanced">
                                            <img src="{{ $curso->imagen ? asset('storage/' . $curso->imagen) : asset('./assets/img/course-default.jpg') }}"
                                                alt="{{ $curso->nombreCurso }}" loading="lazy">
                                            <div class="course-badge-enhanced congress">
                                                <i class="fas fa-calendar-alt me-1"></i> Congreso
                                            </div>
                                        </div>

                                        <div class="course-content-enhanced">
                                            <h3 class="course-title-enhanced">{{ $curso->nombreCurso }}</h3>

                                            <div class="course-meta-enhanced">
                                                <span class="course-meta-item">
                                                    <i class="fas fa-users me-1"></i>
                                                    {{ $curso->inscritos->count() ?? 0 }} participantes
                                                </span>
                                                <span class="course-meta-item">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ $curso->duracion ?? 'N/A' }} horas
                                                </span>
                                            </div>

                                            <div class="progress-section-enhanced">
                                                <div class="progress-header">
                                                    <span class="progress-label">Asistentes</span>
                                                    <span
                                                        class="progress-value">{{ $curso->inscritos->count() ?? 0 }}</span>
                                                </div>
                                                <div class="progress-bar-enhanced">
                                                    <div class="progress-fill"
                                                        style="width: {{ min(($curso->inscritos->count() / 100) * 100, 100) }}%">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="course-actions-enhanced">
                                                <a href="{{ route('Curso', encrypt($curso->id)) }}"
                                                    class="btn-course-action btn-course-success">
                                                    <i class="fas fa-cogs me-2"></i>
                                                    Gestionar Congreso
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="alert-enhanced alert-info-enhanced">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>No tienes congresos asignados</strong>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <!-- No Results Message Mejorado -->
        <div id="noResults" class="no-results-enhanced" style="display: none;">
            <i class="fas fa-search"></i>
            <h5>No se encontraron cursos</h5>
            <p class="text-muted">Intenta con otros términos de búsqueda o filtros diferentes</p>
            <button class="btn btn-outline-primary mt-2" onclick="clearSearch()">
                <i class="fas fa-times me-2"></i>
                Limpiar búsqueda
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const courseSearch = document.getElementById('courseSearch');
        const courseFilter = document.getElementById('courseFilter');
        const btnGrid = document.getElementById('btnGrid');
        const btnList = document.getElementById('btnList');
        const courseItems = document.querySelectorAll('.course-card-enhanced');
        const noResults = document.getElementById('noResults');

        // Función de búsqueda y filtrado
        function filterCourses() {
            const searchTerm = courseSearch.value.toLowerCase();
            const filterValue = courseFilter.value;
            let visibleCount = 0;

            courseItems.forEach(item => {
                const title = item.getAttribute('data-title');
                const type = item.getAttribute('data-type');
                const status = item.getAttribute('data-status');
                const progress = parseInt(item.getAttribute('data-progress') || 0);

                const matchesSearch = title.includes(searchTerm);
                const matchesFilter =
                    filterValue === 'all' ||
                    (filterValue === 'activos' && status === 'activo') ||
                    (filterValue === 'completados' && status === 'completado') ||
                    (filterValue === 'congresos' && type === 'congreso');

                if (matchesSearch && matchesFilter) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            // Mostrar/ocultar mensaje de no resultados
            if (visibleCount === 0) {
                noResults.style.display = 'block';
            } else {
                noResults.style.display = 'none';
            }
        }

        // Event listeners
        courseSearch.addEventListener('input', filterCourses);
        courseFilter.addEventListener('change', filterCourses);

        // Vista de lista/cuadrícula
        btnGrid.addEventListener('click', function() {
            btnGrid.classList.add('active');
            btnList.classList.remove('active');
            document.querySelectorAll('.course-grid-enhanced').forEach(container => {
                container.classList.remove('course-list-enhanced');
            });
            document.querySelectorAll('.course-card-enhanced').forEach(card => {
                card.classList.remove('list-view');
            });
        });

        btnList.addEventListener('click', function() {
            btnList.classList.add('active');
            btnGrid.classList.remove('active');
            document.querySelectorAll('.course-grid-enhanced').forEach(container => {
                container.classList.add('course-list-enhanced');
            });
            document.querySelectorAll('.course-card-enhanced').forEach(card => {
                card.classList.add('list-view');
            });
        });

        // Función para limpiar búsqueda
        window.clearSearch = function() {
            courseSearch.value = '';
            courseFilter.value = 'all';
            filterCourses();
        };

        // Efectos de hover mejorados
        courseItems.forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px)';
                this.style.boxShadow = 'var(--shadow-xl)';
            });

            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(-4px)';
                this.style.boxShadow = 'var(--shadow-lg)';
            });
        });

        // Inicializar filtros
        filterCourses();
    });
</script>

<!-- Script adicional para inicializar tabs de estudiantes y docentes -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Función para inicializar tabs
        function initializeTabs(tabsId, contentId) {
            const tabsContainer = document.getElementById(tabsId);
            const contentContainer = document.getElementById(contentId);

            if (!tabsContainer || !contentContainer) {
                console.log(`Tabs ${tabsId} o contenido ${contentId} no encontrado`);
                return;
            }

            // Inicializar todos los botones de tab
            const tabButtons = tabsContainer.querySelectorAll('[data-bs-toggle="tab"]');

            tabButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Remover active de todos los tabs en este contenedor
                    tabButtons.forEach(btn => {
                        btn.classList.remove('active');
                        btn.setAttribute('aria-selected', 'false');
                    });

                    // Agregar active al tab clickeado
                    this.classList.add('active');
                    this.setAttribute('aria-selected', 'true');

                    // Obtener el target
                    const target = this.getAttribute('data-bs-target');

                    // Ocultar todos los tab panes SOLO en este contenedor
                    const tabPanes = contentContainer.querySelectorAll('.tab-pane');
                    tabPanes.forEach(pane => {
                        pane.classList.remove('show', 'active');
                    });

                    // Mostrar el tab pane correspondiente
                    const targetPane = document.querySelector(target);
                    if (targetPane) {
                        targetPane.classList.add('show', 'active');
                    }
                });
            });

            // Asegurar que el primer tab esté activo al cargar
            const firstTab = tabsContainer.querySelector('.nav-link.active');
            if (firstTab) {
                const target = firstTab.getAttribute('data-bs-target');
                const targetPane = document.querySelector(target);
                if (targetPane) {
                    targetPane.classList.add('show', 'active');
                }
            }

            console.log(`Tabs ${tabsId} inicializados correctamente`);
        }

        // Inicializar tabs de estudiantes
        initializeTabs('courseTabs', 'courseTabsContent');

        // Inicializar tabs de docentes
        initializeTabs('teacherCourseTabs', 'teacherCourseTabsContent');
    });
</script>
