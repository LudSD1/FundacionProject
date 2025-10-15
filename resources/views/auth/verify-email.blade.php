
@section('titulo')
    Verificar Email
@endsection

@section('hero')
    <section id="hero" class="d-flex align-items-center justify-content-center" style="background-image: url('{{ asset('assets/img/bg2.png') }}'); background-size: cover;">
        <div class="login-container">
            <div class="login-card">
                <h3 class="text-center mb-4">Verificar Email</h3>

                @if (session('resent'))
                    <div class="alert alert-success" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ __('Se ha enviado un nuevo enlace de verificación a tu dirección de correo electrónico.') }}
                    </div>
                @endif

                @if (session('info'))
                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-info-circle me-2"></i>
                        {{ session('info') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <div class="text-center mb-4">
                    <i class="bi bi-envelope-fill text-primary" style="font-size: 3rem;"></i>
                    <h4 class="mt-3">¡Casi terminamos!</h4>
                </div>

                <p class="text-center">
                    {{ __('Antes de continuar, por favor verifica tu correo electrónico con el enlace que te enviamos a:') }}
                </p>

                <p class="text-center">
                    <strong>{{ Auth::user()->email }}</strong>
                </p>

                <div class="alert alert-warning">
                    <i class="bi bi-clock me-2"></i>
                    <strong>Importante:</strong> El enlace de verificación expira en 60 minutos por seguridad.
                </div>

                <p class="text-center">
                    {{ __('Si no recibiste el correo electrónico, revisa tu carpeta de spam o') }}
                    <form method="POST" action="{{ url('/email/resend-verification-notification') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">
                            {{ __('haz clic aquí para solicitar otro') }}
                        </button>.
                    </form>
                </p>

                <div class="text-center mt-4">
                    <a href="{{ route('logout') }}" class="btn btn-secondary">
                        <i class="bi bi-box-arrow-right me-2"></i>
                        Cerrar Sesión
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@include('layoutlanding')
