@extends('layout')

@section('titulo', 'Notificaciones')

@section('content')

@php
    $filter = request('filter', 'all');
    $search = request('search');
    $query = auth()->user()->notifications();

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

<div class="tbl-card">
    {{-- Hero Section --}}
    <div class="tbl-card-hero">
        <div class="tbl-card-hero-content">
            <h1 class="tbl-card-hero-title text-white">
                <i class="bi bi-bell-fill me-2"></i>Centro de Notificaciones
            </h1>
            <p class="tbl-card-hero-subtitle text-white">
                Gestione sus avisos, alertas y mensajes del sistema en un solo lugar.
            </p>
        </div>

        <div class="tbl-card-hero-actions">
            {{-- Buscador --}}
            <form method="GET" action="" id="ntfSearchForm" class="tbl-hero-search">
                <input type="hidden" name="filter" value="{{ $filter }}">
                <i class="bi bi-search tbl-hero-search-icon"></i>
                <input type="text" class="tbl-hero-search-input" name="search" id="ntfSearchInput"
                    value="{{ $search }}" placeholder="Buscar notificaciones…" autocomplete="off">
                @if ($search)
                    <button class="tbl-hero-search-clear" type="button" id="ntfClearBtn">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                @endif
            </form>

            {{-- Filtro --}}
            <form id="ntfFilterForm" method="GET" action="">
                <input type="hidden" name="search" value="{{ $search }}">
                <div class="tbl-hero-select-wrap">
                    <i class="bi bi-funnel-fill tbl-hero-select-icon"></i>
                    <select class="tbl-hero-select" name="filter" id="ntfFilterSelect">
                        <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>Todas</option>
                        <option value="unread" {{ $filter === 'unread' ? 'selected' : '' }}>No leídas</option>
                        <option value="read" {{ $filter === 'read' ? 'selected' : '' }}>Leídas</option>
                    </select>
                </div>
            </form>

            <div class="d-flex gap-2 mt-2 mt-md-0">
                <button class="tbl-hero-btn tbl-hero-btn-primary" id="ntfMarkAllBtn">
                    <i class="bi bi-check-all"></i>
                    <span>Marcar todo</span>
                </button>
                <button class="tbl-hero-btn tbl-hero-btn-danger" id="ntfDeleteAllBtn">
                    <i class="bi bi-trash-fill"></i>
                    <span>Eliminar leídas</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    @if ($search || $filter !== 'all')
        <div class="tbl-filter-bar">
            <div class="tbl-filter-bar-left">
                <i class="bi bi-funnel-fill"></i>
                @if ($search)
                    Búsqueda: <strong>{{ $search }}</strong>
                @endif
                @if ($filter !== 'all')
                    @if ($search)
                        ·
                    @endif
                    Estado: <span class="tbl-filter-chip">
                        {{ $filter === 'unread' ? 'No leídas' : 'Leídas' }}
                    </span>
                @endif
                — <strong>{{ $notifications->total() }}</strong> resultado(s)
            </div>
            <a href="{{ url()->current() }}" class="tbl-filter-clear">
                <i class="bi bi-x-circle"></i> Limpiar filtros
            </a>
        </div>
    @endif

    <div class="table-container-modern">
        <table class="table-modern">
            <thead>
                <tr>
                    <th style="width:50%">
                        <div class="th-content">
                            <i class="bi bi-chat-left-text-fill"></i><span>Descripción</span>
                        </div>
                    </th>
                    <th style="width:20%">
                        <div class="th-content">
                            <i class="bi bi-clock-fill"></i><span>Tiempo</span>
                        </div>
                    </th>
                    <th style="width:15%">
                        <div class="th-content">
                            <i class="bi bi-circle-fill"></i><span>Estado</span>
                        </div>
                    </th>
                    <th style="width:15%" class="text-center">
                        <div class="th-content justify-content-center">
                            <i class="bi bi-gear-fill"></i><span>Acciones</span>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($notifications as $notification)
                    <tr class="{{ $notification->read_at ? '' : 'ntf-row-unread' }}"
                        data-notification-id="{{ $notification->id }}"
                        data-icon="{{ $notification->data['icon'] ?? 'bi-info-circle' }}"
                        data-message="{{ $notification->data['message'] ?? '' }}"
                        data-details="{{ $notification->data['details'] ?? '' }}"
                        data-time="{{ $notification->created_at->format('d/m/Y H:i:s') }}"
                        data-time-relative="{{ $notification->created_at->diffForHumans() }}"
                        data-read="{{ $notification->read_at ? '1' : '0' }}">

                        <td>
                            <div class="ntf-content">
                                <div class="ntf-icon-wrap {{ $notification->read_at ? 'ntf-icon-wrap--read' : 'ntf-icon-wrap--unread' }}">
                                    <i class="bi {{ $notification->data['icon'] ?? 'bi-info-circle' }}"></i>
                                </div>
                                <div class="ntf-text">
                                    <div class="ntf-message {{ $notification->read_at ? 'ntf-message--read' : '' }}">
                                        {{ $notification->data['message'] ?? '' }}
                                    </div>
                                    @if (!empty($notification->data['details']))
                                        <div class="ntf-details">
                                            {{ Str::limit($notification->data['details'], 70) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td>
                            <span class="ntf-time" data-bs-toggle="tooltip" title="{{ $notification->created_at->format('d/m/Y H:i:s') }}">
                                <i class="bi bi-clock-history me-1"></i>
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                        </td>

                        <td>
                            @if ($notification->read_at)
                                <span class="status-badge status-active">
                                    <i class="bi bi-check-circle-fill"></i> Leído
                                </span>
                            @else
                                <span class="status-badge status-pending">
                                    <i class="bi bi-circle-fill"></i> No leído
                                </span>
                            @endif
                        </td>

                        <td>
                            <div class="action-buttons-cell">
                                <button class="btn-action-modern btn-view" data-action="view"
                                    data-bs-toggle="tooltip" title="Ver detalles">
                                    <i class="bi bi-eye-fill"></i>
                                </button>

                                @if (!$notification->read_at)
                                    <form action="{{ route('notifications.mark-as-read', $notification->id) }}"
                                        method="POST" class="ntf-form ntf-form-read">
                                        @csrf
                                        <button type="submit" class="btn-action-modern btn-info"
                                            data-bs-toggle="tooltip" title="Marcar como leído">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('notifications.delete', $notification->id) }}" method="POST"
                                    class="ntf-form ntf-form-delete">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action-modern btn-delete"
                                        data-bs-toggle="tooltip" title="Eliminar">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <div class="empty-state-table">
                                <div class="empty-icon-table">
                                    <i class="bi bi-bell-slash"></i>
                                </div>
                                <h5 class="empty-title-table">
                                    @if ($search)
                                        Sin resultados para "{{ $search }}"
                                    @else
                                        No hay notificaciones
                                    @endif
                                </h5>
                                <p class="empty-text-table">
                                    @if (!$search)
                                        Cuando recibas alertas del sistema, aparecerán aquí.
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($notifications->hasPages())
        <div class="tbl-pagination">
            <div class="tbl-pagination-info">
                Mostrando <strong>{{ $notifications->firstItem() }}</strong> –
                <strong>{{ $notifications->lastItem() }}</strong> de
                <strong>{{ $notifications->total() }}</strong>
            </div>
            <div class="tbl-pagination-links">
                {{ $notifications->links('vendor.pagination.custom') }}
            </div>
        </div>
    @endif
</div>

<form id="ntfMarkAllForm" action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-none">@csrf</form>
<form id="ntfDeleteAllForm" action="{{ route('notifications.delete-all-read') }}" method="POST" class="d-none">@csrf @method('DELETE')</form>

<script>
    (function() {
        document.addEventListener('DOMContentLoaded', function() {

            /* ── Tooltips ── */
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                new bootstrap.Tooltip(el, { trigger: 'hover' });
            });

            /* ── Filtro ── */
            document.getElementById('ntfFilterSelect')
                ?.addEventListener('change', () =>
                    document.getElementById('ntfFilterForm').submit()
                );

            /* ── Buscador con debounce ── */
            let searchTimer;
            document.getElementById('ntfSearchInput')
                ?.addEventListener('input', function() {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(() =>
                        document.getElementById('ntfSearchForm').submit(), 500);
                });

            /* ── Limpiar búsqueda ── */
            document.getElementById('ntfClearBtn')
                ?.addEventListener('click', () => {
                    document.getElementById('ntfSearchInput').value = '';
                    document.getElementById('ntfSearchForm').submit();
                });

            /* ── Marcar todo leído ── */
            document.getElementById('ntfMarkAllBtn')
                ?.addEventListener('click', () => {
                    Swal.fire({
                        title: '¿Marcar todas como leídas?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#1a4789',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Sí, marcar todas',
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true,
                    }).then(r => {
                        if (r.isConfirmed)
                            document.getElementById('ntfMarkAllForm').submit();
                    });
                });

            /* ── Eliminar todas leídas ── */
            document.getElementById('ntfDeleteAllBtn')
                ?.addEventListener('click', () => {
                    Swal.fire({
                        title: '¿Eliminar notificaciones leídas?',
                        text: 'Esta acción no se puede deshacer.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true,
                    }).then(r => {
                        if (r.isConfirmed)
                            document.getElementById('ntfDeleteAllForm').submit();
                    });
                });

            /* ── Marcar individual ── */
            document.querySelectorAll('.ntf-form-read').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const f = this;
                    Swal.fire({
                        title: '¿Marcar como leída?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#1a4789',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Sí, marcar',
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true,
                    }).then(r => {
                        if (r.isConfirmed) f.submit();
                    });
                });
            });

            /* ── Eliminar individual ── */
            document.querySelectorAll('.ntf-form-delete').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const f = this;
                    Swal.fire({
                        title: '¿Eliminar notificación?',
                        text: 'Esta acción no se puede deshacer.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true,
                    }).then(r => {
                        if (r.isConfirmed) f.submit();
                    });
                });
            });

            /* ── Modal detalle ── */
            document.querySelectorAll('[data-action="view"]').forEach(btn => {
                btn.addEventListener('click', function() {
                    const row = this.closest('tr');
                    const icon = row.dataset.icon || 'bi-info-circle';
                    const message = row.dataset.message || '';
                    const details = row.dataset.details || '';
                    const time = row.dataset.time || '';
                    const timeRel = row.dataset.timeRelative || '';
                    const id = row.dataset.notificationId;
                    const isRead = row.dataset.read === '1';

                    /* Header dinámico del modal */
                    const header = document.getElementById('ntfModalHeader');
                    const mIcon = document.getElementById('ntfModalIcon');
                    const mTime = document.getElementById('ntfModalTime');

                    header.style.background = isRead ?
                        'linear-gradient(135deg,#64748b,#475569)' :
                        'linear-gradient(135deg,#1a4789,#055c9d)';
                    mIcon.innerHTML = `<i class="bi ${icon}"></i>`;
                    mTime.textContent = timeRel;

                    /* Cuerpo del modal */
                    const badge = isRead ?
                        `<span class="status-badge status-active"><i class="bi bi-check-circle-fill"></i> Leído</span>` :
                        `<span class="status-badge status-pending"><i class="bi bi-circle-fill"></i> No leído</span>`;

                    let html = `
                        <div class="d-flex align-items-start gap-3 mb-4">
                            <div class="ntf-modal-icon-wrap" style="background: ${isRead ? '#f1f5f9' : 'rgba(26, 71, 137, 0.1)'}; color: ${isRead ? '#64748b' : '#1a4789'}">
                                <i class="bi ${icon}"></i>
                            </div>
                            <div>
                                <p class="mb-2" style="font-weight:700;font-size:1.05rem;color:#1e293b">${message}</p>
                                ${badge}
                            </div>
                        </div>`;

                    if (details) {
                        html += `
                        <div class="info-field mb-3">
                            <label class="info-label">
                                <i class="bi bi-card-text"></i> Detalles de la notificación
                            </label>
                            <div class="info-value" style="background:#f8fafc;padding:1rem;border-radius:10px;border:1px solid #e2e8f0;font-size:.9rem">${details}</div>
                        </div>`;
                    }

                    html += `
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-field">
                                    <label class="info-label">
                                        <i class="bi bi-calendar-event"></i> Fecha y hora
                                    </label>
                                    <div class="info-value">${time}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-field">
                                    <label class="info-label">
                                        <i class="bi bi-clock-history"></i> Tiempo transcurrido
                                    </label>
                                    <div class="info-value">${timeRel}</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="info-field">
                                    <label class="info-label">
                                        <i class="bi bi-hash"></i> Identificador
                                    </label>
                                    <div class="info-value">
                                        <code style="background:#f1f5f9;padding:2px 6px;border-radius:4px;color:#475569;font-size:.85rem">${id}</code>
                                    </div>
                                </div>
                            </div>
                        </div>`;

                    document.getElementById('ntfModalBody').innerHTML = html;
                    bootstrap.Modal.getOrCreateInstance(
                        document.getElementById('ntfDetailModal')
                    ).show();
                });
            });

        });
    })();
</script>

@endsection


