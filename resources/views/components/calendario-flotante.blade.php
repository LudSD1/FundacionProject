<!-- Botón flotante principal -->
<div id="floating-calendar-toggle" class="floating-calendar-btn {{ $posicion }}">
    <div class="floating-btn-content">
        <i class="fas fa-calendar-alt"></i>
        <span class="badge-notification" id="urgent-count" style="display: none;"></span>
    </div>
    <div class="floating-btn-text">Calendario</div>
</div>

<!-- Panel flotante del calendario -->
<div id="floating-calendar-panel" class="floating-calendar-panel {{ $posicion }}">
    <!-- Header del panel -->
    <div class="floating-panel-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-calendar-alt me-2"></i>
                Mi Calendario
            </h5>
            <div class="header-actions">
                <button class="btn btn-sm btn-icon" id="minimize-calendar" title="Minimizar">
                    <i class="fas fa-minus"></i>
                </button>
                <button class="btn btn-sm btn-icon" id="close-calendar" title="Cerrar">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Tabs de navegación -->
    <div class="floating-panel-tabs">
        <button class="tab-btn active" data-tab="activities">
            <i class="fas fa-tasks me-1"></i> Actividades
        </button>
        <button class="tab-btn" data-tab="calendar">
            <i class="fas fa-calendar me-1"></i> Calendario
        </button>
    </div>

    <!-- Contenido del panel -->
    <div class="floating-panel-content">
        <!-- Tab de Actividades -->
        <div class="tab-content active" id="tab-activities">
            <div class="activities-summary mb-3">
                <div class="row g-2">
                    <div class="col-6">
                        <div class="summary-card urgent">
                            <div class="summary-number" id="summary-urgent">0</div>
                            <div class="summary-label">Urgentes</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="summary-card pending">
                            <div class="summary-number" id="summary-pending">0</div>
                            <div class="summary-label">Pendientes</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="activities-list" id="floating-activities-list">
                <!-- Se llena dinámicamente -->
            </div>
        </div>

        <!-- Tab de Calendario -->
        <div class="tab-content" id="tab-calendar">
            <div id="floating-mini-calendar"></div>
        </div>
    </div>
</div>

<!-- Overlay -->
<div id="floating-calendar-overlay" class="floating-calendar-overlay"></div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<style>
    :root {
        --color-primary: #1a4789;
        --color-secondary: #39a6cb;
        --color-accent1: #63becf;
        --color-accent2: #055c9d;
        --color-accent3: #2197bd;
        --orange-accent: #ffa500;
        --gradient-primary: linear-gradient(135deg, #1a4789 0%, #055c9d 100%);
        --gradient-secondary: linear-gradient(135deg, #39a6cb 0%, #63becf 100%);
    }

    /* Botón flotante principal */
    .floating-calendar-btn {
        position: fixed;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: var(--gradient-primary);
        color: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 20px rgba(26, 71, 137, 0.4);
        transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        z-index: 1000;
        border: 3px solid white;
    }

    .floating-calendar-btn:hover {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 6px 30px rgba(26, 71, 137, 0.6);
    }

    .floating-calendar-btn.bottom-right {
        bottom: 30px;
        right: 30px;
    }

    .floating-calendar-btn.bottom-left {
        bottom: 30px;
        left: 30px;
    }

    .floating-calendar-btn.top-right {
        top: 30px;
        right: 30px;
    }

    .floating-calendar-btn.top-left {
        top: 30px;
        left: 30px;
    }

    .floating-btn-content {
        position: relative;
        font-size: 24px;
    }

    .floating-btn-text {
        font-size: 10px;
        font-weight: 600;
        margin-top: 2px;
    }

    .badge-notification {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: bold;
        border: 2px solid white;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    /* Panel flotante */
    .floating-calendar-panel {
        position: fixed;
        width: 400px;
        height: 600px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 50px rgba(0, 0, 0, 0.3);
        z-index: 1001;
        display: none;
        flex-direction: column;
        overflow: hidden;
        animation: slideIn 0.3s ease-out;
    }

    .floating-calendar-panel.bottom-right {
        bottom: 100px;
        right: 30px;
    }

    .floating-calendar-panel.bottom-left {
        bottom: 100px;
        left: 30px;
    }

    .floating-calendar-panel.top-right {
        top: 100px;
        right: 30px;
    }

    .floating-calendar-panel.top-left {
        top: 100px;
        left: 30px;
    }

    .floating-calendar-panel.show {
        display: flex;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(20px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* Header del panel */
    .floating-panel-header {
        background: var(--gradient-primary);
        color: white;
        padding: 1rem;
        border-radius: 20px 20px 0 0;
    }

    .header-actions .btn-icon {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-left: 0.5rem;
        transition: all 0.3s;
    }

    .header-actions .btn-icon:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg);
    }

    /* Tabs */
    .floating-panel-tabs {
        display: flex;
        background: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
    }

    .tab-btn {
        flex: 1;
        padding: 0.75rem;
        border: none;
        background: transparent;
        color: #6c757d;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
    }

    .tab-btn.active {
        color: var(--color-primary);
        background: white;
    }

    .tab-btn.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--gradient-primary);
    }

    .tab-btn:hover:not(.active) {
        background: rgba(26, 71, 137, 0.05);
    }

    /* Contenido del panel */
    .floating-panel-content {
        flex: 1;
        overflow-y: auto;
        padding: 1rem;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Resumen de actividades */
    .activities-summary .summary-card {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
        border-left: 4px solid;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s;
    }

    .summary-card:hover {
        transform: translateY(-2px);
    }

    .summary-card.urgent {
        border-left-color: #dc3545;
        background: linear-gradient(135deg, #fff5f5 0%, #ffffff 100%);
    }

    .summary-card.pending {
        border-left-color: var(--orange-accent);
        background: linear-gradient(135deg, #fffcf5 0%, #ffffff 100%);
    }

    .summary-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--color-primary);
    }

    .summary-label {
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: 600;
    }

    /* Lista de actividades */
    .activities-list {
        max-height: 420px;
        overflow-y: auto;
    }

    .activity-item-floating {
        background: white;
        border-radius: 10px;
        padding: 0.75rem;
        margin-bottom: 0.75rem;
        border-left: 4px solid;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    }

    .activity-item-floating:hover {
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(26, 71, 137, 0.2);
    }

    .activity-item-floating.urgent {
        border-left-color: #dc3545;
    }

    .activity-item-floating.warning {
        border-left-color: var(--orange-accent);
    }

    .activity-item-floating.completed {
        border-left-color: #28a745;
        opacity: 0.7;
    }

    .activity-title {
        font-weight: 600;
        color: var(--color-primary);
        margin-bottom: 0.25rem;
        font-size: 0.9rem;
    }

    .activity-course {
        font-size: 0.75rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }

    .activity-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .activity-badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-weight: 600;
    }

    .activity-time {
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Calendario mini */
    #floating-mini-calendar {
        height: 450px;
    }

    /* Overlay */
    .floating-calendar-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.3);
        z-index: 999;
        backdrop-filter: blur(2px);
    }

    .floating-calendar-overlay.show {
        display: block;
    }

    /* Scrollbar personalizado */
    .floating-panel-content::-webkit-scrollbar,
    .activities-list::-webkit-scrollbar {
        width: 6px;
    }

    .floating-panel-content::-webkit-scrollbar-track,
    .activities-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .floating-panel-content::-webkit-scrollbar-thumb,
    .activities-list::-webkit-scrollbar-thumb {
        background: var(--gradient-primary);
        border-radius: 10px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .floating-calendar-panel {
            width: calc(100vw - 40px);
            max-width: 350px;
            height: 500px;
        }

        .floating-calendar-btn {
            width: 55px;
            height: 55px;
        }

        .floating-btn-content {
            font-size: 20px;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales/es.global.min.js"></script>
<script>
(function() {
    const eventos = @json($eventos);

    const COLOR_SCHEME = {
        Tarea: '#1a4789',
        Examen: '#dc3545',
        Proyecto: '#ffa500',
        Quiz: '#17a2b8',
        default: '#39a6cb'
    };

    // Elementos del DOM
    const toggleBtn = document.getElementById('floating-calendar-toggle');
    const panel = document.getElementById('floating-calendar-panel');
    const overlay = document.getElementById('floating-calendar-overlay');
    const closeBtn = document.getElementById('close-calendar');
    const minimizeBtn = document.getElementById('minimize-calendar');
    const tabBtns = document.querySelectorAll('.tab-btn');

    let miniCalendar = null;

    // Toggle panel
    function togglePanel() {
        panel.classList.toggle('show');
        overlay.classList.toggle('show');

        if (panel.classList.contains('show') && !miniCalendar) {
            initMiniCalendar();
        }
    }

    toggleBtn.addEventListener('click', togglePanel);
    closeBtn.addEventListener('click', togglePanel);
    overlay.addEventListener('click', togglePanel);

    // Tabs
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const tab = this.dataset.tab;

            tabBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });

            document.getElementById(`tab-${tab}`).classList.add('active');
        });
    });

    // Cargar actividades
    function cargarActividades() {
        const container = document.getElementById('floating-activities-list');
        const ahora = new Date();

        const actividadesFuturas = eventos
            .filter(e => new Date(e.start) >= ahora && e.extendedProps.estado !== 'Entregada')
            .sort((a, b) => new Date(a.start) - new Date(b.start));

        let urgentes = 0;
        let pendientes = actividadesFuturas.length;

        container.innerHTML = '';

        if (actividadesFuturas.length === 0) {
            container.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fas fa-check-circle fa-3x mb-2" style="color: var(--color-accent1)"></i>
                    <p>¡Todo al día!</p>
                </div>
            `;
        } else {
            actividadesFuturas.slice(0, 10).forEach(evento => {
                const dias = Math.ceil((new Date(evento.start) - ahora) / (1000 * 60 * 60 * 24));
                let variant = '';
                let timeClass = '';

                if (dias <= 1) {
                    variant = 'urgent';
                    timeClass = 'text-danger';
                    urgentes++;
                } else if (dias <= 3) {
                    variant = 'warning';
                    timeClass = 'text-warning';
                }

                container.innerHTML += `
                    <div class="activity-item-floating ${variant}" onclick="window.location.href='${evento.url}'">
                        <div class="activity-title">${evento.title}</div>
                        <div class="activity-course">${evento.extendedProps.curso || ''}</div>
                        <div class="activity-footer">
                            <span class="activity-badge" style="background-color: ${COLOR_SCHEME[evento.extendedProps.tipo] || COLOR_SCHEME.default}; color: white;">
                                ${evento.extendedProps.tipo || 'Actividad'}
                            </span>
                            <span class="activity-time ${timeClass}">
                                <i class="fas fa-clock me-1"></i>
                                ${dias > 0 ? dias + 'd' : 'Hoy'}
                            </span>
                        </div>
                    </div>
                `;
            });
        }

        // Actualizar resúmenes
        document.getElementById('summary-urgent').textContent = urgentes;
        document.getElementById('summary-pending').textContent = pendientes;

        // Actualizar badge de notificación
        const badge = document.getElementById('urgent-count');
        if (urgentes > 0) {
            badge.textContent = urgentes;
            badge.style.display = 'flex';
        } else {
            badge.style.display = 'none';
        }
    }

    // Inicializar mini calendario
    function initMiniCalendar() {
        const calendarEl = document.getElementById('floating-mini-calendar');

        miniCalendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            height: 450,
            headerToolbar: {
                left: 'prev,next',
                center: 'title',
                right: 'today'
            },
            buttonText: {
                today: 'Hoy'
            },
            events: eventos.map(evento => ({
                ...evento,
                color: COLOR_SCHEME[evento.extendedProps?.tipo] || COLOR_SCHEME.default
            })),
            eventClick: function(info) {
                window.location.href = info.event.url;
            },
            eventDidMount: function(info) {
                info.el.setAttribute('title', info.event.title);
            }
        });

        miniCalendar.render();
    }

    // Inicializar
    cargarActividades();

    // Actualizar cada 5 minutos
    setInterval(cargarActividades, 300000);
})();
</script>
@endpush
