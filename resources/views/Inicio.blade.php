@section('titulo')
    Área Personal
@endsection

@section('content')
    @if (!auth()->user()->hasVerifiedEmail())
        <div class="verification-alert alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-start">
                <div class="icon-container me-3">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="alert-title">¡Verifica tu cuenta!</h6>
                    <p class="alert-message">Para acceder a todas las funcionalidades y mantener tu cuenta segura, necesitas
                        verificar tu dirección de correo electrónico.</p>

                    <div style="margin-top: 20px;">
                        <a href="{{ route('email.verification.request') }}" class="cta-button pulse-animation" target="_blank">
                            <span class="email-icon">✉️</span>
                            CONFIRMAR MI CORREO ELECTRÓNICO
                        </a>
                    </div>
                </div>
                <button type="button" class="close-btn" data-bs-dismiss="alert" aria-label="Cerrar">
                    <i class="fas fa-times"></i>
                </button>
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



