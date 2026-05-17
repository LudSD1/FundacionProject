@extends('estudiante.index')



@section('content')

<script>
    window.CAL_DATA = {
        eventos    : @json($eventos),
        estadisticas: @json($estadisticas),
        esDocente  : @json($esDocente ?? false),
    };
</script>

<div class="cv2-wrap">
    <div class="container-fluid cv2-container">

        <div class="cv2-stats">
            <div class="cv2-stat cv2-stat--blue">
                <i class="bi bi-clipboard2-check-fill cv2-stat-icon"></i>
                <div>
                    <div class="cv2-stat-num" id="statTotal">{{ $estadisticas['total'] }}</div>
                    <div class="cv2-stat-lbl">Total</div>
                </div>
            </div>
            <div class="cv2-stat cv2-stat--green">
                <i class="bi bi-check-circle-fill cv2-stat-icon"></i>
                <div>
                    <div class="cv2-stat-num" id="statEnt">{{ $estadisticas['entregadas'] }}</div>
                    <div class="cv2-stat-lbl">{{ $esDocente ? 'Completadas (≥80%)' : 'Completadas' }}</div>
                </div>
            </div>
            <div class="cv2-stat cv2-stat--orange">
                <i class="bi bi-hourglass-split cv2-stat-icon"></i>
                <div>
                    <div class="cv2-stat-num" id="statPen">{{ $estadisticas['pendientes'] }}</div>
                    <div class="cv2-stat-lbl">Pendientes</div>
                </div>
            </div>
            <div class="cv2-stat cv2-stat--red">
                <i class="bi bi-alarm-fill cv2-stat-icon"></i>
                <div>
                    <div class="cv2-stat-num" id="statProx">{{ $estadisticas['proximasVencer'] }}</div>
                    <div class="cv2-stat-lbl">Urgentes</div>
                </div>
            </div>
        </div>

        @if(session('warning'))
        <div class="cv2-alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span>{{ session('warning') }}</span>
        </div>
        @endif

        @if($cursos->isEmpty())
        {{-- Estado vacío --}}
        <div class="cv2-empty">
            <div class="cv2-empty-icon"><i class="bi bi-calendar-x"></i></div>
            <h4>No hay cursos asignados</h4>
            <p>No tienes cursos activos para mostrar en el calendario.</p>
            <a href="{{ route('Inicio') }}" class="cc-btn cc-btn-primary">
                <i class="bi bi-book-fill me-2"></i>Explorar Cursos
            </a>
        </div>

        @else

        <div class="cv2-layout">

            {{-- ── PANEL IZQUIERDO: Calendario ── --}}
            <div class="cv2-cal-panel">

                {{-- Header mes --}}
                <div class="cv2-cal-header">
                    <button class="cv2-nav-btn" id="cv2PrevBtn">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <div class="cv2-cal-month-wrap">
                        <h3 class="cv2-cal-month" id="cv2MonthTitle">—</h3>
                        <button class="cv2-today-btn" id="cv2TodayBtn">Hoy</button>
                    </div>
                    <button class="cv2-nav-btn" id="cv2NextBtn">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>

                {{-- Filtros rápidos --}}
                <div class="cv2-filters">
                    <button class="cv2-filter active" data-filter="all">Todas</button>
                    <button class="cv2-filter" data-filter="pendiente">
                        <span class="cv2-filter-dot cv2-dot--orange"></span>Pendientes
                    </button>
                    <button class="cv2-filter" data-filter="urgente">
                        <span class="cv2-filter-dot cv2-dot--red"></span>Urgentes
                    </button>
                    <button class="cv2-filter" data-filter="entregada">
                        <span class="cv2-filter-dot cv2-dot--green"></span>Completadas
                    </button>
                    <button class="cv2-filter" data-filter="incompleta">
                        <span class="cv2-filter-dot cv2-dot--gray"></span>Incompletas
                    </button>
                </div>

                {{-- Cabecera días de la semana --}}
                <div class="cv2-weekdays">
                    <span>Lun</span><span>Mar</span><span>Mié</span>
                    <span>Jue</span><span>Vie</span><span>Sáb</span><span>Dom</span>
                </div>

                {{-- Cuadrícula de días (generada por JS) --}}
                <div class="cv2-grid" id="cv2Grid"></div>

                {{-- Leyenda --}}
                <div class="cv2-legend">
                    <span class="cv2-legend-item">
                        <span class="cv2-dot cv2-dot--green"></span>Completada
                    </span>
                     <span class="cv2-legend-item">
                        <span class="cv2-dot cv2-dot--orange"></span>Pendiente
                    </span>
                    <span class="cv2-legend-item">
                        <span class="cv2-dot cv2-dot--red"></span>Urgente
                    </span>
                    <span class="cv2-legend-item">
                        <span class="cv2-dot cv2-dot--gray"></span>Incompleta (vencida)
                    </span>
                    <span class="cv2-legend-item">
                        <span class="cv2-dot cv2-dot--blue"></span>Hoy
                    </span>
                </div>
            </div>

            <div class="cv2-agenda-panel">

                <div class="cv2-agenda-header">
                    <div>
                        <div class="cv2-agenda-day-num" id="cv2AgendaDayNum">—</div>
                        <div class="cv2-agenda-day-lbl" id="cv2AgendaDayLbl">Selecciona un día</div>
                    </div>
                    <div class="cv2-agenda-count" id="cv2AgendaCount"></div>
                </div>

                <div class="cv2-agenda-list" id="cv2AgendaList">
                    <div class="cv2-agenda-placeholder">
                        <i class="bi bi-hand-index-thumb"></i>
                        <p>Haz clic en un día con eventos para ver el detalle</p>
                    </div>
                </div>

                <div class="cv2-upcoming-header">
                    <i class="bi bi-clock-history me-2"></i>Próximos a vencer
                </div>
                <div class="cv2-upcoming-list" id="cv2UpcomingList"></div>

            </div>
        </div>

        @endif
    </div>
</div>

<div class="modal fade" id="cv2Modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content cc-modal">
            <div class="cc-modal-header" id="cv2ModalHeader">
                <div class="cc-modal-icon" id="cv2ModalIcon">
                    <i class="bi bi-calendar-event-fill"></i>
                </div>
                <div class="min-width-0 flex-1">
                    <h5 class="cc-modal-title" id="cv2ModalTitle">—</h5>
                    <small id="cv2ModalCurso" class="opacity-75">—</small>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="cv2-modal-fields">
                    <div class="cv2-mf">
                        <div class="cv2-mf-icon"><i class="bi bi-tag-fill"></i></div>
                        <div>
                            <div class="cv2-mf-label">Tipo</div>
                            <div class="cv2-mf-val" id="cv2ModalTipo">—</div>
                        </div>
                    </div>
                    <div class="cv2-mf">
                        <div class="cv2-mf-icon"><i class="bi bi-calendar3"></i></div>
                        <div>
                            <div class="cv2-mf-label">Fecha límite</div>
                            <div class="cv2-mf-val" id="cv2ModalFecha">—</div>
                        </div>
                    </div>
                    <div class="cv2-mf">
                        <div class="cv2-mf-icon"><i class="bi bi-flag-fill"></i></div>
                        <div>
                            <div class="cv2-mf-label">Estado</div>
                            <div id="cv2ModalEstado">—</div>
                        </div>
                    </div>
                    {{-- Porcentaje de compleción (solo docente) --}}
                    <div class="cv2-mf" id="cv2ModalPorcentajeWrap" style="display:none">
                        <div class="cv2-mf-icon"><i class="bi bi-people-fill"></i></div>
                        <div class="flex-1">
                            <div class="cv2-mf-label">Compleción de estudiantes</div>
                            <div id="cv2ModalPorcentaje">—</div>
                        </div>
                    </div>
                    <div class="cv2-mf cv2-mf--full">
                        <div class="cv2-mf-icon"><i class="bi bi-file-text-fill"></i></div>
                        <div class="flex-1">
                            <div class="cv2-mf-label">Descripción</div>
                            <div class="cv2-mf-val" id="cv2ModalDesc">—</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="cc-modal-footer">
                <button type="button" class="cc-btn cc-btn-outline" data-bs-dismiss="modal">Cerrar</button>
                <a href="#" class="cc-btn cc-btn-primary" id="cv2ModalBtn" target="_blank">
                    <i class="bi bi-box-arrow-up-right me-1"></i>Ver Actividad
                </a>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    /* ════════════════════════════════════
       DATOS
    ════════════════════════════════════ */
    const EVENTOS = (window.CAL_DATA?.eventos || []).map(ev => ({
        ...ev,
        _date: ev.start ? ev.start.split('T')[0] : null,
    }));
    const ES_DOCENTE = window.CAL_DATA?.esDocente || false;

    /* ════════════════════════════════════
       ESTADO
    ════════════════════════════════════ */
    const state = {
        year    : new Date().getFullYear(),
        month   : new Date().getMonth(),
        selected: null,
        filter  : 'all',
    };

    const MESES = ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
                   'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    const DIAS_SEMANA = ['lunes','martes','miércoles','jueves','viernes','sábado','domingo'];

    /* ════════════════════════════════════
       HELPERS
    ════════════════════════════════════ */
    function diasRestantes(dateStr) {
        const hoy   = new Date(); hoy.setHours(0,0,0,0);
        const fecha = new Date(dateStr + 'T00:00:00');
        return Math.ceil((fecha - hoy) / 86400000);
    }

    /**
     * Determina el estado visual real del evento.
     * Retorna: 'done' | 'urgent' | 'overdue' | 'pending'
     */
    function estadoReal(ev) {
        const p = ev.extendedProps || {};
        if (p.completada || p.estado === 'entregada') return 'done';
        const dias = diasRestantes(ev._date);
        if (dias < 0) return 'overdue';       // vencida sin entregar
        if (dias <= 2) return 'urgent';        // ≤2 días
        return 'pending';
    }

    function tipoIcono(tipo) {
        const m = {
            'tarea':'bi-file-earmark-check','examen':'bi-clipboard2-data',
            'proyecto':'bi-folder2-open','lectura':'bi-book',
            'quiz':'bi-pencil-square','cuestionario':'bi-ui-checks',
            'presentacion':'bi-easel2',
        };
        return m[(tipo||'').toLowerCase()] || 'bi-calendar-event';
    }

    function dotColor(est) {
        return { done:'green', urgent:'red', overdue:'gray', pending:'orange' }[est] || 'orange';
    }

    function badgeHTML(ev) {
        const est = estadoReal(ev);
        const p   = ev.extendedProps || {};

        if (est === 'done') {
            if (ES_DOCENTE && p.porcentajeCompletado !== null) {
                return `<span class="cv2-event-badge cv2-badge--done">
                    <i class="bi bi-check-circle-fill me-1"></i>${p.porcentajeCompletado}% completado
                </span>`;
            }
            return `<span class="cv2-event-badge cv2-badge--done">
                <i class="bi bi-check-circle-fill me-1"></i>Completada
            </span>`;
        }
        if (est === 'overdue') {
            return `<span class="cv2-event-badge cv2-badge--overdue">
                <i class="bi bi-x-circle-fill me-1"></i>Incompleta
            </span>`;
        }
        if (est === 'urgent') {
            const dias = diasRestantes(ev._date);
            const txt = dias === 0 ? '¡Hoy!' : dias === 1 ? '¡Mañana!' : `${dias} días`;
            return `<span class="cv2-event-badge cv2-badge--urgent">
                <i class="bi bi-alarm-fill me-1"></i>${txt}
            </span>`;
        }
        // pending
        if (ES_DOCENTE && p.porcentajeCompletado !== null) {
            return `<span class="cv2-event-badge cv2-badge--pending">
                <i class="bi bi-hourglass-split me-1"></i>${p.porcentajeCompletado}%
            </span>`;
        }
        return `<span class="cv2-event-badge cv2-badge--pending">
            <i class="bi bi-hourglass-split me-1"></i>Pendiente
        </span>`;
    }

    /** Barra de progreso para docentes */
    function progressBarHTML(porcentaje) {
        if (porcentaje === null || porcentaje === undefined) return '';
        const color = porcentaje >= 80 ? '#16a34a' :
                      porcentaje >= 50 ? '#f59e0b' :
                      porcentaje >= 25 ? '#f97316' : '#dc2626';
        return `<div class="cv2-progress-wrap">
            <div class="cv2-progress-bar">
                <div class="cv2-progress-fill" style="width:${porcentaje}%;background:${color}"></div>
            </div>
            <span class="cv2-progress-text">${porcentaje}%</span>
        </div>`;
    }

    function filtrarEvento(ev) {
        const p   = ev.extendedProps || {};
        const est = estadoReal(ev);
        if (state.filter === 'all')        return true;
        if (state.filter === 'entregada')  return est === 'done';
        if (state.filter === 'pendiente')  return est === 'pending' || est === 'urgent';
        if (state.filter === 'urgente')    return est === 'urgent';
        if (state.filter === 'incompleta') return est === 'overdue';
        return true;
    }

    /* ════════════════════════════════════
       RENDER CUADRÍCULA
    ════════════════════════════════════ */
    function renderGrid() {
        const grid  = document.getElementById('cv2Grid');
        const title = document.getElementById('cv2MonthTitle');
        if (!grid || !title) return;

        title.textContent = `${MESES[state.month]} ${state.year}`;

        const hoyStr    = new Date().toISOString().split('T')[0];
        const primerDia = new Date(state.year, state.month, 1);
        let offset      = primerDia.getDay() - 1;
        if (offset < 0) offset = 6;

        const diasMes     = new Date(state.year, state.month + 1, 0).getDate();
        const diasAnterior= new Date(state.year, state.month, 0).getDate();

        // Mapa de eventos por fecha
        const eventosPorDia = {};
        EVENTOS.filter(filtrarEvento).forEach(ev => {
            if (!ev._date) return;
            if (!eventosPorDia[ev._date]) eventosPorDia[ev._date] = [];
            eventosPorDia[ev._date].push(ev);
        });

        let html = '';

        // Días del mes anterior
        for (let i = offset - 1; i >= 0; i--) {
            const d = diasAnterior - i;
            html += `<div class="cv2-day cv2-day--other-month">
                        <span class="cv2-day-num">${d}</span>
                     </div>`;
        }

        // Días del mes actual
        for (let d = 1; d <= diasMes; d++) {
            const dateStr   = `${state.year}-${String(state.month+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
            const isToday   = dateStr === hoyStr;
            const isSelected= dateStr === state.selected;
            const evsDia    = eventosPorDia[dateStr] || [];
            const hasEvs    = evsDia.length > 0;

            // Puntos (máx 4)
            const dotsHTML = evsDia.slice(0, 4).map(ev => {
                const est = estadoReal(ev);
                return `<span class="cv2-dot cv2-dot--${dotColor(est)}"></span>`;
            }).join('');

            html += `<div class="cv2-day
                        ${isToday    ? 'cv2-day--today'    : ''}
                        ${isSelected ? 'cv2-day--selected' : ''}
                        ${hasEvs     ? 'cv2-day--has-events': ''}"
                        data-date="${dateStr}">
                        <span class="cv2-day-num">${d}</span>
                        ${hasEvs ? `<div class="cv2-day-dots">${dotsHTML}</div>` : ''}
                     </div>`;
        }

        // Completar última fila
        const total  = offset + diasMes;
        const remain = (7 - (total % 7)) % 7;
        for (let d = 1; d <= remain; d++) {
            html += `<div class="cv2-day cv2-day--other-month">
                        <span class="cv2-day-num">${d}</span>
                     </div>`;
        }

        grid.innerHTML = html;

        // Bindear clicks
        grid.querySelectorAll('.cv2-day:not(.cv2-day--other-month)').forEach(el => {
            el.addEventListener('click', function () {
                state.selected = this.getAttribute('data-date');
                renderGrid();
                renderAgenda(state.selected);
            });
        });
    }

    /* ════════════════════════════════════
       RENDER AGENDA
    ════════════════════════════════════ */
    function renderAgenda(dateStr) {
        const list     = document.getElementById('cv2AgendaList');
        const dayNum   = document.getElementById('cv2AgendaDayNum');
        const dayLbl   = document.getElementById('cv2AgendaDayLbl');
        const agCount  = document.getElementById('cv2AgendaCount');
        if (!list) return;

        const fecha = new Date(dateStr + 'T12:00:00');
        if (dayNum) dayNum.textContent = fecha.getDate();
        if (dayLbl) dayLbl.textContent =
            `${DIAS_SEMANA[fecha.getDay() === 0 ? 6 : fecha.getDay()-1]}, ${MESES[fecha.getMonth()]} ${fecha.getFullYear()}`;

        const evs = EVENTOS.filter(ev => ev._date === dateStr && filtrarEvento(ev));

        if (agCount) {
            agCount.textContent = evs.length > 0
                ? `${evs.length} evento${evs.length !== 1 ? 's' : ''}`
                : '';
        }

        if (evs.length === 0) {
            list.innerHTML = `<div class="cv2-agenda-placeholder">
                <i class="bi bi-calendar2-check"></i>
                <p>Sin eventos este día</p>
            </div>`;
            return;
        }

        list.innerHTML = evs.map(ev => {
            const p   = ev.extendedProps || {};
            const est = estadoReal(ev);
            const estClass = { done:'done', urgent:'urgent', overdue:'overdue', pending:'pending' }[est];
            return `
            <div class="cv2-event-card cv2-event-card--${estClass}" data-event-title="${ev.title}">
                <div class="cv2-event-icon">
                    <i class="bi ${tipoIcono(p.tipo)}"></i>
                </div>
                <div class="cv2-event-body">
                    <div class="cv2-event-title">${ev.title}</div>
                    <div class="cv2-event-meta">
                        <span class="cv2-event-curso">
                            <i class="bi bi-book"></i> ${p.curso || '—'}
                        </span>
                        ${badgeHTML(ev)}
                    </div>
                    ${ES_DOCENTE ? progressBarHTML(p.porcentajeCompletado) : ''}
                </div>
                <i class="bi bi-chevron-right cv2-event-arrow"></i>
            </div>`;
        }).join('');

        // Click en tarjeta → modal
        list.querySelectorAll('.cv2-event-card').forEach((card, i) => {
            card.addEventListener('click', () => abrirModal(evs[i]));
        });
    }

    /* ════════════════════════════════════
       RENDER PRÓXIMOS A VENCER
    ════════════════════════════════════ */
    function renderProximos() {
        const list = document.getElementById('cv2UpcomingList');
        if (!list) return;

        const proximos = EVENTOS
            .filter(ev => {
                const est = estadoReal(ev);
                if (est === 'done') return false;
                if (!ev._date) return false;
                const d = diasRestantes(ev._date);
                return d >= 0 && d <= 14;
            })
            .sort((a,b) => a._date.localeCompare(b._date))
            .slice(0, 8);

        if (proximos.length === 0) {
            list.innerHTML = `<div class="cv2-upcoming-empty">
                <i class="bi bi-check2-all" style="font-size:1.5rem;display:block;margin-bottom:.4rem"></i>
                Sin actividades próximas a vencer
            </div>`;
            return;
        }

        list.innerHTML = proximos.map(ev => {
            const p    = ev.extendedProps || {};
            const dias = diasRestantes(ev._date);
            const fecha= new Date(ev._date + 'T12:00:00');
            const urgClass = dias <= 2 ? 'urgent' : dias <= 5 ? 'warning' : 'normal';
            const diasText = dias === 0 ? '¡Hoy!' : dias === 1 ? 'Mañana' : `${dias} días`;
            return `
            <div class="cv2-upcoming-item" data-date="${ev._date}">
                <div class="cv2-upcoming-date">
                    <div class="cv2-upcoming-date-num">${fecha.getDate()}</div>
                    <div class="cv2-upcoming-date-mon">${MESES[fecha.getMonth()].slice(0,3)}</div>
                </div>
                <div class="cv2-upcoming-divider"></div>
                <div class="cv2-upcoming-info">
                    <div class="cv2-upcoming-title">${ev.title}</div>
                    <div class="cv2-upcoming-curso">${p.curso || '—'}</div>
                    ${ES_DOCENTE && p.porcentajeCompletado !== null
                        ? `<div class="cv2-upcoming-pct">${p.totalCompletados}/${p.totalInscritos} estudiantes (${p.porcentajeCompletado}%)</div>`
                        : ''}
                </div>
                <span class="cv2-upcoming-dias cv2-upcoming-dias--${urgClass}">${diasText}</span>
            </div>`;
        }).join('');

        // Click → seleccionar día en calendario
        list.querySelectorAll('.cv2-upcoming-item').forEach((item, i) => {
            item.addEventListener('click', () => {
                const dateStr = item.getAttribute('data-date');
                const d       = new Date(dateStr + 'T12:00:00');
                state.year     = d.getFullYear();
                state.month    = d.getMonth();
                state.selected = dateStr;
                renderGrid();
                renderAgenda(dateStr);
            });
        });
    }

    /* ════════════════════════════════════
       MODAL
    ════════════════════════════════════ */
    function abrirModal(ev) {
        const p   = ev.extendedProps || {};
        const est = estadoReal(ev);

        document.getElementById('cv2ModalTitle').textContent  = ev.title;
        document.getElementById('cv2ModalCurso').textContent  = p.curso || '—';
        document.getElementById('cv2ModalTipo').textContent   = p.tipo  || '—';
        document.getElementById('cv2ModalFecha').textContent  = ev._date
            ? new Date(ev._date + 'T12:00:00').toLocaleDateString('es-ES', {day:'2-digit', month:'long', year:'numeric'})
            : '—';
        document.getElementById('cv2ModalDesc').textContent   = p.descripcion || 'Sin descripción';

        // Estado badge
        const estadoLabels = {
            done   : ['cv2-badge--done',    '<i class="bi bi-check-circle-fill me-1"></i>Completada'],
            urgent : ['cv2-badge--urgent',  '<i class="bi bi-alarm-fill me-1"></i>Urgente'],
            overdue: ['cv2-badge--overdue', '<i class="bi bi-x-circle-fill me-1"></i>Incompleta (vencida)'],
            pending: ['cv2-badge--pending', '<i class="bi bi-hourglass-split me-1"></i>Pendiente'],
        };
        const [badgeCls, badgeTxt] = estadoLabels[est];
        document.getElementById('cv2ModalEstado').innerHTML =
            `<span class="cv2-event-badge ${badgeCls}">${badgeTxt}</span>`;

        // Porcentaje de compleción (solo docentes)
        const pctWrap = document.getElementById('cv2ModalPorcentajeWrap');
        const pctEl   = document.getElementById('cv2ModalPorcentaje');
        if (ES_DOCENTE && p.porcentajeCompletado !== null) {
            pctWrap.style.display = '';
            const color = p.porcentajeCompletado >= 80 ? '#16a34a' :
                          p.porcentajeCompletado >= 50 ? '#f59e0b' : '#dc2626';
            pctEl.innerHTML = `
                <div style="display:flex;align-items:center;gap:.75rem;margin-top:.35rem">
                    <div class="cv2-progress-bar" style="flex:1">
                        <div class="cv2-progress-fill" style="width:${p.porcentajeCompletado}%;background:${color}"></div>
                    </div>
                    <strong style="color:${color}">${p.porcentajeCompletado}%</strong>
                </div>
                <small style="color:#6b7280;margin-top:.25rem;display:block">
                    ${p.totalCompletados} de ${p.totalInscritos} estudiantes completaron
                </small>`;
        } else {
            pctWrap.style.display = 'none';
        }

        // Header color dinámico
        const header = document.getElementById('cv2ModalHeader');
        const icon   = document.getElementById('cv2ModalIcon');
        const gradients = {
            done   : 'linear-gradient(135deg,#15803d,#16a34a)',
            urgent : 'linear-gradient(135deg,#b91c1c,#dc2626)',
            overdue: 'linear-gradient(135deg,#4b5563,#6b7280)',
            pending: 'linear-gradient(135deg,#0d2244,#145da0)',
        };
        header.style.background = gradients[est];
        icon.innerHTML = {
            done   : '<i class="bi bi-check-circle-fill"></i>',
            urgent : '<i class="bi bi-alarm-fill"></i>',
            overdue: '<i class="bi bi-x-circle-fill"></i>',
            pending: '<i class="bi bi-calendar-event-fill"></i>',
        }[est];

        // Botón acción
        const btn = document.getElementById('cv2ModalBtn');
        btn.href  = p.url || '#';

        if (ES_DOCENTE) {
            btn.innerHTML = p.esCuestionario
                ? '<i class="bi bi-ui-checks me-1"></i>Ver Cuestionario'
                : '<i class="bi bi-list-check me-1"></i>Ver Entregas';
            btn.className = 'cc-btn cc-btn-primary';
        } else if (p.esCuestionario) {
            btn.innerHTML = est === 'done'
                ? '<i class="bi bi-eye-fill me-1"></i>Ver Resultados'
                : '<i class="bi bi-pencil-square me-1"></i>Resolver Cuestionario';
            btn.className = est === 'done' ? 'cc-btn cc-btn-success' : 'cc-btn cc-btn-primary';
        } else {
            btn.innerHTML = est === 'done'
                ? '<i class="bi bi-eye-fill me-1"></i>Ver Entrega'
                : '<i class="bi bi-box-arrow-up-right me-1"></i>Ver Actividad';
            btn.className = est === 'done' ? 'cc-btn cc-btn-success' : 'cc-btn cc-btn-primary';
        }

        bootstrap.Modal.getOrCreateInstance(
            document.getElementById('cv2Modal')
        ).show();
    }

    /* ════════════════════════════════════
       FILTROS
    ════════════════════════════════════ */
    function bindFiltros() {
        document.querySelectorAll('.cv2-filter').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.cv2-filter').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                state.filter = this.getAttribute('data-filter');
                renderGrid();
                if (state.selected) renderAgenda(state.selected);
                renderProximos();
            });
        });
    }


    function bindNav() {
        document.getElementById('cv2PrevBtn')?.addEventListener('click', () => {
            if (state.month === 0) { state.month = 11; state.year--; }
            else state.month--;
            state.selected = null;
            renderGrid();
            renderAgenda_placeholder();
        });
        document.getElementById('cv2NextBtn')?.addEventListener('click', () => {
            if (state.month === 11) { state.month = 0; state.year++; }
            else state.month++;
            state.selected = null;
            renderGrid();
            renderAgenda_placeholder();
        });
        document.getElementById('cv2TodayBtn')?.addEventListener('click', () => {
            const hoy      = new Date();
            state.year     = hoy.getFullYear();
            state.month    = hoy.getMonth();
            state.selected = hoy.toISOString().split('T')[0];
            renderGrid();
            renderAgenda(state.selected);
        });
    }

    function renderAgenda_placeholder() {
        const list   = document.getElementById('cv2AgendaList');
        const dayNum = document.getElementById('cv2AgendaDayNum');
        const dayLbl = document.getElementById('cv2AgendaDayLbl');
        const count  = document.getElementById('cv2AgendaCount');
        if (dayNum) dayNum.textContent = '—';
        if (dayLbl) dayLbl.textContent = 'Selecciona un día';
        if (count)  count.textContent  = '';
        if (list)   list.innerHTML     = `<div class="cv2-agenda-placeholder">
            <i class="bi bi-hand-index-thumb"></i>
            <p>Haz clic en un día con eventos para ver el detalle</p>
        </div>`;
    }

    function syncTop() {
        const header  = document.getElementById('header');
        const authNav = document.getElementById('authNavbar');
        const panel   = document.querySelector('.cv2-cal-panel');
        if (!header || !panel) return;
        const hH  = header.getBoundingClientRect().height;
        const anH = authNav ? authNav.getBoundingClientRect().height : 0;
        panel.style.top = (hH + anH + 16) + 'px';
    }


    document.addEventListener('DOMContentLoaded', function () {
        state.selected = new Date().toISOString().split('T')[0];

        renderGrid();
        renderAgenda(state.selected);
        renderProximos();
        bindFiltros();
        bindNav();

        syncTop();
        window.addEventListener('resize', syncTop);
        window.addEventListener('scroll', syncTop, { passive:true });
    });

})();
</script>

@endsection
