<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado Generado - Aprendo Hoy</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #1e293b;
            max-width: 600px;
            margin: 0 auto;
            padding: 0;
            background-color: #f1f5f9;
        }
        .email-container {
            background-color: #ffffff;
            margin: 20px auto;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: #ffffff;
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header p {
            margin: 10px 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 40px 30px;
            text-align: center;
        }
        .greeting {
            font-size: 22px;
            font-weight: 700;
            color: #1e40af;
            margin-bottom: 20px;
        }
        .course-info {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
        }
        .course-name {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            display: block;
            margin-top: 5px;
        }
        .cta-button {
            display: inline-block;
            background-color: #2563eb;
            color: #ffffff !important;
            padding: 16px 32px;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 16px;
            margin: 20px 0;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
            transition: all 0.3s ease;
        }
        .footer {
            padding: 30px;
            background-color: #f8fafc;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            margin: 5px 0;
            font-size: 13px;
            color: #64748b;
        }
        .social-links {
            margin-top: 15px;
        }
        .social-links a {
            color: #1e40af;
            text-decoration: none;
            margin: 0 10px;
            font-weight: 600;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .badge-success { background-color: #dcfce7; color: #166534; }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <img src="{{ asset('assets/img/logof.png') }}" style="height: 60px; margin-bottom: 15px;" alt="Logo">
            <h1>¡Logro Alcanzado!</h1>
            <p>Tu esfuerzo ha dado resultados</p>
        </div>

        <div class="content">
            <div class="greeting">¡Felicidades, {{ $inscrito->estudiantes->name }}!</div>
            
            <p>Es un placer informarte que has completado con éxito todas las actividades requeridas para obtener tu certificación oficial.</p>

            <div class="course-info">
                <span class="badge badge-success">{{ strtoupper($inscrito->cursos->tipo) }} COMPLETADO</span>
                <span class="course-name">{{ $inscrito->cursos->nombreCurso }}</span>
            </div>

            <p>Tu certificado ya está disponible para su descarga y verificación en nuestra plataforma académica.</p>

            <a href="{{ $url }}" class="cta-button">Descargar Certificado</a>

            <p style="font-size: 14px; color: #64748b; margin-top: 20px;">
                Si el botón no funciona, copia y pega el siguiente enlace en tu navegador:<br>
                <span style="word-break: break-all; color: #3b82f6;">{{ $url }}</span>
            </p>
        </div>

        <div class="footer">
            <p><strong>Aprendo Hoy</strong> - Potenciando tu futuro profesional</p>
            <p>&copy; {{ date('Y') }} Fundación Educar Para La Vida. Todos los derechos reservados.</p>
            <div class="social-links">
                <a href="{{ route('Inicio') }}">Sitio Web</a>
                <a href="#">Soporte</a>
            </div>
        </div>
    </div>
</body>
</html>
