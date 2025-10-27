@section('titulo')
    Iniciar Sesión
@endsection

@section('hero')

<section id="auth-section" class="auth-wrapper d-flex align-items-center justify-content-center" style="background-image: url('{{ asset('assets/img/bg2.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="auth-overlay"></div>
    <div class="container position-relative">
        <div class="row justify-content-center">
            <div class="col-12 col-md-11 col-lg-8 col-xl-7">
                <div class="auth-card">
                    <div class="auth-card-header text-center mb-5">
                        <h2 class="fw-bold mb-3">Bienvenido</h2>
                        <p class="text-muted fs-5">Inicia sesión para continuar</p>
                    </div>

                    @guest
                        <!-- Formulario de login -->
                        <form method="POST" action="{{ route('login') }}" class="auth-form">
                            @csrf

                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold fs-5 mb-3">Correo electrónico</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-envelope fs-5"></i>
                                    </span>
                                    <input type="email" name="email" id="email" 
                                           class="form-control form-control-lg border-start-0 ps-0" 
                                           placeholder="tu@correo.com"
                                           required autofocus>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold fs-5 mb-3">Contraseña</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-lock fs-5"></i>
                                    </span>
                                    <input type="password" name="password" id="password" 
                                           class="form-control form-control-lg border-start-0 border-end-0 ps-0" 
                                           placeholder="••••••••"
                                           required>
                                    <button class="btn btn-outline-secondary border-start-0" type="button" id="togglePassword">
                                        <i class="bi bi-eye fs-5"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-5 text-end">
                                <a href="{{ route('password.request') }}" class="text-decoration-none text-primary fw-semibold fs-6">
                                    <i class="bi bi-lock-fill me-1"></i>¿Olvidaste tu contraseña?
                                </a>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 btn-auth py-4 fw-semibold fs-5">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar sesión
                            </button>
                        </form>
                    @else
                        <!-- Usuario autenticado -->
                        <div class="alert alert-info border-0 shadow-sm py-5">
                            <div class="text-center">
                                <i class="bi bi-check-circle-fill fs-1 text-success mb-4" style="font-size: 4rem !important;"></i>
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
