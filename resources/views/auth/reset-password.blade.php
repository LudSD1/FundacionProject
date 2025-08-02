@extends('layoutlogin')

@section('titulo', 'Restablecer Contraseña')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg bg-white bg-opacity-10 backdrop-blur-sm">
                <div class="card-body p-4 p-md-5">
                    <!-- Logo y Título -->
                    <div class="text-center mb-4">
                        <img src="{{ asset('assets/img/Acceder.png') }}" alt="Logo" class="mb-3" height="60">
                        <h4 class="text-white fw-bold">Cambiar Contraseña</h4>
                        <p class="text-white">Ingresa tu nueva contraseña para continuar</p>
                    </div>

                    <!-- Formulario -->
                    <form method="POST" action="{{ route('password.update') }}" class="needs-validation" novalidate>
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <!-- Email (oculto) -->
                        <div class="form-floating mb-4">
                            <div class="input-group">
                                <span class="input-group-text rounded-start-4 bg-white border-end-0">
                                    <i class="fa fa-envelope text-primary"></i>
                                </span>
                                <input type="email"
                                    class="form-control border-start-0 rounded-end-4 ps-0 bg-light @error('email') is-invalid @enderror"
                                    id="email"
                                    name="email"
                                    value="{{ $email ?? old('email') }}"
                                    readonly>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Nueva Contraseña -->
                        <div class="form-floating mb-4">
                            <div class="input-group">
                                <span class="input-group-text rounded-start-4 bg-white border-end-0">
                                    <i class="fa fa-lock text-primary"></i>
                                </span>
                                <input type="password"
                                    class="form-control border-start-0 @error('password') is-invalid @enderror"
                                    id="password"
                                    name="password"
                                    placeholder="Nueva Contraseña"
                                    required
                                    minlength="8">
                                <button class="btn btn-light border rounded-end-4 toggle-password"
                                    type="button"
                                    data-target="password">
                                    <i class="fa fa-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text text-white">
                                <i class="fa fa-info-circle me-1"></i>Mínimo 8 caracteres
                            </div>
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div class="form-floating mb-4">
                            <div class="input-group">
                                <span class="input-group-text rounded-start-4 bg-white border-end-0">
                                    <i class="fa fa-lock text-primary"></i>
                                </span>
                                <input type="password"
                                    class="form-control border-start-0"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    placeholder="Confirmar Contraseña"
                                    required>
                                <button class="btn btn-light border rounded-end-4 toggle-password"
                                    type="button"
                                    data-target="password_confirmation">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Botón -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg rounded-4 py-3">
                                <i class="fa fa-key me-2"></i>Cambiar Contraseña
                            </button>
                            <a href="{{ route('login') }}" class="btn btn-link text-white text-decoration-none">
                                <i class="fa fa-arrow-left me-2"></i>Volver al inicio de sesión
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password visibility toggle
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');

            const type = input.type === 'password' ? 'text' : 'password';
            input.type = type;

            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    });

    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });

    // Password match validation
    const password = document.getElementById('password');
    const confirmation = document.getElementById('password_confirmation');

    confirmation.addEventListener('input', function() {
        if (this.value !== password.value) {
            this.setCustomValidity('Las contraseñas no coinciden');
        } else {
            this.setCustomValidity('');
        }
    });
});
</script>
@endpush