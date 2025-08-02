<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Pago</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .recibo {
            border: 1px solid #000;
            padding: 20px;
            width: 600px;
            margin: 0 auto;
        }
        .header, .footer {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .details {
            margin-bottom: 20px;
        }
        .firma {
            text-align: center;
            margin-top: 30px;
        }
        img {
            max-width: 100px;
        }
        .download-btn {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div id="recibo" class="recibo">
        <div class="logo">
            <img src="{{asset('assets/img/logo.png')}}" alt="Logotipo de la Fundación">
        </div>

        <div class="header">
            <h2>RECIBO DE PAGO</h2>
            <p><strong>FUNDVID</strong></p>
            <p><strong>Nro 01</strong></p>
        </div>

        <div class="details">
            <p><strong>Fecha:</strong> 01/06/2024</p>
            <p><strong>Oficina:</strong> Fundación Educar Para la Vida - Cochabamba</p>
            <p><strong>Área a cargo:</strong> Coordinación Apoyo Psicopedagógico</p>
            <p><strong>Concepto del Servicio:</strong> Pago por apoyo pedagógico correspondiente al mes de abril del 2024 del niño ……………….</p>
            <p><strong>Por un monto de:</strong> Bs. 330 (trescientos y treinta)</p>
            <p><strong>Retenciones Impositivas:</strong> Bs. 0,00 (Cero 00/100 Bolivianos)</p>
            <p><strong>Líquido Pagable:</strong> Bs. 330</p>
        </div>

        <div class="details">
            <p><strong>Método de pago</strong></p>
            <p>Bancos (Cheque): NO</p>
            <p>Transferencia: SI</p>
            <p>QR: NO</p>
            <p>Efectivo: NO</p>
            <p>Paypal: NO</p>
        </div>

        <div class="firma">
            <img src="{{asset('assets/img/firma digital.png')}}" alt="Sello de la Fundación">
            <p>____________________________</p>
            <p><strong>FIRMA O SELLO</strong></p>
        </div>

        <div class="footer">
            <p>Recibo elaborado por: Fundación Educar para la vida.</p>
            <p><strong>Área: Administrativo – Financiera</strong></p>
        </div>
    </div>

    <div class="download-btn">
        <button onclick="downloadPDF()">Descargar como PDF</button>
    </div>

    <!-- Importa la biblioteca html2pdf.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script>
        function downloadPDF() {
            const element = document.getElementById('recibo');
            html2pdf(element, {
                margin: 10,
                filename: 'recibo_de_pago.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            });
        }
    </script>
</body>
</html>
