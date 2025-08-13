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
            // Función para generar el PDF con mejoras
            function generatePdf(format = 'letter') {
                var element = document.getElementById('container');

                // Mostrar indicador de carga
                const loadingIndicator = document.createElement('div');
                loadingIndicator.innerHTML = `
                    <div class="position-fixed top-50 start-50 translate-middle bg-white p-4 rounded shadow" style="z-index: 9999;">
                        <div class="d-flex align-items-center">
                            <div class="spinner-border text-primary me-3" role="status"></div>
                            <span>Generando PDF...</span>
                        </div>
                    </div>
                `;
                document.body.appendChild(loadingIndicator);

                // Configuración mejorada
                const opt = {
                    margin: [10, 10, 10, 10], // top, left, bottom, right
                    filename: `lista_estudiantes_${new Date().toISOString().split('T')[0]}.pdf`,
                    image: {
                        type: 'jpeg',
                        quality: 1.0 // Máxima calidad
                    },
                    html2canvas: {
                        scale: 3, // Mayor escala para mejor calidad
                        useCORS: true,
                        letterRendering: true,
                        logging: false
                    },
                    jsPDF: {
                        unit: 'mm',
                        format: format,
                        orientation: 'portrait',
                        compress: true
                    },
                    pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
                };

                // Generar PDF con manejo de errores
                html2pdf()
                    .set(opt)
                    .from(element)
                    .save()
                    .then(() => {
                        // Remover indicador de carga
                        document.body.removeChild(loadingIndicator);
                        console.log('PDF generado exitosamente');
                    })
                    .catch((error) => {
                        document.body.removeChild(loadingIndicator);
                        alert('Error al generar el PDF: ' + error.message);
                        console.error('Error:', error);
                    });
            }

            // Función para previsualizar PDF
            function previewPdf() {
                var element = document.getElementById('container');

                const opt = {
                    margin: [10, 10, 10, 10],
                    filename: 'preview.pdf',
                    image: { type: 'jpeg', quality: 0.8 },
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: 'mm', format: 'letter', orientation: 'portrait' }
                };

                html2pdf()
                    .set(opt)
                    .from(element)
                    .outputPdf('dataurlnewwindow'); // Abre en nueva ventana
            }

            // Event listeners
            document.getElementById('generatePdfLink').addEventListener('click', function (event) {
                event.preventDefault();
                generatePdf('letter'); // Tamaño carta por defecto
            });

            // Agregar botón de previsualización si no existe
            const previewBtn = document.createElement('a');
            previewBtn.href = '#';
            previewBtn.className = 'btn custom-btn';
            previewBtn.id = 'previewPdfLink';
            previewBtn.innerHTML = '👁️ Previsualizar';
            previewBtn.addEventListener('click', function(event) {
                event.preventDefault();
                previewPdf();
            });

            // Insertar botón de previsualización
            const buttonContainer = document.querySelector('.d-flex.justify-content-center.gap-3');
            buttonContainer.appendChild(previewBtn);

            // Agregar selector de formato
            const formatSelector = document.createElement('select');
            formatSelector.className = 'form-select d-inline-block w-auto ms-2';
            formatSelector.innerHTML = `
                <option value="letter">Carta (8.5x11")</option>
                <option value="a4">A4 (210x297mm)</option>
                <option value="legal">Legal (8.5x14")</option>
            `;

            const formatLabel = document.createElement('span');
            formatLabel.className = 'me-2 text-muted small';
            formatLabel.textContent = 'Formato:';

            buttonContainer.appendChild(formatLabel);
            buttonContainer.appendChild(formatSelector);

            // Actualizar función de generación para usar el formato seleccionado
            document.getElementById('generatePdfLink').addEventListener('click', function (event) {
                event.preventDefault();
                const selectedFormat = formatSelector.value;
                generatePdf(selectedFormat);
            });
        });
    </script>
</body>
</html>
