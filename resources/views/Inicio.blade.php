@section('titulo')
    Área Personal
@endsection

@section('content')
    @if (!auth()->user()->hasVerifiedEmail())
        <div class="alert alert-warning alert-dismissible fade show verification-alert" role="alert">
            <div class="d-flex align-items-start">
                <div class="icon-container me-3">
                    <i class="fas fa-exclamation-triangle fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="alert-heading mb-1">¡Verifica tu cuenta!</h6>
                    <p class="mb-3">Para acceder a todas las funcionalidades y mantener tu cuenta segura, necesitas
                        verificar tu dirección de correo electrónico.</p>

                    <form action="{{ route('verification.send') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary pulse-animation">
                            <span class="email-icon">✉️</span>
                            CONFIRMAR MI CORREO ELECTRÓNICO
                        </button>
                    </form>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        </div>

        <script>
            // Animación suave al cerrar
            document.addEventListener('DOMContentLoaded', function() {
                const alerts = document.querySelectorAll('.alert-dismissible');
                alerts.forEach(alert => {
                    const closeBtn = alert.querySelector('.btn-close, .close-btn');
                    if (closeBtn) {
                        closeBtn.addEventListener('click', function() {
                            alert.style.transform = 'scale(0.95)';
                            alert.style.opacity = '0';
                            setTimeout(() => {
                                alert.style.display = 'none';
                            }, 300);
                        });
                    }
                });
            });
        </script>
    @endif
    @if (auth()->user()->hasRole('Administrador'))
        @include('partials.dashboard.admin.estadisticas')
    @endif

    @if (auth()->user()->hasRole('Docente') || auth()->user()->hasRole('Estudiante'))
        @include('partials.dashboard.common.cursos')
    @endif
@endsection

@if (auth()->user()->hasRole('Administrador'))
    @section('contentini')
        @include('partials.dashboard.admin.notificaciones-reportes')
    @endsection
@endif

@if (auth()->user()->hasRole('Docente') || auth()->user()->hasRole('Estudiante'))
    @include('FundacionPlantillaUsu.index')
@endif


@if (auth()->user()->hasRole('Administrador'))
    @include('layout')
@endif
