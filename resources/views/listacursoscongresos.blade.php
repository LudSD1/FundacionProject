




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
                                Explora nuestra amplia biblioteca de cursos y eventos educativos diseñados para impulsar tu carrera
                            </p>

                            <!-- Formulario de búsqueda mejorado -->
                            <form method="GET" action="{{ route('lista.cursos.congresos') }}" class="search-hero-form">
                                <!-- Campo de búsqueda principal -->
                                <div class="search-input-wrapper">
                                    <input
                                        type="text"
                                        name="search"
                                        class="form-control search-input-main"
                                        placeholder="Buscar cursos, congresos o temas..."
                                        value="{{ request('search') }}"
                                    >
                                    <button type="submit" class="search-btn-main">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>

                                <!-- Filtros -->
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="filter-group">
                                            <label for="type" class="filter-label">Tipo</label>
                                            <select name="type" id="type" class="form-select filter-select">
                                                <option value="">Todos</option>
                                                <option value="curso" {{ request('type') == 'curso' ? 'selected' : '' }}>Curso</option>
                                                <option value="congreso" {{ request('type') == 'congreso' ? 'selected' : '' }}>Evento</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="filter-group">
                                            <label for="categoria" class="filter-label">Categoría</label>
                                            <select name="categoria" id="categoria" class="form-select filter-select">
                                                <option value="">Todas las categorías</option>
                                                @foreach ($categorias as $categoria)
                                                    <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                                                        {{ $categoria->name }} ({{ $categoria->cursos_count }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="filter-group">
                                            <label for="sort" class="filter-label">Ordenar por</label>
                                            <select name="sort" id="sort" class="form-select filter-select">
                                                <option value="">Por defecto</option>
                                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Precio: Menor a mayor</option>
                                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Precio: Mayor a menor</option>
                                                <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Más recientes</option>
                                                <option value="rating_desc" {{ request('sort') == 'rating_desc' ? 'selected' : '' }}>Mejor calificados</option>
                                            </select>
                                        </div>
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
                    <!-- Columna de filtros laterales -->
                    <div class="col-lg-3">
                        <aside class="filters-sidebar">
                            <!-- Controles de vista grid/lista -->
                            <div class="view-controls-wrapper">
                                <div class="row align-items-center">
                                    <div class="col-md-12 mb-3">
                                        <div class="view-toggle-buttons">
                                            <div class="view-btn-group">
                                                <button type="button" class="view-btn active" data-view="grid">
                                                    <i class="bi bi-grid-3x3-gap-fill"></i>
                                                </button>
                                                <button type="button" class="view-btn" data-view="list">
                                                    <i class="bi bi-list-ul"></i>
                                                </button>
                                            </div>
                                            <span class="results-count">
                                                Mostrando {{ $cursos->count() }} de {{ $cursos->total() }} resultados
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <select class="form-select sort-select" id="sortOptions" onchange="updateSort()">
                                            <option value="">Más relevantes</option>
                                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                                                Precio: Menor a Mayor
                                            </option>
                                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                                                Precio: Mayor a Menor
                                            </option>
                                            <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>
                                                Más recientes
                                            </option>
                                            <option value="rating_desc" {{ request('sort') == 'rating_desc' ? 'selected' : '' }}>
                                                Mejor valorados
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Filtros laterales -->
                            <div class="card filters-card">
                                <div class="card-header filters-header">
                                    <h5 class="filters-title text-white">
                                        <i class="bi bi-funnel me-2"></i>Filtros
                                    </h5>
                                </div>
                                <div class="card-body filters-body">
                                    <form id="sidebarFilters" method="GET" action="{{ route('lista.cursos.congresos') }}">
                                        <!-- Campos ocultos -->
                                        <input type="hidden" name="type" value="{{ request('type') }}">
                                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                                        <input type="hidden" name="search" value="{{ request('search') }}">
                                        <input type="hidden" name="categoria" value="{{ request('categoria') }}">

                                        <!-- Filtro de visibilidad para administradores -->
                                        @if (auth()->user() && auth()->user()->hasRole('Administrador'))
                                            <div class="filter-section">
                                                <h6 class="filter-section-title">Visibilidad</h6>
                                                <div class="filter-radio-item">
                                                    <input class="form-check-input filter-radio-input" type="radio"
                                                           name="visibilidad" id="visibilidadTodos" value=""
                                                           {{ request('visibilidad') == '' ? 'checked' : '' }}
                                                           onchange="this.form.submit()">
                                                    <label class="form-check-label filter-radio-label" for="visibilidadTodos">
                                                        Todos
                                                    </label>
                                                </div>
                                                <div class="filter-radio-item">
                                                    <input class="form-check-input filter-radio-input" type="radio"
                                                           name="visibilidad" id="visibilidadPublico" value="publico"
                                                           {{ request('visibilidad') == 'publico' ? 'checked' : '' }}
                                                           onchange="this.form.submit()">
                                                    <label class="form-check-label filter-radio-label" for="visibilidadPublico">
                                                        Público
                                                    </label>
                                                </div>
                                                <div class="filter-radio-item">
                                                    <input class="form-check-input filter-radio-input" type="radio"
                                                           name="visibilidad" id="visibilidadPrivado" value="privado"
                                                           {{ request('visibilidad') == 'privado' ? 'checked' : '' }}
                                                           onchange="this.form.submit()">
                                                    <label class="form-check-label filter-radio-label" for="visibilidadPrivado">
                                                        Privado
                                                    </label>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Nivel -->
                                        <div class="filter-section">
                                            <h6 class="filter-section-title">Nivel</h6>
                                            <div class="filter-radio-item">
                                                <input class="form-check-input filter-radio-input" type="radio"
                                                       name="nivel" id="nivelTodos" value=""
                                                       {{ request('nivel') == '' ? 'checked' : '' }}
                                                       onchange="this.form.submit()">
                                                <label class="form-check-label filter-radio-label" for="nivelTodos">
                                                    Todos
                                                </label>
                                            </div>
                                            <div class="filter-radio-item">
                                                <input class="form-check-input filter-radio-input" type="radio"
                                                       name="nivel" id="beginnerCheck" value="principiante"
                                                       {{ request('nivel') == 'principiante' ? 'checked' : '' }}
                                                       onchange="this.form.submit()">
                                                <label class="form-check-label filter-radio-label" for="beginnerCheck">
                                                    Principiante
                                                </label>
                                            </div>
                                            <div class="filter-radio-item">
                                                <input class="form-check-input filter-radio-input" type="radio"
                                                       name="nivel" id="intermediateCheck" value="intermedio"
                                                       {{ request('nivel') == 'intermedio' ? 'checked' : '' }}
                                                       onchange="this.form.submit()">
                                                <label class="form-check-label filter-radio-label" for="intermediateCheck">
                                                    Intermedio
                                                </label>
                                            </div>
                                            <div class="filter-radio-item">
                                                <input class="form-check-input filter-radio-input" type="radio"
                                                       name="nivel" id="advancedCheck" value="avanzado"
                                                       {{ request('nivel') == 'avanzado' ? 'checked' : '' }}
                                                       onchange="this.form.submit()">
                                                <label class="form-check-label filter-radio-label" for="advancedCheck">
                                                    Avanzado
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Formato -->
                                        <div class="filter-section">
                                            <h6 class="filter-section-title">Formato</h6>
                                            <div class="filter-radio-item">
                                                <input class="form-check-input filter-radio-input" type="radio"
                                                       name="formato" id="formatoTodos" value=""
                                                       {{ request('formato') == '' ? 'checked' : '' }}
                                                       onchange="this.form.submit()">
                                                <label class="form-check-label filter-radio-label" for="formatoTodos">
                                                    Todos
                                                </label>
                                            </div>
                                            <div class="filter-radio-item">
                                                <input class="form-check-input filter-radio-input" type="radio"
                                                       name="formato" id="formatoPresencial" value="Presencial"
                                                       {{ request('formato') == 'Presencial' ? 'checked' : '' }}
                                                       onchange="this.form.submit()">
                                                <label class="form-check-label filter-radio-label" for="formatoPresencial">
                                                    Presencial
                                                </label>
                                            </div>
                                            <div class="filter-radio-item">
                                                <input class="form-check-input filter-radio-input" type="radio"
                                                       name="formato" id="formatoVirtual" value="Virtual"
                                                       {{ request('formato') == 'Virtual' ? 'checked' : '' }}
                                                       onchange="this.form.submit()">
                                                <label class="form-check-label filter-radio-label" for="formatoVirtual">
                                                    Virtual
                                                </label>
                                            </div>
                                            <div class="filter-radio-item">
                                                <input class="form-check-input filter-radio-input" type="radio"
                                                       name="formato" id="formatoHibrido" value="Híbrido"
                                                       {{ request('formato') == 'Híbrido' ? 'checked' : '' }}
                                                       onchange="this.form.submit()">
                                                <label class="form-check-label filter-radio-label" for="formatoHibrido">
                                                    Híbrido
                                                </label>
                                            </div>
                                        </div>
                                    </form>

                                    <!-- Botón para limpiar filtros -->
                                    <a href="{{ route('lista.cursos.congresos') }}" class="clear-filters-btn">
                                        <i class="bi bi-x-circle me-2"></i>Limpiar filtros
                                    </a>
                                </div>
                            </div>

                            <!-- Newsletter Card -->
                            <div class="card newsletter-card">
                                <div class="card-body newsletter-body">
                                    <h5 class="newsletter-title">
                                        <i class="bi bi-envelope-heart me-2"></i>¿Quieres recibir nuevos cursos?
                                    </h5>
                                    <p class="newsletter-text">
                                        Suscríbete a nuestro boletín para recibir actualizaciones sobre nuevos cursos y ofertas especiales.
                                    </p>
                                    <div class="input-group">
                                        <input type="email" class="form-control newsletter-input"
                                               placeholder="Tu correo electrónico">
                                        <button class="btn newsletter-btn" type="button">
                                            Suscribirse
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </aside>
                    </div>

                    <!-- Columna de lista de cursos -->
                    <div class="col-lg-9">
                        <!-- Vista en grid -->
                        <div class="row " id="gridView">
                            @forelse($cursos as $curso)
                                <div class="col-md-6 col-xl-4">
                                    <a href="{{ route('evento.detalle', encrypt($curso->id)) }}" class="course-card-link">
                                        <div class="card course-card">
                                            <div class="course-image-wrapper">
                                                @if ($curso->imagen)
                                                    <img src="{{ asset('storage/' . $curso->imagen) }}"
                                                         class="course-image" alt="{{ $curso->nombreCurso }}">
                                                @else
                                                    <img src="{{ asset('assets/img/bg2.png') }}"
                                                         class="course-image" alt="{{ $curso->nombreCurso }}">
                                                @endif

                                                <span class="course-type-badge">
                                                    {{ ucfirst($curso->tipo) }}
                                                </span>

                                                <button class="course-favorite-btn">
                                                    <i class="bi bi-heart"></i>
                                                </button>

                                                @if (auth()->user() && auth()->user()->hasRole('Administrador') && $curso->visibilidad == 'privado')
                                                    <span class="course-visibility-badge">
                                                        Privado
                                                    </span>
                                                @endif

                                                @if ($curso->proximamente)
                                                    <span class="course-coming-soon-badge">
                                                        Muy pronto
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="card-body course-card-body">
                                                <div class="course-meta-top">
                                                    <span class="course-level-badge">{{ $curso->nivel }}</span>
                                                    @if ($curso->calificaciones_avg_puntuacion)
                                                        <div class="course-rating">
                                                            <i class="bi bi-star-fill"></i>
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

                                                @if ($curso->categorias && $curso->categorias->count() > 0)
                                                    <div class="course-categories">
                                                        @foreach ($curso->categorias->take(2) as $categoria)
                                                            <span class="course-category-badge">{{ $categoria->name }}</span>
                                                        @endforeach
                                                        @if ($curso->categorias->count() > 2)
                                                            <span class="course-category-badge">+{{ $curso->categorias->count() - 2 }}</span>
                                                        @endif
                                                    </div>
                                                @endif

                                                <div class="course-stats">
                                                    @if ($curso->tipo == 'Curso')
                                                        <i class="bi bi-clock"></i>{{ $curso->duracion }} horas
                                                        <i class="bi bi-people ms-3"></i>{{ $curso->inscritos_count ?? 0 }} estudiantes
                                                    @else
                                                        <i class="bi bi-calendar"></i>{{ \Carbon\Carbon::parse($curso->fecha_ini)->format('d M Y') }}
                                                        <i class="bi bi-people ms-3"></i>{{ $curso->cupos ?? 0 }} cupos
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="card-footer course-card-footer">
                                                <div class="course-instructor">
                                                    @if ($curso->docente && $curso->docente->profile_photo_path)
                                                        <img src="{{ asset('storage/' . $curso->docente->profile_photo_path) }}"
                                                             class="course-instructor-avatar" alt="{{ $curso->docente->name }}">
                                                    @else
                                                        <img src="{{ asset('assets/img/user.png') }}"
                                                             class="course-instructor-avatar" alt="Instructor">
                                                    @endif
                                                    <small class="course-instructor-name">
                                                        {{ $curso->docente ? $curso->docente->name . ' ' . $curso->docente->lastname1 : 'Instructor' }}
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
                                        <i class="bi bi-search empty-state-icon"></i>
                                        <h4 class="empty-state-title">No se encontraron cursos</h4>
                                        <p class="empty-state-text">Intenta ajustar tus filtros de búsqueda</p>
                                        <a href="{{ route('lista.cursos.congresos') }}" class="btn empty-state-btn">
                                            Ver todos los cursos
                                        </a>
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        <!-- Vista en lista (oculta por defecto) -->
                        <div class="row g-4 d-none" id="listView">
                            @foreach ($cursos as $curso)
                                <div class="col-12">
                                    <a href="{{ route('evento.detalle', encrypt($curso->id)) }}" class="course-card-link">
                                        <div class="card course-card-list">
                                            <div class="row g-0">
                                                <div class="col-md-4">
                                                    @if ($curso->imagen)
                                                        <img src="{{ asset('storage/' . $curso->imagen) }}"
                                                             class="img-fluid rounded-start course-list-image"
                                                             alt="{{ $curso->nombreCurso }}">
                                                    @else
                                                        <img src="{{ asset('assets/img/bg2.png') }}"
                                                             class="img-fluid rounded-start course-list-image"
                                                             alt="{{ $curso->nombreCurso }}">
                                                    @endif
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="card-body course-list-body">
                                                        <div class="course-list-meta">
                                                            <div class="course-list-badges">
                                                                <span class="course-type-badge">{{ ucfirst($curso->tipo) }}</span>
                                                                <span class="course-level-badge">{{ $curso->nivel }}</span>
                                                            </div>
                                                            @if ($curso->calificaciones_avg_puntuacion)
                                                                <div class="course-rating">
                                                                    <i class="bi bi-star-fill"></i>
                                                                    <span>{{ number_format($curso->calificaciones_avg_puntuacion, 1) }}</span>
                                                                    <small class="course-rating-text ms-1">({{ $curso->calificaciones_count }})</small>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <h5 class="course-list-title">{{ $curso->nombreCurso }}</h5>
                                                        <p class="course-list-description">
                                                            {{ Str::limit($curso->descripcionC ?? $curso->descripcion, 200) }}
                                                        </p>
                                                        <div class="course-list-footer">
                                                            <div class="course-instructor">
                                                                @if ($curso->docente && $curso->docente->profile_photo_path)
                                                                    <img src="{{ asset('storage/' . $curso->docente->profile_photo_path) }}"
                                                                         class="course-instructor-avatar" alt="{{ $curso->docente->name }}">
                                                                @else
                                                                    <img src="{{ asset('assets/img/user.png') }}"
                                                                         class="course-instructor-avatar" alt="Instructor">
                                                                @endif
                                                                <small class="course-instructor-name">
                                                                    {{ $curso->docente ? $curso->docente->name . ' ' . $curso->docente->lastname1 : 'Instructor' }}
                                                                </small>
                                                            </div>
                                                            <h5 class="course-price">Bs. {{ number_format($curso->precio, 2) }}</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <!-- Paginación -->
                    </div>
                    <div class="courses-pagination">
                        {{ $cursos->appends(request()->query())->links('custom-pagination') }}
                    </div>
                </div>
            </div>
        </div>

    <!-- JavaScript para funcionalidades -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cambio de vista grid/lista
            const gridBtn = document.querySelector('[data-view="grid"]');
            const listBtn = document.querySelector('[data-view="list"]');
            const gridView = document.getElementById('gridView');
            const listView = document.getElementById('listView');

            // Animación de entrada escalonada para cards
            function animateCardsIn(container) {
                if (!container) return;
                const cards = container.querySelectorAll('.course-card, .course-card-list');
                cards.forEach((card, index) => {
                    // reiniciar estado por si se cambia de vista
                    card.classList.remove('animate-in');
                    // forzar reflow para reiniciar la transición
                    // eslint-disable-next-line no-unused-expressions
                    card.offsetHeight;
                    const delayMs = Math.min(index * 70, 420); // escalonado y límite
                    card.style.transitionDelay = delayMs + 'ms';
                    requestAnimationFrame(() => {
                        card.classList.add('animate-in');
                    });
                });
            }

            gridBtn.addEventListener('click', function() {
                gridBtn.classList.add('active');
                listBtn.classList.remove('active');
                gridView.classList.remove('d-none');
                listView.classList.add('d-none');
                animateCardsIn(gridView);
            });

            listBtn.addEventListener('click', function() {
                listBtn.classList.add('active');
                gridBtn.classList.remove('active');
                listView.classList.remove('d-none');
                gridView.classList.add('d-none');
                animateCardsIn(listView);
            });

            // Animar cards al cargar según vista visible
            const initialContainer = gridView.classList.contains('d-none') ? listView : gridView;
            animateCardsIn(initialContainer);
        });

        // Función para actualizar ordenamiento
        function updateSort() {
            const sortSelect = document.getElementById('sortOptions');
            const currentUrl = new URL(window.location.href);

            if (sortSelect.value) {
                currentUrl.searchParams.set('sort', sortSelect.value);
            } else {
                currentUrl.searchParams.delete('sort');
            }

            window.location.href = currentUrl.toString();
        }
    </script>

@endsection


@include('layoutlanding')
