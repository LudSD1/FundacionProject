@extends('FundacionPlantillaUsu.index')

@section('content')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.8/index.global.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">



    <div class="calendar-container">
        <div class="container">
            <div class="row">
                <!-- Panel lateral -->
                <div class="col-lg-3 mb-4">
                    <!-- Estadísticas -->
                    <div class="row">
                        <div class="col-6 col-lg-12">
                            <div class="stats-card fade-in-up">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Total</h6>
                                        <h3 class="mb-0">24</h3>
                                    </div>
                                    <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-12">
                            <div class="stats-card success fade-in-up">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Entregadas</h6>
                                        <h3 class="mb-0">18</h3>
                                    </div>
                                    <i class="fas fa-check-circle fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-12">
                            <div class="stats-card warning fade-in-up">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Pendientes</h6>
                                        <h3 class="mb-0">6</h3>
                                    </div>
                                    <i class="fas fa-clock fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-12">
                            <div class="stats-card info fade-in-up">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Por Vencer</h6>
                                        <h3 class="mb-0">3</h3>
                                    </div>
                                    <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtros -->
                    <div class="filter-section fade-in-up">
                        <h5 class="mb-3"><i class="fas fa-filter me-2"></i>Filtros</h5>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Curso</label>
                            <select class="form-select" id="filtro-curso">
                                <option value="">Todos los cursos</option>
                                <option value="1">Matemáticas Avanzadas</option>
                                <option value="2">Programación Web</option>
                                <option value="3">Base de Datos</option>
                                <option value="4">Inteligencia Artificial</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Tipo de Actividad</label>
                            <select class="form-select" id="filtro-tipo">
                                <option value="">Todos los tipos</option>
                                <option value="Tarea">Tareas</option>
                                <option value="Examen">Exámenes</option>
                                <option value="Proyecto">Proyectos</option>
                                <option value="Quiz">Quiz</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Estado</label>
                            <select class="form-select" id="filtro-estado">
                                <option value="">Todos los estados</option>
                                <option value="pendiente">Pendientes</option>
                                <option value="entregada">Entregadas</option>
                            </select>
                        </div>
                        <button class="btn btn-primary w-100">
                            <i class="fas fa-sync-alt me-2"></i>Aplicar Filtros
                        </button>
                    </div>

                    <!-- Próximas actividades -->
                    <div class="calendar-card p-3 fade-in-up">
                        <h5 class="mb-3"><i class="fas fa-list-ul me-2"></i>Próximas Actividades</h5>
                        <div class="upcoming-activities" id="proximas-actividades">
                            <!-- Actividades de ejemplo -->
                            <div class="activity-item urgent" data-activity="1">
                                <div class="activity-date">Hoy, 15 Jun</div>
                                <div class="activity-title">Examen Final - Matemáticas</div>
                                <div class="activity-course">Matemáticas Avanzadas</div>
                            </div>
                            <div class="activity-item warning" data-activity="2">
                                <div class="activity-date">Mañana, 16 Jun</div>
                                <div class="activity-title">Entrega Proyecto Final</div>
                                <div class="activity-course">Programación Web</div>
                            </div>
                            <div class="activity-item" data-activity="3">
                                <div class="activity-date">18 Jun</div>
                                <div class="activity-title">Quiz Semanal</div>
                                <div class="activity-course">Base de Datos</div>
                            </div>
                            <div class="activity-item success" data-activity="4">
                                <div class="activity-date">20 Jun</div>
                                <div class="activity-title">Tarea Entregada</div>
                                <div class="activity-course">Inteligencia Artificial</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Calendario principal -->
                <div class="col-lg-9">
                    <div class="calendar-card p-4 fade-in-up">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h2 class="mb-1"><i class="fas fa-calendar me-2"></i>Calendario Académico</h2>
                                <p class="text-muted mb-0">Gestión de actividades y fechas importantes</p>
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-outline-primary" id="btn-hoy">
                                    <i class="fas fa-calendar-day me-1"></i>Hoy
                                </button>
                                <button class="btn btn-outline-primary" id="btn-agenda">
                                    <i class="fas fa-list me-1"></i>Vista Lista
                                </button>
                                <button class="btn btn-primary" id="btn-mes">
                                    <i class="fas fa-calendar-alt me-1"></i>Vista Mes
                                </button>
                            </div>
                        </div>

                        <!-- Leyenda -->
                        <div class="legend">
                            <div class="legend-item">
                                <div class="legend-color" style="background: var(--color-primary);"></div>
                                <span>Tareas</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background: var(--color-error);"></div>
                                <span>Exámenes</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background: var(--color-warning);"></div>
                                <span>Proyectos</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background: var(--color-info);"></div>
                                <span>Quiz</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background: var(--color-success);"></div>
                                <span>Entregadas</span>
                            </div>
                        </div>

                        <!-- Calendario -->
                        <div id="calendario"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para detalles de actividad -->
    <div class="modal fade" id="modal-actividad" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-titulo">Detalles de la Actividad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modal-contenido">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-book me-2"></i>Curso</h6>
                            <p id="modal-curso">Matemáticas Avanzadas</p>

                            <h6><i class="fas fa-tasks me-2"></i>Tipo</h6>
                            <p id="modal-tipo">Examen</p>

                            <h6><i class="fas fa-calendar me-2"></i>Fecha de Entrega</h6>
                            <p id="modal-fecha">15 de Junio, 2023</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-info-circle me-2"></i>Estado</h6>
                            <p><span class="badge bg-warning" id="modal-estado">Pendiente</span></p>

                            <h6><i class="fas fa-clock me-2"></i>Prioridad</h6>
                            <p id="modal-prioridad">Alta</p>

                            <h6><i class="fas fa-file-alt me-2"></i>Descripción</h6>
                            <p id="modal-descripcion">Examen final que cubre todos los temas del semestre.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <a href="#" class="btn btn-primary" id="btn-ver-actividad">
                        <i class="fas fa-external-link-alt me-1"></i>Ver Actividad Completa
                    </a>
                </div>
            </div>
        </div>



        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.8/index.global.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.8/locales/es.global.min.js"></script>

        <script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuración de colores basada en tu paleta
    const COLOR_SCHEME = {
        primary: '#1a4789',
        secondary: '#39a6cb',
        accent1: '#63becf',
        accent2: '#055c9d',
        accent3: '#2197bd',
        accent4: '#2f89a8',
        accent5: '#145da0',
        accent6: '#2a81c2',
        orange: '#ffa500',
        success: '#28a745',
        warning: '#ffc107',
        error: '#dc3545',
        info: '#17a2b8'
    };

    const EVENT_COLORS = {
        Tarea: COLOR_SCHEME.primary,
        Examen: COLOR_SCHEME.error,
        Proyecto: COLOR_SCHEME.warning,
        Quiz: COLOR_SCHEME.info,
        Entregada: COLOR_SCHEME.success,
        default: COLOR_SCHEME.secondary
    };

    // Elementos del DOM
    const calendarEl = document.getElementById('calendario');
    let eventos = @json($eventos ?? []);
    let calendar;

    // Inicializar calendario
    function inicializarCalendario() {
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            events: eventos.map(evento => ({
                ...evento,
                color: EVENT_COLORS[evento.extendedProps?.tipo] || EVENT_COLORS.default,
                textColor: '#ffffff',
                borderColor: EVENT_COLORS[evento.extendedProps?.tipo] || EVENT_COLORS.default
            })),
            eventClick: function(info) {
                mostrarDetalleActividad(info.event);
            },
            eventDidMount: function(info) {
                // Tooltip mejorado
                const tooltip = `${info.event.title}\nCurso: ${info.event.extendedProps.curso}\nTipo: ${info.event.extendedProps.tipo}`;
                info.el.setAttribute('data-bs-toggle', 'tooltip');
                info.el.setAttribute('title', tooltip);
                info.el.setAttribute('data-bs-placement', 'top');

                // Añadir icono según el tipo de actividad
                const icono = obtenerIconoTipoActividad(info.event.extendedProps.tipo);
                if (icono) {
                    const iconElement = document.createElement('i');
                    iconElement.className = `${icono} me-1`;
                    info.el.querySelector('.fc-event-title').insertBefore(iconElement, info.el.querySelector('.fc-event-title').firstChild);
                }
            },
            dayCellDidMount: function(info) {
                const today = new Date();
                if (info.date.toDateString() === today.toDateString()) {
                    info.el.style.background = `linear-gradient(135deg, ${COLOR_SCHEME.accent1}15 0%, ${COLOR_SCHEME.accent3}15 100%)`;
                    info.el.style.border = `2px solid ${COLOR_SCHEME.accent3}`;
                }

                // Resaltar fines de semana
                if (info.date.getDay() === 0 || info.date.getDay() === 6) {
                    info.el.style.backgroundColor = '#f8f9fa';
                }
            },
            viewDidMount: function() {
                // Inicializar tooltips después de renderizar el calendario
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }
        });

        calendar.render();
    }

    // Función para obtener icono según tipo de actividad
    function obtenerIconoTipoActividad(tipo) {
        const iconos = {
            'Tarea': 'fas fa-tasks',
            'Examen': 'fas fa-file-alt',
            'Proyecto': 'fas fa-project-diagram',
            'Quiz': 'fas fa-question-circle',
            'default': 'fas fa-calendar-check'
        };
        return iconos[tipo] || iconos.default;
    }

    // Función para mostrar detalles de actividad (mejorada)
    function mostrarDetalleActividad(event) {
        const props = event.extendedProps;

        // Actualizar contenido del modal
        document.getElementById('activityTitle').textContent = event.title;
        document.getElementById('activityCourse').textContent = props.nombreCurso || props.curso;
        document.getElementById('activityType').textContent = props.tipo;
        document.getElementById('activityStatus').textContent = props.estado;
        document.getElementById('activityPoints').textContent = props.puntos || 'N/A';
        document.getElementById('activityDescription').textContent = props.descripcion || 'Sin descripción disponible';
        document.getElementById('viewActivityBtn').href = props.url || '#';

        // Estilizar el estado
        const statusBadge = document.getElementById('activityStatus');
        statusBadge.className = `badge ${props.estado === 'Entregada' ? 'bg-success' : 'bg-warning'}`;

        // Añadir horarios del curso
        const horariosContainer = document.getElementById('activitySchedule');
        horariosContainer.innerHTML = '';

        if (props.horarios && props.horarios.length > 0) {
            const horariosList = document.createElement('div');
            horariosList.className = 'list-group list-group-flush';

            props.horarios.forEach(horario => {
                const item = document.createElement('div');
                item.className = 'list-group-item d-flex justify-content-between align-items-center';
                item.innerHTML = `
                    <div>
                        <i class="fas fa-calendar-day me-2" style="color: ${COLOR_SCHEME.primary}"></i>
                        <strong>${horario.dia}:</strong> ${horario.hora_inicio} - ${horario.hora_fin}
                    </div>
                    <span class="badge bg-primary">Activo</span>
                `;
                horariosList.appendChild(item);
            });

            horariosContainer.appendChild(horariosList);
        } else {
            horariosContainer.innerHTML = `
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No hay horarios disponibles para este curso
                </div>
            `;
        }

        // Añadir información adicional si está disponible
        const infoAdicional = document.getElementById('activityAdditionalInfo');
        if (infoAdicional) {
            infoAdicional.innerHTML = `
                <div class="row mt-3">
                    <div class="col-6">
                        <small class="text-muted">Fecha de entrega:</small>
                        <div class="fw-bold">${formatearFecha(event.start)}</div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Prioridad:</small>
                        <div>
                            <span class="badge ${obtenerClasePrioridad(props.prioridad)}">
                                ${props.prioridad || 'Media'}
                            </span>
                        </div>
                    </div>
                </div>
            `;
        }

        // Mostrar el modal
        const modal = new bootstrap.Modal(document.getElementById('activityDetailModal'));
        modal.show();
    }

    // Función para formatear fecha
    function formatearFecha(fecha) {
        return new Date(fecha).toLocaleDateString('es-ES', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }

    // Función para obtener clase de prioridad
    function obtenerClasePrioridad(prioridad) {
        const clases = {
            'Alta': 'bg-danger',
            'Media': 'bg-warning',
            'Baja': 'bg-success',
            'default': 'bg-secondary'
        };
        return clases[prioridad] || clases.default;
    }

    // Sistema de filtros mejorado
    function inicializarFiltros() {
        const filtros = ['#filtro-curso', '#filtro-tipo', '#filtro-estado'];

        filtros.forEach(selector => {
            const elemento = document.querySelector(selector);
            if (elemento) {
                elemento.addEventListener('change', aplicarFiltros);
            }
        });

        // Botón para limpiar filtros
        const btnLimpiarFiltros = document.getElementById('btn-limpiar-filtros');
        if (btnLimpiarFiltros) {
            btnLimpiarFiltros.addEventListener('click', limpiarFiltros);
        }
    }

    function aplicarFiltros() {
        const cursoSeleccionado = document.getElementById('filtro-curso').value;
        const tipoSeleccionado = document.getElementById('filtro-tipo').value;
        const estadoSeleccionado = document.getElementById('filtro-estado').value;

        const eventosFiltrados = eventos.filter(evento => {
            let cumpleFiltros = true;

            if (cursoSeleccionado && evento.extendedProps.curso_id != cursoSeleccionado) {
                cumpleFiltros = false;
            }

            if (tipoSeleccionado && evento.extendedProps.tipo !== tipoSeleccionado) {
                cumpleFiltros = false;
            }

            if (estadoSeleccionado) {
                const estadoEvento = evento.extendedProps.estado?.toLowerCase();
                if (estadoSeleccionado !== estadoEvento) {
                    cumpleFiltros = false;
                }
            }

            return cumpleFiltros;
        });

        calendar.removeAllEvents();
        if (eventosFiltrados.length > 0) {
            calendar.addEventSource(eventosFiltrados.map(evento => ({
                ...evento,
                color: EVENT_COLORS[evento.extendedProps?.tipo] || EVENT_COLORS.default
            })));
        }

        // Actualizar estadísticas
        actualizarEstadisticas(eventosFiltrados);

        // Mostrar mensaje si no hay resultados
        mostrarMensajeSinResultados(eventosFiltrados.length === 0);
    }

    function limpiarFiltros() {
        document.getElementById('filtro-curso').value = '';
        document.getElementById('filtro-tipo').value = '';
        document.getElementById('filtro-estado').value = '';

        aplicarFiltros();
    }

    function mostrarMensajeSinResultados(sinResultados) {
        let mensajeExistente = document.getElementById('mensaje-sin-resultados');

        if (sinResultados && !mensajeExistente) {
            mensajeExistente = document.createElement('div');
            mensajeExistente.id = 'mensaje-sin-resultados';
            mensajeExistente.className = 'alert alert-info mt-3';
            mensajeExistente.innerHTML = `
                <i class="fas fa-info-circle me-2"></i>
                No se encontraron actividades con los filtros aplicados.
                <button class="btn btn-sm btn-outline-info ms-2" onclick="limpiarFiltros()">
                    Limpiar filtros
                </button>
            `;
            calendarEl.parentNode.insertBefore(mensajeExistente, calendarEl.nextSibling);
        } else if (!sinResultados && mensajeExistente) {
            mensajeExistente.remove();
        }
    }

    // Sistema de vistas
    function inicializarVistas() {
        document.getElementById('btn-hoy').addEventListener('click', () => {
            calendar.today();
            resaltarBotonActivo('btn-hoy');
        });

        document.getElementById('btn-agenda').addEventListener('click', () => {
            calendar.changeView('listWeek');
            resaltarBotonActivo('btn-agenda');
        });

        document.getElementById('btn-mes').addEventListener('click', () => {
            calendar.changeView('dayGridMonth');
            resaltarBotonActivo('btn-mes');
        });
    }

    function resaltarBotonActivo(botonId) {
        // Remover clase activa de todos los botones
        ['btn-hoy', 'btn-agenda', 'btn-mes'].forEach(id => {
            const boton = document.getElementById(id);
            if (boton) {
                boton.classList.remove('btn-primary');
                boton.classList.add('btn-outline-primary');
            }
        });

        // Añadir clase activa al botón clickeado
        const botonActivo = document.getElementById(botonId);
        if (botonActivo) {
            botonActivo.classList.remove('btn-outline-primary');
            botonActivo.classList.add('btn-primary');
        }
    }

    // Sistema de próximas actividades mejorado
    function cargarProximasActividades() {
        const ahora = new Date();
        const dosSemanasDespues = new Date(ahora.getTime() + 14 * 24 * 60 * 60 * 1000);

        const proximasActividades = eventos
            .filter(evento => {
                const fechaEvento = new Date(evento.start);
                return fechaEvento >= ahora && fechaEvento <= dosSemanasDespues;
            })
            .sort((a, b) => new Date(a.start) - new Date(b.start))
            .slice(0, 5);

        const container = document.getElementById('proximas-actividades');
        if (!container) return;

        container.innerHTML = '';

        if (proximasActividades.length === 0) {
            container.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fas fa-calendar-times fa-2x mb-2"></i>
                    <p class="mb-0">No hay actividades próximas</p>
                </div>
            `;
            return;
        }

        proximasActividades.forEach(evento => {
            const fechaLimite = new Date(evento.start);
            const diasRestantes = Math.ceil((fechaLimite - ahora) / (1000 * 60 * 60 * 24));

            const claseEstado = obtenerClaseEstadoActividad(evento.extendedProps.estado, diasRestantes);
            const iconoTipo = obtenerIconoTipoActividad(evento.extendedProps.tipo);

            const activityHtml = `
                <div class="activity-item ${claseEstado}" onclick="window.location.href='${evento.extendedProps.url || '#'}'" style="cursor: pointer;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-1">
                                <i class="${iconoTipo} me-2" style="color: ${EVENT_COLORS[evento.extendedProps.tipo] || EVENT_COLORS.default}"></i>
                                <h6 class="mb-0">${evento.title}</h6>
                            </div>
                            <small class="text-muted d-block">${evento.extendedProps.curso}</small>
                            <div class="mt-2">
                                <span class="badge" style="background-color: ${EVENT_COLORS[evento.extendedProps.tipo] || EVENT_COLORS.default}">
                                    ${evento.extendedProps.tipo}
                                </span>
                                <span class="badge ${evento.extendedProps.estado === 'Entregada' ? 'bg-success' : 'bg-warning'}">
                                    ${evento.extendedProps.estado}
                                </span>
                            </div>
                        </div>
                        <div class="text-end">
                            <small class="d-block fw-bold ${diasRestantes <= 1 ? 'text-danger' : 'text-muted'}">
                                ${diasRestantes > 0 ? `${diasRestantes} día${diasRestantes !== 1 ? 's' : ''}` : 'Hoy'}
                            </small>
                            <small class="text-muted">
                                ${formatearFechaCorta(evento.start)}
                            </small>
                        </div>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', activityHtml);
        });
    }

    function obtenerClaseEstadoActividad(estado, diasRestantes) {
        if (estado === 'Entregada') return 'completed';
        if (diasRestantes <= 1) return 'urgent';
        if (diasRestantes <= 3) return 'warning';
        return 'pending';
    }

    function formatearFechaCorta(fecha) {
        return new Date(fecha).toLocaleDateString('es-ES', {
            day: 'numeric',
            month: 'short'
        });
    }

    // Actualizar estadísticas
    function actualizarEstadisticas(eventosFiltrados = eventos) {
        const total = eventosFiltrados.length;
        const entregadas = eventosFiltrados.filter(e => e.extendedProps.estado === 'Entregada').length;
        const pendientes = eventosFiltrados.filter(e => e.extendedProps.estado !== 'Entregada').length;

        const ahora = new Date();
        const proximasVencer = eventosFiltrados.filter(e => {
            const fechaEvento = new Date(e.start);
            const diasRestantes = Math.ceil((fechaEvento - ahora) / (1000 * 60 * 60 * 24));
            return e.extendedProps.estado !== 'Entregada' && diasRestantes <= 3 && diasRestantes >= 0;
        }).length;

        // Actualizar elementos del DOM si existen
        const actualizarEstadistica = (selector, valor) => {
            const elemento = document.querySelector(selector);
            if (elemento) elemento.textContent = valor;
        };

        actualizarEstadistica('[data-estadistica="total"]', total);
        actualizarEstadistica('[data-estadistica="entregadas"]', entregadas);
        actualizarEstadistica('[data-estadistica="pendientes"]', pendientes);
        actualizarEstadistica('[data-estadistica="proximasVencer"]', proximasVencer);
    }

    // Inicialización completa
    function inicializarAplicacion() {
        inicializarCalendario();
        inicializarFiltros();
        inicializarVistas();
        cargarProximasActividades();
        actualizarEstadisticas();

        // Resaltar botón de vista mes por defecto
        resaltarBotonActivo('btn-mes');
    }

    // Iniciar la aplicación
    inicializarAplicacion();

    // Hacer funciones disponibles globalmente si es necesario
    window.limpiarFiltros = limpiarFiltros;
});
</script>
    @endsection
