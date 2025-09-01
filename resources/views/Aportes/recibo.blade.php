<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Pago</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .page-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-bottom: 30px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        .receipt-container {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .receipt-header {
            background: linear-gradient(135deg, #1A4789 0%, #2563eb 100%);
            padding: 30px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .receipt-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(50%, -50%);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .logo {
            height: 60px;
            width: auto;
        }

        .receipt-title {
            text-align: center;
            margin: 20px 0;
        }

        .receipt-title h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .receipt-code {
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        .receipt-body {
            padding: 40px;
        }

        .info-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .info-group h3 {
            color: #1A4789;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 500;
            color: #6b7280;
        }

        .info-value {
            font-weight: 600;
            color: #1f2937;
        }

        .payment-table {
            background: #f8fafc;
            border-radius: 12px;
            overflow: hidden;
            margin-top: 30px;
        }

        .payment-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .payment-table th {
            background: #1A4789;
            color: white;
            padding: 20px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .payment-table td {
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
        }

        .payment-table tr:last-child td {
            border-bottom: none;
        }

        .payment-table .description {
            max-width: 300px;
            word-wrap: break-word;
            color: #374151;
        }

        .payment-table .amount {
            font-weight: 600;
            color: #1f2937;
            text-align: right;
        }

        .total-row {
            background: #f1f5f9;
            font-weight: 600;
        }

        .total-row td {
            border-top: 2px solid #1A4789;
            padding: 15px 20px;
        }

        .change-row {
            background: #dcfce7;
        }

        .qr-section {
            margin-top: 20px;
            text-align: center;
            padding: 20px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .qr-section p {
            margin-bottom: 15px;
            color: #475569;
            font-weight: 500;
            font-size: 14px;
        }

        .qr-code-container {
            display: inline-block;
            padding: 15px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .qr-code-container img {
            display: block;
            width: 120px;
            height: 120px;
            border-radius: 4px;
        }

        .qr-placeholder {
            width: 120px;
            height: 120px;
            background: linear-gradient(45deg, #f0f0f0 25%, transparent 25%),
                linear-gradient(-45deg, #f0f0f0 25%, transparent 25%),
                linear-gradient(45deg, transparent 75%, #f0f0f0 75%),
                linear-gradient(-45deg, transparent 75%, #f0f0f0 75%);
            background-size: 20px 20px;
            background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
            border: 2px dashed #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #888;
            font-size: 12px;
            text-align: center;
            line-height: 1.2;
        }

        .watermark {
            position: absolute;
            bottom: 20px;
            right: 20px;
            opacity: 0.1;
            font-size: 100px;
            font-weight: 900;
            color: #1A4789;
            pointer-events: none;
            transform: rotate(-15deg);
        }

        .status-badge {
            display: inline-block;
            background: #10b981;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
        }

        @media (max-width: 768px) {
            .info-section {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .header-content {
                flex-direction: column;
                gap: 20px;
            }

            .receipt-body {
                padding: 20px;
            }

            .btn {
                font-size: 12px;
                padding: 10px 16px;
            }
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .action-buttons {
                display: none;
            }

            .receipt-container {
                box-shadow: none;
                border-radius: 0;
            }
        }
    </style>
</head>

<body>
    <div class="page-container">
        <!-- Botones de acción -->
        <div class="action-buttons">
            <a class="btn btn-secondary" href="{{ route('Inicio') }}">
                ← Volver
            </a>
            <button class="btn btn-primary" id="generatePdfBtn">
                📄 Generar PDF
            </button>
            <button class="btn btn-secondary" onclick="window.print()">
                🖨️ Imprimir
            </button>
            <button class="btn btn-secondary" id="generateQrBtn">
                📱 Generar QR
            </button>
        </div>

        <!-- Contenedor del recibo -->
        <div class="receipt-container" id="receiptContainer">
            <!-- Encabezado -->
            <div class="receipt-header">
                <div class="header-content">
                    <img src="{{ asset('assets/img/logof.png') }}" alt="Logo Izquierdo" class="logo">
                    <img src="{{ asset('assets/img/Acceder.png') }}" alt="Logo Derecho" class="logo">
                </div>

                <div class="receipt-title">
                    <h1>Recibo de Pago</h1>
                    <div class="receipt-code">Código: {{ $aporte->codigopago ?? $aporte->id }}</div>
                </div>
            </div>

            <!-- Cuerpo del recibo -->
            <div class="receipt-body">
                <div class="info-section">
                    <div class="info-group">
                        <h3>👤 Información del Estudiante</h3>
                        <div class="info-item">
                            <span class="info-label">Estudiante:</span>
                            <span
                                class="info-value">{{ $aporte->datosEstudiante ?? ($aporte->user->name ?? '') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Pagante:</span>
                            <span class="info-value">{{ $aporte->pagante ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">CI:</span>
                            <span class="info-value">{{ $aporte->user->CI ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="info-group">
                        <h3>📅 Información del Pago</h3>
                        <div class="info-item">
                            <span class="info-label">Fecha de emisión:</span>
                            <span
                                class="info-value">{{ $aporte->created_at ? $aporte->created_at->format('d/m/Y') : '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Estado:</span>
                            <span class="status-badge">
                                {{ $aporte->estado_pago ?? 'Pagado' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Tabla de pagos -->
                <div class="payment-table">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 60%;">Descripción</th>
                                <th style="width: 40%;">Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="description">
                                    {{ $aporte->DescripcionDelPago ?? 'Pago de curso' }}
                                </td>
                                <td class="amount">{{ number_format($aporte->monto, 2) }} Bs</td>
                            </tr>
                            <tr class="total-row">
                                <td>Monto Cancelado</td>
                                <td class="amount">{{ number_format($aporte->monto_pagado ?? $aporte->monto, 2) }} Bs
                                </td>
                            </tr>
                            <tr>
                                <td>Restante a Pagar</td>
                                <td class="amount">
                                    {{ number_format($aporte->monto - ($aporte->monto_pagado ?? $aporte->monto), 2) }}
                                    Bs</td>
                            </tr>
                            <tr class="change-row">
                                <td>Cambio</td>
                                <td class="amount">{{ number_format($aporte->cambio ?? 0, 2) }} Bs</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Sección del QR -->
                    <div class="qr-code-container">
                        @if (isset($qrCode))
                                <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="Código QR">
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Marca de agua -->
        <div class="watermark">PAGADO</div>
    </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode-generator/1.4.4/qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const generatePdfBtn = document.getElementById('generatePdfBtn');
            const generateQrBtn = document.getElementById('generateQrBtn');
            const receiptContainer = document.getElementById('receiptContainer');

            // Función para generar QR
            function generateQR() {
                try {
                    // Si no hay QR del servidor, generar uno con JavaScript
                    @if (!isset($qrCode) || empty($qrCode))
                        const receiptData = {
                            codigo: '{{ $aporte->codigopago ?? $aporte->id }}',
                            estudiante: '{{ $aporte->datosEstudiante ?? ($aporte->user->name ?? '') }}',
                            monto: '{{ number_format($aporte->monto, 2) }}',
                            fecha: '{{ $aporte->created_at ? $aporte->created_at->format('d/m/Y') : '-' }}',
                            estado: '{{ $aporte->estado_pago ?? 'Pagado' }}'
                        };

                        const qrData = JSON.stringify(receiptData);
                        const qr = qrcode(0, 'M');
                        qr.addData(qrData);
                        qr.make();

                        const qrContainer = document.getElementById('qrCodeImage');
                        if (qrContainer) {
                            qrContainer.innerHTML = qr.createImgTag(4, 4);

                            // Ajustar el estilo de la imagen generada
                            const img = qrContainer.querySelector('img');
                            if (img) {
                                img.style.width = '120px';
                                img.style.height = '120px';
                                img.style.border = 'none';
                                img.style.borderRadius = '4px';
                            }
                        }
                    @endif

                    console.log('QR procesado exitosamente');
                } catch (error) {
                    console.error('Error al generar QR:', error);
                    const qrContainer = document.getElementById('qrCodeImage');
                    if (qrContainer) {
                        qrContainer.innerHTML = 'Error al<br>generar QR';
                    }
                }
            }

            // Función para generar PDF
            function generatePDF() {
                const options = {
                    margin: 0.5,
                    filename: 'recibo_pago_{{ $aporte->codigopago ?? $aporte->id }}.pdf',
                    image: {
                        type: 'jpeg',
                        quality: 0.98
                    },
                    html2canvas: {
                        scale: 2,
                        useCORS: true,
                        allowTaint: true
                    },
                    jsPDF: {
                        unit: 'in',
                        format: 'a4',
                        orientation: 'portrait'
                    }
                };

                html2pdf()
                    .from(receiptContainer)
                    .set(options)
                    .save()
                    .then(function() {
                        console.log('PDF generado exitosamente');
                    })
                    .catch(function(error) {
                        console.error('Error al generar PDF:', error);
                        alert('Error al generar el PDF. Por favor, intente nuevamente.');
                    });
            }

            // Event listeners
            generatePdfBtn.addEventListener('click', generatePDF);
            generateQrBtn.addEventListener('click', generateQR);

            // Generar QR automáticamente al cargar (solo si no viene del servidor)
            @if (!isset($qrCode) || empty($qrCode))
                generateQR();
            @endif
        });

        function goBack() {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                alert('No hay página anterior');
            }
        }
    </script>
</body>

</html>
