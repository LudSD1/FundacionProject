<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura Computarizada - SIAT</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
        }

        .invoice-container {
            width: 210mm;
            /* A4 width */
            min-height: 297mm;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .company-info {
            width: 60%;
        }

        .company-name {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 5px;
        }

        .invoice-details {
            width: 35%;
            border: 1px solid #000;
            padding: 10px;
            text-align: left;
        }

        .invoice-title {
            text-align: center;
            font-weight: bold;
            font-size: 20px;
            margin: 20px 0;
            text-transform: uppercase;
        }

        .customer-info {
            margin-bottom: 20px;
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            padding: 10px 0;
        }

        .info-row {
            display: flex;
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
            width: 150px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #eee;
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .totals {
            width: 40%;
            margin-left: auto;
        }

        .literal {
            margin-top: 10px;
            font-weight: bold;
            background-color: #f9f9f9;
            padding: 5px;
        }

        .footer {
            margin-top: 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 1px solid #000;
            padding-top: 20px;
        }

        .qr-code {
            width: 150px;
            height: 150px;
        }

        .leyendas {
            width: calc(100% - 170px);
            text-align: center;
            font-size: 10px;
        }

        .cuf-box {
            word-break: break-all;
            font-size: 9px;
        }

        @media print {
            body {
                background: none;
                margin: 0;
            }

            .invoice-container {
                box-shadow: none;
                margin: 0;
                width: 100%;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()"
            style="padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer;">Imprimir
            Factura</button>
        <button onclick="window.history.back()"
            style="padding: 10px 20px; background: #6c757d; color: white; border: none; cursor: pointer; margin-left: 10px;">Volver</button>
    </div>

    <div class="invoice-container">
        <div class="header">
            <div class="company-info">
                <div class="company-name">{{ $factura->razon_social_emisor }}</div>
                <div>Casa Matriz: Pilot House</div>
                <div>{{ $factura->direccion_emisor }}</div>
                <div>Teléfono: 2-123456</div>
                <div>La Paz - Bolivia</div>
            </div>
            <div class="invoice-details">
                <div><strong>NIT:</strong> {{ $factura->nit_emisor }}</div>
                <div><strong>No. GACTURA:</strong> {{ $factura->numero_factura }}</div>
                <div><strong>COD. AUTORIZACIÓN:</strong> <span class="cuf-box">{{ $factura->cuf }}</span></div>
            </div>
        </div>

        <div class="invoice-title">
            FACTURA<br>
            <span style="font-size: 12px; font-weight: normal;">(Con Derecho a Crédito Fiscal)</span>
        </div>

        <div class="customer-info">
            <div class="info-row">
                <div class="info-label">Lugar y Fecha:</div>
                <div>La Paz, {{ $factura->fecha_emision->format('d/m/Y H:i') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Señor(es):</div>
                <div>{{ $factura->razon_social_cliente }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">NIT/CI/CEX:</div>
                <div>{{ $factura->nit_cliente }}</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 10%">CANTIDAD</th>
                    <th style="width: 50%">DESCRIPCIÓN</th>
                    <th style="width: 20%">PRECIO UNITARIO</th>
                    <th style="width: 20%">SUBTOTAL</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center;">1</td>
                    <td>{{ $aporte->DescripcionDelPago ?? 'Pago por Curso/Servicio' }} -
                        {{ $aporte->curso->nombreCurso ?? 'Varios' }}</td>
                    <td class="text-right">{{ number_format($factura->monto_total, 2) }}</td>
                    <td class="text-right">{{ number_format($factura->monto_total, 2) }}</td>
                </tr>
                {{-- Padding empty rows if needed --}}
            </tbody>
        </table>

        <div class="totals">
            <div class="info-row" style="justify-content: space-between;">
                <strong>TOTAL:</strong>
                <span>{{ number_format($factura->monto_total, 2) }}</span>
            </div>
            <div class="info-row" style="justify-content: space-between;">
                <strong>DESCUENTO:</strong>
                <span>{{ number_format($factura->descuento, 2) }}</span>
            </div>
            <div class="info-row" style="justify-content: space-between; border-top: 2px solid #000; padding-top: 5px;">
                <strong>IMPORTE BASE CRÉDITO FISCAL:</strong>
                <span>{{ number_format($factura->monto_final, 2) }} Bs</span>
            </div>
        </div>

        <div class="literal">
            SON: {{ \NumberFormatter::create('es', \NumberFormatter::SPELLOUT)->format($factura->monto_final) }} 00/100
            BOLIVIANOS
        </div>

        <div class="footer">
            <div class="qr-code">
                <img src="data:image/svg+xml;base64,{{ $qrCodeBase64 }}" alt="QR SIAT"
                    style="width: 100%; height: 100%;">
            </div>
            <div class="leyendas">
                <p><strong>ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS, EL USO ILÍCITO SERÁ SANCIONADO PENALMENTE DE
                        ACUERDO A LEY.</strong></p>
                <p>{{ $factura->leyenda }}</p>
                <p>"Este documento es la Representación Gráfica de un Documento Fiscal Digital emitido en una modalidad
                    de facturación en línea"</p>
            </div>
        </div>
    </div>

</body>

</html>
