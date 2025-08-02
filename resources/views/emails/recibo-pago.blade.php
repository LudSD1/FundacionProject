<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Pago Confirmado</title>
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
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .header {
            background: linear-gradient(135deg, #1a4789 0%, #39a6cb 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
            margin: -30px -30px 30px -30px;
        }

        .header h1 {
            margin: 0;
            font-size: 2em;
            font-weight: bold;
        }

        .header .subtitle {
            font-size: 1.1em;
            opacity: 0.9;
            margin-top: 10px;
        }

        .content {
            padding: 20px 0;
        }

        .greeting {
            font-size: 1.2em;
            margin-bottom: 20px;
            color: #1a4789;
        }

        .info-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #1a4789;
        }

        .info-section h3 {
            color: #1a4789;
            margin: 0 0 15px 0;
            font-size: 1.1em;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 5px 0;
        }

        .info-label {
            font-weight: 600;
            color: #495057;
        }

        .info-value {
            color: #212529;
        }

        .payment-details {
            background: #e8f5e8;
            border: 2px solid #28a745;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .payment-details h3 {
            color: #28a745;
            margin: 0 0 15px 0;
            text-align: center;
        }

        .amount-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #28a745;
        }

        .amount-row:last-child {
            border-bottom: none;
            font-weight: bold;
            color: #28a745;
        }

        .cta-button {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }

        .cta-button:hover {
            background: #218838;
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
            background: #28a745;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.9em;
            margin: 10px 0;
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

            .info-item {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>RECIBO DE PAGO</h1>
            <div class="subtitle">Fundación Educar para la Vida</div>
            <div style="margin-top: 15px;">
                <span class="status-badge">PAGO CONFIRMADO</span>
            </div>
        </div>

        <!-- Contenido -->
        <div class="content">
            <div class="greeting">
                <strong>¡Hola {{ $pago->user->name }} {{ $pago->user->lastname1 }}!</strong>
            </div>

            <p>Tu pago ha sido confirmado exitosamente. A continuación encontrarás los detalles de tu transacción:</p>

            <!-- Información del Pago -->
            <div class="info-section">
                <h3>Información del Curso</h3>
                <div class="info-item">
                    <span class="info-label">Curso:</span>
                    <span class="info-value">{{ $pago->curso->nombreCurso }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Código de Pago:</span>
                    <span class="info-value">{{ $pago->codigopago }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Fecha de Confirmación:</span>
                    <span class="info-value">{{ now()->format('d/m/Y H:i:s') }}</span>
                </div>
            </div>

            <!-- Detalles del Pago -->
            <div class="payment-details">
                <h3>Detalles del Pago</h3>
                <div class="amount-row">
                    <span>Monto a Pagar:</span>
                    <span>{{ number_format($pago->monto_a_pagar, 2) }} Bs.</span>
                </div>
                <div class="amount-row">
                    <span>Monto Pagado:</span>
                    <span>{{ number_format($pago->monto_pagado, 2) }} Bs.</span>
                </div>
                @if($pago->monto_a_pagar > $pago->monto_pagado)
                <div class="amount-row">
                    <span>Saldo Pendiente:</span>
                    <span style="color: #dc3545;">{{ number_format($pago->monto_a_pagar - $pago->monto_pagado, 2) }} Bs.</span>
                </div>
                @endif
                <div class="amount-row">
                    <span>Estado:</span>
                    <span>PAGO CONFIRMADO</span>
                </div>
            </div>

            <!-- Call to Action -->
            <div style="text-align: center; margin: 30px 0;">
                <p><strong>Descarga tu recibo oficial:</strong></p>
                <a href="{{ $reciboUrl }}" class="cta-button" target="_blank">
                    📄 Ver Recibo Completo
                </a>
            </div>

            <div class="info-section">
                <h3>Próximos Pasos</h3>
                <ul style="margin: 0; padding-left: 20px;">
                    <li>Tu curso ha sido habilitado automáticamente</li>
                    <li>Puedes acceder al contenido desde tu panel de estudiante</li>
                    <li>Guarda este recibo para tus registros</li>
                    <li>Para consultas, contacta a nuestro equipo de soporte</li>
                </ul>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Fundación Educar para la Vida</strong></p>
            <p>Este documento es un comprobante oficial de pago</p>
            <p>📧 contacto@educarparalavida.org.bo</p>
            <p>📞 +591 72087186</p>
            <p style="margin-top: 15px; font-size: 0.8em; color: #adb5bd;">
                Email generado el {{ now()->format('d/m/Y H:i:s') }}
            </p>
        </div>
    </div>
</body>
</html>
