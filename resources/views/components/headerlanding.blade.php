<header id="header" class="fixed-top">

    <div class="header-top">
        <div class="container">

            {{-- ══ DESKTOP ══ --}}
            <div class="d-none d-md-flex align-items-center justify-content-between gap-3">

                {{-- Logo --}}
                <div class="hd-logo-wrap">
                    <a href="{{ route('home') }}" class="hd-logo">
                        APRENDO <span class="hd-logo-h">H</span>OY
                    </a>
                </div>

                {{-- Buscador --}}
                <div class="hd-search-wrap">
                    <form action="{{ route('lista.cursos.congresos') }}" method="GET" class="hd-search-form">
                        <div class="input-group">
                            <input type="text" name="search" placeholder="Buscar cursos, eventos..."
                                class="form-control hd-search-input" value="{{ request('search') }}">
                            <button type="submit" class="hd-search-btn">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Nav + logo fundación --}}
                <div class="hd-nav-wrap d-flex align-items-center gap-3">
                    <nav>
                        <ul class="hd-nav-list">
                            @auth
                                <li>
                                    <a class="hd-btn hd-btn-ghost" href="{{ route('Inicio') }}">
                                        <i class="bi bi-book me-1"></i>Mi aprendizaje
                                    </a>
                                </li>
                                {{-- Dropdown usuario --}}
                                <li class="dropdown">
                                    <a class="hd-user-toggle" href="#" id="userDropdown" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <div class="hd-avatar">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                        <span class="hd-user-name">{{ Auth::user()->name }}</span>
                                        <i class="bi bi-chevron-down hd-chevron"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end hd-dropdown" aria-labelledby="userDropdown">
                                        {{-- Cabecera dropdown --}}
                                        <li>
                                            <div class="hd-dd-header">
                                                <div class="hd-avatar hd-avatar-sm">
                                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="hd-dd-name">{{ Auth::user()->name }}</div>
                                                    <div class="hd-dd-email">{{ Auth::user()->email }}</div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider my-1">
                                        </li>
                                        <li>
                                            <a class="dropdown-item hd-dd-item"
                                                href="{{ route('perfil', Auth::user()->id) }}">
                                                <i class="bi bi-person-circle"></i> Mi perfil
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item hd-dd-item" href="{{ route('Inicio') }}">
                                                <i class="bi bi-book"></i> Mi aprendizaje
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider my-1">
                                        </li>
                                        <li>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="dropdown-item hd-dd-item hd-dd-danger">
                                                    <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            @else
                                {{-- FIX: </li> suelto eliminado, lógica corregida --}}
                                @if (Route::is('login'))
                                    <li>
                                        <a class="hd-btn hd-btn-ghost" href="{{ route('signin') }}">
                                            <i class="bi bi-person-plus me-1"></i>Crear cuenta
                                        </a>
                                    </li>
                                @else
                                    <li>
                                        <a class="hd-btn hd-btn-ghost" href="{{ route('login.signin') }}">
                                            <i class="bi bi-box-arrow-in-right me-1"></i>Iniciar Sesión
                                        </a>
                                    </li>
                                    <li>
                                        <a class="hd-btn hd-btn-primary" href="{{ route('signin') }}">
                                            <i class="bi bi-person-plus me-1"></i>Crear cuenta
                                        </a>
                                    </li>
                                @endif
                            @endauth
                        </ul>
                    </nav>

                    {{-- Logo fundación --}}
                    <a href="{{ route('home') }}" class="hd-logo-fund">
                        <img src="{{ asset('assets/img/logof.png') }}" alt="Logo Fundación">
                    </a>
                </div>
            </div>

            {{-- ══ MOBILE ══ --}}
            <div class="d-md-none">
                <div class="hd-mobile-bar">
                    {{-- Izquierda: logo + hamburguesa --}}
                    <div class="hd-mobile-left">
                        <a href="{{ route('home') }}" class="hd-logo hd-logo-sm">
                            APRENDO <span class="hd-logo-h">H</span>OY
                        </a>
                        <button class="hd-hamburger" type="button" data-bs-toggle="collapse"
                            data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-expanded="false">
                            <i class="bi bi-list"></i>
                        </button>
                    </div>
                    {{-- Derecha: logo fundación --}}
                    <a href="{{ route('home') }}" class="hd-logo-fund hd-logo-fund-sm">
                        <img src="{{ asset('assets/img/logof.png') }}" alt="Logo Fundación">
                    </a>
                </div>

                {{-- Menú colapsable --}}
                <div class="collapse" id="mobileMenu">
                    <div class="hd-mobile-menu">
                        {{-- Búsqueda --}}
                        <form action="{{ route('lista.cursos.congresos') }}" method="GET" class="mb-3">
                            <div class="input-group">
                                <input type="text" name="search" placeholder="Buscar cursos, eventos..."
                                    class="form-control hd-search-input" value="{{ request('search') }}">
                                <button type="submit" class="hd-search-btn">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>

                        {{-- Nav móvil --}}
                        <ul class="hd-mobile-nav">
                            @auth
                                <li>
                                    <a class="hd-mobile-link" href="{{ route('Inicio') }}">
                                        <i class="bi bi-house-door"></i> Mi aprendizaje
                                    </a>
                                </li>
                                <li>
                                    <a class="hd-mobile-link" href="{{ route('perfil', Auth::user()->id) }}">
                                        <i class="bi bi-person-circle"></i> Mi perfil
                                    </a>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="hd-mobile-link hd-mobile-link-danger w-100">
                                            <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                                        </button>
                                    </form>
                                </li>
                            @else
                                <li>
                                    <a class="hd-mobile-link" href="{{ route('login.signin') }}">
                                        <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                                    </a>
                                </li>
                                <li>
                                    <a class="hd-mobile-link hd-mobile-link-primary" href="{{ route('signin') }}">
                                        <i class="bi bi-person-plus"></i> Crear cuenta
                                    </a>
                                </li>
                            @endauth
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</header>


<script>
    (function() {
        const header = document.getElementById('header');
        if (!header) return;
        const onScroll = () => {
            header.classList.toggle('header-scrolled', window.scrollY > 40);
        };
        window.addEventListener('scroll', onScroll, {
            passive: true
        });
        onScroll(); // estado inicial
    })();
</script>
