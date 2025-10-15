@section('titulo')
    Regístrate
@endsection



@section('hero')
    <section id="hero" class="d-flex align-items-center justify-content-center" style="background-image: url('{{ asset('assets/img/bg2.png') }}'); background-size: cover;">
        <div class="register-container">
            <div class="login-card">
                @guest
                            <h3 class="text-center mb-4">Crear Cuenta Nueva</h3>

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <ul class="list-unstyled mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            <form action="{{ route('registrarse') }}" method="post" id="registerForm">
                                @csrf

                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">Correo electrónico</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email') }}" required>
                                </div>

                                <!-- Contraseñas en dos columnas -->
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="password" class="form-label">Contraseña</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password" name="password" required>
                                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password_confirmation"
                                                name="password_confirmation" required>
                                            <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirmation">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- País -->
                                <div class="mb-3">
                                    <label for="country" class="form-label">País</label>
                                    <select class="form-select" id="country" name="country" required>
                                        <option value="">Selecciona tu país</option>
                                    </select>
                                </div>

                                <!-- Datos Personales en tres columnas -->
                                <div class="row g-3 mb-3">
                                    <div class="col-md-4">
                                        <label for="name" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name') }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="lastname1" class="form-label">Apellido Paterno</label>
                                        <input type="text" class="form-control" id="lastname1" name="lastname1"
                                            value="{{ old('lastname1') }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="lastname2" class="form-label">Apellido Materno</label>
                                        <input type="text" class="form-control" id="lastname2" name="lastname2"
                                            value="{{ old('lastname2') }}" required>
                                    </div>
                                </div>

                                <!-- reCAPTCHA -->
                                <div class="d-flex justify-content-center mb-4">
                                    <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 btn-login">Crear Cuenta</button>
                            </form>

                            <div class="text-center mt-4">
                                <p class="text-muted mb-0">¿Ya tienes una cuenta?
                                    <a href="{{ route('login.signin') }}" class="text-decoration-none">
                                        Inicia sesión aquí
                                    </a>
                                </p>
                            </div>
                @else
                    <!-- Si el usuario ya está autenticado -->
                    <div class="alert alert-info text-center">
                        Ya has iniciado sesión como <strong>{{ Auth::user()->name }}</strong>.
                        <a href="{{ route('home') }}" class="btn btn-sm btn-primary mt-2">Ir al inicio</a>
                    </div>
                @endguest
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
