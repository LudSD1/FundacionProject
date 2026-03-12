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


<div class="dc-wrap" style="margin-top: 8%">
    <div class="dc-header">
        <div class="container">
            <div class="row align-items-center g-4">

                {{-- Título --}}
                <div class="col-lg-5">
                    <div class="dc-header-text">
                        <div class="dc-header-eyebrow">
                            <i class="bi bi-mortarboard-fill"></i>
                            {{ $userRole === 'Estudiante' ? 'Panel del Estudiante' : 'Panel del Docente' }}
                        </div>
                        <h2 class="dc-title">
                            {{ $userRole === 'Estudiante' ? 'Mis Cursos' : 'Cursos que Impartes' }}
                        </h2>
                        <p class="dc-subtitle">
                            {{ $userRole === 'Estudiante'
                                ? 'Gestiona y continúa tu aprendizaje desde aquí'
                                : 'Administra el contenido y los estudiantes de tus cursos' }}
                        </p>
                    </div>
                </div>

                {{-- Controles --}}
                <div class="col-lg-7">
                    <div class="dc-controls">
                        {{-- Buscador --}}
                        <div class="dc-search">
                            <i class="bi bi-search dc-search-icon"></i>
                            <input type="search" id="dcSearch" placeholder="Buscar curso o congreso..."
                                class="dc-search-input" autocomplete="off">
                        </div>

                        <div class="dc-controls-row">
                            {{-- Filtro --}}
                            <div class="dc-select-wrap">
                                <select id="dcFilter" class="dc-select">
                                    <option value="all">Todos</option>
                                    <option value="activo">En progreso</option>
                                    <option value="completado">Completados</option>
                                    <option value="congreso">Congresos</option>
                                    <option value="curso">Solo cursos</option>
                                </select>
                                <i class="bi bi-chevron-down dc-select-icon"></i>
                            </div>

                            {{-- Toggle vista --}}
                            <div class="dc-view-toggle">
                                <button id="dcBtnGrid" class="dc-view-btn active" title="Vista cuadrícula">
                                    <i class="bi bi-grid-3x3-gap-fill"></i>
                                </button>
                                <button id="dcBtnList" class="dc-view-btn" title="Vista lista">
                                    <i class="bi bi-list-ul"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>{{-- /dc-header --}}


    <div class="container dc-body">

        {{-- ╔═══════════════════════════════════════╗
             ║  ESTADO VACÍO                         ║
             ╚═══════════════════════════════════════╝ --}}
        @if ($hasNoCourses)
            <div class="dc-empty">
                <div class="dc-empty-icon">
                    <i class="bi bi-journal-bookmark"></i>
                </div>
                <h3 class="dc-empty-title">
                    No tienes cursos {{ $userRole === 'Estudiante' ? 'inscritos' : 'asignados' }}
                </h3>
                <p class="dc-empty-sub">
                    @if ($userRole === 'Estudiante')
                        Explora nuestro catálogo y empieza tu viaje de aprendizaje
                    @else
                        Contacta con el administrador para comenzar a impartir cursos
                    @endif
                </p>
                @if ($userRole === 'Estudiante')
                    <a href="{{ route('lista.cursos.congresos') }}" class="dc-btn dc-btn-primary">
                        <i class="bi bi-search me-2"></i>Explorar Cursos
                    </a>
                @endif
            </div>
        @else
            {{-- ╔═══════════════════════════════════════╗
             ║  TABS                                 ║
             ╚═══════════════════════════════════════╝ --}}
            @php
                $tabPrefix = $userRole === 'Estudiante' ? 'est' : 'doc';
            @endphp

            <ul class="dc-tabs nav" id="dcTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="dc-tab-btn nav-link active" data-bs-toggle="tab"
                        data-bs-target="#{{ $tabPrefix }}-cursos" type="button" role="tab">
                        <i class="bi bi-book me-2"></i>
                        {{ $userRole === 'Estudiante' ? 'Cursos' : 'Mis Cursos' }}
                        <span class="dc-tab-badge dc-badge-blue">
                            @if ($userRole === 'Estudiante')
                                {{ $inscritos->where('cursos.tipo', '!=', 'congreso')->count() }}
                            @else
                                {{ $cursos->where('tipo', '!=', 'congreso')->where('docente_id', auth()->user()->id)->count() }}
                            @endif
                        </span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="dc-tab-btn nav-link" data-bs-toggle="tab"
                        data-bs-target="#{{ $tabPrefix }}-congresos" type="button" role="tab">
                        <i class="bi bi-calendar-event me-2"></i>Congresos
                        <span class="dc-tab-badge dc-badge-orange">
                            @if ($userRole === 'Estudiante')
                                {{ $inscritos->where('cursos.tipo', 'congreso')->count() }}
                            @else
                                {{ $cursos->where('tipo', 'congreso')->where('docente_id', auth()->user()->id)->count() }}
                            @endif
                        </span>
                    </button>
                </li>
            </ul>

            {{-- ╔═══════════════════════════════════════╗
             ║  TAB CONTENT                          ║
             ╚═══════════════════════════════════════╝ --}}
            <div class="tab-content">

                {{-- ── ESTUDIANTE: TAB CURSOS ── --}}
                @if ($userRole === 'Estudiante')
                    <div class="tab-pane fade show active" id="est-cursos" role="tabpanel">
                        <div class="dc-grid" id="dcGrid">
                            @php
                                $cursosRegulares = $inscritos->filter(
                                    fn($i) => auth()->user()->id == $i->estudiante_id &&
                                        $i->cursos &&
                                        !$i->cursos->deleted_at &&
                                        $i->cursos->tipo != 'congreso',
                                );
                            @endphp

                            @forelse($cursosRegulares as $inscrito)
                                @php
                                    $progreso = $inscrito->progreso ?? 0;
                                    $completado = $progreso == 100;
                                    $imgPath = $inscrito->cursos->imagen;
                                    $imgSrc =
                                        $imgPath && \Storage::exists($imgPath)
                                            ? asset('storage/' . $imgPath)
                                            : asset('assets/img/course-default.jpg');
                                @endphp
                                <div class="dc-card" data-title="{{ strtolower($inscrito->cursos->nombreCurso) }}"
                                    data-type="curso" data-status="{{ $completado ? 'completado' : 'activo' }}">

                                    <div class="dc-card-img">
                                        <img src="{{ $imgSrc }}" alt="{{ $inscrito->cursos->nombreCurso }}"
                                            loading="lazy">
                                        @if ($completado)
                                            <div class="dc-badge dc-badge-green">
                                                <i class="bi bi-check-circle-fill me-1"></i>Completado
                                            </div>
                                        @else
                                            <div class="dc-badge dc-badge-blue">
                                                <i class="bi bi-play-circle me-1"></i>En progreso
                                            </div>
                                        @endif
                                    </div>

                                    <div class="dc-card-body">
                                        <h3 class="dc-card-title">{{ $inscrito->cursos->nombreCurso }}</h3>

                                        <div class="dc-meta">
                                            <span class="dc-meta-item">
                                                <i class="bi bi-calendar3"></i>
                                                {{ $inscrito->created_at->format('d/m/Y') }}
                                            </span>
                                            <span class="dc-meta-item">
                                                <i class="bi bi-clock"></i>
                                                {{ $inscrito->cursos->duracion ?? 'N/A' }} horas
                                            </span>
                                        </div>

                                        @if (isset($inscrito->progreso))
                                            <div class="dc-progress">
                                                <div class="dc-progress-header">
                                                    <span class="dc-progress-label">Tu progreso</span>
                                                    <span class="dc-progress-val">{{ $progreso }}%</span>
                                                </div>
                                                <div class="dc-progress-track">
                                                    <div class="dc-progress-fill" style="width:{{ $progreso }}%"
                                                        data-width="{{ $progreso }}"></div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="dc-card-actions">
                                            @if ($inscrito->pago_completado)
                                                <a href="{{ route('Curso', $inscrito->cursos->codigoCurso) }}"
                                                    class="dc-btn dc-btn-primary">
                                                    <i class="bi bi-play-circle-fill me-2"></i>Continuar Curso
                                                </a>
                                            @else
                                                <button type="button" class="dc-btn dc-btn-warning"
                                                    data-bs-toggle="modal" data-bs-target="#pagoModal"
                                                    data-inscrito-id="{{ $inscrito->id }}"
                                                    data-curso-id="{{ $inscrito->cursos->id }}"
                                                    data-curso-nombre="{{ $inscrito->cursos->nombreCurso }}">
                                                    <i class="bi bi-credit-card-2-front me-2"></i>Completar Pago
                                                </button>
                                                @if ($inscrito->created_at->diffInDays(now()) < 2)
                                                    <div class="dc-payment-status mt-2">
                                                        <i class="bi bi-hourglass-split me-1"></i>Pago en revisión
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="dc-alert">
                                    <i class="bi bi-info-circle-fill me-2"></i>
                                    <strong>No tienes cursos inscritos aún</strong> —
                                    <a href="{{ route('lista.cursos.congresos') }}">Explora nuestro catálogo</a>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- ── ESTUDIANTE: TAB CONGRESOS ── --}}
                    <div class="tab-pane fade" id="est-congresos" role="tabpanel">
                        <div class="dc-grid">
                            @php
                                $congresos = $inscritos->filter(
                                    fn($i) => auth()->user()->id == $i->estudiante_id &&
                                        $i->cursos &&
                                        !$i->cursos->deleted_at &&
                                        $i->cursos->tipo == 'congreso',
                                );
                            @endphp

                            @forelse($congresos as $inscrito)
                                @php
                                    $imgPath = $inscrito->cursos->imagen;
                                    $imgSrc =
                                        $imgPath && \Storage::exists($imgPath)
                                            ? asset('storage/' . $imgPath)
                                            : asset('assets/img/course-default.jpg');
                                @endphp
                                <div class="dc-card" data-title="{{ strtolower($inscrito->cursos->nombreCurso) }}"
                                    data-type="congreso" data-status="activo">

                                    <div class="dc-card-img">
                                        <img src="{{ $imgSrc }}" alt="{{ $inscrito->cursos->nombreCurso }}"
                                            loading="lazy">
                                        <div class="dc-badge dc-badge-orange">
                                            <i class="bi bi-calendar-star me-1"></i>Congreso
                                        </div>
                                    </div>

                                    <div class="dc-card-body">
                                        <h3 class="dc-card-title">{{ $inscrito->cursos->nombreCurso }}</h3>

                                        <div class="dc-meta">
                                            <span class="dc-meta-item">
                                                <i class="bi bi-calendar3"></i>
                                                {{ $inscrito->created_at->format('d/m/Y') }}
                                            </span>
                                            <span class="dc-meta-item">
                                                <i class="bi bi-gift"></i>Gratuito
                                            </span>
                                        </div>

                                        @if (isset($inscrito->progreso))
                                            <div class="dc-progress">
                                                <div class="dc-progress-header">
                                                    <span class="dc-progress-label">Tu progreso</span>
                                                    <span class="dc-progress-val">{{ $inscrito->progreso }}%</span>
                                                </div>
                                                <div class="dc-progress-track">
                                                    <div class="dc-progress-fill"
                                                        style="width:{{ $inscrito->progreso }}%"
                                                        data-width="{{ $inscrito->progreso }}"></div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="dc-card-actions">
                                            <a href="{{ $inscrito->cursos->url }}" class="dc-btn dc-btn-success">
                                                <i class="bi bi-door-open-fill me-2"></i>Acceder al Congreso
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="dc-alert">
                                    <i class="bi bi-info-circle-fill me-2"></i>
                                    <strong>No tienes congresos inscritos</strong> —
                                    <a href="{{ route('lista.cursos.congresos') }}">Descubre nuestros eventos</a>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @else
                    {{-- ══ DOCENTE ══ --}}

                    {{-- ── DOCENTE: TAB CURSOS ── --}}
                    <div class="tab-pane fade show active" id="doc-cursos" role="tabpanel">
                        <div class="dc-grid">
                            @php
                                $cursosDocente = $cursos->filter(
                                    fn($c) => auth()->user()->id == $c->docente_id && $c->tipo != 'congreso',
                                );
                            @endphp

                            @forelse($cursosDocente as $curso)
                                @php
                                    $total = $curso->inscritos->count();
                                    $pct = min(($total / 50) * 100, 100);
                                    $imgSrc = $curso->imagen
                                        ? asset('storage/' . $curso->imagen)
                                        : asset('assets/img/course-default.jpg');
                                @endphp
                                <div class="dc-card" data-title="{{ strtolower($curso->nombreCurso) }}"
                                    data-type="curso" data-status="activo">

                                    <div class="dc-card-img">
                                        <img src="{{ $imgSrc }}" alt="{{ $curso->nombreCurso }}"
                                            loading="lazy">
                                        <div class="dc-badge dc-badge-blue">
                                            <i class="bi bi-person-video3 me-1"></i>Docente
                                        </div>
                                    </div>

                                    <div class="dc-card-body">
                                        <h3 class="dc-card-title">{{ $curso->nombreCurso }}</h3>

                                        <div class="dc-meta">
                                            <span class="dc-meta-item">
                                                <i class="bi bi-people-fill"></i>{{ $total }} estudiantes
                                            </span>
                                            <span class="dc-meta-item">
                                                <i class="bi bi-clock"></i>{{ $curso->duracion ?? 'N/A' }} horas
                                            </span>
                                        </div>

                                        <div class="dc-progress">
                                            <div class="dc-progress-header">
                                                <span class="dc-progress-label">Participación</span>
                                                <span class="dc-progress-val">{{ $total }} alumnos</span>
                                            </div>
                                            <div class="dc-progress-track">
                                                <div class="dc-progress-fill" style="width:{{ $pct }}%"
                                                    data-width="{{ $pct }}"></div>
                                            </div>
                                        </div>

                                        <div class="dc-card-actions">
                                            <a href="{{ route('Curso', $curso->codigoCurso) }}"
                                                class="dc-btn dc-btn-primary">
                                                <i class="bi bi-gear-fill me-2"></i>Gestionar Curso
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="dc-alert">
                                    <i class="bi bi-info-circle-fill me-2"></i>
                                    <strong>No tienes cursos regulares asignados</strong>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- ── DOCENTE: TAB CONGRESOS ── --}}
                    <div class="tab-pane fade" id="doc-congresos" role="tabpanel">
                        <div class="dc-grid">
                            @php
                                $congresosDocente = $cursos->filter(
                                    fn($c) => auth()->user()->id == $c->docente_id && $c->tipo == 'congreso',
                                );
                            @endphp

                            @forelse($congresosDocente as $curso)
                                @php
                                    $total = $curso->inscritos->count();
                                    $pct = min(($total / 100) * 100, 100);
                                    $imgSrc = $curso->imagen
                                        ? asset('storage/' . $curso->imagen)
                                        : asset('assets/img/course-default.jpg');
                                @endphp
                                <div class="dc-card" data-title="{{ strtolower($curso->nombreCurso) }}"
                                    data-type="congreso" data-status="activo">

                                    <div class="dc-card-img">
                                        <img src="{{ $imgSrc }}" alt="{{ $curso->nombreCurso }}"
                                            loading="lazy">
                                        <div class="dc-badge dc-badge-orange">
                                            <i class="bi bi-calendar-event me-1"></i>Congreso
                                        </div>
                                    </div>

                                    <div class="dc-card-body">
                                        <h3 class="dc-card-title">{{ $curso->nombreCurso }}</h3>

                                        <div class="dc-meta">
                                            <span class="dc-meta-item">
                                                <i class="bi bi-people-fill"></i>{{ $total }} participantes
                                            </span>
                                            <span class="dc-meta-item">
                                                <i class="bi bi-clock"></i>{{ $curso->duracion ?? 'N/A' }} horas
                                            </span>
                                        </div>

                                        <div class="dc-progress">
                                            <div class="dc-progress-header">
                                                <span class="dc-progress-label">Asistentes</span>
                                                <span class="dc-progress-val">{{ $total }}</span>
                                            </div>
                                            <div class="dc-progress-track">
                                                <div class="dc-progress-fill" style="width:{{ $pct }}%"
                                                    data-width="{{ $pct }}"></div>
                                            </div>
                                        </div>

                                        <div class="dc-card-actions">
                                            <a href="{{ route('Curso', encrypt($curso->id)) }}"
                                                class="dc-btn dc-btn-success">
                                                <i class="bi bi-gear-fill me-2"></i>Gestionar Congreso
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="dc-alert">
                                    <i class="bi bi-info-circle-fill me-2"></i>
                                    <strong>No tienes congresos asignados</strong>
                                </div>
                            @endforelse
                        </div>
                    </div>

                @endif
            </div>{{-- /tab-content --}}

            {{-- Sin resultados de búsqueda --}}
            <div id="dcNoResults" class="dc-no-results" style="display:none">
                <i class="bi bi-search"></i>
                <h5>No se encontraron cursos</h5>
                <p>Intenta con otros términos o filtros</p>
                <button class="dc-btn dc-btn-outline" onclick="dcClearSearch()">
                    <i class="bi bi-x-circle me-2"></i>Limpiar búsqueda
                </button>
            </div>

        @endif
    </div>{{-- /container --}}
</div>{{-- /dc-wrap --}}

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

<script>
    (function() {

        /* ── 1. Animar barras de progreso al cargar ── */
        function animateProgressBars() {
            document.querySelectorAll('.dc-progress-fill').forEach(bar => {
                const target = bar.getAttribute('data-width') || '0';
                // Pequeño delay para que la transición CSS se vea
                requestAnimationFrame(() => {
                    setTimeout(() => {
                        bar.style.width = target + '%';
                    }, 80);
                });
            });
        }
        animateProgressBars();

        // Re-animar al cambiar de tab (Bootstrap reinicia el display)
        document.querySelectorAll('[data-bs-toggle="tab"]').forEach(btn => {
            btn.addEventListener('shown.bs.tab', animateProgressBars);
        });

        /* ── 2. Toggle vista grid / lista ── */
        const btnGrid = document.getElementById('dcBtnGrid');
        const btnList = document.getElementById('dcBtnList');

        function setView(mode) {
            document.querySelectorAll('.dc-grid').forEach(g => {
                g.classList.toggle('list-mode', mode === 'list');
            });
            btnGrid?.classList.toggle('active', mode === 'grid');
            btnList?.classList.toggle('active', mode === 'list');
            localStorage.setItem('dc_view', mode);
        }

        btnGrid?.addEventListener('click', () => setView('grid'));
        btnList?.addEventListener('click', () => setView('list'));

        // Restaurar preferencia guardada
        const savedView = localStorage.getItem('dc_view');
        if (savedView) setView(savedView);

        /* ── 3. Búsqueda + Filtro ── */
        const search = document.getElementById('dcSearch');
        const filter = document.getElementById('dcFilter');
        const noResults = document.getElementById('dcNoResults');

        function applyFilters() {
            const q = (search?.value || '').toLowerCase().trim();
            const sel = filter?.value || 'all';

            let visible = 0;

            document.querySelectorAll('.dc-card').forEach(card => {
                const title = card.getAttribute('data-title') || '';
                const type = card.getAttribute('data-type') || '';
                const status = card.getAttribute('data-status') || '';

                const matchQ = !q || title.includes(q);
                const matchF = sel === 'all' ||
                    (sel === 'activo' && status === 'activo') ||
                    (sel === 'completado' && status === 'completado') ||
                    (sel === 'congreso' && type === 'congreso') ||
                    (sel === 'curso' && type === 'curso');

                const show = matchQ && matchF;
                card.style.display = show ? '' : 'none';
                if (show) visible++;
            });

            if (noResults) noResults.style.display = visible === 0 ? 'block' : 'none';
        }

        search?.addEventListener('input', applyFilters);
        filter?.addEventListener('change', applyFilters);

        /* ── 4. Limpiar búsqueda (llamado desde onclick) ── */
        window.dcClearSearch = function() {
            if (search) search.value = '';
            if (filter) filter.value = 'all';
            applyFilters();
        };

        /* ── 5. Modal de pago: poblar datos ── */
        document.addEventListener('show.bs.modal', function(e) {
            if (e.target.id !== 'pagoModal') return;
            const btn = e.relatedTarget;
            if (!btn) return;
            const modal = e.target;
            const nombre = btn.getAttribute('data-curso-nombre') || '';
            const el = modal.querySelector('#modalCursoNombre');
            if (el) el.textContent = nombre;
            // Puedes agregar más campos aquí si el modal los necesita
        });

    })();
</script>
