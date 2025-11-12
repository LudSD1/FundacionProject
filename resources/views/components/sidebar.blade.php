
@php
    $navItems = [
        'Administrador' => [
            ['route' => 'Inicio', 'icon' => 'bi bi-house-door-fill', 'text' => 'Inicio'],
            ['route' => 'ListadeCursos', 'icon' => 'bi bi-journal-bookmark-fill', 'text' => 'Lista de Cursos'],
            ['route' => 'ListaDocentes', 'icon' => 'bi bi-person-video2', 'text' => 'Lista de Docentes'],
            ['route' => 'ListaEstudiantes', 'icon' => 'bi bi-people-fill', 'text' => 'Lista de Estudiantes'],
            ['route' => 'ListaExpositores', 'icon' => 'bi bi-person-video3', 'text' => 'Lista de Expositores'],
            ['route' => 'categorias.index', 'icon' => 'bi bi-tag-fill', 'text' => 'Lista de Categorías'],
            ['route' => 'aportesLista', 'icon' => 'bi bi-wallet-fill', 'text' => 'Lista de Pagos'],
            ['route' => 'AsignarCurso', 'icon' => 'bi bi-person-lines-fill', 'text' => 'Asignación de Cursos'],
            ['route' => 'lista.cursos.congresos', 'icon' => 'bi bi-backpack2-fill', 'text' => 'Cursos/Congresos'],
        ],
        'Docente' => [
            ['route' => 'Inicio', 'icon' => 'bi bi-house-door-fill', 'text' => 'Mis Cursos'],
            ['route' => 'AsignarCurso', 'icon' => 'bi bi-key-fill', 'text' => 'Asignación de Cursos'],
            ['route' => 'sumario', 'icon' => 'bi bi-graph-up', 'text' => 'Sumario'],
            ['route' => 'lista.cursos.congresos', 'icon' => 'bi bi-backpack2-fill', 'text' => 'Cursos/Congresos'],
            ['route' => 'calendario', 'icon' => 'bi bi-calendar-event-fill', 'text' => 'Calendario'],
        ],
        'Estudiante' => [
            ['route' => 'Inicio', 'icon' => 'bi bi-house-door-fill', 'text' => 'Mis Cursos'],
            ['route' => 'pagos', 'icon' => 'bi bi-wallet-fill', 'text' => 'Pagos y Facturación'],
            ['route' => 'lista.cursos.congresos', 'icon' => 'bi bi-backpack2-fill', 'text' => 'Cursos/Congresos'],
            ['route' => 'calendario', 'icon' => 'bi bi-calendar-event-fill', 'text' => 'Calendario'],
        ],
    ];

    $currentRoute = Route::currentRouteName();
@endphp

<!-- Mobile Toggle Button -->
<button class="mobile-toggle" id="mobileToggle" aria-label="Abrir menú">
    <i class="bi bi-list"></i>
</button>

<!-- Sidebar Overlay para Mobile -->
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
            <!-- User Avatar & Name -->
            <a href="#"
               class="d-flex align-items-center text-decoration-none dropdown-toggle"
               id="userDropdown"
               data-bs-toggle="dropdown"
               aria-expanded="false"
               aria-label="Menú de usuario">

                <div class="position-relative">
                    <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('./assets/img/user.png') }}"
                         alt="Avatar de {{ auth()->user()->name }}"
                         class="user-avatar rounded-circle shadow-sm"
                         width="50"
                         height="50"
                         style="object-fit: cover;">
                    <!-- Status Indicator -->
                    <span class="position-absolute bottom-0 end-0 translate-middle p-1 bg-success border border-light rounded-circle"
                          style="width: 12px; height: 12px;"
                          title="En línea">
                    </span>
                </div>

                <div class="user-name ms-3 text-white">
                    <div class="fw-bold" style="font-size: 0.95rem;">
                        {{ auth()->user()->name }} {{ auth()->user()->lastname1 }}
                    </div>
                    <small class="text-white-50" style="font-size: 0.75rem;">
                        {{ auth()->user()->getRoleNames()->first() }}
                    </small>
                </div>
            </a>

            <!-- User Dropdown Menu -->
            <ul class="dropdown-menu shadow-lg border-0"
                aria-labelledby="userDropdown"
                style="min-width: 250px;">

                <!-- User Info Header -->
                <li class="dropdown-header bg-light py-3">
                    <div class="d-flex align-items-center">
                        <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('./assets/img/user.png') }}"
                             alt="Avatar"
                             class="rounded-circle me-2"
                             width="40"
                             height="40"
                             style="object-fit: cover;">
                        <div>
                            <strong class="d-block">{{ auth()->user()->name }}</strong>
                            <small class="text-muted">{{ auth()->user()->email }}</small>
                        </div>
                    </div>
                </li>

                <li><hr class="dropdown-divider my-0"></li>

                <!-- Profile Link -->
                <li>
                    <a class="dropdown-item py-2" href="{{ route('Miperfil') }}">
                        <i class="bi bi-person-circle me-2"></i>
                        <span>Mi Perfil</span>
                    </a>
                </li>

                <!-- Settings Link -->
                <li>
                    <a class="dropdown-item py-2" href="#">
                        <i class="bi bi-gear me-2"></i>
                        <span>Configuración</span>
                    </a>
                </li>

                <li><hr class="dropdown-divider my-0"></li>

                <!-- Logout -->
                <li>
                    <a class="dropdown-item py-2 text-danger"
                       href="{{ route('logout') }}"
                       onclick="return confirm('¿Estás seguro de que deseas cerrar sesión?')">
                        <i class="bi bi-box-arrow-right me-2"></i>
                        <span>Cerrar Sesión</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Notifications Section -->
    <div class="dropdown mt-3">
        <button class="notification-btn"
                id="notificationDropdown"
                data-bs-toggle="dropdown"
                aria-expanded="false"
                aria-label="Notificaciones">
            <i class="bi {{ auth()->user()->unreadNotifications->count() > 0 ? 'bi-bell-fill' : 'bi-bell' }}"></i>
            <span class="label-notification">Notificaciones</span>

            @if (auth()->user()->unreadNotifications->count() > 0)
                <span class="badge bg-danger rounded-pill">
                    {{ auth()->user()->unreadNotifications->count() > 9 ? '9+' : auth()->user()->unreadNotifications->count() }}
                </span>
            @endif
        </button>

        <!-- Notifications Dropdown -->
        <ul class="dropdown-menu shadow-lg border-0"
            aria-labelledby="notificationDropdown"
            style="max-height: 400px; overflow-y: auto; min-width: 320px;">

            <!-- Notifications Header -->
            <li class="px-3 py-2 bg-light border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <strong>Notificaciones</strong>
                    @if (auth()->user()->unreadNotifications->count() > 0)
                        <a href="#" class="text-primary small" onclick="markAllAsRead(event)">
                            Marcar todas como leídas
                        </a>
                    @endif
                </div>
            </li>

            @forelse (auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                <li>
                    <a class="dropdown-item {{ $notification->read_at ? '' : 'bg-light border-start border-primary border-3' }}"
                       href="#"
                       data-notification-id="{{ $notification->id }}"
                       onclick="markAsRead(event, '{{ $notification->id }}')">
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

            @if (auth()->user()->notifications->count() > 0)
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
        @foreach ($navItems[auth()->user()->getRoleNames()->first()] ?? [] as $item)
            <a href="{{ route($item['route']) }}"
               class="nav-link {{ $currentRoute === $item['route'] ? 'active' : '' }}"
               role="menuitem"
               aria-label="{{ $item['text'] }}"
               title="{{ $item['text'] }}">
                <i class="{{ $item['icon'] }}"></i>
                <span>{{ $item['text'] }}</span>
            </a>
        @endforeach

        @yield('nav')
    </nav>


</aside>

<!-- JavaScript Mejorado -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleSidebar = document.getElementById('toggleSidebar');
    const toggleIcon = document.getElementById('toggleIcon');
    const mobileToggle = document.getElementById('mobileToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const layoutWrapper = document.querySelector('.layout-wrapper');

    // Función para actualizar el estado del layout wrapper
    function updateLayoutWrapper(isCollapsed) {
        if (layoutWrapper) {
            if (isCollapsed && window.innerWidth >= 992) {
                layoutWrapper.classList.add('sidebar-collapsed');
            } else {
                layoutWrapper.classList.remove('sidebar-collapsed');
            }
        }
    }

    // Función para actualizar el icono del toggle
    function updateToggleIcon(isCollapsed) {
        if (toggleIcon) {
            toggleIcon.className = isCollapsed ? 'bi bi-arrow-bar-right' : 'bi bi-arrow-bar-left';
        }
        const toggleText = toggleSidebar?.querySelector('span');
        if (toggleText) {
            toggleText.textContent = isCollapsed ? 'Expandir menú' : 'Contraer menú';
        }
    }

    // Cargar estado del sidebar desde localStorage
    const sidebarState = localStorage.getItem('sidebarCollapsed');
    if (sidebarState === 'true' && window.innerWidth >= 992) {
        sidebar.classList.add('collapsed');
        updateToggleIcon(true);
        updateLayoutWrapper(true);
    } else {
        updateLayoutWrapper(false);
    }

    // Toggle para desktop
    if (toggleSidebar) {
        toggleSidebar.addEventListener('click', function() {
            if (window.innerWidth >= 992) {
                const isCollapsed = sidebar.classList.toggle('collapsed');
                localStorage.setItem('sidebarCollapsed', isCollapsed);
                updateToggleIcon(isCollapsed);
                updateLayoutWrapper(isCollapsed);
                // Reposicionar dropdowns si están abiertos
                setTimeout(repositionDropdowns, 300);
            } else {
                toggleMobileSidebar();
            }
        });
    }

    // Toggle para móvil
    if (mobileToggle) {
        mobileToggle.addEventListener('click', toggleMobileSidebar);
    }

    // Cerrar al hacer clic en el overlay
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeMobileSidebar);
    }

    // Cerrar sidebar móvil al hacer clic en un enlace
    document.querySelectorAll('.sidebar .nav-link').forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth < 992) {
                closeMobileSidebar();
            }
        });
    });

    // Manejar redimensionamiento de ventana
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (window.innerWidth >= 992) {
                closeMobileSidebar();
                const isCollapsed = sidebar.classList.contains('collapsed');
                updateLayoutWrapper(isCollapsed);
            } else {
                sidebar.classList.remove('collapsed');
                updateLayoutWrapper(false);
            }
            repositionDropdowns();
        }, 250);
    });

    // Función para reposicionar dropdowns
    function repositionDropdowns() {
        if (window.innerWidth < 992) {
            // En móvil, los dropdowns se manejan con CSS
            return;
        }

        const dropdowns = document.querySelectorAll('.sidebar .dropdown-menu.show');
        const isCollapsed = sidebar.classList.contains('collapsed');
        const sidebarWidth = isCollapsed ? 80 : 280;

        dropdowns.forEach(menu => {
            // Buscar el botón toggle asociado
            const ariaLabelledBy = menu.getAttribute('aria-labelledby');
            let toggle = null;

            if (ariaLabelledBy) {
                toggle = document.getElementById(ariaLabelledBy);
            }

            if (!toggle) {
                // Buscar en el contenedor padre
                const dropdownContainer = menu.closest('.dropdown');
                if (dropdownContainer) {
                    toggle = dropdownContainer.querySelector('[data-bs-toggle="dropdown"]');
                }
            }

            if (toggle) {
                const toggleRect = toggle.getBoundingClientRect();
                const menuRect = menu.getBoundingClientRect();

                // Calcular posición
                menu.style.position = 'fixed';
                menu.style.left = (sidebarWidth + 8) + 'px';
                menu.style.top = toggleRect.top + 'px';
                menu.style.zIndex = '1055';

                // Ajustar si se sale de la pantalla por abajo
                const viewportHeight = window.innerHeight;
                const menuBottom = toggleRect.top + menuRect.height;
                if (menuBottom > viewportHeight - 10) {
                    menu.style.top = (viewportHeight - menuRect.height - 10) + 'px';
                }

                // Ajustar si se sale por arriba
                if (toggleRect.top < 10) {
                    menu.style.top = '10px';
                }
            }
        });
    }

    // Esperar a que Bootstrap esté listo
    if (typeof bootstrap !== 'undefined') {
        // Reposicionar dropdowns cuando se abren
        document.querySelectorAll('.sidebar [data-bs-toggle="dropdown"]').forEach(toggle => {
            toggle.addEventListener('shown.bs.dropdown', function() {
                setTimeout(repositionDropdowns, 50);
            });

            toggle.addEventListener('hide.bs.dropdown', function() {
                // Limpiar estilos inline cuando se cierra
                setTimeout(() => {
                    const menu = document.querySelector(`[aria-labelledby="${toggle.id}"]`);
                    if (menu && !menu.classList.contains('show')) {
                        menu.style.left = '';
                        menu.style.top = '';
                    }
                }, 300);
            });
        });
    } else {
        // Fallback si Bootstrap no está disponible todavía
        setTimeout(() => {
            document.querySelectorAll('.sidebar [data-bs-toggle="dropdown"]').forEach(toggle => {
                toggle.addEventListener('click', function() {
                    setTimeout(repositionDropdowns, 100);
                });
            });
        }, 500);
    }

    // Reposicionar al hacer scroll
    window.addEventListener('scroll', function() {
        if (document.querySelectorAll('.sidebar .dropdown-menu.show').length > 0) {
            repositionDropdowns();
        }
    }, { passive: true });

    // Observar cambios en el sidebar para reposicionar dropdowns
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === 'class') {
                setTimeout(repositionDropdowns, 300);
            }
        });
    });

    if (sidebar) {
        observer.observe(sidebar, { attributes: true });
    }

    // Funciones auxiliares
    function toggleMobileSidebar() {
        sidebar.classList.toggle('show');
        sidebarOverlay.classList.toggle('show');
        document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
    }

    function closeMobileSidebar() {
        sidebar.classList.remove('show');
        sidebarOverlay.classList.remove('show');
        document.body.style.overflow = '';
    }

    // Cerrar dropdowns al hacer clic fuera
    document.addEventListener('click', function(event) {
        const dropdowns = document.querySelectorAll('.dropdown-menu.show');
        dropdowns.forEach(dropdown => {
            const toggle = dropdown.previousElementSibling ||
                         document.querySelector(`[aria-labelledby="${dropdown.getAttribute('aria-labelledby')}"]`) ||
                         dropdown.closest('.dropdown')?.querySelector('[data-bs-toggle="dropdown"]');
            if (toggle && !dropdown.contains(event.target) && !toggle.contains(event.target)) {
                const bsDropdown = bootstrap.Dropdown.getInstance(toggle);
                if (bsDropdown) {
                    bsDropdown.hide();
                }
            }
        });
    });
});

// Función para marcar notificación como leída
function markAsRead(event, notificationId) {
    event.preventDefault();

    fetch(`/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Actualizar UI
            const notificationElement = event.currentTarget;
            notificationElement.classList.remove('bg-light', 'border-start', 'border-primary', 'border-3');

            // Actualizar contador
            updateNotificationBadge();
        }
    })
    .catch(error => console.error('Error:', error));
}

// Función para marcar todas como leídas
function markAllAsRead(event) {
    event.preventDefault();

    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

// Actualizar badge de notificaciones
function updateNotificationBadge() {
    const badge = document.querySelector('.notification-btn .badge');
    if (badge) {
        const currentCount = parseInt(badge.textContent) || 0;
        const newCount = currentCount - 1;

        if (newCount <= 0) {
            badge.remove();
            // Cambiar icono
            const icon = document.querySelector('.notification-btn i');
            if (icon) {
                icon.className = 'bi bi-bell';
            }
        } else {
            badge.textContent = newCount > 9 ? '9+' : newCount;
        }
    }
}
</script>
