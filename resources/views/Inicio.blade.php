@section('titulo')
    Área Personal
@endsection

@section('content')

      @if (!auth()->user()->hasVerifiedEmail())
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-3 fa-lg"></i>
                <div class="flex-grow-1">
                    <h6 class="alert-heading mb-1">¡Verifica tu cuenta!</h6>
                    <p class="mb-2">Para acceder a todas las funcionalidades, necesitas verificar tu dirección de correo electrónico.</p>
                    <button type="button" class="btn btn-warning btn-sm" onclick="enviarVerificacion()">
                        <i class="fas fa-envelope me-2"></i>Enviar Email de Verificación
                    </button>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
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


{{-- Script de verificación de email movido al layout global --}}
