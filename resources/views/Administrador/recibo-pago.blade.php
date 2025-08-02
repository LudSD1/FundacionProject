<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Pago - {{ $pago->codigopago ?? 'N/A' }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }

        .recibo-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .recibo-header {
            background: linear-gradient(135deg, #1a4789 0%, #39a6cb 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .recibo-header h1 {
            margin: 0;
            font-size: 2.5em;
            font-weight: bold;
        }

        .recibo-header .subtitle {
            font-size: 1.2em;
            opacity: 0.9;
            margin-top: 10px;
        }

        .recibo-body {
            padding: 40px;
        }

        .recibo-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }

        .info-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #1a4789;
        }

        .info-section h3 {
            color: #1a4789;
            margin: 0 0 15px 0;
            font-size: 1.1em;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .info-item:last-child {
            border-bottom: none;
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
            border-radius: 10px;
            padding: 25px;
            margin: 30px 0;
        }

        .payment-details h3 {
            color: #28a745;
            margin: 0 0 20px 0;
            text-align: center;
            font-size: 1.3em;
        }

        .amount-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 2px solid #28a745;
            font-size: 1.1em;
        }

        .amount-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 1.3em;
            color: #28a745;
        }

        .footer {
            background: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }

        .footer p {
            margin: 5px 0;
            color: #6c757d;
        }

        .qr-code {
            text-align: center;
            margin: 20px 0;
        }

        .qr-code img {
            width: 100px;
            height: 100px;
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
            letter-spacing: 1px;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .recibo-container {
                box-shadow: none;
                border-radius: 0;
            }

            .no-print {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .recibo-info {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .recibo-header h1 {
                font-size: 2em;
            }

            .recibo-body {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    @if(!isset($pago))
        <div style="text-align: center; padding: 50px; font-family: Arial, sans-serif;">
            <h1 style="color: #dc3545;">Error</h1>
            <p>No se encontró la información del pago.</p>
            <p>Por favor, verifica que el pago existe y vuelve a intentar.</p>
            <button onclick="window.close()" style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
                Cerrar
            </button>
        </div>
    @else
    <div class="recibo-container">
        <!-- Header del Recibo -->
        <div class="recibo-header">
            <h1>RECIBO DE PAGO</h1>
            <div class="subtitle">Fundación Educar para la Vida</div>
            <div style="margin-top: 20px;">
                <span class="status-badge">PAGO CONFIRMADO</span>
            </div>
        </div>

        <!-- Cuerpo del Recibo -->
        <div class="recibo-body">
            <!-- Información del Pago -->
            <div class="recibo-info">
                <div class="info-section">
                    <h3>Información del Estudiante</h3>
                    <div class="info-item">
                        <span class="info-label">Nombre:</span>
                        <span class="info-value">{{ $pago->user->name }} {{ $pago->user->lastname1 }} {{ $pago->user->lastname2 }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{ $pago->user->email }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Celular:</span>
                        <span class="info-value">{{ $pago->user->Celular }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">País:</span>
                        <span class="info-value">{{ $pago->user->PaisReside }}</span>
                    </div>
                </div>

                <div class="info-section">
                    <h3>Información del Curso</h3>
                    <div class="info-item">
                        <span class="info-label">Curso:</span>
                        <span class="info-value">{{ $pago->curso->nombreCurso }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Categoría:</span>
                        <span class="info-value">{{ $pago->curso->categoria->nombre ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Duración:</span>
                        <span class="info-value">{{ $pago->curso->duracion ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Modalidad:</span>
                        <span class="info-value">{{ $pago->curso->modalidad ?? 'N/A' }}</span>
                    </div>
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

            <!-- Información Adicional -->
            <div class="recibo-info">
                <div class="info-section">
                    <h3>Información del Pago</h3>
                    <div class="info-item">
                        <span class="info-label">Código de Pago:</span>
                        <span class="info-value">{{ $pago->codigopago }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Fecha de Pago:</span>
                        <span class="info-value">{{ $pago->created_at->format('d/m/Y H:i:s') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Fecha de Confirmación:</span>
                        <span class="info-value">{{ now()->format('d/m/Y H:i:s') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Método de Pago:</span>
                        <span class="info-value">Transferencia Bancaria</span>
                    </div>
                </div>

                <div class="info-section">
                    <h3>Información de Contacto</h3>
                    <div class="info-item">
                        <span class="info-label">Fundación:</span>
                        <span class="info-value">Educar para la Vida</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Teléfono:</span>
                        <span class="info-value">+591 72087186</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email:</span>
                        <span class="info-value">contacto@educarparalavida.org.bo</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">País:</span>
                        <span class="info-value">Bolivia</span>
                    </div>
                </div>
            </div>

            <!-- Código QR (opcional) -->
            <div class="qr-code">
                <p><strong>Escanea para verificar el recibo</strong></p>
                <div style="width: 100px; height: 100px; background: #f8f9fa; border: 1px solid #dee2e6; margin: 0 auto; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                    <span style="color: #6c757d; font-size: 0.8em;">QR Code</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Fundación Educar para la Vida</strong></p>
            <p>Este documento es un comprobante oficial de pago</p>
            <p>Para consultas: contacto@educarparalavida.org.bo</p>
            <p style="margin-top: 20px; font-size: 0.9em; color: #adb5bd;">
                Recibo generado el {{ now()->format('d/m/Y H:i:s') }}
            </p>
        </div>
    </div>

    <!-- Botones de acción (solo para pantalla) -->
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="background: #1a4789; color: white; border: none; padding: 12px 24px; border-radius: 8px; cursor: pointer; margin: 5px;">
            <i class="bi bi-printer"></i> Imprimir Recibo
        </button>
        <button onclick="window.close()" style="background: #6c757d; color: white; border: none; padding: 12px 24px; border-radius: 8px; cursor: pointer; margin: 5px;">
            <i class="bi bi-x-circle"></i> Cerrar
        </button>
    </div>
    @endif
</body>
</html>
