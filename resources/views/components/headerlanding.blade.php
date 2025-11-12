<header id="header" class="fixed-top header-transparent">
    <div class="header-top py-3">
        <div class="container">
            <!-- Desktop Layout -->
            <div class="d-none d-md-flex align-items-center justify-content-between">
                <!-- Logo APRENDO HOY -->
                <div class="logo-section">
                    <a href="{{ route('home') }}" class="logo-aprendo">
                        APRENDO <span class="logo-h-special">H</span>OY
                    </a>
                </div>

                <!-- Buscador centrado -->
                <div class="search-section">
                    <form action="" method="GET" class="search-form">
                        <div class="input-group">
                            <input type="text" name="q" placeholder="Buscar cursos, eventos..."
                                class="form-control search-input">
                            <button type="submit" class="btn btn-primary search-btn">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Navegación y logo fundación -->
                <div class="nav-section d-flex align-items-center">
                    <nav id="navbar" class="navbar">
                        <ul class="d-flex align-items-center mb-0 me-4">
                            @auth
                                <li><a class="getstarted scrollto" href="{{ route('Inicio') }}">Mi aprendizaje</a></li>
                            @else
                                </li>
                                @if (Route::is('login'))
                                    <li><a class="getstarted scrollto" href="{{ route('signin') }}">Crear cuenta</a></li>
                                @else
                                    <li><a class="getstarted scrollto" href="{{ route('login.signin') }}">Iniciar Sesión</a>
                                @endif
                            @endauth
                        </ul>
                    </nav>

                    <div class="logo-fundacion">
                        <img src="{{ asset('assets/img/logof.png') }}" alt="Logo Fundación" class="img-fluid">
                    </div>
                </div>
            </div>

            <!-- Mobile Layout -->
            <div class="d-md-none">
                <div class="d-flex align-items-center justify-content-between">
                    <!-- Logo APRENDO HOY y botón hamburguesa -->
                    <div class="d-flex align-items-center">
                        <a href="{{ route('home') }}" class="logo-aprendo-mobile">
                            APRENDO <span class="logo-h-special-mobile">H</span>OY
                        </a>
                        <button class="btn btn-link p-2 ms-2" type="button" data-bs-toggle="collapse"
                            data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-expanded="false">
                            <i class="bi bi-list" style="font-size: 1.5rem; color: #FFA500;"></i>
                        </button>
                    </div>

                    <!-- Logo fundación -->
                    <div class="logo-fundacion-mobile">
                        <img src="{{ asset('assets/img/logof.png') }}" alt="Logo Fundación" class="img-fluid">
                    </div>
                </div>

                <!-- Menú móvil colapsable -->
                <div class="collapse" id="mobileMenu">
                    <div class="mobile-menu-content">
                        <!-- Búsqueda móvil -->
                        <div class="mobile-search mb-4">
                            <form action="" method="GET">
                                <div class="input-group">
                                    <input type="text" name="q" placeholder="Buscar cursos, eventos..."
                                        class="form-control mobile-search-input">
                                    <button type="submit" class="btn btn-primary mobile-search-btn">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Navegación móvil -->
                        <nav class="mobile-nav">
                            <ul class="mobile-nav-list">
                                @auth
                                    <li class="mobile-nav-item">
                                        <a class="mobile-nav-link" href="{{ route('Inicio') }}">
                                            <i class="bi bi-house-door me-2"></i>Mi aprendizaje
                                        </a>
                                    </li>
                                @else
                                    <li class="mobile-nav-item">
                                        <a class="mobile-nav-link" href="{{ route('login.signin') }}">
                                            <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                                        </a>
                                    </li>
                                    <li class="mobile-nav-item">
                                        <a class="mobile-nav-link" href="{{ route('signin') }}">
                                            <i class="bi bi-person-plus me-2"></i>Crear cuenta
                                        </a>
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
