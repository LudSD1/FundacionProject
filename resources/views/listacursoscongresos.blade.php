@extends('layoutlanding')

@section('main')
<main id="main">
    <!-- PARTE 1: Hero Section con buscador -->
    <section id="hero" class="d-flex align-items-center"
        style="min-height: 600px; background: linear-gradient(rgba(27, 47, 85, 0.8), rgba(40, 58, 90, 0.7)), url('/api/placeholder/1920/1080') center/cover;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h1 class="text-white mb-4 fw-bold animate__animated animate__fadeInDown">
                        Encuentra tu próximo curso o congreso
                    </h1>
                    <p class="text-white mb-5 lead animate__animated animate__fadeInUp">
                        Explora nuestra amplia biblioteca de cursos y eventos educativos diseñados para impulsar tu carrera
                    </p>

                    <!-- Formulario de búsqueda simple -->
                    <form method="GET" action="{{ route('lista.cursos.congresos') }}" class="row g-3 align-items-end mb-4">
                        <!-- Campo de búsqueda por texto -->
                        <div class="col-md-12 mb-3">
                            <div class="input-group input-group-lg">
                                <input type="text" name="search" class="form-control"
                                       placeholder="Buscar cursos, congresos o temas..."
                                       value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i> Buscar
                                </button>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="type" class="text-white form-label">Tipo</label>
                            <select name="type" id="type" class="form-select">
                                <option value="">Todos</option>
                                <option value="curso" {{ request('type') == 'curso' ? 'selected' : '' }}>Curso</option>
                                <option value="congreso" {{ request('type') == 'congreso' ? 'selected' : '' }}>Evento</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="categoria" class="text-white form-label">Categoría</label>
                            <select name="categoria" id="categoria" class="form-select">
                                <option value="">Todas las categorías</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->name }} ({{ $categoria->cursos_count }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="sort" class="text-white form-label">Ordenar por</label>
                            <select name="sort" id="sort" class="form-select">
                                <option value="">Por defecto</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Precio: Menor a mayor</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Precio: Mayor a menor</option>
                                <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Más recientes</option>
                                <option value="rating_desc" {{ request('sort') == 'rating_desc' ? 'selected' : '' }}>Mejor calificados</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- PARTE 2: Estadísticas -->
    <div class="container py-5">
        <div class="row mb-5">
            <div class="col-12">
                <div class="bg-light rounded-3 p-4 shadow-sm">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3 mb-md-0">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="bi bi-journal-text fs-1 text-primary me-3"></i>
                                <div class="text-start">
                                    <h3 class="mb-0 fw-bold">{{ $stats['total_cursos'] ?? $cursos->total() }}</h3>
                                    <p class="mb-0 text-muted">Cursos disponibles</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="bi bi-people fs-1 text-primary me-3"></i>
                                <div class="text-start">
                                    <h3 class="mb-0 fw-bold">{{ $cursos->sum('inscritos_count') }}+</h3>
                                    <p class="mb-0 text-muted">Estudiantes activos</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="bi bi-person-video3 fs-1 text-primary me-3"></i>
                                <div class="text-start">
                                    <h3 class="mb-0 fw-bold">{{ $stats['categorias_disponibles'] ?? $categorias->count() }}+</h3>
                                    <p class="mb-0 text-muted">Categorías disponibles</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="bi bi-star-fill fs-1 text-primary me-3"></i>
                                <div class="text-start">
                                    <h3 class="mb-0 fw-bold">
                                        {{ $stats['promedio_general'] ? number_format($stats['promedio_general'], 1) : '4.8' }}/5
                                    </h3>
                                    <p class="mb-0 text-muted">Calificación promedio</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PARTE 3: Contenido principal (filtros y lista de cursos) -->
        <div class="row">
            <!-- Columna de filtros laterales -->
            <div class="col-lg-3">
                <!-- Controles de vista grid/lista -->
                <section>
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center flex-wrap">
                                <div class="btn-group me-3 mb-2 mb-md-0">
                                    <button type="button" class="btn btn-outline-primary active" data-view="grid">
                                        <i class="bi bi-grid-3x3-gap-fill"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-primary" data-view="list">
                                        <i class="bi bi-list-ul"></i>
                                    </button>
                                </div>
                                <span class="text-muted">Mostrando {{ $cursos->count() }} de {{ $cursos->total() }} resultados</span>
                            </div>
                        </div>
                        <div class="col-md-4 mt-3 mt-md-0">
                            <select class="form-select" id="sortOptions" onchange="updateSort()">
                                <option value="">Más relevantes</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Precio: Menor a Mayor</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Precio: Mayor a Menor</option>
                                <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Más recientes</option>
                                <option value="rating_desc" {{ request('sort') == 'rating_desc' ? 'selected' : '' }}>Mejor valorados</option>
                            </select>
                        </div>
                    </div>

                    <!-- Filtros laterales -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0 py-2">Filtros</h5>
                        </div>
                        <div class="card-body">
                            <!-- Formulario de filtros laterales -->
                            <form id="sidebarFilters" method="GET" action="{{ route('lista.cursos.congresos') }}">
                                <!-- Campos ocultos para mantener filtros previos -->
                                <input type="hidden" name="type" value="{{ request('type') }}">
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                <input type="hidden" name="categoria" value="{{ request('categoria') }}">

                                <!-- Filtro de visibilidad para administradores -->
                                @if(auth()->user() && auth()->user()->hasRole('Administrador'))
                                <div class="mb-4">
                                    <h6 class="mb-3 fw-bold">Visibilidad</h6>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="visibilidad"
                                            id="visibilidadTodos" value=""
                                            {{ request('visibilidad') == '' ? 'checked' : '' }} onchange="this.form.submit()">
                                        <label class="form-check-label" for="visibilidadTodos">
                                            Todos
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="visibilidad"
                                            id="visibilidadPublico" value="publico"
                                            {{ request('visibilidad') == 'publico' ? 'checked' : '' }} onchange="this.form.submit()">
                                        <label class="form-check-label" for="visibilidadPublico">
                                            Público
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="visibilidad"
                                            id="visibilidadPrivado" value="privado"
                                            {{ request('visibilidad') == 'privado' ? 'checked' : '' }} onchange="this.form.submit()">
                                        <label class="form-check-label" for="visibilidadPrivado">
                                            Privado
                                        </label>
                                    </div>
                                </div>
                                @endif

                                <!-- Nivel -->
                                <div class="mb-4">
                                    <h6 class="mb-3 fw-bold">Nivel</h6>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="nivel"
                                            id="nivelTodos" value=""
                                            {{ request('nivel') == '' ? 'checked' : '' }} onchange="this.form.submit()">
                                        <label class="form-check-label" for="nivelTodos">
                                            Todos
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="nivel"
                                            id="beginnerCheck" value="principiante"
                                            {{ request('nivel') == 'principiante' ? 'checked' : '' }} onchange="this.form.submit()">
                                        <label class="form-check-label" for="beginnerCheck">
                                            Principiante
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="nivel"
                                            id="intermediateCheck" value="intermedio"
                                            {{ request('nivel') == 'intermedio' ? 'checked' : '' }} onchange="this.form.submit()">
                                        <label class="form-check-label" for="intermediateCheck">
                                            Intermedio
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="nivel"
                                            id="advancedCheck" value="avanzado"
                                            {{ request('nivel') == 'avanzado' ? 'checked' : '' }} onchange="this.form.submit()">
                                        <label class="form-check-label" for="advancedCheck">
                                            Avanzado
                                        </label>
                                    </div>
                                </div>

                                <!-- Formato -->
                                <div class="mb-4">
                                    <h6 class="mb-3 fw-bold">Formato</h6>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="formato"
                                            id="formatoTodos" value=""
                                            {{ request('formato') == '' ? 'checked' : '' }} onchange="this.form.submit()">
                                        <label class="form-check-label" for="formatoTodos">
                                            Todos
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="formato"
                                            id="formatoPresencial" value="Presencial"
                                            {{ request('formato') == 'Presencial' ? 'checked' : '' }} onchange="this.form.submit()">
                                        <label class="form-check-label" for="formatoPresencial">
                                            Presencial
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="formato"
                                            id="formatoVirtual" value="Virtual"
                                            {{ request('formato') == 'Virtual' ? 'checked' : '' }} onchange="this.form.submit()">
                                        <label class="form-check-label" for="formatoVirtual">
                                            Virtual
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="formato"
                                            id="formatoHibrido" value="Híbrido"
                                            {{ request('formato') == 'Híbrido' ? 'checked' : '' }} onchange="this.form.submit()">
                                        <label class="form-check-label" for="formatoHibrido">
                                            Híbrido
                                        </label>
                                    </div>
                                </div>
                            </form>

                            <!-- Botón para limpiar filtros -->
                            <div class="d-grid gap-2">
                                <a href="{{ route('lista.cursos.congresos') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-2"></i>Limpiar filtros
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Newsletter Card -->
                    <div class="card shadow-sm bg-light border-0">
                        <div class="card-body">
                            <h5 class="card-title">¿Quieres recibir nuevos cursos?</h5>
                            <p class="card-text text-muted">Suscríbete a nuestro boletín para recibir actualizaciones
                                sobre nuevos cursos y ofertas especiales.</p>
                            <div class="input-group mb-3">
                                <input type="email" class="form-control" placeholder="Tu correo electrónico">
                                <button class="btn btn-primary" type="button">Suscribirse</button>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Columna de lista de cursos -->
            <div class="col-lg-9">
                <!-- Vista en grid -->
                <div class="row g-4" id="gridView">
                    @forelse($cursos as $curso)
                        <div class="col-md-6 col-lg-4">
                            <a href="{{ route('evento.detalle', encrypt($curso->id)) }}" class="text-decoration-none">
                                <div class="card h-100 shadow-sm">
                                    <div class="position-relative">
                                        @if ($curso->imagen)
                                            <img src="{{ asset('storage/' . $curso->imagen) }}" class="card-img-top"
                                                alt="{{ $curso->nombreCurso }}" style="height: 200px; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('assets/img/bg2.png') }}" class="card-img-top"
                                                alt="{{ $curso->nombreCurso }}" style="height: 200px; object-fit: cover;">
                                        @endif
                                        <span class="badge bg-primary position-absolute top-0 end-0 m-3">
                                            {{ ucfirst($curso->tipo) }}
                                        </span>
                                        <button class="btn btn-sm btn-light position-absolute top-0 start-0 m-3">
                                            <i class="bi bi-heart"></i>
                                        </button>

                                        <!-- Badge de visibilidad para administradores -->
                                        @if(auth()->user() && auth()->user()->hasRole('Administrador') && $curso->visibilidad == 'privado')
                                            <span class="badge bg-warning position-absolute" style="top: 10px; left: 50px;">
                                                Privado
                                            </span>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="badge bg-success">{{ $curso->nivel }}</span>
                                            @if($curso->calificaciones_avg_puntuacion)
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-star-fill text-warning me-1"></i>
                                                    <small class="text-muted">
                                                        {{ number_format($curso->calificaciones_avg_puntuacion, 1) }}
                                                        ({{ $curso->calificaciones_count }})
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                        <h5 class="card-title">{{ $curso->nombreCurso }}</h5>
                                        <p class="card-text text-muted">{{ Str::limit($curso->descripcionC ?? $curso->descripcion, 100) }}</p>

                                        <!-- Mostrar categorías -->
                                        @if($curso->categorias && $curso->categorias->count() > 0)
                                            <div class="mb-2">
                                                @foreach($curso->categorias->take(2) as $categoria)
                                                    <span class="badge bg-light text-dark me-1">{{ $categoria->name }}</span>
                                                @endforeach
                                                @if($curso->categorias->count() > 2)
                                                    <span class="badge bg-light text-dark">+{{ $curso->categorias->count() - 2 }}</span>
                                                @endif
                                            </div>
                                        @endif

                                        <div class="mb-3">
                                            @if ($curso->tipo == 'Curso')
                                                <small class="text-muted">
                                                    <i class="bi bi-clock me-1"></i>{{ $curso->duracion }} horas
                                                    <i class="bi bi-people ms-3 me-1"></i>{{ $curso->inscritos_count ?? 0 }} estudiantes
                                                </small>
                                            @else
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar me-1"></i>{{ \Carbon\Carbon::parse($curso->fecha_ini)->format('d M Y') }}
                                                    <i class="bi bi-people ms-3 me-1"></i>{{ $curso->cupos ?? 0 }} cupos
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white border-top-0 d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            @if ($curso->docente && $curso->docente->profile_photo_path)
                                                <img src="{{ asset('storage/' . $curso->docente->profile_photo_path) }}"
                                                    class="rounded-circle me-2" width="30" height="30"
                                                    alt="{{ $curso->docente->name }}">
                                            @else
                                                <img src="{{ asset('assets/img/user.png') }}"
                                                    class="rounded-circle me-2" width="30" height="30"
                                                    alt="Instructor">
                                            @endif
                                            <small class="text-muted">
                                                {{ $curso->docente ? $curso->docente->name . ' ' . $curso->docente->lastname1 : 'Instructor' }}
                                            </small>
                                        </div>
                                        <h5 class="text-primary mb-0">Bs. {{ number_format($curso->precio, 2) }}</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <div class="text-center">
                                <i class="bi bi-search display-1 text-muted mb-3"></i>
                                <h4 class="text-muted">No se encontraron cursos</h4>
                                <p class="text-muted">Intenta ajustar tus filtros de búsqueda</p>
                                <a href="{{ route('lista.cursos.congresos') }}" class="btn btn-primary">
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
                            <a href="{{ route('evento.detalle', encrypt($curso->id)) }}" class="text-decoration-none">
                                <div class="card shadow-sm">
                                    <div class="row g-0">
                                        <div class="col-md-4">
                                            @if ($curso->imagen)
                                                <img src="{{ asset('storage/' . $curso->imagen) }}" class="img-fluid rounded-start h-100"
                                                    alt="{{ $curso->nombreCurso }}" style="object-fit: cover;">
                                            @else
                                                <img src="{{ asset('assets/img/bg2.png') }}" class="img-fluid rounded-start h-100"
                                                    alt="{{ $curso->nombreCurso }}" style="object-fit: cover;">
                                            @endif
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <div>
                                                        <span class="badge bg-primary me-2">{{ ucfirst($curso->tipo) }}</span>
                                                        <span class="badge bg-success">{{ $curso->nivel }}</span>
                                                    </div>
                                                    @if($curso->calificaciones_avg_puntuacion)
                                                        <div class="d-flex align-items-center">
                                                            <i class="bi bi-star-fill text-warning me-1"></i>
                                                            <span>{{ number_format($curso->calificaciones_avg_puntuacion, 1) }}</span>
                                                            <small class="text-muted ms-1">({{ $curso->calificaciones_count }})</small>
                                                        </div>
                                                    @endif
                                                </div>
                                                <h5 class="card-title">{{ $curso->nombreCurso }}</h5>
                                                <p class="card-text">{{ Str::limit($curso->descripcionC ?? $curso->descripcion, 200) }}</p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center">
                                                        @if ($curso->docente && $curso->docente->profile_photo_path)
                                                            <img src="{{ asset('storage/' . $curso->docente->profile_photo_path) }}"
                                                                class="rounded-circle me-2" width="30" height="30"
                                                                alt="{{ $curso->docente->name }}">
                                                        @else
                                                            <img src="{{ asset('assets/img/user.png') }}"
                                                                class="rounded-circle me-2" width="30" height="30"
                                                                alt="Instructor">
                                                        @endif
                                                        <small class="text-muted">
                                                            {{ $curso->docente ? $curso->docente->name . ' ' . $curso->docente->lastname1 : 'Instructor' }}
                                                        </small>
                                                    </div>
                                                    <h5 class="text-primary mb-0">${{ number_format($curso->precio, 2) }}</h5>
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
                <div class="mt-4">
                    {{ $cursos->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</main>

<!-- JavaScript para funcionalidades -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cambio de vista grid/lista
    const gridBtn = document.querySelector('[data-view="grid"]');
    const listBtn = document.querySelector('[data-view="list"]');
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');

    gridBtn.addEventListener('click', function() {
        gridBtn.classList.add('active');
        listBtn.classList.remove('active');
        gridView.classList.remove('d-none');
        listView.classList.add('d-none');
    });

    listBtn.addEventListener('click', function() {
        listBtn.classList.add('active');
        gridBtn.classList.remove('active');
        listView.classList.remove('d-none');
        gridView.classList.add('d-none');
    });
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
