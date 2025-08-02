<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Final del Curso</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Montserrat Font -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f3f4f6;
            color: #212529;
        }

        .header-container img {
            height: 60px;
        }

        .header-container {
            background: linear-gradient(to right bottom, #1A4789 49.5%, #ffffff 50%);
            padding: 1.5rem;
            border-radius: 10px;
        }

        h1, h3 {
            text-transform: uppercase;
            font-weight: 700;
        }

        .table thead th {
            background-color: #63becf;
            color: white;
        }

        .chart-wrapper {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
        }

        canvas {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }

        .firma {
            width: 100px;
            margin-top: 2rem;
        }

        @media print {
            #generatePdfLink {
                display: none;
            }

            .container {
                box-shadow: none !important;
            }
        }
    </style>
</head>

<body>

    <div class="container my-5 bg-white p-4 rounded shadow" id="container">
        @if (auth()->user()->hasRole('Administrador'))
            <div class="d-flex justify-content-end mb-3">
                <button id="generatePdfLink" class="btn btn-primary">
                    <i class="bi bi-file-earmark-pdf-fill"></i> Generar PDF
                </button>
            </div>
        @endif

        <!-- Header con logos -->
        <div class="header-container d-flex justify-content-between align-items-center mb-4">
            <img src="{{ asset('assets/img/logof.png') }}" alt="Logo Fundación">
            <img src="{{ asset('assets/img/Acceder.png') }}" alt="Logo Plataforma">
        </div>

        <!-- Título -->
        <div class="text-center mb-5">
            @if ($cursos->tipo == 'curso')
            <h1>Sumario del Curso</h1>
            @else
            <h1>Sumario del Congreso</h1>
            @endif
        </div>

        <!-- Datos del curso -->
        <div class="row mb-4">
            <div class="col-md-6">
                <p><strong>Curso:</strong> {{ $cursos->nombreCurso }}</p>
                @if ($cursos->tipo == 'curso')
                <p><strong>Docente:</strong> {{ $cursos->docente->name }} {{ $cursos->docente->lastname1 }} {{ $cursos->docente->lastname2 }}</p>
                @endif
                <p><strong>Periodo:</strong> {{ $cursos->fecha_ini }} al {{ $cursos->fecha_fin }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Nivel:</strong> {{ $cursos->nivel }}</p>
                <p><strong>Estudiantes Inscritos:</strong> {{ $inscritos->count() }}</p>
            </div>
        </div>

        <!-- Contenido -->
        @if ($cursos->tipo == 'curso')

        <div class="row mb-5">
            <div class="col-md-6">
                <h5 class="text-primary">Contenido</h5>
                <p><strong>Temas:</strong> {{ $temas->count() }}</p>
                <p><strong>Foros:</strong> {{ $foros->count() }}</p>
            </div>
            <div class="col-md-6">
                <h5 class="text-primary">Recursos</h5>
                <p><strong>Recursos:</strong> {{ $recursos->count() }}</p>
                <p><strong>Asistencias:</strong> {{ $asistencias->count() }}</p>
            </div>
        </div>
        @endif

        <!-- Participantes -->
        <h3 class="mb-4 text-primary">Participantes</h3>
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead>
                    <tr>
                        <th>Participantes</th>
                        @if ($cursos->tipo == 'curso')

                        <th>Total Asistencias</th>
                        <th>Detalle Asistencias</th>
                        <th>Nota Final</th>
                        <th>Escala</th>
                        @else
                        <th>Certificado Recibido</th>
                        <th>Fecha Recibido</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($inscritos as $inscrito)
                        @php
                            $notasInscrito = $notasEntregas->where('inscripcion_id', $inscrito->id);
                            $cuestionariosInscrito = $notasCuestionarios->where('inscripcion_id', $inscrito->id);
                            
                            $sumaNotas = 0;
                            $cantidadNotas = 0;

                            // Sumar notas de actividades
                            if ($notasInscrito->count() > 0) {
                                $sumaNotas += $notasInscrito->sum('nota');
                                $cantidadNotas += $notasInscrito->count();
                            }

                            // Sumar notas de cuestionarios
                            if ($cuestionariosInscrito->count() > 0) {
                                $sumaNotas += $cuestionariosInscrito->sum('calificacion');
                                $cantidadNotas += $cuestionariosInscrito->count();
                            }

                            // Calcular promedio final
                            $notaFinal = $cantidadNotas > 0 ? round($sumaNotas / $cantidadNotas, 2) : 0;
                        @endphp
                        <tr>
                            <td>{{ $inscrito->estudiantes->name }} {{ $inscrito->estudiantes->lastname1 }} {{ $inscrito->estudiantes->lastname2 }}</td>
                            @if ($cursos->tipo == 'curso')
                            <td>{{ $inscrito->asistencia->count() }}</td>
                            <td class="text-start">
                                Presente: {{ $inscrito->asistencia->where('tipoAsitencia', 'Presente')->count() }}<br>
                                Retraso: {{ $inscrito->asistencia->where('tipoAsitencia', 'Retraso')->count() }}<br>
                                Falta: {{ $inscrito->asistencia->where('tipoAsitencia', 'Falta')->count() }}<br>
                                Licencia: {{ $inscrito->asistencia->where('tipoAsitencia', 'Licencia')->count() }}
                            </td>
                            <td>{{ $notaFinal }}</td>
                            <td>
                                @if ($notaFinal >= 90)
                                    Experto
                                @elseif ($notaFinal >= 75)
                                    Habilidoso
                                @elseif ($notaFinal >= 60)
                                    Aprendiz
                                @else
                                    Participante
                                @endif
                            </td>
                            @else
                                @if (isset($inscrito->certificado))
                                <td>Si</td>
                                <td>{{ $inscrito->certificado->created_at }}</td>
                                @else
                                <td>No</td>
                                <td>No Recibido</td>
                                @endif
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Gráficos -->
        @if ($cursos->tipo == 'curso')

        <h3 class="mt-5 mb-3 text-primary">Estadísticas del Curso</h3>
        <div class="chart-wrapper">
            <div><canvas id="myChart" width="300" height="200"></canvas></div>
            <div><canvas id="myChart2" width="300" height="200"></canvas></div>
        </div>
        @endif
        <!-- Firma -->
        {{-- @if (auth()->user()->hasRole('Administrador'))
            <div class="text-center mt-5">
                <img src="{{ asset('assets/img/firma digital.png') }}" class="firma" alt="Firma Directora">
                <p class="mb-0 fw-semibold">Mba. Roxana Araujo Romay</p>
                <p class="mb-0 small">Directora Ejecutiva</p>
                <p class="small">FUNDACIÓN EDUCAR PARA LA VIDA</p>
            </div>
        @endif --}}
    </div>

    <!-- Scripts -->
    <script>
        const ctx1 = document.getElementById('myChart').getContext('2d');
        const ctx2 = document.getElementById('myChart2').getContext('2d');

        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['Presente', 'Retraso', 'Falta', 'Licencia'],
                datasets: [{
                    label: 'Asistencias',
                    data: [{{ $conteoPresentes }}, {{ $conteoRetrasos }}, {{ $conteoFaltas }}, {{ $conteoLicencias }}],
                    backgroundColor: ['#0d6efd55', '#ffc10755', '#dc354555', '#20c99755'],
                    borderColor: ['#0d6efd', '#ffc107', '#dc3545', '#20c997'],
                    borderWidth: 1
                }]
            },
            options: { scales: { y: { beginAtZero: true } } }
        });

        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['Participante', 'Aprendiz', 'Habilidoso', 'Experto'],
                datasets: [{
                    label: 'Estudiantes',
                    data: [{{ $participanteCount }}, {{ $aprendizCount }}, {{ $habilidosoCount }}, {{ $expertoCount }}],
                    backgroundColor: ['#dc354555', '#0d6efd55', '#ffc10755', '#20c99755'],
                    borderColor: ['#dc3545', '#0d6efd', '#ffc107', '#20c997'],
                    borderWidth: 1
                }]
            },
            options: { scales: { y: { beginAtZero: true } } }
        });
    </script>

    <!-- PDF Script -->
    <script src="https://rawgit.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>
    <script>
        document.getElementById('generatePdfLink')?.addEventListener('click', function (e) {
            e.preventDefault();
            const element = document.getElementById('container');
            html2pdf().from(element).set({
                margin: 10,
                filename: 'reporte_final_curso.pdf',
                html2canvas: { scale: 2 },
                jsPDF: { orientation: 'portrait', unit: 'mm', format: 'a4' }
            }).save();
        });
    </script>
</body>
</html>
