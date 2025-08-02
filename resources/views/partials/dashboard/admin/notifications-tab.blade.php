@php
    $filter = request('filter', 'all');
    $query = auth()->user()->notifications();
    if ($filter === 'unread') {
        $query->whereNull('read_at');
    } elseif ($filter === 'read') {
        $query->whereNotNull('read_at');
    }
    $notifications = $query->paginate(8);
@endphp
<div class="row mb-3">
    <div class="col-md-4">
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control search-input" placeholder="Buscar notificaciones...">
        </div>
    </div>
    <div class="col-md-4">
        <form id="filterForm" method="GET" action="">
            <select class="form-select filter-select" name="filter" onchange="document.getElementById('filterForm').submit();">
                <option value="all" {{ request('filter') == 'all' ? 'selected' : '' }}>Todas las notificaciones</option>
                <option value="unread" {{ request('filter') == 'unread' ? 'selected' : '' }}>No leídas</option>
                <option value="read" {{ request('filter') == 'read' ? 'selected' : '' }}>Leídas</option>
            </select>
        </form>
    </div>
    <div class="col-md-4 text-end">
        <button class="btn btn-outline-secondary btn-sm me-2" onclick="event.preventDefault(); document.getElementById('markAllReadForm').submit();">
            <i class="bi bi-check-all"></i> Marcar todo como leído
        </button>
        <button class="btn btn-outline-danger btn-sm" onclick="event.preventDefault(); document.getElementById('deleteAllReadForm').submit();">
            <i class="bi bi-trash"></i> Eliminar leídas
        </button>
        <!-- Formulario para marcar todo como leído -->
        <form id="markAllReadForm" action="{{ route('notifications.mark-all-read') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <!-- Formulario para eliminar todas las leídas -->
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
                            <span>{{ $notification->data['message'] }}</span>
                        </div>
                    </td>
                    <td>
                        <span data-bs-toggle="tooltip" title="{{ $notification->created_at }}">
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
                            <!-- Ver detalles (puede ser un modal o link, según tu lógica) -->
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
                                    <button class="btn btn-outline-success btn-sm" data-bs-toggle="tooltip" title="Marcar como leído">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                            @endif
                            <!-- Eliminar notificación -->
                            <form action="{{ route('notifications.delete', $notification->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            <!-- Deshacer (si tienes lógica para esto, puedes agregar el formulario aquí) -->
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-4">
                        <div class="empty-state">
                            <i class="bi bi-bell-slash display-4 text-muted"></i>
                            <p class="mt-3 mb-0">No hay notificaciones para mostrar</p>
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

<!-- Modal para detalles de notificación -->
<div class="modal fade" id="notificationDetailModal" tabindex="-1" aria-labelledby="notificationDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="notificationDetailModalLabel">Detalle de Notificación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" id="notificationDetailContent">
        <!-- Aquí se mostrarán los detalles -->
      </div>
    </div>
  </div>
</div>

<script>
document.querySelectorAll('button[data-action="view"]').forEach(btn => {
    btn.addEventListener('click', function() {
        const row = btn.closest('tr');
        const mensaje = row.querySelector('span').innerText;
        const tiempo = row.querySelector('td:nth-child(2) span').getAttribute('title');
        const estado = row.querySelector('span.badge').innerText;

        document.getElementById('notificationDetailContent').innerHTML = `
            <p><strong>Mensaje:</strong> ${mensaje}</p>
            <p><strong>Fecha:</strong> ${tiempo}</p>
            <p><strong>Estado:</strong> ${estado}</p>
        `;
        var modal = new bootstrap.Modal(document.getElementById('notificationDetailModal'));
        modal.show();
    });
});
</script>
