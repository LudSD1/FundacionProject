<header id="header" class="fixed-top header-transparent">
    <div class="header-top py-3">
        <div class="container">

            {{-- ===== DESKTOP ===== --}}
            <div class="d-none d-md-flex align-items-center justify-content-between">

                {{-- Logo --}}
                <div class="logo-section">
                    <a href="{{ route('home') }}" class="logo-aprendo">
                        APRENDO <span class="logo-h-special">H</span>OY
                    </a>
                </div>

                {{-- Buscador --}}
                <div class="search-section">
                    <form action="{{ route('lista.cursos.congresos') }}" method="GET" class="search-form">
                        <div class="input-group">
                            <input type="text" name="search"
                                placeholder="Buscar cursos, eventos..."
                                class="form-control search-input"
                                value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary search-btn">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Navegación + logo fundación --}}
                <div class="nav-section d-flex align-items-center">
                    <nav id="navbar" class="navbar">
                        <ul class="d-flex align-items-center mb-0 me-4">

                            @auth
                                {{-- Usuario autenticado --}}
                                <li>
                                    <a class="getstarted scrollto" href="{{ route('Inicio') }}">
                                        Mi aprendizaje
                                    </a>
                                </li>

                                <li class="nav-item dropdown ms-3">
                                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 text-dark fw-semibold"
                                        href="#"
                                        id="userDropdown"
                                        role="button"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <div class="user-avatar">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                        {{ Auth::user()->name }}
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0"
                                        aria-labelledby="userDropdown">
                                        <li>
                                            <div class="dropdown-header d-flex align-items-center gap-2 py-2">
                                                <div class="user-avatar user-avatar--sm">
                                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ Auth::user()->name }}</div>
                                                    <div class="text-muted small">{{ Auth::user()->email }}</div>
                                                </div>
                                            </div>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center gap-2"
                                                href="{{ route('perfil', Auth::user()->id) }}">
                                                <i class="bi bi-person-circle"></i> Mi perfil
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center gap-2"
                                                href="{{ route('Inicio') }}">
                                                <i class="bi bi-book"></i> Mi aprendizaje
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit"
                                                    class="dropdown-item d-flex align-items-center gap-2 text-danger">
                                                    <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </li>

                            @else
                                {{-- FIX: eliminado </li> huérfano, añadido </li> faltante --}}
                                @if (Route::is('login'))
                                    <li>
                                        <a class="getstarted scrollto" href="{{ route('signin') }}">
                                            Crear cuenta
                                        </a>
                                    </li>
                                @else
                                    <li>
                                        <a class="getstarted scrollto" href="{{ route('login.signin') }}">
                                            Iniciar Sesión
                                        </a>
                                    </li>
                                @endif

                            @endauth
                        </ul>
                    </nav>

                    <div class="logo-fundacion">
                        <img src="{{ asset('assets/img/logof.png') }}" alt="Logo Fundación" class="img-fluid">
                    </div>
                </div>
            </div>

            {{-- ===== MÓVIL ===== --}}
            <div class="d-md-none">
                <div class="d-flex align-items-center justify-content-between">

                    {{-- Logo + hamburguesa --}}
                    <div class="d-flex align-items-center" style="min-width:0; flex:1 1 auto;">
                        <a href="{{ route('home') }}" class="logo-aprendo-mobile">
                            APRENDO <span class="logo-h-special-mobile">H</span>OY
                        </a>
                        <button class="btn btn-link p-2 ms-2"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#mobileMenu"
                            aria-controls="mobileMenu"
                            aria-expanded="false"
                            aria-label="Abrir menú">
                            <i class="bi bi-list" style="font-size:1.5rem; color:#FFA500;"></i>
                        </button>
                    </div>

                    {{-- Logo fundación --}}
                    <div class="logo-fundacion-mobile" style="flex:0 0 auto;">
                        <img src="{{ asset('assets/img/logof.png') }}" alt="Logo Fundación" class="img-fluid">
                    </div>
                </div>

                {{-- Menú colapsable --}}
                <div class="collapse" id="mobileMenu">
                    <div class="mobile-menu-content">

                        {{-- Búsqueda --}}
                        <div class="mobile-search mb-4">
                            <form action="{{ route('lista.cursos.congresos') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" name="search"
                                        placeholder="Buscar cursos, eventos..."
                                        class="form-control mobile-search-input"
                                        value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-primary mobile-search-btn">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- Navegación --}}
                        <nav class="mobile-nav">
                            <ul class="mobile-nav-list">
                                @auth
                                    <li class="mobile-nav-item">
                                        <a class="mobile-nav-link" href="{{ route('Inicio') }}">
                                            <i class="bi bi-house-door me-2"></i>Mi aprendizaje
                                        </a>
                                    </li>
                                    <li class="mobile-nav-item">
                                        <a class="mobile-nav-link" href="{{ route('perfil', Auth::user()->id) }}">
                                            <i class="bi bi-person-circle me-2"></i>Mi perfil
                                        </a>
                                    </li>
                                    <li class="mobile-nav-item">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="mobile-nav-link w-100 border-0 bg-transparent text-danger">
                                                <i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión
                                            </button>
                                        </form>
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
</header>
