{{-- resources/views/partials/_course-card-grid.blade.php --}}
{{-- Variables esperadas: $curso, $imagen, $docente, $avatar, $esCurso --}}

<a href="{{ $curso->url }}" class="course-card-link">
    <div class="card course-card">

        {{-- ── Imagen + badges ──────────────────────── --}}
        <div class="course-image-wrapper">
            <img src="{{ $imagen }}"
                 class="course-image"
                 alt="{{ $curso->nombreCurso }}"
                 loading="lazy">

            <span class="course-type-badge">{{ ucfirst($curso->tipo) }}</span>

            <button class="course-favorite-btn"
                    type="button"
                    aria-label="Guardar en favoritos">
                <i class="bi bi-heart"></i>
            </button>

            @role('Administrador')
                @if($curso->visibilidad === 'privado')
                    <span class="course-visibility-badge">
                        <i class="bi bi-lock-fill me-1"></i>Privado
                    </span>
                @endif
            @endrole

            @if($curso->proximamente)
                <span class="course-coming-soon-badge">
                    <i class="bi bi-clock me-1"></i>Muy pronto
                </span>
            @endif
        </div>

        {{-- ── Cuerpo ────────────────────────────────── --}}
        <div class="card-body course-card-body">

            <div class="course-meta-top">
                @if($curso->nivel)
                    <span class="course-level-badge">{{ $curso->nivel }}</span>
                @endif

                @if($curso->calificaciones_avg_puntuacion)
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

            @if($curso->categorias?->count())
                <div class="course-categories">
                    @foreach($curso->categorias->take(2) as $cat)
                        <span class="course-category-badge">{{ $cat->name }}</span>
                    @endforeach
                    @if($curso->categorias->count() > 2)
                        <span class="course-category-badge">
                            +{{ $curso->categorias->count() - 2 }}
                        </span>
                    @endif
                </div>
            @endif

            <div class="course-stats">
                @if($esCurso)
                    <span>
                        <i class="bi bi-clock"></i>
                        {{ $curso->duracion }} horas
                    </span>
                    <span>
                        <i class="bi bi-people"></i>
                        {{ $curso->inscritos_count ?? 0 }} estudiantes
                    </span>
                @else
                    <span>
                        <i class="bi bi-calendar"></i>
                        {{ \Carbon\Carbon::parse($curso->fecha_ini)->translatedFormat('d M Y') }}
                    </span>
                    <span>
                        <i class="bi bi-ticket-perforated"></i>
                        {{ $curso->cupos_texto }} cupos
                    </span>
                @endif
            </div>

        </div>

        {{-- ── Footer ────────────────────────────────── --}}
        <div class="card-footer course-card-footer">
            <div class="course-instructor">
                <img src="{{ $avatar }}"
                     class="course-instructor-avatar"
                     alt="{{ $docente?->name ?? 'Instructor' }}"
                     loading="lazy">
                <small class="course-instructor-name">
                    {{ $docente ? $docente->name . ' ' . $docente->lastname1 : 'Instructor' }}
                </small>
            </div>
            <span class="course-price">Bs. {{ number_format($curso->precio, 2) }}</span>
        </div>

    </div>
</a>
