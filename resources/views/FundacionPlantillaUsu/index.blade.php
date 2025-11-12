@section('nav2')

@endsection




<!--Container-->
@section('container')

 <nav class="auth-navbar mt-8">
        <div class="auth-navbar-container">
            <button class="auth-navbar-toggler" type="button"
                    onclick="document.querySelector('.auth-navbar-content').classList.toggle('show')">
                <i class="bi bi-list auth-navbar-toggler-icon"></i>
            </button>

            <div class="auth-navbar-content">
                <ul class="auth-navbar-menu">

                    <!-- Inicio -->
                    <li class="auth-nav-item" data-tooltip="Ir al inicio">
                        <a class="auth-nav-link {{ request()->routeIs('Inicio') ? 'active' : '' }}"
                           href="{{ route('Inicio') }}">
                            <i class="bi bi-house auth-nav-icon"></i>
                            <span>Inicio</span>
                        </a>
                    </li>

                    <!-- Calendario -->
                    <li class="auth-nav-item" data-tooltip="Ver calendario">
                        <a class="auth-nav-link {{ request()->routeIs('calendario') ? 'active' : '' }}"
                           href="{{ route('calendario') }}">
                            <i class="bi bi-calendar-event auth-nav-icon"></i>
                            <span>Calendario</span>
                        </a>
                    </li>

                    <!-- Notificaciones -->
                    <li class="auth-nav-item auth-notifications-dropdown auth-dropdown">
                        <button class="auth-nav-link auth-dropdown-toggle"
                                onclick="this.parentElement.classList.toggle('show')">
                            <i class="bi bi-bell auth-nav-icon"></i>
                            <span>Notificaciones</span>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="auth-notification-badge">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </button>
                        <div class="auth-dropdown-menu">
                            <div class="auth-dropdown-header">
                                <i class="bi bi-bell-fill me-2"></i>Notificaciones Recientes
                            </div>
                            @forelse (auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                                <div class="auth-notification-item {{ $notification->read_at ? '' : 'unread' }}"
                                     onclick="this.classList.remove('unread')">
                                    <p class="auth-notification-message">
                                        {{ $notification->data['message'] }}
                                    </p>
                                    <span class="auth-notification-time">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            @empty
                                <div class="auth-notification-empty">
                                    <i class="bi bi-inbox"></i>
                                    <span>No hay notificaciones</span>
                                </div>
                            @endforelse
                            @if(auth()->user()->notifications->count() > 0)
                                <div class="auth-dropdown-footer">
                                    <a href="#" class="auth-view-all-link">
                                        Ver todas las notificaciones
                                        <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </li>

                    <!-- Sumario (Solo Docentes) -->
                    @if (auth()->user()->hasRole('Docente'))
                        <li class="auth-nav-item" data-tooltip="Ver estadísticas">
                            <a class="auth-nav-link {{ request()->routeIs('sumario') ? 'active' : '' }}"
                               href="{{ route('sumario') }}">
                                <i class="bi bi-bar-chart-line auth-nav-icon"></i>
                                <span>Sumario</span>
                            </a>
                        </li>
                    @endif

                    <!-- Cursos/Congresos (Solo Estudiantes) -->
                    @if (auth()->user()->hasRole('Estudiante'))
                        <li class="auth-nav-item" data-tooltip="Explorar cursos">
                            <a class="auth-nav-link {{ request()->routeIs('lista.cursos.congresos') ? 'active' : '' }}"
                               href="{{ route('lista.cursos.congresos') }}">
                                <i class="bi bi-collection auth-nav-icon"></i>
                                <span>Cursos/Congresos</span>
                            </a>
                        </li>
                    @endif

                    <!-- Pagos -->
                    <li class="auth-nav-item" data-tooltip="Gestionar pagos">
                        <a class="auth-nav-link {{ request()->routeIs('pagos') ? 'active' : '' }}"
                           href="{{ route('pagos') }}">
                            <i class="bi bi-credit-card-2-front auth-nav-icon"></i>
                            <span>Pagos</span>
                        </a>
                    </li>

                    <!-- User Dropdown -->
                    <li class="auth-nav-item auth-user-dropdown auth-dropdown">
                        <button class="auth-user-toggle auth-dropdown-toggle"
                                onclick="this.parentElement.classList.toggle('show')">
                            <span class="auth-user-name">
                                {{ auth()->user()->name }} {{ auth()->user()->lastname1 }}
                            </span>
                            <div class="auth-user-avatar-wrapper">
                                <i class="bi bi-person-circle auth-user-avatar-icon"></i>
                                <span class="auth-user-status"></span>
                            </div>
                        </button>
                        <div class="auth-user-menu">
                            <a class="auth-user-menu-item" href="{{ route('Miperfil') }}">
                                <i class="bi bi-person"></i>
                                <span>Mi perfil</span>
                            </a>
                            <a class="auth-user-menu-item" href="#">
                                <i class="bi bi-bell"></i>
                                <span>Notificaciones</span>
                            </a>
                            <a class="auth-user-menu-item" href="#">
                                <i class="bi bi-gear"></i>
                                <span>Configuración</span>
                            </a>
                            <hr class="auth-user-divider">
                            <a class="auth-user-menu-item logout" href="{{ route('logout') }}">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Cerrar Sesión</span>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container pt-5" style="margin-top: 7rem !important;">
        @yield('content')
    </div>
@endsection

@include('layoutuser')
