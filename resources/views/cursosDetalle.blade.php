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


        @guest
            <div class="modal fade" id="loginRequiredModal" tabindex="-1" aria-labelledby="loginRequiredModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title" id="loginRequiredModalLabel">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>Acceso Requerido
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center p-5">
                            <div class="mb-4">
                                <i class="bi bi-lock-fill" style="font-size: 4rem; color: var(--orange-accent);"></i>
                            </div>
                            <h4 class="mb-3 fw-bold">Debes iniciar sesión para continuar</h4>
                            <p class="text-muted mb-4">
                                Para realizar una compra o inscribirte necesitas tener una cuenta en nuestro sistema.
                            </p>

                            <div class="alert alert-info text-start">
                                <h6 class="alert-heading">
                                    <i class="bi bi-gift-fill me-2"></i>Beneficios de registrarte:
                                </h6>
                                <ul class="mb-0 ps-4">
                                    <li>Acceso a todos los cursos</li>
                                    <li>Certificados digitales</li>
                                    <li>Seguimiento de tu progreso</li>
                                    <li>Soporte personalizado</li>
                                </ul>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center bg-light p-4">
                            <div class="d-grid gap-3 w-100">
                                <a href="{{ route('login') }}" class="btn btn-primary btn-lg py-3">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                                </a>
                                <a href="{{ route('signin') }}" class="btn btn-success btn-lg py-3">
                                    <i class="bi bi-person-plus-fill me-2"></i>Crear Cuenta Gratis
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endguest

        <style>
            /* Estilos específicos para el modal de login required */
            #loginRequiredModal .modal-content {
                border: none;
                box-shadow: 0 20px 60px rgba(255, 165, 0, 0.2);
            }

            #loginRequiredModal .modal-header.bg-warning {
                background: var(--gradient-orange) !important;
            }

            #loginRequiredModal .bi-lock-fill {
                animation: lockPulse 2s infinite;
            }

            @keyframes lockPulse {

                0%,
                100% {
                    transform: scale(1);
                    opacity: 1;
                }

                50% {
                    transform: scale(1.1);
                    opacity: 0.8;
                }
            }

            #loginRequiredModal .alert-info {
                background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
                border-left: 4px solid var(--color-primary);
                border-radius: 10px;
            }

            #loginRequiredModal .alert-info .alert-heading {
                color: var(--color-primary);
                font-weight: 600;
            }

            #loginRequiredModal .btn-lg {
                font-weight: 600;
                border-radius: 25px;
                transition: all 0.3s ease;
            }

            #loginRequiredModal .btn-lg:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 20px rgba(26, 71, 137, 0.3);
            }
        </style>

        @if ($cursos->tipo == 'congreso' && $cursos->certificados_disponibles)

            {{-- ================================================
         MODAL 1: OPCIONES DE REGISTRO
    ================================================ --}}
            <div class="modal fade" id="opcionesRegistroModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title">
                                <i class="bi bi-door-open me-2"></i>Opciones de Registro
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center p-5">
                            <p class="mb-4 fs-5">¿Cómo deseas continuar?</p>

                            <div class="d-grid gap-3">
                                <button class="btn btn-primary btn-lg py-3" data-bs-dismiss="modal"
                                    data-bs-toggle="modal" data-bs-target="#registroCongresoModal">
                                    <i class="bi bi-person-plus-fill me-2"></i>Nuevo Registro
                                </button>

                                <button class="btn btn-outline-primary btn-lg py-3" data-bs-dismiss="modal"
                                    data-bs-toggle="modal" data-bs-target="#loginModal">
                                    <i class="bi bi-person-check-fill me-2"></i>Ya tengo cuenta
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================================================
         MODAL 2: LOGIN PARA CONGRESOS
    ================================================ --}}
            <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title">
                                <i class="bi bi-person-check-fill me-2"></i>Ingresa tu correo electrónico
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <p class="text-center mb-4">Si ya estás registrado, ingresa tu correo para obtener el
                                certificado</p>

                            <form action="{{ route('congreso.inscribir') }}" method="POST">
                                @csrf
                                <input type="hidden" name="congreso_id" value="{{ $cursos->id }}">

                                <div class="mb-4">
                                    <label for="loginEmail" class="form-label">
                                        <i class="bi bi-envelope me-2"></i>Correo Electrónico
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-at"></i>
                                        </span>
                                        <input type="email" class="form-control" id="loginEmail" name="email"
                                            required placeholder="tu@email.com">
                                    </div>
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Ingresa el email con el que te registraste
                                    </small>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg py-3">
                                        <i class="bi bi-award-fill me-2"></i>Obtener Certificado
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer justify-content-center bg-light">
                            <small class="text-muted">
                                ¿No tienes cuenta?
                                <a href="#" class="fw-bold" data-bs-toggle="modal"
                                    data-bs-target="#registroCongresoModal" data-bs-dismiss="modal">
                                    Regístrate aquí
                                </a>
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================================================
         MODAL 3: REGISTRO COMPLETO PARA CONGRESO
    ================================================ --}}
            <div class="modal fade" id="registroCongresoModal" tabindex="-1"
                aria-labelledby="registroCongresoModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title" id="registroCongresoModalLabel">
                                <i class="bi bi-person-badge-fill me-2"></i>Registro al Congreso
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            {{-- Mensajes de error --}}
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <h6 class="alert-heading">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        Por favor, corrige los siguientes errores:
                                    </h6>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            <form action="{{ route('registrarseCongreso', encrypt($cursos->id)) }}" method="POST"
                                id="formRegistroCongreso">
                                @csrf

                                {{-- Campos de nombre y apellidos --}}
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label for="name" class="form-label">
                                            <i class="bi bi-person me-1"></i>Nombre
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-person-fill"></i>
                                            </span>
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="Tu nombre" value="{{ old('name') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="lastname1" class="form-label">
                                            <i class="bi bi-person me-1"></i>Apellido Paterno
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-person-fill"></i>
                                            </span>
                                            <input type="text" class="form-control" id="lastname1" name="lastname1"
                                                placeholder="Apellido Paterno" value="{{ old('lastname1') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="lastname2" class="form-label">
                                            <i class="bi bi-person me-1"></i>Apellido Materno
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-person-fill"></i>
                                            </span>
                                            <input type="text" class="form-control" id="lastname2" name="lastname2"
                                                placeholder="Apellido Materno" value="{{ old('lastname2') }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- Campo de correo electrónico --}}
                                <div class="mb-4">
                                    <label for="email" class="form-label">
                                        <i class="bi bi-envelope me-1"></i>Correo Electrónico
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-at"></i>
                                        </span>
                                        <input type="email" class="form-control" id="email" name="email"
                                            placeholder="ejemplo@correo.com" value="{{ old('email') }}" required>
                                    </div>
                                </div>

                                {{-- Campos de contraseña y confirmación --}}
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label for="password" class="form-label">
                                            <i class="bi bi-lock me-1"></i>Contraseña
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-shield-lock-fill"></i>
                                            </span>
                                            <input type="password" class="form-control" id="password" name="password"
                                                placeholder="Mínimo 8 caracteres" required>
                                            <button class="btn btn-outline-secondary toggle-password" type="button"
                                                data-target="password">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="password_confirmation" class="form-label">
                                            <i class="bi bi-lock-fill me-1"></i>Confirmar Contraseña
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-shield-lock-fill"></i>
                                            </span>
                                            <input type="password" class="form-control" id="password_confirmation"
                                                name="password_confirmation" placeholder="Repite tu contraseña" required>
                                            <button class="btn btn-outline-secondary toggle-password" type="button"
                                                data-target="password_confirmation">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{-- Campo de país --}}
                                <div class="mb-4">
                                    <label for="country" class="form-label">
                                        <i class="bi bi-globe me-1"></i>País
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-geo-alt-fill"></i>
                                        </span>
                                        <select class="form-control" id="country" name="country" required>
                                            <option value="">Selecciona tu país</option>
                                            {{-- Opciones de países se llenarán con JavaScript --}}
                                        </select>
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold">
                                        <i class="bi bi-check2-circle me-2"></i>Confirmar Registro
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer justify-content-center bg-light">
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                ¿Ya tienes una cuenta?
                                <a href="{{ route('login.signin') }}" class="fw-bold">Inicia sesión aquí</a>
                            </small>
                        </div>
                    </div>
                </div>
            </div>

        @endif



        <!-- MODALES -->
        @auth
            <div class="modal fade" id="compraCursoModal" tabindex="-1" aria-labelledby="compraCursoModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title" id="compraCursoModalLabel">
                                <i class="bi bi-cart-check-fill me-2"></i>
                                {{ $cursos->precio > 0 ? 'Completar Compra' : 'Confirmar Inscripción' }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <form action="{{ route('registrarpagoPost') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <!-- Información del usuario -->
                                <div class="mb-4">
                                    <label class="form-label">
                                        <i class="bi bi-person-circle me-2"></i>Usuario
                                    </label>
                                    <input type="text" name="user"
                                        value="{{ auth()->user()->name }} {{ auth()->user()->lastname1 }} {{ auth()->user()->lastname2 }}"
                                        class="form-control" readonly>
                                </div>

                                <hr class="my-4">

                                <!-- Campo oculto con ID del estudiante -->
                                <input type="hidden" name="estudiante_id" value="{{ auth()->user()->id }}">

                                <!-- Curso seleccionado -->
                                <div class="mb-4">
                                    <label class="form-label">
                                        <i class="bi bi-book me-2"></i>Curso
                                    </label>
                                    <select name="curso_id" class="form-select">
                                        <option value="{{ $cursos->id }}" selected>
                                            {{ $cursos->nombreCurso }}
                                            ({{ $cursos->precio > 0 ? 'Bs ' . number_format($cursos->precio, 2) : 'Gratuito' }})
                                        </option>
                                    </select>
                                </div>

                                @if ($cursos->precio > 0)
                                    <!-- Monto a pagar -->
                                    <div class="mb-4">
                                        <label class="form-label">
                                            <i class="bi bi-cash-coin me-2"></i>Monto a Pagar
                                        </label>
                                        <div class="input-group">
                                            <input type="number" name="montopagar" class="form-control"
                                                value="{{ $cursos->precio }}" min="1" step="any" required
                                                readonly>
                                            <span class="input-group-text">Bs</span>
                                        </div>
                                    </div>

                                    <!-- Comprobante -->
                                    <div class="mb-4">
                                        <label class="form-label">
                                            <i class="bi bi-file-earmark-arrow-up me-2"></i>Comprobante de Pago
                                        </label>
                                        <input type="file" name="comprobante" class="form-control"
                                            accept=".pdf,.jpg,.jpeg,.png" required>
                                        <small class="text-muted">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Formatos aceptados: PDF, JPG, PNG (Max. 2MB)
                                        </small>
                                    </div>
                                @endif

                                <!-- Descripción -->
                                <div class="mb-4">
                                    <label class="form-label">
                                        <i class="bi bi-chat-left-text me-2"></i>Descripción
                                    </label>
                                    <textarea name="descripcion" class="form-control" rows="3" required
                                        placeholder="Ingrese detalles adicionales sobre su compra..."></textarea>
                                </div>

                                @if ($cursos->precio > 0)
                                    <!-- Métodos de pago -->
                                    <div class="mb-4">
                                        <h6 class="text-center mb-3">
                                            <i class="bi bi-credit-card me-2"></i>Métodos de Pago Disponibles
                                        </h6>

                                        @if ($metodosPago->where('is_active', true)->count() > 0)
                                            <div id="paymentMethodsCarousel" class="carousel slide" data-bs-ride="carousel">
                                                <div class="carousel-inner">
                                                    @foreach ($metodosPago->where('is_active', true)->sortBy('sort_order') as $index => $metodo)
                                                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                            <div class="card border-0 shadow-sm">
                                                                <div class="card-body text-center p-4">
                                                                    <h6 class="card-title mb-3">{{ $metodo->name }}</h6>

                                                                    @if ($metodo->qr_image)
                                                                        <div class="mb-3">
                                                                            <img src="{{ $metodo->qr_image_url }}"
                                                                                alt="QR {{ $metodo->name }}"
                                                                                class="img-fluid rounded"
                                                                                style="max-height: 250px; max-width: 250px;">
                                                                        </div>
                                                                    @endif

                                                                    @if ($metodo->account_holder)
                                                                        <p class="mb-2">
                                                                            <strong><i
                                                                                    class="bi bi-person me-1"></i>Titular:</strong>
                                                                            {{ $metodo->account_holder }}
                                                                        </p>
                                                                    @endif

                                                                    @if ($metodo->account_number)
                                                                        <p class="mb-2">
                                                                            <strong><i
                                                                                    class="bi bi-credit-card-2-front me-1"></i>Cuenta:</strong>
                                                                            {{ $metodo->account_number }}
                                                                        </p>
                                                                    @endif

                                                                    @if ($metodo->description)
                                                                        <p class="text-muted small mb-2">
                                                                            {{ $metodo->description }}</p>
                                                                    @endif

                                                                    @if ($metodo->additional_info && count($metodo->additional_info) > 0)
                                                                        <div class="mt-3 pt-3 border-top">
                                                                            @foreach ($metodo->additional_info as $info)
                                                                                @if (isset($info['key']) && isset($info['value']) && !empty($info['key']) && !empty($info['value']))
                                                                                    <small class="d-block text-muted mb-1">
                                                                                        <strong>{{ $info['key'] }}:</strong>
                                                                                        {{ $info['value'] }}
                                                                                    </small>
                                                                                @endif
                                                                            @endforeach
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                @if ($metodosPago->where('is_active', true)->count() > 1)
                                                    <!-- Controles del carousel -->
                                                    <button class="carousel-control-prev" type="button"
                                                        data-bs-target="#paymentMethodsCarousel" data-bs-slide="prev">
                                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                        <span class="visually-hidden">Anterior</span>
                                                    </button>
                                                    <button class="carousel-control-next" type="button"
                                                        data-bs-target="#paymentMethodsCarousel" data-bs-slide="next">
                                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                        <span class="visually-hidden">Siguiente</span>
                                                    </button>

                                                    <!-- Indicadores -->
                                                    <div class="carousel-indicators">
                                                        @foreach ($metodosPago->where('is_active', true)->sortBy('sort_order') as $index => $metodo)
                                                            <button type="button" data-bs-target="#paymentMethodsCarousel"
                                                                data-bs-slide-to="{{ $index }}"
                                                                class="{{ $index === 0 ? 'active' : '' }}"
                                                                aria-label="Método {{ $index + 1 }}"></button>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <!-- Fallback si no hay métodos configurados -->
                                            <div class="text-center p-4">
                                                <i class="bi bi-exclamation-circle text-warning" style="font-size: 3rem;"></i>
                                                <p class="mt-3 mb-0">No hay métodos de pago configurados</p>
                                            </div>
                                        @endif

                                        <small class="text-muted d-block text-center mt-3">
                                            <i class="bi bi-shield-check me-1"></i>
                                            Por favor adjunte su comprobante de pago
                                        </small>
                                    </div>
                                @endif
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle me-2"></i>Cancelar
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check2-circle me-2"></i>
                                    {{ $cursos->precio > 0 ? 'Confirmar Compra' : 'Confirmar Inscripción' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endauth




        <!-- SCRIPTS -->
        <script>
            // Fecha de finalización del curso/congreso
            const endDate = new Date("{{ $cursos->fecha_fin }}".replace(' ', 'T')).getTime();

            const countdown = setInterval(function() {
                const now = new Date().getTime();
                const distance = endDate - now;

                // Cálculos de tiempo
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Elemento del timer
                const timerElement = document.getElementById("countdown-timer");

                if (timerElement) {
                    if (distance > 0) {
                        // Mostrar tiempo restante con formato y icono
                        timerElement.innerHTML = `
                    <i class="bi bi-hourglass-split me-2"></i>
                    ${days}d ${hours}h ${minutes}m ${seconds}s
                `;
                        timerElement.className = "badge bg-primary-subtle text-primary px-3 py-2";
                    } else {
                        // Tiempo agotado
                        clearInterval(countdown);
                        timerElement.innerHTML = '<i class="bi bi-x-circle me-2"></i>¡Tiempo agotado!';
                        timerElement.className = "badge bg-danger-subtle text-danger px-3 py-2";

                        // Deshabilitar todos los botones relacionados
                        const buttonsToDisable = [
                            'button[data-bs-target="#opcionesRegistroModal"]',
                            'button[data-bs-target="#registroCongresoModal"]',
                            'button[data-bs-target="#loginModal"]',
                            'form[action*="certificados.obtener"] button[type="submit"]'
                        ];

                        buttonsToDisable.forEach(selector => {
                            document.querySelectorAll(selector).forEach(button => {
                                button.disabled = true;
                                button.classList.remove('btn-primary', 'btn-success');
                                button.classList.add('btn-secondary');
                                button.innerHTML = '<i class="bi bi-lock-fill me-2"></i>Tiempo agotado';
                            });
                        });

                        // Mostrar alerta al usuario
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-warning alert-dismissible fade show mt-3';
                        alertDiv.innerHTML = `
                    <strong><i class="bi bi-exclamation-triangle-fill me-2"></i>Atención:</strong>
                    El tiempo para obtener el certificado ha finalizado.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;

                        if (timerElement.parentElement) {
                            timerElement.parentElement.appendChild(alertDiv);
                        }
                    }
                }
            }, 1000);
        </script>

        {{-- ================================================
     SCRIPT 2: TOGGLE PASSWORD Y CARGA DE PAÍSES
================================================ --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                // ===== TOGGLE PASSWORD VISIBILITY =====
                document.querySelectorAll('.toggle-password').forEach(button => {
                    button.addEventListener('click', function() {
                        const targetId = this.getAttribute('data-target');
                        const input = document.getElementById(targetId);
                        const icon = this.querySelector('i');

                        if (input && icon) {
                            if (input.type === 'password') {
                                // Mostrar contraseña
                                input.type = 'text';
                                icon.classList.remove('bi-eye');
                                icon.classList.add('bi-eye-slash');
                                this.setAttribute('aria-label', 'Ocultar contraseña');
                            } else {
                                // Ocultar contraseña
                                input.type = 'password';
                                icon.classList.remove('bi-eye-slash');
                                icon.classList.add('bi-eye');
                                this.setAttribute('aria-label', 'Mostrar contraseña');
                            }
                        }
                    });
                });

                // ===== CARGAR PAÍSES EN SELECT =====
                const countries = [
                    // América del Norte
                    "Canadá", "Estados Unidos", "México",

                    // América Central y el Caribe
                    "Belice", "Costa Rica", "Cuba", "El Salvador", "Guatemala", "Honduras",
                    "Nicaragua", "Panamá", "República Dominicana", "Jamaica", "Haití",
                    "Trinidad y Tobago", "Bahamas", "Barbados",

                    // América del Sur
                    "Argentina", "Bolivia", "Brasil", "Chile", "Colombia", "Ecuador",
                    "Guyana", "Paraguay", "Perú", "Surinam", "Uruguay", "Venezuela",

                    // Europa
                    "Alemania", "Austria", "Bélgica", "Bulgaria", "Croacia", "Dinamarca",
                    "España", "Francia", "Grecia", "Hungría", "Irlanda", "Italia",
                    "Noruega", "Países Bajos", "Polonia", "Portugal", "Reino Unido",
                    "República Checa", "Rumania", "Suecia", "Suiza",

                    // Asia
                    "Arabia Saudita", "China", "Corea del Norte", "Corea del Sur",
                    "Filipinas", "India", "Indonesia", "Irán", "Iraq", "Israel",
                    "Japón", "Malasia", "Pakistán", "Singapur", "Tailandia",
                    "Turquía", "Vietnam", "Emiratos Árabes Unidos",

                    // Oceanía
                    "Australia", "Nueva Zelanda", "Fiji",

                    // África
                    "Egipto", "Marruecos", "Sudáfrica", "Nigeria", "Kenia", "Ghana",
                    "Argelia", "Túnez"
                ];

                const countrySelect = document.getElementById('country');
                if (countrySelect) {
                    // Ordenar países alfabéticamente
                    countries.sort((a, b) => a.localeCompare(b, 'es'));

                    // Agregar países al select
                    countries.forEach(country => {
                        const option = document.createElement('option');
                        option.value = country;
                        option.textContent = country;
                        countrySelect.appendChild(option);
                    });

                    // Si hay un país pre-seleccionado (old value)
                    const oldCountry = "{{ old('country') }}";
                    if (oldCountry && countrySelect) {
                        countrySelect.value = oldCountry;
                    }
                }

                // ===== VALIDACIÓN DE CONTRASEÑAS EN TIEMPO REAL =====
                const password = document.getElementById('password');
                const passwordConfirmation = document.getElementById('password_confirmation');

                if (password && passwordConfirmation) {
                    passwordConfirmation.addEventListener('input', function() {
                        if (password.value !== this.value) {
                            this.setCustomValidity('Las contraseñas no coinciden');
                            this.classList.add('is-invalid');
                            this.classList.remove('is-valid');
                        } else {
                            this.setCustomValidity('');
                            this.classList.remove('is-invalid');
                            this.classList.add('is-valid');
                        }
                    });

                    password.addEventListener('input', function() {
                        if (passwordConfirmation.value && password.value !== passwordConfirmation.value) {
                            passwordConfirmation.setCustomValidity('Las contraseñas no coinciden');
                            passwordConfirmation.classList.add('is-invalid');
                            passwordConfirmation.classList.remove('is-valid');
                        } else if (passwordConfirmation.value) {
                            passwordConfirmation.setCustomValidity('');
                            passwordConfirmation.classList.remove('is-invalid');
                            passwordConfirmation.classList.add('is-valid');
                        }
                    });
                }
            });
        </script>

        {{-- ================================================
     ESTILOS ADICIONALES PARA LOS SCRIPTS
================================================ --}}
        <style>
            /* Animación de fade in para los modales */
            .modal.fade .modal-dialog {
                transform: scale(0.95);
                transition: transform 0.3s ease-out;
            }

            .modal.show .modal-dialog {
                transform: scale(1);
            }

            /* Efecto de focus en inputs */
            .form-control:focus,
            .form-select:focus {
                border-color: var(--color-primary);
                box-shadow: 0 0 0 0.2rem rgba(26, 71, 137, 0.15);
                transform: translateY(-2px);
            }

            /* Estados de validación */
            .form-control.is-valid {
                border-color: #28a745;
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
                background-repeat: no-repeat;
                background-position: right calc(.375em + .1875rem) center;
                background-size: calc(.75em + .375rem) calc(.75em + .375rem);
            }

            .form-control.is-invalid {
                border-color: #dc3545;
            }

            /* Mejora en los botones de toggle password */
            .toggle-password {
                cursor: pointer;
                transition: all 0.3s ease;
                border-left: none;
            }

            .toggle-password:hover {
                background-color: var(--color-accent1);
                color: white;
                border-color: var(--color-primary);
            }

            .toggle-password:focus {
                box-shadow: none;
                background-color: var(--color-accent1);
                color: white;
            }

            /* Animación de error en formularios */
            @keyframes shake {

                0%,
                100% {
                    transform: translateX(0);
                }

                10%,
                30%,
                50%,
                70%,
                90% {
                    transform: translateX(-5px);
                }

                20%,
                40%,
                60%,
                80% {
                    transform: translateX(5px);
                }
            }

            .alert-danger {
                animation: shake 0.5s;
                border-left: 5px solid #dc3545;
                border-radius: 10px;
            }

            /* Loading spinner para cuando se envía el formulario */
            .btn.loading {
                position: relative;
                pointer-events: none;
            }

            .btn.loading::after {
                content: "";
                position: absolute;
                width: 16px;
                height: 16px;
                top: 50%;
                left: 50%;
                margin-left: -8px;
                margin-top: -8px;
                border: 2px solid transparent;
                border-radius: 50%;
                border-top-color: white;
                animation: spin 0.6s linear infinite;
            }

            @keyframes spin {
                to {
                    transform: rotate(360deg);
                }
            }

            /* Mejora visual del countdown timer */
            #countdown-timer {
                font-family: 'Courier New', monospace;
                letter-spacing: 1px;
                font-weight: 600;
            }
        </style>

        {{-- ================================================
     SCRIPT OPCIONAL: LOADING EN BOTONES DE SUBMIT
================================================ --}}
        <script>
            // Agregar loading spinner a botones de submit
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn && !submitBtn.disabled) {
                        submitBtn.classList.add('loading');
                        submitBtn.disabled = true;

                        // Si el formulario no se envía (por validación), remover loading
                        setTimeout(() => {
                            if (!form.checkValidity()) {
                                submitBtn.classList.remove('loading');
                                submitBtn.disabled = false;
                            }
                        }, 100);
                    }
                });
            });
        </script>

    </div>


    @if ($cursos->tipo == 'curso')
        <section class="mt-5" id="temario">
            <div class="container">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-header bg-light">
                            <h3 class="mb-0">
                                <i class="bi bi-journal-text text-primary me-2"></i>Temario del Curso
                            </h3>
                        </div>
                        <div class="card-body">
                            @forelse ($cursos->temas->sortBy('orden') as $i => $tema)
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">{{ $tema->titulo_tema }}</h5>
                                        <button class="btn btn-sm btn-outline-primary" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#descripcionTema{{ $i }}" aria-expanded="false"
                                            aria-controls="descripcionTema{{ $i }}">
                                            Ver descripción
                                        </button>
                                    </div>
                                    <div class="collapse mt-2" id="descripcionTema{{ $i }}">
                                        <div class="card card-body">
                                            {{ $tema->descripcion }}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted">No hay temas registrados para este curso.</p>
                            @endforelse
                        </div>

                    </div>
                </div>
            </div>
        </section>
    @else
        <section>
            <div class="container">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-header bg-light">
                            <h3 class="mb-0">
                                <i class="bi bi-people text-primary me-2"></i>Facilitadores del Evento
                            </h3>
                        </div>
                        <div class="card-body">
                            @if ($cursos->expositores->isNotEmpty())
                                <div id="expositoresCarousel" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">

                                        @foreach ($cursos->expositores as $index => $expositor)
                                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                <div class="d-flex flex-column align-items-center text-center">
                                                    {{-- Imagen del expositor (usa una imagen por defecto si no tiene) --}}

                                                    <img src="{{ $expositor->imagen ? asset('storage/' . $expositor->imagen) : asset('assets2/img/talker.png') }}"
                                                        class="rounded-circle mb-3" alt="{{ $expositor->nombre }}"
                                                        style="width: 150px; height: 150px; object-fit: cover;">


                                                    {{-- Información del expositor --}}
                                                    <h5 class="fw-bold">{{ $expositor->nombre }}</h5>
                                                    <p class="text-muted mb-1">
                                                        {{ $expositor->pivot->cargo ?? 'Cargo no especificado' }}
                                                    </p>
                                                    <p class="mb-0"><strong>Tema:</strong>
                                                        {{ $expositor->pivot->tema ?? 'Tema no definido' }}</p>
                                                    {{-- <p class="mb-0"><strong>Fecha:</strong>
                                                            {{ \Carbon\Carbon::parse($expositor->pivot->fecha_presentacion)->format('d/m/Y') ?? 'No asignada' }}
                                                        </p> --}}
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>

                                    {{-- Controles --}}
                                    <button class="carousel-control-prev" type="button"
                                        data-bs-target="#expositoresCarousel" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Anterior</span>
                                    </button>
                                    <button class="carousel-control-next" type="button"
                                        data-bs-target="#expositoresCarousel" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Siguiente</span>
                                    </button>
                                </div>
                            @else
                                <p class="text-muted">No hay expositores asignados.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

    @endif


    <section class="mt-" id="valoraciones">
        <div class="container">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-light">
                        <h3 class="mb-0">
                            <i class="bi bi-star-fill text-warning me-2"></i>Valoraciones del Curso
                        </h3>
                    </div>
                    <div class="card-body">
                        <!-- Resumen de Calificaciones -->
                        <div class="row align-items-center mb-4">
                            <div class="col-md-3 text-center">
                                <div class="display-4 fw-bold text-primary">
                                    {{ number_format($cursos->calificaciones_avg_puntuacion, 1) }}
                                </div>
                                <div class="stars mb-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i
                                            class="bi bi-star-fill {{ $i <= round($cursos->calificaciones_avg_puntuacion) ? 'text-warning' : 'text-secondary' }}"></i>
                                    @endfor
                                </div>
                                <small class="text-muted">Basado en {{ $cursos->calificaciones_count }}
                                    valoraciones</small>
                            </div>
                            <div class="col-md-9">
                                @for ($i = 5; $i >= 1; $i--)
                                    <div class="row align-items-center mb-2">
                                        <div class="col-2 text-end">
                                            <small>{{ $i }} <i
                                                    class="bi bi-star-fill text-warning"></i></small>
                                        </div>
                                        <div class="col-7">
                                            <div class="progress" style="height: 8px;">
                                                @php
                                                    $percentage =
                                                        $cursos->calificaciones_count > 0
                                                            ? ($cursos->calificaciones
                                                                    ->where('puntuacion', $i)
                                                                    ->count() /
                                                                    $cursos->calificaciones_count) *
                                                                100
                                                            : 0;
                                                @endphp
                                                <div class="progress-bar bg-warning" role="progressbar"
                                                    style="width: {{ $percentage }}%"
                                                    aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <small
                                                class="text-muted">{{ $cursos->calificaciones->where('puntuacion', $i)->count() }}</small>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>



                        <!-- Formulario de Valoración (solo para usuarios inscritos) -->
                        @if ($usuarioInscrito && !$usuarioCalifico)
                            <div class="rating-form mb-5 p-4 bg-light rounded-3">
                                <h5><i class="bi bi-pencil-square me-2"></i>Deja tu valoración</h5>
                                <form action="{{ route('cursos.calificar', encrypt($cursos->id)) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Tu calificación:</label>
                                        <div class="rating-stars-input">
                                            @for ($i = 5; $i >= 1; $i--)
                                                <input type="radio" id="star{{ $i }}" name="puntuacion"
                                                    value="{{ $i }}" required>
                                                <label for="star{{ $i }}">★</label>
                                            @endfor
                                        </div>
                                        @error('puntuacion')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="comentario" class="form-label">Comentario
                                            (opcional):</label>
                                        <textarea name="comentario" class="form-control" rows="3" placeholder="¿Qué te pareció el curso?">{{ old('comentario') }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-send-fill me-1"></i> Enviar Valoración
                                    </button>
                                </form>
                            </div>
                        @elseif($usuarioCalifico)
                            <div class="alert alert-info">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <i class="bi bi-info-circle-fill me-2"></i>
                                        Ya calificaste este curso con {{ $calificacionUsuario->puntuacion }}
                                        estrellas.
                                        @if ($calificacionUsuario->comentario)
                                            <div class="mt-2">
                                                <strong>Tu comentario:</strong>
                                                <p class="mb-0">{{ $calificacionUsuario->comentario }}</p>
                                            </div>
                                        @endif
                                    </div>
                                    <button class="btn btn-sm btn-outline-warning"
                                        onclick="editarCalificacion({{ $calificacionUsuario->id }}, {{ $calificacionUsuario->puntuacion }}, '{{ $calificacionUsuario->comentario }}')">
                                        <i class="bi bi-pencil"></i> Editar
                                    </button>
                                </div>
                            </div>
                        @elseif(!Auth::check())
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <a href="{{ route('login') }}" class="alert-link">Inicia sesión</a> y
                                únete al curso para poder calificar.
                            </div>
                        @endif

                        <!-- Listado de Valoraciones -->
                        <h5 class="mt-4 mb-3"><i class="bi bi-chat-square-quote-fill me-2"></i>Últimas
                            valoraciones</h5>

                        @forelse($calificacionesRecientes as $calificacion)
                            <div class="card mb-3 border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="mb-1">{{ $calificacion->user->name }}</h6>
                                            <div class="stars small">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i
                                                        class="bi bi-star-fill {{ $i <= $calificacion->puntuacion ? 'text-warning' : 'text-secondary' }}"></i>
                                                @endfor
                                                <small
                                                    class="text-muted ms-2">{{ $calificacion->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-1">
                                            @if (auth()->id() === $calificacion->user_id)
                                                <button type="button" class="btn btn-sm btn-outline-warning"
                                                    onclick="editarCalificacion({{ $calificacion->id }}, {{ $calificacion->puntuacion }}, '{{ $calificacion->comentario }}')">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <form
                                                    action="{{ route('calificaciones.destroy', encrypt($calificacion->id)) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('¿Estás seguro de eliminar esta valoración?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            @hasrole('Administrador')
                                                <form
                                                    action="{{ route('calificaciones.destroy', encrypt($calificacion->id)) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('¿Estás seguro de eliminar esta valoración?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endrole
                                        </div>
                                    </div>
                                    @if ($calificacion->comentario)
                                        <p class="mt-2 mb-0">{{ $calificacion->comentario }}</p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="bi bi-chat-square-text text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mt-2">Aún no hay valoraciones para este curso</p>
                            </div>
                        @endforelse

                        @if ($cursos->calificaciones_count > 5)
                            <div class="text-center mt-3">
                                <a href="{{ route('cursos.allRatings', encrypt($cursos->id)) }}"
                                    class="btn btn-outline-primary">
                                    <i class="bi bi-list-ul me-1"></i> Ver todas las valoraciones
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <!-- Modal para Editar Valoración -->
    <div class="modal fade" id="editarCalificacionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square me-2"></i>Editar tu valoración
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditarCalificacion" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Tu calificación:</label>
                            <div class="rating-stars-input" id="editStarsContainer">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="edit_star{{ $i }}" name="puntuacion"
                                        value="{{ $i }}" required>
                                    <label for="edit_star{{ $i }}">★</label>
                                @endfor
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_comentario" class="form-label">Comentario (opcional):</label>
                            <textarea name="comentario" id="edit_comentario" class="form-control" rows="3"
                                placeholder="¿Qué te pareció el curso?"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check-lg me-1"></i>Actualizar Valoración
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts para manejar la edición -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function editarCalificacion(id, puntuacion, comentario) {
            // Actualizar la acción del formulario
            document.getElementById('formEditarCalificacion').action = `/calificaciones/${id}`;

            // Establecer la puntuación actual
            document.getElementById(`edit_star${puntuacion}`).checked = true;

            // Establecer el comentario actual
            document.getElementById('edit_comentario').value = comentario || '';

            // Mostrar el modal
            new bootstrap.Modal(document.getElementById('editarCalificacionModal')).show();
        }

        // Manejar el envío del formulario de edición
        document.getElementById('formEditarCalificacion').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const actionUrl = this.action;

            fetch(actionUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-HTTP-Method-Override': 'PUT'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Cerrar modal
                        bootstrap.Modal.getInstance(document.getElementById('editarCalificacionModal')).hide();

                        // Mostrar mensaje de éxito
                        Swal.fire({
                            icon: 'success',
                            title: '¡Actualizado!',
                            text: data.message || 'Tu valoración ha sido actualizada correctamente',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // Recargar la página después de un breve delay
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Ocurrió un error al actualizar la valoración'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error inesperado'
                    });
                });
        });
    </script>




@endsection





@include('layoutlanding')
