<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesando tu pago</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #333;
        }

        .payment-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
            text-align: center;
            max-width: 500px;
            width: 90%;
        }

        .loading-spinner {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .info-text {
            font-size: 14px;
            color: #7f8c8d;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <h1>Tu pago se está procesando</h1>
        <div class="loading-spinner"></div>
        <p>Por favor, no cierres esta página ni actualices el navegador mientras completamos tu transacción.</p>
        <p class="info-text">Este proceso puede tardar unos segundos. Recibirás una confirmación cuando se complete.</p>
        <a href="{{ route('Inicio') }}" class="btn btn-primary">Ir al inicio</a>
    </div>
</body>
</html>
