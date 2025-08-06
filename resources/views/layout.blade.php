<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('titulo')</title>

    <!-- Favicon -->
    <link href="{{ asset('./assets/img/Acceder.png') }}" rel="icon" type="image/png">

    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="{{ asset('./assets/js/plugins/nucleo/css/nucleo.css') }}" rel="stylesheet" />
    <link href="{{ asset('./assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        /* ===== CSS VARIABLES ===== */
        :root {
            --primary-color: #2197BD;
            --secondary-color: #39a6cb;
            --tertiary-color: #63becf;
            --dark-blue: #1a4789;
            --success-color: #198754;
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 80px;
            --transition-speed: 0.3s;
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

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: var(--dark-blue);
            padding: 1rem;
            transition: width var(--transition-speed) ease-in-out, transform var(--transition-speed) ease-in-out;
            overflow: visible;
            z-index: 1040;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        /* Prevent content from being cut off */
        .sidebar * {
            box-sizing: border-box;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 12px 10px;
            display: flex;
            align-items: center;
            transition: all var(--transition-speed) ease;
            border-radius: 8px;
            margin-bottom: 4px;
            position: relative;
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
            margin-right: 10px;
            transition: var(--transition-speed);
        }

        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed .user-name {
            display: none !important;
        }

        .sidebar.collapsed .nav-link i {
            margin-right: 0;
        }

        /* Sidebar Menu */
        .sidebar-menu {
            overflow-y: auto;
            overflow-x: visible;
            max-height: calc(80vh - 150px);
            padding-right: 10px;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.4) transparent;
        }

        /* Webkit Scrollbar Styles */
        .sidebar-menu::-webkit-scrollbar {
            width: 8px;
        }

        .sidebar-menu::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.4);
            border-radius: 4px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-menu::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.6);
        }

        .sidebar-menu::-webkit-scrollbar-corner {
            background: transparent;
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
            padding: 10px;
            border-radius: 5px;
            transition: background var(--transition-speed) ease;
        }

        .sidebar-toggler:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar-toggler:focus {
            outline: none;
        }

        /* ===== NOTIFICATIONS ===== */
        .notification-btn {
            padding: 12px 10px;
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
        }

        .notification-btn:hover {
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            transform: translateX(2px);
        }

        .notification-btn i.fa-bell {
            font-size: 18px;
            min-width: 18px;
            text-align: center;
        }

        .notification-btn .badge {
            font-size: 0.65rem;
            margin-left: auto;
            transform: translateY(-2px);
        }

        .sidebar.collapsed .notification-btn {
            justify-content: center !important;
            padding: 12px;
        }

        .sidebar.collapsed .notification-btn i.fa-bell {
            font-size: 20px;
            min-width: auto;
        }

        .sidebar.collapsed .notification-btn .label-notification,
        .sidebar.collapsed .notification-btn .badge {
            display: none !important;
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
            left: calc(var(--sidebar-collapsed-width) + 10px);
            top: 80px;
            animation: fadeDown 0.2s ease-in-out;
        }

        .notification-dropdown .dropdown-item {
            padding: 0.75rem 1rem;
            color: #000;
        }

        .notification-dropdown .dropdown-item:hover {
            background-color: #e3f2fd;
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

        .sidebar.collapsed+.content {
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

        /* ===== USER INTERFACE ===== */
        .user-avatar {
            transition: transform var(--transition-speed) ease-in-out;
            cursor: pointer;
        }

        .user-avatar:hover {
            transform: scale(1.1);
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
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .content {
                margin-left: 0;
            }

            .navbar-main {
                padding: 10px 15px;
                min-height: auto;
            }

            .navbar-brand img {
                max-height: 50px;
            }

            .logo-acceder img {
                max-height: 30px;
            }
        }
    </style>
</head>

<body>
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

            <!-- Logout -->

        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <!-- Header -->
        <div class="header">
            <div class="container-fluid">
                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg navbar-main rounded">
                    <div class="container d-flex justify-content-between align-items-center">
                        <!-- Left Logo -->
                        <a class="navbar-brand" href="{{ route('Inicio') }}">
                            <img src="{{ asset('../assets/img/logof.png') }}" alt="Logo" class="img-fluid">
                        </a>

                        <div class="d-flex align-items-center">
                            <!-- Notifications -->


                            <!-- Right Logo -->
                            <a class="logo-acceder" href="{{ route('Inicio') }}">
                                <img src="{{ asset('../assets/img/Acceder.png') }}" alt="Acceder" class="img-fluid">
                            </a>
                        </div>
                    </div>
                </nav>
                @yield('contentup')
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="container-fluid mt-4">
            @yield('content')
            @yield('contentini')
        </div>

        <!-- Footer -->
        <footer class="footer mt-5 py-4">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start text-muted">
                        &copy; <span id="currentYear"></span>
                        <a href="#" class="text-decoration-none">Fundación Educar para la Vida</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Floating XP Button -->
    @auth
        <div class="floating-xp-button">
            <button type="button" class="btn btn-primary rounded-circle p-3 shadow-lg" data-bs-toggle="offcanvas"
                data-bs-target="#xpOffcanvas" aria-controls="xpOffcanvas">
                <i class="bi bi-trophy-fill"></i>
            </button>
        </div>

        <!-- XP Offcanvas -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="xpOffcanvas">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title">Mi Nivel y Logros</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                @php
                    $user = auth()->user();
                    $inscripciones = $user
                        ->inscritos()
                        ->with(['cursos'])
                        ->get();
                    $xpHistory = \DB::table('xp_events')
                        ->where('users_id', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->get();
                    $totalXP = $xpHistory->sum('xp');
                    $currentLevel = \App\Models\Level::getCurrentLevel($totalXP);
                @endphp

                <!-- Level and XP -->
                <div class="card mb-3 bg-primary text-white achievement-item">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Nivel {{ $currentLevel ? $currentLevel->level_number : 1 }}</h6>
                                <small>{{ $currentLevel ? $currentLevel->title : 'Principiante' }}</small>
                            </div>
                            <div class="text-end">
                                <h4 class="mb-0">{{ number_format($totalXP) }} XP</h4>
                                <small>Total acumulado</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Achievements -->
                <h6 class="mb-3 achievement-item">Últimos Logros</h6>
                @php
                    $unlockedAchievements = \App\Models\Achievement::whereHas('inscritos', function ($query) use (
                        $inscripciones,
                    ) {
                        $query->whereIn('inscrito_id', $inscripciones->pluck('id'));
                    })
                        ->latest()
                        ->take(3)
                        ->get();
                @endphp

                @forelse($unlockedAchievements as $achievement)
                    <div class="d-flex align-items-center mb-2 p-2 bg-light rounded achievement-item">
                        <div class="me-3">
                            <span class="h5 mb-0">{{ $achievement->icon }}</span>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $achievement->title }}</h6>
                            <small class="text-success">+{{ $achievement->xp_reward }} XP</small>
                        </div>
                    </div>
                @empty
                    <p class="text-muted achievement-item">Aún no has desbloqueado ningún logro</p>
                @endforelse

                <div class="mt-3 achievement-item">
                    <a href="{{ route('perfil.xp') }}" class="btn btn-primary w-100">Ver todos mis logros</a>
                </div>
            </div>
        </div>
    @else
        <div class="floating-xp-button">
            <button type="button" class="btn btn-primary rounded-circle p-3 shadow-lg" data-bs-toggle="modal"
                data-bs-target="#registerModal">
                <i class="bi bi-trophy-fill"></i>
            </button>
        </div>

        <!-- Registration Modal -->
        <div class="modal fade" id="registerModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">¡Únete a la aventura!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="bi bi-trophy display-1 text-primary mb-3"></i>
                        <h4>Gana XP y Desbloquea Logros</h4>
                        <p>Regístrate para comenzar a ganar experiencia y desbloquear logros mientras aprendes.</p>
                        <div class="mt-4">
                            <a href="{{ route('signin') }}" class="btn btn-primary">Registrarme ahora</a>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary ms-2">Ya tengo cuenta</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endauth

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('./assets/js/plugins/jquery/dist/jquery.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set current year
            document.getElementById('currentYear').textContent = new Date().getFullYear();

            // Sidebar toggle
            document.getElementById('toggleSidebar').addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('collapsed');
            });

            // Show floating XP button after delay
            setTimeout(() => {
                const button = document.querySelector('.floating-xp-button');
                if (button) button.classList.add('show');
            }, 1000);

            // Hide/show floating button on scroll
            let lastScrollTop = 0;
            let isScrolling;

            window.addEventListener('scroll', function() {
                clearTimeout(isScrolling);
                isScrolling = setTimeout(function() {
                    let currentScroll = window.pageYOffset || document.documentElement.scrollTop;
                    let button = document.querySelector('.floating-xp-button');

                    if (button) {
                        if (currentScroll > lastScrollTop) {
                            button.classList.remove('show');
                        } else {
                            button.classList.add('show');
                        }
                    }
                    lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
                }, 66);
            });

            // Tab and accordion state persistence
            let activeTab = localStorage.getItem("activeTab");
            if (activeTab) {
                let tab = document.querySelector(`[data-bs-target="${activeTab}"]`);
                if (tab) new bootstrap.Tab(tab).show();
            }

            document.querySelectorAll(".nav-link[data-bs-target]").forEach(tab => {
                tab.addEventListener("click", function(event) {
                    let tabTarget = event.target.getAttribute("data-bs-target");
                    localStorage.setItem("activeTab", tabTarget);
                });
            });

            // Achievement animations
            const xpOffcanvas = document.getElementById('xpOffcanvas');
            if (xpOffcanvas) {
                xpOffcanvas.addEventListener('show.bs.offcanvas', function() {
                    const items = document.querySelectorAll('.achievement-item');
                    items.forEach((item, index) => {
                        item.style.animationDelay = `${0.1 * (index + 1)}s`;
                    });
                });
            }

            // SweetAlert notifications
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: "{{ session('success') }}",
                    confirmButtonText: 'Entendido'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: '¡Error!',
                    text: "{{ session('error') }}",
                    confirmButtonText: 'Reintentar'
                });
            @endif
        });
    </script>
    @if (session('info'))
        <script>
            Swal.fire({
                icon: 'info',
                title: 'Información',
                text: "{{ session('info') }}",
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                toast: true,
                position: 'top-end'
            });
        </script>
    @endif
    <script>
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>
</body>

</html>
