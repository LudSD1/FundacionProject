@php
    // ✅ Asignar usuario una sola vez (evita 8+ llamadas a auth()->user())
    $user = auth()->user();
    $userRole = $user->getRoleNames()->first();

    // ✅ Eager-load de notificaciones para evitar N+1 queries
    $unreadCount = $user->unreadNotifications->count();
    $recentNotifications = $user->notifications()->latest()->take(5)->get();
    $totalNotifications = $user->notifications()->count();

    $navItems = [
        'Administrador' => [
            ['route' => 'Inicio',                'icon' => 'bi bi-house-door-fill',     'text' => 'Inicio'],
            ['route' => 'ListadeCursos',          'icon' => 'bi bi-journal-bookmark-fill','text' => 'Lista de Cursos'],
            ['route' => 'ListaUsuarios',          'icon' => 'bi bi-person-video2',        'text' => 'Lista de Usuarios'],
            ['route' => 'ListaExpositores',       'icon' => 'bi bi-person-video3',        'text' => 'Lista de Expositores'],
            ['route' => 'categorias.index',       'icon' => 'bi bi-tag-fill',             'text' => 'Lista de Categorías'],
            ['route' => 'aportesLista',           'icon' => 'bi bi-wallet-fill',          'text' => 'Lista de Pagos'],
            ['route' => 'AsignarCurso',           'icon' => 'bi bi-person-lines-fill',    'text' => 'Asignación de Cursos'],
            ['route' => 'lista.cursos.congresos', 'icon' => 'bi bi-backpack2-fill',       'text' => 'Cursos/Congresos'],
        ],
        'Docente' => [
            ['route' => 'Inicio',                'icon' => 'bi bi-house-door-fill',      'text' => 'Mis Cursos'],
            ['route' => 'AsignarCurso',          'icon' => 'bi bi-key-fill',             'text' => 'Asignación de Cursos'],
            ['route' => 'sumario',               'icon' => 'bi bi-graph-up',             'text' => 'Sumario'],
            ['route' => 'lista.cursos.congresos','icon' => 'bi bi-backpack2-fill',        'text' => 'Cursos/Congresos'],
            ['route' => 'calendario',            'icon' => 'bi bi-calendar-event-fill',  'text' => 'Calendario'],
        ],
        'Estudiante' => [
            ['route' => 'Inicio',                'icon' => 'bi bi-house-door-fill',      'text' => 'Mis Cursos'],
            ['route' => 'pagos',                 'icon' => 'bi bi-wallet-fill',          'text' => 'Pagos y Facturación'],
            ['route' => 'lista.cursos.congresos','icon' => 'bi bi-backpack2-fill',        'text' => 'Cursos/Congresos'],
            ['route' => 'calendario',            'icon' => 'bi bi-calendar-event-fill',  'text' => 'Calendario'],
        ],
    ];

    $currentRoute = Route::currentRouteName();

    // ✅ Calcular avatar URL una sola vez
    $avatarUrl = $user->avatar
        ? asset('storage/' . $user->avatar)
        : asset('./assets/img/user.png');
@endphp

<!-- Mobile Toggle Button -->
<button class="mobile-toggle" id="mobileToggle" aria-label="Abrir menú">
    <i class="bi bi-list"></i>
</button>

<!-- Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar Principal -->
<aside class="sidebar" id="sidebar" role="navigation" aria-label="Menú principal">

    <!-- Sidebar Toggle Button (Desktop) -->
    <button class="sidebar-toggler" id="toggleSidebar" aria-label="Contraer/Expandir menú">
        <i class="bi bi-arrow-bar-left" id="toggleIcon"></i>
        <span class="ms-2">Contraer menú</span>
    </button>

    <!-- User Profile Section -->
    <div class="user-profile">
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
                id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Menú de usuario">

                <div class="position-relative">
                    <img src="{{ $avatarUrl }}" alt="Avatar de {{ $user->name }}"
                        class="user-avatar rounded-circle shadow-sm"
                        width="50" height="50" style="object-fit: cover;">
                    <span class="position-absolute bottom-0 end-0 translate-middle p-1 bg-success border border-light rounded-circle"
                        style="width: 12px; height: 12px;" title="En línea"></span>
                </div>

                <div class="user-name ms-3 text-white">
                    <div class="fw-bold" style="font-size: 0.95rem;">
                        {{ $user->name }} {{ $user->lastname1 }}
                    </div>
                    <small class="text-white-50" style="font-size: 0.75rem;">
                        {{ $userRole }}
                    </small>
                </div>
            </a>

            <!-- User Dropdown Menu -->
            <ul class="dropdown-menu shadow-lg border-0" aria-labelledby="userDropdown" style="min-width: 250px;">

                <li class="dropdown-header bg-light py-3">
                    <div class="d-flex align-items-center">
                        <img src="{{ $avatarUrl }}" alt="Avatar" class="rounded-circle me-2"
                            width="40" height="40" style="object-fit: cover;">
                        <div>
                            <strong class="d-block">{{ $user->name }}</strong>
                            <small class="text-muted">{{ $user->email }}</small>
                        </div>
                    </div>
                </li>

                <li><hr class="dropdown-divider my-0"></li>

                <li>
                    <a class="dropdown-item py-2" href="{{ route('Miperfil') }}">
                        <i class="bi bi-person-circle me-2"></i>
                        <span>Mi Perfil</span>
                    </a>
                </li>

                <li>
                    <a class="dropdown-item py-2" href="#">
                        <i class="bi bi-gear me-2"></i>
                        <span>Configuración</span>
                    </a>
                </li>

                <li><hr class="dropdown-divider my-0"></li>

                <li>
                    <a class="dropdown-item py-2 text-danger" href="#"
                    onclick="event.preventDefault(); cerrarSesion();">
                    <i class="bi bi-box-arrow-right me-2"></i>
                    <span>Cerrar Sesión</span>
                </a>

                 <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                     @csrf
                 </form>
                </li>
            </ul>
        </div>
    </div>

    <!-- Notifications Section -->
    <div class="dropdown mt-3">
        <button class="notification-btn" id="notificationDropdown" data-bs-toggle="dropdown"
            aria-expanded="false" aria-label="Notificaciones">
            <i class="bi {{ $unreadCount > 0 ? 'bi-bell-fill' : 'bi-bell' }}"></i>
            <span class="label-notification">Notificaciones</span>

            @if ($unreadCount > 0)
                <span class="badge bg-danger rounded-pill">
                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                </span>
            @endif
        </button>

        <ul class="dropdown-menu shadow-lg border-0" aria-labelledby="notificationDropdown"
            style="max-height: 400px; overflow-y: auto; min-width: 320px;">

            <li class="px-3 py-2 bg-light border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <strong>Notificaciones</strong>
                    @if ($unreadCount > 0)
                        <a href="#" class="text-primary small" onclick="Sidebar.markAllAsRead(event)">
                            Marcar todas como leídas
                        </a>
                    @endif
                </div>
            </li>

            @forelse ($recentNotifications as $notification)
                <li>
                    <a class="dropdown-item {{ $notification->read_at ? '' : 'bg-light border-start border-primary border-3' }}"
                        href="#" data-notification-id="{{ $notification->id }}"
                        onclick="Sidebar.markAsRead(event, '{{ $notification->id }}')">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-2">
                                <i class="bi bi-info-circle-fill text-primary" style="font-size: 1.25rem;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-1 small">{{ $notification->data['message'] ?? 'Nueva notificación' }}</p>
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $notification->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    </a>
                </li>
            @empty
                <li class="text-center py-4">
                    <i class="bi bi-bell-slash text-muted" style="font-size: 2.5rem;"></i>
                    <p class="text-muted mt-2 mb-0">No hay notificaciones</p>
                </li>
            @endforelse

            @if ($totalNotifications > 0)
                <li class="border-top">
                    <a class="notification-view-all" href="#">
                        Ver todas las notificaciones
                        <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </li>
            @endif
        </ul>
    </div>

    <hr class="bg-white opacity-25 my-3">

    <!-- Navigation Menu -->
    <nav class="sidebar-menu" role="menu">
        @foreach ($navItems[$userRole] ?? [] as $item)
            <a href="{{ route($item['route']) }}"
                class="nav-link {{ $currentRoute === $item['route'] ? 'active' : '' }}"
                role="menuitem" title="{{ $item['text'] }}" aria-label="{{ $item['text'] }}">
                <i class="{{ $item['icon'] }}"></i>
                <span>{{ $item['text'] }}</span>
            </a>
        @endforeach

        @yield('nav')
    </nav>

</aside>

<script>
/**
 * ✅ Namespace para evitar contaminación del scope global (window)
 */
const Sidebar = (() => {
    // Referencias DOM cacheadas
    let sidebar, toggleSidebar, toggleIcon, mobileToggle, sidebarOverlay, layoutWrapper;
    let observerTimeout, resizeTimer;
    let observer;

    // ─── Helpers ────────────────────────────────────────────────────────────────

    function updateLayoutWrapper(isCollapsed) {
        layoutWrapper?.classList.toggle('sidebar-collapsed', isCollapsed && window.innerWidth >= 992);
    }

    function updateToggleIcon(isCollapsed) {
        if (toggleIcon) {
            toggleIcon.className = isCollapsed ? 'bi bi-arrow-bar-right' : 'bi bi-arrow-bar-left';
        }
        const toggleText = toggleSidebar?.querySelector('span');
        if (toggleText) {
            toggleText.textContent = isCollapsed ? 'Expandir menú' : 'Contraer menú';
        }
    }

    function openMobileSidebar() {
        sidebar.classList.add('show');
        sidebarOverlay.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileSidebar() {
        sidebar.classList.remove('show');
        sidebarOverlay.classList.remove('show');
        document.body.style.overflow = '';
    }

    // ─── Dropdown positioning ────────────────────────────────────────────────────
    //
    // Estrategia: posicionar SOLO al momento del "shown" (getBoundingClientRect
    // justo cuando el menú ya existe en el DOM). NO reposicionar en scroll/resize
    // para evitar el desplazamiento errático.
    //
    // Desktop: el menú aparece a la derecha del sidebar.
    // Móvil:   el menú se centra en pantalla (CSS lo maneja con transform).

    function positionDropdown(menu, toggle) {
        if (window.innerWidth < 992) {
            // En móvil el CSS centra el dropdown; solo necesitamos el top
            const toggleRect = toggle.getBoundingClientRect();
            const menuHeight = menu.offsetHeight || 300; // fallback antes de render
            const idealTop   = toggleRect.bottom + 8;
            const maxTop     = window.innerHeight - menuHeight - 10;
            const top        = Math.max(10, Math.min(idealTop, maxTop));

            Object.assign(menu.style, {
                top:  `${top}px`,
                left: '',
                right: '',
            });
            return;
        }

        // Desktop: a la derecha del sidebar
        const isCollapsed  = sidebar.classList.contains('collapsed');
        const sidebarWidth = isCollapsed
            ? parseInt(getComputedStyle(document.documentElement)
                .getPropertyValue('--sidebar-collapsed-width')) || 80
            : parseInt(getComputedStyle(document.documentElement)
                .getPropertyValue('--sidebar-width')) || 280;

        const toggleRect = toggle.getBoundingClientRect();
        const menuHeight = menu.offsetHeight || 300;
        const left       = sidebarWidth + 8;

        let top = toggleRect.top;
        const maxTop = window.innerHeight - menuHeight - 10;
        top = Math.max(10, Math.min(top, maxTop));

        Object.assign(menu.style, {
            position:   'fixed',
            left:       `${left}px`,
            top:        `${top}px`,
            right:      '',
            transform:  'none',
            zIndex:     '1055',
        });
    }

    function clearDropdownStyles(menu) {
        if (!menu) return;
        Object.assign(menu.style, {
            top: '', left: '', right: '', transform: '', position: '',
        });
    }

    // ─── Bootstrap dropdown bindings ────────────────────────────────────────────

    function bindDropdownEvents() {
        document.querySelectorAll('.sidebar [data-bs-toggle="dropdown"]').forEach(toggle => {
            // Al MOSTRAR: posicionar una sola vez (tras el primer frame de render)
            toggle.addEventListener('shown.bs.dropdown', () => {
                const labelledBy = toggle.id
                    ? `[aria-labelledby="${toggle.id}"]`
                    : null;
                const menu = labelledBy
                    ? document.querySelector(labelledBy)
                    : toggle.closest('.dropdown')?.querySelector('.dropdown-menu');
                if (menu) requestAnimationFrame(() => positionDropdown(menu, toggle));
            });

            // Al OCULTAR: limpiar estilos inline
            toggle.addEventListener('hidden.bs.dropdown', () => {
                const labelledBy = toggle.id
                    ? `[aria-labelledby="${toggle.id}"]`
                    : null;
                const menu = labelledBy
                    ? document.querySelector(labelledBy)
                    : toggle.closest('.dropdown')?.querySelector('.dropdown-menu');
                clearDropdownStyles(menu);
            });
        });
    }

    // ─── Init ────────────────────────────────────────────────────────────────────

    function init() {
        sidebar         = document.getElementById('sidebar');
        toggleSidebar   = document.getElementById('toggleSidebar');
        toggleIcon      = document.getElementById('toggleIcon');
        mobileToggle    = document.getElementById('mobileToggle');
        sidebarOverlay  = document.getElementById('sidebarOverlay');
        layoutWrapper   = document.querySelector('.layout-wrapper');

        // Restaurar estado guardado
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true' && window.innerWidth >= 992;
        if (isCollapsed) {
            sidebar.classList.add('collapsed');
            updateToggleIcon(true);
        }
        updateLayoutWrapper(isCollapsed);

        // Toggle desktop / mobile unificado
        toggleSidebar?.addEventListener('click', () => {
            if (window.innerWidth >= 992) {
                const collapsed = sidebar.classList.toggle('collapsed');
                localStorage.setItem('sidebarCollapsed', collapsed);
                updateToggleIcon(collapsed);
                updateLayoutWrapper(collapsed);
            } else {
                sidebar.classList.contains('show') ? closeMobileSidebar() : openMobileSidebar();
            }
        });

        mobileToggle?.addEventListener('click', () =>
            sidebar.classList.contains('show') ? closeMobileSidebar() : openMobileSidebar()
        );

        sidebarOverlay?.addEventListener('click', closeMobileSidebar);

        // Cerrar sidebar móvil al navegar
        document.querySelectorAll('.sidebar .nav-link').forEach(link =>
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) closeMobileSidebar();
            })
        );

        // Resize con debounce
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                if (window.innerWidth >= 992) {
                    closeMobileSidebar();
                    updateLayoutWrapper(sidebar.classList.contains('collapsed'));
                } else {
                    sidebar.classList.remove('collapsed');
                    updateLayoutWrapper(false);
                }
            }, 250);
        });

        // MutationObserver: si el sidebar se colapsa mientras hay un dropdown abierto,
        // cerrarlo para evitar posición incorrecta.
        observer = new MutationObserver(mutations => {
            if (mutations.some(m => m.attributeName === 'class')) {
                document.querySelectorAll('.sidebar [data-bs-toggle="dropdown"]').forEach(toggle => {
                    const instance = bootstrap.Dropdown.getInstance(toggle);
                    if (instance) instance.hide();
                });
            }
        });
        observer.observe(sidebar, { attributes: true, attributeFilter: ['class'] });

        // Cerrar dropdowns al hacer clic fuera
        document.addEventListener('click', e => {
            document.querySelectorAll('.sidebar .dropdown-menu.show').forEach(menu => {
                const labelledBy = menu.getAttribute('aria-labelledby');
                const toggle = labelledBy
                    ? document.getElementById(labelledBy)
                    : menu.closest('.dropdown')?.querySelector('[data-bs-toggle="dropdown"]');

                if (toggle && !menu.contains(e.target) && !toggle.contains(e.target)) {
                    bootstrap.Dropdown.getInstance(toggle)?.hide();
                }
            });
        });

        // Bind Bootstrap dropdown events (espera a que esté disponible)
        if (typeof bootstrap !== 'undefined') {
            bindDropdownEvents();
        } else {
            const checkBootstrap = setInterval(() => {
                if (typeof bootstrap !== 'undefined') {
                    clearInterval(checkBootstrap);
                    bindDropdownEvents();
                }
            }, 100);
        }
    }

    // ─── Notificaciones ──────────────────────────────────────────────────────────

    function markAsRead(event, notificationId) {
        event.preventDefault();

        fetch(`/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) return;
            event.currentTarget.classList.remove('bg-light', 'border-start', 'border-primary', 'border-3');
            updateNotificationBadge(-1);
        })
        .catch(err => console.error('Error al marcar notificación:', err));
    }

    function markAllAsRead(event) {
        event.preventDefault();

        fetch('/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
        })
        .then(res => res.json())
        .then(data => { if (data.success) location.reload(); })
        .catch(err => console.error('Error al marcar todas:', err));
    }

    function updateNotificationBadge(delta = -1) {
        const badge = document.querySelector('.notification-btn .badge');
        if (!badge) return;

        const newCount = (parseInt(badge.textContent) || 0) + delta;

        if (newCount <= 0) {
            badge.remove();
            const icon = document.querySelector('.notification-btn i');
            if (icon) icon.className = 'bi bi-bell';
        } else {
            badge.textContent = newCount > 9 ? '9+' : newCount;
        }
    }

    // ─── Cleanup ─────────────────────────────────────────────────────────────────

    function destroy() {
        observer?.disconnect();
    }

    // API pública
    return { init, markAsRead, markAllAsRead, destroy };
})();

document.addEventListener('DOMContentLoaded', Sidebar.init);


function cerrarSesion() {
    Swal.fire({
        title: '¿Cerrar sesión?',
        text: "Tu sesión actual se cerrará.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, cerrar sesión',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('logout-form').submit();
        }
    });
}


</script>