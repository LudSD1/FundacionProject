<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Correo - Aprendo Hoy</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }

        .email-container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #1e40af 0%, #0891b2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
            margin: -30px -30px 30px -30px;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -100px;
            width: 300px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(20deg);
        }

        .logos-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .logo-left {
            color: white;
            text-align: left;
        }

        .logo-left h2 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }

        .logo-left p {
            margin: 0;
            font-size: 12px;
        }

        .logo-left .tagline {
            font-style: italic;
            font-size: 10px;
        }

        .logo-right {
            color: #fbbf24;
            font-weight: bold;
            font-size: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .logo-right .hoy {
            background: white;
            color: #1e40af;
            padding: 2px 6px;
            border-radius: 4px;
            margin-left: 3px;
        }

        .header h1 {
            margin: 0;
            font-size: 2em;
            font-weight: bold;
            position: relative;
            z-index: 1;
        }

        .header .subtitle {
            font-size: 1.1em;
            opacity: 0.9;
            margin-top: 10px;
            position: relative;
            z-index: 1;
        }

        .content {
            padding: 20px 0;
        }

        .greeting {
            font-size: 1.3em;
            margin-bottom: 20px;
            color: #1e40af;
            text-align: center;
        }

        .info-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #1e40af;
        }

        .info-section h3 {
            color: #1e40af;
            margin: 0 0 15px 0;
            font-size: 1.1em;
        }

        .verification-details {
            background: #e8f4fd;
            border: 2px solid #0891b2;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }

        .verification-details h3 {
            color: #0891b2;
            margin: 0 0 15px 0;
        }

        .cta-button {
            display: inline-block;
            background: #0891b2;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
            font-size: 1.1em;
            transition: background-color 0.3s;
        }

        .cta-button:hover {
            background: #0675a0;
        }

        .security-info {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }

        .security-info h4 {
            color: #856404;
            margin: 0 0 10px 0;
            font-size: 1em;
        }

        .security-info ul {
            margin: 0;
            padding-left: 20px;
            color: #856404;
        }

        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            margin-top: 30px;
            border-top: 1px solid #e9ecef;
        }

        .footer p {
            margin: 5px 0;
            color: #6c757d;
            font-size: 0.9em;
        }

        .status-badge {
            display: inline-block;
            background: #0891b2;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.9em;
            margin: 10px 0;
        }

        .url-fallback {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            margin: 20px 0;
            word-break: break-all;
            font-family: monospace;
            font-size: 0.9em;
        }

        @media (max-width: 600px) {
            body {
                padding: 10px;
            }

            .email-container {
                padding: 20px;
            }

            .header {
                margin: -20px -20px 20px -20px;
                padding: 20px;
            }

            .header h1 {
                font-size: 1.5em;
            }

            .logos-container {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }

            .logo-left {
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <!-- Logos Container -->
            <div class="logos-container">
                <div class="logo-left">
                    <h2>📚 FUNDACIÓN</h2>
                    <p>EDUCAR PARA LA VIDA</p>
                    <p class="tagline">Crecemos para Servir...</p>
                </div>
                <div class="logo-right">
                    APRENDO<span class="hoy">HOY</span>
                </div>
            </div>

            <h1>VERIFICACIÓN DE CORREO</h1>
            <div class="subtitle">Confirma tu cuenta para comenzar</div>
            <div>
                <span class="status-badge">VERIFICACIÓN PENDIENTE</span>
            </div>
        </div>

        <!-- Contenido -->
        <div class="content">
            <div class="greeting">
                <strong>¡Bienvenido/a a Aprendo Hoy! 🎓</strong>
            </div>

            <p>Gracias por registrarte en nuestra plataforma educativa de la Fundación Educar Para La Vida. Estamos
                emocionados de tenerte con nosotros en esta comunidad de aprendizaje.</p>

            <!-- Información de Verificación -->
            <div class="verification-details">
                <h3>🔐 Confirma tu Dirección de Correo</h3>
                <p>Para completar tu registro y acceder a todos nuestros cursos y recursos educativos, necesitamos
                    verificar tu dirección de correo electrónico.</p>

                <!-- Call to Action -->
                <div style="margin: 30px 0;">
                    <a href="   {{ $verificationUrl }}" class="cta-button" target="_blank">
                        ✅ CONFIRMAR MI CORREO ELECTRÓNICO
                    </a>
                </div>
            </div>

            <!-- Información de Seguridad -->
            <div class="security-info">
                <h4>🔒 Información Importante de Seguridad:</h4>
                <ul>
                    <li>Este enlace es válido por <strong>60 minutos</strong></li>
                    <li>Por seguridad, solo puedes usar este enlace <strong>una vez</strong></li>
                    <li>Si el enlace expira, puedes solicitar uno nuevo desde tu cuenta</li>
                    <li>Si no creaste esta cuenta, puedes ignorar este mensaje</li>
                </ul>
            </div>

            <!-- Próximos Pasos -->
            <div class="info-section">
                <h3>📋 ¿Qué sigue después de verificar?</h3>
                <ul style="margin: 0; padding-left: 20px;">
                    <li>Acceso completo a tu panel de estudiante</li>
                    <li>Navegación por nuestro catálogo de cursos</li>
                    <li>Participación en nuestra comunidad educativa</li>
                    <li>Recepción de actualizaciones importantes</li>
                    <li>Soporte técnico y académico personalizado</li>
                </ul>
            </div>

            <!-- URL de Respaldo -->
            <div style="margin: 30px 0;">
                <p><strong>¿Problemas con el botón?</strong></p>
                <p style="font-size: 0.9em;">Si tienes dificultades para hacer clic en el botón, copia y pega la
                    siguiente URL en tu navegador:</p>
                <div class="url-fallback">
                      {{ $verificationUrl }}
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p><strong>Fundación Educar para la Vida</strong></p>
                <p><em>Crecemos para Servir</em></p>
                <p>Plataforma Educativa Aprendo Hoy</p>
                <hr style="border: none; border-top: 1px solid #e9ecef; margin: 15px 0;">
                <p>📧 contacto@educarparalavida.org.bo</p>
                <p>📞 +591 72087186</p>
                <p>🌐 www.aprendohoy.edu.bo</p>
                <p style="margin-top: 15px; font-size: 0.8em; color: #adb5bd;">
                    Email generado automáticamente - No responder a este correo
                </p>
            </div>
        </div>
</body>

</html>
