@extends('layout')

@section('titulo', 'Logs del Sistema')

@section('content')
<div class="container my-4">
    <div class="tbl-card shadow-lg">
   
        <div class="tbl-card-hero">
            <div class="tbl-hero-left">
                <a href="{{ route('Inicio') }}"
                   class="tbl-hero-btn tbl-hero-btn-glass prt-back-btn mb-2">
                    <i class="bi bi-house-door-fill"></i> Volver al Inicio
                </a>
                <div class="tbl-hero-eyebrow">
                    <i class="bi bi-terminal-fill"></i> Seguridad & Auditoría
                </div>
                <h2 class="tbl-hero-title">Logs del Sistema</h2>
                <p class="tbl-hero-sub text-white-50">
                    Monitor de actividad, eventos y errores en tiempo real.
                </p>
            </div>
            <div class="tbl-hero-controls text-end">
                <div class="ec-role-badge mb-2 d-inline-block">
                    <i class="bi bi-shield-lock-fill me-1"></i> {{ auth()->user()->getRoleNames()->first() }}
                </div>
                <div class="d-flex gap-2 justify-content-end">
                    <button onclick="location.reload()" class="tbl-hero-btn tbl-hero-btn-glass btn-sm">
                        <i class="bi bi-arrow-clockwise me-1"></i> Actualizar
                    </button>
                    <button onclick="exportLogs()" class="tbl-hero-btn tbl-hero-btn-glass btn-sm">
                        <i class="bi bi-download me-1"></i> Exportar
                    </button>
                </div>
            </div>
        </div>

        <div class="p-4 p-md-5">
            @if(empty($logs) || (count($logs) == 1 && empty($logs[0])))
                <div class="text-center py-5">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-search text-muted fs-1"></i>
                    </div>
                    <h5 class="text-muted fw-bold">No hay logs disponibles</h5>
                    <p class="text-muted small mb-4">No se encontraron registros de actividad en este momento.</p>
                    <button onclick="location.reload()" class="tbl-hero-btn tbl-hero-btn-primary px-4">
                        <i class="bi bi-arrow-clockwise me-2"></i> Recargar Monitor
                    </button>
                </div>
            @else
                <!-- Barra de Estadísticas & Filtros -->
                <div class="row g-3 align-items-center mb-4 bg-light p-3 rounded-4 border">
                    <div class="col-md-6">
                        <div class="d-flex flex-wrap gap-3">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-white text-dark border p-2 rounded-3 me-2">
                                    <i class="bi bi-list-ol me-1 text-primary"></i> Total: <strong>{{ count(array_filter($logs)) }}</strong>
                                </span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-white text-danger border p-2 rounded-3 me-2">
                                    <i class="bi bi-exclamation-circle-fill me-1"></i> <span id="errorCount">0</span> Errores
                                </span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-white text-warning border p-2 rounded-3">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> <span id="warningCount">0</span> Avisos
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-md-end align-items-center gap-2">
                            <span class="small fw-bold text-muted text-uppercase">Filtrar:</span>
                            <div class="btn-group btn-group-sm p-1 bg-white border rounded-pill shadow-sm">
                                <button class="btn filter-badge active rounded-pill px-3" data-filter="all">Todos</button>
                                <button class="btn filter-badge text-danger rounded-pill px-3" data-filter="error">Errores</button>
                                <button class="btn filter-badge text-warning rounded-pill px-3" data-filter="warning">Avisos</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contenedor de logs con scroll personalizado -->
                <div class="log-scroll-container rounded-4 border bg-dark p-3" style="max-height: 600px; overflow-y: auto;">
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

                        <div class="log-entry-modern {{ $logClass }} mb-2 p-3 rounded-3 border-start border-4" data-log-type="{{ $logClass }}">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="flex-grow-1 overflow-hidden">
                                    <code class="text-light small d-block text-break">{{ $logText }}</code>
                                </div>
                                <span class="badge bg-secondary-subtle text-secondary small ms-3">#{{ $logNumber }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-white-50 small">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ \Carbon\Carbon::now()->subMinutes($index)->diffForHumans() }}
                                </span>
                                <button class="btn btn-sm btn-link text-white-50 p-0 text-decoration-none copy-log" data-log="{{ $logText }}">
                                    <i class="bi bi-clipboard me-1"></i> Copiar
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Footer de Controles -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <div class="d-flex gap-2">
                        <button onclick="scrollToTop()" class="btn btn-light btn-sm rounded-pill px-3 border">
                            <i class="bi bi-chevron-double-up me-1"></i> Inicio
                        </button>
                        <button onclick="scrollToBottom()" class="btn btn-light btn-sm rounded-pill px-3 border">
                            <i class="bi bi-chevron-double-down me-1"></i> Final
                        </button>
                    </div>
                    <div class="text-muted small fw-bold">
                        <i class="bi bi-database-fill-check me-1"></i> Mostrando {{ count($filteredLogs) }} registros
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .ec-role-badge {
        background: rgba(255,165,0,0.15); color: #ffa500;
        padding: 0.25rem 0.75rem; border-radius: 50px; font-size: 0.7rem; font-weight: 800;
        border: 1px solid rgba(255,165,0,0.3);
    }

    /* Estilos para las entradas de Log */
    .log-entry-modern { background: rgba(255,255,255,0.03); transition: all 0.2s; }
    .log-entry-modern:hover { background: rgba(255,255,255,0.06); }

    .log-entry-modern.error { border-color: #ef4444 !important; background: rgba(239, 68, 68, 0.05); }
    .log-entry-modern.warning { border-color: #f59e0b !important; background: rgba(245, 158, 11, 0.05); }
    .log-entry-modern.info { border-color: #3b82f6 !important; background: rgba(59, 130, 246, 0.05); }
    .log-entry-modern.success { border-color: #10b981 !important; background: rgba(16, 185, 129, 0.05); }
    .log-entry-modern.default { border-color: #64748b !important; }

    .filter-badge.active { background: #145da0 !important; color: #fff !important; }

    /* Scrollbar personalizado para los logs */
    .log-scroll-container::-webkit-scrollbar { width: 8px; }
    .log-scroll-container::-webkit-scrollbar-track { background: rgba(0,0,0,0.2); border-radius: 10px; }
    .log-scroll-container::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    .log-scroll-container::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    scrollToBottom();
    countLogTypes();
    setupFilters();
    setupCopyButtons();
});

function scrollToTop() {
    const container = document.querySelector('.log-scroll-container');
    if(container) container.scrollTo({ top: 0, behavior: 'smooth' });
}

function scrollToBottom() {
    const container = document.querySelector('.log-scroll-container');
    if(container) container.scrollTo({ top: container.scrollHeight, behavior: 'smooth' });
}

function countLogTypes() {
    const errorCount = document.querySelectorAll('.log-entry-modern.error').length;
    const warningCount = document.querySelectorAll('.log-entry-modern.warning').length;

    const errEl = document.getElementById('errorCount');
    const warnEl = document.getElementById('warningCount');

    if(errEl) errEl.textContent = errorCount;
    if(warnEl) warnEl.textContent = warningCount;
}

function setupFilters() {
    const filterButtons = document.querySelectorAll('.filter-badge');
    const logEntries = document.querySelectorAll('.log-entry-modern');

    filterButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');

            filterButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            logEntries.forEach(entry => {
                if (filter === 'all') {
                    entry.style.display = 'block';
                } else {
                    entry.style.display = entry.getAttribute('data-log-type') === filter ? 'block' : 'none';
                }
            });
        });
    });
}

function setupCopyButtons() {
    document.querySelectorAll('.copy-log').forEach(button => {
        button.addEventListener('click', function() {
            const logText = this.getAttribute('data-log');
            navigator.clipboard.writeText(logText).then(() => {
                const originalHtml = this.innerHTML;
                this.innerHTML = '<i class="bi bi-check2 me-1"></i> Copiado';
                this.classList.add('text-success');
                setTimeout(() => {
                    this.innerHTML = originalHtml;
                    this.classList.remove('text-success');
                }, 2000);
            });
        });
    });
}

function exportLogs() {
    const logEntries = document.querySelectorAll('code.text-light');
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

    Swal.fire({
        icon: 'success', title: 'Exportación Lista', text: 'El archivo de logs se ha descargado.',
        toast: true, position: 'top-end', showConfirmButton: false, timer: 3000
    });
}

setInterval(() => {
    // Solo recargar si no hay filtros activos distintos a 'all'
    const activeFilter = document.querySelector('.filter-badge.active').getAttribute('data-filter');
    if (activeFilter === 'all') {
        // Podrías implementar una recarga AJAX aquí para no refrescar toda la página
    }
}, 60000);
</script>
@endsection
