@section('titulo')
    Iniciar Sesión
@endsection

@section('btn-bar')
    @guest
        <a href="{{ route('signin') }}" class="btn btn-primary rounded-pill px-4">Crear cuenta</a>
    @endguest
@endsection

@section('content')
<div class="login-card">
    <h3 class="text-center mb-4">Iniciar sesión</h3>
    @guest
    <!-- Formulario de login (solo si NO ha iniciado sesión) -->
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" name="email" id="email" class="form-control" required autofocus>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <div class="input-group">
                <input type="password" name="password" id="password" class="form-control" required>
                <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility(this)">
                    <i class="fa fa-eye"></i>
                </button>
            </div>
        </div>

        <div class="mb-3 text-end">
            <a href="{{ route('password.request') }}" class="text-decoration-none text-muted small">
                <i class="fa fa-lock me-1"></i>¿Olvidaste tu contraseña?
            </a>
        </div>


        <button type="submit" class="btn btn-primary w-100 btn-login">Ingresar</button>
    </form>
@else
    <!-- Si el usuario ya está autenticado -->
    <div class="alert alert-info text-center">
        Ya has iniciado sesión como <strong>{{ Auth::user()->name }}</strong>.
        <a href="{{ route('home') }}" class="btn btn-sm btn-primary mt-2">Ir al inicio</a>
    </div>
@endguest

</div>

<!-- Script para cambiar la visibilidad de la contraseña -->
<script>
    // Obtener elementos
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('password');

    // Añadir evento de clic al botón
    togglePassword.addEventListener('click', function () {
        // Cambiar el tipo de campo entre 'password' y 'text'
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;

        // Cambiar el icono de ojo dependiendo del tipo
        const icon = type === 'password' ? 'fa-eye' : 'fa-eye-slash';
        togglePassword.innerHTML = `<i class="fa ${icon}"></i>`;
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Check for validation errors
    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Error de validación',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            confirmButtonColor: '#3085d6',
        });
    @endif

    // Check for success message
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#3085d6',
        });
    @endif

    // Check for error message
    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            confirmButtonColor: '#3085d6',
        });
    @endif

    // Form submission handling
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Iniciando sesión',
            text: 'Por favor espere...',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        this.submit();
    });

    // Password visibility toggle function
    function togglePasswordVisibility(button) {
        const input = document.getElementById('password');
        const type = input.type === 'password' ? 'text' : 'password';
        input.type = type;

        const icon = button.querySelector('i');
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    }
</script>




@endsection

@include('layoutlogin')
