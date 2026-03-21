@extends('layout')

@section('titulo')
    Ranking de Cuestionarios
@endsection

@section('content')

    <div class="adm-panel">
        <!-- Header del Panel -->
        <div class="info-card-header mb-0">
            <div class="d-flex align-items-center gap-3">
                <div class="info-card-icon primary">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div>
                    <h4 class="mb-1">Resultados del Cuestionario</h4>
                    <p class="text-muted mb-0 small">{{ $cuestionario->titulo ?? 'Análisis de desempeño' }}</p>
                </div>
            </div>

            <div class="header-actions">
                <a href="{{ route('Curso', ['curso' => $cuestionarios->actividad->subtema->tema->curso->codigoCurso]) }}"
                    class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>Volver al curso
                </a>
            </div>
        </div>

        <!-- Tabs principales -->
        <div class="adm-tabs-header">
            <ul class="nav adm-tabs-nav" id="mainTabs" role="tablist">
                @if (Auth::user()->hasRole('Estudiante'))
                    <li class="nav-item" role="presentation">
                        <button class="adm-tab active" id="estudiante-tab" data-bs-toggle="tab" data-bs-target="#estudiante"
                            type="button" role="tab">
                            <i class="fas fa-user-graduate me-2"></i>Mi Progreso
                        </button>
                    </li>
                @endif
                @if (Auth::user()->hasRole('Docente'))
                    <li class="nav-item" role="presentation">
                        <button class="adm-tab {{ Auth::user()->hasRole('Estudiante') ? '' : 'active' }}" id="docente-tab"
                            data-bs-toggle="tab" data-bs-target="#docente" type="button" role="tab">
                            <i class="fas fa-chalkboard-teacher me-2"></i>Análisis Docente
                        </button>
                    </li>
                @endif
            </ul>
        </div>

        <div class="adm-tabs-body">
            <div class="tab-content" id="mainTabsContent">
                <!-- Vista Estudiante -->
                @if (Auth::user()->hasRole('Estudiante'))
                    <div class="tab-pane fade show active" id="estudiante" role="tabpanel" aria-labelledby="estudiante-tab">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0 fw-bold text-primary">
                                <i class="fas fa-trophy me-2 text-warning"></i>Mi Desempeño
                            </h5>
                            <span class="badge bg-primary rounded-pill px-3">
                                {{ $mejoresIntentos->count() }} intento{{ $mejoresIntentos->count() !== 1 ? 's' : '' }}
                            </span>
                        </div>

                        @if ($mejoresIntentos->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle" id="mejoresIntentosTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0">#</th>
                                            <th class="border-0">Cuestionario</th>
                                            <th class="border-0">Calificación</th>
                                            <th class="border-0">Estado</th>
                                            <th class="border-0">Fecha</th>
                                            @hasrole('Docente')
                                                <th class="border-0">Acciones</th>
                                            @endhasrole
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($mejoresIntentos as $intento)
                                            <tr>
                                                <td class="fw-bold text-muted">{{ $loop->iteration }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="info-card-icon primary me-2" style="width:30px; height:30px; font-size:0.8rem;">
                                                            <i class="fas fa-file-alt"></i>
                                                        </div>
                                                        <span class="fw-semibold">{{ $intento->cuestionario->actividad->titulo }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    @php
                                                        $score = $intento->nota ?? 0;
                                                        $badgeClass = $score >= 90 ? 'bg-success' : ($score >= 70 ? 'bg-primary' : ($score >= 50 ? 'bg-warning text-dark' : 'bg-danger'));
                                                    @endphp
                                                    <span class="badge {{ $badgeClass }} px-3 py-2">
                                                        {{ $score }}%
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($intento->aprobado)
                                                        <span class="text-success fw-bold">
                                                            <i class="fas fa-check-circle me-1"></i>Aprobado
                                                        </span>
                                                    @else
                                                        <span class="text-warning fw-bold">
                                                            <i class="fas fa-clock me-1"></i>En progreso
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-muted small">
                                                    {{ $intento->finalizado_en ? $intento->finalizado_en->format('d/m/Y H:i') : 'En curso' }}
                                                </td>
                                                @hasrole('Docente')
                                                    <td>
                                                        <a href="{{ route('cuestionarios.revisarIntento', [encrypt($intento->cuestionario->id), encrypt($intento->id)]) }}"
                                                            class="btn btn-outline-primary btn-sm rounded-pill">
                                                            <i class="fas fa-eye me-1"></i>Ver
                                                        </a>
                                                    </td>
                                                @endhasrole
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="info-card-icon orange mx-auto mb-3" style="width:60px; height:60px; font-size:2rem;">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <h5 class="text-muted">No hay intentos registrados</h5>
                                <p class="text-muted mb-4">Aún no has realizado intentos en este cuestionario.</p>
                                <a href="{{ route('curso.actividad', [encrypt($cuestionarios->actividad->subtema->tema->curso_id), encrypt($cuestionarios->actividad_id)]) }}"
                                    class="btn btn-primary rounded-pill px-4">
                                    <i class="fas fa-play me-2"></i>Comenzar Cuestionario
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Vista Docente -->
                @if (Auth::user()->hasRole('Docente'))
                    <div class="tab-pane fade {{ Auth::user()->hasRole('Estudiante') ? '' : 'show active' }}" id="docente"
                        role="tabpanel" aria-labelledby="docente-tab">

                        <!-- Subtabs internas con estilo dashboard -->
                        <div class="adm-tabs-header mt-0 mb-4 bg-light rounded">
                            <ul class="nav adm-tabs-nav" id="docenteTabs" role="tablist">
                                <li class="nav-item">
                                    <button class="adm-tab active" id="estadisticas-tab" data-bs-toggle="tab"
                                        data-bs-target="#estadisticas" type="button" role="tab">
                                        <i class="fas fa-chart-pie me-2"></i>Estadísticas
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="adm-tab" id="grafico-tab" data-bs-toggle="tab" data-bs-target="#grafico"
                                        type="button" role="tab">
                                        <i class="fas fa-chart-line me-2"></i>Gráficos
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="adm-tab" id="resumen-tab" data-bs-toggle="tab" data-bs-target="#resumen"
                                        type="button" role="tab">
                                        <i class="fas fa-users me-2"></i>Estudiantes
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="adm-tab" id="ranking-tab" data-bs-toggle="tab" data-bs-target="#ranking"
                                        type="button" role="tab">
                                        <i class="fas fa-trophy me-2"></i>Ranking
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content" id="docenteTabsContent">
                            <!-- Estadísticas -->
                            <div class="tab-pane fade show active" id="estadisticas" role="tabpanel"
                                aria-labelledby="estadisticas-tab">
                                @if ($cuestionario->intentos && $cuestionario->intentos->count() > 0)
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-3">
                                            <div class="st-card st-card--blue">
                                                <div class="st-card-body">
                                                    <div>
                                                        <div class="st-label">Promedio</div>
                                                        <div class="st-num">{{ round($cuestionario->intentos->avg('nota'), 1) }}</div>
                                                    </div>
                                                    <div class="st-icon st-icon--blue">
                                                        <i class="fas fa-calculator"></i>
                                                    </div>
                                                </div>
                                                <div class="st-bar st-bar--blue"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="st-card st-card--green">
                                                <div class="st-card-body">
                                                    <div>
                                                        <div class="st-label">Nota Máxima</div>
                                                        <div class="st-num">{{ $cuestionario->intentos->max('nota') }}</div>
                                                    </div>
                                                    <div class="st-icon st-icon--green">
                                                        <i class="fas fa-arrow-up"></i>
                                                    </div>
                                                </div>
                                                <div class="st-bar st-bar--green"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="st-card st-card--red">
                                                <div class="st-card-body">
                                                    <div>
                                                        <div class="st-label">Nota Mínima</div>
                                                        <div class="st-num">{{ $cuestionario->intentos->min('nota') }}</div>
                                                    </div>
                                                    <div class="st-icon st-icon--red">
                                                        <i class="fas fa-arrow-down"></i>
                                                    </div>
                                                </div>
                                                <div class="st-bar st-bar--red"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="st-card st-card--orange">
                                                <div class="st-card-body">
                                                    <div>
                                                        <div class="st-label">Total Intentos</div>
                                                        <div class="st-num">{{ $cuestionario->intentos->count() }}</div>
                                                    </div>
                                                    <div class="st-icon st-icon--orange">
                                                        <i class="fas fa-redo"></i>
                                                    </div>
                                                </div>
                                                <div class="st-bar st-bar--orange"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <div class="card border-0 shadow-sm h-100">
                                                <div class="card-header bg-transparent border-0 pb-0">
                                                    <h6 class="fw-bold mb-0 text-primary">
                                                        <i class="fas fa-chart-pie me-2"></i>Distribución de Calificaciones
                                                    </h6>
                                                </div>
                                                <div class="card-body">
                                                    <canvas id="distributionChart" height="250"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card border-0 shadow-sm h-100">
                                                <div class="card-header bg-transparent border-0 pb-0">
                                                    <h6 class="fw-bold mb-0 text-primary">
                                                        <i class="fas fa-percentage me-2"></i>Tasa de Aprobación
                                                    </h6>
                                                </div>
                                                <div class="card-body text-center d-flex flex-column justify-content-center">
                                                    @php
                                                        $totalIntentos = max($cuestionario->intentos->count(), 1);
                                                        $aprobadosCount = $cuestionario->intentos->where('aprobado', true)->count();
                                                        $tasaAprobacion = round(($aprobadosCount / $totalIntentos) * 100, 1);
                                                    @endphp
                                                    <div class="position-relative d-inline-block mx-auto mb-3">
                                                        <svg width="120" height="120" viewBox="0 0 120 120">
                                                            <circle cx="60" cy="60" r="54" fill="none"
                                                                stroke="#f1f5f9" stroke-width="8" />
                                                            <circle cx="60" cy="60" r="54" fill="none"
                                                                stroke="#22c55e" stroke-width="8"
                                                                stroke-dasharray="339.292"
                                                                stroke-dashoffset="{{ 339.292 * (1 - $tasaAprobacion / 100) }}"
                                                                style="transform: rotate(-90deg); transform-origin: 50% 50%; transition: stroke-dashoffset 1s ease;" />
                                                        </svg>
                                                        <div class="position-absolute top-50 start-50 translate-middle">
                                                            <h3 class="mb-0 fw-bold text-success">{{ $tasaAprobacion }}%</h3>
                                                        </div>
                                                    </div>
                                                    <p class="text-muted mb-0">de estudiantes aprobaron satisfactoriamente</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <div class="info-card-icon orange mx-auto mb-3" style="width:60px; height:60px; font-size:2rem;">
                                            <i class="fas fa-chart-bar"></i>
                                        </div>
                                        <h5 class="text-muted">Sin datos disponibles</h5>
                                        <p class="text-muted mb-0">No hay intentos registrados para este cuestionario.</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Gráficos -->
                            <div class="tab-pane fade" id="grafico" role="tabpanel" aria-labelledby="grafico-tab">
                                @if ($cuestionario->intentos && $cuestionario->intentos->count() > 0)
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-transparent border-0">
                                            <h6 class="fw-bold mb-0 text-primary">
                                                <i class="fas fa-chart-bar me-2"></i>Desempeño por Estudiante
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div style="height: 350px;">
                                                <canvas id="performanceChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <div class="info-card-icon orange mx-auto mb-3" style="width:60px; height:60px; font-size:2rem;">
                                            <i class="fas fa-chart-line"></i>
                                        </div>
                                        <h5 class="text-muted">No hay datos para mostrar</h5>
                                        <p class="text-muted mb-0">Espera a que los estudiantes completen el cuestionario.</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Resumen Estudiantes -->
                            <div class="tab-pane fade" id="resumen" role="tabpanel" aria-labelledby="resumen-tab">
                                @if ($cuestionario->intentos && $cuestionario->intentos->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle" id="resumenEstudiantesTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="border-0">Estudiante</th>
                                                    <th class="border-0">Intentos</th>
                                                    <th class="border-0">Mejor Calificación</th>
                                                    <th class="border-0">Promedio</th>
                                                    <th class="border-0">Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($cuestionario->intentos->groupBy('inscrito.estudiantes.id') as $estudianteId => $intentos)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                                                    style="width: 38px; height: 38px; color: white; font-weight: bold;">
                                                                    {{ substr($intentos->first()->inscrito->estudiantes->name, 0, 1) }}
                                                                </div>
                                                                <div>
                                                                    <div class="fw-bold text-dark">
                                                                        {{ $intentos->first()->inscrito->estudiantes->name }}
                                                                    </div>
                                                                    <small class="text-muted">{{ $intentos->first()->inscrito->estudiantes->email }}</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-light text-primary border px-3 rounded-pill">{{ $intentos->count() }}</span>
                                                        </td>
                                                        <td class="fw-bold text-success">{{ $intentos->max('nota') }}%</td>
                                                        <td class="fw-bold text-dark">{{ round($intentos->avg('nota'), 1) }}%</td>
                                                        <td>
                                                            @if ($intentos->where('aprobado', true)->count() > 0)
                                                                <span class="badge bg-success-subtle text-success border-success-subtle px-3 py-2 rounded-pill">
                                                                    <i class="fas fa-check-circle me-1"></i>Aprobado
                                                                </span>
                                                            @else
                                                                <span class="badge bg-warning-subtle text-warning-emphasis border-warning-subtle px-3 py-2 rounded-pill">
                                                                    <i class="fas fa-clock me-1"></i>En progreso
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <div class="info-card-icon orange mx-auto mb-3" style="width:60px; height:60px; font-size:2rem;">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <h5 class="text-muted">No hay estudiantes registrados</h5>
                                        <p class="text-muted mb-0">Aún no hay intentos en este cuestionario.</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Ranking -->
                            <div class="tab-pane fade" id="ranking" role="tabpanel" aria-labelledby="ranking-tab">
                                @if ($cuestionario->intentos && $cuestionario->intentos->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle" id="rankingTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="border-0">#</th>
                                                    <th class="border-0">Estudiante</th>
                                                    <th class="border-0">Calificación</th>
                                                    <th class="border-0">Fecha</th>
                                                    <th class="border-0 text-end">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($cuestionario->intentos->sortByDesc('nota') as $intento)
                                                    <tr>
                                                        <td class="fw-bold">
                                                            @if ($loop->iteration <= 3)
                                                                <span class="badge bg-warning text-dark rounded-circle d-inline-flex align-items-center justify-content-center" style="width:25px; height:25px;">
                                                                    {{ $loop->iteration }}
                                                                </span>
                                                            @else
                                                                <span class="text-muted ps-2">{{ $loop->iteration }}</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                                                    style="width: 35px; height: 35px; font-weight: bold; font-size: 0.8rem;">
                                                                    {{ substr($intento->inscrito->estudiantes->name, 0, 1) }}
                                                                </div>
                                                                <div>
                                                                    <div class="fw-bold text-dark">{{ $intento->inscrito->estudiantes->name }}</div>
                                                                    <small class="text-muted small">{{ $intento->inscrito->estudiantes->email }}</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $score = $intento->nota ?? 0;
                                                                $badgeClass = $score >= 90 ? 'bg-success' : ($score >= 70 ? 'bg-primary' : ($score >= 50 ? 'bg-warning text-dark' : 'bg-danger'));
                                                            @endphp
                                                            <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill">
                                                                {{ $score }}%
                                                            </span>
                                                        </td>
                                                        <td class="text-muted small">
                                                            {{ $intento->finalizado_en ? $intento->finalizado_en->format('d/m/Y H:i') : 'En curso' }}
                                                        </td>
                                                        <td class="text-end">
                                                            <div class="btn-group">
                                                                <a href="{{ route('cuestionarios.revisarIntento', [encrypt($cuestionario->id), encrypt($intento->id)]) }}"
                                                                    class="btn btn-outline-primary btn-sm rounded-start-pill px-3">
                                                                    <i class="fas fa-eye me-1"></i>Revisar
                                                                </a>
                                                                <form action="{{ route('intentos.eliminar', encrypt($intento->id)) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-end-pill px-3"
                                                                        onclick="return confirm('¿Estás seguro de eliminar este intento?')">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <div class="info-card-icon orange mx-auto mb-3" style="width:60px; height:60px; font-size:2rem;">
                                            <i class="fas fa-trophy"></i>
                                        </div>
                                        <h5 class="text-muted">No hay ranking disponible</h5>
                                        <p class="text-muted mb-0">Espera a que los estudiantes completen el cuestionario.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection



<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- Script para Estudiantes -->
@hasrole('Estudiante')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar DataTables para la tabla de estudiantes
            if (typeof $.fn.DataTable !== 'undefined' && $('#mejoresIntentosTable').length && $(
                    '#mejoresIntentosTable tbody tr').length > 0) {
                try {
                    $('#mejoresIntentosTable').DataTable({
                        language: {
                            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                        },
                        pageLength: 10,
                        responsive: true,
                        autoWidth: false,
                        order: [
                            [4, 'desc']
                        ], // Ordenar por fecha
                        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
                    });
                } catch (e) {
                    console.error('Error initializing mejoresIntentosTable:', e);
                }
            }
        });
    </script>
@endrole

<!-- Script para Docentes -->
@hasrole('Docente')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Verificar si hay datos antes de inicializar gráficos
            const performanceCanvas = document.getElementById('performanceChart');

            if (performanceCanvas && @json($cuestionario->intentos->count() > 0)) {
                const ctx = performanceCanvas.getContext('2d');

                // Datos para el gráfico
                const estudiantes = @json($cuestionario->intentos->pluck('inscrito.estudiantes.name'));
                const notas = @json($cuestionario->intentos->pluck('nota'));
                const intentosIds = @json($cuestionario->intentos->pluck('id'));

                // Colores dinámicos basados en las notas
                const colores = notas.map(nota => {
                    if (nota >= 90) return '#28a745'; // Verde para excelente
                    if (nota >= 70) return '#17a2b8'; // Azul para bueno
                    if (nota >= 50) return '#ffc107'; // Amarillo para regular
                    return '#dc3545'; // Rojo para necesita mejorar
                });

                // Calcular estadísticas
                const promedio = notas.reduce((a, b) => a + b, 0) / notas.length;
                const notaMaxima = Math.max(...notas);
                const notaMinima = Math.min(...notas);

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: estudiantes,
                        datasets: [{
                            label: 'Calificación',
                            data: notas,
                            backgroundColor: colores,
                            borderColor: colores.map(color => color),
                            borderWidth: 2,
                            borderRadius: 8,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Distribución de Calificaciones por Estudiante',
                                font: {
                                    size: 16,
                                    weight: 'bold'
                                },
                                padding: 20
                            },
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleFont: {
                                    size: 14
                                },
                                bodyFont: {
                                    size: 13
                                },
                                callbacks: {
                                    label: function(context) {
                                        const nota = context.raw;
                                        let calificacion = '';
                                        if (nota >= 90) calificacion = ' (Excelente)';
                                        else if (nota >= 70) calificacion = ' (Bueno)';
                                        else if (nota >= 50) calificacion = ' (Regular)';
                                        else calificacion = ' (Necesita mejorar)';

                                        return `Calificación: ${nota}${calificacion}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                title: {
                                    display: true,
                                    text: 'Calificación (%)',
                                    font: {
                                        size: 12,
                                        weight: 'bold'
                                    }
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                },
                                ticks: {
                                    callback: function(value) {
                                        return value + '%';
                                    }
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Estudiantes',
                                    font: {
                                        size: 12,
                                        weight: 'bold'
                                    }
                                },
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    maxRotation: 45,
                                    minRotation: 45
                                }
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },
                        animation: {
                            duration: 1000,
                            easing: 'easeOutQuart'
                        }
                    }
                });
            }

            // Inicializar DataTables solo si las tablas existen y tienen contenido
            if (typeof $.fn.DataTable !== 'undefined') {
                // Configuración base de DataTables
                const tableConfig = {
                    language: {
                        url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                    },
                    pageLength: 10,
                    responsive: true,
                    autoWidth: false,
                    dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
                };

                // Inicializar cada tabla individualmente si existe
                if ($('#rankingTable').length && $('#rankingTable tbody tr').length > 0) {
                    try {
                        $('#rankingTable').DataTable({
                            ...tableConfig,
                            order: [
                                [2, 'desc']
                            ]
                        });
                    } catch (e) {
                        console.error('Error initializing rankingTable:', e);
                    }
                }

                if ($('#resumenEstudiantesTable').length && $('#resumenEstudiantesTable tbody tr').length > 0) {
                    try {
                        $('#resumenEstudiantesTable').DataTable({
                            ...tableConfig,
                            order: [
                                [2, 'desc']
                            ]
                        });
                    } catch (e) {
                        console.error('Error initializing resumenEstudiantesTable:', e);
                    }
                }
            }
        });
    </script>

    <style>
        /* Estilos adicionales para el gráfico */
        .chart-container {
            position: relative;
            height: 500px;
            margin-bottom: 2rem;
        }

        #graficoNotas {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .table-success {
            background-color: rgba(40, 167, 69, 0.1) !important;
        }

        .table-info {
            background-color: rgba(23, 162, 184, 0.1) !important;
        }

        .table-warning {
            background-color: rgba(255, 193, 7, 0.1) !important;
        }

        .table-danger {
            background-color: rgba(220, 53, 69, 0.1) !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 6px !important;
            margin: 0 2px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--color-primary) !important;
            color: white !important;
            border: none !important;
        }
    </style>
@endrole
