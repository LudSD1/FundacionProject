<header id="header" class="fixed-top header-transparent">
    <div class="header-top py-2">
        <div class="container">
            <!-- Desktop Layout -->
            <div class="d-none d-md-flex align-items-center justify-content-between">
                <div class="logo-container d-flex align-items-center gap-2">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('assets/img/Acceder.png') }}" alt="Acceder" style="height: 35px;">
                    </a>
                    <form action="" method="GET" class="d-flex">
                        <input type="text" name="q" placeholder="Buscar..." class="form-control rounded">
                        <button type="submit" class="btn btn-primary rounded ms-2">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>

                <nav id="navbar" class="navbar">
                    <ul class="d-flex align-items-center mb-0">
                        @auth
                            <li><a class="getstarted scrollto" href="{{ route('Inicio') }}">Mi aprendizaje</a></li>
                        @else
                            <li><a class="getstarted scrollto" href="{{ route('login.signin') }}">Iniciar Sesión</a></li>
                            <li><a class="getstarted scrollto" href="{{ route('signin') }}">Registrarse</a></li>
                        @endauth
                    </ul>

                    <div class="right ms-4">
                        <img src="{{ asset('assets/img/logof.png') }}" alt="Logo" class="img-fluid"
                            style="height: 55px;">
                    </div>
                </nav>
            </div>

            <!-- Mobile Layout -->
            <div class="d-md-none">
                <div class="d-flex align-items-center justify-content-between">
                    <!-- Logo y botón hamburguesa -->
                    <div class="d-flex align-items-center">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('assets/img/Acceder.png') }}" alt="Acceder" style="height: 30px;">
                        </a>
                        <button class="btn btn-link p-2 ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-expanded="false">
                            <i class="bi bi-list" style="font-size: 1.5rem;"></i>
                        </button>
                    </div>

                    <!-- Logo principal -->
                    <div>
                        <img src="{{ asset('assets/img/logof.png') }}" alt="Logo" class="img-fluid" style="height: 45px;">
                    </div>
                </div>

                <!-- Menú móvil colapsable -->
                <div class="collapse" id="mobileMenu">
                    <div class="mt-3">
                        <!-- Búsqueda móvil -->
                        <form action="" method="GET" class="mb-3">
                            <div class="input-group">
                                <input type="text" name="q" placeholder="Buscar..." class="form-control rounded">
                                <button type="submit" class="btn btn-primary rounded">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>

                        <!-- Navegación móvil -->
                        <nav class="navbar-nav">
                            <ul class="navbar-nav">
                                @auth
                                    <li class="nav-item">
                                        <a class="nav-link getstarted scrollto" href="{{ route('Inicio') }}">Mi aprendizaje</a>
                                    </li>
                                @else
                                    <li class="nav-item">
                                        <a class="nav-link getstarted scrollto" href="{{ route('login.signin') }}">Iniciar Sesión</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link getstarted scrollto" href="{{ route('signin') }}">Registrarse</a>
                                    </li>
                                @endauth
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-0">

    <!-- 🔽 CATEGORÍAS PRINCIPALES (CARRUSEL) -->
    {{-- <div class="header-bottom bg-white py-2 shadow-sm position-relative">
        <!-- Código del carrusel comentado -->
    </div> --}}
</header>

<style>
/* Estilos adicionales para mejorar la responsividad */
@media (max-width: 767.98px) {
    .header-top {
        padding: 0.5rem 0;
    }

    .logo-container {
        gap: 0.5rem;
    }

    #mobileMenu {
        border-top: 1px solid #dee2e6;
        margin-top: 0.5rem;
        padding-top: 1rem;
    }

    .navbar-nav .nav-link {
        padding: 0.75rem 0;
        border-bottom: 1px solid #f8f9fa;
    }

    .navbar-nav .nav-link:last-child {
        border-bottom: none;
    }
}

/* Asegurar que el header no se superponga con el contenido */
body {
    padding-top: 80px;
}

@media (max-width: 767.98px) {
    body {
        padding-top: 70px;
    }
}
</style>
