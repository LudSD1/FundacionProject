<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boleta de Calificaciones</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Montserrat Font from Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #a1a3a7;
            color: #000;
            font-family: 'Montserrat', sans-serif;
        }

        .header-main {
            background: linear-gradient(to right bottom, #1A4789 49.5%, #FFFF 50%);
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
        }

        .logo-izquierdo img {
            height: 80px;
            width: auto;
        }

        .logo-derecho img {
            height: 125px;
            width: auto;
        }

        .custom-btn {
            background-color: #63becf;
            color: white;
            border: 1px solid #63becf;
            font-weight: 500;
        }

        .custom-btn:hover {
            background-color: #52a8b9;
            border-color: #52a8b9;
        }

        .table th {
            background-color: #63becf;
            color: #fff;
            font-weight: 600;
        }

        .main-container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .info-container {
            margin-bottom: 20px;
        }

        h1 {
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
        }

        .info-text {
            font-weight: 500;
        }
    </style>
</head>

<body>
    <div class="container py-3">
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-center gap-3">
                    <a href="javascript:history.back()" class="btn custom-btn">
                        &#9668; Volver
                    </a>
                    <a href="#" class="btn custom-btn" id="generatePdfLink">Generar PDF</a>
                </div>
            </div>
        </div>

        <div class="main-container" id="container">
            <header class="header-main mb-4">
                <div class="header-container">
                    <a class="logo-izquierdo" href="../index.html">
                        <img src="{{asset('assets/img/logof.png')}}" alt="Logo">
                    </a>
                    <a class="logo-derecho" href="../index.html">
                        <img src="{{asset('assets/img/Acceder.png')}}" style="width: auto; height: 2.5rem;"  alt="Acceder">
                    </a>
                </div>
            </header>

            <h1 class="text-center mb-4">Lista de Estudiantes</h1>

            <div class="row info-container">
                <div class="col-md-6 info-text">
                    <p><strong>Estudiante:</strong> {{auth()->user()->name}} {{auth()->user()->lastname1}} {{auth()->user()->lastname2}}</p>
                    <p><strong>Docente:</strong> {{$curso->docente->name}} {{$curso->docente->lastname1}} {{$curso->docente->lastname2}}</p>
                    <p><strong>Periodo:</strong> {{ $curso->fecha_ini }} al {{ $curso->fecha_fin }}</p>
                </div>
                <div class="col-md-6 info-text">
                    <p><strong>Curso:</strong> {{ ucfirst(strtolower($curso->nombreCurso))}}</p>
                    <p><strong>Nivel:</strong> {{ ucfirst(strtolower($curso->nivel)) }}</p>
                    <p><strong>Horario:</strong>
                        @foreach ($horarios as $horarios)
                            {{$horarios->horario->dia}}
                            {{Carbon\Carbon::parse($horarios->horario->hora_inicio)->format('h:i A') }} a
                            {{Carbon\Carbon::parse($horarios->horario->hora_fin)->format('h:i A') }}
                            <br>
                        @endforeach
                    </p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nro</th>
                            <th>Nombre</th>
                            <th>Apellido Paterno</th>
                            <th>Apellido Materno</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($inscritos as $inscritos)
                            @if ($inscritos->cursos_id == $curso->id)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ isset($inscritos->estudiantes) ? $inscritos->estudiantes->name : 'Estudiante Eliminado' }}</td>
                                <td>{{ isset($inscritos->estudiantes) ? $inscritos->estudiantes->lastname1 : '' }}</td>
                                <td>{{ isset($inscritos->estudiantes) ? $inscritos->estudiantes->lastname2 : '' }}</td>
                            </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="4" class="text-center"><h4>NO HAY ALUMNOS INSCRITOS</h4></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="text-center mt-4">
                <p class="fw-bold">FUNDACIÓN EDUCAR PARA LA VIDA</p>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- html2pdf -->
    <script src="https://rawgit.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Función para generar el PDF
            function generatePdf() {
                var element = document.getElementById('container');

                // Opciones para html2pdf
                const opt = {
                    margin: 10,
                    filename: 'listadeEstudiantes.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                };

                // Generar PDF
                html2pdf().set(opt).from(element).save();
            }

            // Obtén el enlace por su ID
            var generatePdfLink = document.getElementById('generatePdfLink');

            // Agrega un evento de clic al enlace que llame a la función generatePdf
            generatePdfLink.addEventListener('click', function (event) {
                event.preventDefault();
                generatePdf();
            });
        });
    </script>
</body>
</html>