@section('titulo')
    Regístrate
@endsection



@section('main')
    <section id="auth-section" class="auth-wrapper d-flex align-items-center justify-content-center" style="background-image: url('{{ asset('assets/img/bg2.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="auth-overlay"></div>
        <div class="container position-relative">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 col-xl-9">
                    <div class="auth-card">
                        @guest
                            <div class="auth-card-header text-center mb-5">
                                <h2 class="fw-bold mb-3">Crear Cuenta Nueva</h2>
                                <p class="text-muted fs-5">Completa el formulario para registrarte</p>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <ul class="list-unstyled mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li class="mb-2"><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form action="{{ route('registrarse') }}" method="post" id="registerForm" class="auth-form">
                                @csrf

                                <!-- Email -->
                                <div class="mb-4">
                                    <label for="email" class="form-label fw-semibold fs-5 mb-3">Correo electrónico</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-envelope fs-5"></i>
                                        </span>
                                        <input type="email" class="form-control form-control-lg border-start-0 ps-0"
                                            id="email" name="email" value="{{ old('email') }}"
                                            placeholder="tu@correo.com" required>
                                    </div>
                                </div>

                                <!-- Contraseñas en dos columnas -->
                                <div class="row g-4 mb-4">
                                    <div class="col-md-6">
                                        <label for="password" class="form-label fw-semibold fs-5 mb-3">Contraseña</label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-lock fs-5"></i>
                                            </span>
                                            <input type="password" class="form-control form-control-lg border-start-0 border-end-0 ps-0"
                                                id="password" name="password" placeholder="••••••••" required>
                                            <button class="btn btn-outline-secondary border-start-0" type="button" id="togglePassword">
                                                <i class="bi bi-eye fs-5"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="password_confirmation" class="form-label fw-semibold fs-5 mb-3">Confirmar Contraseña</label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-lock-fill fs-5"></i>
                                            </span>
                                            <input type="password" class="form-control form-control-lg border-start-0 border-end-0 ps-0"
                                                id="password_confirmation" name="password_confirmation"
                                                placeholder="••••••••" required>
                                            <button class="btn btn-outline-secondary border-start-0" type="button" id="togglePasswordConfirmation">
                                                <i class="bi bi-eye fs-5"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- País -->
                                <div class="mb-4">
                                    <label for="country" class="form-label fw-semibold fs-5 mb-3">País</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-globe fs-5"></i>
                                        </span>
                                        <select class="form-select form-select-lg border-start-0 ps-0" id="country" name="country" required>
                                            <option value="">Selecciona tu país</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Datos Personales en tres columnas -->
                                <div class="row g-4 mb-4">
                                    <div class="col-md-4">
                                        <label for="name" class="form-label fw-semibold fs-5 mb-3">Nombre</label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-person fs-5"></i>
                                            </span>
                                            <input type="text" class="form-control form-control-lg border-start-0 ps-0"
                                                id="name" name="name" value="{{ old('name') }}"
                                                placeholder="Tu nombre" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="lastname1" class="form-label fw-semibold fs-5 mb-3">Apellido Paterno</label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-person-badge fs-5"></i>
                                            </span>
                                            <input type="text" class="form-control form-control-lg border-start-0 ps-0"
                                                id="lastname1" name="lastname1" value="{{ old('lastname1') }}"
                                                placeholder="Apellido" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="lastname2" class="form-label fw-semibold fs-5 mb-3">Apellido Materno</label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-person-badge-fill fs-5"></i>
                                            </span>
                                            <input type="text" class="form-control form-control-lg border-start-0 ps-0"
                                                id="lastname2" name="lastname2" value="{{ old('lastname2') }}"
                                                placeholder="Apellido" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- reCAPTCHA -->
                                <div class="d-flex justify-content-center mb-5">
                                    <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 btn-auth py-4 fw-semibold fs-5">
                                    <i class="bi bi-person-plus-fill me-2"></i>Crear Cuenta
                                </button>
                            </form>

                            <div class="text-center mt-5">
                                <p class="text-muted mb-0 fs-6">¿Ya tienes una cuenta?
                                    <a href="{{ route('login.signin') }}" class="text-decoration-none text-primary fw-semibold">
                                        Inicia sesión aquí
                                    </a>
                                </p>
                            </div>
                        @else
                            <!-- Usuario autenticado -->
                            <div class="alert alert-info border-0 shadow-sm py-5">
                                <div class="text-center">
                                    <i class="bi bi-check-circle-fill text-success mb-4" style="font-size: 4rem;"></i>
                                    <h4 class="mb-3">¡Ya has iniciado sesión!</h4>
                                    <p class="mb-4 fs-5">Sesión activa como <strong>{{ Auth::user()->name }}</strong></p>
                                    <a href="{{ route('home') }}" class="btn btn-primary btn-lg px-5 py-3">
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
        const countrySelect = document.querySelector('select');
        if (countrySelect) {
            populateCountries(countrySelect);
        }
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
                const type = passwordConfirmationField.getAttribute('type') === 'password' ? 'text' : 'password';
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
