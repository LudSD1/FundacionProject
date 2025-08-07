<style>
    /* ===== CSS VARIABLES ===== */
:root {
    --primary-color: #2197BD;
    --secondary-color: #39a6cb;
    --tertiary-color: #63becf;
    --dark-blue: #1a4789;
    --success-color: #198754;
    --sidebar-width: 280px;
    --sidebar-collapsed-width: 80px;
    --transition-speed: 0.3s;
    --mobile-breakpoint: 992px;
}

/* ===== ANIMATIONS ===== */
@keyframes slideIn {
    from {
        transform: translateX(20px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes fadeDown {
    from {
        opacity: 0;
        transform: translateY(-5px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ===== GLOBAL STYLES ===== */
body {
    overflow-x: hidden;
}

/* ===== SIDEBAR ===== */
.sidebar {
    width: var(--sidebar-width);
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    background: var(--dark-blue);
    padding: 1rem;
    transition: all var(--transition-speed) ease-in-out;
    overflow-y: auto;
    overflow-x: hidden;
    z-index: 1050;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.15);
}

/* Desktop collapsed state */
.sidebar.collapsed {
    width: var(--sidebar-collapsed-width);
}

/* Mobile overlay */
.sidebar-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1040;
    opacity: 0;
    visibility: hidden;
    transition: all var(--transition-speed) ease;
}

.sidebar-overlay.show {
    opacity: 1;
    visibility: visible;
}

/* Prevent content from being cut off */
.sidebar * {
    box-sizing: border-box;
}

.sidebar a {
    color: white;
    text-decoration: none;
    padding: 12px 15px;
    display: flex;
    align-items: center;
    transition: all var(--transition-speed) ease;
    border-radius: 8px;
    margin-bottom: 4px;
    position: relative;
    white-space: nowrap;
}

.sidebar a:hover {
    background: rgba(255, 255, 255, 0.15);
    color: white;
    transform: translateX(2px);
}

.sidebar.collapsed a {
    justify-content: center;
    padding: 12px;
}

.sidebar .nav-link i {
    font-size: 20px;
    margin-right: 12px;
    transition: var(--transition-speed);
    min-width: 20px;
    text-align: center;
}

.sidebar.collapsed .nav-link span,
.sidebar.collapsed .user-name,
.sidebar.collapsed .label-notification {
    display: none !important;
}

.sidebar.collapsed .nav-link i {
    margin-right: 0;
}

/* Sidebar Menu */
.sidebar-menu {
    overflow-y: auto;
    overflow-x: visible;
    max-height: calc(100vh - 200px);
    padding-right: 5px;
    scrollbar-width: thin;
    scrollbar-color: rgba(255, 255, 255, 0.4) transparent;
}

/* Webkit Scrollbar Styles */
.sidebar-menu::-webkit-scrollbar {
    width: 6px;
}

.sidebar-menu::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
}

.sidebar-menu::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.4);
    border-radius: 4px;
}

.sidebar-menu::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.6);
}

/* Sidebar Toggle Button */
.sidebar-toggler {
    background: none;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
    width: 100%;
    text-align: left;
    padding: 12px;
    border-radius: 8px;
    transition: background var(--transition-speed) ease;
    margin-bottom: 1rem;
}

.sidebar-toggler:hover {
    background: rgba(255, 255, 255, 0.1);
}

.sidebar-toggler:focus {
    outline: none;
}

/* Mobile Toggle Button */
.mobile-toggle {
    display: none;
    position: fixed;
    top: 15px;
    left: 15px;
    z-index: 1060;
    background: var(--dark-blue);
    border: none;
    color: white;
    font-size: 20px;
    padding: 12px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    transition: all var(--transition-speed) ease;
}

.mobile-toggle:hover {
    background: var(--primary-color);
    transform: scale(1.05);
}

/* ===== USER PROFILE ===== */
.user-profile {
    margin-bottom: 1rem;
}

.user-avatar {
    transition: transform var(--transition-speed) ease-in-out;
    cursor: pointer;
}

.user-avatar:hover {
    transform: scale(1.1);
}

/* ===== NOTIFICATIONS ===== */
.notification-btn {
    padding: 12px 15px;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: all var(--transition-speed) ease;
    border-radius: 8px;
    margin-bottom: 4px;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.notification-btn:hover {
    background-color: rgba(255, 255, 255, 0.15);
    border-color: rgba(255, 255, 255, 0.3);
    transform: translateX(2px);
}

.notification-btn i.fa-bell,
.notification-btn i.fa-bell-slash {
    font-size: 18px;
    min-width: 20px;
    text-align: center;
}

.notification-btn .badge {
    font-size: 0.7rem;
    margin-left: auto;
}

.sidebar.collapsed .notification-btn {
    justify-content: center !important;
    padding: 12px;
}

.sidebar.collapsed .notification-btn i {
    font-size: 20px;
    min-width: auto;
}

.sidebar.collapsed .notification-btn .badge {
    position: absolute;
    top: 8px;
    right: 8px;
    transform: none;
}

/* Notification Dropdown */
.notification-dropdown .dropdown-menu,
.user-profile .dropdown-menu {
    background-color: #ffffff;
    border: none;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    min-width: 300px;
    z-index: 2000;
    position: fixed !important;
    left: calc(var(--sidebar-width) + 10px);
    top: 80px;
    animation: fadeDown 0.2s ease-in-out;
}

.sidebar.collapsed .notification-dropdown .dropdown-menu,
.sidebar.collapsed .user-profile .dropdown-menu {
    left: calc(var(--sidebar-collapsed-width) + 10px);
}

.notification-dropdown .dropdown-item {
    padding: 0.75rem 1rem;
    color: #000;
    border-bottom: 1px solid #f0f0f0;
}

.notification-dropdown .dropdown-item:hover {
    background-color: #e3f2fd;
}

.notification-dropdown .dropdown-item:last-child {
    border-bottom: none;
}

.notification-dropdown .nav-link {
    color: #f3f3f3;
}

.notification-view-all {
    display: block;
    text-align: center;
    padding: 0.5rem;
    color: white;
    background-color: var(--tertiary-color);
    text-decoration: none;
}

/* ===== CONTENT AREA ===== */
.content {
    margin-left: var(--sidebar-width);
    transition: all var(--transition-speed) ease;
    padding: 2rem;
    min-height: 100vh;
}

.sidebar.collapsed + .content {
    margin-left: var(--sidebar-collapsed-width);
}

/* ===== NAVBAR ===== */
.navbar-main {
    background: linear-gradient(145deg, var(--dark-blue) 40%, var(--primary-color) 53%, rgba(255, 255, 255, 1) 53%);
    border: none;
    padding: 15px 20px;
    min-height: 100px;
}

.navbar-brand img {
    max-height: 80px;
    width: auto;
}

.logo-acceder img {
    max-height: 45px;
}

/* ===== TABLES ===== */
.table {
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.table thead {
    background-color: var(--dark-blue);
    color: white;
}

.table th,
.table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.table tbody tr:nth-child(even) {
    background-color: #f8f9fa;
}

.table tbody tr:hover {
    background-color: #e3f2fd;
    transition: background var(--transition-speed) ease-in-out;
}

/* ===== BUTTONS ===== */
.btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.btn-primary:hover {
    background-color: var(--secondary-color);
    border-color: var(--secondary-color);
}

.btn-info {
    background-color: var(--tertiary-color);
    border-color: var(--tertiary-color);
}

.btn-info:hover {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

/* Floating XP Button */
.floating-xp-button {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    z-index: 1050;
    transform: translateY(100px);
    opacity: 0;
    transition: all var(--transition-speed) ease-in-out;
    visibility: hidden;
}

.floating-xp-button.show {
    transform: translateY(0);
    opacity: 1;
    visibility: visible;
}

.floating-xp-button .btn:hover {
    transform: scale(1.1) rotate(15deg);
}

/* ===== ACHIEVEMENTS ===== */
.achievement-item {
    animation: slideIn var(--transition-speed) ease-out forwards;
    opacity: 0;
}

/* ===== UTILITY CLASSES ===== */
.text-primary {
    color: var(--primary-color) !important;
}

.text-info {
    color: var(--tertiary-color) !important;
}

.bg-primary {
    background-color: var(--primary-color) !important;
}

/* ===== RESPONSIVE DESIGN ===== */

/* Large screens and up (desktops) */
@media (min-width: 992px) {
    .mobile-toggle {
        display: none !important;
    }

    .sidebar-overlay {
        display: none !important;
    }
}

/* Medium screens and down (tablets and phones) */
@media (max-width: 991.98px) {
    .sidebar {
        width: var(--sidebar-width);
        transform: translateX(-100%);
        z-index: 1050;
    }

    .sidebar.show {
        transform: translateX(0);
    }

    .sidebar.collapsed {
        width: var(--sidebar-width);
        transform: translateX(-100%);
    }

    .sidebar.collapsed.show {
        transform: translateX(0);
    }

    .content {
        margin-left: 0 !important;
        padding: 1rem;
    }

    .mobile-toggle {
        display: block;
    }

    .navbar-main {
        padding: 10px 60px 10px 15px;
        min-height: 60px;
    }

    .navbar-brand img {
        max-height: 50px;
    }

    .logo-acceder img {
        max-height: 30px;
    }

    /* Ajustar dropdowns en móvil */
    .notification-dropdown .dropdown-menu,
    .user-profile .dropdown-menu {
        position: fixed !important;
        left: 20px !important;
        right: 20px !important;
        width: auto !important;
        min-width: auto !important;
        max-width: none !important;
        top: 80px !important;
    }

    /* En móvil no aplicar posicionamiento de desktop */
    .sidebar.collapsed .notification-dropdown .dropdown-menu,
    .sidebar.collapsed .user-profile .dropdown-menu {
        left: 20px !important;
        right: 20px !important;
    }
}

/* Small screens (phones) */
@media (max-width: 576px) {
    .content {
        padding: 0.5rem;
    }

    .navbar-main {
        padding: 8px 50px 8px 10px;
        min-height: 50px;
    }

    .navbar-brand img {
        max-height: 40px;
    }

    .logo-acceder img {
        max-height: 25px;
    }

    .notification-dropdown .dropdown-menu,
    .user-profile .dropdown-menu {
        left: 10px !important;
        right: 10px !important;
        top: 60px !important;
    }
}

/* Extra small screens */
@media (max-width: 375px) {
    .mobile-toggle {
        top: 10px;
        left: 10px;
        padding: 8px;
        font-size: 16px;
    }

    .navbar-main {
        padding: 5px 45px 5px 8px;
        min-height: 45px;
    }

    .content {
        padding: 0.25rem;
    }
}

/* Landscape orientation on mobile */
@media (max-height: 600px) and (orientation: landscape) {
    .sidebar {
        padding: 0.5rem;
    }

    .user-profile {
        margin-bottom: 0.5rem;
    }

    .sidebar-menu {
        max-height: calc(100vh - 120px);
    }
}

/* Focus states for accessibility */
.sidebar a:focus,
.sidebar button:focus {
    outline: 2px solid #ffffff;
    outline-offset: 2px;
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
</style>

@php
    $navItems = [
        'Administrador' => [
            ['route' => 'Inicio', 'icon' => 'bi bi-house-door', 'text' => 'Inicio'],
            ['route' => 'ListadeCursos', 'icon' => 'bi bi-journal-bookmark', 'text' => 'Lista de Cursos'],
            ['route' => 'ListaDocentes', 'icon' => 'bi bi-person-video2', 'text' => 'Lista de Docentes'],
            ['route' => 'ListaEstudiantes', 'icon' => 'bi bi-people', 'text' => 'Lista de Estudiantes'],
            ['route' => 'ListaExpositores', 'icon' => 'bi bi-person-video3', 'text' => 'Lista de Expositores'],
            ['route' => 'categorias.index', 'icon' => 'bi bi-tag-fill', 'text' => 'Lista de Categorias'],
            ['route' => 'aportesLista', 'icon' => 'bi bi-wallet', 'text' => 'Lista de Pagos'],
            ['route' => 'AsignarCurso', 'icon' => 'bi bi-person-lines-fill', 'text' => 'Asignación de Cursos'],
            [
                'route' => 'lista.cursos.congresos',
                'icon' => 'bi bi-backpack2-fill',
                'text' => 'Lista de Cursos/Congresos',
            ],
        ],
        'Docente' => [
            ['route' => 'Inicio', 'icon' => 'bi bi-house-door', 'text' => 'Mis Cursos'],
            ['route' => 'AsignarCurso', 'icon' => 'bi bi-key', 'text' => 'Asignación de Cursos'],
            ['route' => 'sumario', 'icon' => 'bi bi-pencil-square', 'text' => 'Sumario'],
            [
                'route' => 'lista.cursos.congresos',
                'icon' => 'bi bi-backpack2-fill',
                'text' => 'Lista de Cursos/Congresos',
            ],
            ['route' => 'calendario', 'icon' => 'bi bi-calendar', 'text' => 'Calendario'],
        ],
        'Estudiante' => [
            ['route' => 'Inicio', 'icon' => 'bi bi-house-door', 'text' => 'Mis Cursos'],
            ['route' => 'pagos', 'icon' => 'bi bi-wallet', 'text' => 'Pagos y Facturación'],
            [
                'route' => 'lista.cursos.congresos',
                'icon' => 'bi bi-backpack2-fill',
                'text' => 'Lista de Cursos/Congresos',
            ],
            ['route' => 'calendario', 'icon' => 'bi bi-calendar', 'text' => 'Calendario'],
        ],
    ];
@endphp

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <!-- Toggle Button -->
    <button class="sidebar-toggler" id="toggleSidebar">
        <i class="bi bi-list"></i>
    </button>

    <!-- User Profile -->
    <div class="user-profile text-center mt-3">
        <!-- Avatar + Dropdown -->
        <div class="dropdown">
            <a href="#"
                class="d-flex align-items-center justify-content-center text-decoration-none text-white dropdown-toggle"
                id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('./assets/img/user.png') }}"
                    alt="Avatar"
                    class="img-fluid rounded-circle user-avatar border border-2 border-light shadow-sm"
                    width="60" height="60" style="object-fit: cover;">
                <span class="user-name ms-2 text-white fw-bold">
                    {{ auth()->user()->name }} {{ auth()->user()->lastname1 }}
                </span>
            </a>

            <ul class="dropdown-menu text-start shadow-lg border-0" aria-labelledby="userDropdown"
                style="min-width: 220px;">
                <li class="dropdown-header">
                    <strong>{{ auth()->user()->name }} {{ auth()->user()->lastname1 }}</strong><br>
                    <small class="text-muted">{{ auth()->user()->email }}</small>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <a class="dropdown-item  text-primary" href="{{ route('Miperfil') }}">
                        <i class="fa fa-user me-2 text-primary"></i> Mi Perfil
                    </a>
                </li>
                <li>
                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                        onclick="return confirm('¿Estás seguro de que deseas cerrar sesión?')">
                        <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                    </a>
                </li>
            </ul>
        </div>

        <!-- Notificaciones debajo del perfil -->
        <div class="dropdown mt-3">
            <button
                class="btn btn-outline-light btn-sm w-100 d-flex align-items-center justify-content-center notification-btn"
                id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i
                    class="fa {{ auth()->user()->unreadNotifications->count() > 0 ? 'fa-bell' : 'fa-bell-slash' }}"></i>

                {{-- Solo mostrar texto cuando el sidebar está expandido --}}
                <span class="ms-2 d-none d-md-inline label-notification">Notificaciones</span>

                {{-- Badge de cantidad de notificaciones --}}
                @if (auth()->user()->unreadNotifications->count() > 0)
                    <span class="badge bg-danger ms-2">
                        {{ auth()->user()->unreadNotifications->count() > 9 ? '9+' : auth()->user()->unreadNotifications->count() }}
                    </span>
                @endif
            </button>

            <ul class="dropdown-menu dropdown-menu-end shadow border-0 notification-dropdown"
                aria-labelledby="notificationDropdown"
                style="max-height: 300px; overflow-y: auto; min-width: 280px;">
                @forelse (auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                    <li>
                        <a class="dropdown-item small {{ $notification->read_at ? '' : 'bg-light' }}"
                            href="#" data-notification-id="{{ $notification->id }}">
                            <i class="fa fa-info-circle text-primary me-2"></i>
                            {{ $notification->data['message'] ?? 'Nueva notificación' }}
                            <div class="text-muted small">{{ $notification->created_at->diffForHumans() }}</div>
                        </a>
                    </li>
                @empty
                    <li>
                        <span class="dropdown-item text-muted small text-center">
                            <i class="fa fa-bell-slash"></i> No hay notificaciones
                        </span>
                    </li>
                @endforelse
            </ul>
        </div>

    </div>

    <hr class="bg-white my-3">

    <!-- Navigation Menu -->
    <div class="sidebar-menu">
        @foreach ($navItems[auth()->user()->getRoleNames()->first()] ?? [] as $item)
            <a href="{{ route($item['route']) }}" class="nav-link">
                <i class="{{ $item['icon'] }}"></i>
                <span>{{ $item['text'] }}</span>
            </a>
        @endforeach

        @yield('nav')
    </div>
</div>
