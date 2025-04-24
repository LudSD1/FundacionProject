@section('hero')
    <section id="hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 d-lg-flex flex-lg-column justify-content-center align-items-stretch pt-5 pt-lg-0 order-2 order-lg-1"
                    data-aos="fade-up">
                    <div>
                        <h3>

                            @if ($cursos->tipo == 'curso')
                                {{ $cursos->nombreCurso }}
                            @elseif ($cursos->tipo == 'congreso')
                                {{ $cursos->nombreCurso }}
                            @endif
                        </h3>
                        <h2>{{ $cursos->descripcionC }}</h2>
                        <h2>📅 {{ \Carbon\Carbon::parse($cursos->fecha_ini)->format('d M Y') }} </h2>


                        {{-- <div class="accordion accordion-course mb-5" id="temarioAccordion">
                            <div class="card rounded-4 shadow border-0 overflow-hidden">
                              <!-- Accordion Header -->
                              <h2 class="accordion-header" id="temarioHeading">
                                <button class="accordion-button fw-bold d-flex align-items-center gap-3 py-4 px-4 bg-light"
                                        type="button" data-bs-toggle="collapse" data-bs-target="#temarioCollapse"
                                        aria-expanded="true" aria-controls="temarioCollapse">
                                  <i class="bi bi-journal-text fs-4 text-primary"></i>
                                  <span class="fs-5">Temario del Curso</span>
                                </button>
                              </h2>

                              <!-- Accordion Content -->
                              <div id="temarioCollapse" class="accordion-collapse collapse show" aria-labelledby="temarioHeading">
                                <div class="accordion-body p-0">
                                  <div class="course-content p-4">
                                    @forelse ($cursos->temas as $tema)
                                      <div class="course-section mb-4 pb-3 border-bottom border-light">
                                        <!-- Section Header -->
                                        <div class="course-section-header d-flex align-items-center mb-3">
                                          <div class="section-number rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3"
                                               style="width: 40px; height: 40px; font-weight: 600;">
                                            {{ $loop->iteration }}
                                          </div>
                                          <h3 class="section-title mb-0 fs-5 fw-semibold">{{ $tema->titulo_tema }}</h3>
                                        </div>

                                        <!-- Section Lessons -->
                                        <div class="course-lessons ms-5 ps-2">
                                          @foreach ($tema->subtemas as $subtema)
                                            <div class="lesson-item d-flex align-items-center p-3 rounded-3 mb-2 hover-bg-light transition-all"
                                                 style="transition: all 0.2s ease;">
                                              <i class="bi bi-play-circle-fill me-3 text-primary fs-5"></i>
                                              <span class="text-secondary">{{ $subtema->titulo_subtema }}</span>
                                            </div>
                                          @endforeach
                                        </div>
                                      </div>
                                    @empty
                                      <div class="text-center py-5">
                                        <i class="bi bi-info-circle text-muted fs-1 mb-3"></i>
                                        <p class="text-muted fs-5">No hay temas disponibles para este curso.</p>
                                        <button class="btn btn-outline-primary mt-2">
                                          <i class="bi bi-plus-circle me-2"></i>Añadir Primer Tema
                                        </button>
                                      </div>
                                    @endforelse
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div> --}}






                        <div class="card shadow-lg rounded-3 border-0 overflow-hidden">
                            @if ($usuarioInscrito)
                            <div class="card-header bg-light py-3 px-4 border-bottom">
                                <h4 class="mb-0 fw-bold">
                                        <i class="bi bi-mortarboard-fill me-2 text-success"></i>Acceso al Curso
                                </h4>
                            </div>
                            <div class="card-body p-4">

                                <a class="btn btn-sm btn-success" href="{{route('Curso', $cursos->id)}}">
                                    <i class="bi bi-door-open me-2"></i> Ir al Curso
                                </a>

                            </div>
                            @else
                            <div class="card-header bg-light py-3 px-4 border-bottom">
                                <h4 class="mb-0 fw-bold">
                                    @if ($cursos->tipo == 'curso')
                                        <i class="bi bi-mortarboard-fill me-2 text-success"></i>Acceso al Curso
                                    @else
                                        <i class="bi bi-calendar-event me-2 text-primary"></i>Registro al Congreso
                                    @endif
                                </h4>
                            </div>
                            <div class="card-body p-4">
                                @if ($cursos->tipo == 'curso')
                                <div class="text-center mb-4">
                                    <span class="badge bg-success-subtle text-success px-3 py-2 mb-2">Oferta Especial</span>
                                    <h3 class="fw-bold text-success mb-1">$ {{ number_format($cursos->precio, 2) }}</h3>
                                    <p class="text-muted">Pago único, acceso de por vida</p>
                                    <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                        <span>Certificado Digital Incluido</span>
                                    </div>
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                        <span>Soporte 24/7</span>
                                    </div>
                                </div>

                                <button class="btn btn-success w-100 py-3 fw-bold fs-5" data-bs-toggle="modal"
                                    data-bs-target="#{{ auth()->check() ? 'compraCursoModal' : 'loginRequiredModal' }}">
                                    <i class="bi bi-credit-card me-2"></i> Comprar Ahora
                                </button>

                                <!-- Modal para usuarios autenticados -->
                                @auth
                                <div class="modal fade" id="compraCursoModal" tabindex="-1" aria-labelledby="compraCursoModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="compraCursoModalLabel">Completar Compra</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('pagarCurso') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">
                                                    <!-- Nombre / Razón Social -->
                                                    <div class="mb-3">
                                                        <label class="form-label">Usuario:</label>
                                                        <input type="text" name="user" value="{{ auth()->user()->name}} {{ auth()->user()->lastname1}} {{ auth()->user()->lastname2}}" class="form-control" readonly>
                                                    </div>


                                                    <hr>

                                                    <!-- Campo oculto con ID del estudiante -->
                                                    <input type="hidden" name="estudiante_id" value="{{ auth()->user()->id }}">

                                                    <!-- Curso (selección única) -->
                                                    <div class="mb-3">
                                                        <label class="form-label">Curso:</label>
                                                        <select name="curso_id" class="form-select">
                                                            <option value="{{ $cursos->id }}" selected>{{ $cursos->nombreCurso }}</option>
                                                        </select>
                                                    </div>

                                                    <div class="row">
                                                        <!-- Monto a Pagar -->
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Monto a Pagar:</label>
                                                            <div class="input-group">
                                                                <input type="number" name="montopagar" class="form-control"
                                                                       value="{{ $cursos->precio }}" min="1" step="any" required readonly>
                                                                <span class="input-group-text">$</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Comprobante:</label>
                                                        <input type="file" name="comprobante" id="">
                                                    </div>

                                                    <!-- Descripción -->
                                                    <div class="mb-3">
                                                        <label class="form-label">Descripción:</label>
                                                        <textarea name="descripcion" class="form-control" rows="4" required></textarea>
                                                    </div>

                                                    <div class=" m-3">
                                                        <img src="{{ asset('assets/img/pago.png')}}" alt="">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-success">Confirmar Compra</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endauth

                                @guest
                                <div class="modal fade" id="loginRequiredModal" tabindex="-1" aria-labelledby="loginRequiredModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="loginRequiredModalLabel">Acceso Requerido</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <i class="bi bi-exclamation-circle text-warning" style="font-size: 3rem;"></i>
                                                <h4 class="my-3">Debes iniciar sesión para continuar</h4>
                                                <p>Para realizar una compra necesitas tener una cuenta en nuestro sistema.</p>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <a href="{{ route('login') }}" class="btn btn-primary me-2">
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
                                    <!-- Información del Congreso -->
                                    <div class="text-center mb-4">

                                        @if (isset($cursos->precio) && $cursos->precio > 0)
                                            <h3 class="fw-bold text-primary mb-1">$ {{ number_format($cursos->precio, 2) }}
                                            </h3>
                                        @else
                                            <h3 class="fw-bold text-primary mb-1">Acceso Gratuito</h3>
                                        @endif

                                        <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
                                            <i class="bi bi-check-circle-fill text-primary"></i>
                                            <span>Material del Congreso</span>
                                        </div>
                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                            <i class="bi bi-check-circle-fill text-primary"></i>
                                            <span>Certificado de Asistencia</span>
                                        </div>
                                    </div>

                                    @if ($cursos->estado == 'Certificado Disponible')
                                        @if (auth()->user())
                                            <div class="text-center mb-3">
                                                <h3>Tiempo Disponible</h3>
                                                <div id="countdown-timer"
                                                    class="badge bg-primary-subtle text-primary px-3 py-2"></div>
                                            </div>


                                            <form action="{{ route('certificados.obtener', $cursos->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="congreso_id" value="{{ $cursos->id }}">

                                                <div class="d-grid gap-2">
                                                    <button type="submit" class="btn btn-success btn-lg py-3">
                                                        <i class="bi bi-award-fill me-2"></i>
                                                        Obtener Mi Certificado Ahora
                                                    </button>
                                                </div>
                                            </form>
                                        @else
                                            <div class="text-center mb-3">
                                                <h3>Tiempo Disponinble</h4>
                                                    <div id="countdown-timer"
                                                        class="badge bg-primary-subtle text-primary px-3 py-2"></div>
                                            </div>
                                            <button
                                                class="btn btn-primary w-100 py-3 fw-bold fs-5 d-flex align-items-center justify-content-center gap-2"
                                                data-bs-toggle="modal" data-bs-target="#opcionesRegistroModal">
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





                        @if ($cursos->estado == 'Certificado Disponible')

                            @if (auth()->user())
                            @else
                                <div class="modal fade" id="opcionesRegistroModal" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">Opciones de Registro</h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center p-4">
                                                <p class="mb-4">¿Cómo deseas continuar?</p>

                                                <button class="btn btn-primary w-100 py-3 mb-3" data-bs-dismiss="modal"
                                                    data-bs-toggle="modal" data-bs-target="#registroCongresoModal">
                                                    <i class="bi bi-person-plus me-2"></i>Nuevo Registro
                                                </button>

                                                <button class="btn btn-outline-primary w-100 py-3" data-bs-dismiss="modal"
                                                    data-bs-toggle="modal" data-bs-target="#loginModal">
                                                    <i class="bi bi-person-check me-2"></i>Ya tengo cuenta
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-primary text-white py-3">
                                                <h5 class="modal-title">
                                                    <i class="bi bi-person-check me-2"></i>Coloca tu correo electronico si
                                                    ya
                                                    estas registrado
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <form action="{{ route('congreso.inscribir') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="congreso_id"
                                                        value="{{ $cursos->id }}">

                                                    <div class="mb-3">
                                                        <label for="loginEmail" class="form-label">Correo
                                                            Electrónico</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-light">
                                                                <i class="bi bi-envelope"></i>
                                                            </span>
                                                            <input type="email" class="form-control" id="loginEmail"
                                                                name="email" required placeholder="tu@email.com">
                                                        </div>
                                                        <small class="text-muted">Ingresa el email con el que estás
                                                            registrado</small>
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
                                                    <a href="#" data-bs-toggle="modal"
                                                        data-bs-target="#registroCongresoModal" data-bs-dismiss="modal">
                                                        Regístrate aquí
                                                    </a>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="registroCongresoModal" tabindex="-1"
                                    aria-labelledby="registroCongresoModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-primary text-white py-3">
                                                <h5 class="modal-title" id="registroCongresoModalLabel">
                                                    <i class="bi bi-person-badge me-2"></i>Registro al Congreso
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <!-- Mensajes de error -->
                                                @if ($errors->any())
                                                    <div class="alert alert-danger">
                                                        <ul class="mb-0">
                                                            @foreach ($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif

                                                <form action="{{ route('registrarseCongreso', $cursos->id) }}"
                                                    method="POST" id="formRegistroCongreso">
                                                    @csrf

                                                    <!-- Campos de nombre y apellidos -->
                                                    <div class="row mb-3">
                                                        <div class="col-md-4 mb-3">
                                                            <label for="name" class="form-label">Nombre</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text bg-light"><i
                                                                        class="bi bi-person"></i></span>
                                                                <input type="text" class="form-control" id="name"
                                                                    name="name" placeholder="Nombre"
                                                                    value="{{ old('name') }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="lastname1" class="form-label">Apellido
                                                                Paterno</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text bg-light"><i
                                                                        class="bi bi-person"></i></span>
                                                                <input type="text" class="form-control" id="lastname1"
                                                                    name="lastname1" placeholder="Apellido Paterno"
                                                                    value="{{ old('lastname1') }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="lastname2" class="form-label">Apellido
                                                                Materno</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text bg-light"><i
                                                                        class="bi bi-person"></i></span>
                                                                <input type="text" class="form-control" id="lastname2"
                                                                    name="lastname2" placeholder="Apellido Materno"
                                                                    value="{{ old('lastname2') }}">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Campo de correo electrónico -->
                                                    <div class="mb-3">
                                                        <label for="email" class="form-label">Correo
                                                            Electrónico</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-light"><i
                                                                    class="bi bi-envelope"></i></span>
                                                            <input type="email" class="form-control" id="email"
                                                                name="email" placeholder="ejemplo@correo.com"
                                                                value="{{ old('email') }}" required>
                                                        </div>
                                                    </div>

                                                    <!-- Campos de contraseña y confirmación -->
                                                    <div class="row mb-3">
                                                        <div class="col-md-6 mb-3">
                                                            <label for="password" class="form-label">Contraseña</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text bg-light"><i
                                                                        class="bi bi-lock"></i></span>
                                                                <input type="password" class="form-control"
                                                                    id="password" name="password"
                                                                    placeholder="Contraseña" required>
                                                                <button class="btn btn-outline-secondary toggle-password"
                                                                    type="button" data-target="password">
                                                                    <i class="bi bi-eye"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label for="password_confirmation"
                                                                class="form-label">Confirmar
                                                                Contraseña</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text bg-light"><i
                                                                        class="bi bi-lock"></i></span>
                                                                <input type="password" class="form-control"
                                                                    id="password_confirmation"
                                                                    name="password_confirmation"
                                                                    placeholder="Confirmar Contraseña" required>
                                                                <button class="btn btn-outline-secondary toggle-password"
                                                                    type="button" data-target="password_confirmation">
                                                                    <i class="bi bi-eye"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <!-- Campo de país -->
                                                    <div class="mb-3">
                                                        <label for="country" class="form-label">País</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-light"><i
                                                                    class="bi bi-globe"></i></span>
                                                            <select class="form-control" id="country" name="country"
                                                                required>
                                                                <option value="">Selecciona tu país</option>
                                                                <!-- Opciones de países se llenarán con JavaScript -->
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">
                                                        <i class="bi bi-check2-circle me-2"></i>Confirmar Registro
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="modal-footer justify-content-center bg-light py-3">
                                                <small class="text-muted">
                                                    <i class="bi bi-info-circle me-1"></i>
                                                    ¿Ya tienes una cuenta? <a href="{{ route('login.signin') }}">Inicia
                                                        sesión
                                                        aquí</a>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else
                        @endif


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
                    </div>
                </div>
                <div class="col-lg-6 order-1 order-lg-2" data-aos="fade-up" data-aos-delay="200">
                    <div id="courseCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner rounded-3 shadow">
                            <!-- Carousel items -->
                            @forelse ($cursos->temas as $index => $tema)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $tema->imagen) }}" class="d-block w-100" alt="{{ $tema->titulo_tema }}">
                            </div>
                        @empty
                            <div class="carousel-item active">
                                <img src="{{ asset('path/to/default-image.jpg') }}" class="d-block w-100" alt="Default Course Image">
                            </div>
                        @endforelse
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#courseCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#courseCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>



            </div>

        </div>
    </section><!-- End Hero -->
@endsection





@include('layoutlanding')
