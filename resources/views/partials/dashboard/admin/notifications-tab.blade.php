@php
    $filter = request('filter', 'all');
    $search = request('search');
    $query = auth()->user()->notifications();

    if ($filter === 'unread') {
        $query->whereNull('read_at');
    } elseif ($filter === 'read') {
        $query->whereNotNull('read_at');
    }

    // Búsqueda simple
    if ($search) {
        $query->where('data->message', 'like', '%' . $search . '%');
    }

    $notifications = $query->latest()->paginate(8);
@endphp

<div class="notifications-wrapper">
    <!-- Header con filtros y búsqueda -->
    <div class="filter-section">
        <div class="row g-3 align-items-center">
            <!-- Búsqueda -->
            <div class="col-lg-4 col-md-6">
                <form method="GET" action="" id="searchForm">
                    <div class="search-box-modern">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text"
                               class="form-control search-input-modern"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Buscar notificaciones..."
                               id="searchInput">
                        <input type="hidden" name="filter" value="{{ request('filter', 'all') }}">
                        @if(request('search'))
                            <button class="btn-clear-search" type="button" onclick="clearSearch()">
                                <i class="bi bi-x-circle-fill"></i>
                            </button>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Filtro -->
            <div class="col-lg-3 col-md-6">
                <form id="filterForm" method="GET" action="">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <div class="select-wrapper-modern">
                        <i class="bi bi-funnel select-icon"></i>
                        <select class="form-select filter-select-modern" name="filter"
                                onchange="document.getElementById('filterForm').submit();">
                            <option value="all" {{ request('filter') == 'all' ? 'selected' : '' }}>
                                Todas las notificaciones
                            </option>
                            <option value="unread" {{ request('filter') == 'unread' ? 'selected' : '' }}>
                                No leídas
                            </option>
                            <option value="read" {{ request('filter') == 'read' ? 'selected' : '' }}>
                                Leídas
                            </option>
                        </select>
                    </div>
                </form>
            </div>

            <!-- Acciones -->
            <div class="col-lg-5 col-md-12">
                <div class="action-buttons d-flex justify-content-end gap-2 flex-wrap">
                    <button class="btn btn-modern btn-read-all"
                            onclick="confirmAction('¿Marcar todas las notificaciones como leídas?', 'markAllReadForm')">
                        <i class="bi bi-check-all me-1"></i>
                        <span>Marcar todo leído</span>
                    </button>
                    <button class="btn btn-modern btn-delete-all"
                            onclick="confirmAction('¿Eliminar todas las notificaciones leídas?', 'deleteAllReadForm')">
                        <i class="bi bi-trash me-1"></i>
                        <span>Eliminar leídas</span>
                    </button>
                </div>

                <!-- Formularios ocultos -->
                <form id="markAllReadForm" action="{{ route('notifications.mark-all-read') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <form id="deleteAllReadForm" action="{{ route('notifications.delete-all-read') }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>

    <!-- Tabla de notificaciones -->
    <div class="table-responsive notifications-table-container">
        <table class="table table-hover notifications-table">
            <thead>
                <tr>
                    <th width="50%">
                        <i class="bi bi-chat-left-text me-2"></i>Descripción
                    </th>
                    <th width="20%">
                        <i class="bi bi-clock me-2"></i>Tiempo
                    </th>
                    <th width="15%">
                        <i class="bi bi-circle-fill me-2"></i>Estado
                    </th>
                    <th width="15%" class="text-center">
                        <i class="bi bi-gear me-2"></i>Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($notifications as $notification)
                    <tr class="notification-row {{ $notification->read_at ? 'notification-read' : 'notification-unread' }}"
                        data-notification-id="{{ $notification->id }}">
                        <td>
                            <div class="notification-content">
                                <div class="notification-icon-wrapper">
                                    <i class="bi {{ $notification->data['icon'] ?? 'bi-info-circle' }} notification-icon"></i>
                                </div>
                                <div class="notification-text">
                                    <div class="notification-message">{{ $notification->data['message'] }}</div>
                                    @if(isset($notification->data['details']) && !empty($notification->data['details']))
                                        <small class="notification-details">
                                            {{ Str::limit($notification->data['details'], 60) }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="notification-time" data-bs-toggle="tooltip"
                                 title="{{ $notification->created_at->format('d/m/Y H:i:s') }}">
                                <i class="bi bi-clock-history me-1"></i>
                                {{ $notification->created_at->diffForHumans() }}
                            </div>
                        </td>
                        <td>
                            @if($notification->read_at)
                                <span class="badge-modern badge-read">
                                    <i class="bi bi-check-circle me-1"></i>Leído
                                </span>
                            @else
                                <span class="badge-modern badge-unread">
                                    <i class="bi bi-circle-fill me-1"></i>No leído
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group-modern">
                                <!-- Ver detalles -->
                                <button class="btn-action btn-action-view"
                                        data-action="view"
                                        data-id="{{ $notification->id }}"
                                        data-bs-toggle="tooltip"
                                        title="Ver detalles">
                                    <i class="bi bi-eye"></i>
                                </button>

                                @if(!$notification->read_at)
                                    <!-- Marcar como leído -->
                                    <form action="{{ route('notifications.mark-as-read', $notification->id) }}"
                                          method="POST" style="display:inline;">
                                        @csrf
                                        <button class="btn-action btn-action-check"
                                                data-bs-toggle="tooltip"
                                                title="Marcar como leído"
                                                onclick="return confirm('¿Marcar esta notificación como leída?')">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                @endif

                                <!-- Eliminar notificación -->
                                <form action="{{ route('notifications.delete', $notification->id) }}"
                                      method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-action btn-action-delete"
                                            data-bs-toggle="tooltip"
                                            title="Eliminar"
                                            onclick="return confirm('¿Eliminar esta notificación permanentemente?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <div class="empty-state-modern">
                                <div class="empty-state-icon">
                                    <i class="bi bi-bell-slash"></i>
                                </div>
                                <h5 class="empty-state-title">
                                    @if(request('search'))
                                        No se encontraron resultados
                                    @elseif(request('filter') == 'unread')
                                        No tienes notificaciones sin leer
                                    @elseif(request('filter') == 'read')
                                        No tienes notificaciones leídas
                                    @else
                                        No hay notificaciones
                                    @endif
                                </h5>
                                <p class="empty-state-text">
                                    @if(request('search'))
                                        No se encontraron notificaciones con "{{ request('search') }}"
                                    @else
                                        Cuando recibas notificaciones, aparecerán aquí
                                    @endif
                                </p>
                                @if(request('search') || request('filter') != 'all')
                                    <a href="{{ url()->current() }}" class="btn btn-modern btn-primary-modern">
                                        <i class="bi bi-arrow-clockwise me-2"></i>
                                        Ver todas las notificaciones
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    @if($notifications->hasPages())
        <div class="pagination-wrapper">
            <div class="pagination-info">
                <i class="bi bi-info-circle me-2"></i>
                Mostrando <strong>{{ $notifications->firstItem() ?? 0 }}</strong> -
                <strong>{{ $notifications->lastItem() ?? 0 }}</strong> de
                <strong>{{ $notifications->total() }}</strong> notificaciones
            </div>
            <div class="pagination-links">
                {{ $notifications->links('vendor.pagination.custom') }}
            </div>
        </div>
    @endif
</div>

<!-- Modal mejorado para detalles -->
<div class="modal fade" id="notificationDetailModal" tabindex="-1" aria-labelledby="notificationDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-modern">
            <div class="modal-header-modern">
                <div class="modal-title-wrapper">
                    <i class="bi bi-info-circle-fill modal-icon"></i>
                    <h5 class="modal-title" id="notificationDetailModalLabel">
                        Detalle de Notificación
                    </h5>
                </div>
                <button type="button" class="btn-close-modern" data-bs-dismiss="modal" aria-label="Cerrar">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="modal-body-modern" id="notificationDetailContent">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer-modern">
                <button type="button" class="btn btn-modern btn-secondary-modern" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Búsqueda con delay
let searchTimeout;
document.getElementById('searchInput').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        document.getElementById('searchForm').submit();
    }, 500);
});

// Limpiar búsqueda
function clearSearch() {
    document.getElementById('searchInput').value = '';
    document.getElementById('searchForm').submit();
}

// Confirmación para acciones
function confirmAction(message, formId) {
    if (confirm(message)) {
        document.getElementById(formId).submit();
    }
}

// Inicializar tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Ver detalles mejorado
document.querySelectorAll('button[data-action="view"]').forEach(btn => {
    btn.addEventListener('click', function() {
        const notificationId = this.getAttribute('data-id');
        const row = this.closest('tr');

        // Obtener datos de la notificación
        const iconElement = row.querySelector('i.bi');
        const icon = iconElement.className;
        const mensaje = row.querySelector('td:first-child div > div:first-child').textContent.trim();
        const detallesElement = row.querySelector('td:first-child small');
        const detalles = detallesElement ? detallesElement.textContent.trim() : null;
        const tiempo = row.querySelector('td:nth-child(2) span').getAttribute('title') ||
               row.querySelector('td:nth-child(2) span').textContent.trim();
        const tiempoRelativo = row.querySelector('td:nth-child(2) span').textContent.trim();
        const estado = row.querySelector('span.badge').textContent.trim();
        const isUnread = row.classList.contains('table-light');

        // Construir contenido del modal
        let modalContent = `
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="d-flex align-items-center">
                        <i class="${icon} me-3 text-primary" style="font-size: 1.5rem;"></i>
                        <div>
                            <h6 class="mb-1">${mensaje}</h6>
                            <span class="badge ${isUnread ? 'bg-primary' : 'bg-secondary'}">${estado}</span>
                        </div>
                    </div>
                </div>
        `;

        if (detalles && detalles !== mensaje) {
            modalContent += `
                <div class="col-12 mb-3">
                    <h6 class="text-muted mb-2">Detalles adicionales</h6>
                    <div class="bg-light p-3 rounded">
                        <p class="mb-0">${detalles}</p>
                    </div>
                </div>
            `;
        }

        modalContent += `
                <div class="col-md-6">
                    <h6 class="text-muted mb-2">Fecha y hora</h6>
                    <p class="mb-1">${tiempo}</p>
                    <small class="text-muted">${tiempoRelativo}</small>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted mb-2">ID de notificación</h6>
                    <p class="mb-0"><code>#${notificationId}</code></p>
                </div>
            </div>
        `;

        // Mostrar modal
        document.getElementById('notificationDetailContent').innerHTML = modalContent;
        var modal = new bootstrap.Modal(document.getElementById('notificationDetailModal'));
        modal.show();

        // Marcar como leída automáticamente si no está leída (opcional)
        if (isUnread) {
            // Aquí podrías hacer una petición AJAX para marcar como leída sin recargar
            // fetch(`/notifications/${notificationId}/mark-as-read`, { method: 'POST', ... })
        }
    });
});
</script>

<style>
/* Estilos adicionales para mejorar la presentación */
.notification-row.table-light {
    background-color: #f8f9ff !important;
    border-left: 3px solid #007bff;
}

.empty-state i {
    opacity: 0.3;
}

.modal-lg {
    max-width: 600px;
}

.bg-light {
    background-color: #f8f9fa !important;
}

/* Mejorar la presentación de detalles en la tabla */
.table td:first-child small {
    display: block;
    margin-top: 0.25rem;
    font-style: italic;
}

/* Animación sutil para botones */
.btn:hover {
    transform: translateY(-1px);
    transition: transform 0.2s ease;
}

/* Mejorar tooltips */
.tooltip {
    font-size: 0.875rem;
}
</style>
