@extends('layout')

@section('titulo')
    Ranking de Cuestionarios
@endsection

@section('content')

<div class="container mt-4">

    <a href="{{ route('Curso', encrypt($cuestionarios->actividad->subtema->tema->curso_id)) }}" class="btn btn-primary mb-3">
        <i class="bi bi-arrow-left"></i> Volver al curso
    </a>
    <!-- Tabs por rol -->
    <ul class="nav nav-tabs" id="mainTabs" role="tablist">
        @if (Auth::user()->hasRole('Estudiante'))
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="estudiante-tab" data-bs-toggle="tab" data-bs-target="#estudiante" type="button" role="tab" aria-controls="estudiante" aria-selected="true">
                <i class="bi bi-person-fill"></i> Estudiante
            </button>
        </li>
        @endif
        @if (Auth::user()->hasRole('Docente'))
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ Auth::user()->hasRole('Estudiante') ? '' : 'active' }}" id="docente-tab" data-bs-toggle="tab" data-bs-target="#docente" type="button" role="tab" aria-controls="docente" aria-selected="{{ Auth::user()->hasRole('Estudiante') ? 'false' : 'true' }}">
                <i class="bi bi-mortarboard-fill"></i> Docente
            </button>
        </li>
        @endif
    </ul>

    <div class="tab-content mt-3" id="mainTabsContent">
        <!-- Estudiante -->
        @if (Auth::user()->hasRole('Estudiante'))
        <div class="tab-pane fade show active" id="estudiante" role="tabpanel" aria-labelledby="estudiante-tab">
            <h4 class="mb-3"><i class="bi bi-star-fill"></i> Tus Mejores Intentos</h4>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle" id="mejoresIntentosTable">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Cuestionario</th>
                            <th>Nota</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mejoresIntentos as $intento)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $intento->cuestionario->actividad->titulo }}</td>
                            <td><span class="badge bg-primary">{{ $intento->nota ?? 'Sin Nota' }}</span></td>
                            <td>{{ $intento->finalizado_en ? $intento->finalizado_en->format('d/m/Y H:i') : 'Salió del cuestionario' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">No tienes intentos registrados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Docente con subtabs -->
        @if (Auth::user()->hasRole('Docente'))
        <div class="tab-pane fade {{ Auth::user()->hasRole('Estudiante') ? '' : 'show active' }}" id="docente" role="tabpanel" aria-labelledby="docente-tab">
            <!-- Subtabs internas -->
            <ul class="nav nav-tabs mt-3" id="docenteTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="estadisticas-tab" data-bs-toggle="tab" data-bs-target="#estadisticas" type="button" role="tab">📊 Estadísticas</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="grafico-tab" data-bs-toggle="tab" data-bs-target="#grafico" type="button" role="tab">📈 Gráfico</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="resumen-tab" data-bs-toggle="tab" data-bs-target="#resumen" type="button" role="tab">📋 Resumen</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="ranking-tab" data-bs-toggle="tab" data-bs-target="#ranking" type="button" role="tab">🏆 Ranking</button>
                </li>
            </ul>

            <div class="tab-content mt-3" id="docenteTabsContent">
                <!-- Estadísticas -->
                <div class="tab-pane fade show active" id="estadisticas" role="tabpanel" aria-labelledby="estadisticas-tab">
                    @if ($cuestionario->intentos && $cuestionario->intentos->count() > 0)
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card text-bg-primary text-center shadow-sm">
                                    <div class="card-body">
                                        <i class="bi bi-graph-up fs-1 mb-2"></i>
                                        <h6>Promedio</h6>
                                        <p class="fs-5">{{ round($cuestionario->intentos->avg('nota'), 2) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-bg-success text-center shadow-sm">
                                    <div class="card-body">
                                        <i class="bi bi-arrow-up-circle fs-1 mb-2"></i>
                                        <h6>Nota Máxima</h6>
                                        <p class="fs-5">{{ $cuestionario->intentos->max('nota') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-bg-danger text-center shadow-sm">
                                    <div class="card-body">
                                        <i class="bi bi-arrow-down-circle fs-1 mb-2"></i>
                                        <h6>Nota Mínima</h6>
                                        <p class="fs-5">{{ $cuestionario->intentos->min('nota') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-bg-secondary text-center shadow-sm">
                                    <div class="card-body">
                                        <i class="bi bi-bar-chart-line fs-1 mb-2"></i>
                                        <h6>Intentos</h6>
                                        <p class="fs-5">{{ $cuestionario->intentos->count() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="alert alert-info d-flex align-items-center">
                                <i class="bi bi-check-circle-fill fs-4 me-2"></i>
                                <div>
                                    <strong>{{ round(($cuestionario->intentos->where('aprobado', true)->count() / $cuestionario->intentos->count()) * 100, 2) }}%</strong>
                                    aprobaron este cuestionario.
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle fs-4 me-2"></i>
                            <div>
                                <strong>Sin datos disponibles</strong><br>
                                No hay intentos registrados para este cuestionario.
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Gráfico -->
                <div class="tab-pane fade" id="grafico" role="tabpanel" aria-labelledby="grafico-tab">
                    <h5><i class="bi bi-bar-chart"></i> Notas por estudiante</h5>
                    @if ($cuestionario->intentos && $cuestionario->intentos->count() > 0)
                        <canvas id="graficoNotas" height="150"></canvas>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="bi bi-graph-up fs-1 mb-2"></i>
                            <p class="mb-0">No hay datos suficientes para mostrar el gráfico</p>
                        </div>
                    @endif
                </div>

                <!-- Resumen -->
                <div class="tab-pane fade" id="resumen" role="tabpanel" aria-labelledby="resumen-tab">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="resumenEstudiantesTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>Estudiante</th>
                                    <th>Intentos</th>
                                    <th>Mejor Nota</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($cuestionario->intentos && $cuestionario->intentos->count() > 0)
                                    @foreach ($cuestionario->intentos->groupBy('inscrito.estudiantes.id') as $intentos)
                                    <tr>
                                        <td>{{ $intentos->first()->inscrito->estudiantes->name }}</td>
                                        <td><span class="badge bg-primary">{{ $intentos->count() }}</span></td>
                                        <td>{{ $intentos->max('nota') }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" class="text-center">No hay intentos registrados.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Ranking -->
                <div class="tab-pane fade" id="ranking" role="tabpanel" aria-labelledby="ranking-tab">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="rankingTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Estudiante</th>
                                    <th>Nota</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($cuestionario->intentos as $intento)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $intento->inscrito->estudiantes->name }} {{ $intento->inscrito->estudiantes->lastname1 }} {{ $intento->inscrito->estudiantes->lastname2 }}</td>
                                    <td>
                                        <span class="badge {{ $intento->nota >= 70 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $intento->nota ?? 'Sin nota' }}
                                        </span>
                                    </td>
                                    <td>{{ $intento->finalizado_en ? $intento->finalizado_en->format('d/m/Y H:i') : 'Salió del cuestionario' }}</td>
                                    <td>
                                        <a href="{{ route('cuestionarios.revisarIntento', [encrypt($cuestionario->id), encrypt($intento->id)]) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye-fill"></i> Revisar
                                        </a>
                                        <form action="{{ route('intentos.eliminar', encrypt($intento->id)) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Eliminar intento?')">
                                                <i class="bi bi-trash-fill"></i> Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No hay intentos registrados.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
@hasrole('Docente')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('graficoNotas')) {
        const ctx = document.getElementById('graficoNotas').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($cuestionario->intentos->pluck('inscrito.estudiantes.name')),
                datasets: [{
                    label: 'Notas',
                    data: @json($cuestionario->intentos->pluck('nota')),
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    }

    $('#rankingTable, #resumenEstudiantesTable, #mejoresIntentosTable').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        },
        order: [[2, 'desc']],
        pageLength: 10
    });
});
</script>
@endrole
