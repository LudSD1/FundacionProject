@section('titulo')
    Restablecer Contraseña
@endsection

@section('hero')
<section id="auth-section" class="auth-wrapper d-flex align-items-center justify-content-center" style="background-image: url('{{ asset('assets/img/bg2.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="auth-overlay"></div>
    <div class="container position-relative">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8 col-xl-7">
                <div class="auth-card">
                    <div class="auth-card-header text-center mb-5">
                        <h2 class="fw-bold mb-3">Cambiar Contraseña</h2>
                        <p class="text-muted fs-5">Ingresa tu nueva contraseña</p>
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

                    <form method="POST" action="{{ route('password.update') }}" class="auth-form">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <!-- Email (oculto) -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold fs-5 mb-3">Correo electrónico</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-envelope fs-5"></i>
                                </span>
                                <input type="email" class="form-control form-control-lg border-start-0 ps-0"
                                       id="email" name="email" value="{{ $email ?? old('email') }}" readonly>
                            </div>
                        </div>

                        <!-- Nueva Contraseña -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold fs-5 mb-3">Nueva Contraseña</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-lock fs-5"></i>
                                </span>
                                <input type="password" class="form-control form-control-lg border-start-0 border-end-0 ps-0"
                                       id="password" name="password" placeholder="••••••••" required minlength="8">
                                <button class="btn btn-outline-secondary border-start-0" type="button" id="togglePassword">
                                    <i class="bi bi-eye fs-5"></i>
                                </button>
                            </div>
                            <div class="form-text mt-2">
                                <i class="bi bi-info-circle me-1"></i>Mínimo 8 caracteres
                            </div>
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div class="mb-4">
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

                        <button type="submit" class="btn btn-primary w-100 btn-auth py-4 fw-semibold fs-5">
                            <i class="bi bi-key-fill me-2"></i>Cambiar Contraseña
                        </button>
                    </form>

                    <div class="text-center mt-5">
                        <p class="text-muted mb-0 fs-6">
                            <a href="{{ route('login') }}" class="text-decoration-none text-primary fw-semibold">
                                <i class="bi bi-arrow-left me-1"></i>Volver al inicio de sesión
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@include('layoutlanding')

<!-- Script para cambiar la visibilidad de la contraseña -->
<script>
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
                const icon = type === 'password' ? 'bi bi-eye fs-5' : 'bi bi-eye-slash fs-5';
                this.innerHTML = `<i class="${icon}"></i>`;
            });
        }

        // Añadir evento de clic al botón de confirmación de contraseña
        if (togglePasswordConfirmation) {
            togglePasswordConfirmation.addEventListener('click', function() {
                const type = passwordConfirmationField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordConfirmationField.setAttribute('type', type);
                const icon = type === 'password' ? 'bi bi-eye fs-5' : 'bi bi-eye-slash fs-5';
                this.innerHTML = `<i class="${icon}"></i>`;
            });
        }

        // Password match validation
        if (passwordConfirmationField) {
            passwordConfirmationField.addEventListener('input', function() {
                if (this.value !== passwordField.value) {
                    this.setCustomValidity('Las contraseñas no coinciden');
                } else {
                    this.setCustomValidity('');
                }
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
