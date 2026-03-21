{{-- resources/views/partials/_course-card-list.blade.php --}}
{{-- Variables esperadas: $curso, $imagen, $docente, $avatar --}}

@php $esCurso = $curso->tipo === 'Curso'; @endphp

<a href="{{ $curso->url }}" class="course-card-link">
    <div class="card course-card-list">
        <div class="row g-0">

            {{-- ── Imagen ──────────────────────────────── --}}
            <div class="col-md-3">
                <div class="course-list-image-wrapper">
                    <img src="{{ $imagen }}"
                         class="course-list-image"
                         alt="{{ $curso->nombreCurso }}"
                         loading="lazy">
                    <span class="course-type-badge">{{ ucfirst($curso->tipo) }}</span>
                </div>
            </div>

            {{-- ── Contenido ────────────────────────────── --}}
            <div class="col-md-9">
                <div class="card-body course-list-body">

                    <div class="course-list-meta">
                        <div class="course-list-badges">
                            @if($curso->nivel)
                                <span class="course-level-badge">{{ $curso->nivel }}</span>
                            @endif
                            @if($curso->categorias?->first())
                                <span class="course-category-badge">
                                    {{ $curso->categorias->first()->name }}
                                </span>
                            @endif
                        </div>

                        @if($curso->calificaciones_avg_puntuacion)
                            <div class="course-rating">
                                <i class="bi bi-star-fill"></i>
                                <span>{{ number_format($curso->calificaciones_avg_puntuacion, 1) }}</span>
                                <small class="course-rating-text ms-1">
                                    ({{ $curso->calificaciones_count }})
                                </small>
                            </div>
                        @endif
                    </div>

                    <h5 class="course-list-title">{{ $curso->nombreCurso }}</h5>

                    <p class="course-list-description">
                        {{ Str::limit($curso->descripcionC ?? $curso->descripcion, 180) }}
                    </p>

                    <div class="course-stats mb-3">
                        @if($esCurso)
                            <span><i class="bi bi-clock"></i> {{ $curso->duracion }} horas</span>
                            <span><i class="bi bi-people"></i> {{ $curso->inscritos_count ?? 0 }} estudiantes</span>
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
                        <span class="course-price">Bs. {{ number_format($curso->precio, 2) }}</span>
                    </div>

                </div>
            </div>

        </div>
    </div>
</a>
