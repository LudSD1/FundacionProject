@php
    $filter = request('filter', 'all');
    $search = request('search');
    $query  = auth()->user()->notifications();

    if ($filter === 'unread') {
        $query->whereNull('read_at');
    } elseif ($filter === 'read') {
        $query->whereNotNull('read_at');
    }

    if ($search) {
        $query->where('data->message', 'like', '%' . $search . '%');
    }

    $notifications = $query->latest()->paginate(8);
@endphp

<div class="notifications-wrapper">

    {{-- ═══ TOOLBAR ═══════════════════════════════════════════ --}}
    <div class="filter-section mb-4">
        <div class="row g-3 align-items-center">

            {{-- Búsqueda --}}
            <div class="col-lg-4 col-md-6">
                <form method="GET" action="" id="searchForm">
                    <input type="hidden" name="filter" value="{{ $filter }}">
                    <div class="search-box-modern">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text"
                               class="form-control search-input-modern"
                               name="search"
                               id="searchInput"
                               value="{{ $search }}"
                               placeholder="Buscar notificaciones…"
                               autocomplete="off">
                        @if($search)
                            <button class="btn-clear-search" type="button" id="clearSearchBtn">
                                <i class="bi bi-x-circle-fill"></i>
                            </button>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Filtro --}}
            <div class="col-lg-3 col-md-6">
                <form id="filterForm" method="GET" action="">
                    <input type="hidden" name="search" value="{{ $search }}">
                    <div class="select-wrapper-modern">
                        <i class="bi bi-funnel select-icon"></i>
                        <select class="form-select filter-select-modern"
                                name="filter"
                                id="filterSelect">
                            <option value="all"    {{ $filter === 'all'    ? 'selected' : '' }}>Todas</option>
                            <option value="unread" {{ $filter === 'unread' ? 'selected' : '' }}>No leídas</option>
                            <option value="read"   {{ $filter === 'read'   ? 'selected' : '' }}>Leídas</option>
                        </select>
                    </div>
                </form>
            </div>

            {{-- Acciones --}}
            <div class="col-lg-5 col-md-12">
                <div class="action-buttons d-flex justify-content-end gap-2 flex-wrap">
                    <button class="btn btn-modern btn-read-all" id="markAllReadBtn">
                        <i class="bi bi-check-all me-1"></i>
                        <span>Marcar todo leído</span>
                    </button>
                    <button class="btn btn-modern btn-delete-all" id="deleteAllReadBtn">
                        <i class="bi bi-trash me-1"></i>
                        <span>Eliminar leídas</span>
                    </button>
                </div>

                {{-- Formularios ocultos para acciones globales --}}
                <form id="markAllReadForm"   action="{{ route('notifications.mark-all-read') }}"    method="POST" class="d-none">@csrf</form>
                <form id="deleteAllReadForm" action="{{ route('notifications.delete-all-read') }}"  method="POST" class="d-none">@csrf @method('DELETE')</form>
            </div>

        </div>
    </div>

    {{-- ═══ TABLE ══════════════════════════════════════════════ --}}
    <div class="table-responsive notifications-table-container">
        <table class="table table-hover notifications-table">
            <thead>
                <tr>
                    <th style="width:50%"><i class="bi bi-chat-left-text me-2"></i>Descripción</th>
                    <th style="width:20%"><i class="bi bi-clock me-2"></i>Tiempo</th>
                    <th style="width:15%"><i class="bi bi-circle-fill me-2"></i>Estado</th>
                    <th style="width:15%" class="text-center"><i class="bi bi-gear me-2"></i>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($notifications as $notification)
                    <tr class="notification-row {{ $notification->read_at ? 'notification-read' : 'notification-unread' }}"
                        data-notification-id="{{ $notification->id }}"
                        data-icon="{{ $notification->data['icon'] ?? 'bi-info-circle' }}"
                        data-message="{{ $notification->data['message'] ?? '' }}"
                        data-details="{{ $notification->data['details'] ?? '' }}"
                        data-time="{{ $notification->created_at->format('d/m/Y H:i:s') }}"
                        data-time-relative="{{ $notification->created_at->diffForHumans() }}"
                        data-read="{{ $notification->read_at ? '1' : '0' }}">

                        {{-- Descripción --}}
                        <td>
                            <div class="notification-content">
                                <div class="notification-icon-wrapper">
                                    <i class="bi {{ $notification->data['icon'] ?? 'bi-info-circle' }} notification-icon"></i>
                                </div>
                                <div class="notification-text">
                                    <div class="notification-message">
                                        {{ $notification->data['message'] ?? '' }}
                                    </div>
                                    @if(!empty($notification->data['details']))
                                        <small class="notification-details">
                                            {{ Str::limit($notification->data['details'], 60) }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Tiempo --}}
                        <td>
                            <div class="notification-time"
                                 data-bs-toggle="tooltip"
                                 data-bs-placement="top"
                                 title="{{ $notification->created_at->format('d/m/Y H:i:s') }}">
                                <i class="bi bi-clock-history me-1"></i>
                                {{ $notification->created_at->diffForHumans() }}
                            </div>
                        </td>

                        {{-- Estado --}}
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

                        {{-- Acciones --}}
                        <td>
                            <div class="btn-group-modern">

                                {{-- Ver detalles --}}
                                <button class="btn-action btn-action-view"
                                        data-action="view"
                                        data-id="{{ $notification->id }}"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        title="Ver detalles">
                                    <i class="bi bi-eye"></i>
                                </button>

                                {{-- Marcar como leído (solo si no está leída) --}}
                                @if(!$notification->read_at)
                                    <form action="{{ route('notifications.mark-as-read', $notification->id) }}"
                                          method="POST"
                                          class="d-inline mark-read-form">
                                        @csrf
                                        <button type="submit"
                                                class="btn-action btn-action-check"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="Marcar como leído">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                @endif

                                {{-- Eliminar --}}
                                <form action="{{ route('notifications.delete', $notification->id) }}"
                                      method="POST"
                                      class="d-inline delete-notif-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn-action btn-action-delete"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <div class="empty-state-modern">
                                <div class="empty-state-icon">
                                    <i class="bi bi-bell-slash"></i>
                                </div>
                                <h5 class="empty-state-title">
                                    @if($search)
                                        No se encontraron resultados para "{{ $search }}"
                                    @elseif($filter === 'unread')
                                        No tienes notificaciones sin leer
                                    @elseif($filter === 'read')
                                        No tienes notificaciones leídas
                                    @else
                                        No hay notificaciones
                                    @endif
                                </h5>
                                <p class="empty-state-text">
                                    @if(!$search)
                                        Cuando recibas notificaciones, aparecerán aquí
                                    @endif
                                </p>
                                @if($search || $filter !== 'all')
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

    {{-- ═══ PAGINACIÓN ═════════════════════════════════════════ --}}
    @if($notifications->hasPages())
        <div class="pagination-wrapper">
            <div class="pagination-info">
                <i class="bi bi-info-circle me-2"></i>
                Mostrando <strong>{{ $notifications->firstItem() }}</strong> –
                <strong>{{ $notifications->lastItem() }}</strong> de
                <strong>{{ $notifications->total() }}</strong> notificaciones
            </div>
            <div class="pagination-links">
                {{ $notifications->links('vendor.pagination.custom') }}
            </div>
        </div>
    @endif

</div>{{-- /notifications-wrapper --}}


{{-- ═══ MODAL DETALLE ══════════════════════════════════════ --}}
<div class="modal fade" id="notificationDetailModal" tabindex="-1" aria-labelledby="notificationDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:560px;">
        <div class="modal-content modal-modern">

            <div class="modal-header-modern">
                <div class="modal-title-wrapper">
                    <i class="bi bi-info-circle-fill modal-icon"></i>
                    <h5 class="modal-title mb-0" id="notificationDetailModalLabel">Detalle de Notificación</h5>
                </div>
                <button type="button" class="btn-close-modern" data-bs-dismiss="modal" aria-label="Cerrar">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="modal-body-modern" id="notificationDetailContent">
                {{-- contenido dinámico --}}
            </div>

            <div class="modal-footer-modern">
                <button type="button" class="btn-secondary-modern" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cerrar
                </button>
            </div>

        </div>
    </div>
</div>


{{-- ═══ SCRIPTS ════════════════════════════════════════════ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Inicializar tooltips ────────────────────────── */
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el, { trigger: 'hover' });
    });

    /* ── Filtro: submit al cambiar ───────────────────── */
    document.getElementById('filterSelect')?.addEventListener('change', function () {
        document.getElementById('filterForm').submit();
    });

    /* ── Búsqueda con debounce ───────────────────────── */
    let searchTimeout;
    document.getElementById('searchInput')?.addEventListener('input', function () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => document.getElementById('searchForm').submit(), 500);
    });

    /* ── Limpiar búsqueda ────────────────────────────── */
    document.getElementById('clearSearchBtn')?.addEventListener('click', function () {
        document.getElementById('searchInput').value = '';
        document.getElementById('searchForm').submit();
    });

    /* ── Acciones globales con SweetAlert ────────────── */
    document.getElementById('markAllReadBtn')?.addEventListener('click', function () {
        Swal.fire({
            title: '¿Marcar todas como leídas?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-check-all me-1"></i> Confirmar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then(r => { if (r.isConfirmed) document.getElementById('markAllReadForm').submit(); });
    });

    document.getElementById('deleteAllReadBtn')?.addEventListener('click', function () {
        Swal.fire({
            title: '¿Eliminar todas las leídas?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-trash me-1"></i> Eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then(r => { if (r.isConfirmed) document.getElementById('deleteAllReadForm').submit(); });
    });

    /* ── Confirmar marcar como leído (individual) ────── */
    document.querySelectorAll('.mark-read-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const self = this;
            Swal.fire({
                title: '¿Marcar como leída?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, marcar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then(r => { if (r.isConfirmed) self.submit(); });
        });
    });

    /* ── Confirmar eliminar (individual) ─────────────── */
    document.querySelectorAll('.delete-notif-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const self = this;
            Swal.fire({
                title: '¿Eliminar notificación?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then(r => { if (r.isConfirmed) self.submit(); });
        });
    });

    /* ── Modal de detalle ────────────────────────────── */
    document.querySelectorAll('button[data-action="view"]').forEach(btn => {
        btn.addEventListener('click', function () {
            // Leer datos desde data-* del <tr> (fuente única de verdad)
            const row     = this.closest('tr');
            const id      = row.dataset.notificationId;
            const icon    = row.dataset.icon;
            const message = row.dataset.message;
            const details = row.dataset.details;
            const time    = row.dataset.time;
            const timeRel = row.dataset.timeRelative;
            const isRead  = row.dataset.read === '1';

            const badgeClass = isRead ? 'badge-read' : 'badge-unread';
            const badgeText  = isRead
                ? '<i class="bi bi-check-circle me-1"></i>Leído'
                : '<i class="bi bi-circle-fill me-1"></i>No leído';

            let html = `
                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="notification-icon-wrapper flex-shrink-0">
                        <i class="bi ${icon} notification-icon" style="font-size:1.5rem;"></i>
                    </div>
                    <div>
                        <p class="mb-2 fw-500">${message}</p>
                        <span class="badge-modern ${badgeClass}">${badgeText}</span>
                    </div>
                </div>`;

            if (details) {
                html += `
                <div class="info-field mb-3">
                    <label class="info-label"><i class="bi bi-card-text"></i> Detalles</label>
                    <div class="info-value">${details}</div>
                </div>`;
            }

            html += `
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="info-field">
                            <label class="info-label"><i class="bi bi-calendar-event"></i> Fecha y hora</label>
                            <div class="info-value">${time}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-field">
                            <label class="info-label"><i class="bi bi-clock-history"></i> Hace</label>
                            <div class="info-value">${timeRel}</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="info-field">
                            <label class="info-label"><i class="bi bi-hash"></i> ID</label>
                            <div class="info-value"><code>#${id}</code></div>
                        </div>
                    </div>
                </div>`;

            document.getElementById('notificationDetailContent').innerHTML = html;
            new bootstrap.Modal(document.getElementById('notificationDetailModal')).show();
        });
    });

});
</script>
