@extends('layout')

@section('titulo', 'Logs del Sistema')

@section('content')
<style>

.logs-container .card {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-md);
}

.logs-container .card-header {
    background: var(--gradient-primary);
    color: white;
    border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
    padding: 1.25rem 1.5rem;
}

.logs-container .btn-light {
    background: white;
    border-color: white;
    border-radius: var(--border-radius-sm);
    color: var(--color-primary);
    font-weight: 600;
}

.logs-container .btn-outline-light {
    border-color: white;
    color: white;
    border-radius: var(--border-radius-sm);
    font-weight: 600;
}

.logs-container .btn-outline-light:hover {
    background: white;
    color: var(--color-primary);
}

.logs-container .alert-info {
    background: var(--color-info);
    color: white;
    border: none;
    border-radius: var(--border-radius-sm);
    padding: 1.5rem;
}

.logs-container .log-container {
    border: 1px solid #e9ecef;
    border-radius: var(--border-radius);
    background: #f8f9fa;
    max-height: 600px;
    overflow-y: auto;
    padding: 1rem;
}

.logs-container .log-entry {
    border-left: 4px solid;
    border-radius: var(--border-radius-sm);
    padding: 1rem;
    margin-bottom: 0.75rem;
    background: white;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-sm);
}

.logs-container .log-entry:hover {
    transform: translateX(4px);
    box-shadow: var(--shadow-md);
}

.logs-container .log-entry.error {
    border-left-color: var(--color-danger);
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
}

.logs-container .log-entry.warning {
    border-left-color: var(--color-warning);
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    color: #856404;
}

.logs-container .log-entry.info {
    border-left-color: var(--color-info);
    background: linear-gradient(135deg, #d1ecf1 0%, #b6e3ec 100%);
    color: #0c5460;
}

.logs-container .log-entry.success {
    border-left-color: var(--color-success);
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
}

.logs-container .log-entry.default {
    border-left-color: var(--color-secondary);
    background: white;
}

.logs-container .log-content {
    white-space: pre-wrap;
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    font-size: 0.85rem;
    line-height: 1.4;
    margin: 0;
}

.logs-container .log-index {
    background: var(--color-primary);
    color: white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: bold;
    flex-shrink: 0;
}

.logs-container .stats-bar {
    background: var(--gradient-secondary);
    color: white;
    border-radius: var(--border-radius-sm);
    padding: 0.75rem 1rem;
    margin-bottom: 1rem;
}

.logs-container .stat-item {
    display: flex;
    align-items: center;
    margin-right: 1.5rem;
}

.logs-container .stat-badge {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    padding: 0.25rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 600;
}

.logs-container .btn-group-custom .btn {
    border-radius: var(--border-radius-sm);
    margin: 0 0.25rem;
}

.logs-container .filter-badge {
    cursor: pointer;
    transition: all 0.2s ease;
}

.logs-container .filter-badge:hover {
    transform: scale(1.05);
}

.logs-container .filter-badge.active {
    box-shadow: 0 0 0 2px white;
}

.logs-container .empty-state {
    padding: 3rem 2rem;
    text-align: center;
}

.logs-container .empty-state i {
    font-size: 4rem;
    opacity: 0.5;
    margin-bottom: 1rem;
    color: var(--color-secondary);
}

/* Scrollbar personalizado */
.logs-container .log-container::-webkit-scrollbar {
    width: 8px;
}

.logs-container .log-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.logs-container .log-container::-webkit-scrollbar-thumb {
    background: var(--color-accent1);
    border-radius: 4px;
}

.logs-container .log-container::-webkit-scrollbar-thumb:hover {
    background: var(--color-secondary);
}

/* Responsive */
@media (max-width: 768px) {
    .logs-container .card-header .d-flex {
        flex-direction: column;
        gap: 1rem;
    }

    .logs-container .stats-bar {
        flex-direction: column;
        gap: 0.5rem;
    }

    .logs-container .stat-item {
        margin-right: 0;
        justify-content: space-between;
    }

    .logs-container .log-entry {
        padding: 0.75rem;
    }
}
</style>

<div class="container-fluid py-4 logs-container">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <div class="d-flex align-items-center mb-2 mb-md-0">
                        <i class="fas fa-file-alt fa-2x me-3"></i>
                        <div>
                            <h4 class="mb-0 fw-bold text-white">Logs del Sistema</h4>
                            <small class="opacity-75">Monitor de actividad y errores</small>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('Inicio') }}" class="btn btn-light">
                            <i class="fas fa-home me-2"></i>
                            Inicio
                        </a>
                        <button onclick="location.reload()" class="btn btn-outline-light">
                            <i class="fas fa-sync-alt me-2"></i>
                            Actualizar
                        </button>
                        <button onclick="exportLogs()" class="btn btn-outline-light">
                            <i class="fas fa-download me-2"></i>
                            Exportar
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    @if(empty($logs) || (count($logs) == 1 && empty($logs[0])))
                        <div class="empty-state">
                            <i class="fas fa-file-search"></i>
                            <h5 class="text-muted">No hay logs disponibles</h5>
                            <p class="text-muted mb-3">No se encontraron registros de logs en este momento.</p>
                            <button onclick="location.reload()" class="btn btn-primary">
                                <i class="fas fa-sync-alt me-2"></i>
                                Recargar
                            </button>
                        </div>
                    @else
                        <!-- Barra de estadísticas -->
                        <div class="stats-bar d-flex flex-wrap align-items-center justify-content-between">
                            <div class="d-flex flex-wrap align-items-center">
                                <div class="stat-item">
                                    <i class="fas fa-list-ol me-2"></i>
                                    <span>Total: <strong>{{ count(array_filter($logs)) }}</strong> entradas</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-badge error-badge">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        <span id="errorCount">0</span> errores
                                    </span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-badge warning-badge">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        <span id="warningCount">0</span> advertencias
                                    </span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <small class="me-2">Filtrar:</small>
                                <div class="btn-group-custom">
                                    <span class="stat-badge filter-badge active" data-filter="all">
                                        Todos
                                    </span>
                                    <span class="stat-badge filter-badge error-badge" data-filter="error">
                                        Errores
                                    </span>
                                    <span class="stat-badge filter-badge warning-badge" data-filter="warning">
                                        Advertencias
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Contenedor de logs -->
                        <div class="log-container">
                            @php
                                $filteredLogs = array_filter($logs);
                                $totalLogs = count($filteredLogs);
                            @endphp

                            @foreach($filteredLogs as $index => $log)
                                @php
                                    $logText = trim($log);
                                    $logClass = 'default';
                                    $logLower = strtolower($logText);

                                    if (str_contains($logLower, 'error') || str_contains($logLower, 'exception')) {
                                        $logClass = 'error';
                                    } elseif (str_contains($logLower, 'warning')) {
                                        $logClass = 'warning';
                                    } elseif (str_contains($logLower, 'info')) {
                                        $logClass = 'info';
                                    } elseif (str_contains($logLower, 'success') || str_contains($logLower, 'completed')) {
                                        $logClass = 'success';
                                    }

                                    $logNumber = $totalLogs - $index;
                                @endphp

                                <div class="log-entry {{ $logClass }}" data-log-type="{{ $logClass }}">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1 me-3">
                                            <code class="log-content">{{ $logText }}</code>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="log-index">{{ $logNumber }}</span>
                                        </div>
                                    </div>
                                    <div class="mt-2 d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            <span class="log-timestamp">{{ \Carbon\Carbon::now()->subMinutes($index)->diffForHumans() }}</span>
                                        </small>
                                        <button class="btn btn-sm btn-outline-secondary copy-log" data-log="{{ $logText }}">
                                            <i class="fas fa-copy me-1"></i>
                                            Copiar
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Controles de navegación -->
                        <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="d-flex gap-2">
                                <button onclick="scrollToTop()" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-up me-2"></i>
                                    Inicio
                                </button>
                                <button onclick="scrollToBottom()" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-down me-2"></i>
                                    Final
                                </button>
                                <button onclick="clearFilters()" class="btn btn-outline-secondary">
                                    <i class="fas fa-filter me-2"></i>
                                    Limpiar filtros
                                </button>
                            </div>
                            <div class="text-muted">
                                <small>
                                    <i class="fas fa-database me-1"></i>
                                    Mostrando {{ count($filteredLogs) }} registros
                                </small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-scroll al final
    scrollToBottom();

    // Contar tipos de logs
    countLogTypes();

    // Configurar filtros
    setupFilters();

    // Configurar botones de copiar
    setupCopyButtons();
});

function scrollToTop() {
    const container = document.querySelector('.log-container');
    container.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

function scrollToBottom() {
    const container = document.querySelector('.log-container');
    container.scrollTo({
        top: container.scrollHeight,
        behavior: 'smooth'
    });
}

function countLogTypes() {
    const errorCount = document.querySelectorAll('.log-entry.error').length;
    const warningCount = document.querySelectorAll('.log-entry.warning').length;

    document.getElementById('errorCount').textContent = errorCount;
    document.getElementById('warningCount').textContent = warningCount;
}

function setupFilters() {
    const filterBadges = document.querySelectorAll('.filter-badge');
    const logEntries = document.querySelectorAll('.log-entry');

    filterBadges.forEach(badge => {
        badge.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');

            // Actualizar badges activos
            filterBadges.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // Aplicar filtro
            logEntries.forEach(entry => {
                if (filter === 'all') {
                    entry.style.display = 'block';
                } else {
                    if (entry.getAttribute('data-log-type') === filter) {
                        entry.style.display = 'block';
                    } else {
                        entry.style.display = 'none';
                    }
                }
            });

            // Recontar después de filtrar
            setTimeout(countLogTypes, 100);
        });
    });
}

function clearFilters() {
    const allBadge = document.querySelector('.filter-badge[data-filter="all"]');
    if (allBadge) {
        allBadge.click();
    }
}

function setupCopyButtons() {
    const copyButtons = document.querySelectorAll('.copy-log');

    copyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const logText = this.getAttribute('data-log');

            navigator.clipboard.writeText(logText).then(() => {
                // Feedback visual
                const originalHtml = this.innerHTML;
                this.innerHTML = '<i class="fas fa-check me-1"></i>Copiado!';
                this.classList.remove('btn-outline-secondary');
                this.classList.add('btn-success');

                setTimeout(() => {
                    this.innerHTML = originalHtml;
                    this.classList.remove('btn-success');
                    this.classList.add('btn-outline-secondary');
                }, 2000);
            }).catch(err => {
                console.error('Error al copiar: ', err);
                alert('Error al copiar el log');
            });
        });
    });
}

function exportLogs() {
    const logEntries = document.querySelectorAll('.log-content');
    let logText = '=== LOGS DEL SISTEMA ===\n';
    logText += 'Generado: ' + new Date().toLocaleString() + '\n';
    logText += 'Total de entradas: ' + logEntries.length + '\n\n';

    logEntries.forEach((entry, index) => {
        logText += `[${logEntries.length - index}] ${entry.textContent}\n\n`;
    });

    const blob = new Blob([logText], { type: 'text/plain' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `system-logs-${new Date().toISOString().split('T')[0]}.txt`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}

// Auto-refresh cada 30 segundos (opcional)
setInterval(() => {
    const refreshBtn = document.querySelector('button[onclick="location.reload()"]');
    if (refreshBtn) {
        refreshBtn.innerHTML = '<i class="fas fa-sync-alt me-2"></i>Actualizando...';
        setTimeout(() => {
            location.reload();
        }, 1000);
    }
}, 30000);
</script>
@endsection
