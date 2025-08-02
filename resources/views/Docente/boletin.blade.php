<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boleta de Calificaciones - Vista Docente</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Montserrat Font -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8f9fa;
        }

        .header-main {
            background: linear-gradient(to right bottom, #1A4789 49.5%, #FFFF 50%);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .logo-container img {
            max-height: 80px;
            width: auto;
        }

        h1, h2 {
            font-weight: 600;
            color: #1A4789;
        }

        .custom-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .table thead th {
            background-color: #63becf;
            color: white;
            font-weight: 500;
        }

        .estado-badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 500;
            display: inline-block;
        }

        .estado-experto {
            background-color: #28a745;
            color: white;
        }

        .estado-habilidoso {
            background-color: #17a2b8;
            color: white;
        }

        .estado-aprendiz {
            background-color: #ffc107;
            color: #000;
        }

        .estado-reprobado {
            background-color: #dc3545;
            color: white;
        }

        .info-container {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .firma-container {
            margin-top: 40px;
            text-align: center;
        }

        .firma {
            width: 150px;
            margin-bottom: 15px;
        }

        .firma-texto {
            font-size: 14px;
            margin: 5px 0;
            color: #495057;
        }

        .btn-custom {
            background-color: #63becf;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background-color: #4fa8b9;
            color: white;
        }

        .btn-custom-primary {
            background-color: #1A4789;
            color: white;
        }

        .btn-custom-primary:hover {
            background-color: #153a6f;
            color: white;
        }

        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }

        .comentarios-form {
            background-color: #ffffff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin: 20px 0;
        }

        .comentarios-form textarea {
            width: 100%;
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            font-family: 'Montserrat', sans-serif;
            resize: vertical;
            min-height: 120px;
        }

        .comentarios-form textarea:focus {
            border-color: #63becf;
            box-shadow: 0 0 0 0.2rem rgba(99, 190, 207, 0.25);
            outline: none;
        }

        .comentarios-form label {
            font-weight: 600;
            color: #1A4789;
            margin-bottom: 10px;
        }
    </style>
</head>

<body class="py-4">
    <div class="container">
        <div class="text-center mb-4">
            <a href="javascript:history.back()" class="btn btn-custom me-2">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <button class="btn btn-custom me-2" id="generatePdfLink">
                <i class="fas fa-file-pdf"></i> Generar PDF
            </button>
            <a href="{{ route('enviarBoletin', encrypt($inscritos->id)) }}" class="btn btn-custom">
                <i class="fas fa-envelope"></i> Enviar por Correo
            </a>
        </div>

        <div class="card custom-card">
            <div class="card-body">
                <header class="header-main d-flex justify-content-between align-items-center mb-4">
                    <div class="logo-container">
                        <img src="{{asset('assets/img/logof.png')}}" alt="Logo Fundación" class="img-fluid">
                    </div>
                    <div class="logo-container">
                        <img src="{{asset('assets/img/logoedin.png')}}" alt="Logo EDIN" class="img-fluid">
                    </div>
                </header>

                <h1 class="text-center mb-4">BOLETA DE CALIFICACIONES</h1>

                <div class="info-container">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Estudiante:</strong> {{$inscritos->estudiantes->name}} {{$inscritos->estudiantes->lastname1}} {{$inscritos->estudiantes->lastname2}}</p>
                            <p><strong>Curso:</strong> {{$inscritos->cursos->nombreCurso}}</p>
                            <p><strong>Docente:</strong> {{$inscritos->cursos->docente->name}} {{$inscritos->cursos->docente->lastname1}} {{$inscritos->cursos->docente->lastname2}}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Periodo:</strong> {{$inscritos->cursos->fecha_ini}} al {{$inscritos->cursos->fecha_fin}}</p>
                            <p><strong>Nivel:</strong> {{$inscritos->cursos->nivel->nombre}}</p>
                            <p><strong>Horario:</strong>
                                @foreach(json_decode($inscritos->cursos->horarios->dias) as $dia)
                                    {{$dia}},
                                @endforeach
                                De {{$inscritos->cursos->horarios->hora_ini}} a {{$inscritos->cursos->horarios->hora_fin}}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card custom-card">
                    <div class="card-body">
                        <h2 class="card-title">Resumen de Calificaciones</h2>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Promedio de Actividades (70%):</strong> {{$resumen['promedio_actividades']}}</p>
                                <p><strong>Porcentaje de Asistencia (30%):</strong> {{$resumen['porcentaje_asistencia']}}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Nota Final:</strong> {{$resumen['nota_final']}}</p>
                                <p><strong>Estado:</strong>
                                    <span class="estado-badge estado-{{strtolower($resumen['estado'])}}">
                                        {{$resumen['estado']}}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card custom-card">
                    <div class="card-body">
                        <h2 class="card-title">Detalle de Actividades</h2>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tema</th>
                                        <th>Subtema</th>
                                        <th>Actividad</th>
                                        <th>Tipo</th>
                                        <th>Nota</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($actividadesData as $actividad)
                                    <tr>
                                        <td>{{$actividad['tema']}}</td>
                                        <td>{{$actividad['subtema']}}</td>
                                        <td>{{$actividad['actividad']}}</td>
                                        <td>{{$actividad['tipo']}}</td>
                                        <td>{{$actividad['nota']}}</td>
                                        <td>{{$actividad['estado']}}</td>
                                        <td>{{$actividad['fecha']}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <form action="{{ route('guardar_boletin') }}" method="POST" class="comentarios-form">
                    @csrf
                    <input type="hidden" name="estudiante" value="{{$inscritos->id}}">
                    <input type="hidden" name="notafinal" value="{{$resumen['nota_final']}}">
                    <input type="hidden" name="evaluaciones" value="evaluaciones">
                    <input type="hidden" name="notaEvaluacion" value="{{$resumen['promedio_actividades']}}">
                    <input type="hidden" name="tareas" value="tareas">
                    <input type="hidden" name="notaTarea" value="{{$resumen['porcentaje_asistencia']}}">

                    <div class="mb-4">
                        <label for="comentario" class="form-label">Comentarios y recomendaciones:</label>
                        <textarea name="comentario" id="comentario" class="form-control" required>{{$boletin->comentario_boletin ?? ''}}</textarea>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-custom btn-custom-primary">
                            <i class="fas fa-save"></i> Guardar Boletín
                        </button>
                    </div>
                </form>

                <div class="firma-container">
                    <img src="{{asset('assets/img/firma digital.png')}}" alt="Firma" class="firma">
                    <p class="firma-texto fw-bold">Mba. Roxana Araujo Romay</p>
                    <p class="firma-texto">Directora Ejecutiva</p>
                    <p class="firma-texto">DIRECCIÓN EJECUTIVA: FUNDACIÓN EDUCAR PARA LA VIDA</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Font Awesome -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-code.js" crossorigin="anonymous"></script>
    <script>
        document.getElementById('generatePdfLink').addEventListener('click', function() {
            window.print();
        });
    </script>
</body>
</html>
