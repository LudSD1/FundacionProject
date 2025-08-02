@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard-tabs.css') }}">
    <style>
        /* Estilos específicos para el contenedor principal */
        .container-fluid {
            padding: 1.5rem;
        }

        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .nav-tabs {
            border-bottom: none;
        }

        .nav-tabs .nav-link {
            border: none;
            color: var(--bs-gray-600);
            padding: 1rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link:hover {
            color: var(--bs-primary);
            background-color: rgba(var(--bs-primary-rgb), 0.05);
        }

        .nav-tabs .nav-link.active {
            color: var(--bs-primary);
            background-color: transparent;
            border-bottom: 2px solid var(--bs-primary);
        }

        .notification-count {
            font-size: 0.75rem;
            padding: 0.25em 0.6em;
            margin-left: 0.5rem;
        }

        /* Asegurar que las pestañas no se superpongan en móviles */
        @media (max-width: 768px) {
            .nav-tabs {
                flex-wrap: nowrap;
                overflow-x: auto;
                overflow-y: hidden;
                -webkit-overflow-scrolling: touch;
            }

            .nav-tabs .nav-link {
                white-space: nowrap;
                padding: 0.75rem 1rem;
            }

            .container-fluid {
                padding: 1rem;
            }
        }

        /* Estilos específicos para notificaciones y reportes */
        .notification-badge {
            font-size: 0.8rem;
            padding: 0.25em 0.6em;
        }

        .btn-group .btn {
            transition: all 0.3s ease;
            margin: 0 1px;
        }

        .btn-group .btn:hover {
            transform: translateY(-2px);
        }

        .nav-tabs .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background-color: var(--bs-primary);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-tabs .nav-link:hover::after,
        .nav-tabs .nav-link.active::after {
            width: 100%;
        }

        .table-hover tbody tr {
            transition: all 0.2s ease;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(var(--bs-primary-rgb), 0.05);
            transform: translateX(5px);
        }

        .empty-state {
            padding: 3rem;
            text-align: center;
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--bs-gray-400);
            margin-bottom: 1rem;
        }

        .progress {
            height: 20px;
            border-radius: 10px;
            background-color: rgba(var(--bs-primary-rgb), 0.1);
        }

        .progress-bar {
            border-radius: 10px;
            transition: width 0.6s ease;
        }

        /* Animaciones para las acciones */
        .notification-row {
            transition: all 0.3s ease;
        }

        .notification-row.fade-out {
            opacity: 0;
            transform: translateX(100%);
        }

        .badge {
            transition: all 0.3s ease;
        }

        /* Estilos para los botones de acción */
        .action-buttons .btn {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            border-radius: 0.25rem;
            transition: all 0.3s ease;
        }

        .action-buttons .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Mejoras visuales para las tablas */
        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            color: var(--bs-gray-600);
        }

        .table td {
            vertical-align: middle;
        }

        /* Estilos para los filtros y búsqueda */
        .filter-section {
            background-color: rgba(var(--bs-light-rgb), 0.5);
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .filter-section .form-control,
        .filter-section .form-select {
            border-radius: 0.5rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .action-buttons {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }

            .btn-group {
                display: flex;
                width: 100%;
            }

            .btn-group .btn {
                flex: 1;
            }
        }
    </style>
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
