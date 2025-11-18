@extends('layout')

@section('titulo', 'Logs del Sistema')

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header text-white d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-file-alt me-3 fs-4"></i>
                            <div>
                                <h4 class="mb-0 fw-bold">Logs del Sistema</h4>
                                <small class="opacity-75">Monitoreo en tiempo real</small>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('Inicio') }}" class="btn btn-light btn-sm me-2">
                                <i class="fas fa-home me-1"></i>
                                Inicio
                            </a>
                            <button onclick="location.reload()" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-sync-alt me-1"></i>
                                Actualizar
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Estadísticas rápidas -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="stats-card">
                                    <div class="stats-number" id="total-logs">24</div>
                                    <div class="stats-label">Total de Logs</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stats-card">
                                    <div class="stats-number" id="error-logs">3</div>
                                    <div class="stats-label">Errores</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stats-card">
                                    <div class="stats-number" id="warning-logs">5</div>
                                    <div class="stats-label">Advertencias</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stats-card">
                                    <div class="stats-number" id="info-logs">16</div>
                                    <div class="stats-label">Informativos</div>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="filter-buttons">
                                <button class="btn btn-primary active" data-filter="all">
                                    Todos
                                </button>
                                <button class="btn btn-outline-primary" data-filter="error">
                                    <i class="fas fa-exclamation-circle me-1"></i>Errores
                                </button>
                                <button class="btn btn-outline-primary" data-filter="warning">
                                    <i class="fas fa-exclamation-triangle me-1"></i>Advertencias
                                </button>
                                <button class="btn btn-outline-primary" data-filter="info">
                                    <i class="fas fa-info-circle me-1"></i>Información
                                </button>
                            </div>

                            <div class="d-flex align-items-center">
                                <div class="input-group input-group-sm me-2" style="width: 200px;">
                                    <input type="text" class="form-control" placeholder="Buscar en logs..."
                                        id="search-logs">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <button class="btn btn-accent btn-sm">
                                    <i class="fas fa-download me-1"></i>Exportar
                                </button>
                            </div>
                        </div>

                        <!-- Contenedor de logs -->
                        <div class="log-container" id="log-container">
                            <!-- Los logs se cargarán aquí dinámicamente -->
                        </div>

                        <!-- Controles de navegación -->
                        <div class="mt-3 d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                Actualizado hace <span id="last-update">2</span> minutos
                            </small>
                            <div>
                                <button onclick="scrollToTop()" class="btn btn-secondary btn-sm me-2">
                                    <i class="fas fa-arrow-up me-1"></i>
                                    Ir al inicio
                                </button>
                                <button onclick="scrollToBottom()" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-down me-1"></i>
                                    Ir al final
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Datos de ejemplo para los logs
        const sampleLogs = [{
                id: 1,
                type: 'info',
                timestamp: '2023-06-15 10:30:45',
                content: 'Sistema iniciado correctamente. Todos los servicios están operativos.'
            },
            {
                id: 2,
                type: 'warning',
                timestamp: '2023-06-15 10:35:22',
                content: 'Advertencia: El uso de memoria está por encima del 80%. Considere optimizar los recursos.'
            },
            {
                id: 3,
                type: 'info',
                timestamp: '2023-06-15 10:40:15',
                content: 'Usuario admin ha iniciado sesión desde la IP 192.168.1.105.'
            },
            {
                id: 4,
                type: 'error',
                timestamp: '2023-06-15 10:42:33',
                content: 'Error: No se pudo establecer conexión con la base de datos. Reintentando en 5 segundos.'
            },
            {
                id: 5,
                type: 'info',
                timestamp: '2023-06-15 10:45:10',
                content: 'Conexión con la base de datos restablecida correctamente.'
            },
            {
                id: 6,
                type: 'warning',
                timestamp: '2023-06-15 10:50:28',
                content: 'Advertencia: Se detectaron múltiples intentos de acceso fallidos para el usuario "test".'
            },
            {
                id: 7,
                type: 'info',
                timestamp: '2023-06-15 11:05:42',
                content: 'Copia de seguridad programada completada exitosamente.'
            },
            {
                id: 8,
                type: 'error',
                timestamp: '2023-06-15 11:15:19',
                content: 'Error crítico: Fallo en el servicio de autenticación. Reiniciando servicio...'
            }
        ];

        // Función para determinar el tipo de log basado en el contenido
        function getLogType(content) {
            if (content.toLowerCase().includes('error') || content.toLowerCase().includes('fallo')) {
                return 'error';
            } else if (content.toLowerCase().includes('warning') || content.toLowerCase().includes('advertencia')) {
                return 'warning';
            } else if (content.toLowerCase().includes('info')) {
                return 'info';
            } else {
                return 'default';
            }
        }

        // Función para formatear el timestamp
        function formatTimestamp(date) {
            return date.toLocaleString('es-ES');
        }

        // Función para renderizar los logs
        function renderLogs(logs) {
            const container = document.getElementById('log-container');
            container.innerHTML = '';

            if (logs.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-search"></i>
                        <h5>No se encontraron logs</h5>
                        <p class="mb-0">Intenta ajustar los filtros o la búsqueda</p>
                    </div>
                `;
                return;
            }

            logs.forEach(log => {
                const logElement = document.createElement('div');
                logElement.className = `log-entry ${log.type} fade-in`;
                logElement.setAttribute('data-log-type', log.type);

                const badgeClass = `badge-${log.type}`;

                logElement.innerHTML = `
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="log-badge ${badgeClass}">${log.type.toUpperCase()}</span>
                        <small class="log-timestamp">${log.timestamp}</small>
                    </div>
                    <p class="log-content">${log.content}</p>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <small class="text-muted">ID: ${log.id}</small>
                        <button class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-copy me-1"></i>Copiar
                        </button>
                    </div>
                `;

                container.appendChild(logElement);
            });

            updateStats(logs);
        }

        // Función para actualizar las estadísticas
        function updateStats(logs) {
            const total = logs.length;
            const errors = logs.filter(log => log.type === 'error').length;
            const warnings = logs.filter(log => log.type === 'warning').length;
            const infos = logs.filter(log => log.type === 'info').length;

            document.getElementById('total-logs').textContent = total;
            document.getElementById('error-logs').textContent = errors;
            document.getElementById('warning-logs').textContent = warnings;
            document.getElementById('info-logs').textContent = infos;
        }

        // Función para filtrar logs
        function filterLogs(type) {
            if (type === 'all') {
                renderLogs(sampleLogs);
            } else {
                const filteredLogs = sampleLogs.filter(log => log.type === type);
                renderLogs(filteredLogs);
            }

            // Actualizar estado de los botones de filtro
            document.querySelectorAll('.filter-buttons .btn').forEach(btn => {
                btn.classList.remove('active', 'btn-primary');
                btn.classList.add('btn-outline-primary');
            });

            const activeButton = document.querySelector(`[data-filter="${type}"]`);
            activeButton.classList.add('active', 'btn-primary');
            activeButton.classList.remove('btn-outline-primary');
        }

        // Funciones de scroll
        function scrollToTop() {
            document.getElementById('log-container').scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function scrollToBottom() {
            const container = document.getElementById('log-container');
            container.scrollTo({
                top: container.scrollHeight,
                behavior: 'smooth'
            });
        }

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            // Renderizar logs iniciales
            renderLogs(sampleLogs);

            // Configurar filtros
            document.querySelectorAll('.filter-buttons .btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');
                    filterLogs(filter);
                });
            });

            // Configurar búsqueda
            document.getElementById('search-logs').addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                if (searchTerm) {
                    const filteredLogs = sampleLogs.filter(log =>
                        log.content.toLowerCase().includes(searchTerm)
                    );
                    renderLogs(filteredLogs);
                } else {
                    renderLogs(sampleLogs);
                }
            });

            // Simular actualización de tiempo
            setInterval(() => {
                const lastUpdate = document.getElementById('last-update');
                let minutes = parseInt(lastUpdate.textContent);
                minutes = (minutes % 10) + 1;
                lastUpdate.textContent = minutes;
            }, 60000);

            // Auto-scroll al final al cargar la página
            setTimeout(scrollToBottom, 300);
        });
    </script>
@endsection
