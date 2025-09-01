@extends('FundacionPlantillaUsu.index')

@section('content')

<!-- CDN: Year Calendar -->
{{-- <link rel="stylesheet" href="https://unpkg.com/js-year-calendar/dist/js-year-calendar.min.css">
<script src="https://unpkg.com/js-year-calendar/dist/js-year-calendar.min.js"></script>
<script src="{{ asset('assets/js/js-year-calendar.es.js') }}"></script> --}}


<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.8/index.global.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

      <style>
        .calendar-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }

        .calendar-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stats-card {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 10px 25px rgba(238, 90, 36, 0.3);
            transition: transform 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .stats-card.success {
            background: linear-gradient(45deg, #26de81, #20bf6b);
            box-shadow: 0 10px 25px rgba(32, 191, 107, 0.3);
        }

        .stats-card.warning {
            background: linear-gradient(45deg, #fed330, #f7b731);
            box-shadow: 0 10px 25px rgba(247, 183, 49, 0.3);
        }

        .stats-card.info {
            background: linear-gradient(45deg, #45aaf2, #2d98da);
            box-shadow: 0 10px 25px rgba(45, 152, 218, 0.3);
        }

        .fc-toolbar {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .fc-button {
            background: linear-gradient(45deg, #667eea, #764ba2) !important;
            border: none !important;
            border-radius: 8px !important;
            transition: all 0.3s ease !important;
        }

        .fc-button:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4) !important;
        }

        .fc-event {
            border-radius: 8px !important;
            border: none !important;
            padding: 5px !important;
            font-weight: 500 !important;
            transition: all 0.3s ease !important;
        }

        .fc-event:hover {
            transform: scale(1.05) !important;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2) !important;
        }

        .filter-section {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .activity-item {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .activity-item:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }

        .activity-item.pending {
            border-left-color: #ff6b6b;
        }

        .activity-item.completed {
            border-left-color: #26de81;
        }

        .activity-item.warning {
            border-left-color: #fed330;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); }
            50% { box-shadow: 0 5px 20px rgba(254, 211, 48, 0.3); }
            100% { box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); }
        }

        .legend {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            font-size: 14px;
            color: white;
        }

        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 50%;
        }

        .upcoming-activities {
            max-height: 400px;
            overflow-y: auto;
        }

        .upcoming-activities::-webkit-scrollbar {
            width: 6px;
        }

        .upcoming-activities::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .upcoming-activities::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }
    </style>

    <div class="calendar-container">
        <div class="container-fluid">
            <div class="row">
                <!-- Panel lateral -->
                <div class="col-lg-3 mb-4">
                    <!-- Estadísticas -->
                    <div class="row">
                        <div class="col-6 col-lg-12">
                            <div class="stats-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Total</h6>
                                        <h3 class="mb-0">{{ $estadisticas['total'] ?? 0 }}</h3>
                                    </div>
                                    <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-12">
                            <div class="stats-card success">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Entregadas</h6>
                                        <h3 class="mb-0">{{ $estadisticas['entregadas'] ?? 0 }}</h3>
                                    </div>
                                    <i class="fas fa-check-circle fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-12">
                            <div class="stats-card warning">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Pendientes</h6>
                                        <h3 class="mb-0">{{ $estadisticas['pendientes'] ?? 0 }}</h3>
                                    </div>
                                    <i class="fas fa-clock fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-12">
                            <div class="stats-card info">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Por Vencer</h6>
                                        <h3 class="mb-0">{{ $estadisticas['proximasVencer'] ?? 0 }}</h3>
                                    </div>
                                    <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtros -->
                    <div class="filter-section">
                        <h5 class=" mb-3"><i class="fas fa-filter me-2"></i>Filtros</h5>
                        <div class="mb-3">
                            <select class="form-select" id="filtro-curso">
                                <option value="">Todos los cursos</option>
                                @foreach($cursos as $curso)
                                    <option class="text-dark" value="{{ $curso->id }}">{{ $curso->nombreCurso }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <select class="form-select" id="filtro-tipo">
                                <option value="">Todos los tipos</option>
                                <option value="Tarea">Tareas</option>
                                <option value="Examen">Exámenes</option>
                                <option value="Proyecto">Proyectos</option>
                                <option value="Quiz">Quiz</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <select class="form-select" id="filtro-estado">
                                <option value="">Todos los estados</option>
                                <option value="pendiente">Pendientes</option>
                                <option value="entregada">Entregadas</option>
                            </select>
                        </div>
                    </div>

                    <!-- Próximas actividades -->
                    <div class="calendar-card p-3">
                        <h5 class="mb-3"><i class="fas fa-list-ul me-2"></i>Próximas Actividades</h5>
                        <div class="upcoming-activities" id="proximas-actividades">
                            <!-- Se llenará dinámicamente -->
                        </div>
                    </div>
                </div>

                <!-- Calendario principal -->
                <div class="col-lg-9">
                    <div class="calendar-card p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2 class="mb-0"><i class="fas fa-calendar me-2"></i>Calendario Académico</h2>
                            <div class="btn-group">
                                <button class="btn btn-outline-primary" id="btn-hoy">Hoy</button>
                                <button class="btn btn-outline-primary" id="btn-agenda">Vista Lista</button>
                                <button class="btn btn-outline-primary" id="btn-mes">Vista Mes</button>
                            </div>
                        </div>

                        <!-- Leyenda -->
                        <div class="legend">
                            <div class="legend-item">
                                <div class="legend-color" style="background: #007bff;"></div>
                                <span class="text-dark">Tareas</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background: #dc3545;"></div>
                                <span class="text-dark">Exámenes</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background: #ffc107;"></div>
                                <span class="text-dark">Proyectos</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background: #28a745;"></div>
                                <span class="text-dark">Entregadas</span>
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
                    <h5 class="modal-title" id="modal-titulo"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modal-contenido">
                    <!-- Contenido dinámico -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <a href="#" class="btn btn-primary" id="btn-ver-actividad">Ver Actividad</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.8/index.global.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.8/locales/es.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendario');
            let eventos = @json($eventos ?? []);

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                events: eventos,
                eventClick: function(info) {
                    mostrarDetalleActividad(info.event);
                },
                eventDidMount: function(info) {
                    info.el.setAttribute('title', info.event.title + ' - ' + info.event.extendedProps.curso);
                },
                dayCellDidMount: function(info) {
                    const today = new Date();
                    if (info.date.toDateString() === today.toDateString()) {
                        info.el.style.backgroundColor = 'rgba(102, 126, 234, 0.1)';
                    }
                }
            });

            calendar.render();

            // Función para mostrar detalles de actividad
            function mostrarDetalleActividad(event) {
                // Código existente para mostrar detalles de la actividad
                document.getElementById('activityTitle').textContent = event.title;
                document.getElementById('activityCourse').textContent = event.extendedProps.nombreCurso;
                document.getElementById('activityType').textContent = event.extendedProps.tipo;
                document.getElementById('activityStatus').textContent = event.extendedProps.estado;
                document.getElementById('activityPoints').textContent = event.extendedProps.puntos;
                document.getElementById('activityDescription').textContent = event.extendedProps.descripcion;
                document.getElementById('viewActivityBtn').href = event.extendedProps.url;
                
                // Añadir horarios del curso si están disponibles
                const horariosContainer = document.getElementById('activitySchedule');
                horariosContainer.innerHTML = '';
                
                if (event.extendedProps.horarios && event.extendedProps.horarios.length > 0) {
                    const horariosList = document.createElement('ul');
                    horariosList.className = 'list-unstyled';
                    
                    event.extendedProps.horarios.forEach(horario => {
                        const item = document.createElement('li');
                        item.innerHTML = `<i class="bi bi-calendar-day me-1"></i> ${horario.dia}: ${horario.hora_inicio} - ${horario.hora_fin}`;
                        horariosList.appendChild(item);
                    });
                    
                    horariosContainer.appendChild(horariosList);
                } else {
                    horariosContainer.textContent = 'No hay horarios disponibles';
                }
                
                // Mostrar el modal
                const modal = new bootstrap.Modal(document.getElementById('activityDetailModal'));
                modal.show();
            }

            // Funcionalidad de filtros
            ['#filtro-curso', '#filtro-tipo', '#filtro-estado'].forEach(selector => {
                document.querySelector(selector).addEventListener('change', aplicarFiltros);
            });

            function aplicarFiltros() {
                const cursoSeleccionado = document.getElementById('filtro-curso').value;
                const tipoSeleccionado = document.getElementById('filtro-tipo').value;
                const estadoSeleccionado = document.getElementById('filtro-estado').value;

                const eventosFiltrados = eventos.filter(evento => {
                    let cumpleFiltros = true;

                    if (cursoSeleccionado && !evento.extendedProps.curso.includes(cursoSeleccionado)) {
                        cumpleFiltros = false;
                    }

                    if (tipoSeleccionado && evento.extendedProps.tipo !== tipoSeleccionado) {
                        cumpleFiltros = false;
                    }

                    if (estadoSeleccionado) {
                        const estadoEvento = evento.extendedProps.estado.toLowerCase();
                        if (estadoSeleccionado !== estadoEvento) {
                            cumpleFiltros = false;
                        }
                    }

                    return cumpleFiltros;
                });

                calendar.removeAllEvents();
                calendar.addEventSource(eventosFiltrados);
            }

            // Botones de vista
            document.getElementById('btn-hoy').addEventListener('click', () => calendar.today());
            document.getElementById('btn-agenda').addEventListener('click', () => calendar.changeView('listWeek'));
            document.getElementById('btn-mes').addEventListener('click', () => calendar.changeView('dayGridMonth'));

            // Cargar próximas actividades
            cargarProximasActividades();

            function cargarProximasActividades() {
                const proximasActividades = eventos
                    .filter(evento => new Date(evento.start) >= new Date())
                    .sort((a, b) => new Date(a.start) - new Date(b.start))
                    .slice(0, 5);

                const container = document.getElementById('proximas-actividades');
                container.innerHTML = '';

                proximasActividades.forEach(evento => {
                    const fechaLimite = new Date(evento.start);
                    const diasRestantes = Math.ceil((fechaLimite - new Date()) / (1000 * 60 * 60 * 24));

                    let claseEstado = 'pending';
                    if (evento.extendedProps.estado === 'Entregada') {
                        claseEstado = 'completed';
                    } else if (diasRestantes <= 1) {
                        claseEstado = 'warning';
                    }

                    const activityHtml = `
                        <div class="activity-item ${claseEstado}" onclick="window.location.href='${evento.extendedProps.url}'">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">${evento.title}</h6>
                                    <small class="text-muted">${evento.extendedProps.curso}</small>
                                    <div class="mt-1">
                                        <span class="badge bg-secondary">${evento.extendedProps.tipo}</span>
                                        <span class="badge ${evento.extendedProps.estado === 'Entregada' ? 'bg-success' : 'bg-warning'}">
                                            ${evento.extendedProps.estado}
                                        </span>
                                    </div>
                                </div>
                                <small class="text-muted">${diasRestantes > 0 ? diasRestantes + ' días' : 'Hoy'}</small>
                            </div>
                        </div>
                    `;

                    container.insertAdjacentHTML('beforeend', activityHtml);
                });

                if (proximasActividades.length === 0) {
                    container.innerHTML = '<p class="text-muted text-center">No hay actividades próximas</p>';
                }
            }
        });
    </script>

@endsection
