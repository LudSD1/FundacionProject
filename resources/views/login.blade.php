@section('titulo')
    Iniciar Sesión
@endsection
@section('hero')

<section id="auth-section" class="auth-wrapper d-flex align-items-center justify-content-center" style="background-image: url('{{ asset('assets/img/bg2.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="auth-overlay"></div>
    <div class="container position-relative">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="auth-card">
                    <div class="auth-card-header text-center mb-4">
                        <h2 class="fw-bold mb-2">Bienvenido</h2>
                        <p class="text-muted mb-0">Inicia sesión para continuar</p>
                    </div>

                    @guest
                        <!-- Formulario de login -->
                        <form method="POST" action="{{ route('login') }}" class="auth-form">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold mb-2">Correo electrónico</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input type="email" name="email" id="email"
                                           class="form-control border-start-0 ps-0"
                                           placeholder="tu@correo.com"
                                           required autofocus>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold mb-2">Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input type="password" name="password" id="password"
                                           class="form-control border-start-0 border-end-0 ps-0"
                                           placeholder="••••••••"
                                           required>
                                    <button class="btn btn-outline-secondary border-start-0" type="button" id="togglePassword">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-4 text-end">
                                <a href="{{ route('password.request') }}" class="text-decoration-none text-primary fw-semibold small">
                                    <i class="bi bi-lock-fill me-1"></i>¿Olvidaste tu contraseña?
                                </a>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 btn-auth py-2 fw-semibold">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar sesión
                            </button>
                        </form>
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
