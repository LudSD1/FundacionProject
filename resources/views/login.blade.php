@section('titulo')
    Iniciar Sesión
@endsection

@section('hero')
    <section id="hero" class="d-flex align-items-center justify-content-center" style="background-image: url('{{ asset('assets/img/bg2.png') }}'); background-size: cover;">
        <div class="login-container">
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
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3 text-end">
                            <a href="{{ route('password.request') }}" class="text-decoration-none text-muted small">
                                <i class="bi bi-lock-fill me-1"></i>¿Olvidaste tu contraseña?
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
        </div>
    </section>

    <!-- Script para cambiar la visibilidad de la contraseña -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Obtener elementos
            const togglePassword = document.getElementById('togglePassword');
            const passwordField = document.getElementById('password');

            // Añadir evento de clic al botón
            togglePassword.addEventListener('click', function() {
                // Cambiar el tipo de campo entre 'password' y 'text'
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);

                // Cambiar el icono de ojo dependiendo del tipo
                const icon = type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
                this.innerHTML = `<i class="${icon}"></i>`;
            });
        });
    </script>
@endsection

@include('layoutlanding')
