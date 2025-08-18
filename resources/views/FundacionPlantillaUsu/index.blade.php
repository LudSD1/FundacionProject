@extends('FundacionPlantillaUsu.layout')
@section('nav2')

<!-- Decorative Diagonal Header -->
<div class="position-fixed top-0 start-0 w-100" style="height: 6rem; z-index: 1040;">
    <!-- White Background -->
    <div class="bg-white w-100 h-100 position-absolute top-0 start-0"></div>

    <!-- Blue Diagonal Background -->
    <div class="position-absolute top-0 end-0 h-100" style="background-color: #1a4789; width: 60%; clip-path: polygon(30% 0%, 100% 0, 100% 100%, 0% 100%); z-index: 1;"></div>

    <!-- Content Container -->
    <div class="container position-relative h-100 d-flex justify-content-between align-items-center z-2 px-3">
        <!-- Aprendo Hoy Logo -->
        <img src="{{ asset('./assets/img/Acceder.png') }}" alt="Aprendo Hoy" class="img-fluid logo-header">

        <!-- Fundación Logo -->
        <img src="{{ asset('./resources/img/logof.png') }}" alt="Fundación Educar para la Vida" class="img-fluid logo-header">
    </div>
</div>

<!-- Navbar below the header -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top border-bottom" style="top: 6rem; z-index: 1030;">
    <div class="container">
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <i class="bi bi-list fs-2 text-primary"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('Inicio') }}">
                        <i class="bi bi-house me-2"></i>Inicio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('calendario') }}">
                        <i class="bi bi-calendar me-2"></i>Calendario
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="notificacionesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell me-2"></i>Notificaciones
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="notificacionesDropdown" style="min-width: 300px;">
                        @forelse (auth()->user()->notifications()->latest()->take(4)->get() as $notification)
                            <li class="px-3 py-2 small">
                                <p class="mb-1">{{ $notification->data['message'] }}</p>
                                <span class="text-muted">{{ $notification->created_at->diffForHumans() }}</span>
                            </li>
                        @empty
                            <li class="text-center text-muted py-3 small">No hay notificaciones</li>
                        @endforelse
                    </ul>
                </li>

                @if (auth()->user()->hasRole('Docente'))
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('sumario') }}">
                        <i class="bi bi-bar-chart me-2"></i>Sumario
                    </a>
                </li>
                @endif

                @if (auth()->user()->hasRole('Estudiante'))
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('lista.cursos.congresos') }}">
                        <i class="bi bi-collection me-2"></i>Cursos/Congresos
                    </a>
                </li>
                @endif

                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('pagos') }}">
                        <i class="bi bi-credit-card me-2"></i>Pagos
                    </a>
                </li>

                <!-- User Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="me-2">{{ auth()->user()->name }} {{ auth()->user()->lastname1 }}</span>
                        <i class="bi bi-person-circle fs-5"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li><a class="dropdown-item" href="{{ route('Miperfil') }}">Mi perfil</a></li>
                        <li><a class="dropdown-item" href="#">Notificaciones</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="{{ route('logout') }}">Cerrar Sesión</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>



<!-- Optional CSS Enhancements -->
<style>
    :root {
        --primary-color: #1a4789;
        --secondary-color: #39a6cb;
        --accent-color: #63becf;
        --hover-color: #055c9d;
        --active-color: #2197bd;
        --link-color: #2f89a8;
        --focus-color: #145da0;
        --highlight-color: #2a81c2;
    }

    .navbar-nav .nav-link {
        position: relative;
        transition: all 0.3s ease;
        color: var(--primary-color);
    }

    .navbar-nav .nav-link:hover {
        color: var(--hover-color);
    }

    .navbar-nav .nav-link.active {
        color: var(--active-color);
    }

    .dropdown-menu li:hover {
        background-color: rgba(26, 71, 137, 0.1);
    }

    .dropdown-item:hover {
        background-color: rgba(26, 71, 137, 0.1);
        color: var(--hover-color);
    }

    .navbar {
        border-color: var(--primary-color) !important;
    }

    .bi {
        color: var(--primary-color);
    }

    .nav-link:hover .bi {
        color: var(--hover-color);
    }

    .text-primary {
        color: var(--primary-color) !important;
    }

    .bg-primary {
        background-color: var(--primary-color) !important;
    }

    .logo-header {
        max-height: 3.5rem;
        object-fit: contain;
    }

    @media (max-width: 576px) {
        .logo-header {
            max-height: 1.5rem;
        }
    }
</style>

@endsection




<!--Container-->
@section('container')

    <div class="container pt-5" style="padding-top: 8rem !important;">>

        @yield('content')
    </div>

@endsection


