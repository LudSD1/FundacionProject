<style>
/* ===== CSS VARIABLES Y CONFIGURACIÓN ===== */
:root {
  /* Colors */
  --color-primary: #2197BD;
  --color-secondary: #39a6cb;
  --color-tertiary: #63becf;
  --color-dark-blue: #1a4789;
  --color-success: #198754;
  --color-white: #ffffff;
  --color-dark: #000000;
  --color-light-gray: #f8f9fa;
  --color-border: #ddd;
  --color-overlay: rgba(0, 0, 0, 0.5);

  /* Spacing */
  --space-xs: 0.25rem;
  --space-sm: 0.5rem;
  --space-md: 1rem;
  --space-lg: 1.5rem;
  --space-xl: 2rem;
  --space-2xl: 2.5rem;

  /* Border Radius */
  --radius-sm: 4px;
  --radius-md: 8px;
  --radius-lg: 12px;

  /* Shadows */
  --shadow-sm: 0 2px 10px rgba(0, 0, 0, 0.15);
  --shadow-md: 0 4px 8px rgba(0, 0, 0, 0.1);
  --shadow-lg: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);

  /* Layout */
  --sidebar-width: 280px;
  --sidebar-collapsed-width: 80px;
  --mobile-breakpoint: 992px;
  --tablet-breakpoint: 991.98px;
  --phone-breakpoint: 576px;
  --small-breakpoint: 375px;

  /* Animations */
  --transition-fast: 0.2s;
  --transition-normal: 0.3s;
  --transition-slow: 0.5s;
}

/* ===== ANIMACIONES ===== */
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

@keyframes scaleUp {
  from {
    transform: scale(0.95);
    opacity: 0;
  }
  to {
    transform: scale(1);
    opacity: 1;
  }
}

/* ===== RESET Y ESTILOS GLOBALES ===== */
* {
  box-sizing: border-box;
}

html, body {
  margin: 0;
  padding: 0;
  height: 100%;
}

body {
  overflow: hidden;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
  background-color: var(--color-white);
}

/* ===== UTILIDADES REUTILIZABLES ===== */
%flex-center {
  display: flex;
  align-items: center;
}

%flex-col {
  display: flex;
  flex-direction: column;
}

%smooth-transition {
  transition: all var(--transition-normal) ease;
}

%focus-visible {
  outline: 2px solid var(--color-white);
  outline-offset: 2px;
}

/* ===== SIDEBAR ===== */
.sidebar {
  width: var(--sidebar-width);
  height: 100vh;
  position: fixed;
  top: 0;
  left: 0;
  background: var(--color-dark-blue);
  padding: var(--space-md);
  overflow-y: auto;
  overflow-x: hidden;
  z-index: 1050;
  box-shadow: var(--shadow-sm);
  @extend %smooth-transition;

  *:not(button) {
    box-sizing: border-box;
  }

  &.collapsed {
    width: var(--sidebar-collapsed-width);

    .nav-link span,
    .user-name,
    .label-notification {
      display: none !important;
    }

    .nav-link i {
      margin-right: 0;
    }

    .notification-btn {
      justify-content: center !important;
      padding: var(--space-md);

      i {
        font-size: 20px;
        min-width: auto;
      }

      .badge {
        position: absolute;
        top: var(--space-xs);
        right: var(--space-xs);
        transform: none;
      }
    }
  }
}

/* Sidebar Menu */
.sidebar-menu {
  overflow-y: auto;
  overflow-x: visible;
  max-height: calc(100vh - 200px);
  padding-right: 5px;
  scrollbar-width: thin;
  scrollbar-color: rgba(255, 255, 255, 0.4) transparent;

  &::-webkit-scrollbar {
    width: 6px;
  }

  &::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: var(--radius-sm);
  }

  &::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.4);
    border-radius: var(--radius-sm);

    &:hover {
      background: rgba(255, 255, 255, 0.6);
    }
  }
}

/* Sidebar Links */
.sidebar a {
  @extend %flex-center;
  color: var(--color-white);
  text-decoration: none;
  padding: var(--space-md) var(--space-lg);
  @extend %smooth-transition;
  border-radius: var(--radius-md);
  margin-bottom: var(--space-xs);
  white-space: nowrap;

  &:hover {
    background: rgba(255, 255, 255, 0.15);
    color: var(--color-white);
    transform: translateX(2px);
  }

  &:focus {
    @extend %focus-visible;
  }

  i {
    font-size: 20px;
    margin-right: var(--space-md);
    min-width: 20px;
    text-align: center;
  }
}

.sidebar.collapsed a {
  justify-content: center;
  padding: var(--space-md);
}

/* Sidebar Toggler */
.sidebar-toggler {
  @extend %flex-center;
  background: none;
  border: none;
  color: var(--color-white);
  font-size: 20px;
  cursor: pointer;
  width: 100%;
  text-align: left;
  padding: var(--space-md);
  border-radius: var(--radius-md);
  @extend %smooth-transition;
  margin-bottom: var(--space-lg);

  &:hover {
    background: rgba(255, 255, 255, 0.1);
  }

  &:focus {
    outline: none;
    @extend %focus-visible;
  }
}

/* Sidebar Overlay (Mobile) */
.sidebar-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: var(--color-overlay);
  z-index: 1040;
  opacity: 0;
  visibility: hidden;
  @extend %smooth-transition;

  &.show {
    opacity: 1;
    visibility: visible;
  }
}

/* ===== NOTIFICATIONS ===== */
.notification-btn {
  padding: var(--space-md) var(--space-lg);
  border: 1px solid rgba(255, 255, 255, 0.2);
  background: none;
  width: 100%;
  text-align: left;
  color: var(--color-white);
  @extend %flex-center;
  gap: var(--space-md);
  @extend %smooth-transition;
  border-radius: var(--radius-md);
  margin-bottom: var(--space-xs);

  &:hover {
    background-color: rgba(255, 255, 255, 0.15);
    border-color: rgba(255, 255, 255, 0.3);
    transform: translateX(2px);
  }

  i {
    font-size: 18px;
    min-width: 20px;
    text-align: center;

    &.fa-bell,
    &.fa-bell-slash {
      font-size: 18px;
    }
  }

  .badge {
    font-size: 0.7rem;
    margin-left: auto;
  }
}

/* Notification Dropdown */
.notification-dropdown,
.user-profile {
  .dropdown-menu {
    background-color: var(--color-white);
    border: none;
    box-shadow: var(--shadow-lg);
    min-width: 300px;
    z-index: 2000;
    position: fixed !important;
    left: calc(var(--sidebar-width) + 10px);
    top: 80px;
    animation: fadeDown var(--transition-fast) ease-in-out;
  }

  .dropdown-item {
    padding: 0.75rem var(--space-lg);
    color: var(--color-dark);
    border-bottom: 1px solid #f0f0f0;

    &:hover {
      background-color: #e3f2fd;
    }

    &:last-child {
      border-bottom: none;
    }
  }

  .nav-link {
    color: #f3f3f3;
  }
}

.sidebar.collapsed {
  .notification-dropdown .dropdown-menu,
  .user-profile .dropdown-menu {
    left: calc(var(--sidebar-collapsed-width) + 10px);
  }
}

.notification-view-all {
  display: block;
  text-align: center;
  padding: var(--space-sm);
  color: var(--color-white);
  background-color: var(--color-tertiary);
  text-decoration: none;
  @extend %smooth-transition;

  &:hover {
    opacity: 0.9;
  }
}

/* ===== USER PROFILE ===== */
.user-profile {
  margin-bottom: var(--space-lg);
}

.user-avatar {
  @extend %smooth-transition;
  cursor: pointer;

  &:hover {
    transform: scale(1.1);
  }
}

/* ===== MOBILE TOGGLE ===== */
.mobile-toggle {
  display: none;
  position: fixed;
  top: 15px;
  left: 15px;
  z-index: 1060;
  background: var(--color-dark-blue);
  border: none;
  color: var(--color-white);
  font-size: 20px;
  padding: var(--space-md);
  border-radius: var(--radius-md);
  box-shadow: var(--shadow-sm);
  @extend %smooth-transition;

  &:hover {
    background: var(--color-primary);
    transform: scale(1.05);
  }

  &:focus {
    outline: none;
    @extend %focus-visible;
  }
}

/* ===== CONTENT AREA ===== */
.content {
  margin-left: var(--sidebar-width);
  @extend %smooth-transition;
  padding: var(--space-xl);
  min-height: 100vh;
  overflow-y: auto;
  height: calc(100vh - 100px);

  .sidebar.collapsed + & {
    margin-left: var(--sidebar-collapsed-width);
  }
}

/* ===== NAVBAR ===== */
.navbar-main {
  background: linear-gradient(
    145deg,
    var(--color-dark-blue) 40%,
    var(--color-primary) 53%,
    rgba(255, 255, 255, 1) 53%
  );
  border: none;
  padding: 15px 20px;
  min-height: 100px;

  .navbar-brand img {
    max-height: 80px;
    width: auto;
  }

  .logo-acceder img {
    max-height: 45px;
  }
}

/* ===== TABLES ===== */
.table {
  overflow: hidden;
  box-shadow: var(--shadow-md);

  thead {
    background-color: var(--color-dark-blue);
    color: var(--color-white);
  }

  th,
  td {
    padding: var(--space-md);
    text-align: left;
    border-bottom: 1px solid var(--color-border);
  }

  tbody {
    tr {
      &:nth-child(even) {
        background-color: var(--color-light-gray);
      }

      &:hover {
        background-color: #e3f2fd;
        @extend %smooth-transition;
      }
    }
  }
}

/* ===== BUTTONS ===== */
.btn-primary {
  background-color: var(--color-primary);
  border-color: var(--color-primary);

  &:hover {
    background-color: var(--color-secondary);
    border-color: var(--color-secondary);
  }
}

.btn-info {
  background-color: var(--color-tertiary);
  border-color: var(--color-tertiary);

  &:hover {
    background-color: var(--color-primary);
    border-color: var(--color-primary);
  }
}

/* ===== FLOATING XP BUTTON ===== */
.floating-xp-button {
  position: fixed;
  bottom: var(--space-xl);
  right: var(--space-xl);
  z-index: 1050;
  transform: translateY(100px);
  opacity: 0;
  @extend %smooth-transition;
  visibility: hidden;

  &.show {
    transform: translateY(0);
    opacity: 1;
    visibility: visible;
  }

  .btn {
    &:hover {
      transform: scale(1.1) rotate(15deg);
    }
  }
}

/* ===== ACHIEVEMENTS ===== */
.achievement-item {
  animation: slideIn var(--transition-normal) ease-out forwards;
  opacity: 0;
}

/* ===== UTILITY CLASSES ===== */
.text-primary {
  color: var(--color-primary) !important;
}

.text-info {
  color: var(--color-tertiary) !important;
}

.bg-primary {
  background-color: var(--color-primary) !important;
}

/* ===== RESPONSIVE DESIGN ===== */

/* Desktop (no changes needed) */
@media (min-width: 992px) {
  .mobile-toggle {
    display: none !important;
  }

  .sidebar-overlay {
    display: none !important;
  }
}

/* Tablets and smaller screens */
@media (max-width: 991.98px) {
  .sidebar {
    width: var(--sidebar-width);
    transform: translateX(-100%);
    z-index: 1050;

    &.show {
      transform: translateX(0);
    }

    &.collapsed {
      width: var(--sidebar-width);
      transform: translateX(-100%);

      &.show {
        transform: translateX(0);
        width: var(--sidebar-width);
      }
    }
  }

  .content {
    margin-left: 0 !important;
    padding: var(--space-lg);
  }

  .mobile-toggle {
    display: block;
    z-index: 1060;
  }

  .navbar-main {
    padding: 10px 60px 10px 15px;
    min-height: 60px;

    .navbar-brand img {
      max-height: 50px;
    }

    .logo-acceder img {
      max-height: 30px;
    }
  }

  .notification-dropdown .dropdown-menu,
  .user-profile .dropdown-menu,
  .sidebar.collapsed .notification-dropdown .dropdown-menu,
  .sidebar.collapsed .user-profile .dropdown-menu {
    position: fixed !important;
    left: 20px !important;
    right: 20px !important;
    width: auto !important;
    min-width: auto !important;
    max-width: none !important;
    top: 80px !important;
  }

  .sidebar-overlay.show {
    opacity: 1;
    visibility: visible;
    z-index: 1049;
  }
}

/* Phones */
@media (max-width: 576px) {
  .content {
    padding: var(--space-sm);
  }

  .navbar-main {
    padding: 8px 50px 8px 10px;
    min-height: 50px;

    .navbar-brand img {
      max-height: 40px;
    }

    .logo-acceder img {
      max-height: 25px;
    }
  }

  .notification-dropdown .dropdown-menu,
  .user-profile .dropdown-menu {
    left: 10px !important;
    right: 10px !important;
    top: 60px !important;
  }
}

/* Extra small phones */
@media (max-width: 375px) {
  .mobile-toggle {
    top: 10px;
    left: 10px;
    padding: var(--space-sm);
    font-size: 16px;
  }

  .navbar-main {
    padding: 5px 45px 5px 8px;
    min-height: 45px;
  }

  .content {
    padding: var(--space-xs);
  }
}

/* Landscape on mobile */
@media (max-height: 600px) and (orientation: landscape) {
  .sidebar {
    padding: var(--space-sm);
  }

  .user-profile {
    margin-bottom: var(--space-sm);
  }

  .sidebar-menu {
    max-height: calc(100vh - 120px);
  }
}

/* Accessibility: Reduced motion */
@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}

/* Dark mode support (opcional) */
@media (prefers-color-scheme: dark) {
  :root {
    --color-light-gray: #2a2a2a;
    --color-border: #444;
  }

  .notification-dropdown .dropdown-item,
  .user-profile .dropdown-item {
    background-color: #333;
    color: var(--color-white);

    &:hover {
      background-color: #444;
    }
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

<button class="mobile-toggle" id="mobileToggle">
    <i class="bi bi-list"></i>
</button>

<!-- Overlay oscuro en móvil -->
<div class="sidebar-overlay"></div>
<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <!-- Toggle Button -->
    <button class="mobile-toggle" id="mobileToggle">
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


<script>
    // Asegúrate de que este código se ejecute después de que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleSidebar = document.getElementById('toggleSidebar');
    const mobileToggle = document.querySelector('.mobile-toggle');
    const sidebarOverlay = document.querySelector('.sidebar-overlay');

    // Función para verificar el tamaño de la pantalla
    function checkScreenSize() {
        if (window.innerWidth < 992) {
            sidebar.classList.remove('collapsed');
        }
    }

    // Verificar al cargar la página
    checkScreenSize();

    // Verificar cuando se redimensiona la ventana
    window.addEventListener('resize', checkScreenSize);

    // Toggle para escritorio
    if (toggleSidebar) {
        toggleSidebar.addEventListener('click', function() {
            if (window.innerWidth >= 992) {
                sidebar.classList.toggle('collapsed');
            } else {
                sidebar.classList.toggle('show');
                document.querySelector('.sidebar-overlay').classList.toggle('show');
            }
        });
    }

    // Toggle para móvil
    if (mobileToggle) {
        mobileToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
            document.querySelector('.sidebar-overlay').classList.toggle('show');
        });
    }

    // Cerrar al hacer clic en el overlay
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('show');
            this.classList.remove('show');
        });
    }
});
</script>
