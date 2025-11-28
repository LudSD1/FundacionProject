@extends('layout')

@section('titulo')
    Ranking de Cuestionarios
@endsection

@section('content')




    <style>
        .results-container {
            background: linear-gradient(135deg, #f8fafc 0%, #e9ecef 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }

        .results-header {
            background: var(--gradient-primary);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: var(--border-radius);
            position: relative;
            overflow: hidden;
        }

        .results-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
            opacity: 0.1;
        }

        .nav-tabs-custom {
            background: white;
            border-radius: var(--border-radius);
            padding: 1rem;
            box-shadow: var(--shadow-sm);
            border: none;
        }

        .nav-tabs-custom .nav-link {
            border: none;
            border-radius: var(--border-radius-sm);
            padding: 1rem 1.5rem;
            margin: 0 0.25rem;
            color: var(--color-muted);
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-tabs-custom .nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 3px;
            background: var(--color-primary);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-tabs-custom .nav-link.active {
            color: var(--color-primary);
            background: rgba(57, 166, 203, 0.1);
        }

        .nav-tabs-custom .nav-link.active::before {
            width: 100%;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            text-align: center;
            transition: all 0.3s ease;
            border-left: 4px solid;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }

        .stat-card.primary {
            border-left-color: var(--color-primary);
        }

        .stat-card.success {
            border-left-color: var(--color-success);
        }

        .stat-card.danger {
            border-left-color: var(--color-danger);
        }

        .stat-card.secondary {
            border-left-color: var(--color-secondary);
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            opacity: 0.8;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--color-muted);
            font-weight: 600;
        }

        .table-modern {
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .table-modern thead th {
            background: var(--gradient-primary);
            color: white;
            border: none;
            padding: 1rem;
            font-weight: 600;
        }

        .table-modern tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-color: #e9ecef;
        }

        .table-modern tbody tr:hover {
            background-color: rgba(57, 166, 203, 0.05);
        }

        .badge-score {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .badge-excellent {
            background: var(--gradient-success);
            color: white;
        }

        .badge-good {
            background: var(--gradient-primary);
            color: white;
        }

        .badge-average {
            background: var(--gradient-warning);
            color: #212529;
        }

        .badge-poor {
            background: var(--gradient-danger);
            color: white;
        }

        .chart-container {
            background: white;
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--shadow-sm);
            margin-bottom: 2rem;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
        }

        .empty-state-icon {
            font-size: 4rem;
            color: var(--color-secondary);
            opacity: 0.5;
            margin-bottom: 1.5rem;
        }

        .btn-modern {
            border: none;
            border-radius: var(--border-radius-sm);
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary-modern {
            background: var(--gradient-primary);
            color: white;
        }

        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .progress-ring {
            width: 120px;
            height: 120px;
            margin: 0 auto 1rem;
        }

        .progress-ring-circle {
            transform: rotate(-90deg);
            transform-origin: 50% 50%;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card,
        .table-modern,
        .chart-container {
            animation: fadeInUp 0.5s ease forwards;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .nav-tabs-custom .nav-link {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }
        }
    </style>

    <div class="container">

    <a href="{{ route('Curso', encrypt($cuestionarios->actividad->subtema->tema->curso_id)) }}"
                            class="btn btn-primary text-white text-decoration-none m-2">
                            <i class="fas fa-arrow-left me-2"></i>Volver al curso
                        </a>
        <!-- Header -->
        <div class="results-header">
            <div class="container">

                <div class="row align-items-center">
                    <div class="col-md-8">

                        <h1 class="mb-2">
                            <i class="fas fa-chart-bar me-3"></i>
                            Resultados del Cuestionario
                        </h1>
                        <p class="mb-0 opacity-75">
                            {{ $cuestionario->titulo ?? 'Análisis de desempeño' }}
                        </p>
                    </div>
                    {{-- <div class="col-md-4 text-md-end">
                        <div class="d-flex gap-2 justify-content-md-end">
                            <button class="btn btn-light" onclick="window.print()">
                                <i class="fas fa-print me-2"></i>Imprimir
                            </button>
                            <button class="btn btn-outline-light" onclick="exportData()">
                                <i class="fas fa-download me-2"></i>Exportar
                            </button>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>

        <!-- Tabs principales -->
        <ul class="nav nav-tabs-custom" id="mainTabs" role="tablist">
            @if (Auth::user()->hasRole('Estudiante'))
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="estudiante-tab" data-bs-toggle="tab" data-bs-target="#estudiante"
                        type="button" role="tab">
                        <i class="fas fa-user-graduate me-2"></i>Mi Progreso
                    </button>
                </li>
            @endif
            @if (Auth::user()->hasRole('Docente'))
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ Auth::user()->hasRole('Estudiante') ? '' : 'active' }}" id="docente-tab"
                        data-bs-toggle="tab" data-bs-target="#docente" type="button" role="tab">
                        <i class="fas fa-chalkboard-teacher me-2"></i>Análisis Docente
                    </button>
                </li>
            @endif
        </ul>

        <div class="tab-content mt-4" id="mainTabsContent">
            <!-- Vista Estudiante -->
            @if (Auth::user()->hasRole('Estudiante'))
                <div class="tab-pane fade show active" id="estudiante" role="tabpanel" aria-labelledby="estudiante-tab">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="mb-0">
                            <i class="fas fa-trophy me-2"></i>Mi Desempeño
                        </h3>
                        <span class="badge bg-primary fs-6">
                            {{ $mejoresIntentos->count() }} intento{{ $mejoresIntentos->count() !== 1 ? 's' : '' }}
                        </span>
                    </div>

                    @if ($mejoresIntentos->count() > 0)
                        <div class="table-modern">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="mejoresIntentosTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Cuestionario</th>
                                            <th>Calificación</th>
                                            <th>Estado</th>
                                            <th>Fecha</th>
                                            @hasrole('Docente')
                                            <th>Acciones</th>
                                            @endhasrole

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($mejoresIntentos as $intento)
                                            <tr>
                                                <td class="fw-bold">{{ $loop->iteration }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-file-alt text-primary me-2"></i>
                                                        <span>{{ $intento->cuestionario->actividad->titulo }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    @php
                                                        $score = $intento->nota ?? 0;
                                                        if ($score >= 90) {
                                                            $badgeClass = 'badge-excellent';
                                                        } elseif ($score >= 70) {
                                                            $badgeClass = 'badge-good';
                                                        } elseif ($score >= 50) {
                                                            $badgeClass = 'badge-average';
                                                        } else {
                                                            $badgeClass = 'badge-poor';
                                                        }
                                                    @endphp
                                                    <span class="badge-score {{ $badgeClass }}">
                                                        {{ $score ?? 'N/A' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($intento->aprobado)
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check me-1"></i>Aprobado
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning">
                                                            <i class="fas fa-clock me-1"></i>En progreso
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        {{ $intento->finalizado_en ? $intento->finalizado_en->format('d/m/Y H:i') : 'En curso' }}
                                                    </small>
                                                </td>
                                                @hasrole(  'Docente')
                                                <td>
                                                    <a href="{{ route('cuestionarios.revisarIntento', [encrypt($intento->cuestionario->id), encrypt($intento->id)]) }}"
                                                        class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-eye me-1"></i>Ver
                                                    </a>
                                                </td>
                                                @endhasrole
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h4 class="text-muted">No hay intentos registrados</h4>
                            <p class="text-muted mb-4">Aún no has realizado intentos en este cuestionario.</p>
                            <a href="{{ route('curso.actividad', [encrypt($cuestionarios->actividad->subtema->tema->curso_id), encrypt($cuestionarios->actividad_id)]) }}"
                                class="btn-modern btn-primary-modern">
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
                    <!-- Subtabs internas -->
                    <ul class="nav nav-tabs-custom mt-0" id="docenteTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" id="estadisticas-tab" data-bs-toggle="tab"
                                data-bs-target="#estadisticas" type="button" role="tab">
                                <i class="fas fa-chart-pie me-2"></i>Estadísticas
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="grafico-tab" data-bs-toggle="tab" data-bs-target="#grafico"
                                type="button" role="tab">
                                <i class="fas fa-chart-line me-2"></i>Gráficos
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="resumen-tab" data-bs-toggle="tab" data-bs-target="#resumen"
                                type="button" role="tab">
                                <i class="fas fa-users me-2"></i>Estudiantes
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="ranking-tab" data-bs-toggle="tab" data-bs-target="#ranking"
                                type="button" role="tab">
                                <i class="fas fa-trophy me-2"></i>Ranking
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content mt-4" id="docenteTabsContent">
                        <!-- Estadísticas -->
                        <div class="tab-pane fade show active" id="estadisticas" role="tabpanel"
                            aria-labelledby="estadisticas-tab">
                            @if ($cuestionario->intentos && $cuestionario->intentos->count() > 0)
                                <div class="stats-grid">
                                    <div class="stat-card primary">
                                        <div class="stat-icon text-primary">
                                            <i class="fas fa-chart-line"></i>
                                        </div>
                                        <div class="stat-value text-primary">
                                            {{ round($cuestionario->intentos->avg('nota'), 1) }}
                                        </div>
                                        <div class="stat-label">Promedio General</div>
                                    </div>

                                    <div class="stat-card success">
                                        <div class="stat-icon text-success">
                                            <i class="fas fa-arrow-up"></i>
                                        </div>
                                        <div class="stat-value text-success">
                                            {{ $cuestionario->intentos->max('nota') }}
                                        </div>
                                        <div class="stat-label">Nota Máxima</div>
                                    </div>

                                    <div class="stat-card danger">
                                        <div class="stat-icon text-danger">
                                            <i class="fas fa-arrow-down"></i>
                                        </div>
                                        <div class="stat-value text-danger">
                                            {{ $cuestionario->intentos->min('nota') }}
                                        </div>
                                        <div class="stat-label">Nota Mínima</div>
                                    </div>

                                    <div class="stat-card secondary">
                                        <div class="stat-icon text-secondary">
                                            <i class="fas fa-redo"></i>
                                        </div>
                                        <div class="stat-value text-secondary">
                                            {{ $cuestionario->intentos->count() }}
                                        </div>
                                        <div class="stat-label">Total de Intentos</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="chart-container">
                                            <h5 class="mb-4">
                                                <i class="fas fa-chart-pie me-2"></i>Distribución de Calificaciones
                                            </h5>
                                            <canvas id="distributionChart" height="250"></canvas>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="chart-container">
                                            <h5 class="mb-4">
                                                <i class="fas fa-percentage me-2"></i>Tasa de Aprobación
                                            </h5>
                                            <div class="text-center">
                                                <div class="progress-ring">
                                                    <svg width="120" height="120" viewBox="0 0 120 120">
                                                        <circle cx="60" cy="60" r="54" fill="none"
                                                            stroke="#e9ecef" stroke-width="8" />
                                                        <circle class="progress-ring-circle" cx="60"
                                                            cy="60" r="54" fill="none" stroke="#28a745"
                                                            stroke-width="8" stroke-dasharray="339.292"
                                                            stroke-dashoffset="{{ 339.292 * (1 - $cuestionario->intentos->where('aprobado', true)->count() / max($cuestionario->intentos->count(), 1)) }}" />
                                                    </svg>
                                                </div>
                                                <h2 class="text-success mb-1">
                                                    {{ round(($cuestionario->intentos->where('aprobado', true)->count() / $cuestionario->intentos->count()) * 100, 1) }}%
                                                </h2>
                                                <p class="text-muted">de estudiantes aprobaron</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-chart-bar"></i>
                                    </div>
                                    <h4 class="text-muted">Sin datos disponibles</h4>
                                    <p class="text-muted mb-4">No hay intentos registrados para este cuestionario.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Gráficos -->
                        <div class="tab-pane fade" id="grafico" role="tabpanel" aria-labelledby="grafico-tab">
                            @if ($cuestionario->intentos && $cuestionario->intentos->count() > 0)
                                <div class="chart-container">
                                    <h5 class="mb-4">
                                        <i class="fas fa-chart-bar me-2"></i>Desempeño por Estudiante
                                    </h5>
                                    <canvas id="performanceChart" height="300"></canvas>
                                </div>
                            @else
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <h4 class="text-muted">No hay datos para mostrar</h4>
                                    <p class="text-muted">Espera a que los estudiantes completen el cuestionario.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Resumen Estudiantes -->
                        <div class="tab-pane fade" id="resumen" role="tabpanel" aria-labelledby="resumen-tab">
                            @if ($cuestionario->intentos && $cuestionario->intentos->count() > 0)
                                <div class="table-modern">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0" id="resumenEstudiantesTable">
                                            <thead>
                                                <tr>
                                                    <th>Estudiante</th>
                                                    <th>Intentos</th>
                                                    <th>Mejor Calificación</th>
                                                    <th>Promedio</th>
                                                    <th>Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($cuestionario->intentos->groupBy('inscrito.estudiantes.id') as $estudianteId => $intentos)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                                                    style="width: 40px; height: 40px; color: white; font-weight: bold;">
                                                                    {{ substr($intentos->first()->inscrito->estudiantes->name, 0, 1) }}
                                                                </div>
                                                                <div>
                                                                    <div class="fw-bold">
                                                                        {{ $intentos->first()->inscrito->estudiantes->name }}
                                                                    </div>
                                                                    <small
                                                                        class="text-muted">{{ $intentos->first()->inscrito->estudiantes->email }}</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="badge bg-primary rounded-pill">{{ $intentos->count() }}</span>
                                                        </td>
                                                        <td class="fw-bold text-success">{{ $intentos->max('nota') }}</td>
                                                        <td class="fw-bold">{{ round($intentos->avg('nota'), 1) }}</td>
                                                        <td>
                                                            @if ($intentos->where('aprobado', true)->count() > 0)
                                                                <span class="badge bg-success">
                                                                    <i class="fas fa-check me-1"></i>Aprobado
                                                                </span>
                                                            @else
                                                                <span class="badge bg-warning">
                                                                    <i class="fas fa-clock me-1"></i>En progreso
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <h4 class="text-muted">No hay estudiantes registrados</h4>
                                    <p class="text-muted">Aún no hay intentos en este cuestionario.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Ranking -->
                        <div class="tab-pane fade" id="ranking" role="tabpanel" aria-labelledby="ranking-tab">
                            @if ($cuestionario->intentos && $cuestionario->intentos->count() > 0)
                                <div class="table-modern">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0" id="rankingTable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Estudiante</th>
                                                    <th>Calificación</th>
                                                    <th>Fecha</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($cuestionario->intentos->sortByDesc('nota') as $intento)
                                                    <tr>
                                                        <td class="fw-bold">
                                                            @if ($loop->iteration <= 3)
                                                                <span
                                                                    class="badge bg-warning rounded-pill">{{ $loop->iteration }}</span>
                                                            @else
                                                                {{ $loop->iteration }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                                                    style="width: 35px; height: 35px; color: white; font-weight: bold; font-size: 0.8rem;">
                                                                    {{ substr($intento->inscrito->estudiantes->name, 0, 1) }}
                                                                </div>
                                                                <div>
                                                                    <div class="fw-bold">
                                                                        {{ $intento->inscrito->estudiantes->name }}</div>
                                                                    <small
                                                                        class="text-muted">{{ $intento->inscrito->estudiantes->email }}</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $score = $intento->nota ?? 0;
                                                                if ($score >= 90) {
                                                                    $badgeClass = 'badge-excellent';
                                                                } elseif ($score >= 70) {
                                                                    $badgeClass = 'badge-good';
                                                                } elseif ($score >= 50) {
                                                                    $badgeClass = 'badge-average';
                                                                } else {
                                                                    $badgeClass = 'badge-poor';
                                                                }
                                                            @endphp
                                                            <span class="badge-score {{ $badgeClass }}">
                                                                {{ $score }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <small class="text-muted">
                                                                {{ $intento->finalizado_en ? $intento->finalizado_en->format('d/m/Y H:i') : 'En curso' }}
                                                            </small>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                <a href="{{ route('cuestionarios.revisarIntento', [encrypt($cuestionario->id), encrypt($intento->id)]) }}"
                                                                    class="btn btn-outline-primary">
                                                                    <i class="fas fa-eye me-1"></i>Revisar
                                                                </a>
                                                                <form
                                                                    action="{{ route('intentos.eliminar', encrypt($intento->id)) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-outline-danger"
                                                                        onclick="return confirm('¿Estás seguro de eliminar este intento?')">
                                                                        <i class="fas fa-trash me-1"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-trophy"></i>
                                    </div>
                                    <h4 class="text-muted">No hay ranking disponible</h4>
                                    <p class="text-muted">Espera a que los estudiantes completen el cuestionario.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
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
