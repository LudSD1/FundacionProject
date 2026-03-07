@section('hero')
    <style>
        /* Animación de entrada para cards de cursos */
        .course-card,
        .course-card-list {
            opacity: 0;
            transform: translateY(12px);
            transition: opacity .4s ease, transform .4s ease;
            will-change: opacity, transform;
        }

        .course-card.animate-in,
        .course-card-list.animate-in {
            opacity: 1;
            transform: none;
        }

        /* Estilos para el enlace del card */
        .course-card-link {
            text-decoration: none;
            color: inherit;
            display: block;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .course-card-link:hover {
            text-decoration: none;
            color: inherit;
            transform: translateY(-5px);
        }

        .course-card-link:hover .course-card {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }
    </style>
    <!-- PARTE 1: Hero Section con buscador -->
    <section class="search-hero-section mt-8">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-9">
                    <div class="search-hero-content text-center">

                        <h1 class="search-hero-title animate-fade-in-down">
                            Encuentra tu próximo curso o congreso
                        </h1>
                        <p class="search-hero-subtitle animate-fade-in-up">
                            Explora nuestra amplia biblioteca de cursos y eventos educativos
                            diseñados para impulsar tu carrera
                        </p>

                        <form method="GET" action="{{ route('lista.cursos.congresos') }}" class="search-hero-form">

                            <div class="search-input-wrapper">
                                <input type="text" name="search" class="form-control search-input-main"
                                    placeholder="Buscar cursos, congresos o temas..." value="{{ request('search') }}"
                                    autocomplete="off">
                                <button type="submit" class="search-btn-main" aria-label="Buscar">
                                    <i class="bi bi-search" aria-hidden="true"></i>
                                </button>
                            </div>

                            <div class="row g-3">
                                {{-- Tipo --}}
                                <div class="col-md-4">
                                    <div class="filter-group">
                                        <label for="heroType" class="filter-label">Tipo</label>
                                        <select name="type" id="heroType" class="form-select filter-select">
                                            <option value="">Todos</option>
                                            <option value="curso" @selected(request('type') === 'curso')>Curso</option>
                                            <option value="congreso" @selected(request('type') === 'congreso')>Evento</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Categoría --}}
                                <div class="col-md-4">
                                    <div class="filter-group">
                                        <label for="heroCategoria" class="filter-label">Categoría</label>
                                        <select name="categoria" id="heroCategoria" class="form-select filter-select">
                                            <option value="">Todas las categorías</option>
                                            @foreach ($categorias as $cat)
                                                <option value="{{ $cat->id }}" @selected(request('categoria') == $cat->id)>
                                                    {{ $cat->name }} ({{ $cat->cursos_count }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Ordenar --}}
                                <div class="col-md-4">
                                    {{-- <div class="filter-group">
                                        <label for="heroSort" class="filter-label">Ordenar por</label>
                                        @include('partials._sort-select', [
                                            'id' => 'heroSort',
                                            'name' => 'sort',
                                        ])
                                    </div> --}}
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <div class="courses-listing-container">
        <div class="container">
            <div class="row">

                {{-- ── Sidebar de filtros ──────────────────────────────── --}}
                <div class="col-lg-3">
                    <aside class="filters-sidebar">

                        {{-- Controles vista + sort --}}
                        <div class="view-controls-wrapper">
                            <div class="view-toggle-buttons">
                                <div class="view-btn-group" role="group" aria-label="Cambiar vista">
                                    <button type="button" class="view-btn active" data-view="grid" aria-pressed="true">
                                        <i class="bi bi-grid-3x3-gap-fill" aria-hidden="true"></i>
                                    </button>
                                    <button type="button" class="view-btn" data-view="list" aria-pressed="false">
                                        <i class="bi bi-list-ul" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <span class="results-count">
                                    Mostrando {{ $cursos->count() }} de {{ $cursos->total() }} resultados
                                </span>
                            </div>

                            {{-- Select de sort reutilizable --}}
                            {{-- @include('partials._sort-select', [
                                'id' => 'sortOptions',
                                'name' => 'sort',
                                'extraClass' => 'sort-select mt-3',
                            ]) --}}
                        </div>

                        {{-- Filtros laterales --}}
                        <div class="card filters-card">
                            <div class="card-header filters-header">
                                <h5 class="filters-title text-white mb-0">
                                    <i class="bi bi-funnel me-2" aria-hidden="true"></i>Filtros
                                </h5>
                            </div>
                            <div class="card-body filters-body">

                                <form id="sidebarFilters" method="GET" action="{{ route('lista.cursos.congresos') }}">
                                    {{-- Preservar estado de otros filtros --}}
                                    @foreach (['type', 'sort', 'search', 'categoria'] as $param)
                                        <input type="hidden" name="{{ $param }}" value="{{ request($param) }}">
                                    @endforeach

                                    {{-- Visibilidad (solo admin) --}}
                                    {{-- @role('Administrador')
                                        @include('partials._filter-radio', [
                                            'name' => 'visibilidad',
                                            'title' => 'Visibilidad',
                                            'options' => [
                                                '' => 'Todos',
                                                'publico' => 'Público',
                                                'privado' => 'Privado',
                                            ],
                                        ])
                                    @endrole --}}

                                    {{-- Nivel --}}
                                    {{-- @include('partials._filter-radio', [
                                        'name' => 'nivel',
                                        'title' => 'Nivel',
                                        'options' => [
                                            '' => 'Todos',
                                            'principiante' => 'Principiante',
                                            'intermedio' => 'Intermedio',
                                            'avanzado' => 'Avanzado',
                                        ],
                                    ]) --}}

                                    {{-- Formato --}}
                                    {{-- @include('partials._filter-radio', [
                                        'name' => 'formato',
                                        'title' => 'Formato',
                                        'options' => [
                                            '' => 'Todos',
                                            'Presencial' => 'Presencial',
                                            'Virtual' => 'Virtual',
                                            'Híbrido' => 'Híbrido',
                                        ],
                                    ]) --}}

                                </form>

                                <a href="{{ route('lista.cursos.congresos') }}" class="clear-filters-btn">
                                    <i class="bi bi-x-circle me-2" aria-hidden="true"></i>Limpiar filtros
                                </a>
                            </div>
                        </div>

                        {{-- Newsletter --}}
                        <div class="card newsletter-card">
                            <div class="card-body newsletter-body">
                                <h5 class="newsletter-title">
                                    <i class="bi bi-envelope-heart me-2" aria-hidden="true"></i>¿Quieres recibir nuevos
                                    cursos?
                                </h5>
                                <p class="newsletter-text">
                                    Suscríbete a nuestro boletín para recibir actualizaciones sobre nuevos cursos y ofertas
                                    especiales.
                                </p>
                                <div class="input-group">
                                    <input type="email" class="form-control newsletter-input"
                                        placeholder="Tu correo electrónico"
                                        aria-label="Correo electrónico para suscripción">
                                    <button class="btn newsletter-btn" type="button">
                                        Suscribirse
                                    </button>
                                </div>
                            </div>
                        </div>

                    </aside>
                </div>

                {{-- ── Lista de cursos ─────────────────────────────────── --}}
                <div class="col-lg-9">

                    {{-- Vista grid --}}
                    <div class="row" id="gridView">
                        @forelse ($cursos as $curso)
                            <div class="col-md-6 col-xl-4">
                                {{-- partials/_course-card-grid.blade.php --}}
                                @php
                                    $imagen = $curso->imagen
                                        ? asset('storage/' . $curso->imagen)
                                        : asset('assets/img/bg2.png');
                                    $docente = $curso->docente;
                                    $avatar = $docente?->profile_photo_path
                                        ? asset('storage/' . $docente->profile_photo_path)
                                        : asset('assets/img/user.png');
                                    $esCurso = $curso->tipo === 'Curso';
                                @endphp

                                <a href="{{ $curso->url }}" class="course-card-link">
                                    <div class="card course-card">

                                        {{-- Imagen + badges --}}
                                        <div class="course-image-wrapper">
                                            <img src="{{ $imagen }}" class="course-image"
                                                alt="{{ $curso->nombreCurso }}" loading="lazy">

                                            <span class="course-type-badge">{{ ucfirst($curso->tipo) }}</span>

                                            <button class="course-favorite-btn" type="button"
                                                aria-label="Guardar en favoritos">
                                                <i class="bi bi-heart" aria-hidden="true"></i>
                                            </button>

                                            @role('Administrador')
                                                @if ($curso->visibilidad === 'privado')
                                                    <span class="course-visibility-badge">Privado</span>
                                                @endif
                                            @endrole

                                            @if ($curso->proximamente)
                                                <span class="course-coming-soon-badge">Muy pronto</span>
                                            @endif
                                        </div>

                                        {{-- Cuerpo --}}
                                        <div class="card-body course-card-body">
                                            <div class="course-meta-top">
                                                <span class="course-level-badge">{{ $curso->nivel }}</span>

                                                @if ($curso->calificaciones_avg_puntuacion)
                                                    <div class="course-rating">
                                                        <i class="bi bi-star-fill" aria-hidden="true"></i>
                                                        <span class="course-rating-text">
                                                            {{ number_format($curso->calificaciones_avg_puntuacion, 1) }}
                                                            ({{ $curso->calificaciones_count }})
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>

                                            <h5 class="course-title">{{ $curso->nombreCurso }}</h5>
                                            <p class="course-description">
                                                {{ Str::limit($curso->descripcionC ?? $curso->descripcion, 100) }}
                                            </p>

                                            @if ($curso->categorias?->count())
                                                <div class="course-categories">
                                                    @foreach ($curso->categorias->take(2) as $cat)
                                                        <span class="course-category-badge">{{ $cat->name }}</span>
                                                    @endforeach
                                                    @if ($curso->categorias->count() > 2)
                                                        <span
                                                            class="course-category-badge">+{{ $curso->categorias->count() - 2 }}</span>
                                                    @endif
                                                </div>
                                            @endif

                                            <div class="course-stats">
                                                @if ($esCurso)
                                                    <i class="bi bi-clock" aria-hidden="true"></i> {{ $curso->duracion }}
                                                    horas
                                                    <i class="bi bi-people ms-3" aria-hidden="true"></i>
                                                    {{ $curso->inscritos_count ?? 0 }} estudiantes
                                                @else
                                                    <i class="bi bi-calendar" aria-hidden="true"></i>
                                                    {{ \Carbon\Carbon::parse($curso->fecha_ini)->translatedFormat('d M Y') }}
                                                    <i class="bi bi-people ms-3" aria-hidden="true"></i>
                                                    {{ $curso->cupos ?? 0 }} cupos
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Footer --}}
                                        <div class="card-footer course-card-footer"> 
                                            <div class="course-instructor">
                                                <img src="{{ $avatar }}" class="course-instructor-avatar"
                                                    alt="{{ $docente?->name ?? 'Instructor' }}" loading="lazy">
                                                <small class="course-instructor-name">
                                                    {{ $docente ? $docente->name . ' ' . $docente->lastname1 : 'Instructor' }}
                                                </small>
                                            </div>
                                            <h5 class="course-price">Bs. {{ number_format($curso->precio, 2) }}</h5>
                                        </div>

                                    </div>
                                </a>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="courses-empty-state">
                                    <i class="bi bi-search empty-state-icon" aria-hidden="true"></i>
                                    <h4 class="empty-state-title">No se encontraron cursos</h4>
                                    <p class="empty-state-text">Intenta ajustar tus filtros de búsqueda</p>
                                    <a href="{{ route('lista.cursos.congresos') }}" class="btn empty-state-btn">
                                        Ver todos los cursos
                                    </a>
                                </div>

                            </div>
                        @endforelse
                    </div>

                    {{-- Vista lista (oculta por defecto) --}}
                    <div class="row g-4 d-none" id="listView">
                        @foreach ($cursos as $curso)
                            <div class="col-12">
                                {{-- partials/_course-card-list.blade.php --}}
                                @php
                                    $imagen = $curso->imagen
                                        ? asset('storage/' . $curso->imagen)
                                        : asset('assets/img/bg2.png');
                                    $docente = $curso->docente;
                                    $avatar = $docente?->profile_photo_path
                                        ? asset('storage/' . $docente->profile_photo_path)
                                        : asset('assets/img/user.png');
                                @endphp

                                <a href="{{ $curso->url }}" class="course-card-link">
                                    <div class="card course-card-list">
                                        <div class="row g-0">

                                            <div class="col-md-4">
                                                <img src="{{ $imagen }}"
                                                    class="img-fluid rounded-start course-list-image"
                                                    alt="{{ $curso->nombreCurso }}" loading="lazy">
                                            </div>

                                            <div class="col-md-8">
                                                <div class="card-body course-list-body">

                                                    <div class="course-list-meta">
                                                        <div class="course-list-badges">
                                                            <span
                                                                class="course-type-badge">{{ ucfirst($curso->tipo) }}</span>
                                                            <span class="course-level-badge">{{ $curso->nivel }}</span>
                                                        </div>

                                                        @if ($curso->calificaciones_avg_puntuacion)
                                                            <div class="course-rating">
                                                                <i class="bi bi-star-fill" aria-hidden="true"></i>
                                                                <span>{{ number_format($curso->calificaciones_avg_puntuacion, 1) }}</span>
                                                                <small
                                                                    class="course-rating-text ms-1">({{ $curso->calificaciones_count }})</small>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <h5 class="course-list-title">{{ $curso->nombreCurso }}</h5>
                                                    <p class="course-list-description">
                                                        {{ Str::limit($curso->descripcionC ?? $curso->descripcion, 200) }}
                                                    </p>

                                                    <div class="course-list-footer">
                                                        <div class="course-instructor">
                                                            <img src="{{ $avatar }}"
                                                                class="course-instructor-avatar"
                                                                alt="{{ $docente?->name ?? 'Instructor' }}"
                                                                loading="lazy">
                                                            <small class="course-instructor-name">
                                                                {{ $docente ? $docente->name . ' ' . $docente->lastname1 : 'Instructor' }}
                                                            </small>
                                                        </div>
                                                        <h5 class="course-price">Bs.
                                                            {{ number_format($curso->precio, 2) }}</h5>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>

                </div>

                {{-- Paginación (fuera del col-lg-9 — igual que el original) --}}
                <div class="courses-pagination w-100">
                    {{ $cursos->appends(request()->query())->links('custom-pagination') }}
                </div>

            </div>
        </div>
    </div>
    <!-- JavaScript para funcionalidades -->
    <script>
        (() => {
            'use strict';

            // ── Helpers ───────────────────────────────────────────────
            /**
             * Anima las cards de un contenedor con un delay escalonado.
             * @param {Element} container
             */
            function animateCardsIn(container) {
                if (!container) return;
                const MAX_DELAY = 420;
                const STEP = 70;

                container.querySelectorAll('.course-card, .course-card-list').forEach((card, i) => {
                    card.classList.remove('animate-in');
                    card.offsetHeight; // forzar reflow
                    card.style.transitionDelay = Math.min(i * STEP, MAX_DELAY) + 'ms';
                    requestAnimationFrame(() => card.classList.add('animate-in'));
                });
            }

            // ── Toggle grid / lista ───────────────────────────────────
            function initViewToggle() {
                const gridBtn = document.querySelector('[data-view="grid"]');
                const listBtn = document.querySelector('[data-view="list"]');
                const gridView = document.getElementById('gridView');
                const listView = document.getElementById('listView');

                if (!gridBtn || !listBtn) return;

                function switchTo(show, hide, activeBtn, inactiveBtn) {
                    activeBtn.classList.add('active');
                    activeBtn.setAttribute('aria-pressed', 'true');
                    inactiveBtn.classList.remove('active');
                    inactiveBtn.setAttribute('aria-pressed', 'false');
                    show.classList.remove('d-none');
                    hide.classList.add('d-none');
                    animateCardsIn(show);
                }

                gridBtn.addEventListener('click', () => switchTo(gridView, listView, gridBtn, listBtn));
                listBtn.addEventListener('click', () => switchTo(listView, gridView, listBtn, gridBtn));

                // Animación inicial
                animateCardsIn(gridView.classList.contains('d-none') ? listView : gridView);
            }

            // ── Sort dinámico ─────────────────────────────────────────
            function initSortSelect() {
                // Aplica a todos los selects de sort en la página (hero + sidebar)
                document.querySelectorAll('.sort-select, [id="sortOptions"]').forEach(select => {
                    select.addEventListener('change', () => {
                        const url = new URL(window.location.href);
                        select.value ?
                            url.searchParams.set('sort', select.value) :
                            url.searchParams.delete('sort');
                        window.location.href = url.toString();
                    });
                });
            }

            // ── Init ──────────────────────────────────────────────────
            document.addEventListener('DOMContentLoaded', () => {
                initViewToggle();
                initSortSelect();
            });
        })();
    </script>
@endsection


@include('layoutlanding')
