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

<div class="row mb-3">
    <div class="col-md-4">
        <form method="GET" action="" id="searchForm">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text"
                       class="form-control search-input"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Buscar notificaciones..."
                       id="searchInput">
                <input type="hidden" name="filter" value="{{ request('filter', 'all') }}">
                @if(request('search'))
                    <button class="btn btn-outline-secondary" type="button" onclick="clearSearch()">
                        <i class="bi bi-x"></i>
                    </button>
                @endif
            </div>
        </form>
    </div>
    <div class="col-md-4">
        <form id="filterForm" method="GET" action="">
            <input type="hidden" name="search" value="{{ request('search') }}">
            <select class="form-select filter-select" name="filter" onchange="document.getElementById('filterForm').submit();">
                <option value="all" {{ request('filter') == 'all' ? 'selected' : '' }}>Todas las notificaciones</option>
                <option value="unread" {{ request('filter') == 'unread' ? 'selected' : '' }}>No leídas</option>
                <option value="read" {{ request('filter') == 'read' ? 'selected' : '' }}>Leídas</option>
            </select>
        </form>
    </div>
    <div class="col-md-4 text-end">
        <button class="btn btn-outline-secondary btn-sm me-2"
                onclick="confirmAction('¿Marcar todas las notificaciones como leídas?', 'markAllReadForm')">
            <i class="bi bi-check-all"></i> Marcar todo como leído
        </button>
        <button class="btn btn-outline-danger btn-sm"
                onclick="confirmAction('¿Eliminar todas las notificaciones leídas?', 'deleteAllReadForm')">
            <i class="bi bi-trash"></i> Eliminar leídas
        </button>

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

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Descripción</th>
                <th>Tiempo</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($notifications as $notification)
                <tr class="{{ $notification->read_at ? '' : 'table-light' }} notification-row"
                    data-notification-id="{{ $notification->id }}">
                    <td>
                        <div class="d-flex align-items-center">
                            <i class="bi {{ $notification->data['icon'] ?? 'bi-info-circle' }} me-2 text-primary"></i>
                            <div>
                                <div>{{ $notification->data['message'] }}</div>
                                @if(isset($notification->data['details']) && !empty($notification->data['details']))
                                    <small class="text-muted">{{ Str::limit($notification->data['details'], 60) }}</small>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <span data-bs-toggle="tooltip" title="{{ $notification->created_at->format('d/m/Y H:i:s') }}">
                            {{ $notification->created_at->diffForHumans() }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $notification->read_at ? 'bg-secondary' : 'bg-primary' }}">
                            {{ $notification->read_at ? 'Leído' : 'No leído' }}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group">
                            <!-- Ver detalles -->
                            <button class="btn btn-outline-primary btn-sm"
                                    data-action="view"
                                    data-id="{{ $notification->id }}"
                                    data-bs-toggle="tooltip"
                                    title="Ver detalles">
                                <i class="bi bi-eye"></i>
                            </button>

                            @if(!$notification->read_at)
                                <!-- Marcar como leído -->
                                <form action="{{ route('notifications.mark-as-read', $notification->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button class="btn btn-outline-success btn-sm"
                                            data-bs-toggle="tooltip"
                                            title="Marcar como leído"
                                            onclick="return confirm('¿Marcar esta notificación como leída?')">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                            @endif

                            <!-- Eliminar notificación -->
                            <form action="{{ route('notifications.delete', $notification->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm"
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
                    <td colspan="4" class="text-center py-4">
                        <div class="empty-state">
                            <i class="bi bi-bell-slash display-4 text-muted"></i>
                            <p class="mt-3 mb-0">
                                @if(request('search'))
                                    No se encontraron notificaciones con "{{ request('search') }}"
                                @elseif(request('filter') == 'unread')
                                    No tienes notificaciones sin leer
                                @elseif(request('filter') == 'read')
                                    No tienes notificaciones leídas
                                @else
                                    No hay notificaciones para mostrar
                                @endif
                            </p>
                            @if(request('search') || request('filter') != 'all')
                                <a href="{{ url()->current() }}" class="btn btn-outline-primary btn-sm mt-2">
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

<div class="d-flex justify-content-between align-items-center mt-3">
    <div>
        Mostrando {{ $notifications->firstItem() ?? 0 }} -
        {{ $notifications->lastItem() ?? 0 }} de
        {{ $notifications->total() }} notificaciones
    </div>
    <div>
         {{ $notifications->links('vendor.pagination.custom') }}
    </div>
</div>

<!-- Modal mejorado para detalles de notificación -->
<div class="modal fade" id="notificationDetailModal" tabindex="-1" aria-labelledby="notificationDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notificationDetailModalLabel">
                    <i class="bi bi-info-circle me-2"></i>
                    Detalle de Notificación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" id="notificationDetailContent">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
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
