@section('titulo')
    Regístrate
@endsection

@section('btn-bar')
    @guest
        <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4">Iniciar Sesion</a>
    @endguest
@endsection

@section('content')
    @guest
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-lg bg-white  backdrop-blur-sm">
                        <div class="card-body p-4 p-md-5">
                            <!-- Logo y Título -->
                            <div class="text-center mb-4">
                                <img src="{{ asset('assets/img/Acceder.png') }}" alt="Logo" class="mb-3" height="60">
                                <h4 class="text-primary fw-bold">Crear Cuenta Nueva</h4>
                            </div>

                            <!-- Alertas de Validación -->
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

                            <!-- Formulario -->
                            <form action="{{ route('registrarse') }}" method="post" id="registerForm" class="needs-validation"
                                novalidate>
                                @csrf

                                <!-- Email -->
                                <div class="form-floating mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text rounded-start-4 bg-white border-end-0">
                                            <i class="fa fa-envelope text-primary"></i>
                                        </span>
                                        <input type="email" class="form-control border-start-0 rounded-end-4 ps-0"
                                            id="email" name="email" placeholder="Correo Electrónico"
                                            value="{{ old('email') }}" required>
                                    </div>
                                </div>

                                <!-- Contraseñas -->
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <div class="input-group">
                                                <span class="input-group-text rounded-start-4 bg-white border-end-0">
                                                    <i class="fa fa-lock text-primary"></i>
                                                </span>
                                                <input type="password" class="form-control border-start-0" id="password"
                                                    name="password" placeholder="Contraseña" required>
                                                <button class="btn btn-light border rounded-end-4 toggle-password"
                                                    type="button" data-target="password">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <div class="input-group">
                                                <span class="input-group-text rounded-start-4 bg-white border-end-0">
                                                    <i class="fa fa-lock text-primary"></i>
                                                </span>
                                                <input type="password" class="form-control border-start-0"
                                                    id="password_confirmation" name="password_confirmation"
                                                    placeholder="Confirmar Contraseña" required>
                                                <button class="btn btn-light border rounded-end-4 toggle-password"
                                                    type="button" data-target="password_confirmation">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Datos Personales -->
                                <div class="row g-3 mb-3">
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <div class="input-group">
                                                <span class="input-group-text rounded-start-4 bg-white border-end-0">
                                                    <i class="fa fa-globe text-primary"></i>
                                                </span>
                                                <select class="form-select border-start-0 rounded-end-4" id="country"
                                                    name="country" required>
                                                    <option value="">Selecciona tu país</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <div class="input-group">
                                                <span class="input-group-text rounded-start-4 bg-white border-end-0">
                                                    <i class="fa fa-user text-primary"></i>
                                                </span>
                                                <input type="text" class="form-control border-start-0 rounded-end-4"
                                                    id="name" name="name" placeholder="Nombre"
                                                    value="{{ old('name') }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <div class="input-group">
                                                <span class="input-group-text rounded-start-4 bg-white border-end-0">
                                                    <i class="fa fa-user text-primary"></i>
                                                </span>
                                                <input type="text" class="form-control border-start-0 rounded-end-4"
                                                    id="lastname1" name="lastname1" placeholder="Apellido Paterno"
                                                    value="{{ old('lastname1') }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <div class="input-group">
                                                <span class="input-group-text rounded-start-4 bg-white border-end-0">
                                                    <i class="fa fa-user text-primary"></i>
                                                </span>
                                                <input type="text" class="form-control border-start-0 rounded-end-4"
                                                    id="lastname2" name="lastname2" placeholder="Apellido Materno"
                                                    value="{{ old('lastname2') }}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- reCAPTCHA -->
                                <div class="d-flex justify-content-center mb-4">
                                    <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                                </div>

                                <!-- Botón de Registro -->
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg rounded-4 py-3">
                                        <i class="fa fa-user-plus me-2"></i>Crear Cuenta
                                    </button>
                                </div>

                                <!-- Link a login -->
                                <div class="text-center mt-4">
                                    <p class="text-white mb-0">¿Ya tienes una cuenta?
                                        <a href="{{ route('login.signin') }}" class="text-white fw-bold">
                                            Inicia sesión aquí
                                        </a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="alert alert-info shadow-lg rounded-4 border-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fa-2x me-3"></i>
                            <div>
                                <h5 class="mb-1">Sesión Activa</h5>
                                <p class="mb-0">Ya has iniciado sesión como <strong>{{ Auth::user()->name }}</strong></p>
                            </div>
                            <a href="{{ route('home') }}" class="btn btn-primary rounded-4 ms-auto">
                                <i class="fas fa-home me-2"></i>Ir al inicio
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endguest
@endsection

@include('layoutlogin')

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
    // Función para alternar la visibilidad de la contraseña
    document.addEventListener('DOMContentLoaded', function() {
        // Manejar el evento click para todos los botones de toggle-password
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });

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
