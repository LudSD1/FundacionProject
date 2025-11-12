@section('hero')

    <div class="page-cursos">
        <section id="hero" class="course-hero">
            <div class="container">
                <div class="row align-items-center">
                    <!-- Columna de información del curso -->
                    <div class="col-lg-6 d-lg-flex flex-lg-column justify-content-center pt-5 pt-lg-0 order-2 order-lg-1"
                        data-aos="fade-up">
                        <div>
                            <!-- Título del curso -->
                            <h3>
                                @if ($cursos->tipo == 'curso')
                                    {{ $cursos->nombreCurso }}
                                @elseif ($cursos->tipo == 'congreso')
                                    {{ $cursos->nombreCurso }}
                                @endif
                            </h3>

                            <!-- Descripción -->
                            <h2 class="text-white">{{ $cursos->descripcionC }}</h2>

                            <!-- Fecha -->
                            @php
                                \Carbon\Carbon::setLocale('es');
                            @endphp
                            <h2 class="text-white mb-4">
                                <i class="bi bi-calendar-event me-2"></i>
                                {{ \Carbon\Carbon::parse($cursos->fecha_ini)->translatedFormat('d \d\e F \d\e Y') }}
                            </h2>

                            <!-- Estado de retiro -->
                            @if ($estadoInscripcion == 'retirado')
                                <div class="alert alert-warning shadow-sm" role="alert">
                                    <h4 class="alert-heading">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        Fuiste retirado de este curso
                                    </h4>
                                    <p class="mb-3">
                                        Lamentamos informarte que has sido retirado de este curso. Si crees que esto
                                        es un error, por favor contacta al administrador.
                                    </p>
                                    <hr>
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                        <p class="mb-0">
                                            <strong>Fecha de retiro:</strong>
                                            {{ $usuarioRetirado->deleted_at->format('d/m/Y') }}
                                        </p>
                                        <a href="{{ route('Inicio') }}" class="btn btn-outline-primary">
                                            <i class="bi bi-arrow-left me-2"></i>Volver a los cursos
                                        </a>
                                    </div>
                                </div>
                            @else
                                <!-- Card de compra/inscripción -->
                                <div class="card course-purchase-card">
                                    @if ($usuarioInscrito)
                                        <!-- Usuario ya inscrito -->
                                        <div class="card-header">
                                            <h4 class="mb-0">
                                                <i class="bi bi-mortarboard-fill me-2"></i>
                                                @if ($cursos->tipo == 'curso')
                                                    Acceso al Curso
                                                @else
                                                    Obtener Certificado
                                                @endif
                                            </h4>
                                        </div>
                                        <div class="card-body course-price-panel">
                                            @if ($cursos->tipo == 'curso')
                                                <a class="btn btn-success w-100 py-3 fw-bold"
                                                    href="{{ route('Curso', encrypt($cursos->id)) }}">
                                                    <i class="bi bi-door-open me-2"></i>Ir al Curso
                                                </a>
                                            @else
                                                @if ($usuarioInscrito->certificado === null)
                                                    <div class="text-center py-3">
                                                        <i class="bi bi-hourglass-split text-primary"
                                                            style="font-size: 2rem;"></i>
                                                        <p class="mt-2 mb-0">Ponte en contacto con el colaborador del
                                                            evento.</p>
                                                    </div>
                                                @else
                                                    <a href="{{ route('verificar.certificado', $usuarioInscrito->certificado->codigo_certificado) }}"
                                                        target="_blank" class="btn btn-success w-100 py-3 fw-bold">
                                                        <i class="bi bi-award-fill me-2"></i>Descargar Certificado
                                                    </a>
                                                @endif
                                            @endif
                                        </div>
                                    @else
                                        <!-- Usuario no inscrito -->
                                        <div class="card-header">
                                            <h4 class="mb-0">
                                                @if ($cursos->tipo == 'curso')
                                                    <i class="bi bi-mortarboard-fill me-2"></i>Acceso al Curso
                                                @else
                                                    <i class="bi bi-calendar-event me-2"></i>Registro al Evento
                                                @endif
                                            </h4>
                                        </div>
                                        <div class="card-body">
                                            @if ($cursos->tipo == 'curso')
                                                <!-- Información de precio del curso -->
                                                <div class="text-center mb-4 course-price-panel">
                                                    {{-- <span class="badge bg-success-subtle text-success px-3 py-2 mb-3">
                                                    <i class="bi bi-tag-fill me-1"></i>Oferta Especial
                                                </span> --}}
                                                    <h3 class="text-primary mb-2">Bs.
                                                        {{ number_format($cursos->precio, 2) }}</h3>
                                                    <p class="text-muted mb-3">Pago único, acceso de por vida</p>

                                                    <div class="d-flex flex-column gap-2 mb-3">
                                                        <div class="course-benefit">
                                                            <i class="bi bi-check-circle-fill"></i>
                                                            <span>Certificado Digital Incluido</span>
                                                        </div>
                                                        <div class="course-benefit">
                                                            <i class="bi bi-check-circle-fill"></i>
                                                            <span>Soporte 24/7</span>
                                                        </div>
                                                        <div class="course-benefit">
                                                            <i class="bi bi-check-circle-fill"></i>
                                                            <span>Acceso ilimitado</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <button class="btn btn-success w-100 py-3 fw-bold fs-5"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#{{ auth()->check() ? 'compraCursoModal' : 'loginRequiredModal' }}">
                                                    <i class="bi bi-credit-card me-2"></i>Comprar Ahora
                                                </button>
                                            @else
                                                <!-- Información del Congreso -->
                                                <div class="text-center mb-4 course-price-panel">
                                                    @if (isset($cursos->precio) && $cursos->precio > 0)
                                                        <h3 class="mb-2">Bs. {{ number_format($cursos->precio, 2) }}</h3>
                                                    @else
                                                        <span class="badge bg-primary-subtle text-primary px-4 py-3 mb-3"
                                                            style="font-size: 1.2rem;">
                                                            <i class="bi bi-gift-fill me-2"></i>Acceso Gratuito
                                                        </span>
                                                    @endif

                                                    <div class="d-flex flex-column gap-2 mb-3">
                                                        <div class="course-benefit">
                                                            <i class="bi bi-check-circle-fill"></i>
                                                            <span>Material del Evento</span>
                                                        </div>
                                                        <div class="course-benefit">
                                                            <i class="bi bi-check-circle-fill"></i>
                                                            <span>Certificado de Asistencia</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                @if ($cursos->certificados_disponibles == true)
                                                    <!-- Contador de tiempo -->
                                                    <div class="text-center mb-4">
                                                        <h5 class="text-primary fw-bold mb-3">
                                                            <i class="bi bi-clock-history me-2"></i>Tiempo Disponible
                                                        </h5>
                                                        <div id="countdown-timer"></div>
                                                    </div>

                                                    @if (auth()->user())
                                                        <form
                                                            action="{{ route('certificados.obtener', encrypt($cursos->id)) }}"
                                                            method="POST">
                                                            @csrf
                                                            <input type="hidden" name="congreso_id"
                                                                value="{{ $cursos->id }}">
                                                            <button type="submit"
                                                                class="btn btn-success w-100 py-3 fw-bold fs-5">
                                                                <i class="bi bi-award-fill me-2"></i>
                                                                Obtener Mi Certificado Ahora
                                                            </button>
                                                        </form>
                                                    @else
                                                        <button class="btn btn-primary w-100 py-3 fw-bold fs-5"
                                                            data-bs-toggle="modal" data-bs-target="#opcionesRegistroModal">
                                                            <i class="bi bi-person-plus-fill me-2"></i>
                                                            Registrarse Ahora
                                                        </button>
                                                    @endif
                                                @else
                                                    <button class="btn btn-secondary w-100 py-3 fw-bold" disabled>
                                                        <i class="bi bi-lock-fill me-2"></i>
                                                        El certificado no está disponible
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Columna del carousel -->

                    {{-- Columna del carousel --}}
                    <div class="col-lg-6 order-1 order-lg-2 mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="200">
                        <div id="courseCarousel" class="carousel slide hero-course-carousel" data-bs-ride="carousel">
                            <div class="carousel-inner hero-carousel-inner">

                                @php
                                    // Detectar video YouTube
                                    $youtubeUrl = $cursos->youtube_url;
                                    $videoId = null;

                                    if ($youtubeUrl) {
                                        preg_match('/(?:v=|\/)([0-9A-Za-z_-]{11})/', $youtubeUrl, $matches);
                                        $videoId = $matches[1] ?? null;
                                    }

                                    $hasVideo = !empty($videoId);

                                    // Filtrar imágenes válidas
                                    $images = $cursos->imagenes
                                        ->where('activo', true)
                                        ->filter(fn($img) => !empty($img->url))
                                        ->values();
                                @endphp

                                {{-- Video con thumbnail (NO se carga hasta hacer clic) --}}
                                @if ($hasVideo)
                                    <div class="carousel-item hero-carousel-item active" data-type="video">
                                        <div class="hero-youtube-container" data-video-id="{{ $videoId }}">
                                            {{-- Thumbnail del video --}}
                                            <div class="hero-youtube-preview">
                                                <img src="https://i.ytimg.com/vi/{{ $videoId }}/maxresdefault.jpg"
                                                    class="hero-youtube-thumbnail" alt="Video preview" loading="lazy"
                                                    onerror="this.src='https://i.ytimg.com/vi/{{ $videoId }}/hqdefault.jpg'">

                                                {{-- Botón de play --}}
                                                <button class="hero-youtube-play-btn" aria-label="Reproducir video">
                                                    <svg viewBox="0 0 68 48">
                                                        <path class="hero-play-bg"
                                                            d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z">
                                                        </path>
                                                        <path class="hero-play-icon" d="M 45,24 27,14 27,34"></path>
                                                    </svg>
                                                    <span class="hero-play-text">Ver video</span>
                                                </button>

                                                {{-- Overlay --}}
                                                <div class="hero-youtube-overlay"></div>
                                            </div>

                                            {{-- Contenedor del iframe (se llena al hacer clic) --}}
                                            <div class="hero-youtube-player" style="display: none;"></div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Imágenes --}}
                                @foreach ($images as $i => $media)
                                    <div class="carousel-item hero-carousel-item {{ !$hasVideo && $i === 0 ? 'active' : '' }}"
                                        data-type="image">
                                        <img src="{{ asset($media->url) }}" class="d-block w-100 hero-carousel-image"
                                            alt="{{ $media->titulo ?? 'Imagen de curso' }}" loading="lazy">
                                    </div>
                                @endforeach

                                {{-- Imagen por defecto --}}
                                @if (!$hasVideo && $images->count() === 0)
                                    <div class="carousel-item hero-carousel-item active" data-type="image">
                                        <img src="{{ asset('assets2/img/congress.jpg') }}"
                                            class="d-block w-100 hero-carousel-image" alt="Imagen por defecto"
                                            loading="lazy">
                                    </div>
                                @endif

                            </div>

                            {{-- Controles - SIEMPRE VISIBLES --}}
                            @if (($hasVideo ? 1 : 0) + $images->count() > 1)
                                <button class="carousel-control-prev hero-carousel-control-prev" type="button"
                                    data-bs-target="#courseCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon hero-control-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next hero-carousel-control-next" type="button"
                                    data-bs-target="#courseCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon hero-control-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Siguiente</span>
                                </button>

                                {{-- Indicadores --}}
                                <div class="carousel-indicators hero-carousel-indicators">
                                    @if ($hasVideo)
                                        <button type="button" data-bs-target="#courseCarousel" data-bs-slide-to="0"
                                            class="hero-indicator active" aria-current="true" aria-label="Video">
                                            <i class="bi bi-play-circle-fill"></i>
                                        </button>
                                    @endif
                                    @foreach ($images as $i => $media)
                                        <button type="button" data-bs-target="#courseCarousel"
                                            data-bs-slide-to="{{ $hasVideo ? $i + 1 : $i }}"
                                            class="hero-indicator {{ !$hasVideo && $i === 0 ? 'active' : '' }}"
                                            aria-label="Imagen {{ $i + 1 }}">
                                            <i class="bi bi-image-fill"></i>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Script para manejar el carousel y video --}}


                </div>
            </div>
        </section>


        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const carouselElement = document.getElementById('courseCarousel');

                if (!carouselElement) return;

                // Inicializar carousel
                const carousel = new bootstrap.Carousel(carouselElement, {
                    interval: 5000,
                    ride: 'carousel',
                    pause: 'hover',
                    wrap: true
                });

                // Manejar click en el botón de play
                document.querySelectorAll('.hero-youtube-play-btn').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        const container = this.closest('.hero-youtube-container');
                        const videoId = container.dataset.videoId;
                        const preview = container.querySelector('.hero-youtube-preview');
                        const playerDiv = container.querySelector('.hero-youtube-player');

                        // Crear iframe
                        const iframe = document.createElement('iframe');
                        iframe.src =
                            `https://www.youtube-nocookie.com/embed/${videoId}?autoplay=1&rel=0&modestbranding=1&playsinline=1`;
                        iframe.title = 'Video del curso';
                        iframe.allow =
                            'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
                        iframe.allowFullscreen = true;

                        // Mostrar player y ocultar preview
                        playerDiv.appendChild(iframe);
                        playerDiv.style.display = 'block';
                        preview.classList.add('hidden');

                        // Pausar el autoplay del carousel cuando se reproduce el video
                        carousel.pause();
                    });
                });

                // Cuando se cambia de slide
                carouselElement.addEventListener('slide.bs.carousel', function(e) {
                    // Si estamos saliendo del slide del video
                    const currentItem = carouselElement.querySelector('.hero-carousel-item.active');
                    const videoContainer = currentItem ? currentItem.querySelector('.hero-youtube-container') :
                        null;

                    if (videoContainer) {
                        const preview = videoContainer.querySelector('.hero-youtube-preview');
                        const playerDiv = videoContainer.querySelector('.hero-youtube-player');
                        const iframe = playerDiv ? playerDiv.querySelector('iframe') : null;

                        // Si el video está reproduciéndose, pausarlo y mostrar preview
                        if (iframe) {
                            iframe.remove();
                            playerDiv.style.display = 'none';
                            preview.classList.remove('hidden');
                        }
                    }

                    // Reanudar autoplay cuando no estamos en el video
                    if (e.to !== 0 || !videoContainer) {
                        carousel.cycle();
                    }
                });
            });
        </script>

        <!-- MODALES -->
        <!-- MODALES -->
        @include('cursos.partials.modal-compra')
        @include('cursos.partials.modal-login-required')
        @include('cursos.partials.modal-congreso')

        <!-- SCRIPTS -->
        @include('cursos.partials.scripts')
    </div>


    @include('cursos.temariocomponent')



    @include('cursos.valoracion')
@endsection





@include('layoutlanding')
