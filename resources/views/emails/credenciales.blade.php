<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificación</title>
    <style>
        /* Estilos para el encabezado */
        .navbar-main {
            background: rgb(26, 71, 137);
            background: linear-gradient(145deg, rgba(26, 71, 137, 1) 40%, rgba(34, 77, 141, 1) 53%, rgba(255, 255, 255, 1) 53%);
            height: 140px;
            width: 100%;
            border: none;
            border-radius: 0;
            position: relative;
            overflow: hidden;
        }

        .navbar-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
            width: 100%;
        }

        .navbar-brand {
            height: 100%;
            width: auto;
            display: flex;
            align-items: center;
        }

        /* Estilos para el cuerpo del correo */
        .email-content {
            padding: 20px;
            font-family: Arial, sans-serif;
            color: #333;
        }

        .email-content h1 {
            color: #1a4789;
        }

        .email-content p {
            line-height: 1.6;
        }
    </style>
</head>

<body>
    <h2>¡Bienvenido/a, {{ $user->name }}!</h2>

    <p>Aquí tienes tus credenciales de acceso al sistema:</p>

    <div class="card">
        <div class="credentials">
            <p><strong>Correo electrónico:</strong> {{ $user->email }}</p>
            <p><strong>Contraseña temporal:</strong> {{ $passwordPlain }}</p>
        </div>
    </div>

    <p>Por seguridad, te recomendamos cambiar tu contraseña después de iniciar sesión por primera vez.</p>

    <p>Saludos cordiales,<br>
    El equipo de soporte</p>
</body>

</html>
