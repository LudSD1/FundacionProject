<header id="header" class="fixed-top">
    <div class="hd-inner">
        <div class="container">

            {{-- ── DESKTOP ──────────────────────────────────── --}}
            <div class="d-none d-md-flex align-items-center gap-3">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="hd-logo">
                    APRENDO <span class="hd-logo-h">H</span>OY
                </a>

                {{-- Buscador --}}
                <div class="hd-search-wrap">
                    <form action="{{ route('lista.cursos.congresos') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" placeholder="Buscar cursos, eventos..."
                                class="form-control hd-search-input" value="{{ request('search') }}">
                            <button type="submit" class="hd-search-btn">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Nav + Logo fundación --}}
                <div class="d-flex align-items-center gap-3 ms-auto">
                    <nav>
                        <ul class="hd-nav-list">
                            @auth
                                <li>
                                    <a class="hd-btn hd-btn-ghost" href="{{ route('Inicio') }}">
                                        <i class="bi bi-book me-1"></i>Mi aprendizaje
                                    </a>
                                </li>
                                <li class="dropdown">
                                    <a class="hd-user-toggle" href="#" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <div class="hd-avatar">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                        <span class="hd-user-name">{{ Auth::user()->name }}</span>
                                        <i class="bi bi-chevron-down hd-chevron"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end hd-dropdown">
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

                    <a href="{{ route('home') }}" class="hd-logo-fund">
                        <img src="{{ asset('assets/img/logof.png') }}" alt="Logo Fundación">
                    </a>
                </div>
            </div>

            {{-- ── MOBILE ───────────────────────────────────── --}}
            <div class="d-md-none">
                <div class="hd-mobile-bar">
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('home') }}" class="hd-logo hd-logo-sm">
                            APRENDO <span class="hd-logo-h">H</span>OY
                        </a>
                        <button class="hd-hamburger" type="button" data-bs-toggle="collapse"
                            data-bs-target="#mobileMenu" aria-expanded="false">
                            <i class="bi bi-list"></i>
                        </button>
                    </div>
                    <a href="{{ route('home') }}" class="hd-logo-fund hd-logo-fund-sm">
                        <img src="{{ asset('assets/img/logof.png') }}" alt="Logo Fundación">
                    </a>
                </div>

                <div class="collapse" id="mobileMenu">
                    <div class="hd-mobile-menu">
                        <form action="{{ route('lista.cursos.congresos') }}" method="GET" class="mb-3">
                            <div class="input-group">
                                <input type="text" name="search" placeholder="Buscar cursos, eventos..."
                                    class="form-control hd-search-input" value="{{ request('search') }}">
                                <button type="submit" class="hd-search-btn">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
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
                                        <button type="submit" class="hd-mobile-link hd-mobile-danger w-100">
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
                                    <a class="hd-mobile-link hd-mobile-primary" href="{{ route('signin') }}">
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


@auth
    <nav id="authNavbar" class="auth-navbar">
        <div class="auth-navbar-container">

            <button class="auth-navbar-toggler" id="authNavToggler" type="button" aria-expanded="false">
                <i class="bi bi-list auth-navbar-toggler-icon"></i>
            </button>

            <div class="auth-navbar-content" id="authNavContent">
                <ul class="auth-navbar-menu">

                    <li class="auth-nav-item" data-tooltip="Ir al inicio">
                        <a class="auth-nav-link {{ request()->routeIs('Inicio') ? 'active' : '' }}"
                            href="{{ route('Inicio') }}">
                            <i class="bi bi-house auth-nav-icon"></i><span>Inicio</span>
                        </a>
                    </li>

                    <li class="auth-nav-item" data-tooltip="Ver calendario">
                        <a class="auth-nav-link {{ request()->routeIs('calendario') ? 'active' : '' }}"
                            href="{{ route('calendario') }}">
                            <i class="bi bi-calendar-event auth-nav-icon"></i><span>Calendario</span>
                        </a>
                    </li>

                    <li class="auth-nav-item auth-dropdown" id="notifDropdown">
                        <button class="auth-nav-link auth-dropdown-toggle" type="button" data-an-toggle="notifDropdown">
                            <i class="bi bi-bell auth-nav-icon"></i>
                            <span>Notificaciones</span>
                            @if (auth()->user()->unreadNotifications->count() > 0)
                                <span class="auth-notification-badge">
                                    {{ auth()->user()->unreadNotifications->count() > 9 ? '9+' : auth()->user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </button>
                        <div class="auth-dropdown-menu">
                            <div class="auth-dropdown-header">
                                <i class="bi bi-bell-fill me-2"></i>Notificaciones Recientes
                            </div>
                            @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notif)
                                <div class="auth-notification-item {{ $notif->read_at ? '' : 'unread' }}">
                                    <p class="auth-notification-message">
                                        {{ $notif->data['message'] ?? 'Sin mensaje' }}
                                    </p>
                                    <span class="auth-notification-time">
                                        <i class="bi bi-clock me-1"></i>{{ $notif->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            @empty
                                <div class="auth-notification-empty">
                                    <i class="bi bi-inbox"></i>
                                    <span>No hay notificaciones</span>
                                </div>
                            @endforelse
                            @if (auth()->user()->notifications->count() > 0)
                                <div class="auth-dropdown-footer">
                                    <a href="#" class="auth-view-all-link">
                                        Ver todas <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </li>

                    @if (auth()->user()->hasRole('Docente'))
                        <li class="auth-nav-item" data-tooltip="Ver estadísticas">
                            <a class="auth-nav-link {{ request()->routeIs('sumario') ? 'active' : '' }}"
                                href="{{ route('sumario') }}">
                                <i class="bi bi-bar-chart-line auth-nav-icon"></i><span>Sumario</span>
                            </a>
                        </li>
                    @endif

                    @if (auth()->user()->hasRole('Estudiante'))
                        <li class="auth-nav-item" data-tooltip="Explorar cursos">
                            <a class="auth-nav-link {{ request()->routeIs('lista.cursos.congresos') ? 'active' : '' }}"
                                href="{{ route('lista.cursos.congresos') }}">
                                <i class="bi bi-collection auth-nav-icon"></i><span>Cursos/Congresos</span>
                            </a>
                        </li>
                    @endif

                    <li class="auth-nav-item" data-tooltip="Gestionar pagos">
                        <a class="auth-nav-link {{ request()->routeIs('pagos') ? 'active' : '' }}"
                            href="{{ route('pagos') }}">
                            <i class="bi bi-credit-card-2-front auth-nav-icon"></i><span>Pagos</span>
                        </a>
                    </li>

                    {{-- Dropdown usuario --}}
                    <li class="auth-nav-item auth-dropdown" id="userDropdownNav">
                        <button class="auth-user-toggle auth-dropdown-toggle" type="button"
                            data-an-toggle="userDropdownNav">
                            <span class="auth-user-name">
                                {{ auth()->user()->name }} {{ auth()->user()->lastname1 }}
                            </span>
                            <div class="auth-user-avatar-wrapper">
                                <div class="auth-user-avatar-letter">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span class="auth-user-status"></span>
                            </div>
                        </button>
                        <div class="auth-user-menu">
                            <a class="auth-user-menu-item" href="{{ route('Miperfil') }}">
                                <i class="bi bi-person"></i><span>Mi perfil</span>
                            </a>
                            <a class="auth-user-menu-item" href="#">
                                <i class="bi bi-bell"></i><span>Notificaciones</span>
                            </a>
                            <hr class="auth-user-divider">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="auth-user-menu-item logout w-100">
                                    <i class="bi bi-box-arrow-right"></i><span>Cerrar Sesión</span>
                                </button>
                            </form>
                        </div>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
@endauth


<script>
    (function() {
        /* ── 1. Calcular top del auth-navbar según altura del #header ── */
        const authNav = document.getElementById('authNavbar');
        const landingHd = document.getElementById('header'); // header de la landing

        function setAuthNavTop() {
            if (!authNav) return;
            if (landingHd) {
                // Existe el header de la landing: posicionar justo debajo
                const hdH = landingHd.getBoundingClientRect().height;
                authNav.style.top = hdH + 'px';
            } else {
                // No hay landing header: va pegado arriba
                authNav.style.top = '0px';
            }
        }

        setAuthNavTop();
        window.addEventListener('resize', setAuthNavTop);

        /* ── 2. Scrolled state ── */
        window.addEventListener('scroll', function() {
            if (!authNav) return;
            authNav.classList.toggle('an-scrolled', window.scrollY > 10);

            // Si hay landing header, el top cambia con scroll (header puede estar scrolled/oculto)
            if (landingHd) setAuthNavTop();
        }, {
            passive: true
        });

        /* ── 3. Toggler mobile ── */
        const toggler = document.getElementById('authNavToggler');
        const content = document.getElementById('authNavContent');

        toggler?.addEventListener('click', function() {
            const isOpen = content?.classList.toggle('show');
            toggler.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        /* ── 4. Dropdowns: abrir/cerrar con data-an-toggle ── */
        document.querySelectorAll('[data-an-toggle]').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const targetId = this.getAttribute('data-an-toggle');
                const container = document.getElementById(targetId);
                const isOpen = container?.classList.contains('show');

                // Cerrar todos primero
                document.querySelectorAll('.auth-dropdown.show').forEach(d => d.classList.remove(
                    'show'));

                // Abrir el clickeado si estaba cerrado
                if (!isOpen) container?.classList.add('show');
            });
        });

        /* ── 5. Click outside cierra dropdowns y menú mobile ── */
        document.addEventListener('click', function(e) {
            // Cerrar dropdowns
            if (!e.target.closest('.auth-dropdown')) {
                document.querySelectorAll('.auth-dropdown.show').forEach(d => d.classList.remove('show'));
            }

            // Cerrar menú mobile si click fuera del nav
            if (!e.target.closest('#authNavbar')) {
                content?.classList.remove('show');
                toggler?.setAttribute('aria-expanded', 'false');
            }
        });

        /* ── 6. Marcar notificaciones como leídas al hacer click ── */
        document.querySelectorAll('.auth-notification-item.unread').forEach(item => {
            item.addEventListener('click', function() {
                this.classList.remove('unread');
            });
        });  

    })();
</script>
