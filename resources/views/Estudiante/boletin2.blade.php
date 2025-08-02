<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boleta de Calificaciones</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            color: #333;
        }

        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        /* Header mejorado */
        .header {
            background: linear-gradient(135deg, #1A4789 0%, #2563eb 100%);
            padding: 30px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: rgba(255,255,255,0.1);
            transform: rotate(45deg);
        }

        .header-content {
            display: grid;
            grid-template-columns: auto 1fr auto;
            align-items: center;
            gap: 30px;
            position: relative;
            z-index: 2;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 15px;
            backdrop-filter: blur(10px);
        }

        .logo-principal {
            height: 80px;
            width: auto;
            filter: brightness(0) invert(1); /* Hace la imagen blanca */
            max-width: 100%;
            object-fit: contain;
        }

        .logo-acceder {
            height: 30px;
            width: auto;
            max-width: 100%;
            object-fit: contain;
        }

        .header-title {
            text-align: center;
        }

        .header-title h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .header-title p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* Navegación mejorada */
        .nav-container {
            padding: 20px 30px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .nav-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
            transform: translateY(-2px);
        }

        /* Contenido principal */
        .content {
            padding: 40px;
        }

        /* Información del estudiante */
        .student-info {
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            border-left: 5px solid #0ea5e9;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-item i {
            color: #0ea5e9;
            width: 20px;
        }

        .info-label {
            font-weight: 600;
            color: #374151;
            margin-right: 10px;
        }

        .info-value {
            color: #1f2937;
        }

        /* Resumen de calificaciones */
        .summary-container {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border: 1px solid #e5e7eb;
        }

        .summary-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .summary-item {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #e5e7eb;
        }

        .summary-item h3 {
            font-size: 0.9rem;
            color: #6b7280;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .summary-item .value {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
        }

        /* Estados mejorados */
        .estado {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .estado-experto {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .estado-habilidoso {
            background: linear-gradient(135deg, #0ea5e9, #0284c7);
            color: white;
        }

        .estado-aprendiz {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .estado-reprobado {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        /* Tabla mejorada */
        .table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border: 1px solid #e5e7eb;
            margin-bottom: 30px;
        }

        .table-header {
            background: linear-gradient(135deg, #1f2937, #374151);
            color: white;
            padding: 20px 30px;
        }

        .table-header h2 {
            font-size: 1.3rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background: #f9fafb;
            font-weight: 600;
            color: #374151;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tr:hover {
            background: #f9fafb;
        }

        /* Comentarios */
        .comments-container {
            background: #fefce8;
            border: 1px solid #facc15;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .comments-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #92400e;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .comments-text {
            color: #78350f;
            font-style: italic;
            line-height: 1.6;
        }

        /* Firma */
        .signature-container {
            text-align: center;
            padding: 40px;
            background: #f9fafb;
            border-radius: 12px;
            border-top: 3px solid #1A4789;
        }

        .signature-image {
            width: 150px;
            height: auto;
            margin-bottom: 15px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }

        .signature-text {
            margin: 8px 0;
            color: #374151;
        }

        .signature-name {
            font-weight: 700;
            font-size: 1.1rem;
            color: #1f2937;
        }

        .signature-title {
            font-weight: 600;
            color: #6b7280;
        }

        .signature-institution {
            font-size: 0.9rem;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .content {
                padding: 20px;
            }

            .header-content {
                grid-template-columns: 1fr;
                text-align: center;
                gap: 15px;
            }

            .header-title h1 {
                font-size: 1.8rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .summary-grid {
                grid-template-columns: 1fr;
            }

            .nav-buttons {
                justify-content: center;
            }

            .table-responsive {
                font-size: 0.9rem;
            }

            th, td {
                padding: 10px 8px;
            }
        }

        /* Estilos para impresión */
        @media print {
            @page {
                size: letter;
                margin: 0.5in;
            }

            body {
                background: white;
                padding: 0;
                font-size: 12pt;
                line-height: 1.4;
            }

            .nav-container {
                display: none;
            }

            .main-container {
                box-shadow: none;
                border-radius: 0;
                max-width: none;
                margin: 0;
            }

            .btn {
                display: none;
            }

            /* Header para impresión */
            .header {
                background: white !important;
                color: #1A4789 !important;
                border: 2px solid #1A4789;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                padding: 15pt !important;
                page-break-inside: avoid;
            }

            .header::before {
                display: none !important;
            }

            .header-content {
                display: grid !important;
                grid-template-columns: 1fr 2fr 1fr !important;
                align-items: center !important;
                gap: 15pt !important;
            }

            .header-title {
                text-align: center !important;
            }

            .header-title h1 {
                color: #1A4789 !important;
                text-shadow: none !important;
                font-size: 18pt !important;
                margin-bottom: 5pt !important;
                font-weight: bold !important;
            }

            .header-title p {
                color: #666 !important;
                font-size: 10pt !important;
                margin: 0 !important;
            }

            .logo-container {
                background: white !important;
                border: 1px solid #ddd;
                padding: 10pt;
            }

            .logo-principal {
                filter: none !important; /* Quita el filtro blanco para impresión */
                max-height: 60pt !important;
                height: auto !important;
                width: auto !important;
                display: block !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .logo-acceder {
                max-height: 25pt !important;
                height: auto !important;
                width: auto !important;
                display: block !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            /* Ajustes de contenido para impresión */
            .content {
                padding: 20pt;
            }

            .student-info {
                background: white !important;
                border: 1px solid #ddd;
                margin-bottom: 15pt;
            }

            .summary-container {
                margin-bottom: 15pt;
                page-break-inside: avoid;
            }

            .table-container {
                margin-bottom: 15pt;
                page-break-inside: avoid;
            }

            .table-header {
                background: white !important;
                color: #1A4789 !important;
                border-bottom: 2px solid #1A4789;
            }

            th {
                background: #f5f5f5 !important;
                color: #333 !important;
                border-bottom: 1px solid #ddd;
            }

            .estado {
                background: white !important;
                color: #333 !important;
                border: 1px solid #ddd;
            }

            .comments-container {
                background: white !important;
                border: 1px solid #ddd;
                margin-bottom: 15pt;
            }

            .comments-title {
                color: #1A4789 !important;
            }

            .signature-container {
                background: white !important;
                border-top: 2px solid #1A4789;
                page-break-inside: avoid;
            }

            /* Evitar saltos de página innecesarios */
            .summary-grid {
                page-break-inside: avoid;
            }

            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            /* Reducir espacios para aprovechar mejor la página */
            .info-grid {
                gap: 10pt;
            }

            .summary-grid {
                gap: 10pt;
            }

            .summary-item {
                padding: 10pt;
            }

            th, td {
                padding: 8pt 10pt;
                font-size: 11pt;
            }
        }

        /* Animaciones */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .content > * {
            animation: fadeInUp 0.6s ease-out;
        }

        .content > *:nth-child(2) { animation-delay: 0.1s; }
        .content > *:nth-child(3) { animation-delay: 0.2s; }
        .content > *:nth-child(4) { animation-delay: 0.3s; }
    </style>
</head>

<body>
    <div class="main-container">
        <!-- Header -->
        <header class="header">
            <div class="header-content">
                <div class="logo-container">
                    <img src="{{asset('assets/img/logof.png')}}" alt="Logo Principal" class="logo-principal">
                </div>
                <div class="header-title">
                    <h1>BOLETA DE CALIFICACIONES</h1>
                    <p>Sistema Académico Integrado</p>
                </div>
                <div class="logo-container">
                    <img src="{{asset('assets/img/Acceder.png')}}" alt="Logo Acceder" class="logo-acceder">
                </div>
            </div>
        </header>

        <!-- Navegación -->
        <nav class="nav-container">
            <div class="nav-buttons">
                <a href="javascript:history.back()" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Volver
                </a>
                <button class="btn btn-primary" id="generatePdfBtn">
                    <i class="fas fa-file-pdf"></i>
                    Generar PDF
                </button>
                <button class="btn btn-primary" id="printBtn">
                    <i class="fas fa-print"></i>
                    Imprimir
                </button>
            </div>
        </nav>

        <!-- Contenido principal -->
        <div class="content" id="printableContent">
            <!-- Información del estudiante -->
            <div class="student-info">
                <div class="info-grid">
                    <div class="info-item">
                        <i class="fas fa-user"></i>
                        <span class="info-label">Estudiante:</span>
                        <span class="info-value">{{$inscritos->estudiantes->name}} {{$inscritos->estudiantes->lastname1}} {{$inscritos->estudiantes->lastname2}}</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-book"></i>
                        <span class="info-label">Curso:</span>
                        <span class="info-value">{{$inscritos->cursos->nombreCurso}}</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span class="info-label">Docente:</span>
                        <span class="info-value">{{$inscritos->cursos->docente->name}} {{$inscritos->cursos->docente->lastname1}} {{$inscritos->cursos->docente->lastname2}}</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-calendar"></i>
                        <span class="info-label">Periodo:</span>
                        <span class="info-value">{{$inscritos->cursos->fecha_ini}} al {{$inscritos->cursos->fecha_fin}}</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-layer-group"></i>
                        <span class="info-label">Nivel:</span>
                        <span class="info-value">{{$inscritos->cursos->nivel}}</span>
                    </div>
                    {{-- <div class="info-item">
                        <i class="fas fa-clock"></i>
                        <span class="info-label">Horario:</span>
                        <span class="info-value">
                            @foreach(json_decode($inscritos->cursos->horarios->dias) as $dia)
                                {{$dia}},
                            @endforeach
                            De {{$inscritos->cursos->horarios->hora_ini}} a {{$inscritos->cursos->horarios->hora_fin}}
                        </span>
                    </div> --}}
                </div>
            </div>

            <!-- Resumen de calificaciones -->
            <div class="summary-container">
                <h2 class="summary-title">
                    <i class="fas fa-chart-line"></i>
                    Resumen de Calificaciones
                </h2>
                <div class="summary-grid">
                    <div class="summary-item">
                        <h3>Promedio Actividades</h3>
                        <div class="value">{{$resumen['promedio_actividades']}}</div>
                        <small>(70%)</small>
                    </div>
                    <div class="summary-item">
                        <h3>Asistencia</h3>
                        <div class="value">{{$resumen['porcentaje_asistencia']}}</div>
                        <small>(30%)</small>
                    </div>
                    <div class="summary-item">
                        <h3>Nota Final</h3>
                        <div class="value">{{$resumen['nota_final']}}</div>
                    </div>
                    <div class="summary-item">
                        <h3>Estado</h3>
                        <span class="estado estado-{{strtolower($resumen['estado'])}}">
                            <i class="fas fa-check-circle"></i>
                            {{$resumen['estado']}}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Detalle de actividades -->
            <div class="table-container">
                <div class="table-header">
                    <h2>
                        <i class="fas fa-tasks"></i>
                        Detalle de Actividades
                    </h2>
                </div>
                <div class="table-responsive">
                    <table>
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
                                <td>
                                    <span class="estado estado-{{strtolower($actividad['estado'])}}">
                                        @if(strtolower($actividad['estado']) == 'experto')
                                            <i class="fas fa-star"></i>
                                        @elseif(strtolower($actividad['estado']) == 'habilidoso')
                                            <i class="fas fa-check"></i>
                                        @elseif(strtolower($actividad['estado']) == 'aprendiz')
                                            <i class="fas fa-exclamation-triangle"></i>
                                        @else
                                            <i class="fas fa-times"></i>
                                        @endif
                                        {{$actividad['estado']}}
                                    </span>
                                </td>
                                <td>{{$actividad['fecha']}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Comentarios del docente -->
            @if(isset($boletin))
            <div class="comments-container">
                <h2 class="comments-title">
                    <i class="fas fa-comment"></i>
                    Comentarios del Docente
                </h2>
                <p class="comments-text">
                    {{$boletin->comentario_boletin ?? 'Sin comentarios'}}
                </p>
            </div>
            @endif

            <!-- Firma -->
            <div class="signature-container">
                <img class="signature-image" src="{{asset('assets/img/firma digital.png')}}" alt="Firma Digital">
                <p class="signature-text signature-name">Mba. Roxana Araujo Romay</p>
                <p class="signature-text signature-title">Directora Ejecutiva</p>
                <p class="signature-text signature-institution">Dirección Ejecutiva: Fundación Educar para la Vida</p>
            </div>
        </div>
    </div>

    <script>
        // Función para generar PDF
        document.getElementById('generatePdfBtn').addEventListener('click', function() {
            window.print();
        });

        // Función para imprimir
        document.getElementById('printBtn').addEventListener('click', function() {
            window.print();
        });

        // Efecto de carga suave
        document.addEventListener('DOMContentLoaded', function() {
            document.body.style.opacity = '0';
            setTimeout(() => {
                document.body.style.transition = 'opacity 0.5s ease-in-out';
                document.body.style.opacity = '1';
            }, 100);
        });

        // Funcionalidad adicional para mejorar la experiencia
        document.querySelectorAll('.summary-item').forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 4px 12px rgba(0,0,0,0.1)';
            });

            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
        });
    </script>
</body>
</html>
