@section('titulo')
    Restablecer Contraseña
@endsection

@section('hero')
    <section id="hero" class="d-flex align-items-center justify-content-center" style="background-image: url('{{ asset('assets/img/bg2.png') }}'); background-size: cover;">
        <div class="login-container">
            <div class="login-card">
                <h3 class="text-center mb-4">Cambiar Contraseña</h3>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email (oculto) -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="{{ $email ?? old('email') }}" readonly>
                    </div>

                    <!-- Nueva Contraseña -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Nueva Contraseña</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password"
                                required minlength="8">
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>Mínimo 8 caracteres
                        </div>
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirmation">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 btn-login">Cambiar Contraseña</button>
                </form>

                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="text-decoration-none">
                        <i class="bi bi-arrow-left me-1"></i>Volver al inicio de sesión
                    </a>
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
    });
</script>
