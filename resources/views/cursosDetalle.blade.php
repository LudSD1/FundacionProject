@section('hero')



       <div class="page-cursos">
        <section id="course-detail-hero" class="course-hero">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 d-lg-flex flex-lg-column justify-content-center align-items-stretch pt-5 pt-lg-0 order-2 order-lg-1"
                        data-aos="fade-up">
                        <div class="course-detail-info">
                            <h3 class="course-title">{{ $cursos->nombreCurso }}</h3>
                            <h2 class="course-description">{{ $cursos->descripcionC }}</h2>

                            @php
                                \Carbon\Carbon::setLocale('es_BO');

                                // Variables para simplificar condiciones
                                $esCurso = $cursos->tipo === 'curso';
                                $esCongreso = $cursos->tipo === 'congreso';
                                $usuarioRetirado = $estadoInscripcion === 'retirado';
                                $usuarioAutenticado = auth()->check();
                                $tienePrecio = isset($cursos->precio) && $cursos->precio > 0;
                                $certificadosDisponibles = $cursos->certificados_disponibles ?? false;
                                $metodosPagoActivos = $metodosPago->where('is_active', true)->sortBy('sort_order');
                                $tieneMetodosPago = $metodosPagoActivos->count() > 0;
                            @endphp

                            <h2 class="course-date">📅
                                {{ \Carbon\Carbon::parse($cursos->fecha_ini)->translatedFormat('d \d\e F \d\e Y') }}</h2>

                            @if ($usuarioRetirado)

                                <div class="container mt-5">
                                    <div class="row justify-content-center">
                                        <div class="col-md-8">
                                            <div class="alert alert-warning" role="alert">
                                                <h4 class="alert-heading">Fuiste retirado de este curso</h4>
                                                <p>Lamentamos informarte que has sido retirado de este curso. Si crees que
                                                    esto
                                                    es un error, por favor contacta al administrador.</p>
                                                <hr>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <p class="mb-0">Fecha de retiro:
                                                        <strong>{{ $usuarioRetirado->deleted_at->format('d/m/Y') }}</strong>
                                                    </p>
                                                    <a href="{{ route('Inicio') }}" class="btn btn-outline-primary">
                                                        Volver a los cursos
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="card shadow-lg rounded-3 border-0 overflow-hidden course-purchase-card">
                                    @if ($usuarioInscrito)
                                        {{-- Usuario inscrito --}}
                                        <div class="card-header bg-light py-3 px-4 border-bottom">
                                            <h4 class="mb-0 fw-bold">
                                                <i class="bi bi-mortarboard-fill me-2 text-success"></i>
                                                {{ $esCurso ? 'Acceso al Curso' : 'Obtener Certificado' }}
                                            </h4>
                                        </div>
                                        <div class="card-body p-4 course-price-panel">
                                            @if ($esCurso)
                                                <a class="btn btn-sm btn-success"
                                                    href="{{ route('Curso', encrypt($cursos->id)) }}">
                                                    <i class="bi bi-door-open me-2"></i> Ir al Curso
                                                </a>
                                            @else
                                                @if ($usuarioInscrito->certificado === null)
                                                    <p>Ponte en contacto con el colaborador del evento.</p>
                                                @else
                                                    <ul>
                                                        <li class="text-success">
                                                            <i class="bi bi-award-fill me-2"></i>
                                                            <a href="{{ route('verificar.certificado', $usuarioInscrito->certificado->codigo_certificado) }}"
                                                                target="_blank">Descargar Certificado</a>
                                                        </li>
                                                    </ul>
                                                @endif
                                            @endif
                                        </div>
                                    @else
                                        <div class="card-header bg-light py-3 px-4 border-bottom">
                                            <h4 class="mb-0 fw-bold">
                                                @if ($esCurso)
                                                    <i class="bi bi-mortarboard-fill me-2 text-success"></i>Acceso al Curso
                                                @else
                                                    <i class="bi bi-calendar-event me-2 text-primary"></i>Registro al Evento
                                                @endif
                                            </h4>
                                        </div>
                                        <div class="card-body p-4">
                                            @if ($esCurso)
                                                <div class="text-center mb-4 course-price-panel">
                                                    <span class="badge bg-success-subtle text-success px-3 py-2 mb-2">Oferta
                                                        Especial</span>
                                                    <h3 class="fw-bold text-success mb-1">Bs.
                                                        {{ number_format($cursos->precio, 2) }}
                                                    </h3>
                                                    <p class="text-muted">Pago único, acceso de por vida</p>
                                                    <div
                                                        class="d-flex justify-content-center align-items-center gap-2 mb-2 course-benefit">
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                        <span>Certificado Digital Incluido</span>
                                                    </div>
                                                    <div
                                                        class="d-flex justify-content-center align-items-center gap-2 course-benefit">
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                        <span>Soporte 24/7</span>
                                                    </div>
                                                </div>

                                                <button class="btn btn-success w-100 py-3 fw-bold fs-5"
                                                    onclick="mostrarFormularioCompra()">
                                                    <i class="bi bi-credit-card me-2"></i> Comprar Ahora
                                                </button>

                                                @auth
                                                    {{-- Formulario oculto para SweetAlert --}}
                                                    <form id="formCompraCurso" action="{{ route('registrarpagoPost') }}"
                                                        method="POST" enctype="multipart/form-data" style="display: none;">
                                                        @csrf
                                                        <input type="hidden" name="estudiante_id"
                                                            value="{{ auth()->user()->id }}">
                                                        <input type="hidden" name="curso_id" value="{{ $cursos->id }}">
                                                        <input type="text" name="user"
                                                            value="{{ auth()->user()->name }} {{ auth()->user()->lastname1 }} {{ auth()->user()->lastname2 }}">
                                                        @if ($tienePrecio)
                                                            <input type="number" name="montopagar"
                                                                value="{{ $cursos->precio }}">
                                                            <input type="file" name="comprobante" id="comprobanteInput"
                                                                accept=".pdf,.jpg,.png">
                                                        @endif
                                                        <textarea name="descripcion" id="descripcionInput"></textarea>
                                                    </form>

                                                    {{-- Modal oculto para mantener compatibilidad con métodos de pago --}}
                                                    <div id="paymentMethodsContainer" style="display: none;">
                                                        @if ($tienePrecio && $tieneMetodosPago)
                                                            <div id="paymentMethodsCarousel" class="carousel slide"
                                                                data-bs-ride="carousel">
                                                                <div class="carousel-inner">
                                                                    @foreach ($metodosPagoActivos as $index => $metodo)
                                                                        <div
                                                                            class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                                            <div class="card border-0 shadow-sm">
                                                                                <div class="card-body text-center p-3">
                                                                                    <h6 class="card-title text-primary mb-2">
                                                                                        {{ $metodo->name }}</h6>
                                                                                    @if ($metodo->qr_image)
                                                                                        <div class="mb-3">
                                                                                            <img src="{{ $metodo->qr_image_url }}"
                                                                                                alt="QR {{ $metodo->name }}"
                                                                                                class="img-fluid rounded"
                                                                                                style="max-height: 200px; max-width: 200px;">
                                                                                        </div>
                                                                                    @endif
                                                                                    @if ($metodo->account_holder)
                                                                                        <p class="mb-1">
                                                                                            <strong>Titular:</strong>
                                                                                            {{ $metodo->account_holder }}
                                                                                        </p>
                                                                                    @endif
                                                                                    @if ($metodo->account_number)
                                                                                        <p class="mb-1">
                                                                                            <strong>Cuenta:</strong>
                                                                                            {{ $metodo->account_number }}
                                                                                        </p>
                                                                                    @endif
                                                                                    @if ($metodo->description)
                                                                                        <p class="text-muted small mb-2">
                                                                                            {{ $metodo->description }}</p>
                                                                                    @endif
                                                                                    @if ($metodo->additional_info && count($metodo->additional_info) > 0)
                                                                                        <div class="mt-2">
                                                                                            @foreach ($metodo->additional_info as $info)
                                                                                                @if (isset($info['key']) && isset($info['value']) && !empty($info['key']) && !empty($info['value']))
                                                                                                    <small
                                                                                                        class="d-block text-muted">
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
                                                                @if ($metodosPagoActivos->count() > 1)
                                                                    <button class="carousel-control-prev" type="button"
                                                                        data-bs-target="#paymentMethodsCarousel"
                                                                        data-bs-slide="prev">
                                                                        <span class="carousel-control-prev-icon"
                                                                            aria-hidden="true"></span>
                                                                        <span class="visually-hidden">Anterior</span>
                                                                    </button>
                                                                    <button class="carousel-control-next" type="button"
                                                                        data-bs-target="#paymentMethodsCarousel"
                                                                        data-bs-slide="next">
                                                                        <span class="carousel-control-next-icon"
                                                                            aria-hidden="true"></span>
                                                                        <span class="visually-hidden">Siguiente</span>
                                                                    </button>
                                                                    <div class="carousel-indicators">
                                                                        @foreach ($metodosPagoActivos as $index => $metodo)
                                                                            <button type="button"
                                                                                data-bs-target="#paymentMethodsCarousel"
                                                                                data-bs-slide-to="{{ $index }}"
                                                                                class="{{ $index === 0 ? 'active' : '' }}"
                                                                                aria-label="Método {{ $index + 1 }}"></button>
                                                                        @endforeach
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @elseif ($tienePrecio)
                                                            <div class="text-center">
                                                                <img src="{{ asset('assets/img/pago.png') }}"
                                                                    alt="Métodos de pago" class="img-fluid">
                                                            </div>
                                                        @endif
                                                    </div>

                                                @endauth

                                                @guest
                                                    <div class="modal fade" id="loginRequiredModal" tabindex="-1"
                                                        aria-labelledby="loginRequiredModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="loginRequiredModalLabel">
                                                                        Acceso
                                                                        Requerido</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body text-center">

                                                                    <h4 class="my-3">Debes iniciar sesión para continuar</h4>
                                                                    <p>Para realizar una compra necesitas tener una cuenta en
                                                                        nuestro
                                                                        sistema.</p>
                                                                </div>
                                                                <div class="modal-footer justify-content-center">
                                                                    <a href="{{ route('login') }}"
                                                                        class="btn btn-primary me-2">
                                                                        <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                                                                    </a>
                                                                    <a href="{{ route('signin') }}" class="btn btn-success">
                                                                        <i class="bi bi-person-plus"></i> Registrarse
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endguest
                                            @else
                                                {{-- Información del Congreso --}}
                                                <div class="text-center mb-4">
                                                    @if ($tienePrecio)
                                                        <h3 class="fw-bold text-primary mb-1">
                                                            ${{ number_format($cursos->precio, 2) }}</h3>
                                                    @else
                                                        <h3 class="fw-bold text-primary mb-1">Acceso Gratuito</h3>
                                                    @endif

                                                    <div
                                                        class="d-flex justify-content-center align-items-center gap-2 mb-2">
                                                        <i class="bi bi-check-circle-fill text-primary"></i>
                                                        <span>Material del Evento</span>
                                                    </div>
                                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                                        <i class="bi bi-check-circle-fill text-primary"></i>
                                                        <span>Certificado de Asistencia</span>
                                                    </div>
                                                </div>

                                                @if ($certificadosDisponibles)
                                                    <div class="text-center mb-3">
                                                        <h3>Tiempo Disponible</h3>
                                                        <div id="countdown-timer"
                                                            class="badge bg-primary-subtle text-primary px-3 py-2"></div>
                                                    </div>

                                                    @if ($usuarioAutenticado)
                                                        <form
                                                            action="{{ route('certificados.obtener', encrypt($cursos->id)) }}"
                                                            method="POST">
                                                            @csrf
                                                            <input type="hidden" name="congreso_id"
                                                                value="{{ $cursos->id }}">
                                                            <div class="d-grid gap-2">
                                                                <button type="submit"
                                                                    class="btn btn-success btn-lg py-3">
                                                                    <i class="bi bi-award-fill me-2"></i>
                                                                    Obtener Mi Certificado Ahora
                                                                </button>
                                                            </div>
                                                        </form>
                                                    @else
                                                        <button
                                                            class="btn btn-primary w-100 py-3 fw-bold fs-5 d-flex align-items-center justify-content-center gap-2"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#opcionesRegistroModal">
                                                            <i class="bi bi-person-plus-fill"></i>
                                                            <span>Registrarse Ahora</span>
                                                        </button>
                                                    @endif
                                                @else
                                                    <div class="d-grid gap-2">
                                                        <button class="btn btn-info btn-lg py-3">
                                                            <i class="bi bi-award-fill me-2"></i>
                                                            El certificado no esta disponible
                                                        </button>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endif



                        </div>
                    </div>
                    <div class="col-lg-6 order-1 order-lg-2" data-aos="fade-up" data-aos-delay="200">
                        <div id="courseCarousel" class="carousel slide course-carousel" data-bs-ride="carousel">
                            <div class="carousel-inner rounded-3 shadow">

                                {{-- Si el curso tiene un video de YouTube --}}
                                @php
                                    $index = 0;
                                    $youtubeUrl = $cursos->youtube_url;
                                    $videoId = null;

                                    if ($youtubeUrl) {
                                        preg_match(
                                            '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|embed)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/',
                                            $youtubeUrl,
                                            $matches,
                                        );
                                        $videoId = $matches[1] ?? null;
                                    }
                                @endphp

                                @if ($videoId)
                                    <div class="carousel-item active">
                                        <div class="ratio ratio-16x9">
                                            <iframe src="https://www.youtube.com/embed/{{ $videoId }}"
                                                frameborder="0" allowfullscreen></iframe>
                                        </div>
                                    </div>
                                    @php $index++; @endphp
                                @endif

                                @forelse ($cursos->imagenes->where('activo', true)->values() as $i => $media)
                                    <div class="carousel-item {{ !$videoId && $i === 0 ? 'active' : '' }}">
                                        <img src="{{ asset($media->url) }}" class="d-block w-100"
                                            alt="{{ $media->titulo ?? 'Imagen de curso' }}">
                                    </div>
                                @empty
                                    @if (!$videoId)
                                        <div class="carousel-item active">
                                            <img src="{{ asset('assets2/img/congress.jpg') }}" class="d-block w-100"
                                                alt="Imagen por defecto">
                                        </div>
                                    @endif
                                @endforelse
                            </div>

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
                        </div>
                    </div>

                    <!-- Sección de Valoraciones -->




                </div>

            </div>
        </section>

        @if ($esCurso)
            <section class="mt-5" id="temario">
                <div class="container">
                    <div class="row">
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
                                                    data-bs-target="#descripcionTema{{ $i }}"
                                                    aria-expanded="false"
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
                </div>
            </section>
        @else
            <div class="container">
                <div class="row">
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
                                                            {{ $expositor->pivot->cargo ?? 'Cargo no especificado' }}</p>
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
            </div>
        @endif




        @include('Cursos.valoracion')



        {{-- Modales de Registro para Congresos --}}
        @if ($esCongreso && $certificadosDisponibles)
            <!-- Modal de Login para Congresos -->
            <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header bg-primary text-white py-3">
                            <h5 class="modal-title">
                                <i class="bi bi-person-check me-2"></i>Coloca tu correo electrónico si ya estás registrado
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <form action="{{ route('congreso.inscribir') }}" method="POST">
                                @csrf
                                <input type="hidden" name="congreso_id" value="{{ $cursos->id }}">

                                <div class="mb-4">
                                    <label for="loginEmail" class="form-label">Correo Electrónico</label>
                                    <div class="input-group input-group-xl">
                                        <span class="input-group-text bg-light">
                                            <i class="bi bi-envelope"></i>
                                        </span>
                                        <input type="email" class="form-control form-control-xl" id="loginEmail"
                                            name="email" required placeholder="tu@email.com">
                                    </div>
                                    <small class="text-muted">Ingresa el email con el que estás registrado</small>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary py-3">
                                        <i class="bi bi-award me-2"></i> Obtener Certificado
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer justify-content-center bg-light py-3">
                            <small class="text-muted">
                                ¿No tienes cuenta?
                                <a href="#" class="link-registro-desde-login">
                                    Regístrate aquí
                                </a>
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de Registro para Congresos -->
            <div class="modal fade" id="registroCongresoModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header bg-primary text-white py-3">
                            <h2 class="text-light modal-title">
                                <i class="bi bi-person-badge me-2"></i>Registro al Congreso
                            </h2>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body p-4">
                            <!-- Indicador de progreso -->
                            <div class="registration-progress mb-4">
                                <div class="progress-steps">
                                    <div class="step active" data-step="1">
                                        <div class="step-number">1</div>
                                        <div class="step-label">Datos Personales</div>
                                    </div>
                                    <div class="step" data-step="2">
                                        <div class="step-number">2</div>
                                        <div class="step-label">Credenciales</div>
                                    </div>
                                    <div class="step" data-step="3">
                                        <div class="step-number">3</div>
                                        <div class="step-label">Confirmación</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mensajes de error -->
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <ul class="list-unstyled mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li class="mb-2"><i
                                                    class="fas fa-exclamation-circle me-2"></i>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <!-- Form -->
                            <form action="{{ route('registrarseCongreso', encrypt($cursos->id)) }}" method="POST"
                                id="formRegistroCongreso" class="auth-form">
                                @csrf

                                <!-- Paso 1: Datos Personales -->
                                <div class="registration-step active" data-step="1">
                                    <h5 class="mb-4 text-primary fw-bold">
                                        <i class="bi bi-person-circle me-2"></i>Datos Personales
                                    </h5>

                                    <div class="mb-3">
                                        <div class="col-md-12">
                                            <label for="name" class="form-label fw-semibold m-2">Nombre</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="bi bi-person"></i>
                                                </span>
                                                <input type="text" class="form-control input-spaced" id="name"
                                                    name="name" value="{{ old('name') }}" placeholder="Tu nombre"
                                                    required>
                                            </div>
                                        </div>
                                        <br>

                                        <div class="col-md-12">
                                            <label for="lastname1" class="form-label fw-semibold m-2">Apellido
                                                Paterno</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="bi bi-person-badge"></i>
                                                </span>
                                                <input type="text" class="form-control input-spaced" id="lastname1"
                                                    name="lastname1" value="{{ old('lastname1') }}"
                                                    placeholder="Apellido Paterno" required>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="col-md-12">
                                            <label for="lastname2" class="form-label fw-semibold m-2">Apellido
                                                Materno</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="bi bi-person-badge-fill"></i>
                                                </span>
                                                <input type="text" class="form-control input-spaced" id="lastname2"
                                                    name="lastname2" value="{{ old('lastname2') }}"
                                                    placeholder="Apellido Materno">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="country" class="form-label fw-semibold mb-2">País</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="bi bi-globe"></i>
                                            </span>
                                            <select class="form-select input-spaced" id="country" name="country"
                                                required>
                                                <option value="">Selecciona tu país</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="button" class="btn btn-primary btn-next" data-next="2">
                                            Siguiente <i class="bi bi-arrow-right ms-2"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Paso 2: Credenciales -->
                                <div class="registration-step" data-step="2">
                                    <h5 class="mb-4 text-primary fw-bold">
                                        <i class="bi bi-shield-lock me-2"></i>Credenciales de Acceso
                                    </h5>

                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-semibold m-2">Correo
                                            electrónico</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="bi bi-envelope"></i>
                                            </span>
                                            <input type="email" class="form-control input-spaced" id="email"
                                                name="email" value="{{ old('email') }}" placeholder="tu@correo.com"
                                                required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="col-md-12">
                                            <label for="password" class="form-label fw-semibold m-2">Contraseña</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="bi bi-lock"></i>
                                                </span>
                                                <input type="password" class="form-control input-spaced" id="password"
                                                    name="password" placeholder="••••••••" required>
                                                <button class="btn btn-outline-secondary toggle-password-modal"
                                                    type="button" data-target="password">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="col-md-12">
                                            <label for="password_confirmation"
                                                class="form-label fw-semibold mb-2">Confirmar
                                                Contraseña</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="bi bi-lock-fill"></i>
                                                </span>
                                                <input type="password" class="form-control input-spaced"
                                                    id="password_confirmation" name="password_confirmation"
                                                    placeholder="••••••••" required>
                                                <button class="btn btn-outline-secondary toggle-password-modal"
                                                    type="button" data-target="password_confirmation">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="button" class="btn btn-outline-secondary btn-prev" data-prev="1">
                                            <i class="bi bi-arrow-left me-2"></i>Anterior
                                        </button>
                                        <button type="button" class="btn btn-primary btn-next" data-next="3">
                                            Siguiente <i class="bi bi-arrow-right ms-2"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Paso 3: Confirmación -->
                                <div class="registration-step" data-step="3">
                                    <h5 class="mb-4 text-primary fw-bold">
                                        <i class="bi bi-check-circle me-2"></i>Confirmación
                                    </h5>

                                    <div class="alert alert-info mb-4">
                                        <h6 class="alert-heading mb-3">Revisa tus datos:</h6>
                                        <div id="review-data-modal" class="small">
                                            <!-- Se llenará con JavaScript -->
                                        </div>
                                    </div>

                                    <div class="alert alert-success d-flex align-items-center">
                                        <i class="bi bi-shield-check fs-4 me-3"></i>
                                        <small>Tus datos están protegidos y no serán compartidos con terceros. Al
                                            registrarte aceptas los términos del congreso.</small>
                                    </div>

                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="button" class="btn btn-outline-secondary btn-prev" data-prev="2">
                                            <i class="bi bi-arrow-left me-2"></i>Anterior
                                        </button>
                                        <button type="submit" class="btn btn-primary btn-auth">
                                            <i class="bi bi-person-plus-fill me-2"></i>Confirmar Registro
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="modal-footer justify-content-center bg-light py-3 border-0">
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                ¿Ya tienes una cuenta?
                                <a href="{{ route('login.signin') }}"
                                    class="text-decoration-none text-primary fw-semibold">
                                    Inicia sesión aquí
                                </a>
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            @push('scripts')
                <script>
                    // SweetAlert2 para opciones de registro
                    function mostrarOpcionesRegistro() {
                        Swal.fire({
                            title: '<strong>Opciones de Registro</strong>',
                            html: `
                    <p class="mb-4 text-muted">¿Cómo deseas continuar?</p>
                    <div class="d-grid gap-3">
                        <button class="btn btn-primary btn-lg swal-btn-nuevo">
                            <i class="bi bi-person-plus me-2"></i>Nuevo Registro
                        </button>
                        <button class="btn btn-outline-primary btn-lg swal-btn-login">
                            <i class="bi bi-person-check me-2"></i>Ya tengo cuenta
                        </button>
                    </div>
                `,
                            showConfirmButton: false,
                            showCancelButton: true,
                            cancelButtonText: 'Cancelar',
                            customClass: {
                                popup: 'swal-wide',
                                cancelButton: 'btn btn-outline-secondary'
                            },
                            buttonsStyling: false,
                            width: '500px',
                            didOpen: () => {
                                document.querySelector('.swal-btn-nuevo').addEventListener('click', () => {
                                    Swal.close();
                                    const registroModal = new bootstrap.Modal(document.getElementById(
                                        'registroCongresoModal'));
                                    registroModal.show();
                                });

                                document.querySelector('.swal-btn-login').addEventListener('click', () => {
                                    Swal.close();
                                    const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                                    loginModal.show();
                                });
                            }
                        });
                    }

                    document.addEventListener('DOMContentLoaded', function() {
                        // Reemplazar el comportamiento del botón de inscripción
                        const btnInscripcion = document.querySelector('[data-bs-target="#opcionesRegistroModal"]');

                        if (btnInscripcion) {
                            btnInscripcion.removeAttribute('data-bs-toggle');
                            btnInscripcion.removeAttribute('data-bs-target');

                            btnInscripcion.addEventListener('click', function(e) {
                                e.preventDefault();
                                mostrarOpcionesRegistro();
                            });
                        }

                        // Link de registro desde el modal de login
                        const linkRegistro = document.querySelector('.link-registro-desde-login');
                        if (linkRegistro) {
                            linkRegistro.addEventListener('click', function(e) {
                                e.preventDefault();
                                bootstrap.Modal.getInstance(document.getElementById('loginModal')).hide();
                                setTimeout(() => {
                                    const registroModal = new bootstrap.Modal(document.getElementById(
                                        'registroCongresoModal'));
                                    registroModal.show();
                                }, 300);
                            });
                        }
                    });
                </script>
            @endpush
        @endif
    </div>    <div class="page-cursos">
        <section id="course-detail-hero" class="course-hero">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 d-lg-flex flex-lg-column justify-content-center align-items-stretch pt-5 pt-lg-0 order-2 order-lg-1"
                        data-aos="fade-up">
                        <div class="course-detail-info">
                            <h3 class="course-title">{{ $cursos->nombreCurso }}</h3>
                            <h2 class="course-description">{{ $cursos->descripcionC }}</h2>

                            @php
                                \Carbon\Carbon::setLocale('es_BO');

                                // Variables para simplificar condiciones
                                $esCurso = $cursos->tipo === 'curso';
                                $esCongreso = $cursos->tipo === 'congreso';
                                $usuarioRetirado = $estadoInscripcion === 'retirado';
                                $usuarioAutenticado = auth()->check();
                                $tienePrecio = isset($cursos->precio) && $cursos->precio > 0;
                                $certificadosDisponibles = $cursos->certificados_disponibles ?? false;
                                $metodosPagoActivos = $metodosPago->where('is_active', true)->sortBy('sort_order');
                                $tieneMetodosPago = $metodosPagoActivos->count() > 0;
                            @endphp

                            <h2 class="course-date">📅
                                {{ \Carbon\Carbon::parse($cursos->fecha_ini)->translatedFormat('d \d\e F \d\e Y') }}</h2>

                            @if ($usuarioRetirado)

                                <div class="container mt-5">
                                    <div class="row justify-content-center">
                                        <div class="col-md-8">
                                            <div class="alert alert-warning" role="alert">
                                                <h4 class="alert-heading">Fuiste retirado de este curso</h4>
                                                <p>Lamentamos informarte que has sido retirado de este curso. Si crees que
                                                    esto
                                                    es un error, por favor contacta al administrador.</p>
                                                <hr>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <p class="mb-0">Fecha de retiro:
                                                        <strong>{{ $usuarioRetirado->deleted_at->format('d/m/Y') }}</strong>
                                                    </p>
                                                    <a href="{{ route('Inicio') }}" class="btn btn-outline-primary">
                                                        Volver a los cursos
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="card shadow-lg rounded-3 border-0 overflow-hidden course-purchase-card">
                                    @if ($usuarioInscrito)
                                        {{-- Usuario inscrito --}}
                                        <div class="card-header bg-light py-3 px-4 border-bottom">
                                            <h4 class="mb-0 fw-bold">
                                                <i class="bi bi-mortarboard-fill me-2 text-success"></i>
                                                {{ $esCurso ? 'Acceso al Curso' : 'Obtener Certificado' }}
                                            </h4>
                                        </div>
                                        <div class="card-body p-4 course-price-panel">
                                            @if ($esCurso)
                                                <a class="btn btn-sm btn-success"
                                                    href="{{ route('Curso', encrypt($cursos->id)) }}">
                                                    <i class="bi bi-door-open me-2"></i> Ir al Curso
                                                </a>
                                            @else
                                                @if ($usuarioInscrito->certificado === null)
                                                    <p>Ponte en contacto con el colaborador del evento.</p>
                                                @else
                                                    <ul>
                                                        <li class="text-success">
                                                            <i class="bi bi-award-fill me-2"></i>
                                                            <a href="{{ route('verificar.certificado', $usuarioInscrito->certificado->codigo_certificado) }}"
                                                                target="_blank">Descargar Certificado</a>
                                                        </li>
                                                    </ul>
                                                @endif
                                            @endif
                                        </div>
                                    @else
                                        <div class="card-header bg-light py-3 px-4 border-bottom">
                                            <h4 class="mb-0 fw-bold">
                                                @if ($esCurso)
                                                    <i class="bi bi-mortarboard-fill me-2 text-success"></i>Acceso al Curso
                                                @else
                                                    <i class="bi bi-calendar-event me-2 text-primary"></i>Registro al Evento
                                                @endif
                                            </h4>
                                        </div>
                                        <div class="card-body p-4">
                                            @if ($esCurso)
                                                <div class="text-center mb-4 course-price-panel">
                                                    <span class="badge bg-success-subtle text-success px-3 py-2 mb-2">Oferta
                                                        Especial</span>
                                                    <h3 class="fw-bold text-success mb-1">Bs.
                                                        {{ number_format($cursos->precio, 2) }}
                                                    </h3>
                                                    <p class="text-muted">Pago único, acceso de por vida</p>
                                                    <div
                                                        class="d-flex justify-content-center align-items-center gap-2 mb-2 course-benefit">
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                        <span>Certificado Digital Incluido</span>
                                                    </div>
                                                    <div
                                                        class="d-flex justify-content-center align-items-center gap-2 course-benefit">
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                        <span>Soporte 24/7</span>
                                                    </div>
                                                </div>

                                                <button class="btn btn-success w-100 py-3 fw-bold fs-5"
                                                    onclick="mostrarFormularioCompra()">
                                                    <i class="bi bi-credit-card me-2"></i> Comprar Ahora
                                                </button>

                                                @auth
                                                    {{-- Formulario oculto para SweetAlert --}}
                                                    <form id="formCompraCurso" action="{{ route('registrarpagoPost') }}"
                                                        method="POST" enctype="multipart/form-data" style="display: none;">
                                                        @csrf
                                                        <input type="hidden" name="estudiante_id"
                                                            value="{{ auth()->user()->id }}">
                                                        <input type="hidden" name="curso_id" value="{{ $cursos->id }}">
                                                        <input type="text" name="user"
                                                            value="{{ auth()->user()->name }} {{ auth()->user()->lastname1 }} {{ auth()->user()->lastname2 }}">
                                                        @if ($tienePrecio)
                                                            <input type="number" name="montopagar"
                                                                value="{{ $cursos->precio }}">
                                                            <input type="file" name="comprobante" id="comprobanteInput"
                                                                accept=".pdf,.jpg,.png">
                                                        @endif
                                                        <textarea name="descripcion" id="descripcionInput"></textarea>
                                                    </form>

                                                    {{-- Modal oculto para mantener compatibilidad con métodos de pago --}}
                                                    <div id="paymentMethodsContainer" style="display: none;">
                                                        @if ($tienePrecio && $tieneMetodosPago)
                                                            <div id="paymentMethodsCarousel" class="carousel slide"
                                                                data-bs-ride="carousel">
                                                                <div class="carousel-inner">
                                                                    @foreach ($metodosPagoActivos as $index => $metodo)
                                                                        <div
                                                                            class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                                            <div class="card border-0 shadow-sm">
                                                                                <div class="card-body text-center p-3">
                                                                                    <h6 class="card-title text-primary mb-2">
                                                                                        {{ $metodo->name }}</h6>
                                                                                    @if ($metodo->qr_image)
                                                                                        <div class="mb-3">
                                                                                            <img src="{{ $metodo->qr_image_url }}"
                                                                                                alt="QR {{ $metodo->name }}"
                                                                                                class="img-fluid rounded"
                                                                                                style="max-height: 200px; max-width: 200px;">
                                                                                        </div>
                                                                                    @endif
                                                                                    @if ($metodo->account_holder)
                                                                                        <p class="mb-1">
                                                                                            <strong>Titular:</strong>
                                                                                            {{ $metodo->account_holder }}
                                                                                        </p>
                                                                                    @endif
                                                                                    @if ($metodo->account_number)
                                                                                        <p class="mb-1">
                                                                                            <strong>Cuenta:</strong>
                                                                                            {{ $metodo->account_number }}
                                                                                        </p>
                                                                                    @endif
                                                                                    @if ($metodo->description)
                                                                                        <p class="text-muted small mb-2">
                                                                                            {{ $metodo->description }}</p>
                                                                                    @endif
                                                                                    @if ($metodo->additional_info && count($metodo->additional_info) > 0)
                                                                                        <div class="mt-2">
                                                                                            @foreach ($metodo->additional_info as $info)
                                                                                                @if (isset($info['key']) && isset($info['value']) && !empty($info['key']) && !empty($info['value']))
                                                                                                    <small
                                                                                                        class="d-block text-muted">
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
                                                                @if ($metodosPagoActivos->count() > 1)
                                                                    <button class="carousel-control-prev" type="button"
                                                                        data-bs-target="#paymentMethodsCarousel"
                                                                        data-bs-slide="prev">
                                                                        <span class="carousel-control-prev-icon"
                                                                            aria-hidden="true"></span>
                                                                        <span class="visually-hidden">Anterior</span>
                                                                    </button>
                                                                    <button class="carousel-control-next" type="button"
                                                                        data-bs-target="#paymentMethodsCarousel"
                                                                        data-bs-slide="next">
                                                                        <span class="carousel-control-next-icon"
                                                                            aria-hidden="true"></span>
                                                                        <span class="visually-hidden">Siguiente</span>
                                                                    </button>
                                                                    <div class="carousel-indicators">
                                                                        @foreach ($metodosPagoActivos as $index => $metodo)
                                                                            <button type="button"
                                                                                data-bs-target="#paymentMethodsCarousel"
                                                                                data-bs-slide-to="{{ $index }}"
                                                                                class="{{ $index === 0 ? 'active' : '' }}"
                                                                                aria-label="Método {{ $index + 1 }}"></button>
                                                                        @endforeach
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @elseif ($tienePrecio)
                                                            <div class="text-center">
                                                                <img src="{{ asset('assets/img/pago.png') }}"
                                                                    alt="Métodos de pago" class="img-fluid">
                                                            </div>
                                                        @endif
                                                    </div>

                                                @endauth

                                                @guest
                                                    <div class="modal fade" id="loginRequiredModal" tabindex="-1"
                                                        aria-labelledby="loginRequiredModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="loginRequiredModalLabel">
                                                                        Acceso
                                                                        Requerido</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body text-center">

                                                                    <h4 class="my-3">Debes iniciar sesión para continuar</h4>
                                                                    <p>Para realizar una compra necesitas tener una cuenta en
                                                                        nuestro
                                                                        sistema.</p>
                                                                </div>
                                                                <div class="modal-footer justify-content-center">
                                                                    <a href="{{ route('login') }}"
                                                                        class="btn btn-primary me-2">
                                                                        <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                                                                    </a>
                                                                    <a href="{{ route('signin') }}" class="btn btn-success">
                                                                        <i class="bi bi-person-plus"></i> Registrarse
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endguest
                                            @else
                                                {{-- Información del Congreso --}}
                                                <div class="text-center mb-4">
                                                    @if ($tienePrecio)
                                                        <h3 class="fw-bold text-primary mb-1">
                                                            ${{ number_format($cursos->precio, 2) }}</h3>
                                                    @else
                                                        <h3 class="fw-bold text-primary mb-1">Acceso Gratuito</h3>
                                                    @endif

                                                    <div
                                                        class="d-flex justify-content-center align-items-center gap-2 mb-2">
                                                        <i class="bi bi-check-circle-fill text-primary"></i>
                                                        <span>Material del Evento</span>
                                                    </div>
                                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                                        <i class="bi bi-check-circle-fill text-primary"></i>
                                                        <span>Certificado de Asistencia</span>
                                                    </div>
                                                </div>

                                                @if ($certificadosDisponibles)
                                                    <div class="text-center mb-3">
                                                        <h3>Tiempo Disponible</h3>
                                                        <div id="countdown-timer"
                                                            class="badge bg-primary-subtle text-primary px-3 py-2"></div>
                                                    </div>

                                                    @if ($usuarioAutenticado)
                                                        <form
                                                            action="{{ route('certificados.obtener', encrypt($cursos->id)) }}"
                                                            method="POST">
                                                            @csrf
                                                            <input type="hidden" name="congreso_id"
                                                                value="{{ $cursos->id }}">
                                                            <div class="d-grid gap-2">
                                                                <button type="submit"
                                                                    class="btn btn-success btn-lg py-3">
                                                                    <i class="bi bi-award-fill me-2"></i>
                                                                    Obtener Mi Certificado Ahora
                                                                </button>
                                                            </div>
                                                        </form>
                                                    @else
                                                        <button
                                                            class="btn btn-primary w-100 py-3 fw-bold fs-5 d-flex align-items-center justify-content-center gap-2"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#opcionesRegistroModal">
                                                            <i class="bi bi-person-plus-fill"></i>
                                                            <span>Registrarse Ahora</span>
                                                        </button>
                                                    @endif
                                                @else
                                                    <div class="d-grid gap-2">
                                                        <button class="btn btn-info btn-lg py-3">
                                                            <i class="bi bi-award-fill me-2"></i>
                                                            El certificado no esta disponible
                                                        </button>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endif



                        </div>
                    </div>
                    <div class="col-lg-6 order-1 order-lg-2" data-aos="fade-up" data-aos-delay="200">
                        <div id="courseCarousel" class="carousel slide course-carousel" data-bs-ride="carousel">
                            <div class="carousel-inner rounded-3 shadow">

                                {{-- Si el curso tiene un video de YouTube --}}
                                @php
                                    $index = 0;
                                    $youtubeUrl = $cursos->youtube_url;
                                    $videoId = null;

                                    if ($youtubeUrl) {
                                        preg_match(
                                            '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|embed)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/',
                                            $youtubeUrl,
                                            $matches,
                                        );
                                        $videoId = $matches[1] ?? null;
                                    }
                                @endphp

                                @if ($videoId)
                                    <div class="carousel-item active">
                                        <div class="ratio ratio-16x9">
                                            <iframe src="https://www.youtube.com/embed/{{ $videoId }}"
                                                frameborder="0" allowfullscreen></iframe>
                                        </div>
                                    </div>
                                    @php $index++; @endphp
                                @endif

                                @forelse ($cursos->imagenes->where('activo', true)->values() as $i => $media)
                                    <div class="carousel-item {{ !$videoId && $i === 0 ? 'active' : '' }}">
                                        <img src="{{ asset($media->url) }}" class="d-block w-100"
                                            alt="{{ $media->titulo ?? 'Imagen de curso' }}">
                                    </div>
                                @empty
                                    @if (!$videoId)
                                        <div class="carousel-item active">
                                            <img src="{{ asset('assets2/img/congress.jpg') }}" class="d-block w-100"
                                                alt="Imagen por defecto">
                                        </div>
                                    @endif
                                @endforelse
                            </div>

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
                        </div>
                    </div>

                    <!-- Sección de Valoraciones -->




                </div>

            </div>
        </section>

        @if ($esCurso)
            <section class="mt-5" id="temario">
                <div class="container">
                    <div class="row">
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
                                                    data-bs-target="#descripcionTema{{ $i }}"
                                                    aria-expanded="false"
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
                </div>
            </section>
        @else
            <div class="container">
                <div class="row">
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
                                                            {{ $expositor->pivot->cargo ?? 'Cargo no especificado' }}</p>
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
            </div>
        @endif




        @include('Cursos.valoracion')



        {{-- Modales de Registro para Congresos --}}
        @if ($esCongreso && $certificadosDisponibles)
            <!-- Modal de Login para Congresos -->
            <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header bg-primary text-white py-3">
                            <h5 class="modal-title">
                                <i class="bi bi-person-check me-2"></i>Coloca tu correo electrónico si ya estás registrado
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <form action="{{ route('congreso.inscribir') }}" method="POST">
                                @csrf
                                <input type="hidden" name="congreso_id" value="{{ $cursos->id }}">

                                <div class="mb-4">
                                    <label for="loginEmail" class="form-label">Correo Electrónico</label>
                                    <div class="input-group input-group-xl">
                                        <span class="input-group-text bg-light">
                                            <i class="bi bi-envelope"></i>
                                        </span>
                                        <input type="email" class="form-control form-control-xl" id="loginEmail"
                                            name="email" required placeholder="tu@email.com">
                                    </div>
                                    <small class="text-muted">Ingresa el email con el que estás registrado</small>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary py-3">
                                        <i class="bi bi-award me-2"></i> Obtener Certificado
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer justify-content-center bg-light py-3">
                            <small class="text-muted">
                                ¿No tienes cuenta?
                                <a href="#" class="link-registro-desde-login">
                                    Regístrate aquí
                                </a>
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de Registro para Congresos -->
            <div class="modal fade" id="registroCongresoModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header bg-primary text-white py-3">
                            <h2 class="text-light modal-title">
                                <i class="bi bi-person-badge me-2"></i>Registro al Congreso
                            </h2>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body p-4">
                            <!-- Indicador de progreso -->
                            <div class="registration-progress mb-4">
                                <div class="progress-steps">
                                    <div class="step active" data-step="1">
                                        <div class="step-number">1</div>
                                        <div class="step-label">Datos Personales</div>
                                    </div>
                                    <div class="step" data-step="2">
                                        <div class="step-number">2</div>
                                        <div class="step-label">Credenciales</div>
                                    </div>
                                    <div class="step" data-step="3">
                                        <div class="step-number">3</div>
                                        <div class="step-label">Confirmación</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mensajes de error -->
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <ul class="list-unstyled mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li class="mb-2"><i
                                                    class="fas fa-exclamation-circle me-2"></i>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <!-- Form -->
                            <form action="{{ route('registrarseCongreso', encrypt($cursos->id)) }}" method="POST"
                                id="formRegistroCongreso" class="auth-form">
                                @csrf

                                <!-- Paso 1: Datos Personales -->
                                <div class="registration-step active" data-step="1">
                                    <h5 class="mb-4 text-primary fw-bold">
                                        <i class="bi bi-person-circle me-2"></i>Datos Personales
                                    </h5>

                                    <div class="mb-3">
                                        <div class="col-md-12">
                                            <label for="name" class="form-label fw-semibold m-2">Nombre</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="bi bi-person"></i>
                                                </span>
                                                <input type="text" class="form-control input-spaced" id="name"
                                                    name="name" value="{{ old('name') }}" placeholder="Tu nombre"
                                                    required>
                                            </div>
                                        </div>
                                        <br>

                                        <div class="col-md-12">
                                            <label for="lastname1" class="form-label fw-semibold m-2">Apellido
                                                Paterno</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="bi bi-person-badge"></i>
                                                </span>
                                                <input type="text" class="form-control input-spaced" id="lastname1"
                                                    name="lastname1" value="{{ old('lastname1') }}"
                                                    placeholder="Apellido Paterno" required>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="col-md-12">
                                            <label for="lastname2" class="form-label fw-semibold m-2">Apellido
                                                Materno</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="bi bi-person-badge-fill"></i>
                                                </span>
                                                <input type="text" class="form-control input-spaced" id="lastname2"
                                                    name="lastname2" value="{{ old('lastname2') }}"
                                                    placeholder="Apellido Materno">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="country" class="form-label fw-semibold mb-2">País</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="bi bi-globe"></i>
                                            </span>
                                            <select class="form-select input-spaced" id="country" name="country"
                                                required>
                                                <option value="">Selecciona tu país</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="button" class="btn btn-primary btn-next" data-next="2">
                                            Siguiente <i class="bi bi-arrow-right ms-2"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Paso 2: Credenciales -->
                                <div class="registration-step" data-step="2">
                                    <h5 class="mb-4 text-primary fw-bold">
                                        <i class="bi bi-shield-lock me-2"></i>Credenciales de Acceso
                                    </h5>

                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-semibold m-2">Correo
                                            electrónico</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="bi bi-envelope"></i>
                                            </span>
                                            <input type="email" class="form-control input-spaced" id="email"
                                                name="email" value="{{ old('email') }}" placeholder="tu@correo.com"
                                                required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="col-md-12">
                                            <label for="password" class="form-label fw-semibold m-2">Contraseña</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="bi bi-lock"></i>
                                                </span>
                                                <input type="password" class="form-control input-spaced" id="password"
                                                    name="password" placeholder="••••••••" required>
                                                <button class="btn btn-outline-secondary toggle-password-modal"
                                                    type="button" data-target="password">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="col-md-12">
                                            <label for="password_confirmation"
                                                class="form-label fw-semibold mb-2">Confirmar
                                                Contraseña</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="bi bi-lock-fill"></i>
                                                </span>
                                                <input type="password" class="form-control input-spaced"
                                                    id="password_confirmation" name="password_confirmation"
                                                    placeholder="••••••••" required>
                                                <button class="btn btn-outline-secondary toggle-password-modal"
                                                    type="button" data-target="password_confirmation">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="button" class="btn btn-outline-secondary btn-prev" data-prev="1">
                                            <i class="bi bi-arrow-left me-2"></i>Anterior
                                        </button>
                                        <button type="button" class="btn btn-primary btn-next" data-next="3">
                                            Siguiente <i class="bi bi-arrow-right ms-2"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Paso 3: Confirmación -->
                                <div class="registration-step" data-step="3">
                                    <h5 class="mb-4 text-primary fw-bold">
                                        <i class="bi bi-check-circle me-2"></i>Confirmación
                                    </h5>

                                    <div class="alert alert-info mb-4">
                                        <h6 class="alert-heading mb-3">Revisa tus datos:</h6>
                                        <div id="review-data-modal" class="small">
                                            <!-- Se llenará con JavaScript -->
                                        </div>
                                    </div>

                                    <div class="alert alert-success d-flex align-items-center">
                                        <i class="bi bi-shield-check fs-4 me-3"></i>
                                        <small>Tus datos están protegidos y no serán compartidos con terceros. Al
                                            registrarte aceptas los términos del congreso.</small>
                                    </div>

                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="button" class="btn btn-outline-secondary btn-prev" data-prev="2">
                                            <i class="bi bi-arrow-left me-2"></i>Anterior
                                        </button>
                                        <button type="submit" class="btn btn-primary btn-auth">
                                            <i class="bi bi-person-plus-fill me-2"></i>Confirmar Registro
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="modal-footer justify-content-center bg-light py-3 border-0">
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                ¿Ya tienes una cuenta?
                                <a href="{{ route('login.signin') }}"
                                    class="text-decoration-none text-primary fw-semibold">
                                    Inicia sesión aquí
                                </a>
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            @push('scripts')
                <script>
                    // SweetAlert2 para opciones de registro
                    function mostrarOpcionesRegistro() {
                        Swal.fire({
                            title: '<strong>Opciones de Registro</strong>',
                            html: `
                    <p class="mb-4 text-muted">¿Cómo deseas continuar?</p>
                    <div class="d-grid gap-3">
                        <button class="btn btn-primary btn-lg swal-btn-nuevo">
                            <i class="bi bi-person-plus me-2"></i>Nuevo Registro
                        </button>
                        <button class="btn btn-outline-primary btn-lg swal-btn-login">
                            <i class="bi bi-person-check me-2"></i>Ya tengo cuenta
                        </button>
                    </div>
                `,
                            showConfirmButton: false,
                            showCancelButton: true,
                            cancelButtonText: 'Cancelar',
                            customClass: {
                                popup: 'swal-wide',
                                cancelButton: 'btn btn-outline-secondary'
                            },
                            buttonsStyling: false,
                            width: '500px',
                            didOpen: () => {
                                document.querySelector('.swal-btn-nuevo').addEventListener('click', () => {
                                    Swal.close();
                                    const registroModal = new bootstrap.Modal(document.getElementById(
                                        'registroCongresoModal'));
                                    registroModal.show();
                                });

                                document.querySelector('.swal-btn-login').addEventListener('click', () => {
                                    Swal.close();
                                    const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                                    loginModal.show();
                                });
                            }
                        });
                    }

                    document.addEventListener('DOMContentLoaded', function() {
                        // Reemplazar el comportamiento del botón de inscripción
                        const btnInscripcion = document.querySelector('[data-bs-target="#opcionesRegistroModal"]');

                        if (btnInscripcion) {
                            btnInscripcion.removeAttribute('data-bs-toggle');
                            btnInscripcion.removeAttribute('data-bs-target');

                            btnInscripcion.addEventListener('click', function(e) {
                                e.preventDefault();
                                mostrarOpcionesRegistro();
                            });
                        }

                        // Link de registro desde el modal de login
                        const linkRegistro = document.querySelector('.link-registro-desde-login');
                        if (linkRegistro) {
                            linkRegistro.addEventListener('click', function(e) {
                                e.preventDefault();
                                bootstrap.Modal.getInstance(document.getElementById('loginModal')).hide();
                                setTimeout(() => {
                                    const registroModal = new bootstrap.Modal(document.getElementById(
                                        'registroCongresoModal'));
                                    registroModal.show();
                                }, 300);
                            });
                        }
                    });
                </script>
            @endpush
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Función para mostrar el formulario de compra con SweetAlert
        function mostrarFormularioCompra() {
            @auth
            const tienePrecio = {{ $tienePrecio ? 'true' : 'false' }};
            const precio = {{ $cursos->precio ?? 0 }};
            const nombreCurso = `{{ $cursos->nombreCurso }}`;
            const nombreUsuario =
                `{{ auth()->user()->name }} {{ auth()->user()->lastname1 }} {{ auth()->user()->lastname2 }}`;

            // Construir el HTML del formulario
            let htmlContent = `
                    <div style="text-align: left; max-height: 60vh; overflow-y: auto;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person-circle me-2 text-primary"></i>Usuario:
                            </label>
                            <input type="text" class="form-control form-control-lg" value="${nombreUsuario}" readonly>
                        </div>
                        <hr class="my-3">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-mortarboard me-2 text-primary"></i>Curso:
                            </label>
                            <input type="text" class="form-control form-control-lg" value="${nombreCurso} (${tienePrecio ? 'Bs ' + precio.toFixed(2) : 'Gratuito'})" readonly>
                        </div>
                `;

            if (tienePrecio) {
                htmlContent += `
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-currency-dollar me-2 text-success"></i>Monto a Pagar:
                            </label>
                            <div class="input-group input-group-lg">
                                <input type="text" class="form-control" value="Bs ${precio.toFixed(2)}" readonly>
                                <span class="input-group-text bg-success text-white fw-bold">Bs</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-file-earmark-arrow-up me-2 text-info"></i>Comprobante:
                            </label>
                            <input type="file" id="swalComprobante" class="form-control form-control-lg" accept=".pdf,.jpg,.png" required>
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>Formatos aceptados: PDF, JPG, PNG (Max. 2MB)
                            </small>
                        </div>
                    `;
            }

            htmlContent += `
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-card-text me-2 text-primary"></i>Descripción:
                            </label>
                            <textarea id="swalDescripcion" class="form-control" rows="3"
                                placeholder="Ingrese una descripción del pago o inscripción..." required></textarea>
                        </div>
                `;

            if (tienePrecio) {
                const paymentMethodsHtml = document.getElementById('paymentMethodsContainer').innerHTML;
                htmlContent += `
                        <div class="payment-methods-section" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 15px; padding: 1.5rem; margin: 1rem 0; border: 2px solid #e0e0e0;">
                            <h6 class="text-center mb-4 fw-bold">
                                <i class="bi bi-credit-card me-2 text-primary"></i>Métodos de Pago Disponibles
                            </h6>
                            ${paymentMethodsHtml}
                            <small class="text-muted d-block text-center mt-3">
                                Por favor adjunte su comprobante de pago
                            </small>
                        </div>
                    `;
            }

            htmlContent += `</div>`;

            Swal.fire({
                title: `<i class="bi bi-cart-check me-2"></i>${tienePrecio ? 'Completar Compra' : 'Confirmar Inscripción'}`,
                html: htmlContent,
                width: '800px',
                showCancelButton: true,
                confirmButtonText: `<i class="bi bi-check-circle me-2"></i>${tienePrecio ? 'Confirmar Compra' : 'Confirmar Inscripción'}`,
                cancelButtonText: '<i class="bi bi-x-circle me-2"></i>Cancelar',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                reverseButtons: true,
                focusConfirm: false,
                customClass: {
                    popup: 'swal2-course-purchase',
                    htmlContainer: 'swal2-html-container-custom'
                },
                didOpen: () => {
                    // Inicializar carousel si existe
                    const carousel = document.querySelector('#paymentMethodsCarousel');
                    if (carousel) {
                        new bootstrap.Carousel(carousel);
                    }
                },
                preConfirm: () => {
                    const descripcion = document.getElementById('swalDescripcion').value;
                    if (!descripcion.trim()) {
                        Swal.showValidationMessage('La descripción es requerida');
                        return false;
                    }

                    if (tienePrecio) {
                        const comprobante = document.getElementById('swalComprobante').files[0];
                        if (!comprobante) {
                            Swal.showValidationMessage('El comprobante es requerido');
                            return false;
                        }

                        // Validar tamaño del archivo (2MB)
                        if (comprobante.size > 2 * 1024 * 1024) {
                            Swal.showValidationMessage('El archivo no debe exceder 2MB');
                            return false;
                        }

                        // Crear un nuevo DataTransfer para asignar el archivo
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(comprobante);
                        document.getElementById('comprobanteInput').files = dataTransfer.files;
                    }

                    document.getElementById('descripcionInput').value = descripcion;

                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Enviar el formulario
                    document.getElementById('formCompraCurso').submit();
                }
            });
        @else
            // Usuario no autenticado - mostrar modal de login requerido
            Swal.fire({
                icon: 'warning',
                title: 'Acceso Requerido',
                html: `
                        <div class="text-center">
                            <h4 class="my-3">Debes iniciar sesión para continuar</h4>
                            <p>Para realizar una compra necesitas tener una cuenta en nuestro sistema.</p>
                        </div>
                    `,
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión',
                cancelButtonText: '<i class="bi bi-person-plus me-2"></i>Registrarse',
                confirmButtonColor: '#145DA0',
                cancelButtonColor: '#28a745',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('login') }}';
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    window.location.href = '{{ route('signin') }}';
                }
            });
        @endauth
        }

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

                        // Mostrar mensaje de éxito con SweetAlert
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: data.message || 'Calificación actualizada correctamente',
                            confirmButtonColor: '#145DA0',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            // Recargar la página para ver los cambios
                            location.reload();
                        });
                    } else {
                        // Mostrar mensaje de error con SweetAlert
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'No se pudo actualizar la calificación',
                            confirmButtonColor: '#dc3545',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al procesar la solicitud',
                        confirmButtonColor: '#dc3545',
                        confirmButtonText: 'Aceptar'
                    });
                });
        });

        // Función para validar y enviar el formulario de login con SweetAlert
        document.getElementById('formLoginCongreso')?.addEventListener('submit', function(e) {
            e.preventDefault();

            const email = document.getElementById('loginEmail').value.trim();

            // Validación con SweetAlert
            if (!email) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campo requerido',
                    text: 'Por favor ingresa tu correo electrónico',
                    confirmButtonColor: '#ffc107',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }

            // Validar formato de email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Correo inválido',
                    text: 'Por favor ingresa un correo electrónico válido',
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }

            // Mostrar confirmación con SweetAlert
            Swal.fire({
                title: '¿Confirmar inscripción?',
                text: '¿Estás seguro de que deseas inscribirte a este congreso?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-check-circle me-2"></i>Sí, inscribirme',
                cancelButtonText: '<i class="bi bi-x-circle me-2"></i>Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar loading
                    Swal.fire({
                        title: 'Procesando...',
                        text: 'Estamos verificando tu información',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Enviar formulario con fetch
                    const formData = new FormData(this);
                    fetch(this.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Inscripción exitosa!',
                                    text: data.message ||
                                        'Te has inscrito correctamente al congreso',
                                    confirmButtonColor: '#28a745',
                                    confirmButtonText: 'Aceptar'
                                }).then(() => {
                                    // Cerrar modal
                                    bootstrap.Modal.getInstance(document.getElementById(
                                        'loginModal')).hide();
                                    // Opcional: recargar página o redirigir
                                    if (data.redirect) {
                                        window.location.href = data.redirect;
                                    }
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message || 'No se pudo completar la inscripción',
                                    confirmButtonColor: '#dc3545',
                                    confirmButtonText: 'Aceptar'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Ocurrió un error al procesar tu solicitud',
                                confirmButtonColor: '#dc3545',
                                confirmButtonText: 'Aceptar'
                            });
                        });
                }
            });
        });

        // Función para validar y enviar el formulario de registro con SweetAlert
        document.getElementById('formRegistroCongreso')?.addEventListener('submit', function(e) {
            e.preventDefault();

            const name = document.getElementById('name').value.trim();
            const lastname1 = document.getElementById('lastname1').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const password_confirmation = document.getElementById('password_confirmation').value;
            const country = document.getElementById('country').value;

            // Validaciones con SweetAlert
            if (!name || !lastname1 || !email || !password || !password_confirmation || !country) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos incompletos',
                    text: 'Por favor completa todos los campos requeridos',
                    confirmButtonColor: '#ffc107',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }

            // Validar formato de email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Correo inválido',
                    text: 'Por favor ingresa un correo electrónico válido',
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }

            // Validar contraseñas coincidan
            if (password !== password_confirmation) {
                Swal.fire({
                    icon: 'error',
                    title: 'Contraseñas no coinciden',
                    text: 'Las contraseñas ingresadas no coinciden',
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }

            // Validar longitud de contraseña
            if (password.length < 6) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Contraseña débil',
                    text: 'La contraseña debe tener al menos 6 caracteres',
                    confirmButtonColor: '#ffc107',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }

            // Mostrar confirmación con SweetAlert
            Swal.fire({
                title: '¿Confirmar registro?',
                text: '¿Estás seguro de que deseas registrarte a este congreso?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-check-circle me-2"></i>Sí, registrarme',
                cancelButtonText: '<i class="bi bi-x-circle me-2"></i>Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar loading
                    Swal.fire({
                        title: 'Procesando...',
                        text: 'Estamos creando tu cuenta',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Enviar formulario
                    this.submit();
                }
            });
        });

        // Manejar respuesta del servidor para registro
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Registro exitoso!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#28a745',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                // Cerrar modal de registro si está abierto
                const registroModal = bootstrap.Modal.getInstance(document.getElementById('registroCongresoModal'));
                if (registroModal) {
                    registroModal.hide();
                }
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Aceptar'
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Errores en el formulario',
                html: `
                    <div class="text-start">
                        <p>Por favor corrige los siguientes errores:</p>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                `,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Aceptar'
            });
        @endif

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
        }
        else {
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


   





    <script>
        // Fecha de finalización del curso
        const endDate = new Date("{{ $cursos->fecha_fin }}".replace(' ', 'T')).getTime();

        const countdown = setInterval(function() {
            const now = new Date().getTime();
            const distance = endDate - now;

            // Cálculos de tiempo
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Mostrar el resultado
            const timerElement = document.getElementById("countdown-timer");

            if (distance > 0) {
                timerElement.innerHTML = `Tiempo restante: ${days}d ${hours}h ${minutes}m ${seconds}s`;
            } else {
                clearInterval(countdown);
                timerElement.innerHTML = "¡Tiempo agotado!";
                timerElement.className = "badge bg-danger-subtle text-danger px-3 py-2";

                // Deshabilitar todos los botones relevantes
                const buttonsToDisable = [
                    'button[data-bs-target="#opcionesRegistroModal"]',
                    'button[data-bs-target="#registroCongresoModal"]',
                    'button[data-bs-target="#loginModal"]',
                    'form button[type="submit"]'
                ];

                buttonsToDisable.forEach(selector => {
                    document.querySelectorAll(selector).forEach(button => {
                        button.disabled = true;
                        button.classList.remove('btn-primary', 'btn-success');
                        button.classList.add('btn-secondary');
                        button.innerHTML = '<i class="bi bi-lock-fill me-2"></i>Tiempo agotado';
                    });
                });

                // También deshabilitar el botón de certificado si existe
                const certButton = document.querySelector('form[action*="certificados.obtener"] button');
                if (certButton) {
                    certButton.disabled = true;
                    certButton.classList.remove('btn-success');
                    certButton.classList.add('btn-secondary');
                    certButton.innerHTML = '<i class="bi bi-lock-fill me-2"></i>Certificado no disponible';
                }
            }
        }, 1000);
    </script>

    <!-- Script para manejar la visibilidad de contraseñas y cargar países -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            document.querySelectorAll('.toggle-password').forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const icon = this.querySelector('i');

                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('bi-eye');
                        icon.classList.add('bi-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('bi-eye-slash');
                        icon.classList.add('bi-eye');
                    }
                });
            });

            // Cargar países (ejemplo con algunos países)
            const countries = [
                // América del Norte
                "Canada", "Estados Unidos", "México",

                // América Central y el Caribe
                "Belice", "Costa Rica", "Cuba", "El Salvador", "Guatemala", "Honduras", "Nicaragua", "Panamá",
                "República Dominicana",

                // América del Sur
                "Argentina", "Bolivia", "Brasil", "Chile", "Colombia", "Ecuador", "Guyana", "Paraguay", "Perú",
                "Surinam",
                "Uruguay", "Venezuela",

                // Europa
                "Alemania", "Francia", "España", "Italia", "Reino Unido", "Portugal", "Países Bajos", "Bélgica",
                "Suiza",
                "Austria", "Grecia", "Suecia", "Noruega",

                // Asia
                "China", "India", "Japón", "Corea del Sur", "Indonesia", "Filipinas", "Malasia", "Singapur",
                "Tailandia",
                "Vietnam", "Israel", "Turquía", "Arabia Saudita",

                // Oceanía
                "Australia", "Nueva Zelanda"
            ];

            const countrySelect = document.getElementById('country');
            countries.forEach(country => {
                const option = document.createElement('option');
                option.value = country;
                option.textContent = country;
                countrySelect.appendChild(option);
            });
        });
    </script>


<script>
    (function() {
        const modalForm = document.getElementById('formRegistroCongreso');
        if (!modalForm) return;

        let currentStep = 1;
        const totalSteps = 3;

        // Función para cambiar de paso
        function changeStep(targetStep) {
            // Validar el paso actual antes de avanzar
            if (targetStep > currentStep) {
                const currentStepElement = modalForm.querySelector(`.registration-step[data-step="${currentStep}"]`);
                const inputs = currentStepElement.querySelectorAll('input[required], select[required]');

                let isValid = true;
                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        isValid = false;
                        input.classList.add('is-invalid');
                    } else {
                        input.classList.remove('is-invalid');
                    }
                });

                // Validación especial para contraseñas en el paso 2
                if (currentStep === 2) {
                    const password = document.getElementById('password');
                    const confirmPassword = document.getElementById('password_confirmation');

                    if (password.value !== confirmPassword.value) {
                        confirmPassword.classList.add('is-invalid');
                        isValid = false;
                        alert('Las contraseñas no coinciden');
                    }
                }

                if (!isValid) {
                    return;
                }
            }

            // Ocultar paso actual
            modalForm.querySelector(`.registration-step[data-step="${currentStep}"]`).classList.remove('active');
            modalForm.querySelector(`.step[data-step="${currentStep}"]`).classList.remove('active');

            // Marcar como completado si avanzamos
            if (targetStep > currentStep) {
                modalForm.querySelector(`.step[data-step="${currentStep}"]`).classList.add('completed');
            } else {
                modalForm.querySelector(`.step[data-step="${targetStep}"]`).classList.remove('completed');
            }

            currentStep = targetStep;

            // Mostrar nuevo paso
            modalForm.querySelector(`.registration-step[data-step="${currentStep}"]`).classList.add('active');
            modalForm.querySelector(`.step[data-step="${currentStep}"]`).classList.add('active');

            // Actualizar resumen en el paso 3
            if (currentStep === 3) {
                updateReview();
            }

            // Scroll al inicio del modal
            document.querySelector('#registroCongresoModal .modal-body').scrollTop = 0;
        }

        // Actualizar el resumen de datos
        function updateReview() {
            const reviewContainer = document.getElementById('review-data-modal');
            const name = document.getElementById('name').value;
            const lastname1 = document.getElementById('lastname1').value;
            const lastname2 = document.getElementById('lastname2').value;
            const email = document.getElementById('email').value;
            const country = document.getElementById('country');
            const countryText = country.options[country.selectedIndex]?.text || '';

            reviewContainer.innerHTML = `
                <p class="mb-2"><strong>Nombre completo:</strong> ${name} ${lastname1} ${lastname2}</p>
                <p class="mb-2"><strong>Correo electrónico:</strong> ${email}</p>
                <p class="mb-0"><strong>País:</strong> ${countryText}</p>
            `;
        }

        // Event listeners para botones de navegación
        modalForm.querySelectorAll('.btn-next').forEach(btn => {
            btn.addEventListener('click', function() {
                const nextStep = parseInt(this.dataset.next);
                changeStep(nextStep);
            });
        });

        modalForm.querySelectorAll('.btn-prev').forEach(btn => {
            btn.addEventListener('click', function() {
                const prevStep = parseInt(this.dataset.prev);
                changeStep(prevStep);
            });
        });

        // Toggle password visibility
        modalForm.querySelectorAll('.toggle-password-modal').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.dataset.target;
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            });
        });

        // Limpiar validaciones al escribir
        modalForm.querySelectorAll('input, select').forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('is-invalid');
            });
        });

        // Reset modal cuando se cierra
        const modal = document.getElementById('registroCongresoModal');
        modal.addEventListener('hidden.bs.modal', function() {
            currentStep = 1;

            // Resetear pasos
            modalForm.querySelectorAll('.registration-step').forEach(step => step.classList.remove('active'));
            modalForm.querySelectorAll('.step').forEach(step => {
                step.classList.remove('active', 'completed');
            });

            // Activar primer paso
            modalForm.querySelector('.registration-step[data-step="1"]').classList.add('active');
            modalForm.querySelector('.step[data-step="1"]').classList.add('active');

            // Limpiar formulario
            modalForm.reset();
            modalForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        });

        // Prevenir envío con Enter
        modalForm.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
                e.preventDefault();
                const nextButton = modalForm.querySelector(`.registration-step[data-step="${currentStep}"] .btn-next`);
                if (nextButton) {
                    nextButton.click();
                }
            }
        });
    })();
</script>




@endsection




@include('layoutlanding')
