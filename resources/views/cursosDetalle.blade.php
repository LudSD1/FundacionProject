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
                    <div class="col-lg-6 order-1 order-lg-2 mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="200">
                        <div id="courseCarousel" class="carousel slide course-carousel" data-bs-ride="carousel"
                            data-bs-interval="6000">
                            <div class="carousel-inner">

                                @php
                                    // Detectar video YouTube
                                    $youtubeUrl = $cursos->youtube_url;
                                    $videoId = null;

                                    if ($youtubeUrl) {
                                        preg_match('/(?:v=|\/)([0-9A-Za-z_-]{11})/', $youtubeUrl, $matches);
                                        $videoId = $matches[1] ?? null;
                                    }

                                    $hasVideo = !empty($videoId);

                                    // Filtrar imágenes válidas (activas y con URL no vacía)
                                    $images = $cursos->imagenes
                                        ->where('activo', true)
                                        ->filter(fn($img) => !empty($img->url))
                                        ->values();
                                @endphp

                                {{-- Mostrar video si existe --}}
                                @if ($hasVideo)
                                    <div class="carousel-item active" data-type="video">
                                        <div class="ratio ratio-16x9 youtube-container">
                                            <iframe
                                                src="https://www.youtube.com/embed/{{ $videoId }}?rel=0&enablejsapi=1&modestbranding=1"
                                                loading="lazy"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                referrerpolicy="strict-origin-when-cross-origin" frameborder="0"
                                                allowfullscreen class="youtube-iframe"></iframe>
                                        </div>
                                    </div>
                                @endif

                                {{-- Mostrar imágenes --}}
                                @foreach ($images as $i => $media)
                                    <div class="carousel-item {{ !$hasVideo && $i === 0 ? 'active' : '' }}"
                                        data-type="image">
                                        <img src="{{ asset($media->url) }}" class="d-block w-100 carousel-image"
                                            alt="{{ $media->titulo ?? 'Imagen de curso' }}" loading="lazy">
                                    </div>
                                @endforeach

                                {{-- Si no hay video ni imágenes, mostrar imagen por defecto --}}
                                @if (!$hasVideo && $images->count() === 0)
                                    <div class="carousel-item active" data-type="image">
                                        <img src="{{ asset('assets2/img/congress.jpg') }}"
                                            class="d-block w-100 carousel-image" alt="Imagen por defecto" loading="lazy">
                                    </div>
                                @endif

                            </div>

                            {{-- Controles solo si hay más de un slide --}}
                            @if (($hasVideo ? 1 : 0) + $images->count() > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#courseCarousel"
                                    data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#courseCarousel"
                                    data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Siguiente</span>
                                </button>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </section>

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
