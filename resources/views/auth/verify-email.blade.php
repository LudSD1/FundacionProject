
@extends('layoutlogin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-envelope-open-text"></i>
                    {{ __('Verifica tu dirección de correo electrónico') }}
                </div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            <i class="fas fa-check-circle"></i>
                            {{ __('Se ha enviado un nuevo enlace de verificación a tu dirección de correo electrónico.') }}
                        </div>
                    @endif

                    @if (session('info'))
                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-info-circle"></i>
                            {{ session('info') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="text-center mb-4">
                        <i class="fa fa-envelope fa-3x text-primary mb-3"></i>
                        <h4>¡Casi terminamos!</h4>
                    </div>

                    <p class="text-center">
                        {{ __('Antes de continuar, por favor verifica tu correo electrónico con el enlace que te enviamos a:') }}
                    </p>

                    <p class="text-center">
                        <strong>{{ Auth::user()->email }}</strong>
                    </p>

                    <div class="alert alert-warning">
                        <i class="fas fa-clock"></i>
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
                            <i class="fas fa-sign-out-alt"></i>
                            Cerrar Sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
