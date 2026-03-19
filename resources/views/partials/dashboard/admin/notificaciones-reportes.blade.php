
<div class="container-fluid px-3 py-4">
    <div class="adm-panel">
        <div class="adm-tabs-header">
            <ul class="adm-tabs-nav" role="tablist">

                <li role="presentation">
                    <button class="adm-tab active"
                            data-bs-toggle="tab"
                            data-bs-target="#tab-notifications"
                            type="button"
                            role="tab"
                            id="btn-tab-notifications">
                        <i class="bi bi-bell-fill"></i>
                        Notificaciones
                        @php $unread = auth()->user()->unreadNotifications->count(); @endphp
                        @if($unread > 0)
                        <span class="adm-notif-badge">
                            {{ $unread > 99 ? '99+' : $unread }}
                        </span>
                        @endif
                    </button>
                </li>

                <li role="presentation">
                    <button class="adm-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#tab-reports"
                            type="button"
                            role="tab"
                            id="btn-tab-reports">
                        <i class="bi bi-file-earmark-bar-graph-fill"></i>
                        Reportes de Cursos
                    </button>
                </li>

                <li role="presentation">
                    <button class="adm-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#tab-achievements"
                            type="button"
                            role="tab"
                            id="btn-tab-achievements">
                        <i class="bi bi-trophy-fill"></i>
                        Logros
                    </button>
                </li>

            </ul>

            {{-- ── Links externos (páginas separadas) ──
                 FIX 4: separados de los tabs internos
                 FIX 5+6: sin class="{{ route() }}" ni onclick inline --}}
            <div class="adm-tabs-links">
                <a href="{{ route('admin.logs') }}"
                   class="adm-tab-link {{ request()->routeIs('admin.logs') ? 'adm-tab-link--active' : '' }}">
                    <i class="bi bi-list-ul"></i>
                    Logs
                </a>
                <a href="{{ route('payment-methods.index') }}"
                   class="adm-tab-link {{ request()->routeIs('payment-methods.*') ? 'adm-tab-link--active' : '' }}">
                    <i class="bi bi-credit-card-2-front-fill"></i>
                    Métodos de Pago
                </a>
                <a href="{{ route('admin.backups') }}"
                   class="adm-tab-link {{ request()->routeIs('admin.backups') ? 'adm-tab-link--active' : '' }}">
                    <i class="bi bi-database-fill"></i>
                    Backup DB
                </a>
            </div>

        </div>
        <div class="adm-tabs-body">
            <div class="tab-content">

                <div class="tab-pane fade show active"
                     id="tab-notifications"
                     role="tabpanel"
                     aria-labelledby="btn-tab-notifications">
                    @include('partials.dashboard.admin.notifications-tab')
                </div>

                <div class="tab-pane fade"
                     id="tab-reports"
                     role="tabpanel"
                     aria-labelledby="btn-tab-reports">
                    @include('partials.dashboard.admin.reports-tab')
                </div>

                <div class="tab-pane fade"
                     id="tab-achievements"
                     role="tabpanel"
                     aria-labelledby="btn-tab-achievements">
                    @include('partials.dashboard.admin.achievements-tab')
                </div>

            </div>
        </div>

    </div>{{-- /adm-panel --}}
    </div>





    <script src="{{ asset('js/dashboard-tabs.js') }}" defer></script>