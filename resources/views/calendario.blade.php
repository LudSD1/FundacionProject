@extends('FundacionPlantillaUsu.index')

@section('content')

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

      <style>
        :root {
            --color-primary: #1a4789;
            --color-secondary: #39a6cb;
            --color-accent1: #63becf;
            --color-accent2: #055c9d;
            --color-accent3: #2197bd;
            --color-success: #28a745;
            --color-warning: #ffc107;
            --color-danger: #dc3545;
            --color-info: #17a2b8;
            
            --gradient-primary: linear-gradient(135deg, #1a4789 0%, #055c9d 100%);
            --gradient-secondary: linear-gradient(135deg, #39a6cb 0%, #63becf 100%);
            
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.12);
            --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.15);
            
            --border-radius: 12px;
            --border-radius-sm: 8px;
        }
        
        .calendario-container {
            background: #f8fafc;
            min-height: 100vh;
        }
        
        .card-modern {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
            background: white;
        }
        
        .card-header-modern {
            background: var(--gradient-primary);
            color: white;
            border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
            padding: 1.5rem;
        }
        
        .stats-card {
            background: white;
            border-radius: var(--border-radius-sm);
            padding: 1.25rem;
            box-shadow: var(--shadow-sm);
            border-left: 4px solid;
            transition: transform 0.2s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .stats-card.total { border-left-color: var(--color-primary); }
        .stats-card.entregadas { border-left-color: var(--color-success); }
        .stats-card.pendientes { border-left-color: var(--color-warning); }
        .stats-card.proximas { border-left-color: var(--color-danger); }
        
        .fc-toolbar {
            padding: 1rem 1.5rem;
            background: white;
            border-bottom: 1px solid #e9ecef;
            margin: 0 !important;
        }
        
        .fc .fc-toolbar-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: white;
        }
        
        .fc .fc-button {
            background: var(--color-primary);
            border: none;
            border-radius: var(--border-radius-sm);
            font-weight: 500;
            padding: 0.5rem 1rem;
        }
        
        .fc .fc-button:hover {
            background: var(--color-accent2);
        }
        
        .fc .fc-button-primary:not(:disabled).fc-button-active {
            background: var(--color-accent2);
        }
        
        .fc .fc-daygrid-day-number {
            color: var(--color-primary);
            font-weight: 600;
        }
        
        .fc .fc-day-today {
            background: rgba(57, 166, 203, 0.1) !important;
        }
        
        .evento-popover {
            max-width: 300px;
        }
        
        .filter-badge {
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .filter-badge.active {
            box-shadow: 0 0 0 2px var(--color-primary);
        }
        
        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
        }
        
        .empty-state i {
            font-size: 4rem;
            color: var(--color-secondary);
            opacity: 0.5;
            margin-bottom: 1rem;
        }
    </style>

     <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="">
                <div class="card-modern">
                    <div class="card-header-modern d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Calendario Académico
                            </h4>
                            <small class="opacity-75">Gestiona tus actividades y fechas importantes</small>
                        </div>
                        {{-- <div>
                            <a href="{{ route('Inicio') }}" class="btn btn-light me-2">
                                <i class="fas fa-home me-1"></i>
                                Inicio
                            </a>
                            <button onclick="window.print()" class="btn btn-outline-light">
                                <i class="fas fa-print me-1"></i>
                                Imprimir
                            </button>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>

        @if(session('warning'))
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-warning d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-3 fa-2x"></i>
                        <div>
                            <h5 class="alert-heading mb-1">Información importante</h5>
                            {{ session('warning') }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Estadísticas -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card total">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ $estadisticas['total'] }}</h3>
                            <small class="text-muted">Total Actividades</small>
                        </div>
                        <i class="fas fa-tasks fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card entregadas">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ $estadisticas['entregadas'] }}</h3>
                            <small class="text-muted">Entregadas</small>
                        </div>
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card pendientes">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ $estadisticas['pendientes'] }}</h3>
                            <small class="text-muted">Pendientes</small>
                        </div>
                        <i class="fas fa-clock fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card proximas">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ $estadisticas['proximasVencer'] }}</h3>
                            <small class="text-muted">Próximas a Vencer</small>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros y Calendario -->
        <div class="row">
            <div class="col-12">
                <div class="card-modern">
                    <div class="card-body">
                        @if($cursos->isEmpty())
                            <div class="empty-state">
                                <i class="fas fa-calendar-times"></i>
                                <h4 class="text-muted">No hay cursos asignados</h4>
                                <p class="text-muted mb-4">No tienes cursos activos para mostrar en el calendario.</p>
                                <a href="{{ route('Inicio') }}" class="btn btn-primary">
                                    <i class="fas fa-book me-2"></i>
                                    Explorar Cursos
                                </a>
                            </div>
                        @else
                            <!-- Filtros -->
                            <div class="row mb-4">
                                <div class="col-md-8">
                                    <div class="d-flex flex-wrap align-items-center gap-2">
                                        <span class="fw-bold text-muted">Filtrar por:</span>
                                        <span class="badge bg-primary filter-badge active" data-filter="all">
                                            <i class="fas fa-layer-group me-1"></i>Todas
                                        </span>
                                        <span class="badge bg-success filter-badge" data-filter="entregada">
                                            <i class="fas fa-check me-1"></i>Entregadas
                                        </span>
                                        <span class="badge bg-warning filter-badge" data-filter="pendiente">
                                            <i class="fas fa-clock me-1"></i>Pendientes
                                        </span>
                                        <span class="badge bg-danger filter-badge" data-filter="urgente">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Urgentes
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="btn-group">
                                        <button class="btn btn-outline-primary" onclick="exportarCalendario()">
                                            <i class="fas fa-download me-1"></i>Exportar
                                        </button>
                                        <button class="btn btn-outline-primary" onclick="syncCalendario()">
                                            <i class="fas fa-sync me-1"></i>Sincronizar
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Calendario -->
                            <div id="calendar"></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para detalles del evento -->
    <div class="modal fade" id="eventoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventoModalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <strong><i class="fas fa-book me-2"></i>Curso:</strong>
                            <p id="eventoCurso" class="mb-2"></p>
                        </div>
                        <div class="col-6">
                            <strong><i class="fas fa-tag me-2"></i>Tipo:</strong>
                            <p id="eventoTipo" class="mb-2"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <strong><i class="fas fa-flag me-2"></i>Estado:</strong>
                            <p id="eventoEstado" class="mb-2"></p>
                        </div>
                        <div class="col-6">
                            <strong><i class="fas fa-calendar me-2"></i>Fecha:</strong>
                            <p id="eventoFecha" class="mb-2"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <strong><i class="fas fa-align-left me-2"></i>Descripción:</strong>
                            <p id="eventoDescripcion" class="mb-0"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="eventoAccionBtn">
                        <i class="fas fa-external-link-alt me-1"></i>
                        Ver Actividad
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.js"></script>

     <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Configuración del calendario
            var calendarEl = document.getElementById('calendar');
            var eventos = @json($eventos);
            
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                },
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    day: 'Día',
                    list: 'Lista'
                },
                events: eventos,
                eventClick: function(info) {
                    mostrarDetallesEvento(info.event);
                },
                eventDidMount: function(info) {
                    // Tooltip para eventos
                    if (info.event.extendedProps.descripcion) {
                        info.el.setAttribute('title', info.event.extendedProps.descripcion);
                    }
                    
                    // Icono según tipo
                    const icon = obtenerIconoTipo(info.event.extendedProps.tipo);
                    if (icon) {
                        const titleEl = info.el.querySelector('.fc-event-title');
                        if (titleEl) {
                            titleEl.innerHTML = `${icon} ${titleEl.textContent}`;
                        }
                    }
                },
                height: 'auto',
                navLinks: true,
                dayMaxEvents: true,
                businessHours: {
                    daysOfWeek: [1, 2, 3, 4, 5],
                    startTime: '08:00',
                    endTime: '18:00'
                }
            });

            calendar.render();

            // Configurar filtros
            configurarFiltros(calendar, eventos);

            // Configurar eventos globales
            configurarEventosGlobales(calendar);
        });

        function mostrarDetallesEvento(evento) {
            const props = evento.extendedProps;
            
            document.getElementById('eventoModalTitle').textContent = evento.title;
            document.getElementById('eventoCurso').textContent = props.curso;
            document.getElementById('eventoTipo').textContent = props.tipo;
            document.getElementById('eventoEstado').innerHTML = obtenerBadgeEstado(props.estado, props.urgente);
            document.getElementById('eventoFecha').textContent = evento.start.toLocaleDateString('es-ES');
            document.getElementById('eventoDescripcion').textContent = props.descripcion || 'Sin descripción';
            
            // Configurar botón de acción
            const btnAccion = document.getElementById('eventoAccionBtn');
            if (props.entregada) {
                btnAccion.innerHTML = '<i class="fas fa-eye me-1"></i>Ver Entrega';
                btnAccion.className = 'btn btn-success';
            } else {
                btnAccion.innerHTML = '<i class="fas fa-external-link-alt me-1"></i>Ver Actividad';
                btnAccion.className = 'btn btn-primary';
            }
            
            const modal = new bootstrap.Modal(document.getElementById('eventoModal'));
            modal.show();
        }

        function obtenerBadgeEstado(estado, urgente) {
            const clases = {
                'entregada': 'bg-success',
                'pendiente': urgente ? 'bg-danger' : 'bg-warning'
            };
            
            const textos = {
                'entregada': 'Entregada',
                'pendiente': urgente ? 'Urgente' : 'Pendiente'
            };
            
            return `<span class="badge ${clases[estado]}">${textos[estado]}</span>`;
        }

        function obtenerIconoTipo(tipo) {
            const iconos = {
                'tarea': '📝',
                'examen': '📋',
                'proyecto': '📂',
                'lectura': '📖',
                'quiz': '✏️',
                'presentacion': '📊'
            };
            return iconos[tipo?.toLowerCase()] || '📅';
        }

        function configurarFiltros(calendar, eventosOriginales) {
            const filtros = document.querySelectorAll('.filter-badge');
            
            filtros.forEach(filtro => {
                filtro.addEventListener('click', function() {
                    const tipoFiltro = this.getAttribute('data-filter');
                    
                    // Actualizar badges activos
                    filtros.forEach(f => f.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Aplicar filtro
                    let eventosFiltrados = eventosOriginales;
                    
                    if (tipoFiltro !== 'all') {
                        eventosFiltrados = eventosOriginales.filter(evento => {
                            const props = evento.extendedProps;
                            
                            switch(tipoFiltro) {
                                case 'entregada':
                                    return props.entregada;
                                case 'pendiente':
                                    return !props.entregada;
                                case 'urgente':
                                    return props.urgente && !props.entregada;
                                default:
                                    return true;
                            }
                        });
                    }
                    
                    calendar.removeAllEvents();
                    calendar.addEventSource(eventosFiltrados);
                });
            });
        }

        function configurarEventosGlobales(calendar) {
            // Exportar calendario
            window.exportarCalendario = function() {
                calendar.getData().then(function(data) {
                    const blob = new Blob([JSON.stringify(data, null, 2)], { 
                        type: 'application/json' 
                    });
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `calendario-${new Date().toISOString().split('T')[0]}.json`;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    URL.revokeObjectURL(url);
                });
            };

            // Sincronizar calendario
            window.syncCalendario = function() {
                const boton = event.target;
                const originalHTML = boton.innerHTML;
                
                boton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Sincronizando...';
                boton.disabled = true;
                
                setTimeout(() => {
                    calendar.refetchEvents();
                    boton.innerHTML = originalHTML;
                    boton.disabled = false;
                    
                    // Mostrar notificación
                    mostrarNotificacion('Calendario sincronizado correctamente', 'success');
                }, 1000);
            };
        }

        function mostrarNotificacion(mensaje, tipo) {
            // Implementar notificación toast
            alert(mensaje); // Reemplazar con tu sistema de notificaciones
        }
    </script>
 

@endsection
