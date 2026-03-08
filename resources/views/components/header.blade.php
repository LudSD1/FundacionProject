<style>
    /* ===== LOGO APRENDO HOY ===== */
.logo-aprendo {
    font-family: "Inter", sans-serif;
    font-size: 2.2rem;
    font-weight: 800;
    color: #ffa500;
    text-decoration: none;
    letter-spacing: -1px;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.logo-aprendo:hover {
    transform: scale(1.05);
    color: #ffa500;
    text-decoration: none;
}

.logo-h-special {
    color: white;
    background: #ffa500;
    padding: 6px 10px;
    border-radius: 6px;
    margin: 0 3px;
    display: inline-block;
    font-weight: 900;
}

</style>

<header id="header" class="sticky-top header-transparent">
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
                {{-- <div class="search-section">
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
                </div> --}}

                {{-- Navegación + logo fundación --}}
                <div class="nav-section d-flex align-items-center">
                    <nav id="navbar" class="navbar">
                        <ul class="d-flex align-items-center mb-0 me-4">

                            @auth


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

                    {{-- Logo --}}
                    <div class="d-flex align-items-center" style="min-width:0; flex:1 1 auto;">
                        <a href="{{ route('home') }}" class="logo-aprendo-mobile">
                            APRENDO <span class="logo-h-special-mobile">H</span>OY
                        </a>

                    </div>

                    {{-- Logo fundación --}}
                    <div class="logo-fundacion-mobile" style="flex:0 0 auto;">
                        <img src="{{ asset('assets/img/logof.png') }}" alt="Logo Fundación" class="img-fluid">
                    </div>
                </div>


            </div>

        </div>
    </div>

</header>
