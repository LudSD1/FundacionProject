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
                            <h3 class="text-white">
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
                                                    href="{{ route('Curso', $cursos->codigoCurso ?? $cursos->id) }}">
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
                                            @if(auth()->check() && auth()->user()->hasRole('Docente'))
                                                {{-- Los docentes no pueden inscribirse --}}
                                                <div class="text-center py-4">
                                                    <i class="bi bi-info-circle-fill text-primary" style="font-size: 2.5rem;"></i>
                                                    <h5 class="mt-3 fw-bold text-primary">Área de Inscripción</h5>
                                                    <p class="text-muted mb-0">
                                                        La inscripción está disponible únicamente para estudiantes.
                                                        Como docente, puedes gestionar este curso desde el panel de administración.
                                                    </p>
                                                </div>
                                            @else
                                            {{-- Indicador de cupos disponibles --}}
                                            @if (!$cursos->esCuposIlimitados())
                                                @php
                                                    $cuposDisponibles = $cursos->cuposDisponibles();
                                                    $cuposAgotados = $cuposDisponibles <= 0;
                                                    $cuposBajos = !$cuposAgotados && $cuposDisponibles <= 5;
                                                @endphp
                                                <div class="text-center mb-3">
                                                    @if ($cuposAgotados)
                                                        <div class="alert alert-danger d-flex align-items-center justify-content-center gap-2 py-2 mb-0 rounded-pill" role="alert">
                                                            <i class="bi bi-x-circle-fill fs-5"></i>
                                                            <strong>¡Cupos Agotados!</strong>
                                                        </div>
                                                    @elseif ($cuposBajos)
                                                        <div class="alert alert-warning d-flex align-items-center justify-content-center gap-2 py-2 mb-0 rounded-pill" role="alert">
                                                            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                                                            <strong>¡Solo {{ $cuposDisponibles }} cupo{{ $cuposDisponibles > 1 ? 's' : '' }} disponible{{ $cuposDisponibles > 1 ? 's' : '' }}!</strong>
                                                        </div>
                                                    @else
                                                        <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
                                                            <i class="bi bi-people-fill me-1"></i>
                                                            {{ $cuposDisponibles }} cupo{{ $cuposDisponibles > 1 ? 's' : '' }} disponible{{ $cuposDisponibles > 1 ? 's' : '' }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif

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

                                                @if (!$cursos->esCuposIlimitados() && $cursos->cuposDisponibles() <= 0)
                                                    {{-- Cupos agotados: botón deshabilitado --}}
                                                    <button class="btn btn-secondary w-100 py-3 fw-bold fs-5" disabled>
                                                        <i class="bi bi-x-circle-fill me-2"></i>Cupos Agotados
                                                    </button>
                                                    <p class="text-muted text-center mt-2 small">
                                                        <i class="bi bi-info-circle me-1"></i>
                                                        No hay cupos disponibles en este momento. Intenta más tarde.
                                                    </p>
                                                @else
                                                    <button class="btn btn-success w-100 py-3 fw-bold fs-5"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#{{ auth()->check() ? 'compraCursoModal' : 'loginRequiredModal' }}">
                                                        <i class="bi bi-credit-card me-2"></i>Comprar Ahora
                                                    </button>
                                                @endif
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

                                                @if ($cursos->registros_habilitados)
                                                    @if ($cursos->certificados_disponibles == true)
                                                        <!-- Contador de tiempo -->
                                                        <div class="text-center mb-4">
                                                            <h5 class="text-primary fw-bold mb-3">
                                                                <i class="bi bi-clock-history me-2"></i>Tiempo Disponible
                                                            </h5>
                                                            <div id="countdown-timer"></div>
                                                        </div>

                                                        @if (!$cursos->esCuposIlimitados() && $cursos->cuposDisponibles() <= 0)
                                                            {{-- Cupos agotados para congreso --}}
                                                            <button class="btn btn-secondary w-100 py-3 fw-bold fs-5" disabled>
                                                                <i class="bi bi-x-circle-fill me-2"></i>
                                                                Cupos Agotados
                                                            </button>
                                                            <p class="text-muted text-center mt-2 small">
                                                                <i class="bi bi-info-circle me-1"></i>
                                                                No hay cupos disponibles para este evento.
                                                            </p>
                                                        @elseif (auth()->user())
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
                                                @else
                                                    {{-- Registro no habilitado aún (Congresos) --}}
                                                    <div class="alert alert-info border-0 shadow-sm text-center mb-0 py-3">
                                                        <i class="bi bi-clock-history fs-3 d-block mb-2"></i>
                                                        <h6 class="fw-bold mb-1">Registro Próximamente</h6>
                                                        <p class="small mb-0">Los registros se habilitarán automáticamente 1 hora antes de finalizar el evento.</p>
                                                        <div class="mt-2 fw-bold text-primary">
                                                            Habilitación: {{ \Carbon\Carbon::parse($cursos->fecha_fin)->subHour()->format('d/m/Y H:i') }}
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                            @endif {{-- end Docente check --}}
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
                                        // Extraer el ID correctamente para cualquier formato de YouTube (embed, watch, youtu.be, etc.)
                                        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $youtubeUrl, $matches)) {
                                            $videoId = $matches[1];
                                        } elseif (preg_match('/(?:v=|\/)([0-9A-Za-z_-]{11})/', $youtubeUrl, $matches)) {
                                            // Fallback
                                            $videoId = $matches[1] ?? null;
                                        }
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
                                        <div class="hero-youtube-container hero-media rounded-4 ratio ratio-16x9" data-video-id="{{ $videoId }}">
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



        @include('partials.cursos.modals.registro_modals')



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


    @include('partials.cursos.valoraciones_section')




@endsection





@include('layoutlanding')
