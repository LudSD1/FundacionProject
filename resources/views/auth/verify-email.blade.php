
@section('titulo')
    Verificar Email
@endsection

@section('hero')
<section id="auth-section" class="auth-wrapper d-flex align-items-center justify-content-center" style="background-image: url('{{ asset('assets/img/bg2.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="auth-overlay"></div>
    <div class="container position-relative">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8 col-xl-7">
                <div class="auth-card">
                    <div class="auth-card-header text-center mb-5">
                        <h2 class="fw-bold mb-3">Verificar Email</h2>
                        <p class="text-muted fs-5">Confirma tu dirección de correo electrónico</p>
                    </div>

                    @if (session('resent'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ __('Se ha enviado un nuevo enlace de verificación a tu dirección de correo electrónico.') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="bi bi-info-circle me-2"></i>
                            {{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="text-center mb-5">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle mb-4" style="width: 120px; height: 120px;">
                            <i class="bi bi-envelope-fill text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="fw-bold text-primary">¡Casi terminamos!</h4>
                    </div>

                    <div class="text-center mb-4">
                        <p class="fs-5 text-muted mb-3">
                            {{ __('Antes de continuar, por favor verifica tu correo electrónico con el enlace que te enviamos a:') }}
                        </p>
                        <div class="bg-light rounded-3 p-3 mb-4">
                            <strong class="fs-5 text-primary">{{ Auth::user()->email }}</strong>
                        </div>
                    </div>

                    <div class="alert alert-warning border-0 shadow-sm">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-clock me-3 fs-4"></i>
                            <div>
                                <strong>Importante:</strong> El enlace de verificación expira en 60 minutos por seguridad.
                            </div>
                        </div>
                    </div>

                    <div class="text-center mb-4">
                        <p class="text-muted mb-3">
                            {{ __('Si no recibiste el correo electrónico, revisa tu carpeta de spam o') }}
                        </p>
                        <form method="POST" action="{{ url('/email/resend-verification-notification') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary btn-lg px-4 py-2">
                                <i class="bi bi-arrow-clockwise me-2"></i>
                                {{ __('Solicitar nuevo enlace') }}
                            </button>
                        </form>
                    </div>

                    <div class="text-center mt-5">
                        <a href="{{ route('logout') }}" class="btn btn-secondary btn-lg px-4 py-2">
                            <i class="bi bi-box-arrow-right me-2"></i>
                            Cerrar Sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@include('layoutlanding')

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert.querySelector('.btn-close')) {
                alert.querySelector('.btn-close').click();
            }
        }, 5000);
    });

    // SweetAlert Notifications
    @if (session('resent'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: 'Se ha enviado un nuevo enlace de verificación a tu dirección de correo electrónico.',
            confirmButtonColor: '#075092'
        });
    @endif

    @if (session('info'))
        Swal.fire({
            icon: 'info',
            title: 'Información',
            text: '{{ session('info') }}',
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
