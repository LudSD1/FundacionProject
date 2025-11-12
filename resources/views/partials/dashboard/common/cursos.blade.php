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
<div class="dashboard-courses">
    <div class="dashboard-header">
        <div class="container py-4">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="dashboard-title-wrapper">
                        <h2 class="dashboard-title">
                            <i class="bi bi-collection-play"></i>
                            {{ $userRole === 'Estudiante' ? 'Mis Cursos' : 'Cursos que Impartes' }}
                        </h2>
                        <p class="dashboard-subtitle mb-0">
                            {{ $userRole === 'Estudiante' ? 'Gestiona y continúa con tu aprendizaje' : 'Administra los cursos que impartes' }}
                        </p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="dashboard-controls">
                        <div class="filters-group">
                            <div class="search-box">
                                <i class="bi bi-search"></i>
                                <input type="search" id="courseSearch" placeholder="Buscar curso..." class="form-control">
                            </div>
                            <div class="select-wrapper">
                                <select class="form-select" id="courseFilter">
                                    <option value="all">Todos</option>
                                    <option value="activos">En progreso</option>
                                    <option value="completados">Completados</option>
                                </select>
                                <span class="select-icon"><i class="bi bi-chevron-down"></i></span>
                            </div>
                        </div>
                        <div class="view-controls">
                            <button id="btnGrid" class="view-btn active" title="Vista de cuadrícula" type="button">
                                <i class="bi bi-grid-3x3-gap-fill"></i>
                            </button>
                            <button id="btnList" class="view-btn" title="Vista de lista" type="button">
                                <i class="bi bi-list-ul"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container pb-5">
        @if ($hasNoCourses)
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="bi bi-journal-x"></i>
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
                        <i class="bi bi-search"></i> Explorar Cursos
                    </a>
                @endif
            </div>
        @else
            @if ($userRole === 'Estudiante')
                {{-- TABS PARA ESTUDIANTES --}}
                <ul class="nav nav-pills mb-4" id="courseTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="cursos-tab" data-bs-toggle="pill"
                                data-bs-target="#cursos" type="button" role="tab">
                            <i class="bi bi-book"></i> Cursos
                            <span class="badge bg-primary ms-2">{{ $inscritos->where('cursos.tipo', '!=', 'congreso')->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="congresos-tab" data-bs-toggle="pill"
                                data-bs-target="#congresos" type="button" role="tab">
                            <i class="bi bi-calendar-event"></i> Congresos
                            <span class="badge bg-success ms-2">{{ $inscritos->where('cursos.tipo', 'congreso')->count() }}</span>
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="courseTabsContent">
                    {{-- TAB CURSOS --}}
                    <div class="tab-pane fade show active" id="cursos" role="tabpanel">
                        <div class="row g-4" id="cursosContainer">
                            @php
                                $cursosRegulares = $inscritos->filter(function($inscrito) {
                                    return auth()->user()->id == $inscrito->estudiante_id
                                        && $inscrito->cursos
                                        && !$inscrito->cursos->deleted_at
                                        && $inscrito->cursos->tipo != 'congreso';
                                });
                            @endphp

                            @if($cursosRegulares->count() > 0)
                                @foreach ($cursosRegulares as $inscrito)
                                    <div class="col-12 col-md-6 col-lg-4 course-item"
                                        data-progress="{{ $inscrito->progreso ?? 0 }}"
                                        data-type="curso"
                                        data-title="{{ strtolower($inscrito->cursos->nombreCurso) }}"
                                        data-status="{{ ($inscrito->progreso ?? 0) == 100 ? 'completado' : 'activo' }}">
                                        <div class="course-card">
                                            <div class="course-image">
                                                @php
                                                    $imagenRuta = $inscrito->cursos->imagen;
                                                    $imagenExiste = $imagenRuta && \Illuminate\Support\Facades\Storage::exists($imagenRuta);
                                                @endphp
                                                <img src="{{ $imagenExiste ? asset('storage/' . $imagenRuta) : asset('assets/img/course-default.jpg') }}"
                                                    alt="{{ $inscrito->cursos->nombreCurso }}" loading="lazy">

                                                @if (($inscrito->progreso ?? 0) == 100)
                                                    <div class="course-badge completed">
                                                        <i class="bi bi-check-circle-fill"></i> Completado
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="course-content">
                                                <h3 class="course-title">{{ $inscrito->cursos->nombreCurso }}</h3>

                                                <div class="course-meta mb-3">
                                                    <span class="d-inline-flex align-items-center">
                                                        <i class="bi bi-calendar-event me-1"></i>
                                                        {{ $inscrito->created_at->format('d/m/Y') }}
                                                    </span>
                                                </div>

                                                @if (isset($inscrito->progreso))
                                                    <div class="progress-section mb-3">
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <span class="progress-label">Progreso</span>
                                                            <span class="progress-value">{{ $inscrito->progreso }}%</span>
                                                        </div>
                                                        <div class="progress" style="height: 6px;">
                                                            <div class="progress-bar" role="progressbar"
                                                                style="width: {{ $inscrito->progreso }}%"
                                                                aria-valuenow="{{ $inscrito->progreso }}"
                                                                aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="course-actions">
                                                    @if($inscrito->pago_completado)
                                                        <a href="{{ route('Curso', encrypt($inscrito->cursos_id)) }}"
                                                            class="btn btn-primary btn-sm w-100">
                                                            <i class="bi bi-play-circle me-1"></i> Continuar Curso
                                                        </a>
                                                    @else
                                                        <button type="button" class="btn btn-primary btn-sm w-100"
                                                            data-bs-toggle="modal" data-bs-target="#pagoModal"
                                                            data-inscrito-id="{{ $inscrito->id }}"
                                                            data-curso-id="{{ $inscrito->cursos->id }}"
                                                            data-curso-nombre="{{ $inscrito->cursos->nombreCurso }}"
                                                            data-curso-precio="{{ $inscrito->cursos->precio }}"
                                                            data-estudiante-nombre="{{ auth()->user()->name }} {{ auth()->user()->lastname1 }} {{ auth()->user()->lastname2 }}"
                                                            data-estudiante-id="{{ auth()->user()->id }}">
                                                            <i class="bi bi-credit-card me-1"></i> Completar Pago
                                                        </button>

                                                        @if ($inscrito->created_at->diffInDays(now()) < 2)
                                                            <div class="payment-status mt-2">
                                                                <i class="bi bi-hourglass-split me-1"></i>
                                                                Pago en revisión
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-12">
                                    <div class="alert alert-info d-flex align-items-center" role="alert">
                                        <i class="bi bi-info-circle me-2"></i>
                                        <div>No tienes cursos inscritos aún</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- TAB CONGRESOS --}}
                    <div class="tab-pane fade" id="congresos" role="tabpanel">
                        <div class="row g-4" id="congresosContainer">
                            @php
                                $congresos = $inscritos->filter(function($inscrito) {
                                    return auth()->user()->id == $inscrito->estudiante_id
                                        && $inscrito->cursos
                                        && !$inscrito->cursos->deleted_at
                                        && $inscrito->cursos->tipo == 'congreso';
                                });
                            @endphp

                            @if($congresos->count() > 0)
                                @foreach ($congresos as $inscrito)
                                    <div class="col-12 col-md-6 col-lg-4 course-item"
                                        data-progress="{{ $inscrito->progreso ?? 0 }}"
                                        data-type="congreso"
                                        data-title="{{ strtolower($inscrito->cursos->nombreCurso) }}"
                                        data-status="{{ ($inscrito->progreso ?? 0) == 100 ? 'completado' : 'activo' }}">
                                        <div class="course-card">
                                            <div class="course-image">
                                                @php
                                                    $imagenRuta = $inscrito->cursos->imagen;
                                                    $imagenExiste = $imagenRuta && \Illuminate\Support\Facades\Storage::exists($imagenRuta);
                                                @endphp
                                                <img src="{{ $imagenExiste ? asset('storage/' . $imagenRuta) : asset('assets/img/course-default.jpg') }}"
                                                    alt="{{ $inscrito->cursos->nombreCurso }}" loading="lazy">

                                                <div class="course-badge completed">
                                                    <i class="bi bi-ticket-perforated-fill"></i> Congreso
                                                </div>
                                            </div>

                                            <div class="course-content">
                                                <h3 class="course-title">{{ $inscrito->cursos->nombreCurso }}</h3>

                                                <div class="course-meta mb-3">
                                                    <span class="d-inline-flex align-items-center">
                                                        <i class="bi bi-calendar-event me-1"></i>
                                                        {{ $inscrito->created_at->format('d/m/Y') }}
                                                    </span>
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-gift me-1"></i> Gratuito
                                                    </span>
                                                </div>

                                                @if (isset($inscrito->progreso))
                                                    <div class="progress-section mb-3">
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <span class="progress-label">Progreso</span>
                                                            <span class="progress-value">{{ $inscrito->progreso }}%</span>
                                                        </div>
                                                        <div class="progress" style="height: 6px;">
                                                            <div class="progress-bar bg-success" role="progressbar"
                                                                style="width: {{ $inscrito->progreso }}%"
                                                                aria-valuenow="{{ $inscrito->progreso }}"
                                                                aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="course-actions">
                                                    <a href="{{ route('evento.detalle', encrypt($inscrito->cursos_id)) }}"
                                                        class="btn btn-success btn-sm w-100">
                                                        <i class="bi bi-door-open me-1"></i> Acceder al Congreso
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-12">
                                    <div class="alert alert-info d-flex align-items-center" role="alert">
                                        <i class="bi bi-info-circle me-2"></i>
                                        <div>No tienes congresos inscritos aún</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                {{-- VISTA PARA DOCENTES --}}
                <div class="row g-4" id="coursesContainer">
                    @if(isset($cursos) && $cursos->count() > 0)
                        @foreach ($cursos as $curso)
                            @if (auth()->user()->id == $curso->docente_id)
                                <div class="col-12 col-md-6 col-lg-4 course-item"
                                    data-title="{{ strtolower($curso->nombreCurso) }}"
                                    data-type="curso"
                                    data-status="activo">
                                    <div class="course-card">
                                        <div class="course-image">
                                            <img src="{{ $curso->imagen ? asset('storage/' . $curso->imagen) : asset('./assets/img/course-default.jpg') }}"
                                                alt="{{ $curso->nombreCurso }}" loading="lazy">
                                            <div class="course-badge teacher">
                                                <i class="bi bi-person-badge"></i> Docente
                                            </div>
                                        </div>

                                        <div class="course-content">
                                            <h3 class="course-title">{{ $curso->nombreCurso }}</h3>

                                            <div class="course-meta mb-3">
                                                <span class="d-inline-flex align-items-center">
                                                    <i class="bi bi-people me-1"></i>
                                                    {{ $curso->inscritos->count() ?? 0 }} estudiantes
                                                </span>
                                            </div>

                                            <div class="course-actions">
                                                <a href="{{ route('Curso', encrypt($curso->id)) }}"
                                                    class="btn btn-primary btn-sm w-100">
                                                    <i class="bi bi-arrow-right-circle me-1"></i> Gestionar Curso
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            @endif
        @endif

        {{-- No Results Message --}}
        <div id="noResults" class="text-center py-5" style="display: none;">
            <div class="text-muted">
                <i class="bi bi-search fs-1 mb-3 d-block"></i>
                <h5>No se encontraron cursos</h5>
                <p>Intenta con otros términos de búsqueda o filtros diferentes</p>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --primary-color: #1a4789;
        --secondary-color: #2196f3;
        --success-color: #198754;
        --warning-color: #ffc107;
        --card-shadow: 0 10px 20px rgba(26, 71, 137, 0.1);
        --card-border-radius: 12px;
        --transition-base: all 0.3s ease;
    }

    .dashboard-header {
        background: linear-gradient(90deg, #e3f0ff 0%, #f8fbff 100%);
        border-bottom: 1px solid #e0e7ef;
        margin-bottom: 2rem;
        box-shadow: 0 2px 8px rgba(26, 71, 137, 0.04);
    }

    .dashboard-title-wrapper {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .dashboard-title {
        font-size: 2rem;
        font-weight: 800;
        color: var(--primary-color);
        margin-bottom: 0.25rem;
        letter-spacing: -1px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .dashboard-title i {
        font-size: 1.5rem;
        color: var(--secondary-color);
    }

    .dashboard-subtitle {
        color: #6c757d;
        font-size: 1.1rem;
        margin-bottom: 0;
        font-weight: 400;
    }

    /* Controls */
    .dashboard-controls {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        align-items: center;
        flex-wrap: wrap;
    }

    .filters-group {
        display: flex;
        gap: 1rem;
        flex: 1;
        max-width: 600px;
        align-items: center;
    }

    .search-box {
        position: relative;
        flex: 1;
        min-width: 180px;
    }

    .search-box i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        z-index: 2;
        pointer-events: none;
        font-size: 1.1rem;
    }

    .search-box input {
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        border-radius: 8px;
        border: 1px solid #dee2e6;
        width: 100%;
        transition: var(--transition-base);
        font-size: 1rem;
        background: #fafdff;
    }

    .search-box input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(26, 71, 137, 0.10);
        outline: none;
        background: #fff;
    }

    .select-wrapper {
        position: relative;
        flex-shrink: 0;
        min-width: 150px;
    }

    .form-select {
        padding: 0.75rem 2.5rem 0.75rem 1rem;
        border-radius: 8px;
        border: 1px solid #dee2e6;
        background: #fafdff;
        font-size: 1rem;
        appearance: none;
        transition: var(--transition-base);
    }

    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(26, 71, 137, 0.10);
        outline: none;
        background: #fff;
    }

    .select-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: #6c757d;
        font-size: 1rem;
    }

    .view-controls {
        display: flex;
        gap: 0.5rem;
        margin-left: 1rem;
    }

    .view-btn {
        padding: 0.75rem;
        border: 1px solid #dee2e6;
        background: white;
        border-radius: 8px;
        color: #6c757d;
        transition: var(--transition-base);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        font-size: 1.2rem;
    }

    .view-btn i {
        color: inherit;
        transition: var(--transition-base);
    }

    .view-btn:hover {
        background: #f8f9fa;
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    .view-btn:hover i {
        color: var(--primary-color);
    }

    .view-btn.active {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .view-btn.active i {
        color: white;
    }

    .view-btn.active:hover {
        background: var(--primary-color);
        color: white;
    }

    .view-btn.active:hover i {
        color: white;
    }

    /* Course Cards */
    .course-item {
        transform: translateY(20px);
        transition: var(--transition-base);
    }

    .course-item.show {
        opacity: 1;
        transform: translateY(0);
    }

    .course-card {
        background: white;
        border-radius: var(--card-border-radius);
        overflow: hidden;
        transition: var(--transition-base);
        border: 1px solid rgba(0, 0, 0, 0.1);
        height: 100%;
        /* display: flex; */
        flex-direction: column;
    }

    .course-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--card-shadow);
    }

    .course-image {
        position: relative;
        height: 200px;
        overflow: hidden;
        flex-shrink: 0;
    }

    .course-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition-base);
    }

    .course-card:hover .course-image img {
        transform: scale(1.05);
    }

    .course-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        backdrop-filter: blur(10px);
    }

    .course-badge.completed {
        background: rgba(25, 135, 84, 0.9);
        color: white;
    }

    .course-badge.teacher {
        background: rgba(26, 71, 137, 0.9);
        color: white;
    }

    .course-content {
        padding: 1.5rem;

        flex-direction: column;
        flex: 1;
    }

    .course-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: var(--primary-color);
        line-height: 1.3;
    }

    .course-meta {
        /* display: flex; */
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
        color: #6c757d;
        font-size: 0.875rem;
        flex-wrap: wrap;
    }

    .course-meta i {
        margin-right: 0.25rem;
    }

    .progress-section {
        margin-bottom: 1rem;
    }

    .progress-label {
        font-size: 0.875rem;
        color: #6c757d;
    }

    .progress-value {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--primary-color);
    }

    .progress {
        border-radius: 10px;
        background-color: #e9ecef;
    }

    .progress-bar {
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        border-radius: 10px;
        transition: width 0.6s ease;
    }

    .course-actions {
        margin-top: auto;
    }

    .payment-status {
        margin-top: 0.5rem;
        padding: 0.5rem;
        background: rgba(255, 193, 7, 0.1);
        color: var(--warning-color);
        border-radius: 6px;
        font-size: 0.875rem;
        text-align: center;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: var(--card-border-radius);
        border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .empty-state-icon {
        font-size: 4rem;
        color: #6c757d;
        margin-bottom: 1.5rem;
    }

    .empty-state h3 {
        color: var(--primary-color);
        margin-bottom: 1rem;
    }

    .empty-state p {
        color: #6c757d;
        margin-bottom: 2rem;
    }

    /* List View Styles */
    .list-view .course-item {
        width: 100% !important;
        max-width: 100%;
        flex: 0 0 100%;
    }

    .list-view .course-card {
        display: flex;
        flex-direction: row;
        height: auto;
        min-height: 200px;
        align-items: stretch;
    }

    .list-view .course-image {
        width: 280px;
        height: 200px;
        flex-shrink: 0;
    }

    .list-view .course-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 1.5rem;
    }

    .list-view .course-title {
        font-size: 1.4rem;
        margin-bottom: 0.75rem;
    }

    .list-view .course-meta {
        margin-bottom: 1rem;
    }

    .list-view .progress-section {
        margin-bottom: 1rem;
    }

    .list-view .course-actions {
        margin-top: auto;
        max-width: 200px;
    }

    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in {
        animation: fadeIn 0.5s ease forwards;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .dashboard-header .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .dashboard-title {
            font-size: 1.5rem;
        }

        .filters-group {
            flex-direction: column;
            align-items: stretch;
            gap: 0.5rem;
            max-width: none;
        }

        .view-controls {
            margin-left: 0;
            justify-content: center;
        }

        /* Lista en móvil se vuelve vertical */
        .list-view .course-card {
            flex-direction: column;
        }

        .list-view .course-image {
            width: 100%;
            height: 200px;
        }
    }

    @media (max-width: 576px) {
        .dashboard-header .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .course-content {
            padding: 1rem;
        }

        .course-title {
            font-size: 1.1rem;
        }

        .list-view .course-content {
            padding: 1rem;
        }

        .list-view .course-title {
            font-size: 1.2rem;
        }
    }

    .sidebar-toggle-vertical {
        position: absolute;
        top: 50%;
        right: -22px; /* La mitad del ancho del botón para que sobresalga */
        transform: translateY(-50%);
        z-index: 1100;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #2197BD;
        color: #fff;
        border: none;
        box-shadow: 0 2px 8px rgba(33,151,189,0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        transition: background 0.2s, transform 0.2s;
        outline: none;
    }
    .sidebar-toggle-vertical:hover,
    .sidebar-toggle-vertical:focus {
        background: #176cae;
        transform: translateY(-50%) scale(1.08);
        outline: 2px solid #fff;
        outline-offset: 2px;
    }
    .sidebar.collapsed .sidebar-toggle-vertical i {
        transform: rotate(180deg);
        transition: transform 0.3s;
    }
</style>
