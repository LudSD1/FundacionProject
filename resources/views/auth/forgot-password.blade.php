@section('titulo')
Restear Contraseña
@endsection



@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg bg-white bg-opacity-10 backdrop-blur-sm">
                <div class="card-body p-4 p-md-5">
                    <!-- Logo y Título -->
                    <div class="text-center mb-4">
                        <img src="{{ asset('assets/img/Acceder.png') }}" alt="Logo" class="mb-3" height="60">
                        <h4 class="text-white fw-bold">Restablecer Contraseña</h4>
                        <p class="text-white">Ingresa tu correo electrónico y te enviaremos las instrucciones</p>
                    </div>

                    <!-- Mensaje de Estado -->
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-check-circle fs-4 me-2"></i>
                                <div>{{ session('status') }}</div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Formulario -->
                    <form method="POST" action="{{ route('password.email') }}" class="needs-validation" novalidate>
                        @csrf

                        <!-- Campo de correo electrónico -->
                        <div class="form-floating mb-4">
                            <div class="input-group">
                                <span class="input-group-text rounded-start-4 bg-white border-end-0">
                                    <i class="fa fa-envelope text-primary"></i>
                                </span>
                                <input type="email"
                                    class="form-control border-start-0 rounded-end-4 ps-0 @error('email') is-invalid @enderror"
                                    id="email"
                                    name="email"
                                    placeholder="Correo Electrónico"
                                    value="{{ old('email') }}"
                                    required
                                    autocomplete="email"
                                    autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg rounded-4 py-3">
                                <i class="fa fa-paper-plane me-2"></i>Enviar Instrucciones
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
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>
@endpush
@include('layoutlogin')
