@section('titulo')
    Regístrate
@endsection



@section('main')
   

    <section id="auth-section" class="auth-wrapper d-flex align-items-center justify-content-center"
        style="background-image: url('{{ asset('assets/img/bg2.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="auth-overlay"></div>
        <div class="container position-relative">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-9 col-xl-8">
                    <div class="auth-card">
                        @guest
                            <div class="auth-card-header text-center mb-4">
                                <h2 class="fw-bold mb-2">Crear Cuenta Nueva</h2>
                                <p class="text-muted mb-0">Completa el formulario para registrarte</p>
                            </div>

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

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <ul class="list-unstyled mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li class="mb-2"><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}
                                            </li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            <form action="{{ route('registrarse') }}" method="post" id="registerForm" class="auth-form">
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
                                                    name="name" value="{{ old('name') }}" placeholder="Tu nombre" required>
                                            </div>
                                        </div>
                                        <br>

                                        <div class="col-md-12">
                                            <label for="lastname1" class="form-label fw-semibold m-2">Apellido Paterno</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="bi bi-person-badge"></i>
                                                </span>
                                                <input type="text" class="form-control input-spaced" id="lastname1"
                                                    name="lastname1" value="{{ old('lastname1') }}" placeholder="Apellido Paterno"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="lastname2" class="form-label fw-semibold m-2">Apellido Materno</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="bi bi-person-badge-fill"></i>
                                                </span>
                                                <input type="text" class="form-control input-spaced" id="lastname2"
                                                    name="lastname2" value="{{ old('lastname2') }}" placeholder="Apellido Materno"
                                                    required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="country" class="form-label fw-semibold mb-2">País</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="bi bi-globe"></i>
                                            </span>
                                            <select class="form-select input-spaced" id="country" name="country" required>
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
                                        <label for="email" class="form-label fw-semibold m-2">Correo electrónico</label>
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
                                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="password_confirmation" class="form-label fw-semibold mb-2">Confirmar
                                                Contraseña</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="bi bi-lock-fill"></i>
                                                </span>
                                                <input type="password" class="form-control input-spaced"
                                                    id="password_confirmation" name="password_confirmation"
                                                    placeholder="••••••••" required>
                                                <button class="btn btn-outline-secondary" type="button"
                                                    id="togglePasswordConfirmation">
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
                                        <div id="review-data" class="small">
                                            <!-- Se llenará con JavaScript -->
                                        </div>
                                    </div>

                                    <!-- reCAPTCHA -->
                                    <div class="d-flex justify-content-center mb-4">
                                        <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                                    </div>

                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="button" class="btn btn-outline-secondary btn-prev" data-prev="2">
                                            <i class="bi bi-arrow-left me-2"></i>Anterior
                                        </button>
                                        <button type="submit" class="btn btn-primary btn-auth">
                                            <i class="bi bi-person-plus-fill me-2"></i>Crear Cuenta
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <div class="text-center mt-5">
                                <p class="text-muted mb-0 fs-6">¿Ya tienes una cuenta?
                                    <a href="{{ route('login.signin') }}"
                                        class="text-decoration-none text-primary fw-semibold">
                                        Inicia sesión aquí
                                    </a>
                                </p>
                            </div>
                        @else
                            <!-- Usuario autenticado -->
                            <div class="alert alert-info border-0 shadow-sm py-4">
                                <div class="text-center">
                                    <i class="bi bi-check-circle-fill text-success mb-3" style="font-size: 3rem;"></i>
                                    <h5 class="mb-2">¡Ya has iniciado sesión!</h5>
                                    <p class="mb-3">Sesión activa como <strong>{{ Auth::user()->name }}</strong></p>
                                    <a href="{{ route('home') }}" class="btn btn-primary px-4 py-2">
                                        <i class="bi bi-house-door me-2"></i>Ir al inicio
                                    </a>
                                </div>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@include('layoutlanding')

<!-- Core JS Files -->
@if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "{{ $errors->first('email') }}",
            timer: 5000,
            showConfirmButton: false,
        });
    </script>
@endif


<script>
    // Array of countries
    const countries = [
        "Canada", "Estados Unidos", "México", "Belice", "Costa Rica", "Cuba", "El Salvador", "Guatemala",
        "Honduras", "Nicaragua", "Panamá",
        "República Dominicana",

        "Argentina", "Bolivia", "Brasil", "Chile", "Colombia", "Ecuador", "Guyana", "Paraguay", "Perú", "Surinam",
        "Uruguay", "Venezuela",

        "Alemania", "Francia", "España", "Italia", "Reino Unido", "Portugal", "Países Bajos", "Bélgica", "Suiza",
        "Austria", "Grecia", "Suecia", "Noruega",

        "China", "India", "Japón", "Corea del Sur", "Indonesia", "Filipinas", "Malasia", "Singapur", "Tailandia",
        "Vietnam", "Israel", "Turquía", "Arabia Saudita",

        "Australia", "Nueva Zelanda"
    ];

    // Function to populate select dropdown
    function populateCountries(selectElement) {
        // Clear existing options except the first (default)
        while (selectElement.options.length > 1) {
            selectElement.remove(1);
        }

        // Sort countries alphabetically
        countries.sort();

        // Add countries to dropdown
        countries.forEach(country => {
            const option = new Option(country, country);
            selectElement.add(option);
        });
    }

    // Event listener to populate dropdown when page loads
    document.addEventListener('DOMContentLoaded', () => {
        const countrySelect = document.getElementById('country');
        if (countrySelect) {
            populateCountries(countrySelect);
        }

        // Sistema de navegación por pasos
        const steps = document.querySelectorAll('.registration-step');
        const progressSteps = document.querySelectorAll('.progress-steps .step');

        // Función para mostrar un paso específico
        function showStep(stepNumber) {
            steps.forEach((step, index) => {
                if (index + 1 === stepNumber) {
                    step.classList.add('active');
                    step.style.display = 'block';
                } else {
                    step.classList.remove('active');
                    step.style.display = 'none';
                }
            });

            // Actualizar indicador de progreso
            progressSteps.forEach((step, index) => {
                if (index + 1 <= stepNumber) {
                    step.classList.add('active');
                } else {
                    step.classList.remove('active');
                }
            });

            // Actualizar datos de revisión en el paso 3
            if (stepNumber === 3) {
                updateReviewData();
            }
        }

        // Función para actualizar datos de revisión
        function updateReviewData() {
            const name = document.getElementById('name').value || 'No especificado';
            const lastname1 = document.getElementById('lastname1').value || 'No especificado';
            const lastname2 = document.getElementById('lastname2').value || 'No especificado';
            const country = document.getElementById('country').value || 'No especificado';
            const email = document.getElementById('email').value || 'No especificado';

            document.getElementById('review-data').innerHTML = `
                <div class="row">
                    <div class="col-6 mb-2"><strong>Nombre:</strong></div>
                    <div class="col-6 mb-2">${name}</div>
                    <div class="col-6 mb-2"><strong>Apellido Paterno:</strong></div>
                    <div class="col-6 mb-2">${lastname1}</div>
                    <div class="col-6 mb-2"><strong>Apellido Materno:</strong></div>
                    <div class="col-6 mb-2">${lastname2}</div>
                    <div class="col-6 mb-2"><strong>País:</strong></div>
                    <div class="col-6 mb-2">${country}</div>
                    <div class="col-6 mb-2"><strong>Email:</strong></div>
                    <div class="col-6 mb-2">${email}</div>
                </div>
            `;
        }

        // Validar campos antes de avanzar
        function validateStep(stepNumber) {
            if (stepNumber === 1) {
                const name = document.getElementById('name').value.trim();
                const lastname1 = document.getElementById('lastname1').value.trim();
                const lastname2 = document.getElementById('lastname2').value.trim();
                const country = document.getElementById('country').value;

                if (!name || !lastname1 || !lastname2 || !country) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campos incompletos',
                        text: 'Por favor completa todos los campos de datos personales',
                        confirmButtonColor: '#145DA0'
                    });
                    return false;
                }
                return true;
            } else if (stepNumber === 2) {
                const email = document.getElementById('email').value.trim();
                const password = document.getElementById('password').value;
                const passwordConfirmation = document.getElementById('password_confirmation').value;

                if (!email || !password || !passwordConfirmation) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campos incompletos',
                        text: 'Por favor completa todos los campos de credenciales',
                        confirmButtonColor: '#145DA0'
                    });
                    return false;
                }

                if (password !== passwordConfirmation) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Contraseñas no coinciden',
                        text: 'Las contraseñas deben ser iguales',
                        confirmButtonColor: '#dc3545'
                    });
                    return false;
                }

                if (password.length < 8) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Contraseña muy corta',
                        text: 'La contraseña debe tener al menos 8 caracteres',
                        confirmButtonColor: '#145DA0'
                    });
                    return false;
                }
                return true;
            }
            return true;
        }

        // Botones siguiente
        document.querySelectorAll('.btn-next').forEach(btn => {
            btn.addEventListener('click', function() {
                const currentStep = parseInt(this.closest('.registration-step').dataset.step);
                const nextStep = parseInt(this.dataset.next);

                if (validateStep(currentStep)) {
                    showStep(nextStep);
                }
            });
        });

        // Botones anterior
        document.querySelectorAll('.btn-prev').forEach(btn => {
            btn.addEventListener('click', function() {
                const prevStep = parseInt(this.dataset.prev);
                showStep(prevStep);
            });
        });

        // Inicializar: mostrar solo el primer paso
        showStep(1);
    });
</script>




<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Script para cambiar la visibilidad de la contraseña
    document.addEventListener('DOMContentLoaded', function() {
        // Obtener elementos
        const togglePassword = document.getElementById('togglePassword');
        const togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');
        const passwordField = document.getElementById('password');
        const passwordConfirmationField = document.getElementById('password_confirmation');

        // Añadir evento de clic al botón de contraseña
        if (togglePassword) {
            togglePassword.addEventListener('click', function() {
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                const icon = type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
                this.innerHTML = `<i class="${icon}"></i>`;
            });
        }

        // Añadir evento de clic al botón de confirmación de contraseña
        if (togglePasswordConfirmation) {
            togglePasswordConfirmation.addEventListener('click', function() {
                const type = passwordConfirmationField.getAttribute('type') === 'password' ? 'text' :
                    'password';
                passwordConfirmationField.setAttribute('type', type);
                const icon = type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
                this.innerHTML = `<i class="${icon}"></i>`;
            });
        }

        // SweetAlert Notifications
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#075092'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                confirmButtonColor: '#dc3545'
            });
        @endif


    });
</script>
