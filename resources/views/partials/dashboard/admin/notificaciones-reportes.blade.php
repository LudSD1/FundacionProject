    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard-tabs.css') }}">

@endpush

<div class="container-fluid px-4 py-5">
    <!-- Contenedor principal con animación de fade-in -->
    <div class="row animate__animated animate__fadeIn">
        <div class="col-12">
            <div class="card shadow-sm">
                <!-- Tabs de navegación -->
                <div class="card-header bg-white">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="notifications-tab" data-bs-toggle="tab" href="#notifications"
                                role="tab">
                                <i class="bi bi-bell"></i> Notificaciones
                                <span
                                    class="badge bg-danger notification-count">{{ auth()->user()->unreadNotifications->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="reports-tab" data-bs-toggle="tab" href="#reports" role="tab">
                                <i class="bi bi-file-text"></i> Reportes de Cursos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="achievements-tab" data-bs-toggle="tab" href="#achievements"
                                role="tab">
                                <i class="bi bi-trophy"></i> Logros
                            </a>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link btn {{ request()->routeIs('admin.logs') ? 'active' : '' }}"
                                onclick="window.location='{{ route('admin.logs') }}'">
                                <i class="bi bi-list"></i> Logs
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link btn {{ route('payment-methods.index') }}"
                                onclick="window.location='{{ route('payment-methods.index') }}'">
                                <i class="bi bi-credit-card"></i> Metodos de pago
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link btn {{ route('admin.backups') }}"
                                onclick="window.location='{{ route('admin.backups') }}'">
                                <i class="bi bi-database"></i> Backup DB
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">
                        <!-- Tab de Notificaciones -->
                        <div class="tab-pane fade show active" id="notifications" role="tabpanel">
                            @include('partials.dashboard.admin.notifications-tab')
                        </div>

                        <!-- Tab de Reportes -->
                        <div class="tab-pane fade" id="reports" role="tabpanel">
                            @include('partials.dashboard.admin.reports-tab')
                        </div>

                        <!-- Tab de Logros -->
                        <div class="tab-pane fade" id="achievements" role="tabpanel">
                            @include('partials.dashboard.admin.achievements-tab')
                        </div>

                    




                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/dashboard-tabs.js') }}"></script>
